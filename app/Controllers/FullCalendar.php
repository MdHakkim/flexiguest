<?php namespace App\Controllers;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
 
 
class FullCalendar extends Controller
{
 
    public function index() {
 
        $db      = \Config\Database::connect();
        $builder = $db->table('FLXY_CURRENCY');   
        $query = $builder->select('*')
                    ->limit(10)->get();
 
        $data = $query->getResult();
 
       foreach ($data as $key => $value) {
            $data['data'][$key]['title'] = $value->CUR_CODE;
            $data['data'][$key]['start'] = '05/13/2022';
            $data['data'][$key]['end'] = '05/20/2022';
            $data['data'][$key]['backgroundColor'] = "#00a65a";
        }        
      return view('Master/calendar',$data);
    }
 
}