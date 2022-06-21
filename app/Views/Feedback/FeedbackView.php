<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">General</span> Feedback</h4>

              <!-- DataTable with Buttons -->
              <div class="card">
                <!-- <h5 class="card-header">Responsive Datatable</h5> -->
                <div class="container-fluid p-3">
                  <table id="dataTable_view" class="table table-striped">
                    <thead>
                      <tr>
                        <th>Feedback By</th>
                        <th>Ratings</th>
                        <th>Description</th>
                        <th>Created Date</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    
                  </table>
                </div>
              </div>
             
              <!--/ Multilingual -->
            </div>
            <!-- / Content -->

            <!-- Modal Window -->

            <div class="modal fade" id="reservationChild" tabindex="-1" aria-labelledby="reservationChildLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="reservationChildLabel">Feedback List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form id="maintenanceForm" enctype="multipart/form-data" novalidate >
                      <div class="window-1" id="window1">
                        <div class="row g-3">

                        <div class="col-md-4 mt-0">
                            <label class="form-label">Apartments</label>
                            <select id="MAINT_ROOM_NO" class=" select2 form-select" name="MAINT_ROOM_NO" data-allow-clear="true">
                              <option value="">Select</option>
                            </select>
                          </div>
                          
                        
                        <div class="col-md-4 mt-0">
                            <label class="form-label">InHouseBooking</label>
                            <select name="InHouseBooking" id="InHouseBooking" class=" select2 form-select" data-allow-clear="true">
                              <option value="">Select</option>
                            </select>
                          </div> 

                          <div class="col-md-4 flxi_ds_flx">
                          <label class="form-label">Type</label>
                            <div class="form-radio mt-4 me-1">
                              <input class="form-radio-input flxCheckBox" type="radio" value="Bulb/Key" name="MAINT_TYPE" id="MAINT_TYPE1">
                              <label class="form-radio-lable" for="defaultCheck1"> Bulb/Key </label>
                            </div>
                            <div class="form-radio mt-4 me-1">
                              <input class="form-radio-input flxCheckBox" type="radio" value="Maintenance Request" name="MAINT_TYPE" id="MAINT_TYPE2">
                              <label class="form-radio-lable" for="defaultCheck1"> Maintenance Request </label>
                            </div>
                          </div>

                          <div class="col-md-4 mt-0">
                            <label class="form-label">Category</label>
                            <select id="MAINT_CATEGORY" name="MAINT_CATEGORY" class=" select2 form-select" data-allow-clear="true">
                              <option value="">Select</option>
                            </select>
                          </div>

                          <div class="col-md-4 mt-0">
                            <label class="form-label">Sub Category</label>
                            <select id="MAINT_SUB_CATEGORY" name="MAINT_SUB_CATEGORY" class=" select2 form-select" data-allow-clear="true">
                              <option value="">Select</option>
                            </select>
                          </div>

                          <div class="col-md-4 mt-0">
                            <label class="form-label">Description</label>
                            <textarea rows="4" class="form-control" name="MAINT_DETAILS" id="MAINT_DETAILS" ></textarea>
                          </div>
                        
                          <div class="col-md-4">
                            <!-- <input type="hidden" name="RESV_STATUS" id="RESV_STATUS" class="form-control"/> -->
                            <label class="form-label">Prefered Date </label>
                              <div class="input-group mb-3">
                                <input type="text" autocomplete="off" name="MAINT_PREFERRED_DT" id="MAINT_PREFERRED_DT" class="form-control MAINT_PREFERRED_DT" placeholder="DD-MM-YYYY">
                                <span class="input-group-append">
                                  <span class="input-group-text bg-light d-block">
                                    <i class="fa fa-calendar"></i>
                                  </span>
                                </span>
                                
                              </div>
                          </div>

                          <div class="col-md-4">
                            <label class="form-label">Prefered Time</label>
                            <input type="time" name="MAINT_PREFERRED_TIME" id="MAINT_PREFERRED_TIME" class="form-control" placeholder="Preferred Time" />
                          </div>

                          <div class="col-md-4 mt-0">
                            <label class="form-label">Image</label>
                            <input type="file" name="MAINT_ATTACHMENT" id="MAINT_ATTACHMENT" class="form-control" />
                          </div>
                          <input type="hidden" name="sysid" id="sysid"  class="form-control" />
                          <div class="modal-footer profileCreate">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" onClick="submitForm('maintenanceForm','C',event)" class="btn btn-primary">Save</button>
                          </div>
                        
                      </div>
                      </div>
                    </form>
                  </div>
                  
                </div>
              </div>
            </div>
            <!-- /Modal window -->
            
            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
<script>
  $(document).ready(function() {
    $('#dataTable_view').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'<?php echo base_url('/getFeedbackList')?>'
        },
        'columns': [
          { data: 'CUST_FULLNAME' },
          { data: 'FB_RATINGS' },
          { data: 'FB_DESCRIPTION' },
          { data: 'FB_CREATE_DT' },
        //   { data: 'MAINT_PREFERRED_TIME' },
        //  { data: 'MAINT_STATUS' },
          // { data: 'MAINT_ATTACHMENT' },
          { data: null , render : function ( data, type, row, meta ) {
            return (
              '<div class="d-inline-block">' +
                '<a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                '<ul class="dropdown-menu dropdown-menu-end">' +
                  '<li><a href="javascript:;" data_sysid="'+data['MAINT_ID']+'" class="dropdown-item editWindow">Edit</a></li>' +
                  '<div class="dropdown-divider"></div>' +
                  '<li><a href="javascript:;" data_sysid="'+data['MAINT_ID']+'" class="dropdown-item text-danger delete-record">Delete</a></li>' +
                '</ul>' +
              '</div>'
            );
          }},
        ],
        autowidth:true
      
    });
    
    $('#MAINT_PREFERRED_DT').datepicker({
        format: 'd-M-yyyy',
        autoclose: true
    });

  });

  
  function addForm(){
    $(':input','#customerForm').val('').prop('checked', false).prop('selected', false);
    $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    
    runRoomList();
    runCatList();
    $('#reservationChild').modal('show');
  }

  
  $(document).on('click','.delete-record',function(){
    var sysid = $(this).attr('data_sysid');
    bootbox.confirm({
    message: "Are you sure you want to delete this request?",
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
    callback: function (result) {
        if(result){
          $.ajax({
            url: '<?php echo base_url('/deleteRequest')?>',
            type: "post",
            data: {sysid:sysid},
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            dataType:'json',
            success:function(respn){
              
              $('#dataTable_view').dataTable().fnDraw();
            }
          });
        }
    }
});
    
  });

  
  function runCatList(){
    $.ajax({
        url: '<?php echo base_url('/getCategory')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        dataType:'json',
        async:false,
        success:function(respn){
          
          var option = '<option value="">Select Category</option>';
          $(respn).each(function(ind,data){
            option += '<option value="'+data['MAINT_CAT_ID']+'">'+data['MAINT_CATEGORY']+'</option>';
          });
          $('#MAINT_CATEGORY').html(option);
        
        }
    });
  }

  

  function submitForm(id,mode){
    var formSerialization = $('#'+id).serializeArray();
    var url = '<?php echo base_url('/insertMaintenanceRequest')?>';
    $.ajax({
        url: url,
        type: "post",
        data: formSerialization,
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        dataType:'json',
        success:function(respn){
          
          if(respn.SUCCESS == 200){
            alert("Request Added successfully");
            
            $('#reservationChild').modal('hide');
            // $('#dataTable_view').dataTable().fnDraw();
            // window.location.reload();
          }else{
            alert("Please check required parameters are added");
          }
          
          
        }
    });
  }

  function runRoomList(){
    $.ajax({
        url: '<?php echo base_url('/roomList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        async:false,
        // dataType:'json',
        success:function(respn){
          
          $('#MAINT_ROOM_NO').html(respn).selectpicker('refresh');
          
        }
    });
  }

  function getCheckedInGuestFromRoom(room) {
    $.ajax({
        url: '<?php echo base_url('/getCustomerFromRoomNo')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{room:room},
        
        success:function(respn){
          
          $('#InHouseBooking').html(respn).selectpicker('refresh');
        }
    });
  }


  function getSubCategoryList(cat){
    $.ajax({
        url: '<?php echo base_url('/getSubCategory')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{category:cat},
        // dataType:'json',
        async:false,
        success:function(respn){
          $('#MAINT_SUB_CATEGORY').html(respn).selectpicker('refresh');
        }
    });
  }

  $(document).on('click','.editWindow',function(){
    runRoomList();
    runCatList();
    
    setTimeout(() => {
      var sysid = $(this).attr('data_sysid');
    $('#sysid').val(sysid);
    $('#reservationChild').modal('show');
    $.ajax({
        url: '<?php echo base_url('/editMaintenanceRequest')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{sysid:sysid},
        // async:false,
        dataType:'json',
        success:function(respn){
          ;
          $(respn).each(function(inx,data){
            var data = respn[0];
            
            var dataTrim=$.trim(data.MAINT_SUB_CATEGORY);
            var roomTrim=$.trim(data['MAINT_ROOM_NO']);
            $('#MAINT_ROOM_NO').val(data['MAINT_ROOM_NO']).trigger('change');
            $('#MAINT_CATEGORY').val(data.MAINT_CATEGORY).trigger('change');
            getSubCategoryList(data.MAINT_CATEGORY);
            getCheckedInGuestFromRoom(roomTrim).trigger('change');
            $('#InHouseBooking').val(roomTrim);
            $('#MAINT_SUB_CATEGORY').val(dataTrim).trigger('change');
            
            $('#MAINT_DETAILS').val(data['MAINT_DETAILS']);
            $('#MAINT_PREFERRED_DT').val(data['MAINT_PREFERRED_DT']);     

            var javaDate = new Date(data['MAINT_PREFERRED_TIME']);
            var time = javaDate.getHours() + ":" + javaDate.getMinutes();
            $('#MAINT_PREFERRED_TIME').val(time);

            if(data['MAINT_TYPE']=='MT'){
              $('#MAINT_TYPE1').prop('checked',true);
              $('#MAINT_TYPE2').prop('checked',false);
            }else{
              $('#MAINT_TYPE2').prop('checked',true);
              $('#MAINT_TYPE1').prop('checked',false);
            }
            
          });
          $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
        }
    });
    }, 500);

  });

  
</script>
<script src="<?php //echo base_url('assets/js/bootstrap.bundle.js')?>"></script>
<script src="<?php //echo base_url('assets/js/bootstrap-select.js')?>"></script>
<?=$this->endSection()?>
