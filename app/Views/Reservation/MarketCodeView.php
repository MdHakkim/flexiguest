<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>
<?=$this->include('Layout/ErrorReport')?>
<?=$this->include('Layout/SuccessReport')?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Masters /</span> Market Codes
        </h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid table-responsive" style="padding: 16px;">
                <table id="dataTable_view" class="dt-responsive table table-striped display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>Market Code</th>
                            <th>Description</th>
                            <th>Market Group</th>
                            <th>Seq</th>
                            <th>Status</th>
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
                    <h4 class="modal-title" id="popModalWindowlabel">Market Code</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="submitForm" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <input type="hidden" name="MK_CD_ID" id="MK_CD_ID" class="form-control" />

                            <div class="border rounded p-3">

                                <div class="col-md-12">
                                    <div class="row mb-3">
                                        <label for="html5-text-input" class="col-form-label col-md-3"><b>Market Code
                                                *</b></label>
                                        <div class="col-md-3">
                                            <input type="text" name="MK_CD_CODE" id="MK_CD_CODE"
                                                class="form-control bootstrap-maxlength" maxlength="10"
                                                placeholder="eg: 1001" required />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="html5-text-input" class="col-form-label col-md-3"><b>Description
                                                *</b></label>
                                        <div class="col-md-7">
                                            <input type="text" name="MK_CD_DESC" id="MK_CD_DESC"
                                                class="form-control bootstrap-maxlength" maxlength="50"
                                                placeholder="eg: Online Travel Agent" required />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="html5-text-input" class="col-form-label col-md-3"><b>Select Market
                                                Group
                                                *</b></label>
                                        <div class="col-md-5">
                                            <select id="MK_GR_ID" name="MK_GR_ID"
                                                class="select2 form-select form-select-lg" data-allow-clear="true"
                                                required>
                                                <?=$marketGroupOptions?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="html5-text-input" class="col-form-label col-md-3"><b>Select Display Color *</b></label>
                                        <div class="col-md-4">
                                            <select id="CLR_ID" name="CLR_ID"
                                                class="select2 form-select form-select-lg" data-allow-clear="true">
                                                <?=$colorOptions?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="html5-text-input" class="col-form-label col-md-3"><b>Display Sequence *</b></label>
                                        <div class="col-md-3">
                                            <input type="number" name="MK_CD_DIS_SEQ" id="MK_CD_DIS_SEQ"
                                                class="form-control" min="0" placeholder="" />
                                        </div>
                                    </div>                                    
                                </div>
                            </div>

                            <div class="text-center statusCheck">
                                <div class="col-md-12">
                                    <label class="switch switch-lg">
                                        <input id="MK_CD_STATUS" name="MK_CD_STATUS" type="checkbox" value="1"
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
            'url': '<?php echo base_url('/marketCodeView') ?>'
        },
        'columns': [{
                data: ''
            },
            {
                data: 'CLR_NAME'
            },
            {
                data: 'MK_CD_CODE'
            },
            {
                data: 'MK_CD_DESC'
            },
            {
                data: 'MK_GR_CODE'
            },
            {
                data: 'MK_CD_DIS_SEQ'
            },
            {
                data: 'MK_CD_STATUS'
            },
            {
                data: null,
                "orderable": false,
                render: function(data, type, row, meta) {
                    return (
                        '<div class="d-inline-block">' +
                        '<a href="javascript:;" title="Edit or Delete" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                        '<ul class="dropdown-menu dropdown-menu-end">' +
                        '<li><a href="javascript:;" data_sysid="' + data['MK_CD_ID'] +
                        '" class="dropdown-item editWindow text-primary"><i class="fa-solid fa-pen-to-square"></i> Edit</a></li>' +
                        '<div class="dropdown-divider"></div>' +
                        '<li><a href="javascript:;" data_sysid="' + data['MK_CD_ID'] +
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
            targets: 1,
            width: "10%",
            render: function (data, type, full, meta) {
                var $color_name = full['CLR_NAME'];
                
                return (
                '<div style="background: ' + $color_name + ';padding: 18px;"></div>'
                );
            }
        }, {
            width: "15%"
        }, {
            width: "15%"
        }, {
            width: "15%"
        }, {
            width: "13%"
        }, {
          // Label
          targets: -2,
          width: "10%",
          render: function (data, type, full, meta) {
            var $status_number = full['MK_CD_STATUS'];
            var $status = {
              0: { title: 'Inactive', class: 'bg-label-danger' },
              1: { title: 'Active', class: 'bg-label-success' }
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
        }],
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
                        return 'Details of ' + data['MK_CD_CODE'];
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



// Show Add Market Code Form

function addForm() {
    $(':input', '#submitForm').not('[type="radio"]').val('').prop('checked', false).prop('selected', false);
    $('.select2').val(null).trigger('change');

    $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    $('#popModalWindowlabel').html('Add New Market Code');
    $("#MK_CD_CODE").prop("readonly", false);
    $('#popModalWindow').modal('show');
    $("#MK_CD_STATUS").prop('checked', 'checked');
}

// Delete Market Code

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
                    url: '<?php echo base_url('/deleteMarketCode') ?>',
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
                            '<li>The Market Code has been deleted</li>'
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


// Show Edit Market Code Form

$(document).on('click', '.editWindow', function() {

    $('.dtr-bs-modal').modal('hide');

    var sysid = $(this).attr('data_sysid');
    $('#popModalWindowlabel').html('Edit Market Code');

    $("#MK_CD_CODE").prop("readonly", true);
    $("#MK_CD_ADJ option").prop('disabled', false);

    $(".statusCheck").show();

    $('#popModalWindow').modal('show');

    var url = '<?php echo base_url('/editMarketCode') ?>';
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

                    if (field == 'MK_GR_ID' || field == 'CLR_ID') {
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


// Add New or Edit Market Code submit Function

function submitForm(id) {
    hideModalAlerts();
    var formSerialization = $('#' + id).serializeArray();
    var url = '<?php echo base_url('/insertMarketCode') ?>';
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
                var alertText = $('#MK_CD_ID').val() == '' ? '<li>The new Market Code \'' +
                    $(
                        '#MK_CD_CODE')
                    .val() + '\' has been created</li>' : '<li>The Market Code \'' + $(
                        '#MK_CD_CODE').val() +
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