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

    $routes->group('maintenance', function($routes) {
        // API to create Maintenance request
        $routes->post("addRequest", "APIController::createRequest"); 
        // API to get details of single request
        $routes->get("listRequests/(:segment)", "APIController::listRequests/$1");
        // API to fetch all requests
        $routes->get("listRequests", "APIController::listRequests");
        $routes->get('get-maintenance-room-list', 'APIController::getMaintenanceRoomList');    
    });
});
//  ------------------------------------ALEESHA CODES ENDS--------------------------------------- //


//  ----------------------------------- ABUBAKAR CODE (START) --------------------------------------- //
$routes->group("api", ["filter" => "authapi:admin_guest", 'namespace' => 'App\Controllers'], function ($routes) {    

    $routes->group('maintenance', function($routes) {
        // API to get category list of maintenance
        $routes->get('getCategory', 'APIController::maintenanceCategoryList');  
        // API to get Subcategory list of maintenance by categoryID
        $routes->post('getSubCategory', 'APIController::maintenanceSubCatByCategoryID'); 
    });

    // API to upload the  documnets proof for checkin 
    $routes->post("checkin/docUplaod", "APIController::docUploadAPI"); 
    // API to update the guest details from the doc uploaded.
    $routes->post("checkin/saveDoc", "APIController::saveDocDetails");
    // API to upload the  documnets proof for checkin
    $routes->get("checkin/checkPrevDocs", "APIController::checkDocDetails");
    // API to update the guest details from the doc uploaded.
    $routes->get("checkin/getUserDetails", "APIController::FetchSavedDocDetails"); 
    // API to Delete doc uploaded.
    $routes->delete("checkin/deleteDoc", "APIController::deleteUploadedDOC");
    // API to fetch guest profile including the guest accomonaying persons
    $routes->post("checkin/guestProfile", "APIController::getGuestAccompanyProfiles");

    // API to Add the details of the vaccine details
    $routes->post("checkin/vaccineForm", "APIController::vaccineForm");
    $routes->post("checkin/vaccine-upload", "APIController::vaccineUpload");
    // API to upload the  Vaccine for checkin 
    $routes->get('checkin/fetch-vaccine-details', 'APIController::fetchVaccineDetails');
    $routes->delete('checkin/delete-vaccine', 'APIController::deleteVaccine');

    // API to upload the signature and accept terms and conditions.
    $routes->post("checkin/signatureUpload", "APIController::acceptAndSignatureUpload");
});

$routes->group("api", ["filter" => "authapi:GUEST", 'namespace' => 'App\Controllers\APIControllers\Guest'], function ($routes) {

    $routes->group('concierge', function($routes) {
        $routes->get("concierge-offers", "ConciergeController::conciergeOffers");
        $routes->post("make-concierge-request", "ConciergeController::makeConciergeRequest");
        $routes->get("list-concierge-requests", "ConciergeController::listConciergeRequests");
    });

    $routes->get("news", "NewsController::news");
    $routes->get("guideline", "GuidelineController::guideline");
    $routes->get("app-update", "AppUpdateController::appUpdate");

    $routes->group('reservation', function($routes) {
        $routes->get("make-checkout-request/(:segment)", "ReservationController::makeCheckoutRequest/$1");
    });

    $routes->group('laundry-amenities', function($routes) {
        $routes->get("all-categories", "ProductCategoryController::allCategories");
        $routes->get("all-products", "ProductController::allProducts");
        $routes->post("place-order", "LaundryAmenitiesController::placeOrder");
        $routes->get("list-orders", "LaundryAmenitiesController::listOrders");
    });

});

// ADMIN ROUTES (START)
$routes->group("api/admin", ["filter" => "authapi:admin", 'namespace' => 'App\Controllers\APIControllers\Admin'], function ($routes) {

    $routes->get("reservation/get-reservations-list", "ReservationController::getReservationsList");

    $routes->group('maintenance', function($routes) {
        $routes->get("maintenance-list", "MaintenanceController::maintenanceList");
        $routes->get("get-room-list", "MaintenanceController::getRoomList");
        $routes->get("reservation-of-room/(:segment)", "MaintenanceController::reservationOfRoom/$1");
        $routes->post('create-update-maintenance-request', 'MaintenanceController::createUpdateMaintenanceRequest');
    });

});

$routes->group("api/admin", ["filter" => "authapi:admin", 'namespace' => 'App\Controllers'], function($routes){  
    $routes->get("profile", "APIController::profileAPI");

    $routes->post('checkin/verify-documents', 'APIController::verifyDocuments');
});
// ADMIN ROUTES (END)

//  ----------------------------------- ABUBAKAR CODE (END) --------------------------------------- //

