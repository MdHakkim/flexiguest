<?php
/*
 * --------------------------------------------------------------------
 * Api Router Setup begin
 * --------------------------------------------------------------------
 */
//-------------------------------------  ALEESHA CODES ------------------------------------ //


//-----------  FLEXI GUEST API ROUTES -----------------//

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
    // API to update the guest details from the doc uploaded.
    $routes->post("checkin/saveDoc", "APIController::saveDocDetails"); 
    // API to Delete doc uploaded.
    $routes->post("checkin/deleteDoc", "APIController::deleteUploadedDOC"); 
    // API to Add the details of the vaccine details 
    $routes->post("checkin/vaccineForm", "APIController::vaccineForm"); 
    // API to upload the signature and accept terms and conditions.
    $routes->post("checkin/signatureUpload", "APIController::acceptAndSignatureUpload"); 

//----------------------------------------------------------------------------- CHECK-IN --------------------------------------------------------------------//
//----------------------------------------------------------------------------- Maintenance Request --------------------------------------------------------------------//
    // API to create Maintenance request
    $routes->post("maintenance/addRequest", "APIController::createRequest"); 
    // API to get details of single request
    $routes->get("maintenance/listRequests/(:segment)", "APIController::listRequests/$1");
    // API to fetch all requests
    $routes->get("maintenance/listRequests", "APIController::listRequests"); 

//----------------------------------------------------------------------------- Maintenance Request --------------------------------------------------------------------//


// ---------------------------------------------------------------------------- Feedback --------------------------------------------------------------------------------//
    // API to fetch all requests
    $routes->post("addFeedback", "APIController::addFeedBack"); 
// ---------------------------------------------------------------------------- Feedback --------------------------------------------------------------------------------//

    
});





// ---------- FLEXI GUEST API ROUTES -----------------//






//  ------------------------------------ALEESHA CODES --------------------------------------- //