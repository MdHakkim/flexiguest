


<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>
<?= $this->include('Layout/ErrorReport') ?>
          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Reservations /</span> Sources</h4>

              <!-- DataTable with Buttons -->
              <div class="card">
                <!-- <h5 class="card-header">Responsive Datatable</h5> -->
                <div class="container-fluid p-3">
                  <table id="dataTable_view" class="table table-striped">
                    <thead>
                      <tr>
                        <th>Source Code</th>
                        <th>Source Description</th>
                        <th>Group</th>
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
           
            <div class="modal fade" id="popModalWindow" data-backdrop="static" data-keyboard="false" aria-lableledby="popModalWindowlable">
              <div class="modal-dialog modal-sm">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="popModalWindowlable">New message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form id="submitForm">
                      <div class="row g-3">
                        <input type="hidden" name="SOR_ID" id="SOR_ID" class="form-control"/>
                        <div class="col-md-12">
                          <lable class="form-lable">Source Code</lable>
                          <input type="text" name="SOR_CODE" id="SOR_CODE" class="form-control" placeholder="source code" />
                        </div>
                        <div class="col-md-12">
                          <lable class="form-lable">Source Description</lable>
                          <input type="text" name="SOR_DESC" id="SOR_DESC" class="form-control" placeholder="source description" />
                        </div>
                        <div class="col-md-12">
                          <lable class="form-lable">Source Group</lable>
                          <select name="SOR_GROUP"  id="SOR_GROUP" class="select2 form-select" data-allow-clear="true">
                            <option value="">Select Group</option>
                          </select>
                        </div>
                        <div class="col-md-12 ">
                          <lable class="form-lable">Display Sequence</lable>
                          <input type="number" name="SOR_DIS_SEQ" id="SOR_DIS_SEQ" class="form-control" placeholder="display sequence" />
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
            'url':'<?php echo base_url('/sourceView')?>'
        },
        'columns': [
          { data: 'SOR_CODE' },
          { data: 'SOR_DESC' },
          { data: 'SOR_GROUP' },
          { data: null , render : function ( data, type, row, meta ) {
            return (
              '<div class="d-inline-block">' +
                '<a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                '<ul class="dropdown-menu dropdown-menu-end">' +
                  '<li><a href="javascript:;" data_sysid="'+data['SOR_ID']+'" class="dropdown-item editWindow">Edit</a></li>' +
                  '<div class="dropdown-divider"></div>' +
                  '<li><a href="javascript:;" data_sysid="'+data['SOR_ID']+'" class="dropdown-item text-danger delete-record">Delete</a></li>' +
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
      url: '<?php echo base_url('/initalConfigLovSource')?>',
      type: "post",
      headers: {'X-Requested-With': 'XMLHttpRequest'},
      dataType:'json',
      async:false,
      success:function(respn){
        var memData = respn[0];
        var idArray = ['SOR_GROUP'];
        $(respn).each(function(ind,data){
          var option = '<option value="">Select</option>';
          $.each(data,function(i,valu){
            var value = $.trim(valu['CODE']);//fields.trim();
            var desc = $.trim(valu['DESCS']);//datavals.trim();
            option += '<option value="'+value+'">'+desc+'</option>';
          });
          $('#'+idArray[ind]).html(option).trigger('change');
        });
      }
    });
  }

  function addForm(){
    $(':input','#submitForm').not('[type="radio"]').val('').prop('checked', false).prop('selected', false);
    $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    $('#popModalWindow').modal('show');
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
                url: '<?php echo base_url('/deleteSource')?>',
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
    var url = '<?php echo base_url('/editSource')?>';
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
    var url = '<?php echo base_url('/insertSource')?>';
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