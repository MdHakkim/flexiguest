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
    $routes->get("health", "APIController::health");

    $routes->post("forget-password", "UserController::forgetPassword");
});

$routes->group("api", ["filter" => "authapi"], function ($routes) {
    $routes->post("translate", "TranslationController::translate");
});

$routes->group("api", ["filter" => "authapi:Guest"], function ($routes) {
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

    $routes->group('maintenance', function ($routes) {
        // API to create Maintenance request
        $routes->post("addRequest", "APIController::createRequest");
        // API to get details of single request
        $routes->get("listRequests/(:segment)", "APIController::listRequests/$1");
        // API to fetch all requests
        $routes->get("listRequests", "APIController::listRequests");
    });

    $routes->post('create-payment-intent', 'PaymentController::createPaymentIntent');

    $routes->group('maintenance', function ($routes) {
        $routes->post("acknowledged", "MaintenanceController::acknowledged");
    });
});
//  ------------------------------------ALEESHA CODES ENDS--------------------------------------- //


//  ----------------------------------- ABUBAKAR CODE (START) --------------------------------------- //

/*****************************  Admin *****************************/
$routes->group("api/admin", ["filter" => "authapi:Admin", 'namespace' => 'App\Controllers'], function ($routes) {
    $routes->get("profile", "APIController::profileAPI");
    $routes->post('checkin/verify-documents', 'APIController::verifyDocuments');
    $routes->post('checkin/guest-checked-in', 'APIController::guestCheckedIn');

    $routes->group('evalet', function ($routes) {
        $routes->post('assign-driver', 'EValetController::assignDriver');
        $routes->post('ready-to-collect', 'EValetController::readyToCollect');
    });

    $routes->group('dashboard', function ($routes) {
        $routes->get('get-stats', 'AdminDashboardController::getStats');
    });
});

/*****************************  Admin + Guest *****************************/
$routes->group("api", ["filter" => "authapi:Admin,Guest", 'namespace' => 'App\Controllers'], function ($routes) {
    $routes->post("log-upload", "APIController::logUpload");

    $routes->group('maintenance', function ($routes) {
        // API to get category list of maintenance
        $routes->get('getCategory', 'APIController::maintenanceCategoryList');
        // API to get Subcategory list of maintenance by categoryID
        $routes->post('getSubCategory', 'APIController::maintenanceSubCatByCategoryID');
    });

    // API to upload the  documnets proof for checkin 
    $routes->post("checkin/docUpload", "APIController::docUploadAPI");
    // API to update the guest details from the doc uploaded.
    $routes->post("checkin/saveGuestDetails", "APIController::saveGuestDetails");
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

    $routes->get('apartment-list', 'APIController::apartmentList');

    $routes->group('evalet', function ($routes) {
        $routes->post('submit-form', 'EValetController::submitForm');
        $routes->get('valet-list', 'EValetController::valetList');
        $routes->post('car-delivery-request', 'EValetController::carDeliveryRequest');
    });

    $routes->get('lookup-api', 'APIController::lookupApi');
    $routes->get('get-state', 'APIController::getState');
    $routes->get('get-city', 'APIController::getCity');
});

/*****************************  Admin + Guest + Attendee *****************************/
$routes->group("api", ["filter" => "authapi:Admin,Guest,Attendee,Supervisor", 'namespace' => 'App\Controllers'], function ($routes) {
    $routes->post("logout", "APIController::logout");

    $routes->group('evalet', function ($routes) {
        $routes->post('guest-collected', 'EValetController::guestCollected');
    });

    $routes->group('restaurant', function ($routes) {
        $routes->get("order-list", "RestaurantController::orderList");
        $routes->post("update-restaurant-order-status", "RestaurantController::updateRestaurantOrderStatus");
    });

    $routes->group('notification', function ($routes) {
        $routes->get("get-user-notifications", "NotificationController::getUserNotifications");
        $routes->get("unread-notifications", "NotificationController::unreadNotifications");
        $routes->post("user-read-notifications", "NotificationController::userReadNotifications");
        $routes->post("trace-resolved", "NotificationController::traceResolved");
    });

    $routes->group('gallery', function ($routes) {
        $routes->get('all-images', 'GalleryController::allImages');
    });
});

/***************************** Admin + Attendee *****************************/
$routes->group("api", ["filter" => "authapi:Admin,Attendee", 'namespace' => 'App\Controllers'], function ($routes) {
    $routes->group('evalet', function ($routes) {
        $routes->post('parked', 'EValetController::parked');
    });

    $routes->group('maintenance', function ($routes) {
        $routes->get("maintenance-list", "MaintenanceController::maintenanceList");
        $routes->get("get-room-list", "MaintenanceController::getRoomList");
        $routes->get("reservation-of-room", "MaintenanceController::reservationOfRoom");
        $routes->post('create-update-maintenance-request', 'MaintenanceController::createUpdateMaintenanceRequest');

        // work order
        $routes->get("work-order-list", "MaintenanceController::maintenanceList");
        $routes->post("assign-task", "MaintenanceController::assignTask");
        $routes->post("update-status", "MaintenanceController::updateStatus");
        $routes->post("add-comment", "MaintenanceController::addComment");
        $routes->get("get-comments", "MaintenanceController::getComments");
    });
});

/*****************************  Admin + Guest *****************************/
$routes->group("api", ["filter" => "authapi:Admin,Guest", 'namespace' => 'App\Controllers\APIControllers'], function ($routes) {
    $routes->group('asset-handover', function ($routes) {
        $routes->get('', 'ReceivingFormController::assetHandover');
        $routes->get('get-assets-list', 'ReceivingFormController::getAssetsList');
        $routes->post('submit-asset-handover-form', 'ReceivingFormController::submitAssetHandoverForm');
    });

    $routes->get('reservation-by-id', 'ReservationController::reservationById');
});

/*****************************  Admin *****************************/
$routes->group("api/admin", ["filter" => "authapi:Admin", 'namespace' => 'App\Controllers\APIControllers\Admin'], function ($routes) {

    $routes->get("reservation/get-reservations-list", "ReservationController::getReservationsList");

    $routes->group('laundry-amenities', function ($routes) {
        $routes->get("orders-list", "LaundryAmenitiesController::ordersList");
        $routes->post("update-delivery-status", "LaundryAmenitiesController::updateDeliveryStatus");
    });

    $routes->get("user-departments", "UserController::userDepartments");
    $routes->get("get-user-by-department", "UserController::getUserByDepartment");

    $routes->group('asset-tracking', function ($routes) {
        $routes->get("get-assets", "AssetTrackingController::getAssets");
        $routes->post("submit-form", "AssetTrackingController::submitForm");
    });
});

/*****************************  Admin + Attendee + Supervisor *****************************/
$routes->group("api/admin", ["filter" => "authapi:Admin,Attendee,Supervisor", 'namespace' => 'App\Controllers\APIControllers\Admin'], function ($routes) {
    $routes->group('housekeeping', function ($routes) {
        $routes->get("all-tasks", "HouseKeepingController::allTasks");
        $routes->post("task-details", "HouseKeepingController::taskDetails");
        $routes->post("mark-subtask-completed-inspected", "HouseKeepingController::markSubtaskCompletedInspected");
        $routes->post("submit-task-note", "HouseKeepingController::submitTaskNote");
        $routes->post("submit-subtask-note", "HouseKeepingController::submitSubtaskNote");
    });
});

$routes->group("api", ["filter" => "authapi:Attendee", 'namespace' => 'App\Controllers\APIControllers\Admin'], function ($routes) {
    $routes->group('housekeeping', function ($routes) {
        $routes->post("task-started", "HouseKeepingController::taskStarted");
    });
});

/*****************************  Guest *****************************/
$routes->group("api", ["filter" => "authapi:Guest", 'namespace' => 'App\Controllers'], function ($routes) {
    $routes->group('restaurant', function ($routes) {
        $routes->get("main-screen", "RestaurantController::mainScreen");
        $routes->get("all-restaurants", "RestaurantController::allRestaurants");
        $routes->get("menu-categories", "RestaurantController::menuCategories");
        $routes->post("place-order", "RestaurantController::placeOrder");
        $routes->post("get-menu-items", "RestaurantController::getMenuItems");

        $routes->get("reservation-slots", "RestaurantReservationSlotController::reservationSlots");
    });

    $routes->group('concierge', function ($routes) {
        $routes->get("concierge-offers", "ConciergeController::conciergeOffers");
        $routes->post("store-concierge-request", "ConciergeController::storeConciergeRequest");
        $routes->get("list-concierge-requests", "ConciergeController::listConciergeRequests");
    });

    $routes->group('transport-request', function ($routes) {
        $routes->get('lookup-api', 'TransportRequestController::lookupApi');
        $routes->post('create-request', 'TransportRequestController::store');
        $routes->get('all-requests', 'TransportRequestController::allRequests');
    });

    $routes->group('reservation', function ($routes) {
        $routes->get("make-checkout-request/(:segment)", "CheckInOutController::makeCheckoutRequest/$1");
    });

    $routes->group('upgrade-room-request', function ($routes) {
        $routes->post('submit-request', 'UpgradeRoomRequestController::submitRequest');
    });

    $routes->get("room-types", "RoomTypeController::roomTypes");
});

/*****************************  Guest *****************************/
$routes->group("api", ["filter" => "authapi:Guest", 'namespace' => 'App\Controllers\APIControllers\Guest'], function ($routes) {

    $routes->get("news", "NewsController::news");
    $routes->get("guideline", "GuidelineController::guideline");
    $routes->get("app-update", "AppUpdateController::appUpdate");

    $routes->group('laundry-amenities', function ($routes) {
        $routes->get("all-categories", "ProductCategoryController::allCategories");
        $routes->get("all-products", "ProductController::allProducts");
        $routes->post("place-order", "LaundryAmenitiesController::placeOrder");
        $routes->get("list-orders", "LaundryAmenitiesController::listOrders");
        $routes->get("download-invoice", "LaundryAmenitiesController::downloadInvoice");
        $routes->post("acknowledged-delivery", "LaundryAmenitiesController::acknowledgedDelivery");
        $routes->post("cancel-order", "LaundryAmenitiesController::cancelOrder");
        $routes->post("payment-successful", "LaundryAmenitiesController::paymentSuccessful");
    });

    $routes->group('profile', function ($routes) {
        $routes->get('all-documents', 'ProfileController::allDocuments');
    });

    $routes->get("reservations-of-customer", "ReservationController::reservationsOfCustomer");
});
//  ----------------------------------- ABUBAKAR CODE (END) --------------------------------------- //
