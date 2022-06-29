<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Users /</span> User Role</h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid table-responsive" style="padding: 16px 16px 6px 16px">
                <table id="dataTable_view" class="dt-responsive table table-striped display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Role Code</th>
                            <th>Role Name</th>  
                            <th>Role Description</th>  
                            <th>Seq.</th>
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
        aria-lableledby="popModalWindowlable">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">User Role</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="submitForm" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <input type="hidden" name="ROLE_ID" id="ROLE_ID" class="form-control" />
                            <div class="col-md-6">
                                <label class="form-label"><b>Role Code *</b></label>
                                <input type="text" name="ROLE_CODE" id="ROLE_CODE"
                                    class="form-control bootstrap-maxlength" maxlength="10" placeholder="eg: GST_ABC"
                                    required />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><b>Role Name *</b></label>
                                <input type="text" name="ROLE_NAME" id="ROLE_NAME"
                                    class="form-control bootstrap-maxlength" maxlength="50"
                                    placeholder="eg: Accompanying Guests" required />
                            </div>
                            <div class="col-md-12">
                                <label class="form-label"><b>Role Description *</b></label>
                                <textarea class="form-control" name="ROLE_DESC" id="ROLE_DESC" rows="1"></textarea> 
                                
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><b>Display Sequence</b></label>
                                <input type="number" name="ROLE_DIS_SEQ" id="ROLE_DIS_SEQ" class="form-control"
                                    min="0" placeholder="eg: 3" />
                            </div>      
                            <div class="col-md-4">
                            <div class="text-left mt-4">
                                <div class="col-md-12">
                                    <label class="switch switch-lg">
                                        <input id="ROLE_STATUS" name="ROLE_STATUS" type="checkbox" value="1"
                                            class="switch-input" />
                                        <span class="switch-toggle-slider">
                                            <span class="switch-on">
                                                <i class="bx bx-check"></i>
                                            </span>
                                            <span class="switch-off">
                                                <i class="bx bx-x"></i>
                                            </span>
                                        </span>
                                        <span class="switch-label"><b>Active</b></span>
                                    </label>
                                </div>
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
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="copyModalWindowlabel">Create Role Copies</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>
                <div class="modal-body">

                    <form id="copyForm" class="form-repeater needs-validation" novalidate>
                        <div data-repeater-list="group-a">
                            <div data-repeater-item>
                                <div class="row">
                                    <div class="col-12 col-md-8 mb-0 mb-3">
                                        <label class="form-label" for="form-repeater-1-1"><b>Role Code
                                                *</b></label>
                                        <input type="text" name="ROLE_CODE" id="form-repeater-1-1" class="form-control"
                                            maxlength="10" placeholder="eg: GST_XYZ" required />
                                    </div>

                                    <div class="d-flex col-12 col-md-4 align-items-center mb-0 mb-3">
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
                            <input type="hidden" name="main_Role_ID" id="main_Role_ID" class="form-control" />
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" id="copyBtn" onClick="copyForm('copyForm')"
                                class="btn btn-primary">Save</button>
                        </div>
                    </form>
                    <div class="form-text" style="clear: both; padding-top: 20px;">Role Description will
                        be copied from original </div>

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

    $('#dataTable_view').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/userRoleView')?>'
        },
        'columns': [{
                data: ''
            },
            {
                data: 'ROLE_CODE'
            },
            {
                data: 'ROLE_NAME'
            },
            {
                data: 'ROLE_DESC'
            },
            
            {
                data: 'ROLE_DIS_SEQ'
            },
            {
                data: null,
                "orderable": false,
                render: function(data, type, row, meta) {
                    return (
                        '<div class="d-inline-block">' +
                        '<a href="javascript:;" title="Edit or Delete" class="btn btn-label-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                        '<ul class="dropdown-menu dropdown-menu-end">' +
                        '<li><a href="javascript:;" data_sysid="' + data['ROLE_ID'] +
                        '" class="dropdown-item editWindow text-primary"><i class="fa-solid fa-pen-to-square"></i> Edit</a></li>' +
                        '<div class="dropdown-divider"></div>' +
                        '<li><a href="javascript:;" data_sysid="' + data['ROLE_ID'] +
                        '" data_rolecode="' + data['ROLE_CODE'] +
                        '" class="dropdown-item copyWindow text-success"><i class="fa-solid fa-copy"></i> Copy</a></li>' +
                        '<div class="dropdown-divider"></div>' +
                        '<li><a href="javascript:;" data_sysid="' + data['ROLE_ID'] +
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
            width: "15%"
        }, {
            width: "18%"
        }, {
            width: "8%"
        }, {
            width: "8%"
        
        }, {
            width: "8%"
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
                        return 'Details of ' + data['GST_TYPE_CODE'];
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

// Show Add Rate Class Form

function addForm() {
    $(':input', '#submitForm').not('[type="radio"]').val('').prop('checked', false).prop('selected', false);
    $('.select2').val(null).trigger('change');
    $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    $('#popModalWindowlabel').html('Add New Role');
    $("#ROLE_CODE").prop("readonly", false);  
    $('#popModalWindow').modal('show');
}

// Delete Rate Class

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
                    url: '<?php echo base_url('/deleteUserRole')?>',
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
                            '<li>The Role has been deleted</li>');
                        $('#dataTable_view').dataTable().fnDraw();
                    }
                });
            }
        }
    });
});



// Show Edit Rate Class Form

$(document).on('click', '.editWindow', function() {

    $('.dtr-bs-modal').modal('hide');

    var sysid = $(this).attr('data_sysid');
    $('#popModalWindowlabel').html('Edit Role');
    $("#ROLE_CODE").prop("readonly", true);
    $('#popModalWindow').modal('show');

    var url = '<?php echo base_url('/editUserRole')?>';
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
                        $('#' + field).val(dataval);
                    
                });
            });
            $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
        }
    });
});

// Show Copy Rate Class Form

$(document).on('click', '.copyWindow', function() {

    $('.dtr-bs-modal').modal('hide');

    var sysid = $(this).attr('data_sysid');
    var rolecode = $(this).attr('data_rolecode');

    $('#main_Role_ID').val(sysid);

    $('#copyModalWindowlabel').html('Create Role Copies of \'' + rolecode + '\'');

    //Reset repeated fields every time modal is loaded
    $('[data-repeater-item]').slice(1).empty();
    $('#form-repeater-1-1').val("");

    $('#copyModalWindow').modal('show');

});


// Add New or Edit Rate Class submit Function

function submitForm(id) {
    hideModalAlerts();
    var formSerialization = $('#' + id).serializeArray();
    var url = '<?php echo base_url('/insertUserRole')?>';
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
                var alertText = $('#ROLE_ID').val() == '' ? '<li>The new Role \'' + $('#ROLE_CODE')
                    .val() + '\' has been created</li>' : '<li>The Role \'' + $('#ROLE_CODE').val() +
                    '\' has been updated</li>';
                showModalAlert('success', alertText);

                $('#popModalWindow').modal('hide');
                $('#dataTable_view').dataTable().fnDraw();
            }
        }
    });
}

// Copy Rate Class to Multiple submit Function

function copyForm(id) {
    hideModalAlerts();

    var formSerialization = $('#' + id).serializeArray();
    var url = '<?php echo base_url('/copyUserRole')?>';
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
                showModalAlert('error', '<li>No New Roles were added</li>');
            } else {
                showModalAlert('success', '<li>' + response +
                    ' new Role copies have been created</li>');
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