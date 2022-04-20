<?php
/*
 * --------------------------------------------------------------------
 * Api Router Setup begin
 * --------------------------------------------------------------------
 */
//-------------------------------------  ALEESHA CODES ------------------------------------ //


//-----------  FLEXI GUEST API ROUTES -----------------//
// ---------------------------------------------------------------LOGIN/REGISTARTION -------------------------------------------------------------------------//
$routes->group("api", function ($routes) {
    
    $routes->post("register", "APIController::registerAPI");
    $routes->post("login", "APIController::loginAPI");

});
$routes->group("api", ["filter" => "authapi"], function ($routes) {

    $routes->get("profile", "APIController::profileAPI"); // user profile 
//----------------------------------------------------------------------------- CHECK-IN --------------------------------------------------------------------//
    // API to list ALL reservations of the loggined user
    $routes->get("checkin/listReservations", "APIController::listReservationsAPI"); 
    // API to get the reservation details from reservation number
    $routes->get("checkin/listReservations/(:segment)", "APIController::listReservationsAPI/$1"); 
    // API to upload the  documnets for checkin 
    $routes->post("checkin/docUplaod", "APIController::passportUploadAPI"); 
    // API to fetch guest profile including the guest accomonaying persons
    $routes->get("checkin/guestProfile", "APIController::getGuestAccompanyProfiles"); 
    // API to update the guest details from the doc uploaded.
    $routes->post("checkin/saveDoc", "APIController::saveDocDetails"); 
    // API to Delete doc uploaded.
    $routes->post("checkin/deleteDoc", "APIController::deleteUploadedDOC"); 
    // API to Add the details of the vaccine details 
    $routes->post("checkin/vaccineForm", "APIController::vaccineForm"); 
    // API to upload the signature and accept terms and conditions.
    $routes->post("checkin/signatureUpload", "APIController::acceptAndSignatureUpload"); 
    // API to send mail to accompany person to uplaod the docs self
    $routes->post("checkin/requestSelfUpload", "APIController::requestSelfUpload"); 
    
//----------------------------------------------------------------------------- Maintenance Request --------------------------------------------------------------------//
    // API to create Maintenance request
    $routes->post("maintenance/addRequest", "APIController::createRequest"); 
    // API to get details of single request
    $routes->get("maintenance/listRequests/(:segment)", "APIController::listRequests/$1");
    // API to fetch all requests
    $routes->get("maintenance/listRequests", "APIController::listRequests"); 
// ---------------------------------------------------------------------------- Feedback --------------------------------------------------------------------------------//
    // API to fetch all requests
    $routes->post("addFeedback", "APIController::addFeedBack"); 
// ---------------------------------------------------------------------------- Shuttle --------------------------------------------------------------------------------//
    // API to fetch all shuttles
    $routes->get("shuttles/list", "APIController::listShuttles"); 
    // API to fetch  shuttles details by id
    $routes->get("shuttles/list/(:segment)", "APIController::listShuttles/$1"); 
    
});





// ---------- FLEXI GUEST API ROUTES -----------------//

// --------------------------------------------------------- FLEXI GUEST ADMIN API ROUTES -----------------------------------------------//
// API to get details of single request
$routes->get("maintenance/listRequests/(:segment)", "APIController::listRequests/$1");
// API to fetch all requests
$routes->get("maintenance/listRequests", "APIController::listRequests"); 






//  ------------------------------------ALEESHA CODES --------------------------------------- //