<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Housekeeping /</span> Tasks</h4>
        <div id="taskDiv" class="content">
        <!-- DataTable with Buttons -->
        <div class="card">
            <h5 class="card-header">Tasks</h5>
            <div class="container-fluid table-responsive task_div" style="padding: 16px 16px 6px 16px">           
            <div class="border rounded p-4 mb-3">

            <div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" onClick="addForm()" ><i class="fa-solid fa-plus fa-lg"></i> Add New</button></div>  
                <div class="col-md-6 ps-0"><div class="row"> <div class="col-md-4 pt-2"><label class="fw-semibold">Select Task Code</label></div> 
                <div class="col-md-6 col-sm-4 ps-0"><select name="HKT_ID" id="HKT_ID" class="select2 form-select" data-allow-clear="true" placeholder="Select Task code"></select></div>
                    </div>
                </div>
            </div>
           
                <table id="dataTable_view" class="dt-responsive table table-striped display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th class="all">Tasks</th>
                            <th class="all">Task Code</th> 
                            <th class="all">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        </div>
        </div>

        
    </div>
    <!-- / Content -->

        <!-- Modal Window -->

        <div class="modal fade" id="popModalWindow" data-backdrop="static" data-keyboard="false"
        aria-lableledby="popModalWindowlable">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">Task Code</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>
                <div class="modal-body">
                <form id="submitForm" class="needs-validation" novalidate>
                
                       <input type="hidden" name="HKST_ID" id="HKST_ID" class="form-control" />
                        <div class="row g-3">                           
                          
                            <div class="col-md-6">
                                <label class="form-label"><b>Task Code *</b></label>
                                <select name="HKST_TASK_ID" id="HKST_TASK_ID" class="select2 form-select"
                                        data-allow-clear="true">
                                        
                                </select>                               
                            </div> 
                            <div class="col-md-12">
                                <label class="form-label"><b>Task *</b></label>
                                <input type="text" name="HKST_DESCRIPTION" id="HKST_DESCRIPTION" class="form-control bootstrap-maxlength"
                                 required />
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
    getTasksCodes();
    toggleButton('.delete-task', 'btn-danger', 'btn-dark', false);
    $('.delete-task').prop('disabled',true);

    $('#dataTable_view').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        
        'ajax': {
            'url': '<?php echo base_url('/tasksView')?>',
            'data':function(d) {
                d['HKT_ID'] = $("#HKT_ID").val();
            }
        },
        'columns': [{
                data: ''
            },
            {
                data: 'HKST_ID',
                'visible':false
            },
            
            {
                data: 'HKST_DESCRIPTION'
            },
            {
                data: 'HKT_CODE'
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
                        '<li><a href="javascript:;" data-sysid="' + data['HKST_ID'] +
                        '" class="dropdown-item editWindow text-primary"><i class="fa-solid fa-pen-to-square"></i> Edit</a></li>' +
                        '<div class="dropdown-divider"></div>' +
                        '<li><a href="javascript:;" data-sysid="' + data['HKST_ID'] +
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
            width: "15%"
        }, 
        
       ],
        "order": [
            [1, "desc"]
        ],
        'createdRow': function(row, data, dataIndex) {

        $(row).attr('data-task_id', data['HKST_ID']);

        },
        destroy: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal({
                    header: function(row) {
                        var data = row.data();
                        return 'Details of ' + data['HKT_CODE'];
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


    // $("#dataTable_view_wrapper .row:first").before(
    //     '<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" onClick="addForm()" ><i class="fa-solid fa-plus fa-lg"></i> Add New</button></div>        <div class="col-md-6 ps-0"><div class="row"> <div class="col-md-4 pt-2"><label class="fw-semibold">Select Task Code</label></div> <div class="col-md-6 col-sm-4 ps-0"><select name="HKT_ID" id="HKT_ID" class="select2 form-select" data-allow-clear="true" placeholder="Select Task code"></select></div></div></div></div>'
    // );
  
});

// Show Add Task Form

function addForm() {
    $(':input', '#submitForm').not('[type="radio"],[type="checkbox"]').val('').prop('checked', false).prop('selected', false);
    $('.select2').val(null).trigger('change');
    $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    $('#popModalWindowlabel').html('Add New Task');
    $('#popModalWindow').modal('show');
}

// Delete Task

$(document).on('click', '.delete-record', function() {
    hideModalAlerts();
    $('.dtr-bs-modal').modal('hide');
    var sysid = $(this).attr('data-sysid');
    
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
                    url: '<?php echo base_url('/deleteTask')?>',
                    type: "post",
                    data: {
                        sysid: sysid
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    dataType: 'json',
                    success: function(respn) {
                        clearFormFields('.task_div');
                        $('#HKST_TASK_ID').val(null).trigger('change');                       
                       
                        if(respn['SUCCESS'] == 1){
                            showModalAlert('warning',
                            '<li>The Task has been deleted</li>');
                            $('#dataTable_view').dataTable().fnDraw();
                        }else{
                            showModalAlert('error',
                            '<li>The Task cannot be deleted</li>');
                        }
                        
                    }
                });
            }
        }
    });
});


// Add New or Edit tasks submit Function

function submitForm(id) {
    hideModalAlerts();
    var formSerialization = $('#' + id).serializeArray();    
    var url = '<?php echo base_url('/insertTask')?>';
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
                var alertText = $('#HKST_ID').val() == '' ? '<li>The new Task has been created</li>' : '<li>The Task has been updated</li>';                
                showModalAlert('success', alertText);
                $('#popModalWindow').modal('hide');
                $('#dataTable_view').dataTable().fnDraw();
            }

            
        }
    });
}



function getTasksCodes(){
    $.ajax({
      url: '<?php echo base_url('/allTaskcodeList')?>',
      type: "get",
      headers: {'X-Requested-With': 'XMLHttpRequest'},
       async:false,
      success:function(respn){        
        $('#HKST_TASK_ID').html(respn);
        $('#HKT_ID').html(respn);
        
      }
  }); 
}


// Show Edit task Form

$(document).on('click', '.editWindow', function() {
    $('.dtr-bs-modal').modal('hide');
    var sysid = $(this).attr('data-sysid');
    $('#popModalWindowlabel').html('Edit Task');
    $('#popModalWindow').modal('show');
    var url = '<?php echo base_url('/editTask')?>';
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
                    
                    if(field == 'HKST_TASK_ID'){
                        $('#' + field).val(dataval).trigger('change');
                    }
                    else
                    $('#' + field).val(dataval);
                });
            });
            $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
        }
    });
});



$(document).on('change', '#HKT_ID', function() {  
   $('#dataTable_view').dataTable().fnDraw();
});




<?php echo isset($toggleButton_javascript) ? $toggleButton_javascript : ''; ?>

 // Display function clearFormFields -->
<?php echo isset($clearFormFields_javascript) ? $clearFormFields_javascript : ''; ?>

 // Display function blockLoader -->
<?php echo isset($blockLoader_javascript) ? $blockLoader_javascript : ''; ?>
</script>


<?=$this->endSection()?>