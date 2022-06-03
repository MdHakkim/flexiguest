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


    /**************      Currency Functions      ***************/

    public function inventory()
    {
        $data['title'] = getMethodName();
        $data['session'] = $this->session;
        $itemLists = $this->itemList();    
        $data['itemLists'] = $itemLists;          
        $data['js_to_load'] = array("inventoryFormWizardNumbered.js");
        return view('Master/ReservationInventory', $data);
        
    }

    public function InventoryView()
    {
        // $mine      = new ServerSideDataTable(); // loads and creates instance
        // $tableName = 'FLXY_CURRENCY';
        // $columns   = 'CUR_ID,CUR_CODE,CUR_DESC,CUR_DECIMAL,CUR_STATUS,CUR_CREATED';
        // $cond      =  array();
        // //$cond["CUR_DELETED LIKE "] = "0";
        // $mine->generate_DatatTable($tableName, $columns, $cond);
        // exit;
    }

    public function itemList()
        {
           
            $item_id = $this->request->getPost("item_id");
            
            if( $item_id > 0 )
                $sql = "SELECT * FROM FLXY_ITEM WHERE ITM_ID='$item_id' ";
            else 
                $sql = "SELECT * FROM FLXY_ITEM";          

            $response = $this->Db->query($sql)->getResultArray();
            $option='<option value="">Select Item </option>';
            $selected = "";
            foreach($response as $row){
                if($row['ITM_ID'] == $item_id){
                    $selected = "selected";
                }

                $option.= '<option value="'.$row['ITM_ID'].'"'.$selected.'>'.$row['ITM_CODE'].' | '.$row['ITM_NAME'].'</option>';
            }
            return $option;
        }

        public function insertItemInventory()
        {
           
            try {
                // if (!$validate) {
                //     $validate = $this->validator->getErrors();
                //     $result["SUCCESS"] = "-402";
                //     $result[]["ERROR"] = $validate;
                //     $result = $this->responseJson("-402", $validate);
                //     echo json_encode($result);
                //     exit;
                // }
                $sysid = $this->request->getPost('RSV_PRI_ID');
                $data = [
                    
                    "RSV_ITM_ID" => trim($this->request->getPost('RSV_ITM_ID')),
                    "RSV_ID" => '100',
                    "RSV_ITM_BEGIN_DATE" => $this->request->getPost('ITEM_AVAIL_START_DT'),
                    "RSV_ITM_END_DATE" => $this->request->getPost('ITEM_AVAIL_END_DT'),
                    "RSV_ITM_QTY" => trim($this->request->getPost('RSV_ITM_QTY'))                   
                ];
    
                $return = !empty($sysid) ? $this->Db->table('FLXY_RESERVATION_ITEM')->where('RSV_PRI_ID', $sysid)->update($data) : $this->Db->table('FLXY_RESERVATION_ITEM')->insert($data);
                $newItemCodeID = empty($sysid) ? $this->Db->insertID() : '';                        
                    
                $result = $return ? $this->responseJson("1", "0", $return, !empty($sysid) ? $sysid : $newItemCodeID) : $this->responseJson("-444", "db insert not successful", $return);
    
                // if(empty($sysid))
                // {
                //     $blank_item_detail = [
                //                              "RSV_ITM_ID" => '0', 
                //                              "RSV_ITM_BEGIN_DATE" => date('Y-m-d'),
                //                              "RSV_ID" => '100',
                //                              "RSV_ITM_END_DATE" => date('Y-m-d', strtotime('+10 years')),
                //                              "RSV_ITM_QTY" => '0'
                //                             ];
                    
                //     $this->Db->table('FLXY_RESERVATION_ITEM')->insert($blank_item_detail);
                // }
    
                echo json_encode($result);
                
            } catch (Exception $e) {
                return $this->respond($e->errors());
            }
        }

    
    
}