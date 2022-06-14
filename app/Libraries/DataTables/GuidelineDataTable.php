<?php 
namespace App\Libraries\DataTables;

class GuidelineDataTable{

    private $DB;

    public function __construct(){
        $this->DB = \Config\Database::connect();
    }

    public function generate_DatatTable(){
        $table = 'FLXY_GUIDELINES';

        $draw = $_POST['draw'];
        $row = $_POST['start'];

        $rowperpage = $_POST['length']; // Rows display per page

        $columns = ['GL_ID', 'GL_TITLE', 'GL_COVER_IMAGE', 'GL_DESCRIPTION', 'GL_BODY', 'GL_CREATED_AT'];
        
        $columnIndex = isset($_POST['order']) ? $_POST['order'][0]['column'] : ''; // Column index
        $columnName  = isset($_POST['order']) ? $columns[$columnIndex] : $columns[0]; // Column name
        $columnSortOrder = isset($_POST['order']) ? $_POST['order'][0]['dir'] : 'asc'; // asc or desc

        // Search value
        $searchQuery = 'WHERE 1 = 1';
        $searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
        if($searchValue != ''){
            $searchQuery .= " AND (GL_TITLE like '%$searchValue%' or GL_DESCRIPTION like '%$searchValue%' or GL_BODY like '%$searchValue%' or cast(GL_CREATED_AT as date) like '%$searchValue%')";
        }

        ## Total number of records without filtering
        $result = $this->DB->query("select count(*) as allcount from $table")->getResultArray();       
        $totalRecords = $result[0]['allcount'];

        ## Total number of record with filtering
        $result1 = $this->DB->query("select count(*) as allcount from $table $searchQuery")->getResultArray();
        $totalRecordwithFilter = $result1[0]['allcount'];
        
        ## Fetch records
        $query = "select * from $table $searchQuery order by ".$columnName." ".$columnSortOrder." OFFSET ".$row." ROWS FETCH NEXT ".$rowperpage." ROWS ONLY";
        $records = $this->DB->query($query)->getResultArray();

        $return = [];
        foreach($records as $row){
            $guideline_files = $this->DB->query("select * from FLXY_GUIDELINE_FILES where GLF_GUIDELINE_ID = {$row['GL_ID']}")->getResultArray();

            $return[] = [
                'GL_ID' => $row['GL_ID'],
                'GL_TITLE' => $row['GL_TITLE'],
                'GL_COVER_IMAGE' => $row['GL_COVER_IMAGE'],
                'GL_DESCRIPTION' => $row['GL_DESCRIPTION'],
                'GL_BODY' => $row['GL_BODY'],
                'GL_CREATED_AT' => $row['GL_CREATED_AT'],
                'GUIDELINE_FILES' => $guideline_files
            ];
        }
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