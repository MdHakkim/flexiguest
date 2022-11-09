<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>
<?= $this->include('Layout/image_modal') ?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Maintenance Request</span> List</h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid p-3">
                <table id="dataTable_view" class="table table-striped">
                    <thead>
                        <tr>
                            <th class="all"></th>
                            <th>ID</th>
                            <th>Apartment</th>
                            <th>Guest Name</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>SubCategory</th>
                            <th>Prefered Date & Time</th>
                            <th>Status</th>
                            <th>Image</th>
                            <th>Reported Time</th>
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

    <div class="modal fade" id="reservationChild" tabindex="-1" aria-labelledby="reservationChildLabel" aria-hidden="true" style="z-index: 1100;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reservationChildLabel">Maintenance Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="maintenanceForm" enctype="multipart/form-data" novalidate>
                        <div class="window-1" id="window1">
                            <div class="row g-3">

                                <div class="col-md-4 mt-0">
                                    <label class="form-label">Apartments</label>
                                    <select id="MAINT_ROOM_NO" class=" select2 form-select" name="MAINT_ROOM_NO" data-allow-clear="true">
                                        <option value="">Select</option>
                                    </select>
                                </div>


                                <div class="col-md-4 mt-0">
                                    <label class="form-label">InHouseBooking</label>
                                    <select name="CUST_NAME" id="InHouseBooking" class=" select2 form-select" data-allow-clear="true">
                                        <option value="">Select</option>
                                    </select>
                                </div>

                                <div class="col-md-4 flxi_ds_flx">
                                    <label class="form-label">Type</label>
                                    <div class="form-radio mt-4 me-1">
                                        <input class="form-radio-input flxCheckBox" type="radio" value="bulb_key" name="MAINT_TYPE" id="MAINT_TYPE1">
                                        <label class="form-radio-lable" for="MAINT_TYPE1"> Bulb/Key </label>
                                    </div>
                                    <div class="form-radio mt-4 me-1">
                                        <input class="form-radio-input flxCheckBox" type="radio" value="maintenance" name="MAINT_TYPE" id="MAINT_TYPE2" checked>
                                        <label class="form-radio-lable" for="MAINT_TYPE2"> Maintenance Request </label>
                                    </div>
                                </div>

                                <div class="col-md-4 mt-0">
                                    <label class="form-label">Category</label>
                                    <select id="MAINT_CATEGORY" name="MAINT_CATEGORY" class=" select2 form-select" data-allow-clear="true">
                                        <option value="">Select</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mt-0">
                                    <label class="form-label">Sub Category</label>
                                    <select id="MAINT_SUB_CATEGORY" name="MAINT_SUB_CATEGORY" class=" select2 form-select" data-allow-clear="true">
                                        <option value="">Select</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mt-0">
                                    <label class="form-label">Description</label>
                                    <textarea rows="4" class="form-control" name="MAINT_DETAILS" id="MAINT_DETAILS"></textarea>
                                </div>

                                <div class="col-md-4">
                                    <!-- <input type="hidden" name="RESV_STATUS" id="RESV_STATUS" class="form-control"/> -->
                                    <label class="form-label">Prefered Date </label>
                                    <div class="input-group mb-3">
                                        <input type="text" autocomplete="off" name="MAINT_PREFERRED_DT" id="MAINT_PREFERRED_DT" class="form-control MAINT_PREFERRED_DT" placeholder="DD-MM-YYYY">
                                        <span class="input-group-append">
                                            <span class="input-group-text bg-light d-block">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                        </span>

                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Prefered Time</label>
                                    <input type="time" name="MAINT_PREFERRED_TIME" id="MAINT_PREFERRED_TIME" class="form-control" placeholder="Preferred Time" />
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Image</label>
                                    <input type="file" name="MAINT_ATTACHMENT[]" id="MAINT_ATTACHMENT" class="form-control" multiple />
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label"><b>Department</b></label>
                                    <select name="MAINT_DEPARTMENT_ID" class="select2 form-select">
                                        <?php foreach ($departments as $department) { ?>
                                            <option value="<?= $department['DEPT_ID'] ?>"><?= $department['DEPT_DESC'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label"><b>Attendee</b></label>
                                    <select name="MAINT_ATTENDANT_ID" class="select2 form-select">
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Status</label>
                                    <select name="MAINT_STATUS" class="form-select" data-allow-clear="true">
                                        <option value="">Select Status</option>
                                        <option>New</option>
                                        <option>Assigned</option>
                                        <option>In Progress</option>
                                        <option>Completed</option>
                                        <option>Acknowledged</option>
                                        <option>Rejected</option>
                                    </select>
                                </div>

                                <div class="col-md-4 maint-comment-div">
                                    <label class="form-label">Comment</label>
                                    <textarea class="form-control" name="MAINT_COMMENT"></textarea>
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

    <div class="modal fade" id="comment-modal" tabindex="-1" aria-hidden="true" style="z-index: 1100;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Comments</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- <b>Admin (2022-05-18 12:00 AM)</b></br>
                    <span class="">Comments</span></br> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->
<script>
    var form_id = "#maintenanceForm";
    var reservation_id = 0;

    $(document).ready(function() {
        $('#dataTable_view').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url': '<?php echo base_url('/getRequestList') ?>'
            },
            'columns': [{
                    data: ''
                },
                {
                    data: 'MAINT_ID',
                },
                {
                    data: 'MAINT_ROOM_NO'
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        let name = '';
                        if (data['CUST_FIRST_NAME'])
                            name += data['CUST_FIRST_NAME'] + ' ';

                        if (data['CUST_MIDDLE_NAME'])
                            name += data['CUST_MIDDLE_NAME'] + ' ';

                        if (data['CUST_LAST_NAME'])
                            name += data['CUST_LAST_NAME'];

                        return name;
                    }
                },
                {
                    data: 'MAINT_TYPE',
                    className: 'none'
                },
                {
                    data: 'MAINT_CATEGORY'
                },
                {
                    data: 'MAINT_SUBCATEGORY'
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return `${data['MAINT_PREFERRED_DT']} ${data['MAINT_PREFERRED_TIME']}`;
                    }
                },
                {
                    data: 'MAINT_STATUS'
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        let html = '';
                        if (data['MAINT_ATTACHMENT']) {
                            $.each(data['MAINT_ATTACHMENT'].split(','), function(key, file) {
                                html += `
                                <img onclick='displayImagePopup("assets/Uploads/Maintenance/${file}")' src="assets/Uploads/Maintenance/${file}" width='80' height='80'/>
                            `;
                            });
                        }

                        return html;
                    }
                },
                {
                    data: 'MAINT_CREATE_DT',
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
                            '<li><a href="javascript:;" data-maintenance_request_id="' + data['MAINT_ID'] + '" class="dropdown-item get-comments">Comments</a></li>' +
                            '<div class="dropdown-divider"></div>' +
                            '<li><a href="javascript:;" data_sysid="' + data['MAINT_ID'] + '" class="dropdown-item editWindow">Edit</a></li>' +
                            '<div class="dropdown-divider"></div>' +
                            '<li><a href="javascript:;" data_sysid="' + data['MAINT_ID'] + '" class="dropdown-item text-danger delete-record">Delete</a></li>' +
                            '</ul>' +
                            '</div>'
                        );
                    }
                },
            ],
            columnDefs: [{
                width: "5%",
                className: 'all control',
                responsivePriority: 1,
                orderable: false,
                targets: 0,
                searchable: false,
                render: function(data, type, full, meta) {
                    return '';
                }
            }, {
                targets: 11,
                responsivePriority: 1,
            }],
            autowidth: true,
            "order": [
                [1, "desc"]
            ],
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
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
            }
        });

        $("#dataTable_view_wrapper .row:first").before('<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" onClick="addForm()"><i class="fa-solid fa-plus fa-lg"></i>Add</button></div></div>');

        $('#MAINT_PREFERRED_DT').datepicker({
            format: 'd-M-yyyy',
            autoclose: true
        });
    });

    function resetForm() {
        $(`${form_id} input[type!="radio"]`).val('');
        $(`${form_id} select`).val('').trigger('change');
        $(`${form_id} textarea`).val('');
        $(`${form_id} .maint-comment-div`).hide();
    }

    function addForm() {
        resetForm();
        $(':input', '#customerForm').val('').prop('checked', false).prop('selected', false);
        $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');

        runRoomList();
        runCatList();
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
                        url: '<?php echo base_url('/deleteRequest') ?>',
                        type: "post",
                        data: {
                            sysid: sysid
                        },
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        dataType: 'json',
                        success: function(respn) {
                            if (respn['SUCCESS'] == 200) {
                                showModalAlert('success', `<li>${respn['RESPONSE']['REPORT_RES']['msg']}</li>`);

                                $('#dataTable_view').dataTable().fnDraw();
                            }
                        }
                    });
                }
            }
        });

    });

    $('input[name="MAINT_TYPE"]').change(runCatList);

    function runCatList() {
        let category_type = $('input[name="MAINT_TYPE"]:checked').val();


        $.ajax({
            url: '<?php echo base_url('/getCategory') ?>',
            type: "post",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                category_type
            },
            dataType: 'json',
            async: false,
            success: function(respn) {

                var option = '<option value="">Select Category</option>';
                $(respn).each(function(ind, data) {
                    option += '<option value="' + data['MAINT_CAT_ID'] + '">' + data['MAINT_CATEGORY'] + '</option>';
                });
                $('#MAINT_CATEGORY').html(option);

            }
        });
    }



    function submitForm(id, mode) {
        // var formSerialization = $('#'+id).serializeArray();
        var form = $('#' + id)[0];
        var formData = new FormData(form);
        formData.append('MAINT_RESV_ID', reservation_id);

        formData.delete('MAINT_ATTACHMENT[]');
        files = $(`#${id} input[name='MAINT_ATTACHMENT[]']`)[0].files;
        for (let i = 0; i < files.length; i++)
            formData.append('MAINT_ATTACHMENT[]', files[i]);

        var url = '<?php echo base_url('/insertMaintenanceRequest') ?>';
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
                var msgs = response['RESPONSE']['REPORT_RES'];
                var mcontent = '';
                $.each(msgs, function(ind, data) {

                    mcontent += '<li>' + data + '</li>';
                });

                if (response['SUCCESS'] != 200) {
                    showModalAlert('error', mcontent);
                } else {
                    showModalAlert('success', mcontent);

                    $('#reservationChild').modal('hide');
                    $('#dataTable_view').dataTable().fnDraw();
                }
            }
        });
    }

    function runRoomList() {
        $.ajax({
            url: '<?php echo base_url('/roomList') ?>',
            type: "post",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            async: false,
            // dataType:'json',
            success: function(respn) {

                $('#MAINT_ROOM_NO').html(respn).selectpicker('refresh');

            }
        });
    }

    function getCheckedInGuestFromRoom(room) {
        $.ajax({
            url: '<?php echo base_url('/getCustomerFromRoomNo') ?>',
            type: "post",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                room: room
            },

            success: function(respn) {

                $('#InHouseBooking').html(respn).selectpicker('refresh');
            }
        });
    }

    $(document).on('change', '#InHouseBooking', function() {
        reservation_id = $(this).find(":selected").attr('data-RESV_ID');
    });

    $(document).on('change', '#MAINT_ROOM_NO', function() {
        var room = $(this).val();
        getCheckedInGuestFromRoom(room);
    });

    $(document).on('change', '#MAINT_CATEGORY', function() {

        var cat = $('#MAINT_CATEGORY').find('option:selected').val();

        getSubCategoryList(cat);

    });

    $(document).on('change', 'select[name="MAINT_STATUS"]', function() {
        if ($(this).val() == 'Rejected') {
            $('.maint-comment-div').show();
        } else {
            $('.maint-comment-div').hide();
        }
    });


    function getSubCategoryList(cat) {
        $.ajax({
            url: '<?php echo base_url('/getSubCategory') ?>',
            type: "post",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                category: cat
            },
            // dataType:'json',
            async: false,
            success: function(respn) {
                $('#MAINT_SUB_CATEGORY').html(respn).selectpicker('refresh');
            }
        });
    }

    $(document).on('click', '.editWindow', function() {
        resetForm();
        runRoomList();

        setTimeout(() => {
            var sysid = $(this).attr('data_sysid');
            $('#sysid').val(sysid);
            $('#reservationChild').modal('show');
            $.ajax({
                url: '<?php echo base_url('/editRequest') ?>',
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
                    var data = respn[0];

                    var dataTrim = $.trim(data.MAINT_SUB_CATEGORY);
                    var roomTrim = $.trim(data['MAINT_ROOM_NO']);

                    $('#MAINT_ROOM_NO').val(data['MAINT_ROOM_NO']).trigger('change');
                    setTimeout(() => {
                        $('#InHouseBooking').val(data.CUST_NAME).trigger('change');
                    }, 500);

                    if (data['MAINT_TYPE'] == 'bulb_key') {
                        $('#MAINT_TYPE1').prop('checked', true);
                        $('#MAINT_TYPE2').prop('checked', false);
                    } else {
                        $('#MAINT_TYPE1').prop('checked', false);
                        $('#MAINT_TYPE2').prop('checked', true);
                    }
                    runCatList();

                    $('#MAINT_CATEGORY').val(data.MAINT_CATEGORY).trigger('change');
                    $('#MAINT_SUB_CATEGORY').val(dataTrim).trigger('change');

                    $('#MAINT_DETAILS').val(data['MAINT_DETAILS']);
                    $('#MAINT_PREFERRED_DT').val(data['MAINT_PREFERRED_DT']);

                    data['MAINT_PREFERRED_TIME'] = data['MAINT_PREFERRED_TIME'].split(' ')[1].split('.')[0];
                    $('#MAINT_PREFERRED_TIME').val(data['MAINT_PREFERRED_TIME']);

                    $('select[name="MAINT_STATUS"]').val(data.MAINT_STATUS).trigger('change');
                    $('textarea[name="MAINT_COMMENT"]').val(data.MAINT_COMMENT);

                    setTimeout(function() {
                        $(`${form_id} select[name='MAINT_DEPARTMENT_ID']`).val(data.MAINT_DEPARTMENT_ID).trigger('change');

                        setTimeout(function() {
                            $(`${form_id} select[name='MAINT_ATTENDANT_ID']`).val(data.MAINT_ATTENDANT_ID).trigger('change');
                        }, 1000);
                    }, 500);

                    $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
                }
            });
        }, 500);

    });

    $(`${form_id} [name='MAINT_DEPARTMENT_ID']`).change(function() {
        let department_id = $(this).val();

        if (department_id) {
            $.ajax({
                url: '<?= base_url('/user-by-department') ?>',
                type: "post",
                data: {
                    department_ids: [department_id]
                },
                dataType: 'json',
                success: function(response) {
                    if (response['SUCCESS'] == 200) {
                        let users = response['RESPONSE']['OUTPUT'];

                        let html = '';
                        for (let user of users) {
                            html += `
                            <option value="${user.USR_ID}">${user.USR_FIRST_NAME} ${user.USR_LAST_NAME}</option>
                        `;
                        }

                        $(`${form_id} select[name='MAINT_ATTENDANT_ID']`).html(html);
                        $(`${form_id} select[name='MAINT_ATTENDANT_ID']`).trigger('change');
                    }
                }
            });
        }
    });

    $(document).on('click', '.get-comments', function() {
        let maintenance_request_id = $(this).data('maintenance_request_id');

        $.ajax({
            url: '<?= base_url('maintenance/get-comments') ?>',
            type: "post",
            data: {
                maintenance_request_id
            },
            dataType: 'json',
            success: function(response) {
                if (response['SUCCESS'] == 200) {
                    let comments = response['RESPONSE']['OUTPUT'];
                    
                    let html = '';
                    for (let comment of comments) {
                        html += `
                            <b>${comment.USR_NAME} (${comment.MRC_CREATED_AT})</b></br>
                            <span class="">${comment.MRC_COMMENT}</span></br>
                        `;
                    }

                    if(!html)
                        html = `<b>No Comments!</b>`;

                    $('#comment-modal .modal-title').html(`Comments of Request# ${maintenance_request_id}`);
                    $('#comment-modal .modal-body').html(html);
                    $('#comment-modal').modal('show');
                }
            }
        });
    });
</script>
<script src="<?php //echo base_url('assets/js/bootstrap.bundle.js')
                ?>"></script>
<script src="<?php //echo base_url('assets/js/bootstrap-select.js')
                ?>"></script>
<?= $this->endSection() ?>