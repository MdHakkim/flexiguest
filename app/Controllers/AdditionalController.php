<?php

namespace App\Controllers;

use App\Libraries\ServerSideDataTable;
use App\ThirdParty\FPDF;

class AdditionalController extends BaseController
{
    public $Db;
    public $request;
    public $session;

    public function __construct()
    {
        $this->Db = \Config\Database::connect();
        $this->request = \Config\Services::request();
        $this->session = \Config\Services::session();
        helper(['form', 'url', 'custom', 'common', 'upload']);
    }

    // public function getMethodName()
    // {
    //     /** Get Method Name as Page Title */

    //     $router = service('router');
    //     $method = $router->methodName();
    //     return ucwords(implode(' ', preg_split('/(?=[A-Z])/', $method)));
    // }

    /**************      Currency Functions      ***************/

    public function currency()
    {
        $data['title'] = getMethodName();
        $data['session'] = $this->session;
        
        return view('Master/CurrencyView', $data);
    }

    public function CurrencyView()
    {
        $mine      = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_CURRENCY';
        $columns   = 'CUR_ID,CUR_CODE,CUR_DESC,CUR_DECIMAL,CUR_STATUS,CUR_CREATED';
        $cond      =  array();
        //$cond["CUR_DELETED LIKE "] = "0";
        $mine->generate_DatatTable($tableName, $columns, $cond);
        exit;
    }

    public function insertCurrency()
    {
        try {
            $sysid = $this->request->getPost('CUR_ID');

            $validate = $this->validate([
                'CUR_CODE' => ['label' => 'Currency Code', 'rules' => 'required|is_unique[FLXY_CURRENCY.CUR_CODE,CUR_ID,' . $sysid . ']'],
                'CUR_DESC' => ['label' => 'Description', 'rules' => 'required'],
                'CUR_DECIMAL' => ['label' => 'Decimal', 'rules' => 'permit_empty|greater_than_equal_to[0]'],         
                
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
                "CUR_CODE" => trim($this->request->getPost('CUR_CODE')),
                "CUR_DESC" => trim($this->request->getPost('CUR_DESC')),
                "CUR_DECIMAL" => trim($this->request->getPost('CUR_DECIMAL')) != '' ? trim($this->request->getPost('CUR_DECIMAL')) : '',
                "CUR_STATUS" => trim($this->request->getPost('CUR_STATUS')),
                "CUR_CREATED" => date("Y-m-d")
            ];

            $return = !empty($sysid) ? $this->Db->table('FLXY_CURRENCY')->where('CUR_ID', $sysid)->update($data) : $this->Db->table('FLXY_CURRENCY')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function editCurrency()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];

        $sql = "SELECT CUR_ID, CUR_CODE, CUR_DESC, CUR_DECIMAL,
                CUR_STATUS
                FROM FLXY_CURRENCY
                WHERE CUR_ID=:SYSID: ";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteCurrency()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            //Soft Delete
            //$data = ["CUR_DELETED" => "1"];
            //$return =  $this->Db->table('FLXY_CURRENCY')->where('CUR_ID', $sysid)->update($data);
            $return = $this->Db->table('FLXY_CURRENCY')->delete(['CUR_ID' => $sysid]);
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }


    /**************      Exchange Codes Functions      ***************/

    public function exchangeCodes()
    {

        $data['title'] = getMethodName();
        $data['session'] = $this->session;
        
        return view('Master/ExchangeCodesView', $data);
    }

    public function ExchangeCodesView()
    {
        $mine      = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_EXCHANGE_CODES';
        $columns   = 'EXCH_ID,EXCH_CODE,EXCH_DESC,EXCH_CASH,EXCH_CHECK,EXCH_SETTLEMENT,EXCH_STATUS,EXCH_CREATED';
        $cond      =  array();
        $mine->generate_DatatTable($tableName, $columns, $cond);
        exit;
    }

    public function insertExchangeCodes()
    {
        try {
            $sysid = $this->request->getPost('EXCH_ID');

            $validate = $this->validate([
                'EXCH_CODE' => ['label' => 'Code', 'rules' => 'required|is_unique[FLXY_EXCHANGE_CODES.EXCH_CODE,EXCH_ID,' . $sysid . ']'],
                'EXCH_DESC' => ['label' => 'Description', 'rules' => 'required']         
                
            ]);
            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }

           

            $data = [
                "EXCH_CODE" => trim($this->request->getPost('EXCH_CODE')),
                "EXCH_DESC" => trim($this->request->getPost('EXCH_DESC')),
                "EXCH_CASH" => trim($this->request->getPost('EXCH_CASH')),
                "EXCH_CHECK" => trim($this->request->getPost('EXCH_CHECK')),
                "EXCH_SETTLEMENT" => trim($this->request->getPost('EXCH_SETTLEMENT')),
                "EXCH_STATUS" => trim($this->request->getPost('EXCH_STATUS')),
                "EXCH_CREATED" => date("Y-m-d")
            ];

            $return = !empty($sysid) ? $this->Db->table('FLXY_EXCHANGE_CODES')->where('EXCH_ID', $sysid)->update($data) : $this->Db->table('FLXY_EXCHANGE_CODES')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function editExchangeCodes()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];

        $sql = "SELECT EXCH_ID, EXCH_CODE, EXCH_DESC, EXCH_CASH, EXCH_CHECK, EXCH_SETTLEMENT, EXCH_STATUS
                FROM FLXY_EXCHANGE_CODES
                WHERE EXCH_ID=:SYSID: ";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteExchangeCodes()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            //Soft Delete
            //$data = ["CUR_DELETED" => "1"];
            //$return =  $this->Db->table('FLXY_CURRENCY')->where('CUR_ID', $sysid)->update($data);
            $return = $this->Db->table('FLXY_EXCHANGE_CODES')->delete(['EXCH_ID' => $sysid]);
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    /**************      Exchange Rate Functions      ***************/

        public function exchangeRates()
        {    
            $currencyLists = $this->currencyLists();
            $exchangeCodesLists = $this->exchangeCodesLists();
    
            $data = [
                'currencyLists' => $currencyLists,
                'exchangeCodesLists' => $exchangeCodesLists
            ];
    
            $data['title'] = getMethodName();
            $data['session'] = $this->session;
            
            return view('Master/ExchangeRatesView', $data);
        }
    
        public function ExchangeRatesView()
        {
            $mine = new ServerSideDataTable(); // loads and creates instance
            $tableName = 'FLXY_EXCHANGE_RATES_VIEW';
            $columns = 'EXCH_RATE_ID,CUR_CODE,CUR_DESC,EXCH_CODE,EXCH_CASH,EXCH_CHECK,EXCH_SETTLEMENT,EXCH_RATE_NET_BUY_RATE,EXCH_RATE_BEGIN_DATE,EXCH_RATE_BEGIN_TIME,EXCH_RATE_COMMENTS';
            $mine->generate_DatatTable($tableName, $columns);
            exit;
        }    

        public function insertExchangeRates()
        {
            try {
                $sysid = $this->request->getPost('EXCH_RATE_ID');

                $validate = $this->validate([
                    'EXCH_RATE_CUR_ID' => ['label' => 'Currency', 'rules' => 'required'],
                    'EXCH_RATE_CODE_ID' => ['label' => 'Code', 'rules' => 'required'],
                    'EXCH_RATE_BEGIN_DATE' => ['label' => 'Date', 'rules' => 'required'],
                    'EXCH_RATE_BUY_RATE' => ['label' => 'Buy Rate', 'rules' => 'greater_than_equal_to[0]'],
                    'EXCH_RATE_FROM_BUY_RATE' => ['label' => 'From Buy Rate', 'rules' => 'greater_than_equal_to[0]'],
                    // 'EXCH_NET_BUY_RATE' => ['label' => 'Net Buy Rate', 'rules' => 'greater_than_equal_to[0]'],
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
                    "EXCH_RATE_CUR_ID" => trim($this->request->getPost('EXCH_RATE_CUR_ID')),
                    "EXCH_RATE_CODE_ID" => trim($this->request->getPost('EXCH_RATE_CODE_ID')),
                    "EXCH_RATE_BEGIN_DATE" => trim($this->request->getPost('EXCH_RATE_BEGIN_DATE')),
                    "EXCH_RATE_BEGIN_TIME" => date('h:i:s'),
                    "EXCH_RATE_BUY_RATE" => trim($this->request->getPost('EXCH_RATE_BUY_RATE')),
                    "EXCH_RATE_FROM_BUY_RATE" => trim($this->request->getPost('EXCH_RATE_FROM_BUY_RATE')),
                    "EXCH_RATE_NET_BUY_RATE" => trim($this->request->getPost('EXCH_RATE_NET_BUY_RATE_HIDDEN')),
                    "EXCH_RATE_COMMENTS" => trim($this->request->getPost('EXCH_RATE_COMMENTS')),
                    "EXCH_RATE_STATUS" => trim($this->request->getPost('EXCH_RATE_STATUS'))
                ];

                $return = !empty($sysid) ? $this->Db->table('FLXY_EXCHANGE_RATES')->where('EXCH_RATE_ID', $sysid)->update($data) : $this->Db->table('FLXY_EXCHANGE_RATES')->insert($data);
                $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
                echo json_encode($result);
            } catch (\Exception $e) {
            return $e->getMessage();
        }
        }    

        public function editExchangeRates()
        {
            $param = ['SYSID' => $this->request->getPost('sysid')];

            $sql = "SELECT FER.EXCH_RATE_ID, FER.EXCH_RATE_BEGIN_DATE, FER.EXCH_RATE_BEGIN_TIME, FER.EXCH_RATE_BUY_RATE, FER.EXCH_RATE_FROM_BUY_RATE, FER.EXCH_RATE_BUY_COM, FER.EXCH_RATE_NET_BUY_RATE, FER.EXCH_RATE_CUR_ID, FER.EXCH_RATE_CODE_ID, FER.EXCH_RATE_COMMENTS, FEC.CUR_ID, FEC.CUR_CODE, FEC.CUR_DESC, FES.EXCH_ID,FES.EXCH_CODE,FES.EXCH_DESC, FES.EXCH_CASH, FES.EXCH_CHECK, FES.EXCH_SETTLEMENT 
            FROM FLXY_EXCHANGE_RATES AS FER LEFT OUTER JOIN FLXY_CURRENCY AS FEC ON FER.EXCH_RATE_CUR_ID = FEC.CUR_ID LEFT OUTER JOIN
            FLXY_EXCHANGE_CODES AS FES ON FER.EXCH_RATE_CODE_ID = FES.EXCH_ID 
            WHERE FER.EXCH_RATE_ID=:SYSID:";

            $response = $this->Db->query($sql, $param)->getResultArray();
            echo json_encode($response);
        }

        public function deleteExchangeRates()
        {
            $sysid = $this->request->getPost('sysid');

            try {
                $return = $this->Db->table('FLXY_EXCHANGE_RATES')->delete(['EXCH_RATE_ID' => $sysid]);
                $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
                echo json_encode($result);
            } catch (\Exception $e) {
            return $e->getMessage();
        }
        }
        
       
        public function currencyLists()
        {
            $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

            $sql = "SELECT CUR_ID, CUR_CODE, CUR_DESC
                    FROM FLXY_CURRENCY";

            if ($search != '') {
                $sql .= " WHERE CUR_CODE LIKE '%$search%'
                        OR CUR_DESC LIKE '%$search%'";
            }

            $response = $this->Db->query($sql)->getResultArray();

            $option = '<option value="">Choose an Option</option>';
            foreach ($response as $row) {
                $option .= '<option value="' . $row['CUR_ID'] . '">' . $row['CUR_CODE'] . ' | ' . $row['CUR_DESC'] . '</option>';
            }

            return $option;
        }

        public function exchangeCodesLists()
        {
            $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

            $sql = "SELECT EXCH_ID, EXCH_CODE, EXCH_DESC
                    FROM FLXY_EXCHANGE_CODES";

            if ($search != '') {
                $sql .= " WHERE EXCH_CODE LIKE '%$search%'
                        OR EXCH_DESC LIKE '%$search%'";
            }

            $response = $this->Db->query($sql)->getResultArray();

            $option = '<option value="">Choose an Option</option>';
            foreach ($response as $row) {
                $option .= '<option value="' . $row['EXCH_ID'] . '">' . $row['EXCH_CODE'] . ' | ' . $row['EXCH_DESC'] . '</option>';
            }

            return $option;
        }


    /**************      Department Functions      ***************/

    public function departments()
    {

        $data['title'] = getMethodName();
        $data['session'] = $this->session;
        
        return view('Master/DepartmentView', $data);
    }

    public function DepartmentView()
    {
        $mine      = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_DEPARTMENT';
        $columns   = 'DEPT_ID,DEPT_CODE,DEPT_DESC,DEPT_DIS_SEQ,DEPT_STATUS';
        $cond      =  array();        
        $mine->generate_DatatTable($tableName, $columns, $cond);
        exit;
    }

    public function insertDepartment()
    {
        try {
            $sysid = $this->request->getPost('DEPT_ID');

            $validate = $this->validate([
                'DEPT_CODE' => ['label' => 'Department Code', 'rules' => 'required|is_unique[FLXY_DEPARTMENT.DEPT_CODE,DEPT_ID,' . $sysid . ']'],
                'DEPT_DESC' => ['label' => 'Description', 'rules' => 'required'],
                'DEPT_DIS_SEQ' => ['label' => 'Seq No', 'rules' => 'required|greater_than_equal_to[0]']        
                
            ]);
            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }


            $data = [
                "DEPT_CODE" => trim($this->request->getPost('DEPT_CODE')),
                "DEPT_DESC" => trim($this->request->getPost('DEPT_DESC')),
                "DEPT_DIS_SEQ" => trim($this->request->getPost('DEPT_DIS_SEQ')),
                "DEPT_STATUS" => trim($this->request->getPost('DEPT_STATUS'))
            ];

            $return = !empty($sysid) ? $this->Db->table('FLXY_DEPARTMENT')->where('DEPT_ID', $sysid)->update($data) : $this->Db->table('FLXY_DEPARTMENT')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function editDepartment()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];

        $sql = "SELECT DEPT_ID, DEPT_CODE, DEPT_DESC, DEPT_DIS_SEQ,
                DEPT_STATUS
                FROM FLXY_DEPARTMENT
                WHERE DEPT_ID=:SYSID: ";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteDepartment()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            //Soft Delete
            //$data = ["CUR_DELETED" => "1"];
            //$return =  $this->Db->table('FLXY_CURRENCY')->where('CUR_ID', $sysid)->update($data);
            $return = $this->Db->table('FLXY_DEPARTMENT')->delete(['DEPT_ID' => $sysid]);
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }


        /**************     Item Class Functions      ***************/

        public function itemClass()
        {
    
            $data['title'] = getMethodName();
            $data['session'] = $this->session;
            $departmentLists = $this->departmentLists();
    
            $data['departmentLists'] = $departmentLists;
            
            return view('Master/ItemClassView', $data);
        }
    
        public function ItemClassView()
        {
            
            $mine      = new ServerSideDataTable(); // loads and creates instance
            $tableName = 'FLXY_ITEM_CLASS INNER JOIN FLXY_DEPARTMENT ON IT_CL_DEPARTMENTS = DEPT_ID';
            $columns   = 'IT_CL_ID,IT_CL_CODE,IT_CL_DESC,DEPT_CODE,IT_CL_DIS_SEQ';
            $cond      =  array();        
            $mine->generate_DatatTable($tableName, $columns, $cond);
            exit;
        }
    
        public function insertItemClass()
        {
            try {
                $sysid = $this->request->getPost('IT_CL_ID');
    
                $validate = $this->validate([
                    'IT_CL_CODE' => ['label' => 'Class Code', 'rules' => 'required|is_unique[FLXY_ITEM_CLASS.IT_CL_CODE,IT_CL_ID,' . $sysid . ']'],
                    'IT_CL_DESC' => ['label' => 'Description', 'rules' => 'required'],
                    'IT_CL_DIS_SEQ' => ['label' => 'Seq No', 'rules' => 'required|greater_than_equal_to[0]']        
                    
                ]);
                if (!$validate) {
                    $validate = $this->validator->getErrors();
                    $result["SUCCESS"] = "-402";
                    $result[]["ERROR"] = $validate;
                    $result = $this->responseJson("-402", $validate);
                    echo json_encode($result);
                    exit;
                }
    
                
    
                $data = [
                    "IT_CL_CODE" => trim($this->request->getPost('IT_CL_CODE')),
                    "IT_CL_DESC" => trim($this->request->getPost('IT_CL_DESC')),
                    "IT_CL_DIS_SEQ" => trim($this->request->getPost('IT_CL_DIS_SEQ')),
                    "IT_CL_DEPARTMENTS" => trim($this->request->getPost('IT_CL_DEPARTMENTS'))
                ];
    
                $return = !empty($sysid) ? $this->Db->table('FLXY_ITEM_CLASS')->where('IT_CL_ID', $sysid)->update($data) : $this->Db->table('FLXY_ITEM_CLASS')->insert($data);
                $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
                echo json_encode($result);
            } catch (\Exception $e) {
            return $e->getMessage();
        }
        }
    
        public function editItemClass()
        {
            $param = ['SYSID' => $this->request->getPost('sysid')];
    
            $sql = "SELECT IT_CL_ID, IT_CL_CODE, IT_CL_DESC, IT_CL_DIS_SEQ,
                    DEPT_CODE, IT_CL_DEPARTMENTS
                    FROM FLXY_ITEM_CLASS INNER JOIN FLXY_DEPARTMENT ON IT_CL_DEPARTMENTS = DEPT_ID
                    WHERE IT_CL_ID=:SYSID: ";
    
            $response = $this->Db->query($sql, $param)->getResultArray();
            echo json_encode($response);
        }
    
        public function deleteItemClass()
        {
            $sysid = $this->request->getPost('sysid');
    
            try {
                //Soft Delete
                //$data = ["CUR_DELETED" => "1"];
                //$return =  $this->Db->table('FLXY_CURRENCY')->where('CUR_ID', $sysid)->update($data);
                $return = $this->Db->table('FLXY_ITEM_CLASS')->delete(['IT_CL_ID' => $sysid]);
                $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
                echo json_encode($result);
            } catch (\Exception $e) {
            return $e->getMessage();
        }
        }

        
    
        public function departmentLists()
        {
            $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

            $sql = "SELECT DEPT_ID, DEPT_CODE, DEPT_DESC
                    FROM FLXY_DEPARTMENT";

            if ($search != '') {
                $sql .= " WHERE DEPT_CODE LIKE '%$search%'
                        OR DEPT_DESC LIKE '%$search%'";
            }

            $response = $this->Db->query($sql)->getResultArray();

            $option = '<option value="">Choose an Option</option>';
            foreach ($response as $row) {
                $option .= '<option value="' . $row['DEPT_ID'] . '">' . $row['DEPT_CODE'] . ' | ' . $row['DEPT_DESC'] . '</option>';
            }

            return $option;
        }

        /**************      Items Functions      ***************/

        public function items()
        {
    
            $itemDepartmentLists = $this->itemDepartmentList();
    
            $data = [
                'itemDepartmentLists' => $itemDepartmentLists
            ];
    
            $data['title'] = getMethodName();
            $data['session'] = $this->session;
            
            return view('Master/ItemsView', $data);
        }
    
        public function ItemsView()
        {
            $mine = new ServerSideDataTable(); // loads and creates instance
            $tableName = 'FLXY_ITEM LEFT JOIN FLXY_DEPARTMENT ON ITM_DEPARTMENTS = DEPT_ID LEFT JOIN FLXY_ITEM_CLASS ON FLXY_ITEM.IT_CL_ID = FLXY_ITEM_CLASS.IT_CL_ID';
            $columns = 'ITM_ID,IT_CL_CODE,ITM_CODE,ITM_NAME,ITM_DESC,ITM_DIS_SEQ,ITM_DEPARTMENTS,ITM_QTY_IN_STOCK,ITM_QTY_DEFAULT,ITM_PRINT,ITM_SELL_CONTROL,ITM_SELL_SEPARATE,ITM_AVAIL_FROM_TIME,ITM_AVAIL_TO_TIME,ITM_TRACE_TEXT,DEPT_CODE,DEPT_DESC,IT_CL_DESC';
            $mine->generate_DatatTable($tableName, $columns);
            exit;
        }    

        public function insertItem()
        {
            try {
                $sysid = $this->request->getPost('ITM_ID');

                $validate = $this->validate([
                    'ITM_NAME' => ['label' => 'Name', 'rules' => 'required|is_unique[FLXY_ITEM.ITM_NAME,ITM_ID,' . $sysid . ']'],
                    'ITM_CODE' => ['label' => 'Code', 'rules' => 'required|is_unique[FLXY_ITEM.ITM_CODE,ITM_ID,' . $sysid . ']'],
                    'ITM_DESC' => ['label' => 'Description', 'rules' => 'required'],
                    'IT_CL_DEPARTMENTS' => ['label' => 'Department', 'rules' => 'required'],
                    'IT_CL_ID' => ['label' => 'Item Class', 'rules' => 'required'],
                    'ITM_QTY_IN_STOCK' => ['label' => 'QTY in Stock', 'rules' => 'required'],
                    'ITM_QTY_DEFAULT' => ['label' => 'Default Quantity', 'rules' => 'required'],
                    'ITM_AVAIL_FROM_TIME' => ['label' => 'Available From Time', 'rules' => 'required'],
                    'ITM_AVAIL_TO_TIME' => ['label' => 'Available To Time', 'rules' => 'required']
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
                $ITEM_AVAIL_QTY = (int)$this->request->getPost('ITM_QTY_IN_STOCK');

                $data = [
                    "ITM_NAME" => trim($this->request->getPost('ITM_NAME')),
                    "ITM_CODE" => trim($this->request->getPost('ITM_CODE')),
                    "ITM_DESC" => trim($this->request->getPost('ITM_DESC')),
                    "ITM_TRACE_TEXT" => trim($this->request->getPost('ITM_TRACE_TEXT')),
                    "ITM_DEPARTMENTS" => trim($this->request->getPost('IT_CL_DEPARTMENTS')),
                    "IT_CL_ID" => trim($this->request->getPost('IT_CL_ID')),
                    "ITM_QTY_IN_STOCK" => trim($this->request->getPost('ITM_QTY_IN_STOCK')),
                    "ITM_QTY_DEFAULT" => trim($this->request->getPost('ITM_QTY_DEFAULT')),
                    "ITM_AVAIL_FROM_TIME" => trim($this->request->getPost('ITM_AVAIL_FROM_TIME')),
                    "ITM_AVAIL_TO_TIME" => trim($this->request->getPost('ITM_AVAIL_TO_TIME')),
                    "ITM_PRINT" => trim($this->request->getPost('ITM_PRINT')),
                    "ITM_SELL_CONTROL" => trim($this->request->getPost('ITM_SELL_CONTROL')),
                    "ITM_SELL_SEPARATE" => trim($this->request->getPost('ITM_SELL_SEPARATE')),
                    "ITM_STATUS" => trim($this->request->getPost('ITM_STATUS')),
                ];

                if(empty($sysid)){
                    $data["ITEM_AVAIL_QTY"]  = trim($this->request->getPost('ITM_QTY_IN_STOCK'));                    
                }

                $return = !empty($sysid) ? $this->Db->table('FLXY_ITEM')->where('ITM_ID', $sysid)->update($data) : $this->Db->table('FLXY_ITEM')->insert($data);
                $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
                echo json_encode($result);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }    

        public function editItem()
        {
            $param = ['SYSID' => $this->request->getPost('sysid')];

            $sql = "SELECT ITM_ID,IT_CL_CODE,ITM_CODE,ITM_NAME,ITM_DESC,FLXY_ITEM.IT_CL_ID,ITM_DIS_SEQ,IT_CL_DEPARTMENTS,ITM_QTY_IN_STOCK,ITM_QTY_DEFAULT,ITM_PRINT,ITM_SELL_CONTROL,ITM_SELL_SEPARATE,ITM_AVAIL_FROM_TIME,ITM_AVAIL_TO_TIME,ITM_TRACE_TEXT,DEPT_CODE,DEPT_DESC,IT_CL_DESC,ITM_STATUS
            FROM FLXY_ITEM LEFT JOIN FLXY_DEPARTMENT ON ITM_DEPARTMENTS = DEPT_ID LEFT JOIN FLXY_ITEM_CLASS ON FLXY_ITEM.IT_CL_ID = FLXY_ITEM_CLASS.IT_CL_ID
            WHERE ITM_ID=:SYSID:";

            $response = $this->Db->query($sql, $param)->getResultArray();
            echo json_encode($response);
        }

        public function deleteItem()
        {
            $sysid = $this->request->getPost('sysid');

            try {
                $return = $this->Db->table('FLXY_ITEM')->delete(['ITM_ID' => $sysid]);
                $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
                echo json_encode($result);
            } catch (\Exception $e) {
            return $e->getMessage();
        }
        }
        
        
        public function itemClassList()
        {
            $deptcode = $this->request->getPost("deptcode");
            $class_id = $this->request->getPost("class_id");
            if($deptcode != '')
                $sql = "SELECT IT_CL_ID,IT_CL_CODE,IT_CL_DESC FROM FLXY_ITEM_CLASS WHERE IT_CL_DEPARTMENTS='$deptcode' ";
            else
                $sql = "SELECT IT_CL_ID,IT_CL_CODE,IT_CL_DESC FROM FLXY_ITEM_CLASS";

            $response = $this->Db->query($sql)->getResultArray();
            $option='<option value="">Select Item Class</option>';
            $selected = "";
            foreach($response as $row){
                if($row['IT_CL_ID'] == $class_id){
                    $selected = "selected";
                }

                $option.= '<option value="'.$row['IT_CL_ID'].'"'.$selected.'>'.$row['IT_CL_CODE'].' | '.$row['IT_CL_DESC'].'</option>';
            }
            echo $option;
        }

        public function itemDepartmentList()
        {
            $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

            $sql = "SELECT *
                    FROM FLXY_DEPARTMENT";

            if ($search != '') {
                $sql .= " WHERE DEPT_CODE LIKE '%$search%'
                        OR DEPT_DESC LIKE '%$search%'";
            }

            $response = $this->Db->query($sql)->getResultArray();

            $option = '<option value="">Choose an Option</option>';
            foreach ($response as $row) {
                $option .= '<option value="' . $row['DEPT_ID'] . '">' . $row['DEPT_CODE'] . ' | ' . $row['DEPT_DESC'] . '</option>';
            }

            return $option;
        }

        /**************     Daily Inventory Functions      ***************/

        public function dailyInventory()
        {
    
            $itemDepartmentLists = $this->itemDepartmentList();
    
            $data = [
                'itemDepartmentLists' => $itemDepartmentLists
            ];
    
            $data['title'] = getMethodName();
            $data['session'] = $this->session;
            
            return view('Master/DailyInventoryView', $data);
        }
    
        public function DailyInventoryView()
        {
            $mine = new ServerSideDataTable(); // loads and creates instance
            $tableName = 'FLXY_DAILY_INVENTORY INNER JOIN FLXY_ITEM ON FLXY_DAILY_INVENTORY.ITM_ID = FLXY_ITEM.ITM_ID INNER JOIN FLXY_ITEM_CLASS ON FLXY_DAILY_INVENTORY.IT_CL_ID = FLXY_ITEM_CLASS.IT_CL_ID';
            $columns = 'ITM_DLY_ID,ITM_NAME,IT_CL_CODE,IT_CL_DESC,ITM_DLY_BEGIN_DATE,ITM_DLY_END_DATE,ITM_DLY_QTY,ITM_DLY_SUN,ITM_DLY_MON,ITM_DLY_TUE,ITM_DLY_WED,ITM_DLY_THU,ITM_DLY_THU,ITM_DLY_FRI,ITM_DLY_SAT,ITM_DLY_STATUS';
            $mine->generate_DatatTable($tableName, $columns);
            exit;
        }    

        public function insertDailyInventory()
        {
            try {
                $sysid = $this->request->getPost('ITM_DLY_ID');

                $validate = $this->validate([
                    'IT_CL_ID' => ['label' => 'Item Class', 'rules' => 'required'],
                    'ITM_ID' => ['label' => 'Item', 'rules' => 'required'],
                    'ITM_DLY_BEGIN_DATE' => ['label' => 'Begin Date', 'rules' => 'required'],
                    'ITM_DLY_END_DATE' => ['label' => 'End Date', 'rules' => 'required'],
                    'ITM_DLY_QTY' => ['label' => 'Quantity', 'rules' => 'required']
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
                    "IT_CL_ID"           => trim($this->request->getPost('IT_CL_ID')),
                    "ITM_ID"             => trim($this->request->getPost('ITM_ID')),
                    "ITM_DLY_BEGIN_DATE" => trim($this->request->getPost('ITM_DLY_BEGIN_DATE')),
                    "ITM_DLY_END_DATE"   => trim($this->request->getPost('ITM_DLY_END_DATE')),
                    "ITM_DLY_QTY"        => trim($this->request->getPost('ITM_DLY_QTY')),                    
                    "ITM_DLY_SUN"        => trim($this->request->getPost('ITM_DLY_SUN')),
                    "ITM_DLY_MON"        => trim($this->request->getPost('ITM_DLY_MON')),
                    "ITM_DLY_TUE"        => trim($this->request->getPost('ITM_DLY_TUE')),
                    "ITM_DLY_WED"        => trim($this->request->getPost('ITM_DLY_WED')),
                    "ITM_DLY_THU"        => trim($this->request->getPost('ITM_DLY_THU')),
                    "ITM_DLY_FRI"        => trim($this->request->getPost('ITM_DLY_FRI')),
                    "ITM_DLY_SAT"        => trim($this->request->getPost('ITM_DLY_SAT')),
                    "ITM_DLY_STATUS"     => trim($this->request->getPost('ITM_DLY_STATUS'))
                ];
                if(empty($sysid))
                    $data["ITM_AVAIL_QTY"] = trim($this->request->getPost('ITM_DLY_QTY'));

                $return = !empty($sysid) ? $this->Db->table('FLXY_DAILY_INVENTORY')->where('ITM_DLY_ID', $sysid)->update($data) : $this->Db->table('FLXY_DAILY_INVENTORY')->insert($data);
                $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
                echo json_encode($result);
            } catch (\Exception $e) {
            return $e->getMessage();
        }
        }    

        public function editDailyInventory()
        {
            $param = ['SYSID' => $this->request->getPost('sysid')];

            $sql = "SELECT ITM_DLY_ID,FLXY_DAILY_INVENTORY.ITM_ID,FLXY_DAILY_INVENTORY.IT_CL_ID,FLXY_ITEM.ITM_NAME,FLXY_ITEM_CLASS.IT_CL_CODE,FLXY_ITEM_CLASS.IT_CL_DESC,ITM_DLY_BEGIN_DATE,ITM_DLY_END_DATE,ITM_DLY_QTY, ITM_DLY_SUN,ITM_DLY_MON, ITM_DLY_TUE,ITM_DLY_WED,ITM_DLY_THU,ITM_DLY_THU,ITM_DLY_FRI, ITM_DLY_SAT,ITM_DLY_STATUS
            FROM FLXY_DAILY_INVENTORY INNER JOIN FLXY_ITEM ON FLXY_DAILY_INVENTORY.ITM_ID = FLXY_ITEM.ITM_ID INNER JOIN FLXY_ITEM_CLASS ON FLXY_DAILY_INVENTORY.IT_CL_ID = FLXY_ITEM_CLASS.IT_CL_ID
            WHERE ITM_DLY_ID=:SYSID:";

            $response = $this->Db->query($sql, $param)->getResultArray();
            echo json_encode($response);
        }

        public function deleteDailyInventory()
        {
            $sysid = $this->request->getPost('sysid');

            try {
                $return = $this->Db->table('FLXY_DAILY_INVENTORY')->delete(['ITM_DLY_ID' => $sysid]);
                $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
                echo json_encode($result);
            } catch (\Exception $e) {
            return $e->getMessage();
        }
        }
        

        public function itemList()
        {
            $item_class_id = $this->request->getPost("item_class_id");
            $item_id = $this->request->getPost("item_id");
            $selected = "";
            if($item_class_id != ''){
                $sql = "SELECT * FROM FLXY_ITEM WHERE IT_CL_ID='$item_class_id' ";           

                $response = $this->Db->query($sql)->getResultArray();
                $option='<option value="">Select Item Class</option>';
                
                foreach($response as $row){
                    $selected = "";
                    if($row['ITM_ID'] == $item_id){
                       $selected = "selected";
                    }

                    $option.= '<option value="'.$row['ITM_ID'].'"'.$selected.'>'.$row['ITM_CODE'].' | '.$row['ITM_NAME'].'</option>';
                }
                echo $option;
            }
            else echo $option = '';
            
        }


    /**************      Guest Type Functions      ***************/

    public function guestType()
    {
        $data['title'] = getMethodName();
        $data['session'] = $this->session;
        
        return view('Master/GuestTypeView', $data);
    }

    public function GuestTypeView()
    {

        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_GUEST_TYPE';
        $columns = 'GST_TYPE_ID,GST_TYPE_CODE,GST_TYPE_DESC,GST_TYPE_DIS_SEQ,GST_TYPE_CRTD_DT';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function insertGuestType()
    {
        try {
            $sysid = $this->request->getPost('GST_TYPE_ID');

            $validate = $this->validate([
                'GST_TYPE_CODE' => ['label' => 'Guest Type Code', 'rules' => 'required|is_unique[FLXY_GUEST_TYPE.GST_TYPE_CODE,GST_TYPE_ID,' . $sysid . ']'],
                'GST_TYPE_DESC' => ['label' => 'Guest Type Description', 'rules' => 'required'],
                'GST_TYPE_DIS_SEQ' => ['label' => 'Display Sequence', 'rules' => 'permit_empty|greater_than_equal_to[0]']                
            ]);
            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }

            $data = [
                "GST_TYPE_CODE" => trim($this->request->getPost('GST_TYPE_CODE')),
                "GST_TYPE_DESC" => trim($this->request->getPost('GST_TYPE_DESC')),
                "GST_TYPE_DIS_SEQ" => trim($this->request->getPost('GST_TYPE_DIS_SEQ')) != '' ? trim($this->request->getPost('GST_TYPE_DIS_SEQ')) : '',
               
            ];

            $return = !empty($sysid) ? $this->Db->table('FLXY_GUEST_TYPE')->where('GST_TYPE_ID', $sysid)->update($data) : $this->Db->table('FLXY_GUEST_TYPE')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function checkGuestType($gtCode)
    {
        $sql = "SELECT GST_TYPE_ID
                FROM FLXY_GUEST_TYPE
                WHERE GST_TYPE_CODE = '" . $gtCode . "'";

        $response = $this->Db->query($sql)->getNumRows();
        return $gtCode == '' || strlen($gtCode) > 10 ? 1 : $response; // Send found row even if submitted code is empty
    }

    public function copyGuestType()
    {
        try {
            $param = ['SYSID' => $this->request->getPost('main_GST_TYPE_ID')];

            $sql = "SELECT GST_TYPE_ID, GST_TYPE_CODE, GST_TYPE_DESC, GST_TYPE_DIS_SEQ
                    FROM FLXY_GUEST_TYPE
                    WHERE GST_TYPE_ID=:SYSID:";

            $origGuestCode = $this->Db->query($sql, $param)->getResultArray()[0];
            $no_of_added = 0;
            $submitted_fields = $this->request->getPost('group-a');

            if ($submitted_fields != null) {
                foreach ($submitted_fields as $submitted_field) {
                    if (!$this->checkGuestType($submitted_field['GST_TYPE_CODE'])) // Check if entered Guest Type already exists
                    {
                        $newRateCode = [
                            "GST_TYPE_CODE" => trim($submitted_field["GST_TYPE_CODE"]),
                            "GST_TYPE_DESC" => $origGuestCode["GST_TYPE_DESC"],
                            "GST_TYPE_DIS_SEQ" => '',
                           
                        ];

                        $this->Db->table('FLXY_GUEST_TYPE')->insert($newRateCode);

                        $no_of_added += $this->Db->affectedRows();
                    }
                }
            }

            echo $no_of_added;
            exit;

        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function GuestTypeList()
    {
        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

        $sql = "SELECT GST_TYPE_CODE,GST_TYPE_DESC,GST_TYPE_DIS_SEQ
                FROM FLXY_GUEST_TYPE";

        if ($search != '') {
            $sql .= " WHERE GST_TYPE_CODE LIKE '%$search%'
                      OR GST_TYPE_DESC LIKE '%$search%'";
        }

        $response = $this->Db->query($sql)->getResultArray();

        $option = '<option value="">Choose an Option</option>';
        foreach ($response as $row) {
            $option .= '<option value="' . $row['GST_TYPE_ID'] . '">' . $row['GST_TYPE_CODE'] . ' | ' . $row['GST_TYPE_DESC'] . '</option>';
        }

        return $option;
    }

    public function editGuestType()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];

        $sql = "SELECT GST_TYPE_ID, GST_TYPE_CODE, GST_TYPE_DESC, GST_TYPE_DIS_SEQ                
                FROM FLXY_GUEST_TYPE
                WHERE GST_TYPE_ID=:SYSID:";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteGuestType()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            $return = $this->Db->table('FLXY_GUEST_TYPE')->delete(['GST_TYPE_ID' => $sysid]);
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function registerCards(){
        $data['title'] = getMethodName();
        $data['session'] = $this->session;

        $roomClassLists = $this->roomClassLists();
        $membershipLists = $this->membershipLists();
        $rateCodeLists = $this->rateCodeLists();
        $vipCodeLists = $this->vipCodeLists();
    
        $data = [
            'roomClassLists' => $roomClassLists,
            'membershipLists' => $membershipLists,
            'rateCodeLists' => $rateCodeLists,
            'vipCodeLists' => $vipCodeLists
        ];

        
        return view('Reservation/RegistrationCardView', $data);
    }


    public function registerCardPrint(){
        $data['title'] = getMethodName();
        $data['js_to_load'] = "app-invoice-print.js";
        return view('Reservation/RegisterCard',$data);

    }
    public function registerCardPreview(){

        $data['title'] = getMethodName();
        $sql = "SELECT RESV_ARRIVAL_DT, RESV_DEPARTURE, RESV_NIGHT, RESV_ADULTS, RESV_CHILDREN, RESV_RATE, RESV_RM_TYPE, FROM FLXY_RESERVATION";

        if ($_SESSION['ARRIVAL_DATE'] != '') {
            $ARRIVAL_DATE = $_SESSION['ARRIVAL_DATE'];
            $sql .= " WHERE RESV_ARRIVAL_DT LIKE '%$ARRIVAL_DATE%' ";                   
        }

        if($_SESSION['ETA_FROM_TIME'] != '' && $_SESSION['ETA_TO_TIME'] !='')        
            $sql .= " OR RESV_ETA BETWEEN '".$_SESSION['ETA_FROM_TIME']." AND '".$_SESSION['ETA_TO_TIME'];
        
        if($_SESSION['ETA_FROM_TIME'] != '')         
            $sql .= " OR  RESV_ETA > '".$_SESSION['ETA_FROM_TIME'];  

        if($_SESSION['ETA_TO_TIME'] != '')         
            $sql .= " OR RESV_ETA < '".$_SESSION['ETA_TO_TIME']; 

        if($_SESSION['ROOM_CLASS'] != '')         
            $sql .= " OR RESV_ROOM_CLASS = '".$_SESSION['ROOM_CLASS']; 
        
        if($_SESSION['RATE_CODE'] != '')         
            $sql .= " OR RESV_RATE_CODE = '".$_SESSION['RATE_CODE'];
        
        if($_SESSION['MEM_TYPE'] != '')         
            $sql .= " OR RESV_MEMBER_TY = '".$_SESSION['MEM_TYPE'];
        
        $response = $this->Db->query($sql)->getResultArray(); 

        return view('Reservation/RegisterCardPreview',$data);

    }

    public function registerCardSaveDetails(){

        $_SESSION['ARRIVAL_DATE']       = date("Y-m-d",strtotime($this->request->getPost('ARRIVAL_DATE')));
        $_SESSION['ETA_FROM_TIME']      = $this->request->getPost('ETA_FROM_TIME');
        $_SESSION['ETA_TO_TIME']        = $this->request->getPost('ETA_TO_TIME');
        $_SESSION['RESV_INDIV']         = $this->request->getPost('RESV_INDIV');
        $_SESSION['RESV_BLOCK']         = $this->request->getPost('RESV_BLOCK');
        $_SESSION['RESV_FROM_NAME']     = $this->request->getPost('RESV_FROM_NAME');
        $_SESSION['RESV_TO_NAME']       = $this->request->getPost('RESV_TO_NAME');
        $_SESSION['ROOM_CLASS']         = $this->request->getPost('ROOM_CLASS');
        $_SESSION['RATE_CODE']          = $this->request->getPost('RATE_CODE');
        $_SESSION['MEM_TYPE']           = $this->request->getPost('MEM_TYPE');
        $_SESSION['VIP_CODE']           = $this->request->getPost('VIP_CODE');
        $_SESSION['IN_HOUSE_GUESTS']    = $this->request->getPost('IN_HOUSE_GUESTS');       

        echo json_encode(['success' => '200']);
    }
   
    public function roomClassLists()
        {
            $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

            $sql = "SELECT RM_CL_ID, RM_CL_CODE, RM_CL_DESC
                    FROM FLXY_ROOM_CLASS";

            if ($search != '') {
                $sql .= " WHERE RM_CL_CODE LIKE '%$search%'
                        OR RM_CL_DESC LIKE '%$search%'";
            }

            $response = $this->Db->query($sql)->getResultArray();

            $option = '<option value="">Choose an Option</option>';
            foreach ($response as $row) {
                $option .= '<option value="' . $row['RM_CL_ID'] . '">' . $row['RM_CL_CODE'] . ' | ' . $row['RM_CL_DESC'] . '</option>';
            }

            return $option;
        }
        public function membershipLists()
        {
            $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

            $sql = "SELECT MEM_ID, MEM_CODE, MEM_DESC
                    FROM FLXY_MEMBERSHIP";

            if ($search != '') {
                $sql .= " WHERE MEM_CODE LIKE '%$search%'
                        OR MEM_DESC LIKE '%$search%'";
            }

            $response = $this->Db->query($sql)->getResultArray();

            $option = '<option value="">Choose an Option</option>';
            foreach ($response as $row) {
                $option .= '<option value="' . $row['MEM_ID'] . '">' . $row['MEM_CODE'] . ' | ' . $row['MEM_DESC'] . '</option>';
            }

            return $option;
        }

        public function rateCodeLists()
        {
            $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

            $sql = "SELECT RT_CD_ID, RT_CD_CODE, RT_CD_DESC
                    FROM FLXY_RATE_CODE";

            if ($search != '') {
                $sql .= " WHERE RT_CD_CODE LIKE '%$search%'
                        OR RT_CD_DESC LIKE '%$search%'";
            }

            $response = $this->Db->query($sql)->getResultArray();

            $option = '<option value="">Choose an Option</option>';
            foreach ($response as $row) {
                $option .= '<option value="' . $row['RT_CD_ID'] . '">' . $row['RT_CD_CODE'] . ' | ' . $row['RT_CD_DESC'] . '</option>';
            }

            return $option;
        }

        public function vipCodeLists()
        {
            $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

            $sql = "SELECT VIP_ID, VIP_DESC
                    FROM FLXY_VIP";

            if ($search != '') {
                $sql .= " WHERE VIP_DESC LIKE '%$search%'
                        ";
            }

            $response = $this->Db->query($sql)->getResultArray();

            $option = '<option value="">Choose an Option</option>';
            foreach ($response as $row) {
                $option .= '<option value="' . $row['VIP_ID'] . '">' . $row['VIP_DESC']  . '</option>';
            }

            return $option;
        }


        ///////////// Menu ///////////



        public function Menu()  
        {            
            $data['title'] = getMethodName();
            $data['session'] = $this->session;  
           // $data['menuLists'] = $this->menuLists();
            return view('Master/MenuView', $data);           
        }
    
        public function MenuView()
        {
            $mine      = new ServerSideDataTable(); 
            $tableName = 'FLXY_MENU LEFT JOIN (SELECT MENU_ID AS P_MENU_ID, MENU_NAME AS P_MENU_NAME FROM FLXY_MENU WHERE PARENT_MENU_ID = 0) P ON P.P_MENU_ID = FLXY_MENU.PARENT_MENU_ID';
            $columns = 'MENU_ID|MENU_CODE|MENU_NAME|MENU_URL|MENU_DESC|MENU_DIS_SEQ|MENU_STATUS|P_MENU_NAME'; 

            $mine->generate_DatatTable($tableName, $columns, [], '|');
            exit;
        }
    
        public function insertMenu()
        {
            try {
                $sysid = $this->request->getPost('MENU_ID');
                $permissions = '';
                $validate = $this->validate([                  
                    'MENU_CODE' => ['label' => 'Menu Code', 'rules' => 'required|is_unique[FLXY_MENU.MENU_CODE,MENU_ID,' . $sysid . ']'],
                    'MENU_NAME' => ['label' => 'Menu Name', 'rules' => 'required|is_unique[FLXY_MENU.MENU_NAME,MENU_ID,' . $sysid . ']'],
                    'MENU_DESC' => ['label' => 'Description', 'rules' => 'required'],
                                      
                    
                ]);
                if (!$validate) {
                    $validate = $this->validator->getErrors();
                    $result["SUCCESS"] = "-402";
                    $result[]["ERROR"] = $validate;
                    $result = $this->responseJson("-402", $validate);
                    echo json_encode($result);
                    exit;
                }
    
                $data = [
                    "PARENT_MENU_ID" => trim($this->request->getPost('PARENT_MENU_ID')),
                    "MENU_CODE" => trim($this->request->getPost('MENU_CODE')),
                    "MENU_NAME" => trim($this->request->getPost('MENU_NAME')),
                    "MENU_URL" => trim($this->request->getPost('MENU_URL')),
                    "MENU_DESC" => trim($this->request->getPost('MENU_DESC')),
                    "MENU_ICON" => trim($this->request->getPost('MENU_ICON')),
                    "MENU_CSS_CLASS" => !empty(trim($this->request->getPost('MENU_CSS_CLASS'))) ? trim($this->request->getPost('MENU_CSS_CLASS')) : null,
                    "MENU_DIS_SEQ" => trim($this->request->getPost('MENU_DIS_SEQ')),
                    "SHOW_IN_MENU" => null !==($this->request->getPost('SHOW_IN_MENU'))? 1:0, 
                    "MENU_STATUS" => null !==($this->request->getPost('MENU_STATUS'))? 1:0               
                ];

                
                if(!empty($sysid)) {
                        if(null !== $this->request->getPost('MENU_STATUS') &&  $this->request->getPost('MENU_STATUS') == 1){
                        $permissions = $this->Db->table('FLXY_USER_ROLE_PERMISSION')->where('ROLE_MENU_ID', $sysid)->update(['ROLE_PERM_STATUS' => '1']);                   
                        $childPermissions = $this->Db->query("UPDATE FLXY_USER_ROLE_PERMISSION SET ROLE_PERM_STATUS = '1' WHERE ROLE_MENU_ID IN (SELECT MENU_ID FROM FLXY_MENU WHERE PARENT_MENU_ID = $sysid)");
                        
                    
                    }
                    else{
                        $permissions = $this->Db->table('FLXY_USER_ROLE_PERMISSION')->where('ROLE_MENU_ID', $sysid)->update(['ROLE_PERM_STATUS' => '0']);
                        $childPermissions = $this->Db->query("UPDATE FLXY_USER_ROLE_PERMISSION SET ROLE_PERM_STATUS = '0' WHERE ROLE_MENU_ID IN (SELECT MENU_ID FROM FLXY_MENU WHERE PARENT_MENU_ID = $sysid)");
                        
                    }
            }
    
                $return = !empty($sysid) ? $this->Db->table('FLXY_MENU')->where('MENU_ID', $sysid)->update($data) : $this->Db->table('FLXY_MENU')->insert($data);
               
                $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
                echo json_encode($result);
            } catch (\Exception $e) {
            return $e->getMessage();
        }
        }

        
    
        public function editMenu()
        {
            $param = ['SYSID' => $this->request->getPost('sysid')];    
            $sql = "SELECT MENU_ID, PARENT_MENU_ID, MENU_CODE, MENU_NAME,MENU_URL, MENU_DESC, MENU_ICON, MENU_CSS_CLASS, MENU_DIS_SEQ, MENU_STATUS, SHOW_IN_MENU
                    FROM FLXY_MENU
                    WHERE MENU_ID=:SYSID: ";
    
            $response = $this->Db->query($sql, $param)->getResultArray();
            echo json_encode($response);
        }
    
    
        public function checkSubMenu($subMenuCode)
        {
            $sql = "SELECT SUB_MENU_ID
                    FROM FLXY_SUB_MENU
                    WHERE SUB_MENU_CODE = '" . $subMenuCode . "'";
    
            $response = $this->Db->query($sql)->getNumRows();
            return $subMenuCode == '' || strlen($subMenuCode) > 10 ? 1 : $response; // Send found row even if submitted code is empty
        }
    
        public function copyMenu()
        {
            try {
                $param = ['SYSID' => $this->request->getPost('main_SubMenu_ID')];
    
                $sql = "SELECT MENU_ID, SUB_MENU_ID, SUB_MENU_CODE, SUB_MENU_NAME,SUB_MENU_DESC, SUB_MENU_DIS_SEQ, SUB_MENU_STATUS
                        FROM FLXY_SUB_MENU
                        WHERE SUB_MENU_ID=:SYSID:";
    
                $origGuestCode = $this->Db->query($sql, $param)->getResultArray()[0];
                $no_of_added = 0;
                $submitted_fields = $this->request->getPost('group-a');
    
                if ($submitted_fields != null) {
                    foreach ($submitted_fields as $submitted_field) {
                        if (!$this->checkMenu($submitted_field['SUB_MENU_CODE'])) // Check if entered Guest Type already exists
                        {
                            $newRateCode = [
                                "SUB_MENU_CODE" => trim($submitted_field["SUB_MENU_CODE"]),
                                "MENU_ID"       => $origGuestCode["MENU_ID"],
                                "SUB_MENU_NAME" => $origGuestCode["SUB_MENU_NAME"],
                                "SUB_MENU_DESC" => $origGuestCode["SUB_MENU_DESC"],
                                "SUB_MENU_DIS_SEQ" => '',
                                "SUB_MENU_STATUS" => $origGuestCode["SUB_MENU_STATUS"],
                               
                            ];
    
                            $this->Db->table('FLXY_SUB_MENU')->insert($newRateCode);
    
                            $no_of_added+= $this->Db->affectedRows();
                        }
                    }
                }
    
                echo $no_of_added;
                exit;
    
            } catch (\Exception $e) {
            return $e->getMessage();
        }
        }


        public function deleteMenu()
        {
            $sysid = $this->request->getPost('sysid');
    
            try {
                //Soft Delete
                //$data = ["CUR_DELETED" => "1"];
                //$return =  $this->Db->table('FLXY_CURRENCY')->where('CUR_ID', $sysid)->update($data);

                $sql = "SELECT MENU_ID, MENU_NAME
                    FROM FLXY_MENU WHERE PARENT_MENU_ID = $sysid";
                $parentCount = $this->Db->query($sql)->getNumRows();
                if($parentCount > 0)
                    $result = $this->responseJson("0", "Record cannot be deleted. Child menus exists");
                else{
                    $return = $this->Db->table('FLXY_MENU')->delete(['MENU_ID' => $sysid]);
                    $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
                }               
                echo json_encode($result);
            } catch (\Exception $e) {
            return $e->getMessage();
        }
        }
    

        public function menuList(){
            $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

            $sql = "SELECT MENU_ID, MENU_NAME
                    FROM FLXY_MENU WHERE PARENT_MENU_ID = 0";

            if ($search != '') {
                $sql .= " WHERE MENU_NAME LIKE '%$search%'
                        ";
            }

            $response = $this->Db->query($sql)->getResultArray();
            
            $option = '<option value="">Choose an Option</option>';
            foreach ($response as $row) {
                $option .= '<option value="' . $row['MENU_ID'] . '">' . $row['MENU_NAME']  . '</option>';
            }

            return $option;
        }


        public function searchMenu()
        {
            $result = [];

            $sql = "SELECT M.MENU_NAME, M.MENU_URL, PAR.MENU_NAME AS MENU_PARENT, PAR.MENU_ICON
                    FROM FLXY_MENU M
                    INNER JOIN FLXY_USER_ROLE_PERMISSION RP ON (RP.ROLE_MENU_ID = M.MENU_ID AND RP.ROLE_ID = '1' 
                                                                AND RP.ROLE_PERM_STATUS = '1')
                    LEFT JOIN FLXY_MENU PAR ON (PAR.MENU_ID = M.PARENT_MENU_ID AND PAR.PARENT_MENU_ID = '0' 
                                                AND PAR.SHOW_IN_MENU = 1 AND PAR.MENU_STATUS = 1)
                    WHERE M.PARENT_MENU_ID != 0 AND M.SHOW_IN_MENU = 1 AND M.MENU_STATUS = 1 
                    ORDER BY M.PARENT_MENU_ID, M.MENU_ID ASC";

            $response = $this->Db->query($sql)->getResultArray();

            foreach($response as $row){
                $result[] = ["name" => $row['MENU_PARENT'] .' - '. $row['MENU_NAME'],
                             "icon" => $row['MENU_ICON'],
                             "url"  => base_url($row['MENU_URL'])];
            }

            echo json_encode($result);
        }



        ///////////////////////////////////

        public function proformaFolio(){
          
            $_SESSION['PROFORMA_RESV_ID']  = $this->request->getPost('PROFORMA_RESV_ID');
            $_SESSION['FOLIO_TXT_ONE']     = $this->request->getPost('FOLIO_TXT_ONE');           
            $_SESSION['FOLIO_TXT_TWO']     = $this->request->getPost('FOLIO_TXT_TWO');
            $_SESSION['PRINT_PHONE']       = $this->request->getPost('PRINT_PHONE');
            $_SESSION['PRINT_CHECK']       = $this->request->getPost('PRINT_CHECK');
            $_SESSION['PRINT_EMAIL']       = $this->request->getPost('PRINT_EMAIL');
            echo '1';
           
           
        }
        public function previewProFormaFolio(){          
            $dompdf = new \Dompdf\Dompdf(); 
            $options = new \Dompdf\Options();
            $options->setIsRemoteEnabled(true);
            $options->setDefaultFont('Courier');
            $dompdf = new \Dompdf\Dompdf($options);

            $sql = "SELECT CONCAT_WS(' ', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME) AS FULLNAME, CUST_EMAIL, cname, RESV_ARRIVAL_DT, RESV_DEPARTURE, RESV_NO, RESV_RATE FROM FLXY_RESERVATION LEFT JOIN FLXY_CUSTOMER ON RESV_NAME = CUST_ID LEFT JOIN COUNTRY ON iso2 = CUST_COUNTRY WHERE RESV_ID = ".$_SESSION['PROFORMA_RESV_ID'];  
            $response = $this->Db->query($sql)->getResultArray();

            if(!empty($response)){
                foreach ($response as $row) {
                    $data = ['CUST_NAME'=> $row['FULLNAME'], 'CUST_COUNTRY'=> $row['cname'], 'RESV_ARRIVAL_DT'=> $row['RESV_ARRIVAL_DT'], 'RESV_DEPARTURE'=> $row['RESV_DEPARTURE'], 'RESV_NO'=> $row['RESV_NO'], 'RESV_RATE'=> $row['RESV_RATE']];
                    $RESV_ARRIVAL_DT = $row['RESV_ARRIVAL_DT'];
                    $RESV_DEPARTURE = $row['RESV_DEPARTURE'];
                    $RESV_RATE = $row['RESV_RATE'];
                    $CUST_EMAIL = $row['CUST_EMAIL'];
                } 
            }

            $sql = "SELECT * FROM FLXY_FIXED_CHARGES INNER JOIN FLXY_TRANSACTION_CODE ON TR_CD_ID = FIXD_CHRG_TRNCODE WHERE FIXD_CHRG_RESV_ID = ".$_SESSION['PROFORMA_RESV_ID'];  
            $fixedChargesResponse = $this->Db->query($sql)->getResultArray();

            $RESV_ARRIVAL_DATE    = strtotime($RESV_ARRIVAL_DT);
            $RESV_DEPARTURE_DATE  = strtotime($RESV_DEPARTURE); 
            $datediff = $RESV_DEPARTURE_DATE - $RESV_ARRIVAL_DATE;
            $RESERV_DAYS = round($datediff / (60 * 60 * 24));
            $VAT = 0.05;
            $TABLE_CONTENTS = '';
            $FIXED_CONTENTS = '';             
            $fixed_rows = 0;
            $DEFAULT_MODE = 20;
            $DEFAULT_ROWS = 0;
            $ROOM_CHARGE_TOTAL = 0;
            $VAT_TOTAL = 0;
            $j = 0;
            $fixedChargesAMOUNT = $fixedChargesVAT = $fixedChargesTotal = $fixedChargesVATTotal = $TOTAL = $TOTALVAT = 0;
            
            for($i = 1; $i <= $RESERV_DAYS; $i++ ){
                $ROOM_CHARGE_TOTAL += $RESV_RATE;
                $VAT_TOTAL += ($RESV_RATE * $VAT);
                $DEFAULT_VAT = $RESV_RATE * $VAT;
                $DEFAULT_PAGE_BREAK = '<tr></tr><div style="margin-top:370px;margin-bottom:5px; page-break-after:always"></div></tr>';
                $sCurrentDate = gmdate("d-m-Y", strtotime("+$i day", $RESV_ARRIVAL_DATE)); 
                $CurrentDate = strtotime($sCurrentDate); 
                $sCurrentDay = gmdate("w", strtotime("+{$i} day", $RESV_ARRIVAL_DATE));
                $sCurrentD = gmdate("d", strtotime("+{$i} day", $RESV_ARRIVAL_DATE)); 
                $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
                        <td class="text-center" >'.$sCurrentDate.'</td>
                        <td class="text-center">Room Charge </td>
                        <td class="text-center"></td>
                        <td class="text-left" >'.round($RESV_RATE,2).' </td>
                        <td class="text-left">0.00</td>
                    </tr>';
                $DEFAULT_ROWS++;
                if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                    $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
                }   

                $TABLE_CONTENTS.= '
                <tr class="mt-5 mb-5">
                    <td class="text-center">'.$sCurrentDate.'</td>
                    <td class="text-center">VAT 5%</td>
                    <td class="text-center"></td>
                    <td class="text-left">'.round($DEFAULT_VAT).' </td>
                    <td class="text-left">0.00</td>
                </tr>';
                $DEFAULT_ROWS++;

                if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                        
                    $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK;   
                } 

                
                ////////// Fixed Charges //////////////               
                if(!empty($fixedChargesResponse)){
                    foreach($fixedChargesResponse as $fixedCharges) {
                      
                        $FIXD_CHRG_BEGIN_DATE =gmdate("d-m-Y", strtotime("+1 day", strtotime($fixedCharges['FIXD_CHRG_BEGIN_DATE'])) );
                        $FIXD_CHRG_END_DATE = gmdate("d-m-Y", strtotime("+1 day", strtotime($fixedCharges['FIXD_CHRG_END_DATE'])));
                        $FIXD_CHRG_FREQUENCY = $fixedCharges['FIXD_CHRG_FREQUENCY'];

                         $FIXD_CHRG_BEGIN_DATE = strtotime($FIXD_CHRG_BEGIN_DATE);
                         $FIXD_CHRG_END_DATE = strtotime($FIXD_CHRG_END_DATE);

                        if($FIXD_CHRG_FREQUENCY == 1 && $CurrentDate == $FIXD_CHRG_BEGIN_DATE){
                            $ONCE = 1;
                            $fixedChargesAMOUNT = $fixedCharges['FIXD_CHRG_AMT'] * $fixedCharges['FIXD_CHRG_QTY'];
                            $fixedChargesVAT = $fixedChargesAMOUNT * $VAT;
                            $fixedChargesTotal += $fixedChargesAMOUNT; 
                            $fixedChargesVATTotal += $fixedChargesVAT;

                            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5 ">
                            <td class="text-center">'.$sCurrentDate.'</td>
                            <td class="text-center">'.$fixedCharges['TR_CD_DESC'].' </td>
                            <td class="text-center"></td>
                            <td width="10%;" class="text-left">'.round($fixedChargesAMOUNT,2).' </td>
                            <td width="10%;" class="text-left">0.00</td>
                            </tr>';

                            $DEFAULT_ROWS++;
                            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
                            }   

                            $TABLE_CONTENTS.= '
                            <tr class="mt-5 mb-5">
                                <td class="text-center">'.$sCurrentDate.'</td>
                                <td class="text-center">VAT 5%</td>
                                <td class="text-center"></td>
                                
                                <td width="10%;" class="text-left">'.round($fixedChargesVAT,2).' </td>
                                <td width="10%;" class="text-left">0.00</td>
                            </tr>';
                            $DEFAULT_ROWS++;

                            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                        
                                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK;   
                            } 
                        }

                       
                         if($FIXD_CHRG_FREQUENCY == 2 && ($CurrentDate >= $FIXD_CHRG_BEGIN_DATE && $CurrentDate <= $FIXD_CHRG_END_DATE)){
                           
                            $fixedChargesAMOUNT = $fixedCharges['FIXD_CHRG_AMT'] * $fixedCharges['FIXD_CHRG_QTY'];
                            $fixedChargesVAT = $fixedChargesAMOUNT * $VAT;
                            $fixedChargesTotal += $fixedChargesAMOUNT; 
                            $fixedChargesVATTotal += $fixedChargesVAT;

                            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5 ">
                            <td class="text-center">'.$sCurrentDate.'</td>
                            <td class="text-center">'.$fixedCharges['TR_CD_DESC'].' </td>
                            <td class="text-center"></td>
                            <td width="10%;" class="text-left">'.round($fixedChargesAMOUNT,2).' </td>
                            <td width="10%;" class="text-left">0.00</td>
                            </tr>';

                            $DEFAULT_ROWS++;
                            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
                            }   

                            $TABLE_CONTENTS.= '
                            <tr class="mt-5 mb-5">
                                <td class="text-center">'.$sCurrentDate.'</td>
                                <td class="text-center">VAT 5%</td>
                                <td class="text-center"></td>
                                
                                <td width="10%;" class="text-left">'.round($fixedChargesVAT,2).' </td>
                                <td width="10%;" class="text-left">0.00</td>
                            </tr>';
                            $DEFAULT_ROWS++;

                            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                        
                                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK;   
                            } 
                        } 
                        
                        
                        else if($FIXD_CHRG_FREQUENCY == 3 && ($CurrentDate >= $FIXD_CHRG_BEGIN_DATE && $CurrentDate <= $FIXD_CHRG_END_DATE) && ($sCurrentDay == $fixedCharges['FIXD_CHRG_WEEKLY'])){
                            $fixedChargesAMOUNT = $fixedCharges['FIXD_CHRG_AMT'] * $fixedCharges['FIXD_CHRG_QTY'];
                            $fixedChargesVAT = $fixedChargesAMOUNT * $VAT;
                            $fixedChargesTotal += $fixedChargesAMOUNT; 
                            $fixedChargesVATTotal += $fixedChargesVAT;

                            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5 ">
                            <td class="text-center">'.$sCurrentDate.'</td>
                            <td class="text-center">'.$fixedCharges['TR_CD_DESC'].' </td>
                            <td class="text-center"></td>
                            <td width="10%;" class="text-left">'.round($fixedChargesAMOUNT,2).' </td>
                            <td width="10%;" class="text-left">0.00</td>
                            </tr>';

                            $DEFAULT_ROWS++;
                            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
                            }   

                            $TABLE_CONTENTS.= '
                            <tr class="mt-5 mb-5">
                                <td class="text-center">'.$sCurrentDate.'</td>
                                <td class="text-center">VAT 5%</td>
                                <td class="text-center"></td>
                                
                                <td width="10%;" class="text-left">'.round($fixedChargesVAT,2).' </td>
                                <td width="10%;" class="text-left">0.00</td>
                            </tr>';
                            $DEFAULT_ROWS++;

                            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                        
                                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK;   
                            } 
                        }   

                        else if($FIXD_CHRG_FREQUENCY == 4 && ($CurrentDate >= $FIXD_CHRG_BEGIN_DATE && $CurrentDate <= $FIXD_CHRG_END_DATE) && ($sCurrentD == $fixedCharges['FIXD_CHRG_MONTHLY'])){
                            $fixedChargesAMOUNT = $fixedCharges['FIXD_CHRG_AMT'] * $fixedCharges['FIXD_CHRG_QTY'];
                            $fixedChargesVAT = $fixedChargesAMOUNT * $VAT;
                            $fixedChargesTotal += $fixedChargesAMOUNT; 
                            $fixedChargesVATTotal += $fixedChargesVAT;

                            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5 ">
                            <td class="text-center">'.$sCurrentDate.'</td>
                            <td class="text-center">'.$fixedCharges['TR_CD_DESC'].' </td>
                            <td class="text-center"></td>
                            <td width="10%;" class="text-left">'.round($fixedChargesAMOUNT,2).' </td>
                            <td width="10%;" class="text-left">0.00</td>
                            </tr>';

                            $DEFAULT_ROWS++;
                            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
                            }   

                            $TABLE_CONTENTS.= '
                            <tr class="mt-5 mb-5">
                                <td class="text-center">'.$sCurrentDate.'</td>
                                <td class="text-center">VAT 5%</td>
                                <td class="text-center"></td>
                                
                                <td width="10%;" class="text-left">'.round($fixedChargesVAT,2).' </td>
                                <td width="10%;" class="text-left">0.00</td>
                            </tr>';
                            $DEFAULT_ROWS++;

                            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                        
                                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK;   
                            } 
                        }  

                        // else if($FIXD_CHRG_FREQUENCY == 5 && ($CurrentDate >= $FIXD_CHRG_BEGIN_DATE && $CurrentDate <= $FIXD_CHRG_END_DATE) && ($sCurrentDate == $fixedCharges['FIXD_CHRG_YEARLY'])){
                        //     $fixedChargesAMOUNT = $fixedCharges['FIXD_CHRG_AMT'] * $fixedCharges['FIXD_CHRG_QTY'];
                        //     $fixedChargesVAT = $fixedChargesAMOUNT * $VAT;
                        //     $fixedChargesTotal += $fixedChargesAMOUNT; 
                        //     $fixedChargesVATTotal += $fixedChargesVAT;

                        //     $TABLE_CONTENTS.= '<tr class="mt-5 mb-5 ">
                        //     <td class="text-center">'.$sCurrentDate.'</td>
                        //     <td class="text-center">'.$fixedCharges['TR_CD_DESC'].' </td>
                        //     <td class="text-center"></td>
                        //     <td width="10%;" class="text-left">'.round($fixedChargesAMOUNT,2).' </td>
                        //     <td width="10%;" class="text-left">0.00</td>
                        //     </tr>';

                        //     $DEFAULT_ROWS++;
                        //     if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                        //         $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
                        //     }   

                        //     $TABLE_CONTENTS.= '
                        //     <tr class="mt-5 mb-5">
                        //         <td class="text-center">'.$sCurrentDate.'</td>
                        //         <td class="text-center">VAT 5%</td>
                        //         <td class="text-center"></td>
                                
                        //         <td width="10%;" class="text-left">'.round($fixedChargesVAT,2).' </td>
                        //         <td width="10%;" class="text-left">0.00</td>
                        //     </tr>';
                        //     $DEFAULT_ROWS++;

                        //     if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                        
                        //         $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK;   
                        //     } 
                        // }  

                        else if($FIXD_CHRG_FREQUENCY == 6 && ($CurrentDate >= $FIXD_CHRG_BEGIN_DATE && $CurrentDate <= $FIXD_CHRG_END_DATE) && (date('d-m',strtotime($sCurrentDate)) == date('d-m',strtotime($fixedCharges['FIXD_CHRG_YEARLY']))) ){
                            $fixedChargesAMOUNT = $fixedCharges['FIXD_CHRG_AMT'] * $fixedCharges['FIXD_CHRG_QTY'];
                            $fixedChargesVAT = $fixedChargesAMOUNT * $VAT;
                            $fixedChargesTotal += $fixedChargesAMOUNT; 
                            $fixedChargesVATTotal += $fixedChargesVAT;

                            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5 ">
                            <td class="text-center">'.$sCurrentDate.'</td>
                            <td class="text-center">'.$fixedCharges['TR_CD_DESC'].' </td>
                            <td class="text-center"></td>
                            <td width="10%;" class="text-left">'.round($fixedChargesAMOUNT,2).' </td>
                            <td width="10%;" class="text-left">0.00</td>
                            </tr>';

                            $DEFAULT_ROWS++;
                            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
                            }   

                            $TABLE_CONTENTS.= '
                            <tr class="mt-5 mb-5">
                                <td class="text-center">'.$sCurrentDate.'</td>
                                <td class="text-center">VAT 5%</td>
                                <td class="text-center"></td>
                                
                                <td width="10%;" class="text-left">'.round($fixedChargesVAT,2).' </td>
                                <td width="10%;" class="text-left">0.00</td>
                            </tr>';
                            $DEFAULT_ROWS++;

                            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                        
                                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK;   
                            } 
                        }  


                    }
                   //exit;
                }
                     
            }


            $TOTAL = $ROOM_CHARGE_TOTAL + $fixedChargesTotal;
            $TOTALVAT = $VAT_TOTAL + $fixedChargesVATTotal;

            ////////////// Footer //////////////////

            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
            <td colspan="5"><hr></td>
            </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            }   

            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
            <td class="text-center"></td>
            <td></td>
            <td>Total</td>
            <td width="10%;" class="text-center">'.round($TOTAL,2).' </td>
            <td width="10%;" class="text-center">0.00</td>
            </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            } 
            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
            <td colspan="5"><hr></td>
            </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            }   

            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
            <td class="text-center"></td>
            <td></td>
            <td>Balance</td>
            <td class="text-center">'.round($TOTAL,2).' </td>
            <td class="text-center">0.00</td>
            </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            }  
            
            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
            <td class="text-center"></td>
            <td></td>
            <td>VAT Incl. Amount</td>
            <td class="text-center">'.round($TOTAL,2).' </td>
            <td class="text-center">0.00</td>
            </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            }  
            
            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
            <td class="text-center"></td>
            <td></td>
            <td>5 % VAT</td>
            <td class="text-center">'.round($TOTALVAT,2).' </td>
            <td class="text-center">0.00</td>
            </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            }  

            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
            <td colspan="5"><hr></td>
            </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            }   
            
            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
            <td class="text-center"></td>
            <td></td>
            <td class="pt-20">Guest Signature</td>
            <td class="text-center"></td>
            </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            }   

            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
            <td colspan="5"></td>
            </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            }   
            $TABLE_CONTENTS.= '<tr class="mt-20 mb-5" >
            <td class="text-center"></td>
            <td></td>
            <td class="mt-20 mb-5 pt-20">Guest Email : '.$CUST_EMAIL.'</td>
            <td class="text-center"></td>
            </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            } 
            $TABLE_CONTENTS.= '<div class="mt-5 mb-5 pl-20" ><p>'.$_SESSION['FOLIO_TXT_ONE'].'
           </p></div>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            } 

            $TABLE_CONTENTS.= '<div class="mt-5 mb-5 pl-20"><p>'.$_SESSION['FOLIO_TXT_TWO'].'</p></div>';
             $DEFAULT_ROWS++;
             if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                 $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
             } 
            
            


          
            $data['CHARGES'] = $TABLE_CONTENTS; 
            $dompdf->loadHtml(view('Master/preview_proforma',$data));
            $dompdf->render();           
            $canvas = $dompdf->getCanvas();
            $canvas->page_text(18, 780, "{PAGE_NUM} / {PAGE_COUNT}", '', 6, array(0,0,0));
            $dompdf->stream("RESERVATON_".$_SESSION['PROFORMA_RESV_ID'].".pdf", array("Attachment" => 0));
           
        }

        

        public function printProFormaFolio(){          
                    
            $dompdf = new \Dompdf\Dompdf(); 
            $options = new \Dompdf\Options();
            $options->setIsRemoteEnabled(true);
            $options->setDefaultFont('Courier');
            $dompdf = new \Dompdf\Dompdf($options);

            $sql = "SELECT CONCAT_WS(' ', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME) AS FULLNAME, CUST_EMAIL, cname, RESV_ARRIVAL_DT, RESV_DEPARTURE, RESV_NO, RESV_RATE FROM FLXY_RESERVATION LEFT JOIN FLXY_CUSTOMER ON RESV_NAME = CUST_ID LEFT JOIN COUNTRY ON iso2 = CUST_COUNTRY WHERE RESV_ID = ".$_SESSION['PROFORMA_RESV_ID'];  
            $response = $this->Db->query($sql)->getResultArray();

            if(!empty($response)){
                foreach ($response as $row) {
                    $data = ['CUST_NAME'=> $row['FULLNAME'], 'CUST_COUNTRY'=> $row['cname'], 'RESV_ARRIVAL_DT'=> $row['RESV_ARRIVAL_DT'], 'RESV_DEPARTURE'=> $row['RESV_DEPARTURE'], 'RESV_NO'=> $row['RESV_NO'], 'RESV_RATE'=> $row['RESV_RATE']];
                    $RESV_ARRIVAL_DT = $row['RESV_ARRIVAL_DT'];
                    $RESV_DEPARTURE = $row['RESV_DEPARTURE'];
                    $RESV_RATE = $row['RESV_RATE'];
                    $CUST_EMAIL = $row['CUST_EMAIL'];
                } 
            }

            $sql = "SELECT * FROM FLXY_FIXED_CHARGES INNER JOIN FLXY_TRANSACTION_CODE ON TR_CD_ID = FIXD_CHRG_TRNCODE WHERE FIXD_CHRG_RESV_ID = ".$_SESSION['PROFORMA_RESV_ID'];  
            $fixedChargesResponse = $this->Db->query($sql)->getResultArray();

            $RESV_ARRIVAL_DATE    = strtotime($RESV_ARRIVAL_DT);
            $RESV_DEPARTURE_DATE  = strtotime($RESV_DEPARTURE); 
            $datediff = $RESV_DEPARTURE_DATE - $RESV_ARRIVAL_DATE;
            $RESERV_DAYS = round($datediff / (60 * 60 * 24));
            $VAT = 0.05;
            $TABLE_CONTENTS = '';
            $FIXED_CONTENTS = '';             
            $fixed_rows = 0;
            $DEFAULT_MODE = 20;
            $DEFAULT_ROWS = 0;
            $ROOM_CHARGE_TOTAL = 0;
            $VAT_TOTAL = 0;
            $j = 0;
            $fixedChargesAMOUNT = $fixedChargesVAT = $fixedChargesTotal = $fixedChargesVATTotal = $TOTAL = $TOTALVAT = 0;
            
            for($i = 1; $i <= $RESERV_DAYS; $i++ ){
                $ROOM_CHARGE_TOTAL += $RESV_RATE;
                $VAT_TOTAL += ($RESV_RATE * $VAT);
                $DEFAULT_VAT = $RESV_RATE * $VAT;
                $DEFAULT_PAGE_BREAK = '<tr></tr><div style="margin-top:370px;margin-bottom:5px; page-break-after:always"></div></tr>';
                $sCurrentDate = gmdate("d-m-Y", strtotime("+$i day", $RESV_ARRIVAL_DATE)); 
                $CurrentDate  = strtotime($sCurrentDate); 
                $sCurrentDay  = gmdate("w", strtotime("+{$i} day", $RESV_ARRIVAL_DATE));
                $sCurrentD    = gmdate("d", strtotime("+{$i} day", $RESV_ARRIVAL_DATE)); 
                $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
                        <td class="text-center" >'.$sCurrentDate.'</td>
                        <td class="text-center">Room Charge </td>
                        <td class="text-center"></td>
                        <td class="text-left" >'.round($RESV_RATE,2).' </td>
                        <td class="text-left">0.00</td>
                    </tr>';
                $DEFAULT_ROWS++;
                if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                    $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
                }   

                $TABLE_CONTENTS.= '
                <tr class="mt-5 mb-5">
                    <td class="text-center">'.$sCurrentDate.'</td>
                    <td class="text-center">VAT 5%</td>
                    <td class="text-center"></td>
                    <td class="text-left">'.round($DEFAULT_VAT).' </td>
                    <td class="text-left">0.00</td>
                </tr>';
                $DEFAULT_ROWS++;

                if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                        
                    $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK;   
                } 

                ////////// Fixed Charges //////////////               
                if(!empty($fixedChargesResponse)){
                    foreach($fixedChargesResponse as $fixedCharges) {
                         
                        $FIXD_CHRG_BEGIN_DATE =gmdate("d-m-Y", strtotime("+1 day", strtotime($fixedCharges['FIXD_CHRG_BEGIN_DATE'])) );
                        $FIXD_CHRG_END_DATE = gmdate("d-m-Y", strtotime("+1 day", strtotime($fixedCharges['FIXD_CHRG_END_DATE'])));
                        $FIXD_CHRG_FREQUENCY = $fixedCharges['FIXD_CHRG_FREQUENCY'];


                        $FIXD_CHRG_BEGIN_DATE = strtotime($FIXD_CHRG_BEGIN_DATE);
                        $FIXD_CHRG_END_DATE = strtotime($FIXD_CHRG_END_DATE);

                        if($FIXD_CHRG_FREQUENCY == 1 && $CurrentDate == $FIXD_CHRG_BEGIN_DATE){
                            $ONCE = 1;
                            $fixedChargesAMOUNT = $fixedCharges['FIXD_CHRG_AMT'] * $fixedCharges['FIXD_CHRG_QTY'];
                            $fixedChargesVAT = $fixedChargesAMOUNT * $VAT;
                            $fixedChargesTotal += $fixedChargesAMOUNT; 
                            $fixedChargesVATTotal += $fixedChargesVAT;

                            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5 ">
                            <td class="text-center">'.$sCurrentDate.'</td>
                            <td class="text-center">'.$fixedCharges['TR_CD_DESC'].' </td>
                            <td class="text-center"></td>
                            <td width="10%;" class="text-left">'.round($fixedChargesAMOUNT,2).' </td>
                            <td width="10%;" class="text-left">0.00</td>
                            </tr>';

                            $DEFAULT_ROWS++;
                            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
                            }   

                            $TABLE_CONTENTS.= '
                            <tr class="mt-5 mb-5">
                                <td class="text-center">'.$sCurrentDate.'</td>
                                <td class="text-center">VAT 5%</td>
                                <td class="text-center"></td>
                                
                                <td width="10%;" class="text-left">'.round($fixedChargesVAT,2).' </td>
                                <td width="10%;" class="text-left">0.00</td>
                            </tr>';
                            $DEFAULT_ROWS++;

                            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                        
                                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK;   
                            } 
                        }

                       
                         if($FIXD_CHRG_FREQUENCY == 2 && ($CurrentDate >= $FIXD_CHRG_BEGIN_DATE && $CurrentDate <= $FIXD_CHRG_END_DATE)){
                           
                            $fixedChargesAMOUNT = $fixedCharges['FIXD_CHRG_AMT'] * $fixedCharges['FIXD_CHRG_QTY'];
                            $fixedChargesVAT = $fixedChargesAMOUNT * $VAT;
                            $fixedChargesTotal += $fixedChargesAMOUNT; 
                            $fixedChargesVATTotal += $fixedChargesVAT;

                            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5 ">
                            <td class="text-center">'.$sCurrentDate.'</td>
                            <td class="text-center">'.$fixedCharges['TR_CD_DESC'].' </td>
                            <td class="text-center"></td>
                            <td width="10%;" class="text-left">'.round($fixedChargesAMOUNT,2).' </td>
                            <td width="10%;" class="text-left">0.00</td>
                            </tr>';

                            $DEFAULT_ROWS++;
                            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
                            }   

                            $TABLE_CONTENTS.= '
                            <tr class="mt-5 mb-5">
                                <td class="text-center">'.$sCurrentDate.'</td>
                                <td class="text-center">VAT 5%</td>
                                <td class="text-center"></td>
                                
                                <td width="10%;" class="text-left">'.round($fixedChargesVAT,2).' </td>
                                <td width="10%;" class="text-left">0.00</td>
                            </tr>';
                            $DEFAULT_ROWS++;

                            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                        
                                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK;   
                            } 
                        } 
                        
                        
                        else if($FIXD_CHRG_FREQUENCY == 3 && ($CurrentDate >= $FIXD_CHRG_BEGIN_DATE && $CurrentDate <= $FIXD_CHRG_END_DATE) && ($sCurrentDay == $fixedCharges['FIXD_CHRG_WEEKLY'])){
                            $fixedChargesAMOUNT = $fixedCharges['FIXD_CHRG_AMT'] * $fixedCharges['FIXD_CHRG_QTY'];
                            $fixedChargesVAT = $fixedChargesAMOUNT * $VAT;
                            $fixedChargesTotal += $fixedChargesAMOUNT; 
                            $fixedChargesVATTotal += $fixedChargesVAT;

                            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5 ">
                            <td class="text-center">'.$sCurrentDate.'</td>
                            <td class="text-center">'.$fixedCharges['TR_CD_DESC'].' </td>
                            <td class="text-center"></td>
                            <td width="10%;" class="text-left">'.round($fixedChargesAMOUNT,2).' </td>
                            <td width="10%;" class="text-left">0.00</td>
                            </tr>';

                            $DEFAULT_ROWS++;
                            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
                            }   

                            $TABLE_CONTENTS.= '
                            <tr class="mt-5 mb-5">
                                <td class="text-center">'.$sCurrentDate.'</td>
                                <td class="text-center">VAT 5%</td>
                                <td class="text-center"></td>
                                
                                <td width="10%;" class="text-left">'.round($fixedChargesVAT,2).' </td>
                                <td width="10%;" class="text-left">0.00</td>
                            </tr>';
                            $DEFAULT_ROWS++;

                            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                        
                                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK;   
                            } 
                        }   

                        else if($FIXD_CHRG_FREQUENCY == 4 && ($CurrentDate >= $FIXD_CHRG_BEGIN_DATE && $CurrentDate <= $FIXD_CHRG_END_DATE) && ($sCurrentD == $fixedCharges['FIXD_CHRG_MONTHLY'])){
                            $fixedChargesAMOUNT = $fixedCharges['FIXD_CHRG_AMT'] * $fixedCharges['FIXD_CHRG_QTY'];
                            $fixedChargesVAT = $fixedChargesAMOUNT * $VAT;
                            $fixedChargesTotal += $fixedChargesAMOUNT; 
                            $fixedChargesVATTotal += $fixedChargesVAT;

                            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5 ">
                            <td class="text-center">'.$sCurrentDate.'</td>
                            <td class="text-center">'.$fixedCharges['TR_CD_DESC'].' </td>
                            <td class="text-center"></td>
                            <td width="10%;" class="text-left">'.round($fixedChargesAMOUNT,2).' </td>
                            <td width="10%;" class="text-left">0.00</td>
                            </tr>';

                            $DEFAULT_ROWS++;
                            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
                            }   

                            $TABLE_CONTENTS.= '
                            <tr class="mt-5 mb-5">
                                <td class="text-center">'.$sCurrentDate.'</td>
                                <td class="text-center">VAT 5%</td>
                                <td class="text-center"></td>
                                
                                <td width="10%;" class="text-left">'.round($fixedChargesVAT,2).' </td>
                                <td width="10%;" class="text-left">0.00</td>
                            </tr>';
                            $DEFAULT_ROWS++;

                            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                        
                                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK;   
                            } 
                        }  

                        // else if($FIXD_CHRG_FREQUENCY == 5 && ($CurrentDate >= $FIXD_CHRG_BEGIN_DATE && $CurrentDate <= $FIXD_CHRG_END_DATE) && ($sCurrentDate == $fixedCharges['FIXD_CHRG_YEARLY'])){
                        //     $fixedChargesAMOUNT = $fixedCharges['FIXD_CHRG_AMT'] * $fixedCharges['FIXD_CHRG_QTY'];
                        //     $fixedChargesVAT = $fixedChargesAMOUNT * $VAT;
                        //     $fixedChargesTotal += $fixedChargesAMOUNT; 
                        //     $fixedChargesVATTotal += $fixedChargesVAT;

                        //     $TABLE_CONTENTS.= '<tr class="mt-5 mb-5 ">
                        //     <td class="text-center">'.$sCurrentDate.'</td>
                        //     <td class="text-center">'.$fixedCharges['TR_CD_DESC'].' </td>
                        //     <td class="text-center"></td>
                        //     <td width="10%;" class="text-left">'.round($fixedChargesAMOUNT,2).' </td>
                        //     <td width="10%;" class="text-left">0.00</td>
                        //     </tr>';

                        //     $DEFAULT_ROWS++;
                        //     if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                        //         $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
                        //     }   

                        //     $TABLE_CONTENTS.= '
                        //     <tr class="mt-5 mb-5">
                        //         <td class="text-center">'.$sCurrentDate.'</td>
                        //         <td class="text-center">VAT 5%</td>
                        //         <td class="text-center"></td>
                                
                        //         <td width="10%;" class="text-left">'.round($fixedChargesVAT,2).' </td>
                        //         <td width="10%;" class="text-left">0.00</td>
                        //     </tr>';
                        //     $DEFAULT_ROWS++;

                        //     if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                        
                        //         $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK;   
                        //     } 
                        // }  

                        else if($FIXD_CHRG_FREQUENCY == 6 && ($CurrentDate >= $FIXD_CHRG_BEGIN_DATE && $CurrentDate <= $FIXD_CHRG_END_DATE) && (date('d-m',$sCurrentDate) == date('d-m',$fixedCharges['FIXD_CHRG_YEARLY']))){
                            $fixedChargesAMOUNT = $fixedCharges['FIXD_CHRG_AMT'] * $fixedCharges['FIXD_CHRG_QTY'];
                            $fixedChargesVAT = $fixedChargesAMOUNT * $VAT;
                            $fixedChargesTotal += $fixedChargesAMOUNT; 
                            $fixedChargesVATTotal += $fixedChargesVAT;

                            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5 ">
                            <td class="text-center">'.$sCurrentDate.'</td>
                            <td class="text-center">'.$fixedCharges['TR_CD_DESC'].' </td>
                            <td class="text-center"></td>
                            <td width="10%;" class="text-left">'.round($fixedChargesAMOUNT,2).' </td>
                            <td width="10%;" class="text-left">0.00</td>
                            </tr>';

                            $DEFAULT_ROWS++;
                            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
                            }   

                            $TABLE_CONTENTS.= '
                            <tr class="mt-5 mb-5">
                                <td class="text-center">'.$sCurrentDate.'</td>
                                <td class="text-center">VAT 5%</td>
                                <td class="text-center"></td>
                                
                                <td width="10%;" class="text-left">'.round($fixedChargesVAT,2).' </td>
                                <td width="10%;" class="text-left">0.00</td>
                            </tr>';
                            $DEFAULT_ROWS++;

                            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                        
                                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK;   
                            } 
                        }  


                    }
                   //exit;
                }
                     
            }


            $TOTAL = $ROOM_CHARGE_TOTAL + $fixedChargesTotal;
            $TOTALVAT = $VAT_TOTAL + $fixedChargesVATTotal;

            ////////////// Footer //////////////////

            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
            <td colspan="5"><hr></td>
            </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            }   

            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
            <td class="text-center"></td>
            <td></td>
            <td>Total</td>
            <td width="10%;" class="text-center">'.round($TOTAL,2).' </td>
            <td width="10%;" class="text-center">0.00</td>
            </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            } 
            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
            <td colspan="5"><hr></td>
            </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            }   

            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
            <td class="text-center"></td>
            <td></td>
            <td>Balance</td>
            <td class="text-center">'.round($TOTAL,2).' </td>
            <td class="text-center">0.00</td>
            </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            }  
            
            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
            <td class="text-center"></td>
            <td></td>
            <td>VAT Incl. Amount</td>
            <td class="text-center">'.round($TOTAL,2).' </td>
            <td class="text-center">0.00</td>
            </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            }  
            
            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
            <td class="text-center"></td>
            <td></td>
            <td>5 % VAT</td>
            <td class="text-center">'.round($TOTALVAT,2).' </td>
            <td class="text-center">0.00</td>
            </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            }  

            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
            <td colspan="5"><hr></td>
            </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            }   
            
            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
            <td class="text-center"></td>
            <td></td>
            <td class="pt-20">Guest Signature</td>
            <td class="text-center"></td>
            </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            }   

            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
            <td colspan="5"></td>
            </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            }   
            $TABLE_CONTENTS.= '<tr class="mt-20 mb-5" >
            <td class="text-center"></td>
            <td></td>
            <td class="mt-20 mb-5 pt-20">Guest Email : '.$CUST_EMAIL.'</td>
            <td class="text-center"></td>
            </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            } 
            $TABLE_CONTENTS.= '<div class="mt-5 mb-5 pl-20" ><p>'.$_SESSION['FOLIO_TXT_ONE'].'
           </p></div>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            } 

            $TABLE_CONTENTS.= '<div class="mt-5 mb-5 pl-20"><p>'.$_SESSION['FOLIO_TXT_TWO'].'</p></div>';
             $DEFAULT_ROWS++;
             if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                 $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
             } 
            
            


          
            $data['CHARGES'] = $TABLE_CONTENTS; 
            $dompdf->loadHtml(view('Master/preview_proforma',$data));
            $dompdf->render();           
            $canvas = $dompdf->getCanvas();
            $canvas->page_text(18, 780, "{PAGE_NUM} / {PAGE_COUNT}", '', 6, array(0,0,0));
            $dompdf->stream("RESERVATON_".$_SESSION['PROFORMA_RESV_ID'].".pdf", array("Attachment" => 0));
           
        }

        public function pdfProFormaFolio(){          
                    
            $dompdf = new \Dompdf\Dompdf(); 
            $options = new \Dompdf\Options();
            $options->setIsRemoteEnabled(true);
            $options->setDefaultFont('Courier');
            $dompdf = new \Dompdf\Dompdf($options);

            $sql = "SELECT CONCAT_WS(' ', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME) AS FULLNAME, CUST_EMAIL, cname, RESV_ARRIVAL_DT, RESV_DEPARTURE, RESV_NO, RESV_RATE FROM FLXY_RESERVATION LEFT JOIN FLXY_CUSTOMER ON RESV_NAME = CUST_ID LEFT JOIN COUNTRY ON iso2 = CUST_COUNTRY WHERE RESV_ID = ".$_SESSION['PROFORMA_RESV_ID'];  
            $response = $this->Db->query($sql)->getResultArray();

            if(!empty($response)){
                foreach ($response as $row) {
                    $data = ['CUST_NAME'=> $row['FULLNAME'], 'CUST_COUNTRY'=> $row['cname'], 'RESV_ARRIVAL_DT'=> $row['RESV_ARRIVAL_DT'], 'RESV_DEPARTURE'=> $row['RESV_DEPARTURE'], 'RESV_NO'=> $row['RESV_NO'], 'RESV_RATE'=> $row['RESV_RATE']];
                    $RESV_ARRIVAL_DT = $row['RESV_ARRIVAL_DT'];
                    $RESV_DEPARTURE = $row['RESV_DEPARTURE'];
                    $RESV_RATE = $row['RESV_RATE'];
                    $CUST_EMAIL = $row['CUST_EMAIL'];
                } 
            }

            $sql = "SELECT * FROM FLXY_FIXED_CHARGES INNER JOIN FLXY_TRANSACTION_CODE ON TR_CD_ID = FIXD_CHRG_TRNCODE WHERE FIXD_CHRG_RESV_ID = ".$_SESSION['PROFORMA_RESV_ID'];  
            $fixedChargesResponse = $this->Db->query($sql)->getResultArray();

            $RESV_ARRIVAL_DATE    = strtotime($RESV_ARRIVAL_DT);
            $RESV_DEPARTURE_DATE  = strtotime($RESV_DEPARTURE); 
            $datediff = $RESV_DEPARTURE_DATE - $RESV_ARRIVAL_DATE;
            $RESERV_DAYS = round($datediff / (60 * 60 * 24));
            $VAT = 0.05;
            $TABLE_CONTENTS = '';
            $FIXED_CONTENTS = '';             
            $fixed_rows = 0;
            $DEFAULT_MODE = 20;
            $DEFAULT_ROWS = 0;
            $ROOM_CHARGE_TOTAL = 0;
            $VAT_TOTAL = 0;
            $j = 0;
            $fixedChargesAMOUNT = $fixedChargesVAT = $fixedChargesTotal = $fixedChargesVATTotal = $TOTAL = $TOTALVAT = 0;
            
            for($i = 1; $i <= $RESERV_DAYS; $i++ ){
                $ROOM_CHARGE_TOTAL += $RESV_RATE;
                $VAT_TOTAL += ($RESV_RATE * $VAT);
                $DEFAULT_VAT = $RESV_RATE * $VAT;
                $DEFAULT_PAGE_BREAK = '<tr></tr><div style="margin-top:370px;margin-bottom:5px; page-break-after:always"></div></tr>';
                $sCurrentDate = gmdate("d-m-Y", strtotime("+$i day", $RESV_ARRIVAL_DATE)); 
                $CurrentDate = strtotime($sCurrentDate); 
                $sCurrentDay = gmdate("w", strtotime("+{$i} day", $RESV_ARRIVAL_DATE));
                $sCurrentD = gmdate("d", strtotime("+{$i} day", $RESV_ARRIVAL_DATE)); 
                $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
                        <td class="text-center" >'.$sCurrentDate.'</td>
                        <td class="text-center">Room Charge </td>
                        <td class="text-center"></td>
                        <td class="text-left" >'.round($RESV_RATE,2).' </td>
                        <td class="text-left">0.00</td>
                    </tr>';
                $DEFAULT_ROWS++;
                if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                    $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
                }   

                $TABLE_CONTENTS.= '
                <tr class="mt-5 mb-5">
                    <td class="text-center">'.$sCurrentDate.'</td>
                    <td class="text-center">VAT 5%</td>
                    <td class="text-center"></td>
                    <td class="text-left">'.round($DEFAULT_VAT).' </td>
                    <td class="text-left">0.00</td>
                </tr>';
                $DEFAULT_ROWS++;

                if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                        
                    $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK;   
                } 

                ////////// Fixed Charges //////////////               
                if(!empty($fixedChargesResponse)){
                    foreach($fixedChargesResponse as $fixedCharges) {
                         
                        $FIXD_CHRG_BEGIN_DATE =gmdate("d-m-Y", strtotime("+1 day", strtotime($fixedCharges['FIXD_CHRG_BEGIN_DATE'])) );
                        $FIXD_CHRG_END_DATE = gmdate("d-m-Y", strtotime("+1 day", strtotime($fixedCharges['FIXD_CHRG_END_DATE'])));
                        $FIXD_CHRG_FREQUENCY = $fixedCharges['FIXD_CHRG_FREQUENCY'];


                        $FIXD_CHRG_BEGIN_DATE = strtotime($FIXD_CHRG_BEGIN_DATE);
                        $FIXD_CHRG_END_DATE = strtotime($FIXD_CHRG_END_DATE);

                        if($FIXD_CHRG_FREQUENCY == 1 && $CurrentDate == $FIXD_CHRG_BEGIN_DATE){
                            $ONCE = 1;
                            $fixedChargesAMOUNT = $fixedCharges['FIXD_CHRG_AMT'] * $fixedCharges['FIXD_CHRG_QTY'];
                            $fixedChargesVAT = $fixedChargesAMOUNT * $VAT;
                            $fixedChargesTotal += $fixedChargesAMOUNT; 
                            $fixedChargesVATTotal += $fixedChargesVAT;

                            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5 ">
                            <td class="text-center">'.$sCurrentDate.'</td>
                            <td class="text-center">'.$fixedCharges['TR_CD_DESC'].' </td>
                            <td class="text-center"></td>
                            <td width="10%;" class="text-left">'.round($fixedChargesAMOUNT,2).' </td>
                            <td width="10%;" class="text-left">0.00</td>
                            </tr>';

                            $DEFAULT_ROWS++;
                            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
                            }   

                            $TABLE_CONTENTS.= '
                            <tr class="mt-5 mb-5">
                                <td class="text-center">'.$sCurrentDate.'</td>
                                <td class="text-center">VAT 5%</td>
                                <td class="text-center"></td>
                                
                                <td width="10%;" class="text-left">'.round($fixedChargesVAT,2).' </td>
                                <td width="10%;" class="text-left">0.00</td>
                            </tr>';
                            $DEFAULT_ROWS++;

                            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                        
                                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK;   
                            } 
                        }

                       
                         if($FIXD_CHRG_FREQUENCY == 2 && ($CurrentDate >= $FIXD_CHRG_BEGIN_DATE && $CurrentDate <= $FIXD_CHRG_END_DATE)){
                           
                            $fixedChargesAMOUNT = $fixedCharges['FIXD_CHRG_AMT'] * $fixedCharges['FIXD_CHRG_QTY'];
                            $fixedChargesVAT = $fixedChargesAMOUNT * $VAT;
                            $fixedChargesTotal += $fixedChargesAMOUNT; 
                            $fixedChargesVATTotal += $fixedChargesVAT;

                            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5 ">
                            <td class="text-center">'.$sCurrentDate.'</td>
                            <td class="text-center">'.$fixedCharges['TR_CD_DESC'].' </td>
                            <td class="text-center"></td>
                            <td width="10%;" class="text-left">'.round($fixedChargesAMOUNT,2).' </td>
                            <td width="10%;" class="text-left">0.00</td>
                            </tr>';

                            $DEFAULT_ROWS++;
                            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
                            }   

                            $TABLE_CONTENTS.= '
                            <tr class="mt-5 mb-5">
                                <td class="text-center">'.$sCurrentDate.'</td>
                                <td class="text-center">VAT 5%</td>
                                <td class="text-center"></td>
                                
                                <td width="10%;" class="text-left">'.round($fixedChargesVAT,2).' </td>
                                <td width="10%;" class="text-left">0.00</td>
                            </tr>';
                            $DEFAULT_ROWS++;

                            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                        
                                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK;   
                            } 
                        } 
                        
                        
                        else if($FIXD_CHRG_FREQUENCY == 3 && ($CurrentDate >= $FIXD_CHRG_BEGIN_DATE && $CurrentDate <= $FIXD_CHRG_END_DATE) && ($sCurrentDay == $fixedCharges['FIXD_CHRG_WEEKLY'])){
                            $fixedChargesAMOUNT = $fixedCharges['FIXD_CHRG_AMT'] * $fixedCharges['FIXD_CHRG_QTY'];
                            $fixedChargesVAT = $fixedChargesAMOUNT * $VAT;
                            $fixedChargesTotal += $fixedChargesAMOUNT; 
                            $fixedChargesVATTotal += $fixedChargesVAT;

                            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5 ">
                            <td class="text-center">'.$sCurrentDate.'</td>
                            <td class="text-center">'.$fixedCharges['TR_CD_DESC'].' </td>
                            <td class="text-center"></td>
                            <td width="10%;" class="text-left">'.round($fixedChargesAMOUNT,2).' </td>
                            <td width="10%;" class="text-left">0.00</td>
                            </tr>';

                            $DEFAULT_ROWS++;
                            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
                            }   

                            $TABLE_CONTENTS.= '
                            <tr class="mt-5 mb-5">
                                <td class="text-center">'.$sCurrentDate.'</td>
                                <td class="text-center">VAT 5%</td>
                                <td class="text-center"></td>
                                
                                <td width="10%;" class="text-left">'.round($fixedChargesVAT,2).' </td>
                                <td width="10%;" class="text-left">0.00</td>
                            </tr>';
                            $DEFAULT_ROWS++;

                            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                        
                                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK;   
                            } 
                        }   

                        else if($FIXD_CHRG_FREQUENCY == 4 && ($CurrentDate >= $FIXD_CHRG_BEGIN_DATE && $CurrentDate <= $FIXD_CHRG_END_DATE) && ($sCurrentD == $fixedCharges['FIXD_CHRG_MONTHLY'])){
                            $fixedChargesAMOUNT = $fixedCharges['FIXD_CHRG_AMT'] * $fixedCharges['FIXD_CHRG_QTY'];
                            $fixedChargesVAT = $fixedChargesAMOUNT * $VAT;
                            $fixedChargesTotal += $fixedChargesAMOUNT; 
                            $fixedChargesVATTotal += $fixedChargesVAT;

                            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5 ">
                            <td class="text-center">'.$sCurrentDate.'</td>
                            <td class="text-center">'.$fixedCharges['TR_CD_DESC'].' </td>
                            <td class="text-center"></td>
                            <td width="10%;" class="text-left">'.round($fixedChargesAMOUNT,2).' </td>
                            <td width="10%;" class="text-left">0.00</td>
                            </tr>';

                            $DEFAULT_ROWS++;
                            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
                            }   

                            $TABLE_CONTENTS.= '
                            <tr class="mt-5 mb-5">
                                <td class="text-center">'.$sCurrentDate.'</td>
                                <td class="text-center">VAT 5%</td>
                                <td class="text-center"></td>
                                
                                <td width="10%;" class="text-left">'.round($fixedChargesVAT,2).' </td>
                                <td width="10%;" class="text-left">0.00</td>
                            </tr>';
                            $DEFAULT_ROWS++;

                            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                        
                                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK;   
                            } 
                        }  

                        // else if($FIXD_CHRG_FREQUENCY == 5 && ($CurrentDate >= $FIXD_CHRG_BEGIN_DATE && $CurrentDate <= $FIXD_CHRG_END_DATE) && ($sCurrentDate == $fixedCharges['FIXD_CHRG_YEARLY'])){
                        //     $fixedChargesAMOUNT = $fixedCharges['FIXD_CHRG_AMT'] * $fixedCharges['FIXD_CHRG_QTY'];
                        //     $fixedChargesVAT = $fixedChargesAMOUNT * $VAT;
                        //     $fixedChargesTotal += $fixedChargesAMOUNT; 
                        //     $fixedChargesVATTotal += $fixedChargesVAT;

                        //     $TABLE_CONTENTS.= '<tr class="mt-5 mb-5 ">
                        //     <td class="text-center">'.$sCurrentDate.'</td>
                        //     <td class="text-center">'.$fixedCharges['TR_CD_DESC'].' </td>
                        //     <td class="text-center"></td>
                        //     <td width="10%;" class="text-left">'.round($fixedChargesAMOUNT,2).' </td>
                        //     <td width="10%;" class="text-left">0.00</td>
                        //     </tr>';

                        //     $DEFAULT_ROWS++;
                        //     if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                        //         $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
                        //     }   

                        //     $TABLE_CONTENTS.= '
                        //     <tr class="mt-5 mb-5">
                        //         <td class="text-center">'.$sCurrentDate.'</td>
                        //         <td class="text-center">VAT 5%</td>
                        //         <td class="text-center"></td>
                                
                        //         <td width="10%;" class="text-left">'.round($fixedChargesVAT,2).' </td>
                        //         <td width="10%;" class="text-left">0.00</td>
                        //     </tr>';
                        //     $DEFAULT_ROWS++;

                        //     if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                        
                        //         $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK;   
                        //     } 
                        // }  

                        else if($FIXD_CHRG_FREQUENCY == 6 && ($CurrentDate >= $FIXD_CHRG_BEGIN_DATE && $CurrentDate <= $FIXD_CHRG_END_DATE) && (date('d-m',$sCurrentDate) == date('d-m',$fixedCharges['FIXD_CHRG_YEARLY']))){
                            $fixedChargesAMOUNT = $fixedCharges['FIXD_CHRG_AMT'] * $fixedCharges['FIXD_CHRG_QTY'];
                            $fixedChargesVAT = $fixedChargesAMOUNT * $VAT;
                            $fixedChargesTotal += $fixedChargesAMOUNT; 
                            $fixedChargesVATTotal += $fixedChargesVAT;

                            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5 ">
                            <td class="text-center">'.$sCurrentDate.'</td>
                            <td class="text-center">'.$fixedCharges['TR_CD_DESC'].' </td>
                            <td class="text-center"></td>
                            <td width="10%;" class="text-left">'.round($fixedChargesAMOUNT,2).' </td>
                            <td width="10%;" class="text-left">0.00</td>
                            </tr>';

                            $DEFAULT_ROWS++;
                            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
                            }   

                            $TABLE_CONTENTS.= '
                            <tr class="mt-5 mb-5">
                                <td class="text-center">'.$sCurrentDate.'</td>
                                <td class="text-center">VAT 5%</td>
                                <td class="text-center"></td>
                                
                                <td width="10%;" class="text-left">'.round($fixedChargesVAT,2).' </td>
                                <td width="10%;" class="text-left">0.00</td>
                            </tr>';
                            $DEFAULT_ROWS++;

                            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                        
                                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK;   
                            } 
                        }  


                    }
                   //exit;
                }
                     
            }


            $TOTAL = $ROOM_CHARGE_TOTAL + $fixedChargesTotal;
            $TOTALVAT = $VAT_TOTAL + $fixedChargesVATTotal;

            ////////////// Footer //////////////////

            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
            <td colspan="5"><hr></td>
            </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            }   

            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
            <td class="text-center"></td>
            <td></td>
            <td>Total</td>
            <td width="10%;" class="text-center">'.round($TOTAL,2).' </td>
            <td width="10%;" class="text-center">0.00</td>
            </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            } 
            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
            <td colspan="5"><hr></td>
            </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            }   

            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
            <td class="text-center"></td>
            <td></td>
            <td>Balance</td>
            <td class="text-center">'.round($TOTAL,2).' </td>
            <td class="text-center">0.00</td>
            </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            }  
            
            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
            <td class="text-center"></td>
            <td></td>
            <td>VAT Incl. Amount</td>
            <td class="text-center">'.round($TOTAL,2).' </td>
            <td class="text-center">0.00</td>
            </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            }  
            
            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
            <td class="text-center"></td>
            <td></td>
            <td>5 % VAT</td>
            <td class="text-center">'.round($TOTALVAT,2).' </td>
            <td class="text-center">0.00</td>
            </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            }  

            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
            <td colspan="5"><hr></td>
            </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            }   
            
            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
            <td class="text-center"></td>
            <td></td>
            <td class="pt-20">Guest Signature</td>
            <td class="text-center"></td>
            </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            }   

            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
            <td colspan="5"></td>
            </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            }   
            $TABLE_CONTENTS.= '<tr class="mt-20 mb-5" >
            <td class="text-center"></td>
            <td></td>
            <td class="mt-20 mb-5 pt-20">Guest Email : '.$CUST_EMAIL.'</td>
            <td class="text-center"></td>
            </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            } 
            $TABLE_CONTENTS.= '<div class="mt-5 mb-5 pl-20" ><p>'.$_SESSION['FOLIO_TXT_ONE'].'
           </p></div>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            } 

            $TABLE_CONTENTS.= '<div class="mt-5 mb-5 pl-20"><p>'.$_SESSION['FOLIO_TXT_TWO'].'</p></div>';
             $DEFAULT_ROWS++;
             if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                 $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
             }   

            $data['CHARGES'] = $TABLE_CONTENTS; 
            $data['TITLE'] = "FlexiGuest_FOLIO_".$_SESSION['PROFORMA_RESV_ID']; 
            $dompdf->loadHtml(view('Master/preview_proforma',$data));
            $dompdf->render();           
            $canvas = $dompdf->getCanvas();
            $canvas->page_text(18, 780, "{PAGE_NUM} / {PAGE_COUNT}", '', 6, array(0,0,0));
            $dompdf->stream("RESERVATON_".$_SESSION['PROFORMA_RESV_ID'].".pdf", array("Attachment" => 1));     
        }    
    
    /**************      Products Functions      ***************/

    public function products()
    {    
        $productCategoryLists = $this->productCategoryList();

        $data = [
            'productCategoryLists' => $productCategoryLists
        ];

        $data['title'] = getMethodName();
        $data['session'] = $this->session;
        
        return view('Master/ProductsView', $data);
    }

    public function ProductsView()
    {
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_PRODUCTS LEFT JOIN FLXY_PRODUCT_CATEGORIES ON PR_CATEGORY_ID = PC_ID';
        $columns = 'PR_ID,PR_NAME,PR_CATEGORY_ID,PC_CATEGORY,PR_IMAGE,PR_PRICE,PR_QUANTITY,PR_ESCALATED_HOURS,PR_ESCALATED_MINS';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }    

    public function insertProduct()
    {
        $user_id = session()->get('USR_ID');

        try {
            $sysid = $this->request->getPost('PR_ID');

            $rules = [
                'PR_NAME' => ['label' => 'Name', 'rules' => 'required|is_unique[FLXY_PRODUCTS.PR_NAME,PR_ID,' . $sysid . ']'],
                'PR_CATEGORY_ID' => ['label' => 'Product Category', 'rules' => 'required'],
                'PR_PRICE' => ['label' => 'Price', 'rules' => 'required'],
                'PR_QUANTITY' => ['label' => 'Product Quantity', 'rules' => 'required'],
                'PR_ESCALATED_HOURS' => ['label' => 'Escalated Hours', 'rules' => 'required'],
                'PR_ESCALATED_MINS' => ['label' => 'Escalated Minutes', 'rules' => 'required'],
            ];

            if (empty($sysid) || null !== $this->request->getFile('PR_IMAGE'))
                $rules = array_merge($rules, [
                    'PR_IMAGE' => [
                        'label' => 'Product Image',
                        'rules' => ['uploaded[PR_IMAGE]', 'mime_in[PR_IMAGE,image/png,image/jpg,image/jpeg]', 'max_size[PR_IMAGE,2048]']
                    ],
                ]);

            $validate = $this->validate($rules);

            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }

            $data = [
                "PR_NAME" => trim($this->request->getPost('PR_NAME')),
                "PR_CATEGORY_ID" => trim($this->request->getPost('PR_CATEGORY_ID')),
                "PR_PRICE" => trim($this->request->getPost('PR_PRICE')),
                "PR_QUANTITY" => trim($this->request->getPost('PR_QUANTITY')),
                "PR_ESCALATED_HOURS" => trim($this->request->getPost('PR_ESCALATED_HOURS')),
                "PR_ESCALATED_MINS" => trim($this->request->getPost('PR_ESCALATED_MINS')),
                "PR_CREATED_AT" => date('Y-m-d H:i:s'),
                "PR_UPDATED_AT" => date('Y-m-d H:i:s'),
                "PR_CREATED_BY" => $user_id,
                "PR_UPDATED_BY" => $user_id
                ];

                if(!empty($sysid)){
                    unset($data["PR_CREATED_AT"]);
                    unset($data["PR_CREATED_BY"]);                    
                }
                
                if ($this->request->getFile('PR_IMAGE')) {
                    $image = $this->request->getFile('PR_IMAGE');
                    $image_name = $image->getRandomName();
                    $directory = "assets/Uploads/laundry_amenities_products/";
        
                    $response = documentUpload($image, $image_name, $user_id, $directory);
        
                    if ($response['SUCCESS'] != 200)
                        return $this->respond(responseJson("500", true, "Product Image not uploaded"));
        
                    $data['PR_IMAGE'] = $directory . $response['RESPONSE']['OUTPUT'];
                }    

                $return = !empty($sysid) ? $this->Db->table('FLXY_PRODUCTS')->where('PR_ID', $sysid)->update($data) : $this->Db->table('FLXY_PRODUCTS')->insert($data);
                $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
                echo json_encode($result);
            } catch (\Exception $e) {
                return $this->respond($e->errors());
        }
    }    

    public function editProduct()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];

        $sql = "SELECT *, PC_CATEGORY FROM FLXY_PRODUCTS 
                LEFT JOIN FLXY_PRODUCT_CATEGORIES ON PR_CATEGORY_ID = PC_ID 
                WHERE PR_ID=:SYSID:";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteProduct()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            $return = $this->Db->table('FLXY_PRODUCTS')->delete(['PR_ID' => $sysid]);
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }
    
    
    public function productCategoryList()
    {
        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

        $sql = "SELECT *
                FROM FLXY_PRODUCT_CATEGORIES";

        if ($search != '') {
            $sql .= " WHERE PC_CATEGORY LIKE '%$search%'";
        }

        $response = $this->Db->query($sql)->getResultArray();

        $option = '<option value="">Choose an Option</option>';
        foreach ($response as $row) {
            $option .= '<option value="' . $row['PC_ID'] . '">' . $row['PC_CATEGORY'] . '</option>';
        }

        return $option;
    }

}