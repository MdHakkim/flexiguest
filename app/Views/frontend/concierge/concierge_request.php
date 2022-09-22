<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>
<?= $this->include('Layout/image_modal') ?>

<style>
    /* .light-style .bs-stepper .step.crossed .bs-stepper-label {
        color: #677788 !important;
    }

    .bs-stepper .step.crossed .step-trigger::after {
        background-color: #d4d8dd;
    }

    .ql-snow .ql-editor {
        min-height: 5rem;
    }

    .light-style .bs-stepper:not(.wizard-modern) {
        box-shadow: unset;
    }

    .light-style .bs-stepper {
        background-color: unset;
    }

    #popModalWindow .modal-body {
        padding: 0.6rem;
    } */
</style>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Concierge /</span> Concierge Requests</h4>

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
                            <th>Payment Method</th>
                            <th>Payment Status</th>
                            <th>Offer</th>
                            <th>Guest Name</th>
                            <th>Guest Phone</th>
                            <th>Guest Email</th>
                            <th>Reservation ID</th>
                            <th>Quantity</th>
                            <th>Total Amount</th>
                            <th>Tax Amount</th>
                            <th>Net Amount</th>
                            <th>Remarks</th>
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
                    <h4 class="modal-title" id="popModalWindowlabel">Add Concierge Request</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>

                <div class="modal-body">

                    <form id="concierge-request-form" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <input type="hidden" name="id" class="form-control" />
                            <input type="hidden" name="CR_CUSTOMER_ID" class="form-control" />

                            <div class="col-md-6">
                                <label class="form-label"><b>Offer *</b></label>
                                <select class="select2" name="CR_OFFER_ID" id="CR_OFFER_ID" onchange="getAmount()">
                                    <?php foreach ($concierge_offers as $concierge_offer) : ?>
                                        <option value="<?= $concierge_offer['CO_ID'] ?>">
                                            <?= $concierge_offer['CO_TITLE'] ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Quantity *</b></label>
                                <input type="number" name="CR_QUANTITY" id="CR_QUANTITY" class="form-control" oninput="getAmount()" placeholder="Quantity" />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Guest Name *</b></label>
                                <input type="text" name="CR_GUEST_NAME" class="form-control" placeholder="Guest name" />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Guest Email *</b></label>
                                <input type="email" name="CR_GUEST_EMAIL" class="form-control" placeholder="Guest email" />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Guest Phone *</b></label>
                                <input type="text" name="CR_GUEST_PHONE" class="form-control" placeholder="Guest phone" />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Reservations *</b></label>
                                <select class="select2" name="CR_RESERVATION_ID" onchange="setCustomerId()">
                                    <?php foreach ($reservations as $reservation) : ?>
                                        <option value="<?= $reservation['RESV_ID'] ?>" data-customer_id="<?= $reservation['CUST_ID'] ?>">
                                            RES<?= $reservation['RESV_ID'] ?>
                                            -
                                            <?= $reservation['CUST_FIRST_NAME'] . ' ' . $reservation['CUST_MIDDLE_NAME'] . ' ' . $reservation['CUST_LAST_NAME'] ?>
                                            -
                                            <?= $reservation['CUST_ID'] ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Net Amount</b></label>
                                <input type="number" name="CR_NET_AMOUNT" id="CR_NET_AMOUNT" class="form-control" placeholder="Net amount" readonly />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Tax Amount</b></label>
                                <input type="number" name="CR_TAX_AMOUNT" id="CR_TAX_AMOUNT" class="form-control" placeholder="Tax amount" readonly />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Total Amount</b></label>
                                <input type="number" name="CR_TOTAL_AMOUNT" id="CR_TOTAL_AMOUNT" class="form-control" placeholder="Total amount" readonly />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Status *</b></label>
                                <select class="select2" name="CR_STATUS">
                                    <option value="in-progress">In Progress</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Preferred Date *</b></label>

                                <div class="input-group">
                                    <input type="text" name="CR_PREFERRED_DATE" placeholder="YYYY-MM-DD" class="form-control">
                                    <span class="input-group-append">
                                        <span class="input-group-text bg-light d-block">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Preferred Time *</b></label>
                                <input type="time" name="CR_PREFERRED_TIME" class="form-control" />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Payment Method *</b></label>
                                <select class="select2" name="CR_PAYMENT_METHOD">
                                    <option>Pay at Reception</option>
                                    <option>Samsung Pay</option>
                                    <option>Credit/Debit card</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Payment Status *</b></label>
                                <select class="select2" name="CR_PAYMENT_STATUS">
                                    <option>UnPaid</option>
                                    <option>Paid</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label"><b>Remarks</b></label>
                                <textarea name="CR_REMARKS" class="form-control" placeholder="Remarks..."></textarea>
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
    var compAgntMode = '';
    var linkMode = '';

    $(document).ready(function() {
        $('input[name="CR_PREFERRED_DATE"]').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
        });

        linkMode = 'EX';

        $('#dataTable_view').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url': '<?php echo base_url('concierge/all-concierge-requests') ?>'
            },
            'columns': [{
                    data: ''
                },
                {
                    data: 'CR_ID'
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        let class_name = 'badge rounded-pill';

                        if (data['CR_STATUS'] == 'accepted')
                            class_name += ' bg-label-success';

                        else if (data['CR_STATUS'] == 'rejected')
                            class_name += ' bg-label-danger';

                        else if (data['CR_STATUS'] == 'in-progress')
                            class_name += ' bg-label-warning';

                        else
                            class_name += ' bg-label-info';

                        return (`
                            <span class="${class_name}">${data['CR_STATUS']}</span>
                        `);
                    }
                },
                {
                    data: 'CR_PAYMENT_STATUS'
                },
                {
                    data: 'CR_PAYMENT_METHOD'
                },
                {
                    data: 'CO_TITLE'
                },
                {
                    data: 'CR_GUEST_NAME'
                },
                {
                    data: 'CR_GUEST_PHONE'
                },
                {
                    data: 'CR_GUEST_EMAIL'
                },
                {
                    data: 'CR_RESERVATION_ID'
                },
                {
                    data: 'CR_QUANTITY'
                },
                {
                    data: 'CR_TOTAL_AMOUNT'
                },
                {
                    data: 'CR_TAX_AMOUNT'
                },
                {
                    data: 'CR_NET_AMOUNT'
                },
                {
                    data: 'CR_REMARKS'
                },
                {
                    data: 'CR_CREATED_AT'
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
                                    <a href="javascript:;" data_id="${data['CR_ID']}" class="dropdown-item editWindow text-primary">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>
                                </li>

                                <div class="dropdown-divider"></div>
                                
                                <li>
                                    <a href="javascript:;" data_id="${data['CR_ID']}" class="dropdown-item text-danger delete-record">
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
                },
            ],
            "order": [
                [0, "desc"]
            ],
            destroy: true,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function(row) {
                            var data = row.data();
                            return 'Details of ' + data['title'];
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
            `<div class="row flxi_pad_view">
                <div class="col-md-3 ps-0">
                    <button type="button" class="btn btn-primary" onClick="addForm()">
                        <i class="fa-solid fa-plus fa-lg"></i> 
                        Add
                    </button>
                </div>
            </div>`
        );
    });

    function getAmount() {
        let concierge_offers = <?= json_encode($concierge_offers) ?>;
        let offer_id = $("#CR_OFFER_ID").val();
        let quantity = parseInt($("#CR_QUANTITY").val() || 0);

        concierge_offers.forEach((offer) => {
            if (offer.CO_ID == offer_id) {
                $("#CR_TOTAL_AMOUNT").val(quantity * offer.CO_OFFER_PRICE);
                $("#CR_TAX_AMOUNT").val(quantity * offer.CO_TAX_AMOUNT);
                $("#CR_NET_AMOUNT").val(quantity * offer.CO_NET_PRICE);
            }
        });
    }

    function setCustomerId() {
        let customer_id = $('select[name="CR_RESERVATION_ID"]').find(":selected").data('customer_id');
        $('input[name="CR_CUSTOMER_ID"]').val(customer_id);
    }

    // Show Add Rate Class Form

    function addForm() {
        resetConciergeRequestForm();

        $('#submitBtn').text('Save');
        $('#popModalWindowlabel').html('Add Concierge Request');

        $('#popModalWindow').modal('show');
    }

    function resetConciergeRequestForm() {
        let id = "concierge-request-form";

        $(`#${id} input`).val('');
        $(`#${id} select`).val('').trigger('change');
        $(`#${id} #CR_QUANTITY`).val('1');

        $(`#${id} select[name='CR_STATUS']`).val('in-progress').trigger('change');
        $(`#${id} select[name='CR_PAYMENT_METHOD']`).val('Pay at Reception').trigger('change');
        $(`#${id} select[name='CR_PAYMENT_STATUS']`).val('UnPaid').trigger('change');
    }

    // Add New or Edit Rate Class submit Function
    function submitForm() {
        let id = "concierge-request-form";
        hideModalAlerts();

        var fd = new FormData($(`#${id}`)[0]);

        $.ajax({
            url: '<?= base_url('/concierge/store-concierge-request') ?>',
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

    // Show Edit Rate Class Form
    $(document).on('click', '.editWindow', function() {
        resetConciergeRequestForm();

        $('.dtr-bs-modal').modal('hide');

        let id = "concierge-request-form";
        let concierge_request_id = $(this).attr('data_id');

        $('#popModalWindowlabel').html('Edit Concierge Request');
        $('#popModalWindow').modal('show');

        $(`#${id} input[name='id']`).val(concierge_request_id);

        var url = '<?php echo base_url('/concierge/edit-concierge-request') ?>';
        $.ajax({
            url: url,
            type: "post",
            data: {
                id: concierge_request_id
            },
            dataType: 'json',
            success: function(respn) {
                $(respn).each(function(inx, data) {


                    $.each(data, function(field, val) {

                        if ($(`#${id} input[name='${field}'][type!='file']`).length)
                            $(`#${id} input[name='${field}']`).val(val);

                        else if ($(`#${id} textarea[name='${field}']`).length)
                            $(`#${id} textarea[name='${field}']`).val(val);

                        else if ($(`#${id} select[name='${field}']`).length)
                            $(`#${id} select[name='${field}']`).val(val).trigger('change');
                    });
                });

                $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
                $('#dataTable_view').dataTable().fnDraw();
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
                        url: '<?php echo base_url('/concierge/delete-concierge-request') ?>',
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
                            var mcontent = '';
                            $.each(response['RESPONSE']['REPORT_RES'], function(ind, data) {
                                mcontent += '<li>' + data + '</li>';
                            });

                            if (response['SUCCESS'] != 200)
                                showModalAlert('error', mcontent);
                            else {
                                showModalAlert('success', mcontent);
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