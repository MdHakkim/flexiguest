<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>

<style>
  .files-wrapper {
  display: flex;
  flex-flow: row wrap;
}

.file__container {
  display: flex;
  flex: 1 1 200px; /* or 1 0 200px, and no more need in min-width at all */
  max-width: 300px;
  min-width: 200px;
  margin: 8px;
  &:hover{
    background-color: #f0f0f0;
    cursor:pointer;
  }

  .file__type {
    order: -1;
    width: 50px;
    height: 75px;
    text-align: center;
    line-height: 65px;
    background-color: Red !important;
    border-radius: 4px;
    margin-right: 16px;
  }
  .file__size {
    display: block;
    font-size: 12px;
  }
}
</style>  

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Hand </span> Book</h4>

              <!-- DataTable with Buttons -->
              <div class="card">
                <!-- <h6 class="card-header">HandBook</h6> -->
                <div class="container-fluid p-3">
                <div id="filesList" class="card" >
                    <div class="files-wrapper">
                      <div class="file__container">
                        <h4 class="file__title col-md-12 mt-0">HandBook  <span class="file__size" id="filesize" >  </span></h4>
                        <div class="file__type">
                          <object data="<?php echo base_url('assets/svg/icons/pdf-icon.svg');?>" width="50" height="50"></object>
                         <a href='<?php echo base_url('assets/Uploads/handbook/hotel-handbook.pdf');?>'>sHandbook.pdf </a> 
                          
                        </div>
                      </div>
                    </div>
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
                    <h5 class="modal-title" id="reservationChildLabel">HandBook Upload</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                  <form id="maintenanceForm" enctype="multipart/form-data" novalidate >
                      <div class="window-1" id="window1">
                        <div class="row g-3">

                          <div class="col-md-12 mt-0">
                            <label class="form-label mt-20">Please Upload handbook</label>
                            <input type="file" name="HANDBOOK" id="HANDBOOK" class="form-control" />
                          </div>
                          
                          <div class="modal-footer profileCreate">
                            
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
          { data: 'TYPE' },
          { data: 'MAINT_CATEGORY' },
          { data: 'MAINT_SUBCATEGORY' },
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
    $(".card .row:first").before('<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" onClick="addForm()"><i class="fa-solid fa-plus fa-lg"></i>Add</button></div></div>');
    $('#MAINT_PREFERRED_DT').datepicker({
        format: 'd-M-yyyy',
        autoclose: true
    });

  });

  
  function addForm(){
    $(':input','#reservationChild').val('').prop('checked', false).prop('selected', false);
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
              console.log(respn,"testing");
              $('#dataTable_view').dataTable().fnDraw();
            }
          });
        }
    }
});
    
  });


  function submitForm(id,mode){
    // var formSerialization = $('#'+id).serializeArray();
    var form = $('#'+id)[0];
    var formData = new FormData(form);
    var url = '<?php echo base_url('/saveHandbook')?>';
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
            
            // $('#dataTable_view').dataTable().fnDraw();
            // window.location.reload();
          }else{
            alert("Please check required parameters are added");
          }
          
          
        }
    });
  }
  $(document).ready(
    function(){

      $.ajax({
        url: '<?php echo base_url('/checkhandbook')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        // data:{sysid:sysid},
        // async:false,
        dataType:'json',
        success:function(respn){
          if(respn.SUCCESS == 200){

            $('#filesize').val();
            let p = document.createElement("p")
            p.val(respn.OUTPUT)
            $('#filesize').append(p)
            $('#filesList').show();
            $('#reservationChild').modal('hide');
          }else{
            alert('please upload the handbook');
            $('#reservationChild').modal('show');
          }
        }
      });
    }

  );

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
          console.log(respn);
          $(respn).each(function(inx,data){
            var data = respn[0];
            console.log("CATHOLOF",data.MAINT_SUB_CATEGORY);
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
