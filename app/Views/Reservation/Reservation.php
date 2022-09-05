<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/SuccessReport') ?>
<?= $this->include('Layout/ErrorReport') ?>


<style>
/* style comparison */


</style>



<style>
    .tooltip{
    z-index: 99999 !important;
}

.tooltip.top .tooltip-inner {
    background-color:red !important;
}
.tooltip.top .tooltip-arrow {
      border-top-color: red !important;
}
.active-tr {
    background-color: #d1e7ff !important;
    --bs-table-striped-bg: none;
}

#accompanyTd .activeTr td {
    background-color: #f0e0cc !important;
    color: #000 !important;
}

#combine-popup .text-right {
    text-align: right !important;
}

#combine-popup .tab-content {
    padding: 1rem 1.375rem 0 1.375rem;
}

#combine-popup .card {
    box-shadow: unset;
    margin-top: 0;
}

.sel2Container {
    display: inline-block !important;
}

.sel2Container .select2-container {
    width: 87% !important;
    float: left;
}

.selPickerDiv .show-tick {
    width: 100% !important;
}

.form-label {
    font-weight: bold;
}

.fc a[data-navlink] {
    pointer-events: none;
}

#dataTable_view .dataTables_empty {
    text-align: left !important;
    padding-left: 30% !important;
}

#Rate_info_length,
#Rate_info_info,
#Rate_info_paginate {
    display: none;
}

.Rate_Info_Div thead th {
    position: sticky;
    top: 0;
}

.Rate_Info_Div {
    overflow-y: auto;
    height: 110px;
}

.Rate_Info_Div thead th {
    position: sticky;
    top: 0;
}

.Rate_Info_Div table {
    border-collapse: collapse;
    width: 100%;
}

.Rate_Info_Div th {
    padding: 10px 15px;
    border: 2px solid #529432;
}

.nav-tabs .nav-item a {
    font-weight: bold;
}

#calendar {
      max-width: 1100px;
      margin: 40px auto;
      font-size: 14px;
    }
.fc-time{ display : none !important; } 
.fc-event-time { display: none }
    
    
.fc-time-grid-event.fc-short .fc-time,.fc-time-grid-event .fc-time{
    display: none !important;
}
    
.fc-time-grid .fc-content-skeleton {
  position: absolute;
  z-index: 3;
  top: 0;
  left: 0;
  right: 0;
  height: 100%; 
}

.fc-event-container .fc-timeline-event{
    background-color: #405974 !important;
    border-color: #405974 !important;
    color: rgb(255, 255, 255) !important;
   
    top: 3px !important;
}
.fc-timeline-event .fc-time, .fc-timeline-event .fc-title{
    padding: 4px !important;
    font-size: 13px !important;
}
  .fc-resource-area col.fc-main-col {
    width: 35% !important;
}
.tooltip
{
opacity: 1;
}
#errorModal{
  display: none;
}
#errorModal{
    position: fixed;
    top: 10px;
    right: 22px;
    z-index: 10000;
    width: 500px;
}
  
</style>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Reservations /</span> Reservation
            List</h4>
        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid p-3">

                <div class="row">
                    <div class="col-md-3 mt-1 mb-3"><button type="button" class="btn btn-primary"
                            onClick="addResvation()"><i class="fa-solid fa-plus fa-lg"></i> Add New</button></div>
                </div>
                <form class="dt_adv_search mb-2" method="POST">
                    <div class="border rounded p-3 mb-3">
                        <div class="row g-3">
                            <div class="col-4 col-sm-6 col-lg-4">
                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4"
                                        style="text-align: right;"><b>Name:</b></label>
                                    <div class="col-md-8">
                                        <input type="text" id="S_GUEST_NAME" name="S_GUEST_NAME"
                                            class="form-control dt-input" data-column="0" placeholder="" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4" style="text-align: right;"><b>First
                                            Name:</b></label>
                                    <div class="col-md-8">
                                        <input type="text" id="S_CUST_FIRST_NAME" name="S_CUST_FIRST_NAME"
                                            class="form-control dt-input" data-column="0" placeholder="" />

                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4"
                                        style="text-align: right;"><b>Company:</b></label>
                                    <div class="col-md-8">
                                        <input type="text" id="S_COMPNAME" name="S_COMPNAME"
                                            class="form-control dt-input" data-column="19" placeholder="" />
                                    </div>
                                </div>

                            </div>

                            <div class="col-4 col-sm-6 col-lg-4">

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4" style="text-align: right;"><b>Arrival
                                            From:</b></label>
                                    <div class="col-md-8">
                                        <input type="text" id="S_ARRIVAL_FROM" name="S_ARRIVAL_FROM"
                                            class="form-control dt-date" placeholder="" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4" style="text-align: right;"><b>Conf
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
                                        <input type="text" id="S_RESV_ROOM" name="S_RESV_ROOM"
                                            class="form-control dt-input" placeholder="" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-4 col-sm-6 col-lg-4">

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4" style="text-align: right;"><b>Arrival
                                            To:</b></label>
                                    <div class="col-md-8">
                                        <input type="text" id="S_ARRIVAL_TO" name="S_ARRIVAL_TO"
                                            class="form-control dt-date" data-column="16" placeholder="" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4" style="text-align: right;"><b>Search
                                            Type:</b></label>
                                    <div class="col-md-8">
                                        <select id="S_SEARCH_TYPE" name="S_SEARCH_TYPE" class="form-select dt-select"
                                            data-column="1">
                                            <option value="">View All</option>
                                            <option value="1">Due In</option>
                                            <option value="2">Due Out</option>
                                            <option value="3">Day Use</option>
                                            <option value="4">Checked In</option>
                                            <option value="5">Checked Out</option>
                                            <option value="6">No Shows</option>
                                            <option value="7">Cancelled</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12 text-end">
                                        <button type="button" class="btn btn-primary submitAdvSearch">
                                            <i class='bx bx-search'></i>&nbsp;
                                            Search
                                        </button>&nbsp;
                                        <button type="button" class="btn btn-info toggleAdvSearch">
                                            <i class='bx bxs-chevron-down'></i> Advanced
                                        </button>&nbsp;
                                        <button type="button" class="btn btn-secondary clearAdvSearch">Clear</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Advanced Search Fields -->

                    <div class="border rounded p-3 advanced_fields" style="display: none;">
                        <div class="row g-3">
                            <div class="col-4 col-sm-6 col-lg-4">
                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4" style="text-align: right;"><b>Contact
                                            No:</b></label>
                                    <div class="col-md-8">
                                        <input type="text" id="S_GUEST_PHONE" name="S_GUEST_PHONE"
                                            class="form-control dt-input" data-column="0" placeholder="" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4"
                                        style="text-align: right;"><b>Email:</b></label>
                                    <div class="col-md-8">
                                        <input type="text" id="S_CUST_EMAIL" name="S_CUST_EMAIL"
                                            class="form-control dt-input" data-column="0" placeholder="" />

                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4" style="text-align: right;"><b>Travel
                                            Agent:</b></label>
                                    <div class="col-md-8">
                                        <input type="text" id="S_AGENTNAME" name="S_AGENTNAME"
                                            class="form-control dt-input" data-column="19" placeholder="" />
                                    </div>
                                </div>

                            </div>

                            <div class="col-4 col-sm-6 col-lg-4">

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4" style="text-align: right;"><b>Departure
                                            From:</b></label>
                                    <div class="col-md-8">
                                        <input type="text" id="S_DEPARTURE_FROM" name="S_DEPARTURE_FROM"
                                            class="form-control dt-date" placeholder="" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4" style="text-align: right;"><b>Room
                                            Type:</b></label>
                                    <div class="col-md-8">
                                        <select id="S_RESV_RM_TYPE" name="S_RESV_RM_TYPE"
                                            class="select2 form-select dt-input" data-allow-clear="true">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-4 col-sm-6 col-lg-4">

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4" style="text-align: right;"><b>Departure
                                            To:</b></label>
                                    <div class="col-md-8">
                                        <input type="text" id="S_DEPARTURE_TO" name="S_DEPARTURE_TO"
                                            class="form-control dt-date" data-column="16" placeholder="" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4" style="text-align: right;"><b>Created
                                            On:</b></label>
                                    <div class="col-md-8">
                                        <input type="text" id="S_RESV_CREATE_DT" name="S_RESV_CREATE_DT"
                                            class="form-control dt-date" data-column="16" placeholder="" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4" style="text-align: right;"><b>Created
                                            By:</b></label>
                                    <div class="col-md-8">
                                        <select id="S_RESV_CREATE_UID" name="S_RESV_CREATE_UID"
                                            class="select2 form-select dt-input" data-allow-clear="true">
                                            <option value=""></option>
                                            <?php
                                                if($userList != NULL) {
                                                    foreach($userList as $userItem)
                                                    {
                                            ?> <option value="<?=$userItem['USR_ID']; ?>">
                                                <?=$userItem['USR_NAME']; ?>
                                            </option>
                                            <?php   }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </form>

                <div class="dataTable_view_div table-responsive text-nowrap">
                    <table id="dataTable_view" class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Reservation No</th>
                                <th>Guest Name</th>
                                <th>Room No.</th>
                                <th>Arrival Date</th>
                                <th>Departure Date</th>
                                <th>Status</th>
                                <th>Nights</th>
                                <th>No of Rooms</th>
                                <th>Room Type</th>
                                <th>Guest Email</th>
                                <th>Guest Mobile</th>
                                <th>Guest Phone</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
        <div id="triggCopyReserv"></div>
        <!--/ Multilingual -->
    </div>
    <!-- / Content -->

    <!-- Option window -->
    <div class="modal fade" id="optionWindow" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="optionWindowLable">Reservation Options</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="Accompany">
                        <div class="flxy_opt_btn text-center">
                            <button type="button" class="btn btn-primary accompany-guests">Accompanying</button>
                            <button type="button" onClick="reservExtraOption('ADO')" class="btn btn-primary">Add
                                On</button>
                            <button type="button" class="btn btn-primary cancel-reservation d-none" data_sysid=""
                                data_custId="">Cancel</button>
                            <button type="button" class="btn btn-primary show-activity-log">Changes</button>
                            <button type="button" class="btn btn-primary checkout-reservation"
                                onclick="reservationCheckout()">Checkout</button>
                            <button type="button" class="btn btn-primary web-link-btn">Docs</button>
                            <button type="button" class="btn btn-primary mt-2" id="fixedChargeButton" data_sysid=""
                                style="width: 135px;">Fixed Charges</button>
                            <button type="button" class="btn btn-primary mt-2" id="proformaButton" data_sysid=""
                                style="width: 135px;">Pro-Forma Folio</button>
                            <button type="button" class="btn btn-primary" onclick="getRateInfo()" id="rateInfoButton"
                                data_sysid="">Rate Info.</button>
                            <button type="button" class="btn btn-primary mt-2" id="registerCardButton" data_sysid=""
                                style="width: 135px;">Registration Card</button>
                            <button type="button" class="btn btn-primary reinstate-reservation d-none"
                                data_sysid="">Reinstate</button>
                            <button type="button" class="btn btn-primary shares-btn">Shares</button>
                            <button type="button" class="btn btn-primary" id="traceButton" data_sysid="">Traces</button>
                        </div>
                    </div>
                </div>
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div> -->
            </div>
        </div>
    </div>
    <!-- option window end -->

    <!-- Option window -->
    <div class="modal fade" id="Addon" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rateQueryWindowLable">Add On</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>
                <div class="modal-body">

                    <div id="">
                        <div id="flxy_add_content">
                            <p>Which of these reservation attributes do you want to copy?</p>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label col-md-12">Room Type</label>
                                    <select name="COPY_RM_TYPE" id="COPY_RM_TYPE" data-width="100%"
                                        class="selectpicker COPY_RM_TYPE" data-live-search="true">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check mt-3 p-0">
                                        <label class="switch">
                                            <input type="checkbox" class="switch-input copyReser" checked
                                                id="COPY_PAYMENT" method="PM" />
                                            <span class="switch-toggle-slider">
                                                <span class="switch-on">
                                                    <i class="bx bx-check"></i>
                                                </span>
                                                <span class="switch-off">
                                                    <i class="bx bx-x"></i>
                                                </span>
                                            </span>
                                        </label>
                                        <label class="form-check-lable flxy_lab_left"> Payment Method</label>
                                    </div>
                                    <div class="form-check mt-3 p-0">
                                        <label class="switch">
                                            <input type="checkbox" class="switch-input copyReser" checked
                                                id="COPY_SPECIALS" method="SP" />
                                            <span class="switch-toggle-slider">
                                                <span class="switch-on">
                                                    <i class="bx bx-check"></i>
                                                </span>
                                                <span class="switch-off">
                                                    <i class="bx bx-x"></i>
                                                </span>
                                            </span>
                                        </label>
                                        <label class="form-check-lable flxy_lab_left"> Specials</label>
                                    </div>
                                    <div class="form-check mt-3 p-0">
                                        <label class="switch">
                                            <input type="checkbox" class="switch-input copyReser" checked
                                                id="COPY_CUST_REF" method="CR" />
                                            <span class="switch-toggle-slider">
                                                <span class="switch-on">
                                                    <i class="bx bx-check"></i>
                                                </span>
                                                <span class="switch-off">
                                                    <i class="bx bx-x"></i>
                                                </span>
                                            </span>
                                        </label>
                                        <label class="form-check-lable flxy_lab_left"> Custom Reference</label>
                                    </div>
                                    <div class="form-check mt-3 p-0">
                                        <label class="switch">
                                            <input type="checkbox" class="switch-input copyReser" checked
                                                id="COPTY_ROUTING" method="RU" />
                                            <span class="switch-toggle-slider">
                                                <span class="switch-on">
                                                    <i class="bx bx-check"></i>
                                                </span>
                                                <span class="switch-off">
                                                    <i class="bx bx-x"></i>
                                                </span>
                                            </span>
                                        </label>
                                        <label class="form-check-lable flxy_lab_left"> Window/Room Routing Instr.
                                        </label>
                                    </div>
                                    <div class="form-check mt-3 p-0">
                                        <label class="switch">
                                            <input type="checkbox" class="switch-input copyReser" checked
                                                id="COPTY_ROUTING" method="CM" />
                                            <span class="switch-toggle-slider">
                                                <span class="switch-on">
                                                    <i class="bx bx-check"></i>
                                                </span>
                                                <span class="switch-off">
                                                    <i class="bx bx-x"></i>
                                                </span>
                                            </span>
                                        </label>
                                        <label class="form-check-lable flxy_lab_left"> Comments</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mt-3 p-0">
                                        <label class="switch">
                                            <input type="checkbox" class="switch-input copyReser" checked
                                                id="COPY_PACKAGE" method="PK" />
                                            <span class="switch-toggle-slider">
                                                <span class="switch-on">
                                                    <i class="bx bx-check"></i>
                                                </span>
                                                <span class="switch-off">
                                                    <i class="bx bx-x"></i>
                                                </span>
                                            </span>
                                        </label>
                                        <label class="form-check-lable flxy_lab_left"> Packages</label>
                                    </div>
                                    <div class="form-check mt-3 p-0">
                                        <label class="switch">
                                            <input type="checkbox" class="switch-input copyReser" checked
                                                id="COPY_ITEM_INV" method="IN" />
                                            <span class="switch-toggle-slider">
                                                <span class="switch-on">
                                                    <i class="bx bx-check"></i>
                                                </span>
                                                <span class="switch-off">
                                                    <i class="bx bx-x"></i>
                                                </span>
                                            </span>
                                        </label>
                                        <label class="form-check-lable flxy_lab_left"> Item Inventory</label>
                                    </div>
                                    <div class="form-check mt-3 p-0">
                                        <label class="switch">
                                            <input type="checkbox" class="switch-input copyReser" checked
                                                id="COPY_GUEST_NAME" method="GU" />
                                            <span class="switch-toggle-slider">
                                                <span class="switch-on">
                                                    <i class="bx bx-check"></i>
                                                </span>
                                                <span class="switch-off">
                                                    <i class="bx bx-x"></i>
                                                </span>
                                            </span>
                                        </label>
                                        <label class="form-check-lable flxy_lab_left"> Guest Name</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" style="text-align:right;">
                                    <button type="button" onClick="copyReservation()"
                                        class="btn btn-primary">Save</button>
                                    <button type="button" data-bs-dismiss="modal"
                                        class="btn btn-secondary">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div> -->
            </div>
        </div>
    </div>
    <!-- option window end -->

    <!-- Modal Window -->

    <div class="modal fade" id="reservationW" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reservationWlable">Reservation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="reservationForm" novalidate>
                        <div class="window-1" id="window1">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="hidden" name="RESV_STATUS" id="RESV_STATUS" class="form-control" />
                                    <label class="form-label">Arrival / Departure Date</label>
                                    <div class="input-group mb-3">
                                        <input type="text" id="RESV_ARRIVAL_DT" class="form-control RESV_ARRIVAL_DT"
                                            placeholder="DD-MM-YYYY">
                                        <span class="input-group-append">
                                            <span class="input-group-text bg-light d-block">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                        </span>
                                        <input type="text" id="RESV_DEPARTURE" class="form-control RESV_DEPARTURE"
                                            placeholder="DD-MM-YYYY">
                                        <span class="input-group-append">
                                            <span class="input-group-text bg-light d-block">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Nights / No of Rooms</label>
                                    <div class="input-group mb-3">
                                        <input type="number" id="RESV_NIGHT" class="form-control RESV_NIGHT" min="0"
                                            placeholder="night" />
                                        <input type="number" id="RESV_NO_F_ROOM" class="form-control RESV_NO_F_ROOM"
                                            min="1" placeholder="no of room" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Adults / Children</label>
                                    <div class="input-group mb-3">
                                        <input type="number" id="RESV_ADULTS" class="form-control RESV_ADULTS" min="1"
                                            placeholder="adults" />
                                        <input type="number" id="RESV_CHILDREN" class="form-control RESV_CHILDREN"
                                            min="0" value="0" placeholder="children" />
                                    </div>
                                </div>
                                <div class="col-md-3 mt-0">
                                    <label class="form-label">Guest Name</label>
                                    <div class="input-group mb-3">
                                        <select id="RESV_NAME" class="selectpicker RESV_NAME" data-live-search="true">
                                            <option value="">Select</option>
                                        </select>
                                        <button type="button" onClick="childReservation('C')"
                                            class="btn flxi_btn btn-sm btn-primary"><i class="fa fa-plus"
                                                aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-3 mt-0">
                                    <label class="form-label">Member Type</label>
                                    <div class="input-group sel2Container mb-3">
                                        <select id="RESV_MEMBER_TY_ADD" class="select2 form-select"
                                            data-allow-clear="true">
                                        </select>
                                        <button type="button" onClick="checkAddMem('window-1')"
                                            class="btn flxi_btn btn-sm btn-primary text-end"><i class="fa fa-plus"
                                                aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-3 mt-0">
                                    <label class="form-label">Company</label>
                                    <div class="input-group sel2Container mb-3">
                                        <select id="RESV_COMPANY_ADD" class="select2 form-select RESV_COMPANY"
                                            data-allow-clear="true">
                                        </select>
                                        <button type="button" onClick="companyAgentClick('COMPANY')"
                                            class="btn flxi_btn btn-sm btn-primary"><i class="fa fa-plus"
                                                aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-3 mt-0">
                                    <label class="form-label">Agent</label>
                                    <div class="input-group sel2Container mb-3">
                                        <select id="RESV_AGENT_ADD" class="select2 form-select RESV_AGENT"
                                            data-allow-clear="true">
                                        </select>
                                        <button type="button" onClick="companyAgentClick('AGENT')"
                                            class="btn flxi_btn btn-sm btn-primary"><i class="fa fa-plus"
                                                aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-3 mt-0">
                                    <label class="form-label">Block</label>
                                    <select id="RESV_BLOCK_ADD" data-width="100%" class="select2 form-select RESV_BLOCK"
                                        data-allow-clear="true">
                                    </select>
                                </div>
                                <div class="col-md-3 mt-0">
                                    <label class="form-label">Member No</label>
                                    <input type="text" id="RESV_MEMBER_NO" class="form-control RESV_MEMBER_NO"
                                        placeholder="member no" readonly />
                                </div>
                                <div class="col-md-3 mt-0">
                                    <label class="form-label">CORP NO</label>
                                    <input type="text" name="RESV_CORP_NO" id="RESV_CORP_NO" class="form-control"
                                        placeholder="CORP no" />
                                </div>
                                <div class="col-md-3 mt-0">
                                    <label class="form-label">IATA NO</label>
                                    <input type="text" name="RESV_IATA_NO" id="RESV_IATA_NO" class="form-control"
                                        placeholder="IATA no" />
                                </div>
                                <div class="col-md-3 flxi_ds_flx">
                                    <div class="form-check mt-4 me-3">
                                        <input class="form-check-input flxCheckBox" type="checkbox"
                                            id="RESV_CLOSED_CHK">
                                        <label class="form-label" for="RESV_CLOSED_CHK"> Closed </label>
                                    </div>
                                    <div class="form-check mt-4 me-3">
                                        <input class="form-check-input flxCheckBox" type="checkbox" value="N"
                                            id="RESV_DAY_USE_CHK">
                                        <!-- <input type="hidden" name="RESV_DAY_USE" id="RESV_DAY_USE" value="N" class="form-control" /> -->
                                        <label class="form-label" for="RESV_DAY_USE_CHK"> Day Use </label>
                                    </div>
                                    <div class="form-check mt-4">
                                        <input class="form-check-input flxCheckBox" type="checkbox" value="N"
                                            id="RESV_PSEUDO_CHK">
                                        <!-- <input type="hidden" name="RESV_PSEUDO" id="RESV_PSEUDO" value="N" class="form-control" /> -->
                                        <label class="form-label" for="RESV_PSEUDO_CHK"> Pseudo </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Rate Class</label>
                                    <select name="RESV_RATE_CLASS" id="RESV_RATE_CLASS" class="select2 form-select"
                                        data-allow-clear="true">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Rate Category</label>
                                    <select name="RESV_RATE_CATEGORY" id="RESV_RATE_CATEGORY"
                                        class="select2 form-select" data-allow-clear="true">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Rate Code</label>
                                    <select id="RESV_RATE_CODE" class="select2 form-select" data-allow-clear="true">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Room Class</label>
                                    <select name="RESV_ROOM_CLASS" id="RESV_ROOM_CLASS" class="select2 form-select"
                                        data-allow-clear="true">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div class="col-md-3 selPickerDiv">
                                    <label class="form-label">Features</label>
                                    <select id="RESV_FEATURE" class="selectpicker" multiple data-icon-base="bx"
                                        data-tick-icon="bx-check text-primary" data-live-search="true"
                                        data-allow-clear="true">
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Packages</label>
                                    <select id="RESV_PACKAGES" class="select2 form-select" data-allow-clear="true">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Purpose Of Stay</label>
                                    <select name="RESV_PURPOSE_STAY" id="RESV_PURPOSE_STAY" class="select2 form-select"
                                        data-allow-clear="true">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="window-2">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Guest Name</label>
                                    <div class="input-group mb-3">
                                        <select name="RESV_NAME" id="RESV_NAME"
                                            class="selectpicker RESV_NAME activeName" data-live-search="true" required>
                                            <option value="">Select</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Guest Name required can't empty.
                                        </div>
                                        <button type="button" onClick="childReservation('C')"
                                            class="btn flxi_btn btn-sm btn-primary"><i class="fa fa-plus"
                                                aria-hidden="true"></i></button>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Title / First Name</label>
                                    <div class="input-group">
                                        <select name="CUST_TITLE" id="CUST_TITLE" class="form-select"
                                            data-allow-clear="true" required>
                                            <option value="">Select</option>
                                            <option value="Mr.">Mr.</option>
                                            <option value="Ms.">Ms.</option>
                                            <option value="Shiekh.">Shiekh.</option>
                                            <option value="Shiekha.">Shiekha.</option>
                                            <option value="Dr.">Dr.</option>
                                            <option value="Ambassador.">Ambassador.</option>
                                            <option value="Madam Ambassadress">Madam Ambassadress</option>
                                            <option value="Prince.">Prince.</option>
                                            <option value="Princess.">Princess.</option>
                                            <option value="President">President</option>
                                            <option value="Prof.">Prof.</option>
                                            <option value="Minister.">Minister.</option>
                                            <option value="Admiral">Admiral</option>
                                            <option value="Lieutenant.">Lieutenant.</option>
                                            <option value="Consul.">Consul.</option>
                                        </select>
                                        <input type="text" name="CUST_FIRST_NAME" id="CUST_FIRST_NAME"
                                            class="form-control" placeholder="first name" readonly />
                                        <div class="invalid-feedback">
                                            Title required can't empty.
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Country</label>
                                    <select name="CUST_COUNTRY" id="CUST_COUNTRY" data-width="100%"
                                        class="selectpicker CUST_COUNTRY" data-live-search="true">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">VIP</label>
                                    <select name="CUST_VIP" id="CUST_VIP_ADD" class="select2 form-select CUST_VIP"
                                        data-allow-clear="true">
                                    </select>
                                </div>
                                <div class="col-md-3 mt-0">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="CUST_PHONE" id="CUST_PHONE" class="form-control"
                                        placeholder="phone" />
                                </div>
                                <div class="col-md-3 mt-0">
                                    <label class="form-label">Member Type</label>
                                    <div class="input-group sel2Container mb-3">
                                        <select name="RESV_MEMBER_TY" id="RESV_MEMBER_TY" class="select2 form-select"
                                            data-allow-clear="true">
                                        </select>
                                        <button type="button" onClick="checkAddMem('window-2')"
                                            class="btn flxi_btn btn-sm btn-primary text-end"><i class="fa fa-plus"
                                                aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-3 mt-0">
                                    <label class="form-label">Member No</label>
                                    <input type="text" name="RESV_MEMBER_NO" id="RESV_MEMBER_NO" class="form-control"
                                        placeholder="member no" readonly />
                                    <div class="invalidfx-feedback"></div>
                                </div>
                                <div class="col-md-3 mt-0">
                                    <label class="form-label">Company</label>
                                    <div class="input-group sel2Container mb-3">
                                        <select name="RESV_COMPANY" id="RESV_COMPANY"
                                            class="select2 form-select RESV_COMPANY" data-allow-clear="true">
                                        </select>
                                        <button type="button" onClick="companyAgentClick('COMPANY')"
                                            class="btn flxi_btn btn-sm btn-primary"><i class="fa fa-plus"
                                                aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-3 mt-0">
                                    <label class="form-label">Agent</label>
                                    <div class="input-group sel2Container mb-3">
                                        <select name="RESV_AGENT" id="RESV_AGENT" class="select2 form-select RESV_AGENT"
                                            data-allow-clear="true">
                                        </select>
                                        <button type="button" onClick="companyAgentClick('AGENT')"
                                            class="btn flxi_btn btn-sm btn-primary"><i class="fa fa-plus"
                                                aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-3 mt-0">
                                    <label class="form-label">Block</label>
                                    <select name="RESV_BLOCK" id="RESV_BLOCK" data-width="100%"
                                        class="select2 form-select RESV_BLOCK" data-allow-clear="true">
                                    </select>
                                </div>
                                <div class="col-md-3 mt-0">
                                    <label class="form-label">Guest Balance</label>
                                    <input type="text" name="RESV_GUST_BAL" value="0.00" readonly id="RESV_GUST_BAL"
                                        class="form-control" placeholder="Guest Balance" />
                                </div>
                                <div class="col-md-3"></div>
                            </div>
                            <div class="row">
                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <a href="#reservationDetail" class="nav-link active"
                                            data-bs-toggle="tab">Reservation Details</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#moreDetails" class="nav-link" data-bs-toggle="tab">More Fields</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="reservationDetail">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="hidden" name="RESV_FEATURE" id="RESV_FEATURE"
                                                    class="form-control" />
                                                <input type="hidden" name="RESV_ID" id="RESV_ID" class="form-control" />
                                                <label class="form-label">Arrival / Departure Date</label>
                                                <div class="input-group mb-3 flxy_fxcolm">
                                                    <div class="flxy_join ">
                                                        <div class="flxy_fixdate" required>
                                                            <input type="text" id="RESV_ARRIVAL_DT"
                                                                name="RESV_ARRIVAL_DT"
                                                                class="form-control RESV_ARRIVAL_DT"
                                                                placeholder="DD-MM-YYYY" required>
                                                            <span class="input-group-append">
                                                                <span class="input-group-text bg-light d-block">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                            </span>
                                                            <div class="invalid-feedback flxy_date_vald">Arrival Date
                                                                required can't empty.</div>
                                                        </div>

                                                    </div>
                                                    <div class="flxy_join">
                                                        <div class="flxy_fixdate">
                                                            <input type="text" id="RESV_DEPARTURE" name="RESV_DEPARTURE"
                                                                class="form-control RESV_DEPARTURE"
                                                                placeholder="DD-MM-YYYY" required>
                                                            <span class="input-group-append">
                                                                <span class="input-group-text bg-light d-block">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                            </span>
                                                        </div>
                                                        <div class="invalid-feedback flxy_date_vald">Departure Date
                                                            required can't empty.</div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Nights / No of Rooms</label>
                                                <div class="input-group mb-3">
                                                    <input type="number" name="RESV_NIGHT" id="RESV_NIGHT"
                                                        class="form-control RESV_NIGHT" placeholder="night" min="0"
                                                        required />
                                                    <input type="number" name="RESV_NO_F_ROOM" id="RESV_NO_F_ROOM"
                                                        class="form-control" placeholder="no of room" min="1"
                                                        required />
                                                </div>
                                                <div class="invalid-feedback">
                                                    Night required can't empty.
                                                </div>
                                                <div class="invalid-feedback">
                                                    No of room required can't empty.
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Adults / Children</label>
                                                <div class="input-group mb-3 flxy_fxcolm">
                                                    <div class="flxy_join">
                                                        <input type="number" name="RESV_ADULTS" id="RESV_ADULTS"
                                                            class="form-control" placeholder="adults" min="1"
                                                            required />
                                                        <div class="invalid-feedback">Adults required can't empty.</div>
                                                    </div>
                                                    <div class="flxy_join">
                                                        <input type="number" name="RESV_CHILDREN" id="RESV_CHILDREN"
                                                            class="form-control" placeholder="children" value="0"
                                                            min="0" />
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Room Type</label>
                                                <select name="RESV_RM_TYPE" id="RESV_RM_TYPE" data-width="100%"
                                                    class="select2 form-select RESV_RM_TYPE" data-allow-clear="true">
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Room</label>
                                                <select name="RESV_ROOM" id="RESV_ROOM" data-width="100%"
                                                    class="select2 form-select RESV_ROOM" data-allow-clear="true">
                                                    <option value="">Select</option>
                                                </select>
                                                <input type="hidden" name="RESV_ROOM_ID" id="RESV_ROOM_ID" value=""
                                                    readonly />
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Rate Code</label>
                                                <div class="input-group mb-3">
                                                    <input type="text" readonly name="RESV_RATE_CODE"
                                                        id="RESV_RATE_CODE" class="form-control" placeholder="rate"
                                                        required />
                                                    <button type="button" onClick="getRateQuery()"
                                                        class="btn flxi_btn btn-sm btn-primary"><i class="fa fa-plus"
                                                            aria-hidden="true"></i></button>
                                                </div>
                                                <div class="invalid-feedback"> Rate Code required can't empty.</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Rate</label>
                                                <input type="number" step="0.01" name="RESV_RATE" id="RESV_RATE"
                                                    class="form-control" placeholder="rate" min="0" required />
                                                <div class="invalid-feedback"> Rate required can't empty.</div>
                                            </div>
                                            <div class="col-md-3 mt-4">
                                                <label class="form-label"> Fixed Rate</label>
                                                <label class="switch">
                                                    <input type="checkbox" class="switch-input"
                                                        id="RESV_FIXED_RATE_CHK" />
                                                    <input type="hidden" name="RESV_FIXED_RATE" value="N"
                                                        id="RESV_FIXED_RATE" class="form-control" />
                                                    <span class="switch-toggle-slider">
                                                        <span class="switch-on">
                                                            <i class="bx bx-check"></i>
                                                        </span>
                                                        <span class="switch-off">
                                                            <i class="bx bx-x"></i>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Package</label>
                                                <div class="input-group mb-3">
                                                    <input type="text" readonly name="RESV_PACKAGE" id="RESV_PACKAGE"
                                                        class="form-control" placeholder="Packages" required />
                                                    <button type="button" onClick="getPackages()"
                                                        class="btn flxi_btn btn-sm btn-primary"><i class="fa fa-plus"
                                                            aria-hidden="true"></i></button>
                                                </div>
                                                <!-- <label class="form-label">Package</label>
                                                <select name="RESV_PACKAGES" id="RESV_PACKAGES" data-width="100%"
                                                    class="selectpicker RESV_PACKAGES" data-live-search="true">
                                                    <option value="">Select</option>
                                                </select> -->
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">ETA - C/O Time</label>
                                                <div class="flxi_flex">
                                                    <input type="time" name="RESV_ETA" id="RESV_ETA"
                                                        class="form-control" placeholder="estime Time" />
                                                    <input type="time" name="RESV_CO_TIME" id="RESV_CO_TIME"
                                                        class="form-control" placeholder="co time" />
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">RTC</label>
                                                <select name="RESV_RTC" id="RESV_RTC" data-width="100%"
                                                    class="selectpicker RESV_RTC" data-live-search="true">
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mt-2">
                                                <label class="form-label">Reservation Type</label>
                                                <select name="RESV_RESRV_TYPE" id="RESV_RESRV_TYPE"
                                                    class="select2 form-select" data-allow-clear="true">
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mt-2">
                                                <label class="form-label">Market</label>
                                                <select name="RESV_MARKET" id="RESV_MARKET" class="select2 form-select"
                                                    data-allow-clear="true">
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mt-2">
                                                <label class="form-label">Source</label>
                                                <select name="RESV_SOURCE" id="RESV_SOURCE" class="select2 form-select"
                                                    data-allow-clear="true">
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mt-2">
                                                <label class="form-label">Origin</label>
                                                <select name="RESV_ORIGIN" id="RESV_ORIGIN" class="select2 form-select"
                                                    data-allow-clear="true">
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mt-2">
                                                <label class="form-label">Payment</label>
                                                <select name="RESV_PAYMENT_TYPE" id="RESV_PAYMENT_TYPE"
                                                    class="select2 form-select" data-allow-clear="true">
                                                    <!-- <option value="">Select</option> -->
                                                </select>
                                                <div class="invalid-feedback"> Payment required can't empty.</div>
                                            </div>
                                            <div class="col-md-3 mt-2 selPickerDiv">
                                                <label class="form-label" for="RESV_SPECIALS">Specials</label>
                                                <select name="RESV_SPECIALS[]" id="RESV_SPECIALS" class="selectpicker"
                                                    multiple data-icon-base="bx" data-tick-icon="bx-check text-primary"
                                                    data-live-search="true" data-allow-clear="true">
                                                </select>
                                            </div>
                                            <div class="col-md-3 mt-2">
                                                <label class="form-label">Comments</label>
                                                <textarea class="form-control" name="RESV_COMMENTS" id="RESV_COMMENTS"
                                                    rows="1"></textarea>
                                            </div>

                                            <!-- Item Inventory  -->

                                            <div class="col-md-3 mt-2">
                                                <label class="form-label">Item Inventory</label>
                                                <div class="input-group mb-3">
                                                    <input type="text" readonly name="RESV_INV_ITEM" id="RESV_INV_ITEM"
                                                        class="form-control" placeholder="Items" required />
                                                    <button type="button" onClick="getInventoryItems()"
                                                        class="btn flxi_btn btn-sm btn-primary"><i class="fa fa-plus"
                                                            aria-hidden="true"></i></button>
                                                </div>
                                                <div class="invalid-feedback"> Item required can't empty.</div>
                                            </div>


                                            <!-- <div class="col-md-3 mt-2">
                                                <label class="form-label">Item Inventory</label>
                                                <div class="input-group mb-3">
                                                   
                                                    <select name="itemsArray" id="itemsArray" class="select2 form-select" data-allow-clear="true" multiple>  

                                                    </select>
                                                    <?php  ?>
                                                    <button type="button" onClick="getInventoryItems()"
                                                        class="btn flxi_btn btn-sm btn-primary"><i class="fa fa-plus"
                                                            aria-hidden="true"></i></button>
                                                </div>
                                                <div class="invalid-feedback"> Item required can't empty.</div>
                                            </div> -->
                                            <!-- End Item Inventory  -->


                                            <div class="col-md-3">
                                                <label class="form-label">Booker Last / First</label>
                                                <div class="flxi_flex">
                                                    <input type="text" name="RESV_BOKR_LAST" id="RESV_BOKR_LAST"
                                                        class="form-control" placeholder="booker last" />
                                                    <input type="text" name="RESV_BOKR_FIRST" id="RESV_BOKR_FIRST"
                                                        class="form-control" placeholder="booker first" />
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Booker Email/Phone</label>
                                                <div class="flxi_flex">
                                                    <input type="text" name="RESV_BOKR_EMAIL" id="RESV_BOKR_EMAIL"
                                                        class="form-control" placeholder="email" />
                                                    <input type="text" name="RESV_BOKR_PHONE" id="RESV_BOKR_PHONE"
                                                        class="form-control" placeholder="phone" />
                                                </div>
                                            </div>
                                            <div class="col-md-2 mt-4">
                                                <label class="form-label" for="RESV_CONFIRM_YN_CHK">
                                                    Confirmation </label>
                                                <label class="switch">
                                                    <input type="checkbox" class="switch-input"
                                                        id="RESV_CONFIRM_YN_CHK" />
                                                    <input type="hidden" name="RESV_CONFIRM_YN" value="N"
                                                        id="RESV_CONFIRM_YN" class="form-control" />
                                                    <span class="switch-toggle-slider">
                                                        <span class="switch-on">
                                                            <i class="bx bx-check"></i>
                                                        </span>
                                                        <span class="switch-off">
                                                            <i class="bx bx-x"></i>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="col-md-2 mt-4">
                                                <label class="form-label" for="RESV_NO_POST_CHK"> No Post </label>
                                                <label class="switch switch-danger">
                                                    <input type="checkbox" class="switch-input" id="RESV_NO_POST_CHK"
                                                        value="1" />
                                                    <input type="hidden" name="RESV_NO_POST" value="N" id="RESV_NO_POST"
                                                        class="form-control" />
                                                    <span class="switch-toggle-slider">
                                                        <span class="switch-on">
                                                            <i class="bx bx-x"></i>
                                                        </span>
                                                        <span class="switch-off">
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="moreDetails">
                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">C/O Time</label>
                                                <input type="time" name="RESV_C_O_TIME" id="RESV_C_O_TIME"
                                                    class="form-control" placeholder="c/o time" />
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Tax Type</label>
                                                <select name="RESV_TAX_TYPE" id="RESV_TAX_TYPE"
                                                    class="select2 form-select" data-allow-clear="true">
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Exempt No</label>
                                                <input type="text" name="RESV_EXEMPT_NO" id="RESV_EXEMPT_NO"
                                                    class="form-control" placeholder="exempt no" />
                                            </div>
                                            <div class="col-md-3"></div>

                                            <div class="col-md-3 mt-4">
                                                <label class="form-label">Pickup Requested ?</label>
                                                <label class="switch">
                                                    <input type="checkbox" class="switch-input"
                                                        id="RESV_PICKUP_YN_CHK" />
                                                    <input type="hidden" name="RESV_PICKUP_YN" value="N"
                                                        id="RESV_PICKUP_YN" class="form-control" />
                                                    <span class="switch-toggle-slider">
                                                        <span class="switch-on">
                                                            <i class="bx bx-check"></i>
                                                        </span>
                                                        <span class="switch-off">
                                                            <i class="bx bx-x"></i>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Transport Type</label>
                                                <select name="RESV_TRANSPORT_TYP" id="RESV_TRANSPORT_TYP"
                                                    class="select2 form-select" data-allow-clear="true">
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Station Code</label>
                                                <input type="text" name="RESV_STATION_CD" id="RESV_STATION_CD"
                                                    class="form-control" placeholder="station code" />
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Carrier Code</label>
                                                <input type="text" name="RESV_CARRIER_CD" id="RESV_CARRIER_CD"
                                                    class="form-control" placeholder="carrier code" />
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Transport No</label>
                                                <input type="text" name="RESV_TRANSPORT_NO" id="RESV_TRANSPORT_NO"
                                                    class="form-control" placeholder="tranport no" />
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Arrival Date</label>
                                                <div class="input-group ">
                                                    <input type="text" id="RESV_ARRIVAL_DT_PK" name="RESV_ARRIVAL_DT_PK"
                                                        class="form-control" placeholder="DD-MM-YYYY">
                                                    <span class="input-group-append">
                                                        <span class="input-group-text bg-light d-block">
                                                            <i class="fa fa-calendar"></i>
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Pick up Time</label>
                                                <input type="time" name="RESV_PICKUP_TIME" id="RESV_PICKUP_TIME"
                                                    class="form-control" placeholder="pickup time" />
                                            </div>
                                            <div class="col-md-3"></div>
                                            <div class="col-md-3 mt-4">
                                                <label class="form-label">Drop off Requested ?</label>
                                                <label class="switch">
                                                    <input type="checkbox" class="switch-input"
                                                        id="RESV_DROPOFF_YN_CHK" />
                                                    <input type="hidden" name="RESV_DROPOFF_YN" value="N"
                                                        id="RESV_DROPOFF_YN" class="form-control" />
                                                    <span class="switch-toggle-slider">
                                                        <span class="switch-on">
                                                            <i class="bx bx-check"></i>
                                                        </span>
                                                        <span class="switch-off">
                                                            <i class="bx bx-x"></i>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Transport Type</label>
                                                <select name="RESV_TRANSPORT_TYP_DO" id="RESV_TRANSPORT_TYP_DO"
                                                    class="select2 form-select" data-allow-clear="true">
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Station Code</label>
                                                <input type="text" name="RESV_STATION_CD_DO" id="RESV_STATION_CD_DO"
                                                    class="form-control" placeholder="station code" />
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Carrier Code</label>
                                                <input type="text" name="RESV_CARRIER_CD_DO" id="RESV_CARRIER_CD_DO"
                                                    class="form-control" placeholder="carrier code" />
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Transport No</label>
                                                <input type="text" name="RESV_TRANSPORT_NO_DO" id="RESV_TRANSPORT_NO_DO"
                                                    class="form-control" placeholder="transport no" />
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Arrival Date</label>
                                                <div class="input-group">
                                                    <input type="text" id="RESV_ARRIVAL_DT_DO" name="RESV_ARRIVAL_DT_DO"
                                                        class="form-control" placeholder="DD-MM-YYYY">
                                                    <span class="input-group-append">
                                                        <span class="input-group-text bg-light d-block">
                                                            <i class="fa fa-calendar"></i>
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Drop off Time</label>
                                                <input type="time" name="RESV_DROPOFF_TIME" id="RESV_DROPOFF_TIME"
                                                    class="form-control" placeholder="drop off time" />
                                            </div>
                                            <div class="col-md-3"></div>
                                            <div class="col-md-3">
                                                <label class="form-label">Guest Type</label>
                                                <select name="RESV_GUST_TY" id="RESV_GUST_TY"
                                                    class="select2 form-select" data-allow-clear="true">
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Purpose of Stay</label>
                                                <select name="RESV_EXT_PURP_STAY" id="RESV_EXT_PURP_STAY"
                                                    class="select2 form-select" data-allow-clear="true">
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Entry Point</label>
                                                <select name="RESV_ENTRY_PONT" id="RESV_ENTRY_PONT"
                                                    class="select2 form-select" data-allow-clear="true">
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Reserv. Profile</label>
                                                <select name="RESV_PROFILE" id="RESV_PROFILE"
                                                    class="select2 form-select" data-allow-clear="true">
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <label class="form-label">Name on Card</label>
                                                <input type="text" name="RESV_NAME_ON_CARD" id="RESV_NAME_ON_CARD"
                                                    class="form-control" placeholder="name on code" />
                                            </div>
                                            <div class="col-md-3 mt-5">
                                                <label class="form-label">Print Rate</label>
                                                <label class="switch">
                                                    <input type="checkbox" class="switch-input"
                                                        id="RESV_EXT_PRINT_RT_CHK" />
                                                    <input type="hidden" name="RESV_EXT_PRINT_RT" value="N"
                                                        id="RESV_EXT_PRINT_RT" class="form-control" />
                                                    <span class="switch-toggle-slider">
                                                        <span class="switch-on">
                                                            <i class="bx bx-check"></i>
                                                        </span>
                                                        <span class="switch-off">
                                                            <i class="bx bx-x"></i>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flxyFooter flxy_space">
                            <button type="button" id="previousbtn" onClick="previous()" class="btn btn-primary"><i
                                    class="fa-solid fa-angle-left"></i> Previous</button>
                            <button type="button" id="optionsResrBtn" data_sysid="" data-reservation_customer_id=""
                                class="btn btn-info reserOption me-2" style="margin-left: auto;">Options</button>
                            <button type="button" id="submitResrBtn" onClick="submitForm('reservationForm','R',event)"
                                class="btn btn-primary submitResr">Save</button>
                            <!--  -->
                            <button type="button" id="nextbtn" onClick="next()" class="btn btn-primary"
                                style="margin-left: auto;"> Next <i class="fa-solid fa-angle-right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reservationChild" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reservationChildlable">Search Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row profileSearch">
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Name</label>
                            <input type="text" id="CUST_LAST_NAME" class="form-control" placeholder="Name" />
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">First Name</label>
                            <input type="text" id="CUST_FIRST_NAME" class="form-control" placeholder="First name" />
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">City</label>
                            <input type="text" id="CUST_CITY" class="form-control" placeholder="City" />
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Email ID</label>
                            <input type="text" name="CUST_EMAIL" id="CUST_EMAIL" class="form-control"
                                placeholder="Email" />
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Client ID</label>
                            <input type="text" id="CUST_CLIENT_ID" class="form-control" placeholder="Client ID" />
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">IATA No</label>
                            <input type="text" id="CUST_IATA_NO" class="form-control" placeholder="IATA No" />
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Corp No</label>
                            <input type="text" id="CUST_CORP_NO" class="form-control" placeholder="Corp No" />
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">A/R No</label>
                            <input type="text" id="CUST_AR_NO" class="form-control" placeholder="A/R No" />
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Mobile</label>
                            <input type="text" id="CUST_MOBILE" class="form-control" placeholder="Mobile" />
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Communication</label>
                            <input type="text" id="CUST_COMMUNICATION_DESC" class="form-control"
                                placeholder="Communication" />
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="form-label">Passport No</label>
                            <input type="text" id="CUST_PASSPORT" class="form-control" placeholder="Passport No" />
                        </div>
                        <div class="col-md-4 mt-4">
                            <button type="button" onClick="searchData('profileSearch','S',event)"
                                class="btn btn-info">Search</button>
                            <button type="button" onClick="searchData('profileSearch','C',event)"
                                class="btn btn-warning">Clear</button>
                            <button type="button" onClick="searchData('profileSearch','N',event)"
                                class="btn btn-primary">New</button>
                            <button type="button" onClick="searchData('profileSearch','PR',event)"
                                class="btn btn-success">Ok</button>
                        </div>
                    </div>
                    <div class="row profileSearch mt-4">
                        <div class="flxy_table_resp">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width:70px">Edit</th>
                                        <th scope="col" style="width:70px">Sr.No</th>
                                        <th scope="col" style="width:250px">First Name</th>
                                        <th scope="col" style="width:250px">Last Name</th>
                                        <th scope="col" style="width:150px">DOB</th>
                                        <th scope="col" style="width:250px">Passport</th>
                                        <th scope="col" style="width:150px">Address</th>
                                        <th scope="col" style="width:250px">City</th>
                                        <th scope="col" style="width:250px">Email</th>
                                        <th scope="col" style="width:250px">Mobile</th>
                                        <th scope="col" style="width:250px">Nationality</th>
                                        <th scope="col" style="width:150px">VIP</th>
                                    </tr>
                                </thead>
                                <tbody id="searchRecord">
                                    <tr>
                                        <td class="text-left" colspan="11" style="padding-left: 20% !important;">No
                                            Record Found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <form id="customerForm" class="profileCreate">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <input type="hidden" name="CUST_ID" id="CUST_ID" class="form-control" />
                                <label class="form-label">First Name</label>
                                <input type="text" name="CUST_FIRST_NAME" id="CUST_FIRST_NAME" class="form-control"
                                    placeholder="first name" required />
                                <div class="invalid-feedback">
                                    First name is required can't empty.
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Middle Name</label>
                                <input type="text" name="CUST_MIDDLE_NAME" id="CUST_MIDDLE_NAME" class="form-control"
                                    placeholder="middle name" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="CUST_LAST_NAME" id="CUST_LAST_NAME" class="form-control"
                                    placeholder="last name" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Language/Title</label>
                                <div class="form-group flxi_join">
                                    <select name="CUST_LANG" id="CUST_LANG" class="form-select" data-allow-clear="true">
                                        <option value="">Select</option>
                                        <option value="EN">English</option>
                                        <option value="AR">Arabic</option>
                                        <option value="FR">French</option>
                                    </select>
                                    <select name="CUST_TITLE" id="CUST_TITLE" class="form-select"
                                        data-allow-clear="true">
                                        <option value="">Select</option>
                                        <option value="Mr">Mr.</option>
                                        <option value="Ms">Ms.</option>
                                        <option value="Shiekh.">Shiekh.</option>
                                        <option value="Shiekha.">Shiekha.</option>
                                        <option value="Dr.">Dr.</option>
                                        <option value="Ambassador.">Ambassador.</option>
                                        <option value="Prof.">Prof.</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">DOB</label>
                                <div class="input-group mb-3">
                                    <input type="text" id="CUST_DOB" name="CUST_DOB" class="form-control CUST_DOB"
                                        placeholder="YYYY-MM-DD">
                                    <span class="input-group-append">
                                        <span class="input-group-text bg-light d-block">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Document Number</label>
                                <input type="text" name="CUST_DOC_NUMBER" class="form-control"
                                    placeholder="Document number" />
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Doc Issue Date</label>
                                <input type="text" name="CUST_DOC_ISSUE" class="form-control CUST_DOC_ISSUE"
                                    placeholder="YYYY-MM-DD" />
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Doc Expiry Date</label>
                                <input type="text" name="CUST_DOC_EXPIRY" class="form-control CUST_DOC_EXPIRY"
                                    placeholder="YYYY-MM-DD" />
                            </div>

                            <!-- <div class="col-md-3">
                                <label class="form-label">Passport</label>
                                <input type="text" name="CUST_PASSPORT" id="CUST_PASSPORT" class="form-control"
                                    placeholder="passport" />
                            </div> -->

                            <div class="col-md-3">
                                <label class="form-label">Address</label>
                                <input type="text" name="CUST_ADDRESS_1" id="CUST_ADDRESS_1" class="form-control"
                                    placeholder="addresss 1" required />
                                <div class="invalid-feedback">
                                    address is required can't empty.
                                </div>
                            </div>

                            <!-- <div class="col-md-3 flxy_mgtop"> -->
                            <div class="col-md-3">
                                <label class="form-label"></label>
                                <input type="text" name="CUST_ADDRESS_2" id="CUST_ADDRESS_2" class="form-control"
                                    placeholder="address 2" />
                            </div>

                            <!-- <div class="col-md-3" style="margin-top: 23px !important;"> -->
                            <div class="col-md-3">
                                <label class="form-label"></label>
                                <input type="text" name="CUST_ADDRESS_3" id="CUST_ADDRESS_3" class="form-control"
                                    placeholder="address 3" />
                            </div>

                            <div class="col-md-3">
                                <label for="html5-text-input" class="form-label" style="display: block"><b>GENDER
                                        *</b></label>
                                <div class="form-check mb-2" style="float:left;margin-right:10px">
                                    <input type="radio" id="CUST_GENDER_Male" name="CUST_GENDER" value="Male"
                                        class="form-check-input" required="" checked>
                                    <label class="form-check-label" for="CUST_GENDER_Male">Male</label>
                                </div>
                                <div class="form-check" style="float:left">
                                    <input type="radio" id="CUST_GENDER_Female" name="CUST_GENDER" value="Female"
                                        class="form-check-input" required="">
                                    <label class="form-check-label" for="CUST_GENDER_Female">Female</label>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <label class="form-label col-md-12">Country</label>
                                <select name="CUST_COUNTRY" id="CUST_COUNTRY" data-width="100%"
                                    class="selectpicker CUST_COUNTRY" data-live-search="true" required>
                                    <option value="">Select</option>
                                </select>
                                <div class="invalid-feedback">
                                    country is required can't empty.
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label col-md-12">State</label>
                                <select name="CUST_STATE" id="CUST_STATE" data-width="100%"
                                    class="selectpicker CUST_STATE" data-live-search="true">
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label col-md-12">City</label>
                                <select name="CUST_CITY" id="CUST_CITY" data-width="100%" class="selectpicker CUST_CITY"
                                    data-live-search="true">
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Email</label>
                                <input type="text" name="CUST_EMAIL" id="CUST_EMAIL" class="form-control"
                                    placeholder="email" required />
                                <div class="invalid-feedback">
                                    Email is required can't empty.
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Mobile</label>
                                <input type="text" name="CUST_MOBILE" id="CUST_MOBILE" class="form-control"
                                    placeholder="mobile" required />
                                <div class="invalid-feedback">
                                    Mobile No is required can't empty.
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="CUST_PHONE" id="CUST_PHONE" class="form-control"
                                    placeholder="phone" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Client ID</label>
                                <input type="text" name="CUST_CLIENT_ID" id="CUST_CLIENT_ID" class="form-control"
                                    placeholder="client id" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Postal Code</label>
                                <input type="text" name="CUST_POSTAL_CODE" id="CUST_POSTAL_CODE" class="form-control"
                                    placeholder="postal" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">VIP</label>
                                <select name="CUST_VIP" id="CUST_VIP" class="select2 form-select CUST_VIP"
                                    data-allow-clear="true">
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Nationality</label>
                                <select name="CUST_NATIONALITY" id="CUST_NATIONALITY" class="select2 form-select"
                                    data-allow-clear="true">
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Business Segment</label>
                                <select name="CUST_BUS_SEGMENT" id="CUST_BUS_SEGMENT" class="select2 form-select"
                                    data-allow-clear="true">
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Communication</label>
                                <select name="CUST_COMMUNICATION" id="CUST_COMMUNICATION" class="select2 form-select"
                                    data-allow-clear="true">
                                    <option value="">Select Communication</option>
                                    <option value="WEB">Web</option>
                                    <option value="WHATSAPP">Whatsapp</option>
                                    <option value="FAX">Fax</option>
                                    <option value="OTHER">Other</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Communication Desc.</label>
                                <input type="text" name="CUST_COMMUNICATION_DESC" id="CUST_COMMUNICATION_DESC"
                                    class="form-control" placeholder="communication desc" />
                            </div>
                            <div class="col-md-3">
                                <div class="form-check mt-3">
                                    <input class="form-check-input flxCheckBox" type="checkbox" id="CUST_ACTIVE_CHK">
                                    <input type="hidden" name="CUST_ACTIVE" id="CUST_ACTIVE" value="N"
                                        class="form-control" />
                                    <label class="form-check-lable" for="defaultCheck1"> Active </label>
                                </div>

                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer profileCreate">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" onClick="submitForm('customerForm','C',event)"
                        class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Modal window -->

    <!-- Rate Query Modal window -->
    <div class="modal fade" id="rateQueryWindow" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header flxy_padding">
                    <h5 class="modal-title" id="rateQueryWindowLable">Rate Query</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>
                <div id="userInfoDate"></div>
                <div class="modal-body flxy_padding">
                    <form id="rateQueryForm">
                        <div class="row g-3">
                            <div class="col-md-10 flxy_horiz_scroll">
                                <table class="table table-bordered" style="table-layout:fixed;">
                                    <tbody id="rateQueryTable">
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-2 flxy_border_over">
                                <button type="button" onClick="detailOption('OB')"
                                    class="btn btn-secondary d-grid gap-2  mx-auto"><span class="btnName">Overbooking
                                        Detail</span></button>
                                <!-- <button type="button" onClick="submitForm('customerForm','C',event)" class="btn btn-primary">Save</button> -->
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer flxy_paddng">
                    <div class="flxy_opertion flxy_opt1 border rounded">
                        <div class="flxy_radio mb-2">
                            <div class="col-md-3 flxy_equal">
                                <label class="switch">
                                    <input type="checkbox" class="switch-input rateRadio" mode="AVG" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label">Average Rate</span>
                                </label>
                            </div>
                            <div class="col-md-3 flxy_equal1">
                                <label class="switch">
                                    <input type="checkbox" class="switch-input rateRadio" mode="TOT" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label">Total Rate</span>
                                </label>
                            </div>
                        </div>

                        <div class="row flxy_radio">
                            <div class="col-md-12 mb-2">
                                <label class="switch">
                                    <input type="checkbox" id="closedRateFilter" class="switch-input rateFilter"
                                        value="1" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label">Closed</span>
                                </label>
                            </div>
                            <div class="col-md-12">
                                <label class="switch">
                                    <input type="checkbox" id="dayUseRateFilter" class="switch-input rateFilter"
                                        value="1" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label">Day Use</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="flxy_opertion flxy_right">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="border rounded p-4" style="min-height: 118px;">
                                    <div class="row mb-3">
                                        <label class="col-md-1"><b>Info:</b></label>
                                        <div class="col-md-11 text-start"><span id="roomRateInfo"></span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2"><button type="button" onclick="selectRate(event)"
                                    class="btn btn-primary">Ok</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Rate Query Modal window end -->


    <!-- Changes Log window -->
    <div class="modal fade" id="changesWindow" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changesWindowLabel">Activity Log</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="table-responsive text-nowrap">
                            <table id="reservation_changes"
                                class="dt-responsive table table-striped table-bordered display nowrap">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th class="all">User</th>
                                        <th>Log ID</th>
                                        <th class="all">Date</th>
                                        <th>Time</th>
                                        <th class="all">Action Type</th>
                                        <th class="none">Description</th>
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
    <!-- Changes Log window end -->

    <!-- Option window -->
    <div class="modal fade" id="appcompanyWindow" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rateQueryWindowLable">Accompanying Guest</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="customeTrigger"></div>
                    <div class="row">
                        <div class="flxy_table_resp">
                            <table class="table table-striped table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col" style="width:70px">Edit</th>
                                        <th scope="col" style="width:70px">Sr.No</th>
                                        <th scope="col" style="width:250px">First Name</th>
                                        <th scope="col" style="width:250px">Last Name</th>
                                        <th scope="col" style="width:150px">DOB</th>
                                        <th scope="col" style="width:250px">Passport</th>
                                        <th scope="col" style="width:150px">Address</th>
                                        <th scope="col" style="width:250px">City</th>
                                        <th scope="col" style="width:250px">Email</th>
                                        <th scope="col" style="width:250px">Mobile</th>
                                        <th scope="col" style="width:250px">Nationality</th>
                                        <th scope="col" style="width:150px">VIP</th>
                                    </tr>
                                </thead>
                                <tbody id="accompanyTd">
                                    <tr>
                                        <td class="text-left" colspan="11" style="padding-left: 20% !important;">No
                                            Accompanying Guests</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onClick="accompanySet('A',event)" class="btn btn-primary">Attach</button>
                    <button type="button" onClick="accompanySet('D',event)"
                        class="btn btn-warning detach-accompany-guest" disabled>Detach</button>
                </div>
            </div>
        </div>
    </div>
    <!-- option window end -->

    <!-- RateQuery Detail window -->
    <div class="modal fade" id="reteQueryDetail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rateQueryWindowLable">Overbooking Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row flxy_height">
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Room Type</th>
                                    <th>Overbooking</th>
                                </tr>
                            </thead>
                            <tbody id="reteQueryDetailTd">
                                <tr>
                                    <td class="text-center" colspan="3">No data</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!--  RateQuery Detail window end -->

    <!-- Modal Window Item Inventory -->
    <div class="modal fade" id="ItemInventory" data-backdrop="static" data-keyboard="false"
        aria-labelledby="popModalWindowlabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">Item Inventory</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div id="wizard-validation" class="bs-stepper mt-2">
                        <div class="bs-stepper-header">
                            <div class="step" data-target="#select_items" >
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-circle">1</span>
                                    <span class="bs-stepper-label">Items</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            <div class="step" data-target="#item_availability"  onclick="showInventoryAvailability()" >
                                <button type="button" class="step-trigger" >
                                    <span class="bs-stepper-circle">2</span>
                                    <span class="bs-stepper-label">Inventory Availability</span>
                                </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content">

                            <form id="item-submit-form" onSubmit="return false">
                                <input type="hidden" name="RSV_ID" id="RSV_ID" class="form-control" />

                                <input type="hidden" name="ITEM_RESV_ID" id="ITEM_RESV_ID" class="form-control" />

                                <div id="select_items" class="content">

                                    <input type="hidden" name="RSV_PRI_ID" id="RSV_PRI_ID" class="form-control" />
                                    <div class="row g-3">

                                        <div class="col-md-5">
                                            <div class="border rounded p-4 mb-3">
                                                <div class="row mb-3">
                                                    <label for="RSV_ITM_CL_ID" class="col-form-label col-md-4"><b>Item
                                                            Class *</b></label>
                                                    <div class="col-md-8">
                                                        <select id="RSV_ITM_CL_ID" name="RSV_ITM_CL_ID"
                                                            class="select2 form-select form-select-lg">

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="RSV_ITM_ID" class="col-form-label col-md-4"><b>Items
                                                            *</b></label>
                                                    <div class="col-md-8">
                                                        <select id="RSV_ITM_ID" name="RSV_ITM_ID"
                                                            class="select2 form-select form-select-lg">

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="RSV_ITM_BEGIN_DATE"
                                                        class="col-form-label col-md-4"><b>Start
                                                            Date *</b></label>
                                                    <div class="col-md-8">
                                                        <input class="form-control dateFieldItem" type="text"
                                                            placeholder="d-Mon-yyyy" id="RSV_ITM_BEGIN_DATE"
                                                            name="RSV_ITM_BEGIN_DATE" />
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="RSV_ITM_END_DATE" class="col-form-label col-md-4"><b>End
                                                            Date *</b></label>
                                                    <div class="col-md-8">
                                                        <input class="form-control dateFieldItem" type="text"
                                                            placeholder="d-Mon-yyyy" id="RSV_ITM_END_DATE"
                                                            name="RSV_ITM_END_DATE" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="RSV_ITM_QTY" class="col-form-label col-md-4"><b>Quantity
                                                            *</b></label>
                                                    <div class="col-md-8">
                                                        <input type="number" name="RSV_ITM_QTY" id="RSV_ITM_QTY"
                                                            class="form-control" min="1" step="1"
                                                            placeholder="eg: 12" />
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-8 float-right">
                                                        <button type="button" class="btn btn-success save-item-detail">
                                                            <i class="fa-solid fa-floppy-disk"></i>&nbsp; Save
                                                        </button>&nbsp;
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-7">

                                            <div class="border rounded p-4 mb-3">
                                                <div class="col-md-6 mb-3">
                                                    <button type="button" class="btn btn-primary add-item-detail">
                                                        <i class="fa-solid fa-circle-plus"></i>&nbsp; Add New
                                                    </button>&nbsp;

                                                    <button type="button" class="btn btn-danger delete-item-detail">
                                                        <i class="fa-solid fa-ban"></i>&nbsp; Delete
                                                    </button>&nbsp;
                                                </div>

                                                <div class="table-responsive text-nowrap">
                                                    <table id="Inventory_Details"
                                                        class="table table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="all">ID</th>
                                                                <th class="all">Items</th>
                                                                <th class="all">Start</th>
                                                                <th class="all">End</th>
                                                                <th class="all">Quantity</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>

                                                <br />




                                            </div>
                                        </div>
                                        <div class="d-flex col-12 justify-content-between">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>

                                            <!-- <button type="button" class="btn btn-primary btn-next">
                        <span class="d-none d-sm-inline-block me-sm-1">Next</span>
                        <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
                      </button> -->
                                        </div>

                                    </div>

                                </div>
                                </form>
                            <div id="item_availability" class="content">
                                <div class="card app-calendar-wrapper">
                                    <div class="row g-0">

                                        <!-- Calendar Sidebar -->
                                        <div class="app-calendar-sidebar col" id="app-calendar-sidebar" style="display: none;">
                                            <div class="border-bottom p-4 my-sm-0 mb-3">
                                                <div class="d-grid">
                                                    <button class="btn btn-primary btn-toggle-sidebar">

                                                        <span class="align-middle">Available Items</span>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="p-4">
                                                <!-- inline calendar (flatpicker) -->
                                                <div class="ms-n2">
                                                    <div class="inline-calendar"></div>
                                                </div>

                                                <hr class="container-m-nx my-4" />


                                                <div class="app-calendar-events-filter">

                                                    <div class="form-check mb-2" style="display: none">
                                                        <input class="form-check-input input-filter" type="checkbox"
                                                            id="select-business" data-value="business" checked />
                                                        <label class="form-check-label" for="select-business">View
                                                            All</label>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <!-- /Calendar Sidebar -->

                                        <!-- Calendar & Modal -->
                                        <div class="app-calendar-content col">
                                            <div class="card shadow-none border-0">
                                                <div class="card-body pb-0">
                                                    <!-- FullCalendar -->
                                                    <div id="calendar"></div>
                                                </div>
                                            </div>
                                            <div class="app-overlay"></div>
                                            <!-- FullCalendar Offcanvas -->
                                            
                                        </div>
                                        <!-- /Calendar & Modal -->
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- /Modal window -->

    <!-- Fixed Charges Modal window -->
    <div class="modal fade" id="fixedCharges" data-backdrop="static" data-keyboard="false"
        aria-labelledby="popModalWindowlabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">Fixed Charges</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form id="fixedcharge-submit-form" onSubmit="return false">
                        <input type="hidden" name="FIXD_CHRG_RESV_ID" id="FIXD_CHRG_RESV_ID" class="form-control" />

                        <div id="fixedTransactionCharges" class="content">

                            <input type="hidden" name="FIXD_CHRG_ID" id="FIXD_CHRG_ID" class="form-control" />
                            <div class="row g-3">
                                <div class="border rounded p-4 mb-3">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label for="FIXD_RSV_NAME" class="col-form-label col-md-4"><b>
                                                    Name</b></label>
                                            <input type="text" name="FIXD_RSV_NAME" id="FIXD_RSV_NAME" disabled
                                                class="form-control" />

                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="FIXD_ARRIVAL" class="col-form-label col-md-4"><b>
                                                    Arrival</b></label>
                                            <input type="text" name="FIXD_ARRIVAL" id="FIXD_ARRIVAL" disabled
                                                class="form-control" />

                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="FIXD_NIGHTS" class="col-form-label col-md-4"><b>
                                                    Nights</b></label>
                                            <input type="text" name="FIXD_NIGHTS" id="FIXD_NIGHTS" class="form-control"
                                                disabled />

                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="FIXD_DEPARTURE" class="col-form-label col-md-4"><b>
                                                    Departure</b></label>
                                            <input type="text" name="FIXD_DEPARTURE" id="FIXD_DEPARTURE" disabled
                                                class="form-control" />
                                            <input type="hidden" name="FIXD_DEPARTURE_UP" id="FIXD_DEPARTURE_UP"
                                                class="form-control" />

                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-2 mb-3">
                                            <label for="FIXD_CHRG_FREQUENCY" class="col-form-label col-md-4"><b>
                                                    FREQUENCY</b></label>

                                        </div>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <?php if (!empty($FrequencyList)) {
                                            foreach ($FrequencyList as $Freequency) {
                                        ?>
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input id="FIXD_CHRG_FREQUENCY<?php echo $Freequency['ID']; ?>"
                                                    value="<?php echo $Freequency['ID']; ?>" name="FIXD_CHRG_FREQUENCY"
                                                    class="form-check-input" type="radio"
                                                    <?php if ($Freequency['ID'] == 2) { ?> checked="checked" <?php } ?>
                                                    onclick=frequency(<?php echo $Freequency['ID']; ?>)>
                                                <label class="form-check-label" for="FIXD_CHRG_FREQUENCY">
                                                    <?php echo $Freequency['FREQ_NAME']; ?> </label>
                                            </div>
                                        </div>
                                        <?php
                                            }
                                        } ?>
                                    </div>
                                    <div class="row g-3 ">
                                        <div class="col-md-4 mb-3">
                                            <label for="FIXD_CHRG_BEGIN_DATE" class="col-form-label col-md-4"><b>Start
                                                    Date *</b></label>
                                            <input class="form-control" type="text" placeholder="d-Mon-yyyy"
                                                id="FIXD_CHRG_BEGIN_DATE" name="FIXD_CHRG_BEGIN_DATE" />
                                        </div>
                                        <div class="col-md-4 END_DATE">
                                            <label for="FIXD_CHRG_END_DATE" class="col-form-label col-md-4"><b>End
                                                    Date *</b></label>
                                            <input class="form-control" type="text" placeholder="d-Mon-yyyy"
                                                id="FIXD_CHRG_END_DATE" name="FIXD_CHRG_END_DATE" />

                                        </div>
                                        <div class="col-md-4 WEEKLY_EXCECUTE" style="display: none">
                                            <label for="FIXD_CHRG_WEEKLY" class="col-form-label col-md-4"><b>Day to
                                                    Execute</b></label>
                                            <select name="FIXD_CHRG_WEEKLY" id="FIXD_CHRG_WEEKLY"
                                                class="select2 form-select form-select-lg">
                                                <option value="0" selected>Sunday</option>
                                                <option value="1">Monday</option>
                                                <option value="2">Tuesday</option>
                                                <option value="3">Wednesday</option>
                                                <option value="4">Thursday</option>
                                                <option value="5">Friday</option>
                                                <option value="6">Saturday</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 MONTHLY_EXCECUTE" style="display: none">
                                            <label for="FIXD_CHRG_MONTHLY" class="col-form-label col-md-5"><b>Day to
                                                    Execute ( 1-31 )</b></label>
                                            <input type="text" name="FIXD_CHRG_MONTHLY" id="FIXD_CHRG_MONTHLY"
                                                class="form-control" placeholder="eg: 1" value='1' />
                                        </div>

                                        <div class="col-md-4 QUARTERLY_EXCECUTE" style="display: none">
                                            <label for="FIXD_CHRG_QUARTERLY" class="col-form-label col-md-5"><b>Day to
                                                    Execute ( 1-31 )</b></label>
                                            <input type="text" name="FIXD_CHRG_QUARTERLY" id="FIXD_CHRG_QUARTERLY"
                                                class="form-control" placeholder="eg: 1" value='1' />
                                        </div>

                                        <div class="col-md-4 YEARLY_EXCECUTE" style="display: none">
                                            <label for="FIXD_CHRG_YEARLY" class="col-form-label col-md-5"><b>Day to
                                                    Execute</b></label>
                                            <input class="form-control dateFIXD_CHRG" type="text"
                                                placeholder="yyyy-mm-dd" id="FIXD_CHRG_YEARLY"
                                                name="FIXD_CHRG_YEARLY" />
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-4">
                                            <label for="FIXD_CHRG_TRNCODE"
                                                class="col-form-label col-md-5"><b>TRANSACTION CODE *</b></label>
                                            <select id="FIXD_CHRG_TRNCODE" name="FIXD_CHRG_TRNCODE"
                                                class="select2 form-select form-select-lg"></select>

                                        </div>
                                        <div class="col-md-4 ">
                                            <label for="FIXD_CHRG_AMT" class="col-form-label col-md-4"><b>AMOUNT
                                                    *</b></label>
                                            <input type="number" name="FIXD_CHRG_AMT" id="FIXD_CHRG_AMT"
                                                class="form-control" min="1" step="1" placeholder="eg: 12" />

                                        </div>

                                        <div class="col-md-4">
                                            <label for="FIXD_CHRG_QTY" class="col-form-label col-md-4"><b>QUANTITY
                                                    *</b></label>
                                            <input type="number" name="FIXD_CHRG_QTY" id="FIXD_CHRG_QTY"
                                                class="form-control" min="1" step="1" placeholder="eg: 12" />

                                        </div>
                                    </div>

                                    <div class="row g-3 ">
                                        <div class="col-md-3 mb-3">
                                            <div class="col-md-8 float-right">
                                                <button type="button" class="btn btn-success save-fixedcharge-detail">
                                                    <i class="fa-solid fa-floppy-disk"></i>&nbsp; Save
                                                </button>&nbsp;
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="border rounded p-4 mb-3">
                                        <div class="col-md-6 mb-3">
                                            <button type="button" class="btn btn-primary add-fixedcharge-detail">
                                                <i class="fa-solid fa-circle-plus"></i>&nbsp; Add New
                                            </button>&nbsp;

                                            <button type="button" class="btn btn-danger delete-fixedcharge-detail">
                                                <i class="fa-solid fa-ban"></i>&nbsp; Delete
                                            </button>&nbsp;
                                        </div>

                                        <div class="table-responsive text-nowrap">
                                            <table id="FixedCharge_Details" class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th class="all">Trans Code & Description</th>
                                                        <th class="all">Quantity</th>
                                                        <th class="all">Amount</th>
                                                        <th class="all">Frequency</th>
                                                        <th class="all">Begin Date</th>
                                                        <th class="all">End Date</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <br />
                                    </div>
                                </div>
                                <div class="d-flex col-12 justify-content-between">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>

                                </div>

                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- /Modal Fixed charges window -->

    <!-- Pro-Forma Folio Modal window -->
    <div class="modal fade" id="proforma-folio" data-backdrop="static" data-keyboard="false"
        aria-labelledby="popModalWindowlabel">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">Pro-Forma Folio</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form id="proforma-submit-form" onSubmit="return false">
                        <input type="hidden" name="PROFORMA_RESV_ID" id="PROFORMA_RESV_ID" class="form-control" />

                        <div id="fixedTransactionCharges" class="content">

                            <input type="hidden" name="PROFORMA_ID" id="PROFORMA_ID" class="form-control" />
                            <div class="row g-3">
                                <div class="border rounded p-4 mb-3">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="FOLIO_TXT_ONE" class="col-form-label col-md-4"><b>
                                                    Folio Text1</b></label>
                                            <input type="text" name="FOLIO_TXT_ONE" id="FOLIO_TXT_ONE"
                                                class="form-control" />

                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="FOLIO_TXT_TWO" class="col-form-label col-md-4"><b>
                                                    Folio Text2</b></label>
                                            <input type="text" name="FOLIO_TXT_TWO" id="FOLIO_TXT_TWO"
                                                class="form-control" />

                                        </div>


                                    </div>

                                    <div class="row g-3 ">
                                        <div class="form-check mt-3 p-0">
                                            <label class="switch">
                                                <input type="checkbox" class="switch-input" checked id="PRINT_PHONE"
                                                    name="PRINT_PHONE" method="PM" />
                                                <span class="switch-toggle-slider">
                                                    <span class="switch-on">
                                                        <i class="bx bx-check"></i>
                                                    </span>
                                                    <span class="switch-off">
                                                        <i class="bx bx-x"></i>
                                                    </span>
                                                </span>
                                            </label>
                                            <lable class="form-check-lable flxy_lab_left"> Print Phone Details</label>
                                        </div>
                                        <div class="form-check mt-3 p-0">
                                            <label class="switch">
                                                <input type="checkbox" class="switch-input " checked id="PRINT_CHECK"
                                                    method="PM" name="PRINT_CHECK" />
                                                <span class="switch-toggle-slider">
                                                    <span class="switch-on">
                                                        <i class="bx bx-check"></i>
                                                    </span>
                                                    <span class="switch-off">
                                                        <i class="bx bx-x"></i>
                                                    </span>
                                                </span>
                                            </label>
                                            <lable class="form-check-lable flxy_lab_left"> Print Check Number</label>
                                        </div>

                                        <div class="form-check mt-3 p-0 mb-3">
                                            <label class="switch">
                                                <input type="checkbox" class="switch-input " checked id="PRINT_EMAIL"
                                                    method="PM" name="PRINT_EMAIL" />
                                                <span class="switch-toggle-slider">
                                                    <span class="switch-on">
                                                        <i class="bx bx-check"></i>
                                                    </span>
                                                    <span class="switch-off">
                                                        <i class="bx bx-x"></i>
                                                    </span>
                                                </span>
                                            </label>
                                            <lable class="form-check-lable flxy_lab_left"> Email List </label>
                                        </div>
                                    </div>

                                    <div class="row g-3 ">
                                        <div class="col-md-12 mb-3 mt-3">
                                            <div class="col-md-12 float-right">
                                                <input type="hidden" name="proforma_action_value"
                                                    id="proforma_action_value">
                                                <button type="button" class="btn btn-success proformafolio-action mr-1"
                                                    rel="1"> PREVIEW</button>
                                                <button type="button" class="btn btn-success proformafolio-action mr-1"
                                                    rel="2">
                                                    PRINT
                                                </button>
                                                <button type="button" class="btn btn-success proformafolio-action mr-1"
                                                    rel="3">
                                                    PDF
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex col-12 justify-content-between">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>

                                </div>

                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- /Modal Pro-forma Folio window -->

    <!-- Package Modal window -->
    <div class="modal fade" id="packagesModal" data-backdrop="static" data-keyboard="false"
        aria-labelledby="popModalWindowlabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">Packages</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="package-submit-form" onSubmit="return false">
                        <input type="hidden" name="PCKG_RESV_ID" id="PCKG_RESV_ID" class="form-control" />
                        <input type="hidden" name="RSV_PCKG_ID" id="RSV_PCKG_ID" class="form-control" />
                        <input type="hidden" name="RESVSTART_DATE" id="RESVSTART_DATE" class="form-control" />
                        <input type="hidden" name="RESVEND_DATE" id="RESVEND_DATE" class="form-control" />
                        <div id="packages" class="content">
                            <div class="row g-3">
                                <div class="border rounded p-4 mb-3">
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-4 ">
                                            <label for="PCKG_ID" class="col-form-label col-md-4"><b>Package
                                                    *</b></label>
                                            <select id="PCKG_ID" name="PCKG_ID"
                                                class="select2 form-select form-select-lg">
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="RSV_PCKG_QTY" class="col-form-label col-md-4"><b>QUANTITY
                                                </b></label>
                                            <input type="number" name="RSV_PCKG_QTY" id="RSV_PCKG_QTY"
                                                class="form-control" min="1" max="99" placeholder="eg: 10" />
                                        </div>

                                        <div class="col-md-2 ">
                                            <label for="PACKAGE_EXCLUDE" class="col-form-label col-md-4"><b>Exclude
                                                </b></label>
                                            <input type="text" name="PACKAGE_EXCLUDE" id="PACKAGE_EXCLUDE"
                                                class="form-control" readonly />

                                        </div>

                                        <div class="col-md-4">
                                            <label for="RSV_PCKG_POST_RYTHM" class="col-form-label col-md-4"><b>POSTING
                                                    RHYTHM</b></label>
                                            <input type="text" name="RSV_PCKG_POST_RYTHM" id="RSV_PCKG_POST_RYTHM"
                                                readonly class="form-control" />

                                        </div>
                                    </div>

                                    <div class="row g-3 ">
                                        <div class="col-md-4">
                                            <label for="RSV_PCKG_CALC_RULE" class="col-form-label"><b>CALCULATION
                                                    RULE</b></label>
                                            <input type="text" name="RSV_PCKG_CALC_RULE" id="RSV_PCKG_CALC_RULE"
                                                class="form-control" readonly />

                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="RSV_PCKG_BEGIN_DATE" class="col-form-label col-md-4"><b>Start
                                                    Date </b></label>
                                            <input class="form-control" type="text" placeholder="yyyy-mm-d"
                                                id="RSV_PCKG_BEGIN_DATE" name="RSV_PCKG_BEGIN_DATE" />
                                        </div>
                                        <div class="col-md-4 END_DATE">
                                            <label for="RSV_PCKG_END_DATE" class="col-form-label col-md-4"><b>End
                                                    Date </b></label>
                                            <input class="form-control" type="text" placeholder="yyyy-mm-d"
                                                id="RSV_PCKG_END_DATE" name="RSV_PCKG_END_DATE" />
                                        </div>
                                    </div>

                                    <div class="row g-3 ">
                                        <div class="col-md-3 mb-3">
                                            <div class="col-md-8 float-right">
                                                <button type="button" class="btn btn-success save-package-detail">
                                                    <i class="fa-solid fa-floppy-disk"></i>&nbsp; Save
                                                </button>&nbsp;
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="border rounded p-4 mb-3">
                                        <div class="col-md-6 mb-3">
                                            <button type="button" class="btn btn-primary add-package-detail">
                                                <i class="fa-solid fa-circle-plus"></i>&nbsp; Add New
                                            </button>&nbsp;

                                            <button type="button" class="btn btn-danger delete-package-detail">
                                                <i class="fa-solid fa-ban"></i>&nbsp; Delete
                                            </button>&nbsp;
                                        </div>

                                        <div class="table-responsive text-nowrap">
                                            <table id="Package_Details" class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th class="all">Package</th>
                                                        <th class="all">Short Description</th>
                                                        <th class="all">Rate Code</th>
                                                        <th class="all">Quantity</th>
                                                        <th class="all">Posting Rythm</th>
                                                        <th class="all">Calculation Rule</th>
                                                        <th class="all">Begin Date</th>
                                                        <th class="all">End Date</th>
                                                        <!-- <th class="all">Exclude</th>
                                                        <th class="all">Allowance</th> -->
                                                        <!-- <th class="all">Next Day</th>                                                   
                                                        <th class="all">Group</th>
                                                        <th class="all">Formula</th> -->
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <br />


                                        <div class="table-responsive text-nowrap">
                                            <table id="Each_Package_Details" class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="all">Consumption Date</th>
                                                        <th class="all">Unit Price</th>
                                                        <th class="all">Reservation Price</th>
                                                    </tr>
                                                </thead>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex col-12 justify-content-between">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>

                                </div>

                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- /Modal Package window -->

    <!-- End Rate Info  -->
    <div class="modal fade" id="RateInfoModal" data-backdrop="static" data-keyboard="false"
        aria-labelledby="popModalWindowlabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">Rate Info</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="rate_info" class="content">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="border rounded p-4 mb-3">
                                    <div class="table-responsive text-nowrap Rate_Info_Div"
                                        style="height: 350px; overflow: auto">
                                        <table id="Rate_info" class="table table-bordered table-hover"
                                            style="height: 350px;">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th class="all">Date</th>
                                                    <th class="all">Rate Code</th>
                                                    <th class="all">Room Revenue</th>
                                                    <th class="all">Packages</th>
                                                    <th class="all">Sub Total</th>
                                                    <th class="all">Generates</th>
                                                    <th class="all">Total</th>

                                                </tr>
                                            </thead>
                                        </table>

                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="p-4 mb-3" style="float: right">
                                    <div class="table-responsive text-nowrap">
                                        <table id="Rate_info_total" class="table table-hover" style="float:right">
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex col-12 justify-content-between">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Rate Info  -->


    <!-- Traces Modal window -->
    <div class="modal fade" id="tracesModal" data-backdrop="static" data-keyboard="false"
        aria-labelledby="popModalWindowlabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">Traces</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form id="trace-submit-form" onSubmit="return false">
                        <input type="hidden" name="TRACE_RESV_ID" id="TRACE_RESV_ID" class="form-control" />

                        <div id="tracesDiv" class="content">

                            <input type="hidden" name="RSV_TRACE_ID" id="RSV_TRACE_ID" class="form-control" />
                            <div class="row g-3">
                                <div class="border rounded p-4 mb-3">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label for="TRACE_RSV_NAME" class="col-form-label col-md-4"><b>
                                                    Name</b></label>
                                            <input type="text" name="TRACE_RSV_NAME" id="TRACE_RSV_NAME" disabled
                                                class="form-control" />

                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="TRACE_ARRIVAL" class="col-form-label col-md-4"><b>
                                                    Arrival</b></label>
                                            <input type="text" name="TRACE_ARRIVAL" id="TRACE_ARRIVAL" disabled
                                                class="form-control" />
                                            <input type="hidden" name="TRACE_ARRIVAL_DT" id="TRACE_ARRIVAL_DT"
                                                class="form-control" />

                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="TRACE_DEPARTURE" class="col-form-label col-md-4"><b>
                                                    Departure</b></label>
                                            <input type="text" name="TRACE_DEPARTURE" id="TRACE_DEPARTURE" disabled
                                                class="form-control" />
                                            <input type="hidden" name="TRACE_DEPARTURE_DT" id="TRACE_DEPARTURE_DT"
                                                class="form-control" />

                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="RESERVATION_STATUS" class="col-form-label col-md-4"><b>
                                                    Status</b></label>
                                            <input type="text" name="RESERVATION_STATUS" id="RESERVATION_STATUS"
                                                class="form-control" disabled />
                                        </div>
                                    </div>

                                    <div class="row g-3 ">
                                        <div class="col-md-4 mb-3">
                                            <label for="RSV_TRACE_DATE" class="col-form-label col-md-4"><b>Trace
                                                    Date *</b></label>
                                            <input class="form-control" type="text" placeholder="d-Mon-yyyy"
                                                id="RSV_TRACE_DATE" name="RSV_TRACE_DATE" />
                                        </div>

                                        <div class="col-md-4 ">
                                            <label for="RSV_TRACE_TIME" class="col-form-label col-md-4"><b>Time
                                                    *</b></label>
                                            <input class="form-control" type="time" placeholder="12:00"
                                                id="RSV_TRACE_TIME" name="RSV_TRACE_TIME" />
                                        </div>
                                        <div class="col-md-4">
                                            <label for="RSV_TRACE_DEPARTMENT"
                                                class="col-form-label col-md-5"><b>DEPARTMENT CODE *</b></label>
                                            <select id="RSV_TRACE_DEPARTMENT" name="RSV_TRACE_DEPARTMENT"
                                                class="select2 form-select form-select-lg"></select>
                                        </div>

                                    </div>
                                    <div class="row g-3 mb-3">

                                        <div class="col-md-12 ">
                                            <label for="RSV_TRACE_TEXT" class="col-form-label col-md-4"><b>TRACE TEXT
                                                    *</b></label>
                                            <textarea name="RSV_TRACE_TEXT" id="RSV_TRACE_TEXT" rows="5"
                                                class="form-control"></textarea>

                                        </div>


                                    </div>

                                    <div class="row g-3 ">
                                        <div class="col-md-3 mb-3">
                                            <div class="col-md-8 float-right">
                                                <button type="button" class="btn btn-success save-trace-detail">
                                                    <i class="fa-solid fa-floppy-disk"></i>&nbsp; Save
                                                </button>&nbsp;
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="border rounded p-4 mb-3">
                                        <div class="col-md-6 mb-3">
                                            <button type="button" class="btn btn-primary add-trace-detail">
                                                <i class="fa-solid fa-circle-plus"></i>&nbsp; Add New
                                            </button>&nbsp;
                                            <button type="button" class="btn btn-primary resolve-trace-detail"
                                                data-rel="1">
                                                <i class="fa-solid fa-check"></i>&nbsp; Resolve
                                            </button>&nbsp;

                                            <button type="button" class="btn btn-danger delete-trace-detail">
                                                <i class="fa-solid fa-ban"></i>&nbsp; Delete
                                            </button>&nbsp;
                                        </div>

                                        <div class="table-responsive text-nowrap">
                                            <table id="Trace_Details" class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="all">ID</th>
                                                        <th class="all">Date & Time</th>
                                                        <th class="all">Department</th>
                                                        <th class="all">Entered By</th>
                                                        <th class="all">Resolved By</th>
                                                        <th class="all">Resolved On</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <br />
                                    </div>
                                </div>
                                <div class="d-flex col-12 justify-content-between">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>

                                </div>

                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- /Modal Traces window -->

    <?= $this->include('includes/EditCustomerPopup') ?>
    <?= $this->include('includes/SharesPopup') ?>
    <?= $this->include('includes/SearchReservationPopup') ?>

    <?= $this->include('includes/CustomerMembershipPopup') ?>

    <?= $this->include('includes/CancelReservationPopup') ?>

    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->
<?= $this->include("Reservation/CompanyAgentModal") ?>
<script>
var compAgntMode = '';
var linkMode = '';
var windowmode = '';

// $(window).on('load', function() {
//    // $('.editReserWindow[data_sysid="2061"]').trigger('click');
// });

$(document).ready(function() {   
    <?php
  
    if(!empty($RESV_ID)) {  ?>    
        $(".editReserWindow").attr('data_sysid','<?php echo $RESV_ID; ?>');
        $(".editReserWindow").attr('data-reservation_customer_id','<?php echo $CUSTOMER_ID; ?>');
        $(".editReserWindow").click();
    <?php
    }
    ?>

    $('#Rate_info').DataTable({
        "ordering": false,
        "searching": false,
        autowidth: true,
        responsive: true
    });

    $('#Each_Package_Details').DataTable({
        "ordering": true,
        "searching": false,
        paging: true,
        autowidth: true,
        responsive: true
    });

    linkMode = 'EX';
    $('#loader_flex_bg').show();

    $('#dataTable_view').DataTable({
        'processing': true,
        'serverSide': true,
        'searching': false,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/reservationView') ?>',
            'type': 'POST',
            'data': function(d) {
                var formSerialization = $('.dt_adv_search').serializeArray();
                $(formSerialization).each(function(i, field) {
                    d[field.name] = field.value;
                });
            },
        },
        'columns': [{
                data: null,
                className: "text-center",
                "orderable": false,
                render: function(data, type, row, meta) {
                    var resvListButtons =
                        '<div class="d-inline-block flxy_option_view dropend">' +
                        '<a href="javascript:;" class="btn btn-sm btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                        '<ul class="dropdown-menu dropdown-menu-end">' +
                        '<li><a href="javascript:;" data_sysid="' + data['RESV_ID'] +
                        '" rmtype="' + data['RESV_RM_TYPE'] + '" rmtypedesc="' + data[
                            'RM_TY_DESC'] + '" data-reservation_customer_id = "' + data[
                            'CUST_ID'] +
                        '"  class="dropdown-item editReserWindow text-primary"><i class="fas fa-edit"></i> Edit</a></li>' +
                        '<div class="dropdown-divider"></div>' +
                        '<li><a href="javascript:;" data_sysid="' + data['RESV_ID'] +
                        '" rmtype="' + data['RESV_RM_TYPE'] + '" rmtypedesc="' + data[
                            'RM_TY_DESC'] + '" data-reservation_customer_id = "' + data[
                            'CUST_ID'] +
                        '"  class="dropdown-item reserOption text-success"><i class="fa-solid fa-align-justify"></i> Options</a></li>';

                    if ($.inArray(data['RESV_STATUS'], ["Checked-In", "Checked-Out"]) == -1)
                        resvListButtons += '<div class="dropdown-divider"></div>' +
                        '<li><a href="javascript:;" data_sysid="' + data['RESV_ID'] +
                        '" class="dropdown-item text-danger delete-record"><i class="fas fa-trash"></i> Delete</a></li>';

                    resvListButtons += '</ul>' +
                        '</div>';

                    return resvListButtons;
                }
            },
            {
                data: 'RESV_NO',
                className: "text-center"
            },
            {
                data: 'CUST_FIRST_NAME'
            },
            {
                data: 'RESV_ROOM',
                className: "text-center"
            },
            {
                data: 'RESV_ARRIVAL_DT',
                className: "text-center"
            },
            {
                data: 'RESV_DEPARTURE',
                className: "text-center"
            },
            // { data: 'RESV_FEATURE'},
            {
                data: 'RESV_STATUS',
                render: function(data, type, row, meta) {
                    var statClass = data == 'Cancelled' ? 'flxy_status_cncl' :
                        'flxy_status_cls';
                    return (
                        '<div class="' + statClass + '">' + data + '</div>'
                    );
                }
            },
            {
                data: 'RESV_NIGHT',
                className: "text-center"
            },
            {
                data: 'RESV_NO_F_ROOM',
                className: "text-center"
            },
            {
                data: 'RESV_RM_TYPE',
                className: "text-center"
            },
            {
                data: 'CUST_EMAIL'
            },
            {
                data: 'CUST_MOBILE'
            },
            {
                data: 'CUST_PHONE'
            },
        ],
        'autowidth': true,
        'order': [
            [1, "desc"]
        ],
        "fnInitComplete": function(oSettings, json) {
            $('#loader_flex_bg').hide();
        },
        language: {
            emptyTable: 'There are no reservations to display'
        }
    });

    $('.RESV_ARRIVAL_DT').datepicker({
        format: 'd-M-yyyy',
        autoclose: true
    });

    $('.RESV_DEPARTURE').datepicker({
        format: 'd-M-yyyy',
        autoclose: true
    });

    $('.CUST_DOB').datepicker({
        format: 'd-M-yyyy',
        autoclose: true
    });

    $('.CUST_DOC_ISSUE').datepicker({
        format: 'd-M-yyyy',
        autoclose: true
    });

    $('.CUST_DOC_EXPIRY').datepicker({
        format: 'd-M-yyyy',
        autoclose: true
    });

    $('#RESV_ARRIVAL_DT_PK').datepicker({
        format: 'd-M-yyyy',
        autoclose: true,
    });
    $('#RESV_ARRIVAL_DT_DO').datepicker({
        format: 'd-M-yyyy',
        autoclose: true,
    });

    $('#RSV_ITM_BEGIN_DATE').datepicker({
        format: 'd-M-yyyy',
        autoclose: true,
       
       
    });

    $('#RSV_ITM_END_DATE').datepicker({
        format: 'd-M-yyyy',
        autoclose: true,
       
       
    });

    $('.dateFieldItem').datepicker({
        format: 'd-M-yyyy',
        autoclose: true,
        
       
    });
    $('#FIXD_CHRG_BEGIN_DATE').datepicker({
        format: 'd-M-yyyy',
        autoclose: true,
       
    });
    $('#FIXD_CHRG_END_DATE').datepicker({
        format: 'd-M-yyyy',
        autoclose: true,
      
    });

    $('#RSV_PCKG_BEGIN_DATE').datepicker({
        format: 'd-M-yyyy',
        autoclose: true,
        
    });
    $('#RSV_PCKG_END_DATE').datepicker({
        format: 'd-M-yyyy',
        autoclose: true,
        
    });
    $('#RSV_TRACE_DATE').datepicker({
        format: 'd-M-yyyy',
        autoclose: true,
        
    });

    //$('#RSV_TRACE_TIME').timepicker();

    $.ajax({
        url: '<?php echo base_url('/roomTypeList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        // dataType:'json',
        success: function(respn) {
            $('#S_RESV_RM_TYPE').html(respn);
            $('.RESV_RM_TYPE').html(respn);
        }
    });


    loadCompanyList();

    loadAgentList();

    loadBlockList();

    $.ajax({
        url: '<?php echo base_url('/specialsList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        // dataType:'json',
        success: function(respn) {
            $('#RESV_SPECIALS').html(respn).selectpicker('refresh');
        }
    });

});



$(document).on('hide.bs.modal', '#compnayAgentWindow', function() {

    loadCompanyList();
    loadAgentList();
});

function loadCompanyList() {
    $.ajax({
        url: '<?php echo base_url('/companyList') ?>',
        async: false,
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        // dataType:'json',
        success: function(respn) {
            $('.RESV_COMPANY').html(respn);
        }
    });
}

function loadAgentList() {
    $.ajax({
        url: '<?php echo base_url('/agentList') ?>',
        async: false,
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        // dataType:'json',
        success: function(respn) {
            $('.RESV_AGENT').html(respn);
        }
    });
}

function loadBlockList() {
    $.ajax({
        url: '<?php echo base_url('/blockList') ?>',
        async: false,
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        // dataType:'json',
        success: function(respn) {
            $('.RESV_BLOCK').html(respn);
        }
    });
}

function generateRateQuery(mode = 'AVG') {
    var formData = {};
    $('.window-1').find('.input-group :input').each(function(i, data) {
        var field = $(data).attr('id');
        var values = $(this).val();
        formData[field] = values;
        formData['mode'] = mode;
    });

    // Room Class filter
    formData['RESV_ROOM_CLASS'] = $('.window-1').is(':visible') ? $('#RESV_ROOM_CLASS').val() : '';
    formData['RESV_FEATURE'] = $('.window-1').is(':visible') ? $('#RESV_FEATURE').val() : '';

    // Rate Code, Category, Class filters
    formData['RESV_RATE_CODE'] = $('.window-1').is(':visible') ? $('.window-1').find('#RESV_RATE_CODE').val() : '';
    formData['RESV_RATE_CODE_ROOM_TYPES'] = $('.window-1').is(':visible') ? $('.window-1').find('#RESV_RATE_CODE').find(
        ':selected').attr('data-rc-roomtypes') : '';

    formData['RESV_RATE_CATEGORY'] = $('.window-1').is(':visible') ? $('.window-1').find('#RESV_RATE_CATEGORY').val() :
        '';
    formData['RESV_RATE_CLASS'] = $('.window-1').is(':visible') ? $('.window-1').find('#RESV_RATE_CLASS').val() : '';

    // Closed and Day Use filters

    formData['resv_room_type'] = $('#RESV_RM_TYPE').val();
    formData['resv_rate'] = $('#RESV_RATE').val();

    formData['closed'] = $('#closedRateFilter').is(':checked') ? '1' : '0';
    formData['day_use'] = $('#dayUseRateFilter').is(':checked') ? '1' : '0';

    $.ajax({
        url: '<?php echo base_url('/getRateQueryData') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: formData,
        dataType: 'json',
        success: function(respn) {
            $('#rateQueryTable').html(respn[0]);

            var activeCol = $('.clickPrice.active');
            var currentTDIndex = activeCol.index();

            var roomTypeInfo = $('#rateQueryTable tr').eq(0).find('td').eq(currentTDIndex).data(
                'roomtype-info');
            var rateCodeInfo = activeCol.data('rate-info');

            getRoomRateDetails(rateCodeInfo, roomTypeInfo);

            checkArrivalDate();
        }
    });
}

function avaiableDatePeriod() {
    var arrival = $('.window-2').is(':visible') ? $('.window-2').find('.RESV_ARRIVAL_DT').val() : $('.window-1')
        .find('.RESV_ARRIVAL_DT').val();
    var departure = $('.window-2').is(':visible') ? $('.window-2').find('.RESV_DEPARTURE').val() : $('.window-1')
        .find('.RESV_DEPARTURE').val();

    var night = $('#RESV_NIGHT').val();
    var nofroom = $('#RESV_NO_F_ROOM').val();
    var adult = $('#RESV_ADULTS').val();
    var children = $('#RESV_CHILDREN').val();
    var ulli = '<li>' + moment(arrival, 'DD-MMM-YYYY').format('dddd') + ', </li>';
    ulli += '<li>&nbsp;' + moment(arrival, 'DD-MMM-YYYY').format('MMMM D YYYY') + ', </li>';
    ulli += '<li>&nbsp;' + night + ' Night, </li>';
    ulli += '<li>&nbsp;' + nofroom + ' Rooms, </li>';
    ulli += '<li>&nbsp;' + adult + ' Adults </li>';
    ulli += (children != 0 ? '<li>,&nbsp;' + children + ' Children</li>' : '');
    return '<ul class="flxy_row">' + ulli + '</ul>';
}

function next() {
    var fetchInfo = avaiableDatePeriod();
    $('#userInfoDate').html(fetchInfo);
    generateRateQuery();
    $('.rateRadio').prop('checked', false);
    $('.rateRadio:first').prop('checked', true);
    $('#rateQueryWindow').modal('show');
}

function getRateQuery() {
    var fetchInfo = avaiableDatePeriod();
    $('#userInfoDate').html(fetchInfo);
    generateRateQuery();
    $('.rateRadio').prop('checked', false);
    $('.rateRadio:first').prop('checked', true);
    $('#rateQueryWindow').modal('show');
}

$(document).on('click', '.clickPrice', function() {
    $('#rateQueryTable .active').removeClass('active');
    $(this).addClass('active');
    var value = $(this).find('input').val();

    var currentTDIndex = $(this).index();

    var roomTypeInfo = $('#rateQueryTable tr').eq(0).find('td').eq(currentTDIndex).data('roomtype-info');
    var rateCodeInfo = $(this).data('rate-info');

    getRoomRateDetails(rateCodeInfo, roomTypeInfo);
});

function getRoomRateDetails(rateCodeInfo, roomTypeInfo) {
    var info = '';
    if (rateCodeInfo)
        info += rateCodeInfo + ', ';

    if (roomTypeInfo)
        info += roomTypeInfo;

    info = info == '' ? 'None' : info;

    $('#roomRateInfo').html(info); //alert(info);
}

function selectRate() {

    //Update membership select 
    fillCustomerMemberships($('#CM_CUST_ID').val(), 'edit', '[name="RESV_MEMBER_TY"]');
    $('[name="RESV_MEMBER_TY"]').val($('#RESV_MEMBER_TY_ADD').val()).trigger('change').trigger('select2:select');

    $('[name="RESV_COMPANY"]').val($('#RESV_COMPANY_ADD').val()).trigger('change').trigger('select2:select');
    $('[name="RESV_AGENT"]').val($('#RESV_AGENT_ADD').val()).trigger('change').trigger('select2:select');
    $('#RESV_EXT_PURP_STAY').val($('#RESV_PURPOSE_STAY').val()).trigger('change').trigger('select2:select');

    if ($('.window-1').is(':visible')) {
        $('.window-2').find('.RESV_ARRIVAL_DT').datepicker({
            format: 'd-M-yyyy',
            autoclose: true
        }).datepicker("setDate", $('.window-1').find(
            '.RESV_ARRIVAL_DT').val());
        $('.window-2').find('.RESV_DEPARTURE').datepicker({
            format: 'd-M-yyyy',
            autoclose: true
        }).datepicker("setDate", $('.window-1').find(
            '.RESV_DEPARTURE').val());
    }

    $('#submitResrBtn').removeClass('submitResr');
    runInitializeConfig();

    $('#errorModal').hide();

    var activeRow = $('.clickPrice.active');
    var rmtype = $(activeRow).find('#ROOMTYPE').val();
    var rmprice = $(activeRow).find('#ACTUAL_ADULT_PRICE').val();
    var rateCode = $(activeRow).parent('.ratePrice').find('#RT_DESCRIPTION').val();
    $('[name="RESV_RATE_CODE"]').val(rateCode);
    $('#RESV_RATE').val(rmprice);
    localStorage.setItem('activerate', rmprice);

    if (rmtype == '' || rmtype == undefined) {
        $('#errorModal').show();
        var error = '<ul>';
        error += '<li>Please select a room type</li>';
        error += '<ul>';
        $('#formErrorMessage').html(error);
        return;
    } else {
        $('#errorModal').hide();
        $('#rateQueryWindow').modal('hide');
        $('.window-1,#nextbtn').hide();
        $('.window-2,#previousbtn').show();
        $('#submitResrBtn').removeClass('submitResr');
        $('.window-2').find('.nav a:first').tab('show'); // Make first tab of Edit form active

        runInitializeConfig();
        $.ajax({
            url: '<?php echo base_url('/getRoomTypeDetails') ?>',
            type: "post",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                rmtype: rmtype
            },
            dataType: 'json',
            success: function(respn) {
                var dataSet = respn[0];
                var option = '<option data-feture="' + $.trim(dataSet['RM_TY_FEATURE']) + '" data-desc="' +
                    $
                    .trim(dataSet['RM_TY_DESC']) + '" data-rmclass="' + $.trim(dataSet[
                        'RM_TY_ROOM_CLASS']) +
                    '" value="' + dataSet['RM_TY_CODE'] + '">' + dataSet['RM_TY_DESC'] + '</option>';
                $('#RESV_RTC').html(option).selectpicker('refresh');
                $('#RESV_RM_TYPE').val(dataSet['RM_TY_CODE']).trigger('change');
            }
        });
    }
}

function previous() {
    $('.window-1,#nextbtn').show();
    $('.window-2,#previousbtn').hide();
    $('#submitResrBtn').addClass('submitResr');
}

$(document).on('keyup', '.RESV_NIGHT,.RESV_NO_F_ROOM,.RESV_ADULTS,.RESV_CHILDREN,.RESV_MEMBER_NO', function() {
    var value = $(this).val();
    var name = $(this).attr('id');
    $('[name="' + name + '"]').val(value);
});

$(document).on('change', '.RESV_ARRIVAL_DT,.RESV_DEPARTURE', function() {
    var value = $(this).val();
    var name = $(this).attr('id');
    $('[name="' + name + '"]').val(value);
    setNights($(this), $(this).val());

    if ($(this).hasClass('RESV_ARRIVAL_DT')) {
        $('#RESV_ARRIVAL_DT_PK,#RESV_ARRIVAL_DT_DO').datepicker({
            format: 'd-M-yyyy',
            autoclose: true
        }).datepicker("setDate", $(this).val());
    }
});

$(document).on('blur', '.RESV_NIGHT', function() {
    var new_days = $(this).val();
    var startField = $(this).closest('.col-md-3').prev('.col-md-6').find('.RESV_ARRIVAL_DT');
    var endField = $(this).closest('.col-md-3').prev('.col-md-6').find('.RESV_DEPARTURE');
    var new_date = moment(startField.val(), "DD-MMM-YYYY").add(new_days, 'days').format("DD-MMM-YYYY");

    //alert(startField.val()); alert(new_date);

    if ($.inArray($('#RESV_STATUS').val(), ["Cancelled", "Checked-Out"]) == -1) {
        endField.datepicker({
            format: 'd-M-yyyy',
            autoclose: true
        }).datepicker("setDate", new_date);
    }
    //alert(value);
});


function setNights(element, val) {
    var arr_date = element.hasClass('RESV_ARRIVAL_DT') ? val : element.closest('.input-group').find('.RESV_ARRIVAL_DT')
        .val();
    var dep_date = element.hasClass('RESV_DEPARTURE') ? val : element.closest('.input-group').find('.RESV_DEPARTURE')
        .val();

    //alert(arr_date); alert(dep_date);

    var startDtFmt = moment(arr_date, 'DD-MMM-YYYY');
    var endDtFmt = moment(dep_date, 'DD-MMM-YYYY');

    if (startDtFmt <= endDtFmt) {
        var no_of_nights = parseInt(endDtFmt.diff(startDtFmt, 'days'));
        //alert(no_of_nights);


        var endField = element.hasClass('RESV_DEPARTURE') ? element : element.closest('.input-group').find(
            '.RESV_DEPARTURE');
        endField.closest('.col-md-6').next('.col-md-3').find('.RESV_NIGHT').val(no_of_nights);

        //$('.RESV_NIGHT').val(no_of_nights);
    }
}

$(document).on('blur', '#RESV_RATE', function() {
    $('#RESV_FIXED_RATE_CHK').prop('checked', true);
    $('#RESV_FIXED_RATE').val('Y');
});
var ressysId = '';
var roomType = '';
var roomTypedesc = '';
var reservation_customer_id = '';

$(document).on('click', '.reserOption', function() {
    ressysId = $(this).attr('data_sysid');
    roomType = $(this).attr('rmtype');
    roomTypedesc = $(this).attr('rmtypedesc');
    reservation_customer_id = $(this).data('reservation_customer_id');

    $('#optionsResrBtn').attr({
        'data-reservation_customer_id': reservation_customer_id
    });

    $('#Accompany').show();

    //$('#Addon').hide();
    $('#Addon,#reservationW').modal('hide');
    $('#optionWindow').modal('show');
    $('.profileSearch').find('input,select').val('');
    windowmode = 'AC';
    customPop = '';

    $('#registerCardButton').attr('data_sysid', ressysId);
    $('#fixedChargeButton').attr('data_sysid', ressysId);
    $('#proformaButton').attr('data_sysid', ressysId);
    $('#rateInfoButton').attr('data_sysid', ressysId);
    $('#traceButton').attr('data_sysid', ressysId);

    $('.cancel-reservation,.reinstate-reservation').attr({
        'data_sysid': ressysId,
        'data_custId': reservation_customer_id
    });

    displayResvOptionButtons(ressysId);
});

function displayResvOptionButtons(ressysId) {
    // Reservation Specific Option display

    $('.flxy_opt_btn > .btn').removeClass('d-none').prop('disabled', false); // Show all buttons by default
    $('.checkout-reservation').addClass('d-none').prop('disabled', true);

    $.ajax({
        url: '<?php echo base_url('/getReservDetails') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        async: false,
        data: {
            reservID: ressysId,
        },
        dataType: 'json',
        success: function(respn) {

            var arrivalDate = new Date(respn.RESV_ARRIVAL_DT);
            var departureDate = new Date(respn.RESV_DEPARTURE);
            var currentDate = new Date();

            $('.cancel-reservation,.reinstate-reservation').attr({
                'data_resv_no': respn.RESV_NO
            });

            $('#optionWindowLable').html('Reservation Options of ' + respn.RESV_NO);

            if (respn.RESV_STATUS == 'Cancelled') {
                $('.cancel-reservation').addClass('d-none').prop('disabled', true);
                $('.reinstate-reservation').removeClass('d-none').prop('disabled', false);
                $('.accompany-guests,#proformaButton,#registerCardButton,.shares-btn').prop(
                    'disabled', true);
            } else {
                $('.reinstate-reservation').addClass('d-none').prop('disabled', true);

                if (respn.RESV_STATUS == 'Due Pre Check-In' || respn.RESV_STATUS ==
                    'Pre Checked-In')
                    $('.cancel-reservation').removeClass('d-none').prop('disabled', false);
                else {
                    $('.cancel-reservation').addClass('d-none').prop('disabled', true);
                    if (respn.RESV_STATUS == 'Checked-In' || respn.RESV_STATUS ==
                        'Checked-Out-Requested') {
                        $('.checkout-reservation').removeClass('d-none');
                        if (currentDate >= departureDate) {
                            $('.checkout-reservation').prop('disabled', false);
                        }
                    } else if (respn.RESV_STATUS == 'Checked-Out') {
                        $('.accompany-guests,#proformaButton').prop(
                            'disabled', true);
                    }
                }
            }
        }
    });
}

$(document).on('click', '.editReserWindow,#triggCopyReserv', function(event, param, paramArr, rmtype) {
    

    var sysid = $(this).attr('data_sysid');
    reservation_customer_id = $(this).attr('data-reservation_customer_id');
    ////Item Inventory
    // showInventoryItems();
    itemClassList();

    $('#reservationForm').removeClass('was-validated');
    $(':input', '#reservationForm').val('').prop('checked', false).prop('selected', false).prop('disabled',
        false);
    $(':input', '#reservationForm').not(
        '#CUST_FIRST_NAME,#RESV_MEMBER_NO,#RESV_GUST_BAL,#RESV_ROOM_ID,#RESV_RATE_CODE,#RESV_PACKAGE,#RESV_INV_ITEM,#PACKAGE_EXCLUDE,#RSV_PCKG_POST_RYTHM,RSV_PCKG_CALC_RULE'
    ).prop('readonly', false);
    $('#RESV_NAME').html('<option value="">Select</option>').selectpicker('refresh');

    runSupportingResevationLov();
    runInitializeConfig();
    runCountryList();

    $('.window-1,#nextbtn,#previousbtn').hide();
    $('.window-2').show();
    //$('.flxyFooter').removeClass('flxy_space');
    $('#submitResrBtn').removeClass('submitResr');
    $('#reservationW').modal('show');

    $('#reservationWlable').html('Edit Reservation');

    
    $('#ITEM_RESV_ID').val(sysid);
    $('#FIXCHRG_RESV_ID').val(sysid);
    $('#PCKG_RESV_ID').val(sysid);


    clearFormFields('#select_items');
    showInventoryItems(sysid);
    showPackages(sysid);

    var mode = '';
    if (param) {
        sysid = param;
        mode = 'CPY';
        $('#reservationWlable').html('Add New Reservation');
        $('#submitResrBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
        $('#optionsResrBtn').hide();
    } else {
        $('#submitResrBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
        $('#optionsResrBtn').show();
        $('#optionsResrBtn').attr({
            'data_sysid': sysid,
            'rmtype': $(this).attr('rmtype'),
            'rmtypedesc': $(this).attr('rmtypedesc'),
            'data-reservation_customer_id': reservation_customer_id
        })
    }

    $.ajax({
        url: '<?php echo base_url('/editReservation') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            sysid: sysid,
            mode: mode,
            paramArr: paramArr
        },
        dataType: 'json',
        success: function(respn) {

            $(respn).each(function(inx, data) {
                $.each(data, function(fields, datavals) {
                    var field = $.trim(fields); //fields.trim();
                    var dataval = $.trim(datavals); //datavals.trim();
                    if (field == 'RESV_NAME_DESC' || field == 'RESV_COMPANY_DESC' ||
                        field == 'RESV_AGENT_DESC' || field == 'RESV_BLOCK_DESC') {
                        return true;
                    };
                    if (field == 'RESV_NAME' || field == 'RESV_RTC') {
                        var option = '<option value="' + dataval + '">' + data[
                            field + '_DESC'] + '</option>';
                        $('*#' + field).html(option).selectpicker('refresh');

                        if (field == 'RESV_NAME' && dataval != '') {
                            fillCustomerMemberships(dataval, 'edit',
                                '[name="RESV_MEMBER_TY"]');
                            $('#CM_CUST_ID').val(dataval);
                        }
                    } else if (field == 'RESV_CUST_MEMBERSHIP') {
                        $('[name="RESV_MEMBER_TY"]').val(dataval).trigger('change');

                    } else if (field == 'RESV_CONFIRM_YN' || field ==
                        'RESV_PICKUP_YN' || field == 'RESV_DROPOFF_YN' || field ==
                        'RESV_EXT_PRINT_RT' || field == 'RESV_FIXED_RATE' ||
                        field == 'RESV_NO_POST') {
                        if (dataval == 'Y') {
                            $('#' + field + '_CHK').prop('checked', true);
                        } else {
                            $('#' + field + '_CHK').prop('checked', false)
                        }
                        $('#' + field).val(dataval);
                    } else if (field == 'RESV_ARRIVAL_DT' || field ==
                        'RESV_DEPARTURE') {
                        $('.' + field).datepicker({
                            format: 'd-M-yyyy',
                            autoclose: true
                        }).datepicker("setDate", dataval);

                    } else if (field == 'CUST_VIP' || field == 'RESV_BLOCK') {
                        $('.' + field).select2("val", dataval);
                    } else {
                        if (field == 'RESV_SPECIALS') {
                            var dataval = dataval.split(',');
                        }

                        $('*#' + field).val(dataval).trigger('change');
                        if (field == 'CUST_COUNTRY' || field == 'RESV_SPECIALS') {
                            $('*#' + field).selectpicker('refresh');
                        }
                    }
                });
            });

            if (mode == 'CPY') {

                var nights = parseInt($('#RESV_NIGHT').val());
                nights = nights != 0 ? nights : 1;

                var today = moment().format('DD-MM-YYYY');
                var end = moment().add(nights, 'days').format('DD-MM-YYYY');
                $('.RESV_ARRIVAL_DT').datepicker({
                    format: 'd-M-yyyy',
                    autoclose: true
                }).datepicker("setDate", today);
                $('.RESV_DEPARTURE').datepicker({
                    format: 'd-M-yyyy',
                    autoclose: true
                }).datepicker("setDate", end);
            }

            checkArrivalDate();

            $('.RESV_ARRIVAL_DT,.RESV_DEPARTURE').removeClass('is-valid');

            // Disable all fields if 'Cancelled'
            if ($('#RESV_STATUS').val() == 'Cancelled' || $('#RESV_STATUS').val() ==
                'Checked-Out') {
                $(':input', '#reservationForm').prop('readonly', true);
                $('#reservationForm .select2,#reservationForm .selectpicker,#reservationForm input[type=checkbox],#reservationForm .flxi_btn,#reservationForm #submitResrBtn')
                    .prop('disabled', true);
                $('#reservationForm .selectpicker').selectpicker('refresh');
                $('.RESV_ARRIVAL_DT,.RESV_DEPARTURE').datepicker("destroy");
            } else if ($('#RESV_STATUS').val() == 'Checked-In') {
                $('.RESV_NAME,#CUST_FIRST_NAME,#RESV_RESRV_TYPE,#RESV_NO_F_ROOM,#RESV_ETA,#RESV_RM_TYPE,#RESV_ROOM')
                    .prop('readonly', true);
                $('.RESV_ARRIVAL_DT').datepicker("destroy");
            }
        }
    });
});

$(document).on('click', '.delete-record', function() {
    var sysid = $(this).attr('data_sysid');
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
                    url: '<?php echo base_url('/deleteReservation') ?>',
                    type: "post",
                    data: {
                        sysid: sysid
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    dataType: 'json',
                    success: function(respn) {

                        $('#dataTable_view').dataTable().fnDraw();
                    }
                });
            }
        }
    });
});

$(document).on('change', '.switch-input', function() {
    var thiss = $(this);
    var checkWhich = $(this).attr('id');
    if (thiss.is(':checked')) {
        thiss.next().val('Y');
    } else {
        if (checkWhich == 'RESV_FIXED_RATE_CHK') {
            var previousRate = localStorage.getItem('activerate');
            $('#RESV_RATE').val(previousRate);
        }
        thiss.next().val('N');
    }
});

function childReservation(param) {
    if (param == 'C') {
        $('#customerForm').find('input,select').val('');
        windowmode = 'C';
        customPop = '';

        showCustomerRow($('.window-2').is(':visible') ? $('.window-2').find('#RESV_NAME')
            .val() : $('.window-1')
            .find('#RESV_NAME').val());
    } else if (param == 'AC') {
        $('.profileSearch').find('input,select').val('');
        $('#searchRecord').html(
            '<tr><td class="text-left" colspan="11" style="padding-left: 20% !important;">No Record Found</td></tr>'
        );
    }

    $('.profileCreate').hide();
    $('.profileSearch').show();
    $('#reservationChildlable').html('Search Customer');
    $('#reservationChild').modal('show');
    runCountryList();
    runSupportingLov();
    //$('#optionWindow').modal('hide');
}

function addResvation() {
    ////Item Inventory
    showInventoryItems();
    itemClassList();
    RateClassList();

    clearFormFields('#select_items');
    $(':input', '#reservationForm').val('').prop('checked', false).prop('selected', false).prop('disabled',
        false);
    $('#RESV_NAME').html('<option value="">Select</option>').selectpicker('refresh');
    $('.select2').val(null).trigger('change');

    $('#reservationW').modal('show');
    $('#reservationForm').removeClass('was-validated');
    $('#ITEM_RESV_ID', '#RSV_ID').val('');

    $('#reservationWlable').html('Add New Reservation');
    runSupportingResevationLov();
    $('.window-1,#nextbtn').show();
    //$('.flxyFooter').addClass('flxy_space');
    $('#submitResrBtn').addClass('submitResr');
    $('#submitResrBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    $('.window-2,#previousbtn,#optionsResrBtn').hide();

    runCountryList();

    fillMembershipTypes('', 'add', '#RESV_MEMBER_TY_ADD');

    var today = moment().format('DD-MM-YYYY');
    var end = moment().add(1, 'days').format('DD-MM-YYYY');
    $('.RESV_ARRIVAL_DT').datepicker({
        format: 'd-M-yyyy',
        autoclose: true
    }).datepicker("setDate", today);
    $('.RESV_DEPARTURE').datepicker({
        format: 'd-M-yyyy',
        autoclose: true
    }).datepicker("setDate", end);

    $('#RESV_NIGHT,#RESV_NO_F_ROOM,#RESV_ADULTS').val('1');
    $('#RESV_CONFIRM_YN,#RESV_NO_POST,#RESV_PICKUP_YN,#RESV_DROPOFF_YN,#RESV_EXT_PRINT_RT,#RESV_FIXED_RATE').val('N');
}

$(document).on('change', '.flxCheckBox', function() {
    var checked = $(this).is(':checked');
    var parent = $(this).parent();
    if (checked) {
        parent.find('input[type=hidden]').val('Y');
    } else {
        parent.find('input[type=hidden]').val('N');
    }

    if ($(this).attr('id') == 'RESV_CLOSED_CHK')
        $('#closedRateFilter').prop('checked', checked);
    else if ($(this).attr('id') == 'RESV_DAY_USE_CHK')
        $('#dayUseRateFilter').prop('checked', checked);

});

$(document).on('change', '*#RESV_NAME', function() {

    var custId = $(this).find('option:selected').val();
    var thisActive = $(this).hasClass('activeName')
    thisActive ? '' : $('[name="RESV_NAME"]').val(custId).selectpicker('refresh');
    var url = '<?php echo base_url('/getCustomerDetail') ?>';
    $.ajax({
        url: url,
        type: "post",
        data: {
            custId: custId
        },
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        success: function(respn) {
            if (respn != '') {
                var json = respn[0];

                $('#CUST_FIRST_NAME').val($.trim(json.CUST_FIRST_NAME));
                $('#CUST_TITLE').val($.trim(json.CUST_TITLE));
                $('#CUST_COUNTRY').val($.trim(json.CUST_COUNTRY)).selectpicker('refresh');
                $('.CUST_VIP').select2("val", $.trim(json.CUST_VIP));
                $('#CUST_PHONE').val($.trim(json.CUST_PHONE));

                fillCustomerMemberships(custId, 'edit', $('.window-2').is(':visible') ?
                    '[name="RESV_MEMBER_TY"]' : '#RESV_MEMBER_TY_ADD');
                $('#CM_CUST_ID').val(custId);

            } else {
                $('#CUST_FIRST_NAME,#CUST_TITLE,#CUST_COUNTRY,#CUST_PHONE').val('');
                $('.CUST_VIP').val(null).trigger('change');
            }
        }
    });
});

// validation start //  
// var validUsername = false;
// var membertype = document.getElementsByName("RESV_MEMBER_NO")[0];
// membertype.addEventListener("keyup", () => {
//     let regex = /^[a-zA-Z]([0-9a-zA-Z]){1,10}$/;
//     let str = membertype.value;
//     if (regex.test(str)) {
//       membertype.classList.remove("isfx-invalid");
//       membertype.nextElementSibling.innerHTML="";
//         validUsername = false;
//     }else if(str==''){
//       membertype.nextElementSibling.innerHTML="Not empty number";
//       membertype.classList.add("isfx-invalid");
//         validUsername = true;
//     } else {
//       membertype.nextElementSibling.innerHTML="Allow only number";
//       membertype.classList.add("isfx-invalid");
//         validUsername = true;
//     }
// });

function reservationValidate(event, id, mode) {
    // membertype.dispatchEvent(new Event('keyup'));
    event.preventDefault();
    var form = document.getElementById(id);
    var condition = (mode == 'R' ? !form.checkValidity() || !checkArrivalDate() || !checkDeparturDate() : !form
        .checkValidity());
    if (mode == 'R') {
        var additionValid = checkPaymentValid();
    } else {
        var additionValid = false;
    }
    form.classList.add('was-validated');

    if (condition || additionValid) { // -- customize validate user validUsername
        return false;
    } else {
        return true;
    }
}

function checkPaymentValid() {
    var payment = $('#RESV_PAYMENT_TYPE').val();
    if (payment == '') {
        $('#RESV_PAYMENT_TYPE').parent('div').removeClass('is-valid').addClass('is-invalid');
        return true;
    } else {
        $('#RESV_PAYMENT_TYPE').parent('div').removeClass('is-invalid').addClass('is-valid');
        return false;
    }
}

$(document).on('change', '#RESV_ARRIVAL_DT', function() {
    checkArrivalDate();
});
$(document).on('change', '#RESV_DEPARTURE', function() {
    checkDeparturDate();
});

function checkArrivalDate() {
    var startField = $('[name="RESV_ARRIVAL_DT"]');
    var endField = $('[name="RESV_DEPARTURE"]');
    var startDt = $(startField).val();
    var endDt = $(endField).val();
    var startDtFmt = moment(startDt, 'DD-MMM-YYYY');
    var endDtFmt = moment(endDt, 'DD-MMM-YYYY');

    if (startDtFmt <= endDtFmt) {
        $(startField).removeClass("is-invalid");
        $(startField).addClass("is-valid");
        $(startField)[0].setCustomValidity("");
        return true;
    } else {
        $(startField).removeClass("is-valid");
        $(startField).addClass("is-invalid");
        $(startField)[0].setCustomValidity("invalid");
        return false;
    }
}

function checkDeparturDate() {
    var startField = $('[name="RESV_ARRIVAL_DT"]');
    var endField = $('[name="RESV_DEPARTURE"]');
    var startDt = startField.val();
    var endDt = endField.val();
    var startDtFmt = moment(startDt, 'DD-MMM-YYYY');
    var endDtFmt = moment(endDt, 'DD-MMM-YYYY');
    if (endDtFmt >= startDtFmt) {
        endField.removeClass("is-invalid");
        endField.addClass("is-valid");
        $(endField)[0].setCustomValidity("");
        return true;
    } else {
        endField.removeClass("is-valid");
        endField.addClass("is-invalid");
        $(endField)[0].setCustomValidity("invalid");
        return false;
    }
}
// validation end //

function submitForm(id, mode, event) {
    var validate = reservationValidate(event, id, mode);
    if (!validate) {
        return false;
    }

    $('#loader_flex_bg').show();
    $('#errorModal').hide();
    var formSerialization = $('#' + id).serializeArray();

    if (mode == 'R') {
        var url = '<?php echo base_url('/insertReservation') ?>';
        // Change membership type and id
        var formType = $('.window-2').is(':visible') ? 'edit' : 'add';
        var cust_membership = $('[name="RESV_MEMBER_TY"]').val();
        var membership_code = $('[name="RESV_MEMBER_TY"]').find(':selected').attr('membership-type');

        if (membership_code) {
            formSerialization.find(function(input) {
                return input.name == 'RESV_MEMBER_TY';
            }).value = membership_code;

            formSerialization.push({
                name: 'RESV_CUST_MEMBERSHIP',
                value: cust_membership
            });
        }

    } else {
        var url = '<?php echo base_url('/insertCustomer') ?>';
    }

    $.ajax({
        url: url,
        type: "post",
        data: formSerialization,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        success: function(respn) {
            var response = respn['SUCCESS'];
            $('#loader_flex_bg').hide();
            if (response != '1') {
                $('#errorModal').show();
                var ERROR = respn['RESPONSE']['ERROR'];
                var error = '<ul>';
                $.each(ERROR, function(ind, data) {

                    error += '<li>' + data + '</li>';
                });
                error += '<ul>';
                $('#formErrorMessage').html(error);
            } else {
                if (mode == 'C') {
                    var response = respn['RESPONSE']['OUTPUT'];
                    $('#reservationChild').modal('hide');
                    var option = '<option value="' + response['ID'] + '">' + response['FULLNAME'] +
                        '</option>';
                    $('*#RESV_NAME').html(option).selectpicker('refresh');
                    $('*#CUST_TITLE').val(response['CUST_TITLE']);
                    $('*#CUST_FIRST_NAME').val(response['CUST_FIRST_NAME']);
                    $('.CUST_VIP').val(response['CUST_VIP']).trigger('change');
                    $('*#CUST_PHONE').val(response['CUST_PHONE']);
                    $('*#CUST_COUNTRY').val(response['CUST_COUNTRY']).selectpicker('refresh');
                    var joinVaribl = windowmode + customPop;
                    if (joinVaribl == 'AC-N') {
                        custId = response['ID'];
                        $('#customeTrigger').trigger('click');
                    }
                } else {
                    var response = respn['RESPONSE']['REPORT_RES'][0];
                    var confirmationNo = response['RESV_NO'];
                    bootbox.alert({
                        message: '<b>Confirmation Number : </b>' + confirmationNo + '',
                        size: 'small'
                    });
                    $('#reservationW').modal('hide');
                    $('#dataTable_view').dataTable().fnDraw();
                }
            }
        }
    });
}

function runCountryList(type) {
    $.ajax({
        url: '<?php echo base_url('/countryList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        // dataType:'json',
        success: function(respn) {
            if (type == 'COMPANY') {
                $('#COM_COUNTRY').html(respn).selectpicker('refresh');
            } else {
                $('*#CUST_COUNTRY').html(respn).selectpicker('refresh');
                $('#CUST_NATIONALITY').html(respn);
            }
        }
    });
}

function runSupportingLov() {
    $.ajax({
        url: '<?php echo base_url('/getSupportingLov') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        success: function(respn) {
            var vipData = respn[0];
            var busegmt = respn[1];
            var option = '<option value="">Select Vip</option>';
            var option2 = '<option value="">Select Segment</option>';
            $(vipData).each(function(ind, data) {
                option += '<option value="' + data['VIP_ID'] + '">' + data['VIP_DESC'] +
                    '</option>';
            });
            $(busegmt).each(function(ind, data) {
                option2 += '<option value="' + data['BUS_SEG_CODE'] + '">' + data['BUS_SEG_DESC'] +
                    '</option>';
            });
            $('.CUST_VIP').html(option);
            $('#CUST_BUS_SEGMENT').html(option2);
        }
    });
}

$(document).on('keyup', '.COPY_RM_TYPE .form-control', function() {
    var search = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/roomTypeList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            search: search
        },
        // dataType:'json',
        success: function(respn) {
            $('#COPY_RM_TYPE').html(respn).selectpicker('refresh');
        }
    });
});

$(document).on('keyup', '.RESV_RTC .form-control', function() {
    var search = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/roomTypeList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            search: search
        },
        // dataType:'json',
        success: function(respn) {

            $('#RESV_RTC').html(respn).selectpicker('refresh');
        }
    });
});

$(document).on('change', '#RESV_RM_TYPE,#RESV_RTC', function() {
    var feature = $(this).find('option:selected').attr('data-feture');
    $('[name="RESV_FEATURE"]').val(feature);

    if ($(this).attr('id') == 'RESV_RM_TYPE') {

        var room_type = $(this).find('option:selected').data('room-type-id');

        $.ajax({
            url: '<?php echo base_url('/roomList') ?>',
            async: false,
            type: "post",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                room_type: room_type
            },
            // dataType:'json',
            success: function(respn) {

                $('*#RESV_ROOM').html(respn);
            }
        });
    }
});

$(document).on('change', '#RESV_ROOM', function() {

    var room_id = $(this).find('option:selected').data('room-id');
    $('#RESV_ROOM_ID').val(room_id);
});

/*
    $(document).on('keyup', '.RESV_BLOCK .form-control', function() {
        var search = $(this).val();
        $.ajax({
            url: '<?php echo base_url('/blockList') ?>',
            type: "post",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                search: search
            },
            // dataType:'json',
            success: function(respn) {

                $('*#RESV_BLOCK').html(respn).selectpicker('refresh');
            }
        });
    });
*/

/*
$(document).on('keyup', '.RESV_COMPANY .form-control', function() {
    var search = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/companyList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            search: search
        },
        // dataType:'json',
        success: function(respn) {

            $('*#RESV_COMPANY').html(respn).selectpicker('refresh');
        }
    });
});

$(document).on('keyup', '.RESV_AGENT .form-control', function() {
    var search = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/agentList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            search: search
        },
        // dataType:'json',
        success: function(respn) {

            $('*#RESV_AGENT').html(respn).selectpicker('refresh');
        }
    });
});
*/


$(document).on('keyup', '.RESV_NAME .form-control', function() {
    var search = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/customerList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            search: search
        },
        // dataType:'json',
        success: function(respn) {

            $('*#RESV_NAME').html(respn).selectpicker('refresh');
        }
    });
});

/*
$(document).on('keyup', '.RESV_ROOM .form-control', function() {
    var search = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/roomList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            search: search
        },
        // dataType:'json',
        success: function(respn) {

            $('*#RESV_ROOM').html(respn).selectpicker('refresh');
        }
    });
});
*/


$(document).on('change', '#CUST_COUNTRY', function() {
    var ccode = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/stateList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            ccode: ccode
        },
        // dataType:'json',
        success: function(respn) {
            $('#CUST_STATE').html(respn).selectpicker('refresh');
        }
    });
});
$(document).on('change', '#CUST_STATE', function() {
    var scode = $(this).val();
    var ccode = $('#customerForm #CUST_COUNTRY').find('option:selected').val();
    $.ajax({
        url: '<?php echo base_url('/cityList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            ccode: ccode,
            scode: scode
        },
        // dataType:'json',
        success: function(respn) {
            $('*#CUST_CITY').html(respn).selectpicker('refresh');
        }
    });
});

function runSupportingResevationLov() {

    $.ajax({
        url: '<?php echo base_url('/getSupportingReservationLov') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        async: false,
        success: function(respn) {
            var memData = respn[0];
            var idArray = ['RESV_MEMBER_TY', 'RESV_RATE_CLASS', 'RESV_RATE_CODE', 'RESV_ROOM_CLASS',
                'RESV_FEATURE', 'RESV_PURPOSE_STAY', 'CUST_VIP', 'RESV_TRANSPORT_TYP', 'RESV_GUST_TY',
                'RESV_ENTRY_PONT', 'RESV_PROFILE'
            ];
            $(respn).each(function(ind, data) {

                var option = '<option value="">Select</option>';
                $.each(data, function(i, valu) {
                    var value = $.trim(valu['CODE']); //fields.trim();
                    var desc = $.trim(valu['DESCS']); //datavals.trim();

                    option += '<option value="' + value + '">' + desc + '</option>';
                });

                if (idArray[ind] == 'RESV_RATE_CLASS') {
                    $('#RESV_RATE_CATEGORY').html(option);
                } else if (idArray[ind] == 'CUST_VIP') {
                    $('.CUST_VIP').html(option);
                } else if (idArray[ind] == 'RESV_FEATURE') {
                    $('#RESV_FEATURE').html(option).selectpicker('refresh');
                } else if (idArray[ind] != 'RESV_MEMBER_TY') {
                    $('#' + idArray[ind]).html(option);

                    if (idArray[ind] == 'RESV_TRANSPORT_TYP') {
                        $('#RESV_TRANSPORT_TYP_DO').html(option);
                    }
                    if (idArray[ind] == 'RESV_PURPOSE_STAY') {
                        $('#RESV_EXT_PURP_STAY').html(option);
                    }
                }
            });
        }
    });
}

function runInitializeConfig() {
    $.ajax({
        url: '<?php echo base_url('/getInitializeListReserv') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        async: false,
        success: function(respn) {
            var memData = respn[0];
            var idArray = ['RESV_RESRV_TYPE', 'RESV_MARKET', 'RESV_SOURCE', 'RESV_ORIGIN',
                'RESV_PAYMENT_TYPE'
            ];

            $(respn).each(function(ind, data) {
                var option = '';
                $.each(data, function(i, valu) {
                    var value = $.trim(valu['CODE']); //fields.trim();
                    var desc = $.trim(valu['DESCS']); //datavals.trim();
                    option += '<option value=\'' + value + '\'>' + desc + '</option>';
                });
                var options = '<option value=\'\'>Select</option>' + option;

                $('#' + idArray[ind]).html(options);

                if ($(`#combine-popup select[name='${idArray[ind]}']`).length) {
                    $(`#combine-popup select[name='${idArray[ind]}']`).html(options);
                }
            });
        }
    });
}


function companyAgentClick(type) {
    compAgntMode = type;
    if (type == 'COMPANY') {
        $('.companyData').show();
        $('.agentData').hide();
    } else {
        $('.companyData').hide();
        $('.agentData').show();
    }
    runCountryListExdClass();
    $('#COM_TYPE').val(compAgntMode);
    $(':input', '#compnayAgentForm').val('').prop('checked', false).prop('selected', false);

    $('#compnayAgentWindow').modal('show');

    var modalTitle = (type == 'COMPANY') ? 'Add New Company' : 'Add New Agent';
    $('#compnayAgentWindowLable').html(modalTitle);
}

$(document).on('change', '.rateRadio', function() {

    var checked = jQuery(this).prop('checked');
    $('.rateRadio').not(this).prop('checked', !checked);

    var thiss = $(this);
    var mode = checked ? thiss.attr('mode') : $('.rateRadio').not(this).attr('mode');

    generateRateQuery(mode);
});

$(document).on('change', '.rateFilter', function() {
    var thiss = $(this);
    var mode = $('.rateRadio:checkbox:checked').attr('mode');
    generateRateQuery(mode);
});

var customPop = '';

function searchData(form, mode, event) {

    if (mode == 'C') {
        $('.' + form).find('input,select').val('');
        $('#searchRecord').html(
            '<tr><td class="text-left" colspan="11" style="padding-left: 20% !important;">No Record Found</td></tr>'
        );
    } else if (mode == 'S') {
        var formData = {};
        $('.' + form).find('input,select').each(function(i, data) {
            var field = $(data).attr('id');
            var values = $(this).val();
            formData[field] = values;
        });

        if ($("#appcompanyWindow").hasClass('show')) { // If Accompany Guest popup is displayed
            formData['RESV_ID'] = ressysId;
            formData['reservation_customer_id'] = $('#optionsResrBtn').data("reservation_customer_id");
            formData['get_not_accomp'] = 1; // Show not accompanied in search list            
        }

        formData['windowmode'] = windowmode;

        $.ajax({
            url: '<?php echo base_url('/searchProfile') ?>',
            type: "post",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: formData,
            dataType: 'json',
            success: function(respn) {
                var respone = respn['table'];
                $('#searchRecord').html(respone);
            }
        });

    } else if (mode == 'N') {
        $('#customerForm').find('input,select').val('');
        $('.profileCreate').show();
        $('.profileSearch').hide();
        $('#reservationChildlable').html('Add Customer');
        customPop = '-N';
    } else if (mode == 'PR' && $("#appcompanyWindow").hasClass('show')) {
        custId = $('#searchRecord > tr.activeTr').attr('data_sysid');
        updateAccompanyGuest('A');
    }
}

function showCustomerRow(custId) {

    $('.profileSearch').find('input,select').val('');

    $.ajax({
        url: '<?php echo base_url('/searchProfile') ?>',
        async: false,
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            'CUST_ID': custId
        },
        dataType: 'json',
        success: function(respn1) {
            var respone = respn1['table'];
            $('#searchRecord').html(respone);
        }
    });
}

var custId = '';

$(document).on('click', '.activeRow,#customeTrigger', function() {
    var joinVaribl = windowmode + customPop;
    if (joinVaribl != 'AC-N') {
        $('.activeRow').removeClass('activeTr');
        $(this).addClass('activeTr');
        custId = $(this).attr('data_sysid');
    }

    if (joinVaribl == 'AC') {
        toggleButton('.detach-accompany-guest', 'btn-dark', 'btn-warning', false);
    }
});

$(document).on('click', '.getExistCust .select', function() {
    $('.getExistCust').removeClass('activeTr');

    // $(this).addClass('activeTr');
    $(this).parent().addClass('activeTr');

    custId = $(this).parent().attr('data_sysid');
    $.ajax({
        url: '<?php echo base_url('/getExistCustomer') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            custId: custId
        },
        dataType: 'json',
        success: function(respn) {
            var jsonForm = respn[0];
            $('#reservationChild').modal('hide');
            var option = '<option value="' + jsonForm['CUST_ID'] + '">' + jsonForm['NAMES'] +
                '</option>';
            $('*#RESV_NAME').html(option).selectpicker('refresh');
            $('*#RESV_NAME').trigger('change');

            if ($(`#combine-popup`).is(':visible')) {
                $(`#combine-popup input[name='CUST_ID']`).val(jsonForm['CUST_ID']);
                $(`#combine-popup select[name='CUST_TITLE']`).val(jsonForm['CUST_TITLE']);
                $(`#combine-popup input[name='CUST_FIRST_NAME']`).val(jsonForm['CUST_FIRST_NAME']);
                $(`#combine-popup input[name='CUST_LAST_NAME']`).val(jsonForm['CUST_LAST_NAME']);
            }

            fillCustomerMemberships(custId, 'add', $('.window-2').is(':visible') ?
                '[name="RESV_MEMBER_TY"]' : '#RESV_MEMBER_TY_ADD');
            $('#CM_CUST_ID').val(custId);
        }
    });
});


function accompanySet(mode, event) {
    if (mode == 'D') {
        bootbox.confirm({
            message: "Are you sure you want to remove this accompanied guest?",
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
                    updateAccompanyGuest(mode);
                }
            }
        });
    } else
        childReservation('AC');
    //updateAccompanyGuest(mode);
}

function updateAccompanyGuest(mode) {
    $.ajax({
        url: '<?php echo base_url('/appcompanyProfileSetup') ?>',
        async: false,
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            mode: mode,
            ACCOMP_CUST_ID: custId,
            ACCOMP_REF_RESV_ID: ressysId,
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
                var alertText = mode == 'D' ?
                    '<li>The Guest is no longer accompanying this reservation</li>' :
                    '<li>The Guest is now accompanying this reservation</li>';
                showModalAlert(mode == 'D' ? 'warning' : 'success', alertText);

                if ($('#reservationChild').hasClass('show'))
                    $('#reservationChild').modal('hide');

                $('.accompany-guests').trigger('click');
            }
        }
    });
}

var ACCOPM_SYSID = '';

$(document).on('click', '.activeDetach', function() {
    $('.activeDetach').removeClass('activeTrDetch');
    $(this).addClass('activeTrDetch');
    ACCOPM_SYSID = $(this).attr('data_sysid');
});

var copyresr = [];

function reservExtraOption(param) {
    if (param == 'ACP') {
        //childReservation();
        //$('#Addon').hide();
        $('#Addon').modal('hide');
        //$('#Accompany').show();
    } else if (param == 'ADO') {
        //$('#Accompany').hide();
        //$('#Addon').show();
        $('#Addon').modal('show');
        copyresr = [];
        copyresr.push('PM', 'SP', 'CR', 'RU', 'CM', 'PK', 'IN', 'GU');
        $('#COPY_RM_TYPE').html('<option value="' + roomType + '">' + roomTypedesc + '</option>').selectpicker(
            'refresh');
    }
}


$(document).on('click', '.accompany-guests', function() {

    //alert(ressysId);

    $('#appcompanyWindow').modal('show');

    $.ajax({
        url: '<?php echo base_url('/searchProfile') ?>',
        async: false,
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            'RESV_ID': ressysId,
            'get_accomp': 1
        },
        dataType: 'json',
        success: function(respn1) {
            var respone = respn1['table'];
            $('#accompanyTd').html(respone);
        }
    });

    toggleButton('.detach-accompany-guest', 'btn-warning', 'btn-dark', true);

    /*
    $.ajax({
        url: '<?php echo base_url('/getExistingAppcompany') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            custId: custId,
            ressysId: ressysId
        },
        dataType: 'json',
        success: function(respn) {
            var respone = respn['table'];
            $('#accompanyTd').html(respone);
        }
    });*/

});



$(document).on('change', '.copyReser', function() {
    var checkedMe = $(this).is(':checked');
    var newData = $(this).attr('method');
    if (checkedMe) {
        copyresr.push(newData);
    } else {
        copyresr = jQuery.grep(copyresr, function(value) {
            return value != newData;
        });
    }
});

function copyReservation() {
    var roomType = $('#COPY_RM_TYPE').val();
    $("#triggCopyReserv").trigger('click', [ressysId, copyresr, roomType]);
    $('#optionWindow').modal('hide');
    $('.copyReser').prop('checked', true);
    windowmode = 'C';
    customPop = '';
}

function detailOption(mode) {
    var roomType = $('#rateQueryTable .active').find('#ROOMTYPE').val();
    var fromdate = $('#RESV_ARRIVAL_DT').val();
    var uptodate = $('#RESV_DEPARTURE').val();
    $('#reteQueryDetail').modal('show');
    $.ajax({
        url: '<?php echo base_url('/rateQueryDetailOption') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            mode: mode,
            fromdate: fromdate,
            uptodate: uptodate,
            roomType: roomType
        },
        dataType: 'json',
        success: function(respn) {
            var respone = respn['table'];
            $('#reteQueryDetailTd').html(respone);
        }
    });
}

function getInventoryItems() {
    // var fetchInfo = avaiableDatePeriod();
    // $('#userInfoDate').html(fetchInfo);
    // generateRateQuery();
    // $('.rateRadio').prop('checked',false);
    // $('.rateRadio:first').prop('checked',true);
    $('#ItemInventory').modal('show');
}

// Add New Inventory Item Function

function submitItemForm(id) {
    //hideModalAlerts();
    var formSerialization = $('#' + id).serializeArray();
    var url = '<?php echo base_url('/insertItemInventory') ?>';
    $.ajax({
        url: url,
        type: "post",
        data: formSerialization,
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
                var alertText = $('#RSV_ITM_ID').val() == '' ? '<li>The item has been added</li>' :
                    '<li>';
                showModalAlert('success', alertText);


                //$('#popModalWindow').modal('hide');

                var pkgCodeID = respn['RESPONSE']['OUTPUT'];
                showPackageCodeDetails(pkgCodeID);
            }
        }
    });
}

//Show Activity Log table in modal

function showReservationChanges(rsrvId = 0) {

    $('#reservation_changes').DataTable({
        'processing': true,
        async: false,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/reservationChangesView') ?>',
            'data': {
                "sysid": rsrvId
            }
        },
        'columns': [{
                data: ''
            },
            {
                data: 'USR_NAME'
            },
            {
                data: 'LOG_ID',
                "visible": false,
            },
            {
                data: 'LOG_DATE'
            },
            {
                data: 'LOG_TIME'
            },
            {
                data: 'AC_TY_DESC'
            },
            {
                data: 'LOG_ACTION_DESCRIPTION'
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
        }, {
            width: "35%"
        }, {
            width: "15%"
        }, {
            width: "10%"
        }, {
            width: "10%"
        }, {
            width: "23%"
        }],
        "order": [
            [2, "desc"]
        ],
        destroy: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end">>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        language: {
            emptyTable: 'There are no logs for this reservation'
        },
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal({
                    header: function(row) {
                        var data = row.data();
                        return 'Log Details of RES' + rsrvId;
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
}

$(document).on('click', '.show-activity-log', function() {
    $('#changesWindow').modal('show');
    showReservationChanges(ressysId);
});

// Function to execute after Customer Membership form submit

function afterMemFormClose() {
    fillCustomerMemberships($('.window-2').is(':visible') ? $('.window-2').find('#RESV_NAME').val() : $('.window-1')
        .find('#RESV_NAME').val(),
        'edit',
        $('.window-2').is(':visible') ? '[name="RESV_MEMBER_TY"]' : '#RESV_MEMBER_TY_ADD');
}



// Reservation Advanced Search Functions Starts
// --------------------------------------------------------------------

const dt_adv_filter_table = $('#dataTable_view');

// on key up from input field
/*
$(document).on('keyup', 'input.dt-input', function() {
    if ($(this).val().length == 0 || $(this).val().length >= 2)
        dt_adv_filter_table.dataTable().fnDraw();
});

$(document).on('change', 'select.dt-select,.dt-date', function() {
    dt_adv_filter_table.dataTable().fnDraw();
});
*/

// Advanced Search Functions Ends

$(document).on('click', '.submitAdvSearch', function() {

    /*
    $('.advanced_fields').slideUp('slow');  
    if ($('.toggleAdvSearch').find('.bxs-chevron-up').length)
        $('.toggleAdvSearch').find('.bxs-chevron-up').removeClass('bxs-chevron-up').addClass('bxs-chevron-down');     
    */
    dt_adv_filter_table.dataTable().fnDraw();
});

$(document).on('click', '.toggleAdvSearch', function() {

    if ($(this).find('.bxs-chevron-down').length)
        $(this).find('.bxs-chevron-down').removeClass('bxs-chevron-down').addClass('bxs-chevron-up');
    else if ($(this).find('.bxs-chevron-up').length)
        $(this).find('.bxs-chevron-up').removeClass('bxs-chevron-up').addClass('bxs-chevron-down');

    $('.advanced_fields').slideToggle('slow');
});

$(document).on('click', '.clearAdvSearch', function() {

    clearFormFields('.dt_adv_search');
    dt_adv_filter_table.dataTable().fnDraw();
});

$(document).ready(function() {

    $('.dt-date').datepicker({
        format: 'dd-M-yyyy',
        autoclose: true,
        onSelect: function() {
            $(this).change();
        }
    });

    $('#FIXD_CHRG_YEARLY').datepicker({
        format: 'd-M-yyyy',
        autoclose: true,

    });

});

function reservationCheckout() {
    $.ajax({
        url: `<?= base_url('/reservation/checkout') ?>/${ressysId}`,
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response['SUCCESS'] == 200) {
                console.log(response);
                showModalAlert('success', `<li>${response['RESPONSE']['REPORT_RES']['msg']}</li>`);
                let invoice = response['RESPONSE']['OUTPUT']['invoice'];
                window.open(invoice, "_blank", 'fullscreen=yes');
            } else {
                showModalAlert('info', `<li>${response['RESPONSE']['REPORT_RES']['msg']}</li>`);
            }
        }
    });
}

$('.web-link-btn').click(function() {
    window.location.href = `<?= base_url('webline/ReservationDetail') ?>/${ressysId}`;
});

$(document).on('click', '#registerCardButton', function() {
    var reservID = $(this).attr('data_sysid');
    $.ajax({
        url: '<?= base_url('singleReservRegCards') ?>',
        type: "post",
        'data': {
            "reservID": reservID
        },
        dataType: 'json',
        success: function(response) {
            if (response.count > 0) {
                window.open('<?= base_url('/singleReservRegCardPrint') ?>', '_blank');
            } else {
                $('#errorModal').show();
                error = '<ul><li>No reservations found</li></ul>';
                $('#formErrorMessage').html(error);

            }
        }
    });

});



// Add / Edit Inventory Items Detail

function submitDetailsForm(id) {
    var RESV_ID = $('#ITEM_RESV_ID').val();
    hideModalAlerts();
    var formSerialization = $('#' + id).serializeArray();
    var url = '<?php echo base_url('/updateInventoryItems') ?>';
    $.ajax({
        url: url,
        type: "post",
        data: formSerialization,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        success: function(respn) {

            var response = respn['SUCCESS'];
            //alert(response);
            if (response == '2') {
                mcontent = '<li>Item Combination already exists</li>';
                showModalAlert('error', mcontent);
            } else if (response != '1') {

                var ERROR = respn['RESPONSE']['ERROR'];
                var mcontent = '';
                $.each(ERROR, function(ind, data) {
                    console.log(data, "SDF");
                    mcontent += '<li>' + data + '</li>';
                });
                showModalAlert('error', mcontent);
            } else {
                var alertText = $('#RSV_PRI_ID').val() == '' ?
                    '<li>The new Inventory Item has been created</li>' :
                    '<li>The Inventory Item has been updated</li>';

                hideModalAlerts();

                if ($('#RSV_PRI_ID').val() == '') {
                    item_id = $('#RSV_ITM_ID').val();
                    item_text = $('#RSV_ITM_ID option:selected').text();

                    ///Append the items to dropdown
                    var data = {
                        id: item_id,
                        text: item_text
                    };

                    var newOption = new Option(data.text, data.id, false, false);
                    $('#itemsArray').append(newOption).trigger('change');
                    $('#itemsArray').select2('destroy').find('option').prop('selected', 'selected')
                        .end()
                        .select2();

                }

                showModalAlert('success', alertText);
                $('#infoModal').delay(2500).fadeOut();
                $('#successModal').delay(2500).fadeOut();


                clearFormFields('#select_items');
                $("#RSV_ITM_ID").html('');


                if (respn['RESPONSE']['OUTPUT'] != '') {
                    $('#RSV_PRI_ID').val(respn['RESPONSE']['OUTPUT']);
                    showInventoryItems(RESV_ID);
                    clearFormFields('#select_items');
                }
            }
        }
    });
}

// Add new item Detail

$(document).on('click', '.add-item-detail', function() {
    hideModalAlerts();
    $('.dtr-bs-modal').modal('hide');
    $('#IT_CL_ID,#ITM_ID').html('<option value="">Select</option>');
    bootbox.dialog({
        message: "Do you want to add a new item Detail?",
        buttons: {
            ok: {
                label: 'Yes',
                className: 'btn-success',
                callback: function(result) {
                    if (result) {
                        clearFormFields('#select_items');
                        $('#Inventory_Details').find('tr.table-warning').removeClass(
                            'table-warning');

                        //Disable Delete button
                        toggleButton('.delete-item-detail', 'btn-danger', 'btn-dark', true);

                        showModalAlert('info',
                            'Fill in the form and click the \'Save\' button to add the new item Detail'
                        );
                        $('#infoModal').delay(2500).fadeOut();

                    }
                }
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        }
    });
});


// Delete Inventory Items Detail

$(document).on('click', '.delete-item-detail', function() {
    hideModalAlerts();
    resvID = $('#ITEM_RESV_ID').val();
    $('.dtr-bs-modal').modal('hide');
    var RSV_PRI_ID = $('#Inventory_Details').find("tr.table-warning").data("itemid");
    // alert(RSV_PRI_ID)
    var delete_id = $(this).attr("data-val");
    var delete_option = $('#itemsArray option[value="' + $('#RSV_ITM_ID').val() + '"]');
    delete_option.prop('selected', false);
    $('#itemsArray option[value="' + $('#RSV_ITM_ID').val() + '"]').remove();
    $('#itemsArray').trigger('change.select2');

    bootbox.confirm({
        message: "Inventory Items is active. Do you want to Delete?",
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
                    url: '<?php echo base_url('/deleteItemInventory') ?>',
                    type: "post",
                    data: {
                        RSV_PRI_ID: RSV_PRI_ID,
                        RESV_ID: resvID
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    dataType: 'json',
                    success: function(respn) {
                        var response = respn['SUCCESS'];
                        if (response == '0') {
                            clearFormFields('#select_items');
                            showModalAlert('error',
                                '<li>The Inventory Items cannot be deleted</li>'
                            );
                            $('#warningModal').delay(2500).fadeOut();
                        } else {
                            blockLoader('#select_items');
                            showModalAlert('warning',
                                '<li>The Inventory Items has been deleted</li>');
                            $('#warningModal').delay(2500).fadeOut();
                            clearFormFields('#select_items');

                            showInventoryItems(resvID);
                        }
                    }
                });


            }
        }
    });

});


function showInventoryItems(resvID) {
    if (resvID == '')
        resvID = $('#ITEM_RESV_ID').val();

    $('#Inventory_Details').find('tr.table-warning').removeClass('table-warning');

    $('#Inventory_Details').DataTable({
        'processing': true,
        async: false,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/showInventoryItems') ?>',
            'data': {
                "RESV_ID": resvID
            }
        },
        'columns': [{
                data: 'RSV_PRI_ID',
                'visible': false
            }, {

                data: 'RSV_ITM_ID',
                render: function(data, type, full, meta) {
                    if (full['ITM_CODE'] != null)
                        return full['ITM_CODE'] + ' | ' + full['ITM_NAME'];
                    else
                        return '';
                }
            },
            {
                data: 'RSV_ITM_BEGIN_DATE'
               
            },
            {
                data: 'RSV_ITM_END_DATE'
            },
            {
                data: 'RSV_ITM_QTY'
            },

        ],
        "order": [
            [1, "asc"]
        ],
        'createdRow': function(row, data, dataIndex) {

            $(row).attr('data-itemid', data['RSV_PRI_ID']);


            if (dataIndex == 0) {

                $(row).addClass('table-warning');
                loadInventoryDetails(data['RSV_PRI_ID']);
            }
        },


        destroy: true,
        "ordering": true,
        "searching": false,
        autowidth: true,
        responsive: true
    });
}

// Show Inventory Detail

function loadInventoryDetails(itemID) {
    var url = '<?php echo base_url('/showInventoryDetails') ?>';
    $.ajax({
        url: url,
        type: "post",
        async: false,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        data: {
            RSV_PRI_ID: itemID
        },
        dataType: 'json',
        success: function(respn) {
            //Enable Repeat and Delete buttons
            toggleButton('.delete-item-detail', 'btn-dark', 'btn-danger', false);
            $(respn).each(function(inx, data) {
                $.each(data, function(fields, datavals) {
                    var field = $.trim(fields);
                    var dataval = $.trim(datavals);
                    if (field == 'RSV_ITM_ID') {
                        class_val = dataval;
                    } else if (field == 'RSV_ITM_CL_ID') {
                        $('#' + field).val(dataval).trigger('change', class_val);
                    }
                    else if(field == 'RSV_ITM_BEGIN_DATE' || field == 'RSV_ITM_END_DATE'){
                        $('#' + field).datepicker("setDate", new Date(dataval));
                    }                    
                    else {
                        $('#' + field).val(dataval);
                    }
                });
            });
        }
    });
}


function itemClassList() {
    $.ajax({
        url: '<?php echo base_url('/itemClassList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        async: false,
        // dataType:'json',
        success: function(respn) {
            // console.log(respn,"testing");
            $('#RSV_ITM_CL_ID').html(respn);
        }
    });
}

$("#RSV_ITM_CL_ID").change(function(e, param = 0) {
    var item_class_id = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/itemList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            item_class_id: item_class_id,
            item_id: param
        },
        // dataType:'json',
        success: function(respn) {
            //console.log(respn,"testing");
            $('#RSV_ITM_ID').html('<option value="">Select Item</option>');
            $('#RSV_ITM_ID').html(respn);
        }
    });
});


function getInventoryItems() {
    $('#ItemInventory').modal('show');
}

function itemInventoryClassSingle() {
    $.ajax({
        url: '<?php echo base_url('/itemInventoryClassSingle') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        async: false,
        // dataType:'json',
        success: function(respn) {
            // console.log(respn,"testing");
            $('#eventLabel').html(respn);
        }
    });
}



// let date = new Date();
// let nextDay = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);
// // prettier-ignore
// let nextMonth = date.getMonth() === 11 ? new Date(date.getFullYear() + 1, 0, 1) : new Date(date.getFullYear(), date
//     .getMonth() + 1, 1);
// // prettier-ignore
// let prevMonth = date.getMonth() === 11 ? new Date(date.getFullYear() - 1, 0, 1) : new Date(date.getFullYear(), date
//     .getMonth() - 1, 1);




//document.addEventListener('DOMContentLoaded', function() {
  function showInventoryAvailability()
 {    
    var output = '';
    $.ajax({
        url: '<?php echo base_url('/getInventoryCalendarData') ?>',
        type: "post",       
        async:false,
        dataType:'json',
        success: function(respn) {
            output = respn;            
        }
    });

    itemCalendarResources = output['itemResources'];
    itemCalendarAvail     = output['itemAvail'];

    if($('#calendar>*').length == 0) {
        calendarRender(itemCalendarResources,itemCalendarAvail);
    }
    console.log( itemCalendarAvail )
 }


function calendarRender(itemCalendarResources, itemCalendarAvail){
   
let date = new Date();
let nextDay = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);
// prettier-ignore
let nextMonth = date.getMonth() === 11 ? new Date(date.getFullYear() + 1, 0, 1) : new Date(date.getFullYear(), date.getMonth() + 1, 1);
// prettier-ignore
let prevMonth = date.getMonth() === 11 ? new Date(date.getFullYear() - 1, 0, 1) : new Date(date.getFullYear(), date.getMonth() - 1, 1);

var calendarEl = document.getElementById('calendar');

var calendar = new FullCalendar.Calendar(calendarEl, {
//schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
//schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
//titleFormat: 'YYYY-MM-DD',
timeZone: 'UTC',
plugins: [ 'resourceTimeline', 'interaction' ],

droppable: true,

header: {

left: 'today prev,next',

center: 'title',

right: ''

},


aspectRatio: 1.5,

defaultView: 'resourceTimelineWeek',

slotLabelFormat: [{ weekday: 'short', month: 'numeric', day: 'numeric', omitCommas: true }],

slotLabelInterval: { days: 1 },

editable: true,

resourceAreaWidth: '35%',

resourceColumns: [
{
labelText: 'Item',

field: 'item',

},
],
validRange: {
    start: '2022-01-01',
    end: '2040-12-31'
},

resources: itemCalendarResources,
//events: itemCalendarAvail,
events: function(info, successCallback, failureCallback) {  
    //console.log(info.start)
        let START = info.start;
        let s = new Date(START);
        START = s.toISOString(START);
        let END = info.end;
        let e = new Date(END);
        END = e.toISOString(END);
    $.ajax({
        url: '<?php echo base_url('/getInventoryAllocatedData') ?>',
        type: "post",  
        dataType: 'json',
        data: {
            start: START,
            end: END
        },
        success: function(res) {
         
          var events = [];
          res.forEach(function (evt) {
            events.push({
              id:evt.id,
              resourceId:evt.resourceId,
              title: evt.title,
              start: evt.start,
              end: evt.end,
            });
          });
          successCallback(events);
        },
        
    });
},
eventMouseEnter: function (info) {
    var titleText = info.event.title;
    var myarr = titleText.split("|");
    var title = 'Available Quantity : '+ myarr[0] + ", Total Quantity: " + myarr[1];
    var title = 
        $(info.el).tooltip({
              title: title,
              html: true,
              placement: 'top',
              trigger: 'hover',
              container: 'body',
              
        });
},

eventDidMount: function(info) {
      var tooltip = new Tooltip(info.el, {
        title: 'titlesdasd',
        placement: 'top',
        trigger: 'hover',
        container: 'body',
      });
    },
 
    
});

calendar.render();
//console.log(itemCalendarAvail);
}
//});

////class="fc-timeline-event fc-h-event fc-event fc-start fc-end fc-draggable fc-resizable fc-allow-mouse-resize"

//a.fc-timeline-event.fc-h-event.fc-event.fc-start.fc-end.fc-draggable.fc-resizable

//////// Fixed Charges Functions

$(document).on('click', '#fixedChargeButton', function() {
    $('#fixedCharges').modal('show');
    var reservID = $(this).attr('data_sysid');
    $("#FIXD_CHRG_RESV_ID").val(reservID);
    transactionList();
    showFixedCharges(reservID);
    $.ajax({
        url: '<?php echo base_url('/getReservDetails') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        async: false,
        data: {
            reservID: reservID,
        },
        dataType: 'json',
        success: function(respn) {
            $('#FIXD_RSV_NAME').val(respn.FULL_NAME);
            $('#FIXD_ARRIVAL').val(respn.RESV_ARRIVAL_DT);
            $('#FIXD_NIGHTS').val(respn.RESV_NIGHT);
            $('#FIXD_DEPARTURE').val(respn.RESV_DEPARTURE);
            $('#FIXD_DEPARTURE_UP').val(respn.RESV_DEPARTURE);
            $('#FIXD_CHRG_BEGIN_DATE').datepicker("setDate", $('.window-1').find('.RESV_ARRIVAL_DT').val());
            $('#FIXD_CHRG_END_DATE').datepicker("setDate", $('.window-1').find('.RESV_DEPARTURE').val());
            //alert($('#FIXD_CHRG_BEGIN_DATE').val());
            $('#FIXD_CHRG_FREQUENCY4').prop('disabled', false);
            $('#FIXD_CHRG_FREQUENCY5').prop('disabled', false);
            $('#FIXD_CHRG_FREQUENCY6').prop('disabled', false);
        }
    });


});


function transactionList() {
    $.ajax({
        url: '<?php echo base_url('/transactionList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        async: false,
        success: function(respn) {
            $('#FIXD_CHRG_TRNCODE').html(respn);
        }
    });
}

$(document).on('click', '.add-fixedcharge-detail', function() {
    transactionList();
    hideModalAlerts();
    $('.dtr-bs-modal').modal('hide');
    $('#FIXD_CHRG_BEGIN_DATE').datepicker("setDate", $('.window-1').find('.RESV_ARRIVAL_DT').val());
    $('#FIXD_CHRG_END_DATE').datepicker("setDate", $('.window-1').find('.RESV_DEPARTURE').val());
    $('#FIXD_CHRG_TRNCODE').val('');
    $('#FIXD_CHRG_AMT').val('');
    $('#FIXD_CHRG_QTY').val('');
    $('#FIXD_CHRG_ID').val('');
    $('.WEEKLY_EXCECUTE').hide();
    $('.MONTHLY_EXCECUTE').hide();
    $('.YEARLY_EXCECUTE').hide();
    $('.QUARTERLY_EXCECUTE').hide();

    bootbox.dialog({
        message: "Do you want to add a new fixed charges?",
        buttons: {
            ok: {
                label: 'Yes',
                className: 'btn-success',
                callback: function(result) {
                    if (result) {
                        //clearFormFields('#fixedTransactionCharges');
                        $('#fixedCharges').find('tr.table-warning').removeClass(
                            'table-warning');

                        //Disable Delete button
                        toggleButton('.delete-fixedcharge-detail', 'btn-danger', 'btn-dark',
                            true);

                        showModalAlert('info',
                            'Fill in the form and click the \'Save\' button to add the new fixed charge'
                        );
                        $('#infoModal').delay(2500).fadeOut();

                    }
                }
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        }
    });
});

$(document).on('click', '.save-fixedcharge-detail', function() {
    var RESV_ID = $('#FIXD_CHRG_RESV_ID').val();


    hideModalAlerts();
    var formSerialization = $('#fixedcharge-submit-form').serializeArray();
    var url = '<?php echo base_url('/updateFixedCharges') ?>';
    $.ajax({
        url: url,
        type: "post",
        data: formSerialization,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        success: function(respn) {
            var response = respn['SUCCESS'];
            if (response == '2') {
                mcontent = '<li>Something went wrong</li>';
                showModalAlert('error', mcontent);
            } else if (response != '1') {
                var ERROR = respn['RESPONSE']['ERROR'];
                var mcontent = '';
                $.each(ERROR, function(ind, data) {
                    //console.log(data, "SDF");
                    mcontent += '<li>' + data + '</li>';
                });
                showModalAlert('error', mcontent);
            } else {
                var alertText = $('#FIXD_CHRG_ID').val() == '' ?
                    '<li>The new fixed charges has been created</li>' :
                    '<li>The fixed charges has been updated</li>';
                hideModalAlerts();
                showModalAlert('success', alertText);


                if (respn['RESPONSE']['OUTPUT'] != '') {
                    $('#FIXD_CHRG_ID').val(respn['RESPONSE']['OUTPUT']);
                    showFixedCharges(RESV_ID);
                    // clearFormFields('#select_items');
                }
            }
        }
    });
});


function showFixedCharges(resvID) {
    if (resvID == '')
        resvID = $('#FIXD_CHRG_RESV_ID').val();

    $('#FixedCharge_Details').find('tr.table-warning').removeClass('table-warning');

    $('#FixedCharge_Details').DataTable({
        'processing': true,
        async: false,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/showFixedCharge') ?>',
            'data': {
                "FIXD_CHRG_RESV_ID": resvID
            }
        },
        'columns': [{
                data: 'FIXD_CHRG_ID',
                'visible': false
            }, {

                data: 'TR_CD_CODE',
                render: function(data, type, full, meta) {
                    if (full['TR_CD_CODE'] != null)
                        return full['TR_CD_CODE'] + ' | ' + full['TR_CD_DESC'];
                    else
                        return '';
                }
            },

            {
                data: 'FIXD_CHRG_QTY'
            },
            {
                data: 'FIXD_CHRG_AMT'
            },
            {
                data: 'FREQ_NAME'
            },
            {
                data: 'FIXD_CHRG_BEGIN_DATE'
            },
            {
                data: 'FIXD_CHRG_END_DATE'
            },

        ],
        "order": [
            [1, "asc"]
        ],
        'createdRow': function(row, data, dataIndex) {

            $(row).attr('data-fxcharg_id', data['FIXD_CHRG_ID']);


            if (dataIndex == 0) {

                $(row).addClass('table-warning');
                loadFixedchargeDetails(data['FIXD_CHRG_ID']);
            }
        },


        destroy: true,
        "ordering": true,
        "searching": false,
        autowidth: true,
        responsive: true
    });
}

// Show Fixedcharge Details

function loadFixedchargeDetails(fixedChargeID) {

    var url = '<?php echo base_url('/showFixedChargeDetails') ?>';
    $.ajax({
        url: url,
        type: "post",
        async: false,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        data: {
            FIXD_CHRG_ID: fixedChargeID
        },
        dataType: 'json',
        success: function(respn) {
            //Enable Repeat and Delete buttons
            FIXD_CHRG_FREQUENCY = 0;
            toggleButton('.delete-fixedcharge-detail', 'btn-dark', 'btn-danger', false);
            $(respn).each(function(inx, data) {
                $.each(data, function(fields, datavals) {
                    //alert(fields)
                    // alert(datavals)
                    var field = $.trim(fields);
                    var dataval = $.trim(datavals);

                    if (field == 'FIXD_CHRG_FREQUENCY') {
                        if (dataval == 1) {
                            FIXD_CHRG_FREQUENCY = 1;
                        } else if (dataval == 2) {
                            FIXD_CHRG_FREQUENCY = 2;
                            $('.WEEKLY_EXCECUTE').hide();
                            $('.MONTHLY_EXCECUTE').hide();
                            $('.YEARLY_EXCECUTE').hide();
                            $('.QUARTERLY_EXCECUTE').hide();
                        } else if (dataval == 3) {
                            FIXD_CHRG_FREQUENCY = 3;
                            $('.WEEKLY_EXCECUTE').show();
                            $('.MONTHLY_EXCECUTE').hide();
                            $('.YEARLY_EXCECUTE').hide();
                            $('.QUARTERLY_EXCECUTE').hide();
                        } else if (dataval == 4) {
                            FIXD_CHRG_FREQUENCY = 4;
                            $('.WEEKLY_EXCECUTE').hide();
                            $('.MONTHLY_EXCECUTE').show();
                            $('.YEARLY_EXCECUTE').hide();
                            $('.QUARTERLY_EXCECUTE').hide();
                        } else if (dataval == 5) {
                            FIXD_CHRG_FREQUENCY = 5;
                            $('.WEEKLY_EXCECUTE').hide();
                            $('.MONTHLY_EXCECUTE').hide();
                            $('.YEARLY_EXCECUTE').hide();
                            $('.QUARTERLY_EXCECUTE').show();
                        } else if (dataval == 6) {
                            FIXD_CHRG_FREQUENCY = 6;
                            $('.WEEKLY_EXCECUTE').hide();
                            $('.MONTHLY_EXCECUTE').hide();
                            $('.YEARLY_EXCECUTE').show();
                            $('.QUARTERLY_EXCECUTE').hide();
                        }
                        $('#' + field + dataval).prop('checked', true);
                    } else
                        $('.END_DATE').show();

                    if (field == 'FIXD_CHRG_TRNCODE') {
                        $('#' + field).val(dataval).trigger('change');
                    } else if (field == 'FIXD_CHRG_END_DATE' && FIXD_CHRG_FREQUENCY ==
                        1) {
                        $('.END_DATE').hide();
                        $('.WEEKLY_EXCECUTE').hide();
                        $('.MONTHLY_EXCECUTE').hide();
                        $('.YEARLY_EXCECUTE').hide();
                        $('.QUARTERLY_EXCECUTE').hide();

                    } else if (FIXD_CHRG_FREQUENCY == 3 && field ==
                        'FIXD_CHRG_WEEKLY') {
                        $('#' + field).val(dataval).trigger('change');
                    } 

                    else if (field == 'FIXD_CHRG_BEGIN_DATE' || field == 'FIXD_CHRG_END_DATE' || field == 'FIXD_CHRG_YEARLY'){
                        $('#' + field).datepicker("setDate", new Date(dataval)); 
                    } 
                    else {
                        $('#' + field).val(dataval);
                    }
                });
            });
        }
    });
}

// Delete Fixed Charge Detail
$(document).on('click', '.delete-fixedcharge-detail', function() {
    hideModalAlerts();
    var resvID = $("#FIXD_CHRG_RESV_ID").val();
    $('.dtr-bs-modal').modal('hide');
    var FIXD_CHRG_ID = $('#FixedCharge_Details').find("tr.table-warning").data("fxcharg_id");

    bootbox.confirm({
        message: "Fixed charge is active. Do you want to Delete?",
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
                    url: '<?php echo base_url('/deleteFixedcharge') ?>',
                    type: "post",
                    data: {
                        FIXD_CHRG_ID: FIXD_CHRG_ID,
                        RESV_ID: resvID
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    dataType: 'json',
                    success: function(respn) {
                        var response = respn['SUCCESS'];
                        if (response == '0') {
                            clearFormFields('#fixedCharges');
                            showModalAlert('error',
                                '<li>The fixed charges cannot be deleted</li>');
                            $('#warningModal').delay(2500).fadeOut();
                        } else {
                            blockLoader('#fixedCharges');
                            showModalAlert('warning',
                                '<li>The fixed charges has been deleted</li>');
                            $('#warningModal').delay(2500).fadeOut();
                            // clearFormFields('#fixedCharges');
                            $('#FIXD_CHRG_TRNCODE').val('');
                            $('#FIXD_CHRG_AMT').val('');
                            $('#FIXD_CHRG_QTY').val('');
                            $('#FIXD_CHRG_BEGIN_DATE').val($('#FIXD_ARRIVAL')
                                .val());
                            $('#FIXD_CHRG_END_DATE').val($('#FIXD_DEPARTURE')
                                .val());
                            $('#FIXD_CHRG_TRNCODE').val('');
                            $('#FIXD_CHRG_AMT').val('');
                            $('#FIXD_CHRG_QTY').val('');
                            $('#FIXD_CHRG_ID').val('');
                            $('.WEEKLY_EXCECUTE').hide();
                            $('.MONTHLY_EXCECUTE').hide();
                            $('.YEARLY_EXCECUTE').hide();
                            $('.QUARTERLY_EXCECUTE').hide();

                            showFixedCharges(resvID);
                        }
                    }
                });


            }
        }
    });

});



$(document).on('click', '#FixedCharge_Details > tbody > tr', function() {

    $('#FixedCharge_Details').find('tr.table-warning').removeClass('table-warning');
    $(this).addClass('table-warning');
    $.when(loadFixedchargeDetails($(this).data('fxcharg_id')))
        .done(function() {})
        .done(function() {
            blockLoader('#fixedTransactionCharges');
        });
});


$(document).on('click', '#FIXD_CHRG_FREQUENCY1', function() {
    $('.END_DATE').hide();
});

function frequency(value) {
    $('.WEEKLY_EXCECUTE').hide();
    $('.MONTHLY_EXCECUTE').hide();
    $('.YEARLY_EXCECUTE').hide();
    $('.QUARTERLY_EXCECUTE').hide();
    $('.END_DATE').hide();

    if (value == 1) {
        $('.END_DATE').hide();
        $('#FIXD_CHRG_END_DATE').val('');
    } else if (value == 3) {
        $('.END_DATE').show();
        $('#FIXD_CHRG_WEEKLY').val('0');
        $('.WEEKLY_EXCECUTE').show();
    } else if (value == 4) {
        $('.END_DATE').show();
        $('#FIXD_CHRG_MONTHLY').val('1');
        $('.MONTHLY_EXCECUTE').show();

    } else if (value == 5) {
        $('.END_DATE').show();
        $('#FIXD_CHRG_QUARTERLY').val('1');
        $('.QUARTERLY_EXCECUTE').show();

    } else if (value == 6) {
        $('.END_DATE').show();
        $('#FIXD_CHRG_YEARLY').datepicker("setDate", new Date($('#FIXD_ARRIVAL').val()));
        $('.YEARLY_EXCECUTE').show();


    } else {
        $('.END_DATE').show();
        $('#FIXD_CHRG_END_DATE').val($('#FIXD_DEPARTURE').val());
    }
}



////////  Functions

$(document).on('click', '#proformaButton', function() {
    $('#proforma-folio').modal('show');
    var reservID = $(this).attr('data_sysid');
    $("#PROFORMA_RESV_ID").val(reservID);
});

$(document).on('click', '.proformafolio-action', function() {

    var formSerialization = $('#proforma-submit-form').serializeArray();
    var proforma = $(this).attr('rel');
    $("#proforma_action_value").val(proforma);
    $.ajax({
        url: '<?php echo base_url('/proformaFolio') ?>',
        type: "post",
        data: formSerialization,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        async: false,
        success: function(respn) {
            if (respn > 0) {
                if (proforma == 1)
                    window.open('<?= base_url('/previewProFormaFolio') ?>', '_blank');
                else if (proforma == 2)
                    window.open('<?= base_url('/printProFormaFolio') ?>', '_blank');
                else if (proforma == 3)
                    window.open('<?= base_url('/pdfProFormaFolio') ?>');

            }

        }

    });

});
//////////////////// Rate Class List ////////////////

function RateClassList() {
    $.ajax({
        url: '<?php echo base_url('/RateClassList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        async: false,
        success: function(respn) {
            $('#RESV_RATE_CLASS').html(respn);
        }
    });
}

$("#RESV_RATE_CLASS").change(function(e, param = 0) {
    var rate_class_id = $(this).val();
    //if (rate_class_id > 0) {
    $.ajax({
        url: '<?php echo base_url('/RateCategory') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            rate_class_id: rate_class_id
        },
        success: function(respn) {
            $('#RESV_RATE_CATEGORY').html('<option value="">Select</option>');
            $('#RESV_RATE_CATEGORY').html(respn);
        }
    });

    $.ajax({
        url: '<?php echo base_url('/RateCodes') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            rate_class_id: rate_class_id
        },
        success: function(respn2) {
            $('#RESV_RATE_CODE').html('<option value="">Select</option>');
            $('#RESV_RATE_CODE').html(respn2);
        }
    });
    //}
});

$("#RESV_RATE_CATEGORY").change(function(e, param = 0) {
    var rate_class_id = $("#RESV_RATE_CLASS").val();
    var rate_category_id = $(this).val();

    //if (rate_category_id > 0) {
    $.ajax({
        url: '<?php echo base_url('/RateCodes') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            rate_class_id: rate_class_id,
            rate_category_id: rate_category_id
        },
        success: function(respn) {
            $('#RESV_RATE_CODE').html('<option value="">Select</option>');
            $('#RESV_RATE_CODE').html(respn);
        }
    });
    //}
});


//////////////////// Package Options ////////////////


function getPackageList() {
    $.ajax({
        url: '<?php echo base_url('/getPackageList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        async: false,
        success: function(respn) {
            $('#PCKG_ID').html(respn);
        }
    });
}


function getPackages() {
    getPackageList();
    $('#RSV_PCKG_BEGIN_DATE').datepicker("setDate", $('.window-1').find('.RESV_ARRIVAL_DT').val());
    $('#RSV_PCKG_END_DATE').datepicker("setDate", $('.window-1').find('.RESV_DEPARTURE').val());
    $('#packagesModal').modal('show');
    resvID = $('#PCKG_RESV_ID').val();
    showPackages(resvID);
    //alert($('#RESV_RATE_CODE').val());        
    var rateCode = $('.clickPrice.active').parent('.ratePrice').find('#RT_DESCRIPTION').val();
    // alert(rateCode)
}

$("#PCKG_ID").change(function(e, param = 0) {
    var package_id = $(this).val();
    if (package_id > 0) {
        $.ajax({
            url: '<?php echo base_url('/getPackageDetails') ?>',
            type: "post",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                package_id: package_id
            },
            dataType: 'json',
            success: function(respn) {
                $('#RSV_PCKG_POST_RYTHM').val(respn.PO_RH_DESC);
                $('#RSV_PCKG_CALC_RULE').val(respn.CLC_RL_DESC);
            }
        });
    }
});

// Add / Edit Package Detail

$(document).on('click', '.save-package-detail', function() {

    var RESV_ID = $('#PCKG_RESV_ID').val();
    hideModalAlerts();
    $("#RESVSTART_DATE").val($("#RESV_ARRIVAL_DT").val());
    $("#RESVEND_DATE").val($("#RESV_DEPARTURE").val());


    var formSerialization = $('#package-submit-form').serializeArray();
    var url = '<?php echo base_url('/updatePackageDetails') ?>';
    $.ajax({
        url: url,
        type: "post",
        data: formSerialization,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        success: function(respn) {
            var response = respn['SUCCESS'];
            //alert(response);
            if (response == '2') {
                mcontent = '<li>Package Combination already exists</li>';
                showModalAlert('error', mcontent);
            } else if (response != '1') {

                var ERROR = respn['RESPONSE']['ERROR'];
                var mcontent = '';
                $.each(ERROR, function(ind, data) {
                    console.log(data, "SDF");
                    mcontent += '<li>' + data + '</li>';
                });
                showModalAlert('error', mcontent);
            } else {
                var alertText = $('#RSV_PCKG_ID').val() == '' ?
                    '<li>The new Package has been created</li>' :
                    '<li>The Package has been updated</li>';

                //hideModalAlerts();                   

                showModalAlert('success', alertText);
                clearFormFields('#packages');

                if (respn['RESPONSE']['OUTPUT'] != '') {
                    $('#RSV_PCKG_ID').val(respn['RESPONSE']['OUTPUT']);
                    showPackages(RESV_ID);
                    //clearFormFields('#packages');
                }
            }
            // hideModalAlerts();
        }
    });
});

// Add new package Detail

$(document).on('click', '.add-package-detail', function() {
    hideModalAlerts();
    $('.dtr-bs-modal').modal('hide');
    
    bootbox.dialog({
        message: "Do you want to add a new Package Detail?",
        buttons: {
            ok: {
                label: 'Yes',
                className: 'btn-success',
                callback: function(result) {
                    if (result) {
                        clearFormFields('#packages');
                        $("#RSV_PCKG_ID").val('');
                        $('#Package_Details').find('tr.table-warning').removeClass(
                            'table-warning');
                            $('#RSV_PCKG_BEGIN_DATE').datepicker("setDate", $('.window-1').find('.RESV_ARRIVAL_DT').val());
                            $('#RSV_PCKG_END_DATE').datepicker("setDate", $('.window-1').find('.RESV_DEPARTURE').val());

                        //Disable Delete button
                        toggleButton('.delete-package-detail', 'btn-danger', 'btn-dark', true);

                        showModalAlert('info',
                            'Fill in the form and click the \'Save\' button to add the package Detail'
                        );
                        $('#infoModal').delay(2500).fadeOut();

                    }
                }
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        }
    });
});


// Delete package Detail

$(document).on('click', '.delete-package-detail', function() {
    hideModalAlerts();
    resvID = $('#PCKG_RESV_ID').val();
    $('.dtr-bs-modal').modal('hide');
    var RSV_PCKG_ID = $('#Package_Details').find("tr.table-warning").data("packageid");

    var delete_id = $(this).attr("data-val");

    bootbox.confirm({
        message: "Package is active. Do you want to Delete?",
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
                    url: '<?php echo base_url('/deletePackageDetail') ?>',
                    type: "post",
                    data: {
                        RSV_PCKG_ID: RSV_PCKG_ID,
                        RESV_ID: resvID
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    dataType: 'json',
                    success: function(respn) {
                        var response = respn['SUCCESS'];
                        if (response == '0') {
                            clearFormFields('#packages');
                            showModalAlert('error',
                                '<li>The package cannot be deleted</li>');
                            $('#warningModal').delay(2500).fadeOut();
                        } else {
                            blockLoader('#packages');
                            showModalAlert('warning',
                                '<li>The package has been deleted</li>');
                            $('#warningModal').delay(2500).fadeOut();
                            clearFormFields('#packages');
                            showPackages(resvID);
                        }
                    }
                });


            }
        }
    });

});


function showPackages(resvID) {
    if (resvID == '')
        resvID = $('#PCKG_RESV_ID').val();
    var rateCode = $('.clickPrice.active').parent('.ratePrice').find('#RT_DESCRIPTION').val();

    $('#Package_Details').find('tr.table-warning').removeClass('table-warning');

    $('#Package_Details').DataTable({
        'processing': true,
        async: false,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/showPackages') ?>',
            'data': {
                "RESV_ID": resvID
            }
        },
        'columns': [{
                data: 'RSV_PCKG_ID',
                'visible': false
            }, {

                data: 'RSV_PCKG_ID',
                render: function(data, type, full, meta) {
                    if (full['PKG_CD_CODE'] != null)
                        return full['PKG_CD_CODE'] + ' | ' + full['PKG_CD_DESC'];
                    else
                        return '';
                }
            },
            {
                data: 'PKG_CD_SHORT_DESC'
            },
            {
                data: 'RSV_PCKG_ID',
                render: function(data, type, full, meta) {
                    if (rateCode != null)
                        return rateCode;
                    else
                        return '';
                }
            },
            {
                data: 'RSV_PCKG_QTY'
            },
            {
                data: 'RSV_PCKG_POST_RYTHM'
            },
            {
                data: 'RSV_PCKG_CALC_RULE'
            },
            {
                data: 'RSV_PCKG_BEGIN_DATE'
            },
            {
                data: 'RSV_PCKG_END_DATE'
            },

        ],
        "order": [
            [0, "asc"]
        ],
        'createdRow': function(row, data, dataIndex) {

            $(row).attr('data-packageid', data['RSV_PCKG_ID']);


            if (dataIndex == 0) {
                $(row).addClass('table-warning');
                loadPackageDetails(data['RSV_PCKG_ID']);
            }
        },


        destroy: true,
        "ordering": true,
        "searching": false,
        autowidth: true,
        responsive: true
    });
}



$(document).on('click', '#Package_Details > tbody > tr', function() {

    var packageID = $(this).data('packageid');

    $('#Package_Details').find('tr.table-warning').removeClass('table-warning');
    $(this).addClass('table-warning');
    $.when(loadPackageDetails(packageID))
        .done(function() {})
        .done(function() {
            blockLoader('#package-submit-form');
        });




    $.ajax({
        url: '<?php echo base_url('/showSinglePackageDetails') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            packageID: packageID
        },
        async: false,
        success: function(respn) {
            $('#Each_Package_Details').DataTable().destroy();
            $('#Each_Package_Details > tbody').html(respn);
            $('#Each_Package_Details').DataTable({ paging: true});
            
        }
    });

});

// Show Package Detail

function loadPackageDetails(packageID) {
    var url = '<?php echo base_url('/showPackageDetails') ?>';
    $.ajax({
        url: url,
        type: "post",
        async: false,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        data: {
            packageID: packageID
        },
        dataType: 'json',
        success: function(respn) {
            //Enable Repeat and Delete buttons
            toggleButton('.delete-package-detail', 'btn-dark', 'btn-danger', false);
            $(respn).each(function(inx, data) {
                $.each(data, function(fields, datavals) {
                    var field = $.trim(fields);
                    var dataval = $.trim(datavals);
                    if (field == 'PCKG_ID') {
                        $('#' + field).val(dataval).trigger('change');
                    }
                    else if(field == 'RSV_PCKG_BEGIN_DATE' || field == 'RSV_PCKG_END_DATE'){
                        $('#' + field).datepicker("setDate", new Date(dataval));
                    } 
                    else {
                        $('#' + field).val(dataval);
                    }
                });
            });
        }
    });



    $.ajax({
        url: '<?php echo base_url('/showSinglePackageDetails') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            packageID: packageID
        },
        async: false,
        success: function(respn) {
            $('#Each_Package_Details').DataTable().destroy();
            $('#Each_Package_Details > tbody').html(respn);
            $('#Each_Package_Details').DataTable({ paging: true});
        }
    });
}

function getRateInfo() {
    resvID = $('#rateInfoButton').attr('data_sysid');
    $('#RateInfoModal').modal('show');
    $.ajax({
        url: '<?php echo base_url('/rateInfoDetails') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            resvID: resvID
        },
        async: false,
        dataType: 'json',
        success: function(respn) {
            $('#Rate_info > tbody').html(respn.output);

            $('#Rate_info_total > tbody').html(respn.total_output);
        }


    });
}





///////////////Traces/////////////

$(document).on('click', '#traceButton', function() {

    $('#tracesModal').modal('show');
    var reservID = $(this).attr('data_sysid');
    $("#TRACE_RESV_ID").val(reservID);
    departmentList();
    showTraces(reservID);
    $('#RSV_TRACE_DATE').val($('#FIXD_ARRIVAL').val());
    $.ajax({
        url: '<?php echo base_url('/getReservDetails') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        async: false,
        data: {
            reservID: reservID,
        },
        dataType: 'json',
        success: function(respn) {
            $('#TRACE_RSV_NAME').val(respn.FULL_NAME);
            $('#TRACE_ARRIVAL').val(respn.RESV_ARRIVAL_DT);
            $('#TRACE_DEPARTURE').val(respn.RESV_DEPARTURE);
            $('#TRACE_ARRIVAL_DT').val(respn.RESV_ARRIVAL_DT);
            $('#TRACE_DEPARTURE_DT').val(respn.RESV_DEPARTURE);
            $('#RESERVATION_STATUS').val(respn.RESV_STATUS);
            $('#RSV_TRACE_DATE').val(respn.RESV_ARRIVAL_DT);
        }
    });


});


function departmentList() {
    $.ajax({
        url: '<?php echo base_url('/departmentList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        async: false,
        success: function(respn) {
            $('#RSV_TRACE_DEPARTMENT').html(respn);
        }
    });
}

$(document).on('click', '.add-trace-detail', function() {
    departmentList();
    hideModalAlerts();
    $('.dtr-bs-modal').modal('hide');
    $('#RSV_TRACE_DATE').val($('#TRACE_ARRIVAL_DT').val());
    $('#RSV_TRACE_DEPARTMENT').val('');
    $('#RSV_TRACE_TIME').val('');
    $('#RSV_TRACE_TEXT').val('');
    $('#RSV_TRACE_ID').val('');



    bootbox.dialog({
        message: "Do you want to add a new trace details?",
        buttons: {
            ok: {
                label: 'Yes',
                className: 'btn-success',
                callback: function(result) {
                    if (result) {

                        $('#tracesDiv').find('tr.table-warning').removeClass(
                            'table-warning');

                        //Disable Delete button
                        toggleButton('.delete-trace-detail', 'btn-danger', 'btn-dark', true);

                        showModalAlert('info',
                            'Fill in the form and click the \'Save\' button to add the new trace'
                        );
                        $('#infoModal').delay(2500).fadeOut();

                    }
                }
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        }
    });
});

$(document).on('click', '.save-trace-detail', function() {
    var RESV_ID = $('#TRACE_RESV_ID').val();


    hideModalAlerts();
    var formSerialization = $('#trace-submit-form').serializeArray();
    var url = '<?php echo base_url('/updateTraces') ?>';
    $.ajax({
        url: url,
        type: "post",
        data: formSerialization,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        success: function(respn) {
            var response = respn['SUCCESS'];
            if (response == '2') {
                mcontent = '<li>Something went wrong</li>';
                showModalAlert('error', mcontent);
            } else if (response != '1') {
                var ERROR = respn['RESPONSE']['ERROR'];
                var mcontent = '';
                $.each(ERROR, function(ind, data) {
                    //console.log(data, "SDF");
                    mcontent += '<li>' + data + '</li>';
                });
                showModalAlert('error', mcontent);
            } else {
                var alertText = $('#RSV_TRACE_ID').val() == '' ?
                    '<li>The new trace has been created</li>' :
                    '<li>The trace has been updated</li>';
                hideModalAlerts();
                showModalAlert('success', alertText);


                if (respn['RESPONSE']['OUTPUT'] != '') {
                    $('#RSV_TRACE_ID').val(respn['RESPONSE']['OUTPUT']);
                    showTraces(RESV_ID);
                    // clearFormFields('#select_items');
                }
            }
        }
    });
});


function showTraces(resvID) {
    if (resvID == '')
        resvID = $('#TRACE_RESV_ID').val();

    $('#Trace_Details').find('tr.table-warning').removeClass('table-warning');

    $('#Trace_Details').DataTable({
        'processing': true,
        async: false,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/showTraces') ?>',
            'data': {
                "TRACE_RESV_ID": resvID
            }
        },
        'columns': [{
                data: 'RSV_TRACE_ID',
                'visible': false
            }, {

                data: 'RSV_TRACE_DATE',
                render: function(data, type, full, meta) {
                    if (full['RSV_TRACE_DATE'] != null)
                        return full['RSV_TRACE_DATE'] + ' | ' + full['RSV_TRACE_TIME'];
                    else
                        return '';
                }
            },

            {
                data: 'DEPT_CODE',
                render: function(data, type, full, meta) {
                    if (full['DEPT_CODE'] != null)
                        return full['DEPT_CODE'] + ' | ' + full['DEPT_DESC'];
                    else
                        return '';
                }
            },
            {
                data: 'UE_FIRST_NAME',
                render: function(data, type, full, meta) {
                    let UE_LAST_NAME = (full['UE_LAST_NAME'] != '' && full['UE_LAST_NAME'] != null) ?
                        full['UE_LAST_NAME'] : '';
                    if (full['UE_FIRST_NAME'] != null)
                        return full['UE_FIRST_NAME'] + ' ' + UE_LAST_NAME;
                    else
                        return '';
                }
            },
            {
                data: 'UR_FIRST_NAME',
                render: function(data, type, full, meta) {
                    let UR_LAST_NAME = (full['UR_LAST_NAME'] != '' && full['UR_LAST_NAME'] != null) ?
                        full['UR_LAST_NAME'] : '';
                    if (full['UR_FIRST_NAME'] != null)
                        return full['UR_FIRST_NAME'] + ' ' + UR_LAST_NAME;
                    else
                        return '';
                }
            },
            {
                data: 'RSV_TRACE_RESOLVED_ON',
                render: function(data, type, full, meta) {
                    if (full['RSV_TRACE_RESOLVED_ON'] != '1900-01-01')
                        return full['RSV_TRACE_RESOLVED_ON']+' '+full['RSV_TRACE_RESOLVED_TIME'];
                    else
                        return '';
                }
            },


        ],
        "order": [
            [1, "asc"]
        ],
        'createdRow': function(row, data, dataIndex) {

            $(row).attr('data-trace_id', data['RSV_TRACE_ID']);


            if (dataIndex == 0) {
                $(row).addClass('table-warning');
                loadTraceDetails(data['RSV_TRACE_ID']);
            }
        },


        destroy: true,
        "ordering": true,
        "searching": false,
        autowidth: true,
        responsive: true
    });
}

// Show Trace Details

function loadTraceDetails(TRACE_ID) {

    var url = '<?php echo base_url('/showTraceDetails') ?>';
    $.ajax({
        url: url,
        type: "post",
        async: false,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        data: {
            RSV_TRACE_ID: TRACE_ID
        },
        dataType: 'json',
        success: function(respn) {
            toggleButton('.delete-trace-detail', 'btn-dark', 'btn-danger', false);
            $(respn).each(function(inx, data) {
                $.each(data, function(fields, datavals) {

                    var field = $.trim(fields);
                    var dataval = $.trim(datavals);
                    // alert(field);
                    // alert(dataval);

                    if (field == 'RSV_TRACE_RESOLVED_BY' && (dataval != 0)) {
                        $(".resolve-trace-detail").attr('data-rel', 2);
                        $(".resolve-trace-detail").html(
                            '<i class="fa-solid fa-check"></i> Unresolve');
                    } else if (field == 'RSV_TRACE_RESOLVED_BY' && (dataval == 0)) {
                        $(".resolve-trace-detail").html(
                            '<i class="fa-solid fa-check"></i> Resolve');
                        $(".resolve-trace-detail").attr('data-rel', 1);
                    } else if (field == 'RSV_TRACE_DEPARTMENT') {
                        $('#' + field).val(dataval).trigger('change');

                    } else {
                        $('#' + field).val(dataval);

                    }
                });
            });
        }
    });
}

// Delete Trace Detail
$(document).on('click', '.delete-trace-detail', function() {
    hideModalAlerts();
    var resvID = $("#TRACE_RESV_ID").val();
    $('.dtr-bs-modal').modal('hide');
    var TRACE_ID = $('#Trace_Details').find("tr.table-warning").data("trace_id");

    bootbox.confirm({
        message: "Trace is active. Do you want to Delete?",
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
                    url: '<?php echo base_url('/deleteTraces') ?>',
                    type: "post",
                    data: {
                        TRACE_ID: TRACE_ID,
                        RESV_ID: resvID
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    dataType: 'json',
                    success: function(respn) {
                        var response = respn['SUCCESS'];
                        if (response == '0') {
                            clearFormFields('#tracesDiv');
                            showModalAlert('error',
                                '<li>The trace cannot be deleted</li>');
                            $('#warningModal').delay(2500).fadeOut();
                        } else {
                            blockLoader('#tracesDiv');
                            showModalAlert('warning',
                                '<li>The trace has been deleted</li>');
                            $('#warningModal').delay(2500).fadeOut();
                            $('#RSV_TRACE_DEPARTMENT').val('');
                            $('#RSV_TRACE_TEXT').val('');
                            $('#RSV_TRACE_TIME').val('');
                            showTraces(resvID);
                        }
                    }
                });


            }
        }
    });

});



$(document).on('click', '#Trace_Details > tbody > tr', function() {

    $('#Trace_Details').find('tr.table-warning').removeClass('table-warning');
    $(this).addClass('table-warning');
    $.when(loadTraceDetails($(this).data('trace_id')))
        .done(function() {})
        .done(function() {
            blockLoader('#tracesDiv');
        });
});


$(document).on('click', '.resolve-trace-detail', function() {
    var resolve = $(".resolve-trace-detail").attr('data-rel');

    var TRACE_ID = $('#Trace_Details').find("tr.table-warning").data("trace_id");
    var resvID = $("#TRACE_RESV_ID").val();
    $.ajax({
        url: '<?php echo base_url('/resolveTraces') ?>',
        type: "post",
        data: {
            TRACE_ID: TRACE_ID,
            resolve: resolve
        },
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        success: function(respn) {
            var response = respn['SUCCESS'];
            if (response == '0') {
                showModalAlert('error',
                    '<li>The trace cannot be resolved</li>');
                $('#warningModal').delay(2500).fadeOut();
            } else {
                blockLoader('#tracesDiv');
                showModalAlert('info',
                    '<li>The trace has been resolved</li>');
                $('#warningModal').delay(2500).fadeOut();
                showTraces(resvID);

            }
        }
    });
});


$(document).on('hide.bs.modal', '#edit-customer', function() {
    // put your default event here

    if ($("#appcompanyWindow").hasClass('show')) { // If Accompany Guest popup is displayed
        $('.accompany-guests').trigger('click');
    }
});




// Display function clearFormFields

// Display function toggleButton
<?php echo isset($toggleButton_javascript) ? $toggleButton_javascript : ''; ?>

// Display function clearFormFields
<?php echo isset($clearFormFields_javascript) ? $clearFormFields_javascript : ''; ?>

// Display function blockLoader
<?php echo isset($blockLoader_javascript) ? $blockLoader_javascript : ''; ?>
</script>

<?= $this->endSection() ?>