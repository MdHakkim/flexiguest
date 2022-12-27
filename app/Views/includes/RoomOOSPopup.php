<!-- Assign Room Modal -->
<style>

.light-style .swal2-container {
    z-index: 1110;
}
</style>
        <!-- OOO/OOS Modal window -->
        <div class="modal fade" id="OOOSRoom" data-backdrop="static" data-keyboard="false"
        aria-labelledby="popModalWindowlabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">OOO / OOS </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="OOS_Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ooos-submit-form" onSubmit="return false">
                        <div id="OOOS_DIV" class="content">
                            <input type="hidden" name="OOOS_ID" id="OOOS_ID" class="form-control" />
                            <input type="hidden" name="Room_ID" id="Room_ID" class="form-control" />
                            <div class="row g-3">
                                <div class="border rounded p-4 mb-3">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">                                        
                                            <label for="ROOMS"
                                                class="col-form-label col-md-5"><b>Room *</b></label>
                                            <select id="ROOMS" name="ROOMS"
                                                class="select2 form-select form-select-lg"></select>                                       
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="STATUS_FROM_DATE" class="col-form-label col-md-4"><b>
                                                    From Date</b></label>
                                            <input type="text" name="STATUS_FROM_DATE" id="STATUS_FROM_DATE"
                                                class="form-control" />
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="STATUS_TO_DATE" class="col-form-label col-md-4"><b>
                                                    Through Date</b></label>
                                            <input type="text" name="STATUS_TO_DATE" id="STATUS_TO_DATE" class="form-control"
                                                 />
                                        </div>
                                        <div class="col-md-4 mb-3">                                        
                                        <label for="ROOM_STATUS"
                                            class="col-form-label col-md-5"><b>Status </b></label>
                                        <select id="ROOM_STATUS" name="ROOM_STATUS"
                                            class="select2 form-select form-select-lg"></select>                                   
                                       </div>

                                       <div class="col-md-4 mb-3">                                        
                                        <label for="ROOM_RETURN_STATUS"
                                            class="col-form-label col-md-5"><b>Return Status </b></label>
                                        <select id="ROOM_RETURN_STATUS" name="ROOM_RETURN_STATUS"
                                            class="select2 form-select form-select-lg">
                                          </select>                                   
                                       </div>
                                       <div class="col-md-4 mb-3">                                        
                                        <label for="ROOM_CHANGE_REASON"
                                            class="col-form-label col-md-5"><b>Change Reason *</b></label>
                                        <select id="ROOM_CHANGE_REASON" name="ROOM_CHANGE_REASON"
                                            class="select2 form-select form-select-lg">
                                          </select>                                   
                                       </div>
                                       <div class="col-md-8 mb-3">                                        
                                        <label for="ROOM_REMARKS"
                                            class="col-form-label col-md-5"><b>Remarks</b></label>
                                               <textarea name="ROOM_REMARKS" id="ROOM_REMARKS" class="form-control" rows="4"></textarea>                                               
                                       </div>
                                    </div>

                                    <div class="row g-3 ">
                                        <div class="col-md-3 mb-3">
                                            <div class="col-md-8 float-right">
                                                <button type="button" class="btn btn-success save-roomstatus-details">
                                                    <i class="fa-solid fa-floppy-disk"></i>&nbsp; Save
                                                </button>&nbsp;
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="border rounded p-4 mb-3">
                                        <div class="col-md-6 mb-3">
                                            <button type="button" class="btn btn-primary add-room-status">
                                                <i class="fa-solid fa-circle-plus"></i>&nbsp; Add New
                                            </button>&nbsp;

                                            <button type="button" class="btn btn-danger delete-room-status">
                                                <i class="fa-solid fa-ban"></i>&nbsp; Delete
                                            </button>&nbsp;
                                        </div>

                                        <div class="table-responsive text-nowrap">
                                            <table id="Status_Details" class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                      <th></th>
                                                      <th class="all">Room</th>
                                                      <th class="all">Status</th>
                                                      <th class="all">From Date</th>
                                                      <th class="all">Through Date</th>
                                                      <th class="all">Return As</th>
                                                      <th class="all">Reason</th>
                                                      <th class="all">Remarks</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <br />
                                    </div>
                                </div>
                                <div class="d-flex col-12 justify-content-between">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal" id="OOS_Close">Close</button>
                                </div>

                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- /Modal OOS window --> 

<script>


        //////// OOO/OOS Functions

// Click ROOM OOS menu link
$(document).on('click', '.hkRoomOOS,#showRoomOSModal', function() {

$('.hkRoomOOS').parent().addClass('active');
$('#OOOSRoom').modal('show');
    roomsList();
    roomsStatus();
    roomsReturnStatus();
    roomsChangeReason();    
    showRoomStatus();
    var today = moment().format('DD-MM-YYYY');             

    $('#STATUS_FROM_DATE').datepicker({
        format: 'd-M-yyyy',
        autoclose: true,
        
    }).datepicker("setDate", today);

    $('#STATUS_TO_DATE').datepicker({
        format: 'd-M-yyyy',
        autoclose: true,
        
    }).datepicker("setDate", today);
                        
});

// Close ROOM OOS popup
$(document).on('hide.bs.modal', '#OOOSRoom', function() {
$('.hkRoomOOS').parent().removeClass('active');
});


// $(document).on('click', '#showRoomOSModal', function() {
//     $('#OOOSRoom').modal('show');    
//     roomsList();
//     roomsStatus();
//     roomsReturnStatus();
//     roomsChangeReason();    
//     showRoomStatus();
// });


function roomsList() {
    $.ajax({
        url: '<?php echo base_url('/roomPlanList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        async: false,
        success: function(respn) {
            $('#ROOMS').html(respn);
        }
    });
}

function roomsStatus() {
    $.ajax({
        url: '<?php echo base_url('/roomsStatusList') ?>',
        type: "post",
        data:{type:1},
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        async: false,
        success: function(respn) {
            $('#ROOM_STATUS').html(respn);
        }
    });
}

function roomsReturnStatus() {
    $.ajax({
        url: '<?php echo base_url('/roomsStatusList') ?>',
        type: "post",
        data:{type:2},
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        async: false,
        success: function(respn) {
            $('#ROOM_RETURN_STATUS').html(respn);
        }
    });
}

function roomsChangeReason() {
    $.ajax({
        url: '<?php echo base_url('/roomsChangeReasonList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        async: false,
        success: function(respn) {
            $('#ROOM_CHANGE_REASON').html(respn);
        }
    });
}

$(document).on('click', '.add-room-status', function() {
    hideModalAlerts();
    $('.dtr-bs-modal').modal('hide'); 

    bootbox.dialog({
        message: "Do you want to add room status?",
        buttons: {
            ok: {
                label: 'Yes',
                className: 'btn-success',
                callback: function(result) {
                    if (result) {
                      clearFormFields('#OOOS_DIV');
                      $("#ROOM_REMARKS").val('');
                      var today = moment().format('DD-MM-YYYY');   
                      $('#STATUS_FROM_DATE').datepicker("setDate", today);
                      $('#STATUS_TO_DATE').datepicker("setDate", today);
                      
                        $('#Status_Details').find('tr.table-warning').removeClass(
                            'table-warning');

                        //Disable Delete button
                        toggleButton('.delete_room_status', 'btn-danger', 'btn-dark',
                            true);

                        showModalAlert('info',
                            'Fill in the form and click the \'Save\' button to update the status'
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

$(document).on('click', '.save-roomstatus-details', function() {
  $('#ROOMS').attr("disabled", false);
    hideModalAlerts();
    var formSerialization = $('#ooos-submit-form').serializeArray();
    var url = '<?php echo base_url('/insertRoomOOS') ?>';
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
            if (response == '2') {
                mcontent = '<li>Something went wrong</li>';
                showModalAlert('error', mcontent);
            } else if (response != '1') {
                var ERROR = respn['RESPONSE']['ERROR'];
                var mcontent = '';
                $.each(ERROR, function(ind, data) {
                    //console.log(data, "SDF");
                    mcontent += '<li>' + data + '</li>';
                });
                showModalAlert('error', mcontent);
            } else {
                var alertText = $('#OOOS_ID').val() == '' ?
                    '<li>Successfully added</li>' :
                    '<li>Successfully updated</li>';
                hideModalAlerts();
                showModalAlert('success', alertText);


                if (respn['RESPONSE']['OUTPUT'] != '') {                  
                    $('#OOOS_ID').val(respn['RESPONSE']['OUTPUT']);
                    showRoomStatus();
                    clearFormFields('#OOOS_DIV');
                }
            }
        }
    });
});


function showRoomStatus() {
    $('#ROOMS').prop("disabled", false);
    $('#Status_Details').find('tr.table-warning').removeClass('table-warning');

    $('#Status_Details').DataTable({
        'processing': true,
        async: false,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/roomOOSList') ?>',
           
        },
        'columns': [{
                data: 'OOOS_ID',
                'visible': false
            }, {

                data: 'RM_NO',
                render: function(data, type, full, meta) {
                    if (full['RM_NO'] != null)
                        return full['RM_NO'];
                    else
                        return '';
                }
            },
            {
                data: 'RSM_RM_STATUS_CODE'
            },

            {
                data: 'STATUS_FROM_DATE'
            },
            {
                data: 'STATUS_TO_DATE'
            },
            
            {
                data: 'SM_RM_STATUS_CODE'
            },
            {

              data: 'RM_STATUS_CHANGE_CODE',
              render: function(data, type, full, meta) {
                  if (full['RM_STATUS_CHANGE_CODE'] != null)
                      return full['RM_STATUS_CHANGE_CODE']+' | '+full['RM_STATUS_CHANGE_DESC'];
                  else
                      return '';
              }
            },
            {
                data: 'ROOM_REMARKS'
            },

        ],
        "order": [
            [1, "asc"]
        ],
        'createdRow': function(row, data, dataIndex) {

            $(row).attr('data-status_id', data['OOOS_ID']);

            if (dataIndex == 0) {
                //$(row).addClass('table-warning');
                //loadRoomStatusDetails(data['OOOS_ID']);
            }
        },


        destroy: true,
        "ordering": true,
        "searching": false,
        autowidth: true,
        responsive: true
    });
}



function loadRoomStatusDetails(OOOS_ID) {
  $('#ROOMS').prop("disabled", true);
    var url = '<?php echo base_url('/showRoomStatusDetails') ?>';
    $.ajax({
        url: url,
        type: "post",
        async: false,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        data: {
          OOOS_ID: OOOS_ID
        },
        dataType: 'json',
        success: function(respn) {
          console.log(respn)
            toggleButton('.delete-room-status', 'btn-dark', 'btn-danger', false);
            $(respn).each(function(inx, data) {
                $.each(data, function(fields, datavals) {                   
                    var field = $.trim(fields);
                    var dataval = $.trim(datavals);                
                    
                  
                   if ( field == 'STATUS_FROM_DATE' || field == 'STATUS_TO_DATE' ){
                        $('#' + field).datepicker("setDate", new Date(dataval)); 
                    } 
                   else if (field == 'ROOMS' || field == 'ROOM_STATUS' || field == 'ROOM_RETURN_STATUS' || field == 'ROOM_CHANGE_REASON') {
                        $('#' + field).val(dataval).trigger('change');
                        
                        if(field == 'ROOMS')  $('#Room_ID').val(dataval);
                    } 
                   else {
                        $('#' + field).val(dataval);
                    }
                });
            });
        }
    });
}

// Delete status
$(document).on('click', '.delete-room-status', function() {
    hideModalAlerts();
    $('.dtr-bs-modal').modal('hide');
    var status_id = $('#Status_Details').find("tr.table-warning").data("status_id");
    if(status_id > 0){
    bootbox.confirm({
        message: "Status is active. Do you want to Delete?",
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
                    url: '<?php echo base_url('/deleteRoomOOS') ?>',
                    type: "post",
                    data: {
                      OOOS_ID: status_id,
                      
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    dataType: 'json',
                    success: function(respn) {
                        var response = respn['SUCCESS'];
                        if (response == '0') {
                            clearFormFields('#OOOS_DIV');
                            showModalAlert('error',
                                '<li>The status cannot be deleted</li>');
                            $('#warningModal').delay(2500).fadeOut();
                        } else {
                            clearFormFields('#OOOS_DIV');
                            blockLoader('#OOOSRoom');
                            showModalAlert('warning',
                                '<li>The status has been deleted</li>');
                            $('#warningModal').delay(2500).fadeOut(); 
                            showRoomStatus();
                        }
                    }
                });
            }
        }
    });
  }else{
    showModalAlert('warning',
            '<li>Please select a status</li>');
        $('#warningModal').delay(2500).fadeOut(); 
  }

});



$(document).on('click', '#Status_Details > tbody > tr', function() {
    $('#Status_Details').find('tr.table-warning').removeClass('table-warning');
    $(this).addClass('table-warning');
    $.when(loadRoomStatusDetails($(this).data('status_id')))
        .done(function() {})
        .done(function() {
            blockLoader('#OOOS_DIV');
        });
});

function toggleButton(elem, currentClass, replaceClass, disabled = false) {
                    if ($(elem).hasClass(currentClass))
                        $(elem).removeClass(currentClass).addClass(replaceClass);
                
                    $(elem).prop('disabled', disabled);
                }
function clearFormFields(elem) {

        var formSerialization = $(elem).find("input,select,textarea").serialize();
        // alert(formSerialization);
    
        $(elem).find('input,select,textarea').each(function() {
    
            if ($(this).hasClass('dateField')) {
                $(this).datepicker("setDate", new Date());
                return true;
            }
    
            switch ($(this).attr('type')) {
                case 'password':
                case 'text':
                case 'textarea':
                case 'file':
                case 'date':
                case 'number':
                case 'tel':
                case 'date':
                case 'hidden':
                case 'email':
                        $(this).val('');
                    break;
                case 'checkbox':
                    $(this).prop('checked', false);
                    break;
                case 'radio':
                    //this.checked = false;
                    break;
                case 'submit':
                        break;
                default:
                    if (!$(this).closest(".table-responsive").length)
                        $(this).val(null).trigger('change');
                    break;
            }
        });
    }
   


    function blockLoader()
    {
        return function blockLoader(elem, duration = 500, alert = '') {
                    $(elem).block({
                        message: '<div class=\"spinner-border text-white\" role=\"status\"></div>',
                        timeout: duration,
                        css: {
                            backgroundColor: 'transparent',
                            border: '0'
                        },
                        overlayCSS: {
                            opacity: 0.5
                        },
                        onUnblock: function() {
                
                        }
                    });
                }
            }

</script>