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
                            <th>Item Code</th>
                            <th class="all">Description</th>
                            <th>Department</th>
                            <th>Class</th>
                            <th>QTY in Stock</th>
                            <th>Default Quantity</th>
                            <th>Available From Time</th>
                            <th>Available To Time</th>
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
                    <h4 class="modal-title" id="popModalWindowlabel">Items</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="submitForm" class="needs-validation" novalidate>
                        
                        <div class="row g-3">
                            <input type="hidden" name="ITM_ID" id="ITM_ID" class="form-control" />

                            <div class="col-md-6">
                                <lable class="form-lable"><b> Name *</b></lable>
                                <input type="text" name="ITM_NAME" id="ITM_NAME" class="form-control bootstrap-maxlength"
                                    maxlength="10" placeholder="eg: Accessories set per room" required />
                            </div>
                            <div class="col-md-6">
                                <lable class="form-lable"><b> Code *</b></lable>
                                <input type="text" name="ITM_CODE" id="ITM_CODE" class="form-control bootstrap-maxlength"
                                    maxlength="10" placeholder="eg: FF&E-AP- ACC" required />
                            </div>
                            <div class="col-md-6">
                                <lable class="form-lable"><b> Description *</b></lable>
                                <textarea class="form-control" name="ITM_DESC" id="ITM_DESC" rows="1"></textarea>  
                                
                            </div>

                            <div class="col-md-6">
                                <lable class="form-lable"><b>Trace Text</b></lable>
                                <textarea class="form-control" name="ITM_TRACE_TEXT" id="ITM_TRACE_TEXT" rows="1"></textarea>                                
                            </div>

                            <div class="col-md-6">
                                <lable class="form-lable"><b>Department *</b></lable>
                                <select id="IT_CL_DEPARTMENTS" name="IT_CL_DEPARTMENTS" class="select2 form-select form-select-lg" data-allow-clear="true" required >
                                <option value="">Select</option>
                                </select>                                
                            </div>

                            <div class="col-md-6">
                                <lable class="form-lable"><b>Class *</b></lable>
                                <select id="IT_CL_ID" name="IT_CL_ID" class="select2 form-select form-select-lg" data-allow-clear="true" required> 
                                <option value="">Select</option>                                     
                                </select>                                
                            </div>
                            <div class="col-md-3">
                                <lable class="form-lable"><b>Quantity in Stock *</b></lable>
                                <input type="number" name="ITM_QTY_IN_STOCK" id="ITM_QTY_IN_STOCK" class="form-control bootstrap-maxlength" min="1" required />                        
                            </div>
                            <div class="col-md-3">
                                <lable class="form-lable"><b>Default Quantity *</b></lable>
                                <input type="number" name="ITM_QTY_DEFAULT" id="ITM_QTY_DEFAULT" class="form-control bootstrap-maxlength" min="1" required />                                
                            </div>

                            <div class="col-md-3">
                                <lable class="form-lable"><b>Item Available From *</b></lable>
                                <input type="time" name="ITM_AVAIL_FROM_TIME" id="ITM_AVAIL_FROM_TIME" class="form-control" required />                                
                            </div>
                            <div class="col-md-3">
                                <lable class="form-lable"><b>Item Available To *</b></lable>
                                <input type="time" name="ITM_AVAIL_TO_TIME" id="ITM_AVAIL_TO_TIME" class="form-control" required />                                
                            </div>                            

                            <div class="col-md-4">
                                <label class="switch">
                                    <input id="ITM_PRINT"  value="1" name="ITM_PRINT" type="checkbox"
                                        class="switch-input" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label"><b>Print</b></span>
                                </label>
                            </div>

                            <div class="col-md-4">
                                <label class="switch">
                                    <input id="ITM_SELL_CONTROL"  value="1" name="ITM_SELL_CONTROL" type="checkbox"
                                        class="switch-input" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label"><b>Sell Control</b></span>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="switch">
                                    <input id="ITM_SELL_SEPARATE"  value="1" name="ITM_SELL_SEPARATE" type="checkbox"
                                        class="switch-input" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label"><b>Sell Seperate</b></span>
                                </label>
                            </div>
                         
                           

                            <div class="text-left mt-4">
                                <div class="col-md-12">
                                    <label class="switch switch-lg">
                                        <input id="ITM_STATUS" name="ITM_STATUS" type="checkbox" value="1"
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
                'url': '<?php echo base_url('/ItemsView') ?>'
            },
            'columns': [{
                    data: ''
                },
                {
                    data: 'ITM_ID',
                    'visible': false
                },
                {
                    data: 'ITM_NAME'
                },
                {
                    data: 'ITM_CODE'
                },
                {
                    data: 'ITM_DESC'
                },
                {
                    data: 'DEPT_CODE',
                     render: function(data, type, full, meta) {
                         if(full['DEPT_CODE'] != null)
                         return full['DEPT_CODE']+' | '+full['DEPT_DESC'];
                         else
                         return '';
                    }
                },
                {
                    
                    data: 'IT_CL_CODE',
                     render: function(data, type, full, meta) {
                        return full['IT_CL_CODE']+' | '+full['IT_CL_DESC'];
                    }
                    
                },
                {
                    data: 'ITM_QTY_IN_STOCK',
                    
                },
                {                    
                    data: 'ITM_QTY_DEFAULT',
                   
                },
                {
                    data: 'ITM_AVAIL_FROM_TIME'
                },
                {
                    data: 'ITM_AVAIL_TO_TIME'
                },
                {
                    data: null,
                    "orderable": false,
                    render: function(data, type, row, meta) {
                        return (
                            '<div class="d-inline-block">' +
                            '<a href="javascript:;" title="Edit or Delete" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                            '<ul class="dropdown-menu dropdown-menu-end">' +
                            '<li><a href="javascript:;" data_sysid="' + data['ITM_ID'] +
                            '" class="dropdown-item editWindow text-primary"><i class="fa-solid fa-pen-to-square"></i> Edit</a></li>' +
                            '<div class="dropdown-divider"></div>' +
                            '<li><a href="javascript:;" data_sysid="' + data['ITM_ID'] +
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
                }, {
                    width: "15%"
                }, {
                    width: "10%"
                }, {
                    width: "10%"
                }, {
                    width: "10%"
                },
                {
                    width: "10%"
                },
                {
                    width: "10%"
                },
                {
                    width: "10%"
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

        $('#EXCH_RATE_BEGIN_DATE').datepicker({
            format: 'd-M-yyyy',
            autoclose: true,
        });
        $('#EXCH_RATE_BEGIN_DATE').datepicker({
            format: 'd-M-yyyy',
            autoclose: true,
        });


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

    function addForm() {
        $(':input', '#submitForm').not('[type="radio"],[type="checkbox"]').val('').prop('checked', false).prop('selected', false);
        $('.select2').val(null).trigger('change');

        $('#IT_CL_ID,#IT_CL_DEPARTMENTS').html('<option value="">Select</option>');

        $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
        $('#popModalWindowlabel').html('Add New Item');
        $(".statusCheck").hide();
        $('#popModalWindow').modal('show');

        departmentList();

    }


    // Show Edit Item Form

    $(document).on('click', '.editWindow', function() {
        departmentList();

        $('.dtr-bs-modal').modal('hide');

        var sysid = $(this).attr('data_sysid');
        $('#popModalWindowlabel').html('Edit Item');

        $(".statusCheck").show();

        $('#popModalWindow').modal('show');

        var url = '<?php echo base_url('/editItem') ?>';
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
                        
                        else if(field == 'IT_CL_ID')  {  
                             class_val = dataval;                            
                        }

                        else if(field == 'IT_CL_DEPARTMENTS')  {                                                                              
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



    // Add New or Edit Item submit Function

    function submitForm(id) {
        hideModalAlerts();
        var formSerialization = $('#' + id).serializeArray();
        var url = '<?php echo base_url('/insertItem') ?>';
        $.ajax({
            url: url,
            type: "post",
            data: formSerialization,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            dataType: 'json',
            success: function(respn) {
                //console.log(respn, "testing");
                var response = respn['SUCCESS'];
                if (response != '1') {
                    var ERROR = respn['RESPONSE']['ERROR'];
                    var mcontent = '';
                    $.each(ERROR, function(ind, data) {
                       // console.log(data, "SDF");
                        mcontent += '<li>' + data + '</li>';
                    });
                    showModalAlert('error', mcontent);
                } else {
                    
                    var alertText = $('#ITM_ID').val() == '' ? '<li>New Item has been added </li>' : '<li>The Item has been updated</li>';


                    showModalAlert('success', alertText);

                    $('#popModalWindow').modal('hide');
                    $('#dataTable_view').dataTable().fnDraw();
                }
            }
        });
    }


    // Delete Item 

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
                        url: '<?php echo base_url('/deleteItem') ?>',
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
                                '<li>The Item has been deleted</li>'
                            );
                            $('#dataTable_view').dataTable().fnDraw();
                        }
                    });
                }
            }
        });
    });

    function departmentList(){
    $.ajax({
        url: '<?php echo base_url('/itemDepartmentList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        async:false,
        // dataType:'json',
        success:function(respn){
          // console.log(respn,"testing");
          $('#IT_CL_DEPARTMENTS').html(respn);
        }
    });
  }

  $("#IT_CL_DEPARTMENTS").change(function(e,param = 0) {
      
        var deptcode = $(this).val();
        $.ajax({
        url: '<?php echo base_url('/itemClassList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{deptcode:deptcode,class_id:param},
        // dataType:'json',
        success:function(respn){
          //console.log(respn,"testing");          
          $('#IT_CL_ID').html(respn).trigger('change');
        }
    });
  });
</script>

<?= $this->endSection() ?>