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
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Housekeeping /</span> Room History
        </h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid p-3">

                <form class="dt_adv_search mb-2" method="POST">

                    <!-- Advanced Search Fields -->

                    <div class="row g-3">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="row border rounded p-3 pb-1 m-1">

                                <div class="col-12 col-sm-6 col-lg-4">

                                    <div class="row mb-3">

                                        <label
                                            class="col-form-label col-md-5 col-lg-4 d-flex justify-content-lg-end justify-content-sm-start"><b>Room
                                                Type:</b></label>
                                        <div class="col-md-7 col-lg-8">
                                            <div class="sk-wave sk-primary">
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                            </div>
                                            <div class="d-none">
                                                <select name="S_RM_TYPE" id="S_RM_TYPE" data-width="100%"
                                                    class="select2 form-select" data-placeholder="Any Room Type"
                                                    data-allow-clear="true">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-4">

                                    <div class="row mb-3">
                                        <label
                                            class="col-form-label col-md-3 d-flex justify-content-lg-end justify-content-sm-start"><b>Room
                                                No:</b></label>
                                        <div class="col-md-9">
                                            <div class="sk-wave sk-primary">
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                            </div>
                                            <div class="d-none S_RM_ID_div col-12">
                                                <select id="S_RM_ID" name="S_RM_ID" class="selectpicker w-100"
                                                    data-style="btn btn-default" data-icon-base="bx"
                                                    data-tick-icon="bx-check text-primary" data-live-search="true"
                                                    data-placeholder="Enter 3 or more characters"
                                                    data-allow-clear="true">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-4 mb-2">

                                    <div class="row mb-3">
                                        <label
                                            class="col-form-label col-md-5 col-lg-5 d-flex justify-content-lg-end justify-content-sm-start"><b>Departure
                                                From:</b></label>
                                        <div class="col-md-7 col-lg-7">
                                            <div class="sk-wave sk-primary">
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                            </div>
                                            <div class="d-none">
                                                <input type="text" id="S_DEPARTURE_FROM" name="S_DEPARTURE_FROM"
                                                    class="form-control dt-date" placeholder="" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-12">

                                    <div class="mb-3 d-flex justify-content-end">

                                        <button type="button" class="btn btn-primary submitAdvSearch">
                                            <i class='bx bx-search'></i>
                                            Search
                                        </button>&nbsp;&nbsp;
                                        <button type="button" class="btn btn-secondary clearAdvSearch">Clear</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>


                <div class="room_history_list_div table-responsive text-nowrap">
                    <table id="room_history_list" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Room ID</th>
                                <th>Reservation ID</th>
                                <th class="all">Room No</th>
                                <th class="all">Reservation No</th>
                                <th class="all">Guest Name</th>
                                <th>Arrival Date</th>
                                <th>Departure Date</th>
                                <th data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top"
                                    data-bs-html="true" title=""
                                    data-bs-original-title="<span>Adults / Children</span>">Persons</th>
                                <th>Rate Code</th>
                                <th class="all">Revenue</th>
                                <th class="all">Rate</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>

        <!--/ Multilingual -->
    </div>
    <!-- / Content -->

    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->
<script>
$(document).ready(function() {

    $.ajax({
        url: '<?php echo base_url('/roomTypeList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        // dataType:'json',
        success: function(respn) {
            $('#S_RM_TYPE').html(respn);
            $('#S_RM_TYPE').val(null);
        }
    });

    $('.dt-date').datepicker({
        format: 'dd-M-yyyy',
        autoclose: true,
        onSelect: function() {
            $(this).change();
        }
    });

    $('body').tooltip({
        selector: '[data-bs-toggle="tooltip"]'
    });

    <?php
    if($room_id) { ?>

    $.ajax({
        url: '<?php echo base_url('/roomList') ?>',
        async: false,
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            room_ids: <?=$room_id?>
        },
        dataType: 'html'
    }).done(function(respn) {
        var room_opts = respn;
        room_opts = room_opts.replace('<option value="">Select Room</option>', '');
        room_opts = room_opts.replace('<option value="">No Rooms</option>', '');

        $('#S_RM_ID').html(room_opts).selectpicker('refresh');
        $("#S_RM_ID option[data-room-id='<?=$room_id?>']").prop("selected", true);
        $('#S_RM_ID').selectpicker('refresh');
    });

    <?php } ?>

    showRoomHistory();
});

function showRoomHistory() {

    $('#room_history_list').DataTable({
        'processing': true,
        async: false,
        "searching": false,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/roomHistoryView') ?>',
            'type': 'POST',
            'data': function(d) {

                var formSerialization = $('.dt_adv_search').serializeArray();
                $(formSerialization).each(function(i, field) {
                    if (field.name == 'S_RM_TYPE') {
                        d[field.name] = $('#S_RM_TYPE').find(":selected").data(
                            'room-type-id');
                    } else if (field.name == 'S_RM_ID') {
                        d[field.name] = $('#S_RM_ID').find(":selected") ? $('#S_RM_ID')
                            .find(":selected").data('room-id') : 0;
                    } else if (field.name == 'S_RM_CLASS')
                        d[field.name] = $('#S_RM_CLASS').find(":selected").data(
                            'rmclass-id');
                    else
                        d[field.name] = field.value;
                });
            },
        },
        'columns': [{
                data: ''
            },
            {
                data: 'RM_ID',
                "visible": false,
            },
            {
                data: 'RESV_ID',
                "visible": false,
            },
            {
                data: 'RM_NO',
                className: "text-center"
            },
            {
                data: 'RESV_NO',
                className: "text-center",
                render: function(data, type, row, meta) {
                    return (
                        '<a href="<?php echo base_url('/reservation') ?>?RESV_ID=' + row[
                            'RESV_ID'] +
                        '" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title="" data-bs-original-title="<span>View/Edit Reservation</span>" target="_blank" class="text-truncate"><span class="fw-semibold">' +
                        data + '</span></a>'
                    );
                }
            },
            {
                data: 'CUST_FULL_NAME',
                render: function(data, type, row, meta) {
                    return (
                        '<a href="<?php echo base_url('/customer') ?>?editId=' + row[
                            'RESV_NAME'] +
                        '" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title="" data-bs-original-title="<span>View/Edit Profile</span>" target="_blank" class="text-truncate"><span class="fw-semibold">' +
                        data + '</span></a>'
                    );
                }
            },
            {
                data: 'RESV_ARRIVAL_DT',
                className: "text-center"
            },
            {
                data: 'RESV_DEPARTURE',
                className: "text-center"
            },
            {
                data: 'RESV_PERSONS',
                className: "text-center",
                render: function(data, type, row, meta) {
                    return (
                        '<span data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title="" data-bs-original-title="<span>' +
                        row['RESV_ADULTS'] + ' Adults / ' + row['RESV_CHILDREN'] +
                        ' Children</span>" target="_blank" class="text-truncate"><span class="fw-semibold">' +
                        data + '</span></span>'
                    );
                }
            },
            {
                data: 'RESV_RATE_CODE'
            },
            {
                data: 'TOTAL_REVENUE',
                render: DataTable.render.number( ',', '.', 2, '' ),
                className: "text-end",
            },
            {
                data: 'RESV_RATE',
                render: DataTable.render.number( ',', '.', 2, '' ),
                className: "text-end",
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
            width: "0%"
        }, {
            width: "0%"
        }, {
            width: "8%",
            responsivePriority: 2
        }, {
            width: "8%",
            responsivePriority: 3
        }, {
            width: "15%",
            responsivePriority: 4
        }, {
            width: "10%",
            responsivePriority: 5
        }, {
            width: "10%",
            responsivePriority: 6
        }, {
            width: "5%",
            responsivePriority: 10
        }, {
            width: "10%",
            responsivePriority: 9
        }, {
            width: "10%",
            targets: 10,
            responsivePriority: 7
        }, {
            width: "10%",
            responsivePriority: 8
        }],
        autowidth: true,
        "order": [
            [2, "asc"]
        ],
        destroy: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        language: {
            emptyTable: $('#S_RM_ID').val() != '' ? 'There is no history for this room' :
                'Please select a room to view its history'
        },
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal({
                    header: function(row) {
                        var data = row.data();
                        return 'History Details of Room ' + data['RM_NO'] + '';
                    }
                }),
                type: 'column',
                renderer: function(api, rowIdx, columns) {
                    var room_id = resv_id = fo_status = '';
                    var data = $.map(columns, function(col, i) {

                        var dataVal = col.data;

                        return col.title !==
                            '' // ? Do not show row in modal popup if title is blank (for check box)
                            ?
                            '<tr data-dt-row="' +
                            col.rowIndex +
                            '" data-dt-column="' +
                            col.columnIndex +
                            '">' +
                            '<td width="35%"><b>' +
                            col.title +
                            ':' +
                            '</b></td> ' +
                            '<td>' +
                            dataVal +
                            '</td>' +
                            '</tr>' :
                            '';
                    }).join('');

                    return data ? $('<table class="table"/><tbody />').append(data) :
                        false;
                }
            }
        }

    });
}

$(window).on('load', function() {
    // executes when complete page is fully loaded, including all frames, objects and images
    $('.sk-wave').hide().next().removeClass('d-none');
});


//Select room type to load room options
$(document).on('change.select2', '#S_RM_TYPE', function() {

    var room_types = '';
    var room_opts = '';

    var selectedRoomType = $(this).find(":selected");

    if (selectedRoomType.length > 0) {
        var room_type = selectedRoomType.data('room-type-id');

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
            dataType: 'html'
        }).done(function(respn) {
            var room_opts = respn;
            room_opts = room_opts.replace('<option value="">Select Room</option>', '');
            room_opts = room_opts.replace('<option value="">No Rooms</option>', '');

            $('#S_RM_ID').html(room_opts).selectpicker('refresh');
        });
    } else
        $('#S_RM_ID').html(room_opts).selectpicker('refresh');
});


// Search room nos in dropdown
$(document).on('keyup', '.S_RM_ID_div .form-control', function() {
    var search = $(this).val();
    if (search.length >= 3) {

        var room_type = $('#S_RM_TYPE').find(":selected").data('room-type-id');

        $.ajax({
            url: '<?php echo base_url('/roomList') ?>',
            async: false,
            type: "post",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                search: search,
                room_type: room_type
            },
            dataType: 'html'
        }).done(function(respn) {
            var room_opts = respn;
            room_opts = room_opts.replace('<option value="">Select Room</option>', '');
            room_opts = room_opts.replace('<option value="">No Rooms</option>', '');

            $('#S_RM_ID').html(room_opts).selectpicker('refresh');
        });
    }
});

(function() {

    // Room History List Advanced Search Functions Starts
    // --------------------------------------------------------------------
    const dt_adv_filter_table = $('#room_history_list');

    $(document).on('click', '.submitAdvSearch', function() { // Click Search button

        blockLoader('.room_history_list_div');

        showRoomHistory();
    });

    $(document).on('click', '.clearAdvSearch', function() { // Click Clear button

        clearFormFields('.dt_adv_search');

        blockLoader('.dt_adv_search');
        blockLoader('.room_history_list_div');

        $('#S_RM_ID').html("").selectpicker('refresh');

        showRoomHistory();
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