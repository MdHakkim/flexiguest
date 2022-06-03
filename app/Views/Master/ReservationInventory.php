

<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>
<?= $this->include('Layout/ErrorReport') ?>
          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

       

            <!-- Modal Window -->
           
          
            <button type="button" onClick="getInventoryItems()" class="btn flxi_btn btn-sm btn-primary"></button>

        <!-- Modal Window Item Inventory -->

    <div class="modal fade" id="ItemInventory" data-backdrop="static" data-keyboard="false"
        aria-labelledby="popModalWindowlabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">Item Inventory</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div id="wizard-validation" class="bs-stepper mt-2">
                        <div class="bs-stepper-header">
                            <div class="step" data-target="#select_items">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-circle">1</span>
                                    <span class="bs-stepper-label">Items</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            <div class="step" data-target="#item_availability">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-circle">2</span>
                                    <span class="bs-stepper-label">Inventory Availability</span>
                                </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content">
                         

                            <form id="item-submit-form" onSubmit="return false">                                
                           
                                <div id="select_items" class="content" >
                                <input type="hidden" name="RSV_PRI_ID" id="RSV_PRI_ID" class="form-control" />
                                    <div class="row g-3">

                                        <div class="col-md-5">
                                            <div class="border rounded p-4 mb-3">
                                                <div class="row mb-3">
                                                    <label for="RSV_ITM_ID"
                                                        class="col-form-label col-md-4"><b>Items *</b></label>
                                                    <div class="col-md-8">
                                                    <select id="RSV_ITM_ID" name="RSV_ITM_ID"
                                                        class="select2 form-select form-select-lg">
                                                       <?php echo $itemLists;?>
                                                    </select> 
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="ITEM_AVAIL_START_DT"
                                                        class="col-form-label col-md-4"><b>Start
                                                            Date *</b></label>
                                                    <div class="col-md-8">
                                                        <input class="form-control dateField" type="text"
                                                            placeholder="d-Mon-yyyy" id="ITEM_AVAIL_START_DT"
                                                            name="ITEM_AVAIL_START_DT" />
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="ITEM_AVAIL_END_DT" class="col-form-label col-md-4"><b>End
                                                            Date *</b></label>
                                                    <div class="col-md-8">
                                                        <input class="form-control dateField" type="text"
                                                            placeholder="d-Mon-yyyy" id="ITEM_AVAIL_END_DT"
                                                            name="ITEM_AVAIL_END_DT" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="RSV_ITM_QTY"
                                                        class="col-form-label col-md-4"><b>Quantity *</b></label>
                                                    <div class="col-md-8">
                                                        <input type="number" name="RSV_ITM_QTY" id="RSV_ITM_QTY"
                                                            class="form-control" min="1" step="1"
                                                            placeholder="eg: 12" />
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                <div class="col-md-8 float-right">
                                                <button type="button" class="btn btn-success save-package-code-detail"  onclick="submitItemForm('item-submit-form')">
                                                    <i class="fa-solid fa-floppy-disk"></i>&nbsp; Save
                                                </button>&nbsp;
                                                </div>
                                                </div>


                                            </div>

                                        </div>

                                        <div class="col-md-7">

                                            <div class="border rounded p-4 mb-3">
                                                <div class="col-md-6 mb-3">
                                                  <button type="button" class="btn btn-primary add-package-code-detail">
                                                      <i class="fa-solid fa-circle-plus"></i>&nbsp; Add New
                                                  </button>&nbsp;
                                                  
                                                  <button type="button" class="btn btn-danger delete-package-code-detail">
                                                      <i class="fa-solid fa-ban"></i>&nbsp; Delete
                                                  </button>&nbsp;
                                                </div>

                                                <div class="table-responsive text-nowrap">
                                                    <table id="PKG_CD_Details" class="table table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="all">Start</th>
                                                                <th class="all">End</th>
                                                                <th class="all">Price</th>
                                                                <th class="all">Active</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>

                                                <br />

                                                <input type="hidden" name="PKG_CD_DT_ID" id="PKG_CD_DT_ID" readonly />

                                                
                                            </div>
                                        </div>
                                        <div class="d-flex col-12 justify-content-between">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>                                         

                                            <button type="button" class="btn btn-primary btn-next">
                                                <span class="d-none d-sm-inline-block me-sm-1">Next</span>
                                                <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
                                            </button>
                                        </div>

                                    </div>

                                </div>
                                <div id="item_availability" class="content">

                                    <div class="row g-3">
                                        <input type="hidden" name="PKG_CD_ID" id="PKG_CD_ID" />

                                        <div class="border rounded p-3">

                                            <div class="col-md-12">
                                                <div class="row mb-3">
                                                    <label for="html5-text-input"
                                                        class="col-form-label col-md-3"><b>Code
                                                            *</b></label>
                                                    <div class="col-md-3">
                                                        <input type="text" name="PKG_CD_CODE" id="PKG_CD_CODE"
                                                            class="form-control bootstrap-maxlength textField" maxlength="10"
                                                            placeholder="eg: 1001" required />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="html5-text-input" class="col-form-label col-md-3">Short
                                                        Description</label>
                                                    <div class="col-md-5">
                                                        <input type="text" name="PKG_CD_SHORT_DESC"
                                                            id="PKG_CD_SHORT_DESC"
                                                            class="form-control bootstrap-maxlength" maxlength="50"
                                                            placeholder="eg: Online Travel Agent" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="html5-text-input"
                                                        class="col-form-label col-md-3"><b>Description
                                                            *</b></label>
                                                    <div class="col-md-7">
                                                        <input type="text" name="PKG_CD_DESC" id="PKG_CD_DESC"
                                                            class="form-control bootstrap-maxlength textField" maxlength="50"
                                                            placeholder="eg: Online Travel Agent" />
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="border rounded p-3">
                                            <h6>Transaction Details</h6>
                                            <div class="row g-3 mb-3">
                                                <label for="TR_CD_ID" class="col-form-label col-md-3"><b>Transaction
                                                        Code *</b></label>
                                                <div class="col-md-4">
                                                    <select id="TR_CD_ID" name="TR_CD_ID"
                                                        class="select2 form-select form-select-lg">
                                                        
                                                    </select>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="switch">
                                                        <input type="checkbox" class="switch-input"
                                                            id="PKG_CD_TAX_INCLUDED" name="PKG_CD_TAX_INCLUDED"
                                                            value="1" />
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bx-check"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                                <i class="bx bx-x"></i>
                                                            </span>
                                                        </span>
                                                        <span class="switch-label">Tax Included</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="border rounded p-3">
                                            <h6>Attributes</h6>
                                            <div class="row mb-3">

                                                <div class="col-md-4">

                                                    
                                                </div>

                                                <div class="col-md-8">
                                                    <div class="row mb-3">

                                                        <label for="PO_RH_ID" class="col-form-label col-md-4"
                                                            style="text-align: right;"><b>Posting Rhythm *</b></label>
                                                        <div class="col-md-8">
                                                            <select id="PO_RH_ID" name="PO_RH_ID"
                                                                class="select2 form-select form-select-lg">
                                                              
                                                            </select>
                                                        </div>

                                                    </div>

                                                    <div class="row mb-3">

                                                        <label for="CLC_RL_ID" class="col-form-label col-md-4"
                                                            style="text-align: right;"><b>Calculation Rule *</b></label>
                                                        <div class="col-md-8">
                                                            <select id="CLC_RL_ID" name="CLC_RL_ID"
                                                                class="select2 form-select form-select-lg">
                                                               
                                                            </select>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label class="switch">
                                                <input id="PKG_CD_SELL_SEP" name="PKG_CD_SELL_SEP" type="checkbox"
                                                    value="1" class="switch-input" />
                                                <span class="switch-toggle-slider">
                                                    <span class="switch-on">
                                                        <i class="bx bx-check"></i>
                                                    </span>
                                                    <span class="switch-off">
                                                        <i class="bx bx-x"></i>
                                                    </span>
                                                </span>
                                                <span class="switch-label">Sell Separately</span>
                                            </label>
                                        </div>

                                        <div class="d-flex col-12 justify-content-between">

                                        <button class="btn btn-primary btn-prev">
                                            <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                                            <span class="d-none d-sm-inline-block">Previous</span>
                                        </button>

                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        </div>

                                      

                                    </div>
                                
                                  </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- /Modal window -->
            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
          <?=$this->include("Reservation/CompanyAgentModal")?>
<script>
  var compAgntMode='';
  var linkMode='';
  var windowmode='';
  $(document).ready(function() {

    $('.dateField').datepicker({
        format: 'dd-M-yyyy',
        autoclose: true,
        onSelect: function() {
            $(this).change();
        }
    });
    linkMode='EX';
    //$('#loader_flex_bg').show();
    $('#dataTable_view').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'<?php echo base_url('/reservationView')?>'
        },
        'columns': [
          { data: 'RESV_NO' },
          { data: 'FULLNAME' },
          { data: 'RESV_ARRIVAL_DT'},
          { data: 'RESV_DEPARTURE'},
          { data: 'RESV_NIGHT' },
          { data: 'RESV_NO_F_ROOM'},
          // { data: 'RESV_FEATURE'},
          { data: 'RESV_STATUS', render : function ( data, type, row, meta ) {
            return (
              '<div class="flxy_status_cls">'+data+'</div>'
            );
          }},
          { data: null , render : function ( data, type, row, meta ) {
            return (
              '<div class="d-inline-block flxy_option_view">' +
                '<a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                '<ul class="dropdown-menu dropdown-menu-end">' +
                  '<li><a href="javascript:;" data_sysid="'+data['RESV_ID']+'" class="dropdown-item editReserWindow"><i class="fas fa-edit"></i> Edit</a></li>' +
                  '<li><a href="javascript:;" data_sysid="'+data['RESV_ID']+'" rmtype="'+data['RESV_RM_TYPE']+'" rmtypedesc="'+data['RM_TY_DESC']+'"  class="dropdown-item reserOption"><i class="fa-solid fa-align-justify"></i> Options</a></li>' +
                  // '<div class="dropdown-divider"></div>' +
                  '<li><a href="javascript:;" data_sysid="'+data['RESV_ID']+'" class="dropdown-item text-danger delete-record"><i class="fas fa-trash"></i> Delete</a></li>' +
                '</ul>' +
              '</div>'
            );
          }},
        ],
        'autowidth':true,
        'order': [[ 0, "desc" ]],
        "fnInitComplete": function (oSettings, json) {
          $('#loader_flex_bg').hide();
        }
    });
    $("#dataTable_view_wrapper .row:first").before('<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" onClick="addResvation()"><i class="fa-solid fa-plus fa-lg"></i> Add</button></div></div>');
   
    $('.RESV_ARRIVAL_DT').datepicker({
        format: 'd-M-yyyy',
        autoclose: true,
    });
    $('.RESV_DEPARTURE').datepicker({
        format: 'd-M-yyyy',
        autoclose: true,
    });
    $('.CUST_DOB').datepicker({
        format: 'd-M-yyyy',
        autoclose: true
    });

    $('#RESV_ARRIVAL_DT_PK').datepicker({
        format: 'd-M-yyyy',
        autoclose: true,
    });
    $('#RESV_ARRIVAL_DT_DO').datepicker({
        format: 'd-M-yyyy',
        autoclose: true,
    });
    
  });


  function generateRateQuery(mode='AVG'){
    var formData={};
    $('.window-1').find('.input-group :input').each(function(i,data){
      var field = $(data).attr('id');
      var values = $(this).val();
      formData[field]=values; 
      formData['mode']=mode;
    });
    $.ajax({
        url: '<?php echo base_url('/getRateQueryData')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:formData,
        dataType:'json',
        success:function(respn){
          $('#rateQueryTable').html(respn[0]);
          checkArrivalDate();
        }
      });
  }

  function avaiableDatePeriod(){
    var arrival = $('#RESV_ARRIVAL_DT').val();
    var departure = $('#RESV_DEPARTURE').val();
    var night = $('#RESV_NIGHT').val();
    var nofroom = $('#RESV_NO_F_ROOM').val();
    var adult = $('#RESV_ADULTS').val();
    var children = $('#RESV_CHILDREN').val();
    var ulli = '<li>'+moment(arrival,'DD-MMM-YYYY').format('dddd')+' ,</li>';
    ulli+='<li>&nbsp;'+moment(arrival,'DD-MMM-YYYY').format('MMMM D YYYY')+' ,</li>';
    ulli+='<li>&nbsp;'+night+' Night ,</li>';
    ulli+='<li>&nbsp;'+nofroom+' Rooms ,</li>';
    ulli+='<li>&nbsp;'+adult+' Adults </li>';
    ulli+=(children!=0 ? '<li>,&nbsp;'+children+' Children</li>' : '');
    return '<ul class="flxy_row">'+ulli+'</ul>';
  }

  function next(){
    var fetchInfo = avaiableDatePeriod();
    $('#userInfoDate').html(fetchInfo);
    generateRateQuery();
    $('.rateRadio').prop('checked',false);
    $('.rateRadio:first').prop('checked',true);
    $('#rateQueryWindow').modal('show');
  }

  function getRateQuery(){
    var fetchInfo = avaiableDatePeriod();
    $('#userInfoDate').html(fetchInfo);
    generateRateQuery();
    $('.rateRadio').prop('checked',false);
    $('.rateRadio:first').prop('checked',true);
    $('#rateQueryWindow').modal('show');
  }

  $(document).on('click','.clickPrice',function(){
      $('#rateQueryTable .active').removeClass('active');
      $(this).addClass('active');
      var value = $(this).find('input').val();
      console.log(value);
  });

  function selectRate(){
    $('#rateQueryWindow').modal('hide');
    $('.window-1,#nextbtn').hide();
    $('.window-2').show();
    $('#submitResrBtn').removeClass('submitResr');
    runInitializeConfig();
    var activeRow = $('.clickPrice.active');
    var rmtype = $(activeRow).find('#ROOMTYPE').val();
    var rmprice = $(activeRow).find('#ACTUAL_ADULT_PRICE').val();
    var rateCode = $(activeRow).parent('.ratePrice').find('#RT_DESCRIPTION').val();
    $('[name="RESV_RATE_CODE"]').val(rateCode);
    $('#RESV_RATE').val(rmprice);
    localStorage.setItem('activerate', rmprice);
    $.ajax({
        url: '<?php echo base_url('/getRoomTypeDetails')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{rmtype:rmtype},
        dataType:'json',
        success:function(respn){
          var dataSet = respn[0];
          // var option= '<option data-feture="'+$.trim(dataSet['RM_TY_FEATURE'])+'" data-desc="'+$.trim(dataSet['RM_TY_DESC'])+'" data-rmclass="'+$.trim(dataSet['RM_TY_ROOM_CLASS'])+'" value="'+dataSet['RM_TY_CODE']+'">'+dataSet['RM_TY_DESC']+'</option>';
         // $('#RESV_RM_TYPE,#RESV_RTC').html(option).selectpicker('refresh');
        }
      });
  }

  function previous(){
    $('.window-1,#nextbtn').show();
    $('.window-2').hide();
    $('#submitResrBtn').addClass('submitResr');
  }

  $(document).on('keyup','.RESV_NIGHT,.RESV_NO_F_ROOM,.RESV_ADULTS,.RESV_CHILDREN,.RESV_MEMBER_NO',function(){
      var value = $(this).val();
      var name = $(this).attr('id');
      $('[name="'+name+'"]').val(value);
  });

  $(document).on('change','.RESV_ARRIVAL_DT,.RESV_DEPARTURE',function(){
      var value = $(this).val();
      var name = $(this).attr('id');
      $('[name="'+name+'"]').val(value);
  });

  $(document).on('blur','#RESV_RATE',function(){
      $('#RESV_FIXED_RATE_CHK').prop('checked',true);
      $('#RESV_FIXED_RATE').val('Y');
  });
  var ressysId='';
  var roomType='';
  var roomTypedesc='';
  $(document).on('click','.reserOption',function(){
      ressysId=$(this).attr('data_sysid');
      roomType = $(this).attr('rmtype');
      roomTypedesc = $(this).attr('rmtypedesc');
      $('#Accompany').show();
      $('#Addon').hide();
      $('#optionWindow').modal('show');
      $('.profileSearch').find('input,select').val('');
      windowmode='AC';
      customPop='';
  });
  
  $(document).on('click','.editReserWindow,#triggCopyReserv',function(event,param,paramArr,rmtype){
    $(':input','#reservationForm').val('').prop('checked', false).prop('selected', false);
    $('#RESV_NAME,#RESV_COMPANY,#RESV_AGENT,#RESV_BLOCK').html('<option value="">Select</option>').selectpicker('refresh');
    runSupportingResevationLov();
    runInitializeConfig();
    $('.window-1,#nextbtn,#previousbtn').hide();
    $('.window-2').show();
    $('.flxyFooter').removeClass('flxy_space');
    $('#submitResrBtn').removeClass('submitResr');
    $('#reservationW').modal('show');
    var sysid = $(this).attr('data_sysid');
    var mode='';
    if(param){
      sysid = param;
      mode='CPY';
      $('#submitResrBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    }else{
      $('#submitResrBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
    }
    $.ajax({
        url: '<?php echo base_url('/editReservation')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{sysid:sysid,mode:mode,paramArr:paramArr},
        dataType:'json',
        success:function(respn){
          // console.log(respn,"testing");
          $(respn).each(function(inx,data){
            $.each(data,function(fields,datavals){
              var field = $.trim(fields);//fields.trim();
              var dataval = $.trim(datavals);//datavals.trim();
              if(field=='RESV_NAME_DESC' || field=='RESV_COMPANY_DESC' || field=='RESV_AGENT_DESC' || field=='RESV_BLOCK_DESC'){ return true; };
              if(field=='RESV_NAME' || field=='RESV_COMPANY' || field=='RESV_AGENT' || field=='RESV_BLOCK' || field=='CUST_COUNTRY' || field=='RESV_RM_TYPE' || field=='RESV_ROOM' || field=='RESV_RTC'){
                var option = '<option value="'+dataval+'">'+data[field+'_DESC']+'</option>';
                $('*#'+field).html(option).selectpicker('refresh');
              }else if(field=='RESV_CONFIRM_YN' || field=='RESV_PICKUP_YN' || field=='RESV_DROPOFF_YN' ||       field=='RESV_EXT_PRINT_RT' || field=='RESV_FIXED_RATE'){
                if(dataval=='Y'){
                  $('#'+field+'_CHK').prop('checked',true);
                }else{
                  $('#'+field+'_CHK').prop('checked',false)
                }
                $('#'+field).val(dataval);
              }else{
                $('*#'+field).val(dataval).trigger('change');
              }
            });
          });
          checkArrivalDate();
        }
    });
  });

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
                url: '<?php echo base_url('/deleteReservation')?>',
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

  $(document).on('change','.switch-input',function(){
    var thiss = $(this);
    var checkWhich = $(this).attr('id');
    if(thiss.is(':checked')){
      thiss.next().val('Y');
    }else{
      if(checkWhich=='RESV_FIXED_RATE_CHK'){
        var previousRate = localStorage.getItem('activerate');
        $('#RESV_RATE').val(previousRate);
      }
      thiss.next().val('N');
    }
  });

  function childReservation(param){
    if(param=='C'){
      $('#customerForm').find('input,select').val('');
      windowmode='C';
      customPop='';
    }
    $('.profileCreate').hide();
    $('.profileSearch').show();
    $('#reservationChild').modal('show');
    runCountryList();
    runSupportingLov();
    $('#optionWindow').modal('hide');
  }

  function addResvation(){
    $(':input','#reservationForm').val('').prop('checked', false).prop('selected', false);
    $('#RESV_NAME,#RESV_COMPANY,#RESV_AGENT,#RESV_BLOCK').html('<option value="">Select</option>').selectpicker('refresh');
    $('#reservationW').modal('show');
    runSupportingResevationLov();
    $('.window-1,#nextbtn,#previousbtn').show();
    $('.flxyFooter').addClass('flxy_space');
    $('#submitResrBtn').addClass('submitResr');
    $('#submitResrBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    $('.window-2').hide();
    runCountryList();
    var today = moment().format('DD-MM-YYYY');
    var end = moment().add(1,'days').format('DD-MM-YYYY');
    $('.RESV_ARRIVAL_DT').datepicker().datepicker("setDate",today);
    $('.RESV_DEPARTURE').datepicker().datepicker("setDate",end);
    $('#RESV_NIGHT,#RESV_NO_F_ROOM,#RESV_ADULTS').val('1');
    $('#RESV_CONFIRM_YN,#RESV_PICKUP_YN,#RESV_DROPOFF_YN,#RESV_EXT_PRINT_RT,#RESV_FIXED_RATE').val('N');
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

  $(document).on('change','*#RESV_NAME',function(){
    var custId = $(this).find('option:selected').val();
    var thisActive = $(this).hasClass('activeName')
    thisActive ? '' : $('[name="RESV_NAME"]').val(custId).selectpicker('refresh');
    var url = '<?php echo base_url('/getCustomerDetail')?>';
    $.ajax({
        url: url,
        type: "post",
        data: {custId:custId},
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        dataType:'json',
        success:function(respn){
          if(respn!=''){
            var json = respn[0];
            console.log(json.CUST_COUNTRY,json.CUST_VIP,"GJLKES");
            $('#CUST_FIRST_NAME').val($.trim(json.CUST_FIRST_NAME))
            $('#CUST_TITLE').val($.trim(json.CUST_TITLE));
            $('#CUST_COUNTRY').val($.trim(json.CUST_COUNTRY)).selectpicker('refresh');
            $('#CUST_VIP').val($.trim(json.CUST_VIP));
            $('#CUST_PHONE').val($.trim(json.CUST_PHONE));
          }else{
            $('#CUST_FIRST_NAME,#CUST_TITLE,#CUST_COUNTRY,#CUST_VIP,#CUST_PHONE').val('');
          }
        }
      });
  });

  // validation start //  
  // var validUsername = false;
  // var membertype = document.getElementsByName("RESV_MEMBER_NO")[0];
  // membertype.addEventListener("keyup", () => {
  //     let regex = /^[a-zA-Z]([0-9a-zA-Z]){1,10}$/;
  //     let str = membertype.value;
  //     if (regex.test(str)) {
  //       membertype.classList.remove("isfx-invalid");
  //       membertype.nextElementSibling.innerHTML="";
  //         validUsername = false;
  //     }else if(str==''){
  //       membertype.nextElementSibling.innerHTML="Not empty number";
  //       membertype.classList.add("isfx-invalid");
  //         validUsername = true;
  //     } else {
  //       membertype.nextElementSibling.innerHTML="Allow only number";
  //       membertype.classList.add("isfx-invalid");
  //         validUsername = true;
  //     }
  // });

  function reservationValidate(event,id,mode){
    // membertype.dispatchEvent(new Event('keyup'));
    event.preventDefault();
    var form = document.getElementById(id);
    var condition = (mode=='R' ? !form.checkValidity() || !checkArrivalDate() || !checkDeparturDate() : !form.checkValidity());
    if(mode=='R'){
      var additionValid = checkPaymentValid();
    }else{
      var additionValid = false;
    }
    form.classList.add('was-validated');
    // console.log(condition,additionValid,"additionValid sdfsf");
    if (condition || additionValid) {   // -- customize validate user validUsername
      return false;
    }else{
      return true;
    }
  }

  function checkPaymentValid(){
    var payment = $('#RESV_PAYMENT_TYPE').val();
    if(payment==''){
      $('#RESV_PAYMENT_TYPE').parent('div').removeClass('is-valid').addClass('is-invalid');
      return true;
    }else{
      $('#RESV_PAYMENT_TYPE').parent('div').removeClass('is-invalid').addClass('is-valid');
      return false;
    }
  }

  $(document).on('change','#RESV_ARRIVAL_DT',function(){
    checkArrivalDate();
  });
  $(document).on('change','#RESV_DEPARTURE',function(){
    checkDeparturDate();
  });

  function checkArrivalDate() {
    var startField = $('[name="RESV_ARRIVAL_DT"]');
    var endField = $('[name="RESV_DEPARTURE"]');
    var startDt = $(startField).val();
    var endDt = $(endField).val();
    var startDtFmt = moment(startDt,'DD-MMM-YYYY');
    var endDtFmt = moment(endDt,'DD-MMM-YYYY');
    console.log(startDtFmt,endDtFmt,"startDtFmt TREU SDFF");
    if(startDtFmt<endDtFmt){
      $(startField).removeClass("is-invalid");
      $(startField).addClass("is-valid");
      $(startField)[0].setCustomValidity("");
      return true;
    }else{
      $(startField).removeClass("is-valid");
      $(startField).addClass("is-invalid");
      $(startField)[0].setCustomValidity("invalid");
      return false;
    }
  }
  function checkDeparturDate() {
    var startField = $('[name="RESV_ARRIVAL_DT"]');
    var endField = $('[name="RESV_DEPARTURE"]');
    var startDt = startField.val();
    var endDt = endField.val();
    var startDtFmt = moment(startDt,'DD-MMM-YYYY');
    var endDtFmt = moment(endDt,'DD-MMM-YYYY');
    if(endDtFmt>startDtFmt){
      endField.removeClass("is-invalid");
      endField.addClass("is-valid");
      $(endField)[0].setCustomValidity("");
      return true;
    }else{
      endField.removeClass("is-valid");
      endField.addClass("is-invalid");
      $(endField)[0].setCustomValidity("invalid");
      return false;
    }
  }
  // validation end //

  function submitForm(id,mode,event){
    var validate = reservationValidate(event,id,mode);
    if(!validate){
      return false;
    }
    if(mode=='R'){
      var url = '<?php echo base_url('/insertReservation')?>';
    }else{
      var url = '<?php echo base_url('/insertCustomer')?>';
    }
    $('#loader_flex_bg').show();
    $('#errorModal').hide();
    var formSerialization = $('#'+id).serializeArray();
    $.ajax({
        url: url,
        type: "post",
        data: formSerialization,
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        dataType:'json',
        success:function(respn){
          var response = respn['SUCCESS'];
          $('#loader_flex_bg').hide();
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
            if(mode=='C'){
              var response = respn['RESPONSE']['OUTPUT'];
              $('#reservationChild').modal('hide');
              var option = '<option value="'+response['ID']+'">'+response['FULLNAME']+'</option>';
              $('*#RESV_NAME').html(option).selectpicker('refresh');
              $('*#CUST_TITLE').val(response['CUST_TITLE']);
              $('*#CUST_FIRST_NAME').val(response['CUST_FIRST_NAME']);
              $('*#CUST_VIP').val(response['CUST_VIP']);
              $('*#CUST_PHONE').val(response['CUST_PHONE']);
              $('*#CUST_COUNTRY').val(response['CUST_COUNTRY']).selectpicker('refresh');
              var joinVaribl = windowmode+customPop;
              if(joinVaribl=='AC-N'){
                custId=response['ID'];
                $('#customeTrigger').trigger('click');
              }
            }else{
              var response = respn['RESPONSE']['REPORT_RES'][0];
              var confirmationNo = response['RESV_NO'];
              bootbox.alert({
                message: '<b>Confimation Number : </b>'+confirmationNo+'',
                size: 'small'
              });
              $('#reservationW').modal('hide');
              $('#dataTable_view').dataTable().fnDraw();
            }
          }
        }
    });
  }
  function runCountryList(type){
    $.ajax({
        url: '<?php echo base_url('/countryList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        // dataType:'json',
        success:function(respn){
          if(type=='COMPANY'){
            $('#COM_COUNTRY').html(respn).selectpicker('refresh');
          }else{
            $('*#CUST_COUNTRY').html(respn).selectpicker('refresh');
            $('#CUST_NATIONALITY').html(respn);
          }
        }
    });
  }

  function runSupportingLov(){
    $.ajax({
        url: '<?php echo base_url('/getSupportingLov')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        dataType:'json',
        success:function(respn){
          var vipData = respn[0];
          var busegmt = respn[1];
          var option = '<option value="">Select Vip</option>';
          var option2 = '<option value="">Select Segment</option>';
          $(vipData).each(function(ind,data){
            option+= '<option value="'+data['VIP_ID']+'">'+data['VIP_DESC']+'</option>';
          });
          $(busegmt).each(function(ind,data){
            option2+= '<option value="'+data['BUS_SEG_CODE']+'">'+data['BUS_SEG_DESC']+'</option>';
          });
          $('*#CUST_VIP').html(option);
          $('#CUST_BUS_SEGMENT').html(option2);
        }
    });
  }
  
  $(document).on('keyup','.COPY_RM_TYPE .form-control',function(){
    var search = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/roomTypeList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{search:search},
        // dataType:'json',
        success:function(respn){
          $('#COPY_RM_TYPE').html(respn).selectpicker('refresh');
        }
    });
  });

  $(document).on('keyup','.RESV_RM_TYPE,.RESV_RTC .form-control',function(){
    var search = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/roomTypeList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{search:search},
        // dataType:'json',
        success:function(respn){
          console.log(respn,"testing");
          $('#RESV_RM_TYPE,#RESV_RTC').html(respn).selectpicker('refresh');
        }
    });
  });

  $(document).on('change','#RESV_RM_TYPE,#RESV_RTC',function(){
    var feature = $(this).find('option:selected').attr('data-feture');
    $('[name="RESV_FEATURE"]').val(feature);
  });  

  $(document).on('keyup','.RESV_BLOCK .form-control',function(){
    var search = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/blockList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{search:search},
        // dataType:'json',
        success:function(respn){
          console.log(respn,"testing");
          $('*#RESV_BLOCK').html(respn).selectpicker('refresh');
        }
    });
  });

  $(document).on('keyup','.RESV_COMPANY .form-control',function(){
    var search = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/companyList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{search:search},
        // dataType:'json',
        success:function(respn){
          console.log(respn,"testing");
          $('*#RESV_COMPANY').html(respn).selectpicker('refresh');
        }
    });
  });

  $(document).on('keyup','.RESV_AGENT .form-control',function(){
    var search = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/agentList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{search:search},
        // dataType:'json',
        success:function(respn){
          console.log(respn,"testing");
          $('*#RESV_AGENT').html(respn).selectpicker('refresh');
        }
    });
  });


  $(document).on('keyup','.RESV_NAME .form-control',function(){
    var search = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/customerList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{search:search},
        // dataType:'json',
        success:function(respn){
          console.log(respn,"testing");
          $('*#RESV_NAME').html(respn).selectpicker('refresh');
        }
    });
  });

  $(document).on('keyup','.RESV_ROOM .form-control',function(){
    var search = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/roomList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{search:search},
        // dataType:'json',
        success:function(respn){
          console.log(respn,"testing");
          $('*#RESV_ROOM').html(respn).selectpicker('refresh');
        }
    });
  });


  $(document).on('change','#CUST_COUNTRY',function(){
    var ccode = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/stateList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{ccode:ccode},
        // dataType:'json',
        success:function(respn){
          $('#CUST_STATE').html(respn).selectpicker('refresh');
        }
    });
  });
  $(document).on('change','#CUST_STATE',function(){
    var scode = $(this).val();
    var ccode = $('#customerForm #CUST_COUNTRY').find('option:selected').val();
    $.ajax({
        url: '<?php echo base_url('/cityList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{ccode:ccode,scode:scode},
        // dataType:'json',
        success:function(respn){
            $('*#CUST_CITY').html(respn).selectpicker('refresh');
        }
    });
  });
  
  function runSupportingResevationLov(){
    $.ajax({
      url: '<?php echo base_url('/getSupportingReservationLov')?>',
      type: "post",
      headers: {'X-Requested-With': 'XMLHttpRequest'},
      dataType:'json',
      async:false,
      success:function(respn){
        var memData = respn[0];
        var idArray = ['RESV_MEMBER_TY','RESV_RATE_CLASS','RESV_RATE_CODE','RESV_ROOM_CLASS','RESV_FEATURE','RESV_PURPOSE_STAY','CUST_VIP','RESV_TRANSPORT_TYP','RESV_GUST_TY','RESV_ENTRY_PONT','RESV_PROFILE'];
        $(respn).each(function(ind,data){
          var option = '<option value="">Select</option>';
          $.each(data,function(i,valu){
            var value = $.trim(valu['CODE']);//fields.trim();
            var desc = $.trim(valu['DESCS']);//datavals.trim();
            option += '<option value="'+value+'">'+desc+'</option>';
          });
          if(idArray[ind]=='RESV_MEMBER_TY'){
            $('*#'+idArray[ind]).html(option);
          }else if(idArray[ind]=='RESV_RATE_CLASS'){
            $('#RESV_RATE_CATEGORY').html(option);
          }else{
            $('#'+idArray[ind]).html(option);
            if(idArray[ind]=='RESV_TRANSPORT_TYP'){
              $('#RESV_TRANSPORT_TYP_DO').html(option);
            }
            if(idArray[ind]=='RESV_PURPOSE_STAY'){
              $('#RESV_EXT_PURP_STAY').html(option);
            }
          }
        });
      }
    });
  }

  function runInitializeConfig(){
    $.ajax({
      url: '<?php echo base_url('/getInitializeListReserv')?>',
      type: "post",
      headers: {'X-Requested-With': 'XMLHttpRequest'},
      dataType:'json',
      async:false,
      success:function(respn){
        var memData = respn[0];
        var idArray = ['RESV_RESRV_TYPE','RESV_MARKET','RESV_SOURCE','RESV_ORIGIN','RESV_PAYMENT_TYPE'];
        
        $(respn).each(function(ind,data){
          var option = '';
          $.each(data,function(i,valu){
            var value = $.trim(valu['CODE']);//fields.trim();
            var desc = $.trim(valu['DESCS']);//datavals.trim();
            option+= '<option value=\''+value+'\'>'+desc+'</option>';
          });
          var options='<option value=\'\'>Select</option>'+option;
          console.log(options,"options");
          $('#'+idArray[ind]).html(options);
        });
      }
    });
  }


  function companyAgentClick(type){
    compAgntMode=type;
    if(type=='COMPANY'){
      $('.companyData').show();
      $('.agentData').hide();
    }else{
      $('.companyData').hide();
      $('.agentData').show();
    }
    runCountryListExdClass();
    $('#COM_TYPE').val(compAgntMode);
    $(':input','#compnayAgentForm').val('').prop('checked', false).prop('selected', false);
    $('#compnayAgentWindow').modal('show');
  }

  $(document).on('change','.rateRadio',function(){
    $('.rateRadio').not(this).prop('checked',false);
    var thiss = $(this);
    var mode = thiss.attr('mode');
    if(thiss.is(':checked')){
      generateRateQuery(mode);
    }
  });

  var customPop='';
  function searchData(form,mode,event){
    if(mode=='C'){
      $('.'+form).find('input,select').val('');
      $('#searchRecord').html('<tr><td class="text-center" colspan="11">No Record Found</td></tr>');
    }else if(mode=='S'){
      var formData={};
      $('.'+form).find('input,select').each(function(i,data){
        var field = $(data).attr('id');
        var values = $(this).val();
        formData[field]=values; 
      });
      formData['windowmode']=windowmode;
      $.ajax({
        url: '<?php echo base_url('/searchProfile')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:formData,
        dataType:'json',
        success:function(respn){
          var respone = respn['table'];
          console.log(respone,"SDFF");
          $('#searchRecord').html(respone);
        }
      });
    }else if(mode=='N'){
      $('#customerForm').find('input,select').val('');
      $('.profileCreate').show();
      $('.profileSearch').hide();
      customPop='-N';
    }
  }
  var custId ='';
  $(document).on('click','.activeRow,#customeTrigger',function(){
    var joinVaribl = windowmode+customPop;
    if(joinVaribl!='AC-N'){
      $('.activeRow').removeClass('activeTr');
      $(this).addClass('activeTr');
      custId = $(this).attr('data_sysid');
    }
    $('#appcompanyWindow').modal('show');
    $.ajax({
      url: '<?php echo base_url('/getExistingAppcompany')?>',
      type: "post",
      headers: {'X-Requested-With': 'XMLHttpRequest'},
      data:{custId:custId,ressysId:ressysId},
      dataType:'json',
      success:function(respn){
        var respone = respn['table'];
        $('#accompanyTd').html(respone);
      }
    });
  });

  $(document).on('click','.getExistCust',function(){
      $('.getExistCust').removeClass('activeTr');
      $(this).addClass('activeTr');
      custId = $(this).attr('data_sysid');
    $.ajax({
      url: '<?php echo base_url('/getExistCustomer')?>',
      type: "post",
      headers: {'X-Requested-With': 'XMLHttpRequest'},
      data:{custId:custId},
      dataType:'json',
      success:function(respn){
        var jsonForm = respn[0];
        $('#reservationChild').modal('hide');
        var option = '<option value="'+jsonForm['CUST_ID']+'">'+jsonForm['NAMES']+'</option>';
        $('*#RESV_NAME').html(option).selectpicker('refresh');
      }
    });
  });


  function accompanySet(mode,event){
    if(mode=='D'){
      $('.activeTrDetch').remove();
    }
    $.ajax({
      url: '<?php echo base_url('/appcompanyProfileSetup')?>',
      type: "post",
      headers: {'X-Requested-With': 'XMLHttpRequest'},
      data:{mode:mode,ACCOMP_CUST_ID:custId,ACCOMP_REF_RESV_ID:ressysId,ACCOPM_ID:ACCOPM_SYSID},
      dataType:'json',
      success:function(respn){
        var respone = respn['table'];
        $('#accompanyTd').html(respone);
      }
    });
  }
  var ACCOPM_SYSID='';
  $(document).on('click','.activeDetach',function(){
    $('.activeDetach').removeClass('activeTrDetch');
    $(this).addClass('activeTrDetch');
    ACCOPM_SYSID = $(this).attr('data_sysid');
  });

  var copyresr=[];
  function reservExtraOption(param){
    if(param=='ACP'){
      childReservation();
      $('#Addon').hide();
      $('#Accompany').show();
    }else if(param=='ADO'){
      $('#Accompany').hide();
      $('#Addon').show();
      copyresr=[];
      copyresr.push('PM','SP','CR','RU','CM','PK','IN','GU');
      $('#COPY_RM_TYPE').html('<option value="'+roomType+'">'+roomTypedesc+'</option>').selectpicker('refresh');
    }
  }

  
  $(document).on('change','.copyReser',function(){
    var checkedMe = $(this).is(':checked');
    var newData = $(this).attr('method');
    if(checkedMe){
      copyresr.push(newData);
    }else{
      copyresr = jQuery.grep(copyresr, function(value) { return value != newData; });
    }
  });

  function copyReservation(){
    var roomType = $('#COPY_RM_TYPE').val();
    $("#triggCopyReserv").trigger('click',[ressysId,copyresr,roomType]);
    $('#optionWindow').modal('hide');
    $('.copyReser').prop('checked',true);
    windowmode='C';
    customPop='';
  }

  function detailOption(mode){
    var roomType=$('#rateQueryTable .active').find('#ROOMTYPE').val();
    var fromdate = $('#RESV_ARRIVAL_DT').val();
    var uptodate = $('#RESV_DEPARTURE').val();
    $('#reteQueryDetail').modal('show');
    $.ajax({
      url: '<?php echo base_url('/rateQueryDetailOption')?>',
      type: "post",
      headers: {'X-Requested-With': 'XMLHttpRequest'},
      data:{mode:mode,fromdate:fromdate,uptodate:uptodate,roomType:roomType},
      dataType:'json',
      success:function(respn){
        var respone = respn['table'];
        $('#reteQueryDetailTd').html(respone);
      }
    });
  }

  function getInventoryItems(){
    // var fetchInfo = avaiableDatePeriod();
    // $('#userInfoDate').html(fetchInfo);
    // generateRateQuery();
    // $('.rateRadio').prop('checked',false);
    // $('.rateRadio:first').prop('checked',true);
    $('#ItemInventory').modal('show');
  }

  
// Add New or Edit Package Code submit Function

function submitItemForm(id) {
    //hideModalAlerts();
    alert('q');
    var formSerialization = $('#' + id).serializeArray();
    var url = '<?php echo base_url('/insertItemInventory') ?>';
    $.ajax({
        url: url,
        type: "post",
        data: formSerialization,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        success: function(respn) {
            console.log(respn, "testing");
            var response = respn['SUCCESS'];
            if (response != '1') {
                var ERROR = respn['RESPONSE']['ERROR'];
                var mcontent = '';
                $.each(ERROR, function(ind, data) {
                    console.log(data, "SDF");
                    mcontent += '<li>' + data + '</li>';
                });
                showModalAlert('error', mcontent);
            } else {
                var alertText = $('#RSV_ITM_ID').val() == '' ? '<li>The item has been added</li>' : '<li>';
                showModalAlert('success', alertText);
                

                //$('#popModalWindow').modal('hide');

                var pkgCodeID = respn['RESPONSE']['OUTPUT'];
                showPackageCodeDetails(pkgCodeID);
            }
        }
    });
}

 
</script>

<?=$this->endSection()?>