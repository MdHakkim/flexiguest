<?php

namespace App\Controllers;

use App\Libraries\ServerSideDataTable;

class MastersController extends BaseController
{
    public $Db;
    public $request;
    public $session;

    public function __construct()
    {
        $this->Db = \Config\Database::connect();
        $this->request = \Config\Services::request();
        $this->session = \Config\Services::session();
        helper(['form', 'url', 'custom', 'common']);
    }

    /**************      Rate Class Functions      ***************/

    public function rateClass()
    {
        $data['title'] = getMethodName();
        $data['session'] = $this->session;
        
        return view('Reservation/RateClassView', $data);
    }

    public function RateClassView()
    {
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_RATE_CLASS_VIEW';
        $columns = 'RT_CL_ID,RT_CL_CODE,RT_CL_DESC,RT_CL_BEGIN_DT,RT_CL_END_DT,RT_CL_DIS_SEQ,ASSOC_CATS';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function insertRateClass()
    {
        try {
            $sysid = $this->request->getPost('RT_CL_ID');

            $validate = $this->validate([
                'RT_CL_CODE' => ['label' => 'Rate Class Code', 'rules' => 'required|is_unique[FLXY_RATE_CLASS.RT_CL_CODE,RT_CL_ID,' . $sysid . ']'],
                'RT_CL_DESC' => ['label' => 'Rate Class Description', 'rules' => 'required'],
                'RT_CL_DIS_SEQ' => ['label' => 'Display Sequence', 'rules' => 'permit_empty|greater_than_equal_to[0]'],
                'RT_CL_BEGIN_DT' => ['label' => 'Begin Date', 'rules' => 'required'],
                'RT_CL_END_DT' => ['label' => 'End Date', 'rules' => 'required|compareDate', 'errors' => ['compareDate' => 'The End Date should be after Begin Date']],
            ]);
            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }

            //echo json_encode(print_r($_POST));
            //exit;

            $data = [
                "RT_CL_CODE" => trim($this->request->getPost('RT_CL_CODE')),
                "RT_CL_DESC" => trim($this->request->getPost('RT_CL_DESC')),
                "RT_CL_DIS_SEQ" => trim($this->request->getPost('RT_CL_DIS_SEQ')) != '' ? trim($this->request->getPost('RT_CL_DIS_SEQ')) : '',
                "RT_CL_BEGIN_DT" => trim($this->request->getPost('RT_CL_BEGIN_DT')),
                "RT_CL_END_DT" => trim($this->request->getPost('RT_CL_END_DT')) != '' ? trim($this->request->getPost('RT_CL_END_DT')) : '2030-12-31',
            ];

            $return = !empty($sysid) ? $this->Db->table('FLXY_RATE_CLASS')->where('RT_CL_ID', $sysid)->update($data) : $this->Db->table('FLXY_RATE_CLASS')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function checkRateClass($rcCode)
    {
        $sql = "SELECT RT_CL_ID
                FROM FLXY_RATE_CLASS
                WHERE RT_CL_CODE = '" . $rcCode . "'";

        $response = $this->Db->query($sql)->getNumRows();
        return $rcCode == '' || strlen($rcCode) > 10 ? 1 : $response; // Send found row even if submitted code is empty
    }

    public function copyRateClass()
    {
        try {
            $param = ['SYSID' => $this->request->getPost('main_RT_CL_ID')];

            $sql = "SELECT RT_CL_ID, RT_CL_CODE, RT_CL_DESC, RT_CL_DIS_SEQ, RT_CL_BEGIN_DT, RT_CL_END_DT
                    FROM FLXY_RATE_CLASS
                    WHERE RT_CL_ID=:SYSID:";

            $origRateCode = $this->Db->query($sql, $param)->getResultArray()[0];

            //echo json_encode($response);
            //echo json_encode(print_r($origRateCode)); exit;

            $no_of_added = 0;
            $submitted_fields = $this->request->getPost('group-a');

            if ($submitted_fields != null) {
                foreach ($submitted_fields as $submitted_field) {
                    if (!$this->checkRateClass($submitted_field['RT_CL_CODE'])) // Check if entered Rate Class already exists
                    {
                        $newRateCode = [
                            "RT_CL_CODE" => trim($submitted_field["RT_CL_CODE"]),
                            "RT_CL_DESC" => $origRateCode["RT_CL_DESC"],
                            "RT_CL_DIS_SEQ" => '',
                            "RT_CL_BEGIN_DT" => $origRateCode["RT_CL_BEGIN_DT"] != '' ? $origRateCode["RT_CL_BEGIN_DT"] : date('Y-m-d'),
                            "RT_CL_END_DT" => $origRateCode["RT_CL_END_DT"] != '' ? $origRateCode["RT_CL_END_DT"] : '2030-12-31',
                        ];

                        $this->Db->table('FLXY_RATE_CLASS')->insert($newRateCode);

                        $no_of_added += $this->Db->affectedRows();
                    }
                }
            }

            echo $no_of_added;
            exit;

            //echo json_encode(print_r($_POST)); exit;
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function rateClassList()
    {
        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

        $sql = "SELECT RT_CL_ID, RT_CL_CODE, RT_CL_DESC
                FROM FLXY_RATE_CLASS";

        if ($search != '') {
            $sql .= " WHERE RT_CL_CODE LIKE '%$search%'
                      OR RT_CL_DESC LIKE '%$search%'";
        }

        $response = $this->Db->query($sql)->getResultArray();

        $option = '<option value="">Choose an Option</option>';
        foreach ($response as $row) {
            $option .= '<option value="' . $row['RT_CL_ID'] . '">' . $row['RT_CL_CODE'] . ' | ' . $row['RT_CL_DESC'] . '</option>';
        }

        return $option;
    }

    public function editRateClass()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];

        $sql = "SELECT RT_CL_ID, RT_CL_CODE, RT_CL_DESC, RT_CL_DIS_SEQ,
                FORMAT(RT_CL_BEGIN_DT, 'dd-MMM-yyyy') as RT_CL_BEGIN_DT, FORMAT(RT_CL_END_DT, 'dd-MMM-yyyy') as RT_CL_END_DT
                FROM FLXY_RATE_CLASS
                WHERE RT_CL_ID=:SYSID:";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteRateClass()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            $return = $this->Db->table('FLXY_RATE_CLASS')->delete(['RT_CL_ID' => $sysid]);
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    /**************      Rate Category Functions      ***************/

    public function rateCategory()
    {

        $rateClassOptions = $this->rateClassList();

        $data = ['rateClassOptions' => $rateClassOptions];
        $data['title'] = getMethodName();
        $data['session'] = $this->session;
        
        return view('Reservation/RateCategoryView', $data);
    }

    public function RateCategoryView()
    {
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_RATE_CATEGORY LEFT JOIN FLXY_RATE_CLASS FRC ON FRC.RT_CL_ID = FLXY_RATE_CATEGORY.RT_CL_ID';
        $columns = 'RT_CT_ID,RT_CT_CODE,RT_CT_DESC,RT_CL_CODE,RT_CT_BEGIN_DT,RT_CT_END_DT,RT_CT_DIS_SEQ';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function insertRateCategory()
    {
        try {

            $_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);

            $sysid = $this->request->getPost('RT_CT_ID');

            $validate = $this->validate([
                'RT_CT_CODE' => ['label' => 'Rate Category Code', 'rules' => 'required|is_unique[FLXY_RATE_CATEGORY.RT_CT_CODE,RT_CT_ID,' . $sysid . ']'],
                'RT_CT_DESC' => ['label' => 'Rate Category Description', 'rules' => 'required'],
                'RT_CL_ID' => ['label' => 'Rate Class Code', 'rules' => 'required'],
                'RT_CT_DIS_SEQ' => ['label' => 'Display Sequence', 'rules' => 'permit_empty|greater_than_equal_to[0]'],
                'RT_CT_BEGIN_DT' => ['label' => 'Begin Date', 'rules' => 'required'],
                'RT_CT_END_DT' => ['label' => 'End Date', 'rules' => 'required|compareDate', 'errors' => ['compareDate' => 'The End Date should be after Begin Date']],
            ]);
            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }

            //echo json_encode(print_r($_POST)); exit;

            $data = [
                "RT_CT_CODE" => trim($this->request->getPost('RT_CT_CODE')),
                "RT_CT_DESC" => trim($this->request->getPost('RT_CT_DESC')),
                "RT_CL_ID" => trim($this->request->getPost('RT_CL_ID')),
                "RT_CT_DIS_SEQ" => trim($this->request->getPost('RT_CT_DIS_SEQ')) != '' ? trim($this->request->getPost('RT_CT_DIS_SEQ')) : '',
                "RT_CT_BEGIN_DT" => trim($this->request->getPost('RT_CT_BEGIN_DT')),
                "RT_CT_END_DT" => trim($this->request->getPost('RT_CT_END_DT')) != '' ? trim($this->request->getPost('RT_CT_END_DT')) : '2030-12-31',
            ];

            $return = !empty($sysid) ? $this->Db->table('FLXY_RATE_CATEGORY')->where('RT_CT_ID', $sysid)->update($data) : $this->Db->table('FLXY_RATE_CATEGORY')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function checkRateCategory($rcCode)
    {
        $sql = "SELECT RT_CT_ID
                FROM FLXY_RATE_CATEGORY
                WHERE RT_CT_CODE = '" . $rcCode . "'";

        $response = $this->Db->query($sql)->getNumRows();
        return $rcCode == '' || strlen($rcCode) > 10 ? 1 : $response; // Send found row even if submitted code is empty
    }

    public function copyRateCategory()
    {
        try {
            $_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);

            $param = ['SYSID' => $this->request->getPost('main_RT_CT_ID')];

            $sql = "SELECT RT_CT_ID, RT_CT_CODE, RT_CT_DESC, RT_CL_ID, RT_CT_DIS_SEQ, RT_CT_BEGIN_DT, RT_CT_END_DT
                    FROM FLXY_RATE_CATEGORY
                    WHERE RT_CT_ID=:SYSID:";

            $origRateCode = $this->Db->query($sql, $param)->getResultArray()[0];

            //echo json_encode($response);
            //echo json_encode(print_r($origRateCode)); exit;

            $no_of_added = 0;
            $submitted_fields = $this->request->getPost('group-a');

            if ($submitted_fields != null) {
                foreach ($submitted_fields as $submitted_field) {
                    if (!$this->checkRateCategory($submitted_field['RT_CT_CODE'])) // Check if entered Rate Category already exists
                    {
                        $newRateCode = [
                            "RT_CT_CODE" => trim($submitted_field["RT_CT_CODE"]),
                            "RT_CT_DESC" => $origRateCode["RT_CT_DESC"],
                            "RT_CT_DIS_SEQ" => '',
                            "RT_CL_ID" => $origRateCode["RT_CL_ID"],
                            "RT_CT_BEGIN_DT" => $origRateCode["RT_CT_BEGIN_DT"] != '' ? $origRateCode["RT_CT_BEGIN_DT"] : date('Y-m-d'),
                            "RT_CT_END_DT" => $origRateCode["RT_CT_END_DT"] != '' ? $origRateCode["RT_CT_END_DT"] : '2030-12-31',
                        ];

                        $this->Db->table('FLXY_RATE_CATEGORY')->insert($newRateCode);

                        $no_of_added += $this->Db->affectedRows();
                    }
                }
            }

            echo $no_of_added;
            exit;

            //echo json_encode(print_r($_POST)); exit;
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function rateCategoryList()
    {
        $search = $this->request->getPost('search');

        $sql = "SELECT  RT_CT_ID, RTRIM(LTRIM(REPLACE(REPLACE(REPLACE(RT_CT_CODE, CHAR(9), ' '), CHAR(10), ' '), CHAR(13), ' '))) AS RT_CT_CODE,
                        RT_CT_DESC, RTRIM(LTRIM(REPLACE(REPLACE(REPLACE(RT_CL_CODE, CHAR(9), ' '), CHAR(10), ' '), CHAR(13), ' '))) AS RT_CL_CODE,
                        RT_CT_BEGIN_DT, RT_CT_END_DT

                FROM FLXY_RATE_CATEGORY
                LEFT JOIN FLXY_RATE_CLASS FRC ON FRC.RT_CL_ID = FLXY_RATE_CATEGORY.RT_CL_ID
                WHERE 1=1";
                
        if (trim($search) != '')
            $sql .= "RT_CT_CODE LIKE '%$search%' OR RT_CT_DESC LIKE '%$search%'";

        $response = $this->Db->query($sql)->getResultArray();

        $options = array();

        foreach ($response as $row) {
            $options[] = array("value" => $row['RT_CT_ID'], "name" => $row['RT_CT_CODE'], "desc" => $row['RT_CT_DESC'],
                "rt_class" => $row['RT_CL_CODE'], "begin_date" => $row['RT_CT_BEGIN_DT'], "end_date" => $row['RT_CT_END_DT']);
        }

        return $options;
    }

    public function editRateCategory()
    {
        $_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);

        $param = ['SYSID' => $this->request->getPost('sysid')];

        $sql = "SELECT RT_CT_ID, RT_CT_CODE, RT_CT_DESC, RT_CL_ID, RT_CT_DIS_SEQ,
                FORMAT(RT_CT_BEGIN_DT, 'dd-MMM-yyyy') as RT_CT_BEGIN_DT, FORMAT(RT_CT_END_DT, 'dd-MMM-yyyy') as RT_CT_END_DT
                FROM FLXY_RATE_CATEGORY
                WHERE RT_CT_ID=:SYSID:";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteRateCategory()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            $return = $this->Db->table('FLXY_RATE_CATEGORY')->delete(['RT_CT_ID' => $sysid]);
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    /**************      Transaction Code Group Functions      ***************/

    public function transactionCodeGroup()
    {

        $transactionCodeGroupTypes = $this->transactionCodeGroupTypeList();

        $data = ['transactionCodeGroupTypes' => $transactionCodeGroupTypes];
        $data['title'] = getMethodName();
        $data['session'] = $this->session;
        
        return view('Reservation/TransactionCodeGroupView', $data);
    }

    public function TransactionCodeGroupView()
    {
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_TRANSACTION_CODE_GROUP_VIEW';
        $columns = 'TC_GR_ID,TC_GR_CODE,TC_GR_DESC,TC_GR_DIS_SEQ,TC_GR_TYPE';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function transactionCodeGroupTypeList()
    {

        $sql = "SELECT TC_GR_TY_ID, TC_GR_TY_DESC
                FROM FLXY_TRANSACTION_CODE_GROUP_TYPE";

        $response = $this->Db->query($sql)->getResultArray();

        $types = array();

        foreach ($response as $row) {
            $types[$row['TC_GR_TY_ID']] = $row['TC_GR_TY_DESC'];
        }

        return $types;
    }

    public function insertTransactionCodeGroup()
    {
        try {
            $sysid = $this->request->getPost('TC_GR_ID');

            $validate = $this->validate([
                'TC_GR_CODE' => ['label' => 'Group Code', 'rules' => 'required|is_unique[FLXY_TRANSACTION_CODE_GROUP.TC_GR_CODE,TC_GR_ID,' . $sysid . ']'],
                'TC_GR_DESC' => ['label' => 'Group Description', 'rules' => 'required'],
                'TC_GR_DIS_SEQ' => ['label' => 'Display Sequence', 'rules' => 'permit_empty|greater_than_equal_to[0]'],
            ]);
            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }

            //echo json_encode(print_r($_POST)); exit;

            $data = [
                "TC_GR_CODE" => trim($this->request->getPost('TC_GR_CODE')),
                "TC_GR_DESC" => trim($this->request->getPost('TC_GR_DESC')),
                "TC_GR_DIS_SEQ" => trim($this->request->getPost('TC_GR_DIS_SEQ')) != '' ? trim($this->request->getPost('TC_GR_DIS_SEQ')) : '',
                "TC_GR_TY_ID" => trim($this->request->getPost('TC_GR_TY_ID')),
            ];

            $return = !empty($sysid) ? $this->Db->table('FLXY_TRANSACTION_CODE_GROUP')->where('TC_GR_ID', $sysid)->update($data) : $this->Db->table('FLXY_TRANSACTION_CODE_GROUP')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function checkTransactionCodeGroup($rcCode)
    {
        $sql = "SELECT TC_GR_ID
                FROM FLXY_TRANSACTION_CODE_GROUP
                WHERE TC_GR_CODE = '" . $rcCode . "'";

        $response = $this->Db->query($sql)->getNumRows();
        return $rcCode == '' || strlen($rcCode) > 10 ? 1 : $response; // Send found row even if submitted code is empty
    }

    public function transactionCodeGroupList()
    {
        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

        $sql = "SELECT TC_GR_ID, TC_GR_CODE, TC_GR_DESC
                FROM FLXY_TRANSACTION_CODE_GROUP";

        if ($search != '') {
            $sql .= " WHERE TC_GR_CODE LIKE '%$search%'
                      OR TC_GR_DESC LIKE '%$search%'";
        }

        $response = $this->Db->query($sql)->getResultArray();

        $option = '<option value="">Choose an Option</option>';
        foreach ($response as $row) {
            $option .= '<option value="' . $row['TC_GR_ID'] . '">' . $row['TC_GR_CODE'] . ' | ' . $row['TC_GR_DESC'] . '</option>';
        }

        return $option;
    }

    public function editTransactionCodeGroup()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];

        $sql = "SELECT TC_GR_ID, TC_GR_CODE, TC_GR_DESC, TC_GR_DIS_SEQ, TC_GR_TY_ID
                FROM FLXY_TRANSACTION_CODE_GROUP
                WHERE TC_GR_ID=:SYSID:";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteTransactionCodeGroup()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            $return = $this->Db->table('FLXY_TRANSACTION_CODE_GROUP')->delete(['TC_GR_ID' => $sysid]);
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    /**************      Transaction Code Subgroup Functions      ***************/

    public function transactionCodeSubGroup()
    {

        $transactionCodeGroups = $this->transactionCodeGroupList();

        $data = ['transactionCodeGroupOptions' => $transactionCodeGroups];
        $data['title'] = getMethodName();
        $data['session'] = $this->session;
        
        return view('Reservation/TransactionCodeSubGroupView', $data);
    }

    public function TransactionCodeSubGroupView()
    {
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_TRANSACTION_CODE_SUBGROUP_VIEW';
        $columns = 'TC_SGR_ID,TC_SGR_CODE,TC_SGR_DESC,TC_SGR_DIS_SEQ,TC_GR_ID,TC_GR_CODE,TC_GR_DESC,TC_GR_TY_DESC';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function insertTransactionCodeSubGroup()
    {
        try {
            $sysid = $this->request->getPost('TC_SGR_ID');

            $validate = $this->validate([
                'TC_SGR_CODE' => ['label' => 'Subgroup Code', 'rules' => 'required|is_unique[FLXY_TRANSACTION_CODE_SUBGROUP.TC_SGR_CODE,TC_SGR_ID,' . $sysid . ']'],
                'TC_SGR_DESC' => ['label' => 'Subgroup Description', 'rules' => 'required'],
                'TC_GR_ID' => ['label' => 'Group', 'rules' => 'required'],
                'TC_SGR_DIS_SEQ' => ['label' => 'Display Sequence', 'rules' => 'permit_empty|greater_than_equal_to[0]'],
            ]);
            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }

            //echo json_encode(print_r($_POST)); exit;

            $data = [
                "TC_SGR_CODE" => trim($this->request->getPost('TC_SGR_CODE')),
                "TC_SGR_DESC" => trim($this->request->getPost('TC_SGR_DESC')),
                "TC_SGR_DIS_SEQ" => trim($this->request->getPost('TC_SGR_DIS_SEQ')) != '' ? trim($this->request->getPost('TC_SGR_DIS_SEQ')) : '',
                "TC_GR_ID" => trim($this->request->getPost('TC_GR_ID')),
            ];

            $return = !empty($sysid) ? $this->Db->table('FLXY_TRANSACTION_CODE_SUBGROUP')->where('TC_SGR_ID', $sysid)->update($data) : $this->Db->table('FLXY_TRANSACTION_CODE_SUBGROUP')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function checkTransactionCodeSubGroup($rcCode)
    {
        $sql = "SELECT TC_SGR_ID
                FROM FLXY_TRANSACTION_CODE_SUBGROUP
                WHERE TC_SGR_CODE = '" . $rcCode . "'";

        $response = $this->Db->query($sql)->getNumRows();
        return $rcCode == '' || strlen($rcCode) > 10 ? 1 : $response; // Send found row even if submitted code is empty
    }

    public function transactionCodeSubGroupList()
    {
        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

        $sql = "SELECT TC_SGR_ID, TC_SGR_CODE, TC_SGR_DESC
                FROM FLXY_TRANSACTION_CODE_SUBGROUP";

        if ($search != '') {
            $sql .= " WHERE TC_SGR_CODE LIKE '%$search%'
                      OR TC_SGR_DESC LIKE '%$search%'";
        }

        $response = $this->Db->query($sql)->getResultArray();

        $option = '<option value="">Choose an Option</option>';
        foreach ($response as $row) {
            $option .= '<option value="' . $row['TC_SGR_ID'] . '">' . $row['TC_SGR_CODE'] . ' | ' . $row['TC_SGR_DESC'] . '</option>';
        }

        return $option;
    }

    public function editTransactionCodeSubGroup()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];

        $sql = "SELECT FTCSG.TC_SGR_ID, FTCSG.TC_SGR_CODE, FTCSG.TC_SGR_DESC, FTCSG.TC_SGR_DIS_SEQ, FTCG.TC_GR_ID, FTCG.TC_GR_CODE, FTCG.TC_GR_DESC, FTCGT.TC_GR_TY_DESC
                FROM dbo.FLXY_TRANSACTION_CODE_SUBGROUP AS FTCSG
                LEFT OUTER JOIN dbo.FLXY_TRANSACTION_CODE_GROUP AS FTCG ON FTCG.TC_GR_ID = FTCSG.TC_GR_ID
                LEFT OUTER JOIN dbo.FLXY_TRANSACTION_CODE_GROUP_TYPE AS FTCGT ON FTCGT.TC_GR_TY_ID = FTCG.TC_GR_TY_ID
                WHERE TC_SGR_ID=:SYSID:";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteTransactionCodeSubGroup()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            $return = $this->Db->table('FLXY_TRANSACTION_CODE_SUBGROUP')->delete(['TC_SGR_ID' => $sysid]);
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    /**************      Transaction Code Functions      ***************/

    public function transactionCode()
    {

        $transactionCodeSubGroups = $this->transactionCodeSubGroupList();
        $transactionTypes = $this->transactionTypeList();
        $adjTransactionCodes = $this->transactionCodeList();

        $data = [
            'transactionCodeSubGroupOptions' => $transactionCodeSubGroups,
            'transactionTypeOptions' => $transactionTypes,
            'adjTransactionCodeOptions' => $adjTransactionCodes,
        ];

        $data['title'] = getMethodName();
        $data['session'] = $this->session;
        
        return view('Reservation/TransactionCodeView', $data);
    }

    public function TransactionCodeView()
    {
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_TRANSACTION_CODE_VIEW';
        $columns = 'TR_CD_ID,TR_CD_CODE,TR_CD_DESC,TC_GR_CODE,TC_SGR_CODE';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function transactionTypeList()
    {

        $sql = "SELECT TR_TY_ID, TR_TY_DESC
                FROM FLXY_TRANSACTION_TYPE";

        $response = $this->Db->query($sql)->getResultArray();

        $option = '<option value="">Choose an Option</option>';

        foreach ($response as $row) {
            $option .= '<option value="' . $row['TR_TY_ID'] . '">' . $row['TR_TY_DESC'] . '</option>';
        }

        return $option;
    }

    public function insertTransactionCode()
    {
        try {
            $sysid = $this->request->getPost('TR_CD_ID');

            $validate = $this->validate([
                'TR_CD_CODE' => ['label' => 'Code', 'rules' => 'required|is_unique[FLXY_TRANSACTION_CODE.TR_CD_CODE,TR_CD_ID,' . $sysid . ']'],
                'TR_CD_DESC' => ['label' => 'Description', 'rules' => 'required'],
                'TC_SGR_ID' => ['label' => 'Sub Group', 'rules' => 'required'],
                'TR_CD_DEF_PRICE' => ['label' => 'Default Price', 'rules' => 'permit_empty|greater_than_equal_to[0]'],
                'TR_CD_MIN_AMT' => ['label' => 'Minimum Amount', 'rules' => 'permit_empty|greater_than_equal_to[0]'],
                'TR_CD_MAX_AMT' => ['label' => 'Maximum Amount', 'rules' => 'permit_empty|greater_than_equal_to[0]'],
            ]);
            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }

            //echo json_encode(print_r($_POST)); exit;

            $data = [
                "TR_CD_CODE" => trim($this->request->getPost('TR_CD_CODE')),
                "TR_CD_DESC" => trim($this->request->getPost('TR_CD_DESC')),
                "TC_SGR_ID" => trim($this->request->getPost('TC_SGR_ID')),
                "TR_TY_ID" => trim($this->request->getPost('TR_TY_ID')),
                "TR_CD_ADJ" => trim($this->request->getPost('TR_CD_ADJ')),
                "TR_CD_DEF_PRICE" => trim($this->request->getPost('TR_CD_DEF_PRICE')),
                "TR_CD_MIN_AMT" => trim($this->request->getPost('TR_CD_MIN_AMT')),
                "TR_CD_MAX_AMT" => trim($this->request->getPost('TR_CD_MAX_AMT')),
                "TR_CD_MANUAL_POST" => trim($this->request->getPost('TR_CD_MANUAL_POST')),
                "TR_CD_REVN_GRP" => trim($this->request->getPost('TR_CD_REVN_GRP')),
                "TR_CD_MEMBERSHIP" => trim($this->request->getPost('TR_CD_MEMBERSHIP')),
                "TR_CD_PAIDOUT" => trim($this->request->getPost('TR_CD_PAIDOUT')),
                "TR_CD_GENRT_INCL" => trim($this->request->getPost('TR_CD_GENRT_INCL')),
                "TR_CD_INCL_DEPOSIT_RULE" => trim($this->request->getPost('TR_CD_INCL_DEPOSIT_RULE')),
                "TR_CD_CHECK_NO_MANDATORY" => trim($this->request->getPost('TR_CD_CHECK_NO_MANDATORY')),
                "TR_CD_STATUS" => trim($this->request->getPost('TR_CD_STATUS')),
            ];

            $return = !empty($sysid) ? $this->Db->table('FLXY_TRANSACTION_CODE')->where('TR_CD_ID', $sysid)->update($data) : $this->Db->table('FLXY_TRANSACTION_CODE')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function checkTransactionCode($rcCode)
    {
        $sql = "SELECT TR_CD_ID
                FROM FLXY_TRANSACTION_CODE
                WHERE TR_CD_CODE = '" . $rcCode . "'";

        $response = $this->Db->query($sql)->getNumRows();
        return $rcCode == '' || strlen($rcCode) > 10 ? 1 : $response; // Send found row even if submitted code is empty
    }

    public function transactionCodeList($groupTypes = '')
    {
        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

        $sql = "SELECT TR_CD_ID, TR_CD_CODE, TR_CD_DESC
                FROM FLXY_TRANSACTION_CODE_VIEW WHERE 1=1";

        if ($search != '') {
            $sql .= " AND (TR_CD_CODE LIKE '%$search%'
                      OR TR_CD_DESC LIKE '%$search%') ";
        }

        if ($groupTypes != '') {
            $sql .= " AND (TC_GR_TY_ID IN ($groupTypes)) ";
        }

        $response = $this->Db->query($sql)->getResultArray();

        $option = '<option value="">Choose an Option</option>';
        foreach ($response as $row) {
            $option .= '<option value="' . $row['TR_CD_ID'] . '">' . $row['TR_CD_CODE'] . ' | ' . $row['TR_CD_DESC'] . '</option>';
        }

        return $option;
    }

    public function editTransactionCode()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];

        $sql = "SELECT FTC.*, FTCG.TC_GR_ID, FTCG.TC_GR_CODE, FTCG.TC_GR_DESC, FTCSG.TC_SGR_CODE, FTCSG.TC_SGR_DESC, FTCT.TR_TY_DESC
                FROM dbo.FLXY_TRANSACTION_CODE AS FTC
                LEFT OUTER JOIN dbo.FLXY_TRANSACTION_CODE_SUBGROUP AS FTCSG ON FTCSG.TC_SGR_ID = FTC.TC_SGR_ID
                LEFT OUTER JOIN dbo.FLXY_TRANSACTION_CODE_GROUP AS FTCG ON FTCG.TC_GR_ID = FTCSG.TC_GR_ID
                LEFT OUTER JOIN dbo.FLXY_TRANSACTION_TYPE AS FTCT ON FTCT.TR_TY_ID = FTC.TR_TY_ID
                WHERE TR_CD_ID=:SYSID:";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteTransactionCode()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            $return = $this->Db->table('FLXY_TRANSACTION_CODE')->delete(['TR_CD_ID' => $sysid]);
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    /**************      Package Group Functions      ***************/

    public function packageGroup()
    {

        $data['title'] = getMethodName();
        $data['session'] = $this->session;
        $data['js_to_load'] = array("PackageGroup.js");

        return view('Reservation/PackageGroupView', $data);
    }

    public function PackageGroupView()
    {
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_PACKAGE_GROUP';
        $columns = 'PKG_GR_ID,PKG_GR_CODE,PKG_GR_SHORT_DESC,PKG_GR_DESC,PKG_GR_SELL_SEP';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function insertPackageGroup()
    {
        try {
            $sysid = $this->request->getPost('PKG_GR_ID');

            $validate = $this->validate([
                'PKG_GR_CODE' => ['label' => 'Package Group Code', 'rules' => 'required|is_unique[FLXY_PACKAGE_GROUP.PKG_GR_CODE,PKG_GR_ID,' . $sysid . ']'],
                'PKG_GR_DESC' => ['label' => 'Package Group Description', 'rules' => 'required'],
                'PKG_CODES' => ['label' => 'Package List', 'rules' => 'required'],
            ]);

            //echo json_encode(print_r($_POST));
            //exit;

            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }

            $data = [
                "PKG_GR_CODE" => trim($this->request->getPost('PKG_GR_CODE')),
                "PKG_GR_SHORT_DESC" => trim($this->request->getPost('PKG_GR_SHORT_DESC')),
                "PKG_GR_DESC" => trim($this->request->getPost('PKG_GR_DESC')),
                "PKG_GR_SELL_SEP" => null !== $this->request->getPost('PKG_GR_SELL_SEP') ? '1' : '0',
            ];

            $return = !empty($sysid) ? $this->Db->table('FLXY_PACKAGE_GROUP')->where('PKG_GR_ID', $sysid)->update($data) : $this->Db->table('FLXY_PACKAGE_GROUP')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function checkPackageGroup($rcCode)
    {
        $sql = "SELECT PKG_GR_ID
                FROM FLXY_PACKAGE_GROUP
                WHERE PKG_GR_CODE = '" . $rcCode . "'";

        $response = $this->Db->query($sql)->getNumRows();
        return $rcCode == '' || strlen($rcCode) > 10 ? 1 : $response; // Send found row even if submitted code is empty
    }

    public function packageGroupList()
    {
        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

        $sql = "SELECT PKG_GR_ID, PKG_GR_CODE, PKG_GR_SHORT_DESC, PKG_GR_DESC
                FROM FLXY_PACKAGE_GROUP";

        if ($search != '') {
            $sql .= " WHERE PKG_GR_CODE LIKE '%$search%'
                      OR PKG_GR_DESC LIKE '%$search%'";
        }

        $response = $this->Db->query($sql)->getResultArray();

        $option = '<option value="">Choose an Option</option>';
        foreach ($response as $row) {
            $option .= '<option value="' . $row['PKG_GR_ID'] . '">' . $row['PKG_GR_CODE'] . ' | ' . $row['PKG_GR_DESC'] . '</option>';
        }

        return $option;
    }

    public function editPackageGroup()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];

        $sql = "SELECT PKG_GR_ID, PKG_GR_CODE, PKG_GR_SHORT_DESC, PKG_GR_DESC, PKG_GR_SELL_SEP
                FROM FLXY_PACKAGE_GROUP
                WHERE PKG_GR_ID=:SYSID:";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function deletePackageGroup()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            /*
            $return = $this->Db->table('FLXY_PACKAGE_GROUP')->delete(['PKG_GR_ID' => $sysid]);
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
             */

            //Dummy function to prevent deletion if package is attached to any Rate Detail

            $result["SUCCESS"] = "-402";
            $result["ERROR"] = "Package is attached to Rate Detail(s) and cannot be deleted";
            echo json_encode($result);

        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    
    /**************      Package Code Functions      ***************/

    public function packageCode()
    {
        $transactionCodes = $this->transactionCodeList();
        $rateInclusionRules = $this->rateInclusionRuleList();
        $postingRhythm = $this->postingRhythmList();
        $calcInclusionRules = $this->calculationInclusionRuleList();

        $data = [
            'transactionCodeOptions' => $transactionCodes,
            'rateInclusionRules' => $rateInclusionRules,
            'postingRhythmOptions' => $postingRhythm,
            'calcInclusionRules' => $calcInclusionRules,
            'toggleButton_javascript' => toggleButton_javascript(),
            'clearFormFields_javascript' => clearFormFields_javascript(),
            'blockLoader_javascript' => blockLoader_javascript(),
        ];

        $data['title'] = getMethodName();
        $data['session'] = $this->session;
        $data['js_to_load'] = array("PackageCode.js");

        return view('Reservation/PackageCodeView', $data);
    }

    public function PackageCodeView()
    {
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_PACKAGE_CODE';
        $columns = 'PKG_CD_ID,PKG_CD_CODE,PKG_CD_SHORT_DESC,PKG_CD_DESC';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function insertPackageCode()
    {
        try {
            $sysid = $this->request->getPost('PKG_CD_ID');

            $validate = $this->validate([
                'PKG_CD_CODE' => ['label' => 'Package Code', 'rules' => 'required|is_unique[FLXY_PACKAGE_CODE.PKG_CD_CODE,PKG_CD_ID,' . $sysid . ']'],
                'PKG_CD_DESC' => ['label' => 'Package Code Description', 'rules' => 'required'],
                'TR_CD_ID' => ['label' => 'Transaction Code', 'rules' => 'required'],
            ]);

            //echo json_encode(print_r($_POST));
            //exit;

            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }

            $data = [
                "PKG_CD_CODE" => trim($this->request->getPost('PKG_CD_CODE')),
                "PKG_CD_SHORT_DESC" => trim($this->request->getPost('PKG_CD_SHORT_DESC')),
                "PKG_CD_DESC" => trim($this->request->getPost('PKG_CD_DESC')),
                "TR_CD_ID" => trim($this->request->getPost('TR_CD_ID')),
                "PKG_CD_TAX_INCLUDED" => trim($this->request->getPost('PKG_CD_TAX_INCLUDED')),
                "RT_INCL_ID" => trim($this->request->getPost('RT_INCL_ID')),
                "PO_RH_ID" => trim($this->request->getPost('PO_RH_ID')),
                "CLC_RL_ID" => trim($this->request->getPost('CLC_RL_ID')),
                "PKG_CD_SELL_SEP" => null !== $this->request->getPost('PKG_CD_SELL_SEP') ? '1' : '0',
            ];

            $return = !empty($sysid) ? $this->Db->table('FLXY_PACKAGE_CODE')->where('PKG_CD_ID', $sysid)->update($data) : $this->Db->table('FLXY_PACKAGE_CODE')->insert($data);
            $newPkgCodeID = empty($sysid) ? $this->Db->insertID() : '';                        
                
            $result = $return ? $this->responseJson("1", "0", $return, !empty($sysid) ? $sysid : $newPkgCodeID) : $this->responseJson("-444", "db insert not successful", $return);

            if(empty($sysid))
            {
                $blank_package_detail = ["PKG_CD_ID" => $newPkgCodeID, 
                                         "PKG_CD_START_DT" => date('Y-m-d'), 
                                         "PKG_CD_END_DT" => date('Y-m-d', strtotime('+10 years')),
                                         "PKG_CD_DT_PRICE" => '0'];
                
                $this->Db->table('FLXY_PACKAGE_CODE_DETAIL')->insert($blank_package_detail);
            }

            echo json_encode($result);
            
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function checkPackageCode($rcCode)
    {
        $sql = "SELECT PKG_CD_ID
                FROM FLXY_PACKAGE_CODE
                WHERE PKG_CD_CODE = '" . $rcCode . "'";

        $response = $this->Db->query($sql)->getNumRows();
        return $rcCode == '' || strlen($rcCode) > 10 ? 1 : $response; // Send found row even if submitted code is empty
    }

    public function packageCodeList($codes = null)
    {
        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

        $sql = "SELECT PKG_CD_ID, RTRIM(LTRIM(REPLACE(REPLACE(REPLACE(PKG_CD_CODE, CHAR(9), ' '), CHAR(10), ' '), CHAR(13), ' '))) AS PKG_CD_CODE,
                PKG_CD_SHORT_DESC, PKG_CD_DESC
                FROM FLXY_PACKAGE_CODE WHERE PKG_CD_STATUS = 1";

        if ($search != '') {
            $sql .= " AND (PKG_CD_CODE LIKE '%$search%'
                    OR PKG_CD_DESC LIKE '%$search%')";
        }
        if ($codes != null) {
            $sql .= " AND PKG_CD_CODE IN ('" . str_replace(",", "','", $codes) . "')";
        }                                                               

        $response = $this->Db->query($sql)->getResultArray();

        $options = array();
        foreach ($response as $row) {
            $options[] = array("value" => $row['PKG_CD_ID'], "name" => $row['PKG_CD_CODE'], "desc" => $row['PKG_CD_DESC']);
        }

        return $options;
    }

    public function rateInclusionRuleList()
    {
        $sql = "SELECT  RT_INCL_ID, 
                        RTRIM(LTRIM(REPLACE(REPLACE(REPLACE(RT_INCL_DESC, CHAR(9), ' '), CHAR(10), ' '), CHAR(13), ' '))) AS RT_INCL_DESC

                FROM FLXY_RATE_INCLUSION_RULE
                WHERE 1=1 ";

        $response = $this->Db->query($sql)->getResultArray();

        $options = array();

        foreach ($response as $row) {
            $options[] = array("value" => $row['RT_INCL_ID'], "name" => $row['RT_INCL_DESC']);
        }

        return $options;
    }

    public function postingRhythmList()
    {
        $sql = "SELECT  PO_RH_ID, 
                        RTRIM(LTRIM(REPLACE(REPLACE(REPLACE(PO_RH_DESC, CHAR(9), ' '), CHAR(10), ' '), CHAR(13), ' '))) AS PO_RH_DESC

                FROM FLXY_POSTING_RHYTHM
                WHERE 1=1 ";

        $response = $this->Db->query($sql)->getResultArray();

        $options = array();

        foreach ($response as $row) {
            $options[] = array("value" => $row['PO_RH_ID'], "name" => $row['PO_RH_DESC']);
        }

        return $options;
    }

    public function calculationInclusionRuleList()
    {
        $sql = "SELECT  CLC_RL_ID, 
                        RTRIM(LTRIM(REPLACE(REPLACE(REPLACE(CLC_RL_DESC, CHAR(9), ' '), CHAR(10), ' '), CHAR(13), ' '))) AS CLC_RL_DESC

                FROM FLXY_CALCULATION_RULE
                WHERE 1=1 ";

        $response = $this->Db->query($sql)->getResultArray();

        $options = array();

        foreach ($response as $row) {
            $options[] = array("value" => $row['CLC_RL_ID'], "name" => $row['CLC_RL_DESC']);
        }

        return $options;
    }    

    public function PackageCodeDetailsView()
    {
        $sysid = $this->request->getPost('sysid');

        $init_cond = array("PKG_CD_ID = " => "'$sysid'"); // Add condition for Package Code

        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_PACKAGE_CODE_DETAIL';
        $columns = 'PKG_CD_DT_ID,PKG_CD_ID,PKG_CD_START_DT,PKG_CD_END_DT,PKG_CD_DT_PRICE,PKG_CD_DT_STATUS';
        $mine->generate_DatatTable($tableName, $columns, $init_cond);
        exit;
    }

    public function getPackageCodeDetails($pkgID = 0, $id = 0)
    {
        $param = ['SYSID' => $pkgID];

        $sql = "SELECT FPKGCDT.*,
                FORMAT(FPKGCDT.PKG_CD_START_DT, 'dd-MMM-yyyy') as PKG_CD_START_DT, 
                FORMAT(FPKGCDT.PKG_CD_END_DT, 'dd-MMM-yyyy') as PKG_CD_END_DT
               
                FROM dbo.FLXY_PACKAGE_CODE_DETAIL FPKGCDT
                WHERE FPKGCDT.PKG_CD_ID=:SYSID:";

        if ($id != 0) {
            $sql .= " AND FPKGCDT.PKG_CD_DT_ID = $id";
        }

        $response = $this->Db->query($sql, $param)->getResultArray();
        return $response;
    }

    public function showPackageCodeDetails()
    {
        $rateCodeDetailsList = $this->getPackageCodeDetails($this->request->getGet('sysid'), null !== $this->request->getGet('dtID') ? $this->request->getGet('dtID') : 0);
        echo json_encode($rateCodeDetailsList);
    }

    public function showPackageCodeList()
    {
        $response = $this->packageCodeList($this->request->getGet('codes'));
        echo json_encode($response);
    }

    public function editPackageCode()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];

        $sql = "SELECT FPC.*
                FROM dbo.FLXY_PACKAGE_CODE AS FPC
                WHERE PKG_CD_ID=:SYSID:";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function updatePackageCodeDetail()
    {
        try {
            $sysid = $this->request->getPost('PKG_CD_DT_ID');

            $data = [
                "PKG_CD_ID" => trim($this->request->getPost('PKG_CD_ID')),
                "PKG_CD_START_DT" => trim($this->request->getPost('PKG_CD_START_DT')),
                "PKG_CD_END_DT" => trim($this->request->getPost('PKG_CD_END_DT')),
                "PKG_CD_DT_PRICE" => trim($this->request->getPost('PKG_CD_DT_PRICE'))
            ];

            $rules = [  'PKG_CD_ID' => ['label' => 'Package Code ID', 'rules' => 'required'],
                        'PKG_CD_START_DT' => ['label' => 'Start Date', 'rules' => 'required'],
                        'PKG_CD_END_DT' => ['label' => 'End Date', 'rules' => 'required|compareDate', 'errors' => ['compareDate' => 'The End Sell Date should be after Begin Date']],
                        'PKG_CD_DT_PRICE' => ['label' => 'Package Price', 'rules' => 'required'],
                     ];

            $validate = $this->validate($rules);
            
            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }

            //echo json_encode(print_r($_POST)); exit;

            $return = !empty($sysid) ? $this->Db->table('FLXY_PACKAGE_CODE_DETAIL')->where('PKG_CD_DT_ID', $sysid)->update($data) : $this->Db->table('FLXY_PACKAGE_CODE_DETAIL')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, !empty($sysid) ? $sysid : $this->Db->insertID()) : $this->responseJson("-444", "db insert not successful", $return);

            if(!$return)
                $this->session->setFlashdata('error', 'There has been an error. Please try again.');
            else
            {
                if(empty($sysid))
                    //$this->session->setFlashdata('success', 'The Package Code has been updated.');
                    //else
                    $this->session->setFlashdata('success', 'The new Package Code Detail has been created.');
            }
            echo json_encode($result);

        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }


    public function deletePackageCode()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            
            $return = $this->Db->table('FLXY_PACKAGE_CODE')->delete(['PKG_CD_ID' => $sysid]);
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            
            //Dummy function to prevent deletion if package is attached to any Rate Detail
            echo json_encode($result);

        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function deletePackageCodeDetail()
    {
        $sysid = $this->request->getPost('sysid');
        $packageCodeID = $this->request->getPost('packageCodeID');

        $sql = "SELECT PKG_CD_DT_ID
                FROM FLXY_PACKAGE_CODE_DETAIL
                WHERE PKG_CD_ID = '" . $packageCodeID . "'";

        $response = $this->Db->query($sql)->getNumRows();

        if($response <= 1) // Check if Package Code Detail can be deleted
            echo json_encode($this->responseJson("0", "The Package Detail cannot be deleted"));
        else
        {
            try {
                $return = $this->Db->table('FLXY_PACKAGE_CODE_DETAIL')->delete(['PKG_CD_DT_ID' => $sysid]); 
                //$return = NULL;
                $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
                echo json_encode($result);
            } catch (Exception $e) {
                return $this->respond($e->errors());
            }
        }
    }


    /**************      Market Group Functions      ***************/

    public function marketGroup()
    {
        $data['title'] = getMethodName();
        $data['session'] = $this->session;
        
        return view('Reservation/MarketGroupView', $data);
    }

    public function MarketGroupView()
    {
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_MARKET_GROUP';
        $columns = 'MK_GR_ID,MK_GR_CODE,MK_GR_DESC,MK_GR_DIS_SEQ,MK_GR_STATUS';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function insertMarketGroup()
    {
        try {
            $sysid = $this->request->getPost('MK_GR_ID');

            $validate = $this->validate([
                'MK_GR_CODE' => ['label' => 'Group Code', 'rules' => 'required|is_unique[FLXY_MARKET_GROUP.MK_GR_CODE,MK_GR_ID,' . $sysid . ']'],
                'MK_GR_DESC' => ['label' => 'Group Description', 'rules' => 'required'],
                'MK_GR_DIS_SEQ' => ['label' => 'Display Sequence', 'rules' => 'permit_empty|greater_than_equal_to[0]'],
            ]);
            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }

            //echo json_encode(print_r($_POST)); exit;

            $data = [
                "MK_GR_CODE" => trim($this->request->getPost('MK_GR_CODE')),
                "MK_GR_DESC" => trim($this->request->getPost('MK_GR_DESC')),
                "MK_GR_DIS_SEQ" => trim($this->request->getPost('MK_GR_DIS_SEQ')) != '' ? trim($this->request->getPost('MK_GR_DIS_SEQ')) : '',
                "MK_GR_STATUS" => trim($this->request->getPost('MK_GR_STATUS')),
            ];

            $return = !empty($sysid) ? $this->Db->table('FLXY_MARKET_GROUP')->where('MK_GR_ID', $sysid)->update($data) : $this->Db->table('FLXY_MARKET_GROUP')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function checkMarketGroup($rcCode)
    {
        $sql = "SELECT MK_GR_ID
                FROM FLXY_MARKET_GROUP
                WHERE MK_GR_CODE = '" . $rcCode . "'";

        $response = $this->Db->query($sql)->getNumRows();
        return $rcCode == '' || strlen($rcCode) > 10 ? 1 : $response; // Send found row even if submitted code is empty
    }

    public function marketGroupList()
    {
        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

        $sql = "SELECT MK_GR_ID, MK_GR_CODE, MK_GR_DESC
                FROM FLXY_MARKET_GROUP WHERE MK_GR_STATUS = 1 ";

        if ($search != '') {
            $sql .= " AND MK_GR_CODE LIKE '%$search%'
                      OR MK_GR_DESC LIKE '%$search%'";
        }

        $response = $this->Db->query($sql)->getResultArray();

        $option = '<option value="">Choose an Option</option>';
        foreach ($response as $row) {
            $option .= '<option value="' . $row['MK_GR_ID'] . '">' . $row['MK_GR_CODE'] . ' | ' . $row['MK_GR_DESC'] . '</option>';
        }

        return $option;
    }

    public function editMarketGroup()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];

        $sql = "SELECT MK_GR_ID, MK_GR_CODE, MK_GR_DESC, MK_GR_DIS_SEQ, MK_GR_STATUS
                FROM FLXY_MARKET_GROUP
                WHERE MK_GR_ID=:SYSID:";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteMarketGroup()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            $return = $this->Db->table('FLXY_MARKET_GROUP')->delete(['MK_GR_ID' => $sysid]);
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    /**************      Market Code Functions      ***************/

    public function marketCode()
    {
        $colors = $this->colorList();
        $marketGroups = $this->marketGroupList();

        $data = [
            'colorOptions' => $colors,
            'marketGroupOptions' => $marketGroups,
        ];

        $data['title'] = getMethodName();
        $data['session'] = $this->session;
        
        return view('Reservation/MarketCodeView', $data);
    }

    public function MarketCodeView()
    {
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_MARKET_CODE
        LEFT JOIN FLXY_MARKET_GROUP FMG ON FMG.MK_GR_ID = FLXY_MARKET_CODE.MK_GR_ID
        LEFT JOIN FLXY_COLOR FC ON FC.CLR_ID = FLXY_MARKET_CODE.CLR_ID';

        $columns = 'MK_CD_ID,MK_CD_CODE,MK_CD_DESC,MK_GR_CODE,CLR_NAME,MK_CD_DIS_SEQ,MK_CD_STATUS';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function colorList()
    {
        $sql = "SELECT CLR_ID, CLR_NAME
                FROM FLXY_COLOR WHERE CLR_STATUS = 1 ORDER BY CLR_DIS_SEQ ASC";

        $response = $this->Db->query($sql)->getResultArray();

        $option = '<option value="">Choose a Color</option>';

        foreach ($response as $row) {
            $option .= '<option value="' . $row['CLR_ID'] . '">' . $row['CLR_NAME'] . '</option>';
        }

        return $option;
    }

    public function insertMarketCode()
    {
        try {
            $sysid = $this->request->getPost('MK_CD_ID');

            $validate = $this->validate([
                'MK_CD_CODE' => ['label' => 'Market Code', 'rules' => 'required|is_unique[FLXY_MARKET_CODE.MK_CD_CODE,MK_CD_ID,' . $sysid . ']'],
                'MK_CD_DESC' => ['label' => 'Description', 'rules' => 'required'],
                'MK_GR_ID' => ['label' => 'Market Group', 'rules' => 'required'],
                'CLR_ID' => ['label' => 'Color', 'rules' => 'required'],
                'MK_CD_DIS_SEQ' => ['label' => 'Display Sequence', 'rules' => 'permit_empty|greater_than_equal_to[0]'],
            ]);
            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }

            //echo json_encode(print_r($_POST)); exit;

            $data = [
                "MK_CD_CODE" => trim($this->request->getPost('MK_CD_CODE')),
                "MK_CD_DESC" => trim($this->request->getPost('MK_CD_DESC')),
                "MK_GR_ID" => trim($this->request->getPost('MK_GR_ID')),
                "CLR_ID" => trim($this->request->getPost('CLR_ID')),
                "MK_CD_DIS_SEQ" => trim($this->request->getPost('MK_CD_DIS_SEQ')),
                "MK_CD_STATUS" => trim($this->request->getPost('MK_CD_STATUS')),
            ];

            $return = !empty($sysid) ? $this->Db->table('FLXY_MARKET_CODE')->where('MK_CD_ID', $sysid)->update($data) : $this->Db->table('FLXY_MARKET_CODE')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function checkMarketCode($rcCode)
    {
        $sql = "SELECT MK_CD_ID
                FROM FLXY_MARKET_CODE
                WHERE MK_CD_CODE = '" . $rcCode . "'";

        $response = $this->Db->query($sql)->getNumRows();
        return $rcCode == '' || strlen($rcCode) > 10 ? 1 : $response; // Send found row even if submitted code is empty
    }

    public function marketCodeList()
    {
        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

        $sql = "SELECT MK_CD_ID, MK_CD_CODE, MK_CD_DESC
                FROM FLXY_MARKET_CODE WHERE MK_CD_STATUS = 1";

        if ($search != '') {
            $sql .= " AND (MK_CD_CODE LIKE '%$search%'
                      OR MK_CD_DESC LIKE '%$search%') ";
        }

        $sql .= " ORDER BY MK_CD_DIS_SEQ ASC";

        $response = $this->Db->query($sql)->getResultArray();

        $option = '<option value="">Choose an Option</option>';
        foreach ($response as $row) {
            $option .= '<option value="' . $row['MK_CD_ID'] . '">' . $row['MK_CD_CODE'] . ' | ' . $row['MK_CD_DESC'] . '</option>';
        }

        return $option;
    }

    public function sourceCodeList()
    {
        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

        $sql = "SELECT SOR_ID, SOR_CODE, SOR_DESC
                FROM FLXY_SOURCE WHERE SOR_ACTIVE = 'Y'";

        if ($search != '') {
            $sql .= " AND (SOR_CODE LIKE '%$search%'
                      OR SOR_DESC LIKE '%$search%') ";
        }

        $sql .= " ORDER BY SOR_DIS_SEQ ASC";

        $response = $this->Db->query($sql)->getResultArray();

        $option = '<option value="">Choose an Option</option>';
        foreach ($response as $row) {
            $option .= '<option value="' . $row['SOR_ID'] . '">' . $row['SOR_CODE'] . ' | ' . $row['SOR_DESC'] . '</option>';
        }

        return $option;
    }

    public function editMarketCode()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];

        $sql = "SELECT FTC.*, FMG.MK_GR_CODE, FC.CLR_NAME
                FROM dbo.FLXY_MARKET_CODE AS FTC
                LEFT JOIN FLXY_MARKET_GROUP FMG ON FMG.MK_GR_ID = FTC.MK_GR_ID
                LEFT JOIN FLXY_COLOR FC ON FC.CLR_ID = FTC.CLR_ID
                WHERE MK_CD_ID=:SYSID:";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteMarketCode()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            $return = $this->Db->table('FLXY_MARKET_CODE')->delete(['MK_CD_ID' => $sysid]);
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    /**************      Rate Codes Functions      ***************/

    public function rateCode()
    {

        $data['title'] = getMethodName();
        $data['session'] = $this->session;

        return view('Reservation/RateCodeView', $data);
    }

    public function RateCodeView()
    {
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_RATE_CODE
        LEFT JOIN FLXY_RATE_CATEGORY FRC ON FRC.RT_CT_ID = FLXY_RATE_CODE.RT_CT_ID';

        $columns = 'RT_CD_ID,RT_CD_CODE,RT_CD_DESC,RT_CT_CODE,RT_CD_BEGIN_SELL_DT,RT_CD_END_SELL_DT,RT_CD_DIS_SEQ,RT_CD_STATUS';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function getRateCodeInfo($id = 0)
    {
        $param = ['SYSID' => $id];

        $sql = "SELECT FRTC.*, FRC.RT_CT_CODE, FRCL.RT_CL_CODE,
                FORMAT(FRTC.RT_CD_BEGIN_SELL_DT, 'dd-MMM-yyyy') as RT_CD_BEGIN_SELL_DT, 
                FORMAT(FRTC.RT_CD_END_SELL_DT, 'dd-MMM-yyyy') as RT_CD_END_SELL_DT
               
                FROM dbo.FLXY_RATE_CODE FRTC
                LEFT JOIN FLXY_RATE_CATEGORY FRC ON FRC.RT_CT_ID = FRTC.RT_CT_ID
                LEFT JOIN FLXY_RATE_CLASS FRCL ON FRC.RT_CL_ID = FRCL.RT_CL_ID
                WHERE FRTC.RT_CD_ID=:SYSID:";

        $response = $this->Db->query($sql, $param)->getRowArray();
        return $response;
    }

    public function getRateCodeDetails($rcID = 0, $id = 0)
    {
        $param = ['SYSID' => $rcID];

        $sql = "SELECT FRTCDT.*,
                FORMAT(FRTCDT.RT_CD_START_DT, 'dd-MMM-yyyy') as RT_CD_START_DT, 
                FORMAT(FRTCDT.RT_CD_END_DT, 'dd-MMM-yyyy') as RT_CD_END_DT
               
                FROM dbo.FLXY_RATE_CODE_DETAIL FRTCDT
                WHERE FRTCDT.RT_CD_ID=:SYSID:";

        if ($id != 0) {
            $sql .= " AND FRTCDT.RT_CD_DT_ID = $id";
        }

        $response = $this->Db->query($sql, $param)->getResultArray();
        return $response;
    }

    public function showRateCodeInfo()
    {
        $response = $this->getRateCodeInfo($this->request->getGet('sysid'));
        echo json_encode($response);
    }

    public function showRateCodeDetails()
    {
        $rateCodeDetailsList = $this->getRateCodeDetails($this->request->getGet('sysid'), null !== $this->request->getGet('dtID') ? $this->request->getGet('dtID') : 0);
        /*
        if($rateCodeDetailsList != NULL)
        {
            $no_of_details = count($rateCodeDetailsList);
            for($i = 0; $i < $no_of_details; $i++){
                $rateCodeDetailsList[$i]['RT_CD_DT_ROOM_TYPE_STR'] = get_color_badges($rateCodeDetailsList[$i]['RT_CD_DT_ROOM_TYPES']);
            }
        }
        */
        echo json_encode($rateCodeDetailsList);
    }

    public function showColorBadges()
    {
        $response = get_color_badges($this->request->getGet('str'));
        echo $response;
    }

    public function RateCodeDetailsView()
    {
        $sysid = $this->request->getPost('sysid');

        $init_cond = array("RT_CD_ID = " => "'$sysid'"); // Add condition for main Rate Code

        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_RATE_CODE_DETAIL';
        $columns = 'RT_CD_DT_ID,RT_CD_ID,RT_CD_START_DT,RT_CD_END_DT,RT_CD_DT_ROOM_TYPES';
        $mine->generate_DatatTable($tableName, $columns, $init_cond);
        exit;
    }

    public function roomTypeList($codes = null)
    {
        $search = $this->request->getPost('search');

        $sql = "SELECT  RM_TY_ID, RTRIM(LTRIM(REPLACE(REPLACE(REPLACE(RM_TY_CODE, CHAR(9), ' '), CHAR(10), ' '), CHAR(13), ' '))) AS RM_TY_CODE,
                        RM_TY_DESC

                FROM FLXY_ROOM_TYPE
                WHERE 1=1";

        if (trim($search) != '') {
            $sql .= " AND (RM_TY_CODE LIKE '%$search%' OR RM_TY_DESC LIKE '%$search%')";
        }
        if ($codes != null) {
            $sql .= " AND RM_TY_CODE IN ('" . str_replace(",", "','", $codes) . "')";
        }

        $response = $this->Db->query($sql)->getResultArray();

        $options = array();

        foreach ($response as $row) {
            $options[] = array("value" => $row['RM_TY_ID'], "name" => $row['RM_TY_CODE'], "desc" => $row['RM_TY_DESC']);
        }

        return $options;
    }    

    public function showRoomTypeList()
    {
        $response = $this->roomTypeList($this->request->getGet('codes'));
        echo json_encode($response);
    }

    public function addRateCode()
    {
        $rateCategories = $this->rateCategoryList();
        $roomTypes = $this->roomTypeList();
        $packageCodes = $this->packageCodeList();
        $marketCodes = $this->marketCodeList();
        $sourceCodes = $this->sourceCodeList();
        $transactionCodes = $this->transactionCodeList('1,2');
        $pkgTransactionCodes = $this->transactionCodeList('3');

        $data = [
            'rateCodeDetails' => array(),
            'rateCategoryOptions' => $rateCategories,
            'roomTypeOptions' => $roomTypes,
            'packageCodeOptions' => $packageCodes,
            'marketCodeOptions' => $marketCodes,
            'sourceCodeOptions' => $sourceCodes,
            'transactionCodeOptions' => $transactionCodes,
            'pkgTransactionCodeOptions' => $pkgTransactionCodes,
            'toggleButton_javascript' => toggleButton_javascript(),
            'clearFormFields_javascript' => clearFormFields_javascript(),
            'blockLoader_javascript' => blockLoader_javascript()
        ];

        $data['session'] = $this->session;
        $data['title'] = getMethodName();
        $data['js_to_load'] = array("rateCodeForm.js", "rateCategory.js", "roomType.js", "packageList.js");

        return view('Reservation/RateCodeForm', $data);
    }

    public function insertRateCode()
    {
        try {
            $sysid = $this->request->getPost('RT_CD_ID');

            $validate = $this->validate([
                'RT_CD_CODE' => ['label' => 'Rate Code', 'rules' => 'required|is_unique[FLXY_RATE_CODE.RT_CD_CODE,RT_CD_ID,' . $sysid . ']'],
                'RT_CD_DESC' => ['label' => 'Description', 'rules' => 'required'],
                'RT_CT_ID' => ['label' => 'Rate Category', 'rules' => 'required'],
                'TR_CD_ID' => ['label' => 'Transaction Code', 'rules' => 'required'],
                'RT_CD_DIS_SEQ' => ['label' => 'Display Sequence', 'rules' => 'permit_empty|greater_than_equal_to[0]'],
                'RT_CD_BEGIN_SELL_DT' => ['label' => 'Begin Sell Date', 'rules' => 'required'],
                'RT_CD_END_SELL_DT' => ['label' => 'End Sell Date', 'rules' => 'required|compareDate', 'errors' => ['compareDate' => 'The End Sell Date should be after Begin Date']],
                'RT_CD_ROOM_TYPES' => ['label' => 'Room Types', 'rules' => 'required'],
                
            ]);
            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }

            //echo json_encode(print_r($_POST)); exit;

            $data = [
                "RT_CD_CODE" => trim($this->request->getPost('RT_CD_CODE')),
                "RT_CD_DESC" => trim($this->request->getPost('RT_CD_DESC')),
                "RT_CT_ID" => json_decode($this->request->getPost('RT_CT_ID'), true)[0]['value'],
                "TR_CD_ID" => trim($this->request->getPost('TR_CD_ID')),
                "RT_CD_FOLIO" => trim($this->request->getPost('RT_CD_FOLIO')),
                "RT_CD_DIS_SEQ" => trim($this->request->getPost('RT_CD_DIS_SEQ')),
                "RT_CD_BEGIN_SELL_DT" => trim($this->request->getPost('RT_CD_BEGIN_SELL_DT')),
                "RT_CD_END_SELL_DT" => trim($this->request->getPost('RT_CD_END_SELL_DT')),
                "MK_CD_ID" => trim($this->request->getPost('MK_CD_ID')),
                "SOR_ID" => trim($this->request->getPost('SOR_ID')),
                "RT_CD_ROOM_TYPES" => "",
                "RT_CD_PACKAGES" => "",
                "RT_CD_COMMISSION" => trim($this->request->getPost('RT_CD_COMMISSION')),
                "RT_CD_ADDITION" => trim($this->request->getPost('RT_CD_ADDITION')),
                "RT_CD_MULTIPLICATION" => trim($this->request->getPost('RT_CD_MULTIPLICATION')),
                "RT_CD_MIN_OCCUPANCY" => trim($this->request->getPost('RT_CD_MIN_OCCUPANCY')),
                "RT_CD_MAX_OCCUPANCY" => trim($this->request->getPost('RT_CD_MAX_OCCUPANCY')),
                "TR_CD_ID" => trim($this->request->getPost('TR_CD_ID')),
                "RT_CD_TAX_INCLUDED" => trim($this->request->getPost('RT_CD_TAX_INCLUDED')),
                "PKG_TR_CD_ID" => trim($this->request->getPost('PKG_TR_CD_ID')),
                "RT_CD_DAY_USE" => trim($this->request->getPost('RT_CD_DAY_USE')),
                "RT_CD_NEGOTIATED" => trim($this->request->getPost('RT_CD_NEGOTIATED')),
                "RT_CD_COMPLIMENTARY" => trim($this->request->getPost('RT_CD_COMPLIMENTARY')),
                "RT_CD_SUPPRESS_RATE" => trim($this->request->getPost('RT_CD_SUPPRESS_RATE')),
                "RT_CD_HOUSE_USE" => trim($this->request->getPost('RT_CD_HOUSE_USE')),
                "RT_CD_PRINT_RATE" => trim($this->request->getPost('RT_CD_PRINT_RATE')),
                "RT_CD_STATUS" => '1',
            ];

            $RT_CD_ROOM_TYPES = json_decode($this->request->getPost('RT_CD_ROOM_TYPES'), true);
            if($RT_CD_ROOM_TYPES != NULL)
            {
                foreach($RT_CD_ROOM_TYPES as $RT_CD_ROOM_TYPE){
                    $data["RT_CD_ROOM_TYPES"] .= $RT_CD_ROOM_TYPE['name'].',';
                }
                $data["RT_CD_ROOM_TYPES"] = rtrim($data["RT_CD_ROOM_TYPES"], ',');
            }

            $RT_CD_PACKAGE_CODES = json_decode($this->request->getPost('RT_CD_PACKAGES'), true);
            if($RT_CD_PACKAGE_CODES != NULL)
            {
                foreach($RT_CD_PACKAGE_CODES as $RT_CD_PACKAGE_CODE){
                    $data["RT_CD_PACKAGES"] .= $RT_CD_PACKAGE_CODE['name'].',';
                }
                $data["RT_CD_PACKAGES"] = rtrim($data["RT_CD_PACKAGES"], ',');
            }

            //echo json_encode(print_r($data)); exit;

            $return = !empty($sysid) ? $this->Db->table('FLXY_RATE_CODE')->where('RT_CD_ID', $sysid)->update($data) : $this->Db->table('FLXY_RATE_CODE')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, !empty($sysid) ? '' : $this->Db->insertID()) : $this->responseJson("-444", "db insert not successful", $return);

            if(!$return)
                $this->session->setFlashdata('error', 'There has been an error. Please try again.');
            else
            {
                if(empty($sysid))
                    //$this->session->setFlashdata('success', 'The Rate Code has been updated.');
                    //else
                    $this->session->setFlashdata('success', 'The new Rate Code has been created.');
            }
            echo json_encode($result);

        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function checkRateCode($rcCode)
    {
        $sql = "SELECT RT_CD_ID
                FROM FLXY_RATE_CODE
                WHERE RT_CD_CODE = '" . $rcCode . "'";

        $response = $this->Db->query($sql)->getNumRows();
        return $rcCode == '' || strlen($rcCode) > 10 ? 1 : $response; // Send found row even if submitted code is empty
    }

    public function copyRateCode()
    {
        try {
            $_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);

            $param = ['SYSID' => $this->request->getPost('main_RT_CD_ID')];
            $origRateCode = $this->getRateCodeInfo($param['SYSID']);
            $origRateCodeDetailsList = $this->getRateCodeDetails($param['SYSID']);

            //echo json_encode($response);
            //echo json_encode(print_r($origRateCode)); exit;

            $no_of_added = 0;
            $submitted_fields = $this->request->getPost('group-a');

            if ($submitted_fields != null) {
                foreach ($submitted_fields as $submitted_field) {
                    if (!$this->checkRateCode($submitted_field['RT_CD_CODE'])) // Check if entered Rate Category already exists
                    {
                        $newRateCode = $origRateCode;
                        unset($newRateCode['RT_CD_ID'], $newRateCode['RT_CT_CODE'], $newRateCode['RT_CL_CODE']); 
                        // to add copies as new entries

                        $newRateCode["RT_CD_CODE"] = trim($submitted_field["RT_CD_CODE"]);
                        $newRateCode["RT_CD_BEGIN_SELL_DT"] = $origRateCode["RT_CD_BEGIN_SELL_DT"] != '' ? $origRateCode["RT_CD_BEGIN_SELL_DT"] : date('Y-m-d');
                        $newRateCode["RT_CD_END_SELL_DT"] = $origRateCode["RT_CD_END_SELL_DT"] != '' ? $origRateCode["RT_CD_END_SELL_DT"] : '2030-12-31';

                        $this->Db->table('FLXY_RATE_CODE')->insert($newRateCode);
                        $newRateCodeID = $this->Db->insertID();
                        if($newRateCodeID)
                            $no_of_added += 1;

                        //Copy Rate Code details
                        if($origRateCodeDetailsList != NULL){
                            foreach($origRateCodeDetailsList as $origRateCodeDetail)
                            {
                                $newRateCodeDetail = $origRateCodeDetail;
                                unset($newRateCodeDetail['RT_CD_DT_ID']);
                                $newRateCodeDetail['RT_CD_ID'] = $newRateCodeID;
                                $this->Db->table('FLXY_RATE_CODE_DETAIL')->insert($newRateCodeDetail);
                            }
                        }
                    }
                }
            }

            echo $no_of_added;
            exit;

            //echo json_encode(print_r($_POST)); exit;
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function editRateCode($id = 0)
    {
        $rateCodeDetails = $this->getRateCodeInfo($id);

        if (!$rateCodeDetails) {
            $this->session->setFlashdata('error', 'Rate Code does not exist. Please try again.');
            return redirect()->route("rateCode");
        }

        // Rate Code Info
        $rateCategories = $this->rateCategoryList();
        $roomTypes = $this->roomTypeList();
        $selectedRoomTypes = !empty($rateCodeDetails['RT_CD_ROOM_TYPES']) ? $this->roomTypeList($rateCodeDetails['RT_CD_ROOM_TYPES']) : array();

        $packageCodes = $this->packageCodeList();

        //$selectedPackageCodes = !empty($rateCodeDetails['RT_CD_PACKAGES']) ? $this->packageCodeList($rateCodeDetails['RT_CD_PACKAGES']) : array();
        $getRateCodePackages = $this->getRateCodePackages($id);

        $selectedPackageCodes = array();

        foreach ($getRateCodePackages as $row) {
            if($row['RT_CD_PKG_ID'] == NULL) continue;
            
            $selectedPackageCodes[] = array( "id" => $row['RT_CD_PKG_ID'], "value" => $row['PKG_CD_ID'], 
                                             "name" => $row['PKG_CD_CODE'], "desc" => $row['PKG_CD_DESC']);
        }

        //echo "<pre>"; print_r($selectedPackageCodes); echo "</pre>"; exit;

        $rateInclusionRules = $this->rateInclusionRuleList();
        $postingRhythm = $this->postingRhythmList();
        $calcInclusionRules = $this->calculationInclusionRuleList();

        $marketCodes = $this->marketCodeList();
        $sourceCodes = $this->sourceCodeList();
        
        $transactionCodes = $this->transactionCodeList('1,2');
        $pkgTransactionCodes = $this->transactionCodeList('3');

        // Rate Code Details

        $rateCodeDetailsList = $this->getRateCodeDetails($id);
        if($rateCodeDetailsList != NULL)
        {
            $no_of_details = count($rateCodeDetailsList);
            for($i = 0; $i < $no_of_details; $i++){
                $rateCodeDetailsList[$i]['RT_CD_DT_ROOM_TYPE_STR'] = get_color_badges($rateCodeDetailsList[$i]['RT_CD_DT_ROOM_TYPES']);
            }
        }

        $data = [
            'rateCodeID' => $id,
            'rateCodeDetails' => $rateCodeDetails,
            'rateCategoryOptions' => $rateCategories,
            'roomTypeOptions' => $roomTypes,
            'selectedRoomTypes' => $selectedRoomTypes,
            'packageCodeOptions' => $packageCodes,
            'selectedPackageCodes' => $selectedPackageCodes,
            'rateInclusionRules' => $rateInclusionRules,
            'postingRhythmOptions' => $postingRhythm,
            'calcInclusionRules' => $calcInclusionRules,
            'marketCodeOptions' => $marketCodes,
            'sourceCodeOptions' => $sourceCodes,
            'transactionCodeOptions' => $transactionCodes,
            'pkgTransactionCodeOptions' => $pkgTransactionCodes,
            'rateCodeDetailsList' => $rateCodeDetailsList, 
            'profileTypeOptions' => profileTypeList(), 
            'membershipTypes' => getMembershipTypeList(NULL, 'edit'), 
            'color_array' => jumble_color_array(),
            'jumble_array_javascript' => jumble_array_javascript(),
            'color_badges_javascript' => show_color_badges_javascript(),
            'toggleButton_javascript' => toggleButton_javascript(),
            'clearFormFields_javascript' => clearFormFields_javascript(),
            'blockLoader_javascript' => blockLoader_javascript()
        ];

        $data['session'] = $this->session;
        $data['title'] = getMethodName();
        $data['js_to_load'] = array("rateCodeForm.js", "rateCategory.js", "roomType.js", "packageList.js");

        return view('Reservation/RateCodeForm', $data);
    }

    public function deleteRateCode()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            $return = $this->Db->table('FLXY_RATE_CODE')->delete(['RT_CD_ID' => $sysid]);
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");

            if($result)
                $this->Db->table('FLXY_RATE_CODE')->delete(['RT_CD_ID' => $sysid]);

            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function getRateCodePackages($rcID = 0, $pkgId = 0, $rcPkgId = 0)
    {
        $param = ['SYSID' => $rcID];

        $sql = "SELECT FPC.PKG_CD_ID, RTRIM(LTRIM(REPLACE(REPLACE(REPLACE(FPC.PKG_CD_CODE, CHAR(9), ' '), CHAR(10), ' '), CHAR(13), ' '))) AS PKG_CD_CODE,
                FPC.PKG_CD_SHORT_DESC, 
                FPC.TR_CD_ID, FPC.PKG_CD_TAX_INCLUDED, FPC.PKG_CD_SELL_SEP,
                FRPC.RT_CD_PKG_ID,
                (CASE WHEN FRPC.RT_CD_PKG_DESC IS NOT NULL THEN FRPC.RT_CD_PKG_DESC ELSE FPC.PKG_CD_DESC END) AS PKG_CD_DESC, 
                (CASE WHEN FRPC.RT_INCL_ID IS NOT NULL THEN FRPC.RT_INCL_ID ELSE FPC.RT_INCL_ID END) AS RT_INCL_ID, 
                (CASE WHEN FRPC.PO_RH_ID IS NOT NULL THEN FRPC.PO_RH_ID ELSE FPC.PO_RH_ID END) AS PO_RH_ID, 
                (CASE WHEN FRPC.CLC_RL_ID IS NOT NULL THEN FRPC.CLC_RL_ID ELSE FPC.CLC_RL_ID END) AS CLC_RL_ID 
        
                FROM FLXY_PACKAGE_CODE FPC 
                LEFT JOIN FLXY_RATE_CODE_PACKAGE FRPC ON (FRPC.PKG_CD_ID = FPC.PKG_CD_ID AND FRPC.RT_CD_ID=:SYSID: AND FRPC.RT_CD_PKG_STATUS = 1)
                WHERE FPC.PKG_CD_STATUS = 1";

        if ($pkgId != 0) {
            $sql .= " AND FPC.PKG_CD_ID = $pkgId";
        }

        if ($rcPkgId != 0) {
            $sql .= " AND FRPC.RT_CD_PKG_ID = $rcPkgId";
        }

        $response = $this->Db->query($sql, $param)->getResultArray();
        return $response;
    }

    public function showRateCodePackageDetails()
    {
        $rcPackageDetailsList = $this->getRateCodePackages( $this->request->getPost('rcId'), 
                                                            null !== $this->request->getPost('pkgId') ? $this->request->getPost('pkgId') : 0, 
                                                            null !== $this->request->getPost('rcPkgId') ? $this->request->getPost('rcPkgId') : 0);
        
        $rcPackages = array();
        
        foreach($rcPackageDetailsList as $rcPackageDetailsItem)
        {
            $rcPackages[] = array(  'PKG_CD_ID' => $rcPackageDetailsItem['PKG_CD_ID'], 'PKG_CD_CODE' => $rcPackageDetailsItem['PKG_CD_CODE'], 
                                    'PKG_CD_DESC' => $rcPackageDetailsItem['PKG_CD_DESC'], 
                                    'PKG_CD_SHORT_DESC' => $rcPackageDetailsItem['PKG_CD_SHORT_DESC'], 
                                    'PKG_RT_TR_CD_ID' => $rcPackageDetailsItem['TR_CD_ID'], 'PKG_CD_TAX_INCLUDED' => $rcPackageDetailsItem['TR_CD_ID'], 
                                    'RT_CD_PKG_ID' => $rcPackageDetailsItem['RT_CD_PKG_ID'],
                                    'RT_INCL_ID' => $rcPackageDetailsItem['RT_INCL_ID'], 'PO_RH_ID' => $rcPackageDetailsItem['PO_RH_ID'], 
                                    'CLC_RL_ID' => $rcPackageDetailsItem['CLC_RL_ID'] );
        }
                                                            
        echo json_encode($rcPackages);
    }

    public function insertRateCodePackage()
    {
        try {
            $sysid = $this->request->getPost('RT_CD_PKG_ID');

            $validate = $this->validate([
                'PKG_CD_ID' => ['label' => 'Package', 'rules' => 'required'],
                'PKG_CD_CODE' => ['label' => 'Package Code', 'rules' => 'required'],
                'PKG_CD_DESC' => ['label' => 'Package Code Description', 'rules' => 'required']
            ]);

            //echo json_encode(print_r($_POST));
            //exit;

            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }

            $data = [
                "RT_CD_ID" => trim($this->request->getPost('RT_CD_ID')),
                "PKG_CD_ID" => trim($this->request->getPost('PKG_CD_ID')),
                "RT_CD_PKG_DESC" => trim($this->request->getPost('PKG_CD_DESC')),
                "RT_INCL_ID" => trim($this->request->getPost('RT_INCL_ID')),
                "PO_RH_ID" => trim($this->request->getPost('PO_RH_ID')),
                "CLC_RL_ID" => trim($this->request->getPost('CLC_RL_ID')),
            ];

            $return = !empty($sysid) ? $this->Db->table('FLXY_RATE_CODE_PACKAGE')->where('RT_CD_PKG_ID', $sysid)->update($data) : $this->Db->table('FLXY_RATE_CODE_PACKAGE')->insert($data);
            $newPkgCodeID = empty($sysid) ? $this->Db->insertID() : '';                        
                
            $result = $return ? $this->responseJson("1", "0", $return, !empty($sysid) ? $sysid : $newPkgCodeID) : $this->responseJson("-444", "db insert not successful", $return);

            echo json_encode($result);
            
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function deleteRatePackageCode()
    {
        $rcId = $this->request->getPost('rcId');
        $pkgId = $this->request->getPost('pkgId');

        try {
            $return = $this->Db->table('FLXY_RATE_CODE_PACKAGE')->delete(['RT_CD_ID' => $rcId, 'PKG_CD_ID' => $pkgId]);
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }
    
    public function updateRateCodeDetail()
    {
        try {
            $sysid = $this->request->getPost('RT_CD_DT_ID');

            $data = [
                "RT_CD_ID" => trim($this->request->getPost('RT_CD_ID')),
                "RT_CD_DT_MK_CD_ID" => trim($this->request->getPost('RT_CD_DT_MK_CD_ID')),
                "RT_CD_DT_SOR_ID" => trim($this->request->getPost('RT_CD_DT_SOR_ID')),
                "RT_CD_START_DT" => trim($this->request->getPost('RT_CD_START_DT')),
                "RT_CD_END_DT" => trim($this->request->getPost('RT_CD_END_DT')),
                "RT_CD_DT_1_ADULT" => trim($this->request->getPost('RT_CD_DT_1_ADULT')),
                "RT_CD_DT_2_ADULT" => trim($this->request->getPost('RT_CD_DT_2_ADULT')),
                "RT_CD_DT_3_ADULT" => trim($this->request->getPost('RT_CD_DT_3_ADULT')),
                "RT_CD_DT_4_ADULT" => trim($this->request->getPost('RT_CD_DT_4_ADULT')),
                "RT_CD_DT_5_ADULT" => trim($this->request->getPost('RT_CD_DT_5_ADULT')),
                "RT_CD_DT_EXTRA_ADULT" => trim($this->request->getPost('RT_CD_DT_EXTRA_ADULT')),
                "RT_CD_DT_EXTRA_CHILD" => trim($this->request->getPost('RT_CD_DT_EXTRA_CHILD')),
                "RT_CD_DT_1_CHILD" => trim($this->request->getPost('RT_CD_DT_1_CHILD')),
                "RT_CD_DT_2_CHILD" => trim($this->request->getPost('RT_CD_DT_2_CHILD')),
                "RT_CD_DT_3_CHILD" => trim($this->request->getPost('RT_CD_DT_3_CHILD')),
                "RT_CD_DT_4_CHILD" => trim($this->request->getPost('RT_CD_DT_4_CHILD')),
                "RT_CD_DT_ROOM_TYPES" => "",
                "RT_CD_DT_PACKAGES" => "",
                "RT_CD_DT_DAYS" => "",                
            ];

            // Get Room Type codes comma separated
            $allRoomTypes = $this->roomTypeList();

            $RT_CD_DT_ROOM_TYPE_IDS = $this->request->getPost('RT_CD_DT_ROOM_TYPES[]');

            if($RT_CD_DT_ROOM_TYPE_IDS != NULL)
            {
                foreach($RT_CD_DT_ROOM_TYPE_IDS as $RT_CD_DT_ROOM_TYPE_ID){
                    $rtKey = array_search($RT_CD_DT_ROOM_TYPE_ID, array_column($allRoomTypes, 'value')); // Find pos of ID in main array                    
                    $data["RT_CD_DT_ROOM_TYPES"] .= $allRoomTypes[$rtKey]['name'].',';                   // Get corresponding Room Type Code
                }
                $data["RT_CD_DT_ROOM_TYPES"] = rtrim($data["RT_CD_DT_ROOM_TYPES"], ',');
            }

            $rules = [  'RT_CD_ID' => ['label' => 'Rate Code ID', 'rules' => 'required'],
                        'RT_CD_START_DT' => ['label' => 'Start Date', 'rules' => 'required|dateOverlapCheck[RT_CD_START_DT,'.$data["RT_CD_DT_ROOM_TYPES"].']', 'errors' => ['dateOverlapCheck' => 'The Start Sell Date overlaps with an existing Rate Detail. Change the date or selected room type']],
                        'RT_CD_END_DT' => ['label' => 'End Date', 'rules' => 'required|compareDate|dateOverlapCheck[RT_CD_END_DT,'.$data["RT_CD_DT_ROOM_TYPES"].']', 'errors' => ['compareDate' => 'The End Sell Date should be after Begin Date', 'dateOverlapCheck' => 'The End Sell Date overlaps with an existing Rate Detail. Change the date or selected room type']],
                        'RT_CD_DT_DAYS' => ['label' => 'Days', 'rules' => 'required', 'errors' => ['required' => 'Please select at least one day']],
                        'RT_CD_DT_1_ADULT' => ['label' => 'Rate for 1 Adult', 'rules' => 'required'],
                        'RT_CD_DT_ROOM_TYPES' => ['label' => 'Room Types', 'rules' => 'required', 'errors' => ['required' => 'Please select at least one room type']],
                     ];

            $validate = $this->validate($rules);
            
            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }

            //echo json_encode(print_r($_POST)); exit;

            // Get Package Codes comma separated
            $allPackageCodes = $this->packageCodeList();

            $RT_CD_DT_PACKAGES = $this->request->getPost('RT_CD_DT_PACKAGES[]');

            if($RT_CD_DT_PACKAGES != NULL)
            {
                foreach($RT_CD_DT_PACKAGES as $RT_CD_DT_PACKAGE){
                    $rtKey = array_search($RT_CD_DT_PACKAGE, array_column($allPackageCodes, 'value')); // Find pos of ID in main array                    
                    $data["RT_CD_DT_PACKAGES"] .= $allPackageCodes[$rtKey]['name'].',';             // Get corresponding Room Type Code
                }
                $data["RT_CD_DT_PACKAGES"] = rtrim($data["RT_CD_DT_PACKAGES"], ',');
            }            

            //Check for days not checked

            $days = array();
            for ($i = 0; $i < 7; $i++) {
                $days[$i] = jddayofweek($i,2);
            }

            $not_checked = ''; $checked = 'ALL';
            
            foreach($days as $day)
            {
                $uday = strtoupper($day);
                if(!in_array($uday, $this->request->getPost('RT_CD_DT_DAYS[]')))
                    $not_checked .= $uday .',';
            }

            $data["RT_CD_DT_DAYS"] = ($not_checked != '' && count($this->request->getPost('RT_CD_DT_DAYS')) < 7) ? rtrim($not_checked, ',') : $checked;

            //echo json_encode(print_r($data)); exit;

            $return = !empty($sysid) ? $this->Db->table('FLXY_RATE_CODE_DETAIL')->where('RT_CD_DT_ID', $sysid)->update($data) : $this->Db->table('FLXY_RATE_CODE_DETAIL')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, !empty($sysid) ? $sysid : $this->Db->insertID()) : $this->responseJson("-444", "db insert not successful", $return);

            if(!$return)
                $this->session->setFlashdata('error', 'There has been an error. Please try again.');
            else
            {
                if(empty($sysid))
                    //$this->session->setFlashdata('success', 'The Rate Code has been updated.');
                    //else
                    $this->session->setFlashdata('success', 'The new Rate Code Detail has been created.');
            }
            echo json_encode($result);

        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function copyRateCodeDetail()
    {
        try {
            $_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);

            $param = [  'RT_CD_DT_ID' => $this->request->getPost('rep_RT_CD_DT_ID'),
                        'RT_CD_ID' => $this->request->getPost('rep_RT_CD_ID') ];

            $origRateCodeDetail = $this->getRateCodeDetails($param['RT_CD_ID'], $param['RT_CD_DT_ID']);

            //echo json_encode($response);
            //echo json_encode(print_r($origRateCodeDetail)); exit;

            $no_of_added = 0;
            $submitted_field = $this->request->getPost();

            //echo json_encode(print_r($submitted_field)); exit;

            if ($submitted_field != null) {
                
                $newRateCodeDetail = $origRateCodeDetail[0];
                unset($newRateCodeDetail['RT_CD_DT_ID']); 
                // to add copies as new entries
    
                $newRateCodeDetail["RT_CD_ID"] = trim($submitted_field["rep_RT_CD_ID"]);
                $newRateCodeDetail["RT_CD_START_DT"] = trim($submitted_field["rep_RT_CD_START_DT"]);
                $newRateCodeDetail["RT_CD_END_DT"] = trim($submitted_field["rep_RT_CD_END_DT"]);
                $newRateCodeDetail["RT_CD_DT_ROOM_TYPES"] = '';
                $newRateCodeDetail["RT_CD_DT_PACKAGES"] = null !== $this->request->getPost('rep_PACKAGES') ? $newRateCodeDetail["RT_CD_DT_PACKAGES"] : null;
                

                // Get Room Type codes comma separated
                $allRoomTypes = $this->roomTypeList();

                $RT_CD_DT_ROOM_TYPE_IDS = $this->request->getPost('rep_RT_CD_DT_ROOM_TYPES[]');

                if($RT_CD_DT_ROOM_TYPE_IDS != NULL)
                {
                    foreach($RT_CD_DT_ROOM_TYPE_IDS as $RT_CD_DT_ROOM_TYPE_ID){
                        $rtKey = array_search($RT_CD_DT_ROOM_TYPE_ID, array_column($allRoomTypes, 'value')); // Find pos of ID in main array                    
                        $newRateCodeDetail["RT_CD_DT_ROOM_TYPES"] .= $allRoomTypes[$rtKey]['name'].',';      // Get corresponding Room Type Code
                    }
                    $newRateCodeDetail["RT_CD_DT_ROOM_TYPES"] = rtrim($newRateCodeDetail["RT_CD_DT_ROOM_TYPES"], ',');
                }

                $validate = $this->validate([
                    'rep_RT_CD_DT_ID' => ['label' => 'Rate Code Detail', 'rules' => 'required'],
                    'rep_RT_CD_ID' => ['label' => 'Rate Code', 'rules' => 'required'],
                    'rep_RT_CD_START_DT' => ['label' => 'Start Date', 'rules' => 'required|dateOverlapCheck[rep_RT_CD_START_DT,'.$newRateCodeDetail["RT_CD_DT_ROOM_TYPES"].']', 'errors' => ['dateOverlapCheck' => 'The Start Sell Date overlaps with an existing Rate Detail. Change the date or selected room type']],
                    'rep_RT_CD_END_DT' => ['label' => 'End Date', 'rules' => 'required|compareDate|dateOverlapCheck[rep_RT_CD_END_DT,'.$newRateCodeDetail["RT_CD_DT_ROOM_TYPES"].']', 'errors' => ['compareDate' => 'The End Sell Date should be after Begin Date', 'dateOverlapCheck' => 'The End Sell Date overlaps with an existing Rate Detail. Change the date or selected room type']],
                    'rep_RT_CD_DT_DAYS' => ['label' => 'Days', 'rules' => 'required', 'errors' => ['required' => 'Please select at least one day']],
                    'rep_RT_CD_DT_ROOM_TYPES' => ['label' => 'Room Types', 'rules' => 'required', 'errors' => ['required' => 'Please select at least one room type']],
                ]);
    
                if (!$validate) {
                    $validate = $this->validator->getErrors();
                    $result["SUCCESS"] = "-402";
                    $result[]["ERROR"] = $validate;
                    $result = $this->responseJson("-402", $validate);
                    echo json_encode($result);
                    exit;
                }

                
                // Get Package Codes comma separated
                $allPackageCodes = $this->packageCodeList();

                $RT_CD_DT_PACKAGES = $this->request->getPost('rep_RT_CD_DT_PACKAGES[]');

                if($RT_CD_DT_PACKAGES != NULL)
                {
                    foreach($RT_CD_DT_PACKAGES as $RT_CD_DT_PACKAGE){
                        $rtKey = array_search($RT_CD_DT_PACKAGE, array_column($allPackageCodes, 'value')); // Find pos of ID in main array                    
                        $newRateCodeDetail["RT_CD_DT_PACKAGES"] .= $allPackageCodes[$rtKey]['name'].',';             // Get corresponding Room Type Code
                    }
                    $newRateCodeDetail["RT_CD_DT_PACKAGES"] = rtrim($newRateCodeDetail["RT_CD_DT_PACKAGES"], ',');
                }

                //Check for days not checked

                $days = array();
                for ($i = 0; $i < 7; $i++) {
                    $days[$i] = jddayofweek($i,2);
                }

                $not_checked = ''; $checked = 'ALL';
                
                foreach($days as $day)
                {
                    $uday = strtoupper($day);
                    if(!in_array($uday, $this->request->getPost('rep_RT_CD_DT_DAYS[]')))
                        $not_checked .= $uday .',';
                }

                $newRateCodeDetail["RT_CD_DT_DAYS"] = ($not_checked != '' && count($this->request->getPost('rep_RT_CD_DT_DAYS')) < 7) ? rtrim($not_checked, ',') : $checked;

                //echo json_encode(print_r($newRateCodeDetail)); exit;

                $return = $this->Db->table('FLXY_RATE_CODE_DETAIL')->insert($newRateCodeDetail);
                $result = $return ? $this->responseJson("1", "0", $return, $this->Db->insertID()) : $this->responseJson("-444", "db insert not successful", $return);
                echo json_encode($result);
            }
            //echo json_encode(print_r($_POST)); exit;
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function deleteRateCodeDetail()
    {
        $sysid = $this->request->getPost('sysid');
        $rateCodeID = $this->request->getPost('rateCodeID');

        $sql = "SELECT RT_CD_DT_ID
                FROM FLXY_RATE_CODE_DETAIL
                WHERE RT_CD_ID = '" . $rateCodeID . "'";

        $response = $this->Db->query($sql)->getNumRows();

        if($response <= 1) // Check if Rate Code Detail can be deleted
           echo json_encode($this->responseJson("0", "The Rate Detail cannot be deleted"));
        else
        {
            try {
                $return = $this->Db->table('FLXY_RATE_CODE_DETAIL')->delete(['RT_CD_DT_ID' => $sysid]); 
                //$return = NULL;
                $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
                echo json_encode($result);
            } catch (Exception $e) {
                return $this->respond($e->errors());
            }
        }
    }

    public function NegotiatedRateView()
    {
        $sysid = $this->request->getPost('sysid');

        $init_cond = array("RT_CD_ID = " => "'$sysid'"); // Add condition for main Rate Code

        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_RATE_CODE_NEGOTIATED_RATE_VIEW LEFT JOIN FLXY_CUSTOMER FCU ON (FCU.CUST_ID = FLXY_RATE_CODE_NEGOTIATED_RATE_VIEW.PROFILE_ID AND FLXY_RATE_CODE_NEGOTIATED_RATE_VIEW.PROFILE_TYPE = 1)';
        $columns = 'NG_RT_ID,RT_CD_ID,PROFILE_ID,PROFILE_TYPE,PROFILE_NAME,PROFILE_TYPE_NAME,CUST_CLIENT_ID,PROFILE_NUMBER,NG_RT_START_DT,NG_RT_END_DT,NG_RT_DIS_SEQ';
        $mine->generate_DatatTable($tableName, $columns, $init_cond);
        exit;
    }

    public function CombinedProfilesView()
    {
        $_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);

        $sysid = $this->request->getPost('sysid');
        $postValues = $this->request->getPost('columns');

        //echo json_encode(print_r($postValues));
        //exit;

        // Hide profiles with negotiated rates already created 
        /*
        $init_cond = array("CONCAT_WS('_',PROFILE_ID,PROFILE_TYPE) NOT IN" => "(SELECT CONCAT_WS('_',PROFILE_ID,PROFILE_TYPE)
        FROM FLXY_RATE_CODE_NEGOTIATED_RATE_VIEW WHERE RT_CD_ID = '".$sysid."')");
        */

        $search_keys = ['S_PROFILE_NAME', 'S_PROFILE_FIRST_NAME', 'S_PROFILE_COMMUNICATION', 'S_PROFILE_TYPE', 'S_PROFILE_CITY', 
                        'S_MEMBERSHIP_TYPE', 'S_MEMBERSHIP_NUMBER', 'S_PROFILE_PASSPORT', 'S_PROFILE_NUMBER', 
                        'S_AGN_IATA', 'S_COM_CORP_ID'];
        
        $init_cond = array();

        if($search_keys != NULL){
            foreach($search_keys as $search_key)
            {
                if(null !== $this->request->getPost($search_key) && !empty(trim($this->request->getPost($search_key))))
                {
                    $value = trim($this->request->getPost($search_key));

                    switch($search_key)
                    {
                        case 'S_PROFILE_COMMUNICATION': $init_cond["(PROFILE_NUMBER LIKE '%$value%' OR PROFILE_MOBILE LIKE '%$value%')"] = ""; break;
                        case 'S_MEMBERSHIP_TYPE': $init_cond["(SELECT COUNT(*) FROM FLXY_CUSTOMER_MEMBERSHIP WHERE CUST_ID = PROFILE_ID AND MEM_ID = '$value' AND CM_STATUS = 1) = "] = "1"; break;
                        case 'S_MEMBERSHIP_NUMBER': $init_cond["(SELECT COUNT(*) FROM FLXY_CUSTOMER_MEMBERSHIP WHERE CUST_ID = PROFILE_ID AND CM_CARD_NUMBER LIKE '%$value%' AND CM_STATUS = 1) = "] = "1"; break;
                        
                        default: $init_cond["".ltrim($search_key, "S_")." LIKE "] = "'%$value%'"; break;                        
                    }                    
                }
            }
        }
        
        //echo json_encode(print_r($init_cond));

        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_COMBINED_PROFILES_VIEW
                      LEFT JOIN FLXY_COMPANY_PROFILE ON (FLXY_COMPANY_PROFILE.COM_ID = [FLXY_COMBINED_PROFILES_VIEW].PROFILE_ID AND [FLXY_COMBINED_PROFILES_VIEW].PROFILE_TYPE = 2)
                      LEFT JOIN FLXY_AGENT_PROFILE ON (FLXY_AGENT_PROFILE.AGN_ID = [FLXY_COMBINED_PROFILES_VIEW].PROFILE_ID AND [FLXY_COMBINED_PROFILES_VIEW].PROFILE_TYPE = 3)';

        $columns = 'PROFILE_ID,PROFILE_NAME,PROFILE_FIRST_NAME,PROFILE_TYPE,PROFILE_ADDRESS,PROFILE_CITY,PROFILE_POSTAL_CODE,PROFILE_COMP_CODE,PROFILE_VIP,PROFILE_TITLE,PROFILE_COUNTRY,PROFILE_NUMBER,PROFILE_EMAIL,PROFILE_MOBILE,PROFILE_PASSPORT,COUNTRY_NAME,PROFILE_TYPE_NAME,COM_CORP_ID,AGN_ID';

        $mine->generate_DatatTable($tableName, $columns, $init_cond);
        exit;
    }

    public function insertNegotiatedRate()
    {
        try {
            $sysid = $this->request->getPost('NG_RT_ID');

            $validate = $this->validate([
                'neg_RT_CD_ID' => ['label' => 'Rate Code', 'rules' => 'required|is_unique[FLXY_RATE_CLASS.RT_CL_CODE,RT_CL_ID,' . $sysid . ']'],
                'neg_PROFILE_IDS' => ['label' => 'Profiles', 'rules' => 'required', 'errors' => ['required' => 'No Profiles have been selected. Please try again.']],
                'NG_RT_DIS_SEQ' => ['label' => 'Display Sequence', 'rules' => 'permit_empty|greater_than_equal_to[0]'],
                'NG_RT_START_DT' => ['label' => 'Start Date', 'rules' => 'required|dateOverlapCheckNR[NG_RT_START_DT,'.$this->request->getPost('neg_PROFILE_IDS').']', 'errors' => ['dateOverlapCheckNR' => 'The Start Date overlaps with an existing Negotiated Rate. Change the date or selected user(s)']],
                'NG_RT_END_DT' => ['label' => 'End Date', 'rules' => 'required|compareDate|dateOverlapCheckNR[NG_RT_END_DT,'.$this->request->getPost('neg_PROFILE_IDS').']', 'errors' => ['compareDate' => 'The End Date should be after Start Date', 'dateOverlapCheckNR' => 'The End Date overlaps with an existing Negotiated Rate. Change the date or selected user(s)']],
            ]);
            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }

            //echo json_encode(print_r($_POST));
            //exit;

            $profiles = explode(',', $this->request->getPost('neg_PROFILE_IDS'));
            $no_of_added = 0;
            if($profiles != NULL)
            {
                foreach($profiles as $profile_string)
                {
                    $profile_data = explode('_', $profile_string);
                    $data = [
                        "RT_CD_ID" => trim($this->request->getPost('neg_RT_CD_ID')),
                        "PROFILE_ID" => $profile_data[3],
                        "PROFILE_TYPE" => $profile_data[2],
                        "NG_RT_DIS_SEQ" => trim($this->request->getPost('NG_RT_DIS_SEQ')) != '' ? trim($this->request->getPost('NG_RT_DIS_SEQ')) : '',
                        "NG_RT_START_DT" => trim($this->request->getPost('NG_RT_START_DT')),
                        "NG_RT_END_DT" => trim($this->request->getPost('NG_RT_END_DT')) != '' ? trim($this->request->getPost('NG_RT_END_DT')) : '2030-12-31',
                    ];
        
                    //$return = $this->Db->table('FLXY_RATE_CODE_NEGOTIATED_RATE')->insert($data);
                    $return = !empty($sysid) ? $this->Db->table('FLXY_RATE_CODE_NEGOTIATED_RATE')->where('NG_RT_ID', $sysid)->update($data) : $this->Db->table('FLXY_RATE_CODE_NEGOTIATED_RATE')->insert($data);

                    $no_of_added += $this->Db->affectedRows();                    
                }
            }
            
            $result = $no_of_added > 0 ? $this->responseJson("1", "0", $return, $no_of_added) : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
            
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function deleteNegotiatedRate()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            $return = $this->Db->table('FLXY_RATE_CODE_NEGOTIATED_RATE')->delete(['NG_RT_ID' => $sysid]);
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    
    /**************      Membership Type Functions      ***************/

    public function membershipType()
    {
        $data['title'] = getMethodName();
        $data['session'] = $this->session;
        
        return view('Reservation/MembershipTypeView', $data);
    }

    public function MembershipTypeView()
    {
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_MEMBERSHIP';
        $columns = 'MEM_ID,MEM_CODE,MEM_DESC,MEM_DIS_SEQ,MEM_STATUS';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function insertMembershipType()
    {
        try {
            $sysid = $this->request->getPost('MEM_ID');

            $validate = $this->validate([
                'MEM_CODE' => ['label' => 'Membership Type', 'rules' => 'required|is_unique[FLXY_MEMBERSHIP.MEM_CODE,MEM_ID,' . $sysid . ']'],
                'MEM_DESC' => ['label' => 'Description', 'rules' => 'required']
            ]);
            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }

            //echo json_encode(print_r($_POST)); exit;

            $data = [
                "MEM_CODE" => trim($this->request->getPost('MEM_CODE')),
                "MEM_DESC" => trim($this->request->getPost('MEM_DESC')),
                "MEM_DIS_SEQ" => trim($this->request->getPost('MEM_DIS_SEQ')),
                "MEM_POINT_LABEL" => trim($this->request->getPost('MEM_POINT_LABEL')),
                "MEM_CARD_LENGTH" => trim($this->request->getPost('MEM_CARD_LENGTH')),
                "MEM_CARD_PREFIX" => trim($this->request->getPost('MEM_CARD_PREFIX')),
                "MEM_EXP_DATE_REQ" => null !== $this->request->getPost('MEM_EXP_DATE_REQ') ? '1' : '0',
                "MEM_STATUS" => null !== $this->request->getPost('MEM_STATUS') ? '1' : '0',
            ];

            $return = !empty($sysid) ? $this->Db->table('FLXY_MEMBERSHIP')->where('MEM_ID', $sysid)->update($data) : $this->Db->table('FLXY_MEMBERSHIP')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function checkMembershipType($rcCode)
    {
        $sql = "SELECT MEM_ID
                FROM FLXY_MEMBERSHIP
                WHERE MEM_CODE = '" . $rcCode . "'";

        $response = $this->Db->query($sql)->getNumRows();
        return $rcCode == '' || strlen($rcCode) > 10 ? 1 : $response; // Send found row even if submitted code is empty
    }

    public function editMembershipType()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];

        $sql = "SELECT FMT.*
                FROM dbo.FLXY_MEMBERSHIP AS FMT
                WHERE MEM_ID=:SYSID:";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function copyMembershipType()
    {
        try {
            $param = ['SYSID' => $this->request->getPost('main_MEM_ID')];

            $sql = "SELECT *
                    FROM FLXY_MEMBERSHIP
                    WHERE MEM_ID=:SYSID:";

            $origMemType = $this->Db->query($sql, $param)->getResultArray()[0];

            //echo json_encode($response);
            //echo json_encode(print_r($origMemType)); exit;

            $no_of_added = 0;
            $submitted_fields = $this->request->getPost('group-a');

            if ($submitted_fields != null) {
                foreach ($submitted_fields as $submitted_field) {
                    if (!$this->checkMembershipType($submitted_field['MEM_CODE'])) // Check if entered Rate Class already exists
                    {
                        $newMemType = [
                            "MEM_CODE" => trim($submitted_field["MEM_CODE"]),
                            "MEM_DESC" => trim($submitted_field["MEM_DESC"]),
                            "MEM_DIS_SEQ" => $origMemType["MEM_DIS_SEQ"],
                            "MEM_POINT_LABEL" => $origMemType["MEM_POINT_LABEL"],
                            "MEM_CARD_LENGTH" => $origMemType["MEM_CARD_LENGTH"],
                            "MEM_CARD_PREFIX" => $origMemType["MEM_CARD_PREFIX"],
                            "MEM_EXP_DATE_REQ" => $origMemType["MEM_EXP_DATE_REQ"],
                        ];

                        $this->Db->table('FLXY_MEMBERSHIP')->insert($newMemType);

                        $no_of_added += $this->Db->affectedRows();
                    }
                }
            }

            echo $no_of_added;
            exit;

            //echo json_encode(print_r($_POST)); exit;
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function deleteMembershipType()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            $return = $this->Db->table('FLXY_MEMBERSHIP')->delete(['MEM_ID' => $sysid]);
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }
}