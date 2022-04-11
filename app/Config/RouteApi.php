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
    
//---------------------------------------CHECK-IN ----------------------------------------//

    // API to list ALL reservations of the user
    $routes->get("checkin/listReservations", "APIController::listReservationsAPI"); 
    $routes->post("checkin/docUplaod", "APIController::passportUploadAPI"); 


    
});





// ---------- FLEXI GUEST API ROUTES -----------------//






//  ------------------------------------ALEESHA CODES --------------------------------------- //