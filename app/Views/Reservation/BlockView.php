


<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>
<?= $this->include('Layout/ErrorReport') ?>
          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Reservations /</span> Block</h4>

              <!-- DataTable with Buttons -->
              <div class="card">
                <!-- <h5 class="card-header">Responsive Datatable</h5> -->
                <div class="container-fluid p-3">
                  <table id="dataTable_view" class="table table-striped">
                    <thead>
                      <tr>
                        <th>Block Code</th>
                        <th>Block Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Res Type</th>
                        <th>Res Method</th>
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
           
            <div class="modal fade" id="reservationW" tabindex="-1" aria-lableledby="reservationWlable" aria-hidden="true">
              <div class="modal-dialog modal-xl">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="reservationWlable">Block</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form id="blockForm">
                      <div class="row g-3">
                      <input type="hidden" name="BLK_ID" id="BLK_ID" class="form-control"/>
                        <div class="col-md-3">
                          <lable class="form-lable">Company</lable>
                          <select name="BLK_COMP"  id="BLK_COMP" data-width="100%" class="selectpicker BLK_COMP" data-live-search="true">
                            <option value="">Select</option>
                          </select>
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Agent</lable>
                          <select name="BLK_AGENT"  id="BLK_AGENT" data-width="100%" class="selectpicker BLK_AGENT" data-live-search="true">
                            <option value="">Select</option>
                          </select>
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Group</lable>
                          <select name="BLK_GROUP"  id="BLK_GROUP" data-width="100%" class="selectpicker BLK_GROUP" data-live-search="true">
                            <option value="">Select</option>
                          </select>
                        </div>
                        <div class="col-md-3"></div>
                        <div class="col-md-3">
                          <lable class="form-lable">Block Name</lable>
                          <input type="text" name="BLK_NAME" id="BLK_NAME" class="form-control" placeholder="block name" />
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Block Code</lable>
                          <input type="text" name="BLK_CODE" id="BLK_CODE" class="form-control" placeholder="block code" />
                        </div>
                        <div class="col-md-4">
                          <lable class="form-lable">Start/End Date</lable>
                            <div class="input-group mb-3">
                              <input type="text" id="BLK_START_DT" name="BLK_START_DT" class="form-control" placeholder="DD/MM/YYYY">
                              <span class="input-group-append">
                                <span class="input-group-text bg-light d-block">
                                  <i class="fa fa-calendar"></i>
                                </span>
                              </span>
                              <input type="text" id="BLK_END_DT" name="BLK_END_DT" class="form-control flatpickr-input active" placeholder="YYYY-MM-DD" readonly="readonly">
                              <span class="input-group-append">
                                <span class="input-group-text bg-light d-block">
                                  <i class="fa fa-calendar"></i>
                                </span>
                              </span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-check mt-3">
                              <input class="form-check-input flxCheckBox" type="checkbox"  id="BLK_ELASTIC_CHK">
                              <input type="hidden" name="BLK_ELASTIC" id="BLK_ELASTIC" value="N" class="form-control" />
                              <lable class="form-check-lable" for="defaultCheck1"> Elastic </lable>
                            </div>
                        </div> 
                        <div class="col-md-3">
                          <lable class="form-lable">Night/Status</lable>
                          <div class="input-group mb-3">
                            <input type="number" name="BLK_NIGHT" id="BLK_NIGHT" class="form-control" placeholder="night" />
                            <select name="BLK_STATUS" id="BLK_STATUS" class=" select2 form-select" data-allow-clear="true">
                              <option value="">Select</option>
                              <option value="INQ">Inquiry</option>
                              <option value="PRS">Prospect</option>
                            </select>
                          </div>
                        </div>
                        
                        <div class="col-md-3">
                          <lable class="form-lable">Reservation Type</lable>
                          <select name="BLK_RESER_TYPE" id="BLK_RESER_TYPE" class=" select2 form-select" data-allow-clear="true">
                            <option value="">Select</option>
                            <option value="GRINQ">Group Inquiry</option>
                            <option value="GRPRS">Group Prospect</option>
                            <option value="PM">Payment</option>
                          </select>
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Market</lable>
                          <select name="BLK_MARKET" id="BLK_MARKET" class=" select2 form-select" data-allow-clear="true">
                            <option value="">Select</option>
                          </select>
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Source</lable>
                          <select name="BLK_SOURCE" id="BLK_SOURCE" class=" select2 form-select" data-allow-clear="true">
                            <option value="">Select</option>
                          </select>
                        </div>
                        
                        <div class="col-md-3">
                          <lable class="form-lable">Reservation Method</lable>
                          <select name="BLK_RESER_METHOD" id="BLK_RESER_METHOD" class=" select2 form-select" data-allow-clear="true">
                            <option value="">Select</option>
                            <option value="RM">Rooming List</option>
                            <option value="IN">Individual Call In</option>
                            <option value="TC">To Be Determined</option>
                          </select>
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Cutoff Date</lable>
                          <div class="input-group mb-3">
                            <input type="text" id="BLK_CUTOFF_DT" name="BLK_CUTOFF_DT" class="form-control flatpickr-input active" placeholder="YYYY-MM-DD" readonly="readonly">
                             <span class="input-group-append">
                              <span class="input-group-text bg-light d-block">
                              <i class="fa fa-calendar"></i>
                             </span>
                             </span>
                             </div>
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Cutoff Days</lable>
                          <input type="number" name="BLK_CUTOFF_DAYS" id="BLK_CUTOFF_DAYS" class="form-control" placeholder="member no" />
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Rate Code</lable>
                          <select name="BLK_RATE_CODE" id="BLK_RATE_CODE" class=" select2 form-select" data-allow-clear="true">
                            <option value="">Select</option>
                          </select>
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Packages</lable>
                          <select name="BLK_PACKAGE" id="BLK_PACKAGE" class=" select2 form-select" data-allow-clear="true">
                            <option value="">Select</option>
                          </select>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="submitBtn" onClick="submitForm('blockForm')" class="btn btn-primary">Save</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- /Modal window -->
            
            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
<script>
  var compAgntMode='';
  var linkMode='';
  $(document).ready(function() {
    linkMode='EX';
    $('#dataTable_view').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'<?php echo base_url('/blockView')?>'
        },
        'columns': [
          { data: 'BLK_CODE' },
          { data: 'BLK_NAME' },
          { data: 'BLK_START_DT'},
          { data: 'BLK_END_DT' },
          { data: 'BLK_STATUS' },
          { data: 'BLK_RESER_TYPE' },
          { data: 'BLK_RESER_METHOD' },
          { data: null , render : function ( data, type, row, meta ) {
            return (
              '<div class="d-inline-block">' +
                '<a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                '<ul class="dropdown-menu dropdown-menu-end">' +
                  '<li><a href="javascript:;" data_sysid="'+data['BLK_ID']+'" class="dropdown-item editWindow">Edit</a></li>' +
                  '<div class="dropdown-divider"></div>' +
                  '<li><a href="javascript:;" data_sysid="'+data['BLK_ID']+'" class="dropdown-item text-danger delete-record">Delete</a></li>' +
                '</ul>' +
              '</div>'
            );
          }},
        ],
        autowidth:true
      
    });
    $("#dataTable_view_wrapper .row:first").before('<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" onClick="addResvation()"><i class="fa-solid fa-plus fa-lg"></i> Add</button></div></div>');
    $('#BLK_START_DT').datepicker({
        format: 'd-M-yyyy',
        autoclose: true
    });
    $('#BLK_END_DT').datepicker({
        format: 'd-M-yyyy',
        autoclose: true
    });
    $('#BLK_CUTOFF_DT').datepicker({
        format: 'd-M-yyyy',
        autoclose: true
    });

  });

  function addResvation(){
    $(':input','#reservationForm').val('').prop('checked', false).prop('selected', false);
    $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    $('#reservationW').modal('show');
    // runSupportingResevationLov();
    runSupportingLov();
  }

  $(document).on('click','.delete-record',function(){
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
        callback: function (result) {
            if(result){
              $.ajax({
                url: '<?php echo base_url('/deleteBlock')?>',
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

  $(document).on('click','.flxCheckBox',function(){
    var checked = $(this).is(':checked');
    var parent = $(this).parent();
    if(checked){
      parent.find('input[type=hidden]').val('Y');
    }else{
      parent.find('input[type=hidden]').val('N');
    }
  });

  $(document).on('click','.editWindow',function(){
    runSupportingLov();
    var sysid = $(this).attr('data_sysid');
    $('#reservationW').modal('show');
    var url = '<?php echo base_url('/editBlock')?>';
    $.ajax({
        url: url,
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{sysid:sysid},
        dataType:'json',
        success:function(respn){
          
          $(respn).each(function(inx,data){
            $.each(data,function(fields,datavals){
              var field = $.trim(fields);//fields.trim();
              var dataval = $.trim(datavals);//datavals.trim();
              if(field=='BLK_COMP_DESC' || field=='BLK_AGENT_DESC' || field=='BLK_GROUP_DESC'){ return true; };
              if(field=='BLK_COMP' || field=='BLK_AGENT' || field=='BLK_GROUP'){
                var option = '<option value="'+dataval+'">'+data[field+'_DESC']+'</option>';
                $('#'+field).html(option).selectpicker('refresh');
              }else if(field=='BLK_ELASTIC'){
                if(dataval=='Y'){
                  $('#BLK_ELASTIC_CHK').prop('checked',true);
                }else{
                  $('#BLK_ELASTIC_CHK').prop('checked',false)
                }
              }else{
                $('#'+field).val(dataval);
                // if(field=='COM_COUNTRY'){
                //   $('#'+field).selectpicker('refresh');
                // }
              }
            
            });
          });
          $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
        }
    });
  });

  function submitForm(id,mode){
    $('#errorModal').hide();
    var formSerialization = $('#'+id).serializeArray();
    var url = '<?php echo base_url('/insertBlock')?>';
    $.ajax({
        url: url,
        type: "post",
        data: formSerialization,
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        dataType:'json',
        success:function(respn){
          
          var response = respn['SUCCESS'];
          if(response!='1'){
            $('#errorModal').show();
            var ERROR = respn['RESPONSE']['ERROR'];
            var error='<ul>';
            $.each(ERROR,function(ind,data){
              
              error+='<li>'+data+'</li>';
            });
            error+='<ul>';
            $('#formErrorMessage').html(error);
          }else{
            $('#reservationW').modal('hide');
            $('#dataTable_view').dataTable().fnDraw();
          }
        }
    });
  }

  function runSupportingLov(){
    $.ajax({
        url: '<?php echo base_url('/getSupportingblkLov')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        dataType:'json',
        success:function(respn){
          var market = respn[0];
          var source = respn[1];
          var option = '<option value="">Select Market</option>';
          var option2 = '<option value="">Select Source</option>';
          // console.log(vipData,busegmt,"testing");
          $(market).each(function(ind,data){
            option += '<option value="'+data['CODE']+'">'+data['DESCS']+'</option>';
          });
          $(source).each(function(ind,data){
            option2 += '<option value="'+data['CODE']+'">'+data['DESCS']+'</option>';
          });
          $('#BLK_MARKET').html(option);
          $('#BLK_SOURCE').html(option2);
        }
    });
  }

  $(document).on('keyup','.BLK_COMP .form-control',function(){
    var search = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/companyList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{search:search},
        // dataType:'json',
        success:function(respn){
          
          $('#BLK_COMP').html(respn).selectpicker('refresh');
        }
    });
  });
  $(document).on('keyup','.BLK_AGENT .form-control',function(){
    var search = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/agentList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{search:search},
        // dataType:'json',
        success:function(respn){
          
          $('#BLK_AGENT').html(respn).selectpicker('refresh');
        }
    });
  });
  $(document).on('keyup','.BLK_GROUP .form-control',function(){
    var search = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/groupList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{search:search},
        // dataType:'json',
        success:function(respn){
          
          $('#BLK_GROUP').html(respn).selectpicker('refresh');
        }
    });
  });

  
  function runSupportingResevationLov(){
    $.ajax({
      url: '<?php echo base_url('/getSupportingReservationLov')?>',
      type: "post",
      headers: {'X-Requested-With': 'XMLHttpRequest'},
      dataType:'json',
      success:function(respn){
        var memData = respn[0];
        var idArray = ['RESV_MEMBER_TY','RESV_RATE_CLASS','RESV_RATE_CODE','RESV_ROOM_CLASS','RESV_FEATURE','RESV_PURPOSE_STAY'];
        $(respn).each(function(ind,data){
          var option = '<option value="">Select</option>';
          $.each(data,function(i,valu){
            option += '<option value="'+valu['CODE']+'">'+valu['DESCS']+'</option>';
          });
          $('#'+idArray[ind]).html(option);
          if(idArray[ind]=='RESV_RATE_CLASS'){
            $('#RESV_RATE_CATEGORY').html(option);
          }
        });
      }
    });
  }

  function companyAgentClick(type){
    compAgntMode=type;
    if(type=='COMPANY'){
      $('.companyData').show();
      $('.agentData').hide();
    }else{
      $('.companyData').hide();
      $('.agentData').show();
    }
    runCountryListExdClass();
    $('#COM_TYPE').val(compAgntMode);
    $('#compnayAgentWindow').modal('show');
  }
</script>

<?=$this->endSection()?>