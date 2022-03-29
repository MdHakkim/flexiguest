


<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>
<?= $this->include('Layout/ErrorReport') ?>
          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Reservation /</span> Overbooking</h4>

              <!-- DataTable with Buttons -->
              <div class="card">
                <!-- <h5 class="card-header">Responsive Datatable</h5> -->
                <div class="container-fluid" style="padding:6px;">
                  <table id="dataTable_view" class="table table-striped">
                    <thead>
                      <tr>
                        <th>From Date</th>
                        <th>Upto Date</th>
                        <th>Room Class</th>
                        <th>Room Type</th>
                        <th>Overbooking Count</th>
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
           
            <div class="modal fade" id="popModalWindow" tabindex="-1" aria-lableledby="popModalWindowlable" aria-hidden="true">
              <div class="modal-dialog modal-sm">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="popModalWindowlable">New message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form id="submitForm">
                      <div class="row g-3">
                        <input type="hidden" name="OB_ID" id="OB_ID" class="form-control"/>
                        <div class="col-md-12">
                          <lable class="form-lable">From Date</lable>
                          <div class="input-group">
                            <input type="text" name="OB_FROM_DT" id="OB_FROM_DT" class="form-control" placeholder="from date" />
                            <span class="input-group-append">
                              <span class="input-group-text bg-light d-block">
                                <i class="fa fa-calendar"></i>
                              </span>
                            </span>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <lable class="form-lable">Upto Date</lable>
                          <div class="input-group">
                          <input type="text" name="OB_UPTO_DT" id="OB_UPTO_DT" class="form-control" placeholder="upto date" />
                          <span class="input-group-append">
                            <span class="input-group-text bg-light d-block">
                              <i class="fa fa-calendar"></i>
                            </span>
                          </span>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <input type="hidden" name="OB_DAYS[]" id="OB_DAYS" class="form-control"/>
                          <div class="flxy_inlineblk">
                            <?php $days=['S','M','T','W','TH','F','SA']; foreach($days as $day){ ?>
                              <div class="flxy_join">
                                <span class="flxy_fixed">
                                  <lable class="flxy_labstick"><?php echo $day;?></lable>
                                </span>
                                <label class="switch">
                                  <input type="checkbox" data-value="<?php echo $day;?>" class="switch-input" checked="checked" />
                                  <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                      <i class="bx bx-check"></i>
                                    </span>
                                    <span class="switch-off">
                                      <i class="bx bx-x"></i>
                                    </span>
                                  </span>
                                </label>
                            </div>
                            <?php } ?>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <lable class="form-lable">Room Class</lable>
                          <select name="OB_RM_CLASS" id="OB_RM_CLASS" class="select2 form-select" data-allow-clear="true">
                              <option value="">Select</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                          <lable class="form-lable">Room Type</lable>
                            <div class="input-group mb-3">
                              <select  id="OB_RM_TYPE" name="OB_RM_TYPE"  data-width="100%" class="selectpicker OB_RM_TYPE" data-live-search="true">
                                <option value="">Select</option>
                              </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                          <lable class="form-lable">Overbook Count</lable>
                          <input type="number" name="OB_OVER_BK_COUNT" id="OB_OVER_BK_COUNT" class="form-control" placeholder="overbook count" />
                          
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="submitBtn" onClick="submitForm('submitForm')" class="btn btn-primary">Save</button>
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
            'url':'<?php echo base_url('/overBookingView')?>'
        },
        'columns': [
          { data: 'OB_FROM_DT' },
          { data: 'OB_UPTO_DT' },
          { data: 'OB_RM_CLASS' },
          { data: 'OB_RM_TYPE' },
          { data: 'OB_OVER_BK_COUNT' },
          { data: null , render : function ( data, type, row, meta ) {
            return (
              '<div class="d-inline-block">' +
                '<a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                '<ul class="dropdown-menu dropdown-menu-end">' +
                  '<li><a href="javascript:;" data_sysid="'+data['OB_ID']+'" class="dropdown-item editWindow">Edit</a></li>' +
                  '<div class="dropdown-divider"></div>' +
                  '<li><a href="javascript:;" data_sysid="'+data['OB_ID']+'" class="dropdown-item text-danger delete-record">Delete</a></li>' +
                '</ul>' +
              '</div>'
            );
          }},
        ],
        autowidth:true
      
    });
    $("#dataTable_view_wrapper .row:first").before('<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" onClick="addForm()"><i class="fa-solid fa-plus fa-lg"></i> Add</button></div></div>');

    $('#OB_FROM_DT').datepicker({
        format: 'd-M-yyyy',
        autoclose: true
    });
    $('#OB_UPTO_DT').datepicker({
        format: 'd-M-yyyy',
        autoclose: true
    });
  });

  function runInitialLevel(){
    $.ajax({
      url: '<?php echo base_url('/getSupportingOverbookingLov')?>',
      type: "post",
      headers: {'X-Requested-With': 'XMLHttpRequest'},
      // dataType:'json',
      // async:false,
      success:function(respn){
       $('#OB_RM_CLASS').html(respn);//.trigger('change');
      }
    });
  }
  
  var dayArray=['ALL'];
  $(document).on('change','.switch-input',function(){
    var checkedMe = $(this).is(':checked');
    var newData = $(this).attr('data-value');
    if(checkedMe){
      dayArray = jQuery.grep(dayArray, function(value) { return value != newData; });
    }else{
      dayArray = jQuery.grep(dayArray, function(value) { return value != 'ALL'; });
      dayArray.push(newData);
    }
    if(dayArray.length==0){
      dayArray.push('ALL');
    }
    $('#OB_DAYS').val(dayArray);
  });

  function addForm(){
    $(':input','#submitForm').not('[type="radio"]').val('').prop('checked', false).prop('selected', false);
    $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    $('#popModalWindow').modal('show');
    runInitialLevel();
    $('.switch-input').prop('checked',true);
    $('#OB_DAYS').val('ALL');
  }

  $(document).on('click','.delete-record',function(){
    var sysid = $(this).attr('data_sysid');
      bootbox.confirm({
        message: "Are you confirm to delete this record?",
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
                url: '<?php echo base_url('/deleteOverBooking')?>',
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

  $(document).on('change','#OB_RM_CLASS',function(e,param){
    if(param=='PM'){
      return false;
    }
    var rmclass = $(this).find('option:selected').val();
    $.ajax({
      url: '<?php echo base_url('/getRoomType')?>',
      type: "post",
      headers: {'X-Requested-With': 'XMLHttpRequest'},
      data:{rmclass:rmclass},
      // dataType:'json',
      // async:false,
      success:function(respn){
       $('#OB_RM_TYPE').html(respn).selectpicker('refresh');
      }
    });
  });

  $(document).on('click','.editWindow',function(){
    runInitialLevel();
    $('.switch-input').prop('checked',true);
    var sysid = $(this).attr('data_sysid');
    $('#popModalWindow').modal('show');
    var url = '<?php echo base_url('/editOverBooking')?>';
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
              if(field=='OB_RM_TYPE'){
                var option = '<option value="'+dataval+'">'+data[field+'_DESC']+'</option>';
                $('#'+field).html(option).selectpicker('refresh');
              }else if(field=='RM_TY_PSEUDO_RM' || field=='RM_TY_HOUSEKEEPING' || field=='RM_TY_SEND_T_INTERF'){
                if(dataval=='Y'){
                  $('#'+field+'_CHK').prop('checked',true);
                }else{
                  $('#'+field+'_CHK').prop('checked',false)
                }
              }else if(field=='OB_RM_CLASS'){
                $('#'+field).val(dataval).trigger('change',"PM");
              }else if(field=='OB_DAYS'){
                dayArray=[];
                var dayArr = dataval.split(',');
                $.each(dayArr,function(i,data){
                  
                  dayArray.push(data);
                  console.log(dayArray,data,"data SETS");
                  switch (data) {
                    case 'S':
                      $('.switch-input:eq(0)').prop('checked',false);
                      break;
                    case 'M':
                      $('.switch-input:eq(1)').prop('checked',false);
                      break;
                    case 'T':
                      $('.switch-input:eq(2)').prop('checked',false);
                      break;
                    case 'W':
                      $('.switch-input:eq(3)').prop('checked',false);
                      break;
                    case 'TH':
                      $('.switch-input:eq(4)').prop('checked',false);
                      break;
                    case 'F':
                      $('.switch-input:eq(5)').prop('checked',false);
                      break;
                    case 'SA':
                      $('.switch-input:eq(6)').prop('checked',false);
                      break;
                    default:
                      $('.switch-input').prop('checked',true);
                      break;
                  }
                });
                $('#'+field).val(dataval);
              }else{
                // console.log(dataval,"SDF");
                $('#'+field).val(dataval);
              }
            });
          });
          $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
        }
    });
  });

  function submitForm(id){
    $('#errorModal').hide();
    var formSerialization = $('#'+id).serializeArray();
    var url = '<?php echo base_url('/insertOverBooking')?>';
    $.ajax({
        url: url,
        type: "post",
        data: formSerialization,
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        dataType:'json',
        success:function(respn){
          console.log(respn,"testing");
          var response = respn['SUCCESS'];
          if(response!='1'){
            $('#errorModal').show();
            var ERROR = respn['RESPONSE']['ERROR'];
            var error='<ul>';
            $.each(ERROR,function(ind,data){
              console.log(data,"SDF");
              error+='<li>'+data+'</li>';
            });
            error+='<ul>';
            $('#formErrorMessage').html(error);
          }else{
            $('#popModalWindow').modal('hide');
            $('#dataTable_view').dataTable().fnDraw();
          }
        }
    });
  }
</script>

<?=$this->endSection()?>