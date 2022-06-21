<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>
<?= $this->include('Layout/image_modal') ?>

<style>
    .light-style .bs-stepper .step.crossed .bs-stepper-label {
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
    }
    .text-right {
        text-align: right !important;
    }
</style>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Concierge /</span> Concierge Offers</h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid table-responsive" style="padding: 16px 16px 6px 16px">
                <table id="dataTable_view" class="dt-responsive table table-striped display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Status</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Cover Image</th>
                            <th>From Date</th>
                            <th>To Date</th>
                            <th>Offer Price</th>
                            <th>Actual Price</th>
                            <th>Provider Title</th>
                            <th>Provider Logo</th>
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
                    <h4 class="modal-title" id="popModalWindowlabel">Add Concierge Offer</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="bs-stepper wizard-numbered mt-2">
                            <div class="bs-stepper-header">

                                <div class="step" data-target="#general-details">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-label mt-1">
                                            <span class="bs-stepper-title">General Details</span>
                                        </span>
                                    </button>
                                </div>

                                <div class="line"></div>

                                <div class="step" data-target="#contact-info">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-label mt-1">
                                            <span class="bs-stepper-title">Contact Info</span>
                                        </span>
                                    </button>
                                </div>

                                <div class="line"></div>

                                <div class="step" data-target="#price-instructions">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-label mt-1">
                                            <span class="bs-stepper-title">Price & Instructions</span>
                                        </span>
                                    </button>
                                </div>
                            </div>

                            <div class="bs-stepper-content">

                                <form id="concierge-offer-form" onSubmit="return false">
                                    <input type="hidden" name="id" />

                                    <!-- General Details -->
                                    <div id="general-details" class="content">

                                        <div class="row g-3">

                                            <div class="col-md-6">
                                                <label class="form-label" for="title"><b>Title *</b></label>
                                                <input type="text" name="CO_TITLE" id="title" class="form-control" placeholder="Title" />
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label" for='cover_image'><b>Cover Image *</b></label>
                                                <input type="file" name="CO_COVER_IMAGE" id="cover_image" class="form-control" />
                                            </div>

                                            <div class="col-md-12">

                                                <label class="form-label"><b>Description *</b></label>

                                                <textarea name="CO_DESCRIPTION" class="d-none"></textarea>

                                                <div id="snow-editor"></div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label"><b>Valid From Date *</b></label>

                                                <div class="input-group">
                                                    <input type="text" name="CO_VALID_FROM_DATE" id="valid_from_date" placeholder="DD-MM-YYYY" class="form-control">
                                                    <span class="input-group-append">
                                                        <span class="input-group-text bg-light d-block">
                                                            <i class="fa fa-calendar"></i>
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label"><b>Valid To Date *</b></label>

                                                <div class="input-group">
                                                    <input type="text" name="CO_VALID_TO_DATE" id="valid_to_date" placeholder="DD-MM-YYYY" class="form-control">
                                                    <span class="input-group-append">
                                                        <span class="input-group-text bg-light d-block">
                                                            <i class="fa fa-calendar"></i>
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label"><b>PMS Code</b></label>

                                                <input type="text" name="CO_PMS_CODE" class="form-control" placeholder="PMS code" />
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label"><b>Ext Ref</b></label>

                                                <input type="text" name="CO_EXT_REF" class="form-control" placeholder="Ext Ref" />
                                            </div>

                                            <div class="col-12 d-flex justify-content-between">
                                                <button class="btn btn-label-secondary btn-prev" disabled>
                                                    <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                </button>

                                                <button class="btn btn-primary btn-next">
                                                    <span class="align-middle d-sm-inline-block d-none me-sm-1 me-0">Next</span>
                                                    <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contact Info -->
                                    <div id="contact-info" class="content">
                                        <div class="row g-3">

                                            <div class="col-md-6">

                                                <label class="form-label"><b>Contact Email</b></label>

                                                <input type="email" name="CO_CONTACT_EMAIL" class="form-control" placeholder="Contact Email" />
                                            </div>

                                            <div class="col-md-6">

                                                <label class="form-label"><b>Contact Phone</b></label>

                                                <input type="text" name="CO_CONTACT_PHONE" class="form-control" placeholder="Contact Phone" />
                                            </div>

                                            <div class="col-md-12">

                                                <label class="form-label"><b>location</b></label>

                                                <input type="text" name="CO_LOCATION" class="form-control" placeholder="Location" />
                                            </div>

                                            <div class="col-md-6">

                                                <label class="form-label"><b>Provider Title</b></label>

                                                <input type="text" name="CO_PROVIDER_TITLE" class="form-control" placeholder="Provider Title" />
                                            </div>

                                            <div class="col-md-6">

                                                <label class="form-label"><b>Provider Logo</b></label>

                                                <input type="file" name="CO_PROVIDER_LOGO" class="form-control" />
                                            </div>

                                            <div class="col-md-12">

                                                <label class="form-label"><b>Provider Sub Title</b></label>

                                                <input type="text" name="CO_PROVIDER_SUB_TITLE" class="form-control" placeholder="Provider Sub Title" />
                                            </div>

                                            <div class="col-md-6">

                                                <label class="form-label"><b>Provider Email *</b></label>

                                                <input type="email" name="CO_PROVIDER_EMAIL" class="form-control" placeholder="Provider Email" />
                                            </div>

                                            <div class="col-md-6">

                                                <label class="form-label"><b>Provider Phone</b></label>

                                                <input type="text" name="CO_PROVIDER_PHONE" class="form-control" placeholder="Provider Phone" />
                                            </div>

                                            <div class="col-12 d-flex justify-content-between">
                                                <button class="btn btn-primary btn-prev">
                                                    <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                </button>

                                                <button class="btn btn-primary btn-next">
                                                    <span class="align-middle d-sm-inline-block d-none me-sm-1 me-0">Next</span>
                                                    <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Social Links -->
                                    <div id="price-instructions" class="content">

                                        <div class="row g-3">

                                            <div class="col-md-12 text-right">
                                                <div class="form-check form-switch">
                                                    <!-- fa-spin -->
                                                    <i class="fa-solid fa-rotate fa-xl me-2 calculate-price" onclick="calculatePrice(this)"></i>

                                                    <span>Exclusive</span>
                                                    <input class="form-check-input" type="checkbox" name="CO_EXCLUSIVE_OR_INCLUSIVE" id="CO_EXCLUSIVE_OR_INCLUSIVE" checked style="margin: 0 10px; float: unset" />
                                                    <label>Inclusive</label>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Currency *</label>
                                                <select class="select2" name="CO_CURRENCY_ID">
                                                    <option value="">Select Currency</option>

                                                    <?php foreach ($currencies as $currency) : ?>
                                                        <option value="<?= $currency['CUR_ID'] ?>">
                                                            <?= $currency['CUR_DESC'] ?>
                                                        </option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label"><b>Actual Price *</b></label>
                                                <input type="number" name="CO_ACTUAL_PRICE" class="form-control" placeholder="Actual price" />
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label"><b>Offer Price *</b></label>
                                                <input type="number" name="CO_OFFER_PRICE" id="CO_OFFER_PRICE" class="form-control" placeholder="Offer price" />
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label"><b>Tax Rate *</b></label>
                                                <input type="number" name="CO_TAX_RATE" id="CO_TAX_RATE" class="form-control" placeholder="Tax rate" value="5" readonly />
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label"><b>Tax Amount *</b></label>
                                                <input type="number" name="CO_TAX_AMOUNT" id="CO_TAX_AMOUNT" class="form-control" placeholder="Tax amount" />
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label"><b>Net Price *</b></label>
                                                <input type="number" name="CO_NET_PRICE" id="CO_NET_PRICE" class="form-control" placeholder="Net price" />
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label"><b>Min Quantity</b></label>
                                                <input type="number" name="CO_MIN_QUANTITY" class="form-control" placeholder="Min quantity" />
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label"><b>Max Quantity</b></label>
                                                <input type="number" name="CO_MAX_QUANTITY" class="form-control" placeholder="Max quantity" />
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label"><b>Min Age</b></label>
                                                <input type="number" name="CO_MIN_AGE" class="form-control" placeholder="Min age" />
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label"><b>Max Age</b></label>
                                                <input type="number" name="CO_MAX_AGE" class="form-control" placeholder="Max age" />
                                            </div>

                                            <div class="col-12 d-flex justify-content-between">
                                                <button class="btn btn-primary btn-prev">
                                                    <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                </button>

                                                <button id="submitBtn" class="btn btn-success btn-submit" onClick="submitForm()">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>

                </div>

                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>

                    <button type="button" id="submitBtn" onClick="submitForm('submit-form')" class="btn btn-primary">
                        Save
                    </button>
                </div> -->
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
    $(document).ready(function() {
        $('#valid_from_date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
        });
        $('#valid_to_date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
        });

        const snowEditor = new Quill('#snow-editor', {
            bounds: '#snow-editor',
            theme: 'snow',
            placeholder: 'Content...',
        });

        const wizard_form = $(".wizard-numbered")[0];
        if (typeof wizard_form !== undefined && wizard_form !== null) {

            const btn_next_list = [].slice.call(wizard_form.querySelectorAll('.btn-next'));
            const btn_prev_list = [].slice.call(wizard_form.querySelectorAll('.btn-prev'));
            const btn_submit = wizard_form.querySelector('.btn-submit');

            const form_stepper = new Stepper(wizard_form, {
                linear: false
            });

            if (btn_next_list) {
                btn_next_list.forEach(btn => {
                    btn.addEventListener('click', event => {
                        form_stepper.next();
                    });
                });
            }

            if (btn_prev_list) {
                btn_prev_list.forEach(btn => {
                    btn.addEventListener('click', event => {
                        form_stepper.previous();
                    });
                });
            }
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
                'url': '<?php echo base_url('/concierge/all-concierge-offers') ?>'
            },
            'columns': [{
                    data: ''
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        let class_name = 'badge rounded-pill';

                        if (data['CO_STATUS'] == 'disabled')
                            class_name += ' bg-label-danger';
                        else
                            class_name += ' bg-label-success';

                        return (`
                            <span class="${class_name}">${data['CO_STATUS']}</span>
                        `);
                    }
                },
                {
                    data: 'CO_TITLE'
                },
                {
                    data: 'CO_DESCRIPTION'
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return (`<img onClick='displayImagePopup("<?= base_url() ?>/${data['CO_COVER_IMAGE']}")' src='<?= base_url() ?>/${data['CO_COVER_IMAGE']}' width='80' height='80'/>`);
                    }
                },
                {
                    data: 'CO_VALID_FROM_DATE'
                },
                {
                    data: 'CO_VALID_TO_DATE'
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return (`${data['CUR_CODE']} ${data['CO_OFFER_PRICE']}`);
                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return (`${data['CUR_CODE']} ${data['CO_ACTUAL_PRICE']}`);
                    }
                },
                {
                    data: 'CO_PROVIDER_TITLE'
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return (`<img onClick='displayImagePopup("<?= base_url() ?>/${data['CO_PROVIDER_LOGO']}")' src='<?= base_url() ?>/${data['CO_PROVIDER_LOGO']}' width='80' height='80'/>`);
                    }
                },
                {
                    data: 'CO_CREATED_AT'
                },
                {
                    data: null,
                    "orderable": false,
                    render: function(data, type, row, meta) {
                        return (
                            `
                        <div class="d-inline-block">
                            <a href="javascript:;" title="Edit or Delete" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a 
                                        href="javascript:;" 
                                        data_id="${data['CO_ID']}" 
                                        data_status="${data['CO_STATUS'] == 'disabled' ? 'enabled' : 'disabled'}" 
                                        class="dropdown-item change-status ${data['CO_STATUS'] == 'disabled' ? 'text-success' : 'text-info'}">

                                        <i class="fa-solid ${data['CO_STATUS'] == 'disabled' ? 'fa-unlock' : 'fa-lock'}"></i>

                                        ${data['CO_STATUS'] == 'disabled' ? 'Enable' : 'Disable'}
                                    </a>
                                </li>

                                <li>
                                    <a href="javascript:;" data_id="${data['CO_ID']}" class="dropdown-item editWindow text-primary">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>
                                </li>

                                <div class="dropdown-divider"></div>
                                
                                <li>
                                    <a href="javascript:;" data_id="${data['CO_ID']}" class="dropdown-item text-danger delete-record">
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
                width: "13%"
            }, {
                width: "10%"
            }, {
                width: "10%"
            }, {
                width: "5%"
            }, {
                width: "35%"
            }, {
                width: "5%"
            }, {
                width: "5%"
            }, {
                width: "5%"
            }],
            "order": [
                [11, "desc"]
            ],
            destroy: true,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function(row) {
                            var data = row.data();
                            return 'Details of ' + data['CO_TITLE'];
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

    function calculatePrice(e) {
        $('.calculate-price').addClass('fa-spin');

        let tax_rate = parseFloat($('#CO_TAX_RATE').val()) || 0;
        let exclusive_or_inclusive = $('#CO_EXCLUSIVE_OR_INCLUSIVE').val();
        let offer_price = parseFloat($('#CO_OFFER_PRICE').val()) || 0;

        if($('#CO_EXCLUSIVE_OR_INCLUSIVE').is(':checked')) { // inclusive
            let tax_amount = (tax_rate / 100) * offer_price;

            $('#CO_TAX_AMOUNT').val(tax_amount);
            $('#CO_NET_PRICE').val(offer_price - tax_amount);
        }
        else { // exclusive
            let tax_amount = (tax_rate / 100) * offer_price;

            $('#CO_OFFER_PRICE').val(tax_amount + offer_price);
            $('#CO_TAX_AMOUNT').val(tax_amount);
            $('#CO_NET_PRICE').val(offer_price);
        }

        window.setTimeout(() => {
            $('.calculate-price').removeClass('fa-spin');
        }, 300);
    }

    // Show Add Rate Class Form

    function addForm() {
        resetConciergeOfferForm();

        $('#submitBtn').text('Save');
        $('#popModalWindowlabel').html('Add Concierge Offer');

        $('#popModalWindow').modal('show');
    }

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
                        url: '<?php echo base_url('/concierge/delete-concierge-offer') ?>',
                        type: "post",
                        data: {
                            id: id,
                            '_method': 'delete'
                        },
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        dataType: 'json',
                        success: function(respn) {
                            if (respn['SUCCESS'] != 200) {
                                var ERROR = respn['RESPONSE']['ERROR'];

                                var mcontent = '';
                                $.each(ERROR, function(ind, data) {
                                    mcontent += '<li>' + data + '</li>';
                                });

                                showModalAlert('error', mcontent);
                            } else {
                                showModalAlert('warning', respn['RESPONSE']['REPORT_RES']);
                                $('#dataTable_view').dataTable().fnDraw();
                            }
                        }
                    });
                }
            }
        });
    });

    // $(document).on('click','.flxCheckBox',function(){
    //   var checked = $(this).is(':checked');
    //   var parent = $(this).parent();
    //   if(checked){
    //     parent.find('input[type=hidden]').val('Y');
    //   }else{
    //     parent.find('input[type=hidden]').val('N');
    //   }
    // });

    $(document).on('click', '.change-status', function() {
        let concierge_offer_id = $(this).attr('data_id');
        let status = $(this).attr('data_status');

        var url = '<?php echo base_url('/concierge/change-concierge-offer-status') ?>';
        $.ajax({
            url: url,
            type: "post",
            data: {
                id: concierge_offer_id,
                status: status,
            },
            dataType: 'json',
            success: function(respn) {
                if (respn['SUCCESS'] != 200) {
                    var ERROR = respn['RESPONSE']['ERROR'];

                    var mcontent = '';
                    $.each(ERROR, function(ind, data) {
                        mcontent += '<li>' + data + '</li>';
                    });

                    showModalAlert('error', mcontent);
                } else {
                    showModalAlert('warning', respn['RESPONSE']['REPORT_RES']);
                    $('#dataTable_view').dataTable().fnDraw();
                }

            }
        });
    });


    // Show Edit Rate Class Form
    $(document).on('click', '.editWindow', function() {
        resetConciergeOfferForm();
        $('.dtr-bs-modal').modal('hide');

        let id = "concierge-offer-form";
        let concierge_offer_id = $(this).attr('data_id');

        $('#popModalWindowlabel').html('Edit Concierge Offer');
        $('#popModalWindow').modal('show');

        $(`#${id} input[name='id']`).val(concierge_offer_id);

        var url = '<?php echo base_url('/concierge/edit-concierge-offer') ?>';
        $.ajax({
            url: url,
            type: "post",
            data: {
                id: concierge_offer_id
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
                            $(`#${id} select[name='${field}']`).select2('val', `${val}`);

                        if (field == 'CO_DESCRIPTION')
                            $("#snow-editor .ql-editor").html(val);
                    });
                });

                $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
            }
        });
    });

    function resetConciergeOfferForm() {
        let id = "concierge-offer-form";

        $(`#${id} input[type='hidden']`).val('');
        $(`#${id} input[type='text']`).val('');
        $(`#${id} input[type='email']`).val('');
        $(`#${id} input[type='number']`).val('');
        $(`#${id} input[type='file']`).val('');
        $(`#${id} select[name='CO_CURRENCY_ID']`).select2('val', `0`);

        $(`#${id} #CO_TAX_RATE`).val('5');
    }

    // Add New or Edit Rate Class submit Function
    function submitForm() {
        let id = "concierge-offer-form";
        hideModalAlerts();

        if ($("#snow-editor .ql-editor").html() != "<p><br></p>")
            $(`#${id} textarea[name='CO_DESCRIPTION']`).val($("#snow-editor .ql-editor").html());

        var fd = new FormData($(`#${id}`)[0]);
        fd.delete('CO_COVER_IMAGE');
        fd.delete('CO_PROVIDER_LOGO');
        fd.delete('CO_EXCLUSIVE_OR_INCLUSIVE');

        if($('#CO_EXCLUSIVE_OR_INCLUSIVE').is(':checked')) { // inclusive
            fd.append('CO_EXCLUSIVE_OR_INCLUSIVE', 'inclusive');
        }
        else { // exclusive
            fd.append('CO_EXCLUSIVE_OR_INCLUSIVE', 'exclusive');
        }

        files = $(`#${id} input[name='CO_COVER_IMAGE']`)[0].files;
        if (files.length)
            fd.append('CO_COVER_IMAGE', files[0]);

        files = $(`#${id} input[name='CO_PROVIDER_LOGO']`)[0].files;
        if (files.length)
            fd.append('CO_PROVIDER_LOGO', files[0]);

        $.ajax({
            url: '<?= base_url('/concierge/store-concierge-offer') ?>',
            type: "post",
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(respn) {
                
                var response = respn['SUCCESS'];
                if (response != '200') {

                    var ERROR = respn['RESPONSE']['ERROR'];
                    var mcontent = '';
                    $.each(ERROR, function(ind, data) {
                        mcontent += '<li>' + data + '</li>';
                    });
                    showModalAlert('error', mcontent);
                } else {

                    var alertText = respn['RESPONSE']['REPORT_RES'];

                    showModalAlert('success', alertText);

                    $('#popModalWindow').modal('hide');
                    resetConciergeOfferForm();
                    $('#dataTable_view').dataTable().fnDraw();
                }
            }
        });
    }

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