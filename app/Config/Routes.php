<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

$modules_path = ROOTPATH;
$modules      = scandir($modules_path);
// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.

// echo $ConfigRouteFiles =  $this->uri->segment(1);//uri_string();
// exit;
// if(){

// }
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}
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

$routes->match(['get', 'post'], 'login', 'UserController::login', ["filter" => "noauth"]);
// Admin routes
$routes->get('/', 'DashboardController::index',["filter" => "auth"]);
$routes->group("/", ["filter" => "auth"], function ($routes) {
    $routes->get('logout', 'UserController::logout');
    $routes->get("/editor", "EditorController::index");

    $routes->get('/reservation', 'ApplicatioController::Reservation');
    $routes->match(['post'],'/reservationView', 'ApplicatioController::reservationView');
    $routes->match(['post'],'/insertReservation', 'ApplicatioController::insertReservation');
    $routes->match(['post'],'/countryList', 'ApplicatioController::countryList');
    $routes->match(['post'],'/stateList', 'ApplicatioController::stateList');
    $routes->match(['post'],'/cityList', 'ApplicatioController::cityList');
    $routes->match(['post'],'/insertCustomer', 'ApplicatioController::insertCustomer');
    $routes->match(['post'],'/editReservation', 'ApplicatioController::editReservation');
    $routes->match(['post'],'/deleteReservation', 'ApplicatioController::deleteReservation');

    $routes->get('/customer', 'ApplicatioController::Customer');
    $routes->match(['post'],'/customerView', 'ApplicatioController::customerView');
    $routes->match(['post'],'/editCustomer', 'ApplicatioController::editCustomer');
    $routes->match(['post'],'/deleteCustomer', 'ApplicatioController::deleteCustomer');
    $routes->match(['post'],'/getSupportingLov', 'ApplicatioController::getSupportingLov');
    $routes->match(['post'],'/customerList', 'ApplicatioController::customerList');
    $routes->match(['post'],'/getSupportingReservationLov', 'ApplicatioController::getSupportingReservationLov');
    $routes->match(['post'],'/insertCompAgent', 'ApplicatioController::insertCompAgent');

    $routes->get('/company', 'ApplicatioController::company');
    $routes->match(['post'],'/companyView', 'ApplicatioController::companyView');
    $routes->match(['post'],'/insertCompany', 'ApplicatioController::insertCompany');
    $routes->match(['post'],'/editCompany', 'ApplicatioController::editCompany');
    $routes->match(['post'],'/deleteCompany', 'ApplicatioController::deleteCompany');

    $routes->get('/agent', 'ApplicatioController::agent');
    $routes->match(['post'],'/AgentView', 'ApplicatioController::AgentView');
    $routes->match(['post'],'/insertAgent', 'ApplicatioController::insertAgent');
    $routes->match(['post'],'/editAgent', 'ApplicatioController::editAgent');
    $routes->match(['post'],'/deleteAgent', 'ApplicatioController::deleteAgent');

    $routes->get('/group', 'ApplicatioController::group');
    $routes->match(['post'],'/GroupView', 'ApplicatioController::GroupView');
    $routes->match(['post'],'/insertGroup', 'ApplicatioController::insertGroup');
    $routes->match(['post'],'/editGroup', 'ApplicatioController::editGroup');
    $routes->match(['post'],'/deleteGroup', 'ApplicatioController::deleteGroup');

    $routes->match(['post'],'/getSupportingVipLov', 'ApplicatioController::getSupportingVipLov');
    $routes->match(['post'],'/companyList', 'ApplicatioController::companyList');
    $routes->match(['post'],'/agentList', 'ApplicatioController::agentList');
    $routes->match(['post'],'/groupList', 'ApplicatioController::groupList');
    $routes->match(['post'],'/getSupportingblkLov', 'ApplicatioController::getSupportingblkLov');

    $routes->get('/block', 'ApplicatioController::block');
    $routes->match(['post'],'/blockList', 'ApplicatioController::blockList');
    $routes->match(['post'],'/blockView', 'ApplicatioController::BlockView');
    $routes->match(['post'],'/insertBlock', 'ApplicatioController::insertBlock');
    $routes->match(['post'],'/editBlock', 'ApplicatioController::editBlock');
    $routes->match(['post'],'/deleteBlock', 'ApplicatioController::deleteBlock');

    $routes->get('/room', 'ApplicatioController::room');
    $routes->match(['post'],'/roomView', 'ApplicatioController::RoomView');
    $routes->match(['post'],'/insertRoom', 'ApplicatioController::insertRoom');
    $routes->match(['post'],'/editRoom', 'ApplicatioController::editRoom');
    $routes->match(['post'],'/deleteRoom', 'ApplicatioController::deleteRoom');

    $routes->get('/roomClass', 'ApplicatioController::roomClass');
    $routes->match(['post'],'/roomClassView', 'ApplicatioController::RoomClassView');
    $routes->match(['post'],'/insertRoomClass', 'ApplicatioController::insertRoomClass');
    $routes->match(['post'],'/editRoomClass', 'ApplicatioController::editRoomClass');
    $routes->match(['post'],'/deleteRoomClass', 'ApplicatioController::deleteRoomClass');


    $routes->get('/roomType', 'ApplicatioController::roomType');
    $routes->match(['post'],'/roomTypeView', 'ApplicatioController::RoomTypeView');
    $routes->match(['post'],'/insertRoomType', 'ApplicatioController::insertRoomType');
    $routes->match(['post'],'/editRoomType', 'ApplicatioController::editRoomType');
    $routes->match(['post'],'/deleteRoomType', 'ApplicatioController::deleteRoomType');

    $routes->get('/roomFloor', 'ApplicatioController::roomFloor');
    $routes->match(['post'],'/roomFloorView', 'ApplicatioController::RoomFloorView');
    $routes->match(['post'],'/insertRoomFloor', 'ApplicatioController::insertRoomFloor');
    $routes->match(['post'],'/editRoomFloor', 'ApplicatioController::editRoomFloor');
    $routes->match(['post'],'/deleteRoomFloor', 'ApplicatioController::deleteRoomFloor');

    $routes->get('/roomFeature', 'ApplicatioController::roomFeature');
    $routes->match(['post'],'/roomFeatureView', 'ApplicatioController::RoomFeatureView');
    $routes->match(['post'],'/insertRoomFeature', 'ApplicatioController::insertRoomFeature');
    $routes->match(['post'],'/editRoomFeature', 'ApplicatioController::editRoomFeature');
    $routes->match(['post'],'/deleteRoomFeature', 'ApplicatioController::deleteRoomFeature');

});
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
