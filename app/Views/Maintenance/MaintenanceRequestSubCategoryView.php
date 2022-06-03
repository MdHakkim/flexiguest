<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Maintenance Request</span> Sub Category</h4>

              <!-- DataTable with Buttons -->
              <div class="card">
               
                <div class="container-fluid" style="padding:6px;">
                  <table id="dataTable_view" class="table table-striped">
                    <thead>
                      <tr>
                      <th>SubCategory</th>
                        <th>Category</th>
                        <th>Added on</th>
                        <th>Added By</th>
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
                    <form id="maintenanceForm"  novalidate >
                      <div class="window-1" id="window1">
                        <div class="row g-3">

                        <div class="col-md-4 mt-0">
                            <label class="form-label">Category</label>
                            <select id="MAINT_CATEGORY" name="MAINT_CATEGORY" class=" select2 form-select" data-allow-clear="true">
                              <option value="">Select</option>
                            </select>
                          </div>
                          
                        
                          <div class="col-md-4 mt-0">
                      
                            <label class="form-label">Sub Category</label>
                            <input type="text" name="MAINT_SUBCATEGORY" id="MAINT_SUBCATEGORY" class="form-control" >
                              
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
            'url':'<?php echo base_url('/SubCategory')?>'
        },
        'columns': [
          { data: 'MAINT_SUBCATEGORY' },
          { data: 'MAINT_CATEGORY' },
          { data: 'MAINT_SUBCAT_CREATE_DT' },
          { data: 'CUST_FULLNAME' },
          
          // { data: 'MAINT_ATTACHMENT' },
          { data: null , render : function ( data, type, row, meta ) {
            return (
              '<div class="d-inline-block">' +
                '<a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                '<ul class="dropdown-menu dropdown-menu-end">' +
                  '<li><a href="javascript:;" data_sysid="'+data['MAINT_SUBCAT_ID']+'" class="dropdown-item editWindow">Edit</a></li>' +
                  '<div class="dropdown-divider"></div>' +
                  '<li><a href="javascript:;" data_sysid="'+data['MAINT_SUBCAT_ID']+'" class="dropdown-item text-danger delete-record">Delete</a></li>' +
                '</ul>' +
              '</div>'
            );
          }},
        ],
        autowidth:true
      
    });
    $("#dataTable_view_wrapper .row:first").before('<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" onClick="addForm()"><i class="fa-solid fa-plus fa-lg"></i>Add</button></div></div>');

  });

  
  function addForm(){
    $(':input','#reservationChild').val('').prop('checked', false).prop('selected', false);
    $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    runCatList();
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
            url: '<?php echo base_url('/deleteSubCategory')?>',
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
    // var formSerialization = $('#'+id).serializeArray();
    var form = $('#'+id)[0];
    var formData = new FormData(form);
    var url = '<?php echo base_url('/insertSubCategory')?>';
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
    
    runCatList();
    
    setTimeout(() => {
      var sysid = $(this).attr('data_sysid');
    $('#sysid').val(sysid);
    $('#reservationChild').modal('show');
    $.ajax({
        url: '<?php echo base_url('/editSubCategory')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{sysid:sysid},
        // async:false,
        dataType:'json',
        success:function(respn){
          console.log(respn);
          $(respn).each(function(inx,data){
            var data = respn[0];
            var dataTrim=$.trim(data.MAINT_CAT_ID);
            var sub=$.trim(data['MAINT_SUBCATEGORY']);
            
            $('#MAINT_CATEGORY').val(data['MAINT_CAT_ID']).trigger('change')
        
            $('#MAINT_SUBCATEGORY').val(sub);
            
            
            
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
