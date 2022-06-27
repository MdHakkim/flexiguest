<?php

namespace App\Controllers;
use  App\Libraries\ServerSideDataTable;
use  App\Libraries\EmailLibrary;
use DateTime;
use DateTimeZone;

class UsersController extends BaseController
{
    public $Db;
    public $session;
    public $request;
    public $todayDate;
    public function __construct(){
        $this->Db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        helper(['form', 'common', 'custom']);
        $this->request = \Config\Services::request();
        $this->todayDate = new DateTime("now", new DateTimeZone('Asia/Dubai'));
    }

    
    /**************      Users Functions      ***************/

    public function Users()
    {
        $data['title'] = getMethodName();
        $data['session'] = $this->session;     
        
        //$data['js_to_load'] = array("app-user-list.js");
        return view('Users/UsersList', $data);
    }

    public function UsersList()  
    {
        $mine      = new ServerSideDataTable(); 
        $tableName = 'FLXY_USERS LEFT JOIN FLXY_USER_ROLE ON USR_ROLE = ROLE_ID LEFT JOIN FLXY_DEPARTMENT ON DEPT_ID = USER_DEPARTMENT LEFT JOIN FLXY_JOB_TITLE ON USER_JOB_TITLE = JOB_TITLE_ID';
        $columns = 'USR_NAME,ROLE_NAME,USER_FIRST_NAME,USER_LAST_NAME,JOB_TITLE_CODE,JOB_TITLE_DESC,USER_STATUS,DEPT_CODE,DEPT_DESC';   
        
        
        $mine->generate_DatatTable($tableName, $columns);
        exit;
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
        $columns = 'ROLE_ID,ROLE_NAME,ROLE_DESC,ROLE_STATUS';  
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function insertUserRole()
    {
        try {
            $sysid = $this->request->getPost('ROLE_ID');

            $validate = $this->validate([
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
                "ROLE_NAME" => trim($this->request->getPost('ROLE_NAME')),
                "ROLE_DESC" => trim($this->request->getPost('ROLE_DESC')),
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

        $sql = "SELECT ROLE_ID, ROLE_NAME, ROLE_DESC, ROLE_STATUS
                FROM FLXY_USER_ROLE
                WHERE ROLE_ID=:SYSID: ";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
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






    


       
}