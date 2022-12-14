<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/SuccessReport') ?>
<?= $this->include('Layout/ErrorReport') ?>

<style>
.tagify__input {
    padding-left: 6px;
}

.tagify__tag>div {
    cursor: pointer;
}

.table-hover>tbody>tr:hover {
    cursor: pointer;
}

.table-warning {
    color: #000 !important;
}

.roomTypeSelDiv .select2-search--inline {
    display: contents;
    /*this will make the container disappear, making the child the one who sets the width of the element*/
}

.roomTypeSelDiv .select2-search__field:placeholder-shown {
    width: 100% !important;
    /*makes the placeholder to be 100% of the width while there are no options selected*/
}
</style>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Housekeeping /</span> Room Status
        </h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid p-3">

                <?= $this->include('includes/RoomsStatusList') ?>

            </div>
        </div>

        <!--/ Multilingual -->
    </div>
    <!-- / Content -->

    <!-- Quick Change Room Status Modal -->
    <div class="modal fade" id="quickChangeRmStat" data-backdrop="static" data-keyboard="false"
        aria-lableledby="quickChangeRmStatlable" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="quickChangeRmStatlabel">Quick Change Room Status</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="quickChangeRmForm" class="needs-validation" novalidate>

                        <div class="mb-3 d-flex flex-row bd-highlight">
                            <label class="form-label fw-bold col-md-3 pt-2">Room Types</label>
                            <div class="col-md-9 ps-3 pe-3 roomTypeSelDiv">
                                <select name="RM_TYPES[]" id="RM_TYPES" data-width="100%" multiple
                                    class="select2 form-select" data-placeholder="Select Room Type First"
                                    data-allow-clear="true">
                                </select>
                            </div>
                        </div>

                        <div class="border rounded pt-3 p-3">
                            <div class="row mb-3">
                                <div class="col-md-3 pt-1">
                                    <div class="form-check">
                                        <input id="selectRoomsByL" name="selectRoomsBy" class="form-check-input "
                                            type="radio" value="L" checked="">
                                        <label class="form-label fw-bold" for="selectRoomsByL"> Room List </label>
                                    </div>
                                </div>
                                <div class="col-md-9 selectRoomsByCol">
                                    <select id="selectRoomsList" name="selectRoomsList[]"
                                        class="selectpicker selectRooms w-100" multiple data-style="btn btn-default"
                                        data-icon-base="bx" data-tick-icon="bx-check text-primary"
                                        data-live-search="true" data-allow-clear="true">
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3 border-top pt-3">
                                <div class="col-md-3 pt-1">
                                    <div class="form-check">
                                        <input name="selectRoomsBy" class="form-check-input" type="radio" value="N"
                                            id="selectRoomsByN">
                                        <label class="form-label fw-bold" for="selectRoomsByN"> From Room </label>
                                    </div>
                                </div>
                                <div class="col-md-4 selectRoomsByCol">
                                    <select id="selectRoomsFrom" name="selectRoomsFrom"
                                        class="selectpicker selectRooms w-100" data-style="btn btn-default"
                                        data-icon-base="bx" data-tick-icon="bx-check text-primary"
                                        data-live-search="true" data-allow-clear="true">
                                    </select>
                                </div>
                                <label class="col-md-1 pt-2 form-label text-end fw-bold">To</label>
                                <div class="col-md-4 selectRoomsByCol">
                                    <select id="selectRoomsTo" name="selectRoomsTo"
                                        class="selectpicker selectRooms w-100" data-style="btn btn-default"
                                        data-icon-base="bx" data-tick-icon="bx-check text-primary"
                                        data-live-search="true" data-allow-clear="true">
                                    </select>
                                </div>
                            </div>

                            <div class="row border-top pt-3">
                                <label class="col-md-3 pt-2 form-label fw-bold">Change Status to</label>
                                <div class="col-md-4 qcRoomStatusDiv">
                                    <!-- Change Status button here -->
                                </div>
                                <input type="hidden" id="statusToChange" name="statusToChange" value="" />
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary clearQuickChange">Reset</button>
                    <button type="button" id="quickChangeSubmitBtn" class="btn btn-primary">Update Rooms</button>
                </div>
            </div>
        </div>
    </div>

    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->

<script>

$(document).ready(function() {

    var filterData = [
        /*{
                field: '#S_PROFILE_TYPE',
                value: '1',
                status: '0'
            }*/
    ];

    loadRoomsTable(filterData);
});

// Display function toggleButton
<?php echo isset($toggleButton_javascript) ? $toggleButton_javascript : ''; ?>

// Display function clearFormFields
<?php echo isset($clearFormFields_javascript) ? $clearFormFields_javascript : ''; ?>

// Display function blockLoader
<?php echo isset($blockLoader_javascript) ? $blockLoader_javascript : ''; ?>
</script>

<?= $this->endSection() ?>