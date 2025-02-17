<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>
<?= $this->include('Layout/image_modal') ?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Shuttle </span> List</h4>

        <!-- DataTable with Buttons -->
        <div class="card">

            <div class="container-fluid p-3">
                <table id="dataTable_view" class="table table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Image</th>
                            <th>Shuttle Name</th>
                            <th>Route</th>
                            <th>Start time</th>
                            <th>End Time</th>
                            <th>Next Shuttle at</th>
                            <th>Description</th>
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

    <div class="modal fade" id="reservationChild" tabindex="-1" aria-labelledby="reservationChildLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reservationChildLabel">Create Shuttle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="maintenanceForm" enctype="multipart/form-data" novalidate>
                        <div class="window-1" id="window1">
                            <div class="row g-3">

                                <div class="col-md-4 mt-0">
                                    <label class="form-label">Shuttle Name</label>
                                    <input type="text" name="SHUTL_NAME" id="SHUTL_NAME" class="form-control" autocomplete="off">
                                </div>
                                <div class="col-md-4 mt-0">
                                    <label class="form-label">Shuttle From</label>
                                    <select id="SHUTL_FROM" name="SHUTL_FROM" class=" select2 form-select stages" data-allow-clear="true">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mt-0">
                                    <label class="form-label">Shuttle To</label>
                                    <select id="SHUTL_TO" name="SHUTL_TO" class=" select2 form-select stages" data-allow-clear="true">
                                        <option value="">Select</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Start Time</label>
                                    <input type="time" autocomplete="off" name="SHUTL_START_AT" id="SHUTL_START_AT" class="form-control" placeholder="08:10">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">End Time</label>
                                    <input type="time" autocomplete="off" name="SHUTL_END_AT" id="SHUTL_END_AT" class="form-control" placeholder="08:10" />
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Next Shuttle at</label>
                                    <input type="text" name="SHUTL_NEXT" id="SHUTL_NEXT" class="form-control" autocomplete="off">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Description</label>
                                    <textarea rows="4" class="form-control" name="SHUTL_DESCRIPTION" id="SHUTL_DESCRIPTION"></textarea>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Image</label>
                                    <input type="file" name="SHUTL_ROUTE_IMG" id="SHUTL_ROUTE_IMG" class="form-control" />
                                </div>

                                <input type="hidden" name="sysid" id="sysid" class="form-control" />
                                <div class="modal-footer profileCreate">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" onClick="submitForm('maintenanceForm','C',event)" class="btn btn-primary">Save</button>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- /Modal window -->

    <!-- Add Stops Modal -->
    <div class="modal fade" id="add-stop-modal" tabindex="-1" aria-labelledby="add-stop-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add-stop-modal-label">Add Stop</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="window-1" id="window1">

                        <form id="add-stop-form" enctype="multipart/form-data" novalidate>
                            <input type="hidden" name="FSR_SHUTTLE_ID" class="form-control" />

                            <div class="row g-3">

                                <div class="col-md-4">
                                    <label class="form-label">Stops</label>
                                    <select name="FSR_STAGE_ID" class="select2 form-select stages" data-allow-clear="true">
                                        <option value="">Select Stop</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Stop Duration <b>(mins)</b></label>
                                    <input type="number" name="FSR_DURATION_MINS" class="form-control" autocomplete="off">
                                </div>

                                <div class="col-md-4">
                                    <br>
                                    <button type="button" onClick="submitAddStopForm()" class="btn btn-primary">Add Stop</button>
                                </div>
                            </div>
                        </form>

                        <form id="order-form">
                            <div class="row g-3 mt-3">

                                <h5 class="modal-title">All Stops</h5>

                                <div class="col-md-4">
                                    <ul class="list-group list-group-flush" id="handle-list">
                                    </ul>
                                </div>

                                <div class="modal-footer profileCreate">
                                    <button type="button" onClick="submitOrderForm()" class="btn btn-primary update-btn">Update</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->

<?= $this->endSection() ?>

<?= $this->section("script") ?>
<script>

    $(document).ready(function() {
        Sortable.create($("#handle-list")[0], {
            animation: 150,
            group: 'handleList',
            handle: '.drag-handle'
        });

        $('#dataTable_view').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url': '<?php echo base_url('/shuttlelist') ?>'
            },
            'columns': [{
                    data: ''
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return (
                            `
                                <img onClick='displayImagePopup("${data['SHUTL_ROUTE_IMG']}")' src='${data['SHUTL_ROUTE_IMG']}' width='80' height='80'/>
                            `
                        );
                    }
                },
                {
                    data: 'SHUTL_NAME'
                },
                {
                    data: 'SHUTL_ROUTE'
                },
                {
                    data: 'SHUTL_START_AT'
                },
                {
                    data: 'SHUTL_END_AT'
                },
                {
                    data: 'SHUTL_NEXT'
                },
                {
                    data: 'SHUTL_DESCRIPTION'
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

                            '<li><a href="javascript:;" data_id="' + data['SHUTL_ID'] + '" class="dropdown-item add-stops-Window">Stops</a></li>' +

                            '<div class="dropdown-divider"></div>' +

                            '<li><a href="javascript:;" data_sysid="' + data['SHUTL_ID'] + '" class="dropdown-item editWindow">Edit</a></li>' +

                            '<div class="dropdown-divider"></div>' +

                            '<li><a href="javascript:;" data_sysid="' + data['SHUTL_ID'] + '" class="dropdown-item text-danger delete-record">Delete</a></li>' +
                            '</ul>' +
                            '</div>'
                        );
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
                [3, "asc"]
            ],
            destroy: true,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function(row) {
                            var data = row.data();
                            return 'Details of request# ' + data['SHUTL_NAME'];
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

        $("#dataTable_view_wrapper .row:first").before('<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" onClick="addForm()"><i class="fa-solid fa-plus fa-lg"></i>Add</button></div></div>');
        $('#MAINT_PREFERRED_DT').datepicker({
            format: 'd-M-yyyy',
            autoclose: true
        });

    });

    function resetForm() {
        let id = "maintenanceForm";
        
        $(`#${id} input`).val('');
        $(`#${id} textarea`).val('');
    }

    function addForm() {
        resetForm();

        $(':input', '#reservationChild').val('').prop('checked', false).prop('selected', false);
        $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
        runStageList();
        $('#reservationChild').modal('show');
    }

    $(document).on('click', '.delete-record', function() {
        var sysid = $(this).attr('data_sysid');
        bootbox.confirm({
            message: "Are you sure you want to delete this request?",
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
                        url: '<?php echo base_url('/deleteShuttle') ?>',
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

    function runStageList() {
        $.ajax({
            url: '<?php echo base_url('/getStages') ?>',
            type: "post",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            dataType: 'json',
            async: false,
            success: function(respn) {

                var option = '<option value="">Select Stages</option>';
                $(respn).each(function(ind, data) {
                    option += '<option value="' + data['SHUTL_STAGE_ID'] + '">' + data['SHUTL_STAGE_NAME'] + '</option>';
                });
                $('.stages').html(option);

            }
        });
    }

    function submitForm(id, mode) {

        var form = $('#' + id)[0];
        var formData = new FormData(form);
        formData.delete('SHUTL_ROUTE_IMG');

        files = $(`#${id} input[name='SHUTL_ROUTE_IMG']`)[0].files;
        if (files.length)
            formData.append('SHUTL_ROUTE_IMG', files[0]);

        var url = '<?php echo base_url('/insertShuttle') ?>';

        $.ajax({
            url: url,
            type: "post",
            data: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(response) {

                if (response.SUCCESS != 200) {
                    var mcontent = '';
                    $.each(response['RESPONSE']['REPORT_RES'], function(ind, data) {
                        mcontent += '<li>' + data + '</li>';
                    });

                    showModalAlert('error', mcontent);
                } else {
                    showModalAlert('success', 'Request Added successfully');

                    $('#reservationChild').modal('hide');
                    $('#dataTable_view').dataTable().fnDraw();
                }
            }
        });
    }

    function getShuttleStops(shuttle_id) {
        $.ajax({
            url: '<?= base_url('get-shuttle-stops') ?>',
            type: 'post',
            data: {
                shuttle_id
            },
            dataType: 'json',
            success: function(response) {
                if (response.SUCCESS == 200) {
                    let stops_list = response.RESPONSE.OUTPUT;
                    
                    let stop_list_html = '';
                    $.each(stops_list, function(index, stop) {
                        stop_list_html += `
                                    <li class="list-group-item lh-1 d-flex justify-content-between align-items-center">
                                        <input type='hidden' name='shuttle_route_ids[]' value='${stop.FSR_ID}'/>
                                        
                                        <span class="d-flex justify-content-between align-items-center">
                                            <i class="drag-handle cursor-move bx bx-menu align-text-bottom me-2"></i>
                                            <span>${stop.SHUTL_STAGE_NAME}</span>
                                        </span>

                                        <span>
                                            ${stop.FSR_DURATION_MINS} Mins
                                        </span>
                                        <i onClick="removeShuttleStop(${stop.FSR_ID})" class="text-danger cursor-pointer fa-solid fa-xmark"></i>
                                    </li>
                        `;
                    });

                    if(!stop_list_html)
                        stop_list_html = "No Stop has been added yet!";

                    $("#handle-list").html(stop_list_html);
                    
                    if(stops_list.length < 2)
                        $('#order-form .update-btn').hide();
                    else
                        $('#order-form .update-btn').show();
                }
            }
        })
    }

    $(document).on('click', '.add-stops-Window', function() {
        runStageList();

        let shuttle_id = $(this).attr('data_id');
        getShuttleStops(shuttle_id);

        $('#add-stop-modal input[name="FSR_SHUTTLE_ID"]').val(shuttle_id);

        $('#add-stop-modal').modal('show');
    });

    function resetAddStopForm() {
        $("#add-stop-form .select2").val(null).trigger('change');
        $("#add-stop-form input[name='FSR_DURATION_MINS']").val('');
    }

    function submitAddStopForm() {
        let fd = new FormData($("#add-stop-form")[0]);

        $.ajax({
            url: '<?= base_url('store-route-stop') ?>',
            type: 'POST',
            processData: false,
            contentType: false,
            data: fd,
            dataType: 'json',
            success: function(response) {
                let status = response.SUCCESS;

                if (status != '200') {
                    var errors = response['RESPONSE']['ERROR'];

                    var mcontent = '';
                    $.each(errors, function(index, error) {
                        mcontent += '<li>' + error + '</li>';
                    });

                    showModalAlert('error', mcontent);
                    return;
                }

                showModalAlert('success', response.RESPONSE.REPORT_RES);
                getShuttleStops($("#add-stop-form input[name='FSR_SHUTTLE_ID']").val());
                resetAddStopForm();
            }
        });
    }

    function removeShuttleStop(shuttle_route_id) {
        $.ajax({
            url: '<?= base_url('remove-shuttle-stop') ?>',
            type: 'POST',
            data: {
                shuttle_route_id
            },
            dataType: 'json',
            success: function(response) {
                let status = response.SUCCESS;

                if (status != '200') {
                    showModalAlert('success', response.RESPONSE.REPORT_RES);
                    return;
                }

                showModalAlert('success', response.RESPONSE.REPORT_RES);
                getShuttleStops($("#add-stop-form input[name='FSR_SHUTTLE_ID']").val());
            }
        });
    }

    function submitOrderForm() {
        let fd = new FormData($("#order-form")[0]);

        $.ajax({
            url: '<?= base_url('update-shuttle-stops-order') ?>',
            type: 'POST',
            processData: false,
            contentType: false,
            data: fd,
            dataType: 'json',
            success: function(response) {
                let status = response.SUCCESS;

                if (status != '200') {
                    showModalAlert('success', response.RESPONSE.REPORT_RES);
                    return;
                }

                showModalAlert('success', response.RESPONSE.REPORT_RES);
            }
        });
    }

    $(document).on('click', '.editWindow', function() {
        resetForm();
        runStageList();
        setTimeout(() => {
            var sysid = $(this).attr('data_sysid');
            $('#sysid').val(sysid);
            $('#reservationChild').modal('show');
            $.ajax({
                url: '<?php echo base_url('/editShuttle') ?>',
                type: "post",
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                data: {
                    sysid: sysid
                },
                // async:false,
                dataType: 'json',
                success: function(respn) {
                    ;
                    $(respn).each(function(inx, data) {
                        var data = respn[0];

                        var SHUTL_FROM = $.trim(data['SHUTL_FROM']);
                        var SHUTL_TO = $.trim(data['SHUTL_TO']);

                        $('#SHUTL_FROM').val(SHUTL_FROM).trigger('change');
                        $('#SHUTL_TO').val(SHUTL_TO).trigger('change');
                        $('#SHUTL_NAME').val(data['SHUTL_NAME']);

                        data['SHUTL_START_AT'] = data['SHUTL_START_AT'].split(' ')[1].split(".")[0];
                        $('#SHUTL_START_AT').val(data['SHUTL_START_AT']);

                        data['SHUTL_END_AT'] = data['SHUTL_END_AT'].split(' ')[1].split(".")[0];
                        $('#SHUTL_END_AT').val(data['SHUTL_END_AT']);

                        $('#SHUTL_NEXT').val(data['SHUTL_NEXT']);
                        $('#SHUTL_ROUTE').val(data['SHUTL_ROUTE']);
                        $('#SHUTL_DESCRIPTION').val(data['SHUTL_DESCRIPTION']);

                    });
                    $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
                }
            });
        }, 500);

    });
</script>
<script src="<?php //echo base_url('assets/js/bootstrap.bundle.js')
                ?>"></script>
<script src="<?php //echo base_url('assets/js/bootstrap-select.js')
                ?>"></script>
<?= $this->endSection() ?>