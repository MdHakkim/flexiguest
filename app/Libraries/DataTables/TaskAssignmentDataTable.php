<?php 
namespace App\Libraries\DataTables;

class TaskAssignmentDataTable{
    public $Db;
    public function __construct(){
        $this->Db = \Config\Database::connect();
    }
    public function generate_DatatTable($table,$columns,$init_cond=array(),$formatby=','){
        $draw = $_POST['draw'];
        $row = $_POST['start'];
        // exit;
        $rowperpage = $_POST['length']; // Rows display per page

        $fields = explode(",",$columns);
        
        $columnIndex = isset($_POST['order']) ? $_POST['order'][0]['column'] : ''; // Column index
        $columnName  = isset($_POST['order']) ? $_POST['columns'][$columnIndex]['data'] : $fields[0]; // Column name
        $columnSortOrder = isset($_POST['order']) ? $_POST['order'][0]['dir'] : 'asc'; // asc or desc

        $searchValue = $_POST['search']['value']; // Search value

        ## Search 
        
        $table .= " WHERE 1=1";

        // Add initial conditions if specified
        if($init_cond != NULL)
        {
            foreach($init_cond as $fieldNameCond => $fieldValue)
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
        // exit;
        $empRecords = $this->Db->query($empQuery)->getResultArray();

        $return = [];
        $fields = explode("$formatby",$columns);
        foreach($empRecords as $key=>$row){
            $designArr=[];
            foreach($fields as $name){
                if($formatby!=','){
                    if(strpos($name, ')') !== false){
                        $extName = explode(")",$name);
                        $name=$extName[1];
                    }
                }

                // if($name == "STATUSCODE") {                    
                //     if($row[$name] != '' ){
                //        $department_ids = implode(',', json_decode($row[$name]));  
                //        //echo "select DEPT_DESC from FLXY_DEPARTMENT where DEPT_ID in ($department_ids)";                                
                //         $departments = $this->Db->query("select DEPT_DESC from FLXY_DEPARTMENT where DEPT_ID in ($department_ids)")->getResultArray();                        
                //         $row[$name] = '';
                //         if(!empty($departments)){
                //             foreach($departments as $department)
                //                 $row[$name] .= $department['DEPT_DESC'] . ', ';
                            
                //             $row[$name] = substr($row[$name], 0, 20). '...';
                //         }
                //     }
                // }


               $designArr[$name] = $row[$name];
            }
            $return[] = $designArr;
        }
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