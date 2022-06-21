<?php
/*
 * --------------------------------------------------------------------
 * Api Router Setup begin
 * --------------------------------------------------------------------
 */
//-------------------------------------  ALEESHA CODES STARTS------------------------------------ //


//-----------  FLEXI GUEST API ROUTES -----------------//
// ---------------------------------------------------------------LOGIN/REGISTARTION -------------------------------------------------------------------------//
$routes->group("api", function ($routes) {
    
    $routes->post("register", "APIController::registerAPI");
    $routes->post("login", "APIController::loginAPI");

    $routes->get('lookup-api', 'APIController::lookupApi');

});
$routes->group("api", ["filter" => "authapi:GUEST"], function ($routes) {

    $routes->get("profile", "APIController::profileAPI"); // user profile 
//----------------------------------------------------------------------------- CHECK-IN --------------------------------------------------------------------//
    // API to list ALL reservations of the loggined user
    $routes->get("checkin/listReservations", "APIController::listReservationsAPI"); 
    // API to get the reservation details from reservation number
    $routes->get("checkin/listReservations/(:segment)", "APIController::listReservationsAPI/$1"); 
    // API to upload the  documnets proof for checkin 
    $routes->get("checkin/checkPrevDocs", "APIController::checkDocDetails"); 
    // API to upload the  documnets proof for checkin 
    $routes->post("checkin/docUplaod", "APIController::docUploadAPI"); 
    // API to upload the  Vaccine for checkin 
    $routes->post("checkin/vaccine-upload", "APIController::vaccineUpload"); 
    
    $routes->get('checkin/fetch-vaccine-details', 'APIController::fetchVaccineDetails');
    $routes->delete('checkin/delete-vaccine', 'APIController::deleteVaccine');
    
    // API to fetch guest profile including the guest accomonaying persons
    $routes->post("checkin/guestProfile", "APIController::getGuestAccompanyProfiles"); 
    // API to update the guest details from the doc uploaded.
    $routes->post("checkin/saveDoc", "APIController::saveDocDetails"); 
    // API to update the guest details from the doc uploaded.
    $routes->get("checkin/getUserDetails", "APIController::FetchSavedDocDetails"); 
    // API to Delete doc uploaded.
    $routes->delete("checkin/deleteDoc", "APIController::deleteUploadedDOC"); 
    // API to Add the details of the vaccine details 
    $routes->post("checkin/vaccineForm", "APIController::vaccineForm"); 
    // API to upload the signature and accept terms and conditions.
    $routes->post("checkin/signatureUpload", "APIController::acceptAndSignatureUpload"); 
    // API to send mail to accompany person to uplaod the docs self
    $routes->post("checkin/requestSelfUpload", "APIController::requestSelfUpload"); 
    
// ---------------------------------------------------------------------------- Feedback --------------------------------------------------------------------------------//
    // API to fetch all requests
    $routes->post("addFeedback", "APIController::addFeedBack"); 
// API to fetch Handbook
    $routes->get("handbook", "APIController::getHandBookURL"); 
// ---------------------------------------------------------------------------- Shuttle --------------------------------------------------------------------------------//
    // API to fetch all shuttles
    $routes->get("shuttles/list", "APIController::listShuttles");
    // API to fetch  shuttles details by id
    $routes->get("shuttles/list/(:segment)", "APIController::listShuttles/$1");
    
});

//  ----------------------------------- ABUBAKAR CODE (START) --------------------------------------- //

$routes->group("api", ["filter" => "authapi:admin_guest", 'namespace' => 'App\Controllers\APIControllers\Guest'], function ($routes) {
    
    //----------------------------------------------------------------------------- Maintenance Request --------------------------------------------------------------------//
    // API to create Maintenance request
    $routes->post("maintenance/addRequest", "APIController::createRequest"); 
    // API to get details of single request
    $routes->get("maintenance/listRequests/(:segment)", "APIController::listRequests/$1");
    // API to fetch all requests
    $routes->get("maintenance/listRequests", "APIController::listRequests"); 
    // API to get category list of maintenance
    $routes->get('maintenance/getCategory', 'APIController::maintenanceCategoryList');  
     // API to get Subcategory list of maintenance by categoryID
    $routes->post('maintenance/getSubCategory', 'APIController::maintenanceSubCatByCategoryID'); 

    $routes->get('maintenance/get-maintenance-room-list', 'APIController::getMaintenanceRoomList');
});

$routes->group("api", ["filter" => "authapi:GUEST", 'namespace' => 'App\Controllers\APIControllers\Guest'], function ($routes) {

    $routes->get("concierge/concierge-offers", "ConciergeController::conciergeOffers");

    $routes->post("concierge/make-concierge-request", "ConciergeController::makeConciergeRequest");
});

//  ----------------------------------- ABUBAKAR CODE (END) --------------------------------------- //



// ---------- FLEXI GUEST API ROUTES -----------------//

// --------------------------------------------------------- FLEXI GUEST ADMIN API ROUTES -----------------------------------------------//
// API to get details of single request
$routes->get("maintenance/listRequests/(:segment)", "APIController::listRequests/$1");
// API to fetch all requests
$routes->get("maintenance/listRequests", "APIController::listRequests"); 

//  ------------------------------------ALEESHA CODES ENDS--------------------------------------- //


//  ----------------------------------- ABUBAKAR CODE (START) --------------------------------------- //

// ADMIN ROUTES (START)
$routes->group("api/admin", ["filter" => "authapi:admin", 'namespace' => 'App\Controllers\APIControllers\Admin'], function ($routes) {

    $routes->get("reservation/get-reservations-list", "ReservationController::getReservationsList");
    $routes->get("news", "NewsController::news");
    $routes->get("guideline", "GuidelineController::guideline");
    $routes->get("app-update", "AppUpdateController::appUpdate");

    $routes->get("maintenance/maintenance-list", "MaintenanceController::maintenanceList");
});

$routes->group("api/admin", ["filter" => "authapi:admin", 'namespace' => 'App\Controllers'], function($routes){  
    $routes->post("customer/update-customer-details", "APIController::saveDocDetails"); 
    $routes->get("profile", "APIController::profileAPI");

    $routes->post("checkin/guestProfile", "APIController::getGuestAccompanyProfiles"); 
});
// ADMIN ROUTES (END)


//  ----------------------------------- ABUBAKAR CODE (END) --------------------------------------- //