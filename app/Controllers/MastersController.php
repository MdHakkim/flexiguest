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
            $options[] = array(
                "value" => $row['RT_CT_ID'], "name" => $row['RT_CT_CODE'], "desc" => $row['RT_CT_DESC'],
                "rt_class" => $row['RT_CL_CODE'], "begin_date" => $row['RT_CT_BEGIN_DT'], "end_date" => $row['RT_CT_END_DT']
            );
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

            if (empty($sysid)) {
                $blank_package_detail = [
                    "PKG_CD_ID" => $newPkgCodeID,
                    "PKG_CD_START_DT" => date('Y-m-d'),
                    "PKG_CD_END_DT" => date('Y-m-d', strtotime('+10 years')),
                    "PKG_CD_DT_PRICE" => '0'
                ];

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

            $rules = [
                'PKG_CD_ID' => ['label' => 'Package Code ID', 'rules' => 'required'],
                'PKG_CD_START_DT' => ['label' => 'Start Date', 'rules' => 'required'],
                'PKG_CD_END_DT' => ['label' => 'End Date', 'rules' => 'required|compareDate', 'errors' => ['compareDate' => 'The End Date should be after Begin Date']],
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

            if (!$return)
                $this->session->setFlashdata('error', 'There has been an error. Please try again.');
            else {
                if (empty($sysid))
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

        if ($response <= 1) // Check if Package Code Detail can be deleted
            echo json_encode($this->responseJson("0", "The Package Detail cannot be deleted"));
        else {
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
            'selectedRoomTypes' => $roomTypes,
            'packageCodeOptions' => $packageCodes,
            'marketCodeOptions' => $marketCodes,
            'sourceCodeOptions' => $sourceCodes,
            'transactionCodeOptions' => $transactionCodes,
            'pkgTransactionCodeOptions' => $pkgTransactionCodes,
            'toggleButton_javascript' => toggleButton_javascript(),
            'profileTypeOptions' => profileTypeList(),
            'membershipTypes' => getMembershipTypeList(NULL, 'edit'),
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
                'RT_CD_BEGIN_SELL_DT' => ['label' => 'Begin Booking Date', 'rules' => 'required'],
                'RT_CD_END_SELL_DT' => ['label' => 'End Booking Date', 'rules' => 'required|compareDate', 'errors' => ['compareDate' => 'The End Booking Date should be after Begin Date']],
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
            if ($RT_CD_ROOM_TYPES != NULL) {
                foreach ($RT_CD_ROOM_TYPES as $RT_CD_ROOM_TYPE) {
                    $data["RT_CD_ROOM_TYPES"] .= $RT_CD_ROOM_TYPE['name'] . ',';
                }
                $data["RT_CD_ROOM_TYPES"] = rtrim($data["RT_CD_ROOM_TYPES"], ',');
            }

            $RT_CD_PACKAGE_CODES = json_decode($this->request->getPost('RT_CD_PACKAGES'), true);
            if ($RT_CD_PACKAGE_CODES != NULL) {
                foreach ($RT_CD_PACKAGE_CODES as $RT_CD_PACKAGE_CODE) {
                    $data["RT_CD_PACKAGES"] .= $RT_CD_PACKAGE_CODE['name'] . ',';
                }
                $data["RT_CD_PACKAGES"] = rtrim($data["RT_CD_PACKAGES"], ',');
            }

            //echo json_encode(print_r($data)); exit;

            $return = !empty($sysid) ? $this->Db->table('FLXY_RATE_CODE')->where('RT_CD_ID', $sysid)->update($data) : $this->Db->table('FLXY_RATE_CODE')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, !empty($sysid) ? '' : $this->Db->insertID()) : $this->responseJson("-444", "db insert not successful", $return);

            if (!$return)
                $this->session->setFlashdata('error', 'There has been an error. Please try again.');
            else {
                if (empty($sysid))
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
                        if ($newRateCodeID)
                            $no_of_added += 1;

                        //Copy Rate Code details
                        if ($origRateCodeDetailsList != NULL) {
                            foreach ($origRateCodeDetailsList as $origRateCodeDetail) {
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
        $id = (int)$id;

        if (!$id)
            return redirect()->to(base_url('rateCode'));

        $rateCodeDetails = $this->getRateCodeInfo($id);

        if (!$rateCodeDetails) {
            $this->session->setFlashdata('error', 'Rate Code does not exist. Please try again.');
            return redirect()->to(base_url('rateCode'));
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
            if ($row['RT_CD_PKG_ID'] == NULL) continue;

            $selectedPackageCodes[] = array(
                "id" => $row['RT_CD_PKG_ID'], "value" => $row['PKG_CD_ID'],
                "name" => $row['PKG_CD_CODE'], "desc" => $row['PKG_CD_DESC']
            );
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
        if ($rateCodeDetailsList != NULL) {
            $no_of_details = count($rateCodeDetailsList);
            for ($i = 0; $i < $no_of_details; $i++) {
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

        $data['showTab'] = null !== $this->request->getGet("showTab") ? $this->request->getGet("showTab") : null;

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

            if ($result)
                $this->Db->table('FLXY_RATE_CODE')->delete(['RT_CD_ID' => $sysid]);

            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function checkRoomTypeinRateCodeDetail()
    {
        $rate_code_id = $this->request->getPost('rate_code_id');
        $room_type = $this->request->getPost('room_type');

        $sql = "SELECT RT_CD_DT_ID FROM FLXY_RATE_CODE_DETAIL 
                WHERE RT_CD_ID = '" . $rate_code_id . "'";

        if (!empty($room_type)) {
            $sql .= " AND CONCAT(',', RT_CD_DT_ROOM_TYPES, ',') LIKE '%," . $room_type . ",%'";
        }

        $response = $this->Db->query($sql)->getNumRows();
        echo ($response > 0 ? "0" : "1");
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
        $rcPackageDetailsList = $this->getRateCodePackages(
            $this->request->getPost('rcId'),
            null !== $this->request->getPost('pkgId') ? $this->request->getPost('pkgId') : 0,
            null !== $this->request->getPost('rcPkgId') ? $this->request->getPost('rcPkgId') : 0
        );

        $rcPackages = array();

        foreach ($rcPackageDetailsList as $rcPackageDetailsItem) {
            $rcPackages[] = array(
                'PKG_CD_ID' => $rcPackageDetailsItem['PKG_CD_ID'], 'PKG_CD_CODE' => $rcPackageDetailsItem['PKG_CD_CODE'],
                'PKG_CD_DESC' => $rcPackageDetailsItem['PKG_CD_DESC'],
                'PKG_CD_SHORT_DESC' => $rcPackageDetailsItem['PKG_CD_SHORT_DESC'],
                'PKG_RT_TR_CD_ID' => $rcPackageDetailsItem['TR_CD_ID'], 'PKG_CD_TAX_INCLUDED' => $rcPackageDetailsItem['TR_CD_ID'],
                'RT_CD_PKG_ID' => $rcPackageDetailsItem['RT_CD_PKG_ID'],
                'RT_INCL_ID' => $rcPackageDetailsItem['RT_INCL_ID'], 'PO_RH_ID' => $rcPackageDetailsItem['PO_RH_ID'],
                'CLC_RL_ID' => $rcPackageDetailsItem['CLC_RL_ID']
            );
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
                "RT_CD_DT_1_ADULT" => setZeroOnEmpty($this->request->getPost('RT_CD_DT_1_ADULT')),
                "RT_CD_DT_2_ADULT" => setZeroOnEmpty($this->request->getPost('RT_CD_DT_2_ADULT')),
                "RT_CD_DT_3_ADULT" => setZeroOnEmpty($this->request->getPost('RT_CD_DT_3_ADULT')),
                "RT_CD_DT_4_ADULT" => setZeroOnEmpty($this->request->getPost('RT_CD_DT_4_ADULT')),
                "RT_CD_DT_5_ADULT" => setZeroOnEmpty($this->request->getPost('RT_CD_DT_5_ADULT')),
                "RT_CD_DT_EXTRA_ADULT" => setZeroOnEmpty($this->request->getPost('RT_CD_DT_EXTRA_ADULT')),
                "RT_CD_DT_EXTRA_CHILD" => setZeroOnEmpty($this->request->getPost('RT_CD_DT_EXTRA_CHILD')),
                "RT_CD_DT_1_CHILD" => setZeroOnEmpty($this->request->getPost('RT_CD_DT_1_CHILD')),
                "RT_CD_DT_2_CHILD" => setZeroOnEmpty($this->request->getPost('RT_CD_DT_2_CHILD')),
                "RT_CD_DT_3_CHILD" => setZeroOnEmpty($this->request->getPost('RT_CD_DT_3_CHILD')),
                "RT_CD_DT_4_CHILD" => setZeroOnEmpty($this->request->getPost('RT_CD_DT_4_CHILD')),
                "RT_CD_DT_ROOM_TYPES" => "",
                "RT_CD_DT_PACKAGES" => "",
                "RT_CD_DT_DAYS" => "",
            ];

            // Get Room Type codes comma separated
            $allRoomTypes = $this->roomTypeList();

            $RT_CD_DT_ROOM_TYPE_IDS = $this->request->getPost('RT_CD_DT_ROOM_TYPES[]');

            if ($RT_CD_DT_ROOM_TYPE_IDS != NULL) {
                foreach ($RT_CD_DT_ROOM_TYPE_IDS as $RT_CD_DT_ROOM_TYPE_ID) {
                    $rtKey = array_search($RT_CD_DT_ROOM_TYPE_ID, array_column($allRoomTypes, 'value')); // Find pos of ID in main array                    
                    $data["RT_CD_DT_ROOM_TYPES"] .= $allRoomTypes[$rtKey]['name'] . ',';                   // Get corresponding Room Type Code
                }
                $data["RT_CD_DT_ROOM_TYPES"] = rtrim($data["RT_CD_DT_ROOM_TYPES"], ',');
            }

            $rules = [
                'RT_CD_ID' => ['label' => 'Rate Code ID', 'rules' => 'required'],
                'RT_CD_START_DT' => ['label' => 'Start Date', 'rules' => 'required|dateOverlapCheck[RT_CD_START_DT,' . $data["RT_CD_DT_ROOM_TYPES"] . ']', 'errors' => ['dateOverlapCheck' => 'The Start Booking Date overlaps with an existing Rate Detail. Change the date or selected room type']],
                'RT_CD_END_DT' => ['label' => 'End Date', 'rules' => 'required|compareDate|dateOverlapCheck[RT_CD_END_DT,' . $data["RT_CD_DT_ROOM_TYPES"] . ']', 'errors' => ['compareDate' => 'The End Booking Date should be after Begin Date', 'dateOverlapCheck' => 'The End Booking Date overlaps with an existing Rate Detail. Change the date or selected room type']],
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

            if ($RT_CD_DT_PACKAGES != NULL) {
                foreach ($RT_CD_DT_PACKAGES as $RT_CD_DT_PACKAGE) {
                    $rtKey = array_search($RT_CD_DT_PACKAGE, array_column($allPackageCodes, 'value')); // Find pos of ID in main array                    
                    $data["RT_CD_DT_PACKAGES"] .= $allPackageCodes[$rtKey]['name'] . ',';             // Get corresponding Room Type Code
                }
                $data["RT_CD_DT_PACKAGES"] = rtrim($data["RT_CD_DT_PACKAGES"], ',');
            }

            //Check for days not checked

            $days = array();
            for ($i = 0; $i < 7; $i++) {
                $days[$i] = jddayofweek($i, 2);
            }

            $not_checked = '';
            $checked = 'ALL';

            foreach ($days as $day) {
                $uday = strtoupper($day);
                if (!in_array($uday, $this->request->getPost('RT_CD_DT_DAYS[]')))
                    $not_checked .= $uday . ',';
            }

            $data["RT_CD_DT_DAYS"] = ($not_checked != '' && count($this->request->getPost('RT_CD_DT_DAYS')) < 7) ? rtrim($not_checked, ',') : $checked;

            //echo json_encode(print_r($data)); exit;

            $return = !empty($sysid) ? $this->Db->table('FLXY_RATE_CODE_DETAIL')->where('RT_CD_DT_ID', $sysid)->update($data) : $this->Db->table('FLXY_RATE_CODE_DETAIL')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, !empty($sysid) ? $sysid : $this->Db->insertID()) : $this->responseJson("-444", "db insert not successful", $return);

            if (!$return)
                $this->session->setFlashdata('error', 'There has been an error. Please try again.');
            else {
                if (empty($sysid))
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

            $param = [
                'RT_CD_DT_ID' => $this->request->getPost('rep_RT_CD_DT_ID'),
                'RT_CD_ID' => $this->request->getPost('rep_RT_CD_ID')
            ];

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

                if ($RT_CD_DT_ROOM_TYPE_IDS != NULL) {
                    foreach ($RT_CD_DT_ROOM_TYPE_IDS as $RT_CD_DT_ROOM_TYPE_ID) {
                        $rtKey = array_search($RT_CD_DT_ROOM_TYPE_ID, array_column($allRoomTypes, 'value')); // Find pos of ID in main array                    
                        $newRateCodeDetail["RT_CD_DT_ROOM_TYPES"] .= $allRoomTypes[$rtKey]['name'] . ',';      // Get corresponding Room Type Code
                    }
                    $newRateCodeDetail["RT_CD_DT_ROOM_TYPES"] = rtrim($newRateCodeDetail["RT_CD_DT_ROOM_TYPES"], ',');
                }

                $validate = $this->validate([
                    'rep_RT_CD_DT_ID' => ['label' => 'Rate Code Detail', 'rules' => 'required'],
                    'rep_RT_CD_ID' => ['label' => 'Rate Code', 'rules' => 'required'],
                    'rep_RT_CD_START_DT' => ['label' => 'Start Date', 'rules' => 'required|dateOverlapCheck[rep_RT_CD_START_DT,' . $newRateCodeDetail["RT_CD_DT_ROOM_TYPES"] . ']', 'errors' => ['dateOverlapCheck' => 'The Start Booking Date overlaps with an existing Rate Detail. Change the date or selected room type']],
                    'rep_RT_CD_END_DT' => ['label' => 'End Date', 'rules' => 'required|compareDate|dateOverlapCheck[rep_RT_CD_END_DT,' . $newRateCodeDetail["RT_CD_DT_ROOM_TYPES"] . ']', 'errors' => ['compareDate' => 'The End Booking Date should be after Begin Date', 'dateOverlapCheck' => 'The End Booking Date overlaps with an existing Rate Detail. Change the date or selected room type']],
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

                if ($RT_CD_DT_PACKAGES != NULL) {
                    foreach ($RT_CD_DT_PACKAGES as $RT_CD_DT_PACKAGE) {
                        $rtKey = array_search($RT_CD_DT_PACKAGE, array_column($allPackageCodes, 'value')); // Find pos of ID in main array                    
                        $newRateCodeDetail["RT_CD_DT_PACKAGES"] .= $allPackageCodes[$rtKey]['name'] . ',';             // Get corresponding Room Type Code
                    }
                    $newRateCodeDetail["RT_CD_DT_PACKAGES"] = rtrim($newRateCodeDetail["RT_CD_DT_PACKAGES"], ',');
                }

                //Check for days not checked

                $days = array();
                for ($i = 0; $i < 7; $i++) {
                    $days[$i] = jddayofweek($i, 2);
                }

                $not_checked = '';
                $checked = 'ALL';

                foreach ($days as $day) {
                    $uday = strtoupper($day);
                    if (!in_array($uday, $this->request->getPost('rep_RT_CD_DT_DAYS[]')))
                        $not_checked .= $uday . ',';
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

        if ($response <= 1) // Check if Rate Code Detail can be deleted
            echo json_encode($this->responseJson("0", "The Rate Detail cannot be deleted"));
        else {
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

        $search_keys = [
            'S_PROFILE_NAME', 'S_PROFILE_FIRST_NAME', 'S_PROFILE_COMMUNICATION', 'S_PROFILE_TYPE', 'S_CITY_NAME','S_PROFILE_POSTAL_CODE',
            'S_MEMBERSHIP_TYPE', 'S_MEMBERSHIP_NUMBER', 'S_PROFILE_PASSPORT', 'S_PROFILE_NUMBER',
            'S_AGN_IATA', 'S_COM_CORP_ID', 'S_REMOVE_PROFILES'
        ];

        $init_cond = array();

        if ($search_keys != NULL) {
            foreach ($search_keys as $search_key) {
                if (null !== $this->request->getPost($search_key) && !empty(trim($this->request->getPost($search_key)))) {
                    $value = trim($this->request->getPost($search_key));

                    

                    switch ($search_key) {
                        case 'S_PROFILE_COMMUNICATION':
                            $init_cond["(PROFILE_NUMBER LIKE '%$value%' OR PROFILE_MOBILE LIKE '%$value%' OR PROFILE_EMAIL LIKE '%$value%')"] = "";
                            break;
                        case 'S_MEMBERSHIP_TYPE':
                            $init_cond["(SELECT COUNT(*) FROM FLXY_CUSTOMER_MEMBERSHIP WHERE CUST_ID = PROFILE_ID AND MEM_ID = '$value' AND CM_STATUS = 1) = "] = "1";
                            break;
                        case 'S_MEMBERSHIP_NUMBER':
                            $init_cond["(SELECT COUNT(*) FROM FLXY_CUSTOMER_MEMBERSHIP WHERE CUST_ID = PROFILE_ID AND CM_CARD_NUMBER LIKE '%$value%' AND CM_STATUS = 1) = "] = "1";
                            break;

                        case 'S_REMOVE_PROFILES':
                            $remove_profiles = json_decode($value, true);
                            $remove_profiles_str = '';

                            if ($remove_profiles != NULL) {
                                foreach ($remove_profiles as $remove_profile) {
                                    $remove_profiles_str .= "'" . $remove_profile['prof_id'] . "_" . $remove_profile['prof_type'] . "',";
                                }
                                $remove_profiles_str = rtrim($remove_profiles_str, ',');

                                $init_cond["CONCAT_WS('_',PROFILE_ID,PROFILE_TYPE) NOT IN"] = "(" . $remove_profiles_str . ")";
                            }
                            break;
                        default:
                            $init_cond["" . ltrim($search_key, "S_") . " LIKE "] = "'%$value%'";
                            break;
                    }
                }
            }
        }

        //echo json_encode(print_r($init_cond));

        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_COMBINED_PROFILES_VIEW
                      LEFT JOIN FLXY_COMPANY_PROFILE ON (FLXY_COMPANY_PROFILE.COM_ID = [FLXY_COMBINED_PROFILES_VIEW].PROFILE_ID AND [FLXY_COMBINED_PROFILES_VIEW].PROFILE_TYPE = 2)
                      LEFT JOIN FLXY_AGENT_PROFILE ON (FLXY_AGENT_PROFILE.AGN_ID = [FLXY_COMBINED_PROFILES_VIEW].PROFILE_ID AND [FLXY_COMBINED_PROFILES_VIEW].PROFILE_TYPE = 3)
                      LEFT JOIN (SELECT NR.PROFILE_ID AS P_ID, NR.PROFILE_TYPE AS P_TYPE, 
                                        STRING_AGG(RC.RT_CD_CODE, \', \') WITHIN GROUP (ORDER BY RC.RT_CD_CODE) AS RATE_CODES
                                 FROM FLXY_RATE_CODE_NEGOTIATED_RATE NR
                                 LEFT JOIN FLXY_RATE_CODE RC ON RC.RT_CD_ID = NR.RT_CD_ID
                                 GROUP BY NR.PROFILE_ID,NR.PROFILE_TYPE) PRC ON (PRC.P_ID = [FLXY_COMBINED_PROFILES_VIEW].PROFILE_ID 
                                                                                 AND PRC.P_TYPE = [FLXY_COMBINED_PROFILES_VIEW].PROFILE_TYPE)';

        $columns = 'PROFILE_ID,PROFILE_NAME,PROFILE_FIRST_NAME,PROFILE_TYPE,PROFILE_ADDRESS,PROFILE_CITY,PROFILE_POSTAL_CODE,PROFILE_COMP_CODE,PROFILE_VIP,RATE_CODES,PROFILE_TITLE,PROFILE_COUNTRY,PROFILE_NUMBER,PROFILE_EMAIL,PROFILE_MOBILE,PROFILE_PASSPORT,COUNTRY_NAME,CITY_NAME,PROFILE_TYPE_NAME,COM_CORP_ID,AGN_ID';

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
                'NG_RT_START_DT' => ['label' => 'Start Date', 'rules' => 'required|dateOverlapCheckNR[NG_RT_START_DT,' . $this->request->getPost('neg_PROFILE_IDS') . ']', 'errors' => ['dateOverlapCheckNR' => 'The Start Date overlaps with an existing Negotiated Rate. Change the date or selected user(s)']],
                'NG_RT_END_DT' => ['label' => 'End Date', 'rules' => 'required|compareDate|dateOverlapCheckNR[NG_RT_END_DT,' . $this->request->getPost('neg_PROFILE_IDS') . ']', 'errors' => ['compareDate' => 'The End Date should be after Start Date', 'dateOverlapCheckNR' => 'The End Date overlaps with an existing Negotiated Rate. Change the date or selected user(s)']],
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
            if ($profiles != NULL) {
                foreach ($profiles as $profile_string) {
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

    /**************      Preference Group Functions      ***************/

    public function preferenceGroup()
    {
        $data['title'] = getMethodName();
        $data['session'] = $this->session;

        return view('Reservation/PreferenceGroupView', $data);
    }

    public function PreferenceGroupView()
    {
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_PREFERENCE_GROUP';
        $columns = 'PF_GR_ID,PF_GR_CODE,PF_GR_DESC,PF_GR_DIS_SEQ,PF_GR_STATUS';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function insertPreferenceGroup()
    {
        try {
            $sysid = $this->request->getPost('PF_GR_ID');

            $validate = $this->validate([
                'PF_GR_CODE' => ['label' => 'Group Code', 'rules' => 'required|is_unique[FLXY_PREFERENCE_GROUP.PF_GR_CODE,PF_GR_ID,' . $sysid . ']'],
                'PF_GR_DESC' => ['label' => 'Group Description', 'rules' => 'required'],
                'PF_GR_MAX_QTY' => ['label' => 'Maximum Quantity', 'rules' => 'permit_empty|greater_than_equal_to[0]'],
                'PF_GR_DIS_SEQ' => ['label' => 'Display Sequence', 'rules' => 'permit_empty|greater_than_equal_to[0]'],
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
                "PF_GR_CODE" => trim($this->request->getPost('PF_GR_CODE')),
                "PF_GR_DESC" => trim($this->request->getPost('PF_GR_DESC')),
                "PF_GR_DIS_SEQ" => trim($this->request->getPost('PF_GR_DIS_SEQ')) != '' ? trim($this->request->getPost('PF_GR_DIS_SEQ')) : '',
                "PF_GR_MAX_QTY" => trim($this->request->getPost('PF_GR_MAX_QTY')),
                "PF_GR_RESV_PREF" => trim($this->request->getPost('PF_GR_RESV_PREF')),
                "PF_GR_STATUS" => trim($this->request->getPost('PF_GR_STATUS')),
            ];

            $return = !empty($sysid) ? $this->Db->table('FLXY_PREFERENCE_GROUP')->where('PF_GR_ID', $sysid)->update($data) : $this->Db->table('FLXY_PREFERENCE_GROUP')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function checkPreferenceGroup($rcCode)
    {
        $sql = "SELECT PF_GR_ID
                FROM FLXY_PREFERENCE_GROUP
                WHERE PF_GR_CODE = '" . $rcCode . "'";

        $response = $this->Db->query($sql)->getNumRows();
        return $rcCode == '' || strlen($rcCode) > 10 ? 1 : $response; // Send found row even if submitted code is empty
    }

    public function preferenceGroupList()
    {
        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

        $sql = "SELECT PF_GR_ID, PF_GR_CODE, PF_GR_DESC
                FROM FLXY_PREFERENCE_GROUP WHERE PF_GR_STATUS = 1 ";

        if ($search != '') {
            $sql .= " AND PF_GR_CODE LIKE '%$search%'
                      OR PF_GR_DESC LIKE '%$search%'";
        }

        $response = $this->Db->query($sql)->getResultArray();

        $option = '<option value="">Choose an Option</option>';
        foreach ($response as $row) {
            $option .= '<option value="' . $row['PF_GR_ID'] . '">' . $row['PF_GR_CODE'] . ' | ' . $row['PF_GR_DESC'] . '</option>';
        }

        return $option;
    }

    public function editPreferenceGroup()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];

        $sql = "SELECT *
                FROM FLXY_PREFERENCE_GROUP
                WHERE PF_GR_ID=:SYSID:";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function deletePreferenceGroup()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            $return = $this->Db->table('FLXY_PREFERENCE_GROUP')->delete(['PF_GR_ID' => $sysid]);
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    /**************      Preference Code Functions      ***************/

    public function preferenceCode()
    {
        $preferenceGroups = $this->preferenceGroupList();

        $data = [
            'preferenceGroupOptions' => $preferenceGroups,
        ];

        $data['title'] = getMethodName();
        $data['session'] = $this->session;

        return view('Reservation/PreferenceCodeView', $data);
    }

    public function PreferenceCodeView()
    {
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_PREFERENCE_CODE
        LEFT JOIN FLXY_PREFERENCE_GROUP FMG ON FMG.PF_GR_ID = FLXY_PREFERENCE_CODE.PF_GR_ID';

        $columns = 'PF_CD_ID,PF_CD_CODE,PF_CD_DESC,PF_GR_CODE,PF_CD_DIS_SEQ,PF_CD_STATUS';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function insertPreferenceCode()
    {
        try {
            $sysid = $this->request->getPost('PF_CD_ID');

            $validate = $this->validate([
                'PF_CD_CODE' => ['label' => 'Preference Code', 'rules' => 'required|is_unique[FLXY_PREFERENCE_CODE.PF_CD_CODE,PF_CD_ID,' . $sysid . ']'],
                'PF_CD_DESC' => ['label' => 'Description', 'rules' => 'required'],
                'PF_GR_ID' => ['label' => 'Preference Group', 'rules' => 'required'],
                'PF_CD_DIS_SEQ' => ['label' => 'Display Sequence', 'rules' => 'permit_empty|greater_than_equal_to[0]'],
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
                "PF_CD_CODE" => trim($this->request->getPost('PF_CD_CODE')),
                "PF_CD_DESC" => trim($this->request->getPost('PF_CD_DESC')),
                "PF_GR_ID" => trim($this->request->getPost('PF_GR_ID')),
                "PF_CD_DIS_SEQ" => trim($this->request->getPost('PF_CD_DIS_SEQ')),
                "PF_CD_STATUS" => trim($this->request->getPost('PF_CD_STATUS')),
            ];

            $return = !empty($sysid) ? $this->Db->table('FLXY_PREFERENCE_CODE')->where('PF_CD_ID', $sysid)->update($data) : $this->Db->table('FLXY_PREFERENCE_CODE')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function checkPreferenceCode($rcCode)
    {
        $sql = "SELECT PF_CD_ID
                FROM FLXY_PREFERENCE_CODE
                WHERE PF_CD_CODE = '" . $rcCode . "'";

        $response = $this->Db->query($sql)->getNumRows();
        return $rcCode == '' || strlen($rcCode) > 10 ? 1 : $response; // Send found row even if submitted code is empty
    }

    public function preferenceCodeList()
    {
        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

        $sql = "SELECT PF_CD_ID, PF_CD_CODE, PF_CD_DESC
                FROM FLXY_PREFERENCE_CODE WHERE PF_CD_STATUS = 1";

        if ($search != '') {
            $sql .= " AND (PF_CD_CODE LIKE '%$search%'
                      OR PF_CD_DESC LIKE '%$search%') ";
        }

        $sql .= " ORDER BY PF_CD_DIS_SEQ ASC";

        $response = $this->Db->query($sql)->getResultArray();

        $option = '<option value="">Choose an Option</option>';
        foreach ($response as $row) {
            $option .= '<option value="' . $row['PF_CD_ID'] . '">' . $row['PF_CD_CODE'] . ' | ' . $row['PF_CD_DESC'] . '</option>';
        }

        return $option;
    }

    public function editPreferenceCode()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];

        $sql = "SELECT FTC.*, FMG.PF_GR_CODE
                FROM dbo.FLXY_PREFERENCE_CODE AS FTC
                LEFT JOIN FLXY_PREFERENCE_GROUP FMG ON FMG.PF_GR_ID = FTC.PF_GR_ID
                WHERE PF_CD_ID=:SYSID:";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function deletePreferenceCode()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            $return = $this->Db->table('FLXY_PREFERENCE_CODE')->delete(['PF_CD_ID' => $sysid]);
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    /**************      Vaccine Type Functions      ***************/

    public function vaccineType()
    {
        $data['title'] = getMethodName();
        $data['session'] = $this->session;

        return view('Master/VaccineTypeView', $data);
    }

    public function VaccineTypeView()
    {
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_VACCINE_TYPES';
        $columns = 'VT_ID,VT_NAME,VT_POP_NAME,VT_COMPANY,VT_AB_FORM_DAYS,VT_AB_DUR_DAYS';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function insertVaccineType()
    {
        try {
            $sysid = $this->request->getPost('VT_ID');

            $validate = $this->validate([
                'VT_NAME' => ['label' => 'Vaccine Name', 'rules' => 'required|is_unique[FLXY_VACCINE_TYPES.VT_NAME,VT_ID,' . $sysid . ']'],
                'VT_POP_NAME' => ['label' => 'Popular Name', 'rules' => 'required'],
                'VT_COMPANY' => ['label' => 'Vaccine Company', 'rules' => 'required'],
                'VT_AB_FORM_DAYS' => ['label' => 'Ab Formation Days', 'rules' => 'permit_empty|greater_than_equal_to[0]'],
                'VT_AB_DUR_DAYS' => ['label' => 'Ab Duration Days', 'rules' => 'permit_empty|greater_than_equal_to[0]'],
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
                "VT_NAME" => trim($this->request->getPost('VT_NAME')),
                "VT_POP_NAME" => trim($this->request->getPost('VT_POP_NAME')),
                "VT_COMPANY" => trim($this->request->getPost('VT_COMPANY')) != '' ? trim($this->request->getPost('VT_COMPANY')) : '',
                "VT_AB_FORM_DAYS" => trim($this->request->getPost('VT_AB_FORM_DAYS')),
                "VT_AB_DUR_DAYS" => trim($this->request->getPost('VT_AB_DUR_DAYS')),
            ];

            $return = !empty($sysid) ? $this->Db->table('FLXY_VACCINE_TYPES')->where('VT_ID', $sysid)->update($data) : $this->Db->table('FLXY_VACCINE_TYPES')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function checkVaccineType($rcCode)
    {
        $sql = "SELECT VT_ID
                FROM FLXY_VACCINE_TYPES
                WHERE VT_NAME = '" . $rcCode . "'";

        $response = $this->Db->query($sql)->getNumRows();
        return $rcCode == '' || strlen($rcCode) > 10 ? 1 : $response; // Send found row even if submitted code is empty
    }

    public function vaccineTypeList()
    {
        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

        $sql = "SELECT VT_ID, VT_NAME, VT_POP_NAME
                FROM FLXY_VACCINE_TYPES WHERE VT_STATUS = 1 ";

        if ($search != '') {
            $sql .= " AND VT_NAME LIKE '%$search%'
                      OR VT_POP_NAME LIKE '%$search%'";
        }

        $response = $this->Db->query($sql)->getResultArray();

        $option = '<option value="">Choose an Option</option>';
        foreach ($response as $row) {
            $option .= '<option value="' . $row['VT_ID'] . '">' . $row['VT_POP_NAME'] . '</option>';
        }

        return $option;
    }

    public function editVaccineType()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];

        $sql = "SELECT *
                FROM FLXY_VACCINE_TYPES
                WHERE VT_ID=:SYSID:";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteVaccineType()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            $return = $this->Db->table('FLXY_VACCINE_TYPES')->delete(['VT_ID' => $sysid]);
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    /**************      Product Category Functions      ***************/

    public function productCategory()
    {
        $data['title'] = 'Product Categories';
        $data['session'] = $this->session;

        return view('Master/ProductCategoryView', $data);
    }

    public function ProductCategoryView()
    {
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_PRODUCT_CATEGORIES';
        $columns = 'PC_ID|PC_CATEGORY|FORMAT(PC_CREATED_AT,\'dd-MMM-yyyy hh:mm:ss\')PC_CREATED_AT|FORMAT(PC_UPDATED_AT,\'dd-MMM-yyyy hh:mm:ss\')PC_UPDATED_AT';
        $mine->generate_DatatTable($tableName, $columns, [], '|');
        exit;
    }

    public function insertProductCategory()
    {
        try {
            $sysid = $this->request->getPost('PC_ID');

            $validate = $this->validate([
                'PC_CATEGORY' => ['label' => 'Product Category', 'rules' => 'required|is_unique[FLXY_PRODUCT_CATEGORIES.PC_CATEGORY,PC_ID,' . $sysid . ']'],
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
                "PC_CATEGORY" => trim($this->request->getPost('PC_CATEGORY')),
                "PC_CREATED_AT" => date('Y-m-d H:i:s'),
                "PC_UPDATED_AT" => date('Y-m-d H:i:s'),
                "PC_CREATED_BY" => session()->get('USR_ID'),
                "PC_UPDATED_BY" => session()->get('USR_ID'),
            ];

            if (!empty($sysid)) {
                unset($data["PC_CREATED_AT"]);
                unset($data["PC_CREATED_BY"]);
            }

            $return = !empty($sysid) ? $this->Db->table('FLXY_PRODUCT_CATEGORIES')->where('PC_ID', $sysid)->update($data) : $this->Db->table('FLXY_PRODUCT_CATEGORIES')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function checkProductCategory($rcCode)
    {
        $sql = "SELECT PC_ID
                FROM FLXY_PRODUCT_CATEGORIES
                WHERE PC_CATEGORY = '" . $rcCode . "'";

        $response = $this->Db->query($sql)->getNumRows();
        return $rcCode == '' || strlen($rcCode) > 70 ? 1 : $response; // Send found row even if submitted code is empty
    }

    public function editProductCategory()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];

        $sql = "SELECT *
                FROM FLXY_PRODUCT_CATEGORIES
                WHERE PC_ID=:SYSID:";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteProductCategory()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            $return = $this->Db->table('FLXY_PRODUCT_CATEGORIES')->delete(['PC_ID' => $sysid]);
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    /**************      Cancellation Reason Functions      ***************/

    public function cancellationReason()
    {
        $data['title'] = getMethodName();
        $data['session'] = $this->session;

        return view('Master/CancellationReasonView', $data);
    }

    public function CancellationReasonView()
    {
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_CANCELLATION_REASONS';
        $columns = 'CN_RS_ID,CN_RS_CODE,CN_RS_DESC,CN_RS_DIS_SEQ,CN_RS_STATUS';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function insertCancellationReason()
    {
        try {
            $sysid = $this->request->getPost('CN_RS_ID');

            $validate = $this->validate([
                'CN_RS_CODE' => ['label' => 'Cancellation Code', 'rules' => 'required|is_unique[FLXY_CANCELLATION_REASONS.CN_RS_CODE,CN_RS_ID,' . $sysid . ']'],
                'CN_RS_DESC' => ['label' => 'Cancellation Description', 'rules' => 'required'],
                'CN_RS_DIS_SEQ' => ['label' => 'Display Sequence', 'rules' => 'permit_empty|greater_than_equal_to[0]'],
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
                "CN_RS_CODE" => trim($this->request->getPost('CN_RS_CODE')),
                "CN_RS_DESC" => trim($this->request->getPost('CN_RS_DESC')),
                "CN_RS_DIS_SEQ" => trim($this->request->getPost('CN_RS_DIS_SEQ')) != '' ? trim($this->request->getPost('CN_RS_DIS_SEQ')) : '',
                "CN_RS_STATUS" => trim($this->request->getPost('CN_RS_STATUS')),
            ];

            $return = !empty($sysid) ? $this->Db->table('FLXY_CANCELLATION_REASONS')->where('CN_RS_ID', $sysid)->update($data) : $this->Db->table('FLXY_CANCELLATION_REASONS')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function checkCancellationReason($rcCode)
    {
        $sql = "SELECT CN_RS_ID
                FROM FLXY_CANCELLATION_REASONS
                WHERE CN_RS_CODE = '" . $rcCode . "'";

        $response = $this->Db->query($sql)->getNumRows();
        return $rcCode == '' || strlen($rcCode) > 10 ? 1 : $response; // Send found row even if submitted code is empty
    }

    public function editCancellationReason()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];

        $sql = "SELECT CN_RS_ID, CN_RS_CODE, CN_RS_DESC, CN_RS_DIS_SEQ, CN_RS_STATUS
                FROM FLXY_CANCELLATION_REASONS
                WHERE CN_RS_ID=:SYSID:";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteCancellationReason()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            $return = $this->Db->table('FLXY_CANCELLATION_REASONS')->delete(['CN_RS_ID' => $sysid]);
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    /**************      Block Status Code Functions      ***************/

    public function blockStatusCode()
    {
        $colors = $this->colorList();
        $reservationTypes = $this->reservationTypeList();
        $roomStatusTypes = $this->roomStatusTypeList();
        $cancelTypes = $this->cancelTypeList();

        $data = [
            'colorOptions' => $colors,
            'reservationTypeOptions' => $reservationTypes,
            'roomStatusTypeOptions' => $roomStatusTypes,
            'cancelTypeOptions' => $cancelTypes,
        ];

        $data['title'] = getMethodName();
        $data['session'] = $this->session;

        return view('Master/BlockStatusCodeView', $data);
    }

    public function BlockStatusCodeView()
    {
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = '  FLXY_BLOCK_STATUS_CODE
                        LEFT JOIN FLXY_RESERVATION_TYPE FRT ON FRT.RESV_TY_ID = FLXY_BLOCK_STATUS_CODE.RESV_TY_ID
                        LEFT JOIN FLXY_ROOM_STATUS_TYPE FST ON FST.RM_STATUS_TY_ID = FLXY_BLOCK_STATUS_CODE.RM_STATUS_TY_ID
                        LEFT JOIN FLXY_COLOR FC ON FC.CLR_ID = FLXY_BLOCK_STATUS_CODE.CLR_ID
                        LEFT JOIN FLXY_BLOCK_CANCEL_TYPE FBCT ON FBCT.RM_CANCEL_TY_ID = FLXY_BLOCK_STATUS_CODE.RM_CANCEL_TY_ID';

        $columns = 'BLK_STS_CD_ID,BLK_STS_CD_CODE,BLK_STS_CD_DESC,RESV_TY_CODE,RM_STATUS_TY_CODE,CLR_NAME,BLK_STS_CD_DIS_SEQ,BLK_STS_CD_ALLOW_PICKUP,BLK_STS_CD_RETURN_INVENTORY,BLK_STS_CD_STARTING_STATUS,BLK_STS_CD_LEAD_STATUS,RM_CANCEL_TY_DESC,BLK_STS_CD_STATUS';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function reservationTypeList()
    {
        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

        $sql = "SELECT RESV_TY_ID, RESV_TY_CODE, RESV_TY_DESC
                FROM FLXY_RESERVATION_TYPE ";

        if ($search != '') {
            $sql .= " AND RESV_TY_CODE LIKE '%$search%'
                      OR RESV_TY_DESC LIKE '%$search%'";
        }

        $response = $this->Db->query($sql)->getResultArray();

        $option = '<option value="">Choose an Option</option>';
        foreach ($response as $row) {
            $option .= '<option value="' . $row['RESV_TY_ID'] . '">' . $row['RESV_TY_CODE'] . ' | ' . $row['RESV_TY_DESC'] . '</option>';
        }

        return $option;
    }

    public function roomStatusTypeList()
    {
        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

        $sql = "SELECT RM_STATUS_TY_ID, RM_STATUS_TY_CODE
                FROM FLXY_ROOM_STATUS_TYPE ";

        if ($search != '') {
            $sql .= " AND RM_STATUS_TY_CODE LIKE '%$search%'
                      OR RM_STATUS_TY_DESC LIKE '%$search%'";
        }

        $response = $this->Db->query($sql)->getResultArray();

        $option = '<option value="">Choose an Option</option>';
        foreach ($response as $row) {
            $option .= '<option value="' . $row['RM_STATUS_TY_ID'] . '">' . $row['RM_STATUS_TY_CODE'] . '</option>';
        }

        return $option;
    }

    public function cancelTypeList()
    {
        $sql = "SELECT RM_CANCEL_TY_ID, RM_CANCEL_TY_DESC
                FROM FLXY_BLOCK_CANCEL_TYPE ";

        $response = $this->Db->query($sql)->getResultArray();

        $option = '<option value="">Choose an Option</option>';
        foreach ($response as $row) {
            $option .= '<option value="' . $row['RM_CANCEL_TY_ID'] . '">' . $row['RM_CANCEL_TY_DESC'] . '</option>';
        }

        return $option;
    }

    public function insertBlockStatusCode()
    {
        try {
            $sysid = $this->request->getPost('BLK_STS_CD_ID');

            $rules = [
                'BLK_STS_CD_CODE' => ['label' => 'Block Status Code', 'rules' => 'required|is_unique[FLXY_BLOCK_STATUS_CODE.BLK_STS_CD_CODE,BLK_STS_CD_ID,' . $sysid . ']'],
                'BLK_STS_CD_DESC' => ['label' => 'Description', 'rules' => 'required'],
                'RM_STATUS_TY_ID' => ['label' => 'Room Status Type', 'rules' => 'required'],
                'CLR_ID' => ['label' => 'Color', 'rules' => 'required'],
                'BLK_STS_CD_DIS_SEQ' => ['label' => 'Display Sequence', 'rules' => 'permit_empty|greater_than_equal_to[0]'],
            ];

            if (null !== $this->request->getPost('RM_STATUS_TY_ID') && $this->request->getPost('RM_STATUS_TY_ID') == '4') {
                $rules['RM_CANCEL_TY_ID'] = [
                    'label' => 'Cancel Type',
                    'rules' => 'required'
                ];
            }

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

            $data = [
                "BLK_STS_CD_CODE" => trim($this->request->getPost('BLK_STS_CD_CODE')),
                "BLK_STS_CD_DESC" => trim($this->request->getPost('BLK_STS_CD_DESC')),
                "RM_STATUS_TY_ID" => trim($this->request->getPost('RM_STATUS_TY_ID')),
                "RESV_TY_ID" => trim($this->request->getPost('RESV_TY_ID')),
                "RM_CANCEL_TY_ID" => trim($this->request->getPost('RM_CANCEL_TY_ID')),
                "CLR_ID" => trim($this->request->getPost('CLR_ID')),
                "BLK_STS_CD_DIS_SEQ" => trim($this->request->getPost('BLK_STS_CD_DIS_SEQ')),
                "BLK_STS_CD_ALLOW_PICKUP" => null !== $this->request->getPost('BLK_STS_CD_ALLOW_PICKUP') ? '1' : '0',
                "BLK_STS_CD_RETURN_INVENTORY" => null !== $this->request->getPost('BLK_STS_CD_RETURN_INVENTORY') ? '1' : '0',
                "BLK_STS_CD_STARTING_STATUS" => null !== $this->request->getPost('BLK_STS_CD_STARTING_STATUS') ? '1' : '0',
                "BLK_STS_CD_LEAD_STATUS" => null !== $this->request->getPost('BLK_STS_CD_LEAD_STATUS') ? '1' : '0',
                "BLK_STS_CD_LOG_CATERING_CHANGES" => null !== $this->request->getPost('BLK_STS_CD_LOG_CATERING_CHANGES') ? '1' : '0',
                "BLK_STS_CD_STATUS" => null !== $this->request->getPost('BLK_STS_CD_STATUS') ? '1' : '0',
            ];

            $return = !empty($sysid) ? $this->Db->table('FLXY_BLOCK_STATUS_CODE')->where('BLK_STS_CD_ID', $sysid)->update($data) : $this->Db->table('FLXY_BLOCK_STATUS_CODE')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function checkBlockStatusCode($rcCode)
    {
        $sql = "SELECT BLK_STS_CD_ID
                FROM FLXY_BLOCK_STATUS_CODE
                WHERE BLK_STS_CD_CODE = '" . $rcCode . "'";

        $response = $this->Db->query($sql)->getNumRows();
        return $rcCode == '' || strlen($rcCode) > 10 ? 1 : $response; // Send found row even if submitted code is empty
    }

    public function editBlockStatusCode()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];

        $sql = "SELECT FBSC.*, FRT.RESV_TY_CODE, FC.CLR_NAME
                FROM dbo.FLXY_BLOCK_STATUS_CODE AS FBSC
                LEFT JOIN FLXY_RESERVATION_TYPE FRT ON FRT.RESV_TY_ID = FBSC.RESV_TY_ID
                LEFT JOIN FLXY_COLOR FC ON FC.CLR_ID = FBSC.CLR_ID
                WHERE BLK_STS_CD_ID=:SYSID:";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteBlockStatusCode()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            $return = $this->Db->table('FLXY_BLOCK_STATUS_CODE')->delete(['BLK_STS_CD_ID' => $sysid]);
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    /**************      Room Status Change Reason Functions      ***************/

    public function roomStatusChangeReason()
    {
        $data['title'] = getMethodName();
        $data['session'] = $this->session;

        return view('Master/RoomStatusChangeReasonView', $data);
    }

    public function RoomStatusChangeReasonView()
    {
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_ROOM_STATUS_CHANGE_REASON';
        $columns = 'RM_STATUS_CHANGE_ID,RM_STATUS_CHANGE_CODE,RM_STATUS_CHANGE_DESC,RM_STATUS_CHANGE_DIS_SEQ,RM_STATUS_CHANGE_STATUS';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function insertRoomStatusChangeReason()
    {
        try {
            $sysid = $this->request->getPost('RM_STATUS_CHANGE_ID');

            $validate = $this->validate([
                'RM_STATUS_CHANGE_CODE' => ['label' => 'Room Status Change Code', 'rules' => 'required|is_unique[FLXY_ROOM_STATUS_CHANGE_REASON.RM_STATUS_CHANGE_CODE,RM_STATUS_CHANGE_ID,' . $sysid . ']'],
                'RM_STATUS_CHANGE_DESC' => ['label' => 'Room Status Change Description', 'rules' => 'required'],
                'RM_STATUS_CHANGE_DIS_SEQ' => ['label' => 'Display Sequence', 'rules' => 'permit_empty|greater_than_equal_to[0]'],
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
                "RM_STATUS_CHANGE_CODE" => trim($this->request->getPost('RM_STATUS_CHANGE_CODE')),
                "RM_STATUS_CHANGE_DESC" => trim($this->request->getPost('RM_STATUS_CHANGE_DESC')),
                "RM_STATUS_CHANGE_DIS_SEQ" => trim($this->request->getPost('RM_STATUS_CHANGE_DIS_SEQ')) != '' ? trim($this->request->getPost('RM_STATUS_CHANGE_DIS_SEQ')) : '',
                "RM_STATUS_CHANGE_STATUS" => null !== $this->request->getPost('RM_STATUS_CHANGE_STATUS') ? '1' : '0',
            ];

            $return = !empty($sysid) ? $this->Db->table('FLXY_ROOM_STATUS_CHANGE_REASON')->where('RM_STATUS_CHANGE_ID', $sysid)->update($data) : $this->Db->table('FLXY_ROOM_STATUS_CHANGE_REASON')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function checkRoomStatusChangeReason($rcCode)
    {
        $sql = "SELECT RM_STATUS_CHANGE_ID
                FROM FLXY_ROOM_STATUS_CHANGE_REASON
                WHERE RM_STATUS_CHANGE_CODE = '" . $rcCode . "'";

        $response = $this->Db->query($sql)->getNumRows();
        return $rcCode == '' || strlen($rcCode) > 10 ? 1 : $response; // Send found row even if submitted code is empty
    }

    public function editRoomStatusChangeReason()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];

        $sql = "SELECT RM_STATUS_CHANGE_ID, RM_STATUS_CHANGE_CODE, RM_STATUS_CHANGE_DESC, RM_STATUS_CHANGE_DIS_SEQ, RM_STATUS_CHANGE_STATUS
                FROM FLXY_ROOM_STATUS_CHANGE_REASON
                WHERE RM_STATUS_CHANGE_ID=:SYSID:";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteRoomStatusChangeReason()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            $return = $this->Db->table('FLXY_ROOM_STATUS_CHANGE_REASON')->delete(['RM_STATUS_CHANGE_ID' => $sysid]);
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }
}