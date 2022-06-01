<?php

namespace App\Libraries\DataTables;

class ConciergeRequestDataTable
{

    private $DB;

    public function __construct()
    {
        $this->DB = \Config\Database::connect();
    }

    public function generate_DatatTable()
    {
        $table = 'FLXY_CONCIERGE_REQUESTS';

        $draw = $_POST['draw'];
        $row = $_POST['start'];

        $rowperpage = $_POST['length']; // Rows display per page

        $columns = [
            'CR_ID', 
            'CR_STATUS', 
            'CO_TITLE', 
            'CR_GUEST_NAME', 
            'CR_GUEST_PHONE', 
            'CR_GUEST_EMAIL', 
            'CR_GUEST_ROOM', 
            'CR_QUANTITY', 
            'CR_TOTAL_AMOUNT', 
            'CR_TAX_AMOUNT', 
            'CR_NET_AMOUNT', 
            'CR_REMARKS',
            'CR_CREATED_AT',
        ];

        $columnIndex = isset($_POST['order']) ? $_POST['order'][0]['column'] : ''; // Column index
        $columnName  = isset($_POST['order']) ? $columns[$columnIndex] : $columns[0]; // Column name
        $columnSortOrder = isset($_POST['order']) ? $_POST['order'][0]['dir'] : 'asc'; // asc or desc

        // Search value
        $searchQuery = 'WHERE 1 = 1';
        $searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
        if ($searchValue != '') {
            $searchQuery .= " AND (CR_STATUS like '%$searchValue%' 
                                    or CO_TITLE like '%$searchValue%' 
                                    or CR_GUEST_NAME like '%$searchValue%' 
                                    or CR_GUEST_PHONE like '%$searchValue%' 
                                    or CR_GUEST_EMAIL like '%$searchValue%' 
                                    or CR_GUEST_ROOM like '%$searchValue%' 
                                    or CR_QUANTITY like '%$searchValue%' 
                                    or CR_TOTAL_AMOUNT like '%$searchValue%' 
                                    or CR_TAX_AMOUNT like '%$searchValue%' 
                                    or CR_NET_AMOUNT like '%$searchValue%' 
                                    or CR_REMARKS like '%$searchValue%' 
                                    or cast(CR_CREATED_AT as date) like '%$searchValue%'
                                    )";
        }

        ## Total number of records without filtering
        $result = $this->DB->query("select count(*) as allcount from $table")->getResultArray();
        $totalRecords = $result[0]['allcount'];

        ## Total number of record with filtering
        $result1 = $this->DB->query("select count(*) as allcount from $table $searchQuery")->getResultArray();
        $totalRecordwithFilter = $result1[0]['allcount'];

        ## Fetch records
        $query = "select $table.*, co.CO_TITLE, rm.RM_NO from $table 
                    inner join FLXY_CONCIERGE_OFFERS as co on $table.CR_OFFER_ID = co.CO_ID 
                    inner join FLXY_ROOM as rm on $table.CR_GUEST_ROOM_ID = rm.RM_ID
                    $searchQuery order by " . $columnName . " " . $columnSortOrder . " OFFSET " . $row . " ROWS FETCH NEXT " . $rowperpage . " ROWS ONLY";
        $records = $this->DB->query($query)->getResultArray();
        
        $return = $records;
        // foreach ($records as $row) {
            // $return[] = [
            // ];
        // }
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
