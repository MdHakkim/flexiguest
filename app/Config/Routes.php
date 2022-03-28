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
$routes->get("/test", "ApplicatioController::test");
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
    $routes->match(['post'],'/getInitializeListReserv', 'ApplicatioController::getInitializeListReserv');
    $routes->match(['post'],'/roomList', 'ApplicatioController::roomList');
    $routes->match(['post'],'/getRateQueryData', 'ApplicatioController::getRateQueryData');

    $routes->get('/customer', 'ApplicatioController::Customer');
    $routes->match(['post'],'/customerView', 'ApplicatioController::customerView');
    $routes->match(['post'],'/editCustomer', 'ApplicatioController::editCustomer');
    $routes->match(['post'],'/deleteCustomer', 'ApplicatioController::deleteCustomer');
    $routes->match(['post'],'/getSupportingLov', 'ApplicatioController::getSupportingLov');
    $routes->match(['post'],'/customerList', 'ApplicatioController::customerList');
    $routes->match(['post'],'/getSupportingReservationLov', 'ApplicatioController::getSupportingReservationLov');
    $routes->match(['post'],'/insertCompAgent', 'ApplicatioController::insertCompAgent');
    $routes->match(['post'],'/getCustomerDetail', 'ApplicatioController::getCustomerDetail');

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
    $routes->match(['post'],'/getSupportingRoomLov', 'ApplicatioController::getSupportingRoomLov');
    $routes->match(['post'],'/roomTypeList', 'ApplicatioController::roomTypeList');

    $routes->get('/roomClass', 'ApplicatioController::roomClass');
    $routes->match(['post'],'/roomClassView', 'ApplicatioController::RoomClassView');
    $routes->match(['post'],'/insertRoomClass', 'ApplicatioController::insertRoomClass');
    $routes->match(['post'],'/editRoomClass', 'ApplicatioController::editRoomClass');
    $routes->match(['post'],'/deleteRoomClass', 'ApplicatioController::deleteRoomClass');
    $routes->match(['post'],'/roomClassList', 'ApplicatioController::roomClassList');
    $routes->match(['post'],'/getSupportingRoomClassLov', 'ApplicatioController::getSupportingRoomClassLov');
    $routes->match(['post'],'/featureList', 'ApplicatioController::featureList');
    $routes->match(['post'],'/houseKeepSecionList', 'ApplicatioController::houseKeepSecionList');

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

    $routes->get('/section', 'ApplicatioController::section');
    $routes->match(['post'],'/sectionView', 'ApplicatioController::SectionView');
    $routes->match(['post'],'/insertSection', 'ApplicatioController::insertSection');
    $routes->match(['post'],'/editSection', 'ApplicatioController::editSection');
    $routes->match(['post'],'/deleteSection', 'ApplicatioController::deleteSection');

    $routes->get('/source', 'ApplicatioController::source');
    $routes->match(['post'],'/sourceView', 'ApplicatioController::SourceView');
    $routes->match(['post'],'/insertSource', 'ApplicatioController::insertSource');
    $routes->match(['post'],'/editSource', 'ApplicatioController::editSource');
    $routes->match(['post'],'/deleteSource', 'ApplicatioController::deleteSource');
    $routes->match(['post'],'/initalConfigLovSource', 'ApplicatioController::initalConfigLovSource');

    $routes->get('/sourceGroup', 'ApplicatioController::sourceGroup');
    $routes->match(['post'],'/sourceGroupView', 'ApplicatioController::SourceGroupView');
    $routes->match(['post'],'/insertSourceGroup', 'ApplicatioController::insertSourceGroup');
    $routes->match(['post'],'/editSourceGroup', 'ApplicatioController::editSourceGroup');
    $routes->match(['post'],'/deleteSourceGroup', 'ApplicatioController::deleteSourceGroup');

    $routes->get('/reservationType', 'ApplicatioController::reservationType');
    $routes->match(['post'],'/reservationTypeView', 'ApplicatioController::ReservationTypeView');
    $routes->match(['post'],'/insertReservationType', 'ApplicatioController::insertReservationType');
    $routes->match(['post'],'/editReservationType', 'ApplicatioController::editReservationType');
    $routes->match(['post'],'/deleteReservationType', 'ApplicatioController::deleteReservationType');

    $routes->get('/purposeStay', 'ApplicatioController::purposeStay');
    $routes->match(['post'],'/purposeStayView', 'ApplicatioController::PurposeStayView');
    $routes->match(['post'],'/insertPurposeStay', 'ApplicatioController::insertPurposeStay');
    $routes->match(['post'],'/editPurposeStay', 'ApplicatioController::editPurposeStay');
    $routes->match(['post'],'/deletePurposeStay', 'ApplicatioController::deletePurposeStay');

    $routes->get('/payment', 'ApplicatioController::payment');
    $routes->match(['post'],'/paymentView', 'ApplicatioController::PaymentView');
    $routes->match(['post'],'/insertPayment', 'ApplicatioController::insertPayment');
    $routes->match(['post'],'/editPayment', 'ApplicatioController::editPayment');
    $routes->match(['post'],'/deletePayment', 'ApplicatioController::deletePayment');

    $routes->get('/overBooking', 'ApplicatioController::overBooking');
    $routes->match(['post'],'/overBookingView', 'ApplicatioController::OverBookingView');
    $routes->match(['post'],'/insertOverBooking', 'ApplicatioController::insertOverBooking');
    $routes->match(['post'],'/editOverBooking', 'ApplicatioController::editOverBooking');
    $routes->match(['post'],'/deleteOverBooking', 'ApplicatioController::deleteOverBooking');

    $routes->match(['post'],'/getSupportingOverbookingLov', 'ApplicatioController::getSupportingOverbookingLov');
    $routes->match(['post'],'/getRoomType', 'ApplicatioController::getRoomType');

    //Changes by Deleep Bose

    $routes->get('/rateClass', 'MastersController::rateClass');
    $routes->match(['post'],'/rateClassView', 'MastersController::RateClassView');
    $routes->match(['post'],'/insertRateClass', 'MastersController::insertRateClass');
    $routes->match(['post'],'/copyRateClass', 'MastersController::copyRateClass');
    $routes->match(['post'],'/editRateClass', 'MastersController::editRateClass');
    $routes->match(['post'],'/deleteRateClass', 'MastersController::deleteRateClass');  
    
    $routes->get('/getAssocRateCategories', 'MastersController::getAssocRateCategories');    

    $routes->get('/rateCategory', 'MastersController::rateCategory');
    $routes->match(['post'],'/rateCategoryView', 'MastersController::RateCategoryView');
    $routes->match(['post'],'/insertRateCategory', 'MastersController::insertRateCategory');
    $routes->match(['post'],'/copyRateCategory', 'MastersController::copyRateCategory');
    $routes->match(['post'],'/editRateCategory', 'MastersController::editRateCategory');
    $routes->match(['post'],'/deleteRateCategory', 'MastersController::deleteRateCategory');  
   
    $routes->get('/rateCode', 'MastersController::rateCode');
    $routes->get('/addRateCode', 'MastersController::addRateCode');
    $routes->get('/editRateCode', 'MastersController::editRateCode');
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