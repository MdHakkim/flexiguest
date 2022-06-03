


<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>
<?= $this->include('Layout/ErrorReport') ?>
          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">DataTables /</span> Basic</h4>

              <!-- DataTable with Buttons -->
              <div class="card">
                <!-- <h5 class="card-header">Responsive Datatable</h5> -->
                <div class="container-fluid" style="padding:6px;">
                  <table id="dataTable_view" class="table table-striped">
                    <thead>
                      <tr>
                        <th>Reservation Type</th>
                        <th>Reservation Description</th>
                        <th>Sequence</th>
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
                        <input type="hidden" name="RESV_TY_ID" id="RESV_TY_ID" class="form-control"/>
                        <div class="col-md-12">
                          <label class="form-label">Reservation Type</label>
                          <input type="text" name="RESV_TY_CODE" id="RESV_TY_CODE" class="form-control" placeholder="reservation type" />
                        </div>
                        <div class="col-md-12">
                          <label class="form-label">Reservation Type Description</label>
                          <input type="text" name="RESV_TY_DESC" id="RESV_TY_DESC" class="form-control" placeholder="reservation desc." />
                        </div>
                        <div class="col-md-12">
                          <label class="form-label">Display Seq</label>
                            <div class="input-group mb-3">
                              <input type="number" name="RESV_TY_SEQ" id="RESV_TY_SEQ" class="form-control" placeholder="display seq." />
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
            'url':'<?php echo base_url('/reservationTypeView')?>'
        },
        'columns': [
          { data: 'RESV_TY_CODE' },
          { data: 'RESV_TY_DESC' },
          { data: 'RESV_TY_SEQ' },
          { data: null , render : function ( data, type, row, meta ) {
            return (
              '<div class="d-inline-block">' +
                '<a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                '<ul class="dropdown-menu dropdown-menu-end">' +
                  '<li><a href="javascript:;" data_sysid="'+data['RESV_TY_ID']+'" class="dropdown-item editWindow">Edit</a></li>' +
                  '<div class="dropdown-divider"></div>' +
                  '<li><a href="javascript:;" data_sysid="'+data['RESV_TY_ID']+'" class="dropdown-item text-danger delete-record">Delete</a></li>' +
                '</ul>' +
              '</div>'
            );
          }},
        ],
        autowidth:true
      
    });
    $("#dataTable_view_wrapper .row:first").before('<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" onClick="addForm()"><i class="fa-solid fa-plus fa-lg"></i> Add</button></div></div>');

  });

  function runInitialLevel(){
    $.ajax({
      url: '<?php echo base_url('/getSupportingRoomClassLov')?>',
      type: "post",
      headers: {'X-Requested-With': 'XMLHttpRequest'},
      dataType:'json',
      async:false,
      success:function(respn){
        var memData = respn[0];
        var idArray = ['RM_TY_PUBLIC_RATE_CODE','RM_TY_FEATURE'];
        $(respn).each(function(ind,data){
          var option = '<option value="">Select</option>';
          $.each(data,function(i,valu){
            var value = $.trim(valu['CODE']);//fields.trim();
            var desc = $.trim(valu['DESCS']);//datavals.trim();
            option += '<option value="'+value+'">'+desc+'</option>';
          });
          $('#'+idArray[ind]).html(option);
        });
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

  function addForm(){
    $(':input','#submitForm').not('[type="radio"]').val('').prop('checked', false).prop('selected', false);
    $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    $('#popModalWindow').modal('show');
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
                url: '<?php echo base_url('/deleteReservationType')?>',
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

  $(document).on('keyup','.RM_TY_ROOM_CLASS .form-control',function(){
    var search = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/roomClassList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{search:search},
        // dataType:'json',
        success:function(respn){
          console.log(respn,"testing");
          $('#RM_TY_ROOM_CLASS').html(respn).selectpicker('refresh');
        }
    });
  });

  $(document).on('click','.editWindow',function(){
    runInitialLevel();
    var sysid = $(this).attr('data_sysid');
    $('#popModalWindow').modal('show');
    var url = '<?php echo base_url('/editReservationType')?>';
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
              if(field=='RM_TY_ROOM_CLASS'){
                var option = '<option value="'+dataval+'">'+data[field+'_DESC']+'</option>';
                $('#'+field).html(option).selectpicker('refresh');
              }else if(field=='RM_TY_PSEUDO_RM' || field=='RM_TY_HOUSEKEEPING' || field=='RM_TY_SEND_T_INTERF'){
                if(dataval=='Y'){
                  $('#'+field+'_CHK').prop('checked',true);
                }else{
                  $('#'+field+'_CHK').prop('checked',false)
                }
              }else if(field=='RM_TY_FEATURE'){
                var feture = dataval.split(',');
                $('#'+field).val(feture).trigger('change');
              }else{
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
    var url = '<?php echo base_url('/insertReservationType')?>';
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