<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Masters /</span> Items
        </h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid table-responsive" style="padding: 16px;">
                <table id="dataTable_view" class="dt-responsive table table-striped display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th class="all">Item Name</th>
                            <th>Class</th> 
                            <th>Begin Date</th> 
                            <th>End Date</th>
                            <th>Quantity</th> 
                            <th>Sunday</th> 
                            <th>Monday</th> 
                            <th>Tuesday</th> 
                            <th>Wednesday</th> 
                            <th>Thursday</th> 
                            <th>Friday</th> 
                            <th>Saturday</th> 
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
        aria-lableledby="popModalWindowlable">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">Daily Inventory</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="submitForm" class="needs-validation" novalidate>
                        
                        <div class="row g-3">
                            <input type="hidden" name="ITM_DLY_ID" id="ITM_DLY_ID" class="form-control" />                           

                            <div class="col-md-6">
                                <lable class="form-lable"><b>Class *</b></lable>
                                <select id="IT_CL_ID" name="IT_CL_ID" class="select2 form-select form-select-lg" data-allow-clear="true" required> 
                                <option value="">Select</option>                                     
                                </select>                                
                            </div>
                            <div class="col-md-6">
                                <lable class="form-lable"><b>Item *</b></lable>
                                <select id="ITM_ID" name="ITM_ID" class="select2 form-select form-select-lg" data-allow-clear="true" required> 
                                <option value="">Select</option>                                     
                                </select>                                
                            </div>

                            <div class="col-md-6">
                                <lable class="form-lable"><b>Begin Date *</b></lable>
                                <div class="col-md-12">
                                <div class="input-group">
                                    <input type="text" id="ITM_DLY_BEGIN_DATE" name="ITM_DLY_BEGIN_DATE" class="form-control" placeholder="DD-MM-YYYY">
                                    <span class="input-group-append">
                                        <span class="input-group-text bg-light d-block">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </span>
                                </div>
                                </div>                       
                            </div> 
                            
                            <div class="col-md-6">
                                <lable class="form-lable"><b>End Date *</b></lable>
                                <div class="col-md-12">
                                <div class="input-group">
                                    <input type="text" id="ITM_DLY_END_DATE" name="ITM_DLY_END_DATE" class="form-control" placeholder="DD-MM-YYYY">
                                    <span class="input-group-append">
                                        <span class="input-group-text bg-light d-block">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </span>
                                </div>
                                </div>                     
                            </div> 
                            <div class="col-md-4">
                                <lable class="form-lable"><b>Quantity *</b></lable>
                                <input type="number" name="ITM_DLY_QTY" id="ITM_DLY_QTY" class="form-control bootstrap-maxlength" min="1" required />                        
                            </div> 
                        </div>
                        <div class="row g-3 mt-2">   
                            
                            <div class="col-md-3">
                                <label class="switch">
                                    <input id="ITM_DLY_SUN"  value="1" name="ITM_DLY_SUN" type="checkbox"
                                        class="switch-input" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label"><b>Sunday</b></span>
                                </label>
                            </div>

                            <div class="col-md-3">
                                <label class="switch">
                                    <input id="ITM_DLY_MON"  value="1" name="ITM_DLY_MON" type="checkbox"
                                        class="switch-input" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label"><b>Monday</b></span>
                                </label>
                            </div>

                            <div class="col-md-3">
                                <label class="switch">
                                    <input id="ITM_DLY_TUE"  value="1" name="ITM_DLY_TUE" type="checkbox"
                                        class="switch-input" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label"><b>Tuesday</b></span>
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label class="switch">
                                    <input id="ITM_DLY_WED"  value="1" name="ITM_DLY_WED" type="checkbox"
                                        class="switch-input" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label"><b>Wednesday</b></span>
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label class="switch">
                                    <input id="ITM_DLY_THU"  value="1" name="ITM_DLY_THU" type="checkbox"
                                        class="switch-input" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label"><b>Thursday</b></span>
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label class="switch">
                                    <input id="ITM_DLY_FRI"  value="1" name="ITM_DLY_FRI" type="checkbox"
                                        class="switch-input" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label"><b>Friday</b></span>
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label class="switch">
                                    <input id="ITM_DLY_SAT"  value="1" name="ITM_DLY_SAT" type="checkbox"
                                        class="switch-input" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label"><b>Saturday</b></span>
                                </label>
                            </div>       

                            <div class="text-left mt-4">
                                <div class="col-md-12">
                                    <label class="switch switch-lg">
                                        <input id="ITM_DLY_STATUS" name="ITM_DLY_STATUS" type="checkbox" value="1"
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
                'url': '<?php echo base_url('/DailyInventoryView') ?>'
            },
            'columns': [{
                    data: ''
                },
                {
                    data: 'ITM_DLY_ID',
                    'visible': false
                },
                {
                    data: 'ITM_NAME'
                },
                {
                    data: 'IT_CL_CODE',
                    render: function(data, type, full, meta) {
                        return full['IT_CL_CODE']+' | '+full['IT_CL_DESC'];
                    }
                },
                {  
                    data: 'ITM_DLY_BEGIN_DATE'                   
                },                
                {
                    data: 'ITM_DLY_END_DATE'
                },
                {
                    data: 'ITM_DLY_QTY'
                },
                {
                    data: 'ITM_DLY_SUN',
                    render: function (data, type, full, meta) {
                        var $status_number = full['ITM_DLY_SUN'];
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
                    data: 'ITM_DLY_MON',
                    'visible': false,
                    render: function (data, type, full, meta) {
                        var $status_number = full['ITM_DLY_MON'];
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
                    data: 'ITM_DLY_TUE',
                    'visible': false,
                    render: function (data, type, full, meta) {
                        var $status_number = full['ITM_DLY_TUE'];
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
                    data: 'ITM_DLY_WED',
                    'visible': false,
                    render: function (data, type, full, meta) {
                        var $status_number = full['ITM_DLY_WED'];
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
                    data: 'ITM_DLY_THU',
                    'visible': false,
                    render: function (data, type, full, meta) {
                        var $status_number = full['ITM_DLY_THU'];
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
                    data: 'ITM_DLY_FRI',
                    'visible': false,
                    render: function (data, type, full, meta) {
                        var $status_number = full['ITM_DLY_FRI'];
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
                    data: 'ITM_DLY_SAT',
                    'visible': false,
                    render: function (data, type, full, meta) {
                        var $status_number = full['ITM_DLY_SAT'];
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
                    data: 'ITM_DLY_STATUS',
                    render: function (data, type, full, meta) {
                        var $status_number = full['ITM_DLY_STATUS'];
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
                    data: null,
                    "orderable": false,
                    render: function(data, type, row, meta) {
                        return (
                            '<div class="d-inline-block">' +
                            '<a href="javascript:;" title="Edit or Delete" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                            '<ul class="dropdown-menu dropdown-menu-end">' +
                            '<li><a href="javascript:;" data_sysid="' + data['ITM_DLY_ID'] +
                            '" class="dropdown-item editWindow text-primary"><i class="fa-solid fa-pen-to-square"></i> Edit</a></li>' +
                            '<div class="dropdown-divider"></div>' +
                            '<li><a href="javascript:;" data_sysid="' + data['ITM_DLY_ID'] +
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
                    width: "20%"
                }, {
                    width: "15%"
                }, {
                    width: "15%"
                },
                {
                    width: "15%"
                },
                {
                    width: "15%"
                },
                {
                    width: "15%"
                },
                {
                    width: "15%"
                },
                {
                    width: "15%"
                },
                {
                    width: "15%"
                },
                {
                    width: "15%"
                },
                {
                    width: "15%"
                },
                {
                    width: "15%"
                },
                {
                    width: "15%"
                },
                
                
                
            ],
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
                            return 'Item Details';
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

        $('#ITM_DLY_BEGIN_DATE').datepicker({
            format: 'd-M-yyyy',
            autoclose: true,
        });
        $('#ITM_DLY_END_DATE').datepicker({
            format: 'd-M-yyyy',
            autoclose: true,
        });


    });

    function addForm() {
        $(':input', '#submitForm').not('[type="radio"],[type="checkbox"]').val('').prop('checked', false).prop('selected', false);
        $('.select2').val(null).trigger('change');

        $('#IT_CL_ID,#ITM_ID').html('<option value="">Select</option>');

        $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
        $('#popModalWindowlabel').html('Add New Daily Inventory');
        $(".statusCheck").hide();
        $('#popModalWindow').modal('show');

        itemClassList();

    }


    // Show Edit Exchange  Code Form

    $(document).on('click', '.editWindow', function() {
        itemClassList();

        $('.dtr-bs-modal').modal('hide');

        var sysid = $(this).attr('data_sysid');
        $('#popModalWindowlabel').html('Edit Daily Inventory');

        $(".statusCheck").show();

        $('#popModalWindow').modal('show');

        var url = '<?php echo base_url('/editDailyInventory') ?>';
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
                        } 
                        
                        else if(field == 'ITM_ID')  {  
                             class_val = dataval;                            
                        }

                        else if(field == 'IT_CL_ID')  {                                                                              
                            $('#' + field).val(dataval).trigger('change',class_val);                           
                            
                        }
                        else{
                            $('#' + field).val(dataval);
                        }
                        

                    });
                });
                $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
            }
        });
    });



    // Add New or Edit Exchange Rate submit Function

    function submitForm(id) {
        hideModalAlerts();
        var formSerialization = $('#' + id).serializeArray();
        var url = '<?php echo base_url('/insertDailyInventory') ?>';
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
                    
                    var alertText = $('#ITM_DLY_ID').val() == '' ? '<li>New Daily Inventory has been added </li>' : '<li>The Daily Inventory has been updated</li>';


                    showModalAlert('success', alertText);

                    $('#popModalWindow').modal('hide');
                    $('#dataTable_view').dataTable().fnDraw();
                }
            }
        });
    }


    // Delete Exchange Rate 

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
                        url: '<?php echo base_url('/deleteDailyInventory') ?>',
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
                                '<li>The Daily Inventory has been deleted</li>'
                            );
                            $('#dataTable_view').dataTable().fnDraw();
                        }
                    });
                }
            }
        });
    });

    function itemClassList(){
    $.ajax({
        url: '<?php echo base_url('/itemClassList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        async:false,
        // dataType:'json',
        success:function(respn){
          
          $('#IT_CL_ID').html(respn);
        }
    });
  }

  $("#IT_CL_ID").change(function(e,param = 0) {
      
        var item_class_id = $(this).val();
        $.ajax({
        url: '<?php echo base_url('/itemList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{item_class_id:item_class_id,item_id:param},
        // dataType:'json',
        success:function(respn){
                    
          $('#ITM_ID').html(respn);
        }
    });
  });
</script>

<?= $this->endSection() ?>