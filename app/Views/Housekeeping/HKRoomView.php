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

                <button type="button" class="btn btn-primary mb-2 use_selected_rooms" data-change-selected="0"><i
                        class="fa-solid fa-pen-to-square"></i>&nbsp;&nbsp;Quick Change</button>

                <div class="room_list_div table-responsive text-nowrap">
                    <table id="room_list" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Room ID</th>
                                <th class="all">Room No</th>
                                <th class="all">Room Type</th>
                                <th class="all">Room Status</th>
                                <th>Description</th>
                                <th>Max Occupancy</th>
                                <th>Smoking Preference</th>
                                <th>Square Units</th>
                                <th>Phone Number</th>
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
                                    class="select2 form-select" data-placeholder="All Room Types"
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
var clicked_room_ids = [];

$(document).ready(function() {

    $('#room_list').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/hkroomView') ?>'
        },
        'columns': [{
                data: ''
            },
            {
                data: 'RM_ID',
                "visible": false,
            },
            {
                data: 'RM_NO'
            },
            {
                data: 'RM_TYPE'
            },
            {
                data: 'RM_STATUS_CODE',
                className: "text-center rmStatusCol"
            },
            {
                data: 'RM_DESC',
                "visible": false,
            },
            {
                data: 'RM_MAX_OCCUPANCY',
                "visible": false,
            },
            {
                data: 'RM_SMOKING_PREFERN',
                "visible": false,
            },
            {
                data: 'RM_SQUARE_UNITS',
                "visible": false,
            },
            {
                data: 'RM_PHONE_NO',
                "visible": false,
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
        'createdRow': function(row, data, dataIndex) {
            var check_str = 'room_chk_' + data['RM_ID'];

            $(row).attr('data-room-id', data['RM_ID']);
            $(row).addClass('roomRow' + data['RM_ID']);

            if (jQuery.inArray(check_str, clicked_room_ids) !== -1 && !$(row).hasClass(
                    'table-warning')) {
                $(row).addClass('table-warning');
            } else if (jQuery.inArray(check_str, clicked_room_ids) == -1 && $(row)
                .hasClass(
                    'table-warning')) {
                $(row).removeClass('table-warning');
            }
        },
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
            width: "8%",
            responsivePriority: 2
        }, {
            width: "10%",
            responsivePriority: 3
        }, {
            // Label
            targets: 4,
            responsivePriority: 4,
            width: "10%",
            className: "text-center",
            render: function(data, type, full, meta) {

                var $status_name = full['RM_STATUS_CODE'];
                var $status_id = full['RM_STATUS_ID'];

                var $statButton = showRoomStatChange($status_id, $status_name,
                    full[
                        'RM_ID']);
                return $statButton;
            },
        }, {
            width: "0%"
        }, {
            width: "0%"
        }, {
            width: "0%"
        }, {
            width: "0%"
        }, {
            width: "0%"
        }, {
            width: "8%",
            responsivePriority: 7
        }, {
            width: "12%",
            responsivePriority: 8
        }, {
            width: "5%",
            responsivePriority: 5
        }, {
            width: "10%",
            responsivePriority: 9
        }, {
            width: "20%",
            responsivePriority: 6
        }],
        autowidth: true,
        "order": [
            [2, "asc"]
        ],
        destroy: true,
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
                    var room_id = '';
                    var data = $.map(columns, function(col, i) {

                        if (col.title == 'Room ID')
                            room_id = col.data;

                        var dataVal = col.title == 'Features' ?
                            showFeaturesDesc(col.data) :
                            (col.title == 'Reservation Status' ? col.data +
                                '<a href="<?php echo base_url('/reservation') ?>?ROOM_ID=' +
                                room_id +
                                '&ARRIVAL_DATE=<?= date('Y-m-d') ?>&CREATE_WALKIN=1" target="_blank" class="btn btn-sm btn-primary ms-2">Create Walk-In</a>' :
                                (col.title == 'Room Status' ?
                                    showRoomCurrentStat(room_id) :
                                    col.data));

                        var rowClass = col.title == 'Room Status' ? 'roomRow' +
                            room_id :
                            '';
                        var colClass = col.title == 'Room Status' ?
                            'rmStatusCol' : '';

                        return col.title !==
                            '' // ? Do not show row in modal popup if title is blank (for check box)
                            ?
                            '<tr class="' + rowClass + '" data-dt-row="' +
                            col.rowIndex +
                            '" data-dt-column="' +
                            col.columnIndex +
                            '">' +
                            '<td width="35%"><b>' +
                            col.title +
                            ':' +
                            '</b></td> ' +
                            '<td class="' + colClass + '">' +
                            dataVal +
                            '</td>' +
                            '</tr>' :
                            '';
                    }).join('');

                    return data ? $('<table class="table"/><tbody />').append(data) :
                        false;
                }
            }
        },
        select: {
            style: 'multi',
            info: false
        }

    });
    $("#room_list_wrapper .row:first").before(
        '<div class="row flxi_pad_view"><div class="col-md-3 ps-0"></div></div>'
    );


    $(document).on('click', '#room_list > tbody > tr', function() {

        var room_chk_str = $(this).attr('data-room-id') ? $(this).attr('data-room-id') : '';

        //If value in array
        if (jQuery.inArray(room_chk_str, clicked_room_ids) !==
            -1) {
            if ($(this).hasClass("table-warning")) {
                // Remove value from array
                clicked_room_ids = $.grep(clicked_room_ids, function(value) {
                    return value != room_chk_str;
                });
            }
        } else {
            if (!$(this).hasClass("table-warning") && room_chk_str != '') {
                clicked_room_ids.push(room_chk_str);
            }
        }

        if (clicked_room_ids.length == 0) {
            $('.use_selected_rooms').html(
                '<i class="fa-solid fa-pen-to-square"></i>&nbsp;&nbsp;Quick Change');
            $('.use_selected_rooms').attr('data-change-selected', 0);
        } else {
            $('.use_selected_rooms').html(
                '<i class="fa-solid fa-pen-to-square"></i>&nbsp;&nbsp;Change Selected');
            $('.use_selected_rooms').attr('data-change-selected', 1);
        }

        //alert(clicked_room_ids);

        $(this).toggleClass('table-warning', $(this).hasClass('selected'));
    });

    $.ajax({
        url: '<?php echo base_url('/roomTypeList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        // dataType:'json',
        success: function(respn) {
            $('#RM_TYPES').html(respn);
        }
    });

});

// Return Features Details
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
        <?php foreach ($room_status_list as $room_status) { ?> '<?= $room_status['RM_STATUS_ID'] ?>': {
            class: 'btn-<?= $room_status['RM_STATUS_COLOR_CLASS'] ?>',
            title: '<?= $room_status['RM_STATUS_CODE'] ?>'
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

    if ($status_id != '4' && $status_id != '5') {
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
                ' href="javascript:void(0);">' + $status[statText].title + '</a></li>';
        });

        $statButton += '  </ul>';
    }

    return $statButton;
}

// Return Features Details
function showRoomCurrentStat(rmId) {

    $.ajax({
        url: '<?php echo base_url('/showRoomStatus') ?>',
        type: 'POST',
        async: false,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            roomId: rmId,
        },
        dataType: 'json'
    }).done(function(response) {
        ret_val = response;
    }).fail(function(jqXHR, textStatus, errorThrown) {
        ret_val = null;
    });

    return showRoomStatChange(ret_val.RM_STAT_ROOM_STATUS, ret_val.RM_STATUS_CODE, rmId);
}


// Change Room status
$(document).on('click', '.changeRoomStatus', function() {
    var roomId = $(this).attr('data-room-id');
    var current_status = $(this).attr('data-room-old-stat');
    var current_statusName = $(this).attr('data-room-old-statName');
    var new_status = $(this).attr('data-room-new-stat');
    var new_statusName = $(this).attr('data-room-new-statName');

    var clickedCol = $('.roomRow' + roomId).find('.rmStatusCol');

    if (current_status == new_status) {
        alert('The Room status is already ' + current_statusName);
    } else {
        bootbox.confirm({
            message: "Are you sure you want to change the housekeeping status from '" +
                current_statusName +
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

                                    mcontent += '<li>' + data +
                                        '</li>';
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
    }
});

// Click Change Selected Button
$(document).on('click', '.use_selected_rooms', function() {

    $('#quickChangeRmStat').modal('show');

    var hasSelectedRooms = $(this).attr('data-change-selected');

    $('#RM_TYPES,#selectRoomsByN').prop('disabled', hasSelectedRooms == '1' ? true : false);
    $('#selectRoomsFrom,#selectRoomsTo').prop('disabled', hasSelectedRooms == '1' ? true : false).selectpicker(
        'refresh');
    $('#selectRoomsByL').prop("checked", true).trigger("click");

    if (clicked_room_ids.length > 0) {

        var room_ids = clicked_room_ids.join(',');
        $.ajax({
            url: '<?php echo base_url('/roomList') ?>',
            async: false,
            type: "post",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                room_ids: room_ids
            },
            dataType: 'html'
        }).done(function(respn) {
            var room_opts = respn;
            room_opts = room_opts.replace('<option value="">Select Room</option>', '');
            room_opts = room_opts.replace('<option value="">No Rooms</option>', '');

            $('#selectRoomsList').html(room_opts).selectpicker('refresh');
            $.each(clicked_room_ids, function(i, room_id) {
                $("#selectRoomsList option[data-room-id='" + room_id + "']").prop("selected",
                    true);
            });
            $('#selectRoomsList').selectpicker('refresh');
        });
    }

    // Show 'Clean' status by default
    $('.qcRoomStatusDiv').html(showQuickStatChange(1, 'Clean'));
    $('#statusToChange').val(1);
});


// Reset and clear Quick Change form
function resetQuickChange() {
    blockLoader('#quickChangeRmForm');
    $('#RM_TYPES,#selectRoomsByL,#selectRoomsByN').prop('disabled', false);
    $("#selectRoomsByL").prop("checked", true).trigger("click");

    clearFormFields('#quickChangeRmForm');

    // Show 'Clean' status by default
    $('.qcRoomStatusDiv').html(showQuickStatChange(1, 'Clean'));
    $('#statusToChange').val(1);
}

// Show and Change room status button in popup
function showQuickStatChange(curStatId, curStatName) {

    var $status_name = curStatName;
    var $status_id = curStatId;

    var $status = {
        <?php foreach ($room_status_list as $room_status) { ?> '<?= $room_status['RM_STATUS_ID'] ?>': {
            class: 'btn-<?= $room_status['RM_STATUS_COLOR_CLASS'] ?>',
            title: '<?= $room_status['RM_STATUS_CODE'] ?>'
        },
        <?php } ?>
    };
    if (typeof $status[$status_id] === 'undefined') {
        return $status_name;
    }

    var $statButton = '<button type="button" class="btn ' + $status[$status_id].class +
        ' dropdown-toggle qcRoomStatusBtn"' + ' data-bs-toggle="dropdown" data-new-status="' + $status_name +
        '" aria-haspopup="true" aria-expanded="false">' +
        $status_name + '</button>';

    $statButton += ' <ul class="dropdown-menu">';

    $.each($status, function(statText) {
        if (statText == $status_id || statText == 4 || statText == 5) $statButton += '';
        else $statButton +=
            '<li><a class="dropdown-item qcRoomStatus" data-statId="' + statText + '" data-statName="' +
            $status[statText].title + '" href="javascript:void(0);">' + $status[statText].title +
            '</a></li>';
    });

    $statButton += '  </ul>';

    return $statButton;
}

// Change Room status
$(document).on('click', '.qcRoomStatus', function() {
    var new_status = $(this).attr('data-statId');
    var new_statusName = $(this).attr('data-statName');

    $('.qcRoomStatusDiv').html(showQuickStatChange(new_status, new_statusName));
    $('#statusToChange').val(new_status);
});

$(document).on('click', '.clearQuickChange', function() {
    resetQuickChange();
});

//Select room type to load room options
$(document).on('change.select2', '#RM_TYPES', function() {

    var room_types = '';
    var room_opts = '';

    var selectedRoomTypes = $(this).find(":selected");

    if (selectedRoomTypes.length > 0) {
        selectedRoomTypes.each(function() {
            room_types += $(this).data('room-type-id') + ',';
        });
        room_types = room_types.substring(0, room_types.length - 1);

        $.ajax({
            url: '<?php echo base_url('/roomList') ?>',
            async: false,
            type: "post",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                room_type: room_types
            },
            dataType: 'html'
        }).done(function(respn) {
            var room_opts = respn;
            room_opts = room_opts.replace('<option value="">Select Room</option>', '');
            room_opts = room_opts.replace('<option value="">No Rooms</option>', '');

            $('#selectRoomsList,#selectRoomsFrom,#selectRoomsTo').html(room_opts).selectpicker(
                'refresh');
        });
    } else
        $('#selectRoomsList,#selectRoomsFrom,#selectRoomsTo').html(room_opts).selectpicker('refresh');

});

$(document).on('click', 'input[name=selectRoomsBy]', function() {

    //alert($('input[name=selectRoomsBy]').not($(this)).closest('.col-md-3').siblings('.selectRoomsByCol').find('.selectRooms').val());

    $(this).closest('.col-md-3').siblings('.selectRoomsByCol').find('.selectRooms').prop('disabled', false)
        .selectpicker('refresh');
    $('input[name=selectRoomsBy]').not($(this)).closest('.col-md-3').siblings('.selectRoomsByCol').find(
            '.selectRooms')
        .prop('disabled', true).selectpicker('refresh');

});

// Submit Quick Change Room Status form

quickChangeSubmitBtn = document.querySelector('#quickChangeSubmitBtn');

quickChangeSubmitBtn.onclick = function() {

    var formSerialization = $('#quickChangeRmForm').serializeArray();
    var roomIds = [];

    var selectRoomsBy = '';

    $.each(formSerialization, function(ind, field) {
        if (field.name == 'selectRoomsBy') {
            selectRoomsBy = field.value;
            return false; //break
        }
    });

    if (selectRoomsBy == 'L') {
        var selectedRoomList = $('#selectRoomsList').find('option:selected');

        if ($('#selectRoomsList').val() == '') {
            showModalAlert('error', '<li>You have to select at least one Room from the list</li>');
            return false;
        }

        $.each(selectedRoomList, function(index, item) {
            roomIds.push(item.getAttribute('data-room-id'));
        });
    } else if (selectRoomsBy == 'N') {
        var selectRoomsFrom = $('#selectRoomsFrom').val() ? $('#selectRoomsFrom').find('option:selected').attr(
            'data-room-id') : null;
        var selectRoomsTo = $('#selectRoomsTo').val() ? $('#selectRoomsTo').find('option:selected').attr(
            'data-room-id') : null;

        if ($('#selectRoomsFrom').val() == '') {
            showModalAlert('error', '<li>You have to select the From Room</li>');
            return false;
        } else if ($('#selectRoomsTo').val() == '') {
            showModalAlert('error', '<li>You have to select the To Room</li>');
            return false;
        }

        var flag = 0;

        $.each($('#selectRoomsFrom > option'), function(index, item) {

            var curRoomId = item.getAttribute('data-room-id');

            if (curRoomId != selectRoomsFrom && flag == 0) return true; // continue
            else flag = 1;
            roomIds.push(curRoomId);
            if (curRoomId == selectRoomsTo) return false; // break
        });
    }

    formSerialization.push({
        name: "roomIds",
        value: roomIds
    });

    Swal.fire({
        title: '',
        html: '<h4 class="lh-lg">Are you sure you want to change the status of these rooms to \'' + $(
            '.qcRoomStatusBtn').data('new-status') + '\'?</h4>',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, change them!',
        customClass: {
            confirmButton: 'btn btn-primary me-3',
            cancelButton: 'btn btn-label-secondary'
        },
        buttonsStyling: false
    }).then(function(result) {
        if (result.value) {
            var url = '<?php echo base_url('/bulkUpdateRoomStatus') ?>';
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
                        if (respn['RESPONSE']['OUTPUT'] != '0') {

                            var alertText = '<li>' + respn['RESPONSE']['OUTPUT'] +
                                ' Rooms have been updated.</li>';
                            showModalAlert('success', alertText);
                        } else
                            showModalAlert('warning',
                                '<li>No Room statuses have been updated. Please try again</li>'
                            );

                        $('#quickChangeRmStat').modal('hide');
                        clicked_room_ids = [];
                        $('.use_selected_rooms').html(
                            '<i class="fa-solid fa-pen-to-square"></i>&nbsp;&nbsp;Quick Change'
                        );
                        $('.use_selected_rooms').attr('data-change-selected', 0);

                        $('#room_list').dataTable().fnDraw();
                    }

                }
            });
        }
    });
};


// Close Quick Change Popup Form
$(document).on('hide.bs.modal', '#quickChangeRmStat', function() {
    resetQuickChange();
});

// Display function toggleButton
<?php echo isset($toggleButton_javascript) ? $toggleButton_javascript : ''; ?>

// Display function clearFormFields
<?php echo isset($clearFormFields_javascript) ? $clearFormFields_javascript : ''; ?>

// Display function blockLoader
<?php echo isset($blockLoader_javascript) ? $blockLoader_javascript : ''; ?>
</script>

<?= $this->endSection() ?>