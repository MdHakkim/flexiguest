<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Masters /</span> Exchange Codes</h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid table-responsive" style="padding: 16px 16px 6px 16px">
                <table id="dataTable_view" class="dt-responsive table table-striped display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th class="all">Code</th>
                            <th class="all">Description</th>
                            <th>Cash</th>
                            <th>Check</th>
                            <th>Settlement</th>
                            <th>Status</th>
                            <th>Date</th>
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
                    <h4 class="modal-title" id="popModalWindowlabel">Exchange Codes</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="submitForm" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <input type="hidden" name="EXCH_ID" id="EXCH_ID" class="form-control" />

                            <div class="col-md-7">
                                <lable class="form-lable"><b> Code *</b></lable>
                                <input type="text" name="EXCH_CODE" id="EXCH_CODE" class="form-control bootstrap-maxlength"
                                    maxlength="10" placeholder="eg: Base" required />
                            </div>
                            <div class="col-md-8">
                                <lable class="form-lable"><b> Description *</b></lable>
                                <input type="text" name="EXCH_DESC" id="EXCH_DESC" class="form-control bootstrap-maxlength"
                                    maxlength="50" placeholder="eg: Base Currency" required />
                            </div>
                           

                            <div class="col-md-6">
                                <label class="switch">
                                    <input id="EXCH_CASH"  value="1" name="EXCH_CASH" type="checkbox"
                                        class="switch-input" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label"><b>Exchange Cash</b></span>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="switch">
                                    <input id="EXCH_CHECK"  value="1" name="EXCH_CHECK" type="checkbox"
                                        class="switch-input" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label"><b>Exchange Check</b></span>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="switch">
                                    <input id="EXCH_SETTLEMENT"  value="1" name="EXCH_SETTLEMENT" type="checkbox"
                                        class="switch-input" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label"><b>Settlement</b></span>
                                </label>
                            </div>
                            </div>
                            <!-- <div class="col-md-6">
                                <label class="switch">
                                    <input id="EXCH_STATUS"  value="1" name="EXCH_STATUS" type="checkbox"
                                        class="switch-input" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label">Status</span>
                                </label>
                            </div> -->

                            <div class="text-left statusCheck mt-4">
                                <div class="col-md-12">
                                    <label class="switch switch-lg">
                                        <input id="EXCH_STATUS" name="EXCH_STATUS" type="checkbox" value="1"
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
            'url': '<?php echo base_url('/ExchangeCodesView')?>'
        },
        'columns': [{
                data: ''
            },
            {
                data: 'EXCH_ID',
                'visible':false
            },
            {
                data: 'EXCH_CODE'
            },
            {
                data: 'EXCH_DESC'
            },
            {
                data: 'EXCH_CASH'
            },
            {
                data: 'EXCH_CHECK'
            },
            {
                data: 'EXCH_SETTLEMENT'
            },
            {
                data: 'EXCH_STATUS'
            },
            {
                data: 'EXCH_CREATED'
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
                        '<li><a href="javascript:;" data_sysid="' + data['EXCH_ID'] +
                        '" class="dropdown-item editWindow text-primary"><i class="fa-solid fa-pen-to-square"></i> Edit</a></li>' +
                        '<div class="dropdown-divider"></div>' +
                        '<li><a href="javascript:;" data_sysid="' + data['EXCH_ID'] +
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
            width: "16%"
        },
        {
          // Label
          targets: -6,
          width: "15%",
          render: function (data, type, full, meta) {
            var $status_number = full['EXCH_CASH'];
            var $status = { 
              0: {  class: 'fa-solid fa-xmark' },
              1: { class: 'fa-solid fa-check' }
            };
            
            if (typeof $status[$status_number] === 'undefined') {
              return data;
            }
            return (
              '<i class="'+ $status[$status_number].class +'"></i>'
            );
          }
        },
        {
          // Label
          targets: -5,
          width: "15%",
          render: function (data, type, full, meta) {
            var $status_number = full['EXCH_CHECK'];
            var $status = { 
              0: {  class: 'fa-solid fa-xmark' },
              1: { class: 'fa-solid fa-check' }
            };
            
            if (typeof $status[$status_number] === 'undefined') {
              return data;
            }
            return (
              '<i class="'+ $status[$status_number].class +'"></i>'
            );
          }
        },
        {
          // Label
          targets: -4,
          width: "15%",
          render: function (data, type, full, meta) {
            var $status_number = full['EXCH_SETTLEMENT'];
            var $status = { 
              0: {  class: 'fa-solid fa-xmark' },
              1: { class: 'fa-solid fa-check' }
            };
           
            if (typeof $status[$status_number] === 'undefined') {
              return data;
            }
            return (
              '<i class="'+ $status[$status_number].class +'"></i>'
            );
          }
        },
        
        
         
        {
          // Label
          targets: -3,
          width: "15%",
          render: function (data, type, full, meta) {
            var $status_number = full['EXCH_STATUS'];
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
            [1, "asc"]
        ],
        destroy: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal({
                    header: function(row) {
                        var data = row.data();
                        return 'Details';
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



// Show Add Exchange Code Form

function addForm() {
    $(':input', '#submitForm').not('[type="radio"],[type="checkbox"]').val('').prop('checked', false).prop('selected', false);
    $('.select2').val(null).trigger('change');

    $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    $('#popModalWindowlabel').html('Add New Exchange Code');
    $("#EXCH_CODE").prop("readonly", false);

    $('#popModalWindow').modal('show');
}

// Delete Exchange Code

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
                    url: '<?php echo base_url('/deleteExchangeCodes')?>',
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
                            '<li>The Exchange code has been deleted</li>');
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


// Show Edit Exchange Code Form

$(document).on('click', '.editWindow', function() {

    $('.dtr-bs-modal').modal('hide');

    var sysid = $(this).attr('data_sysid');
    $('#popModalWindowlabel').html('Edit Exchange Code');
    $("#EXCH_CODE").prop("readonly", true);

    $('#popModalWindow').modal('show');

    var url = '<?php echo base_url('/editExchangeCodes')?>';
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


// Add New or Edit Exchange Code submit Function

function submitForm(id) {

    hideModalAlerts();
    var formSerialization = $('#' + id).serializeArray();
    
    var url = '<?php echo base_url('/insertExchangeCodes')?>';
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
                var alertText = $('#EXCH_ID').val() == '' ? '<li>The new Exchange Code \'' + $(
                        '#EXCH_CODE')
                    .val() + '\' has been created</li>' : '<li>The Exchange Code \'' + $(
                        '#EXCH_CODE').val() +
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