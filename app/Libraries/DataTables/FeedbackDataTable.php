<?php

namespace App\Libraries\DataTables;

class FeedbackDataTable
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

            $module = 'General';
            $reservation_id = $room_no = '';

            if (empty($designArr['FB_MODEL'])) {
            } else if ($designArr['FB_MODEL'] == 'FLXY_RESERVATION') {
                $module = 'Reservation';

                $sql = "select * from FLXY_RESERVATION left join FLXY_ROOM on RESV_ROOM_ID = RM_ID where RESV_ID = " . $designArr['FB_MODEL_ID'];
                $row = $this->Db->query($sql)->getRowArray();
                if (!empty($row)) {
                    $reservation_id = $row['RESV_ID'];
                    $room_no = $row['RM_NO'];
                }
            } else if ($designArr['FB_MODEL'] == 'FLXY_EVALET') {
                $module = 'E-Valet';

                $sql = "select * from FLXY_EVALET left join FLXY_RESERVATION on EV_RESERVATION_ID = RESV_ID left join FLXY_ROOM on RESV_ROOM_ID = RM_ID where EV_ID = " . $designArr['FB_MODEL_ID'];
                $row = $this->Db->query($sql)->getRowArray();
                if (!empty($row)) {
                    $reservation_id = $row['RESV_ID'];
                    $room_no = $row['RM_NO'];
                }
            }

            $designArr['FB_MODULE'] = $module;
            $designArr['FB_RESERVATION'] = $reservation_id;
            $designArr['FB_ROOM'] = $room_no;

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
