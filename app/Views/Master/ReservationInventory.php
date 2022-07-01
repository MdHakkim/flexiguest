<?= $this->extend("Layout/appView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>
<!-- Content wrapper -->
<div class="content-wrapper">
  <!-- Content -->
         

  <!-- Modal Window -->
  <div class="row">
<div class="col-md-6 mt-2">
<lable class="form-lable">Item Inventory</lable>

<div class="input-group mb-3">
<select name="itemsArray" id="itemsArray" class="select2 form-select" data-allow-clear="true" multiple>  

  </select>
  <?php  ?>
    <button type="button" onClick="getInventoryItems()"
        class="btn flxi_btn btn-sm btn-primary"><i class="fa fa-plus"
            aria-hidden="true"></i></button>
</div>
<div class="invalid-feedback"> Item required can't empty.</div>
</div>
</div>
 
  <!-- Modal Window Item Inventory -->

  <div class="modal fade" id="ItemInventory" data-backdrop="static" data-keyboard="false" aria-labelledby="popModalWindowlabel">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="popModalWindowlabel">Item Inventory</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <div id="wizard-validation" class="bs-stepper mt-2">
            <div class="bs-stepper-header">
              <div class="step" data-target="#select_items">
                <button type="button" class="step-trigger">
                  <span class="bs-stepper-circle">1</span>
                  <span class="bs-stepper-label">Items</span>
                </button>
              </div>
              <div class="line"></div>
              <div class="step" data-target="#item_availability">
                <button type="button" class="step-trigger" >
                  <span class="bs-stepper-circle">2</span>
                  <span class="bs-stepper-label" >Inventory Availability</span>
                </button>
              </div>
            </div>
            <div class="bs-stepper-content">             
            <form id="item-submit-form" onSubmit="return false">
                <div id="select_items" class="content">
                
                  <input type="hidden" name="RSV_ID" id="RSV_ID" class="form-control" />
                  <input type="hidden" name="RSV_PRI_ID" id="RSV_PRI_ID" class="form-control" />
                  <div class="row g-3">

                    <div class="col-md-5">
                      <div class="border rounded p-4 mb-3">
                      <div class="row mb-3">
                          <label for="RSV_ITM_CL_ID" class="col-form-label col-md-4"><b>Item Class *</b></label>
                          <div class="col-md-8">
                            <select id="RSV_ITM_CL_ID" name="RSV_ITM_CL_ID" class="select2 form-select form-select-lg">
                             
                            </select>
                          </div>
                        </div>
                        <div class="row mb-3">
                          <label for="RSV_ITM_ID" class="col-form-label col-md-4"><b>Items *</b></label>
                          <div class="col-md-8">
                            <select id="RSV_ITM_ID" name="RSV_ITM_ID" class="select2 form-select form-select-lg">
                              
                            </select>
                          </div>
                        </div>
                        <div class="row mb-3">
                          <label for="RSV_ITM_BEGIN_DATE" class="col-form-label col-md-4"><b>Start
                              Date *</b></label>
                          <div class="col-md-8">
                            <input class="form-control dateFieldItem" type="text" placeholder="d-Mon-yyyy" id="RSV_ITM_BEGIN_DATE" name="RSV_ITM_BEGIN_DATE" />
                          </div>
                        </div>
                        <div class="row mb-3">
                          <label for="RSV_ITM_END_DATE" class="col-form-label col-md-4"><b>End
                              Date *</b></label>
                          <div class="col-md-8">
                            <input class="form-control dateFieldItem" type="text" placeholder="d-Mon-yyyy" id="RSV_ITM_END_DATE" name="RSV_ITM_END_DATE" />
                          </div>
                        </div>

                        <div class="row mb-3">
                          <label for="RSV_ITM_QTY" class="col-form-label col-md-4"><b>Quantity *</b></label>
                          <div class="col-md-8">
                            <input type="number" name="RSV_ITM_QTY" id="RSV_ITM_QTY" class="form-control" min="1" step="1" placeholder="eg: 12" />
                          </div>
                        </div>
                        <div class="row mb-3">
                          <div class="col-md-8 float-right">
                            <button type="button" class="btn btn-success save-item-detail">
                              <i class="fa-solid fa-floppy-disk"></i>&nbsp; Save
                            </button>&nbsp;
                          </div>
                        </div>

                      </div>

                    </div>

                    <div class="col-md-7">
                    
                      <div class="border rounded p-4 mb-3">
                        <div class="col-md-6 mb-3">
                          <button type="button" class="btn btn-primary add-item-detail">
                            <i class="fa-solid fa-circle-plus"></i>&nbsp; Add New
                          </button>&nbsp;

                          <button type="button" class="btn btn-danger delete-item-detail">
                            <i class="fa-solid fa-ban"></i>&nbsp; Delete
                          </button>&nbsp;
                        </div>

                        <div class="table-responsive text-nowrap">
                          <table id="Inventory_Details" class="table table-bordered table-hover">
                            <thead>
                              <tr>
                               <th class="all">ID</th>
                                <th class="all">Items</th>
                                <th class="all">Start</th>
                                <th class="all">End</th>
                                <th class="all">Quantity</th>
                              </tr>
                            </thead>
                          </table>
                        </div>

                        <br />

                      


                      </div>
                    </div>
                    <div class="d-flex col-12 justify-content-between">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                      <button type="button" class="btn btn-primary btn-next">
                        <span class="d-none d-sm-inline-block me-sm-1">Next</span>
                        <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
                      </button>
                    </div>

                  </div>
                 
                </div>
                </form>
                <div id="item_availability" class="content">                
                <div class="card app-calendar-wrapper">
                <div class="row g-0">
                 
                  <!-- Calendar Sidebar -->
                  <div class="app-calendar-sidebar col" id="app-calendar-sidebar">
                    <div class="border-bottom p-4 my-sm-0 mb-3">
                      <div class="d-grid">
                        <button
                          class="btn btn-primary btn-toggle-sidebar"
                          
                        >
                          
                          <span class="align-middle">Available Items</span>
                        </button>
                      </div>
                    </div>
                    <div class="p-4">
                      <!-- inline calendar (flatpicker) -->
                      <div class="ms-n2">
                        <div class="inline-calendar"></div>
                      </div>

                      <hr class="container-m-nx my-4" />

                      <!-- Filter -->
                      <!-- <div class="mb-4">
                        <small class="text-small text-muted text-uppercase align-middle">Filter</small>
                      </div> -->

                       <!-- <div class="form-check mb-2">
                        <input
                          class="form-check-input select-all"
                          type="checkbox"
                          id="selectAll"
                          data-value="all"
                          checked
                        />
                        <label class="form-check-label" for="selectAll">View All</label>
                      </div>  -->

                      <div class="app-calendar-events-filter">
                        <!-- <div class="form-check form-check-danger mb-2">
                          <input
                            class="form-check-input input-filter"
                            type="checkbox"
                            id="select-personal"
                            data-value="personal"
                            checked
                          />
                          <label class="form-check-label" for="select-personal">Personal</label>
                        </div> -->
                        <div class="form-check mb-2">
                          <input
                            class="form-check-input input-filter"
                            type="checkbox"
                            id="select-business"
                            data-value="business"
                            checked
                          />
                          <label class="form-check-label" for="select-business">View All</label>
                        </div>
                        <!-- <div class="form-check form-check-warning mb-2">
                          <input
                            class="form-check-input input-filter"
                            type="checkbox"
                            id="select-family"
                            data-value="family"
                            checked
                          />
                          <label class="form-check-label" for="select-family">Family</label>
                        </div> -->
                        <!-- <div class="form-check form-check-success mb-2">
                          <input
                            class="form-check-input input-filter"
                            type="checkbox"
                            id="select-holiday"
                            data-value="holiday"
                            checked
                          />
                          <label class="form-check-label" for="select-holiday">Holiday</label>
                        </div> -->
                        <!-- <div class="form-check form-check-info">
                          <input
                            class="form-check-input input-filter"
                            type="checkbox"
                            id="select-etc"
                            data-value="etc"
                            checked
                          />
                          <label class="form-check-label" for="select-etc">ETC</label>
                        </div> -->
                      </div>
                    </div>
                  </div>
                  <!-- /Calendar Sidebar -->

                  <!-- Calendar & Modal -->
                  <div class="app-calendar-content col">
                    <div class="card shadow-none border-0">
                      <div class="card-body pb-0">
                        <!-- FullCalendar -->
                        <div id="calendar"></div>
                      </div>
                    </div>
                    <div class="app-overlay"></div>
                    <!-- FullCalendar Offcanvas -->
                    <div
                      class="offcanvas offcanvas-end event-sidebar"
                      tabindex="-1"
                      id="addEventSidebar"
                      aria-labelledby="addEventSidebarLabel"
                    >
                      <div class="offcanvas-header border-bottom">
                        <h6 class="offcanvas-title" id="addEventSidebarLabel">Show Item Details</h6>
                        <button
                          type="button"
                          class="btn-close text-reset"
                          data-bs-dismiss="offcanvas"
                          aria-label="Close"
                        ></button>
                      </div>
                      <div class="offcanvas-body">
                        <form class="event-form pt-0" id="eventForm" onsubmit="return false">
                          <div class="mb-3">
                            <label class="form-label" for="eventTitle">Title</label>
                            <input
                              type="text"
                              class="form-control"
                              id="eventTitle"
                              name="eventTitle"
                              placeholder="Event Title"
                            />
                          </div>
                          <!-- <div class="mb-3">
                            <label class="form-label" for="eventLabel">Item Class</label>
                            <select class="select2 select-event-label form-select" id="eventLabel" name="eventLabel">
                              <option data-label="primary" value="Business" selected>Business</option>
                              <option data-label="danger" value="Personal">Personal</option>
                              <option data-label="warning" value="Family">Family</option>
                              <option data-label="success" value="Holiday">Holiday</option>
                              <option data-label="info" value="ETC">ETC</option>
                            </select>
                          </div> -->
                          <div class="mb-3">
                            <label class="form-label" for="eventStartDate">Start Date</label>
                            <input
                              type="text"
                              class="form-control"
                              id="eventStartDate"
                              name="eventStartDate"
                              placeholder="Start Date"
                            />
                          </div>
                          <div class="mb-3">
                            <label class="form-label" for="eventEndDate">End Date</label>
                            <input
                              type="text"
                              class="form-control"
                              id="eventEndDate"
                              name="eventEndDate"
                              placeholder="End Date"
                            />
                          </div>
                          <div class="mb-3">
                            <label class="switch">
                              <input type="checkbox" class="switch-input allDay-switch" />
                              <span class="switch-toggle-slider">
                                <span class="switch-on"></span>
                                <span class="switch-off"></span>
                              </span>
                              <span class="switch-label">All Day</span>
                            </label>
                          </div>
                        
                          
                          <div class="mb-3">
                            <label class="form-label" for="eventDescription">Description</label>
                            <textarea class="form-control" name="eventDescription" id="eventDescription"></textarea>
                          </div>


                          <div class="d-flex justify-content-start justify-content-sm-between my-4 mb-3" style="display:none !important">
                            <div>
                              <button type="submit" class="btn btn-primary btn-add-event me-1 me-sm-3">Add</button>
                              <button type="submit" class="btn btn-primary btn-update-event d-none me-1 me-sm-3">
                                Update
                              </button>
                              
                            </div>
                            <div><button class="btn btn-label-danger btn-delete-event d-none">Delete</button></div>
                          </div>

                          
                        </form>
                      </div>
                    </div>
                  </div>
                  <!-- /Calendar & Modal -->
                </div>
              </div>
                </div>

              
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- /Modal window -->
  <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->
     <!-- Core JS -->

     


<style>
  .fc-list-event-time{
    visibility: hidden;
    width:0px !important
  }
</style>
<script>
  $(document).ready(function() { 
      showInventoryItems();
      itemClassList();
      //itemInventoryClassList();
      var dateToday = new Date();
      $('.dateField').datepicker({
        format: 'dd-M-yyyy',
        autoclose: true,
        startDate: '-0m',
        onSelect: function() {
          $(this).change();
        }
      });
      linkMode = 'EX';

  });

// Update existing Inventory Items Detail
  /*
  $(document).on('click', '.save-item-detail', function() {

    submitDetailsForm('item-submit-form');
  });
  */
 
  // Add / Edit Inventory Items Detail

  function submitDetailsForm(id) {
    hideModalAlerts();
    var formSerialization = $('#' + id).serializeArray();
    var url = '<?php echo base_url('/updateInventoryItems') ?>';
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
          mcontent = '<li>Item Combination already exists</li>';
          showModalAlert('error', mcontent);
        }
        else if (response != '1') {
         
          var ERROR = respn['RESPONSE']['ERROR'];
          var mcontent = '';
          $.each(ERROR, function(ind, data) {
            
            mcontent += '<li>' + data + '</li>';
          });
          showModalAlert('error', mcontent);
        } else {
          var alertText = $('#RSV_PRI_ID').val() == '' ?
            '<li>The new Inventory Item has been created</li>' :
            '<li>The Inventory Item has been updated</li>';
            
            hideModalAlerts();
            if($('#RSV_PRI_ID').val() == ''){
              item_id = $('#RSV_ITM_ID').val();
              item_text = $('#RSV_ITM_ID option:selected').text();

              ///Append the items to dropdown
                var data = {
                  id: item_id,
                  text: item_text
              };

              var newOption = new Option(data.text, data.id, false, false);
              $('#itemsArray').append(newOption).trigger('change');
              $('#itemsArray').select2('destroy').find('option').prop('selected', 'selected').end().select2();
              
            } 



          showModalAlert('success', alertText);
          clearFormFields('#select_items');
          $("#RSV_ITM_ID").html('');
          

          if (respn['RESPONSE']['OUTPUT'] != '') {         

            $('#RSV_PRI_ID').val(respn['RESPONSE']['OUTPUT']);
            

            showInventoryItems($('#RSV_ID').val());
            clearFormFields('#select_items');
          }
        }
      }
    });
  }

  // Add new item Detail

  $(document).on('click', '.add-item-detail', function() {
    hideModalAlerts();
    $('.dtr-bs-modal').modal('hide');
    $('#IT_CL_ID,#ITM_ID').html('<option value="">Select</option>');
    bootbox.dialog({
      message: "Do you want to add a new item Detail?",
      buttons: {
        ok: {
          label: 'Yes',
          className: 'btn-success',
          callback: function(result) {
            if (result) {
              clearFormFields('#select_items');
              $('#Inventory_Details').find('tr.table-warning').removeClass('table-warning');

              //Disable Delete button
             toggleButton('.delete-item-detail', 'btn-danger', 'btn-dark', true);

              showModalAlert('info',
                'Fill in the form and click the \'Save\' button to add the new item Detail'
              );
              //$('#infoModal').delay(2500).fadeOut();
              
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


  // Delete Inventory Items Detail

  $(document).on('click', '.delete-item-detail', function() {
    hideModalAlerts();
    $('.dtr-bs-modal').modal('hide');
    var RSV_PRI_ID = $('#Inventory_Details').find("tr.table-warning").data("itemid");
    var delete_id = $(this).attr("data-val");
    var delete_option = $('#itemsArray option[value="'+ $('#RSV_ITM_ID').val() +'"]');
    delete_option.prop('selected', false);
    $('#itemsArray option[value="'+ $('#RSV_ITM_ID').val() +'"]').remove();
    $('#itemsArray').trigger('change.select2');

   

    bootbox.confirm({
      message: "Inventory Items is active. Do you want to Delete?",
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
            url: '<?php echo base_url('/deleteItemInventory') ?>',
            type: "post",
            data: {
              RSV_PRI_ID: RSV_PRI_ID
            },
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            },
            dataType: 'json',
            success: function(respn) {
              var response = respn['SUCCESS'];
              if (response == '0') {
                clearFormFields('#select_items');
                showModalAlert('error',
                  '<li>The Inventory Items cannot be deleted</li>');
              } else {
                blockLoader('#select_items');
                showModalAlert('warning',
                  '<li>The Inventory Items has been deleted</li>');
                clearFormFields('#select_items');
                showInventoryItems();
              }
            }
          });
        }
      }
    });
  });


  function showInventoryItems(itemID) {
    $('#Inventory_Details').find('tr.table-warning').removeClass('table-warning');
    
    $('#Inventory_Details').DataTable({
      'processing': true,
      async: false,
      'serverSide': true,
      'serverMethod': 'post',
      'ajax': {
        'url': '<?php echo base_url('/showInventoryItems') ?>',
        'data': {
          "RSV_PRI_ID": itemID
        }
      },
      'columns': [
        {        
          data: 'RSV_PRI_ID',
          'visible':false
        },{
            
          data: 'RSV_ITM_ID',
                     render: function(data, type, full, meta) {
                         if(full['ITM_CODE'] != null)
                         return full['ITM_CODE']+' | '+full['ITM_NAME'];
                         else
                         return '';
                    }
        },
        {
          data: 'RSV_ITM_BEGIN_DATE'
        },
        {
          data: 'RSV_ITM_END_DATE'
        },
        {
          data: 'RSV_ITM_QTY'
        },

      ],
      "order": [
            [1, "asc"]
        ],
        'createdRow': function(row, data, dataIndex) {
            
            $(row).attr('data-itemid', data['RSV_PRI_ID']);
            

            if (dataIndex == 0) {
              
                $(row).addClass('table-warning');
                loadInventoryDetails(data['RSV_PRI_ID']);
            }
        },


      destroy: true,
      "ordering": true,
      "searching": false,
      autowidth: true,
      responsive: true
    });
  }

  // Show Package Code Detail

function loadInventoryDetails(itemID) { 

var url = '<?php echo base_url('/showInventoryDetails')?>';
$.ajax({
    url: url,
    async: false,
    'processing': true,   
    'serverSide': true,   
    'serverMethod': 'get',
    data: {
      RSV_PRI_ID: itemID
    },
    dataType: 'json',
    success: function(respn) {

        //Enable Repeat and Delete buttons
        toggleButton('.delete-item-detail', 'btn-dark', 'btn-danger', false);
      

        $(respn).each(function(inx, data) {
            $.each(data, function(fields, datavals) {
                var field = $.trim(fields); 
                var dataval = $.trim(datavals); 
                  if(field == 'RSV_ITM_ID')  {  
                    class_val = dataval;                            
                  }else if(field == 'RSV_ITM_CL_ID')  {                                                                              
                    $('#' + field).val(dataval).trigger('change',class_val);                           
                      
                  }else {
                    $('#' + field).val(dataval);
                  }
            });
        });
    }
});
}


function itemClassList(){
    $.ajax({
        url: '<?php echo base_url('/itemClassList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        async:false,
        // dataType:'json',
        success:function(respn){
          
          $('#RSV_ITM_CL_ID').html(respn);
        }
    });
  }

  $("#RSV_ITM_CL_ID").change(function(e,param = 0) {  
      
        var item_class_id = $(this).val();
        $.ajax({
        url: '<?php echo base_url('/itemList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{item_class_id:item_class_id,item_id:param},
        // dataType:'json',
        success:function(respn){
          
          $('#RSV_ITM_ID').html('<option value="">Select Item</option>');          
          $('#RSV_ITM_ID').html(respn);
        }
    });
  });


  function getInventoryItems() {
    $('#ItemInventory').modal('show');

  }

  function itemInventoryClassSingle(){
    $.ajax({
        url: '<?php echo base_url('/itemInventoryClassSingle')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        async:false,
        // dataType:'json',
        success:function(respn){
          
          $('#eventLabel').html(respn);
        }
    });
  }



  let date = new Date();
let nextDay = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);
// prettier-ignore
let nextMonth = date.getMonth() === 11 ? new Date(date.getFullYear() + 1, 0, 1) : new Date(date.getFullYear(), date.getMonth() + 1, 1);
// prettier-ignore
let prevMonth = date.getMonth() === 11 ? new Date(date.getFullYear() - 1, 0, 1) : new Date(date.getFullYear(), date.getMonth() - 1, 1);



    // $row['ITM_AVAIL_TO_TIME']
    // $row['ITM_AVAIL_FROM_TIME']
  let events = [
  <?php 
  if(!empty($itemAvail)){ 
  foreach ($itemAvail as $row) {
   
      $start= $row['ITM_DLY_BEGIN_DATE'];
      $startDate= date("Y-m-d",strtotime($start)); 
  
      $end = $row['ITM_DLY_END_DATE'];
      $endDate = date("Y-m-d",strtotime($end));

      $startTime= date("H:i A",strtotime($row['ITM_AVAIL_FROM_TIME'])); 
  
      $endTime = date("H:i A",strtotime($row['ITM_AVAIL_TO_TIME']));
   

    ?>  
  ,{
    id: '<?php echo $row['ITM_ID'] ?>',
    url: '',
    title: '<?php echo $startTime.' - '.$endTime.'  | '.$row['ITM_CODE'].' - '. $row['ITM_CODE'] ?>',
    start: '<?php echo $startDate; ?>',
    end: '<?php echo $endDate; ?>',
    allDay: false,
    description:'',
    extendedProps: {
      
      calendar: 'Business'
    }
  },

  <?php } 
  } ?>
 
];

  


  // Display function toggleButton
<?php echo isset($toggleButton_javascript) ? $toggleButton_javascript : ''; ?>

// Display function clearFormFields
<?php echo isset($clearFormFields_javascript) ? $clearFormFields_javascript : ''; ?>

// Display function blockLoader
<?php echo isset($blockLoader_javascript) ? $blockLoader_javascript : ''; ?>
</script>


<?= $this->endSection() ?>