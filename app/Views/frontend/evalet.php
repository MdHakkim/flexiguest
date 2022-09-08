<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>
<?= $this->include('Layout/image_modal') ?>

<style>
    .optional-files .image,
    .optional-files .file {
        position: relative;
        height: 100px;
        margin-bottom: 10px;
        width: 100px;
    }

    .optional-files .image img {
        width: 100%;
        height: 100%;
    }

    .optional-files .delete-icon {
        position: absolute;
        color: red;
        right: 5px;
        top: -8px;
        cursor: pointer;
    }

    .optional-files .file {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .optional-files .file a {
        display: flex;
        flex-direction: column;
        justify-content: space-evenly;
        height: 100%;
        overflow: hidden;
    }
</style>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Facility /</span> E-Valet</h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid table-responsive" style="padding: 16px 16px 6px 16px">
                <table id="dataTable_view" class="dt-responsive table table-striped display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Status</th>
                            <th>Parking Driver Name</th>
                            <th>Delivery Driver Name</th>
                            <th>Guest Type</th>
                            <th>Guest Name</th>
                            <th>Contact Number</th>
                            <th>Images</th>
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
                    <h4 class="modal-title" id="popModalWindowlabel">Add EValet</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="submit-form" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <input type="hidden" name="id" class="form-control" />
                            <input type="hidden" name="EV_CUSTOMER_ID" class="form-control" />
                            <input type="hidden" name="EV_ROOM_ID" class="form-control" />

                            <div class="col-md-12">
                                <label class="form-label"><b>Guest Type *</b></label>
                                <select class="select2" name="EV_GUEST_TYPE">
                                    <option>InHouse Guest</option>
                                    <option>Walk-In</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Reservation</b></label>
                                <select class="select2" name="EV_RESERVATION_ID" onchange="onChangeReservation()">
                                    <option value="0">Select Reservation</option>
                                    <?php foreach ($reservations as $reservation) : ?>
                                        <option value="<?= $reservation['RESV_ID'] ?>" data-customer_id="<?= $reservation['CUST_ID'] ?>" data-customer_name="<?= $reservation['CUST_FIRST_NAME'] . ' ' . $reservation['CUST_LAST_NAME'] ?>" data-room_no="<?= $reservation['RESV_ROOM'] ?>" data-room_id="<?= $reservation['RM_ID'] ?>" data-email="<?= $reservation['CUST_EMAIL'] ?>" data-mobile="<?= $reservation['CUST_MOBILE'] ?>">

                                            RES<?= $reservation['RESV_ID'] ?>
                                            -
                                            <?= $reservation['CUST_FIRST_NAME'] . ' ' . $reservation['CUST_LAST_NAME'] ?>
                                            -
                                            <?= $reservation['CUST_ID'] ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Room No.</b></label>
                                <input type="text" name="EV_ROOM_NO" class="form-control" placeholder="Room No" readonly />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Guest Name</b></label>
                                <input type="text" name="EV_GUEST_NAME" class="form-control" placeholder="Guest name" />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Guest Email</b></label>
                                <input type="email" name="EV_EMAIL" class="form-control" placeholder="Guest email" />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Mobile Number</b></label>
                                <input type="text" name="EV_CONTACT_NUMBER" class="form-control" placeholder="Mobile number" />
                            </div>

                            <div class="col-md-6 extra-space"></div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Car Plate Number *</b></label>
                                <input type="text" name="EV_CAR_PLATE_NUMBER" class="form-control" placeholder="Plate number" />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Car Make *</b></label>
                                <input type="text" name="EV_CAR_MAKE" class="form-control" placeholder="Car make" />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Car Model *</b></label>
                                <input type="text" name="EV_CAR_MODEL" class="form-control" placeholder="Car model" />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Status *</b></label>
                                <select class="select2" name="EV_STATUS">
                                    <option>New</option>
                                    <option>Parking Assigned</option>
                                    <option>Parked</option>
                                    <option>Delivery Requested</option>
                                    <option>Delivery Assigned</option>
                                    <option>Ready to Collect</option>
                                    <option>Guest Collected</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label"><b>Image</b></label>
                                <input type="file" name="EV_CAR_IMAGES[]" class="form-control" multiple />
                            </div>

                            <div class="col-md-12">
                                <h5 class="mb-0">Parking Driver</h5>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Department</b></label>

                                <select name="EV_PARKING_DEPARTMENT_ID" class="select2 form-select">
                                    <?php foreach ($departments as $department) { ?>
                                        <option value="<?= $department['DEPT_ID'] ?>"><?= $department['DEPT_DESC'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Driver</b></label>
                                <select name="EV_PARKING_DRIVER_ID" class="select2 form-select">
                                </select>
                            </div>

                            <div class="col-md-12">
                                <h5 class="mb-0">Delivery Driver</h5>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Department</b></label>

                                <select name="EV_DELIVERY_DEPARTMENT_ID" class="select2 form-select">
                                    <?php foreach ($departments as $department) { ?>
                                        <option value="<?= $department['DEPT_ID'] ?>"><?= $department['DEPT_DESC'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Driver</b></label>
                                <select name="EV_DELIVERY_DRIVER_ID" class="select2 form-select">
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label"><b>Parking Details</b></label>
                                <textarea name="EV_PARKING_DETAILS" class="form-control" placeholder="Parking Details..."></textarea>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>

                    <button type="button" id="submitBtn" onClick="submitForm()" class="btn btn-primary">
                        Save
                    </button>
                </div>
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
    var form_id = "#submit-form";

    function onChangeReservation() {
        let customer_id = $(`${form_id} select[name="EV_RESERVATION_ID"]`).find(":selected").data('customer_id');
        let customer_name = $(`${form_id} select[name="EV_RESERVATION_ID"]`).find(":selected").data('customer_name');
        let room_id = $(`${form_id} select[name="EV_RESERVATION_ID"]`).find(":selected").data('room_id');
        let room_no = $(`${form_id} select[name="EV_RESERVATION_ID"]`).find(":selected").data('room_no');
        let email = $(`${form_id} select[name="EV_RESERVATION_ID"]`).find(":selected").data('email');
        let mobile = $(`${form_id} select[name="EV_RESERVATION_ID"]`).find(":selected").data('mobile');

        $(`${form_id} input[name="EV_CUSTOMER_ID"]`).val(customer_id);
        $(`${form_id} input[name="EV_ROOM_ID"]`).val(room_id);
        $(`${form_id} input[name="EV_ROOM_NO"]`).val(room_no);
        
        if(customer_name)
            $(`${form_id} input[name="EV_GUEST_NAME"]`).val(customer_name);
        if(email)
            $(`${form_id} input[name="EV_EMAIL"]`).val(email);
        if(mobile)
            $(`${form_id} input[name="EV_CONTACT_NUMBER"]`).val(mobile);
    }

    $(`${form_id} [name='EV_PARKING_DEPARTMENT_ID'], ${form_id} [name='EV_DELIVERY_DEPARTMENT_ID']`).change(function() {
        let department_id = $(this).val();
        let name = $(this).attr('name');

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

                        if (name == 'EV_PARKING_DEPARTMENT_ID') {
                            $(`${form_id} select[name='EV_PARKING_DRIVER_ID']`).html(html);
                            $(`${form_id} select[name='EV_PARKING_DRIVER_ID']`).trigger('change');
                        } else {
                            $(`${form_id} select[name='EV_DELIVERY_DRIVER_ID']`).html(html);
                            $(`${form_id} select[name='EV_DELIVERY_DRIVER_ID']`).trigger('change');
                        }
                    }
                }
            });
        }
    });

    $(`${form_id} select[name='EV_GUEST_TYPE']`).change(function() {
        if ($(this).val() == 'InHouse Guest') {
            $(`${form_id} select[name="EV_RESERVATION_ID"]`).parent().parent().removeClass('d-none');
            $(`${form_id} input[name="EV_ROOM_NO"]`).parent().removeClass('d-none');
        } else {
            $(`${form_id} select[name="EV_RESERVATION_ID"]`).parent().parent().addClass('d-none');
            $(`${form_id} input[name="EV_ROOM_NO"]`).parent().addClass('d-none');

            $(`${form_id} select[name="EV_RESERVATION_ID"]`).val('').trigger('change');
            $(`${form_id} input[name="EV_ROOM_NO"]`).val('');
        }
    });

    var compAgntMode = '';
    var linkMode = '';

    $(document).ready(function() {
        linkMode = 'EX';

        $('#dataTable_view').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url': '<?php echo base_url('/evalet/all-evalet') ?>'
            },
            'columns': [{
                    data: ''
                },
                {
                    data: 'EV_ID'
                },
                {
                    data: 'EV_STATUS'
                },
                {
                    data: 'EV_PARKING_DRIVER_NAME',
                },
                {
                    data: 'EV_DELIVERY_DRIVER_NAME',
                },
                {
                    data: 'EV_GUEST_TYPE'
                },
                {
                    data: 'EV_GUEST_NAME'
                },
                {
                    data: 'EV_CONTACT_NUMBER'
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        let html = `<div class="optional-files"> <div class="row">`;

                        $.each(data['FILES'], function(key, file) {
                            html += `
                                <div class="col-sm-4 col-md-3 col-lg-2 image">
                                    <img onClick='displayImagePopup("${file['EVI_FILE_URL']}")' src="${file['EVI_FILE_URL']}"/>
                                </div>
                            `;
                        });

                        return html + `</div></div>`;
                    }
                },
                {
                    data: 'EV_CREATED_AT'
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
                                    <a href="javascript:;" 
                                        data_id="${data['EV_ID']}" 
                                        data-parking_department_id="${data['EV_PARKING_DEPARTMENT_ID']}" 
                                        data-delivery_department_id="${data['EV_DELIVERY_DEPARTMENT_ID']}" 
                                        data-reservation_id="${data['EV_RESERVATION_ID']}" 
                                        class="dropdown-item editWindow text-primary">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>
                                </li>

                                <div class="dropdown-divider"></div>
                                
                                <li>
                                    <a href="javascript:;" data_id="${data['EV_ID']}" class="dropdown-item text-danger delete-record">
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
            }, {
                width: "15%"
            }, {
                width: "10%"
            }, {
                width: "20%"
            }, {
                width: "20%"
            }, {
                width: "15%"
            }],
            "order": [
                [1, "desc"]
            ],
            destroy: true,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function(row) {
                            var data = row.data();
                            return 'Details of ' + data['AL_ID'];
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

    function resetForm() {
        $(`${form_id} input`).val('');
        $(`${form_id} textarea`).val('');
        $(`${form_id} select`).val('').trigger('change');

        $(`${form_id} select[name="EV_RESERVATION_ID"]`).val('0').trigger('change');
        $(`${form_id} select[name="EV_GUEST_TYPE"]`).val('InHouse Guest').trigger('change');
        $(`${form_id} select[name="EV_STATUS"]`).val('New').trigger('change');
    }

    // Show Add Rate Class Form
    function addForm() {
        resetForm();

        $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
        $('#popModalWindowlabel').html('Add E-Valet');

        $('#popModalWindow').modal('show');
    }

    // Add New or Edit Rate Class submit Function
    function submitForm() {
        hideModalAlerts();

        var fd = new FormData($(`${form_id}`)[0]);
        fd.delete('EV_CAR_IMAGES[]');

        if ($(`${form_id} select[name="EV_RESERVATION_ID"]`).val() == '0')
            fd.delete('EV_RESERVATION_ID');

        files = $(`${form_id} input[name='EV_CAR_IMAGES[]']`)[0].files;
        for (let i = 0; i < files.length; i++)
            fd.append('EV_CAR_IMAGES[]', files[i]);

        $.ajax({
            url: '<?= base_url('/evalet/store') ?>',
            type: "post",
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response['SUCCESS'] != 200) {

                    var ERROR = response['RESPONSE']['REPORT_RES'];
                    var mcontent = '';
                    $.each(ERROR, function(ind, data) {

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

        $('.dtr-bs-modal').modal('hide');
        var evalet_id = $(this).attr('data_id');

        $(`${form_id} input[name='id']`).val(evalet_id);

        $('#popModalWindowlabel').html('Edit E-Valet');
        $('#popModalWindow').modal('show');


        $(`${form_id} select[name='EV_PARKING_DEPARTMENT_ID']`).val($(this).data('parking_department_id')).trigger('change');
        $(`${form_id} select[name='EV_DELIVERY_DEPARTMENT_ID']`).val($(this).data('delivery_department_id')).trigger('change');
        
        $(`${form_id} select[name='EV_RESERVATION_ID']`).val($(this).data('reservation_id')).trigger('change');

        var url = '<?php echo base_url('/evalet/edit') ?>';
        $.ajax({
            url: url,
            type: "post",
            data: {
                id: evalet_id
            },
            dataType: 'json',
            success: function(response) {
                $(response).each(function(inx, data) {
                    $.each(data, function(field, val) {

                        if ($(`${form_id} input[name='${field}'][type!='file']`).length)
                            $(`${form_id} input[name='${field}']`).val(val);

                        else if ($(`${form_id} textarea[name='${field}']`).length)
                            $(`${form_id} textarea[name='${field}']`).val(val);

                        else if (field == 'EV_PARKING_DRIVER_ID' || field == 'EV_DELIVERY_DRIVER_ID')
                            setTimeout(() => {
                                $(`${form_id} select[name='${field}']`).val(val).trigger('change');
                            }, 500);

                        else if (field != 'EV_RESERVATION_ID' && $(`${form_id} select[name='${field}']`).length)
                            $(`${form_id} select[name='${field}']`).val(val).trigger('change');
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
                        url: '<?php echo base_url('/evalet/delete') ?>',
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