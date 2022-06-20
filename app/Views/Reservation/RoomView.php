


<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>
<?= $this->include('Layout/ErrorReport') ?>
          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Reservations /</span> Rooms</h4>

              <!-- DataTable with Buttons -->
              <div class="card">
                <!-- <h5 class="card-header">Responsive Datatable</h5> -->
                <div class="container-fluid p-3">
                  <table id="dataTable_view" class="table table-striped">
                    <thead>
                      <tr>
                        <th>Room No</th>
                        <th>Room Description</th>
                        <th>Room Type</th>
                        <th>Room Feature</th>
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
              <div class="modal-dialog modal-md">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="popModalWindowlable">Room</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form id="submitForm">
                      <div class="row g-3">
                        <input type="hidden" name="RM_ID" id="RM_ID" class="form-control"/>
                        <div class="col-md-6">
                          <lable class="form-lable">Room No / Room Class</lable>
                            <div class="input-group mb-3">
                                <div class="col-md-6">
                                  <input type="number" name="RM_NO" id="RM_NO" class="form-control" placeholder="room no" />
                                </div>
                                  <div class="col-md-6">
                                  <input type="text" readonly name="RM_CLASS" id="RM_CLASS" class="form-control" placeholder="room class" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                          <lable class="form-lable">Room Type</lable>
                          <input type="hidden" name="RM_DESC" id="RM_DESC" class="form-control"/>
                          <select name="RM_TYPE"  id="RM_TYPE" data-width="100%" class="selectpicker RM_TYPE" data-live-search="true">
                              <option value="">Select</option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <lable class="form-lable">Pub. Rate Code</lable>
                          <select name="RM_PUBLIC_RATE_CODE" id="RM_PUBLIC_RATE_CODE" class="select2 form-select" data-allow-clear="true">
                            <option value="">Select</option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <lable class="form-lable">Floor Preference</lable>
                          <select name="RM_FLOOR_PREFERN" id="RM_FLOOR_PREFERN" class="select2 form-select" data-allow-clear="true">
                            <option value="">Select</option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <lable class="form-lable">Pub. Rate Amount</lable>
                          <input type="text" name="RM_PUBLIC_RATE_AMOUNT" id="RM_PUBLIC_RATE_AMOUNT" class="form-control" placeholder="rate amount" />
                        </div>  
                        <div class="col-md-6">
                          <lable class="form-lable">Smoking Preference</lable>
                          <select name="RM_SMOKING_PREFERN" id="RM_SMOKING_PREFERN" class="select2 form-select" data-allow-clear="true">
                            <option value="">Select</option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <lable class="form-lable">Display Seq./Max Occupancy</lable>
                            <div class="input-group mb-3">
                              <input type="number" name="RM_DISP_SEQ" id="RM_DISP_SEQ" class="form-control" placeholder="display seq." />
                              <input type="number" name="RM_MAX_OCCUPANCY" id="RM_MAX_OCCUPANCY" class="form-control" placeholder="max occupancy" />
                            </div>
                        </div>
                        <div class="col-md-6">
                          <lable class="form-lable">Measurement/Square Units</lable>
                            <div class="input-group mb-3">
                              <input type="number" name="RM_MEASUREMENT" id="RM_MEASUREMENT" class="form-control" placeholder="measurement" />
                              <input type="number" name="RM_SQUARE_UNITS" id="RM_SQUARE_UNITS" class="form-control" placeholder="square units" />
                            </div>
                        </div>
                        <div class="col-md-12">
                          <lable class="form-lable">Features</lable>
                          <!-- <select name="RM_FEATURE"  id="RM_FEATURE" data-width="100%" class="selectpicker RM_FEATURE" data-live-search="true">
                              <option value="">Select</option>
                          </select> -->
                          <select name="RM_FEATURE[]"id="RM_FEATURE" class="select2 form-select" multiple>
                            <option value="">Select</option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <lable class="form-lable">Day section</lable>
                          <select name="RM_HOUSKP_DY_SECTION"  id="RM_HOUSKP_DY_SECTION" data-width="100%" class="selectpicker RM_HOUSKP_DY_SECTION" data-live-search="true">
                              <option value="">Select</option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <lable class="form-lable">Evening section</lable>
                          <select name="RM_HOUSKP_EV_SECTION"  id="RM_HOUSKP_EV_SECTION" data-width="100%" class="selectpicker RM_HOUSKP_EV_SECTION" data-live-search="true">
                              <option value="">Select</option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <lable class="form-lable">Phone No</lable>
                          <input type="text" name="RM_PHONE_NO" id="RM_PHONE_NO" class="form-control" placeholder="phone" />
                        </div>
                        <div class="col-md-6">
                          <lable class="form-lable">Stayover/Departure Credits</lable>
                            <div class="input-group mb-3">
                              <input type="number" name="RM_STAYOVER_CR" id="RM_STAYOVER_CR" class="form-control" placeholder="stayover" />
                              <input type="number" name="RM_DEPARTURE_CR" id="RM_DEPARTURE_CR" class="form-control" placeholder="departure" />
                            </div>
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
            'url':'<?php echo base_url('/roomView')?>'
        },
        'columns': [
          { data: 'RM_NO' },
          { data: 'RM_DESC' },
          { data: 'RM_TYPE'},
          { data: 'RM_FEATURE' },
          { data: null , render : function ( data, type, row, meta ) {
            return (
              '<div class="d-inline-block">' +
                '<a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                '<ul class="dropdown-menu dropdown-menu-end">' +
                  '<li><a href="javascript:;" data_sysid="'+data['RM_ID']+'" class="dropdown-item editWindow">Edit</a></li>' +
                  '<div class="dropdown-divider"></div>' +
                  '<li><a href="javascript:;" data_sysid="'+data['RM_ID']+'" class="dropdown-item text-danger delete-record">Delete</a></li>' +
                '</ul>' +
              '</div>'
            );
          }},
        ],
        autowidth:true
      
    });
    $("#dataTable_view_wrapper .row:first").before('<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" onClick="addForm()"><i class="fa-solid fa-plus fa-lg"></i> Add</button></div></div>');

  });

  function addForm(){
    $(':input','#submitForm').not('[type="radio"]').val('').prop('checked', false).prop('selected', false);
    $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    $('#popModalWindow').modal('show');
    $('#RM_TYPE,#RM_HOUSKP_DY_SECTION,#RM_HOUSKP_EV_SECTION').html('<option value="">Select</option>').selectpicker('refresh');
    $('#RM_FEATURE').val('').trigger('change');
    runInitialLevel();
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
                url: '<?php echo base_url('/deleteRoom')?>',
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

  function runInitialLevel(){
    $.ajax({
      url: '<?php echo base_url('/getSupportingRoomLov')?>',
      type: "post",
      headers: {'X-Requested-With': 'XMLHttpRequest'},
      dataType:'json',
      async:false,
      success:function(respn){
        var memData = respn[0];
        var idArray = ['RM_PUBLIC_RATE_CODE','RM_FLOOR_PREFERN','RM_SMOKING_PREFERN','RM_FEATURE'];
        $(respn).each(function(ind,data){
          var option = '<option value="">Select</option>';
          $.each(data,function(i,valu){
            var value = $.trim(valu['CODE']);//fields.trim();
            var desc = $.trim(valu['DESCS']);//datavals.trim();
            option += '<option value='+value+'>'+desc+'</option>';
          });
          $('#'+idArray[ind]).html(option);
        });
      }
    });
  }

  $(document).on('keyup','.RM_TYPE .form-control',function(){
    var search = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/roomTypeList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{search:search},
        // dataType:'json',
        success:function(respn){
          console.log(respn,"testing");
          $('#RM_TYPE').html(respn).selectpicker('refresh');
        }
    });
  });

  $(document).on('change','#RM_TYPE',function(){
    var value = $(this).find('option:selected').attr('data-rmclass');
    var desc = $(this).find('option:selected').attr('data-desc');
    $('#RM_DESC').val(desc);
    $('#RM_CLASS').val(value);
  });

  // $(document).on('keyup','.RM_FEATURE .form-control',function(){
  //   var search = $(this).val();
  //   $.ajax({
  //       url: '<?php echo base_url('/featureList')?>',
  //       type: "post",
  //       headers: {'X-Requested-With': 'XMLHttpRequest'},
  //       data:{search:search},
  //       // dataType:'json',
  //       success:function(respn){
  //         console.log(respn,"testing");
  //         $('#RM_FEATURE').html(respn).selectpicker('refresh');
  //       }
  //   });
  // });

  $(document).on('keyup','.RM_HOUSKP_DY_SECTION .form-control,.RM_HOUSKP_EV_SECTION .form-control',function(){
    var search = $(this).val();
    var fieldName = $(this).parents('.bootstrap-select')[0].classList[2];
    $.ajax({
        url: '<?php echo base_url('/houseKeepSecionList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{search:search},
        // dataType:'json',
        success:function(respn){
          console.log(respn,"testing");
          $('#'+fieldName).html(respn).selectpicker('refresh');
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
    var thiss = $(this);
    $.when(runInitialLevel()).done(function (){
      var sysid = thiss.attr('data_sysid');
      $('#popModalWindow').modal('show');
      var url = '<?php echo base_url('/editRoom')?>';
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
                if(field=='RM_TYPE'){
                  var option = '<option value="'+dataval+'">'+data[field+'_DESC']+'</option>';
                  $('#'+field).html(option).selectpicker('refresh');
                }else if(field=='RM_FEATURE'){
                  var feture = dataval.split(',');
                  $('#'+field).val(feture).trigger('change');
                }else{
                  $('#'+field).val(dataval).trigger('change');
                }
              });
            });
            $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
          }
      });
    });
  });

  function submitForm(id){
    $('#errorModal').hide();
    var formSerialization = $('#'+id).serializeArray();
    var url = '<?php echo base_url('/insertRoom')?>';
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