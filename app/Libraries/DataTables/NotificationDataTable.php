<?php 
namespace App\Libraries\DataTables;

class NotificationDataTable{
    public $Db;
    public function __construct(){
        $this->Db = \Config\Database::connect();
    }
    public function generate_DataTable($table,$columns,$init_cond=array(),$formatby=','){
        $draw = $_POST['draw'];
        $row = $_POST['start'];
        $rowperpage = $_POST['length']; // Rows display per page

        $fields = explode(",",$columns);
        
        $columnIndex     = isset($_POST['order']) ? $_POST['order'][0]['column'] : ''; // Column index
        $columnName      = isset($_POST['order']) ? $_POST['columns'][$columnIndex]['data'] : $fields[0]; // Column name
        $columnSortOrder = isset($_POST['order']) ? $_POST['order'][0]['dir'] : 'asc'; // asc or desc

        $searchValue = $_POST['search']['value']; // Search value

        ## Search 
        
        $table .= " WHERE 1=1";
       //echo $columns;

        // Add initial conditions if specified
        if($init_cond != NULL)
        {
            foreach($init_cond as $fieldNameCond => $fieldValue)
                if(in_array($fieldNameCond, ['NOTIFICATION_DEPARTMENT', 'NOTIFICATION_RESERVATION_ID']))
                    $table .= " AND " . $fieldValue."";
                else 
                    $table .= " AND " . $fieldNameCond . " " . $fieldValue."";
        }

        $searchQuery = "";

        if($searchValue != ''){
			$fields = explode("$formatby",$columns);          
            $joinQr = '';

            foreach($fields as $key=>$name){
                if ($key === array_key_last($fields)) {
                    $or="";
                }else{
                    $or=" or ";
                }
                if( strpos($name, ")") !== false ) {
                    $name = explode(")",$name);   
                    $name=$name[0].')';
                }
                $joinQr.="$name like '%".$searchValue."%' $or";
            }
            $searchQuery = " AND ($joinQr) ";
        }
        ## Total number of records without filtering
        $result = $this->Db->query("SELECT COUNT(*) AS allcount FROM $table")->getResultArray();
        // $records = mysqli_fetch_assoc($sel);
       
        $totalRecords = $result[0]['allcount'];
        ## Total number of record with filtering
        // echo "SELECT COUNT(*) AS allcount FROM $table".$searchQuery;exit;
        $result1 = $this->Db->query("SELECT COUNT(*) AS allcount FROM $table".$searchQuery)->getResultArray();
        $totalRecordwithFilter = $result1[0]['allcount'];
        // print_r($totalRecordwithFilter);exit;
        ## Fetch records
        $formatColumns = str_replace("|",",",$columns);
        $empQuery="SELECT $formatColumns FROM $table".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." OFFSET ".$row." ROWS FETCH NEXT ".$rowperpage." ROWS ONLY";
      
        $empRecords = $this->Db->query($empQuery)->getResultArray();

        $return = [];
        $fields = explode("$formatby",$columns);
        foreach($empRecords as $key=>$row){
            $designArr=[];
            foreach($fields as $name){
                if($formatby!=','){
                    if(strpos($name, ')') !== false){
                        $extName = explode(")",$name);
                        $name = $extName[1];
                    }
                }

                if($name == "NOTIFICATION_DEPARTMENT") {                    
                    if($row[$name] != '' ){
                       $department_ids = implode(',', json_decode($row[$name]));  
                       //echo "select DEPT_DESC from FLXY_DEPARTMENT where DEPT_ID in ($department_ids)";                                
                        $departments = $this->Db->query("select DEPT_DESC from FLXY_DEPARTMENT where DEPT_ID in ($department_ids)")->getResultArray();                        
                        $row[$name] = '';
                        if(!empty($departments)){
                            foreach($departments as $department)
                                $row[$name] .= $department['DEPT_DESC'] . ', ';
                            
                            $row[$name] = substr($row[$name], 0, 20). '...';
                        }
                    }
                }

                if($name == "NOTIFICATION_TO_ID") {
                    if($row[$name] != ''){                                        
                        $user_ids = implode(',', json_decode($row[$name]));                                        
                        $users = $this->Db->query("select concat(USR_FIRST_NAME, ' ', USR_LAST_NAME) as NAME from FlXY_USERS where USR_ID in ($user_ids)")->getResultArray();                       
                        
                        $row[$name] = '';
                        if(!empty($users)){
                            foreach($users as $user)
                                $row[$name] .= $user['NAME'] . ', ';

                            $row[$name] = substr($row[$name], 0, 20). '...';
                        }
                    }
                }

                if($name == "RSV_ID") {
                    if($row[$name] != ''){                                        
                        $reservation = $this->Db->query("select RESV_NO FROM FLXY_RESERVATION where RESV_ID = $row[$name]")->getRow()->RESV_NO;            
                        
                        $row[$name] = '';
                        if(!empty($reservation)){                             

                            $row[$name] = $reservation;
                        }
                    }
                }

                if($name == "NOTIFICATION_GUEST_ID") {
                    if($row[$name] != ''){                                        
                        $guest_ids = implode(',', json_decode($row[$name]));                                        
                        $reservationsGuests = $this->Db->query("select CONCAT_WS(' ', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME) AS FULLNAME FROM FLXY_CUSTOMER where CUST_ID in ($guest_ids)")->getResultArray();                       
                        
                        $row[$name] = '';
                        if(!empty($guest_ids)){
                            foreach($reservationsGuests as $reservation)
                                $row[$name] .= $reservation['FULLNAME'] . (count($reservationsGuests) > 1 ? ',':'');

                            $row[$name] = substr($row[$name], 0, 20). '...';
                        }
                    }
                }

                
               

                if($name == "RSV_TRACE_RESOLVED_BY") {
                    if($row[$name] != '' ){                        
                        $RESOLVED_BY = $this->Db->query("select concat(USR_FIRST_NAME, ' ', USR_LAST_NAME) as NAME from FlXY_USERS where USR_ID = '".$row[$name]."'")->getResultArray(); 
                        $row[$name] = '';
                        if(!empty($RESOLVED_BY)){
                            foreach($RESOLVED_BY as $user)
                                $row[$name] = $user['NAME'];                            
                        }             
                     }
                }


               $designArr[$name] = $row[$name];
               
            }
            
            $return[]=$designArr;
        }
        ##print_r($return);
        ## Response
        $response = array(
          "draw" => intval($draw),
          "iTotalRecords" => $totalRecords,
          "iTotalDisplayRecords" => $totalRecordwithFilter,
          "aaData" => $return,
          "query" => $empQuery
        );
        echo json_encode($response);
    }
} 