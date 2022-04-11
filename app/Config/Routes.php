<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

$modules_path = ROOTPATH;
$modules      = scandir($modules_path);
// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
$request = \Config\Services::request();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
// $routes->setDefaultController('Home');
// $routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

$whichRoute = $request->uri->getSegment(1);
if($whichRoute!='api'){
    if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
        require ROOTPATH . 'app/Config/RouteWeb.php';
    }
}else{
    if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
        require ROOTPATH . 'app/Config/RouteApi.php';
    }
}
/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.


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