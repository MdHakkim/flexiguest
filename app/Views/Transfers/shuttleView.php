<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Shuttle </span> List</h4>

              <!-- DataTable with Buttons -->
              <div class="card">
                
                <div class="container-fluid" style="padding:6px;">
                  <table id="dataTable_view" class="table table-striped">
                    <thead>
                      <tr>
                        <th>Shuttle Name</th>
                        <th>Route</th>
                        <th>Start time</th>
                        <th>End Time</th>
                        <th>Next Shuttle at</th>
                        <th>Description</th>
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
                    <h5 class="modal-title" id="reservationChildLabel">Create Shuttle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form id="maintenanceForm" enctype="multipart/form-data"  novalidate >
                      <div class="window-1" id="window1">
                        <div class="row g-3">

                        <div class="col-md-4 mt-0">
                            <lable class="form-lable">Shuttle Name</lable>
                            <input type="text" name="SHUTL_NAME" id="SHUTL_NAME" class="form-control" autocomplete="off" >
                          </div>
                          <div class="col-md-4 mt-0">
                            <lable class="form-lable">Shuttle From</lable>
                            <select id="SHUTL_FROM" name="SHUTL_FROM" class=" select2 form-select stages" data-allow-clear="true">
                              <option value="">Select</option>
                            </select>
                          </div>
                          <div class="col-md-4 mt-0">
                            <lable class="form-lable">Shuttle To</lable>
                            <select id="SHUTL_TO" name="SHUTL_TO" class=" select2 form-select stages" data-allow-clear="true">
                              <option value="">Select</option>
                            </select>
                          </div>

                          <div class="col-md-4">
                            <lable class="form-lable">Start Time</lable>
                                <input type="time" autocomplete="off" name="SHUTL_START_AT" id="SHUTL_START_AT" class="form-control" placeholder="08:10">
                          </div>

                          <div class="col-md-4">
                            <lable class="form-lable">End Time</lable>
                            <input type="time" autocomplete="off"  name="SHUTL_END_AT" id="SHUTL_END_AT" class="form-control" placeholder="08:10" />
                          </div>

                          <div class="col-md-4">
                            <lable class="form-lable">Next Shuttle at</lable>
                            <input type="text" name="SHUTL_NEXT" id="SHUTL_NEXT" class="form-control" autocomplete="off" >
                          </div>

                          <div class="col-md-4">
                            <lable class="form-lable">Description</lable>
                            <textarea rows="4" class="form-control" name="SHUTL_DESCRIPTION" id="SHUTL_DESCRIPTION" ></textarea>
                          </div>

                          <div class="col-md-4">
                            <lable class="form-lable">Image</lable>
                            <input type="file" name="SHUTL_ROUTE_IMG" id="SHUTL_ROUTE_IMG" class="form-control" />
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
            'url':'<?php echo base_url('/shuttlelist')?>'
        },
        'columns': [
          { data: 'SHUTL_NAME' },
          { data: 'SHUTL_ROUTE' },
          { data: 'SHUTL_START_AT' },
          { data: 'SHUTL_END_AT' },
          { data: 'SHUTL_NEXT' },
          { data: 'SHUTL_DESCRIPTION' },
          
          { data: null , render : function ( data, type, row, meta ) {
            return (
              '<div class="d-inline-block">' +
                '<a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                '<ul class="dropdown-menu dropdown-menu-end">' +
                  '<li><a href="javascript:;" data_sysid="'+data['SHUTL_ID']+'" class="dropdown-item editWindow">Edit</a></li>' +
                  '<div class="dropdown-divider"></div>' +
                  '<li><a href="javascript:;" data_sysid="'+data['SHUTL_ID']+'" class="dropdown-item text-danger delete-record">Delete</a></li>' +
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
    $(':input','#reservationChild').val('').prop('checked', false).prop('selected', false);
    $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    runStageList();
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
            url: '<?php echo base_url('/deleteShuttle')?>',
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

  function runStageList(){
    $.ajax({
        url: '<?php echo base_url('/getStages')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        dataType:'json',
        async:false,
        success:function(respn){
          
          var option = '<option value="">Select Stages</option>';
          $(respn).each(function(ind,data){
            option += '<option value="'+data['SHUTL_STAGE_ID']+'">'+data['SHUTL_STAGE_NAME']+'</option>';
          });
          $('.stages').html(option);
        
        }
    });
  }

  function submitForm(id,mode){
    
    var form = $('#'+id)[0];
    var formData = new FormData(form);
    var url = '<?php echo base_url('/insertShuttle')?>';
    $.ajax({
        url: url,
        type: "post",
        data: formData,
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        dataType:'json',
        processData: false,
        contentType: false,
        success:function(respn){
          
          if(respn.SUCCESS == 200){
            alert("Request Added successfully");
            console.log(respn)
            $('#reservationChild').modal('hide');
            // $('#dataTable_view').dataTable().fnDraw();
            // window.location.reload();
          }else{
            alert("Please check required parameters are added");
          }
          
        }
    });
  }

  $(document).on('click','.editWindow',function(){
    runStageList();
    setTimeout(() => {
      var sysid = $(this).attr('data_sysid');
    $('#sysid').val(sysid);
    $('#reservationChild').modal('show');
    $.ajax({
        url: '<?php echo base_url('/editShuttle')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{sysid:sysid},
        // async:false,
        dataType:'json',
        success:function(respn){
          console.log(respn);
          $(respn).each(function(inx,data){
            var data = respn[0];
           
            var SHUTL_FROM = $.trim(data['SHUTL_FROM']);
            var SHUTL_TO = $.trim(data['SHUTL_TO']);
            $('#SHUTL_FROM').val(SHUTL_FROM).trigger('change');
            $('#SHUTL_TO').val(SHUTL_TO).trigger('change');
            $('#SHUTL_NAME').val(data['SHUTL_NAME']);
            
            var StartAT = new Date(data['SHUTL_START_AT']);
            var timeStart = StartAT.getHours() + ":" + StartAT.getMinutes();
            $('#SHUTL_START_AT').val(timeStart);
           
            
            var EndAT = new Date(data['SHUTL_END_AT']);
            var timeEnd = EndAT.getHours() + ":" + EndAT.getMinutes();
            $('#SHUTL_END_AT').val(timeEnd);

            $('#SHUTL_NEXT').val(data['SHUTL_NEXT']);     
            $('#SHUTL_ROUTE').val(data['SHUTL_ROUTE']);     
            $('#SHUTL_DESCRIPTION').val(data['SHUTL_DESCRIPTION']);     

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
