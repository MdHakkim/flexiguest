<?php

namespace App\Libraries\DataTables;

class ConciergeOffersDataTable
{

    private $DB;

    public function __construct()
    {
        $this->DB = \Config\Database::connect();
    }

    public function generate_DatatTable()
    {
        $table = 'concierge_offers';

        $draw = $_POST['draw'];
        $row = $_POST['start'];

        $rowperpage = $_POST['length']; // Rows display per page

        $columns = [
            'id', 
            'status', 
            'title', 
            'description',
            'cover_image', 
            'valid_from_date',
            'valid_to_date',
            'actual_price',
            'provider_title',
            'provider_logo',
        ];

        $columnIndex = isset($_POST['order']) ? $_POST['order'][0]['column'] : ''; // Column index
        $columnName  = isset($_POST['order']) ? $columns[$columnIndex] : $columns[0]; // Column name
        $columnSortOrder = isset($_POST['order']) ? $_POST['order'][0]['dir'] : 'asc'; // asc or desc

        // Search value
        $searchQuery = 'WHERE 1 = 1';
        $searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
        if ($searchValue != '') {
            $searchQuery .= " AND (title like '%$searchValue%' or description like '%$searchValue%' or body like '%$searchValue%' or cast(created_at as date) like '%$searchValue%')";
        }

        ## Total number of records without filtering
        $result = $this->DB->query("select count(*) as allcount from $table")->getResultArray();
        $totalRecords = $result[0]['allcount'];

        ## Total number of record with filtering
        $result1 = $this->DB->query("select count(*) as allcount from $table $searchQuery")->getResultArray();
        $totalRecordwithFilter = $result1[0]['allcount'];

        ## Fetch records
        $query = "select $table.*, c.CUR_ID, c.CUR_DESC as currency, c.CUR_CODE as currency_code from $table inner join FLXY_CURRENCY as c on $table.currency_id = c.CUR_ID $searchQuery order by " . $columnName . " " . $columnSortOrder . " OFFSET " . $row . " ROWS FETCH NEXT " . $rowperpage . " ROWS ONLY";
        $records = $this->DB->query($query)->getResultArray();

        $return = [];
        foreach ($records as $row) {
            $return[] = [
                'id' => $row['id'],
                'status' => $row['status'],
                'title' => $row['title'],
                'description' => $row['description'],
                'cover_image' => $row['cover_image'],
                'valid_from_date' => $row['valid_from_date'],
                'valid_to_date' => $row['valid_to_date'],
                'actual_price' => $row['actual_price'],
                'currency_code' => $row['currency_code'],
                'provider_title' => $row['provider_title'],
                'provider_logo' => $row['provider_logo'],
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
