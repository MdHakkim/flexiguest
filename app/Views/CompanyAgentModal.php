



            <!-- Modal Window -->
            <div class="modal fade" id="compnayAgentWindow" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="compnayAgentWindowLable">New message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form id="compnayAgentForm">
                      <div class="row g-3">
                        <input type="hidden" name="COM_TYPE" id="COM_TYPE" class="form-control" value="COMPANY" />
                        <input type="hidden" name="COM_ID" id="COM_ID" class="form-control"/>
                        <input type="hidden" name="AGN_ID" id="AGN_ID" class="form-control"/>
                        <div class="col-md-3">
                        
                          <lable class="form-lable">Account</lable>
                          <input type="text" name="COM_ACCOUNT" id="COM_ACCOUNT" class="form-control" placeholder="account" />
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Address</lable>
                          <input type="text" name="COM_ADDRESS1"  id="COM_ADDRESS1" class="form-control" placeholder="addresss 1" />
                        </div> 
                        <div class="col-md-3 flx_top_lb">
                          <lable class="form-lable"></lable>
                          <input type="text" name="COM_ADDRESS2"  id="COM_ADDRESS2" class="form-control" placeholder="address 2" />
                        </div> 
                        <div class="col-md-3 flx_top_lb">
                          <lable class="form-lable"></lable>
                          <input type="text" name="COM_ADDRESS3"  id="COM_ADDRESS3" class="form-control" placeholder="address 3" />
                        </div> 
                        <div class="col-md-3">
                          <lable class="form-lable col-md-12">Country</lable>
                          <select name="COM_COUNTRY"  id="COM_COUNTRY" data-width="100%" class="selectpicker COM_COUNTRY" data-live-search="true">
                            <option value="">Select</option>
                          </select>
                        </div> 
                        <div class="col-md-3">
                          <lable class="form-lable col-md-12">State</lable>
                          <select name="COM_STATE"  id="COM_STATE" data-width="100%" class="selectpicker COM_STATE" data-live-search="true">
                            <option value="">Select</option>
                          </select>
                        </div> 
                        <div class="col-md-3">
                          <lable class="form-lable col-md-12">City</lable>
                          <select name="COM_CITY"  id="COM_CITY" data-width="100%" class="selectpicker COM_CITY" data-live-search="true">
                            <option value="">Select</option>
                          </select>
                        </div> 
                        <div class="col-md-3">
                          <lable class="form-lable">Postal</lable>
                          <input type="text" name="COM_POSTAL"  id="COM_POSTAL" class="form-control" placeholder="postal" />
                        </div>
                        
                          <div class="col-md-3 companyData">
                            <lable class="form-lable">Contact First</lable>
                            <input type="text" name="COM_CONTACT_FR"  id="COM_CONTACT_FR" class="form-control" placeholder="client id" />
                          </div>
                          <div class="col-md-3 companyData">
                            <lable class="form-lable">Contact Last</lable>
                            <input type="text" name="COM_CONTACT_LT"  id="COM_CONTACT_LT" class="form-control" placeholder="postal" />
                          </div> 
                          <div class="col-md-3">
                            <lable class="form-lable">Contact No</lable>
                            <input type="text" name="COM_CONTACT_NO"  id="COM_CONTACT_NO" class="form-control" placeholder="client id" />
                          </div> 
                          <div class="col-md-3">
                            <lable class="form-lable">Contact Email</lable>
                            <input type="text" name="COM_CONTACT_EMAIL"  id="COM_CONTACT_EMAIL" class="form-control" placeholder="client id" />
                          </div> 
                          <div class="col-md-3 companyData">
                            <lable class="form-lable">Corporate ID</lable>
                            <input type="text" name="COM_CORP_ID"  id="COM_CORP_ID" class="form-control" placeholder="communication desc" />
                          </div> 
                        
                          <div class="col-md-3 agentData">
                            <lable class="form-lable">Territory</lable>
                            <select name="COM_TERRITORY"  id="COM_TERRITORY" class="select2 form-select" data-allow-clear="true">
                              <option value="">Select Territory</option>
                              <option value="UAE">United Arabian Emirates</option>
                              <option value="EU">Europe</option>
                            </select>
                          </div> 
                          <div class="col-md-3 agentData">
                            <lable class="form-lable">IATA</lable>
                            <input type="text" name="COM_IATA"  id="COM_IATA" class="form-control" placeholder="iata" />
                          </div> 
                        
                        <div class="col-md-3">
                          <lable class="form-lable">Communication</lable>
                          <select name="COM_COMMUNI_CODE"  id="COM_COMMUNI_CODE" class="select2 form-select" data-allow-clear="true">
                            <option value="">Select Communication</option>
                            <option value="WEB">Web</option>
                            <option value="WHATSAPP">Whatsapp</option>
                            <option value="FAX">Fax</option>
                            <option value="OTHER">Other</option>
                          </select>
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Communcation Desc.</lable>
                          <input type="text" name="COM_COMMUNI_DESC"  id="COM_COMMUNI_DESC" class="form-control" placeholder="communication desc" />
                        </div> 
                        <div class="col-md-3">
                            <div class="form-check mt-3">
                              <input class="form-check-input flxCheckBox" type="checkbox"  id="COM_ACTIVE_CHK">
                              <input type="hidden" name="COM_ACTIVE" id="COM_ACTIVE" value="N" class="form-control" />
                              <lable class="form-check-lable" for="defaultCheck1"> Active </lable>
                            </div>
                        </div> 
                        
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" onClick="submitFormComp('compnayAgentForm')" id="submitBtn" class="btn btn-primary">Save</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- /Modal window -->
<script> 

  // function runSupportingLov(){
  //   $.ajax({
  //       url: '<?php echo base_url('/getSupportingLov')?>',
  //       type: "post",
  //       headers: {'X-Requested-With': 'XMLHttpRequest'},
  //       dataType:'json',
  //       async:false,
  //       success:function(respn){
  //         var vipData = respn[0];
  //         var busegmt = respn[1];
  //         var option = '<option value="">Select Vip</option>';
  //         var option2 = '<option value="">Select Segment</option>';
  //         // console.log(vipData,busegmt,"testing");
  //         $(vipData).each(function(ind,data){
  //           option += '<option value="'+data['VIP_ID']+'">'+data['VIP_DESC']+'</option>';
  //         });
  //         $(busegmt).each(function(ind,data){
  //           option2 += '<option value="'+data['BUS_SEG_CODE']+'">'+data['BUS_SEG_DESC']+'</option>';
  //         });
  //         $('#CUST_VIP').html(option);
  //         $('#CUST_BUS_SEGMENT').html(option);
  //       }
  //   });
  // }

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
          console.log(respn,"testing");
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
          $('#COM_COUNTRY').html(respn).selectpicker('refresh');
        }
    });
  }

  $(document).on('change','#COM_COUNTRY,#AGN_COUNTRY',function(){
    var ccode = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/stateList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{ccode:ccode},
        // dataType:'json',
        success:function(respn){
          $('#COM_STATE').html(respn).selectpicker('refresh');
        }
    });
  });
  $(document).on('change','#COM_STATE,#AGN_STATE',function(){
    var ccode = $('#COM_COUNTRY').find('option:selected').val();
    var scode = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/cityList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{ccode:ccode,scode:scode},
        // dataType:'json',
        success:function(respn){
          $('#COM_CITY').html(respn).selectpicker('refresh');
        }
    });
  });

  var mode='';
  $(document).on('click','.editWindow',function(){
    if(compAgntMode=='COMPANY'){
      $('.companyData').show();
      $('.agentData').hide();
    }else{
      $('.companyData').hide();
      $('.agentData').show();
    }
    $('#COM_TYPE').val(compAgntMode);
    runCountryListExdClass();
    mode='E';
    var sysid = $(this).attr('data_sysid');
    $('#compnayAgentWindow').modal('show');
    if(compAgntMode=='COMPANY'){
      var url = '<?php echo base_url('/editCompany')?>';
    }else{
      var url = '<?php echo base_url('/editAgent')?>';
    }
    $.ajax({
        url: url,
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{sysid:sysid},
        dataType:'json',
        success:function(respn){
          // console.log(respn,"testing");
          $(respn).each(function(inx,data){
            $.each(data,function(fields,datavals){
              var field = $.trim(fields);//fields.trim();
              var dataval = $.trim(datavals);//datavals.trim();
              if(field=='COM_COUNTRY_DESC' || field=='COM_STATE_DESC' || field=='COM_CITY_DESC'){ return true; };
              if(field=='COM_STATE' || field=='COM_CITY'){
                var option = '<option value="'+dataval+'">'+data[field+'_DESC']+'</option>';
                $('#'+field).html(option).selectpicker('refresh');
              }else if(field=='COM_ACTIVE'){
                if(dataval=='Y'){
                  $('#COM_ACTIVE_CHK').prop('checked',true);
                }else{
                  $('#COM_ACTIVE_CHK').prop('checked',false)
                }
              }else{
                $('#'+field).val(dataval);
                if(field=='COM_COUNTRY'){
                  $('#'+field).selectpicker('refresh');
                }
              }
            
            });
          });
          $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
        }
    });
  });

  function submitFormComp(id){
    $('#errorModal').hide();
    var formSerialization = $('#'+id).serializeArray();
    if(compAgntMode=='COMPANY'){
      var url = '<?php echo base_url('/insertCompany')?>';
    }else{
      var url = '<?php echo base_url('/insertAgent')?>';
    }
    formSerialization.push({name: 'method', value: compAgntMode});
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
            $('#compnayAgentWindow').modal('hide');
            if(linkMode=='EX'){
              var response = respn['RESPONSE']['OUTPUT'];
              var option = '<option value="'+response['ID']+'">'+response['FULLNAME']+'</option>';
              if(compAgntMode=='COMPANY'){
                $('#RESV_COMPANY').html(option).selectpicker('refresh');
              }else{
                $('#RESV_AGENT').html(option).selectpicker('refresh');
              }
            }else{
              $('#dataTable_view').dataTable().fnDraw();
            }
            // if(mode=='C'){
            //   var response = respn['RESPONSE']['OUTPUT'];
            //   $('#reservationChild').modal('hide');
            //   var option = '<option value="'+response['ID']+'">'+response['FULLNAME']+'</option>';
            //   $('#RESV_NAME').html(option).selectpicker('refresh');
            // }
          }
        }
    });
  }
</script>