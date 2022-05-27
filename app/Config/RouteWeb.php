<?php
/*
 * --------------------------------------------------------------------
 * Web Router Setup begin
 * --------------------------------------------------------------------
 */
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
    $routes->match(['post'],'/getInitializeListReserv', 'ApplicatioController::getInitializeListReserv');
    $routes->match(['post'],'/roomList', 'ApplicatioController::roomList');
    $routes->match(['post'],'/getRateQueryData', 'ApplicatioController::getRateQueryData');
    $routes->match(['post'],'/searchProfile', 'ApplicatioController::searchProfile');
    $routes->match(['post'],'/getExistingAppcompany', 'ApplicatioController::getExistingAppcompany');
    $routes->match(['post'],'/appcompanyProfileSetup', 'ApplicatioController::appcompanyProfileSetup');
    $routes->match(['post'],'/getExistCustomer', 'ApplicatioController::getExistCustomer');
    $routes->match(['post'],'/rateQueryDetailOption', 'ApplicatioController::rateQueryDetailOption');

    // $routes->match(['get'],'/testingApi/(:segment)', 'ApplicatioController::triggerReservationEmail/$1');

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

    $routes->get('/special', 'ApplicatioController::special');
    $routes->match(['post'],'/specialView', 'ApplicatioController::SpecialView');
    $routes->match(['post'],'/insertSpecial', 'ApplicatioController::insertSpecial');
    $routes->match(['post'],'/editSpecial', 'ApplicatioController::editSpecial');
    $routes->match(['post'],'/deleteSpecial', 'ApplicatioController::deleteSpecial');

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
    $routes->match(['post'],'/getRoomTypeDetails', 'ApplicatioController::getRoomTypeDetails');
    
    //Changes by Deleep Bose
    $routes->get('/rateClass', 'MastersController::rateClass');
    $routes->match(['post'],'/rateClassView', 'MastersController::RateClassView');
    $routes->match(['post'],'/insertRateClass', 'MastersController::insertRateClass');
    $routes->match(['post'],'/copyRateClass', 'MastersController::copyRateClass');
    $routes->match(['post'],'/editRateClass', 'MastersController::editRateClass');
    $routes->match(['post'],'/deleteRateClass', 'MastersController::deleteRateClass');  
    
    $routes->get('/rateCategory', 'MastersController::rateCategory');
    $routes->match(['post'],'/rateCategoryView', 'MastersController::RateCategoryView');
    $routes->match(['post'],'/insertRateCategory', 'MastersController::insertRateCategory');
    $routes->match(['post'],'/copyRateCategory', 'MastersController::copyRateCategory');
    $routes->match(['post'],'/editRateCategory', 'MastersController::editRateCategory');
    $routes->match(['post'],'/deleteRateCategory', 'MastersController::deleteRateCategory');  
   
    $routes->get('/rateCode', 'MastersController::rateCode');
    $routes->match(['post'],'/rateCodeView', 'MastersController::RateCodeView');
    $routes->match(['post'],'/rateCodeDetailsView', 'MastersController::RateCodeDetailsView');
    $routes->get('/addRateCode', 'MastersController::addRateCode');

    $routes->get('/editRateCode/(:num)', 'MastersController::editRateCode/$1');
    $routes->get('/rateCodeDetails/(:num)', 'MastersController::rateCodeDetails/$1');
    $routes->get('/showRateCodeInfo', 'MastersController::showRateCodeInfo');
    $routes->get('/showRateCodeDetails', 'MastersController::showRateCodeDetails');
    $routes->get('/showColorBadges', 'MastersController::showColorBadges');
    $routes->get('/showRoomTypeList', 'MastersController::showRoomTypeList');

    $routes->match(['post'],'/insertRateCode', 'MastersController::insertRateCode');
    $routes->match(['post'],'/deleteRateCode', 'MastersController::deleteRateCode');
    $routes->match(['post'],'/deleteRateCodeDetail', 'MastersController::deleteRateCodeDetail');
    $routes->match(['post'],'/copyRateCode', 'MastersController::copyRateCode');
    $routes->match(['post'],'/updateRateCodeDetail', 'MastersController::updateRateCodeDetail');
    $routes->match(['post'],'/copyRateCodeDetail', 'MastersController::copyRateCodeDetail');
    $routes->match(['post'],'/negotiatedRateView', 'MastersController::NegotiatedRateView');

    
    $routes->get('/transactionCodeGroup', 'MastersController::transactionCodeGroup');
    $routes->match(['post'],'/transactionCodeGroupView', 'MastersController::TransactionCodeGroupView');
    $routes->match(['post'],'/insertTransactionCodeGroup', 'MastersController::insertTransactionCodeGroup');
    $routes->match(['post'],'/editTransactionCodeGroup', 'MastersController::editTransactionCodeGroup');
    $routes->match(['post'],'/deleteTransactionCodeGroup', 'MastersController::deleteTransactionCodeGroup');
    
    $routes->get('/transactionCodeSubGroup', 'MastersController::transactionCodeSubGroup');
    $routes->match(['post'],'/transactionCodeSubGroupView', 'MastersController::TransactionCodeSubGroupView');
    $routes->match(['post'],'/insertTransactionCodeSubGroup', 'MastersController::insertTransactionCodeSubGroup');
    $routes->match(['post'],'/editTransactionCodeSubGroup', 'MastersController::editTransactionCodeSubGroup');
    $routes->match(['post'],'/deleteTransactionCodeSubGroup', 'MastersController::deleteTransactionCodeSubGroup');  
    
    $routes->get('/transactionCode', 'MastersController::transactionCode');
    $routes->match(['post'],'/transactionCodeView', 'MastersController::TransactionCodeView');
    $routes->match(['post'],'/insertTransactionCode', 'MastersController::insertTransactionCode');
    $routes->match(['post'],'/editTransactionCode', 'MastersController::editTransactionCode');
    $routes->match(['post'],'/deleteTransactionCode', 'MastersController::deleteTransactionCode');  
    
    $routes->get('/packageGroup', 'MastersController::packageGroup');
    $routes->match(['post'],'/packageGroupView', 'MastersController::PackageGroupView');
    $routes->match(['post'],'/insertPackageGroup', 'MastersController::insertPackageGroup');
    $routes->match(['post'],'/editPackageGroup', 'MastersController::editPackageGroup');
    $routes->match(['post'],'/deletePackageGroup', 'MastersController::deletePackageGroup');  

    $routes->get('/packageCode', 'MastersController::packageCode');
    $routes->match(['post'],'/packageCodeView', 'MastersController::PackageCodeView');
    $routes->match(['post'],'/insertPackageCode', 'MastersController::insertPackageCode');
    $routes->match(['post'],'/editPackageCode', 'MastersController::editPackageCode');
    $routes->match(['post'],'/deletePackageCode', 'MastersController::deletePackageCode');  
    $routes->get('/showPackageCodeList', 'MastersController::showPackageCodeList');

    $routes->get('/marketGroup', 'MastersController::marketGroup');
    $routes->match(['post'],'/marketGroupView', 'MastersController::MarketGroupView');
    $routes->match(['post'],'/insertMarketGroup', 'MastersController::insertMarketGroup');
    $routes->match(['post'],'/editMarketGroup', 'MastersController::editMarketGroup');
    $routes->match(['post'],'/deleteMarketGroup', 'MastersController::deleteMarketGroup'); 
    
    $routes->get('/marketCode', 'MastersController::marketCode');
    $routes->match(['post'],'/marketCodeView', 'MastersController::MarketCodeView');
    $routes->match(['post'],'/insertMarketCode', 'MastersController::insertMarketCode');
    $routes->match(['post'],'/editMarketCode', 'MastersController::editMarketCode');
    $routes->match(['post'],'/deleteMarketCode', 'MastersController::deleteMarketCode'); 



    // Code By ALEESHA 
    // Maintenance Request 
    $routes->get('/maintenance', 'FacilityController::maintenanceRequest');
    $routes->match(['post'],'/getRequestList', 'FacilityController::getRequestList');
    $routes->match(['post'],'/insertMaintenanceRequest', 'FacilityController::insertMaintenanceRequest');
    $routes->match(['post'],'/editRequest', 'FacilityController::editMaintenanceRequest');
    $routes->match(['post'],'/deleteRequest', 'FacilityController::deleteRequest');  
    $routes->match(['post'],'/getCustomerFromRoomNo', 'FacilityController::getCustomerFromRoomNo');  
    $routes->match(['post'],'/getCategory', 'FacilityController::maintenanceCategoryList');  
    $routes->match(['post'],'/getSubCategory', 'FacilityController::maintenanceSubCatByCategoryID');  
    // Maintenance Category
    $routes->get('/maintenanceCategory', 'FacilityController::maintenanceRequestCategory');  
    $routes->match(['post'],'/insertCategory', 'FacilityController::insertCategory');  
    $routes->match(['post'],'/category', 'FacilityController::categorylist');  
    $routes->match(['post'],'/editCategory', 'FacilityController::editCategory');  
    $routes->match(['post'],'/deleteCategory', 'FacilityController::deleteCategory');  
    // Maintenance Sub Category
    $routes->match(['post'],'/SubCategory', 'FacilityController::subCategoryList');  
    $routes->get('/maintenanceSubCategory', 'FacilityController::maintenanceRequestSubCategory'); 
    $routes->match(['post'],'/editSubCategory', 'FacilityController::editSubCategory');  
    $routes->match(['post'],'/insertSubCategory', 'FacilityController::insertSubCategory');  
    $routes->match(['post'],'/deleteSubCategory', 'FacilityController::deleteSubCategory');  
     

    // Feedback 
    $routes->get('/feedback', 'FacilityController::feedback');  
    $routes->match(['post'],'/getFeedbackList', 'FacilityController::feedbackList');
    // HANDBOOK
    $routes->get('/handbook', 'FacilityController::handbook');  
    $routes->match(['post'],'/saveHandbook', 'FacilityController::saveHandbook');
    $routes->match(['post'],'/checkhandbook', 'FacilityController::checkthehandbook');

    // SHUTTLE
    $routes->get('/shuttle', 'FacilityController::shuttle');  
    $routes->match(['post'],'/shuttlelist', 'FacilityController::shuttlelist');
    $routes->match(['post'],'/getStages', 'FacilityController::getStages');
    $routes->match(['post'],'/insertShuttle', 'FacilityController::insertShuttle');
    $routes->match(['post'],'/deleteShuttle', 'FacilityController::deleteShuttle');
    $routes->match(['post'],'/editShuttle', 'FacilityController::editShuttle');
    
    // STAGES - SHUTTLE
    $routes->get('/stages', 'FacilityController::stages');  
    $routes->match(['post'],'/getStages', 'FacilityController::getStages');
    $routes->match(['post'],'/deleteStages', 'FacilityController::deleteStages');
    $routes->match(['post'],'/insertStages', 'FacilityController::insertStages');
    $routes->match(['post'],'/editStages', 'FacilityController::editStages');
    $routes->match(['post'],'/getStagesList', 'FacilityController::getStagesList');

    // Shuttle & Stages Mapping
    $routes->get('all-routes-stages', 'FacilityController::allRoutesStages');
    $routes->post('store-route-stop', 'FacilityController::storeRouteStop');
    $routes->post('get-shuttle-stops', 'FacilityController::getShuttleStops');
    $routes->post('update-shuttle-stops-order', 'FacilityController::updateShuttleStopsOrder');
    $routes->post('remove-shuttle-stop', 'FacilityController::removeShuttleStop');

    //Subina Code (START)  
    
    //Currency - 19/05 
    $routes->get('/currency', 'AdditionalController::currency');
    $routes->match(['post'],'/CurrencyView', 'AdditionalController::CurrencyView');
    $routes->match(['post'],'/insertCurrency', 'AdditionalController::insertCurrency');
    $routes->match(['post'],'/editCurrency', 'AdditionalController::editCurrency');
    $routes->match(['post'],'/deleteCurrency', 'AdditionalController::deleteCurrency'); 

    //Exchange Codes - 21/05 
    $routes->get('/exchangeCodes', 'AdditionalController::exchangeCodes');
    $routes->match(['post'],'/ExchangeCodesView', 'AdditionalController::ExchangeCodesView');
    $routes->match(['post'],'/insertExchangeCodes', 'AdditionalController::insertExchangeCodes');
    $routes->match(['post'],'/editExchangeCodes', 'AdditionalController::editExchangeCodes');
    $routes->match(['post'],'/deleteExchangeCodes', 'AdditionalController::deleteExchangeCodes'); 

    //Exchange Rates - 23/05 
    $routes->get('/exchangeRates', 'AdditionalController::exchangeRates');
    $routes->match(['post'],'/ExchangeRatesView', 'AdditionalController::ExchangeRatesView');
    $routes->match(['post'],'/insertExchangeRates', 'AdditionalController::insertExchangeRates');
    $routes->match(['post'],'/editExchangeRates', 'AdditionalController::editExchangeRates');
    $routes->match(['post'],'/deleteExchangeRates', 'AdditionalController::deleteExchangeRates'); 

    //Department - 24/05 
    $routes->get('/departments', 'AdditionalController::departments');
    $routes->match(['post'],'/DepartmentView', 'AdditionalController::DepartmentView');
    $routes->match(['post'],'/insertDepartment', 'AdditionalController::insertDepartment');
    $routes->match(['post'],'/editDepartment', 'AdditionalController::editDepartment');
    $routes->match(['post'],'/deleteDepartment', 'AdditionalController::deleteDepartment'); 

     //Item Class - 24/05 
     $routes->get('/itemClass', 'AdditionalController::itemClass');
     $routes->match(['post'],'/ItemClassView', 'AdditionalController::ItemClassView');
     $routes->match(['post'],'/insertItemClass', 'AdditionalController::insertItemClass');
     $routes->match(['post'],'/editItemClass', 'AdditionalController::editItemClass');
     $routes->match(['post'],'/deleteItemClass', 'AdditionalController::deleteItemClass'); 

      //Item Class - 25/05 
      $routes->get('/items', 'AdditionalController::items');
      $routes->match(['post'],'/ItemsView', 'AdditionalController::ItemsView');
      $routes->match(['post'],'/insertItem', 'AdditionalController::insertItem');
      $routes->match(['post'],'/editItem', 'AdditionalController::editItem');
      $routes->match(['post'],'/deleteItem', 'AdditionalController::deleteItem'); 

      $routes->match(['post'],'/itemClassList', 'AdditionalController::itemClassList');
      $routes->match(['post'],'/itemDepartmentList', 'AdditionalController::itemDepartmentList');
      
   //Subina Code (END)  
   
    // ABUBAKAR CODE (START)
    $routes->group('news', function ($routes) {
        $routes->get('', 'NewsController::news');
        $routes->post('all-news', 'NewsController::allNews');
        $routes->post('store', 'NewsController::store');
        $routes->post('edit', 'NewsController::edit');
        $routes->delete('delete', 'NewsController::delete');
    });

    $routes->group('guideline', function ($routes) {
        $routes->get('', 'GuidelineController::guideline');
        $routes->post('all-guidelines', 'GuidelineController::allGuidelines');
        $routes->post('store', 'GuidelineController::store');
        $routes->post('edit', 'GuidelineController::edit');
        $routes->delete('delete', 'GuidelineController::delete');
        $routes->post('delete-optional-file', 'GuidelineController::deleteOptionalFile');
    });

    $routes->group('app-update', function ($routes) {
        $routes->get('', 'AppUpdateController::appUpdate');
        $routes->post('all-app-updates', 'AppUpdateController::allAppUpdates');
        $routes->post('store', 'AppUpdateController::store');
        $routes->post('edit', 'AppUpdateController::edit');
        $routes->delete('delete', 'AppUpdateController::delete');
    });

    $routes->group('concierge', function ($routes) {
        $routes->get('concierge-offer', 'ConciergeController::conciergeOffer');
        $routes->post('all-concierge-offers', 'ConciergeController::allConciergeOffers');

        $routes->post('store-concierge-offer', 'ConciergeController::storeConciergeOffer');
        $routes->post('edit-concierge-offer', 'ConciergeController::editConciergeOffer');
        $routes->delete('delete-concierge-offer', 'ConciergeController::deleteConciergeOffer');

    });
    // ABUBAKAR CODE (END)
});


//Web Link Reservation
$routes->group("webline", function ($routes) {
    $routes->match(['get'],'ReservationDetail/(:any)', 'ApplicatioController::webLineReservation/$1');
});

    $routes->match(['post'],'/imageUpload', 'ApplicatioController::imageUpload'); 
    $routes->match(['post'],'/croppingImage', 'ApplicatioController::croppingImage'); 
    $routes->match(['post'],'/getActiveUploadImages', 'ApplicatioController::getActiveUploadImages');
    $routes->match(['post'],'/deleteUploadImages', 'ApplicatioController::deleteUploadImages');
    $routes->match(['post'],'/updateCustomerData', 'ApplicatioController::updateCustomerDetail');
    $routes->match(['post'],'/checkStatusUploadFiles', 'ApplicatioController::checkStatusUploadFiles');
    $routes->match(['post'],'/updateVaccineReport', 'ApplicatioController::updateVaccineReport');
    $routes->match(['post'],'/getVaccinUploadImages', 'ApplicatioController::getVaccinUploadImages');
    $routes->match(['post'],'/updateSignatureReserv', 'ApplicatioController::updateSignatureReserv'); 
    $routes->match(['post'],'/confirmPrecheckinStatus', 'ApplicatioController::confirmPrecheckinStatus'); 
    $routes->match(['get'],'/reservationCheckin', 'ApplicatioController::reservationCheckin'); 
    $routes->get('autherropage', 'ApplicatioController::AuthErroPage');