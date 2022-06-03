<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Masters /</span> Package Groups</h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid table-responsive" style="padding: 16px 16px 6px 16px">
                <table id="dataTable_view" class="dt-responsive table table-striped display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Package Group Code</th>
                            <th>Short Description</th>
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
        aria-lableledby="popModalWindowlable">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">Package Group</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="submitForm" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <input type="hidden" name="PKG_GR_ID" id="PKG_GR_ID" class="form-control" />
                            <div class="col-md-6">
                                <label class="form-label"><b>Package Group Code *</b></label>
                                <input type="text" name="PKG_GR_CODE" id="PKG_GR_CODE"
                                    class="form-control bootstrap-maxlength" maxlength="10" placeholder="eg: FULL"
                                    required />
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Short Description</label>
                                <input type="text" name="PKG_GR_SHORT_DESC" id="PKG_GR_SHORT_DESC"
                                    class="form-control bootstrap-maxlength" maxlength="50" placeholder="eg: Full Meal"
                                    required />
                            </div>
                            <div class="col-md-10">
                                <label class="form-label"><b>Description *</b></label>
                                <input type="text" name="PKG_GR_DESC" id="PKG_GR_DESC"
                                    class="form-control bootstrap-maxlength" maxlength="255"
                                    placeholder="eg: Provides Full Meals for a whole day" required />
                            </div>
                            <div class="col-md-12">
                                <label for="form-label"><b>Package List *</b></label>
                                <input id="PKG_CODES" name="TagifyPkgGroupList" class="form-control TagifyPkgGroupList"
                                    value="FOOD" />
                            </div>

                            <div class="col-md-6">
                                <label class="switch">
                                    <input id="PKG_GR_SELL_SEP" name="PKG_GR_SELL_SEP" type="checkbox" value="1"
                                        class="switch-input" />
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
var compAgntMode = '';
var linkMode = '';

$(document).ready(function() {
    linkMode = 'EX';

    $('#dataTable_view').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/packageGroupView')?>'
        },
        'columns': [{
                data: ''
            },
            {
                data: 'PKG_GR_CODE'
            },
            {
                data: 'PKG_GR_SHORT_DESC'
            },
            {
                data: 'PKG_GR_DESC'
            },
            {
                data: null,
                "orderable": false,
                render: function(data, type, row, meta) {
                    return (
                        '<div class="d-inline-block">' +
                        '<a href="javascript:;" title="Edit or Delete" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                        '<ul class="dropdown-menu dropdown-menu-end">' +
                        '<li><a href="javascript:;" data_sysid="' + data['PKG_GR_ID'] +
                        '" class="dropdown-item editWindow text-primary"><i class="fa-solid fa-pen-to-square"></i> Edit</a></li>' +
                        '<div class="dropdown-divider"></div>' +
                        '<li><a href="javascript:;" data_sysid="' + data['PKG_GR_ID'] +
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
                        return 'Details of ' + data['PKG_GR_CODE'];
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

// Show Add Package Group Form

function addForm() {
    $(':input', '#submitForm').not('[type="radio"]').val('').prop('checked', false).prop('selected', false);
    //$('.select2').val(null).trigger('change');

    $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    $('#popModalWindowlabel').html('Add New Package Group');
    $("#PKG_GR_CODE").prop("readonly", false);

    $('#popModalWindow').modal('show');
    $("#PKG_GR_SELL_SEP").prop('checked', 'checked');

}

// Delete Package Group

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
                    url: '<?php echo base_url('/deletePackageGroup')?>',
                    type: "post",
                    data: {
                        sysid: sysid
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    dataType: 'json',
                    success: function(respn) {

                        var response = respn['SUCCESS'];
                        if (response != '1') {
                            var ERROR = respn['ERROR'];
                            var mcontent = '';
                            mcontent += '<li>' + ERROR + '</li>';
                            showModalAlert('error', mcontent);
                        } else {
                            showModalAlert('warning',
                                '<li>The Package Group has been deleted</li>');
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


// Show Edit Package Group Form

$(document).on('click', '.editWindow', function() {

    $('.dtr-bs-modal').modal('hide');

    var sysid = $(this).attr('data_sysid');
    $('#popModalWindowlabel').html('Edit Package Group');
    $("#PKG_GR_CODE").prop("readonly", true);
    $('#popModalWindow').modal('show');

    var url = '<?php echo base_url('/editPackageGroup')?>';
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


// Add New or Edit Package Group submit Function

function submitForm(id) {
    hideModalAlerts();
    var formSerialization = $('#' + id).serializeArray();
    var url = '<?php echo base_url('/insertPackageGroup')?>';
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
                var alertText = $('#PKG_GR_ID').val() == '' ? '<li>The new Package Group \'' + $(
                        '#PKG_GR_CODE')
                    .val() + '\' has been created</li>' : '<li>The Package Group \'' + $('#PKG_GR_CODE')
                    .val() +
                    '\' has been updated</li>';
                showModalAlert('success', alertText);

                $('#popModalWindow').modal('hide');
                $('#dataTable_view').dataTable().fnDraw();
            }
        }
    });
}

// Copy Package Group to Multiple submit Function

function copyForm(id) {
    hideModalAlerts();

    var formSerialization = $('#' + id).serializeArray();
    var url = '<?php echo base_url('/copyPackageGroup')?>';
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
                showModalAlert('error', '<li>No New Package Groups were added</li>');
            } else {
                showModalAlert('success', '<li>' + response +
                    ' new Package Group copies have been created</li>');
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