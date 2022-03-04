<?php 
namespace App\Libraries;

class ServerSideDataTable{
    public $Db;
    public function __construct(){
        $this->Db = \Config\Database::connect();
    }
    public function generate_DatatTable($table,$columns){
        $draw = $_POST['draw'];
        $row = $_POST['start'];
        // exit;
        $rowperpage = $_POST['length']; // Rows display per page
        $columnIndex = $_POST['order'][0]['column']; // Column index
        $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
        $searchValue = $_POST['search']['value']; // Search value
        
        ## Search 
        $searchQuery = " ";
        if($searchValue != ''){
            $fields = explode(",",$columns);
            $joinQr = '';
            foreach($fields as $key=>$name){
                if ($key === array_key_last($fields)) {
                    $or="";
                }else{
                    $or=" or ";
                }
                $joinQr.="$name like '%".$searchValue."%' $or";
            }
            $searchQuery = " WHERE ($joinQr) ";
        }
        ## Total number of records without filtering
        $result = $this->Db->query("select count(*) as allcount from $table")->getResultArray();
        // $records = mysqli_fetch_assoc($sel);
       
        $totalRecords = $result[0]['allcount'];
        ## Total number of record with filtering
        $result1 = $this->Db->query("select count(*) as allcount from $table".$searchQuery)->getResultArray();
        // $records = mysqli_fetch_assoc($sel);
        $totalRecordwithFilter = $result1[0]['allcount'];
        ## Fetch records
        $empQuery="select $columns from $table".$searchQuery." order by ".$columnName." ".$columnSortOrder." OFFSET ".$row." ROWS FETCH NEXT ".$rowperpage." ROWS ONLY";
        // exit;
        $empRecords = $this->Db->query($empQuery)->getResultArray();

        $return = [];
        $fields = explode(",",$columns);
        foreach($empRecords as $key=>$row){
            $designArr=[];
            foreach($fields as $name){
               $designArr[$name] = $row[$name];
            }
            $return[]=$designArr;
            // $return[] = array( 
            //     "name_e"=>$row['name_e'],
            //     "email"=>$row['email'],
            //     "age"=>$row['age']
            // );
        }
        // print_r($return);
        // exit;
        ## Response
        $response = array(
          "draw" => intval($draw),
          "iTotalRecords" => $totalRecords,
          "iTotalDisplayRecords" => $totalRecordwithFilter,
          "aaData" => $return
        );
        
        echo json_encode($response);
    }
} 