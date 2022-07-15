<?php

namespace App\Controllers;

use App\Libraries\ServerSideDataTable;
use CodeIgniter\API\ResponseTrait;

class InventoryController extends BaseController
{
    use ResponseTrait;

    public $Db;
    public $request;
    public $session;

    public function __construct()
    {
        $this->Db = \Config\Database::connect();
        $this->request = \Config\Services::request();
        $this->session = \Config\Services::session();
        helper(['form', 'url', 'custom', 'common']);
    }


    /**************      Inventory Functions      ***************/

    public function inventory()
    {
        $data['title'] = getMethodName();
        $data['session'] = $this->session;
        $itemLists = $this->itemList();    
        $data['itemLists'] = $itemLists;                 
        $data['itemAvail'] = $this->ItemCalendar();     
        $data['classList'] = $this->itemInventoryClassList();

        $data['toggleButton_javascript'] = toggleButton_javascript();
        $data['clearFormFields_javascript'] = clearFormFields_javascript();
        $data['blockLoader_javascript'] = blockLoader_javascript();
        $data['js_to_load'] = array("inventoryFormWizardNumbered.js","reservation-calendar.js");
        return view('Master/ReservationInventory', $data);
                
    }

    public function showInventoryItems()
    { 
        $mine = new ServerSideDataTable(); // loads and creates instance

        //Reservation ID 
        $RESV_ID = $this->request->getPost('RESV_ID');
       // $RESV_ID = 0;
         $session_id = session_id();

            if($RESV_ID  > 0 || $RESV_ID != NULL)
            $init_cond = array("RSV_ID = " => $RESV_ID); 
            else
            $init_cond = array("RSV_SESSION_ID LIKE " => "'".$session_id."'", "RSV_ID = " => 0); 

           //echo $init_cond;
            $tableName = 'FLXY_RESERVATION_ITEM INNER JOIN FLXY_ITEM ON FLXY_RESERVATION_ITEM.RSV_ITM_ID = FLXY_ITEM.ITM_ID';
            $columns = 'RSV_PRI_ID,ITM_CODE,ITM_NAME,RSV_ITM_ID,RSV_ITM_CL_ID,RSV_ITM_BEGIN_DATE,RSV_ITM_END_DATE,RSV_ITM_QTY';
            $mine->generate_DatatTable($tableName, $columns, $init_cond);
        
        
        exit;
    }

    public function itemList()
        {
            $option = '';
            $item_id = $this->request->getPost("item_id");
            
            if( $item_id > 0 )
                $sql = "SELECT * FROM FLXY_ITEM WHERE ITM_ID='$item_id' ";
            else 
                $sql = "SELECT * FROM FLXY_ITEM";          

            $response = $this->Db->query($sql)->getResultArray();
            //$option='<option value="">Select Item </option>';
            $selected = "";
            foreach($response as $row){
                if($row['ITM_ID'] == $item_id){
                    $selected = "selected";
                }

                $option.= '<option value="'.$row['ITM_ID'].'"'.$selected.'>'.$row['ITM_CODE'].' | '.$row['ITM_NAME'].'</option>';
            }
            return $option;
        }

        
    public function updateInventoryItems()
    {
        try {
            $RSV_PRI_ID    = $this->request->getPost('RSV_PRI_ID');
            $RESV_ID       = $this->request->getPost('ITEM_RESV_ID');
            $RSV_ITM_CL_ID = $this->request->getPost('RSV_ITM_CL_ID');
            $RSV_ITM_ID    = $this->request->getPost('RSV_ITM_ID');
            $existsCount   = 0;
            if($RESV_ID == '') 
            $RESV_ID = 0;
            if($RSV_PRI_ID == ''){
                 
                 $existsCount = $this->ItemExists($RSV_ITM_CL_ID, $RSV_ITM_ID, $RESV_ID);
                 if($existsCount > 0){
                    $result['SUCCESS'] = '2';
                    echo json_encode($result);
                    return;
                 }
            }         


             // Checks item quantity
            $resvItemQty = $this->request->getPost('RSV_ITM_QTY');
            $resvItemID  =  $this->request->getPost('RSV_ITM_ID');            
             
            $ITEM_AVAIL_QTY = 0;
 
            $ITEM_AVAIL_QTY = $this->Db->table('FLXY_ITEM')->select('ITEM_AVAIL_QTY')->where('ITM_ID', $this->request->getPost('RSV_ITM_ID'))->get()->getRowArray();
             $ITEM_QTY = (int)$ITEM_AVAIL_QTY['ITEM_AVAIL_QTY']; 

            $data = [
                "RSV_ID" => $RESV_ID,
                "RSV_ITM_CL_ID" => trim($this->request->getPost('RSV_ITM_CL_ID')),
                "RSV_ITM_ID" => trim($this->request->getPost('RSV_ITM_ID')),
                "RSV_ITM_BEGIN_DATE" => date('Y-m-d',(strtotime($this->request->getPost('RSV_ITM_BEGIN_DATE')))),
                "RSV_ITM_END_DATE" => date('Y-m-d',(strtotime($this->request->getPost('RSV_ITM_END_DATE')))),
                "RSV_ITM_QTY" => trim($this->request->getPost('RSV_ITM_QTY')),
                "RSV_SESSION_ID" => session_id()
            ];


           // echo  $data['RSV_ITM_BEGIN_DATE'];
           
            
          

            $rules = [  'RSV_ITM_CL_ID' => ['label' => 'Class', 'rules' => 'required'],
                        'RSV_ITM_ID' => ['label' => 'Item', 'rules' => 'required'],
                        'RSV_ITM_BEGIN_DATE' => ['label' => 'Start Date', 'rules' => 'required|itemAvailableCheck[RSV_ITM_BEGIN_DATE]|itemDateOverlapCheck[RSV_ITM_BEGIN_DATE]', 'errors' => ['itemAvailableCheck' => 'Item is unavailable on this start date','itemDateOverlapCheck' => 'The Start Date of item overlaps with an existing reservation. Change the date']],                       
                         'RSV_ITM_END_DATE' => ['label' => 'End Date', 'rules' => 'required|compareDate[RSV_ITM_END_DATE]|itemAvailableCheck[RSV_ITM_END_DATE]|itemDateOverlapCheck[RSV_ITM_END_DATE]', 'errors' => ['compareDate' => 'The End Date should be after Begin Date','itemAvailableCheck' => 'Item is unavailable on this end date','itemDateOverlapCheck' => 'The End Date of item overlaps with an existing reservation. Change the date']],
                        'RSV_ITM_QTY' => ['label' => 'Quantity', 'rules' => 'required|checkAvailableItemQty['.$ITEM_QTY.']', 'errors' => ['checkAvailableItemQty' => 'Item available quantity is less than the requested quantity']]                      
                        
                     ];

                   

            $validate = $this->validate($rules);
            
            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }
           

            
          
            (int)$ITEM_QTY = $ITEM_QTY-(int)$resvItemQty;
            $this->Db->table('FLXY_ITEM')->where('ITM_ID', $resvItemID)->update(['ITEM_AVAIL_QTY' => $ITEM_QTY]);


            $return = !empty($RSV_PRI_ID) ? $this->Db->table('FLXY_RESERVATION_ITEM')->where('RSV_PRI_ID', $RSV_PRI_ID)->update($data) : $this->Db->table('FLXY_RESERVATION_ITEM')->insert($data);

            $result = $return ? $this->responseJson("1", "0", $return, !empty($RSV_PRI_ID) ? $RSV_PRI_ID : $this->Db->insertID()) : $this->responseJson("-444", "db insert not successful", $return);


           
            // $query = $this->Db->query('SELECT ITEM_AVAIL_QTY FROM FLXY_ITEM WHERE ITM_ID = '.$resvItemID);
            // $row   = $query->getRowArray();
            // echo $row['ITEM_AVAIL_QTY'];exit;
           
            if(!$return)
                $this->session->setFlashdata('error', 'There has been an error. Please try again.');
            else
            {
                if(empty($RSV_PRI_ID))
                    //$this->session->setFlashdata('success', 'The Package Code has been updated.');
                    //else
                    $this->session->setFlashdata('success', 'The new Item  has been added.');
            }
            echo json_encode($result);

        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function showInventoryDetails()
    {
        $itemDetailsList = $this->getItemDetails($this->request->getPost('RSV_PRI_ID'));
        echo json_encode($itemDetailsList);
    }

    public function getItemDetails($itemID = 0)
    {
        $param = ['SYSID' => $itemID];

        $sql = "SELECT *           
                FROM dbo.FLXY_RESERVATION_ITEM
                WHERE RSV_PRI_ID=:SYSID:";       

        $response = $this->Db->query($sql, $param)->getResultArray();
        return $response;
    }

    
    public function deleteItemInventory()
    {
        $RSV_PRI_ID = $this->request->getPost('RSV_PRI_ID');

        try {
            $return = $this->Db->table('FLXY_RESERVATION_ITEM')->delete(['RSV_PRI_ID' => $RSV_PRI_ID]); 
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch (\Exception $e) {
            return $this->respond($e->errors());
        }
        
    }

    public function ItemExists($itemClass, $itemID, $reserID)
    {      
                

        $sql = "SELECT *           
                FROM dbo.FLXY_RESERVATION_ITEM
                WHERE RSV_ITM_CL_ID = '" . $itemClass . "' AND RSV_ITM_ID = '" . $itemID . "' AND RSV_ID = '" . $reserID . "' AND RSV_SESSION_ID LIKE  '" . session_id() . "'";  

        $response = $this->Db->query($sql)->getNumRows();
        return $response;
    }


    public function itemInventoryClassSingle()
        {
            $option = '';
            $sql = "SELECT IT_CL_ID,IT_CL_CODE,IT_CL_DESC FROM FLXY_ITEM_CLASS";
            $response = $this->Db->query($sql)->getResultArray();
            
            foreach($response as $row){
                
                $option.= '<option value="'.$row['IT_CL_ID'].'">'.$row['IT_CL_CODE'].' | '.$row['IT_CL_DESC'].'</option>';
            }
            echo $option;
        }


    public function itemInventoryClassList()
        {       
            $response = NULL;     
            $sql = "SELECT IT_CL_ID,IT_CL_CODE,IT_CL_DESC FROM FLXY_ITEM_CLASS";                 
            $responseCount = $this->Db->query($sql)->getNumRows();
            if($responseCount > 0) 
            $response = $this->Db->query($sql)->getResultArray(); 
            return $response;
        }




    public function ItemCalendar(){
        $response = NULL;
        $sql = "SELECT dbo.FLXY_ITEM.ITM_ID, ITM_CODE, ITM_NAME, ITM_DESC, dbo.FLXY_ITEM.IT_CL_ID, IT_CL_CODE, IT_CL_DESC, ITM_AVAIL_FROM_TIME, ITM_AVAIL_TO_TIME, ITM_DLY_BEGIN_DATE, ITM_DLY_END_DATE          
                FROM dbo.FLXY_ITEM LEFT JOIN dbo.FLXY_DAILY_INVENTORY ON dbo.FLXY_ITEM.ITM_ID = dbo.FLXY_DAILY_INVENTORY.ITM_ID INNER JOIN dbo.FLXY_ITEM_CLASS ON dbo.FLXY_ITEM.IT_CL_ID = dbo.FLXY_ITEM_CLASS.IT_CL_ID ";       
        $responseCount = $this->Db->query($sql)->getNumRows();
        if($responseCount > 0)
        $response = $this->Db->query($sql)->getResultArray();

      return $response;
    }


    // public function reservationItemList()
    //     {
            
    //         $sql = "SELECT RSV_ITM_ID, RSV_ITM_BEGIN_DATE, RSV_ITM_END_DATE FROM FLXY_RESERVATION_ITEM";
                 

    //         $response = $this->Db->query($sql)->getResultArray();
           
    //         foreach($response as $row){               

                
    //         }
    //         return $option;
    //     }


    
    
}