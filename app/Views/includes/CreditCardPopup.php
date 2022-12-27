<!-- Add New Credit Card Modal -->
<div class="modal fade" id="editCCModal" tabindex="-1" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc">
<div class="modal-content p-3 p-md-5">
<div class="modal-body">
    <button
    type="button"
    class="btn-close"
    data-bs-dismiss="modal"
    aria-label="Close"
    ></button>
    <div class="text-center mb-4">
    <h3> Card Details</h3>
    <p>Enter your card details</p>
    </div>
    <form id="editCCForm" class="row g-3" onsubmit="return false">
    <input type="hidden" name="RESERVATION_CARD_RESVID" id="RESERVATION_CARD_RESVID">
    <div class="col-12">
        <label class="form-label w-100" for="modalEditCard">Card Number</label>
        <div class="input-group input-group-merge">
        <input
            id="RESERVATION_CARD_NUMBER"
            name="RESERVATION_CARD_NUMBER"
            class="form-control credit-card-mask-edit"
            type="text"
            placeholder="4356 3215 6548 7898"
            aria-describedby="modalEditCard2"
        />
        <span class="input-group-text p-1 cursor-pointer" id="modalEditCard2"
            ><span class="card-type-edit"></span
        ></span>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <label class="form-label" for="modalEditName">Name</label>
        <input
        type="text"
        id="RESERVATION_CARD_NAME"
        name="RESERVATION_CARD_NAME"
        class="form-control"
        placeholder="John Doe"
        />
    </div>
    <div class="col-6 col-md-3">
        <label class="form-label" for="modalEditExpiryDate">Exp. Date</label>
        <input
        type="text"
        id="RESERVATION_CARD_EXPIRY"
        name="RESERVATION_CARD_EXPIRY"
        class="form-control expiry-date-mask-edit"
        placeholder="MM/YY"
        />
    </div>
    <div class="col-6 col-md-3">
        <label class="form-label" for="modalEditCvv">CVV Code</label>
        <div class="input-group input-group-merge">
        <input
            type="text"
            id="RESERVATION_CARD_CVV"
            name="RESERVATION_CARD_CVV"
            class="form-control cvv-code-mask-edit"
            maxlength="3"
            placeholder="654"
            value="XXX"
        />
        <span class="input-group-text cursor-pointer" id="modalEditCvv2"
            ><i
            class="bx bx-help-circle text-muted"
            data-bs-toggle="tooltip"
            data-bs-placement="top"
            title="Card Verification Value"
            ></i
        ></span>
        </div>
    </div>
    <!-- <div class="col-12">
        <label class="switch">
        <input type="checkbox" class="switch-input" checked />
        <span class="switch-toggle-slider">
            <span class="switch-on">
            <i class="bx bx-check"></i>
            </span>
            <span class="switch-off">
            <i class="bx bx-x"></i>
            </span>
        </span>
        <span class="switch-label">Set as primary card</span>
        </label>
    </div> -->
    <div class="col-12 text-center mt-4">
        <button type="submit" class="btn btn-primary me-1 me-sm-3" id="savecard">Submit</button>
        <button
        type="reset"
        class="btn btn-label-secondary"
        data-bs-dismiss="modal"
        aria-label="Close"
        >
        Cancel
        </button>
    </div>
    </form>
</div>
</div>
</div>
</div>
<!--/ Add New Credit Card Modal -->
<script>
// $(document).on('change', '#RESV_PAYMENT_TYPE', function(e) {   
// 		var payment_type = $(this).val();
//         // alert(payment_type)
//         var resv_id = $("#RESV_ID").val();
//         //$("#editCCModal").modal('hide');
//         $("#editCCModal").modal('hide');
//         if(payment_type === "MSCARD" && resv_id == ''){
//             $("#editCCModal").modal('show');
//         }else if(payment_type === "MSCARD") {
//             $.ajax({
                // url: '<?php /*echo base_url('/getCreditCardDetails') */?>',
//                 type: "post",
//                 data:{sysid:resv_id},
//                 headers: {
//                     'X-Requested-With': 'XMLHttpRequest'
//                 },
//                 async: false,
//                 dataType:'json',
//                 success: function(respn) {  
//                     //alert(respn)
//                     if(respn != ''){
//                         $(respn).each(function(inx, data) {
//                             $.each(data, function(fields, datavals) {                   
//                                 var field = $.trim(fields);
//                                 var dataval = $.trim(datavals);    
//                                     $('#' + field).val(dataval);
//                             });

//                         });
                        
//                     }
//                     $("#editCCModal").modal('show');
                
//                 }
//             });
            

//         }
// 	});

// $(document).on('click', '#savecard', function() {
  
//     hideModalAlerts();
//     var formSerialization = $('#editCCForm').serializeArray();
//     var url = '<?php /*echo base_url('/insertCard')*/ ?>';
//     $.ajax({
//         url: url,
//         type: "post",
//         data: formSerialization,
//         headers: {
//             'X-Requested-With': 'XMLHttpRequest'
//         },
//         dataType: 'json',
//         success: function(respn) {
          
//             var response = respn['SUCCESS'];
//             if (response == '2') {
//                 mcontent = '<li>Something went wrong</li>';
//                 showModalAlert('error', mcontent);
//             } else if (response != '1') {
//                 var ERROR = respn['RESPONSE']['ERROR'];
//                 var mcontent = '';
//                 $.each(ERROR, function(ind, data) {
//                     //console.log(data, "SDF");
//                     mcontent += '<li>' + data + '</li>';
//                 });
//                 showModalAlert('error', mcontent);
//             } else {
//                 var alertText = $('#RESERVATION_CARD_RESVID').val() == '' ?
//                     '<li>Successfully added</li>' :
//                     '<li>Successfully updated</li>';
//                 hideModalAlerts();
//                 showModalAlert('success', alertText);


//                 if (respn['RESPONSE']['OUTPUT'] != '') {                  
//                     $('#RESERVATION_CARD_RESVID').val(respn['RESPONSE']['OUTPUT']);   
//                 }
//             }
//         }
//     });
// });




</script>

