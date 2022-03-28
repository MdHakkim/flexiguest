<?php

namespace App\Controllers;
use  App\Libraries\ServerSideDataTable;
class MastersController extends BaseController
{
    public $Db;
    public $request;
    public $session;

    public function __construct(){
        $this->Db = \Config\Database::connect();
        $this->request = \Config\Services::request();
        $this->session = \Config\Services::session();
        helper(['form']);
    }

    public function getMethodName()
    {
        /** Get Method Name as Page Title */

        $router = service('router'); 
        $method = $router->methodName();
        return ucwords(implode(' ',preg_split('/(?=[A-Z])/', $method)));
    }
    
    /**************      Rate Class Functions      ***************/

    public function rateClass(){

        $data['title'] = $this->getMethodName();
        return view('Reservation/RateClassView', $data);
    }

    public function RateClassView(){
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_RATE_CLASS';
        $columns = 'RT_CL_ID,RT_CL_CODE,RT_CL_DESC,RT_CL_BEGIN_DT,RT_CL_END_DT,RT_CL_DIS_SEQ';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
    }

    public function insertRateClass(){
        try{
            $sysid = $this->request->getPost('RT_CL_ID');

            $validate = $this->validate([
                'RT_CL_CODE' => ['label' => 'Rate Class Code', 'rules' => 'required|is_unique[FLXY_RATE_CLASS.RT_CL_CODE,RT_CL_ID,'.$sysid.']'],
                'RT_CL_DESC' => ['label' => 'Rate Class Description', 'rules' => 'required'],
                'RT_CL_DIS_SEQ' => ['label' => 'Display Sequence', 'rules' => 'permit_empty|greater_than_equal_to[0]'],
                'RT_CL_BEGIN_DT' => ['label' => 'Begin Date', 'rules' => 'required'],
                'RT_CL_END_DT' => ['label' => 'End Date', 'rules' => 'compareDate', 'errors' => ['compareDate' => 'The End Date should be after Begin Date']]
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }

            //echo json_encode(print_r($_POST)); exit;
            
            $data = [   "RT_CL_CODE" => trim($this->request->getPost('RT_CL_CODE')),
                        "RT_CL_DESC" => trim($this->request->getPost('RT_CL_DESC')),
                        "RT_CL_DIS_SEQ" => trim($this->request->getPost('RT_CL_DIS_SEQ')) != '' ? trim($this->request->getPost('RT_CL_DIS_SEQ')) : '',
                        "RT_CL_BEGIN_DT" => trim($this->request->getPost('RT_CL_BEGIN_DT')),
                        "RT_CL_END_DT" => trim($this->request->getPost('RT_CL_END_DT')) != '' ? trim($this->request->getPost('RT_CL_END_DT')) : '2030-12-31'
                    ];
            
            $return = !empty($sysid) ? $this->Db->table('FLXY_RATE_CLASS')->where('RT_CL_ID', $sysid)->update($data) : $this->Db->table('FLXY_RATE_CLASS')->insert($data); 
            $result = $return ? $this->responseJson("1","0",$return,$response='') : $this->responseJson("-444","db insert not successful",$return);
            echo json_encode($result);            
        }
        catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function checkRateClass($rcCode)
    {
        $sql = "SELECT RT_CL_ID 
                FROM FLXY_RATE_CLASS 
                WHERE RT_CL_CODE = '".$rcCode."'";

        $response = $this->Db->query($sql)->getNumRows();
        return $rcCode == '' || strlen($rcCode) > 10 ? 1 : $response; // Send found row even if submitted code is empty
    }

    public function getAssocRateCategories()
    {
        $param = ['SYSID'=> $this->request->getGet('rclId')];
        
        $sql = "SELECT STRING_AGG(RT_CT_CODE, ', ') as ASSOC_CATS
                FROM FLXY_RATE_CATEGORY 
                WHERE RT_CL_ID=:SYSID:";

        $response = $this->Db->query($sql,$param)->getRowArray();
        echo json_encode($response);
    }

    public function copyRateClass(){
        try{
            $param = ['SYSID'=> $this->request->getPost('main_RT_CL_ID')];
        
            $sql = "SELECT RT_CL_ID, RT_CL_CODE, RT_CL_DESC, RT_CL_DIS_SEQ, RT_CL_BEGIN_DT, RT_CL_END_DT 
                    FROM FLXY_RATE_CLASS 
                    WHERE RT_CL_ID=:SYSID:";

            $origRateCode = $this->Db->query($sql,$param)->getResultArray()[0];
            
            //echo json_encode($response);
            //echo json_encode(print_r($origRateCode)); exit;

            $no_of_added = 0;
            $submitted_fields = $this->request->getPost('group-a');

            if($submitted_fields != NULL)
            {
                foreach($submitted_fields as $submitted_field)
                {
                    if(!$this->checkRateClass($submitted_field['RT_CL_CODE'])) // Check if entered Rate Class already exists
                    {
                        $newRateCode = [    "RT_CL_CODE" => trim($submitted_field["RT_CL_CODE"]),
                                            "RT_CL_DESC" => $origRateCode["RT_CL_DESC"],
                                            "RT_CL_DIS_SEQ" => '',
                                            "RT_CL_BEGIN_DT" => $origRateCode["RT_CL_BEGIN_DT"] != '' ? $origRateCode["RT_CL_BEGIN_DT"] : date('Y-m-d'),
                                            "RT_CL_END_DT" => $origRateCode["RT_CL_END_DT"] != '' ? $origRateCode["RT_CL_END_DT"] : '2030-12-31'
                                       ];

                        $this->Db->table('FLXY_RATE_CLASS')->insert($newRateCode); 

                        $no_of_added += $this->Db->affectedRows();
                    }
                   
                }
            }

            echo $no_of_added;
            exit;
            
            //echo json_encode(print_r($_POST)); exit;            
        }
        catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function rateClassList(){
        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';
        
        $sql = "SELECT RT_CL_ID, RT_CL_CODE, RT_CL_DESC 
                FROM FLXY_RATE_CLASS";

        if($search != '')        
            $sql .= " WHERE RT_CL_CODE LIKE '%$search%' 
                      OR RT_CL_DESC LIKE '%$search%'";

        $response = $this->Db->query($sql)->getResultArray();
        
        $option='<option value="">Choose an Option</option>';
        foreach($response as $row){
            $option .= '<option value="'.$row['RT_CL_ID'].'">'.$row['RT_CL_CODE'].' | '.$row['RT_CL_DESC'].'</option>';
        }

        return $option;
    }

    public function editRateClass(){
        $param = ['SYSID'=> $this->request->getPost('sysid')];
        
        $sql = "SELECT RT_CL_ID, RT_CL_CODE, RT_CL_DESC, RT_CL_DIS_SEQ, 
                FORMAT(RT_CL_BEGIN_DT, 'd-MMM-yyyy') as RT_CL_BEGIN_DT, FORMAT(RT_CL_END_DT, 'dd-MMM-yyyy') as RT_CL_END_DT 
                FROM FLXY_RATE_CLASS 
                WHERE RT_CL_ID=:SYSID:";

        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteRateClass(){
        $sysid = $this->request->getPost('sysid');

        try{
            $return = $this->Db->table('FLXY_RATE_CLASS')->delete(['RT_CL_ID' => $sysid]); 
            $result = $return ? $this->responseJson("1","0",$return) : $this->responseJson("-402","Record not deleted");
            echo json_encode($result);
        }
        catch (Exception $e){
            return $this->respond($e->errors());
        }
    }


    /**************      Rate Category Functions      ***************/

    public function rateCategory(){

        $rateClassOptions = $this->rateClassList();
    
        $data = ['rateClassOptions' => $rateClassOptions];
        $data['title'] = $this->getMethodName();
        
        return view('Reservation/RateCategoryView', $data);
    }
    
    public function RateCategoryView(){
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_RATE_CATEGORY LEFT JOIN FLXY_RATE_CLASS FRC ON FRC.RT_CL_ID = FLXY_RATE_CATEGORY.RT_CL_ID';
        $columns = 'RT_CT_ID,RT_CT_CODE,RT_CT_DESC,RT_CL_CODE,RT_CT_BEGIN_DT,RT_CT_END_DT,RT_CT_DIS_SEQ';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
    }
    
    public function insertRateCategory(){
        try{

            $_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);

            $sysid = $this->request->getPost('RT_CT_ID');
    
            $validate = $this->validate([
                'RT_CT_CODE' => ['label' => 'Rate Category Code', 'rules' => 'required|is_unique[FLXY_RATE_CATEGORY.RT_CT_CODE,RT_CT_ID,'.$sysid.']'],
                'RT_CT_DESC' => ['label' => 'Rate Category Description', 'rules' => 'required'],
                'RT_CL_ID' => ['label' => 'Rate Class Code', 'rules' => 'required'],
                'RT_CT_DIS_SEQ' => ['label' => 'Display Sequence', 'rules' => 'permit_empty|greater_than_equal_to[0]'],
                'RT_CT_BEGIN_DT' => ['label' => 'Begin Date', 'rules' => 'required'],
                'RT_CT_END_DT' => ['label' => 'End Date', 'rules' => 'compareDate2', 'errors' => ['compareDate2' => 'The End Date should be after Begin Date']]
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
    
            //echo json_encode(print_r($_POST)); exit;
            
            $data = [   "RT_CT_CODE" => trim($this->request->getPost('RT_CT_CODE')),
                        "RT_CT_DESC" => trim($this->request->getPost('RT_CT_DESC')),
                        "RT_CL_ID" => trim($this->request->getPost('RT_CL_ID')),
                        "RT_CT_DIS_SEQ" => trim($this->request->getPost('RT_CT_DIS_SEQ')) != '' ? trim($this->request->getPost('RT_CT_DIS_SEQ')) : '',
                        "RT_CT_BEGIN_DT" => trim($this->request->getPost('RT_CT_BEGIN_DT')),
                        "RT_CT_END_DT" => trim($this->request->getPost('RT_CT_END_DT')) != '' ? trim($this->request->getPost('RT_CT_END_DT')) : '2030-12-31'
                    ];
            
            $return = !empty($sysid) ? $this->Db->table('FLXY_RATE_CATEGORY')->where('RT_CT_ID', $sysid)->update($data) : $this->Db->table('FLXY_RATE_CATEGORY')->insert($data); 
            $result = $return ? $this->responseJson("1","0",$return,$response='') : $this->responseJson("-444","db insert not successful",$return);
            echo json_encode($result);            
        }
        catch (Exception $e){
            return $this->respond($e->errors());
        }
    }
    
    public function checkRateCategory($rcCode)
    {
        $sql = "SELECT RT_CT_ID 
                FROM FLXY_RATE_CATEGORY 
                WHERE RT_CT_CODE = '".$rcCode."'";
    
        $response = $this->Db->query($sql)->getNumRows();
        return $rcCode == '' || strlen($rcCode) > 10 ? 1 : $response; // Send found row even if submitted code is empty
    }
    
    public function copyRateCategory(){
        try{
            $_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);

            $param = ['SYSID'=> $this->request->getPost('main_RT_CT_ID')];
        
            $sql = "SELECT RT_CT_ID, RT_CT_CODE, RT_CT_DESC, RT_CL_ID, RT_CT_DIS_SEQ, RT_CT_BEGIN_DT, RT_CT_END_DT 
                    FROM FLXY_RATE_CATEGORY 
                    WHERE RT_CT_ID=:SYSID:";
    
            $origRateCode = $this->Db->query($sql,$param)->getResultArray()[0];
            
            //echo json_encode($response);
            //echo json_encode(print_r($origRateCode)); exit;
    
            $no_of_added = 0;
            $submitted_fields = $this->request->getPost('group-a');
    
            if($submitted_fields != NULL)
            {
                foreach($submitted_fields as $submitted_field)
                {
                    if(!$this->checkRateCategory($submitted_field['RT_CT_CODE'])) // Check if entered Rate Category already exists
                    {
                        $newRateCode = [    "RT_CT_CODE" => trim($submitted_field["RT_CT_CODE"]),
                                            "RT_CT_DESC" => $origRateCode["RT_CT_DESC"],
                                            "RT_CT_DIS_SEQ" => '',
                                            "RT_CL_ID" => $origRateCode["RT_CL_ID"],
                                            "RT_CT_BEGIN_DT" => $origRateCode["RT_CT_BEGIN_DT"] != '' ? $origRateCode["RT_CT_BEGIN_DT"] : date('Y-m-d'),
                                            "RT_CT_END_DT" => $origRateCode["RT_CT_END_DT"] != '' ? $origRateCode["RT_CT_END_DT"] : '2030-12-31'
                                       ];
    
                        $this->Db->table('FLXY_RATE_CATEGORY')->insert($newRateCode); 
    
                        $no_of_added += $this->Db->affectedRows();
                    }
                   
                }
            }
    
            echo $no_of_added;
            exit;
            
            //echo json_encode(print_r($_POST)); exit;            
        }
        catch (Exception $e){
            return $this->respond($e->errors());
        }
    }
    
    public function rateCategoryList(){
        $search = $this->request->getPost('search');
        
        $sql = "SELECT RT_CT_CODE, RT_CT_DESC 
                FROM FLXY_RATE_CATEGORY 
                WHERE RT_CT_CODE LIKE '%$search%' 
                OR RT_CT_DESC LIKE '%$search%'";
    
        $response = $this->Db->query($sql)->getResultArray();
        
        $option='<option value="">Select Rate Category</option>';
        foreach($response as $row){
            $option .= '<option value="'.$row['RT_CT_CODE'].'">'.$row['RT_CT_CODE'].' | '.$row['RT_CT_DESC'].'</option>';
        }
        echo $option;
    }
    
    public function editRateCategory(){
        $_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);
        
        $param = ['SYSID'=> $this->request->getPost('sysid')];
        
        $sql = "SELECT RT_CT_ID, RT_CT_CODE, RT_CT_DESC, RT_CL_ID, RT_CT_DIS_SEQ, 
                FORMAT(RT_CT_BEGIN_DT, 'd-MMM-yyyy') as RT_CT_BEGIN_DT, FORMAT(RT_CT_END_DT, 'dd-MMM-yyyy') as RT_CT_END_DT 
                FROM FLXY_RATE_CATEGORY 
                WHERE RT_CT_ID=:SYSID:";
    
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }
    
    public function deleteRateCategory(){
        $sysid = $this->request->getPost('sysid');
    
        try{
            $return = $this->Db->table('FLXY_RATE_CATEGORY')->delete(['RT_CT_ID' => $sysid]); 
            $result = $return ? $this->responseJson("1","0",$return) : $this->responseJson("-402","Record not deleted");
            echo json_encode($result);
        }
        catch (Exception $e){
            return $this->respond($e->errors());
        }
    }


    /**************      Rate Codes Functions      ***************/

    public function rateCode(){

        $data['title'] = $this->getMethodName();

        return view('Reservation/RateCodeView', $data);
    }

    public function addRateCode(){

        $data['title'] = $this->getMethodName();        
        $data['js_to_load'] = array("rateCodeForm.js");

        return view('Reservation/RateCodeForm', $data);
    }

    public function editRateCode(){

        $data['title'] = $this->getMethodName();        
        $data['js_to_load'] = array("rateCodeForm.js");

        return view('Reservation/RateCodeForm', $data);
    }

}