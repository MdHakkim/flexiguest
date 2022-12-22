<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/SuccessReport') ?>
<?= $this->include('Layout/ErrorReport') ?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Masters /</span> Payment Methods</h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid p-3">
                <table id="dataTable_view" class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Enable/Disable</th>
                            <th>Code</th>
                            <th>Payment Description</th>
                            <th>Transaction code</th>
                            <th>Credit Limit</th>
                            <th>Display Sequence</th>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="popModalWindowlable">Add Payment Method</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="submitForm">
                        <div class="row g-3">

                            <input type="hidden" name="PYM_ID" id="PYM_ID" class="form-control" />

                            <div class="col-md-6">
                                <lable class="form-lable">Payment Method</lable>
                                <input type="text" name="PYM_CODE" class="form-control" placeholder="payment method" />
                            </div>

                            <div class="col-md-6">
                                <lable class="form-lable">Description</lable>
                                <input type="text" name="PYM_DESC" class="form-control" placeholder="description" />
                            </div>

                            <div class="col-md-6">
                                <lable class="form-lable">Transaction Code</lable>
                                <input type="number" name="PYM_TXN_CODE" class="form-control" placeholder="transaction code" />
                            </div>

                            <div class="col-md-6">
                                <lable class="form-lable">Credit Limit</lable>
                                <input type="number" name="PYM_CREDIT_LIMIT" class="form-control" placeholder="credit limit" />
                            </div>

                            <h6 class="mb-1">Validations</h6>

                            <div class="col-md-6">
                                <lable class="form-lable">Card Lenght</lable>
                                <input type="text" name="PYM_CARD_LENGTH" class="form-control" placeholder="card length" />
                            </div>

                            <div class="col-md-6">
                                <lable class="form-lable">Card Prefix</lable>
                                <input type="text" name="PYM_CARD_PREFIX" class="form-control" placeholder="card prefix" />
                            </div>

                            <div class="col-md-6">
                                <lable class="form-lable">Display Sequence</lable>
                                <input type="number" name="PYM_DISPLAY_SEQUENCE" class="form-control" placeholder="display sequence" />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Enable/Disable *</b></label>
                                <select class="select2" name="PYM_ENABLE_DISABLE">
                                    <option value="1">Enabled</option>
                                    <option value="0">Disable</option>
                                </select>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="submitBtn" onClick="submitForm()" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Modal window -->

    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    var form_id = '#submitForm';
    var compAgntMode = '';
    var linkMode = '';

    $(document).ready(function() {
        linkMode = 'EX';
        $('#dataTable_view').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url': '<?php echo base_url('/paymentView') ?>'
            },
            'columns': [{
                    data: 'PYM_ID'
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        if (data['PYM_ENABLE_DISABLE'])
                            return `<span class="badge rounded-pill bg-label-success">Enabled</span>`;
                        else
                            return `<span class="badge rounded-pill bg-label-danger">Disabled</span>`;
                    }
                },
                {
                    data: 'PYM_CODE'
                },
                {
                    data: 'PYM_DESC'
                },
                {
                    data: 'PYM_TXN_CODE'
                },
                {
                    data: 'PYM_CREDIT_LIMIT'
                },
                {
                    data: 'PYM_DISPLAY_SEQUENCE'
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
                            '<li><a href="javascript:;" data_sysid="' + data['PYM_ID'] + '" class="dropdown-item editWindow">Edit</a></li>' +
                            '<div class="dropdown-divider"></div>' +
                            '<li><a href="javascript:;" data_sysid="' + data['PYM_ID'] + '" class="dropdown-item text-danger delete-record">Delete</a></li>' +
                            '</ul>' +
                            '</div>'
                        );
                    }
                },
            ],
            autowidth: true

        });
        $("#dataTable_view_wrapper .row:first").before('<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" onClick="addForm()"><i class="fa-solid fa-plus fa-lg"></i> Add</button></div></div>');

    });

    function resetForm() {
        $(`${form_id} input`).val('');
        $(`${form_id} textarea`).val('');
        $(`${form_id} select`).val('').trigger('change');

        $(`${form_id} input[name='PYM_DISPLAY_SEQUENCE']`).val('1');
        $(`${form_id} select[name='PYM_ENABLE_DISABLE']`).val('1').trigger('change');
    }

    function addForm() {
        resetForm();
        // $(':input', '#submitForm').not('[type="radio"]').val('').prop('checked', false).prop('selected', false);
        $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
        $('#popModalWindow').modal('show');
    }

    function submitForm() {
        hideModalAlerts();

        var fd = new FormData($(`${form_id}`)[0]);

        var url = '<?php echo base_url('/insertPayment') ?>';
        $.ajax({
            url: url,
            type: "post",
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {

                var mcontent = '';
                $.each(response['RESPONSE']['REPORT_RES'], function(ind, data) {
                    mcontent += '<li>' + data + '</li>';
                });

                if (response['SUCCESS'] != 200) {
                    showModalAlert('error', mcontent);
                } else {
                    showModalAlert('success', mcontent);

                    $('#popModalWindow').modal('hide');
                    $('#dataTable_view').dataTable().fnDraw();
                }
            }
        });
    }

    $(document).on('click', '.editWindow', function() {
        resetForm();

        var sysid = $(this).attr('data_sysid');
        $('#popModalWindow').modal('show');

        var url = '<?php echo base_url('/editPayment') ?>';
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
                    $.each(data, function(field, val) {
                        if ($(`${form_id} input[name='${field}'][type!='file']`).length)
                            $(`${form_id} input[name='${field}']`).val(val);

                        else if ($(`${form_id} textarea[name='${field}']`).length)
                            $(`${form_id} textarea[name='${field}']`).val(val);

                        else if ($(`${form_id} select[name='${field}']`).length)
                            $(`${form_id} select[name='${field}']`).val(val).trigger('change');
                    });
                });

                $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
            }
        });
    });

    $(document).on('click', '.delete-record', function() {
        hideModalAlerts();

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
                        url: '<?php echo base_url('/deletePayment') ?>',
                        type: "post",
                        data: {
                            sysid: sysid
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
</script>

<?= $this->endSection() ?>