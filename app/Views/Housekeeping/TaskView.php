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
            <form id="submitForm" class="needs-validation" novalidate>
                
                <input type="hidden" name="HKST_ID" id="HKST_ID" class="form-control" />
                        <div class="row g-3">                           
                            <div class="col-md-6">
                                <label class="form-label"><b>Tasks *</b></label>
                                <input type="text" name="HKST_DESCRIPTION" id="HKST_DESCRIPTION" class="form-control bootstrap-maxlength"
                                 required />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><b>Task Code *</b></label>
                                <select name="HKST_TASK_ID" id="HKST_TASK_ID" class="select2 form-select"
                                        data-allow-clear="true">
                                        
                                </select>
                               
                            </div>                         
                            <div class="col-md-12">
                            <button type="button" id="submitBtn" onClick="submitForm('submitForm')"
                                 class="btn btn-primary">Save</button>
                            </div>                          
                        </div>
                    
                    </form>
            </div>
            <div class="border rounded p-4 mb-3">
            <div class="col-md-6 mb-3">
                <button type="button" class="btn btn-primary add-new-task">
                    <i class="fa-solid fa-circle-plus"></i>&nbsp; Add New
                </button>&nbsp;
               

                <button type="button" class="btn btn-danger delete-task">
                    <i class="fa-solid fa-ban"></i>&nbsp; Delete
                </button>&nbsp;
            </div>
                <table id="dataTable_view" class="dt-responsive table table-striped display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th class="all">Tasks</th>
                            <th class="all">Task Code</th> 
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        </div>
        </div>

        
    </div>
    <!-- / Content -->

    

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
            'url': '<?php echo base_url('/tasksView')?>'
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
            [1, "asc"]
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
  
});

$(document).on('click', '#dataTable_view > tbody > tr', function() {

$('#dataTable_view').find('tr.table-warning').removeClass('table-warning');
$(this).addClass('table-warning');
$.when(loadTaskDetails($(this).data('task_id')))
    .done(function() {})
    .done(function() {
        blockLoader('#taskDiv');
    });
});


$(document).on('click', '.add-new-task', function() {
   
    hideModalAlerts();
    $('.dtr-bs-modal').modal('hide'); 
    $('#HKST_DESCRIPTION').val('');    
    $('#HKST_TASK_ID').val(null).trigger('change');

    bootbox.dialog({
        message: "Do you want to add a new task?",
        buttons: {
            ok: {
                label: 'Yes',
                className: 'btn-success',
                callback: function(result) {
                    if (result) {

                        $('.task_div').find('tr.table-warning').removeClass(
                            'table-warning');

                        //Disable Delete button
                        toggleButton('.delete-task', 'btn-danger', 'btn-dark', true);
                        $('.delete-task').prop('disabled',false);

                        showModalAlert('info',
                            'Fill in the form and click the \'Save\' button to add the new task'
                        );
                        $('#infoModal').delay(2500).fadeOut();

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



// Delete Task

$(document).on('click', '.delete-task', function() {
    hideModalAlerts();
    $('.dtr-bs-modal').modal('hide');

    var sysid = $('#HKST_ID').val();
    
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
                        toggleButton('.delete-task', 'btn-danger', 'btn-dark', false);
                        $('.delete-task').prop('disabled',true);
                        $('.task_div').find('tr.table-warning').removeClass(
                            'table-warning');
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

            $('#HKST_ID').val(''); 
            $('#HKST_DESCRIPTION').val('');    
            $('#HKST_TASK_ID').val(null).trigger('change');
            toggleButton('.delete-task', 'btn-danger', 'btn-dark', false);
            $('.delete-task').prop('disabled',true);
        }
    });
}


function loadTaskDetails(taskID) {

var url = '<?php echo base_url('/editTask') ?>';
$.ajax({
    url: url,
    type: "post",
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    data: {
        taskID: taskID
    },
    dataType: 'json',
    success: function(respn) {
        toggleButton('.delete-task', 'btn-dark', 'btn-danger', false);
        $('.delete-task').prop('disabled',false);
        $(respn).each(function(inx, data) {
            $.each(data, function(fields, datavals) {
                var field = $.trim(fields);
                var dataval = $.trim(datavals);             

                if (field == 'HKST_TASK_ID') {
                    $('#' + field).val(dataval).trigger('change');
                } else {
                    $('#' + field).val(dataval);
                }
            });
        });
    }
});
}

function getTasksCodes(){
    $.ajax({
      url: '<?php echo base_url('/taskcodeList')?>',
      type: "get",
      headers: {'X-Requested-With': 'XMLHttpRequest'},
       async:false,
      success:function(respn){        
        $('#HKST_TASK_ID').html(respn);
      }
  }); 
}
<?php echo isset($toggleButton_javascript) ? $toggleButton_javascript : ''; ?>

 // Display function clearFormFields -->
<?php echo isset($clearFormFields_javascript) ? $clearFormFields_javascript : ''; ?>

 // Display function blockLoader -->
<?php echo isset($blockLoader_javascript) ? $blockLoader_javascript : ''; ?>
</script>


<?=$this->endSection()?>