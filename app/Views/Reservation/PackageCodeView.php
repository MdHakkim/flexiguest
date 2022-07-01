<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>

<style>
.tagify__input {
    padding-left: 6px;
}

.table-hover>tbody>tr:hover {
    cursor: pointer;
}

.table-warning {
    color: #000 !important;
}
</style>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">

        <?=$this->include('Layout/ErrorReport')?>
        <?=$this->include('Layout/SuccessReport')?>

        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Masters /</span> Package Codes
        </h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid table-responsive" style="padding: 16px;">
                <table id="dataTable_view" class="dt-responsive table table-striped display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Pkg. Code</th>
                            <th>Description</th>
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

    <div class="modal fade" id="popModalWindow" data-backdrop="static" data-keyboard="false"
        aria-labelledby="popModalWindowlabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">Package Code</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div id="wizard-validation" class="bs-stepper mt-2">
                        <div class="bs-stepper-header">
                            <div class="step" data-target="#package-header-validation">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-circle">1</span>
                                    <span class="bs-stepper-label">Package Header</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            <div class="step" data-target="#package-detail-validation">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-circle">2</span>
                                    <span class="bs-stepper-label">Package Detail</span>
                                </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content">

                            <form id="packageCode-submit-form" onSubmit="return false">

                                <div id="package-header-validation" class="content">

                                    <div class="row g-3">
                                        <input type="hidden" name="PKG_CD_ID" id="PKG_CD_ID" />

                                        <div class="border rounded p-3">

                                            <div class="col-md-12">
                                                <div class="row mb-3">
                                                    <label for="html5-text-input"
                                                        class="col-form-label col-md-3"><b>Code
                                                            *</b></label>
                                                    <div class="col-md-3">
                                                        <input type="text" name="PKG_CD_CODE" id="PKG_CD_CODE"
                                                            class="form-control bootstrap-maxlength textField" maxlength="10"
                                                            placeholder="eg: 1001" required />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="html5-text-input" class="col-form-label col-md-3">Short
                                                        Description</label>
                                                    <div class="col-md-5">
                                                        <input type="text" name="PKG_CD_SHORT_DESC"
                                                            id="PKG_CD_SHORT_DESC"
                                                            class="form-control bootstrap-maxlength" maxlength="50"
                                                            placeholder="eg: Online Travel Agent" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="html5-text-input"
                                                        class="col-form-label col-md-3"><b>Description
                                                            *</b></label>
                                                    <div class="col-md-7">
                                                        <input type="text" name="PKG_CD_DESC" id="PKG_CD_DESC"
                                                            class="form-control bootstrap-maxlength textField" maxlength="50"
                                                            placeholder="eg: Online Travel Agent" />
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="border rounded p-3">
                                            <h6>Transaction Details</h6>
                                            <div class="row g-3 mb-3">
                                                <label for="TR_CD_ID" class="col-form-label col-md-3"><b>Transaction
                                                        Code *</b></label>
                                                <div class="col-md-4">
                                                    <select id="TR_CD_ID" name="TR_CD_ID"
                                                        class="select2 form-select form-select-lg">
                                                        <?=$transactionCodeOptions?>
                                                    </select>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="switch">
                                                        <input type="checkbox" class="switch-input"
                                                            id="PKG_CD_TAX_INCLUDED" name="PKG_CD_TAX_INCLUDED"
                                                            value="1" />
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bx-check"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                                <i class="bx bx-x"></i>
                                                            </span>
                                                        </span>
                                                        <span class="switch-label">Tax Included</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="border rounded p-3">
                                            <h6>Attributes</h6>
                                            <div class="row mb-3">

                                                <div class="col-md-4">

                                                    <?php if($rateInclusionRules != NULL) { 
                                                            foreach($rateInclusionRules as $rateInclusionRule) { ?>

                                                    <div class="form-check mt-3">
                                                        <input class="form-check-input" type="radio"
                                                            value="<?=$rateInclusionRule['value']?>"
                                                            id="RT_INCL_ID<?=$rateInclusionRule['value']?>"
                                                            name="RT_INCL_ID"
                                                            <?php if($rateInclusionRule['value'] == 1) echo ' checked'; ?> />
                                                        <label class="form-check-label"
                                                            for="RT_INCL_ID<?=$rateInclusionRule['value']?>">
                                                            <?=$rateInclusionRule['name']?> </label>
                                                    </div>
                                                    <?php   }
                                                    }   
                                                    ?>
                                                </div>

                                                <div class="col-md-8">
                                                    <div class="row mb-3">

                                                        <label for="PO_RH_ID" class="col-form-label col-md-4"
                                                            style="text-align: right;"><b>Posting Rhythm *</b></label>
                                                        <div class="col-md-8">
                                                            <select id="PO_RH_ID" name="PO_RH_ID"
                                                                class="select2 form-select form-select-lg">
                                                                <?php
                                                            if($postingRhythmOptions != NULL) {
                                                                foreach($postingRhythmOptions as $postingRhythmOption)
                                                                {
                                                        ?> <option value="<?=$postingRhythmOption['value']; ?>">
                                                                    <?=$postingRhythmOption['name']; ?>
                                                                </option>
                                                                <?php   }
                                                            }                                                            
                                                        ?>
                                                            </select>
                                                        </div>

                                                    </div>

                                                    <div class="row mb-3">

                                                        <label for="CLC_RL_ID" class="col-form-label col-md-4"
                                                            style="text-align: right;"><b>Calculation Rule *</b></label>
                                                        <div class="col-md-8">
                                                            <select id="CLC_RL_ID" name="CLC_RL_ID"
                                                                class="select2 form-select form-select-lg">
                                                                <?php
                                                            if($calcInclusionRules != NULL) {
                                                                foreach($calcInclusionRules as $calcInclusionRule)
                                                                {
                                                        ?> <option value="<?=$calcInclusionRule['value']; ?>">
                                                                    <?=$calcInclusionRule['name']; ?>
                                                                </option>
                                                                <?php   }
                                                            }                                                            
                                                        ?>
                                                            </select>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label class="switch">
                                                <input id="PKG_CD_SELL_SEP" name="PKG_CD_SELL_SEP" type="checkbox"
                                                    value="1" class="switch-input" />
                                                <span class="switch-toggle-slider">
                                                    <span class="switch-on">
                                                        <i class="bx bx-check"></i>
                                                    </span>
                                                    <span class="switch-off">
                                                        <i class="bx bx-x"></i>
                                                    </span>
                                                </span>
                                                <span class="switch-label">Sell Separately</span>
                                            </label>
                                        </div>

                                        <div class="d-flex col-12 justify-content-between">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>

                                            <button type="button" onclick="submitForm('packageCode-submit-form')"
                                                class="btn btn-success saveBtn">
                                                <i class="fa-solid fa-floppy-disk"></i>&nbsp; Save
                                            </button>

                                            <button type="button" class="btn btn-primary btn-next">
                                                <span class="d-none d-sm-inline-block me-sm-1">Next</span>
                                                <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
                                            </button>
                                        </div>

                                    </div>
                                </div>

                                <div id="package-detail-validation" class="content">

                                    <div class="row g-3">

                                        <div class="col-md-5">
                                            <div class="border rounded p-4 mb-3">

                                                <div class="row mb-3">
                                                    <label for="PKG_CD_START_DT"
                                                        class="col-form-label col-md-4"><b>Start
                                                            Date *</b></label>
                                                    <div class="col-md-8">
                                                        <input class="form-control dateField" type="text"
                                                            placeholder="d-Mon-yyyy" id="PKG_CD_START_DT"
                                                            name="PKG_CD_START_DT" />
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="PKG_CD_END_DT" class="col-form-label col-md-4"><b>End
                                                            Date *</b></label>
                                                    <div class="col-md-8">
                                                        <input class="form-control dateField" type="text"
                                                            placeholder="d-Mon-yyyy" id="PKG_CD_END_DT"
                                                            name="PKG_CD_END_DT" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="PKG_CD_DT_PRICE"
                                                        class="col-form-label col-md-4"><b>Price *</b></label>
                                                    <div class="col-md-8">
                                                        <input type="number" name="PKG_CD_DT_PRICE" id="PKG_CD_DT_PRICE"
                                                            class="form-control" min="0.00" step=".01"
                                                            placeholder="eg: 430.50" />
                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                        <div class="col-md-7">

                                            <div class="border rounded p-4 mb-3">

                                                <div class="table-responsive text-nowrap">
                                                    <table id="PKG_CD_Details" class="table table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="all">Start</th>
                                                                <th class="all">End</th>
                                                                <th class="all">Price</th>
                                                                <th class="all">Active</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>

                                                <br />

                                                <input type="hidden" name="PKG_CD_DT_ID" id="PKG_CD_DT_ID" readonly />

                                                <button type="button" class="btn btn-primary add-package-code-detail">
                                                    <i class="fa-solid fa-circle-plus"></i>&nbsp; Add New
                                                </button>&nbsp;

                                                <button type="button" class="btn btn-success save-package-code-detail">
                                                    <i class="fa-solid fa-floppy-disk"></i>&nbsp; Save
                                                </button>&nbsp;

                                                <button type="button" class="btn btn-danger delete-package-code-detail">
                                                    <i class="fa-solid fa-ban"></i>&nbsp; Delete
                                                </button>&nbsp;


                                            </div>



                                        </div>

                                        <div class="d-flex col-12 justify-content-between">

                                            <button class="btn btn-primary btn-prev">
                                                <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                                                <span class="d-none d-sm-inline-block">Previous</span>
                                            </button>

                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                        </div>

                                    </div>

                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- /Modal window -->

    <div class="content-backdrop fade"></div>
</div>

<!-- Content wrapper -->
<script>
$(document).ready(function() {

    $('.dateField').datepicker({
        format: 'dd-M-yyyy',
        autoclose: true,
        onSelect: function() {
            $(this).change();
        }
    });

    $('#dataTable_view').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/packageCodeView') ?>'
        },
        'columns': [{
                data: ''
            },
            {
                data: 'PKG_CD_CODE'
            },
            {
                data: 'PKG_CD_DESC'
            },
            {
                data: null,
                className: "text-center",
                "orderable": false,
                render: function(data, type, row, meta) {
                    return (
                        '<div class="d-inline-block">' +
                        '<a href="javascript:;" title="Edit or Delete" class="btn btn-sm btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                        '<ul class="dropdown-menu dropdown-menu-end">' +
                        '<li><a href="javascript:;" data_sysid="' + data['PKG_CD_ID'] +
                        '" class="dropdown-item editWindow text-primary"><i class="fa-solid fa-pen-to-square"></i> Edit</a></li>' +
                        '<div class="dropdown-divider"></div>' +
                        '<li><a href="javascript:;" data_sysid="' + data['PKG_CD_ID'] +
                        '" class="dropdown-item text-danger delete-record"><i class="fa-solid fa-ban"></i> Delete</a></li>' +
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
        }, {
            width: "20%"
        }, {
            width: "25%"
        }, {
            width: "35%"
        }, {
            width: "13%"
        }],
        "order": [
            [1, "asc"]
        ],
        destroy: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal({
                    header: function(row) {
                        var data = row.data();
                        return 'Details of ' + data['PKG_CD_CODE'];
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
        '<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary addWindow" onClick="addForm()"><i class="fa-solid fa-plus fa-lg"></i> Add</button></div></div>'
    );

});



// Show Add Package Code Form

function addForm() {
    $(':input', '#packageCode-submit-form').not('[type="radio"]').val('').prop('checked', false).prop('selected',
        false);

    if ($('.select2').val() != '')
        $('.select2').val(null).trigger('change');

    //$('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');

    $('#popModalWindowlabel').html('Add New Package Code');
    $("#PKG_CD_CODE").prop("readonly", false);

    $('#popModalWindow').modal('show');

    $('#PO_RH_ID,#CLC_RL_ID').val('1').trigger('change');
    $("#PKG_CD_SELL_SEP").prop('checked', 'checked');
}

function show_status_checkbox(id, status) {
    var checked = status ? "checked" : "";
    var checkHTML = '<label class="switch"><input id="PKG_CD_DT_STATUS_' + id + '" name="PKG_CD_DT_STATUS_' + id +
        '" type="checkbox" value="1" class="switch-input" ' + checked +
        ' /><span class="switch-toggle-slider"><span class="switch-on"><i class="bx bx-check"></i></span><span class="switch-off"><i class="bx bx-x"></i></span></span></label>';
    return '';
}

// Delete Package Code

$(document).on('click', '.delete-record', function() {
    hideModalAlerts();
    $('.dtr-bs-modal').modal('hide');

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
                    url: '<?php echo base_url('/deletePackageCode') ?>',
                    type: "post",
                    data: {
                        sysid: sysid
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    dataType: 'json',
                    success: function(respn) {
                        showModalAlert('warning',
                            '<li>The Package Code has been deleted</li>'
                        );
                        $('#dataTable_view').dataTable().fnDraw();
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

function showPackageCodeDetails(pkgCodeID, dtID = 0) {
    $('#PKG_CD_Details').find('tr.table-warning').removeClass('table-warning');

    $('#PKG_CD_Details').DataTable({
        'processing': true,
        async: false,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/packageCodeDetailsView') ?>',
            'data': {
                "sysid": pkgCodeID
            }
        },
        'columns': [{
                data: 'PKG_CD_START_DT'
            },
            {
                data: 'PKG_CD_END_DT'
            },
            {
                data: 'PKG_CD_DT_PRICE'
            },
            {
                data: null
            },
        ],
        columnDefs: [{
            // Label
            targets: -1,
            render: function(data, type, full, meta) {
                var detailID = data['PKG_CD_DT_ID'];
                var statusCheck = full['PKG_CD_DT_STATUS'];
                return show_status_checkbox(detailID, statusCheck);
            }
        }],
        'createdRow': function(row, data, dataIndex) {
            $(row).attr('data-packagedetailsid', data['PKG_CD_DT_ID']);
            $(row).attr('data-packagecodeid', pkgCodeID);

            if (dtID != 0) {
                if (data['PKG_CD_DT_ID'] == dtID) {
                    $(row).addClass('table-warning');
                    loadPackageCodeDetails(pkgCodeID, dtID);
                }
            } else if (dataIndex == 0) {
                $(row).addClass('table-warning');
                loadPackageCodeDetails(pkgCodeID, data['PKG_CD_DT_ID']);
            }
        },
        destroy: true,
        "ordering": false,
        "searching": false,
        autowidth: true,
        responsive: true
    });
}


// Show Edit Package Code Form

$(document).on('click', '.editWindow', function() {

    $('.dtr-bs-modal').modal('hide');

    var sysid = $(this).attr('data_sysid');
    $('#PKG_CD_ID').val(sysid);

    $('#popModalWindowlabel').html('Edit Package Code');

    $("#PKG_CD_CODE").prop("readonly", true);

    $('#popModalWindow').modal('show');

    $('#package-detail-validation').find('.btn-prev').trigger('click'); // Go to first tab

    var url = '<?php echo base_url('/editPackageCode') ?>';
    $.ajax({
        url: url,
        async: false,
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

                    if (field == 'TR_CD_ID' || field == 'PO_RH_ID' || field ==
                        'CLC_RL_ID') {
                        $('#' + field).select2("val", dataval);
                    } else if ($('#' + field).attr('type') == 'checkbox') {
                        $('#' + field).prop('checked', dataval == 1 ? true : false);
                    } else if ($('#' + field + dataval).attr('type') == 'radio') {
                        $('#' + field + dataval).prop('checked', true);
                    } else {
                        $('#' + field).val(dataval);
                    }

                });
            });

            //$('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
        }
    });
});


// Add New or Edit Package Code submit Function

function submitForm(id) {
    hideModalAlerts();
    var formSerialization = $('#' + id).serializeArray();
    var url = '<?php echo base_url('/insertPackageCode') ?>';
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
                var alertText = $('#PKG_CD_ID').val() == '' ? '<li>The new Package Code \'' +
                    $(
                        '#PKG_CD_CODE')
                    .val() + '\' has been created</li>' : '<li>The Package Code \'' + $(
                        '#PKG_CD_CODE').val() +
                    '\' has been updated</li>';

                showModalAlert('success', alertText);

                $('#popModalWindowlabel').html('Edit Package Code');

                //$('#popModalWindow').modal('hide');

                var pkgCodeID = respn['RESPONSE']['OUTPUT'];
                showPackageCodeDetails(pkgCodeID);
            }
        }
    });
}


// Show Package Code Detail

function loadPackageCodeDetails(packageCodeID, id) {

    var url = '<?php echo base_url('/showPackageCodeDetails')?>';
    $.ajax({
        url: url,
        type: "get",
        async: false,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            sysid: packageCodeID,
            dtID: id
        },
        dataType: 'json',
        success: function(respn) {

            //Enable Repeat and Delete buttons
            toggleButton('.delete-package-code-detail', 'btn-dark', 'btn-danger', false);

            $(respn).each(function(inx, data) {
                $.each(data, function(fields, datavals) {
                    var field = $.trim(fields); //fields.trim();
                    var dataval = $.trim(datavals); //datavals.trim();

                    if (field == 'PKG_CD_START_DT' || field ==
                        'PKG_CD_END_DT') {
                        $('#' + field).datepicker("setDate", new Date(dataval));
                    } else {
                        $('#' + field).val(dataval);
                    }
                });
            });
        }
    });
}


// Add new Package Code Detail

$(document).on('click', '.add-package-code-detail', function() {
    hideModalAlerts();
    $('.dtr-bs-modal').modal('hide');

    bootbox.dialog({
        message: "Do you want to add a new Package Code Detail?",
        buttons: {
            ok: {
                label: 'Yes',
                className: 'btn-success',
                callback: function(result) {
                    if (result) {
                        clearFormFields('#package-detail-validation');
                        $('#PKG_CD_Details').find('tr.table-warning').removeClass('table-warning');

                        //Disable Delete button
                        toggleButton('.delete-package-code-detail', 'btn-danger', 'btn-dark', true);

                        showModalAlert('info',
                            'Fill in the form and click the \'Save\' button to add the new Package Detail'
                        );
                    }
                }
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        }
    });
});


// Update existing Package Code Detail

$(document).on('click', '.save-package-code-detail', function() {

    submitDetailsForm('packageCode-submit-form');
});


// Add / Edit Package Code Detail

function submitDetailsForm(id) {
    hideModalAlerts();
    var formSerialization = $('#' + id).serializeArray();
    var url = '<?php echo base_url('/updatePackageCodeDetail')?>';
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

                blockLoader('#package-detail-validation');

                var alertText = $('#PKG_CD_DT_ID').val() == '' ?
                    '<li>The new Package Code Detail has been created</li>' :
                    '<li>The Package Code Detail has been updated</li>';

                showModalAlert('success', alertText);

                if (respn['RESPONSE']['OUTPUT'] != '') {

                    $('#PKG_CD_DT_ID').val(respn['RESPONSE']['OUTPUT']);

                    showPackageCodeDetails($('#PKG_CD_ID').val(), $('#PKG_CD_DT_ID').val());
                }
            }
        }
    });
}

// Delete Package Code Detail

$(document).on('click', '.delete-package-code-detail', function() {
    hideModalAlerts();
    $('.dtr-bs-modal').modal('hide');

    var sysid = $('#PKG_CD_Details').find("tr.table-warning").data("packagedetailsid");

    bootbox.confirm({
        message: "Package Code Detail is active. Do you want to Delete?",
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
                    url: '<?php echo base_url('/deletePackageCodeDetail')?>',
                    type: "post",
                    data: {
                        sysid: sysid,
                        packageCodeID: $('#PKG_CD_ID').val()
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    dataType: 'json',
                    success: function(respn) {
                        var response = respn['SUCCESS'];
                        if (response == '0') {
                            showModalAlert('error',
                                '<li>The Package Code Detail cannot be deleted</li>');
                        } else {
                            blockLoader('#package-detail-validation');
                            showModalAlert('warning',
                                '<li>The Package Code Detail has been deleted</li>');

                            showPackageCodeDetails($('#PKG_CD_ID').val());
                        }
                    }
                });
            }
        }
    });
});

// Display function toggleButton
<?php echo isset($toggleButton_javascript) ? $toggleButton_javascript : ''; ?>

// Display function clearFormFields
<?php echo isset($clearFormFields_javascript) ? $clearFormFields_javascript : ''; ?>

// Display function blockLoader
<?php echo isset($blockLoader_javascript) ? $blockLoader_javascript : ''; ?>
</script>

<?=$this->endSection()?>