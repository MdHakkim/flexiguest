<?php
/*
 * --------------------------------------------------------------------
 * Web Router Setup begin
 * --------------------------------------------------------------------
 */
$routes->match(['get', 'post'], 'login', 'UserController::login', ["filter" => "noauth"]);

$routes->match(['post'], '/countryList', 'ApplicatioController::countryList');
$routes->match(['post'], '/stateList', 'ApplicatioController::stateList');
$routes->match(['post'], '/cityList', 'ApplicatioController::cityList');
$routes->match(['get'], '/accessDenied', 'UserController::accessDenied');

$routes->get("reset-password-form/(:segment)", "UserController::resetPasswordForm/$1");
$routes->post("reset-password/(:segment)", "UserController::resetPassword/$1");

// Admin routes
$routes->get('/', 'DashboardController::index', ["filter" => "auth"]);
$routes->group("/", ["filter" => "auth"], function ($routes) {
    $routes->get('logout', 'UserController::logout');
    $routes->get("/editor", "EditorController::index");

    $routes->get('/frontDesk', 'DashboardController::frontDesk');

    $routes->get('/reservation', 'ApplicatioController::Reservation');
    $routes->match(['post'], '/reservationView', 'ApplicatioController::reservationView');
    $routes->match(['post'], '/insertReservation', 'ApplicatioController::insertReservation');
    $routes->match(['post'], '/insertCustomer', 'ApplicatioController::insertCustomer');
    $routes->match(['post'], '/editReservation', 'ApplicatioController::editReservation');
    $routes->match(['post'], '/deleteReservation', 'ApplicatioController::deleteReservation');
    $routes->match(['post'], '/getInitializeListReserv', 'ApplicatioController::getInitializeListReserv');
    $routes->match(['post'], '/roomList', 'ApplicatioController::roomList');
    $routes->match(['post'], '/getRateQueryData', 'ApplicatioController::getRateQueryData');
    $routes->match(['post'], '/searchProfile', 'ApplicatioController::searchProfile');
    $routes->match(['post'], '/getExistingAppcompany', 'ApplicatioController::getExistingAppcompany');
    $routes->match(['post'], '/appcompanyProfileSetup', 'ApplicatioController::appcompanyProfileSetup');
    $routes->match(['post'], '/getExistCustomer', 'ApplicatioController::getExistCustomer');
    $routes->match(['post'], '/rateQueryDetailOption', 'ApplicatioController::rateQueryDetailOption');

    $routes->match(['post'], '/reservationChangesView', 'ApplicatioController::ReservationChangesView');
    $routes->match(['post'], '/setUpdatedRateCode', 'ApplicatioController::setUpdatedRateCode');

    $routes->post('checkin/verify-documents', 'ApplicatioController::verifyDocuments');

    $routes->group('reservation', function ($routes) {
        $routes->post('search-reservation', 'ReservationController::searchReservation');
        $routes->post('checkout/(:segment)', 'CheckInOutController::checkout/$1');
        
        $routes->post('update-privileges', 'ReservationController::updatePrivileges');

        $routes->group('shares', function ($routes) {
            $routes->get('get-reservation-details', 'ReservationController::getReservationDetails');
            $routes->post('create-reservation', 'ReservationController::sharesCreateReservation');
            $routes->post('add-share-reservations', 'ReservationController::addShareReservations');
            $routes->post('break-share-reservation', 'ReservationController::breakShareReservation');
            $routes->post('change-share-rate', 'ReservationController::changeShareRate');
        });
    });

    // $routes->match(['get'],'/testingApi/(:segment)', 'ApplicatioController::triggerReservationEmail/$1');

    $routes->get('/customer', 'ApplicatioController::Customer');
    $routes->match(['post'], '/customerView', 'ApplicatioController::customerView');
    $routes->match(['post'], '/editCustomer', 'ApplicatioController::editCustomer');
    $routes->match(['post'], '/deleteCustomer', 'ApplicatioController::deleteCustomer');
    $routes->match(['post'], '/getSupportingLov', 'ApplicatioController::getSupportingLov');
    $routes->match(['post'], '/customerList', 'ApplicatioController::customerList');
    $routes->match(['post'], '/getSupportingReservationLov', 'ApplicatioController::getSupportingReservationLov');
    $routes->match(['post'], '/insertCompAgent', 'ApplicatioController::insertCompAgent');
    $routes->match(['post'], '/getCustomerDetail', 'ApplicatioController::getCustomerDetail');

    $routes->get('/profiles', 'ApplicatioController::profiles');
    $routes->get('/printProfile/(:num)', 'ApplicatioController::printProfile/$1');
    $routes->get('/exportProfile/(:num)', 'ApplicatioController::exportProfile/$1');
    $routes->match(['post'], '/customerChangesView', 'ApplicatioController::CustomerChangesView');

    $routes->get('/showCompareProfiles', 'ApplicatioController::showCompareProfiles');
    $routes->match(['post'], '/mergeProfileTables', 'ApplicatioController::mergeProfileTables');

    $routes->get('/company', 'ApplicatioController::company');
    $routes->match(['post'], '/companyView', 'ApplicatioController::companyView');
    $routes->match(['post'], '/insertCompany', 'ApplicatioController::insertCompany');
    $routes->match(['post'], '/editCompany', 'ApplicatioController::editCompany');
    $routes->match(['post'], '/deleteCompany', 'ApplicatioController::deleteCompany');

    $routes->get('/agent', 'ApplicatioController::agent');
    $routes->match(['post'], '/AgentView', 'ApplicatioController::AgentView');
    $routes->match(['post'], '/insertAgent', 'ApplicatioController::insertAgent');
    $routes->match(['post'], '/editAgent', 'ApplicatioController::editAgent');
    $routes->match(['post'], '/deleteAgent', 'ApplicatioController::deleteAgent');

    $routes->get('/group', 'ApplicatioController::group');
    $routes->match(['post'], '/GroupView', 'ApplicatioController::GroupView');
    $routes->match(['post'], '/insertGroup', 'ApplicatioController::insertGroup');
    $routes->match(['post'], '/editGroup', 'ApplicatioController::editGroup');
    $routes->match(['post'], '/deleteGroup', 'ApplicatioController::deleteGroup');

    $routes->match(['post'], '/getSupportingVipLov', 'ApplicatioController::getSupportingVipLov');
    $routes->match(['post'], '/companyList', 'ApplicatioController::companyList');
    $routes->match(['post'], '/agentList', 'ApplicatioController::agentList');
    $routes->match(['post'], '/groupList', 'ApplicatioController::groupList');
    $routes->match(['post'], '/getSupportingblkLov', 'ApplicatioController::getSupportingblkLov');
    $routes->match(['post'], '/numRooms', 'ApplicatioController::numRooms');
    $routes->match(['post'], '/getRateCodesByRoomType', 'ApplicatioController::getRateCodesByRoomType');

    $routes->get('/block', 'ApplicatioController::block');
    $routes->match(['post'], '/blockList', 'ApplicatioController::blockList');
    $routes->match(['post'], '/blockView', 'ApplicatioController::BlockView');
    $routes->match(['post'], '/insertBlock', 'ApplicatioController::insertBlock');
    $routes->match(['post'], '/editBlock', 'ApplicatioController::editBlock');
    $routes->match(['post'], '/deleteBlock', 'ApplicatioController::deleteBlock');
    $routes->match(['post'], '/insertBlockReservation', 'ApplicatioController::insertBlockReservation');
    $routes->match(['post'], '/showBlockRoomPool', 'ApplicatioController::showBlockRoomPool');

    $routes->get('/blockStatusCode', 'MastersController::blockStatusCode');
    $routes->match(['post'], '/blockStatusCodeView', 'MastersController::BlockStatusCodeView');
    $routes->match(['post'], '/insertBlockStatusCode', 'MastersController::insertBlockStatusCode');
    $routes->match(['post'], '/editBlockStatusCode', 'MastersController::editBlockStatusCode');
    $routes->match(['post'], '/deleteBlockStatusCode', 'MastersController::deleteBlockStatusCode');

    $routes->match(['post'], '/blockReservationView', 'ApplicatioController::BlockReservationView');


    $routes->get('/room', 'ApplicatioController::room');
    $routes->match(['post'], '/roomView', 'ApplicatioController::RoomView');
    $routes->match(['post'], '/insertRoom', 'ApplicatioController::insertRoom');
    $routes->match(['post'], '/editRoom', 'ApplicatioController::editRoom');
    $routes->match(['post'], '/deleteRoom', 'ApplicatioController::deleteRoom');
    $routes->match(['post'], '/getSupportingRoomLov', 'ApplicatioController::getSupportingRoomLov');
    $routes->match(['post'], '/roomTypeList', 'ApplicatioController::roomTypeList');

    $routes->get('/roomClass', 'ApplicatioController::roomClass');
    $routes->match(['post'], '/roomClassView', 'ApplicatioController::RoomClassView');
    $routes->match(['post'], '/insertRoomClass', 'ApplicatioController::insertRoomClass');
    $routes->match(['post'], '/editRoomClass', 'ApplicatioController::editRoomClass');
    $routes->match(['post'], '/deleteRoomClass', 'ApplicatioController::deleteRoomClass');
    $routes->match(['post'], '/roomClassList', 'ApplicatioController::roomClassList');
    $routes->match(['post'], '/getSupportingRoomClassLov', 'ApplicatioController::getSupportingRoomClassLov');
    $routes->match(['post'], '/featureList', 'ApplicatioController::featureList');
    $routes->match(['post'], '/houseKeepSecionList', 'ApplicatioController::houseKeepSecionList');

    $routes->get('/roomType', 'ApplicatioController::roomType');
    $routes->match(['post'], '/roomTypeView', 'ApplicatioController::RoomTypeView');
    $routes->match(['post'], '/insertRoomType', 'ApplicatioController::insertRoomType');
    $routes->match(['post'], '/editRoomType', 'ApplicatioController::editRoomType');
    $routes->match(['post'], '/deleteRoomType', 'ApplicatioController::deleteRoomType');

    $routes->get('/roomFloor', 'ApplicatioController::roomFloor');
    $routes->match(['post'], '/roomFloorView', 'ApplicatioController::RoomFloorView');
    $routes->match(['post'], '/insertRoomFloor', 'ApplicatioController::insertRoomFloor');
    $routes->match(['post'], '/editRoomFloor', 'ApplicatioController::editRoomFloor');
    $routes->match(['post'], '/deleteRoomFloor', 'ApplicatioController::deleteRoomFloor');

    $routes->get('/roomFeature', 'ApplicatioController::roomFeature');
    $routes->match(['post'], '/roomFeatureView', 'ApplicatioController::RoomFeatureView');
    $routes->match(['post'], '/insertRoomFeature', 'ApplicatioController::insertRoomFeature');
    $routes->match(['post'], '/editRoomFeature', 'ApplicatioController::editRoomFeature');
    $routes->match(['post'], '/deleteRoomFeature', 'ApplicatioController::deleteRoomFeature');

    $routes->get('/section', 'ApplicatioController::section');
    $routes->match(['post'], '/sectionView', 'ApplicatioController::SectionView');
    $routes->match(['post'], '/insertSection', 'ApplicatioController::insertSection');
    $routes->match(['post'], '/editSection', 'ApplicatioController::editSection');
    $routes->match(['post'], '/deleteSection', 'ApplicatioController::deleteSection');

    $routes->get('/source', 'ApplicatioController::source');
    $routes->match(['post'], '/sourceView', 'ApplicatioController::SourceView');
    $routes->match(['post'], '/insertSource', 'ApplicatioController::insertSource');
    $routes->match(['post'], '/editSource', 'ApplicatioController::editSource');
    $routes->match(['post'], '/deleteSource', 'ApplicatioController::deleteSource');
    $routes->match(['post'], '/initalConfigLovSource', 'ApplicatioController::initalConfigLovSource');

    $routes->get('/sourceGroup', 'ApplicatioController::sourceGroup');
    $routes->match(['post'], '/sourceGroupView', 'ApplicatioController::SourceGroupView');
    $routes->match(['post'], '/insertSourceGroup', 'ApplicatioController::insertSourceGroup');
    $routes->match(['post'], '/editSourceGroup', 'ApplicatioController::editSourceGroup');
    $routes->match(['post'], '/deleteSourceGroup', 'ApplicatioController::deleteSourceGroup');

    $routes->get('/special', 'ApplicatioController::special');
    $routes->match(['post'], '/specialView', 'ApplicatioController::SpecialView');
    $routes->match(['post'], '/insertSpecial', 'ApplicatioController::insertSpecial');
    $routes->match(['post'], '/editSpecial', 'ApplicatioController::editSpecial');
    $routes->match(['post'], '/deleteSpecial', 'ApplicatioController::deleteSpecial');
    $routes->match(['post'], '/specialsList', 'ApplicatioController::specialsList');

    $routes->get('/reservationType', 'ApplicatioController::reservationType');
    $routes->match(['post'], '/reservationTypeView', 'ApplicatioController::ReservationTypeView');
    $routes->match(['post'], '/insertReservationType', 'ApplicatioController::insertReservationType');
    $routes->match(['post'], '/editReservationType', 'ApplicatioController::editReservationType');
    $routes->match(['post'], '/deleteReservationType', 'ApplicatioController::deleteReservationType');

    $routes->get('/purposeStay', 'ApplicatioController::purposeStay');
    $routes->match(['post'], '/purposeStayView', 'ApplicatioController::PurposeStayView');
    $routes->match(['post'], '/insertPurposeStay', 'ApplicatioController::insertPurposeStay');
    $routes->match(['post'], '/editPurposeStay', 'ApplicatioController::editPurposeStay');
    $routes->match(['post'], '/deletePurposeStay', 'ApplicatioController::deletePurposeStay');

    $routes->get('/payment', 'ApplicatioController::payment');
    $routes->match(['post'], '/paymentView', 'ApplicatioController::PaymentView');
    $routes->match(['post'], '/insertPayment', 'ApplicatioController::insertPayment');
    $routes->match(['post'], '/editPayment', 'ApplicatioController::editPayment');
    $routes->match(['post'], '/deletePayment', 'ApplicatioController::deletePayment');

    $routes->get('/overBooking', 'ApplicatioController::overBooking');
    $routes->match(['post'], '/overBookingView', 'ApplicatioController::OverBookingView');
    $routes->match(['post'], '/insertOverBooking', 'ApplicatioController::insertOverBooking');
    $routes->match(['post'], '/editOverBooking', 'ApplicatioController::editOverBooking');
    $routes->match(['post'], '/deleteOverBooking', 'ApplicatioController::deleteOverBooking');

    $routes->match(['post'], '/getSupportingOverbookingLov', 'ApplicatioController::getSupportingOverbookingLov');
    $routes->match(['post'], '/getRoomType', 'ApplicatioController::getRoomType');
    $routes->match(['post'], '/getRoomTypeDetails', 'ApplicatioController::getRoomTypeDetails');

    //Changes by Deleep Bose
    $routes->get('/rateClass', 'MastersController::rateClass');
    $routes->match(['post'], '/rateClassView', 'MastersController::RateClassView');
    $routes->match(['post'], '/insertRateClass', 'MastersController::insertRateClass');
    $routes->match(['post'], '/copyRateClass', 'MastersController::copyRateClass');
    $routes->match(['post'], '/editRateClass', 'MastersController::editRateClass');
    $routes->match(['post'], '/deleteRateClass', 'MastersController::deleteRateClass');

    $routes->get('/rateCategory', 'MastersController::rateCategory');
    $routes->match(['post'], '/rateCategoryView', 'MastersController::RateCategoryView');
    $routes->match(['post'], '/insertRateCategory', 'MastersController::insertRateCategory');
    $routes->match(['post'], '/copyRateCategory', 'MastersController::copyRateCategory');
    $routes->match(['post'], '/editRateCategory', 'MastersController::editRateCategory');
    $routes->match(['post'], '/deleteRateCategory', 'MastersController::deleteRateCategory');

    $routes->get('/rateCode', 'MastersController::rateCode');
    $routes->match(['post'], '/rateCodeView', 'MastersController::RateCodeView');
    $routes->match(['post'], '/rateCodeDetailsView', 'MastersController::RateCodeDetailsView');
    $routes->get('/addRateCode', 'MastersController::addRateCode');

    $routes->get('/editRateCode/(:num)', 'MastersController::editRateCode/$1');
    $routes->get('/rateCodeDetails/(:num)', 'MastersController::rateCodeDetails/$1');
    $routes->get('/showRateCodeInfo', 'MastersController::showRateCodeInfo');
    $routes->get('/showRateCodeDetails', 'MastersController::showRateCodeDetails');
    $routes->get('/showColorBadges', 'MastersController::showColorBadges');
    $routes->get('/showRoomTypeList', 'MastersController::showRoomTypeList');

    $routes->match(['post'], '/insertRateCode', 'MastersController::insertRateCode');
    $routes->match(['post'], '/deleteRateCode', 'MastersController::deleteRateCode');
    $routes->match(['post'], '/deleteRateCodeDetail', 'MastersController::deleteRateCodeDetail');
    $routes->match(['post'], '/copyRateCode', 'MastersController::copyRateCode');
    $routes->match(['post'], '/updateRateCodeDetail', 'MastersController::updateRateCodeDetail');
    $routes->match(['post'], '/copyRateCodeDetail', 'MastersController::copyRateCodeDetail');
    $routes->match(['post'], '/negotiatedRateView', 'MastersController::NegotiatedRateView');
    $routes->match(['post'], '/combinedProfilesView', 'MastersController::CombinedProfilesView');
    $routes->match(['post'], '/insertNegotiatedRate', 'MastersController::insertNegotiatedRate');
    $routes->match(['post'], '/deleteNegotiatedRate', 'MastersController::deleteNegotiatedRate');
    $routes->match(['post'], '/checkRoomTypeinRateCodeDetail', 'MastersController::checkRoomTypeinRateCodeDetail');

    $routes->match(['post'], '/customerNegotiatedRateView', 'ApplicatioController::CustomerNegotiatedRateView');
    $routes->match(['post'], '/insertCustomerNegotiatedRate', 'ApplicatioController::insertCustomerNegotiatedRate');
    $routes->match(['post'], '/deleteCustomerNegotiatedRate', 'ApplicatioController::deleteCustomerNegotiatedRate');


    $routes->get('/transactionCodeGroup', 'MastersController::transactionCodeGroup');
    $routes->match(['post'], '/transactionCodeGroupView', 'MastersController::TransactionCodeGroupView');
    $routes->match(['post'], '/insertTransactionCodeGroup', 'MastersController::insertTransactionCodeGroup');
    $routes->match(['post'], '/editTransactionCodeGroup', 'MastersController::editTransactionCodeGroup');
    $routes->match(['post'], '/deleteTransactionCodeGroup', 'MastersController::deleteTransactionCodeGroup');

    $routes->get('/transactionCodeSubGroup', 'MastersController::transactionCodeSubGroup');
    $routes->match(['post'], '/transactionCodeSubGroupView', 'MastersController::TransactionCodeSubGroupView');
    $routes->match(['post'], '/insertTransactionCodeSubGroup', 'MastersController::insertTransactionCodeSubGroup');
    $routes->match(['post'], '/editTransactionCodeSubGroup', 'MastersController::editTransactionCodeSubGroup');
    $routes->match(['post'], '/deleteTransactionCodeSubGroup', 'MastersController::deleteTransactionCodeSubGroup');

    $routes->get('/transactionCode', 'MastersController::transactionCode');
    $routes->match(['post'], '/transactionCodeView', 'MastersController::TransactionCodeView');
    $routes->match(['post'], '/insertTransactionCode', 'MastersController::insertTransactionCode');
    $routes->match(['post'], '/editTransactionCode', 'MastersController::editTransactionCode');
    $routes->match(['post'], '/deleteTransactionCode', 'MastersController::deleteTransactionCode');

    $routes->get('/packageGroup', 'MastersController::packageGroup');
    $routes->match(['post'], '/packageGroupView', 'MastersController::PackageGroupView');
    $routes->match(['post'], '/insertPackageGroup', 'MastersController::insertPackageGroup');
    $routes->match(['post'], '/editPackageGroup', 'MastersController::editPackageGroup');
    $routes->match(['post'], '/deletePackageGroup', 'MastersController::deletePackageGroup');

    $routes->get('/packageCode', 'MastersController::packageCode');
    $routes->match(['post'], '/packageCodeView', 'MastersController::PackageCodeView');
    $routes->match(['post'], '/packageCodeDetailsView', 'MastersController::PackageCodeDetailsView');
    $routes->get('/showPackageCodeDetails', 'MastersController::showPackageCodeDetails');
    $routes->match(['post'], '/insertPackageCode', 'MastersController::insertPackageCode');
    $routes->match(['post'], '/editPackageCode', 'MastersController::editPackageCode');
    $routes->match(['post'], '/deletePackageCode', 'MastersController::deletePackageCode');
    $routes->match(['post'], '/updatePackageCodeDetail', 'MastersController::updatePackageCodeDetail');
    $routes->match(['post'], '/deletePackageCodeDetail', 'MastersController::deletePackageCodeDetail');
    $routes->get('/showPackageCodeList', 'MastersController::showPackageCodeList');
    $routes->match(['post'], '/showRateCodePackageDetails', 'MastersController::showRateCodePackageDetails');
    $routes->match(['post'], '/insertRateCodePackage', 'MastersController::insertRateCodePackage');
    $routes->match(['post'], '/deleteRatePackageCode', 'MastersController::deleteRatePackageCode');

    $routes->get('/marketGroup', 'MastersController::marketGroup');
    $routes->match(['post'], '/marketGroupView', 'MastersController::MarketGroupView');
    $routes->match(['post'], '/insertMarketGroup', 'MastersController::insertMarketGroup');
    $routes->match(['post'], '/editMarketGroup', 'MastersController::editMarketGroup');
    $routes->match(['post'], '/deleteMarketGroup', 'MastersController::deleteMarketGroup');

    $routes->get('/marketCode', 'MastersController::marketCode');
    $routes->match(['post'], '/marketCodeView', 'MastersController::MarketCodeView');
    $routes->match(['post'], '/insertMarketCode', 'MastersController::insertMarketCode');
    $routes->match(['post'], '/editMarketCode', 'MastersController::editMarketCode');
    $routes->match(['post'], '/deleteMarketCode', 'MastersController::deleteMarketCode');

    $routes->get('/membershipType', 'MastersController::membershipType');
    $routes->match(['post'], '/membershipTypeView', 'MastersController::MembershipTypeView');
    $routes->match(['post'], '/insertMembershipType', 'MastersController::insertMembershipType');
    $routes->match(['post'], '/editMembershipType', 'MastersController::editMembershipType');
    $routes->match(['post'], '/deleteMembershipType', 'MastersController::deleteMembershipType');
    $routes->match(['post'], '/copyMembershipType', 'MastersController::copyMembershipType');

    $routes->match(['post'], '/customerMembershipsView', 'ApplicatioController::CustomerMembershipsView');
    $routes->match(['post'], '/insertCustomerMembership', 'ApplicatioController::insertCustomerMembership');
    $routes->match(['post'], '/showMembershipTypeList', 'ApplicatioController::showMembershipTypeList');
    $routes->match(['post'], '/editCustomerMembership', 'ApplicatioController::editCustomerMembership');
    $routes->match(['post'], '/deleteCustomerMembership', 'ApplicatioController::deleteCustomerMembership');
    $routes->match(['post'], '/getCustomerMembershipsList', 'ApplicatioController::getCustomerMembershipsList');

    $routes->get('/preferenceGroup', 'MastersController::preferenceGroup');
    $routes->match(['post'], '/preferenceGroupView', 'MastersController::PreferenceGroupView');
    $routes->match(['post'], '/insertPreferenceGroup', 'MastersController::insertPreferenceGroup');
    $routes->match(['post'], '/editPreferenceGroup', 'MastersController::editPreferenceGroup');
    $routes->match(['post'], '/deletePreferenceGroup', 'MastersController::deletePreferenceGroup');

    $routes->get('/preferenceCode', 'MastersController::preferenceCode');
    $routes->match(['post'], '/preferenceCodeView', 'MastersController::PreferenceCodeView');
    $routes->match(['post'], '/insertPreferenceCode', 'MastersController::insertPreferenceCode');
    $routes->match(['post'], '/editPreferenceCode', 'MastersController::editPreferenceCode');
    $routes->match(['post'], '/deletePreferenceCode', 'MastersController::deletePreferenceCode');

    $routes->match(['post'], '/customerPreferenceView', 'ApplicatioController::CustomerPreferencesView');
    $routes->get('/showPreferenceCodeList', 'ApplicatioController::showPreferenceCodeList');
    $routes->match(['post'], '/insertCustomerPreference', 'ApplicatioController::insertCustomerPreference');
    $routes->match(['post'], '/deletePreference', 'ApplicatioController::deletePreference');

    $routes->get('/vaccineType', 'MastersController::vaccineType');
    $routes->match(['post'], '/vaccineTypeView', 'MastersController::VaccineTypeView');
    $routes->match(['post'], '/insertVaccineType', 'MastersController::insertVaccineType');
    $routes->match(['post'], '/editVaccineType', 'MastersController::editVaccineType');
    $routes->match(['post'], '/deleteVaccineType', 'MastersController::deleteVaccineType');

    $routes->get('/productCategory', 'MastersController::productCategory');
    $routes->match(['post'], '/productCategoryView', 'MastersController::ProductCategoryView');
    $routes->match(['post'], '/insertProductCategory', 'MastersController::insertProductCategory');
    $routes->match(['post'], '/editProductCategory', 'MastersController::editProductCategory');
    $routes->match(['post'], '/deleteProductCategory', 'MastersController::deleteProductCategory');

    $routes->get('/products', 'AdditionalController::products');
    $routes->match(['post'], '/ProductsView', 'AdditionalController::ProductsView');
    $routes->match(['post'], '/insertProduct', 'AdditionalController::insertProduct');
    $routes->match(['post'], '/editProduct', 'AdditionalController::editProduct');
    $routes->match(['post'], '/deleteProduct', 'AdditionalController::deleteProduct');

    $routes->get('/amenitiesRequests', 'FacilityController::amenitiesRequests');
    $routes->match(['post'], '/getAmenitiesRequestList', 'FacilityController::getAmenitiesRequestList');
    $routes->match(['post'], '/insertAmenitiesRequest', 'FacilityController::insertAmenitiesRequest');
    $routes->match(['post'], '/editAmenitiesRequest', 'FacilityController::editAmenitiesRequest');
    $routes->match(['post'], '/deleteAmenitiesRequest', 'FacilityController::deleteAmenitiesRequest');
    $routes->match(['post'], '/updateAmenityOrder', 'FacilityController::updateAmenityOrder');
    $routes->match(['post'], '/updateAmenityOrderPaymentMethod', 'FacilityController::updateAmenityOrderPaymentMethod');
    $routes->match(['post'], '/updateAmenityProdRequestUser', 'FacilityController::updateAmenityProdRequestUser');
    $routes->match(['post'], '/amenityOrderDetailsView', 'FacilityController::AmenityOrderDetailsView');
    $routes->match(['post'], '/updateAmenityOrderDetails', 'FacilityController::updateAmenityOrderDetails');
    $routes->match(['post'], '/searchRequestProducts', 'FacilityController::searchRequestProducts');
    $routes->match(['post'], '/showRequestProducts', 'FacilityController::showRequestProducts');
    $routes->match(['post'], '/getReservationCustomers', 'FacilityController::getReservationCustomers');
    $routes->match(['post'], '/showReservationCustomers', 'FacilityController::showReservationCustomers');
    $routes->match(['post'], '/insertAmenityOrder', 'FacilityController::insertAmenityOrder');


    $routes->get('/cancellationReason', 'MastersController::cancellationReason');
    $routes->match(['post'], '/cancellationReasonView', 'MastersController::CancellationReasonView');
    $routes->match(['post'], '/insertCancellationReason', 'MastersController::insertCancellationReason');
    $routes->match(['post'], '/editCancellationReason', 'MastersController::editCancellationReason');
    $routes->match(['post'], '/deleteCancellationReason', 'MastersController::deleteCancellationReason');

    $routes->match(['post'], '/insertResvCancelHistory', 'ApplicatioController::insertResvCancelHistory');
    $routes->match(['post'], '/reinstateReservation', 'ApplicatioController::reinstateReservation');
    $routes->match(['post'], '/resvCancelHistoryView', 'ApplicatioController::ResvCancelHistoryView');

    $routes->match(['post'], '/uploadResvAttachments', 'ReservationController::uploadResvAttachments');

    $routes->get('/roomStatusChangeReason', 'MastersController::roomStatusChangeReason');
    $routes->match(['post'], '/roomStatusChangeReasonView', 'MastersController::RoomStatusChangeReasonView');
    $routes->match(['post'], '/insertRoomStatusChangeReason', 'MastersController::insertRoomStatusChangeReason');
    $routes->match(['post'], '/editRoomStatusChangeReason', 'MastersController::editRoomStatusChangeReason');
    $routes->match(['post'], '/deleteRoomStatusChangeReason', 'MastersController::deleteRoomStatusChangeReason');


    // Code By ALEESHA 
    // Maintenance Request 
    $routes->get('/maintenance', 'FacilityController::maintenanceRequest');
    $routes->match(['post'], '/getRequestList', 'FacilityController::getRequestList');
    $routes->match(['post'], '/insertMaintenanceRequest', 'FacilityController::insertMaintenanceRequest');
    $routes->match(['post'], '/editRequest', 'FacilityController::editMaintenanceRequest');
    $routes->match(['post'], '/deleteRequest', 'FacilityController::deleteRequest');
    $routes->match(['post'], '/getCustomerFromRoomNo', 'FacilityController::getCustomerFromRoomNo');
    $routes->match(['post'], '/getCategory', 'FacilityController::maintenanceCategoryList');
    $routes->match(['post'], '/getSubCategory', 'FacilityController::maintenanceSubCatByCategoryID');

    $routes->group('maintenance', function ($routes) {
        $routes->post("get-comments", "MaintenanceController::getComments");
    });

    // Maintenance Category
    $routes->get('/maintenanceCategory', 'FacilityController::maintenanceRequestCategory');
    $routes->match(['post'], '/insertCategory', 'FacilityController::insertCategory');
    $routes->match(['post'], '/category', 'FacilityController::categorylist');
    $routes->match(['post'], '/editCategory', 'FacilityController::editCategory');
    $routes->match(['post'], '/deleteCategory', 'FacilityController::deleteCategory');
    // Maintenance Sub Category
    $routes->match(['post'], '/SubCategory', 'FacilityController::subCategoryList');
    $routes->get('/maintenanceSubCategory', 'FacilityController::maintenanceRequestSubCategory');
    $routes->match(['post'], '/editSubCategory', 'FacilityController::editSubCategory');
    $routes->match(['post'], '/insertSubCategory', 'FacilityController::insertSubCategory');
    $routes->match(['post'], '/deleteSubCategory', 'FacilityController::deleteSubCategory');


    // Feedback 
    $routes->get('/feedback', 'FacilityController::feedback');
    $routes->match(['post'], '/getFeedbackList', 'FacilityController::feedbackList');
    // HANDBOOK
    $routes->get('/handbook', 'FacilityController::handbook');
    $routes->match(['post'], '/saveHandbook', 'FacilityController::saveHandbook');
    $routes->match(['post'], '/checkhandbook', 'FacilityController::checkthehandbook');

    // SHUTTLE
    $routes->get('/shuttle', 'FacilityController::shuttle');
    $routes->match(['post'], '/shuttlelist', 'FacilityController::shuttlelist');
    $routes->match(['post'], '/getStages', 'FacilityController::getStages');
    $routes->match(['post'], '/insertShuttle', 'FacilityController::insertShuttle');
    $routes->match(['post'], '/deleteShuttle', 'FacilityController::deleteShuttle');
    $routes->match(['post'], '/editShuttle', 'FacilityController::editShuttle');

    // STAGES - SHUTTLE
    $routes->get('/stages', 'FacilityController::stages');
    $routes->match(['post'], '/getStages', 'FacilityController::getStages');
    $routes->match(['post'], '/deleteStages', 'FacilityController::deleteStages');
    $routes->match(['post'], '/insertStages', 'FacilityController::insertStages');
    $routes->match(['post'], '/editStages', 'FacilityController::editStages');
    $routes->match(['post'], '/getStagesList', 'FacilityController::getStagesList');

    // Shuttle & Stages Mapping
    $routes->get('all-routes-stages', 'FacilityController::allRoutesStages');
    $routes->post('store-route-stop', 'FacilityController::storeRouteStop');
    $routes->post('get-shuttle-stops', 'FacilityController::getShuttleStops');
    $routes->post('update-shuttle-stops-order', 'FacilityController::updateShuttleStopsOrder');
    $routes->post('remove-shuttle-stop', 'FacilityController::removeShuttleStop');

    //Subina Code (START)  

    //Currency - 19/05 
    $routes->get('/currency', 'AdditionalController::currency');
    $routes->match(['post'], '/CurrencyView', 'AdditionalController::CurrencyView');
    $routes->match(['post'], '/insertCurrency', 'AdditionalController::insertCurrency');
    $routes->match(['post'], '/editCurrency', 'AdditionalController::editCurrency');
    $routes->match(['post'], '/deleteCurrency', 'AdditionalController::deleteCurrency');

    //Exchange Codes - 21/05 
    $routes->get('/exchangeCodes', 'AdditionalController::exchangeCodes');
    $routes->match(['post'], '/ExchangeCodesView', 'AdditionalController::ExchangeCodesView');
    $routes->match(['post'], '/insertExchangeCodes', 'AdditionalController::insertExchangeCodes');
    $routes->match(['post'], '/editExchangeCodes', 'AdditionalController::editExchangeCodes');
    $routes->match(['post'], '/deleteExchangeCodes', 'AdditionalController::deleteExchangeCodes');

    //Exchange Rates - 23/05 
    $routes->get('/exchangeRates', 'AdditionalController::exchangeRates');
    $routes->match(['post'], '/ExchangeRatesView', 'AdditionalController::ExchangeRatesView');
    $routes->match(['post'], '/insertExchangeRates', 'AdditionalController::insertExchangeRates');
    $routes->match(['post'], '/editExchangeRates', 'AdditionalController::editExchangeRates');
    $routes->match(['post'], '/deleteExchangeRates', 'AdditionalController::deleteExchangeRates');

    //Department - 24/05 
    $routes->get('/departments', 'AdditionalController::departments');
    $routes->match(['post'], '/DepartmentView', 'AdditionalController::DepartmentView');
    $routes->match(['post'], '/insertDepartment', 'AdditionalController::insertDepartment');
    $routes->match(['post'], '/editDepartment', 'AdditionalController::editDepartment');
    $routes->match(['post'], '/deleteDepartment', 'AdditionalController::deleteDepartment');

    //Item Class - 24/05 
    $routes->get('/itemClass', 'AdditionalController::itemClass');
    $routes->match(['post'], '/ItemClassView', 'AdditionalController::ItemClassView');
    $routes->match(['post'], '/insertItemClass', 'AdditionalController::insertItemClass');
    $routes->match(['post'], '/editItemClass', 'AdditionalController::editItemClass');
    $routes->match(['post'], '/deleteItemClass', 'AdditionalController::deleteItemClass');

    //Item  - 26/05 
    $routes->get('/items', 'AdditionalController::items');
    $routes->match(['post'], '/ItemsView', 'AdditionalController::ItemsView');
    $routes->match(['post'], '/insertItem', 'AdditionalController::insertItem');
    $routes->match(['post'], '/editItem', 'AdditionalController::editItem');
    $routes->match(['post'], '/deleteItem', 'AdditionalController::deleteItem');

    //Daily Inventory  - 27/05 
    $routes->get('/dailyInventory', 'AdditionalController::dailyInventory');
    $routes->match(['post'], '/DailyInventoryView', 'AdditionalController::DailyInventoryView');
    $routes->match(['post'], '/insertDailyInventory', 'AdditionalController::insertDailyInventory');
    $routes->match(['post'], '/editDailyInventory', 'AdditionalController::editDailyInventory');
    $routes->match(['post'], '/deleteDailyInventory', 'AdditionalController::deleteDailyInventory');

    $routes->match(['post'], '/itemClassList', 'AdditionalController::itemClassList');
    $routes->match(['post'], '/itemDepartmentList', 'AdditionalController::itemDepartmentList');
    $routes->match(['post'], '/itemList', 'AdditionalController::itemList');
    $routes->match(['post'], '/insertItemInventory', 'ApplicatioController::insertItemInventory');
    //$routes->match(['post'],'/showInventoryItems', 'ApplicatioController::showInventoryItems');  



    //Reservation Inventory  - 26/05 
    $routes->get('/inventory', 'InventoryController::inventory');
    $routes->match(['post'], '/showInventoryItems', 'InventoryController::showInventoryItems');
    $routes->match(['post'], '/updateInventoryItems', 'InventoryController::updateInventoryItems');
    $routes->match(['post'], '/showInventoryDetails', 'InventoryController::showInventoryDetails');
    $routes->match(['post'], '/deleteItemInventory', 'InventoryController::deleteItemInventory');

    //Guests Type Master
    $routes->get('/guestType', 'AdditionalController::guestType');
    $routes->match(['post'], '/guestTypeView', 'AdditionalController::GuestTypeView');
    $routes->match(['post'], '/insertGuestType', 'AdditionalController::insertGuestType');
    $routes->match(['post'], '/copyGuestType', 'AdditionalController::copyGuestType');
    $routes->match(['post'], '/editGuestType', 'AdditionalController::editGuestType');
    $routes->match(['post'], '/deleteGuestType', 'AdditionalController::deleteGuestType');

    //Register Cards
    $routes->get('/registerCards', 'ReservationController::registerCards');
    $routes->get('/registerCardPrint', 'ReservationController::registerCardPrint');
    $routes->get('/registerCardPreview', 'ReservationController::registerCardPreview');
    $routes->match(['post'], '/registerCardSaveDetails', 'ReservationController::registerCardSaveDetails');

    $routes->match(['post'], '/singleReservRegCards', 'ReservationController::singleReservRegCards');
    $routes->get('/singleReservRegCardPrint', 'ReservationController::singleReservRegCardPrint');




    //Users
    $routes->match(['post'], '/UsersList', 'UserController::UsersList');
    $routes->match(['post'], '/insertUser', 'UserController::insertUser');
    $routes->match(['post'], '/editUser', 'UserController::editUser');
    $routes->match(['post'], '/suspendUser', 'UserController::suspendUser');
    $routes->get('/Users', 'UserController::Users');
    $routes->match(['post'], '/userCountryList', 'UserController::userCountryList');
    $routes->match(['post'], '/userStateList', 'UserController::userStateList');
    $routes->match(['post'], '/userCityList', 'UserController::userCityList');
    $routes->match(['post'], '/user-by-department', 'UserController::userByDepartment');

    $routes->group('my-profile', function ($routes) {
        $routes->get('', 'UserController::myProfile');
        $routes->get('edit', 'UserController::editProfile');
    });

    //User Role
    $routes->get('/UserRole', 'UserController::UserRole');
    $routes->match(['post'], '/userRoleView', 'UserController::userRoleView');
    $routes->match(['post'], '/insertUserRole', 'UserController::insertUserRole');
    $routes->match(['post'], '/copyUserRole', 'UserController::copyUserRole');
    $routes->match(['post'], '/editUserRole', 'UserController::editUserRole');
    $routes->match(['post'], '/deleteUserRole', 'UserController::deleteUserRole');

    //User Menu      
    $routes->get('/Menu', 'AdditionalController::Menu');
    $routes->get('/menuList', 'AdditionalController::menuList');
    $routes->match(['post'], '/MenuView', 'AdditionalController::MenuView');
    $routes->match(['post'], '/insertMenu', 'AdditionalController::insertMenu');
    $routes->match(['post'], '/copyMenu', 'AdditionalController::copyMenu');
    $routes->match(['post'], '/editMenu', 'AdditionalController::editMenu');
    $routes->match(['post'], '/deleteMenu', 'AdditionalController::deleteMenu');

    $routes->get('/roleList', 'UserController::roleList');
    $routes->get('/loadUserRoles', 'UserController::loadUserRoles');
    $routes->get('/userRoles', 'UserController::userRoles');
    $routes->match(['post'], '/viewUserRoles', 'UserController::viewUserRoles');
    $routes->match(['post'], '/addRolePermission', 'UserController::addRolePermission');
    $routes->match(['post'], '/editRolePermission', 'UserController::editRolePermission');
    $routes->get('/checkRolePermission', 'UserController::checkRolePermission');
    $routes->match(['post'], '/searchMenu', 'AdditionalController::searchMenu');

    $routes->match(['post'], '/allUsersList', 'UserController::allUsersList');

    /// Reservation - Fixed Charges 
    $routes->match(['post'], '/transactionList', 'ApplicatioController::transactionList');
    $routes->match(['post'], '/showFixedCharge', 'ApplicatioController::showFixedCharge');
    $routes->match(['post'], '/updateFixedCharges', 'ApplicatioController::updateFixedCharges');
    $routes->match(['post'], '/showFixedChargeDetails', 'ApplicatioController::showFixedChargeDetails');
    $routes->match(['post'], '/deleteFixedcharge', 'ApplicatioController::deleteFixedcharge');
    $routes->match(['post'], '/getReservDetails', 'ApplicatioController::getReservDetails');

    $routes->match(['post'], '/proformaFolio', 'AdditionalController::proformaFolio');
    $routes->get('/printProFormaFolio', 'AdditionalController::printProFormaFolio');
    $routes->get('/previewProFormaFolio', 'AdditionalController::previewProFormaFolio');
    $routes->get('/pdfProFormaFolio', 'AdditionalController::pdfProFormaFolio');


    ///////Reservation - Packages////
    $routes->match(['post'], '/RateClassList', 'ReservationController::RateClassList');
    $routes->match(['post'], '/RateCategory', 'ReservationController::RateCategory');
    $routes->match(['post'], '/RateCodes', 'ReservationController::RateCodes');
    $routes->match(['post'], '/getPackageList', 'ReservationController::getPackageList');
    $routes->match(['post'], '/getPackageDetails', 'ReservationController::getPackageDetails');
    $routes->match(['post'], '/updatePackageDetails', 'ReservationController::updatePackageDetails');

    $routes->match(['post'], '/showPackages', 'ReservationController::showPackages');
    $routes->match(['post'], '/showPackageDetails', 'ReservationController::showPackageDetails');
    $routes->match(['post'], '/deletePackageDetail', 'ReservationController::deletePackageDetail');
    $routes->match(['post'], '/showSinglePackageDetails', 'ReservationController::showSinglePackageDetails');
    $routes->match(['post'], '/rateInfoDetails', 'ReservationController::rateInfoDetails');

    $routes->match(['post'], '/rateInfoDetails', 'ReservationController::rateInfoDetails');
    $routes->match(['post'], '/departmentList', 'UserController::departmentList');

    $routes->match(['post'], '/updateTraces', 'ReservationController::updateTraces');
    $routes->match(['post'], '/showTraces', 'ReservationController::showTraces');
    $routes->match(['post'], '/deleteTraces', 'ReservationController::deleteTraces');
    $routes->match(['post'], '/showTraceDetails', 'ReservationController::showTraceDetails');
    $routes->match(['post'], '/resolveTraces', 'ReservationController::resolveTraces');


    $routes->get('/roomPlanResource', 'ReservationController::roomPlanResource');

    $routes->match(['post'], '/roomplanResources', 'ReservationController::roomplanResources');
    $routes->match(['post'], '/getReservations', 'ReservationController::getReservations');
    $routes->get('/pdfTest', 'AdditionalController::pdfTest');
    $routes->match(['post'], '/updateRoomPlan', 'ReservationController::updateRoomPlan');
    $routes->match(['post'], '/changeReservationDates', 'ReservationController::changeReservationDates');
    $routes->match(['post'], '/updateRoomPlanDetails', 'ReservationController::updateRoomPlanDetails');



    $routes->match(['post'], '/reservationDepartments', 'ReservationController::reservationDepartments');
    $routes->match(['post'], '/updateTraces', 'ReservationController::updateTraces');
    $routes->match(['post'], '/showTraces', 'ReservationController::showTraces');
    $routes->match(['post'], '/deleteTraces', 'ReservationController::deleteTraces');
    $routes->match(['post'], '/showTraceDetails', 'ReservationController::showTraceDetails');
    $routes->match(['post'], '/resolveTraces', 'ReservationController::resolveTraces');

    //$routes->get('/roomPlanResource', 'ReservationController::roomPlanResource');

    $routes->match(['post'], '/roomplanResources', 'ReservationController::roomplanResources');
    $routes->match(['post'], '/getReservations', 'ReservationController::getReservations');
    $routes->get('/pdfTest', 'AdditionalController::pdfTest');
    $routes->match(['post'], '/updateRoomPlan', 'ReservationController::updateRoomPlan');
    $routes->match(['post'], '/changeReservationDates', 'ReservationController::changeReservationDates');
    $routes->get('/roomPlanTest', 'ReservationController::roomPlanTest');
    $routes->match(['post'], '/getInventoryCalendarData', 'ApplicatioController::getInventoryCalendarData');
    $routes->match(['post'], '/getInventoryAllocatedData', 'ApplicatioController::getInventoryAllocatedData');
    $routes->match(['post'], '/getRoomStatistics', 'ReservationController::getRoomStatistics');
    $routes->match(['post'], '/updateRoomRTC', 'ReservationController::updateRoomRTC');



    $routes->get('/Notifications', 'NotificationController::Notifications');
    $routes->match(['post'], '/NotificationList', 'NotificationController::NotificationList');
    $routes->match(['post'], '/insertNotification', 'NotificationController::insertNotification');
    $routes->match(['post'], '/editNotification', 'NotificationController::editNotification');
    $routes->match(['post'], '/deleteNotification', 'NotificationController::deleteNotification');
    $routes->get('/notificationTypeList', 'NotificationController::notificationTypeList');
    $routes->get('/usersList', 'NotificationController::usersList');
    $routes->get('/allDepartmentList', 'NotificationController::allDepartmentList');
    $routes->get('/reservationList', 'NotificationController::reservationList');
    $routes->get('/getCustomers', 'NotificationController::getCustomers');

    $routes->match(['post'], '/viewAllNotificationDetails', 'NotificationController::viewAllNotificationDetails');
    $routes->match(['post'], '/guestByReservation', 'NotificationController::guestByReservation');
    $routes->match(['post'], '/loadNotification', 'NotificationController::loadNotification');

    $routes->get('/taskcode', 'HousekeepingController::taskcode');
    $routes->match(['post'], '/taskcodeView', 'HousekeepingController::taskcodeView');
    $routes->match(['post'], '/insertTaskcode', 'HousekeepingController::insertTaskcode');
    $routes->match(['post'], '/editTaskcode', 'HousekeepingController::editTaskcode');
    $routes->match(['post'], '/deleteTaskcode', 'HousekeepingController::deleteTaskcode');

    $routes->get('/tasks', 'HousekeepingController::tasks');
    $routes->match(['post'], '/tasksView', 'HousekeepingController::tasksView');
    $routes->match(['post'], '/insertTask', 'HousekeepingController::insertTask');
    $routes->match(['post'], '/editTask', 'HousekeepingController::editTask');
    $routes->match(['post'], '/deleteTask', 'HousekeepingController::deleteTask');
    $routes->get('/taskcodeList', 'HousekeepingController::taskcodeList');
    $routes->get('/allTaskcodeList', 'HousekeepingController::allTaskcodeList');

    $routes->match(['post'], '/searchRooms', 'ReservationController::searchRooms');


    $routes->match(['post'], '/floorsList', 'ReservationController::floorsList');
    $routes->match(['post'], '/assignRoomFromOptions', 'ReservationController::assignRoomFromOptions');
    

    //Subina Code (END)  
    $routes->match(['post'], '/roomTypeSearchList', 'ReservationController::roomTypeSearchList');
    $routes->match(['post'], '/roomClassSearchList', 'ReservationController::roomClassSearchList');
    $routes->match(['post'], '/roomSearchList', 'ReservationController::roomSearchList');
    $routes->match(['post'], '/roomplanResourcesJson', 'ReservationController::roomplanResourcesJson');
    $routes->match(['post', 'get'], '/roomPlan', 'ReservationController::roomPlan');
    $routes->get('/roomPlan/(:segment)', 'ReservationController::roomPlan/$1');

    $routes->match(['post'], '/roomOOSList', 'ReservationController::roomOOSList');
    $routes->match(['post'], '/roomPlanList', 'ReservationController::roomPlanList');
    $routes->match(['post'], '/roomsStatusList', 'ReservationController::roomsStatusList');
    $routes->match(['post'], '/roomsChangeReasonList', 'ReservationController::roomsChangeReasonList');
    $routes->match(['post'], '/insertRoomOOS', 'ReservationController::insertRoomOOS');
    $routes->match(['post'], '/showRoomStatusDetails', 'ReservationController::showRoomStatusDetails');
    $routes->match(['post'], '/deleteRoomOOS', 'ReservationController::deleteRoomOOS');
    $routes->match(['post'], '/checkArrivalExists', 'ReservationController::checkArrivalExists');
    $routes->match(['post'], '/checkReservationExists', 'ReservationController::checkReservationExists');
    $routes->match(['post'], '/getAllVacantRooms', 'ReservationController::getAllVacantRooms');
    $routes->match(['post'], '/updateRoomAssign', 'ReservationController::updateRoomAssign');
    $routes->get('/readNotifications', 'NotificationController::readNotifications');
    $routes->match(['post'], '/updateNotification', 'NotificationController::updateNotification');
    $routes->get('/showAllNotifications', 'NotificationController::showAllNotifications');
    $routes->match(['post'], '/userNotifications', 'NotificationController::userNotifications');
    $routes->match(['post'], '/viewAllNotification', 'NotificationController::viewAllNotification');
    $routes->match(['post'], '/usersByDepartmentList', 'NotificationController::usersByDepartmentList');

    $routes->match(['post'], '/roomStatusList', 'ReservationController::roomStatusList');
    $routes->match(['post'], '/roomFloorList', 'ReservationController::roomFloorList');
    $routes->get('/itemAvailability', 'ReservationController::itemAvailability');
    $routes->match(['post'], '/getResvNo', 'NotificationController::getResvNo');
    $routes->match(['post'], '/resolveNotification', 'NotificationController::resolveNotification');
    $routes->match(['post'], '/notification-status', 'NotificationController::notificationStatus');


    $routes->get('/Notifications', 'NotificationController::Notifications');
    $routes->match(['post'], '/NotificationList', 'NotificationController::NotificationList');


    $routes->get('/TaskAssignment', 'TaskAssignmentController::TaskAssignment');
    $routes->match(['post'], '/TaskAssignmentView', 'TaskAssignmentController::TaskAssignmentView');
    $routes->match(['post'], '/insertTaskAssignment', 'TaskAssignmentController::InsertTaskAssignment');
    $routes->match(['post'], '/attendantList', 'TaskAssignmentController::attendantList');
    $routes->match(['post'], '/showTaskSheets', 'TaskAssignmentController::showTaskSheets');
    $routes->match(['post'], '/getLastSheetNo', 'TaskAssignmentController::getLastSheetNo');

    $routes->match(['post'], '/insertTaskAssignmentSheet', 'TaskAssignmentController::insertTaskAssignmentSheet');
    $routes->match(['post'], '/deleteTaskAssignmentSheet', 'TaskAssignmentController::deleteTaskAssignmentSheet');
    $routes->match(['post'], '/showTaskAssignedRooms', 'TaskAssignmentController::showTaskAssignedRooms');
    $routes->match(['post'], '/taskSheetList', 'TaskAssignmentController::taskSheetList');
    $routes->match(['post'], '/insertTaskAssignmentRoom', 'TaskAssignmentController::insertTaskAssignmentRoom');
    $routes->match(['post'], '/deleteTaskAssignmentRoom', 'TaskAssignmentController::deleteTaskAssignmentRoom');
    $routes->match(['post'], '/taskRoomList', 'TaskAssignmentController::taskRoomList');
    $routes->match(['post'], '/updateTaskStatus', 'TaskAssignmentController::updateTaskStatus');
    $routes->match(['post'], '/viewTaskAssignedRooms', 'TaskAssignmentController::viewTaskAssignedRooms');

    $routes->match(['post'], '/setTaskSheet', 'TaskAssignmentController::setTaskSheet');
    $routes->get('/printTaskSheet', 'TaskAssignmentController::printTaskSheet');
    $routes->match(['post'], '/getTaskComments', 'TaskAssignmentController::getTaskComments');
    $routes->match(['post'], '/checkRoomAlreadyAssigned', 'TaskAssignmentController::checkRoomAlreadyAssigned');
    $routes->match(['post'], '/customerNotesView', 'ApplicatioController::customerNotesView');
    $routes->match(['post'], '/checkRoomAssigned', 'ReservationController::checkRoomAssigned');
    $routes->match(['post'], '/getCreditCardDetails', 'ReservationController::getCreditCardDetails');
    $routes->match(['post'], '/insertCard', 'ReservationController::insertCard');
    $routes->match(['post'], '/getCompanyOwner', 'ApplicatioController::getCompanyOwner');
    $routes->match(['post'], '/checkSharedReservation', 'ReservationController::checkSharedReservation');
    $routes->match(['post'], '/getRateCodeDateRange', 'ReservationController::getRateCodeDateRange');
    $routes->match(['post'], '/getReservationAttachments','ReservationController::getReservationAttachments');
    $routes->match(['post'], '/uploadReservationFiles','ReservationController::uploadReservationFiles');
    
    


    

    //Subina Code (END)  

    // Deleep 
    $routes->group('housekeeping', function ($routes) {
        $routes->get('rooms', 'HousekeepingController::housekeeping');

        $routes->get('room-history', 'HousekeepingController::roomHistory');
        $routes->get('room-history/(:num)', 'HousekeepingController::roomHistory/$1');
    });
    $routes->match(['post'], '/hkroomView', 'HousekeepingController::HkRoomView');
    $routes->match(['post'], '/showFeaturesDesc', 'HousekeepingController::showFeaturesDesc');
    $routes->match(['post'], '/updateRoomStatus', 'HousekeepingController::updateRoomStatus');
    $routes->match(['post'], '/showRoomStatus', 'HousekeepingController::showRoomStatus');
    $routes->match(['post'], '/bulkUpdateRoomStatus', 'HousekeepingController::bulkUpdateRoomStatus');
    $routes->match(['post'], '/updateServiceStatus', 'HousekeepingController::updateServiceStatus');
    $routes->match(['post'], '/showRoomServiceStatus', 'HousekeepingController::showRoomServiceStatus');
    $routes->match(['post'], '/roomHistoryView', 'HousekeepingController::roomHistoryView');
    $routes->match(['post'], '/HkRoomStatistics', 'HousekeepingController::HkRoomStatistics');

    $routes->get('OccupancyGraph', 'HousekeepingController::OccupancyGraph');

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
        $routes->post('disable-enable-guideline', 'GuidelineController::disableEnableGuideline');
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
        $routes->post('change-concierge-offer-status', 'ConciergeController::changeConciergeOfferStatus');
        $routes->post('edit-concierge-offer', 'ConciergeController::editConciergeOffer');
        $routes->delete('delete-concierge-offer', 'ConciergeController::deleteConciergeOffer');

        $routes->get('concierge-request', 'ConciergeController::conciergeRequest');
        $routes->post('all-concierge-requests', 'ConciergeController::allConciergeRequests');
        $routes->post('store-concierge-request', 'ConciergeController::storeConciergeRequest');
        $routes->post('edit-concierge-request', 'ConciergeController::editConciergeRequest');
        $routes->delete('delete-concierge-request', 'ConciergeController::deleteConciergeRequest');
    });

    $routes->group('transport', function ($routes) {
        $routes->group('transport-type', function ($routes) {
            $routes->get('', 'TransportTypeController::transportType');
            $routes->post('all-transport-types', 'TransportTypeController::allTransportTypes');
            $routes->post('store', 'TransportTypeController::store');
            $routes->post('edit', 'TransportTypeController::edit');
            $routes->delete('delete', 'TransportTypeController::delete');
        });

        $routes->group('pickup-point', function ($routes) {
            $routes->get('', 'PickupPointController::pickupPoint');
            $routes->post('all-pickup-points', 'PickupPointController::allPickupPoints');
            $routes->post('store', 'PickupPointController::store');
            $routes->post('edit', 'PickupPointController::edit');
            $routes->delete('delete', 'PickupPointController::delete');
        });

        $routes->group('dropoff-point', function ($routes) {
            $routes->get('', 'DropoffPointController::dropoffPoint');
            $routes->post('all-dropoff-points', 'DropoffPointController::allDropoffPoints');
            $routes->post('store', 'DropoffPointController::store');
            $routes->post('edit', 'DropoffPointController::edit');
            $routes->delete('delete', 'DropoffPointController::delete');
        });

        $routes->group('flight-carrier', function ($routes) {
            $routes->get('', 'FlightCarrierController::flightCarrier');
            $routes->post('all-flight-carriers', 'FlightCarrierController::allFlightCarriers');
            $routes->post('store', 'FlightCarrierController::store');
            $routes->post('edit', 'FlightCarrierController::edit');
            $routes->delete('delete', 'FlightCarrierController::delete');
        });

        $routes->group('transport-request', function ($routes) {
            $routes->get('', 'TransportRequestController::transportRequest');
            $routes->post('all-transport-requests', 'TransportRequestController::allTransportRequests');
            $routes->post('store', 'TransportRequestController::store');
            $routes->post('edit', 'TransportRequestController::edit');
            $routes->delete('delete', 'TransportRequestController::delete');
        });
    });

    $routes->group('property-info', function ($routes) {
        $routes->get('', 'PropertyInfoController::propertyInfo');
        $routes->post('all-property-info', 'PropertyInfoController::allPropertyInfo');
        $routes->post('store', 'PropertyInfoController::store');
    });

    $routes->group('branding-logo', function ($routes) {
        $routes->get('', 'BrandingLogoController::brandingLogo');
        $routes->post('all-branding-logo', 'BrandingLogoController::allBrandingLogo');
        $routes->post('store', 'BrandingLogoController::store');
    });

    $routes->group('alert', function ($routes) {
        $routes->get('', 'AlertController::alert');
        $routes->post('all-alerts', 'AlertController::allAlerts');
        $routes->post('store', 'AlertController::store');
        $routes->post('edit', 'AlertController::edit');
        $routes->delete('delete', 'AlertController::delete');
    });

    $routes->group('evalet', function ($routes) {
        $routes->get('', 'EValetController::eValet');
        $routes->post('all-evalet', 'EValetController::allEValet');
        $routes->post('store', 'EValetController::submitForm');
        $routes->post('edit', 'EValetController::edit');
        $routes->delete('delete', 'EValetController::delete');
    });

    $routes->group('restaurant', function ($routes) {
        $routes->get('', 'RestaurantController::restaurant');
        $routes->post('all-restaurant', 'RestaurantController::allRestaurant');
        $routes->post('store-restaurant', 'RestaurantController::storeRestaurant');
        $routes->post('edit-restaurant', 'RestaurantController::editRestaurant');
        $routes->delete('delete-restaurant', 'RestaurantController::deleteRestaurant');

        $routes->post('menu-categories-by-restaurant', 'RestaurantController::menuCategoriesByRestaurant');
        $routes->post('get-menu-items', 'RestaurantController::getMenuItems');

        $routes->group('menu-category', function ($routes) {
            $routes->get('', 'RestaurantController::menuCategory');
            $routes->post('all-menu-category', 'RestaurantController::allMenuCategory');
            $routes->post('store-menu-category', 'RestaurantController::storeMenuCategory');
            $routes->post('edit-menu-category', 'RestaurantController::editMenuCategory');
            $routes->delete('delete-menu-category', 'RestaurantController::deleteMenuCategory');
        });

        $routes->group('meal-type', function ($routes) {
            $routes->get('', 'RestaurantController::mealType');
            $routes->post('all-meal-type', 'RestaurantController::allMealType');
            $routes->post('store-meal-type', 'RestaurantController::storeMealType');
            $routes->post('edit-meal-type', 'RestaurantController::editMealType');
            $routes->delete('delete-meal-type', 'RestaurantController::deleteMealType');
        });

        $routes->group('menu-item', function ($routes) {
            $routes->get('', 'RestaurantController::menuItem');
            $routes->post('all-menu-item', 'RestaurantController::allmenuItem');
            $routes->post('store-menu-item', 'RestaurantController::storeMenuItem');
            $routes->post('edit-menu-item', 'RestaurantController::editMenuItem');
            $routes->delete('delete-menu-item', 'RestaurantController::deleteMenuItem');
        });

        $routes->group('order', function ($routes) {
            $routes->get('', 'RestaurantController::order');
            $routes->post('all-order', 'RestaurantController::allOrder');
            $routes->post('place-order', 'RestaurantController::placeOrder');
            $routes->post('edit-order', 'RestaurantController::editOrder');
            $routes->delete('delete-order', 'RestaurantController::deleteOrder');
        });

        // $routes->group('table', function ($routes) {
        //     $routes->get('', 'RestaurantTableController::table');
        //     $routes->post('all-tables', 'RestaurantTableController::allTables');
        //     $routes->post('store', 'RestaurantTableController::store');
        //     $routes->post('edit', 'RestaurantTableController::edit');
        //     $routes->delete('delete', 'RestaurantTableController::delete');
        // });

        $routes->group('reservation-slot', function ($routes) {
            $routes->get('', 'RestaurantReservationSlotController::reservationSlot');
            $routes->post('all-reservation-slots', 'RestaurantReservationSlotController::allReservationSlots');
            $routes->post('store', 'RestaurantReservationSlotController::store');
            $routes->post('edit', 'RestaurantReservationSlotController::edit');
            $routes->delete('delete', 'RestaurantReservationSlotController::delete');
        });

        $routes->group('reservation', function ($routes) {
            $routes->get('', 'RestaurantReservationController::reservation');
            $routes->post('all-reservations', 'RestaurantReservationController::allReservations');
        });
    });

    $routes->group('asset', function ($routes) {
        $routes->group('category', function ($routes) {
            $routes->get('', 'AssetCategoryController::assetCategory');
            $routes->post('all-asset-categories', 'AssetCategoryController::allAssetCategories');
            $routes->post('store', 'AssetCategoryController::store');
            $routes->post('edit', 'AssetCategoryController::edit');
            $routes->delete('delete', 'AssetCategoryController::delete');
        });

        $routes->group('asset', function ($routes) {
            $routes->get('', 'AssetController::asset');
            $routes->post('all-assets', 'AssetController::allAssets');
            $routes->post('store', 'AssetController::store');
            $routes->post('edit', 'AssetController::edit');
            $routes->delete('delete', 'AssetController::delete');
            $routes->post('asset-by-categories', 'AssetController::assetByCategories');
        });

        $routes->group('room-asset', function ($routes) {
            $routes->get('', 'roomAssetController::roomAsset');
            $routes->post('all-room-assets', 'roomAssetController::allRoomAssets');
            $routes->post('store', 'roomAssetController::store');
            $routes->post('edit', 'roomAssetController::edit');
            // $routes->delete('delete', 'roomAssetController::delete');
        });

        $routes->group('reservation-asset', function ($routes) {
            $routes->get('', 'ReservationAssetController::reservationAsset');
            $routes->post('all-reservation-assets', 'ReservationAssetController::allReservationAssets');
            // $routes->post('store', 'ReservationAssetController::store');
            $routes->post('edit', 'ReservationAssetController::edit');
        });
    });

    $routes->group('gallery', function ($routes) {
        $routes->get('', 'GalleryController::gallery');
        $routes->post('all-images', 'GalleryController::allImages');
        $routes->post('store', 'GalleryController::store');
        $routes->post('edit', 'GalleryController::edit');
        $routes->delete('delete', 'GalleryController::delete');
    });

    $routes->group('upgrade-room-request', function ($routes) {
        $routes->get('', 'UpgradeRoomRequestController::upgradeRoomRequest');
        $routes->post('all-requests', 'UpgradeRoomRequestController::allRequests');
        $routes->post('update-status', 'UpgradeRoomRequestController::updateStatus');
    });

    $routes->group('billing', function ($routes) {
        $routes->get('', 'BillingController::billing');
        $routes->post('post-or-payment', 'BillingController::postOrPayment');
        $routes->post('load-windows-data', 'BillingController::loadWindowsData');
        $routes->post('move-transaction', 'BillingController::moveTransaction');
        $routes->post('delete-transaction', 'BillingController::deleteTransaction');
        $routes->post('delete-window', 'BillingController::deleteWindow');
        $routes->get('preview-folio', 'BillingController::previewFolio');
    });

    $routes->group('deposit', function ($routes) {
        $routes->get('', 'DepositController::deposit');
    });

    $routes->group('user', function ($routes) {
        $routes->post('confirm-password', 'UserController::confirmPassword');
    });

    // ABUBAKAR CODE (END)
});


//Web Link Reservation
$routes->group("webline", function ($routes) {
    $routes->match(['get'], 'ReservationDetail/(:any)', 'ApplicatioController::webLineReservation/$1');
});

$routes->match(['post'], '/imageUpload', 'ApplicatioController::imageUpload');
$routes->match(['post'], '/croppingImage', 'ApplicatioController::croppingImage');
$routes->match(['post'], '/getActiveUploadImages', 'ApplicatioController::getActiveUploadImages');
$routes->match(['post'], '/deleteUploadImages', 'ApplicatioController::deleteUploadImages');
$routes->match(['post'], '/updateCustomerData', 'ApplicatioController::updateCustomerDetail');
$routes->match(['post'], '/checkStatusUploadFiles', 'ApplicatioController::checkStatusUploadFiles');
$routes->match(['post'], '/updateVaccineReport', 'ApplicatioController::updateVaccineReport');
$routes->match(['post'], '/getVaccinUploadImages', 'ApplicatioController::getVaccinUploadImages');
$routes->match(['post'], '/updateSignatureReserv', 'ApplicatioController::updateSignatureReserv');
$routes->match(['post'], '/confirmPrecheckinStatus', 'ApplicatioController::confirmPrecheckinStatus');
$routes->match(['get'], '/reservationCheckin', 'ApplicatioController::reservationCheckin');
$routes->get('autherropage', 'ApplicatioController::AuthErroPage');

$routes->group('laundry-amenities', function ($routes) {
    $routes->get('check-unattended-items', 'LaundryAmenitiesController::checkUnattendedItems');
});

$routes->get('evalet/qr/(:segment)', 'EValetController::qr/$1');

$routes->post('webhook', 'PaymentController::webhook');

$routes->match(['post'], '/uploadReservationAttachments', 'ReservationController::uploadReservationAttachments',["filter" => "noauth"]);
