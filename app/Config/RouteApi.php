<?php
/*
 * --------------------------------------------------------------------
 * Api Router Setup begin
 * --------------------------------------------------------------------
 */
//-------------------------------------  ALEESHA CODES ------------------------------------ //

// using raw sql

$routes->post('searchname', 'EmployeesController::searchBySQL');
$routes->post('employee',   'EmployeesController::createBySQL');
$routes->get('employee',    'EmployeesController::indexBySQL');
$routes->get('employee/(:segment)',    'EmployeesController::showBySQL/$1');
$routes->post('employee/(:segment)',    'EmployeesController::updateBySQL/$1');
$routes->delete('employee/(:segment)',   'EmployeesController::DeleteBySQL/$1');

// // using build in query functions
// $routes->post('searchname', 'employee::filterByName');
// $routes->post('employee',                'employee::create');
// $routes->get('employee',                 'employee::index');
// $routes->get('employee/(:segment)',      'employee::show/$1');
// $routes->post('employee/(:segment)',    'employee::update/$1');
// $routes->delete('employee/(:segment)',   'employee::delete/$1');

//-----------  FLEXI GUEST API ROUTES -----------------//

$routes->group("api", function ($routes) {
    
    $routes->post("register", "APIController::registerAPI");
    $routes->post("login", "APIController::loginAPI");
    $routes->get("profile", "APIController::profileAPI");
    
});





// ---------- FLEXI GUEST API ROUTES -----------------//




//  print_r($this->router->routes);exit;
// // using raw sql
// $routes->post('searchname', 'EmployeesController::searchBySQL');
// // $routes->post('employee',                'EmployeesController::createBySQL');
// $routes->get('employee',                 'EmployeesController::indexBySQL');
// //$routes->get('employee/(:segment)',      'employeesController::showBySQL/$1');
// //$routes->post('employee/(:segment)',    'employeesController::updateBySQL/$1');
// //$routes->delete('employee/(:segment)',   'employeesController::DeleteBySQL/$1');

//  ------------------------------------ALEESHA CODES --------------------------------------- //