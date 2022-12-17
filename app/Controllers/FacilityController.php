<?php

namespace App\Controllers;

use App\Controllers\Repositories\DepartmentRepository;
use App\Libraries\DataTables\FeedbackDataTable;
use  App\Libraries\ServerSideDataTable;
use  App\Libraries\EmailLibrary;
use App\Models\ShutlStages;
use App\Models\Shuttle;
use App\Models\ShuttleRoute;
use App\Models\LaundryAmenitiesOrder;
use App\Models\LaundryAmenitiesOrderDetail;
use App\Models\Product;
use CodeIgniter\API\ResponseTrait;
use DateTime;
use DateTimeZone;

class FacilityController extends BaseController
{
    use ResponseTrait;

    public $Db;
    public $session;
    public $request;
    public $todayDate;
    private $Shuttle;
    private $ShuttleRoute;
    private $ShutlStages;
    private $DepartmentRepository;

    public function __construct()
    {
        $this->Db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        helper(['form', 'responsejson', 'upload', 'custom', 'common']);
        $this->request = \Config\Services::request();
        $this->todayDate = new DateTime("now", new DateTimeZone('Asia/Dubai'));

        $this->Shuttle = new Shuttle();
        $this->ShutlStages = new ShutlStages();
        $this->ShuttleRoute = new ShuttleRoute();

        $this->Product = new Product();
        $this->LaundryAmenitiesOrder = new LaundryAmenitiesOrder();
        $this->LaundryAmenitiesOrderDetail = new LaundryAmenitiesOrderDetail();
        $this->DepartmentRepository = new DepartmentRepository();
    }
    // CODE BY ALEESHA  - Maintenance Request
    public function maintenanceRequest()
    {
        $data['title'] = 'Maintenance Requests';
        $data['departments'] = $this->DepartmentRepository->allDepartments();

        return view('Maintenance/MaintenanceRequestView', $data);
    }

    public function getRequestList()
    {
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_MAINTENANCE_VIEW left join FLXY_CUSTOMER on FLXY_MAINTENANCE_VIEW.CUST_NAME = FLXY_CUSTOMER.CUST_ID left join FlXY_USERS on MAINT_ATTENDANT_ID = USR_ID';
        $columns = 'MAINT_ID|MAINT_ROOM_NO|MAINT_TYPE|MAINT_CATEGORY|MAINT_SUBCATEGORY|MAINT_PREFERRED_DT|cast(MAINT_PREFERRED_TIME as time)MAINT_PREFERRED_TIME|MAINT_STATUS|MAINT_COMPLETED_AT|MAINT_ATTACHMENT|MAINT_CREATE_DT|CUST_FIRST_NAME|CUST_LAST_NAME|CONCAT_WS(\' \', USR_FIRST_NAME, USR_LAST_NAME)MAINT_ATTENDANT_NAME';
        $mine->generate_DatatTable($tableName, $columns, [], '|');
        exit;
    }
    
    function getCustomerFromRoomNo()
    {

        $room = $this->request->getPost("room");
        $sql = "SELECT concat(b.CUST_FIRST_NAME,' ',b.CUST_MIDDLE_NAME,' ',b.CUST_LAST_NAME) NAME, b.CUST_ID, a.RESV_ID FROM FLXY_RESERVATION a
        LEFT JOIN FLXY_CUSTOMER b ON b.CUST_ID = a.RESV_NAME WHERE a.RESV_ROOM =:RESV_ROOM: AND a.RESV_STATUS = 'Checked-In'";
        
        $param = ['RESV_ROOM' => $room];
        $response = $this->Db->query($sql, $param)->getResultArray();
        
        $option = '<option value="">Select Customer</option>';
        foreach ($response as $row) {
            $option .= "<option value='{$row['CUST_ID']}' data-RESV_ID='{$row['RESV_ID']}'>
                            {$row['RESV_ID']}-{$row['NAME']}-{$row['CUST_ID']}
                        </option>";
        }
        echo $option;
    }

    public function insertMaintenanceRequest()
    {   
        $user_id = session()->get('USR_ID');

        $attached_path = '';
        $rules = [
            'MAINT_ROOM_NO' => [
                'label' => 'Room Number', 
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please select a room.'
                ]
            ],
            'CUST_NAME' => [
                'label' => 'Guest', 
                'rules' => 'required', 
                'errors' => [
                'required' => 'Please select a guest.'
                ]
            ],
            'MAINT_TYPE' => ['label' => 'Maintenance Type', 'rules' => 'required'],
            'MAINT_CATEGORY' => ['label' => 'Category', 'rules' => 'required'],
            'MAINT_PREFERRED_TIME' => ['label' => 'Preferred Time', 'rules' => 'required'],
            'MAINT_PREFERRED_DT' => ['label' => 'Preferred Date', 'rules' => 'required'],
            // 'MAINT_ATTACHMENT' => [
                // 'uploaded[MAINT_ATTACHMENT]',
                // 'mime_in[MAINT_ATTACHMENT,image/png,image/jpeg,image/jpg]',
                // 'max_size[MAINT_ATTACHMENT,5120]',
            // ],
            'MAINT_STATUS' => [
                'label' => 'Status',
                'rules' => 'required',
                'errors' => [
                    'required' => 'please select a status'
                ]
            ]
        ];

        if(!empty($this->request->getVar('MAINT_TYPE')) && $this->request->getVar('MAINT_TYPE') == 'maintenance')
            $rules['MAINT_SUB_CATEGORY'] = ['label' => 'Sub Category', 'rules' => 'required'];

        $validate = $this->validate($rules);

        if (!$validate) {
            $validate = $this->validator->getErrors();            
            return $this->respond(responseJson(403, true, $validate));
        }
        

        $doc_files = $this->request->getFileMultiple('MAINT_ATTACHMENT');
        if($doc_files){
            foreach ($doc_files as $key => $file) {
                $file_name = $file->getRandomName();
                $directory = "assets/Uploads/Maintenance/";

                $response = documentUpload($file, $file_name, $user_id, $directory);

                if ($response['SUCCESS'] != 200)
                    return $this->respond(responseJson(500, true, ['msg' => "file not uploaded"]));

                $file_name = $response['RESPONSE']['OUTPUT'];

                $comma = '';
                if (isset($doc_files[$key + 1]) && $doc_files[$key + 1]->isValid()) {
                    $comma = ',';
                }

                if ($file_name)
                    $attached_path .= $file_name . $comma;
            }
        }

        $sysid = $this->request->getPost("sysid");
        if (empty($sysid)) {
            $data =
                [
                    "CUST_NAME" => $this->request->getPost("CUST_NAME"),
                    "MAINT_ATTENDANT_ID" => $this->request->getPost("MAINT_ATTENDANT_ID"),
                    "MAINT_RESV_ID" => $this->request->getPost("MAINT_RESV_ID"),
                    "MAINT_TYPE" => $this->request->getPost("MAINT_TYPE"),
                    "MAINT_CATEGORY" => $this->request->getPost("MAINT_CATEGORY"),
                    "MAINT_SUB_CATEGORY" => $this->request->getPost("MAINT_SUB_CATEGORY"),
                    "MAINT_DETAILS" => $this->request->getPost("MAINT_DETAILS"),
                    "MAINT_PREFERRED_DT" => date("d-M-Y", strtotime($this->request->getPost("MAINT_PREFERRED_DT"))),
                    "MAINT_PREFERRED_TIME" => date("d-M-Y H:i:s", strtotime($this->request->getPost("MAINT_PREFERRED_TIME"))),
                    "MAINT_ATTACHMENT" => $attached_path,
                    "MAINT_STATUS" => $this->request->getPost("MAINT_STATUS"),
                    "MAINT_COMMENT" => $this->request->getPost("MAINT_COMMENT"),
                    "MAINT_ROOM_NO" => $this->request->getPost("MAINT_ROOM_NO"),
                    "MAINT_CREATE_DT" => date("Y-m-d H:i:s"),
                    "MAINT_UPDATE_DT" => date("Y-m-d H:i:s"),
                    "MAINT_CREATE_UID" => $user_id,
                    "MAINT_UPDATE_UID" => $user_id,
                ];
            $ins = $this->Db->table('FLXY_MAINTENANCE')->insert($data);
        } else {
            $prev_maint = $this->Db->table('FLXY_MAINTENANCE')->where('MAINT_ID', $sysid)->get()->getRowArray();
            if(!empty($prev_maint['MAINT_ATTACHMENT']))
                $attached_path = $prev_maint['MAINT_ATTACHMENT'] . (!empty($attached_path) ? ",$attached_path" : '');
            
            // UPDATE
            $data =
                [
                    "CUST_NAME" => $this->request->getPost("CUST_NAME"),
                    "MAINT_ATTENDANT_ID" => $this->request->getPost("MAINT_ATTENDANT_ID"),
                    "MAINT_RESV_ID" => $this->request->getPost("MAINT_RESV_ID"),
                    "MAINT_TYPE" => $this->request->getPost("MAINT_TYPE"),
                    "MAINT_CATEGORY" => $this->request->getPost("MAINT_CATEGORY"),
                    "MAINT_SUB_CATEGORY" => $this->request->getPost("MAINT_SUB_CATEGORY"),
                    "MAINT_DETAILS" => $this->request->getPost("MAINT_DETAILS"),
                    "MAINT_PREFERRED_DT" => date("d-M-Y", strtotime($this->request->getPost("MAINT_PREFERRED_DT"))),
                    "MAINT_PREFERRED_TIME" => date("d-M-Y H:i:s", strtotime($this->request->getPost("MAINT_PREFERRED_TIME"))),
                    "MAINT_ATTACHMENT" => $attached_path,
                    "MAINT_STATUS" => $this->request->getPost("MAINT_STATUS"),
                    "MAINT_COMMENT" => $this->request->getPost("MAINT_COMMENT"),
                    "MAINT_ROOM_NO" => $this->request->getPost("MAINT_ROOM_NO"),
                    "MAINT_UPDATE_DT" => date("Y-m-d H:i:s"),
                    "MAINT_UPDATE_UID" => $user_id,
                ];
            $ins = $this->Db->table('FLXY_MAINTENANCE')->where('MAINT_ID', $sysid)->update($data);
        }

        if ($ins)
            $result = responseJson(200, false, ['msg' => "Request created/updated successfully"]);
        else
            $result = responseJson(500, true, ['msg' => "Creation Failed"]);

        return $this->respond($result);
    }
    
    public function deleteRequest()
    {
        $sysid = $this->request->getPost("sysid");
        $doc_data = $this->Db->table('FLXY_MAINTENANCE')->select('MAINT_ATTACHMENT')->where(['MAINT_ID' => $sysid])->get()->getRowArray();

        $return = $this->Db->table('FLXY_MAINTENANCE')->delete(['MAINT_ID' => $sysid]);

        // unlink the document attached
        $folderPath = "assets/Uploads/Maintenance/" . $doc_data['MAINT_ATTACHMENT'];
        if (file_exists($folderPath)) {
            unlink($folderPath);
        }

        if ($return)
            $result = $this->responseJson(200, false, ['msg' => "Maintenance request deleted successfully"]);
        else
            $result = $this->responseJson(500, true, ['msg' => "Not able to delete"]);

        return $this->respond($result);
    }

    function editMaintenanceRequest()
    {
        $param = ['MAINT_ID' => $this->request->getPost("sysid")];
        $sql = "SELECT *, (SELECT RM_DESC FROM FLXY_ROOM WHERE RM_NO = MAINT_ROOM_NO) RM_DESC, USR_DEPARTMENT as MAINT_DEPARTMENT_ID
                    FROM FLXY_MAINTENANCE 
                    left join FlXY_USERS on MAINT_ATTENDANT_ID = USR_ID
                    WHERE MAINT_ID =:MAINT_ID:";
        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }
    public function maintenanceCategoryList()
    {
        $category_type = $this->request->getVar('category_type');

        $sql = "SELECT MAINT_CAT_ID, MAINT_CATEGORY, MAINT_CATEGORY_TYPE FROM FLXY_MAINTENANCE_CATEGORY";
        if(!empty($category_type))
            $sql = "SELECT MAINT_CAT_ID, MAINT_CATEGORY FROM FLXY_MAINTENANCE_CATEGORY where MAINT_CATEGORY_TYPE = :category_type:";

        $params = ['category_type' => $category_type];

        $response = $this->Db->query($sql, $params)->getResultArray();
        echo json_encode($response);
    }
    public function maintenanceSubCatByCategoryID($api = 0)
    {
        $param = ['MAINT_CAT_ID' => $this->request->getPost("category")];
        $sql = "SELECT a.MAINT_CAT_ID,b.MAINT_SUBCATEGORY ,b.MAINT_SUBCAT_ID FROM FLXY_MAINTENANCE_CATEGORY a
        LEFT JOIN FLXY_MAINTENANCE_SUBCATEGORY b ON b.MAINT_CAT_ID = a.MAINT_CAT_ID WHERE a.MAINT_CAT_ID =:MAINT_CAT_ID:";
        $response = $this->Db->query($sql, $param)->getResultArray();
        if ($api) {
            echo json_encode($response);
        } else {
            $option = '<option value="">Select SubCategory</option>';
            foreach ($response as $row) {
                $option .= '<option value="' . $row['MAINT_SUBCAT_ID'] . '">' . $row['MAINT_SUBCATEGORY'] . '</option>';
            }
            echo $option;
        }
    }
    // Maintenance request - Category
    public function maintenanceRequestCategory()
    {
        return view('Maintenance/MaintenanceRequestCategoryView');
    }
    public function categorylist()
    {
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_MAINTENANCE_CATEGORY';
        $columns = 'MAINT_CAT_ID|MAINT_CATEGORY_TYPE|MAINT_CATEGORY|MAINT_CAT_CREATE_UID|FORMAT(MAINT_CAT_CREATE_DT,\'dd-MMM-yyyy\')MAINT_CAT_CREATE_DT';
        $mine->generate_DatatTable($tableName, $columns, [], '|');
        exit;
    }
    function editCategory()
    {
        $param = ['MAINT_CAT_ID' => $this->request->getPost("sysid")];
        $sql = "SELECT MAINT_CATEGORY_TYPE, MAINT_CATEGORY FROM FLXY_MAINTENANCE_CATEGORY WHERE MAINT_CAT_ID =:MAINT_CAT_ID:";
        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function insertCategory()
    {
        $user_id = session()->get('USR_ID');

        $validate = $this->validate([
            'MAINT_CATEGORY' => [
                'label' => 'Category', 
                'rules' => 'required'
            ],
            'MAINT_CATEGORY_TYPE' => [
                'label' => 'Category Type', 
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please select a category type.'
                ]
            ]
        ]);

        if (!$validate) {
            $validate = $this->validator->getErrors();
            $result = responseJson(403, true, $validate);

            return $this->respond($result);
        }

        $sysid = $this->request->getPost("sysid");
        if (empty($sysid)) {
            $data =
                [
                    "MAINT_CATEGORY_TYPE" => $this->request->getPost("MAINT_CATEGORY_TYPE"),
                    "MAINT_CATEGORY" => $this->request->getPost("MAINT_CATEGORY"),
                    "MAINT_CAT_CREATE_DT" => date("d-M-Y"),
                    "MAINT_CAT_UPDATE_DT" => date("d-M-Y"),
                    "MAINT_CAT_CREATE_UID" => $user_id,
                    "MAINT_CAT_UPDATE_UID" => $user_id,
                ];
            $ins = $this->Db->table('FLXY_MAINTENANCE_CATEGORY')->insert($data);
        } else {
            // UPDATE
            $data =
                [
                    "MAINT_CATEGORY_TYPE" => $this->request->getPost("MAINT_CATEGORY_TYPE"),
                    "MAINT_CATEGORY" => $this->request->getPost("MAINT_CATEGORY"),
                    "MAINT_CAT_UPDATE_DT" => date("d-M-Y"),
                    "MAINT_CAT_UPDATE_UID" => $user_id,
                ];
            $ins = $this->Db->table('FLXY_MAINTENANCE_CATEGORY')->where('MAINT_CAT_ID', $sysid)->update($data);
        }

        if ($ins)
            $result = responseJson(200, false, ['msg' => "Category Added/updated"]);
        else
            $result = responseJson(500, true, ['msg' => "Category addition Failed"]);

        return $this->respond($result);
    }

    public function deleteCategory()
    {
        $sysid = $this->request->getPost("sysid");
        try {


            $return = $this->Db->table('FLXY_MAINTENANCE_CATEGORY')->delete(['MAINT_CAT_ID' => $sysid]);

            if ($return) {
                $result = $this->responseJson(200, false, "Deleted the Category", $return);
                echo json_encode($result);
            } else {
                $result = $this->responseJson(500, true, "Category not deleted", []);
                echo json_encode($result);
            }
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }
    public function maintenanceRequestSubCategory()
    {
        return view('Maintenance/MaintenanceRequestSubCategoryView');
    }
    public function subCategoryList()
    {
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_MAINT_SUBCATEGORY_VIEW';
        $columns = 'MAINT_CAT_ID|MAINT_SUBCAT_ID|MAINT_SUBCATEGORY|MAINT_CATEGORY|CUST_FULLNAME|FORMAT(MAINT_SUBCAT_CREATE_DT,\'dd-MMM-yyyy\')MAINT_SUBCAT_CREATE_DT';
        $mine->generate_DatatTable($tableName, $columns, [], '|');
        exit;
    }
    function editSubCategory()
    {
        $param = ['MAINT_SUBCAT_ID' => $this->request->getPost("sysid")];
        $sql = "SELECT * FROM FLXY_MAINTENANCE_SUBCATEGORY WHERE MAINT_SUBCAT_ID =:MAINT_SUBCAT_ID:";
        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }
    public function insertSubCategory()
    {
        try {
            $validate = $this->validate([
                'MAINT_SUBCATEGORY' => ['label' => 'Sub-category', 'rules' => 'required'],
                'MAINT_CATEGORY' => ['label' => 'Category', 'rules' => 'required'],

            ]);
            if (!$validate) {

                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $this->request->getPost("sysid");
            if (empty($sysid)) {
                $data =
                    [
                        "MAINT_CAT_ID" => $this->request->getPost("MAINT_CATEGORY"),
                        "MAINT_SUBCATEGORY" => $this->request->getPost("MAINT_SUBCATEGORY"),
                        "MAINT_SUBCAT_CREATE_DT" => date("d-M-Y"),
                        "MAINT_SUBCAT_CREATE_UID" => session()->get('USR_ID'),
                        "MAINT_SUBCAT_UPDATE_DT" => date("d-M-Y"),
                        "MAINT_SUBCAT_UPDATE_UID" => session()->get('USR_ID'),
                    ];
                $ins = $this->Db->table('FLXY_MAINTENANCE_SUBCATEGORY')->insert($data);
            } else {
                $data =
                    [
                        "MAINT_CAT_ID" => $this->request->getPost("MAINT_CATEGORY"),
                        "MAINT_SUBCATEGORY" => $this->request->getPost("MAINT_SUBCATEGORY"),
                        "MAINT_SUBCAT_CREATE_DT" => date("d-M-Y"),
                        "MAINT_SUBCAT_CREATE_UID" => session()->get('USR_ID'),
                        "MAINT_SUBCAT_UPDATE_DT" => date("d-M-Y"),
                        "MAINT_SUBCAT_UPDATE_UID" => session()->get('USR_ID'),
                    ];
                $ins = $this->Db->table('FLXY_MAINTENANCE_SUBCATEGORY')->where('MAINT_SUBCAT_ID', $sysid)->update($data);
            }
            if ($ins) {
                $result = responseJson(200, true, "SubCategory Added", []);
                echo json_encode($result);
                die;
            } else {
                $result = responseJson(500, true, "SubCategory Creation Failed", []);
                echo json_encode($result);
                die;
            }
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }
    public function deleteSubCategory()
    {
        $sysid = $this->request->getPost("sysid");
        try {
            $return = $this->Db->table('FLXY_MAINTENANCE_SUBCATEGORY')->delete(['MAINT_SUBCAT_ID' => $sysid]);
            if ($return) {
                $result = $this->responseJson(200, false, "Deleted the request", $return);
                echo json_encode($result);
            } else {
                $result = $this->responseJson(500, true, "Record not deleted", []);
                echo json_encode($result);
            }
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }
    // Feedback
    public function feedback()
    {
        return view('Feedback/FeedbackView');
    }

    public function feedbackList()
    {
        $mine = new FeedbackDataTable(); // loads and creates instance
        // $tableName = 'FLXY_FEEDBACK_VIEW';
        // $columns = 'CUST_FULLNAME|FB_RATINGS|FB_DESCRIPTION|FORMAT(FB_CREATE_DT,\'dd-MMM-yyyy\')FB_CREATE_DT';
        // $mine->generate_DatatTable($tableName, $columns, [], '|');

        $tableName = 'FLXY_FEEDBACK left join FLXY_CUSTOMER on FB_CUST_ID = CUST_ID';
        $columns = 'FB_ID,FB_CUST_ID,FB_RATINGS,FB_DESCRIPTION,FB_MODEL,FB_MODEL_ID,FB_CREATE_DT,CUST_FIRST_NAME,CUST_LAST_NAME';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    // HANDBOOK
    public function handbook()
    {
        return view('handbookView');
    }

    public function saveHandbook()
    {
        try {
            $validate = $this->validate([
                'HANDBOOK' =>  [
                    'uploaded[HANDBOOK]',
                    'mime_in[HANDBOOK,application/pdf]',
                    // 'max_size[HANDBOOK,5120]',
                ],
            ]);
            if (!$validate) {

                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = responseJson(402, true, "validation errors. please check required parameters", $validate);
                echo json_encode($result);
                exit;
            }
            $HANDBOOK = $this->request->getFile("HANDBOOK");
            // $doc_name = $HANDBOOK->getName();
            $folderPath = "assets/Uploads/handbook/";
            $doc_up = documentUpload($HANDBOOK, "handbook.pdf", "hotel", $folderPath,1);
            if ($doc_up['SUCCESS'] == 200) {

                $result = responseJson(200, false, "HandBook Uploaded successfully", []);
                echo json_encode($result);
            } else {
                $result = responseJson(500, true, "Something went wrong on uploading.please try again", []);
                echo json_encode($result);
            }
        } catch (Exception $e) {
            $result = responseJson(500, true, "Something went wrong on uploading.please try again", $e->errors());
            echo json_encode($result);
        }
    }
    public function checkthehandbook()
    {
        try {
            $folderPath = "assets/Uploads/handbook/hotel-handbook.pdf";
            if (file_exists($folderPath)) {
                $filesize = filesize($folderPath);
                $result = responseJson(200, false, "files in the directory", $filesize);
                echo json_encode($result);
            } else {
                $result = responseJson(201, false, "No files in the directory", []);
                echo json_encode($result);
            }
        } catch (Exception $e) {
            $result = responseJson(500, true, "errors", $e->errors());
            echo json_encode($result);
        }
    }
    // SHUTTLE
    public function shuttle()
    {
        $data['title'] = getMethodName();

        return view('Transfers/shuttleView', $data);
    }
    public function shuttlelist()
    {
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_SHUTTLE';
        $columns = 'SHUTL_ID|SHUTL_NAME|SHUTL_ROUTE|SHUTL_NEXT|SHUTL_DESCRIPTION|cast(SHUTL_START_AT as time)SHUTL_START_AT|cast(SHUTL_END_AT as time)SHUTL_END_AT|SHUTL_ROUTE_IMG';
        $mine->generate_DatatTable($tableName, $columns, [], '|');
        exit;
    }
    function getStages($id = NULL)
    {

        $WHERE = "";
        if ($id) {
            $WHERE = " where SHUTL_STAGE_ID =" . $id;
        }
        $sql = "SELECT * FROM FLXY_SHUTL_STAGES" . $WHERE;
        $response = $this->Db->query($sql)->getResultArray();
        return json_encode($response);
    }
    public function insertShuttle()
    {
        try {
            $sysid = $this->request->getPost("sysid");

            $attached_path = NULL;

            $rules = [
                'SHUTL_NAME' => ['label' => 'Shuttle Name', 'rules' => 'required'],
                'SHUTL_FROM' => ['label' => 'Shuttle From', 'rules' => 'required'],
                'SHUTL_TO' => ['label' => 'Shuttle To', 'rules' => 'required'],
                'SHUTL_START_AT' => ['label' => 'Start At', 'rules' => 'required'],
                'SHUTL_END_AT' => ['label' => 'End At', 'rules' => 'required'],
                'SHUTL_NEXT' => ['label' => 'Shuttle Nect', 'rules' => 'required'],
                'SHUTL_DESCRIPTION' => ['label' => 'Description', 'rules' => 'required'],
            ];

            if(empty($sysid) || $this->request->getFile('SHUTL_ROUTE_IMG')) {
                $rules['SHUTL_ROUTE_IMG'] = [
                    'label' => 'Image',
                    'rules' => [
                        'uploaded[SHUTL_ROUTE_IMG]',
                        'mime_in[SHUTL_ROUTE_IMG,image/png,image/jpeg,image/jpg]',
                        'max_size[SHUTL_ROUTE_IMG,5120]',
                    ]
                ];
            }

            $validate = $this->validate($rules);

            if (!$validate)
                return $this->respond(responseJson(403, true, $this->validator->getErrors()));

            $doc_file = $this->request->getFile('SHUTL_ROUTE_IMG');
            // GET SUTTLE NAME FROM IDS
            $stages_from = json_decode($this->getStages($this->request->getPost("SHUTL_FROM")));
            $stages_To = json_decode($this->getStages($this->request->getPost("SHUTL_TO")));
            $route = $stages_from[0]->SHUTL_STAGE_NAME . " - " . $stages_To[0]->SHUTL_STAGE_NAME;

            if (empty($sysid)) {

                // INSERT
                if ($doc_file) {
                    $doc_name = $doc_file->getName();
                    $folderPath = "assets/Uploads/Shuttle/";
                    $doc_up = documentUpload($doc_file, $doc_name, $this->session->USR_ID, $folderPath);
                    if ($doc_up['SUCCESS'] == 200) {
                        $attached_path = base_url($folderPath . $doc_up['RESPONSE']['OUTPUT']);
                    }
                }
                $data =
                    [
                        "SHUTL_NAME" => $this->request->getPost("SHUTL_NAME"),
                        "SHUTL_FROM" => $this->request->getPost("SHUTL_FROM"),
                        "SHUTL_TO" => $this->request->getPost("SHUTL_TO"),
                        "SHUTL_START_AT" => $this->request->getPost("SHUTL_START_AT"),
                        "SHUTL_END_AT" => $this->request->getPost("SHUTL_END_AT"),
                        "SHUTL_NEXT" => $this->request->getPost("SHUTL_NEXT"),
                        "SHUTL_ROUTE" => $route,
                        "SHUTL_ROUTE_IMG" => $attached_path,
                        "SHUTL_DESCRIPTION" => $this->request->getPost("SHUTL_DESCRIPTION"),
                        "SHUTL_CREATE_DT" => date("d-M-Y"),
                        "SHUTL_CREATE_UID" => session()->get('USR_ID'),
                        "SHUTL_UPDATE_DT" => date("d-M-Y"),
                        "SHUTL_UPDATE_UID" => session()->get('USR_ID'),
                    ];
                $ins = $this->Db->table('FLXY_SHUTTLE')->insert($data);
            } else {
                $data = [
                    "SHUTL_NAME" => $this->request->getPost("SHUTL_NAME"),
                    "SHUTL_FROM" => $this->request->getPost("SHUTL_FROM"),
                    "SHUTL_TO" => $this->request->getPost("SHUTL_TO"),
                    "SHUTL_START_AT" => $this->request->getPost("SHUTL_START_AT"),
                    "SHUTL_END_AT" => $this->request->getPost("SHUTL_END_AT"),
                    "SHUTL_NEXT" => $this->request->getPost("SHUTL_NEXT"),
                    "SHUTL_ROUTE" => $route,
                    "SHUTL_DESCRIPTION" => $this->request->getPost("SHUTL_DESCRIPTION"),
                    "SHUTL_CREATE_DT" => date("d-M-Y"),
                    "SHUTL_CREATE_UID" => session()->get('USR_ID'),
                    "SHUTL_UPDATE_DT" => date("d-M-Y"),
                    "SHUTL_UPDATE_UID" => session()->get('USR_ID'),
                ];

                // UPDATE
                if ($doc_file) {
                    // unlink the old file from the folder and update the column in db
                    $doc_data = $this->Db->table('FLXY_SHUTTLE')->select('SHUTL_ROUTE_IMG')->where('SHUTL_ID', $sysid)->get()->getRowArray();
                    $filename = $doc_data['SHUTL_ROUTE_IMG'];
                    if ($filename) {
                        $filename = explode('/', $filename);
                        $file = end($filename);
                        $folderPath = "assets/Uploads/Shuttle/" . $file;
                        if (file_exists($folderPath)) {
                            unlink($folderPath);                            
                        }
                    }

                    $doc_name = $doc_file->getName();
                    $folderPath = "assets/Uploads/Shuttle/";
                    // 
                    $doc_up = documentUpload($doc_file, $doc_name, $this->session->USR_ID, $folderPath);
                    if ($doc_up['SUCCESS'] == 200) {
                        $data['SHUTL_ROUTE_IMG'] = base_url($folderPath . $doc_up['RESPONSE']['OUTPUT']);
                    }
                }
                
                $ins = $this->Db->table('FLXY_SHUTTLE')->where('SHUTL_ID', $sysid)->update($data);
            }
            if ($ins) {
                $result = responseJson(200, true, "Shuttle request Added", []);
                echo json_encode($result);
                die;
            } else {
                $result = responseJson(500, true, "Creation Failed", []);
                echo json_encode($result);
                die;
            }
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }
    public function deleteShuttle()
    {
        $sysid = $this->request->getPost("sysid");
        try {
            $return = $this->Db->table('FLXY_SHUTTLE')->delete(['SHUTL_ID' => $sysid]);
            if ($return) {
                $result = $this->responseJson(200, false, "Deleted the shuttle", $return);
                echo json_encode($result);
            } else {
                $result = $this->responseJson(500, true, "shuttle not deleted", []);
                echo json_encode($result);
            }
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }
    function editShuttle()
    {
        $param = ['SHUTL_ID' => $this->request->getPost("sysid")];
        $sql = "SELECT * FROM FLXY_SHUTTLE WHERE SHUTL_ID =:SHUTL_ID:";
        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }
    public function stages()
    {
        $data['title'] = getMethodName();

        return view('Transfers/stagesView', $data);
    }
    public function insertStages()
    {
        try {
            $user = session('user');
            $user_id = $user['USR_ID'];

            $sysid = $this->request->getPost("sysid");

            $rules = [
                'SHUTL_STAGE_NAME' => ['label' => 'stage name', 'rules' => 'required'],
            ];

            if(empty($sysid) || !empty($this->request->getFile('SHUTL_STAGE_IMAGE')))
                $rules['SHUTL_STAGE_IMAGE'] = [
                    'label' => 'stage image',
                    'rules' => ['uploaded[SHUTL_STAGE_IMAGE]', 'mime_in[SHUTL_STAGE_IMAGE,image/png,image/jpg,image/jpeg]', 'max_size[SHUTL_STAGE_IMAGE,5120]']
                ];
                
            if (!$this->validate($rules))
                return $this->respond(responseJson(403, true, $this->validator->getErrors()));

            if (!empty($image = $this->request->getFile('SHUTL_STAGE_IMAGE'))) {
                $image_name = $image->getName();
                $directory = "assets/Uploads/Shuttle/stage_images/";
                $response = documentUpload($image, $image_name, $user_id, $directory);
    
                if ($response['SUCCESS'] != 200)
                    return $this->respond(responseJson(500, true, ['msg' => "stage image not uploaded"]));
    
                $image_path = $directory . $response['RESPONSE']['OUTPUT'];
            } 

            if (empty($sysid)) {
                // INSERT
                $data =
                    [
                        "SHUTL_STAGE_NAME" => $this->request->getPost("SHUTL_STAGE_NAME"),
                        "SHUTL_CREATE_DT" => date("d-M-Y"),
                        "SHUTL_CREATE_UID" => session()->get('USR_ID'),
                        "SHUTL_UPDATE_DT" => date("d-M-Y"),
                        "SHUTL_UPDATE_UID" => session()->get('USR_ID'),
                        "SHUTL_STAGE_IMAGE" => $image_path
                    ];
                $ins = $this->Db->table('FLXY_SHUTL_STAGES')->insert($data);
            } else {
                $data =
                    [
                        "SHUTL_STAGE_NAME" => $this->request->getPost("SHUTL_STAGE_NAME"),
                        "SHUTL_CREATE_DT" => date("d-M-Y"),
                        "SHUTL_CREATE_UID" => session()->get('USR_ID'),
                        "SHUTL_UPDATE_DT" => date("d-M-Y"),
                        "SHUTL_UPDATE_UID" => session()->get('USR_ID'),
                    ];

                    if(!empty($image_path))
                        $data['SHUTL_STAGE_IMAGE'] = $image_path;

                $ins = $this->Db->table('FLXY_SHUTL_STAGES')->where('SHUTL_STAGE_ID', $sysid)->update($data);
            }

            if ($ins)
                $result = responseJson(200, false, ['msg' => 'Shuttle Stage created/updated successfully.']);
            else
                $result = responseJson(500, true, ['msg' => 'Unable to create/update']);
            
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->respond($e->getMessage());
        }
    }
    public function deleteStages()
    {
        $sysid = $this->request->getPost("sysid");
        try {
            $check = $this->Shuttle->where('SHUTL_FROM', $sysid)->orWhere('SHUTL_TO', $sysid)->findAll();
            if(!empty($check))
                return $this->respond(responseJson(202, true, ['msg' => "It can't be removed. First remove it from all shuttles."]));

            $check = $this->ShuttleRoute->where('FSR_STAGE_ID', $sysid)->findAll();
            if(!empty($check))
                return $this->respond(responseJson(202, true, ['msg' => "It can't be removed. First remove it from all shuttle routes."]));

            $return = $this->Db->table('FLXY_SHUTL_STAGES')->delete(['SHUTL_STAGE_ID' => $sysid]);
            if ($return)
                $result = responseJson(200, false, ['msg' => "Shuttle Stage deleted successfully."]);
            else
                $result = responseJson(500, true, ['msg' => "Unable to delete shuttle stage"]);
            
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->respond($e->getMessage());
        }
    }
    function editStages()
    {
        $param = ['SHUTL_STAGE_ID' => $this->request->getPost("sysid")];
        $sql = "SELECT * FROM FLXY_SHUTL_STAGES WHERE SHUTL_STAGE_ID =:SHUTL_STAGE_ID:";
        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }
    public function getStagesList()
    {
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_SHUTL_STAGES';
        // CUST_FULLNAME|
        $columns = 'SHUTL_STAGE_ID|SHUTL_CREATE_UID|SHUTL_STAGE_NAME|SHUTL_STAGE_IMAGE|FORMAT(SHUTL_CREATE_DT,\'dd-MMM-yyyy\')SHUTL_CREATE_DT';
        $mine->generate_DatatTable($tableName, $columns, [], '|');
        exit;
    }

    public function getShuttleStops()
    {
        $shuttle_id = $this->request->getPost('shuttle_id');

        $all_stops = $this->ShuttleRoute
            ->select('FLXY_SHUTTLE_ROUTE.*, st.SHUTL_STAGE_NAME')
            ->join('FLXY_SHUTL_STAGES as st', 'FLXY_SHUTTLE_ROUTE.FSR_STAGE_ID = st.SHUTL_STAGE_ID')
            ->where('FSR_SHUTTLE_ID', $shuttle_id)
            ->orderBy('FSR_ORDER_NO', 'asc')
            ->findAll();

        return $this->respond(responseJson(200, false, "all stops list", $all_stops));
    }

    public function storeRouteStop()
    {
        $rules = [
            'FSR_STAGE_ID' => [
                'label' => 'Stage',
                'rules' => 'required',
                'errors' => [
                    'required' => "Please select a stop."
                ]
            ],
            'FSR_DURATION_MINS' => ['label' => 'Stop Duration', 'rules' => 'required|min_length[1]'],
        ];

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();

            $result = responseJson(403, $errors);

            return $this->respond($result);
        }

        $last_shuttle_route = $this->ShuttleRoute->select('max(FSR_ORDER_NO) as FSR_ORDER_NO_COUNT')
            ->where('FSR_SHUTTLE_ID', $this->request->getPost('shuttle_id'))
            ->first();

        $order_no = 1;
        if ($last_shuttle_route) {
            $order_no = $last_shuttle_route['FSR_ORDER_NO_COUNT'] + 1;
        }

        $shuttle_route = $this->ShuttleRoute->insert([
            'FSR_SHUTTLE_ID' => $this->request->getPost('FSR_SHUTTLE_ID'),
            'FSR_STAGE_ID' => $this->request->getPost('FSR_STAGE_ID'),
            'FSR_DURATION_MINS' => $this->request->getPost('FSR_DURATION_MINS'),
            'FSR_ORDER_NO' => $order_no,
        ]);

        if ($shuttle_route) {
            $result = responseJson(200, false, "New Stop added");
            return $this->respond($result);
        } else {
            $result = responseJson(500, true, "something went wrong!");
            return $this->respond($result);
        }
    }

    public function updateShuttleStopsOrder()
    {
        try {
            $shuttle_route_ids = $this->request->getPost('shuttle_route_ids');

            $order_no = 1;
            foreach ($shuttle_route_ids as $id) {
                $this->ShuttleRoute->update($id, ['FSR_ORDER_NO' => $order_no++]);
            }

            return $this->respond(responseJson(200, false, "Order updated successfully."));
        } catch (\Exception $e) {
            return $this->respond(responseJson(500, true, "Something Went Wrong!"));
        }
    }

    public function removeShuttleStop()
    {
        try {
            $shuttle_route_id = $this->request->getPost('shuttle_route_id');

            $this->ShuttleRoute->delete($shuttle_route_id);

            return $this->respond(responseJson(200, false, "Stop deleted succeddfully."));
        } catch (\Exception $e) {
            return $this->respond(responseJson(500, true, "Something Went Wrong!"));
        }
    }

    /**************      Amenities Functions      ***************/

    public function amenitiesRequests()
    {    
        $data = [
            'toggleButton_javascript' => toggleButton_javascript(),
            'clearFormFields_javascript' => clearFormFields_javascript(),
            'blockLoader_javascript' => blockLoader_javascript(),
        ];

        $data['title'] = getMethodName();
        $data['session'] = $this->session;
        $data['productOptions'] = $this->productList();
        $data['reservationOptions'] = $this->reservationList();
        $data['js_to_load'] = array("amenitiesRequestForm.js");
        $data['departments'] = $this->DepartmentRepository->allDepartments();
        
        return view('Amenities/AmenitiesRequestView', $data);
    }

    public function getAmenitiesRequestList()
    {
        $_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);

        //echo json_encode(print_r($_POST));
        //exit;

        $user_id = session()->get('USR_ID');
        $user_role_id = session()->get('USR_ROLE_ID');
        
        $init_cond = $user_role_id == 1 ? array() : array('LAO_CREATED_BY' => $user_id);

        $search_keys = [
            'S_CUST_FULL_NAME', 'S_LAO_CREATED_AT', 'S_RESV_NO', 'S_RM_NO', 'S_LAO_TOTAL_PAYABLE',
            'S_LAO_PAYMENT_STATUS', 'S_PRODUCTS'
        ];

        $init_cond = array();

        if ($search_keys != NULL) {
            foreach ($search_keys as $search_key) {
                if (null !== $this->request->getPost($search_key) && !empty($this->request->getPost($search_key))) {
                    $value = $this->request->getPost($search_key);

                    switch ($search_key) {
                        case 'S_CUST_FULL_NAME': 
                            $init_cond["CONCAT_WS(' ', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME) LIKE "] = "'%$value%'"; 
                            break;

                        case 'S_LAO_CREATED_AT':
                            $dates = explode(' to ', $value);
                            $dateCond = isset($dates[1]) ? "LAO_CREATED_AT BETWEEN '".$dates[0]."' AND '".$dates[1]."'" : "FORMAT(LAO_CREATED_AT, 'dd-MMM-yyyy') = '".$dates[0]."'";
                            $init_cond[$dateCond] = "";
                            break;

                        case 'S_LAO_TOTAL_PAYABLE':
                            $init_cond["" . ltrim($search_key, "S_") . " <= "] = "'$value'";
                            break;

                        case 'S_CUST_FULL_NAME':
                        case 'S_RESV_NO':
                            
                            $init_cond["" . ltrim($search_key, "S_") . " LIKE "] = "'%$value%'"; 
                            break;

                        case 'S_PRODUCTS':
                            $init_cond["LAO_ID IN"] = "(SELECT LAOD_ORDER_ID 
                                                        FROM FLXY_LAUNDRY_AMENITIES_ORDER_DETAILS 
                                                        WHERE LAOD_PRODUCT_ID IN (".implode(",", $value)."))";
                            break;
                        default:
                            $init_cond["" . ltrim($search_key, "S_") . " = "] = "'$value'";
                            break;
                    }
                }
            }
        }

        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_LAUNDRY_AMENITIES_ORDERS 
                      LEFT JOIN FLXY_CUSTOMER on FLXY_LAUNDRY_AMENITIES_ORDERS.LAO_CUSTOMER_ID = FLXY_CUSTOMER.CUST_ID
                      LEFT JOIN FLXY_ROOM on FLXY_LAUNDRY_AMENITIES_ORDERS.LAO_ROOM_ID = FLXY_ROOM.RM_ID
                      LEFT JOIN FLXY_RESERVATION on FLXY_LAUNDRY_AMENITIES_ORDERS.LAO_RESERVATION_ID = FLXY_RESERVATION.RESV_ID';

        $columns = 'LAO_ID|LAO_ROOM_ID|RM_NO|LAO_RESERVATION_ID|RESV_NO|LAO_CUSTOMER_ID|CONCAT_WS(\' \', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME)CUST_FULL_NAME|FORMAT(LAO_TOTAL_PAYABLE, \'N2\')LAO_TOTAL_PAYABLE|LAO_PAYMENT_METHOD|LAO_PAYMENT_STATUS|FORMAT(LAO_CREATED_AT,\'dd-MMM-yyyy, hh:mm:ss tt\')LAO_CREATED_AT|LAO_CREATED_BY';
        $mine->generate_DatatTable($tableName, $columns, $init_cond, '|');
        exit;
    } 
    
    public function productList()
    {
        $sql = "SELECT PR_ID, PR_NAME, PC_CATEGORY
                FROM FLXY_PRODUCTS PR
                LEFT JOIN FLXY_PRODUCT_CATEGORIES PC ON PC.PC_ID = PR.PR_CATEGORY_ID
                ORDER BY PC_CATEGORY, PR_NAME";

        $response = $this->Db->query($sql)->getResultArray();

        $option = '';
        $numResults = count($response);

        for ($i = 0; $i < $numResults; $i++) {
            
            if($i == 0)
                $option .= '<optgroup label="' . $response[$i]['PC_CATEGORY'] . '">'; 
            else if($response[$i-1]['PC_CATEGORY'] != $response[$i]['PC_CATEGORY'])
            {
                $option .= '</optgroup>
                            <optgroup label="' . $response[$i]['PC_CATEGORY'] . '">'; 
            }       
            $option .= '<option value="' . $response[$i]['PR_ID'] . '">' . $response[$i]['PR_NAME'] . '</option>';
        }
        $option .= '</optgroup>';

        return $option;
    }

    public function reservationList()
    {
        $sql = "SELECT RESV_ID, RESV_NO, RESV_STATUS, RESV_RM_TYPE, RESV_ROOM, RESV_ROOM_ID, 
                       (SELECT RM_ID FROM FLXY_ROOM WHERE RM_NO = RESV_ROOM AND RM_TYPE = RESV_RM_TYPE) RM_ID
                FROM FLXY_RESERVATION RESV
                WHERE RESV_STATUS IN ('Checked-In','Check-Out-Requested')
                AND RESV_ROOM != ''
                ORDER BY RESV_NO DESC";

        $response = $this->Db->query($sql)->getResultArray();

        $option = '<option value="">Choose an Option</option>';
        $numResults = count($response);

        for ($i = 0; $i < $numResults; $i++) {

            $room_id = !empty($response[$i]['RESV_ROOM_ID']) ? $response[$i]['RESV_ROOM_ID'] : $response[$i]['RM_ID'];
            
            $option .= '<option value="' . $response[$i]['RESV_ID'] . '"
                                data-room-type="' . $response[$i]['RESV_RM_TYPE'] . '"
                                data-room-no="' . $response[$i]['RESV_ROOM'] . '"
                                data-room-id="' . $room_id . '">' . $response[$i]['RESV_NO'] . ' - ' . $response[$i]['RESV_STATUS'] . '</option>';
        }

        return $option;
    }

    public function getReservationCustomers($resvId = 0)
    {
        $param = ['RESV_ID' => $resvId];

        $sql = "SELECT  CUST_ID, 
                        CONCAT_WS(' ', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME) AS FULLNAME, 
                        CONCAT_WS(' ', CUST_ADDRESS_1, CUST_ADDRESS_2, CUST_ADDRESS_3) AS CUST_ADDRESS,
                        CUST_COUNTRY,(SELECT cname FROM COUNTRY WHERE ISO2=CUST_COUNTRY) CUST_COUNTRY_DESC,
                        CUST_STATE,(SELECT sname FROM STATE WHERE STATE_CODE=CUST_STATE AND COUNTRY_CODE=CUST_COUNTRY) CUST_STATE_DESC,
                        CUST_CITY,(SELECT ctname FROM CITY WHERE ID=CUST_CITY) CUST_CITY_DESC,
                        CUST_EMAIL,CUST_MOBILE,CUST_PHONE,CUST_POSTAL_CODE

                FROM FLXY_CUSTOMER
                WHERE CUST_ID IN (  SELECT RESV_NAME AS CUST_ID 
                                    FROM FLXY_RESERVATION WHERE RESV_ID = :RESV_ID:
                                        UNION 
                                    SELECT ACCOMP_CUST_ID AS CUST_ID 
                                    FROM FLXY_ACCOMPANY_PROFILE WHERE ACCOMP_REF_RESV_ID = :RESV_ID:)";
        
        $response = $this->Db->query($sql, $param)->getResultArray();

        $options = array();

        foreach ($response as $row) {
            $options[] = array( "id" => $row['CUST_ID'], "text" => $row['FULLNAME'], 
                                "address" => $row['CUST_ADDRESS'], "city" => $row['CUST_CITY_DESC'], "state" => $row['CUST_STATE_DESC'], 
                                "country" => $row['CUST_COUNTRY_DESC'], "email" => $row['CUST_EMAIL'], "phone" => !empty($row['CUST_MOBILE']) ? $row['CUST_MOBILE'] : $row['CUST_PHONE'], 
                                "postcode" => $row['CUST_POSTAL_CODE']);
        }

        return $options;
    }

    public function showReservationCustomers()
    {
        $customersList = $this->getReservationCustomers($this->request->getPost('sysid'));
        echo json_encode($customersList);
    }

    public function searchRequestProducts()
    {
        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';
        $selectedProductDetails = null !== $this->request->getPost('selectedProductDetails') && $this->request->getPost('selectedProductDetails') != '' ? json_decode($this->request->getPost('selectedProductDetails'), true) : [];

        $sql = "SELECT  PR_ID, PR_NAME, PC_CATEGORY, PR_IMAGE, PR_QUANTITY, PR_PRICE, 
                        PR_ESCALATED_HOURS, PR_ESCALATED_MINS
                FROM FLXY_PRODUCTS PR
                LEFT JOIN FLXY_PRODUCT_CATEGORIES PC ON PC.PC_ID = PR.PR_CATEGORY_ID
                WHERE 1 = 1";

        if ($search != '') {
            $sql .= " AND PR_NAME LIKE '%$search%'";
        }

        if($selectedProductDetails != NULL)
        {
            $sql .= " AND PR_ID NOT IN (";
            foreach($selectedProductDetails as $selectedProductDetail)
            {
                $sql .= $selectedProductDetail['prodId'].",";
            }
            $sql = rtrim($sql, ",").")";            
        }

        $sql .= " ORDER BY PR_NAME";

        $response = $this->Db->query($sql)->getResultArray();

        //$option = $sql.'<br/>';
        $option = '';
        $numResults = count($response);

        for ($i = 0; $i < $numResults; $i++) {

            $option .= '<div class="col-md-12 mb-2">

                            <div class="form-check custom-option custom-option-basic checkSelectProductDiv">
                                <label class="form-check-label custom-option-content" for="checkSelectProduct' . $response[$i]['PR_ID'] . '">';

            if($response[$i]['PR_QUANTITY'] > 0)
            $option .= '            <input class="form-check-input checkSelectProduct" type="checkbox" 
                                    data-product-name="' . $response[$i]['PR_NAME'] . '" 
                                    data-product-price="' . $response[$i]['PR_PRICE'] . '" 
                                    data-product-image="' . $response[$i]['PR_IMAGE'] . '" 
                                    data-product-esc-hours="' . $response[$i]['PR_ESCALATED_HOURS'] . '" 
                                    data-product-esc-mins="' . $response[$i]['PR_ESCALATED_MINS'] . '" 
                                    value="' . $response[$i]['PR_ID'] . '" 
                                    id="checkSelectProduct' . $response[$i]['PR_ID'] . '">';
            
            $option .= '            <div class="d-flex align-items-center cursor-pointer">';

            if(file_exists($response[$i]['PR_IMAGE']))
                $option .= '            <img src="' . base_url($response[$i]['PR_IMAGE']) . '" alt="' . $response[$i]['PR_NAME'] . '"
                                            class="w-px-50 h-px-50 me-3">';
            else {
                        //file not exists
                        $stateNum = rand(0,6);
                        $states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
                        $state = $states[$stateNum];
                        $name = $response[$i]['PR_NAME'];
                        $name_string = explode(" ", trim($name));
                        $initials = count($name_string) >= 2 ? strtoupper(substr($name_string[0], 0, 1).substr($name_string[1], 0, 1)) : strtoupper(substr($name_string[0], 0, 2));

                        $option .=
                            '<div class="avatar avatar-lg me-3">
                                <span class="avatar-initial bg-label-warning w-px-50 h-px-50">' . $initials . '</span>
                             </div>';
            }

            $option .= '                <div class="w-100">
                                            <div class="d-flex justify-content-between">
                                                <div class="user-info w-50">
                                                    <h6 class="mb-1">' . $response[$i]['PR_NAME'] . '</h6>
                                                    <small>' . $response[$i]['PC_CATEGORY'] . '</small>
                                                    <div class="mt-1">';

            if($response[$i]['PR_QUANTITY'] > 0)
            $option .= '                                <span class="badge bg-label-success">
                                                            <span class="badge bg-success me-1">' . $response[$i]['PR_QUANTITY'] . '</span>
                                                            &nbsp;In Stock
                                                        </span>';
            else
            $option .= '                                <span class="badge bg-label-danger">
                                                            &nbsp;Out of Stock
                                                        </span>';                                                        

            $option .= '                            </div>
                                                </div>';

            if($response[$i]['PR_QUANTITY'] > 0)
            $option .= '                        <div class="w-25"><input type="number" class="form-control form-control-sm numSelectProduct" value="1"
                                                        min="1" max="' . $response[$i]['PR_QUANTITY'] . '" style="width: 70px;">
                                                </div>';
            $option .= '                        <div class="w-25 text-end">
                                                    <span class="text-primary" style="font-weight: bold;">' . number_format($response[$i]['PR_PRICE'], 2) . '</span>
                                                </div>';

            $option .= '                    </div>
                                        </div>
                                    </div>
                                </label>
                            </div>

                        </div>';
        }

        echo empty($option) ? '<h6 class="mb-1" align="center">There are no products that match your search</h6>' : $option;
    }

    public function showRequestProducts()
    {
        $selectedProductDetails = null !== $this->request->getPost('selectedProductDetails') && $this->request->getPost('selectedProductDetails') != '' ? json_decode($this->request->getPost('selectedProductDetails'), true) : [];
        $displayType = null !== $this->request->getPost('displayType') ? $this->request->getPost('displayType') : 'cart';
        //echo "<pre>"; print_r(json_decode($selectedProductDetails, true)); echo "</pre>";

        $output = '';

        if($selectedProductDetails != NULL)
        {
            foreach($selectedProductDetails as $selectedProductDetail)
            {
                $output .= '<li class="list-group-item p-4 selectedProduct">
                                <div class="gap-3 d-flex">
                                    <div class="flex-shrink-0">';

                if(file_exists($selectedProductDetail['prodImg']))
                    $output .= '        <img src="' . base_url($selectedProductDetail['prodImg']) . '" alt="' . $selectedProductDetail['prodName'] . '"
                                                class="w-px-100 h-px-100">';
                else {
                            //file not exists
                            $stateNum = rand(0,6);
                            $states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
                            $state = $states[$stateNum];
                            $name = $selectedProductDetail['prodName'];
                            $name_string = explode(" ", trim($name));
                            $initials = count($name_string) >= 2 ? strtoupper(substr($name_string[0], 0, 1).substr($name_string[1], 0, 1)) : strtoupper(substr($name_string[0], 0, 2));

                            $output .=  '<div class="avatar avatar-lg w-px-100 h-px-100">
                                            <span class="avatar-initial bg-label-warning">' . $initials . '</span>
                                        </div>';
                }
                $output .= '        </div>
                                    <div class="flex-grow-1">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h6 class="fw-normal me-3 mb-2">
                                                    <a href="javascript:void(0)"
                                                        class="text-body"><b>' . $selectedProductDetail['prodName'] . '</b></a>
                                                </h6>
                                                <div class="d-flex flex-wrap text-muted mb-1">';

                if($selectedProductDetail['maxVal'] > 0)
                {
                $output .= '                            <span class="badge bg-label-success">';
                                                        if($displayType == 'cart')
                $output .= '                                <span class="badge bg-success me-1">' . $selectedProductDetail['maxVal'] . '</span>&nbsp;';
                $output .= '                                In Stock
                                                        </span>';
                }
                else
                $output .= '                            <span class="badge bg-label-danger">
                                                            &nbsp;Out of Stock
                                                        </span>';       
                $output .= '                    </div>
                                                <div class="row mb-3">';
                                                if($displayType == 'cart')
                $output .= '                                
                                                    <div class="col-md-3">
                                                        <input type="number"
                                                            class="form-control form-control-sm w-px-75 selProdNum" 
                                                            data-product-id="'.$selectedProductDetail['prodId'].'"
                                                            data-product-name="' . $selectedProductDetail['prodName'] . '"
                                                            data-product-price="' . $selectedProductDetail['prodPrice'] . '"
                                                            value="' . $selectedProductDetail['prodNum'] . '" min="1" max="' . $selectedProductDetail['maxVal'] . '" />
                                                    </div>';

                $output .= '                        <label class="col-md-8 pt-1"><b>';
                                                        if($displayType != 'cart')
                                                            $output .= $selectedProductDetail['prodNum'];
                $output .= '                        x ' . number_format($selectedProductDetail['prodPrice'], 2) . '</b></label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-md-end">';
                                                if($displayType == 'cart')
                                                $output .= '                            
                                                    <button type="button"
                                                        class="btn-close btn-pinned removeSelectedProduct" 
                                                        data-product-id="' . $selectedProductDetail['prodId'] . '"
                                                        data-product-name="' . $selectedProductDetail['prodName'] . '"
                                                        aria-label="Close"></button>';
                $output .= ' 
                                                    <div class="my-2 my-md-4">
                                                        <span class="text-primary selProdTotal" style="font-weight: bold;">' . number_format(($selectedProductDetail['prodNum'] * $selectedProductDetail['prodPrice']), 2) . '</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>';
            }
        }

        echo empty($output) ? '<h6 class="mb-1" align="center">Please select some products</h6>' : $output;
    }

    public function AmenityOrderDetailsView()
    {
        $sysid = $this->request->getPost('sysid');

        $init_cond = array( "LAOD_ORDER_ID = " => "'$sysid'"); // Add condition for Amenity Order

        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_LAUNDRY_AMENITIES_ORDER_DETAILS 
                      LEFT JOIN FLXY_PRODUCTS PR ON PR.PR_ID = FLXY_LAUNDRY_AMENITIES_ORDER_DETAILS.LAOD_PRODUCT_ID
                      LEFT JOIN FLXY_PRODUCT_CATEGORIES PC ON PC.PC_ID = PR.PR_CATEGORY_ID
                      LEFT JOIN FlXY_USERS on LAOD_ATTENDANT_ID = USR_ID';
        $columns = 'LAOD_ID|LAOD_ORDER_ID|LAOD_PRODUCT_ID|PR_NAME|PR_IMAGE|PC_CATEGORY|CONCAT_WS(\' \', USR_FIRST_NAME, USR_LAST_NAME)ATTENDANT_NAME|LAOD_ATTENDANT_ID|USR_DEPARTMENT|LAOD_QUANTITY|LAOD_AMOUNT|FORMAT(LAOD_AMOUNT/LAOD_QUANTITY, \'N2\')UNIT_PRICE|LAOD_DELIVERY_STATUS|LAOD_EXPIRY_DATETIME|PR_PRICE|PR_QUANTITY|PR_ESCALATED_HOURS|PR_ESCALATED_MINS';
        $mine->generate_DatatTable($tableName, $columns, $init_cond, '|');
        exit;
    }

    public function updateAmenityOrder()
    {
        try {
            $sysid = $this->request->getPost('sysid');
            $new_status = $this->request->getPost('new_status');

            $return = $this->LaundryAmenitiesOrder->update($sysid, ['LAO_PAYMENT_STATUS' => $new_status]);

            echo json_encode($this->responseJson("1", "0", $return, $response = ''));
        } catch (\Exception $e) {
            echo json_encode($this->responseJson("-444", "db insert not successful", $return));
        }
    }

    public function updateAmenityOrderPaymentMethod()
    {
        try {
             //echo json_encode(print_r($_POST));
             //exit;

            $sysid = $this->request->getPost('change_LAO_ID');
            $pay_method = $this->request->getPost('change_LAO_PAYMENT_METHOD');

            $return = $this->LaundryAmenitiesOrder->update($sysid, ['LAO_PAYMENT_METHOD' => $pay_method]);

            echo json_encode($this->responseJson("1", "0", $return, $response = ''));
        } catch (\Exception $e) {
            echo json_encode($this->responseJson("-444", "db insert not successful", $return));
        }
    }

    public function updateAmenityProdRequestUser()
    {
        try {
             //echo json_encode(print_r($_POST));
             //exit;

            $sysid = $this->request->getPost('change_LAOD_ID');
            $attendee_user = $this->request->getPost('LAOD_ATTENDANT_ID');

            $return = $this->LaundryAmenitiesOrderDetail->update($sysid, ['LAOD_ATTENDANT_ID' => $attendee_user]);

            echo json_encode($this->responseJson("1", "0", $return, $response = ''));
        } catch (\Exception $e) {
            echo json_encode($this->responseJson("-444", "db insert not successful", $return));
        }
    }

    public function updateAmenityOrderDetails()
    {
        try {
            $sysid = $this->request->getPost('sysid');
            $new_status = $this->request->getPost('new_status');

            $return = $this->LaundryAmenitiesOrderDetail->update($sysid, ['LAOD_DELIVERY_STATUS' => $new_status]);

            echo json_encode($this->responseJson("1", "0", $return, $response = ''));
        } catch (\Exception $e) {
            echo json_encode($this->responseJson("-444", "db insert not successful", $return));
        }
    }

    public function insertAmenityOrder()
    {
        $user_id = session()->get('USR_ID');
        
        try {
            //echo json_encode(print_r($_POST)); exit;

            $formFields = $this->request->getPost('formFields');
            $selectedProductDetails = $this->request->getPost('selectedProductDetails');

            $data = [];
            foreach($formFields as $formField){
                $data[$formField['name']] = $formField['value'];
            }

            if(!isset($data['LAO_PAYMENT_STATUS']))
                $data['LAO_PAYMENT_STATUS'] = 'UnPaid';
            
            $data['LAO_CREATED_AT'] = date('Y-m-d H:i:s');
            $data['LAO_UPDATED_AT'] = date('Y-m-d H:i:s');
            $data['LAO_CREATED_BY'] = $user_id;
            $data['LAO_UPDATED_BY'] = $user_id;

            $ins = $this->LaundryAmenitiesOrder->insert($data);
            if($ins){
                $ordId = $this->Db->insertID();
                
                foreach($selectedProductDetails as $selectedProductDetail){

                    $details = [];
                    $details['LAOD_ORDER_ID'] = $ordId;
                    $details['LAOD_PRODUCT_ID'] = $selectedProductDetail['prodId'];
                    $details['LAOD_QUANTITY'] = $selectedProductDetail['prodNum'];
                    $details['LAOD_AMOUNT'] = number_format((float)($selectedProductDetail['prodPrice'] * $selectedProductDetail['prodNum']), 2, '.', '');
                    $details['LAOD_DELIVERY_STATUS'] = 'New';
                    $details['LAOD_EXPIRY_DATETIME'] = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')) + ($selectedProductDetail['prodEscHrs'] * 60 * 60) + ($selectedProductDetail['prodEscMins'] * 60));
                    $details['LAOD_CREATED_AT'] = date('Y-m-d H:i:s');
                    $details['LAOD_UPDATED_AT'] = date('Y-m-d H:i:s');
                    $details['LAOD_CREATED_BY'] = $user_id;
                    $details['LAOD_UPDATED_BY'] = $user_id;

                    $this->LaundryAmenitiesOrderDetail->insert($details);

                    //Update Product Stock
                    $pr = $this->Product->find($selectedProductDetail['prodId']);
                    $pr['PR_QUANTITY'] = $pr['PR_QUANTITY'] - $selectedProductDetail['prodNum'];
                    $this->Product->save($pr);
                }
            }

            $result = $ins ? $this->responseJson("1", "0", $ins, "1") : $this->responseJson("-444", "db insert not successful", $ins);
            echo json_encode($result);

        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }



}