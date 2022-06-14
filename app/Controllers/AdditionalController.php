<?php

namespace App\Controllers;

use App\Libraries\ServerSideDataTable;

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
        helper(['form', 'url', 'custom', 'common']);
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
            } catch (Exception $e) {
                return $this->respond($e->errors());
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
            } catch (Exception $e) {
                return $this->respond($e->errors());
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

            //echo json_encode(print_r($_POST));
            //exit;

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
            } catch (Exception $e) {
                return $this->respond($e->errors());
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
            } catch (Exception $e) {
                return $this->respond($e->errors());
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
                    "ITM_STATUS" => trim($this->request->getPost('ITM_STATUS'))
                ];

                $return = !empty($sysid) ? $this->Db->table('FLXY_ITEM')->where('ITM_ID', $sysid)->update($data) : $this->Db->table('FLXY_ITEM')->insert($data);
                $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
                echo json_encode($result);
            } catch (Exception $e) {
                return $this->respond($e->errors());
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
            } catch (Exception $e) {
                return $this->respond($e->errors());
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
                    "IT_CL_ID" => trim($this->request->getPost('IT_CL_ID')),
                    "ITM_ID" => trim($this->request->getPost('ITM_ID')),
                    "ITM_DLY_BEGIN_DATE" => trim($this->request->getPost('ITM_DLY_BEGIN_DATE')),
                    "ITM_DLY_END_DATE" => trim($this->request->getPost('ITM_DLY_END_DATE')),
                    "ITM_DLY_QTY" => trim($this->request->getPost('ITM_DLY_QTY')),
                    "ITM_DLY_SUN" => trim($this->request->getPost('ITM_DLY_SUN')),
                    "ITM_DLY_MON" => trim($this->request->getPost('ITM_DLY_MON')),
                    "ITM_DLY_TUE" => trim($this->request->getPost('ITM_DLY_TUE')),
                    "ITM_DLY_WED" => trim($this->request->getPost('ITM_DLY_WED')),
                    "ITM_DLY_THU" => trim($this->request->getPost('ITM_DLY_THU')),
                    "ITM_DLY_FRI" => trim($this->request->getPost('ITM_DLY_FRI')),
                    "ITM_DLY_SAT" => trim($this->request->getPost('ITM_DLY_SAT')),
                    "ITM_DLY_STATUS" => trim($this->request->getPost('ITM_DLY_STATUS'))
                ];

                $return = !empty($sysid) ? $this->Db->table('FLXY_DAILY_INVENTORY')->where('ITM_DLY_ID', $sysid)->update($data) : $this->Db->table('FLXY_DAILY_INVENTORY')->insert($data);
                $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
                echo json_encode($result);
            } catch (Exception $e) {
                return $this->respond($e->errors());
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
            } catch (Exception $e) {
                return $this->respond($e->errors());
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
        $data['js_to_load'] = "app-invoice-print.js";
        $data['css_to_load'] = "app-invoice-preview.js";
        return view('Reservation/RegisterCard');

    }
    public function registerCardPreview(){
        return view('Reservation/RegisterCardPreview');

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





  


    
}