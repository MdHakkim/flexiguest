<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>
<?= $this->include('Layout/image_modal') ?>

<style>
.priceDetails {
    display: none;
}

.ps__rail-x,
.ps__rail-y {
    opacity: 0.6 !important;
}
</style>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Facility /</span> Amenities Requests
        </h4>
        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->

            <div class="container-fluid p-3">

                <div class="row">
                    <div class="col-md-3 mt-1 mb-3">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary amenityOrderRequest">
                                <i class="bx bx-plus me-md-2"></i><span class="d-md-inline-block d-none">Add
                                    Request</span>
                            </button>
                        </div>
                    </div>
                </div>

                <form class="dt_adv_search" method="POST">
                    <div class="border rounded pt-4 p-3">
                        <div class="row g-3">
                            <div class="col-4 col-sm-6 col-lg-4">

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4" style="text-align: right;"><b>Guest
                                            Name:</b></label>
                                    <div class="col-md-8">
                                        <input type="text" id="S_CUST_FULL_NAME" name="S_CUST_FULL_NAME"
                                            class="form-control dt-input dt-full-name" data-column="0" placeholder="" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4" style="text-align: right;"><b>Requested
                                            On:</b></label>
                                    <div class="col-md-8">
                                        <input type="text" id="S_LAO_CREATED_AT" name="S_LAO_CREATED_AT"
                                            class="form-control" placeholder="" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-4 col-sm-6 col-lg-4">

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4" style="text-align: right;"><b>Resvn
                                            No:</b></label>
                                    <div class="col-md-8">
                                        <input type="text" id="S_RESV_NO" name="S_RESV_NO" class="form-control dt-input"
                                            placeholder="" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4" style="text-align: right;"><b>Room
                                            No:</b></label>
                                    <div class="col-md-8">
                                        <input type="text" id="S_RM_NO" name="S_RM_NO" class="form-control dt-input"
                                            placeholder="" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-4 col-sm-6 col-lg-4">

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4" style="text-align: right;"><b>Max
                                            Total:</b></label>
                                    <div class="col-md-8">
                                        <input type="number" id="S_LAO_TOTAL_PAYABLE" name="S_LAO_TOTAL_PAYABLE"
                                            min="0.00" step="0.50" class="form-control dt-input" placeholder="" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4" style="text-align: right;"><b>Paymemt
                                            Status:</b></label>
                                    <div class="col-md-8">
                                        <select id="S_LAO_PAYMENT_STATUS" name="S_LAO_PAYMENT_STATUS"
                                            class="form-select select2 dt-input dt-mem-type" data-allow-clear="true">
                                            <option value="">View All</option>
                                            <option value="UnPaid">UnPaid</option>
                                            <option value="Payment Initiated">Payment Initiated</option>
                                            <option value="Payment Processing">Payment Processing</option>
                                            <option value="Paid">Paid</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row g-3">

                            <div class="col-8 col-sm-6 col-lg-8">

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-2"
                                        style="text-align: right;"><b>Products:</b></label>
                                    <div class="col-md-10">
                                        <select id="S_PRODUCTS" name="S_PRODUCTS[]" class="select2 form-select"
                                            multiple>
                                            <?= $productOptions ?>
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="col-4 col-sm-6 col-lg-4">

                                <div class="row mb-3">
                                    <div class="col-md-12 text-end">
                                        <button type="button" class="btn btn-primary submitAdvSearch">
                                            <i class='bx bx-search'></i>&nbsp;
                                            Search
                                        </button>&nbsp;
                                        <button type="button" class="btn btn-secondary clearAdvSearch">Clear</button>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </form>

                <div class="amenities_requests_div table-responsive text-nowrap">
                    <table id="amenities_requests" class="table border-top table-hover table-striped">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Order ID</th>
                                <th class="all">Room No.</th>
                                <th>Reservation No</th>
                                <th class="all">Guest Name</th>
                                <th>Total</th>
                                <th>Requested On</th>
                                <th class="all">Payment Status</th>
                                <th class="all">Action</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>

        <!--/ Multilingual -->
    </div>
    <!-- / Content -->

    <!-- Amenities Order Details window -->
    <div class="modal fade" id="amenityOrderDetailsWindow" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="amenityOrderDetailsWindowLabel">Products Requested in Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="table-responsive text-nowrap">
                            <table id="amenity_order_details"
                                class="dt-responsive table table-striped table-bordered display nowrap">
                                <thead>
                                    <tr>
                                        <th class="all">Product Name</th>
                                        <th class="all text-center">Quantity</th>
                                        <th class="text-center">Unit Rate</th>
                                        <th class="all text-center">Total Amount</th>
                                        <th class="all text-center">Delivery Status</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Amenities Order Details window end -->

    <!-- Modal Window -->

    <div class="modal fade" id="amenityOrderRequestWindow" tabindex="-1"
        aria-labelledby="amenityOrderRequestWindowLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="amenityOrderRequestWindowLabel">Add New Amenities Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="bs-stepper border rounded wizard-icons wizard-icons-example mt-2">
                        <div class="bs-stepper-header border-0 m-auto" style="max-width: 750px;">
                            <div class="step" data-target="#account-details">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-icon">
                                        <svg viewBox="0 0 54 54">
                                            <use
                                                xlink:href="<?= base_url() ?>/assets/svg/icons/form-wizard-account.svg#wizardAccount">
                                            </use>
                                        </svg>
                                    </span>
                                    <span class="bs-stepper-label">Guest / Reservation Details</span>
                                </button>
                            </div>
                            <div class="line">
                                <i class="bx bx-chevron-right"></i>
                            </div>
                            <div class="step" data-target="#select-products">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-icon">
                                        <svg viewBox="0 0 58 54">
                                            <use
                                                xlink:href="<?= base_url() ?>/assets/svg/icons/wizard-checkout-cart.svg#wizardCart">
                                            </use>
                                        </svg>
                                    </span>
                                    <span class="bs-stepper-label">Products</span>
                                </button>
                            </div>
                            <div class="line">
                                <i class="bx bx-chevron-right"></i>
                            </div>
                            <div class="step" data-target="#review-submit">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-icon">
                                        <svg viewBox="0 0 54 54">
                                            <use
                                                xlink:href="<?= base_url() ?>/assets/svg/icons/form-wizard-submit.svg#wizardSubmit">
                                            </use>
                                        </svg>
                                    </span>
                                    <span class="bs-stepper-label">Review & Submit</span>
                                </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content">
                            <form id="amenityOrderRequestForm" enctype="multipart/form-data" novalidate
                                onSubmit="return false">
                                <!-- Account Details -->
                                <div id="account-details" class="content">
                                    <div class="content-header mb-3">
                                        <h6 class="mb-0">Guest / Reservation Details</h6>
                                        <small>Select the Guest / Reservation Details.</small>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row mb-3 offset-md-3">
                                            <label for="html5-text-input" class="col-form-label col-md-3"><b>Select
                                                    Reservation *</b></label>
                                            <div class="col-md-5">
                                                <select id="LAO_RESERVATION_ID" name="LAO_RESERVATION_ID"
                                                    class="select2 form-select form-select-lg" data-allow-clear="true"
                                                    required>
                                                    <?= $reservationOptions ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3 offset-md-3">
                                            <label for="html5-text-input" class="col-form-label col-md-3"><b>Select
                                                    Room No *</b></label>
                                            <div class="col-md-5">
                                                <select id="LAO_ROOM_ID" name="LAO_ROOM_ID"
                                                    class="select2 form-select form-select-lg" data-allow-clear="true">
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3 offset-md-3">
                                            <label for="html5-text-input" class="col-form-label col-md-3"><b>Select
                                                    Guest*</b></label>
                                            <div class="col-md-5">
                                                <select id="LAO_CUSTOMER_ID" name="LAO_CUSTOMER_ID"
                                                    class="select2 form-select form-select-lg" data-allow-clear="true">
                                                </select>
                                            </div>
                                        </div>


                                        <div class="d-flex col-12 justify-content-between">
                                            <button class="btn btn-label-secondary btn-prev" disabled>
                                                <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                                                <span class="d-none d-sm-inline-block">Previous</span>
                                            </button>
                                            <button class="btn btn-primary confirmReservDetails">
                                                <span class="d-none d-sm-inline-block me-sm-1">Next</span>
                                                <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
                                            </button>
                                        </div>

                                    </div>
                                </div>
                                <!-- Products -->
                                <div id="select-products" class="content">
                                    <div class="row">
                                        <!-- Cart left -->
                                        <div class="col-xl-8 mb-3 mb-xl-0">
                                            <!-- Shopping bag -->
                                            <div class="card mb-4 overflow-hidden selectedProductsDiv"
                                                style="height: auto;">
                                                <div class="card-header d-flex justify-content-between">
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                        data-bs-toggle="modal" data-bs-target="#selectProductsModal">
                                                        <i class="fa-solid fa-plus"></i>
                                                        Add Products
                                                    </button>
                                                    <div class="alert alert-primary mb-0" role="alert">
                                                        <h6 class="alert-heading mb-0 selectedProductsNum">(0) Products
                                                            selected</h6>
                                                    </div>
                                                </div>
                                                <div class="card-body vertical-scroll">
                                                    <ul class="list-group showSelectedProducts">
                                                        <!-- Selected Products here -->
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Cart right -->
                                        <div class="col-xl-4">
                                            <div class="border rounded p-3 mb-3 priceDetails">

                                                <!-- Price Details -->
                                                <h6>Price Details</h6>
                                                <dl class="row mb-0">
                                                    <dt class="col-6 fw-normal">Order Total</dt>
                                                    <dd class="col-6 text-end pDTotal">0.00</dd>

                                                    <dt class="col-6 fw-normal">Delivery Charges</dt>
                                                    <dd class="col-6 text-end text-muted">
                                                        <s>5.00</s> <span class="badge bg-label-success">Free</span>
                                                    </dd>

                                                    <hr />

                                                    <dt class="col-6">Total</dt>
                                                    <dd class="col-6 text-end fw-semibold mb-0 pDTotal">0.00</dd>
                                                </dl>

                                            </div>
                                            <div class="d-grid">
                                                <button class="btn btn-secondary btn-prev mb-3">
                                                    <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                                                    <span class="d-none d-sm-inline-block">Previous</span>
                                                </button>

                                                <button class="btn btn-primary confirmProducts"
                                                    disabled>Confirm</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Address -->

                                <!-- Review -->
                                <div id="review-submit" class="content">

                                    <div class="row mb-3">
                                        <div class="col-12 col-lg-8 offset-lg-2 text-center mb-3">
                                            <h5 class="mt-2">Review the Amenities Request & Submit</h5>
                                        </div>
                                        <!-- Confirmation details -->
                                        <div class="col-12">
                                            <ul class="list-group list-group-horizontal-md">
                                                <li class="list-group-item flex-fill">
                                                    <h6><i class="bx bx-hotel"></i> Reservation Details</h6>
                                                    <address id="resvDetails">
                                                    </address>
                                                </li>
                                                <li class="list-group-item flex-fill">
                                                    <h6><i class="bx bx-user"></i> Guest Details</h6>
                                                    <address id="guestDetails">
                                                    </address>
                                                </li>
                                                <li class="list-group-item flex-fill">
                                                    <h6><i class="bx bx-money"></i> Payment Method</h6>
                                                    <span class="fw-semibold paymentMethod">Pay at
                                                        Reception</span><br />
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <!-- Confirmation items -->
                                        <div class="col-xl-8 mb-3 mb-xl-0">
                                            <div class="card mb-4 overflow-hidden selectedProductsDiv"
                                                style="height: auto;">

                                                <div class="alert alert-primary col-md-4 m-3" role="alert">
                                                    <h6 class="alert-heading mb-1 selectedProductsNum">(0) Products
                                                        selected</h6>
                                                </div>

                                                <div class="card-body vertical-scroll pt-2">
                                                    <ul class="list-group showSelectedProducts">

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Confirmation total -->
                                        <div class="col-xl-4">
                                            <div class="border rounded p-3 priceDetails">
                                                <!-- Price Details -->
                                                <h6>Price Details</h6>
                                                <dl class="row mb-0">
                                                    <dt class="col-6 fw-normal">Order Total</dt>
                                                    <dd class="col-6 text-end pDTotal">0.00</dd>

                                                    <dt class="col-6 fw-normal">Delivery Charges</dt>
                                                    <dd class="col-6 text-end text-muted">
                                                        <s>5.00</s> <span class="badge bg-label-success">Free</span>
                                                    </dd>

                                                    <hr />

                                                    <dt class="col-6">Grand Total <br><small>(Inclusive of VAT)</small>
                                                    </dt>
                                                    <dd class="col-6 text-end fw-semibold mb-0 pDTotal">0.00</dd>
                                                </dl>
                                            </div>

                                        </div>
                                    </div>

                                    <!-- Choose Delivery -->
                                    <p class="mt-2">Choose Payment Method</p>
                                    <div class="row">
                                        <div class="col-md mb-2 mb-md-0">
                                            <div
                                                class="form-check custom-option custom-option-icon checked position-relative">
                                                <label class="form-check-label custom-option-content"
                                                    for="LAO_PAYMENT_METHOD1">
                                                    <span class="custom-option-body">
                                                        <i class="bx bx-credit-card-alt mb-2"></i>
                                                        <span class="custom-option-title">Credit/Debit Card</span>
                                                        <small>The Guest has paid via Credit/Debit Card.</small>
                                                    </span>
                                                    <input name="LAO_PAYMENT_METHOD"
                                                        class="form-check-input LAO_PAYMENT_METHOD" type="radio"
                                                        value="Credit/Debit card" id="LAO_PAYMENT_METHOD1" />
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md mb-2 mb-md-0">
                                            <div class="form-check custom-option custom-option-icon position-relative">
                                                <label class="form-check-label custom-option-content"
                                                    for="LAO_PAYMENT_METHOD2">
                                                    <span class="custom-option-body">
                                                        <i class="bx bx-mobile mb-2"></i>
                                                        <span class="custom-option-title">Samsung Pay</span>
                                                        <small>The Guest has paid using Samsung Pay.</small>
                                                    </span>
                                                    <input name="LAO_PAYMENT_METHOD"
                                                        class="form-check-input LAO_PAYMENT_METHOD" type="radio"
                                                        value="Samsung Pay" id="LAO_PAYMENT_METHOD2" />
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <div class="form-check custom-option custom-option-icon position-relative">
                                                <label class="form-check-label custom-option-content"
                                                    for="LAO_PAYMENT_METHOD3">
                                                    <span class="custom-option-body">
                                                        <i class="bx bx-money mb-2"></i>
                                                        <span class="custom-option-title">Paid at Reception</span>
                                                        <small>The Guest has paid at the reception.</small>
                                                    </span>
                                                    <input name="LAO_PAYMENT_METHOD"
                                                        class="form-check-input LAO_PAYMENT_METHOD" type="radio"
                                                        value="Pay at Reception" id="LAO_PAYMENT_METHOD3" checked="" />
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex col-12 justify-content-between mt-3">
                                        <button class="btn btn-primary btn-prev recheckProducts">
                                            <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                                            <span class="d-none d-sm-inline-block">Previous</span>
                                        </button>

                                        <label class="switch switch-lg mt-3">
                                            <input type="checkbox" name="LAO_PAYMENT_STATUS" class="switch-input"
                                                value="Paid">
                                            <input type="hidden" name="LAO_TOTAL_PAYABLE" id="LAO_TOTAL_PAYABLE"
                                                value="0.00" readonly />

                                            <span class="switch-toggle-slider">
                                                <span class="switch-on">
                                                    <i class="bx bx-check"></i>
                                                </span>
                                                <span class="switch-off">
                                                    <i class="bx bx-x"></i>
                                                </span>
                                            </span>
                                            <span class="switch-label">Paid</span>
                                        </label>

                                        <button class="btn btn-success btn-submit submitAddRequestForm">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <!-- /Modal window -->

    <!-- Modal -->
    <div class="modal fade" id="selectProductsModal" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable">
            <form class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="selectProductsModalTitle">Choose Products for Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        data-bs-target="#amenityOrderRequestWindow" data-bs-toggle="modal" aria-label="Close"></button>
                </div>
                <div class="modal-header mb-2">
                    <input type="text" id="search_products" name="search_products" class="form-control"
                        placeholder="Search for products here.." />
                </div>

                <div class="modal-body">
                    <div class="row showSearchProducts">
                        <!-- List Products Here -->
                    </div>
                </div>
                <div class="modal-footer mt-3">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal"
                        data-bs-target="#amenityOrderRequestWindow" data-bs-toggle="modal">
                        Close
                    </button>
                    <button type="button" class="btn btn-primary addSearchProducts" disabled>Add Selected
                        Products</button>
                </div>
            </form>
        </div>
    </div>

    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->
<script>
var reservation_id = 0;

var selectedProductDetails = [];

$(document).ready(function() {
    $('#amenities_requests').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/getAmenitiesRequestList') ?>',
            'type': 'POST',
            'data': function(d) {

                var formSerialization = $('.dt_adv_search').serializeArray();
                $(formSerialization).each(function(i, field) {
                    if (field.name == 'S_PRODUCTS[]')
                        d[field.name] = $('#S_PRODUCTS').val();
                    else
                        d[field.name] = field.value;
                });
            },
        },
        'columns': [{
                data: ''
            },
            {
                data: 'LAO_ID',
                "visible": false,
            },
            {
                data: 'RM_NO',
                className: "text-center"
            },
            {
                data: 'RESV_NO',
                className: "text-center"
            },
            {
                data: 'CUST_FULL_NAME'
            },
            {
                data: 'LAO_TOTAL_PAYABLE',
                className: "text-right"
            },
            {
                data: 'LAO_CREATED_AT'
            },
            {
                data: 'LAO_PAYMENT_STATUS',
                className: "text-center"
            },
            {
                data: null,
                className: "text-center",
                "orderable": false,
                render: function(data, type, row, meta) {
                    return (
                        '<div class="d-inline-block">' +
                        '<a href="javascript:;" class="btn btn-sm btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                        '<ul class="dropdown-menu dropdown-menu-end">' +
                        '<li><a href="javascript:;" data_sysid="' + data['LAO_ID'] +
                        '" class="dropdown-item text-info viewDetails">View Requested Products</a></li>' +
                        '</ul>' +
                        '</div>'
                    );
                }
            },
        ],
        columnDefs: [{
            width: "5%",
            className: 'control',
            responsivePriority: 1,
            orderable: false,
            targets: 0,
            searchable: false,
            render: function(data, type, full, meta) {
                return '';
            }
        }, {
            width: "10%"
        }, {
            width: "10%"
        }, {
            width: "15%"
        }, {
            width: "15%"
        }, {
            width: "10%"
        }, {
            width: "10%"
        }, {
            // Label
            targets: -2,
            width: "10%",
            className: "text-center",
            render: function(data, type, full, meta) {
                var $status_name = full['LAO_PAYMENT_STATUS'];
                var $status = {
                    'Paid': {
                        class: 'btn-label-success'
                    },
                    'UnPaid': {
                        class: 'btn-label-danger'
                    },
                    'Payment Processing': {
                        class: 'btn-label-info'
                    },
                    'Payment Initiated': {
                        class: 'btn-label-warning'
                    }
                };
                if (typeof $status[$status_name] === 'undefined') {
                    return data;
                }

                var $statButton = '<button type="button" class="btn ' + $status[
                        $status_name]
                    .class + ' dropdown-toggle"' +
                    'data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                    $status_name + '</button>' +
                    ' <ul class="dropdown-menu">' +
                    '     <li>' +
                    '      <h6 class="dropdown-header">Change Payment Status</h6>' +
                    '     </li><li><hr class="dropdown-divider"></li>';

                $.each($status, function(statText) {
                    if (statText == $status_name) $statButton += '';
                    else $statButton +=
                        '<li><a class="dropdown-item changeAmOrdStatus" data-amord-id="' +
                        full['LAO_ID'] +
                        '"' +
                        ' data-amord-new-stat="' + statText + '"' +
                        ' data-amord-old-stat="' + $status_name + '"' +
                        'href="javascript:void(0);">' + statText + '</a></li>';
                });

                $statButton += '  </ul>';
                return $statButton;
            },
            // render: function(data, type, full, meta) {
            //     var $status_name = full['LAO_PAYMENT_STATUS'];

            //     var $status = {
            //         'UnPaid': {
            //             class: 'bg-label-danger'
            //         },
            //         'Paid': {
            //             class: 'bg-label-success'
            //         }
            //     };
            //     if (typeof $status[$status_name] === 'undefined') {
            //         return data;
            //     }
            //     return (
            //         '<span class="badge rounded-pill ' +
            //         $status[$status_name].class +
            //         '">' +
            //         $status_name +
            //         '</span>'
            //     );
            // }
        }, {
            width: "25%"
        }],
        autowidth: true,
        "order": [
            [1, "desc"]
        ],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end">>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        language: {
            emptyTable: 'There are no amenities requests to display'
        },
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal({
                    header: function(row) {
                        var data = row.data();
                        return 'Details of request';
                    }
                }),
                type: 'column',
                renderer: function(api, rowIdx, columns) {
                    var data = $.map(columns, function(col, i) {

                        return col.title !==
                            '' // ? Do not show row in modal popup if title is blank (for check box)
                            ?
                            '<tr data-dt-row="' +
                            col.rowIndex +
                            '" data-dt-column="' +
                            col.columnIndex +
                            '">' +
                            '<td>' +
                            col.title +
                            ':' +
                            '</td> ' +
                            '<td>' +
                            col.data +
                            '</td>' +
                            '</tr>' :
                            '';
                    }).join('');

                    return data ? $('<table class="table"/><tbody />').append(data) : false;
                }
            }
        },
        initComplete: function() {
            // Adding role filter once table initialized
            this.api()
                .columns(6)
                .every(function() {
                    var column = this;
                    var select = $(
                            '<select id="UserRole" class="form-select"><option value=""> Select Status </option></select>'
                        )
                        .appendTo('.invoice_status')
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search(val ? val : '', true, false).draw();
                        });

                    column
                        .data()
                        .unique()
                        .sort()
                        .each(function(d, j) {
                            select.append('<option value="' + d +
                                '" class="text-capitalize">' + d + '</option>');
                        });
                });
        }
    });
});


// Change Request Order status
$(document).on('click', '.changeAmOrdStatus', function() {
    var orderId = $(this).attr('data-amord-id');

    var current_status = $(this).attr('data-amord-old-stat');
    var new_status = $(this).attr('data-amord-new-stat');

    bootbox.confirm({
        message: "Are you sure you want to change the payment status from '" + current_status +
            "' to '" +
            new_status + "' for this request?",
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function(result) {
            if (result) {
                $.ajax({
                    url: '<?php echo base_url('/updateAmenityOrder') ?>',
                    type: "post",
                    data: {
                        sysid: orderId,
                        new_status: new_status
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    dataType: 'json',
                    success: function(respn) {
                        var response = respn['SUCCESS'];
                        if (response != '1') {
                            var ERROR = respn['RESPONSE']['ERROR'];
                            var mcontent = '';
                            $.each(ERROR, function(ind, data) {

                                mcontent += '<li>' + data + '</li>';
                            });
                            showModalAlert('error', mcontent);
                        } else {
                            showModalAlert('success',
                                `<li>The Order has been updated successfully.</li>`
                            );

                            $('#amenities_requests').dataTable().fnDraw();
                        }
                    }
                });
            }
        }
    });

});


// Open Amenity Order Request Modal
$(document).on('click', '.amenityOrderRequest', function() {

    $('#amenityOrderRequestWindow').modal('show');
    selectedProductDetails = [];

    $('.showSelectedProducts').html("");
    $('.priceDetails').hide();
    $('.selectedProductsDiv').css('height', 'auto');
    $('.selectedProductsNum').html('(' + selectedProductDetails.length + ') Products selected');
    $('.confirmProducts').prop('disabled', true);

});

//Select Reservation in Add Request
$(document).on('change', '#LAO_RESERVATION_ID', function() {
    var resvId = $(this).val();

    var resvNo = $(this).find(":selected").text();
    var roomType = $(this).find(":selected").data('room-type');
    var roomNo = $(this).find(":selected").data('room-no');
    var roomId = $(this).find(":selected").data('room-id');

    var roomSelect = $('#LAO_ROOM_ID');
    roomSelect.empty().trigger("change");

    if (roomId != '') {
        roomSelect.append(new Option(roomNo, roomId, false, false));
    }

    fillCustomerSelect(resvId);

    $('#resvDetails').html('Reservation No: ' + resvNo + '<br/>Room No: ' + roomNo);
});

function fillCustomerSelect(resvId) {
    $.ajax({
        url: '<?php echo base_url('/showReservationCustomers') ?>',
        async: false,
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            sysid: resvId
        },
        dataType: 'json',
        success: function(respn) {
            var customerSelect = $('#LAO_CUSTOMER_ID');
            customerSelect.empty().trigger("change");

            $(respn).each(function(inx, data) {
                var newOption = new Option(data.text, data.id, false, false);
                newOption.setAttribute("data-address", data.address);
                newOption.setAttribute("data-city", data.city);
                newOption.setAttribute("data-state", data.state);
                newOption.setAttribute("data-country", data.country);
                newOption.setAttribute("data-email", data.email);
                newOption.setAttribute("data-phone", data.phone);
                newOption.setAttribute("data-postcode", data.postcode);

                customerSelect.append(newOption);
            });
            customerSelect.val(respn.length == 1 ? respn[0].id : null).trigger('change');
            // if only 1 guest, select by default
        }
    });
}

//Display Guest Details in Confirmation Tab
$(document).on('change', '#LAO_CUSTOMER_ID', function() {

    if ($(this).val()) {
        var guestData = '';
        guestData += $(this).find(":selected").text() + '<br/>';
        guestData += $(this).find(":selected").data('address') + ',<br/>';
        guestData += $(this).find(":selected").data('city') + ' ' + $(this).find(":selected").data('state') +
            '  ' + $(this).find(":selected").data('postcode') + ',<br/>';
        guestData += $(this).find(":selected").data('country') + '<br/>';
        guestData += $(this).find(":selected").data('phone') + ' | ' + $(this).find(":selected").data('email') +
            '<br/>';

        $('#guestDetails').html(guestData);
    }

});

//Select Reservation in Add Request
$(document).on('change', 'input[name=LAO_PAYMENT_METHOD]', function() {

    $('.paymentMethod').html($(this).val());
});

//Display Individual Products requested in an Order
$(document).on('click', '.viewDetails', function() {

    var orderId = $(this).attr('data_sysid');

    $('#amenityOrderDetailsWindow').modal('show');
    showAmenityOrderDetails(orderId);
    $('#amenityOrderDetailsWindowLabel').html('Products Requested in Order: ' + orderId);

    $('.addOrderProducts').attr({'data-selected-orderId': orderId});
});

function checkFileExist(urlToFile) {
    var xhr = new XMLHttpRequest();
    xhr.open('HEAD', urlToFile, false);
    xhr.send();

    if (xhr.status == "404") {
        return false;
    } else {
        return true;
    }
}

function showAmenityOrderDetails(orderId) {
    // Amenities Order Details List

    $('#amenity_order_details').DataTable({
        'processing': true,
        async: false,
        'serverSide': true,
        'serverMethod': 'post',
        'searching': true,
        'ajax': {
            'url': '<?php echo base_url('/amenityOrderDetailsView') ?>',
            'data': {
                "sysid": orderId
            }
        },
        'columns': [{
                data: 'PR_NAME',
                render: function(data, type, full, meta) {
                    var $user_img = '<?= base_url() ?>/' + full['PR_IMAGE'],
                        $name = full['PR_NAME'],
                        $post = full['PC_CATEGORY'];
                    var $output = '';

                    if (checkFileExist($user_img))
                        $output =
                        '<img onclick=\'displayImagePopup("' + full['PR_IMAGE'] +
                        '")\' src="' + $user_img + '" alt="' + $name +
                        '" class="">';
                    else {
                        //file not exists
                        var stateNum = Math.floor(Math.random() * 6);
                        var states = ['success', 'danger', 'warning', 'info', 'dark',
                            'primary', 'secondary'
                        ];
                        var $state = states[stateNum],
                            $name = full['PR_NAME'];

                        var $initials = $name.match(/\b\w/g) || [];
                        $initials = (($initials.shift() || '') + ($initials.pop() ||
                            '')).toUpperCase();
                        $output =
                            '<span class="avatar-initial bg-label-' +
                            $state +
                            '">' + $initials + '</span>';
                    }

                    // Creates full output for row
                    var $row_output =
                        '<div class="d-flex justify-content-start align-items-center">' +
                        '<div class="avatar-wrapper">' +
                        '<div class="avatar me-2">' +
                        $output +
                        '</div>' +
                        '</div>' +
                        '<div class="d-flex flex-column">' +
                        '<span class="emp_name text-truncate">' +
                        $name +
                        '</span>' +
                        '<small class="emp_post text-truncate text-muted">' +
                        $post +
                        '</small>' +
                        '</div>' +
                        '</div>';

                    return $row_output;
                }
            },
            {
                data: 'LAOD_QUANTITY',
                className: "text-center",
            },
            {
                data: 'UNIT_PRICE',
                className: "text-center",
            },
            {
                data: 'LAOD_AMOUNT',
                className: "text-center",
            },
            {
                data: 'LAOD_DELIVERY_STATUS',
                className: "text-center",
                render: function(data, type, full, meta) {
                    var $status_name = full['LAOD_DELIVERY_STATUS'];
                    var $status = {
                        'New': {
                            class: 'btn-label-primary'
                        },
                        'Processing': {
                            class: 'btn-label-warning'
                        },
                        'Delivered': {
                            class: 'btn-label-success'
                        },
                        'Rejected': {
                            class: 'btn-label-secondary'
                        },
                        'Cancelled': {
                            class: 'btn-label-danger'
                        },
                        'Acknowledged': {
                            class: 'btn-label-info'
                        },
                    };
                    if (typeof $status[$status_name] === 'undefined') {
                        return data;
                    }

                    var $statButton = '<button type="button" class="btn ' + $status[$status_name]
                        .class + ' dropdown-toggle"' +
                        'data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                        $status_name + '</button>' +
                        ' <ul class="dropdown-menu">' +
                        '     <li>' +
                        '      <h6 class="dropdown-header">Change Delivery Status</h6>' +
                        '     </li><li><hr class="dropdown-divider"></li>';

                    $.each($status, function(statText) {
                        if (statText == $status_name) $statButton += '';
                        else $statButton +=
                            '<li><a class="dropdown-item changeAmDetStatus" data-amdet-id="' +
                            full['LAOD_ID'] + '"' + ' data-amord-id="' + full['LAOD_ORDER_ID'] +
                            '"' +
                            ' data-amdet-new-stat="' + statText + '"' +
                            ' data-amdet-old-stat="' + $status_name + '"' +
                            'href="javascript:void(0);">' + statText + '</a></li>';
                    });

                    $statButton += '  </ul>';
                    return $statButton;
                }
            },
        ],
        columnDefs: [{
            // Avatar image/badge, Name and post
            width: "25%"
        }, {
            width: "15%"
        }, {
            width: "15%"
        }, {
            width: "10%"
        }, {
            width: "20%"
        }],
        "order": [
            [0, "asc"]
        ],
        responsive: true,
        destroy: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        language: {
            emptyTable: 'There are no products requested'
        }
    });
    
    // $("#amenity_order_details_wrapper .row:first").before(
    //     '<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary addOrderProducts" data-selected-orderId=""><i class="fa-solid fa-plus fa-lg"></i> Add New</button></div></div>'
    // );
}

// Change Request Detail Order status
$(document).on('click', '.changeAmDetStatus', function() {
    var sysid = $(this).attr('data-amdet-id');
    var orderId = $(this).attr('data-amord-id');

    var current_status = $(this).attr('data-amdet-old-stat');
    var new_status = $(this).attr('data-amdet-new-stat');

    bootbox.confirm({
        message: "Are you sure you want to change the delivery status from '" + current_status +
            "' to '" +
            new_status + "' for this request?",
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function(result) {
            if (result) {
                $.ajax({
                    url: '<?php echo base_url('/updateAmenityOrderDetails') ?>',
                    type: "post",
                    data: {
                        sysid: sysid,
                        new_status: new_status
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    dataType: 'json',
                    success: function(respn) {
                        var response = respn['SUCCESS'];
                        if (response != '1') {
                            var ERROR = respn['RESPONSE']['ERROR'];
                            var mcontent = '';
                            $.each(ERROR, function(ind, data) {

                                mcontent += '<li>' + data + '</li>';
                            });
                            showModalAlert('error', mcontent);
                        } else {
                            showModalAlert('success',
                                `<li>The Order Detail has been updated successfully.</li>`
                            );

                            showAmenityOrderDetails(orderId);
                        }
                    }
                });
            }
        }
    });

});

function showSearchProducts() {
    blockLoader('.showSearchProducts');

    var search = $('#search_products').val();
    $.ajax({
        url: '<?php echo base_url('/searchRequestProducts') ?>',
        type: "post",
        async: false,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            search: search,
            selectedProductDetails: JSON.stringify(selectedProductDetails)
        },
        // dataType:'json',
        success: function(respn) {
            $('.showSearchProducts').html(respn);
        }
    });
}

function showSelectedProducts(selection, displayType = 'cart') {
    blockLoader('.showSelectedProducts');

    $.ajax({
        url: '<?php echo base_url('/showRequestProducts') ?>',
        type: "post",
        async: false,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            selectedProductDetails: JSON.stringify(selection),
            displayType: displayType
        },
        // dataType:'json',
        success: function(respn) {

            $('.showSelectedProducts').html(respn);
        }
    });
}

function getSelectedProductSum() {
    var totalPrice = 0;
    $.each(selectedProductDetails, function(item) {
        totalPrice += parseFloat(selectedProductDetails[item].prodNum) * parseFloat(selectedProductDetails[item]
            .prodPrice);
    });

    return parseFloat(totalPrice).toFixed(2);
}

$(document).on('show.bs.modal', '#selectProductsModal', function() {
    $('#search_products').val("");
    showSearchProducts();
    $('.addSearchProducts').prop('disabled', true);
});

// on key up search product text field
$(document).on('keyup', '#search_products', function() {
    showSearchProducts();
});

$(document).on('click', '.checkSelectProduct', function() {
    if ($(this).is(':checked')) {
        $(this).closest('.checkSelectProductDiv').addClass('checked');
    } else {
        $(this).closest('.checkSelectProductDiv').removeClass('checked');
    }

    $('.addSearchProducts').prop('disabled', $('.checkSelectProduct:checkbox:checked').length == 0 ? true :
        false);
});

// Click 'Add Selected Products' from Search Modal
$(document).on('click', '.addSearchProducts', function() {

    var errorAlert = '';
    $('.checkSelectProduct:checkbox:checked').each(function() {

        var prodName = $(this).data('product-name');
        var prodPrice = parseFloat($(this).data('product-price'));
        var prodImg = $(this).data('product-image');
        var numField = $(this).closest('.checkSelectProductDiv').find('.numSelectProduct');
        var prodNum = parseInt(numField.val());
        var maxVal = parseInt(numField.attr('max'));
        var prodEscHrs = $(this).data('product-esc-hours');
        var prodEscMins = $(this).data('product-esc-mins');


        errorAlert += prodNum > maxVal ? '<li>We have only ' + maxVal + ' ' + prodName +
            ' left in stock. So you can order ' + maxVal + ' ' + prodName + ' only.</li>' : (!prodNum ?
                '<li>Please select at least one ' + prodName + '' : '');

        if (prodNum <= maxVal && prodNum > 0 && !checkArrayValue(selectedProductDetails, $(this).val(),
                'prodId'))
            selectedProductDetails.push({
                prodId: $(this).val(),
                prodPrice: prodPrice,
                prodName: prodName,
                prodNum: prodNum,
                prodImg: prodImg,
                maxVal: maxVal,
                prodEscHrs: prodEscHrs,
                prodEscMins: prodEscMins
            });

        //console.log("updArr", selectedProductDetails);
    });

    if (errorAlert != '')
        showModalAlert('error', errorAlert);
    else {
        showSelectedProducts(selectedProductDetails);
        $('.selectedProductsNum').html('(' + selectedProductDetails.length + ') Products selected');

        $('#selectProductsModal').modal('hide');
        $('#amenityOrderRequestWindow').modal('show');
        $('.selectedProductsDiv').css('height', selectedProductDetails.length >= 2 ? '350px' : 'auto');

        $('.priceDetails').show();

        var totalProdPrice = getSelectedProductSum();
        $('.pDTotal').html(totalProdPrice);
        $('#LAO_TOTAL_PAYABLE').val(totalProdPrice);

        $('.confirmProducts').prop('disabled', false);
    }
});

//On blur Selected Product quantity field
$(document).on('blur', '.selProdNum', function() {
    var new_qty = parseInt($(this).val());
    var selProdId = $(this).data('product-id');
    var selProdPrice = $(this).data('product-price');
    var maxVal = parseInt($(this).attr('max'));
    var prodName = $(this).data('product-name');
    var errorAlert = '';

    errorAlert = new_qty > maxVal ? '<li>We have only ' + maxVal + ' ' + prodName +
        ' left in stock. So you can order ' + maxVal + ' ' + prodName + ' only.</li>' : (!new_qty ?
            '<li>Please select at least one ' + prodName + '' : '');

    if (errorAlert != '') {
        $(this).focus();
        showModalAlert('error', errorAlert);
        $('.confirmProducts').prop('disabled', true);
    } else {
        $.each(selectedProductDetails, function(item) {
            if (selectedProductDetails[item].prodId == selProdId)
                selectedProductDetails[item].prodNum = new_qty;
        });

        $(this).closest('.selectedProduct').find('.selProdTotal').html(parseFloat(parseFloat(selProdPrice) *
            new_qty).toFixed(2));

        blockLoader('.priceDetails');
        var totalProdPrice = getSelectedProductSum();
        $('.pDTotal').html(totalProdPrice);

        $('.confirmProducts').prop('disabled', false);
    }

    //console.log("updArr", selectedProductDetails);
});

// Click 'X' button on selected products
$(document).on('click', '.removeSelectedProduct', function() {
    var selProd = $(this);
    bootbox.confirm({
        message: "Are you sure you want to remove '" + selProd.data("product-name") + "'?",
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function(result) {
            if (result) {

                blockLoader('.showSelectedProducts');

                selectedProductDetails = removeArrayValue(selectedProductDetails, selProd.data(
                    "product-id"), 'prodId');
                //console.log("updArr", selectedProductDetails);

                selProd.closest('.selectedProduct').remove();

                blockLoader('.priceDetails');
                var totalProdPrice = getSelectedProductSum();
                $('.pDTotal').html(totalProdPrice);

                if ($('.showSelectedProducts').children('.selectedProduct').length ==
                    0) // No products selected
                {
                    $('.priceDetails').hide();
                    $('.selectedProductsDiv').css('height', 'auto');
                    $('.confirmProducts').prop('disabled', true);
                }

                $('.selectedProductsNum').html('(' + selectedProductDetails.length +
                    ') Products selected');
            }
        }
    });

});

$(document).on('click', '.confirmProducts', function() {
    showSelectedProducts(selectedProductDetails, 'checkout');

});

$(document).on('click', '.recheckProducts', function() {
    showSelectedProducts(selectedProductDetails, 'cart');
});

// Submit Add Amenity Order form
$(document).on('click', '.submitAddRequestForm', function() {

    //alert(selectedProductDetails);

    var formSerialization = $('#amenityOrderRequestForm').serializeArray();

    var url = '<?php echo base_url('/insertAmenityOrder') ?>';
    $.ajax({
        url: url,
        type: "post",
        data: {
            formFields: formSerialization,
            selectedProductDetails: selectedProductDetails
        },
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        success: function(respn) {

            var response = respn['SUCCESS'];
            if (response != '1') {
                var ERROR = respn['RESPONSE']['ERROR'];
                var mcontent = '';
                $.each(ERROR, function(ind, data) {
                    mcontent += '<li>' + data + '</li>';
                });
                showModalAlert('error', mcontent);
            } else {
                var alertText = '<li>The new Amenities Request has been created</li>';
                showModalAlert('success', alertText);

                $('#amenityOrderRequestWindow').modal('hide');
                selectedProductDetails = [];

                $('#amenities_requests').dataTable().fnDraw();
            }

        }
    });

});


$(document).on('click', '.addOrderProducts', function() {

    var orderId = $(this).data('selected-orderId');
});




function checkArrayValue(arr, value, key) {

    var exists = 0;
    $.each(arr, function(item) {
        if (arr[item][key] == value) {
            exists = 1;
        }
    });

    return exists;
}

function removeArrayValue(arr, value, key) {

    var newArr = [];
    $.each(arr, function(item) {
        if (arr[item][key] == value) {
            return true;
        } else
            newArr.push(arr[item]);
    });

    return newArr;
}

$(function() {

    var bsRangePickerRange = $('#S_LAO_CREATED_AT');

    bsRangePickerRange.daterangepicker({
        autoUpdateInput: false,
        ranges: {
            Today: [moment(), moment()],
            Yesterday: [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month')
                .endOf('month')
            ]
        },
        locale: {
            cancelLabel: 'Clear'
        },
        opens: 'right'
    });

    bsRangePickerRange.on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MMM-yyyy') == picker.endDate.format('DD-MMM-yyyy') ?
            picker.startDate.format('DD-MMM-yyyy') : picker.startDate.format('DD-MMM-yyyy') +
            ' to ' + picker.endDate.format(
                'DD-MMM-yyyy'));
    });

    bsRangePickerRange.on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    bsRangePickerRange.keypress(function(event) {
        event.preventDefault();
    });

});


(function() {

    // Amenities Requests Advanced Search Functions Starts
    // --------------------------------------------------------------------
    const dt_adv_filter_table = $('#amenities_requests');

    $(document).on('click', '.submitAdvSearch', function() {

        blockLoader('.amenities_requests_div');
        dt_adv_filter_table.dataTable().fnDraw();
    });

    $(document).on('click', '.clearAdvSearch', function() {

        clearFormFields('.dt_adv_search');
        blockLoader('.amenities_requests_div');
        dt_adv_filter_table.dataTable().fnDraw();
    });

})();



// Display function toggleButton
<?php echo isset($toggleButton_javascript) ? $toggleButton_javascript : ''; ?>

// Display function clearFormFields
<?php echo isset($clearFormFields_javascript) ? $clearFormFields_javascript : ''; ?>

// Display function blockLoader
<?php echo isset($blockLoader_javascript) ? $blockLoader_javascript : ''; ?>
</script>

<?= $this->endSection() ?>