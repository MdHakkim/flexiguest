



            <!-- Modal Window -->
            <div class="modal fade" id="GroupWindow" tabindex="-1" aria-lableledby="GroupWindow" aria-hidden="true">
              <div class="modal-dialog modal-xl">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="GroupWindowLable">Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form id="GroupForm">
                      <div class="row g-3">
                        <input type="hidden" name="GRP_ID" id="GRP_ID" class="form-control"/>
                        <div class="col-md-3">
                          <label class="form-label">Group Name</label>
                          <input type="text" name="GRP_NAME" id="GRP_NAME" class="form-control" placeholder="account" />
                        </div>
                        <div class="col-md-3">
                          <label class="form-label">Language</label>
                          <select name="GRP_LANG"  id="GRP_LANG" class="select2 form-select" data-allow-clear="true">
                              <option value="">Select Language</option>
                              <option value="AR">Arabic</option>
                              <option value="EN">English</option>
                              <option value="FR">French</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                          <label class="form-label">Address</label>
                          <input type="text" name="GRP_ADDRESS1"  id="GRP_ADDRESS1" class="form-control" placeholder="addresss 1" />
                        </div> 
                        <div class="col-md-3 flx_top_lb">
                          <label class="form-label"></label>
                          <input type="text" name="GRP_ADDRESS2"  id="GRP_ADDRESS2" class="form-control" placeholder="address 2" />
                        </div> 
                        <div class="col-md-3 flx_top_lb">
                          <label class="form-label"></label>
                          <input type="text" name="GRP_ADDRESS3"  id="GRP_ADDRESS3" class="form-control" placeholder="address 3" />
                        </div> 
                        <div class="col-md-3">
                          <label class="form-label col-md-12">Country</label>
                          <select name="GRP_COUNTRY"  id="GRP_COUNTRY" data-width="100%" class="selectpicker GRP_COUNTRY" data-live-search="true">
                            <option value="">Select</option>
                          </select>
                        </div> 
                        <div class="col-md-3">
                          <label class="form-label col-md-12">State</label>
                          <select name="GRP_STATE"  id="GRP_STATE" data-width="100%" class="selectpicker GRP_STATE" data-live-search="true">
                            <option value="">Select</option>
                          </select>
                        </div> 
                        <div class="col-md-3">
                          <label class="form-label col-md-12">City</label>
                          <select name="GRP_CITY"  id="GRP_CITY" data-width="100%" class="selectpicker GRP_CITY" data-live-search="true">
                            <option value="">Select</option>
                          </select>
                        </div> 
                        <div class="col-md-3">
                          <label class="form-label">Postal</label>
                          <input type="text" name="GRP_POSTAL"  id="GRP_POSTAL" class="form-control" placeholder="postal" />
                        </div>

                          <div class="col-md-3">
                            <label class="form-label">Contact No</label>
                            <input type="text" name="GRP_CONTACT_NO"  id="GRP_CONTACT_NO" class="form-control" placeholder="client id" />
                          </div> 
                          <div class="col-md-3">
                            <label class="form-label">Contact Email</label>
                            <input type="text" name="GRP_EMAIL"  id="GRP_EMAIL" class="form-control" placeholder="client id" />
                          </div> 
                          
                          <div class="col-md-3">
                            <label class="form-label">VIP</label>
                            <select name="GRP_VIP"  id="GRP_VIP" class="select2 form-select" data-allow-clear="true">
                            </select>
                          </div> 
                          <div class="col-md-3">
                            <label class="form-label">VIP</label>
                            <select name="GRP_CURR"  id="GRP_CURR" class="select2 form-select" data-allow-clear="true">
                              <option value="">Select Territory</option>
                              <option value="UAE">United Arabian Emirates</option>
                              <option value="EU">Europe</option>
                            </select>
                          </div> 

                        <div class="col-md-3">
                          <label class="form-label">Communication</label>
                          <select name="GRP_COMMUNI_CODE"  id="GRP_COMMUNI_CODE" class="select2 form-select" data-allow-clear="true">
                            <option value="">Select Communication</option>
                            <option value="WEB">Web</option>
                            <option value="WHATSAPP">Whatsapp</option>
                            <option value="FAX">Fax</option>
                            <option value="OTHER">Other</option>
                          </select>
                        </div>
                        <div class="col-md-3">
                          <label class="form-label">Communcation Desc.</label>
                          <input type="text" name="GRP_COMMUNI_DESC"  id="GRP_COMMUNI_DESC" class="form-control" placeholder="communication desc" />
                        </div> 
                        <div class="col-md-3">
                          <label class="form-label">Notes</label>
                          <input type="text" name="GRP_NOTES"  id="GRP_NOTES" class="form-control" placeholder="communication desc" />
                        </div> 
                        <div class="col-md-3">
                            <div class="form-check mt-3">
                              <input class="form-check-input flxCheckBox" type="checkbox"  id="GRP_ACTIVE_CHK">
                              <input type="hidden" name="GRP_ACTIVE" id="GRP_ACTIVE" value="N" class="form-control" />
                              <label class="form-check-lable" for="defaultCheck1"> Active </label>
                            </div>
                        </div> 
                        
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" onClick="submitFormGrp('GroupForm')" id="submitBtn" class="btn btn-primary">Save</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- /Modal window -->
<script> 

  function runSupportingLov(){
    $.ajax({
        url: '<?php echo base_url('/getSupportingVipLov')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        dataType:'json',
        async:false,
        success:function(respn){
          var vipData = respn;
          var option = '<option value="">Select Vip</option>';
          $(vipData).each(function(ind,data){
            option += '<option value="'+data['VIP_ID']+'">'+data['VIP_DESC']+'</option>';
          });
          $('#GRP_VIP').html(option);
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
    // if(mode=='R'){
    //   var url = '<?php echo base_url('/insertReservation')?>';
    // }else{
    //   var url = '<?php echo base_url('/insertCustomer')?>';
    // }
    var url = '<?php echo base_url('/insertCustomer')?>';
    $.ajax({
        url: url,
        type: "post",
        data: formSerialization,
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        dataType:'json',
        success:function(respn){
          
          $('#compnayAgentWindow').modal('hide');
          $('#dataTable_view').dataTable().fnDraw();
        }
    });
  }

  function runCountryListExdClass(){
    $.ajax({
        url: '<?php echo base_url('/countryList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        async:false,
        // dataType:'json',
        success:function(respn){
          $('#GRP_COUNTRY').html(respn).selectpicker('refresh');
        }
    });
  }

  $(document).on('change','#GRP_COUNTRY',function(){
    var ccode = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/stateList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{ccode:ccode},
        // dataType:'json',
        success:function(respn){
          $('#GRP_STATE').html(respn).selectpicker('refresh');
        }
    });
  });
  $(document).on('change','#GRP_STATE',function(){
    var ccode = $('#GRP_COUNTRY').find('option:selected').val();
    var scode = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/cityList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{ccode:ccode,scode:scode},
        // dataType:'json',
        success:function(respn){
          $('#GRP_CITY').html(respn).selectpicker('refresh');
        }
    });
  });

  var mode='';
  $(document).on('click','.editWindow',function(){
    runCountryListExdClass();
    runSupportingLov();
    var sysid = $(this).attr('data_sysid');
    $('#GroupWindow').modal('show');
    var url = '<?php echo base_url('/editGroup')?>';
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
              if(field=='GRP_COUNTRY_DESC' || field=='GRP_STATE_DESC' || field=='GRP_CITY_DESC'){ return true; };
              if(field=='GRP_STATE' || field=='GRP_CITY'){
                var option = '<option value="'+dataval+'">'+data[field+'_DESC']+'</option>';
                $('#'+field).html(option).selectpicker('refresh');
              }else if(field=='GRP_ACTIVE'){
                if(dataval=='Y'){
                  $('#GRP_ACTIVE_CHK').prop('checked',true);
                }else{
                  $('#GRP_ACTIVE_CHK').prop('checked',false)
                }
              }else{
                $('#'+field).val(dataval);
                if(field=='GRP_COUNTRY'){
                  $('#'+field).selectpicker('refresh');
                }
              }
            
            });
          });
          $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
        }
    });
  });

  function submitFormGrp(id){
    $('#errorModal').hide();
    var formSerialization = $('#'+id).serializeArray();
    var url = '<?php echo base_url('/insertGroup')?>';
    // formSerialization.push({name: 'method', value: compAgntMode});
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
            $('#GroupWindow').modal('hide');
            if(linkMode=='EX'){
              var response = respn['RESPONSE']['OUTPUT'];
              var option = '<option value="'+response['ID']+'">'+response['FULLNAME']+'</option>';
              $('#RESV_AGENT').html(option).selectpicker('refresh');
            }else{
              $('#dataTable_view').dataTable().fnDraw();
            }
          }
        }
    });
  }
</script>