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
    $routes->get("profile", "APIController::profileAPI");
    
});





// ---------- FLEXI GUEST API ROUTES -----------------//






//  ------------------------------------ALEESHA CODES --------------------------------------- //