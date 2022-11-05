<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;
use App\Controllers\Repositories\UserRepository;
use App\Models\UserModel;
use App\Libraries\ServerSideDataTable;
use App\Libraries\EmailLibrary;
use DateTime;
use DateTimeZone;

class UserController extends BaseController
{
    use ResponseTrait;

    private $UserRepository;
    public $Db;
    public $session;
    public $request;
    public $todayDate;
    public $EmailLibrary;

    public function __construct()
    {
        $this->UserRepository = new UserRepository();
        $this->Db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        helper(['form', 'common', 'custom']);
        $this->request = \Config\Services::request();
        $this->todayDate = new DateTime("now", new DateTimeZone('Asia/Dubai'));

        $this->EmailLibrary = new EmailLibrary();
    }

    public function login()
    {
        $data = [];

        if ($this->request->getMethod() == 'post') {
            // echo "Test";exit;
            $rules = [
                'username' => 'required|max_length[50]',
                'password' => 'required|min_length[8]|max_length[255]|validateUser[username,password]',
            ];

            $errors = [
                'password' => [
                    'validateUser' => "Email/Username or Password don't match",
                ],
            ];

            if (!$this->validate($rules, $errors)) {

                return view('login/login', [
                    "validation" => $this->validator,
                ]);
            } else {
                $model = new UserModel();
                $user = $model->where('USR_EMAIL', $this->request->getVar('username'))->orWhere('USR_NAME', $this->request->getVar('username'))->first();

                // Stroing session values
                $this->setUserSession($user);

                // Redirecting to dashboard after login
                if ($user['USR_ROLE_ID'] != "3") {
                    return redirect()->to(base_url('/'));
                } elseif ($user['USR_ROLE_ID'] == "3") {
                    return redirect()->to(base_url('editor'));
                }
            }
        }
        return view('login/login');
    }

    private function setUserSession($user)
    {
        $data = [
            'user' => $user,
            'USR_ID' => $user['USR_ID'],
            'USR_NAME' => $user['USR_NAME'],
            'USR_FIRST_NAME' => $user['USR_FIRST_NAME'],
            'USR_LAST_NAME' => $user['USR_LAST_NAME'],
            'USR_PHONE' => $user['USR_PHONE'],
            'USR_EMAIL' => $user['USR_EMAIL'],
            'USR_IMAGE' => $user['USR_IMAGE'],
            'isLoggedIn' => true,
            "USR_ROLE" => $user['USR_ROLE'],
            'USR_ROLE_ID' => $user['USR_ROLE_ID']
        ];

        session()->set($data);
        return true;
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'));
    }




    /**************      Users Functions      ***************/

    public function Users()
    {
        $data['editId'] = null !== $this->request->getGet("editId") ? $this->request->getGet("editId") : null;
        //Check if edit ID exists in Customer table
        if ($data['editId'] && !checkValueinTable('USR_ID', $data['editId'], 'FlXY_USERS'))
            return redirect()->to(base_url('Users'));

        $data['title'] = getMethodName();
        $data['session'] = $this->session;
        $data['roleList'] = $this->roleList();
        $data['departmentList'] = $this->departmentList();
        //$data['js_to_load'] = array("app-user-list.js");
        return view('Users/UsersList', $data);
    }

    public function UsersList()
    {
        $mine      = new ServerSideDataTable();
        $tableName = 'FLXY_USERS LEFT JOIN FLXY_USER_ROLE ON USR_ROLE_ID = ROLE_ID LEFT JOIN FLXY_DEPARTMENT ON DEPT_ID = USR_DEPARTMENT';
        $columns = 'USR_ID,USR_NAME,ROLE_NAME,USR_ROLE_ID,USR_EMAIL,USR_FIRST_NAME,USR_LAST_NAME,USR_DOJ,ROLE_CODE,ROLE_DESC,USR_STATUS,DEPT_CODE,DEPT_DESC';

        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function getUserDetails($user_id)
    {
        $param = ['SYSID' => $user_id];

        $sql = "SELECT  USR_NAME,USR_NUMBER,USR_FIRST_NAME,USR_LAST_NAME,USR_EMAIL,USR_PASSWORD,USR_ROLE_ID,USR_DEPARTMENT,USR_IMAGE,
                        FORMAT(USR_DOB, 'dd-MMM-yyyy') as USR_DOB,USR_ADDRESS,USR_CITY,USR_STATE,USR_COUNTRY,
                        FORMAT(USR_DOJ, 'dd-MMM-yyyy') as USR_DOJ,USR_PHONE,USR_GENDER,USR_TEL_EXT,USR_STATUS,ROLE_NAME 
                FROM FLXY_USERS 
                LEFT JOIN FLXY_USER_ROLE ON USR_ROLE_ID = ROLE_ID 
                LEFT JOIN COUNTRY ON FLXY_USERS.USR_COUNTRY = COUNTRY.id 
                LEFT JOIN FLXY_DEPARTMENT ON USR_DEPARTMENT = DEPT_ID 
                LEFT JOIN STATE ON FLXY_USERS.USR_STATE = STATE.id 
                WHERE USR_ID=:SYSID:";

        $profile_data = $this->Db->query($sql, $param)->getRowArray();
        return $profile_data;
    }

    public function myProfile()
    {
        $data['title'] = getMethodName();
        $user_id = session()->get('USR_ID');

        $data['profile_data'] = $this->getUserDetails($user_id);

        return view('Users/Profile', $data);
    }

    public function editProfile()
    {
        $data['title'] = getMethodName();
        $user_id = session()->get('USR_ID');

        $data['roleList'] = $this->roleList();
        $data['departmentList'] = $this->departmentList();

        $data['editId'] = $user_id;
        $data['profile_data'] = $this->getUserDetails($user_id);
        return view('Users/editProfile', $data);
    }

    public function UserRole()
    {

        $data['title'] = getMethodName();
        $data['session'] = $this->session;
        return view('Users/UserRoleView', $data);
    }

    public function UserRoleView()
    {
        $mine      = new ServerSideDataTable();
        $tableName = 'FLXY_USER_ROLE';
        $columns = 'ROLE_ID,ROLE_CODE,ROLE_NAME,ROLE_DESC,ROLE_DIS_SEQ,ROLE_STATUS';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function insertUserRole()
    {
        try {
            $sysid = $this->request->getPost('ROLE_ID');

            $validate = $this->validate([
                'ROLE_CODE' => ['label' => 'Role Code', 'rules' => 'required|is_unique[FLXY_USER_ROLE.ROLE_CODE,ROLE_ID,' . $sysid . ']'],
                'ROLE_NAME' => ['label' => 'Role Name', 'rules' => 'required|is_unique[FLXY_USER_ROLE.ROLE_NAME,ROLE_ID,' . $sysid . ']'],
                'ROLE_DESC' => ['label' => 'Description', 'rules' => 'required']

            ]);
            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }

            $data = [
                "ROLE_CODE" => trim($this->request->getPost('ROLE_CODE')),
                "ROLE_NAME" => trim($this->request->getPost('ROLE_NAME')),
                "ROLE_DESC" => trim($this->request->getPost('ROLE_DESC')),
                "ROLE_DIS_SEQ" => trim($this->request->getPost('ROLE_DIS_SEQ')),
                "ROLE_STATUS" => trim($this->request->getPost('ROLE_STATUS'))
            ];

            $return = !empty($sysid) ? $this->Db->table('FLXY_USER_ROLE')->where('ROLE_ID', $sysid)->update($data) : $this->Db->table('FLXY_USER_ROLE')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function editUserRole()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];

        $sql = "SELECT ROLE_ID, ROLE_CODE, ROLE_NAME, ROLE_DESC, ROLE_STATUS, ROLE_DIS_SEQ
                FROM FLXY_USER_ROLE
                WHERE ROLE_ID=:SYSID: ";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }


    public function checkRole($roleCode)
    {
        $sql = "SELECT ROLE_ID
                FROM FLXY_USER_ROLE
                WHERE ROLE_CODE = '" . $roleCode . "'";

        $response = $this->Db->query($sql)->getNumRows();
        return $roleCode == '' || strlen($roleCode) > 10 ? 1 : $response; // Send found row even if submitted code is empty
    }

    public function copyUserRole()
    {
        try {
            $param = ['SYSID' => $this->request->getPost('main_Role_ID')];

            $sql = "SELECT ROLE_ID, ROLE_CODE, ROLE_NAME, ROLE_DESC, ROLE_DIS_SEQ, ROLE_STATUS
                    FROM FLXY_USER_ROLE
                    WHERE ROLE_ID=:SYSID:";

            $origGuestCode = $this->Db->query($sql, $param)->getResultArray()[0];
            $no_of_added = 0;
            $submitted_fields = $this->request->getPost('group-a');

            if ($submitted_fields != null) {
                foreach ($submitted_fields as $submitted_field) {
                    if (!$this->checkRole($submitted_field['ROLE_CODE'])) // Check if entered Guest Type already exists
                    {
                        $newRateCode = [
                            "ROLE_CODE" => trim($submitted_field["ROLE_CODE"]),
                            "ROLE_NAME" => $origGuestCode["ROLE_NAME"],
                            "ROLE_DESC" => $origGuestCode["ROLE_DESC"],
                            "ROLE_DIS_SEQ" => '',

                        ];

                        $this->Db->table('FLXY_USER_ROLE')->insert($newRateCode);

                        $no_of_added += $this->Db->affectedRows();
                    }
                }
            }

            echo $no_of_added;
            exit;
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }
    public function deleteUserRole()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            //Soft Delete
            //$data = ["CUR_DELETED" => "1"];
            //$return =  $this->Db->table('FLXY_CURRENCY')->where('CUR_ID', $sysid)->update($data);
            $return = $this->Db->table('FLXY_USER_ROLE')->delete(['ROLE_ID' => $sysid]);
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }


    public function UserJobTitle()
    {
        $data['title'] = getMethodName();
        $data['session'] = $this->session;
        return view('Users/UserJobTitleView', $data);
    }

    public function UserJobTitleView()
    {
        $mine      = new ServerSideDataTable();
        $tableName = 'FLXY_JOB_TITLE';
        $columns = 'JOB_TITLE_ID,JOB_TITLE_CODE,JOB_TITLE_DESC,JOB_TITLE_DIS_SEQ,JOB_TITLE_STATUS';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function insertUserJobTitle()
    {
        try {
            $sysid = $this->request->getPost('JOB_TITLE_ID');

            $validate = $this->validate([
                'JOB_TITLE_CODE' => ['label' => 'Job Title', 'rules' => 'required|is_unique[FLXY_JOB_TITLE.JOB_TITLE_CODE,JOB_TITLE_ID,' . $sysid . ']'],
                'JOB_TITLE_DESC' => ['label' => 'Description', 'rules' => 'required']

            ]);
            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }

            $data = [
                "JOB_TITLE_CODE" => trim($this->request->getPost('JOB_TITLE_CODE')),
                "JOB_TITLE_DESC" => trim($this->request->getPost('JOB_TITLE_DESC')),
                "JOB_TITLE_DIS_SEQ" => trim($this->request->getPost('JOB_TITLE_DIS_SEQ')),
                "JOB_TITLE_STATUS" => trim($this->request->getPost('JOB_TITLE_STATUS'))
            ];

            $return = !empty($sysid) ? $this->Db->table('FLXY_JOB_TITLE')->where('JOB_TITLE_ID', $sysid)->update($data) : $this->Db->table('FLXY_JOB_TITLE')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function editUserJobTitle()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];

        $sql = "SELECT JOB_TITLE_ID, JOB_TITLE_CODE, JOB_TITLE_DESC, JOB_TITLE_DIS_SEQ, JOB_TITLE_STATUS
                FROM FLXY_JOB_TITLE
                WHERE JOB_TITLE_ID=:SYSID: ";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteUserJobTitle()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            //Soft Delete
            //$data = ["CUR_DELETED" => "1"];
            //$return =  $this->Db->table('FLXY_CURRENCY')->where('CUR_ID', $sysid)->update($data);
            $return = $this->Db->table('FLXY_JOB_TITLE')->delete(['JOB_TITLE_ID' => $sysid]);
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }


    public function jobTitleList()
    {
        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

        $sql = "SELECT JOB_TITLE_ID, JOB_TITLE_CODE, JOB_TITLE_DESC
                FROM FLXY_JOB_TITLE";

        if ($search != '') {
            $sql .= " WHERE JOB_TITLE_DESC LIKE '%$search%'
                    ";
        }

        $response = $this->Db->query($sql)->getResultArray();

        $option = '<option value="">Choose an Option</option>';
        foreach ($response as $row) {
            $option .= '<option value="' . $row['JOB_TITLE_ID'] . '">' . $row['JOB_TITLE_CODE'] . ' | ' . $row['JOB_TITLE_DESC']  . '</option>';
        }

        return $option;
    }


    public function roleList()
    {
        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

        $sql = "SELECT ROLE_ID, ROLE_CODE, ROLE_NAME, ROLE_DESC, ROLE_STATUS, ROLE_DIS_SEQ
        ,ROLE_CREATED FROM FLXY_USER_ROLE";

        if ($search != '') {
            $sql .= " WHERE ROLE_DESC LIKE '%$search%'
                    ";
        }
        $response = $this->Db->query($sql)->getResultArray();
        $option = '<option value="">Choose an Option</option>';
        foreach ($response as $row) {
            $option .= '<option value="' . $row['ROLE_ID'] . '">' . $row['ROLE_NAME']  . '</option>';
        }

        return $option;
    }


    public function departmentList()
    {
        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

        $sql = "SELECT DEPT_ID, DEPT_CODE, DEPT_DESC
                FROM FLXY_DEPARTMENT";

        if ($search != '') {
            $sql .= " WHERE DEPT_DESC LIKE '%$search%'
                    ";
        }

        $response = $this->Db->query($sql)->getResultArray();

        $option = '<option value="">Choose an Option</option>';
        foreach ($response as $row) {
            $option .= '<option value="' . $row['DEPT_ID'] . '">' . $row['DEPT_CODE'] . ' | ' . $row['DEPT_DESC']  . '</option>';
        }

        return $option;
    }

    public function insertUser()
    {
        try {
            $sysid = $this->request->getPost('USR_ID');

            $rules = [
                'USR_NAME' => ['label' => 'User Name', 'rules' => 'trim|required|is_unique[FLXY_USERS.USR_NAME,USR_ID,' . $sysid . ']'],
                'USR_NUMBER' => ['label' => 'Employee Number', 'rules' => 'trim'],
                'USR_EMAIL' => ['label' => 'User Email', 'rules' => 'trim|required|valid_email|is_unique[FLXY_USERS.USR_EMAIL,USR_ID,' . $sysid . ']'],
                'USR_FIRST_NAME' => ['label' => 'First Name', 'rules' => 'trim|required'],
                'USR_LAST_NAME' => ['label' => 'Last Name', 'rules' => 'trim|required'],
                'USR_ROLE_ID' => ['label' => 'Role', 'rules' => 'trim|required'],
                'USR_DOB' => ['label' => 'Date of Birth', 'rules' => 'trim|required'],
                'USR_ADDRESS' => ['label' => 'Address', 'rules' => 'trim'],
                'USR_COUNTRY' => ['label' => 'Country', 'rules' => 'trim'],
                'USR_STATE' => ['label' => 'State', 'rules' => 'trim'],
                'USR_CITY' => ['label' => 'City', 'rules' => 'trim'],
                'USR_DOJ' => ['label' => 'Date of Joining', 'rules' => 'trim'],
                'USR_PHONE' => ['label' => 'Phone', 'rules' => 'trim'],
                'USR_DEPARTMENT' => ['label' => 'Department', 'rules' => 'trim'],
                'USR_GENDER' => ['label' => 'Gender', 'rules' => 'trim|required']
            ];

            if (!empty($sysid) && $this->request->getPost("USR_PASSWORD") == '' && $this->request->getPost("USR_CONFIRM_PASSWORD") == '') {
            } else if (empty($sysid) || (!empty($sysid) && (null !== $this->request->getPost("USR_PASSWORD") || null !== $this->request->getPost("USR_CONFIRM_PASSWORD")))) {
                $rules['USR_PASSWORD'] = [
                    'label' => 'Password', 'rules' => 'trim|required|min_length[8]|is_password_strong[USR_PASSWORD]',
                    'errors' => [
                        'is_password_strong' => 'The password field must be contains at least one letter and one digit'
                    ]
                ];
                $rules['USR_CONFIRM_PASSWORD'] = [
                    'label' => 'Confirm Password', 'rules' => 'trim|required|matches[USR_PASSWORD]|min_length[8]|is_password_strong[USR_CONFIRM_PASSWORD]',
                    'errors' => [
                        'is_password_strong' => 'The confirm-password field must be contains at least one letter and one digit'
                    ]
                ];
            }

            /*

            $submitted_image = $this->request->getFile('USR_IMAGE');

            // if ( (NULL !== $submitted_image) && ($submitted_image->isValid() &&  !$submitted_image->hasMoved()))

            //     //echo "<pre>"; print_r($this->request->getFile('USR_IMAGE')); echo "</pre>";

                $rules = array_merge($rules, [
                    'USR_IMAGE' => [
                        'label' => 'User Avatar',
                        'rules' => ['uploaded[USR_IMAGE]', 'mime_in[USR_IMAGE,image/png,image/jpg,image/jpeg]', 'max_size[USR_IMAGE,2048]']
                    ],
                ]);
            */

            $validate = $this->validate($rules);

            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }

            $data = [
                "USR_NAME" => trim($this->request->getPost('USR_NAME')),
                "USR_NUMBER" => trim($this->request->getPost('USR_NUMBER')),
                "USR_FIRST_NAME" => trim($this->request->getPost('USR_FIRST_NAME')),
                "USR_LAST_NAME" => trim($this->request->getPost('USR_LAST_NAME')),
                "USR_EMAIL" => trim($this->request->getPost('USR_EMAIL')),
                "USR_ROLE" => trim(strtoupper($this->request->getPost('USR_ROLE'))),
                "USR_ROLE_ID" => trim($this->request->getPost('USR_ROLE_ID')),
                "USR_JOB_TITLE" => trim($this->request->getPost('USR_ROLE_ID')),
                "USR_DEPARTMENT" => trim($this->request->getPost('USR_DEPARTMENT')),
                "USR_DOB" => date('Y-m-d', strtotime($this->request->getPost('USR_DOB'))),
                "USR_ADDRESS" => trim($this->request->getPost('USR_ADDRESS')),
                "USR_COUNTRY" => trim($this->request->getPost('USR_COUNTRY')),
                "USR_STATE" => trim($this->request->getPost('USR_STATE')),
                "USR_CITY" => trim($this->request->getPost('USR_CITY')),
                "USR_DOJ" => date('Y-m-d', strtotime($this->request->getPost('USR_DOJ'))),
                "USR_PHONE" => trim($this->request->getPost('USR_PHONE')),
                "USR_GENDER" => trim(!($this->request->getPost('USR_GENDER'))) ? 0 : 1,
                "USR_TEL_EXT" => trim($this->request->getPost('USR_TEL_EXT')),
                "USR_CREATED_DT" => date("Y-m-d H:i:s"),
                "USR_STATUS" => trim(!($this->request->getPost('USR_STATUS')) ? 0 : 1)

            ];
            if ($this->request->getPost("USR_PASSWORD") != '')
                $data["USR_PASSWORD"] =  password_hash($this->request->getPost("USR_PASSWORD"), PASSWORD_DEFAULT);

            /*
            if ($submitted_image->isValid()) {
                $image = $this->request->getFile('USR_IMAGE');
                $image_name = $image->getRandomName();
                $directory = "assets/img/avatars/";

            //     $response = documentUpload($image, $image_name, $sysid, $directory);

            //     if ($response['SUCCESS'] != 200)
            //         return $this->respond(responseJson("500", true, "Product Image not uploaded"));

                $data['USR_IMAGE'] = $directory . $response['RESPONSE']['OUTPUT'];
            }
            */

            $return = !empty($sysid) ? $this->Db->table('FLXY_USERS')->where('USR_ID', $sysid)->update($data) : $this->Db->table('FLXY_USERS')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            if ($return) {
                $model = new UserModel();
                $user = $model->where('USR_ID', $sysid)->first();

                // Stroing session values
                $this->setUserSession($user);
            }
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    function userCountryList()
    {
        $response = $this->Db->table('COUNTRY')->select('id,cname')->get()->getResultArray();
        $option = '<option value="">Select Country</option>';
        foreach ($response as $row) {
            $option .= '<option value="' . $row['id'] . '">' . $row['cname'] . '</option>';
        }
        echo $option;
    }

    function userStateList()
    {
        $ccode = $this->request->getPost("ccode");
        $state_id = $this->request->getPost("state_id");
        $sql = "SELECT id,sname,state_code FROM STATE WHERE country_id='$ccode'";
        $response = $this->Db->query($sql)->getResultArray();
        $option = '<option value="">Select State</option>';
        $selected = "";
        foreach ($response as $row) {
            $selected = "";
            if ($row['id'] == $state_id) {
                $selected = "selected";
            }
            $option .= '<option value="' . $row['id'] . '"' . $selected . '>' . $row['sname'] . '</option>';
        }
        echo $option;
    }

    function userCityList()
    {
        $ccode = $this->request->getPost("ccode");
        $scode = $this->request->getPost("scode");
        $cityid = $this->request->getPost("cityid");
        $sql = "SELECT ctname,id FROM CITY WHERE country_id='$ccode' AND state_id='$scode'";
        $response = $this->Db->query($sql)->getResultArray();
        $option = '<option value="">Select City</option>';
        $selected = "";
        foreach ($response as $row) {
            $selected = "";
            if ($row['id'] == $cityid) {
                $selected = "selected";
            }
            $option .= '<option value="' . $row['id'] . '"' . $selected . '>' . $row['ctname'] . '</option>';
        }
        echo $option;
    }

    public function editUser()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];

        $sql = "SELECT USR_NAME,USR_NUMBER,USR_FIRST_NAME,USR_LAST_NAME,USR_EMAIL,USR_PASSWORD,USR_ROLE_ID,USR_DEPARTMENT,FORMAT(USR_DOB, 'dd-MMM-yyyy') as USR_DOB,USR_ADDRESS,USR_CITY,USR_STATE,USR_COUNTRY,FORMAT(USR_DOJ, 'dd-MMM-yyyy') as USR_DOJ,USR_PHONE,USR_GENDER,USR_TEL_EXT,USR_STATUS,ROLE_NAME FROM FLXY_USERS LEFT JOIN FLXY_USER_ROLE ON USR_ROLE_ID = ROLE_ID LEFT JOIN COUNTRY ON FLXY_USERS.USR_COUNTRY = COUNTRY.id LEFT JOIN FLXY_DEPARTMENT ON USR_DEPARTMENT = DEPT_ID LEFT JOIN STATE ON FLXY_USERS.USR_STATE = STATE.id WHERE USR_ID=:SYSID:";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function suspendUser()
    {
        $sysid = $this->request->getPost('sysid');
        $suspend = $this->request->getPost('suspend');

        try {
            $return = $this->Db->table('FLXY_USERS')->where('USR_ID', $sysid)->update(array('USR_STATUS' => $suspend));
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not suspended");
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function userRoles()
    {
        try {
            $data['title'] = getMethodName();
            $data['session'] = $this->session;
            $responseSubMenu = null;
            $sql = "SELECT ROLE_NAME,COUNT(USR_ID) AS USR_COUNT FROM FLXY_USERS RIGHT JOIN FLXY_USER_ROLE ON USR_ROLE_ID = ROLE_ID GROUP BY ROLE_NAME";
            $response = $this->Db->query($sql)->getResultArray();
            $responseCount = $this->Db->query($sql)->getNumRows();

            $sql = "SELECT MENU_ID,MENU_NAME,PARENT_MENU_ID FROM FLXY_MENU WHERE MENU_STATUS = 1 AND PARENT_MENU_ID = 0";
            $responseMenu = $this->Db->query($sql)->getResultArray();
            $responseMenuCount = $this->Db->query($sql)->getNumRows();
            if ($responseMenuCount > 0) {
                foreach ($responseMenu as $menu) {
                    $sql = "SELECT MENU_ID,MENU_NAME FROM FLXY_MENU WHERE MENU_STATUS = 1 AND PARENT_MENU_ID = " . $menu['MENU_ID'] . " AND  PARENT_MENU_ID > 0";
                    $subMenu = $this->Db->query($sql)->getResultArray();
                    $subMenuCount = $this->Db->query($sql)->getNumRows();
                    if ($subMenuCount > 0) {
                        foreach ($subMenu as $value) {
                            $responseSubMenu[$menu['MENU_ID']][] = ['sub_menu_id' => $value['MENU_ID'], 'sub_menu_name' => $value['MENU_NAME']];
                        }
                    }
                }
            }

            $data['roleList'] = $this->roleList();
            $data['departmentList'] = $this->departmentList();
            $data['response'] = $response;
            $data['responseCount'] = $responseCount;
            $data['responseMenu'] = $responseMenu;
            $data['responseMenuCount'] = $responseMenuCount;
            $data['responseSubMenu'] = $responseSubMenu;
            //print_r($data['responseSubMenu']);
            return view('Users/UserRoles', $data);
        } catch (Exception $e) {
            echo json_encode($e->errors());
        }
    }

    public function addRolePermission()
    {
        try {
            $sysid = $this->request->getPost('ROLE_ID');

            $validate = $this->validate([
                'ROLE_NAME' => ['label' => 'Role', 'rules' => 'required|is_unique[FLXY_USER_ROLE.ROLE_NAME,ROLE_ID,' . $sysid . ']',],
                'MENU_ID' => ['label' => 'Permission', 'rules' => 'required']

            ]);

            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }

            $data = [
                "ROLE_NAME" => trim($this->request->getPost('ROLE_NAME')),
                "ROLE_CODE" => trim($this->request->getPost('ROLE_NAME'))
            ];

            $return = !empty($sysid) ? $this->Db->table('FLXY_USER_ROLE')->where('ROLE_ID', $sysid)->update($data) : $this->Db->table('FLXY_USER_ROLE')->insert($data);
            $ROLE_ID = !empty($sysid) ? $sysid : $this->Db->insertID();

            // if($sysid > 0 ){
            $deleteRolePerm = $this->Db->table('FLXY_USER_ROLE_PERMISSION')->delete(['ROLE_ID' => $sysid]);

            $MENU_PERM = $this->request->getPost('MENU_ID');
            if (!empty($MENU_PERM)) {
                for ($i = 0; $i < count($MENU_PERM); $i++) {
                    $data = [
                        "ROLE_ID" => trim($ROLE_ID),
                        "ROLE_MENU_ID" => $MENU_PERM[$i],
                        "ROLE_PERM_STATUS" => 1
                    ];
                    $return = $this->Db->table('FLXY_USER_ROLE_PERMISSION')->insert($data);
                }
            }

            $SUB_MENU_PERM = $this->request->getPost('sub_menu_id');
            if (!empty($SUB_MENU_PERM)) {
                for ($j = 0; $j < count($SUB_MENU_PERM); $j++) {
                    $data = [
                        "ROLE_ID" => trim($ROLE_ID),
                        "ROLE_MENU_ID" => $SUB_MENU_PERM[$j],
                        "ROLE_PERM_STATUS" => 1
                    ];
                    $return = $this->Db->table('FLXY_USER_ROLE_PERMISSION')->insert($data);
                }
            }
            // }




            // $return = !empty($sysid) ? $this->Db->table('FLXY_SUB_MENU')->where('SUB_MENU_ID', $sysid)->update($data) : $this->Db->table('FLXY_SUB_MENU')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }


    public function loadUserRoles()
    {
        $sql = "SELECT ROLE_ID,ROLE_NAME,COUNT(USR_ID) AS USR_COUNT FROM FLXY_USERS RIGHT JOIN FLXY_USER_ROLE ON USR_ROLE_ID = ROLE_ID GROUP BY ROLE_NAME,ROLE_ID ORDER BY ROLE_ID OFFSET 0 ROWS FETCH FIRST 5 ROWS ONLY";
        $sql1 = "SELECT ROLE_ID,ROLE_NAME,COUNT(USR_ID) AS USR_COUNT FROM FLXY_USERS RIGHT JOIN FLXY_USER_ROLE ON USR_ROLE_ID = ROLE_ID GROUP BY ROLE_NAME,ROLE_ID ORDER BY ROLE_ID ";
        $response = $this->Db->query($sql)->getResultArray();
        $responseCount = $this->Db->query($sql1)->getNumRows();
        $rolesOutput = $rolesAddNew = $addRole = '';

        if ($responseCount > 5) {
            $addRole =  '<button
            data-bs-target="#viewRoleModal"
            data-bs-toggle="modal"
            class="btn btn-primary view-user-role text-nowrap mb-3"
            >
            View All
            </button>';
        }


        if (!empty($response)) {
            foreach ($response as $row) {
                $ROLE_NAME =  ucfirst(strtolower($row['ROLE_NAME']));
                $rolesOutput .= <<<EOD
            <div class="col-md-6 col-lg-6 col-xl-4">
                <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                    <h6 class="fw-normal">Total {$row['USR_COUNT']} users</h6>
                    <ul class="list-unstyled avatar-group d-flex align-items-center mb-0">
                            <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="" class="avatar avatar-sm pull-up">
                            <span class="avatar-initial rounded-circle bg-label-info">
                                <i class="fa fa-user"></i>                            
                            </span>
                            </li>
                    </ul>
                    </div>
                    <div class="d-flex justify-content-between align-items-end">
                    <div class="role-heading">
                        <h4 class="mb-1">{$ROLE_NAME}</h4>
                        <a
                        href="javascript:;"
                        class="role-edit-modal" data_sysid="{$row['ROLE_ID']}"
                        ><small>Edit Role</small></a
                        >
                    </div>
                    <a href="javascript:void(0);" class="text-muted"><i class="bx bx-copy"></i></a>
                    </div>
                </div>
                </div>
                </div>
        
            EOD;
            }
        }


        $rolesOutput .= <<<EOD
        <div class="col-md-6 col-lg-6 col-xl-4">
        <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-2">
            <h6 class="fw-normal">Add role, if it does not exist</h6>
            <ul class="list-unstyled avatar-group d-flex align-items-center mb-0">
                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="" class="avatar avatar-sm pull-up">
                      <span class="avatar-initial rounded-circle bg-label-info">
                        <i class="fa fa-user"></i>
                    
                    </span>
                      </li>
            </ul>
            </div>
            <div class="d-flex justify-content-between align-items-end">
            <button
                data-bs-target="#addRoleModal"
                data-bs-toggle="modal"
                class="btn btn-primary add-new-role text-nowrap mb-3"
                >
                Add Role
                </button>
                
                {$addRole}
            
            <a href="javascript:void(0);" class="text-muted"><i class="bx bx-copy"></i></a>
            </div>
        </div>
        </div>
    </div></div>
    EOD;

        echo $rolesOutput;
    }


    public function editRolePermission()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];
        $sql = "SELECT FLXY_USER_ROLE.ROLE_ID, ROLE_MENU_ID as MENU_ID, ROLE_NAME
            FROM FLXY_USER_ROLE LEFT JOIN FLXY_USER_ROLE_PERMISSION ON FLXY_USER_ROLE.ROLE_ID = FLXY_USER_ROLE_PERMISSION.ROLE_ID
            WHERE FLXY_USER_ROLE.ROLE_ID=:SYSID: ";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }



    public function viewUserRoles()
    {

        $mine      = new ServerSideDataTable();
        $tableName = 'FLXY_USERS_ROLES_VIEW';
        $columns   = 'ROLE_ID,ROLE_NAME,USR_COUNT';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function accessDenied()
    {

        return view('Users/notAuth');
    }

    public function userByDepartment()
    {
        $department_ids = $this->request->getPost('department_ids');

        $result = $this->UserRepository->userByDepartment($department_ids);
        return $this->respond(responseJson(200, false, ['msg' => 'users'], $result));
    }

    public function forgetPassword()
    {   
        $rules = [
            'email' => 'required'
        ];

        if(!$this->validate($rules))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));

        $data = json_decode(json_encode($this->request->getVar()), true);

        $user = $this->UserRepository->userByEmail($data['email']);
        if (empty($user))
            return $this->respond(responseJson(404, true, ['msg' => 'There is no account with this email.']));

        $bytes = random_bytes(40);
        $random_token = bin2hex($bytes);

        $data['user'] = $user;
        $data['from_email'] = 'notifications@farnek.com';
        $data['from_name'] = 'FlexiGuest | Hitek';

        $data['to_email'] = $user['USR_EMAIL'];
        $data['to_name'] = $user['USR_FIRST_NAME'] . ' ' . $user['USR_LAST_NAME'];

        $data['subject'] = 'Reset Password';
        $data['branding_logo'] = brandingLogo();
        $data['reset_url'] = base_url("reset-password-form/$random_token");
        $data['html'] = view('EmailTemplates/reset_password', $data);

        $response = $this->EmailLibrary->commonEmail($data);
        if ($response != true)
            return $this->respond(responseJson(500, true, ['msg' => 'Unable to send mail.']));

        $this->UserRepository->insertForgetPasswordToken([
            'FPT_USER_ID' => $user['USR_ID'],
            'FPT_TOKEN' => $random_token,
            'FPT_EXPIRE_AT' => date('Y-m-d H:i:s', strtotime('+1 day')),
            'FPT_CREATED_BY' => $user['USR_ID'],
            'FPT_UPDATED_BY' => $user['USR_ID']
        ]);

        return $this->respond(responseJson(200, false, ['msg' => 'Please check your mail to reset password.']));
    }

    public function resetPasswordForm($token)
    {
        $data['token'] = $token;
        $user = $this->UserRepository->getUserByToken($token);
        if (empty($user)) {
            $data ['type'] = 'error'; 
            $data['messages'] = ['Token is expired or invalid!'];
        }

        return view('frontend/reset_password_form', $data);
    }

    public function resetPassword($token)
    {
        $data = $this->request->getPost();
        $data['token'] = $token;

        $user = $this->UserRepository->getUserByToken($token);
        if (empty($user))
            return view('frontend/reset_password_form', array_merge($data, ['type' => 'error', 'messages' => ['Token is expired or invalid!']]));

        $rules = [
            'email' => 'required|valid_email',
            'new_password' => 'required|min_length[8]|max_length[255]|strongPassword[new_password]',
            'confirm_password' => 'required|matches[new_password]',
        ];

        $messages = [
            'new_password' => [
                'strongPassword' => 'Password is not strong. It should contain at least one digit, one capital letter, one small letter, and one special character. (white spaces are not allowed).'
            ],
        ];

        if (!$this->validate($rules, $messages))
            return view('frontend/reset_password_form', array_merge($data, ['type' => 'error', 'messages' => $this->validator->getErrors()]));

        if($user['USR_EMAIL'] != $data['email'])
            return view('frontend/reset_password_form', array_merge($data, ['type' => 'error', 'messages' => ['Invalid Email.']]));

        $this->UserRepository->updateUserById([
            'USR_ID' => $user['USR_ID'],
            'USR_PASSWORD' => password_hash($data['new_password'], PASSWORD_DEFAULT),
        ]);
        
        $this->UserRepository->removeForgetPasswordToken($user['USR_ID']);

        return view('frontend/reset_password_form', array_merge($data, ['type' => 'success', 'messages' => ['Password updated successfully.']]));
    }
}
