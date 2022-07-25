<!-- Add / Edit Membership Form -->

<div class="modal fade" id="memModalWindow" data-backdrop="static" data-keyboard="false"
    aria-labelledby="memModalWindowlabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="memModalWindowlabel">Customer Membership</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="submitMemForm" class="needs-validation" novalidate>
                    <div class="row g-3">
                        <input type="hidden" name="CM_ID" id="CM_ID" class="form-control" />
                        <input type="hidden" name="CM_TODAY" id="CM_TODAY" value="<?php echo date('Y-m-d'); ?>"
                            readonly />
                        <input type="hidden" name="CM_CUST_ID" id="CM_CUST_ID" readonly />

                        <div class="border rounded p-3">

                            <div class="col-md-12">
                                <div class="row mb-3">
                                    <label for="CUST_NAME" class="col-form-label col-md-3"><b>Membership *</b></label>
                                    <div class="col-md-6">
                                        <input type="text" name="CUST_NAME" id="CUST_NAME"
                                            class="form-control bootstrap-maxlength" maxlength="50" readonly />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="MEM_ID" class="col-form-label col-md-3"><b>Membership Type
                                            *</b></label>
                                    <div class="col-md-6">
                                        <select id="MEM_ID" name="MEM_ID" class="select2 form-select form-select-lg"
                                            data-allow-clear="true" required>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="CM_CARD_NUMBER" class="col-form-label col-md-3"><b>Card Number
                                            *</b></label>
                                    <div class="col-md-7">
                                        <input type="text" name="CM_CARD_NUMBER" id="CM_CARD_NUMBER"
                                            class="form-control bootstrap-maxlength" maxlength="50" placeholder=""
                                            required />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="CM_NAME_CARD" class="col-form-label col-md-3"><b>Name on
                                            Card *</b></label>
                                    <div class="col-md-7">
                                        <input type="text" name="CM_NAME_CARD" id="CM_NAME_CARD"
                                            class="form-control bootstrap-maxlength" maxlength="50" placeholder=""
                                            required />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="CM_EXPIRY_DATE" class="col-form-label col-md-3">Expiry Date</label>
                                    <div class="col-md-4">
                                        <input type="text" id="CM_EXPIRY_DATE" name="CM_EXPIRY_DATE"
                                            class="form-control flatpickr-input" placeholder="MM/YYYY">
                                    </div>
                                </div>

                                <div class="row">
                                    <label for="CM_DIS_SEQ" class="col-form-label col-md-3">Sequence</label>
                                    <div class="col-md-2">
                                        <input type="number" name="CM_DIS_SEQ" id="CM_DIS_SEQ" class="form-control"
                                            min="0" placeholder="eg: 3" />
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="border rounded p-3">
                            <div class="row">

                                <div class="row mb-3">
                                    <label for="CM_MEMBER_SINCE" class="col-form-label col-md-3">Member Since</label>
                                    <div class="col-md-4">
                                        <input type="text" id="CM_MEMBER_SINCE" name="CM_MEMBER_SINCE"
                                            class="form-control flatpickr-input" placeholder="YYYY-MM-DD">
                                    </div>
                                </div>

                                <div class="row">
                                    <label for="CM_COMMENTS" class="col-form-label col-md-3">Comments</label>
                                    <div class="col-md-9">
                                        <textarea rows="3" class="form-control" name="CM_COMMENTS"
                                            id="CM_COMMENTS"></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="text-center memStatusCheck">
                            <div class="col-md-12">
                                <label class="switch switch-lg">
                                    <input id="CM_STATUS" name="CM_STATUS" type="checkbox" value="1"
                                        class="switch-input" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label">Active</span>
                                </label>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="submitMemBtn" onClick="submitMemForm('submitMemForm')"
                    class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {

    $("#CM_MEMBER_SINCE").datepicker({
        format: "yyyy-mm-dd"
    });

    $("#CM_EXPIRY_DATE").datepicker({
        format: "mm/yyyy",
        startView: "months",
        minViewMode: "months",
        startDate: '+1m'
    });

});

// Get Membership Types not already added for Customer

function fillMembershipTypes(custId, mode = 'add', field = '#MEM_ID') {
    var jqXHR2 = $.ajax({
        url: '<?php echo base_url('/showMembershipTypeList')?>',
        type: "post",
        async: false,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            custId: custId,
            mode: mode
        },
        dataType: 'json',
        success: function(respn) {

            var memTypeSelect = $(field);
            memTypeSelect.empty().trigger("change");

            $(respn).each(function(inx, data) {
                var newOption = new Option(data.code + ' | ' + data.text, data.id, false, false);
                newOption.setAttribute('expdate-req', data.exp_date_req);
                memTypeSelect.append(newOption);
            });
            memTypeSelect.val(null).trigger('change');
        }
    });
}


// Get Customer Memberships added for Customer

function fillCustomerMemberships(custId, mode = 'add', field = '#RESV_MEMBER_TY') {
    var jqXHR2 = $.ajax({
        url: '<?php echo base_url('/getCustomerMembershipsList')?>',
        type: "post",
        async: false,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            custId: custId,
            mode: mode
        },
        dataType: 'json',
        success: function(respn) {

            var memTypeSelect = $(field);
            memTypeSelect.empty().trigger("change");

            $(respn).each(function(inx, data) {
                var dataText = data.text;
                var dataArr = dataText.split('|');
                var newOption = new Option($.trim(dataArr[1]), data.id, false, false);
                newOption.setAttribute('membership-type', $.trim(dataArr[0]));
                newOption.setAttribute('card-no', data.card_no);
                memTypeSelect.append(newOption);
            });
            memTypeSelect.val(null).trigger('change');
        }
    });
}

//Set Card Number on selecting Membership

$(document).on('select2:select', '#RESV_MEMBER_TY,#RESV_MEMBER_TY_ADD', function() {
    $(this).closest('.window-2,.window-1').find('#RESV_MEMBER_NO').val($(this).find(':selected').attr('card-no'));
});

$(document).on('select2:unselect', '#RESV_MEMBER_TY,#RESV_MEMBER_TY_ADD', function() {
    $(this).closest('.window-2,.window-1').find('#RESV_MEMBER_NO').val("");
});



//Change required status of expiry date based on membership type selection 

$('#MEM_ID').on('select2:select', function() {

    var exp_date_req = $(this).find(':selected').attr('expdate-req');
    $("label[for='CM_EXPIRY_DATE']").html(exp_date_req == '1' ? "<b>Expiry Date *</b>" : "Expiry Date");
    $("#CM_EXPIRY_DATE").attr("required", exp_date_req == '1' ? true : false);

});

function getCustomerDetails(custId) {
    var jqXHR = $.ajax({
        url: '<?php echo base_url('/editCustomer')?>',
        type: "post",
        async: false,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            sysid: custId
        },
        dataType: 'json'
    });

    var custData = jqXHR.responseText;

    custData = custData.substring(1, parseInt(custData.length - 1)); // Remove square brackets
    var custArray = JSON.parse(custData);

    return custArray;
}

function checkAddMem(secId) {
    if ($('.' + secId).find('#RESV_NAME').val() == '') {
        showModalAlert('error', '<li>Please select a Customer first</li>');
    } else
        addMemForm('.' + secId);
}

// Show Add Customer Membership Form

function addMemForm(secId) {
    $(':input', '#submitMemForm').not('[type="radio"],[type="hidden"]').val('').prop('checked', false).prop('selected',
        false);
    //clearFormFields('#submitMemForm');

    var custOptId = $(secId).find('#RESV_NAME').val();

    var custArray = getCustomerDetails(custOptId);
    $('#CUST_NAME,#CM_NAME_CARD').val(custArray.CUST_FIRST_NAME + ' ' + custArray.CUST_LAST_NAME);

    $('#CM_MEMBER_SINCE').datepicker("setDate", new Date());

    fillMembershipTypes(custOptId, 'add', '#MEM_ID');

    //$('.select2').val(null).trigger('change');

    $('#submitMemBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    $('#memModalWindowlabel').html('Add New Customer Membership');
    $(".memStatusCheck").hide();
    $('#memModalWindow').modal('show');
    $("#CM_STATUS").prop('checked', 'checked');
}

// Show Edit Customer Membership Form

$(document).on('click', '.editMemWindow', function() {

    var sysid = $(this).attr('data_sysid');

    $(':input', '#submitMemForm').not('[type="radio"],[type="hidden"]').val('').prop('checked', false).prop(
        'selected',
        false);

    var custOptId = $('.show-cust-memberships').attr('data_sysid');
    var custArray = getCustomerDetails(custOptId);
    $('#CUST_NAME,#CM_NAME_CARD').val(custArray.CUST_FIRST_NAME + ' ' + custArray.CUST_LAST_NAME);

    fillMembershipTypes(custOptId, 'edit', '#MEM_ID');

    $('#memModalWindowlabel').html('Edit Customer Membership');
    $(".memStatusCheck").show();
    $('#memModalWindow').modal('show');

    var url = '<?php echo base_url('/editCustomerMembership') ?>';
    $.ajax({
        url: url,
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            sysid: sysid
        },
        dataType: 'json',
        success: function(respn) {
            $(respn).each(function(inx, data) {
                $.each(data, function(fields, datavals) {
                    var field = $.trim(fields); //fields.trim();
                    var dataval = $.trim(datavals); //datavals.trim();

                    if (field == 'MEM_ID') {
                        $('#' + field).select2("val", dataval);
                    } else if ($('#' + field).attr('type') == 'checkbox') {
                        $('#' + field).prop('checked', dataval == 1 ? true : false);
                    } else {
                        $('#' + field).val(dataval);
                    }

                });
            });
            $('#submitMemBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
        }
    });
});


// Add New or Edit Customer Membership submit Function

function submitMemForm(id) {
    hideModalAlerts();
    var formSerialization = $('#' + id).serializeArray();
    var url = '<?php echo base_url('/insertCustomerMembership') ?>';
    $.ajax({
        url: url,
        type: "post",
        data: formSerialization,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        success: function(respn) {

            var response = respn['SUCCESS'];
            if (response != '1') {
                var ERROR = respn['RESPONSE']['ERROR'];
                var mcontent = '';
                $.each(ERROR, function(ind, data) {
                    mcontent += '<li>' + data + '</li>';
                });
                showModalAlert('error', mcontent);
            } else {
                var alertText = $('#CM_ID').val() == '' ?
                    '<li>The new Membership has been added to the Customer</li>' :
                    '<li>The Customer Membership has been updated</li>';
                showModalAlert('success', alertText);

                $('#memModalWindow').modal('hide');

                afterMemFormClose();
            }
        }
    });
}
</script>