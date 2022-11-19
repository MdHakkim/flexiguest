<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>
<?= $this->include('Layout/SuccessReport') ?>
<?= $this->include('Layout/ErrorReport') ?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Reservation /</span> Room Type</h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid p-3">
                <table id="dataTable_view" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Room Type Code</th>
                            <th>Room Type Description</th>
                            <th>Feature</th>
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

    <div class="modal fade" id="popModalWindow" tabindex="-1" aria-lableledby="popModalWindowlable" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="popModalWindowlable">Room Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="submitForm">
                        <div class="row g-3">
                            <input type="hidden" name="RM_TY_ID" id="RM_TY_ID" class="form-control" />
                            <div class="col-md-6">
                                <label class="form-label">Room Class</label>
                                <select name="RM_TY_ROOM_CLASS" id="RM_TY_ROOM_CLASS" data-width="100%"
                                    class="selectpicker RM_TY_ROOM_CLASS" data-live-search="true">
                                    <option value="">Select</option>
                                </select>
                                <input type="hidden" name="RM_CL_ID" id="RM_CL_ID" value="" readonly />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Room Type</label>
                                <input type="text" name="RM_TY_CODE" id="RM_TY_CODE" class="form-control"
                                    placeholder="room code" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Room Type Description</label>
                                <input type="text" name="RM_TY_DESC" id="RM_TY_DESC" class="form-control"
                                    placeholder="room description" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Display Seq./Total Rooms</label>
                                <div class="input-group mb-3">
                                    <input type="number" name="RM_TY_DISP_SEQ" id="RM_TY_DISP_SEQ" class="form-control"
                                        placeholder="display seq." />
                                    <input type="number" name="RM_TY_TOTAL_ROOM" id="RM_TY_TOTAL_ROOM"
                                        class="form-control" placeholder="total rooms" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Pub. Rate Amount</label>
                                <input type="text" name="RM_TY_PUBLIC_RATE_AMT" id="RM_TY_PUBLIC_RATE_AMT"
                                    class="form-control" placeholder="room description" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Pub. Rate Code</label>
                                <select name="RM_TY_PUBLIC_RATE_CODE" id="RM_TY_PUBLIC_RATE_CODE"
                                    class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Feature</label>
                                <select name="RM_TY_FEATURE[]" id="RM_TY_FEATURE" class="select2 form-select" multiple>
                                    <!-- <option value="">Select</option> -->
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Default/Maximum Occupancy</label>
                                <div class="input-group mb-3">
                                    <input type="number" name="RM_TY_DEFUL_OCCUPANCY" id="RM_TY_DEFUL_OCCUPANCY"
                                        class="form-control" placeholder="display seq." />
                                    <input type="number" name="RM_TY_MAX_OCCUPANCY" id="RM_TY_MAX_OCCUPANCY"
                                        class="form-control" placeholder="maximum occupany" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Maximum Adults/Children</label>
                                <div class="input-group mb-3">
                                    <input type="number" name="RM_TY_MAX_ADULTS" id="RM_TY_MAX_ADULTS"
                                        class="form-control" placeholder="max adults" />
                                    <input type="number" name="RM_TY_MAX_CHILDREN" id="RM_TY_MAX_CHILDREN"
                                        class="form-control" placeholder="max children" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Minimum Occupancy</label>
                                <input type="text" name="RM_TY_MIN_OCCUPANCY" id="RM_TY_MIN_OCCUPANCY"
                                    class="form-control" placeholder="minimum occpancy" />
                            </div>
                            <div class="col-md-12 flxi_ds_flx">
                                <div class="form-check mt-3 me-1">
                                    <input class="form-check-input flxCheckBox" type="checkbox"
                                        id="RM_TY_PSEUDO_RM_CHK">
                                    <input type="hidden" name="RM_TY_PSEUDO_RM" id="RM_TY_PSEUDO_RM" value="N"
                                        class="form-control" />
                                    <label class="form-check-lable" for="defaultCheck1"> Pseudo Room </label>
                                </div>
                                <div class="form-check mt-3 me-1">
                                    <input class="form-check-input flxCheckBox" type="checkbox" value="N"
                                        id="RM_TY_HOUSEKEEPING_CHK">
                                    <input type="hidden" name="RM_TY_HOUSEKEEPING" id="RM_TY_HOUSEKEEPING" value="N"
                                        class="form-control" />
                                    <label class="form-check-lable" for="defaultCheck1"> Housekeeping </label>
                                </div>
                                <div class="form-check mt-3">
                                    <input class="form-check-input flxCheckBox" type="checkbox" value="N"
                                        id="RM_TY_SEND_T_INTERF_CHK">
                                    <input type="hidden" name="RM_TY_SEND_T_INTERF" id="RM_TY_SEND_T_INTERF" value="N"
                                        class="form-control" />
                                    <label class="form-check-lable" for="defaultCheck1"> Send to Interface </label>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="submitBtn" onClick="submitForm('submitForm')"
                        class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Modal window -->

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
            'url': '<?php echo base_url('/roomTypeView')?>'
        },
        'columns': [{
                data: 'RM_TY_CODE'
            },
            {
                data: 'RM_TY_DESC'
            },
            {
                data: 'RM_TY_FEATURE'
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
                        '<li><a href="javascript:;" data_sysid="' + data['RM_TY_ID'] +
                        '" class="dropdown-item editWindow">Edit</a></li>' +
                        '<div class="dropdown-divider"></div>' +
                        '<li><a href="javascript:;" data_sysid="' + data['RM_TY_ID'] +
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
        '<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" onClick="addForm()"><i class="fa-solid fa-plus fa-lg"></i> Add</button></div></div>'
    );

});

function runInitialLevel() {
    $.ajax({
        url: '<?php echo base_url('/getSupportingRoomClassLov')?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        async: false,
        success: function(respn) {
            var memData = respn[0];
            var idArray = ['RM_TY_PUBLIC_RATE_CODE', 'RM_TY_FEATURE'];
            $(respn).each(function(ind, data) {
                var option = (idArray[ind] == 'RM_TY_FEATURE' ? '' :
                    '<option value="">Select</option>');
                $.each(data, function(i, valu) {
                    var value = $.trim(valu['CODE']); //fields.trim();
                    var desc = $.trim(valu['DESCS']); //datavals.trim();
                    option += '<option value="' + value + '">' + desc + '</option>';
                });
                $('#' + idArray[ind]).html(option);
            });
        }
    });
}

$(document).on('click', '.flxCheckBox', function() {
    var checked = $(this).is(':checked');
    var parent = $(this).parent();
    if (checked) {
        parent.find('input[type=hidden]').val('Y');
    } else {
        parent.find('input[type=hidden]').val('N');
    }
});

function addForm() {
    $(':input', '#submitForm').not('[type="radio"]').val('').prop('checked', false).prop('selected', false);
    $('#RM_TY_ROOM_CLASS').html('<option value="">Select</option>').selectpicker('refresh');
    $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    $('#popModalWindow').modal('show');
    runInitialLevel();
    $('#RM_TY_FEATURE').val('').trigger('change');
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
                    url: '<?php echo base_url('/deleteRoomType')?>',
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

$(document).on('keyup', '.RM_TY_ROOM_CLASS .form-control', function() {
    var search = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/roomClassList')?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            search: search
        },
        // dataType:'json',
        success: function(respn) {

            $('#RM_TY_ROOM_CLASS').html(respn).selectpicker('refresh');
        }
    });
});

//Select Room Class
$(document).on('change', '#RM_TY_ROOM_CLASS', function() {

    var selectedRClass = $(this).val();
    var selectedRClassId = selectedRClass != '' ? $(this).find('option:selected').attr('data-room-class-id') : '';
    $('#RM_CL_ID').val(selectedRClassId);
    
});

$(document).on('click', '.editWindow', function() {
    runInitialLevel();
    var sysid = $(this).attr('data_sysid');
    $('#popModalWindow').modal('show');
    var url = '<?php echo base_url('/editRoomType')?>';
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
                    if (field == 'RM_TY_ROOM_CLASS') {
                        var option = '<option value="' + dataval + '">' + data[
                            field + '_DESC'] + '</option>';
                        $('#' + field).html(option).selectpicker('refresh');
                    } else if (field == 'RM_TY_PSEUDO_RM' || field ==
                        'RM_TY_HOUSEKEEPING' || field == 'RM_TY_SEND_T_INTERF') {
                        if (dataval == 'Y') {
                            $('#' + field + '_CHK').prop('checked', true);
                        } else {
                            $('#' + field + '_CHK').prop('checked', false)
                        }
                    } else if (field == 'RM_TY_FEATURE') {
                        var feture = dataval.split(',');
                        $('#' + field).val(feture).trigger('change');
                    } else {
                        $('#' + field).val(dataval);
                    }
                });
            });
            $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
        }
    });
});

function submitForm(id) {
    $('#errorModal').hide();
    var formSerialization = $('#' + id).serializeArray();
    var url = '<?php echo base_url('/insertRoomType')?>';
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
                showModalAlert('error', error);
            } else {
                var alertText = $('#RM_TY_ID').val() == '' ? '<li>The new Room Type has been added </li>' :
                    '<li>The Room Type has been updated</li>';

                showModalAlert('success', alertText);

                $('#popModalWindow').modal('hide');
                $('#dataTable_view').dataTable().fnDraw();
            }
        }
    });
}
</script>

<?=$this->endSection()?>