<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>
<?=$this->include('Layout/ErrorReport')?>
<?=$this->include('Layout/SuccessReport')?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Masters /</span> Membership Types
        </h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid table-responsive" style="padding: 16px;">
                <table id="dataTable_view" class="dt-responsive table table-striped display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th class="text-center">Membership Type</th>
                            <th class="all">Description</th>
                            <th>Sequence</th>
                            <th class="text-center">Status</th>
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
                    <h4 class="modal-title" id="popModalWindowlabel">Membership Type</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="submitForm" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <input type="hidden" name="MEM_ID" id="MEM_ID" class="form-control" />

                            <div class="border rounded p-3">

                                <div class="col-md-12">
                                    <div class="row mb-3">
                                        <label for="html5-text-input" class="col-form-label col-md-3"><b>Membership Type
                                                *</b></label>
                                        <div class="col-md-3">
                                            <input type="text" name="MEM_CODE" id="MEM_CODE"
                                                class="form-control bootstrap-maxlength" maxlength="10"
                                                placeholder="eg: EK" required />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="html5-text-input" class="col-form-label col-md-3"><b>Description
                                                *</b></label>
                                        <div class="col-md-7">
                                            <input type="text" name="MEM_DESC" id="MEM_DESC"
                                                class="form-control bootstrap-maxlength" maxlength="50"
                                                placeholder="eg: Emirates Airline" required />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="html5-text-input" class="col-form-label col-md-3">Display
                                            Sequence</label>
                                        <div class="col-md-3">
                                            <input type="number" name="MEM_DIS_SEQ" id="MEM_DIS_SEQ"
                                                class="form-control" min="0" placeholder="" />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="html5-text-input" class="col-form-label col-md-3">Card
                                            Length</label>
                                        <div class="col-md-3">
                                            <input type="number" name="MEM_CARD_LENGTH" id="MEM_CARD_LENGTH"
                                                class="form-control" min="0" placeholder="" />
                                        </div>

                                        <label for="html5-text-input" class="col-form-label col-md-3">Card
                                            Prefix</label>
                                        <div class="col-md-3">
                                            <input type="text" name="MEM_CARD_PREFIX" id="MEM_CARD_PREFIX"
                                                class="form-control" placeholder="" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="border rounded p-3">
                                <div class="row">

                                    <div class="col-md-6">
                                        <label class="switch">
                                            <input id="MEM_EXP_DATE_REQ" name="MEM_EXP_DATE_REQ" type="checkbox"
                                                value="1" class="switch-input" />
                                            <span class="switch-toggle-slider">
                                                <span class="switch-on">
                                                    <i class="bx bx-check"></i>
                                                </span>
                                                <span class="switch-off">
                                                    <i class="bx bx-x"></i>
                                                </span>
                                            </span>
                                            <span class="switch-label">Expiration Date Required</span>
                                        </label>
                                    </div>

                                    <div class="col-md-12">&nbsp;</div>

                                </div>
                            </div>

                            <div class="text-center statusCheck">
                                <div class="col-md-12">
                                    <label class="switch switch-lg">
                                        <input id="MEM_STATUS" name="MEM_STATUS" type="checkbox" value="1"
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

    <div class="modal fade" id="copyModalWindow" data-backdrop="static" data-keyboard="false"
        aria-lableledby="copyModalWindowlable">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="copyModalWindowlabel">Create Rate Category Copies</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>
                <div class="modal-body">

                    <form id="copyForm" class="form-repeater needs-validation" novalidate>
                        <div data-repeater-list="group-a">
                            <div data-repeater-item>
                                <div class="row">
                                    <div class="col-12 col-md-3 mb-0 mb-3">
                                        <label class="form-label" for="form-repeater-1-1"><b>Membership Type
                                                *</b></label>
                                        <input type="text" name="MEM_CODE" id="form-repeater-1-1" class="form-control"
                                            maxlength="10" required />
                                    </div>

                                    <div class="col-12 col-md-6 mb-0 mb-3">
                                        <label class="form-label" for="form-repeater-1-2"><b>Description
                                                *</b></label>
                                        <input type="text" name="MEM_DESC" id="form-repeater-1-2" class="form-control"
                                            maxlength="50" required />
                                    </div>

                                    <div class="d-flex col-12 col-md-3 align-items-center mb-0 mb-3">
                                        <button class="btn btn-label-danger mt-4" data-repeater-delete>
                                            <i class="bx bx-x"></i>
                                            <span class="align-left">Delete</span>
                                        </button>
                                    </div>
                                </div>
                                <hr />
                            </div>
                        </div>
                        <div class="mb-0" style="float: left;">
                            <button class="btn btn-primary" data-repeater-create>
                                <i class="bx bx-plus"></i>
                                <span class="align-middle">Add New</span>
                            </button>
                        </div>
                        <div style="float: right;">
                            <input type="hidden" name="main_MEM_ID" id="main_MEM_ID" class="form-control" />
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" id="copyBtn" onClick="copyForm('copyForm')"
                                class="btn btn-primary">Save</button>
                        </div>
                    </form>
                    <div class="form-text" style="clear: both; padding-top: 20px;">Rate Category Description & Dates
                        will be copied from original </div>

                </div>

            </div>
        </div>
    </div>

    <!-- /Modal window -->

    <div class="content-backdrop fade"></div>
</div>

<!-- Content wrapper -->
<script>
var compAgntMode = '';
var linkMode = '';

$(document).ready(function() {
    linkMode = 'EX';

    jQuery.fn.dataTableExt.oSort['string-num-asc'] = function(x1, y1) {
        var x = x1;
        var y = y1;
        var pattern = /[0-9]+/g;
        var matches;
        if (x1.length !== 0) {
            matches = x1.match(pattern);
            x = matches[0];
        }
        if (y1.length !== 0) {
            matches = y1.match(pattern);
            y = matches[0];
        }
        return ((x < y) ? -1 : ((x > y) ? 1 : 0));

    };

    jQuery.fn.dataTableExt.oSort['string-num-desc'] = function(x1, y1) {

        var x = x1;
        var y = y1;
        var pattern = /[0-9]+/g;
        var matches;
        if (x1.length !== 0) {
            matches = x1.match(pattern);
            x = matches[0];
        }
        if (y1.length !== 0) {
            matches = y1.match(pattern);
            y = matches[0];
        }
        $("#debug").html('x=' + x + ' y=' + y);
        return ((x < y) ? 1 : ((x > y) ? -1 : 0));

    };

    $('#dataTable_view').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/membershipTypeView') ?>'
        },
        'columns': [{
                data: ''
            },
            {
                data: 'MEM_CODE',
                className: "text-center"
            },
            {
                data: 'MEM_DESC'
            },
            {
                data: 'MEM_DIS_SEQ',
                className: "text-center"
            },
            {
                data: 'MEM_STATUS',
                className: "text-center"
            },
            {
                data: null,
                className: "text-center",
                "orderable": false,
                render: function(data, type, row, meta) {
                    return (
                        '<div class="d-inline-block">' +
                        '<a href="javascript:;" title="Edit or Delete" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                        '<ul class="dropdown-menu dropdown-menu-end">' +
                        '<li><a href="javascript:;" data_sysid="' + data['MEM_ID'] +
                        '" class="dropdown-item editWindow text-primary"><i class="fa-solid fa-pen-to-square"></i> Edit</a></li>' +
                        '<div class="dropdown-divider"></div>' +
                        '<li><a href="javascript:;" data_sysid="' + data['MEM_ID'] +
                        '" data_memcode="' + data['MEM_DESC'] +
                        '" class="dropdown-item copyWindow text-success"><i class="fa-solid fa-copy"></i> Copy</a></li>' +
                        '<div class="dropdown-divider"></div>' +
                        '<li><a href="javascript:;" data_sysid="' + data['MEM_ID'] +
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
            width: "8%"
        }, {
            width: "35%"
        }, {
            width: "10%"
        }, {
            // Label
            targets: -2,
            width: "20%",
            render: function(data, type, full, meta) {
                var $status_number = full['MEM_STATUS'];
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
        }, {
            width: "10%"
        }],
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
                        return 'Details of Membership Type ' + data['MEM_CODE'];
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
        },

    });
    $("#dataTable_view_wrapper .row:first").before(
        '<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" onClick="addForm()"><i class="fa-solid fa-plus fa-lg"></i> Add</button></div></div>'
    );
});

function hideModalAlerts() {
    $('#errorModal').hide();
    $('#successModal').hide();
    $('#warningModal').hide();
}

function showModalAlert(modalType, modalContent) {
    $('#' + modalType + 'Modal').show();
    $('#form' + modalType.charAt(0).toUpperCase() + modalType.slice(1) + 'Message').html('<ul>' + modalContent +
        '</ul>');
}

// Show Add Membership Type Form

function addForm() {
    $(':input', '#submitForm').not('[type="radio"]').val('').prop('checked', false).prop('selected', false);
    $('.select2').val(null).trigger('change');

    $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    $('#popModalWindowlabel').html('Add New Membership Type');
    $("#MEM_CODE").prop("readonly", false);
    $(".statusCheck").hide();
    $('#popModalWindow').modal('show');
    $("#MEM_STATUS").prop('checked', 'checked');
}

// Delete Membership Type

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
                    url: '<?php echo base_url('/deleteMembershipType') ?>',
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
                            '<li>The Membership Type has been deleted</li>'
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


// Show Edit Membership Type Form

$(document).on('click', '.editWindow', function() {

    $('.dtr-bs-modal').modal('hide');

    var sysid = $(this).attr('data_sysid');
    $('#popModalWindowlabel').html('Edit Membership Type');

    $("#MEM_CODE").prop("readonly", true);

    $(".statusCheck").show();

    $('#popModalWindow').modal('show');

    var url = '<?php echo base_url('/editMembershipType') ?>';
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

                    if ($('#' + field).attr('type') == 'checkbox') {
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


// Show Copy Rate Category Form

$(document).on('click', '.copyWindow', function() {

    $('.dtr-bs-modal').modal('hide');

    var sysid = $(this).attr('data_sysid');
    var memcode = $(this).attr('data_memcode');

    $('#main_MEM_ID').val(sysid);

    $('#copyModalWindowlabel').html('Create Membership Type Copies of \'' + memcode + '\'');

    //Reset repeated fields every time modal is loaded
    $('[data-repeater-item]').slice(1).empty();
    $('#form-repeater-1-1').val("");

    $('#copyModalWindow').modal('show');

});


// Add New or Edit Membership Type submit Function

function submitForm(id) {
    hideModalAlerts();
    var formSerialization = $('#' + id).serializeArray();
    var url = '<?php echo base_url('/insertMembershipType') ?>';
    $.ajax({
        url: url,
        type: "post",
        data: formSerialization,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        success: function(respn) {
            console.log(respn, "testing");
            var response = respn['SUCCESS'];
            if (response != '1') {
                var ERROR = respn['RESPONSE']['ERROR'];
                var mcontent = '';
                $.each(ERROR, function(ind, data) {
                    console.log(data, "SDF");
                    mcontent += '<li>' + data + '</li>';
                });
                showModalAlert('error', mcontent);
            } else {
                var alertText = $('#MEM_ID').val() == '' ? '<li>The new Membership Type \'' +
                    $(
                        '#MEM_CODE')
                    .val() + '\' has been created</li>' : '<li>The Membership Type \'' + $(
                        '#MEM_CODE').val() +
                    '\' has been updated</li>';
                showModalAlert('success', alertText);

                $('#popModalWindow').modal('hide');
                $('#dataTable_view').dataTable().fnDraw();
            }
        }
    });
}

// Copy Rate Category to Multiple submit Function

function copyForm(id) {
    hideModalAlerts();

    var formSerialization = $('#' + id).serializeArray();
    var url = '<?php echo base_url('/copyMembershipType')?>';
    $.ajax({
        url: url,
        type: "post",
        data: formSerialization,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        success: function(response) {
            if (response == '0') {
                showModalAlert('error', '<li>No New Membership Types were added</li>');
            } else {
                showModalAlert('success', '<li>' + response +
                    ' new Membership Type copies have been created</li>');
                $('#copyModalWindow').modal('hide');
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

<?=$this->endSection()?>