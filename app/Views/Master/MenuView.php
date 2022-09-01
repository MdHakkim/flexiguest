<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>

<style>

.disabled {
  pointer-events: none;
  cursor: default;
  opacity: 0.6;
}
</style>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Masters /</span> Menu</h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid table-responsive" style="padding: 16px 16px 6px 16px">
                <table id="dataTable_view" class="dt-responsive table table-striped display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Code</th>
                            <th>Name</th>  
                            <th>URL</th>  
                            <th>Parent Menu</th>
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
                    <h4 class="modal-title" id="popModalWindowlabel"> Menu</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="submitForm" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <input type="hidden" name="MENU_ID" id="MENU_ID" class="form-control" />

                            <div class="col-md-8">
                                <lable class="form-lable"><b>Menu *</b></lable>

                                <select id="PARENT_MENU_ID" name="PARENT_MENU_ID" class="select2 form-select form-select-lg" data-allow-clear="true" required>
                                       
                                </select>                                
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><b> Code *</b></label>
                                <input type="text" name="MENU_CODE" id="MENU_CODE"
                                    class="form-control bootstrap-maxlength" maxlength="10" placeholder="eg: ADDRESV"
                                    required />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><b> Name *</b></label>
                                <input type="text" name="MENU_NAME" id="MENU_NAME"
                                    class="form-control bootstrap-maxlength" maxlength="50"
                                    placeholder="eg: Add Reservation" required />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><b> Description *</b></label>
                                <textarea class="form-control" name="MENU_DESC" id="MENU_DESC" rows="1"></textarea> 
                                
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b> URL </b></label>
                                <input type="text" name="MENU_URL" id="MENU_URL"
                                    class="form-control bootstrap-maxlength" maxlength="50"
                                    placeholder="reservation" required />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><b> Icon</b></label>
                                <input type="text" name="MENU_ICON" id="MENU_ICON" class="form-control"
                                    placeholder="" />
                            </div> 
                        </div>
                        <div class="row g-3 mt-1">
                            <div class="col-md-4 ">
                                <label class="form-label"><b> Display Sequence *</b></label>
                                <input type="number" name="MENU_DIS_SEQ" id="MENU_DIS_SEQ" class="form-control"
                                    min="0" placeholder="eg: 3" />
                            </div>     
                            <div class="col-md-4">
                            <div class="text-left mt-4">
                                <div class="col-md-12">
                                    <label class="switch switch-md">
                                        <input id="SHOW_IN_MENU" name="SHOW_IN_MENU" type="checkbox" value="1"
                                            class="switch-input" />
                                        <span class="switch-toggle-slider">
                                            <span class="switch-on">
                                                <i class="bx bx-check"></i>
                                            </span>
                                            <span class="switch-off">
                                                <i class="bx bx-x"></i>
                                            </span>
                                        </span>
                                        <span class="switch-label"><b>Show in Menu </b></span>
                                    </label>
                                </div>
                            </div>
                            </div>
                         </div>

                         <div class="row g-3 mt-1">
                                
                            <div class="col-md-4">
                            <div class="text-left mt-4">
                                <div class="col-md-12">
                                    <label class="switch switch-lg">
                                        <input id="MENU_STATUS" name="MENU_STATUS" type="checkbox" value="1"
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

    
    <!-- /Modal window -->

    <div class="content-backdrop fade"></div>
</div>

<!-- Content wrapper -->
<script>
var compAgntMode = '';
var linkMode = '';

$(document).ready(function() {
    menuList();
    linkMode = 'EX';

    $('#dataTable_view').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/MenuView')?>'
        },
        'columns': [{
                data: 'MENU_ID'
            },
            {
                data: 'MENU_CODE'
            },
            {
                data: 'MENU_NAME'
            },
            {
                data: 'MENU_URL'
            },
            {
                data: 'P_MENU_NAME'
            },            
            {
                data: 'MENU_DIS_SEQ'
            },
            {
                data: null,
                className: "text-center",
                "orderable": false,
                render: function(data, type, row, meta) {
                
                    if(data['MENU_CODE'] == "MASTR"){
                        return (
                            '<div class="d-inline-block">' +
                            '<a class="disabled" href="javascript:;" data_sysid="' + data['MENU_ID'] +
                            '" class="dropdown-item editWindow text-primary"><i class="fa-solid fa-pen-to-square"></i> Edit</a>'+
                            '</div>'
                        );
                    }else{
                        return (
                            '<div class="d-inline-block">' +
                            '<a href="javascript:;" data_sysid="' + data['MENU_ID'] +
                            '" class="dropdown-item editWindow text-primary"><i class="fa-solid fa-pen-to-square"></i> Edit</a>'+                      
                            
                            '</div>'
                        );
                    }
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
        
        },
        {
            width: "8%"
        
        }, {
            width: "8%"
        }],
        "order": [
            [0, "asc"]
        ],
        destroy: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal({
                    header: function(row) {
                        var data = row.data();
                        return 'Details of ' + data['MENU_CODE'];
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

// Show Add Menu Form

function addForm() {
    $("#MENU_CODE").prop("readonly", false);
   $(':input', '#submitForm').not('[type="checkbox"]').val('').prop('selected', false);
   $('.switch-input').prop('checked', false);
   $('.select2').val(null).trigger('change');
   $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
   $('#popModalWindowlabel').html('Add new menu');   
   menuList();
   $('#popModalWindow').modal('show');    
}


// // Show Copy Submenu Form

// $(document).on('click', '.copyWindow', function() {

//     $('.dtr-bs-modal').modal('hide');

//     var sysid = $(this).attr('data_sysid');
//     var submenucode = $(this).attr('data_submenucode');

//     $('#main_SubMenu_ID').val(sysid);

//     $('#copyModalWindowlabel').html('Create menu copies of \'' + submenucode + '\'');

//     //Reset repeated fields every time modal is loaded
//     $('[data-repeater-item]').slice(1).empty();
//     $('#form-repeater-1-1').val("");

//     $('#copyModalWindow').modal('show');

// });


// Add New or Edit submenu Function

function submitForm(id) {
    hideModalAlerts();
    var formSerialization = $('#' + id).serializeArray();
    var url = '<?php echo base_url('/insertMenu')?>';
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
                var alertText = $('#MENU_ID').val() == '' ? '<li>The new menu \'' + $('#MENU_CODE')
                    .val() + '\' has been created</li>' : '<li>The Menu \'' + $('#MENU_CODE').val() +
                    '\' has been updated</li>';
                showModalAlert('success', alertText);
                menuList();
                $('#popModalWindow').modal('hide');
                $('#dataTable_view').dataTable().fnDraw();
                location.reload();
               
                
            }
        }
    });
}


// Show Edit menu Form

$(document).on('click', '.editWindow', function() {
    $('.dtr-bs-modal').modal('hide');
    var sysid = $(this).attr('data_sysid');
    $('#popModalWindowlabel').html('Edit Menu');
    $("#MENU_CODE").prop("readonly", true);
    $('#popModalWindow').modal('show');

    var url = '<?php echo base_url('/editMenu')?>';
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
                    if (field == 'PARENT_MENU_ID') {                                        
                        $('#' + field).val(dataval).trigger('change');
                    } 
                    else if ($('#' + field).attr('type') == 'checkbox') {
                        $('#' + field).prop('checked', dataval == 1 ? true : false);
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




// Delete menu

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
                    url: '<?php echo base_url('/deleteMenu')?>',
                    type: "post",
                    data: {
                        sysid: sysid
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    dataType: 'json',
                    success: function(respn) {
                        
                        var response = respn['RESPONSE'];                        
                        if (respn['SUCCESS'] == 0) {
                            var ERROR = response['ERROR'];
                            var mcontent = '';
                            mcontent += '<li>' + ERROR + '</li>';
                            showModalAlert('error', mcontent);
                        }
                        else{
                            showModalAlert('warning',
                                '<li>The menu has been deleted</li>');
                            $('#dataTable_view').dataTable().fnDraw();
                        }
                    }
                });
            }
        }
    });
});



function menuList() { 

$.ajax({
      url: '<?php echo base_url('/menuList')?>',
      type: "get",
      headers: {'X-Requested-With': 'XMLHttpRequest'},
       async:false,
      success:function(respn){        
        $('#PARENT_MENU_ID').html(respn);
      }
  }); 
  
}

</script>

<?=$this->endSection()?>