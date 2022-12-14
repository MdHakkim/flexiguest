<?= $this->extend('Layout/AppView') ?>
<?= $this->section('contentRender') ?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>

<style>
.tagify__input {
    padding-left: 6px;
}

.tagify__tag>div {
    cursor: pointer;
}

.table-hover>tbody>tr:hover {
    cursor: pointer;
}

.table-warning {
    color: #000 !important;
}
</style>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Reservations /</span> Registration Cards</h4>
        <!-- Default -->
        <div class="row">

            <!-- Validation Wizard -->
            <div class="col-12 mb-4">
                <div id="wizard-validation" class="bs-stepper mt-2">
                   
                    <div class="bs-stepper-content">
                    <form id="submitForm" class="needs-validation" novalidate>

                  
                        
                        <div class="row g-3 mt-2">                           

                            <div class="col-md-5">
                                <lable class="form-lable"><b>Arrival Date </b></lable>
                                <div class="col-md-12">
                                <div class="input-group">
                                    <input type="text" id="ARRIVAL_DATE" name="ARRIVAL_DATE" class="form-control" placeholder="DD-MM-YYYY" required>
                                    <span class="input-group-append">
                                        <span class="input-group-text bg-light d-block">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </span>
                                </div>
                                </div>                       
                            </div> 

                            <div class="col-md-3">
                                <lable class="form-lable"><b>ETA From </b></lable>
                                <input type="time" name="ETA_FROM_TIME" id="ETA_FROM_TIME" class="form-control" required />                                
                            </div>
                            <div class="col-md-3">
                                <lable class="form-lable"><b> To </b></lable>
                                <input type="time" name="ETA_TO_TIME" id="ETA_TO_TIME" class="form-control" required />                                
                            </div>   
                         
                        </div>

                        <div class="row g-3 mt-2">                           

                        <div class="col-md-6">
                            <lable class="form-lable"><b>Guest Name </b></lable>
                            <div class="col-md-12">
                           
                                <select id="GUEST_NAME" name="GUEST_NAME" class="select2 form-select " data-allow-clear="true" > 
                                <option value="">Select</option> 
                                        <?= $customerLists;?>                        
                                </select>  
                            </div>                       
                        </div> 
                        <div class="col-md-3">
                            <lable class="form-lable"><b>Confirm No:</b></lable>
                            <input type="text" name="CONFIRM_NO" id="CONFIRM_NO" class="form-control" />                                
                        </div>


                        </div>
                        <div class="row g-3 mt-2">  
                            <h5>Reservation Types                   
                            </h5>                          
                            <div class="col-md-3">
                                <label class="switch">
                                    <input  value="1" name="RESV_INDIV" id="RESV_INDIV" type="checkbox"
                                        class="switch-input" required />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label"><b>Individuals</b></span>
                                </label>
                            </div>

                            <div class="col-md-3">
                                <label class="switch">
                                    <input id="RESV_BLOCK"  value="1" name="RESV_BLOCK" type="checkbox"
                                        class="switch-input" required />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label"><b>Blocks</b></span>
                                </label>
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <h5 mb-0 pb-0>Filter </h5>                   
                            <div class="col-md-4">
                                <lable class="form-lable"><b>Name </b></lable>
                                <input type="text" name="RESV_FROM_NAME" id="RESV_FROM_NAME" class="form-control bootstrap-maxlength"  />                        
                            </div> 


                            <div class="col-md-4">
                                <lable class="form-lable"><b>Room Class </b></lable>
                                <select id="ROOM_CLASS" name="ROOM_CLASS" class="select2 form-select form-select-lg" data-allow-clear="true" > 
                                <option value="">Select</option> 
                                        <?= $roomClassLists;?>                        
                                </select>                                
                            </div>

                            <div class="col-md-4">
                                <lable class="form-lable"><b> Rate Code </b></lable>
                                <select id="RATE_CODE" name="RATE_CODE" class="select2 form-select form-select-lg" data-allow-clear="true" > 
                                <option value="">Select</option> 
                                <?= $rateCodeLists;?>                                     
                                </select>                                
                            </div>

                            <div class="col-md-4">
                                <lable class="form-lable"><b> Membership Type</b></lable>
                                <select id="MEM_TYPE" name="MEM_TYPE" class="select2 form-select form-select-lg" data-allow-clear="true" > 
                                <option value="">Select</option>
                                <?= $membershipLists;?>                                       
                                </select>                                
                            </div>

                            <div class="col-md-4">
                                <lable class="form-lable"><b>VIP Code</b></lable>
                                <select id="VIP_CODE" name="VIP_CODE" class="select2 form-select form-select-lg" data-allow-clear="true" > 
                                <option value="">Select</option>
                                <?= $vipCodeLists;?>                                      
                                </select>                                
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <h5 mb-0 pb-0>Include </h5>    

                        <div class="col-md-4">
                                <label class="switch">
                                    <input id="IN_HOUSE_GUESTS"  value="1" name="IN_HOUSE_GUESTS" type="checkbox"
                                        class="switch-input" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label"><b>In-House Guests</b></span>
                                </label>
                            </div>
                        </div>
                        <div class="row g-3 mt-2">   
                            <div class="col-md-2">
                            <a class="btn btn-primary d-grid w-100 mb-3" href="javascript:;" onclick ="onClickPreview('1')">
                                Preview
                            </a>
                            </div>
                            <div class="col-md-2">

                            <a class="btn btn-primary d-grid w-100 mb-3"  href="javascript:;" onclick="onClickPreview('2')">
                                Print
                            </a>
                             

                            </div>
                        </div>


                            
                    </form>
                    </div>
                </div>
            </div>
            <!-- /Validation Wizard -->

            <div class="content-backdrop fade"></div>
        </div>

    </div>

 

</div>
<script>
$(document).ready(function() {
    $('#ARRIVAL_DATE').datepicker({
        format: 'd-M-yyyy',
        autoclose: true,
        // startDate: '-0m',
    });
});

function onClickPreview(action) { 
    $('#errorModal').hide();
    
        if($('#ARRIVAL_DATE').val() == '' && ( !$('#RESV_INDIV').is(':checked') && !$('#RESV_BLOCK').is(':checked') && !$('#IN_HOUSE_GUESTS').is(':checked')&& ($('#CONFIRM_NO').val() == ''))){
            $('#errorModal').show();        
            error = '<ul><li>Arrival date is required</li><li>Reservation type is required</li></ul>';   
            $('#formErrorMessage').html(error);
            
        }
        else if($('#ARRIVAL_DATE').val() == '' && !$('#IN_HOUSE_GUESTS').is(':checked') && ($('#CONFIRM_NO').val() == '')){
            $('#errorModal').show();        
            error = '<ul><li>Arrival date is required</li></ul>';   
            $('#formErrorMessage').html(error);            
        }
    
        else if( !$('#RESV_INDIV').is(':checked') && !$('#RESV_BLOCK').is(':checked')){
            $('#errorModal').show();   
            error = '<ul><li>Reservation type is required</li></ul>';  
            $('#formErrorMessage').html(error);
           
        }
        else{

            $.ajax({
                url: '<?= base_url('registerCardSaveDetails') ?>',
                type: "post",
                data: $('#submitForm').serializeArray(),
                dataType: 'json',
                success: function(response) {

                    if (response > 0) {
                        if(action == 1)                   
                        window.open('<?= base_url('/registerCardPreview') ?>', '_blank');
                        else if(action == 2)
                        window.open('<?= base_url('/registerCardPrint') ?>', '_blank');
                        
                    }
                    else{
                        $('#errorModal').show();   
                        error = '<ul><li>No reservations found</li></ul>';  
                        $('#formErrorMessage').html(error);

                    }
                }
            });  
    }
          
}

</script>



<?= $this->endSection() ?>