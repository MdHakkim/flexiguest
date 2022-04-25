<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Maintenance Request</span> List</h4>

              <!-- DataTable with Buttons -->
              <div class="card">
                <!-- <h5 class="card-header">Responsive Datatable</h5> -->
                <div class="container-fluid" style="padding:6px;">
                  <table id="dataTable_view" class="table table-striped">
                    <thead>
                      <tr>
                        <th>Apartment</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>SubCategory</th>
                        <th>Prefered Date & Time</th>
                        <th>Status</th>
                        <!-- <th>Image</th> -->
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
                    <h5 class="modal-title" id="reservationChildLabel">Maintenance Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form id="maintenanceForm" novalidate enctype="multipart/form-data">
                      <div class="window-1" id="window1">
                        <div class="row g-3">

                        <div class="col-md-4 mt-0">
                            <lable class="form-lable">Apartments</lable>
                            <select id="MAINT_ROOM_NO" class=" select2 form-select" name="MAINT_ROOM_NO" data-allow-clear="true">
                              <option value="">Select</option>
                            </select>
                          </div>
                          
                        
                        <div class="col-md-4 mt-0">
                            <lable class="form-lable">InHouseBooking</lable>
                            <select name="InHouseBooking" id="InHouseBooking" class=" select2 form-select" data-allow-clear="true">
                              <option value="">Select</option>
                            </select>
                          </div> 

                          <div class="col-md-4 flxi_ds_flx">
                          <lable class="form-lable">Type</lable>
                            <div class="form-radio mt-4 me-1">
                              <input class="form-radio-input flxCheckBox" type="radio" value="Bulb/Key" name="MAINT_TYPE" id="MAINT_TYPE1">
                              <lable class="form-radio-lable" for="defaultCheck1"> Bulb/Key </lable>
                            </div>
                            <div class="form-radio mt-4 me-1">
                              <input class="form-radio-input flxCheckBox" type="radio" value="Maintenance Request" name="MAINT_TYPE" id="MAINT_TYPE2">
                              <lable class="form-radio-lable" for="defaultCheck1"> Maintenance Request </lable>
                            </div>
                          </div>

                          <div class="col-md-4 mt-0">
                            <lable class="form-lable">Category</lable>
                            <select id="MAINT_CATEGORY" name="MAINT_CATEGORY" class=" select2 form-select" data-allow-clear="true">
                              <option value="">Select</option>
                              <option value="Air Conditioner">Air Conditioner</option>
                              <option value="Civil">Civil</option>
                              <option value="Common">Common</option>
                              <option value="Electrical">Electrical</option>
                              <option value="Plumbing">Plumbing</option>
                            </select>
                          </div>

                          <div class="col-md-4 mt-0">
                            <lable class="form-lable">Sub Category</lable>
                            <select id="MAINT_SUB_CATEGORY" name="MAINT_SUB_CATEGORY" class=" select2 form-select" data-allow-clear="true">
                              <option value="">Select</option>
                            </select>
                          </div>

                          <div class="col-md-4 mt-0">
                            <lable class="form-lable">Description</lable>
                            <textarea rows="4" class="form-control" name="MAINT_DETAILS" id="MAINT_DETAILS" ></textarea>
                          </div>
                        
                          <div class="col-md-4">
                            <!-- <input type="hidden" name="RESV_STATUS" id="RESV_STATUS" class="form-control"/> -->
                            <lable class="form-lable">Prefered Date </lable>
                              <div class="input-group mb-3">
                                <input type="text" name="MAINT_PREFERRED_DT" id="MAINT_PREFERRED_DT" class="form-control MAINT_PREFERRED_DT" placeholder="DD-MM-YYYY">
                                <span class="input-group-append">
                                  <span class="input-group-text bg-light d-block">
                                    <i class="fa fa-calendar"></i>
                                  </span>
                                </span>
                                
                              </div>
                          </div>

                          <div class="col-md-4">
                            <lable class="form-lable">Prefered Time</lable>
                            <input type="time" name="MAINT_PREFERRED_TIME" id="MAINT_PREFERRED_TIME" class="form-control" placeholder="Preferred Time" />
                          </div>

                          <div class="col-md-4 mt-0">
                            <lable class="form-lable">Image</lable>
                            <input type="file" name="MAINT_ATTACHMENT" id="MAINT_ATTACHMENT" class="form-control" />
                          </div>
                        
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
            'url':'<?php echo base_url('/getRequestList')?>'
        },
        'columns': [
          { data: 'MAINT_ROOM_NO' },
          { data: 'MAINT_TYPE' },
          { data: 'MAINT_CATEGORY' },
          { data: 'MAINT_SUB_CATEGORY' },
          { data: 'MAINT_PREFERRED_TIME' },
          { data: 'MAINT_STATUS' },
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
    $("#dataTable_view_wrapper .row:first").before('<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" onClick="addForm()"><i class="fa-solid fa-plus fa-lg"></i>Add</button></div></div>');
    $('#MAINT_PREFERRED_DT').datepicker({
        format: 'd-M-yyyy',
        autoclose: true
    });

  });

  
  function addForm(){
    $(':input','#customerForm').val('').prop('checked', false).prop('selected', false);
    $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    $('#CUST_COUNTRY,#CUST_STATE,#CUST_CITY').html('<option value="">Select</option>').selectpicker('refresh');
    runRoomList();
    // runSupportingLov();
    $('#reservationChild').modal('show');
  }

  
  $(document).on('click','.delete-record',function(){
    var sysid = $(this).attr('data_sysid');
    bootbox.confirm({
    message: "Are you confirm to delete this request?",
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
              console.log(respn,"testing");
              $('#dataTable_view').dataTable().fnDraw();
            }
          });
        }
    }
});
    
  });

  function runSupportingLov(){
    $.ajax({
        url: '<?php echo base_url('/getSupportingLov')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        dataType:'json',
        async:false,
        success:function(respn){
          var vipData = respn[0];
          var busegmt = respn[1];
          var option = '<option value="">Select Vip</option>';
          var option2 = '<option value="">Select Segment</option>';
          // console.log(vipData,busegmt,"testing");
          $(vipData).each(function(ind,data){
            option += '<option value="'+data['VIP_ID']+'">'+data['VIP_DESC']+'</option>';
          });
          $(busegmt).each(function(ind,data){
            option2 += '<option value="'+data['BUS_SEG_CODE']+'">'+data['BUS_SEG_DESC']+'</option>';
          });
          $('#CUST_VIP').html(option);
          $('#CUST_BUS_SEGMENT').html(option2);
        }
    });
  }

  $(document).on('click','.flxCheckBox',function(){
    var checked = $(this).is(':checked');
    var parent = $(this).parent();
    if(checked){
      parent.find('input[type=hidden]').val('Y');
    }else{
      parent.find('input[type=hidden]').val('N');
    }
  });

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
            $('#dataTable_view').dataTable().fnDraw();
            window.location.reload();
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
          // console.log(respn,"testing");
          $('#MAINT_ROOM_NO').html(respn).selectpicker('refresh');
          
        }
    });
  }
  $(document).on('change','#MAINT_ROOM_NO',function(){
    var room = $(this).val();
    // alert(ccode)
    $.ajax({
        url: '<?php echo base_url('/getCustomerFromRoomNo')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{room:room},
        // dataType:'json',
        success:function(respn){
          console.log(respn,"testing");
          $('#InHouseBooking').html(respn).selectpicker('refresh');
        }
    });
  });

  $(document).on('change','#MAINT_CATEGORY',function(){

    var cat = $('#MAINT_CATEGORY').find('option:selected').val();
    var scode = $(this).val();
    var option = '<option value="">Select Sub Category</option>';
    var sub_category_AC = ["A/C is noisy", "A/C is not working","A/C is very cold","A/C not coolimg","A/C needs Servicing","Thermostat not working","Water leakage from A/C unit"];
    var sub_category_ELECTRICAL = [
    "Balcony door / window not closing / damaged",
    "Balcony glass broken",
    "Cupboards / wardrobes damaged / broken",
    "Cat Door handle is loose",
    "Door hinges are broken",
    "Door is making noise",
    "Door lock is damaged",
    "Door not closing properly (other than main door)",
    "False Ceiling Damage / Broken",
    "Key to doors not working (other than the main door)",
    "Kitchen cabinet damaged / broken",
    "Main door lock- broken", 
    "Sliding door needsto be fixed",
    "A/C is very cold",
    "Toilet Mirror damaged/broken" ,
    "Window fly net / mesh damaged /broken",
    "Window glass damaged /broken",
    "Window lock damaged",
    ];
    var sub_category_PLUMBING = [
    "Bad smell in bathroom",
    "Bathtub related plumbing problems",
    "Broken Pipeline - causing flood",
    "Dirty water from taps",
    "Drain is blocked",
    "Flexible hose is not working in toilets",
    "Flooding in Apartment",
    "Floor trap / water traps blocked in bathroom",
    "Flush not working in the toilets",
    "Foul smell in domestic water from taps",
    "Heavy Water Leak - flooding from roof / false ceiling",
    "Heavy water",
    "Kichen sink is blocked" ,
    "No Water Supply in Apartment",
    "Wash basin is blocked",
    "WC is blocked"
    ];
    if(cat == 'Air Conditioner') {

      $(sub_category_AC).each(function(ind,data){

            option += '<option value="'+data+'">'+data+'</option>';
          });

    }else if(cat == 'Civil'  || cat == 'Common') {

      $('#MAINT_SUB_CATEGORY').disabled = true;

    }else if(cat == 'Electrical') {

      $(sub_category_ELECTRICAL).each(function(ind,data){

            option += '<option value="'+data+'">'+data+'</option>';
          });
    } 
    else if(cat == 'Plumbing') {

      $(sub_category_PLUMBING).each(function(ind,data){

            option += '<option value="'+data+'">'+data+'</option>';
          });

    }
    $('#MAINT_SUB_CATEGORY').html(option);

    // $.ajax({
    //     url: '<?php echo base_url('/cityList')?>',
    //     type: "post",
    //     headers: {'X-Requested-With': 'XMLHttpRequest'},
    //     data:{category:cat,scode:scode},
    //     // dataType:'json',
    //     success:function(respn){
    //       console.log(respn,"testing");
    //       $('#MAINT_SUB_CATEGORY').html(respn).selectpicker('refresh');
    //     }
    // });
  });

  $(document).on('click','.editWindow',function(){
    runRoomList();
  
    var sysid = $(this).attr('data_sysid');
    $('#reservationChild').modal('show');
    $.ajax({
        url: '<?php echo base_url('/editMaintenanceRequest')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{sysid:sysid},
        dataType:'json',
        success:function(respn){
          
          $(respn).each(function(inx,data){
            var data = respn[0];
            console.log(data.MAINT_ROOM_NO);
           
            var option = '<option value="'+data.MAINT_ROOM_NO+'">'+data.RM_DESC+'</option>';
            $('#MAINT_ROOM_NO').html(option).selectpicker('refresh');
            var category = '<option value="'+data.MAINT_CATEGORY+'">'+data.MAINT_CATEGORY+'</option>';
            $('#MAINT_CATEGORY').html(category).selectpicker('refresh');
            var subcategory = '<option value="'+data.MAINT_SUB_CATEGORY+'">'+data.MAINT_SUB_CATEGORY+'</option>';
            $('#MAINT_SUB_CATEGORY').html(subcategory).selectpicker('refresh');
            $("#radio_1").attr('checked', 'checked');
              // $('#MAINT_ROOM_NO').html(option).selectpicker('refresh');
              // var valuess = $.trim(data);
              // f(inx=='MAINT_ROOM_NO' || inx=='CUST_NAME'){
              //   var option = '<option value="'+data+'">'+data+'</option>';
              //     $('#'+field).html(option).selectpicker('refresh');
              // }
            $.each(data,function(fields,datavals){
              var field = $.trim(fields);//fields.trim();
              var dataval = $.trim(datavals);//datavals.trim();
              if(field=='CUST_COUNTRY_DESC' || field=='CUST_STATE_DESC' || field=='CUST_CITY_DESC'){ return true; };
              // (field=='CUST_ACTIVE' ? (dataval=='Y' ? $('#CUST_ACTIVE_CHK').prop('checked',true) : $('#CUST_ACTIVE_CHK').prop('checked',false)) : '')
              if(field=='CUST_STATE' || field=='CUST_CITY'){
                var option = '<option value="'+dataval+'">'+data[field+'_DESC']+'</option>';
                $('#'+field).html(option).selectpicker('refresh');
              }else if(field=='CUST_ACTIVE'){
                // var rmSpace = dataval.trim();
                if(dataval=='Y'){
                  console.log($('#CUST_ACTIVE_CHK'),dataval,"CUST_ACTIVE_CHK");
                  $('#CUST_ACTIVE_CHK').prop('checked',true);
                }else{
                  console.log($('#CUST_ACTIVE_CHK'),dataval,"CUST_ACTIVE_CHK");
                  $('#CUST_ACTIVE_CHK').prop('checked',false)
                }
              }else{
                $('#'+field).val(dataval);
                if(field=='CUST_COUNTRY'){
                  $("#radio_1").attr('checked', 'checked');
                  $('#'+field).selectpicker('refresh');
                }
              }
            
            });
          });
          $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
        }
    });
  });

  
</script>
<script src="<?php //echo base_url('assets/js/bootstrap.bundle.js')?>"></script>
<script src="<?php //echo base_url('assets/js/bootstrap-select.js')?>"></script>
<?=$this->endSection()?>
