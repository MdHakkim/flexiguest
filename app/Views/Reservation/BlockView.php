<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/SuccessReport') ?>
<?= $this->include('Layout/ErrorReport') ?>

<style>
.form-label {
    font-weight: bold;
}

#blk_reservations_list .dataTables_empty {
    text-align: left !important;
    padding-left: 25% !important;
}

.sel2Container {
    display: inline-block !important;
}

.sel2Container .select2-container {
    width: 87% !important;
    float: left;
}
</style>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Reservations /</span> Block
            Reservations</h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid p-3">
                <table id="dataTable_view" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Block Code</th>
                            <th>Block Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Res Type</th>
                            <th>Res Method</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>

        <!--/ Multilingual -->
    </div>
    <!-- / Content -->

    <!-- Modal Window -->

    <div class="modal fade" id="blockFormPopup" tabindex="-1" aria-labelledby="blockFormPopuplabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="blockFormPopuplabel">Block Reservation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="border rounded p-2">

                        <div class="card-header border-bottom">

                            <ul class="nav nav-pills" role="tablist">
                                <li class="nav-item">
                                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#navs-block-details" aria-controls="navs-block-details"
                                        aria-selected="true">
                                        Block Details
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#navs-block-reservations"
                                        aria-controls="navs-block-reservations" aria-selected="false">
                                        Reservations
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#navs-room-pool" aria-controls="navs-room-pool"
                                        aria-selected="false">
                                        Room Pool
                                    </button>
                                </li>
                            </ul>

                        </div>

                        <div class="tab-content">

                            <div class="tab-pane fade show active" id="navs-block-details" role="tabpanel">

                                <form id="blockForm">
                                    <div class="row g-3">
                                        <input type="hidden" name="BLK_ID" id="BLK_ID" class="form-control BLK_ID" />
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Company</label>
                                            <select name="BLK_COMP" id="BLK_COMP" data-width="100%"
                                                class="selectpicker BLK_COMP" data-live-search="true">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Agent</label>
                                            <select name="BLK_AGENT" id="BLK_AGENT" data-width="100%"
                                                class="selectpicker BLK_AGENT" data-live-search="true">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Group</label>
                                            <select name="BLK_GROUP" id="BLK_GROUP" data-width="100%"
                                                class="selectpicker BLK_GROUP" data-live-search="true">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3"></div>

                                        <div class="col-md-3">
                                            <label class="form-label">Block Name *</label>
                                            <input type="text" name="BLK_NAME" id="BLK_NAME" class="form-control"
                                                placeholder="block name" />
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Block Code *</label>
                                            <input type="text" name="BLK_CODE" id="BLK_CODE" class="form-control"
                                                placeholder="block code" />
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Start Date *</label>
                                            <input type="text" id="BLK_START_DT" name="BLK_START_DT"
                                                class="form-control dateField" placeholder="d-M-YYYY">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">End Date *</label>
                                            <input type="text" id="BLK_END_DT" name="BLK_END_DT"
                                                class="form-control dateField" placeholder="d-M-YYYY">
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label class="form-label">Nights</label>
                                            <input type="number" name="BLK_NIGHT" id="BLK_NIGHT" class="form-control"
                                                placeholder="eg: 3" min="0" step="1" />
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Status *</label>
                                            <select name="BLK_STATUS" id="BLK_STATUS" class=" select2 form-select"
                                                data-allow-clear="true">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Reservation Type *</label>
                                            <select name="BLK_RESER_TYPE" id="BLK_RESER_TYPE"
                                                class=" select2 form-select" data-allow-clear="true">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Market *</label>
                                            <select name="BLK_MARKET" id="BLK_MARKET" class=" select2 form-select"
                                                data-allow-clear="true">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check mt-4 ps-5">
                                                <input class="form-check-input flxCheckBox" type="checkbox"
                                                    id="BLK_ELASTIC_CHK">
                                                <input type="hidden" name="BLK_ELASTIC" id="BLK_ELASTIC" value="N"
                                                    class="form-control" />
                                                <label class="form-label" for="BLK_ELASTIC_CHK"> Elastic </label>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">Source *</label>
                                            <select name="BLK_SOURCE" id="BLK_SOURCE" class=" select2 form-select"
                                                data-allow-clear="true">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Reservation Method</label>
                                            <select name="BLK_RESER_METHOD" id="BLK_RESER_METHOD"
                                                class=" select2 form-select" data-allow-clear="true">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Cutoff Date</label>
                                            <div class="input-group mb-2">
                                                <input type="text" id="BLK_CUTOFF_DT" name="BLK_CUTOFF_DT"
                                                    class="form-control dateField" placeholder="d-M-YYYY" />
                                                <span class="input-group-append">
                                                    <span class="input-group-text bg-light d-block">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Cutoff Days</label>
                                            <input type="number" name="BLK_CUTOFF_DAYS" id="BLK_CUTOFF_DAYS"
                                                class="form-control" placeholder="member no" />
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Rate Code</label>
                                            <select name="BLK_RATE_CODE" id="BLK_RATE_CODE" class=" select2 form-select"
                                                data-allow-clear="true">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Packages</label>
                                            <select name="BLK_PACKAGE" id="BLK_PACKAGE" class=" select2 form-select"
                                                data-allow-clear="true">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                    </div>

                                </form>

                            </div>

                            <div class="tab-pane fade" id="navs-block-reservations" role="tabpanel">

                                <!-- Reservations List -->
                                <div class="d-flex justify-content-between mb-3">
                                    <button type="button" class="btn btn-primary addBlkResrv" data_blockcode=""
                                        data_blockId="">
                                        <i class='fa-solid fa-plus'></i> Add New
                                    </button>&nbsp;

                                    <button type="button" class="btn btn-info toggleAdvSearch">
                                        <i class='bx bxs-chevron-down'></i> Advanced Search
                                    </button>
                                </div>

                                <form class="dt_adv_search mb-2" method="POST">
                                    <div class="border rounded p-3 mb-3 advanced_fields" style="display: none;">
                                        <div class="row g-3">
                                            <div class="col-4 col-sm-6 col-lg-4">
                                                <div class="row mb-3">
                                                    <label class="col-form-label col-md-4"
                                                        style="text-align: right;"><b>Name:</b></label>
                                                    <div class="col-md-8">
                                                        <input type="text" id="S_GUEST_NAME" name="S_GUEST_NAME"
                                                            class="form-control dt-input" data-column="0"
                                                            placeholder="" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-form-label col-md-4"
                                                        style="text-align: right;"><b>First
                                                            Name:</b></label>
                                                    <div class="col-md-8">
                                                        <input type="text" id="S_CUST_FIRST_NAME"
                                                            name="S_CUST_FIRST_NAME" class="form-control dt-input"
                                                            data-column="0" placeholder="" />

                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-form-label col-md-4"
                                                        style="text-align: right;"><b>Email:</b></label>
                                                    <div class="col-md-8">
                                                        <input type="text" id="S_CUST_EMAIL" name="S_CUST_EMAIL"
                                                            class="form-control dt-input" data-column="0"
                                                            placeholder="" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-form-label col-md-4"
                                                        style="text-align: right;"><b>Contact
                                                            No:</b></label>
                                                    <div class="col-md-8">
                                                        <input type="text" id="S_GUEST_PHONE" name="S_GUEST_PHONE"
                                                            class="form-control dt-input" data-column="0"
                                                            placeholder="" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-form-label col-md-4"
                                                        style="text-align: right;"><b>Company:</b></label>
                                                    <div class="col-md-8">
                                                        <input type="text" id="S_COMPNAME" name="S_COMPNAME"
                                                            class="form-control dt-input" data-column="19"
                                                            placeholder="" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-form-label col-md-4"
                                                        style="text-align: right;"><b>Travel
                                                            Agent:</b></label>
                                                    <div class="col-md-8">
                                                        <input type="text" id="S_AGENTNAME" name="S_AGENTNAME"
                                                            class="form-control dt-input" data-column="19"
                                                            placeholder="" />
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-4 col-sm-6 col-lg-4">

                                                <div class="row mb-3">
                                                    <label class="col-form-label col-md-4"
                                                        style="text-align: right;"><b>Arrival
                                                            From:</b></label>
                                                    <div class="col-md-8">
                                                        <input type="text" id="S_ARRIVAL_FROM" name="S_ARRIVAL_FROM"
                                                            class="form-control dt-date" placeholder="" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-form-label col-md-4"
                                                        style="text-align: right;"><b>Departure
                                                            From:</b></label>
                                                    <div class="col-md-8">
                                                        <input type="text" id="S_DEPARTURE_FROM" name="S_DEPARTURE_FROM"
                                                            class="form-control dt-date" placeholder="" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-form-label col-md-4"
                                                        style="text-align: right;"><b>Room
                                                            No:</b></label>
                                                    <div class="col-md-8">
                                                        <input type="text" id="S_RESV_ROOM" name="S_RESV_ROOM"
                                                            class="form-control dt-input" placeholder="" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-form-label col-md-4"
                                                        style="text-align: right;"><b>Room
                                                            Type:</b></label>
                                                    <div class="col-md-8">
                                                        <select id="S_RESV_RM_TYPE" name="S_RESV_RM_TYPE"
                                                            class="select2 form-select dt-input"
                                                            data-allow-clear="true">
                                                            <option value=""></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-form-label col-md-4"
                                                        style="text-align: right;"><b>Created
                                                            On:</b></label>
                                                    <div class="col-md-8">
                                                        <input type="text" id="S_RESV_CREATE_DT" name="S_RESV_CREATE_DT"
                                                            class="form-control dt-date" data-column="16"
                                                            placeholder="" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-4 col-sm-6 col-lg-4">

                                                <div class="row mb-3">
                                                    <label class="col-form-label col-md-4"
                                                        style="text-align: right;"><b>Arrival
                                                            To:</b></label>
                                                    <div class="col-md-8">
                                                        <input type="text" id="S_ARRIVAL_TO" name="S_ARRIVAL_TO"
                                                            class="form-control dt-date" data-column="16"
                                                            placeholder="" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-form-label col-md-4"
                                                        style="text-align: right;"><b>Departure
                                                            To:</b></label>
                                                    <div class="col-md-8">
                                                        <input type="text" id="S_DEPARTURE_TO" name="S_DEPARTURE_TO"
                                                            class="form-control dt-date" data-column="16"
                                                            placeholder="" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-form-label col-md-4"
                                                        style="text-align: right;"><b>Search
                                                            Type:</b></label>
                                                    <div class="col-md-8">
                                                        <select id="S_SEARCH_TYPE" name="S_SEARCH_TYPE"
                                                            class="form-select dt-select" data-column="1">
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
                                                    <label class="col-form-label col-md-4"
                                                        style="text-align: right;"><b>Conf
                                                            No:</b></label>
                                                    <div class="col-md-8">
                                                        <input type="text" id="S_RESV_NO" name="S_RESV_NO"
                                                            class="form-control dt-input" placeholder="" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-form-label col-md-4"
                                                        style="text-align: right;"><b>Created
                                                            By:</b></label>
                                                    <div class="col-md-8">
                                                        <select id="S_RESV_CREATE_UID" name="S_RESV_CREATE_UID"
                                                            class="select2 form-select dt-input"
                                                            data-allow-clear="true">
                                                            <option value=""></option>
                                                            <?php
                                                            if ($userList != NULL) {
                                                                foreach ($userList as $userItem) {
                                                            ?> <option value="<?= $userItem['USR_ID']; ?>">
                                                                <?= $userItem['USR_NAME']; ?>
                                                            </option>
                                                            <?php   }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 text-end mt-3">
                                                        <button type="button" class="btn btn-primary submitAdvSearch">
                                                            <i class='bx bx-search'></i>&nbsp;
                                                            Search
                                                        </button>&nbsp;
                                                        <button type="button"
                                                            class="btn btn-secondary clearAdvSearch">Clear</button>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>

                                        <div class="row g-3">

                                            <div class="col-4 col-sm-6 col-lg-4">


                                            </div>

                                            <div class="col-4 col-sm-6 col-lg-4">




                                            </div>
                                        </div>

                                    </div>

                                </form>

                                <div class="blk_reservations_list_div table-responsive text-nowrap">
                                    <table id="blk_reservations_list" class="table table-hover table-striped">
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

                            <div class="tab-pane fade" id="navs-room-pool" role="tabpanel">

                                <div class="room_pool_div table-responsive text-nowrap">
                                    
                                </div>

                            </div>


                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="submitBtn" onClick="submitForm('blockForm')"
                        class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Modal window -->

    <div class="modal fade" id="addBlkResrvModalWindow" data-backdrop="static" data-keyboard="false"
        aria-lableledby="addBlkResrvModalWindowlable" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBlkResrvModalWindowlabel">Create Reservations under Block</h5>
                    <button type="button" class="btn-close addResvModalClose"></button>
                </div>
                <div class="modal-body">

                    <form id="newResForm" class="form-repeater needs-validation" novalidate>
                        <div data-repeater-list="ADD_RESV">
                            <div data-repeater-item>
                                <div class="row addResvRow">
                                    <div class="col-12 col-md-3 mb-0">
                                        <label class="form-label">Guest Name *</label>
                                        <div class="input-group sel2Container mb-2">
                                            <select id="form-repeater-1-1" name="RESV_NAME"
                                                class="select2 form-control RESV_NAME" data-field-name="Guest Name"
                                                data-live-search="true">
                                                <option value="">Select</option>
                                            </select>
                                            <button type="button" onClick="childReservation('C')"
                                                class="btn flxi_btn btn-sm btn-primary"><i class="fa fa-plus"
                                                    aria-hidden="true"></i></button>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-2 mb-0">
                                        <label class="form-label">Arrival Date *</label>
                                        <div class="input-group mb-2">
                                            <input type="text" id="form-repeater-1-2" name="RESV_ARRIVAL_DT"
                                                class="form-control RESV_ARRIVAL_DT dateNewResField"
                                                placeholder="d-M-YYYY">
                                            <span class="input-group-append">
                                                <span class="input-group-text bg-light d-block">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-2 mb-0">
                                        <label class="form-label">Departure Date *</label>
                                        <div class="input-group mb-2">
                                            <input type="text" id="form-repeater-1-3" name="RESV_DEPARTURE"
                                                class="form-control RESV_DEPARTURE dateNewResField"
                                                placeholder="d-M-YYYY">
                                            <span class="input-group-append">
                                                <span class="input-group-text bg-light d-block">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-2 mb-0">
                                        <label class="form-label">Room Type *</label>
                                        <select name="RESV_RM_TYPE" id="form-repeater-1-4" data-width="100%"
                                            class="select2 form-select RESV_RM_TYPE" data-field-name="Room Type"
                                            data-allow-clear="true">
                                            <option value="">Select</option>
                                        </select>
                                        <input type="hidden" name="RESV_RM_TYPE_ID" id="form-repeater-1-7"
                                            class="form-select RESV_RM_TYPE_ID" value="" readonly />
                                        <input type="hidden" name="RESV_FEATURE" id="form-repeater-1-10"
                                            class="form-select RESV_FEATURE" value="" readonly />
                                        <input type="hidden" name="RESV_ROOM_CLASS" id="form-repeater-1-11"
                                            class="form-select RESV_ROOM_CLASS" value="" readonly />
                                    </div>

                                    <div class="col-12 col-md-1 mb-0">
                                        <label class="form-label">Rooms *</label>
                                        <input type="number" class="form-control RESV_NO_F_ROOM" name="RESV_NO_F_ROOM"
                                            id="form-repeater-1-5" value="" />
                                        <input type="hidden" name="MAX_NO_F_ROOM" id="form-repeater-1-8"
                                            class="form-select MAX_NO_F_ROOM" value="" readonly />
                                    </div>

                                    <div class="col-12 col-md-2 mb-0">
                                        <label class="form-label">Rate Code *</label>
                                        <select name="RESV_RATE_CODE" id="form-repeater-1-6" data-width="100%"
                                            class="select2 form-select RESV_RATE_CODE" data-field-name="Rate Code"
                                            data-allow-clear="true">
                                            <option value="">Select</option>
                                        </select>
                                        <input type="hidden" name="RESV_RATE" id="form-repeater-1-9"
                                            class="form-select RESV_RATE" value="" readonly />
                                    </div>

                                    <div class="d-flex col-12 col-md-2 align-items-center mb-0">
                                        <button class="btn btn-label-danger mt-2" data-repeater-delete>
                                            <i class="bx bx-x"></i>
                                            <span class="align-left">Delete</span>
                                        </button>
                                    </div>
                                </div>
                                <hr />
                            </div>
                        </div>
                        <div class="mb-0" style="float: left;">
                            <button class="btn btn-primary addMoreResv" data-repeater-create>
                                <i class="bx bx-plus"></i>
                                <span class="align-middle">Add New</span>
                            </button>
                        </div>
                        <div style="float: right;">
                            <input type="hidden" name="main_BLK_ID" id="main_BLK_ID" class="form-control BLK_ID" />
                            <input type="hidden" name="main_BLK_COMPANY" id="main_BLK_COMPANY"
                                class="form-control BLK_COMP" />
                            <input type="hidden" name="main_BLK_AGENT" id="main_BLK_AGENT"
                                class="form-control BLK_AGENT" />
                            <input type="hidden" name="main_BLK_MARKET" id="main_BLK_MARKET"
                                class="form-control BLK_MARKET" />
                            <input type="hidden" name="main_BLK_SOURCE" id="main_BLK_SOURCE"
                                class="form-control BLK_SOURCE" />
                            <button type="button" class="btn btn-secondary addResvModalClose">Close</button>
                            <button type="button" onClick="submitNewResForm('newResForm')"
                                class="btn btn-primary">Save</button>
                        </div>
                    </form>
                    <div class="form-text" style="clear: both; padding-top: 20px;">Rooms will be split into separate
                        Reservations </div>

                </div>

            </div>
        </div>
    </div>

    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->
<script>
var compAgntMode = '';
var linkMode = '';
$(document).ready(function() {
    linkMode = 'EX';
    $('#dataTable_view').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/blockView') ?>'
        },
        'columns': [{
                data: 'BLK_CODE'
            },
            {
                data: 'BLK_NAME'
            },
            {
                data: 'BLK_START_DT'
            },
            {
                data: 'BLK_END_DT'
            },
            {
                data: 'BLK_STATUS'
            },
            {
                data: 'BLK_RESER_TYPE'
            },
            {
                data: 'BLK_RESER_METHOD'
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
                        '<li><a href="javascript:;" data_sysid="' + data['BLK_ID'] +
                        '" data-blockcode="' + data['BLK_CODE'] +
                        '" data-blockname="' + data['BLK_NAME'] +
                        '" class="dropdown-item editWindow">Edit</a></li>' +
                        '<div class="dropdown-divider"></div>' +
                        '<li><a href="javascript:;" data_sysid="' + data['BLK_ID'] +
                        '" class="dropdown-item text-danger delete-record">Delete</a></li>' +
                        '</ul>' +
                        '</div>'
                    );
                }
            },
        ],
        autowidth: true

    });
    $("#dataTable_view_wrapper .row:first").before(
        '<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" onClick="addBlockResvation()"><i class="fa-solid fa-plus fa-lg"></i> Add</button></div></div>'
    );
    $('.dateField,.dt-date,.dateNewResField').datepicker({
        format: 'd-M-yyyy',
        autoclose: true
    });

    // $("#BLK_START_DT").datepicker().on('changeDate', function(selected) {
    //     console.log('startChange', selected);
    //     if (selected.dates.length) {
    //         var minDate = new Date(selected.date.valueOf());
    //         $('#BLK_END_DT,.RESV_ARRIVAL_DT,.RESV_DEPARTURE').datepicker('setStartDate', minDate);
    //     }
    // });

    // $("#BLK_END_DT").datepicker().on('changeDate', function(selected) {
    //     console.log('endChange', selected);
    //     if (selected.dates.length) {
    //         var maxDate = new Date(selected.date.valueOf());
    //         $('#BLK_CUTOFF_DT,.RESV_ARRIVAL_DT,.RESV_DEPARTURE').datepicker('setEndDate', maxDate);
    //     }
    // });

});

$(document).on('click', '.nav-item', function() {

    if ($(this).find('.nav-link').hasClass('disabled')) {
        if ($(this).find('.nav-link').data('bs-target') == '#navs-block-reservations') {
            if ($('#BLK_ID').val() == '')
                showModalAlert('error',
                    '<li>The Block has to be saved first before adding Reservations</li>'
                );
            else
                showModalAlert('error',
                    '<li>The Block Status has to be \'Tentative\' or \'Definite\' to add or display Reservations</li>'
                );
        }
    }

});

function addBlockResvation() {
    clearFormFields('#blockForm');
    $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    $('#blockFormPopup').modal('show');

    $('#blockFormPopuplabel').html('Add Block Reservation');

    // runSupportingResevationLov();
    runSupportingLov();

    showBlockRoomPool();

    $('[data-bs-target="#navs-block-details"]').trigger('click');
    $('[data-bs-target="#navs-block-reservations"]').addClass('disabled');

    var today = moment().format('DD-MM-YYYY');
    var end = moment().add(1, 'days').format('DD-MM-YYYY');

    $('#BLK_START_DT,#BLK_END_DT,#BLK_CUTOFF_DT').datepicker('setStartDate', today);
}

$(document).on('click', '.editWindow', function() {
    var sysid = $(this).attr('data_sysid');
    $('#BLK_ID').val(sysid);

    $('.addBlkResrv').attr('data_blockcode', $(this).data('blockname'));
    $('.addBlkResrv').attr('data_blockId', sysid);

    $('#blockFormPopup').modal('show');
    $('#blockFormPopuplabel').html('Edit Block Reservation ' + $(this).data('blockname'));

    //Make First Tab active
    $('[data-bs-target="#navs-block-details"]').trigger('click');

    runSupportingLov();

    showBlockReservations(sysid); // List Reservations in 2nd Tab
    
    showBlockRoomPool(sysid); // Show Room Pool in 3rd Tab

    var url = '<?php echo base_url('/editBlock') ?>';
    $.ajax({
        url: url,
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            sysid: sysid
        },
        dataType: 'json',
        success: function(respn) {

            $(respn).each(function(inx, data) {
                $.each(data, function(fields, datavals) {
                    var field = $.trim(fields); //fields.trim();
                    var dataval = $.trim(datavals); //datavals.trim();

                    if (field == 'BLK_COMP' || field == 'BLK_AGENT' || field ==
                        'BLK_GROUP') {
                        var option = dataval == '' ?
                            '<option value="">Select</option>' : '<option value="' +
                            dataval + '">' + data[
                                field + '_DESC'] + '</option>';
                        $('#' + field).html(option).selectpicker('refresh');
                        if ($('.' + field).length)
                            $('.' + field).val(dataval);
                    } else if (field == 'BLK_ELASTIC') {
                        if (dataval == 'Y') {
                            $('#BLK_ELASTIC_CHK').prop('checked', true);
                        } else {
                            $('#BLK_ELASTIC_CHK').prop('checked', false)
                        }
                    } else if ($('#' + field).hasClass('select2')) {
                        $('#' + field).val(dataval).trigger('change');
                        if ($('.' + field).length)
                            $('.' + field).val(dataval);
                    } else if ($('#' + field).hasClass('dateField')) {
                        if (field == 'BLK_START_DT') {
                            $('#BLK_START_DT,#BLK_END_DT,#BLK_CUTOFF_DT,.RESV_ARRIVAL_DT,.RESV_DEPARTURE')
                                .datepicker('setStartDate', new Date(dataval));
                        }
                        if (field == 'BLK_END_DT') {
                            $('#BLK_CUTOFF_DT,.RESV_ARRIVAL_DT,.RESV_DEPARTURE')
                                .datepicker('setEndDate', new Date(dataval));
                        }
                        $('#' + field).datepicker({
                            format: 'd-M-yyyy',
                            autoclose: true
                        }).datepicker("setDate", new Date(dataval));
                    } else {
                        $('#' + field).val(dataval);
                        // if(field=='COM_COUNTRY'){
                        //   $('#'+field).selectpicker('refresh');
                        // }
                    }

                });
            });
            $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
        }
    });
});

function submitForm(id, mode) {
    $('#errorModal').hide();
    var formSerialization = $('#' + id).serializeArray();
    var url = '<?php echo base_url('/insertBlock') ?>';
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
                $('#errorModal').show();
                var ERROR = respn['RESPONSE']['ERROR'];
                var error = '<ul>';
                $.each(ERROR, function(ind, data) {

                    error += '<li>' + data + '</li>';
                });
                error += '<ul>';
                $('#formErrorMessage').html(error);
            } else {
                $('#blockFormPopup').modal('hide');
                var alertText = $('#BLK_ID').val() == '' ?
                    '<li>The Block Reservation has been created</li>' :
                    '<li>The Block Reservation has been updated</li>';

                showModalAlert('success', alertText);

                $('#dataTable_view').dataTable().fnDraw();
            }
        }
    });
}

function runSupportingLov() {
    $.ajax({
        url: '<?php echo base_url('/getSupportingblkLov') ?>',
        async: false,
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        success: function(respn) {
            var market = respn[0];
            var source = respn[1];
            var resmethod = respn[2];
            var startstat = respn[3];
            var allstat = respn[4];
            var restype = respn[5];

            var option = '<option value="">Select Market</option>';
            var option2 = '<option value="">Select Source</option>';
            var option3 = '<option value="">Select Method</option>';
            var option4 = '<option value="">Select Status</option>';
            var option5 = option4;
            var option6 = '<option value="">Select Reservation Type</option>';

            $(market).each(function(ind, data) {
                option += '<option value="' + data['CODE'] + '">' + data['DESCS'] + '</option>';
            });
            $(source).each(function(ind, data) {
                option2 += '<option value="' + data['CODE'] + '">' + data['DESCS'] + '</option>';
            });
            $(resmethod).each(function(ind, data) {
                option3 += '<option value="' + data['CODE'] + '">' + data['DESCS'] + '</option>';
            });
            $(startstat).each(function(ind, data) {
                option4 += '<option value="' + data['CODE'] + '" data-return-inv="' + data[
                    'RET_INV'] + '">' + data['DESCS'] + '</option>';
            });
            $(allstat).each(function(ind, data) {
                option5 += '<option value="' + data['CODE'] + '" data-return-inv="' + data[
                    'RET_INV'] + '">' + data['DESCS'] + '</option>';
            });
            $(restype).each(function(ind, data) {
                option6 += '<option value="' + data['CODE'] + '">' + data['DESCS'] + '</option>';
            });


            $('#BLK_MARKET').html(option);
            $('#BLK_SOURCE').html(option2);
            $('#BLK_RESER_METHOD').html(option3);
            $('#BLK_STATUS').html($('#BLK_ID').val() == '' ? option4 : option5);
            $('#BLK_RESER_TYPE').html(option6);
        }
    });
}

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
                    url: '<?php echo base_url('/deleteBlock') ?>',
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

$(document).on('click', '.flxCheckBox', function() {
    var checked = $(this).is(':checked');
    var parent = $(this).parent();
    if (checked) {
        parent.find('input[type=hidden]').val('Y');
    } else {
        parent.find('input[type=hidden]').val('N');
    }
});

$(document).on('keyup', '.BLK_COMP .form-control', function() {
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

            $('#BLK_COMP').html(respn).selectpicker('refresh');
        }
    });
});
$(document).on('keyup', '.BLK_AGENT .form-control', function() {
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

            $('#BLK_AGENT').html(respn).selectpicker('refresh');
        }
    });
});
$(document).on('keyup', '.BLK_GROUP .form-control', function() {
    var search = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/groupList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            search: search
        },
        // dataType:'json',
        success: function(respn) {

            $('#BLK_GROUP').html(respn).selectpicker('refresh');
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
        success: function(respn) {
            var memData = respn[0];
            var idArray = ['RESV_MEMBER_TY', 'RESV_RATE_CLASS', 'RESV_RATE_CODE', 'RESV_ROOM_CLASS',
                'RESV_FEATURE', 'RESV_PURPOSE_STAY'
            ];
            $(respn).each(function(ind, data) {
                var option = '<option value="">Select</option>';
                $.each(data, function(i, valu) {
                    option += '<option value="' + valu['CODE'] + '">' + valu['DESCS'] +
                        '</option>';
                });
                $('#' + idArray[ind]).html(option);
                if (idArray[ind] == 'RESV_RATE_CLASS') {
                    $('#RESV_RATE_CATEGORY').html(option);
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
    $('#compnayAgentWindow').modal('show');
}

function setNights() {
    var arr_date = $('#BLK_START_DT').val();
    var dep_date = $('#BLK_END_DT').val();

    var startDtFmt = moment(arr_date, 'DD-MMM-YYYY');
    var endDtFmt = moment(dep_date, 'DD-MMM-YYYY');

    if (startDtFmt <= endDtFmt) {
        var no_of_nights = parseInt(endDtFmt.diff(startDtFmt, 'days'));
        //alert(no_of_nights);

        $('#BLK_NIGHT').val(no_of_nights);
    }
}

$(document).on('blur', '#BLK_NIGHT', function() {
    var new_days = parseInt($(this).val()) < 0 ? 0 : parseInt($(this).val());
    var startField = $('#BLK_START_DT');
    var endField = $('#BLK_END_DT');
    var new_date = moment(startField.val(), "DD-MMM-YYYY").add(new_days, 'days').format("DD-MMM-YYYY");

    //alert(startField.val()); alert(new_date);

    endField.datepicker().datepicker("setDate", new_date);
});

$(document).on('change', '#BLK_START_DT,#BLK_END_DT', function() {
    setNights();
});

$('#BLK_STATUS').on('change.select2', function() {
    var selStatus = $(this).val();
    if (selStatus == '5' || selStatus == '6')
        $('[data-bs-target="#navs-block-reservations"]').removeClass('disabled');
    else
        $('[data-bs-target="#navs-block-reservations"]').addClass('disabled');
});

function showBlockReservations(blkId) {

    $('#blk_reservations_list').DataTable({
        'processing': true,
        async: false,
        'serverSide': true,
        'searching': false,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/blockReservationView') ?>',
            'type': 'POST',
            'data': function(d) {
                var formSerialization = $('.dt_adv_search').serializeArray();
                $(formSerialization).each(function(i, field) {
                    d[field.name] = field.value;
                });

                d["RESV_BLOCK"] = blkId;
            }
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
                        '<li><a href="<?php echo base_url('/reservation?RESV_ID=') ?>' + data[
                            'RESV_ID'] +
                        '" target="_blank" rmtype="' + data['RESV_RM_TYPE'] + '" rmtypedesc="' + data[
                            'RM_TY_DESC'] + '" data-reservation_customer_id = "' + data[
                            'CUST_ID'] +
                        '"  class="dropdown-item editReserWindow text-primary"><i class="fas fa-edit"></i> Edit</a></li>';

                    if ($.inArray(data['RESV_STATUS'], ["Checked-In", "Checked-Out"]) == -1)
                        resvListButtons += '<div class="dropdown-divider"></div>' +
                        '<li><a href="javascript:;" data_sysid="' + data['RESV_ID'] +
                        '" data_blkId="' + blkId +
                        '" class="dropdown-item text-danger deleteResv"><i class="fas fa-trash"></i> Delete</a></li>';

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
        destroy: true,
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
}

function showBlockRoomPool(blkId = '') {

    $.ajax({
        url: '<?php echo base_url('/showBlockRoomPool') ?>',
        type: 'POST',
        async: false,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            blkId: blkId
        },
        dataType: 'html',
        success: function(respn) {

            $(".room_pool_div").html(respn);

        }
    });

}


// Reservation Advanced Search Functions Starts
// --------------------------------------------------------------------

const dt_adv_filter_table = $('#blk_reservations_list');

// Advanced Search Functions Ends

$(document).on('click', '.submitAdvSearch', function() {

    //dt_adv_filter_table.dataTable().fnDraw();
    showBlockReservations($('#BLK_ID').val());
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
    //dt_adv_filter_table.dataTable().fnDraw();
    showBlockReservations($('#BLK_ID').val());
});

// Add New Reservations Button
$(document).on('click', '.addBlkResrv', function() {

    clearFormFields('#newResForm');
    var blockcode = $(this).attr('data_blockcode');
    $('#main_BLK_ID').val($(this).attr('data_blockId'));

    $('#addBlkResrvModalWindowlabel').html('Create Reservations under Block \'' + blockcode + '\'');

    //Reset repeated fields every time modal is loaded
    $('[data-repeater-item]').slice(1).empty();
    loadCustomerList('.RESV_NAME');
    $('#form-repeater-1-1').val("");

    $('#form-repeater-1-2').datepicker('setDate', new Date($("#BLK_START_DT").val()));
    $('#form-repeater-1-3').datepicker('setDate', new Date($("#BLK_END_DT").val()));

    loadRoomTypeList('.RESV_RM_TYPE');
    $('#form-repeater-1-4').val("");

    $('#blockFormPopup').modal('hide');
    $('#addBlkResrvModalWindow').modal('show');

});

$(document).on('click', '.addResvModalClose', function() {
    $('#addBlkResrvModalWindow').modal('hide');
    $('#blockFormPopup').modal('show');
    showBlockReservations($('#BLK_ID').val());
});

function validateRepeaterFields() {

    var error = 1;

    $("div[data-repeater-list='ADD_RESV']").find(".RESV_NAME").each(function(i) {
        alert($(this).val());
        if ($(this).val() == "") {
            error = 0;
            return false;
        }
    });

    return error;
}

// bootstrap-maxlength & repeater (jquery)
$(function() {
    var formRepeater = $('.form-repeater');

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

                    if ($('#' + id).hasClass('select2')) {

                        $('#' + id).select2({
                            placeholder: "Select value",
                            dropdownParent: $('#addBlkResrvModalWindow')
                        });

                        if ($('#' + id).hasClass('RESV_NAME'))
                            loadCustomerList('#' + id);
                    }
                    if ($('#' + id).hasClass('RESV_ARRIVAL_DT')) {
                        $(this).datepicker({
                            format: 'd-M-yyyy',
                            autoclose: true
                        }).datepicker('setDate', new Date($("#BLK_START_DT").val()));
                    }

                    if ($('#' + id).hasClass('RESV_DEPARTURE')) {
                        $(this).datepicker({
                            format: 'd-M-yyyy',
                            autoclose: true
                        }).datepicker('setDate', new Date($("#BLK_END_DT").val()));
                    }

                    if ($('#' + id).hasClass('RESV_RM_TYPE')) {
                        loadRoomTypeList('#' + id);
                    }

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

function loadCustomerList(elem) {
    $.ajax({
        url: '<?php echo base_url('/customerList') ?>',
        async: false,
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            search: ""
        },
        // dataType:'json',
        success: function(respn) {
            $(elem).html(respn);
        }
    });
}

function loadRoomTypeList(elem) {
    $.ajax({
        url: '<?php echo base_url('/roomTypeList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        // dataType:'json',
        success: function(respn) {
            $(elem).html(respn);
        }
    });
}

function loadNumRooms(elem) {
    $.ajax({
        url: '<?php echo base_url('/numRooms') ?>',
        async: false,
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            rmtype: $(elem).find(':selected').attr('data-room-type-id')
        },
        // dataType:'json',
        success: function(respn) {
            $(elem).closest('.addResvRow').find('.RESV_NO_F_ROOM').val(respn).attr('max', respn);
            $(elem).closest('.addResvRow').find('.MAX_NO_F_ROOM').val(respn);
        }
    });
}

function loadRateCodes(elem) {
    $.ajax({
        url: '<?php echo base_url('/getRateCodesByRoomType') ?>',
        async: false,
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            rmtype: $(elem).val(),
            arr_date: $(elem).closest('.addResvRow').find('.RESV_ARRIVAL_DT').val(),
            dep_date: $(elem).closest('.addResvRow').find('.RESV_DEPARTURE').val()
        },
        // dataType:'json',
        success: function(respn) {
            $(elem).closest('.addResvRow').find('.RESV_RATE_CODE').html(respn);
        }
    });
}

$(document).on('change.select2', '.RESV_RM_TYPE', function() {
    loadNumRooms($(this));
    loadRateCodes($(this));

    var selectedRoomType = $(this).find(":selected");

    $(this).closest('.addResvRow').find('.RESV_RM_TYPE_ID').val(selectedRoomType.data('room-type-id'));
    $(this).closest('.addResvRow').find('.RESV_FEATURE').val(selectedRoomType.data('feture'));
    $(this).closest('.addResvRow').find('.RESV_ROOM_CLASS').val(selectedRoomType.data('rmclass'));
});

$(document).on('change.select2', '.RESV_RATE_CODE', function() {

    var selectedRateCode = $(this).find(":selected");
    $(this).closest('.addResvRow').find('.RESV_RATE').val(selectedRateCode.data('rtcode-price'));
});

$('body').on('focus', ".dateNewResField", function() {
    $(this).datepicker({
        autoclose: true
    });
    $(this).datepicker('setStartDate', new Date($("#BLK_START_DT").val()));
    $(this).datepicker('setEndDate', new Date($("#BLK_END_DT").val()));

    $(this).datepicker("option", "dateFormat", 'd-M-yyyy');
});

function submitNewResForm(id, mode) {
    $('#errorModal').hide();
    var formSerialization = $('#' + id).serializeArray();
    var url = '<?php echo base_url('/insertBlockReservation') ?>';
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
                $('#errorModal').show();
                var ERROR = respn['RESPONSE']['ERROR'];
                var error = '<ul>';
                $.each(ERROR, function(ind, data) {

                    error += '<li>' + data + '</li>';
                });
                error += '<ul>';
                $('#formErrorMessage').html(error);
            } else {
                showModalAlert('success', '<li>' + respn['RESPONSE']['OUTPUT'] +
                    ' new Reservations have been created</li>');

                $('.addResvModalClose').trigger('click');
            }
        }
    });
}

$(document).on('click', '.deleteResv', function() {
    var sysid = $(this).attr('data_sysid');
    var blkId = $(this).attr('data_blkId');

    bootbox.confirm({
        message: "Are you sure you want to delete this reservation?",
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

                        showModalAlert('warning',
                            '<li>The Reservation has been deleted</li>');
                        showBlockReservations(blkId);
                    }
                });
            }
        }
    });
});

// Display function clearFormFields
<?php echo isset($clearFormFields_javascript) ? $clearFormFields_javascript : ''; ?>
</script>

<?= $this->endSection() ?>