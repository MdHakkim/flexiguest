<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>

<style>
    .light-style .bs-stepper .step.crossed .bs-stepper-label {
        color: #677788 !important;
    }

    .bs-stepper .step.crossed .step-trigger::after {
        background-color: #d4d8dd;
    }

    .ql-snow .ql-editor {
        min-height: 5rem;
    }

    .light-style .bs-stepper:not(.wizard-modern) {
        box-shadow: unset;
    }

    .light-style .bs-stepper {
        background-color: unset;
    }

    #popModalWindow .modal-body {
        padding: 0.6rem;
    }

    .text-right {
        text-align: right !important;
    }

    .bs-stepper .bs-stepper-header {
        flex-wrap: wrap !important;
    }
</style>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Masters /</span> Transport Requests</h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid table-responsive" style="padding: 16px 16px 6px 16px">
                <table id="dataTable_view" class="dt-responsive table table-striped display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Status</th>
                            <th>Request ID</th>
                            <th>Reservation</th>
                            <th>Room</th>
                            <th>Guest Name</th>
                            <th>Transport Type</th>
                            <th>Payment Status</th>
                            <th>Travel Date & Time</th>
                            <th>Created At</th>
                            <th class="all">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <!--/ Multilingual -->
    </div>
    <!-- / Content -->

    <!-- Modal Window -->

    <div class="modal fade" id="popModalWindow" data-backdrop="static" data-keyboard="false" aria-lableledby="popModalWindowlable">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">Add Transport Request</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="bs-stepper wizard-numbered mt-2">
                            <div class="bs-stepper-header">

                                <div class="step" data-target="#in-house-information">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-label mt-1">
                                            <span class="bs-stepper-title">In-House Information</span>
                                        </span>
                                    </button>
                                </div>

                                <div class="line"></div>

                                <div class="step" data-target="#transfer-details">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-label mt-1">
                                            <span class="bs-stepper-title">Transfer Details</span>
                                        </span>
                                    </button>
                                </div>

                                <div class="line"></div>

                                <div class="step" data-target="#pick-up">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-label mt-1">
                                            <span class="bs-stepper-title">Pick Up</span>
                                        </span>
                                    </button>
                                </div>

                                <div class="line"></div>

                                <div class="step" data-target="#drop-off">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-label mt-1">
                                            <span class="bs-stepper-title">Drop Off</span>
                                        </span>
                                    </button>
                                </div>

                                <div class="line"></div>

                                <div class="step" data-target="#flight-information">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-label mt-1">
                                            <span class="bs-stepper-title">Flight Information</span>
                                        </span>
                                    </button>
                                </div>

                                <div class="line"></div>

                                <div class="step" data-target="#status-remarks-reminder">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-label mt-1">
                                            <span class="bs-stepper-title">Status/Remarks/Reminder</span>
                                        </span>
                                    </button>
                                </div>
                            </div>

                            <div class="bs-stepper-content">
                                <form id="submit-form" class="needs-validation" novalidate onSubmit="return false">
                                    <input type="hidden" name="id" class="form-control" />
                                    <input type="hidden" name="TR_CUSTOMER_ID" class="form-control" />
                                    <input type="hidden" name="TR_ROOM_ID" class="form-control" />

                                    <!-- in-house-information -->
                                    <div id="in-house-information" class="content">

                                        <div class="row g-3">

                                            <div class="col-md-6">
                                                <label class="form-label"><b>Reservation *</b></label>
                                                <select class="select2" name="TR_RESERVATION_ID" onchange="onChangeReservation()">
                                                    <option value="">Select Reservation</option>
                                                    <?php foreach ($reservations as $reservation) : ?>
                                                        <option value="<?= $reservation['RESV_ID'] ?>" data-customer_id="<?= $reservation['CUST_ID'] ?>" data-customer_name="<?= $reservation['CUST_FIRST_NAME'] . ' ' . $reservation['CUST_MIDDLE_NAME'] . ' ' . $reservation['CUST_LAST_NAME'] ?>" data-room_no="<?= $reservation['RESV_ROOM'] ?>" data-room_id="<?= $reservation['RM_ID'] ?>">

                                                            RES<?= $reservation['RESV_ID'] ?>
                                                            -
                                                            <?= $reservation['CUST_FIRST_NAME'] . ' ' . $reservation['CUST_MIDDLE_NAME'] . ' ' . $reservation['CUST_LAST_NAME'] ?>
                                                            -
                                                            <?= $reservation['CUST_ID'] ?>
                                                        </option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label"><b>Apartment No.</b></label>
                                                <input type="text" name="APARTMENT_NO" class="form-control" placeholder="Apartment No" readonly />
                                            </div>

                                            <div class="col-12 d-flex justify-content-between">
                                                <button class="btn btn-label-secondary btn-prev" disabled>
                                                    <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                </button>

                                                <button class="btn btn-primary btn-next">
                                                    <span class="align-middle d-sm-inline-block d-none me-sm-1 me-0">Next</span>
                                                    <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- transfer-details -->
                                    <div id="transfer-details" class="content">
                                        <div class="row g-3">

                                            <div class="col-md-6">
                                                <label class="form-label"><b>Travel Type *</b></label>
                                                <select class="select2" name="TR_TRAVEL_TYPE">
                                                    <option>One Way</option>
                                                    <option>Round Trip</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label"><b>Transport Type *</b></label>
                                                <select class="select2" name="TR_TRANSPORT_TYPE_ID">
                                                    <?php foreach ($transport_types as $transport_type) : ?>
                                                        <option value="<?= $transport_type['TT_ID'] ?>">
                                                            <?= $transport_type['TT_LABEL'] ?>
                                                        </option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label"><b>Travel Purpose *</b></label>
                                                <select class="select2" name="TR_TRAVEL_PURPOSE">
                                                    <option>Airport Drop Off</option>
                                                    <option>Airport Pickup</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label"><b>Guest Name *</b></label>
                                                <input type="text" name="TR_GUEST_NAME" class="form-control" placeholder="Guest name" />
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label"><b>Travel Date *</b></label>

                                                <div class="input-group">
                                                    <input type="text" name="TR_TRAVEL_DATE" placeholder="YYYY-MM-DD" class="form-control">
                                                    <span class="input-group-append">
                                                        <span class="input-group-text bg-light d-block">
                                                            <i class="fa fa-calendar"></i>
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label"><b>Travel Time *</b></label>
                                                <input type="time" name="TR_TRAVEL_TIME" class="form-control" />
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label"><b>Adults *</b></label>
                                                <input type="number" name="TR_ADULTS" class="form-control" value=1 oninput="updateTotalPassengers()" />
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label"><b>Children</b></label>
                                                <input type="number" name="TR_CHILDREN" class="form-control" oninput="updateTotalPassengers()" />
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label"><b>Total Passengers *</b></label>
                                                <input type="number" name="TR_TOTAL_PASSENGERS" class="form-control" value="0" readonly />
                                            </div>

                                            <!-- <div class="col-md-6">
                                                <label class="form-label"><b>Is child seat required</b></label>
                                                <select class="select2" name="TR_IS_CHILD_SEAT_REQUIRED">
                                                    <option value="0">No</option>
                                                    <option value="1">Yes</option>
                                                </select>
                                            </div> -->

                                            <div class="col-12 d-flex justify-content-between">
                                                <button class="btn btn-primary btn-prev">
                                                    <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                </button>

                                                <button class="btn btn-primary btn-next">
                                                    <span class="align-middle d-sm-inline-block d-none me-sm-1 me-0">Next</span>
                                                    <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- pick-up -->
                                    <div id="pick-up" class="content">
                                        <div class="row g-3">

                                            <div class="col-md-6">
                                                <label class="form-label"><b>Pickup Point *</b></label>
                                                <select class="select2" name="TR_PICKUP_POINT_ID">
                                                    <?php foreach ($pickup_points as $pickup_point) : ?>
                                                        <option value="<?= $pickup_point['PP_ID'] ?>">
                                                            <?= $pickup_point['PP_POINT'] ?>
                                                        </option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label"><b>Pickup Place</b></label>
                                                <input type="text" name="TR_PICKUP_PLACE" class="form-control" placeholder="Pickup Place" />
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label"><b>Pickup Instructions</b></label>
                                                <textarea name="TR_PICKUP_INSTRUCTIONS" class="form-control" placeholder="Pickup instructions..."></textarea>
                                            </div>

                                            <div class="col-12 d-flex justify-content-between">
                                                <button class="btn btn-primary btn-prev">
                                                    <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                </button>

                                                <button class="btn btn-primary btn-next">
                                                    <span class="align-middle d-sm-inline-block d-none me-sm-1 me-0">Next</span>
                                                    <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- drop-off -->
                                    <div id="drop-off" class="content">
                                        <div class="row g-3">

                                            <div class="col-md-6">
                                                <label class="form-label"><b>Dropoff Point *</b></label>
                                                <select class="select2" name="TR_DROPOFF_POINT_ID">
                                                    <?php foreach ($dropoff_points as $dropoff_point) : ?>
                                                        <option value="<?= $dropoff_point['DP_ID'] ?>">
                                                            <?= $dropoff_point['DP_POINT'] ?>
                                                        </option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label"><b>Dropoff Place</b></label>
                                                <input type="text" name="TR_DROPOFF_PLACE" class="form-control" placeholder="Dropoff Place" />
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label"><b>Dropoff Instructions</b></label>
                                                <textarea name="TR_DROPOFF_INSTRUCTIONS" class="form-control" placeholder="Dropoff instructions..."></textarea>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label"><b>Vehicle#</b></label>
                                                <input type="text" name="TR_VEHICLE_NO" class="form-control" placeholder="Vehicle#" />
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label"><b>Return Vehicle#</b></label>
                                                <input type="text" name="TR_RETURN_VEHICLE_NO" class="form-control" placeholder="Return Vehicle#" />
                                            </div>

                                            <div class="col-12 d-flex justify-content-between">
                                                <button class="btn btn-primary btn-prev">
                                                    <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                </button>

                                                <button class="btn btn-primary btn-next">
                                                    <span class="align-middle d-sm-inline-block d-none me-sm-1 me-0">Next</span>
                                                    <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- flight-information -->
                                    <div id="flight-information" class="content">
                                        <div class="row g-3">

                                            <div class="col-md-6">
                                                <label class="form-label"><b>Flight Carrier *</b></label>
                                                <select class="select2" name="TR_FLIGHT_CARRIER_ID" onchange="onChangeFlightCarrier()">
                                                    <option value="">Select Flight Carrier</option>
                                                    <?php foreach ($flight_carriers as $flight_carrier) : ?>
                                                        <option value="<?= $flight_carrier['FC_ID'] ?>" data-flight_code="<?= $flight_carrier['FC_FLIGHT_CODE'] ?>">
                                                            <?= $flight_carrier['FC_FLIGHT_CARRIER'] ?>
                                                        </option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label"><b>Flight Code</b></label>
                                                <input type="text" name="FLIGHT_CODE" class="form-control" placeholder="Flight code" readonly />
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label"><b>Flight Date</b></label>

                                                <div class="input-group">
                                                    <input type="text" name="TR_FLIGHT_DATE" placeholder="YYYY-MM-DD" class="form-control">
                                                    <span class="input-group-append">
                                                        <span class="input-group-text bg-light d-block">
                                                            <i class="fa fa-calendar"></i>
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label"><b>Flight Time</b></label>
                                                <input type="time" name="TR_FLIGHT_TIME" class="form-control" />
                                            </div>

                                            <div class="col-12 d-flex justify-content-between">
                                                <button class="btn btn-primary btn-prev">
                                                    <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                </button>

                                                <button class="btn btn-primary btn-next">
                                                    <span class="align-middle d-sm-inline-block d-none me-sm-1 me-0">Next</span>
                                                    <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- status-remarks-reminder -->
                                    <div id="status-remarks-reminder" class="content">
                                        <div class="row g-3">

                                            <div class="col-md-6">
                                                <label class="form-label"><b>Status</b></label>
                                                <select class="select2" name="TR_STATUS">
                                                    <option>New</option>
                                                    <option>In Progress</option>
                                                    <option>Closed</option>
                                                    <option>Rejected</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label"><b>Payment Method</b></label>
                                                <select class="select2" name="TR_PAYMENT_METHOD">
                                                    <option>Pay at Reception</option>
                                                    <option>Samsung Pay</option>
                                                    <option>Credit/Debit card</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label"><b>Payment Status</b></label>
                                                <select class="select2" name="TR_PAYMENT_STATUS">
                                                    <option>UnPaid</option>
                                                    <option>Paid</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6"></div>
                                            <!-- <div class="col-md-6">
                                                <label class="form-label"><b>Reminder Required</b></label>
                                                <select class="select2" name="TR_REMINDER_REQUIRED">
                                                    <option value="0">No</option>
                                                    <option value="1">Yes</option>
                                                </select>
                                            </div> -->

                                            <div class="col-md-12">
                                                <label class="form-label"><b>Remarks</b></label>
                                                <textarea name="TR_REMARKS" class="form-control" placeholder="Remarks..."></textarea>
                                            </div>

                                            <div class="col-12 d-flex justify-content-between">
                                                <button class="btn btn-primary btn-prev">
                                                    <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                </button>

                                                <button id="submitBtn" class="btn btn-success btn-submit" onClick="submitForm()">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>

                        <button type="button" id="submitBtn" onClick="submitForm()" class="btn btn-primary">
                            Save
                        </button>
                    </div> -->
                </div>
            </div>
        </div>

        <!-- /Modal window -->

        <div class="content-backdrop fade"></div>
    </div>

    <!-- Content wrapper -->
    <?= $this->endSection() ?>

    <?= $this->section("script") ?>
    <script>
        var form_id = '#submit-form';

        $(document).ready(function() {
            $('input[name="TR_TRAVEL_DATE"]').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
            });

            const wizard_form = $(".wizard-numbered")[0];
            if (typeof wizard_form !== undefined && wizard_form !== null) {

                const btn_next_list = [].slice.call(wizard_form.querySelectorAll('.btn-next'));
                const btn_prev_list = [].slice.call(wizard_form.querySelectorAll('.btn-prev'));
                const btn_submit = wizard_form.querySelector('.btn-submit');

                const form_stepper = new Stepper(wizard_form, {
                    linear: false
                });

                if (btn_next_list) {
                    btn_next_list.forEach(btn => {
                        btn.addEventListener('click', event => {
                            form_stepper.next();
                        });
                    });
                }

                if (btn_prev_list) {
                    btn_prev_list.forEach(btn => {
                        btn.addEventListener('click', event => {
                            form_stepper.previous();
                        });
                    });
                }
            }
        });

        function onChangeReservation() {
            let customer_id = $(`${form_id} select[name="TR_RESERVATION_ID"]`).find(":selected").data('customer_id');
            let customer_name = $(`${form_id} select[name="TR_RESERVATION_ID"]`).find(":selected").data('customer_name');
            let room_id = $(`${form_id} select[name="TR_RESERVATION_ID"]`).find(":selected").data('room_id');
            let room_no = $(`${form_id} select[name="TR_RESERVATION_ID"]`).find(":selected").data('room_no');

            $(`${form_id} input[name="TR_CUSTOMER_ID"]`).val(customer_id);
            $(`${form_id} input[name="TR_GUEST_NAME"]`).val(customer_name);
            $(`${form_id} input[name="TR_ROOM_ID"]`).val(room_id);
            $(`${form_id} input[name="APARTMENT_NO"]`).val(room_no);
        }

        function onChangeFlightCarrier() {
            let flight_code = $(`${form_id} select[name="TR_FLIGHT_CARRIER_ID"]`).find(":selected").data('flight_code');
            $(`${form_id} input[name="FLIGHT_CODE"]`).val(flight_code);
        }

        function updateTotalPassengers() {
            let adults = parseInt($(`${form_id} input[name="TR_ADULTS"]`).val());
            let children = parseInt($(`${form_id} input[name="TR_CHILDREN"]`).val());

            $(`${form_id} input[name="TR_TOTAL_PASSENGERS"]`).val(adults + children);
        }

        var compAgntMode = '';
        var linkMode = '';

        $(document).ready(function() {
            linkMode = 'EX';

            $('#dataTable_view').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': '<?php echo base_url('transport/transport-request/all-transport-requests') ?>'
                },
                'columns': [{
                        data: ''
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            let class_name = 'badge rounded-pill';

                            if (data['TR_STATUS'] == 'Closed')
                                class_name += ' bg-label-success';

                            else if (data['TR_STATUS'] == 'Rejected')
                                class_name += ' bg-label-danger';

                            else if (data['TR_STATUS'] == 'In Progress')
                                class_name += ' bg-label-info';
                            else
                                class_name += ' bg-label-warning';

                            return (`
                            <span class="${class_name}">${data['TR_STATUS']}</span>
                        `);
                        }
                    },
                    {
                        data: 'TR_ID'
                    },
                    {
                        data: 'TR_RESERVATION_ID'
                    },
                    {
                        data: 'RM_NO'
                    },
                    {
                        data: 'TR_GUEST_NAME'
                    },
                    {
                        data: 'TT_LABEL'
                    },
                    {
                        data: 'TR_PAYMENT_STATUS'
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return (`${data['TR_TRAVEL_DATE']} ${data['TR_TRAVEL_TIME']}`);
                        }
                    },
                    {
                        data: 'TR_CREATED_AT'
                    },
                    {
                        data: null,
                        className: "text-center",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            return (
                                `
                        <div class="d-inline-block">
                            <a href="javascript:;" title="Edit or Delete" class="btn btn-sm btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a href="javascript:;" data_id="${data['TR_ID']}" class="dropdown-item editWindow text-primary">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>
                                </li>

                                <div class="dropdown-divider"></div>
                                
                                <li>
                                    <a href="javascript:;" data_id="${data['TR_ID']}" class="dropdown-item text-danger delete-record">
                                        <i class="fa-solid fa-ban"></i> Delete
                                    </a>
                                </li>
                            </ul>
                        </div>
                    `);
                        }
                    },
                ],
                columnDefs: [{
                        width: "7%",
                        className: 'control',
                        responsivePriority: 1,
                        orderable: false,
                        targets: 0,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            return '';
                        }
                    },
                    // {
                    //     width: "15%"
                    // }, {
                    //     width: "10%"
                    // }, {
                    //     width: "20%"
                    // }, {
                    //     width: "20%"
                    // }, {
                    //     width: "15%"
                    // }
                ],
                "order": [
                    [2, "desc"]
                ],
                destroy: true,
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({
                            header: function(row) {
                                var data = row.data();
                                return 'Details of request# ' + data['TR_ID'];
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
                }

            });

            $("#dataTable_view_wrapper .row:first").before(
                '<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" onClick="addForm()"><i class="fa-solid fa-plus fa-lg"></i> Add</button></div></div>'
            );
        });

        // Show Add Rate Class Form
        function addForm() {
            resetForm();
            // $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
            $('#popModalWindowlabel').html('Add Transport Request');

            $('#popModalWindow').modal('show');
        }

        function resetForm() {
            let id = "submit-form";

            $(`#${id} input`).val('');
            $(`#${id} textarea`).val('');

            $(`#${id} input[name='TR_ADULTS']`).val(1);
            $(`#${id} input[name='TR_CHILDREN']`).val(0);
            $(`#${id} input[name='TR_TOTAL_PASSENGERS']`).val(1);
        }

        // Add New or Edit Rate Class submit Function
        function submitForm() {
            hideModalAlerts();
            let id = "submit-form";

            var fd = new FormData($(`#${id}`)[0]);

            $.ajax({
                url: '<?= base_url('transport/transport-request/store') ?>',
                type: "post",
                data: fd,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {

                    if (response['SUCCESS'] != 200) {
                        var mcontent = '';
                        $.each(response['RESPONSE']['REPORT_RES'], function(ind, data) {
                            mcontent += '<li>' + data + '</li>';
                        });

                        showModalAlert('error', mcontent);
                    } else {
                        var alertText = response['RESPONSE']['REPORT_RES']['msg'];

                        showModalAlert('success', alertText);

                        $('#popModalWindow').modal('hide');
                        $('#dataTable_view').dataTable().fnDraw();
                    }
                }
            });
        }

        // Show Edit Rate Class Form
        $(document).on('click', '.editWindow', function() {
            resetForm();

            let transport_request_id = $(this).attr('data_id');
            $('.dtr-bs-modal').modal('hide');

            let id = "submit-form";
            $(`#${id} input[name='id']`).val(transport_request_id);

            $('#popModalWindowlabel').html('Edit Transport Request');
            $('#popModalWindow').modal('show');

            var url = '<?php echo base_url('transport/transport-request/edit') ?>';
            $.ajax({
                url: url,
                type: "post",
                data: {
                    id: transport_request_id
                },
                dataType: 'json',
                success: function(respn) {
                    $(respn).each(function(inx, data) {


                        $.each(data, function(field, val) {
                            if (field == 'TR_TRAVEL_TIME')
                                val = val.replace('.0000000', '');

                            if ($(`#${id} input[name='${field}'][type!='file']`).length)
                                $(`#${id} input[name='${field}']`).val(val);

                            else if ($(`#${id} textarea[name='${field}']`).length)
                                $(`#${id} textarea[name='${field}']`).val(val);

                            else if ($(`#${id} select[name='${field}']`).length)
                                $(`#${id} select[name='${field}']`).val(val).trigger('change');
                        });
                    });

                    $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
                }
            });
        });

        // Delete Rate Class
        $(document).on('click', '.delete-record', function() {
            hideModalAlerts();
            $('.dtr-bs-modal').modal('hide');

            var id = $(this).attr('data_id');
            bootbox.confirm({
                message: "Are you sure you want to delete this record?",
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
                            url: '<?php echo base_url('transport/transport-request/delete') ?>',
                            type: "post",
                            data: {
                                id: id,
                                '_method': 'delete'
                            },
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response['SUCCESS'] != 200) {
                                    showModalAlert('error', response['RESPONSE']['REPORT_RES']['msg']);
                                } else {
                                    showModalAlert('success', response['RESPONSE']['REPORT_RES']['msg']);

                                    $('#dataTable_view').dataTable().fnDraw();
                                }
                            }
                        });
                    }
                }
            });
        });

        // bootstrap-maxlength & repeater (jquery)
        $(function() {
            var maxlengthInput = $('.bootstrap-maxlength'),
                formRepeater = $('.form-repeater');

            // Bootstrap Max Length
            // --------------------------------------------------------------------
            if (maxlengthInput.length) {
                /*maxlengthInput.each(function () {
                  $(this).maxlength({
                    warningClass: 'label label-success bg-success text-white',
                    limitReachedClass: 'label label-danger',
                    separator: ' out of ',
                    preText: 'You typed ',
                    postText: ' chars available.',
                    validate: true,
                    threshold: +this.getAttribute('maxlength')
                  });
                });*/
            }

            // Form Repeater
            // ! Using jQuery each loop to add dynamic id and class for inputs. You may need to improve it based on form fields.
            // -----------------------------------------------------------------------------------------------------------------

            if (formRepeater.length) {
                var row = 2;
                var col = 1;
                formRepeater.on('submit', function(e) {
                    e.preventDefault();
                });
                formRepeater.repeater({
                    show: function() {
                        var fromControl = $(this).find('.form-control, .form-select');
                        var formLabel = $(this).find('.form-label');

                        fromControl.each(function(i) {
                            var id = 'form-repeater-' + row + '-' + col;
                            $(fromControl[i]).attr('id', id);
                            $(formLabel[i]).attr('for', id);
                            col++;
                        });

                        row++;

                        $(this).slideDown();
                    },
                    hide: function(e) {
                        confirm('Are you sure you want to delete this element?') && $(this).slideUp(e);
                    },
                    isFirstItemUndeletable: true

                });
            }
        });
    </script>

    <?= $this->endSection() ?>