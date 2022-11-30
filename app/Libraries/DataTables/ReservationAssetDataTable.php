<?php

namespace App\Libraries\DataTables;

class ReservationAssetDataTable
{
    public $Db;
    public function __construct()
    {
        $this->Db = \Config\Database::connect();
    }
    public function generate_DatatTable($table, $columns, $init_cond = array(), $formatby = ',')
    {
        $draw = $_POST['draw'];
        $row = $_POST['start'];
        // exit;
        $rowperpage = $_POST['length']; // Rows display per page

        $fields = explode(",", $columns);

        $columnIndex = isset($_POST['order']) ? $_POST['order'][0]['column'] : ''; // Column index
        $columnName  = isset($_POST['order']) ? $_POST['columns'][$columnIndex]['data'] : $fields[0]; // Column name
        $columnSortOrder = isset($_POST['order']) ? $_POST['order'][0]['dir'] : 'asc'; // asc or desc

        $searchValue = $_POST['search']['value']; // Search value

        ## Search 

        $table .= " WHERE 1=1";

        // Add initial conditions if specified
        if ($init_cond != NULL) {
            foreach ($init_cond as $fieldNameCond => $fieldValue)
                $table .= " AND " . $fieldNameCond . " " . $fieldValue . "";
        }

        $searchQuery = "";

        if ($searchValue != '') {
            $fields = explode("$formatby", $columns);
            $joinQr = '';

            foreach ($fields as $key => $name) {
                if ($key === array_key_last($fields)) {
                    $or = "";
                } else {
                    $or = " or ";
                }
                if (strpos($name, ")") !== false) {
                    $name = explode(")", $name);
                    $name = $name[0] . ')';
                }
                $joinQr .= "$name like '%" . $searchValue . "%' $or";
            }
            $searchQuery = " AND ($joinQr) ";
        }

        $table .= "  group by RRA_RESERVATION_ID, RRA_ROOM_ID, RRA_CREATED_AT, RM_NO";
        ## Total number of records without filtering
        $result = $this->Db->query("SELECT COUNT(*) AS allcount FROM $table")->getResultArray();
        // $records = mysqli_fetch_assoc($sel);

        $totalRecords = $result[0]['allcount'];
        ## Total number of record with filtering
        // echo "SELECT COUNT(*) AS allcount FROM $table".$searchQuery;exit;
        $result1 = $this->Db->query("SELECT COUNT(*) AS allcount FROM $table" . $searchQuery)->getResultArray();
        $totalRecordwithFilter = $result1[0]['allcount'];
        // print_r($totalRecordwithFilter);exit;
        ## Fetch records
        $formatColumns = str_replace("|", ",", $columns);
        $empQuery = "SELECT $formatColumns FROM $table" . $searchQuery . " ORDER BY " . $columnName . " " . $columnSortOrder . " OFFSET " . $row . " ROWS FETCH NEXT " . $rowperpage . " ROWS ONLY";
        // exit;
        $empRecords = $this->Db->query($empQuery)->getResultArray();

        $return = [];
        $fields = explode("$formatby", $columns);
        foreach ($empRecords as $key => $row) {
            $designArr = [];
            foreach ($fields as $name) {
                if ($formatby != ',') {
                    if (strpos($name, ')') !== false) {
                        $extName = explode(")", $name);
                        $name = $extName[1];
                    }
                }
                $designArr[$name] = $row[$name];
            }

            if (!empty($designArr['RRA_RESERVATION_ID']) && !empty($designArr['RRA_ROOM_ID'])) {
                $designArr['assets'] = [];
                
                $sql = "select * from FLXY_RESERVATION_ROOM_ASSETS left join FLXY_ASSETS on RRA_ASSET_ID = AS_ID where RRA_RESERVATION_ID = " . $designArr['RRA_RESERVATION_ID'] . " and RRA_ROOM_ID = " . $designArr['RRA_ROOM_ID'];
                $assets = $this->Db->query($sql)->getResultArray();
                foreach ($assets as $index => $asset) {
                    $designArr['assets'][] = [
                        'id' => $asset['RRA_ID'],
                        'asset' => $asset['AS_ASSET'],
                        'quantity' => $asset['RRA_QUANTITY'],
                        'status' => $asset['RRA_STATUS'],
                        'remarks' => $asset['RRA_REMARKS'],
                        'tracking_remarks' => $asset['RRA_TRACKING_REMARKS'],
                    ];
                }

                $designArr['assets'] = json_encode($designArr['assets']);
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
