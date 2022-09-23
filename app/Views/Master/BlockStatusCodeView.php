<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>
<?=$this->include('Layout/ErrorReport')?>
<?=$this->include('Layout/SuccessReport')?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Masters /</span> Block Status Codes
        </h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid table-responsive" style="padding: 16px;">
                <table id="dataTable_view" class="dt-responsive table table-striped display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th class="all">Status</th>
                            <th></th>
                            <th class="all">Description</th>
                            <th>Room Status</th>
                            <th data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top"
                                data-bs-html="true" title="" data-bs-original-title="<span>Pickup</span>">P</th>
                            <th data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top"
                                data-bs-html="true" title="" data-bs-original-title="<span>Return</span>">R</th>
                            <th data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top"
                                data-bs-html="true" title="" data-bs-original-title="<span>Starting</span>">S</th>
                            <th data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top"
                                data-bs-html="true" title="" data-bs-original-title="<span>Lead</span>">L</th>
                            <th>Def. Res Type</th>
                            <th>Cancel Type</th>
                            <th>Seq</th>
                            <th class="all">Status</th>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">Block Status Code</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="submitForm" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <input type="hidden" name="BLK_STS_CD_ID" id="BLK_STS_CD_ID" class="form-control" />

                            <div class="border rounded p-3">

                                <div class="col-md-12">
                                    <div class="row mb-3">
                                        <label for="html5-text-input" class="col-form-label col-md-3"><b>Block Status
                                                Code
                                                *</b></label>
                                        <div class="col-md-3">
                                            <input type="text" name="BLK_STS_CD_CODE" id="BLK_STS_CD_CODE"
                                                class="form-control bootstrap-maxlength" maxlength="10"
                                                placeholder="eg: INQ" required />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="html5-text-input" class="col-form-label col-md-3"><b>Description
                                                *</b></label>
                                        <div class="col-md-7">
                                            <input type="text" name="BLK_STS_CD_DESC" id="BLK_STS_CD_DESC"
                                                class="form-control bootstrap-maxlength" maxlength="50"
                                                placeholder="eg: Inquiry" required />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="html5-text-input" class="col-form-label col-md-3"><b>Room Status
                                                Type
                                                *</b></label>
                                        <div class="col-md-4">
                                            <select id="RM_STATUS_TY_ID" name="RM_STATUS_TY_ID"
                                                class="select2 form-select form-select-lg" data-allow-clear="true"
                                                required>
                                                <?=$roomStatusTypeOptions?>
                                            </select>
                                        </div>

                                        <label for="html5-text-input"
                                            class="col-form-label col-md-2 cancelType d-none"><b>Cancel Type
                                                *</b></label>
                                        <div class="col-md-3 cancelType d-none">
                                            <select id="RM_CANCEL_TY_ID" name="RM_CANCEL_TY_ID"
                                                class="select2 form-select form-select-lg" data-allow-clear="true"
                                                required>
                                                <?=$cancelTypeOptions?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="html5-text-input" class="col-form-label col-md-3"><b>Default
                                                Reservation Type</b></label>
                                        <div class="col-md-5">
                                            <select id="RESV_TY_ID" name="RESV_TY_ID"
                                                class="select2 form-select form-select-lg" data-allow-clear="true"
                                                required>
                                                <?=$reservationTypeOptions?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="html5-text-input" class="col-form-label col-md-3"><b>Select Display
                                                Color *</b></label>
                                        <div class="col-md-4">
                                            <select id="CLR_ID" name="CLR_ID" class="select2 form-select form-select-lg"
                                                data-allow-clear="true">
                                                <?=$colorOptions?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="html5-text-input" class="col-form-label col-md-3"><b>Display
                                                Sequence *</b></label>
                                        <div class="col-md-3">
                                            <input type="number" name="BLK_STS_CD_DIS_SEQ" id="BLK_STS_CD_DIS_SEQ"
                                                class="form-control" min="0" placeholder="" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="border rounded p-3">
                                <div class="row mb-3">

                                    <div class="col-md-4">
                                        <label class="switch">
                                            <input id="BLK_STS_CD_ALLOW_PICKUP" name="BLK_STS_CD_ALLOW_PICKUP"
                                                type="checkbox" value="1" class="switch-input" />
                                            <span class="switch-toggle-slider">
                                                <span class="switch-on">
                                                    <i class="bx bx-check"></i>
                                                </span>
                                                <span class="switch-off">
                                                    <i class="bx bx-x"></i>
                                                </span>
                                            </span>
                                            <span class="switch-label">Allow Pickup</span>
                                        </label>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="switch">
                                            <input id="BLK_STS_CD_RETURN_INVENTORY" name="BLK_STS_CD_RETURN_INVENTORY"
                                                type="checkbox" value="1" class="switch-input" />
                                            <span class="switch-toggle-slider">
                                                <span class="switch-on">
                                                    <i class="bx bx-check"></i>
                                                </span>
                                                <span class="switch-off">
                                                    <i class="bx bx-x"></i>
                                                </span>
                                            </span>
                                            <span class="switch-label">Return Inventory</span>
                                        </label>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="switch">
                                            <input id="BLK_STS_CD_STARTING_STATUS" name="BLK_STS_CD_STARTING_STATUS"
                                                type="checkbox" value="1" class="switch-input" />
                                            <span class="switch-toggle-slider">
                                                <span class="switch-on">
                                                    <i class="bx bx-check"></i>
                                                </span>
                                                <span class="switch-off">
                                                    <i class="bx bx-x"></i>
                                                </span>
                                            </span>
                                            <span class="switch-label">Starting Status</span>
                                        </label>
                                    </div>

                                    <div class="col-md-12">&nbsp;</div>

                                    <div class="col-md-4">
                                        <label class="switch">
                                            <input id="BLK_STS_CD_LEAD_STATUS" name="BLK_STS_CD_LEAD_STATUS"
                                                type="checkbox" value="1" class="switch-input" />
                                            <span class="switch-toggle-slider">
                                                <span class="switch-on">
                                                    <i class="bx bx-check"></i>
                                                </span>
                                                <span class="switch-off">
                                                    <i class="bx bx-x"></i>
                                                </span>
                                            </span>
                                            <span class="switch-label">Lead Status</span>
                                        </label>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="switch">
                                            <input id="BLK_STS_CD_LOG_CATERING_CHANGES"
                                                name="BLK_STS_CD_LOG_CATERING_CHANGES" type="checkbox" value="1"
                                                class="switch-input" />
                                            <span class="switch-toggle-slider">
                                                <span class="switch-on">
                                                    <i class="bx bx-check"></i>
                                                </span>
                                                <span class="switch-off">
                                                    <i class="bx bx-x"></i>
                                                </span>
                                            </span>
                                            <span class="switch-label">Log Catering Changes</span>
                                        </label>
                                    </div>

                                </div>
                            </div>

                            <div class="text-center statusCheck">
                                <div class="col-md-12">
                                    <label class="switch switch-lg">
                                        <input id="BLK_STS_CD_STATUS" name="BLK_STS_CD_STATUS" type="checkbox" value="1"
                                            class="switch-input" />
                                        <span class="switch-toggle-slider">
                                            <span class="switch-on">
                                                <i class="bx bx-check"></i>
                                            </span>
                                            <span class="switch-off">
                                                <i class="bx bx-x"></i>
                                            </span>
                                        </span>
                                        <span class="switch-label">Active</span>
                                    </label>
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
$(document).ready(function() {

    $('#dataTable_view').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/blockStatusCodeView') ?>'
        },
        'columns': [{
                data: ''
            },
            {
                data: 'BLK_STS_CD_CODE'
            },
            {
                data: 'CLR_NAME'
            },
            {
                data: 'BLK_STS_CD_DESC'
            },
            {
                data: 'RM_STATUS_TY_CODE'
            },
            {
                data: 'BLK_STS_CD_ALLOW_PICKUP'
            },
            {
                data: 'BLK_STS_CD_RETURN_INVENTORY'
            },
            {
                data: 'BLK_STS_CD_STARTING_STATUS'
            },
            {
                data: 'BLK_STS_CD_LEAD_STATUS'
            },
            {
                data: 'RESV_TY_CODE'
            },
            {
                data: 'RM_CANCEL_TY_DESC'
            },
            {
                data: 'BLK_STS_CD_DIS_SEQ'
            },
            {
                data: 'BLK_STS_CD_STATUS'
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
                        '<li><a href="javascript:;" data_sysid="' + data['BLK_STS_CD_ID'] +
                        '" class="dropdown-item editWindow text-primary"><i class="fa-solid fa-pen-to-square"></i> Edit</a></li>' +
                        '<div class="dropdown-divider"></div>' +
                        '<li><a href="javascript:;" data_sysid="' + data['BLK_STS_CD_ID'] +
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
                width: "10%"
            }, {
                targets: 2,
                width: "6%",
                render: function(data, type, full, meta) {
                    var $color_name = full['CLR_NAME'];

                    return (
                        '<div style="background: ' + $color_name + ';padding: 18px;"></div>'
                    );
                }
            }, {
                width: "10%"
            }, {
                width: "7%"
            }, {
                width: "5%",
                targets: 5,
                render: function(data, type, full, meta) {
                    return showTickStatus(full['BLK_STS_CD_ALLOW_PICKUP']);
                }
            }, {
                width: "5%",
                targets: 6,
                render: function(data, type, full, meta) {
                    return showTickStatus(full['BLK_STS_CD_RETURN_INVENTORY']);
                }
            }, {
                width: "5%",
                targets: 7,
                render: function(data, type, full, meta) {
                    return showTickStatus(full['BLK_STS_CD_STARTING_STATUS']);
                }
            }, {
                width: "5%",
                targets: 8,
                render: function(data, type, full, meta) {
                    return showTickStatus(full['BLK_STS_CD_LEAD_STATUS']);
                }
            }, {
                width: "7%"
            }, {
                width: "7%"
            }, {
                width: "5%"
            }, {
                // Label
                targets: -2,
                width: "10%",
                render: function(data, type, full, meta) {
                    var $status_number = full['BLK_STS_CD_STATUS'];
                    var $status = {
                        0: {
                            title: 'Inactive',
                            class: 'bg-label-danger'
                        },
                        1: {
                            title: 'Active',
                            class: 'bg-label-success'
                        }
                    };
                    if (typeof $status[$status_number] === 'undefined') {
                        return data;
                    }
                    return (
                        '<span class="badge rounded-pill ' +
                        $status[$status_number].class +
                        '">' +
                        $status[$status_number].title +
                        '</span>'
                    );
                }
            },
            {
                width: "10%"
            }
        ],
        "order": [
            [5, "asc"]
        ],
        destroy: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal({
                    header: function(row) {
                        var data = row.data();
                        return 'Details of ' + data['BLK_STS_CD_CODE'];
                    }
                }),
                type: 'column',
                renderer: function(api, rowIdx, columns) {
                    var data = $.map(columns, function(col, i) {

                        var dispTitle = '';
                        switch (col.title) {
                            case 'P':
                                dispTitle = 'Pickup';
                                break;
                            case 'R':
                                dispTitle = 'Return';
                                break;
                            case 'S':
                                dispTitle = 'Starting';
                                break;
                            case 'L':
                                dispTitle = 'Lead';
                                break;

                            default:
                                dispTitle = col.title;
                                break;
                        }

                        return dispTitle !==
                            '' // ? Do not show row in modal popup if title is blank (for check box)
                            ?
                            '<tr data-dt-row="' +
                            col.rowIndex +
                            '" data-dt-column="' +
                            col.columnIndex +
                            '">' +
                            '<td>' +
                            dispTitle +
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

function showTickStatus(stat) {
    return stat == '1' ? '<i class="bx bx-check bx-sm text-success"></i>' : '';
}

$(document).on('change', '#RM_STATUS_TY_ID', function() {
    var rmStatId = $(this).val();

    if (rmStatId == '4') // Cancel
        $('.cancelType').removeClass('d-none');
    else {
        $('#RM_CANCEL_TY_ID').val(null).trigger('change');
        $('.cancelType').addClass('d-none');
    }
});


// Show Add Block Status Code Form

function addForm() {
    $(':input', '#submitForm').not('[type="radio"]').val('').prop('checked', false).prop('selected', false);
    $('.select2').val(null).trigger('change');

    $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    $('#popModalWindowlabel').html('Add New Block Status Code');
    $("#BLK_STS_CD_CODE").prop("readonly", false);
    $(".statusCheck").hide();
    $('#popModalWindow').modal('show');
    $("#BLK_STS_CD_STATUS").prop('checked', 'checked');
}

// Delete Block Status Code

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
                    url: '<?php echo base_url('/deleteBlockStatusCode') ?>',
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
                            '<li>The Block Status Code has been deleted</li>'
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


// Show Edit Block Status Code Form

$(document).on('click', '.editWindow', function() {

    $('.dtr-bs-modal').modal('hide');

    var sysid = $(this).attr('data_sysid');
    $('#popModalWindowlabel').html('Edit Block Status Code');

    $("#BLK_STS_CD_CODE").prop("readonly", true);
    $("#BLK_STS_CD_ADJ option").prop('disabled', false);

    $(".statusCheck").show();

    $('#popModalWindow').modal('show');

    var url = '<?php echo base_url('/editBlockStatusCode') ?>';
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

                    if ($('#' + field).hasClass('select2')) {
                        $('#' + field).select2("val", dataval);
                    } else if ($('#' + field).attr('type') == 'checkbox') {
                        $('#' + field).prop('checked', dataval == 1 ? true : false);
                    } else {
                        $('#' + field).val(dataval);
                    }

                });
            });
            $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
        }
    });
});


// Add New or Edit Block Status Code submit Function

function submitForm(id) {
    hideModalAlerts();
    var formSerialization = $('#' + id).serializeArray();
    var url = '<?php echo base_url('/insertBlockStatusCode') ?>';
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
                var alertText = $('#BLK_STS_CD_ID').val() == '' ? '<li>The new Block Status Code \'' +
                    $(
                        '#BLK_STS_CD_CODE')
                    .val() + '\' has been created</li>' : '<li>The Block Status Code \'' + $(
                        '#BLK_STS_CD_CODE').val() +
                    '\' has been updated</li>';
                showModalAlert('success', alertText);

                $('#popModalWindow').modal('hide');
                $('#dataTable_view').dataTable().fnDraw();
            }
        }
    });
}
</script>

<?=$this->endSection()?>