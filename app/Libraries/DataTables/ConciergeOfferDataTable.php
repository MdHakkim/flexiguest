<?php

namespace App\Libraries\DataTables;

class ConciergeOfferDataTable
{

    private $DB;

    public function __construct()
    {
        $this->DB = \Config\Database::connect();
    }

    public function generate_DatatTable()
    {
        $table = 'FLXY_CONCIERGE_OFFERS';

        $draw = $_POST['draw'];
        $row = $_POST['start'];

        $rowperpage = $_POST['length']; // Rows display per page

        $columns = [
            'CO_ID', 
            'CO_STATUS', 
            'CO_TITLE', 
            'CO_DESCRIPTION',
            'CO_COVER_IMAGE', 
            'CO_VALID_FROM_DATE',
            'CO_VALID_TO_DATE',
            'CO_OFFER_PRICE',
            'CO_ACTUAL_PRICE',
            'CO_PROVIDER_TITLE',
            'CO_PROVIDER_LOGO',
            'CO_CREATED_AT'
        ];

        $columnIndex = isset($_POST['order']) ? $_POST['order'][0]['column'] : ''; // Column index
        $columnName  = isset($_POST['order']) ? $columns[$columnIndex] : $columns[0]; // Column name
        $columnSortOrder = isset($_POST['order']) ? $_POST['order'][0]['dir'] : 'asc'; // asc or desc

        // Search value
        $searchQuery = 'WHERE 1 = 1';
        $searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
        if ($searchValue != '') {
            $searchQuery .= " AND (CO_TITLE like '%$searchValue%' 
                                    or CO_DESCRIPTION like '%$searchValue%' 
                                    or cast(CO_VALID_FROM_DATE as date) like '%$searchValue%')
                                    or cast(CO_VALID_TO_DATE as date) like '%$searchValue%'
                                    or CO_ACTUAL_PRICE like '%$searchValue%' 
                                    or CO_PROVIDER_TITLE like '%$searchValue%'
                                    or CO_STATUS like '%$searchValue%')
                                    or cast(CO_CREATED_AT as datetime) like '%$searchValue%'";
        }

        ## Total number of records without filtering
        $result = $this->DB->query("select count(*) as allcount from $table")->getResultArray();
        $totalRecords = $result[0]['allcount'];

        ## Total number of record with filtering
        $result1 = $this->DB->query("select count(*) as allcount from $table $searchQuery")->getResultArray();
        $totalRecordwithFilter = $result1[0]['allcount'];

        ## Fetch records
        $query = "select $table.*, c.CUR_ID, c.CUR_DESC as CURRENCY, c.CUR_CODE from $table inner join FLXY_CURRENCY as c on $table.CO_CURRENCY_ID = c.CUR_ID $searchQuery order by " . $columnName . " " . $columnSortOrder . " OFFSET " . $row . " ROWS FETCH NEXT " . $rowperpage . " ROWS ONLY";
        $records = $this->DB->query($query)->getResultArray();
        
        $return = $records;

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
