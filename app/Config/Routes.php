<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

$modules_path = ROOTPATH;
$modules      = scandir($modules_path);
// echo $modules;
// print_r($modules_path);
// exit;
// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.

// echo $ConfigRouteFiles =  $this->uri->segment(1);//uri_string();
// exit;
// if(){

// }
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}
// echo  uri_string();
// echo "route two exit";
// exit;
/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
// $routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// $routes->get('/', 'Home::index');

//...

$routes->match(['get', 'post'], 'login', 'UserController::login', ["filter" => "noauth"]);
// Admin routes
$routes->group("admin", ["filter" => "auth"], function ($routes) {
    $routes->get("/", "AdminController::index");
});
// Editor routes
$routes->group("editor", ["filter" => "auth"], function ($routes) {
    $routes->get("/", "EditorController::index");
});
$routes->get('logout', 'UserController::logout');

//...

$routes->get('/', 'DashboardController::index',["filter" => "auth"]);

//$routes->get('/', 'ApplicatioController::index',["filter" => "auth"]);
//$routes->get('/select', 'MainController::select');
//$routes->get('/insert', 'MainController::insert');

$routes->get('/reservation', 'ApplicatioController::Reservation',["filter" => "auth"]);

$routes->match(['get', 'post'],'/datatableView', 'ApplicatioController::datatableView');
$routes->match(['get', 'post'],'/insertReservation', 'ApplicatioController::insertReservation');
$routes->match(['post'],'/countryList', 'ApplicatioController::countryList');
$routes->match(['post'],'/stateList', 'ApplicatioController::stateList');
$routes->match(['post'],'/cityList', 'ApplicatioController::cityList');
$routes->match(['post'],'/insertCustomer', 'ApplicatioController::insertCustomer');

$routes->get('/customer', 'ApplicatioController::Customer');
$routes->match(['post'],'/customerView', 'ApplicatioController::customerView');
$routes->match(['post'],'/editCustomer', 'ApplicatioController::editCustomer');
$routes->match(['post'],'/deleteCustomer', 'ApplicatioController::deleteCustomer');
$routes->match(['post'],'/getSupportingLov', 'ApplicatioController::getSupportingLov');
$routes->match(['post'],'/customerList', 'ApplicatioController::customerList');
$routes->match(['post'],'/getSupportingReservationLov', 'ApplicatioController::getSupportingReservationLov');


$routes->get('/test', 'ApplicatioController::test');

// $routes->match(['get', 'post'], '/ajaxresponse', 'Home::ajaxresponse');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
