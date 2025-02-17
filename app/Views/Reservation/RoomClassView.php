


<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>
<?= $this->include('Layout/SuccessReport') ?>
<?= $this->include('Layout/ErrorReport') ?>

    <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Reservations /</span> Room Classes</h4>

              <!-- DataTable with Buttons -->
              <div class="card">
                <!-- <h5 class="card-header">Responsive Datatable</h5> -->
                <div class="container-fluid p-3">
                  <table id="dataTable_view" class="table table-striped">
                    <thead>
                      <tr>
                        <th>Room Code</th>
                        <th>Room Description</th>
                        <th>Total Room</th>
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
                    <h5 class="modal-title" id="popModalWindowlable">Room Class</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form id="submitForm">
                      <div class="row g-3">
                        <input type="hidden" name="RM_CL_ID" id="RM_CL_ID" class="form-control"/>
                        <div class="col-md-12">
                          <lable class="form-lable">Room Code</lable>
                          <input type="text" name="RM_CL_CODE" id="RM_CL_CODE" class="form-control" placeholder="room code" />
                        </div>
                        <div class="col-md-12">
                          <lable class="form-lable">Room Description</lable>
                          <input type="text" name="RM_CL_DESC" id="RM_CL_DESC" class="form-control" placeholder="room description" />
                        </div>
                        <div class="col-md-12">
                          <lable class="form-lable">Display Seq./Total Rooms</lable>
                            <div class="input-group mb-3">
                              <input type="number" name="RM_CL_DISPLY_SEQ" id="RM_CL_DISPLY_SEQ" class="form-control" placeholder="display seq." />
                              <input type="number" name="RM_CL_TOTAL_ROOM" id="RM_CL_TOTAL_ROOM" class="form-control" placeholder="total rooms" />
                            </div>
                        </div>
                        <!-- <div class="col-md-12">
                            <div class="input-group mb-3 radioBtnClass">
                              <div class="form-check pe-2">
                                <input class="form-check-input" type="radio" checked Value="SL" name="RM_CL_FEATURE" id="RM_CL_FEATURE">
                                <lable class="form-check-lable"> Selected </lable>
                              </div>
                              <div class="form-check pe-2">
                                <input class="form-check-input" type="radio" Value="NS" name="RM_CL_FEATURE" id="RM_CL_FEATURE">
                                <lable class="form-check-lable"> Non-selected </lable>
                              </div>
                              <div class="form-check pe-2">
                                <input class="form-check-input" Value="BT" type="radio" name="RM_CL_FEATURE" id="RM_CL_FEATURE">
                                <lable class="form-check-lable"> Both </lable>
                              </div>
                            </div>
                        </div> -->
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
            'url':'<?php echo base_url('/roomClassView')?>'
        },
        'columns': [
          { data: 'RM_CL_CODE' },
          { data: 'RM_CL_DESC' },
          { data: 'RM_CL_TOTAL_ROOM' },
          { data: null, className: "text-center", "orderable": false, render : function ( data, type, row, meta ) {
            return (
              '<div class="d-inline-block">' +
                '<a href="javascript:;" class="btn btn-sm btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                '<ul class="dropdown-menu dropdown-menu-end">' +
                  '<li><a href="javascript:;" data_sysid="'+data['RM_CL_ID']+'" class="dropdown-item editWindow">Edit</a></li>' +
                  '<div class="dropdown-divider"></div>' +
                  '<li><a href="javascript:;" data_sysid="'+data['RM_CL_ID']+'" class="dropdown-item text-danger delete-record">Delete</a></li>' +
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
                url: '<?php echo base_url('/deleteRoomClass')?>',
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

  // $(document).on('click','.flxCheckBox',function(){
  //   var checked = $(this).is(':checked');
  //   var parent = $(this).parent();
  //   if(checked){
  //     parent.find('input[type=hidden]').val('Y');
  //   }else{
  //     parent.find('input[type=hidden]').val('N');
  //   }
  // });

  $(document).on('click','.editWindow',function(){
    var sysid = $(this).attr('data_sysid');
    $('#popModalWindow').modal('show');
    var url = '<?php echo base_url('/editRoomClass')?>';
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
              if(field=='RM_FEATURE'){
                if(dataval=='SL'){
                  $('.radioBtnClass .form-check').eq(0).find('[type="radio"]').prop('checked',true);
                }else if(dataval=='NS'){
                  $('.radioBtnClass .form-check').eq(1).find('[type="radio"]').prop('checked',true);
                }else{
                  $('.radioBtnClass .form-check').eq(2).find('[type="radio"]').prop('checked',true);
                }
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
    var url = '<?php echo base_url('/insertRoomClass')?>';
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
            $('#popModalWindow').modal('hide');
            $('#dataTable_view').dataTable().fnDraw();
          }
        }
    });
  }
</script>

<?=$this->endSection()?>