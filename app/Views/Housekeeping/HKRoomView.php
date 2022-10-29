<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>
<?= $this->include('Layout/SuccessReport') ?>
<?= $this->include('Layout/ErrorReport') ?>

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
                <div class="room_list_div table-responsive text-nowrap">
                    <table id="room_list" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Room ID</th>
                                <th class="all">Room No</th>
                                <th class="all">Room Type</th>
                                <th class="all">Room Status</th>
                                <th>FO Status</th>
                                <th>Reservation Status</th>
                                <th class="all">Floor</th>
                                <th>Room Class</th>
                                <th class="all">Features</th>
                            </tr>
                        </thead>

                    </table>
                </div>
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
                    <h5 class="modal-title" id="popModalWindowlable">Room</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="submitForm">
                        <div class="row g-3">
                            <input type="hidden" name="RM_ID" id="RM_ID" class="form-control" />
                            <div class="col-md-6">
                                <lable class="form-lable">Room No / Room Class</lable>
                                <div class="input-group mb-3">
                                    <div class="col-md-6">
                                        <input type="number" name="RM_NO" id="RM_NO" class="form-control"
                                            placeholder="room no" />
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" readonly name="RM_CLASS" id="RM_CLASS" class="form-control"
                                            placeholder="room class" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <lable class="form-lable">Room Type</lable>
                                <input type="hidden" name="RM_DESC" id="RM_DESC" class="form-control" />
                                <input type="hidden" name="RM_TYPE_REF_ID" id="RM_TYPE_REF_ID" class="form-control" />
                                <select name="RM_TYPE" id="RM_TYPE" data-width="100%" class="selectpicker RM_TYPE"
                                    data-live-search="true">
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <lable class="form-lable">Pub. Rate Code</lable>
                                <select name="RM_PUBLIC_RATE_CODE" id="RM_PUBLIC_RATE_CODE" class="select2 form-select"
                                    data-allow-clear="true">
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <lable class="form-lable">Floor Preference</lable>
                                <select name="RM_FLOOR_PREFERN" id="RM_FLOOR_PREFERN" class="select2 form-select"
                                    data-allow-clear="true">
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <lable class="form-lable">Pub. Rate Amount</lable>
                                <input type="text" name="RM_PUBLIC_RATE_AMOUNT" id="RM_PUBLIC_RATE_AMOUNT"
                                    class="form-control" placeholder="rate amount" />
                            </div>
                            <div class="col-md-6">
                                <lable class="form-lable">Smoking Preference</lable>
                                <select name="RM_SMOKING_PREFERN" id="RM_SMOKING_PREFERN" class="select2 form-select"
                                    data-allow-clear="true">
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <lable class="form-lable">Display Seq./Max Occupancy</lable>
                                <div class="input-group mb-3">
                                    <input type="number" name="RM_DISP_SEQ" id="RM_DISP_SEQ" class="form-control"
                                        placeholder="display seq." />
                                    <input type="number" name="RM_MAX_OCCUPANCY" id="RM_MAX_OCCUPANCY"
                                        class="form-control" placeholder="max occupancy" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <lable class="form-lable">Measurement/Square Units</lable>
                                <div class="input-group mb-3">
                                    <input type="number" name="RM_MEASUREMENT" id="RM_MEASUREMENT" class="form-control"
                                        placeholder="measurement" />
                                    <input type="number" name="RM_SQUARE_UNITS" id="RM_SQUARE_UNITS"
                                        class="form-control" placeholder="square units" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <lable class="form-lable">Features</lable>
                                <!-- <select name="RM_FEATURE"  id="RM_FEATURE" data-width="100%" class="selectpicker RM_FEATURE" data-live-search="true">
                              <option value="">Select</option>
                          </select> -->
                                <select name="RM_FEATURE[]" id="RM_FEATURE" class="select2 form-select" multiple>
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <lable class="form-lable">Day section</lable>
                                <select name="RM_HOUSKP_DY_SECTION" id="RM_HOUSKP_DY_SECTION" data-width="100%"
                                    class="selectpicker RM_HOUSKP_DY_SECTION" data-live-search="true">
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <lable class="form-lable">Evening section</lable>
                                <select name="RM_HOUSKP_EV_SECTION" id="RM_HOUSKP_EV_SECTION" data-width="100%"
                                    class="selectpicker RM_HOUSKP_EV_SECTION" data-live-search="true">
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <lable class="form-lable">Phone No</lable>
                                <input type="text" name="RM_PHONE_NO" id="RM_PHONE_NO" class="form-control"
                                    placeholder="phone" />
                            </div>
                            <div class="col-md-6">
                                <lable class="form-lable">Stayover/Departure Credits</lable>
                                <div class="input-group mb-3">
                                    <input type="number" name="RM_STAYOVER_CR" id="RM_STAYOVER_CR" class="form-control"
                                        placeholder="stayover" />
                                    <input type="number" name="RM_DEPARTURE_CR" id="RM_DEPARTURE_CR"
                                        class="form-control" placeholder="departure" />
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
    $('#room_list').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/hkroomView')?>'
        },
        'columns': [{
                data: ''
            }, {
                data: 'RM_ID',
                "visible": false,
            }, {
                data: 'RM_NO'
            },
            {
                data: 'RM_TYPE'
            },
            {
                data: 'RM_STATUS_CODE',
                className: "text-center"
            },
            {
                data: 'FO_STATUS',
                className: "text-center"
            },
            {
                data: 'RESV_STATUS',
                className: "text-center"
            },
            {
                data: 'RM_FLOOR_PREFERN',
                className: "text-center"
            },
            {
                data: 'RM_CLASS'
            },
            {
                data: 'RM_FEATURE'
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
            width: "2%"
        }, {
            width: "8%"
        }, {
            width: "10%"
        }, {
            // Label
            targets: 4,
            width: "10%",
            className: "text-center",
            render: function(data, type, full, meta) {

                var $status_name = full['RM_STATUS_CODE'];
                var $status_id = full['RM_STATUS_ID'];

                var $statButton = showRoomStatChange($status_id, $status_name, full[
                    'RM_ID']);
                return $statButton;
            },
        }, {
            width: "8%"
        }, {
            width: "12%"
        }, {
            width: "5%"
        }, {
            width: "10%"
        }, {
            width: "20%"
        }],
        autowidth: true,
        "order": [
            [2, "asc"]
        ],
        language: {
            emptyTable: 'There are no rooms to display'
        },
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal({
                    header: function(row) {
                        var data = row.data();
                        return 'Details of Room ' + data['RM_NO'];
                    }
                }),
                type: 'column',
                renderer: function(api, rowIdx, columns) {
                    var data = $.map(columns, function(col, i) {

                        var dataVal = col.title == 'Features' ? showFeaturesDesc(col.data) :
                            col.data;
                        var colClass = col.title == 'Features' ? 'featPopup' : '';

                        return col.title !==
                            '' // ? Do not show row in modal popup if title is blank (for check box)
                            ?
                            '<tr data-dt-row="' +
                            col.rowIndex +
                            '" data-dt-column="' +
                            col.columnIndex +
                            '">' +
                            '<td width="35%">' +
                            col.title +
                            ':' +
                            '</td> ' +
                            '<td class="' + colClass + '">' +
                            dataVal +
                            '</td>' +
                            '</tr>' :
                            '';
                    }).join('');

                    return data ? $('<table class="table"/><tbody />').append(data) : false;
                }
            }
        }

    });
    $("#room_list_wrapper .row:first").before(
        '<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" onClick=""><i class="fa-solid fa-pencil"></i>&nbsp;&nbsp;Change Selected</button></div></div>'
    );

});

function showFeaturesDesc(comma_list = '') {

    $.ajax({
        url: '<?php echo base_url('/showFeaturesDesc') ?>',
        type: 'POST',
        async: false,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            comma_list: comma_list
        },
        dataType: 'html'
    }).done(function(response) {
        ret_val = response;
    }).fail(function(jqXHR, textStatus, errorThrown) {
        ret_val = null;
    });

    return ret_val;
}

function showRoomStatChange(curStatId, curStatName, rmId) {

    var $status_name = curStatName;
    var $status_id = curStatId;

    var $status = {
        <?php foreach($room_status_list as $room_status) { ?> '<?=$room_status['RM_STATUS_ID']?>': {
            class: 'btn-<?=$room_status['RM_STATUS_COLOR_CLASS']?>',
            title: '<?=$room_status['RM_STATUS_CODE']?>'
        },
        <?php } ?>
    };
    if (typeof $status[$status_id] === 'undefined') {
        return $status_name;
    }

    var $statButton = '<button type="button" class="btn btn-sm ' + $status[
            $status_id]
        .class + ' dropdown-toggle"' +
        'data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
        $status_name + '</button>';

    //if ($status_id != '4' && $status_id != '5') {
    $statButton += ' <ul class="dropdown-menu">' +
        '     <li>' +
        '      <h6 class="dropdown-header">Change Room Status</h6>' +
        '     </li><li><hr class="dropdown-divider"></li>';

    $.each($status, function(statText) {
        if (statText == $status_id) $statButton += '';
        else $statButton +=
            '<li><a class="dropdown-item changeRoomStatus" data-room-id="' + rmId + '"' +
            ' data-room-new-stat="' + statText + '"' +
            ' data-room-new-statName="' + $status[statText].title + '"' +
            ' data-room-old-stat="' + $status_id + '"' +
            ' data-room-old-statName="' + $status_name + '"' +
            'href="javascript:void(0);">' + $status[statText].title + '</a></li>';
    });

    $statButton += '  </ul>';
    //}

    return $statButton;
}



// Change Room status
$(document).on('click', '.changeRoomStatus', function() {
    var roomId = $(this).attr('data-room-id');
    var current_status = $(this).attr('data-room-old-stat');
    var current_statusName = $(this).attr('data-room-old-statName');
    var new_status = $(this).attr('data-room-new-stat');
    var new_statusName = $(this).attr('data-room-new-statName');

    var clickedCol = $(this).closest('td');

    bootbox.confirm({
        message: "Are you sure you want to change the housekeeping status from '" + current_statusName +
            "' to '" + new_statusName + "' for this room?",
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
                    url: '<?php echo base_url('/updateRoomStatus') ?>',
                    type: "post",
                    data: {
                        roomId: roomId,
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
                                `<li>The Room Status has been updated successfully.</li>`
                            );

                            clickedCol.html(showRoomStatChange(new_status,
                                new_statusName, roomId));
                            //$('#room_list').dataTable().fnDraw();
                        }
                    }
                });
            }
        }
    });

});

function addForm() {
    $(':input', '#submitForm').not('[type="radio"]').val('').prop('checked', false).prop('selected', false);
    $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    $('#popModalWindow').modal('show');
    $('#RM_TYPE,#RM_HOUSKP_DY_SECTION,#RM_HOUSKP_EV_SECTION').html('<option value="">Select</option>').selectpicker(
        'refresh');
    $('#RM_FEATURE').val('').trigger('change');
    runInitialLevel();
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
                    url: '<?php echo base_url('/deleteRoom')?>',
                    type: "post",
                    data: {
                        sysid: sysid
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    dataType: 'json',
                    success: function(respn) {

                        $('#room_list').dataTable().fnDraw();
                    }
                });
            }
        }
    });
});

function runInitialLevel() {
    $.ajax({
        url: '<?php echo base_url('/getSupportingRoomLov')?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        async: false,
        success: function(respn) {
            var memData = respn[0];
            var idArray = ['RM_PUBLIC_RATE_CODE', 'RM_FLOOR_PREFERN', 'RM_SMOKING_PREFERN', 'RM_FEATURE'];
            $(respn).each(function(ind, data) {
                var option = '<option value="">Select</option>';
                $.each(data, function(i, valu) {
                    var value = $.trim(valu['CODE']); //fields.trim();
                    var desc = $.trim(valu['DESCS']); //datavals.trim();
                    option += '<option value=' + value + '>' + desc + '</option>';
                });
                $('#' + idArray[ind]).html(option);
            });
        }
    });
}

$(document).on('keyup', '.RM_TYPE .form-control', function() {
    var search = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/roomTypeList')?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            search: search
        },
        // dataType:'json',
        success: function(respn) {

            $('#RM_TYPE').html(respn).selectpicker('refresh');
        }
    });
});

$(document).on('change', '#RM_TYPE', function() {
    var value = $(this).find('option:selected').attr('data-rmclass');
    var room_type_id = $(this).find('option:selected').data('room-type-id');
    var desc = $(this).find('option:selected').attr('data-desc');

    $('#RM_DESC').val(desc);
    $('#RM_TYPE_REF_ID').val(room_type_id);
    $('#RM_CLASS').val(value);
});

// $(document).on('keyup','.RM_FEATURE .form-control',function(){
//   var search = $(this).val();
//   $.ajax({
//       url: '<?php echo base_url('/featureList')?>',
//       type: "post",
//       headers: {'X-Requested-With': 'XMLHttpRequest'},
//       data:{search:search},
//       // dataType:'json',
//       success:function(respn){
//         
//         $('#RM_FEATURE').html(respn).selectpicker('refresh');
//       }
//   });
// });

$(document).on('keyup', '.RM_HOUSKP_DY_SECTION .form-control,.RM_HOUSKP_EV_SECTION .form-control', function() {
    var search = $(this).val();
    var fieldName = $(this).parents('.bootstrap-select')[0].classList[2];
    $.ajax({
        url: '<?php echo base_url('/houseKeepSecionList')?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            search: search
        },
        // dataType:'json',
        success: function(respn) {

            $('#' + fieldName).html(respn).selectpicker('refresh');
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

$(document).on('click', '.editWindow', function() {
    var thiss = $(this);
    $.when(runInitialLevel()).done(function() {
        var sysid = thiss.attr('data_sysid');
        $('#popModalWindow').modal('show');
        var url = '<?php echo base_url('/editRoom')?>';
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
                        if (field == 'RM_TYPE') {
                            var option = '<option value="' + dataval +
                                '" data-room-type-id="' + data[field +
                                    '_REF_ID'] + '">' + data[field +
                                    '_DESC'] + '</option>';
                            $('#' + field).html(option).selectpicker(
                                'refresh');
                        } else if (field == 'RM_FEATURE') {
                            var feture = dataval.split(',');
                            $('#' + field).val(feture).trigger('change');
                        } else {
                            $('#' + field).val(dataval).trigger('change');
                        }
                    });
                });
                $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text(
                    'Update');
            }
        });
    });
});

function submitForm(id) {
    $('#errorModal').hide();
    var formSerialization = $('#' + id).serializeArray();
    var url = '<?php echo base_url('/insertRoom')?>';
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
                $('#popModalWindow').modal('hide');
                $('#room_list').dataTable().fnDraw();
            }
        }
    });
}
</script>

<?=$this->endSection()?>