<div class="modal fade" id="edit-customer" tabindex="-1" aria-labelledby="editCustomerLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCustomerLabel">Edit Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="edit-customer-form">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <input type="hidden" name="CUST_ID" class="form-control" />
                            <label class="form-label">First Name</label>
                            <input type="text" name="CUST_FIRST_NAME" class="form-control" placeholder="first name" />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Middle Name</label>
                            <input type="text" name="CUST_MIDDLE_NAME" class="form-control" placeholder="middle name" />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="CUST_LAST_NAME" class="form-control" placeholder="last name" />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Language/Title</label>
                            <div class="form-group flxi_join">
                                <select name="CUST_LANG" class="form-select" data-allow-clear="true">
                                    <option value="">Select</option>
                                    <option value="EN">English</option>
                                    <option value="AR">Arabic</option>
                                    <option value="FR">French</option>
                                </select>
                                <select name="CUST_TITLE" class="form-select" data-allow-clear="true">
                                    <option value="">Select</option>
                                    <option value="Mr">Mr.</option>
                                    <option value="Ms">Ms.</option>
                                    <option value="Shiekh.">Shiekh.</option>
                                    <option value="Shiekha.">Shiekha.</option>
                                    <option value="Dr.">Dr.</option>
                                    <option value="Ambassador.">Ambassador.</option>
                                    <option value="Prof.">Prof.</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">DOB</label>
                            <div class="input-group mb-3">
                                <input type="text" name="CUST_DOB" class="form-control flatpickr-input" placeholder="YYYY-MM-DD">
                                <span class="input-group-append">
                                    <span class="input-group-text bg-light d-block">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Passport</label>
                            <input type="text" name="CUST_PASSPORT" class="form-control" placeholder="passport" />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Address</label>
                            <input type="text" name="CUST_ADDRESS_1" class="form-control" placeholder="addresss 1" />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"></label>
                            <input type="text" name="CUST_ADDRESS_2" class="form-control" placeholder="address 2" />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"></label>
                            <input type="text" name="CUST_ADDRESS_3" class="form-control" placeholder="address 3" />
                        </div>
                        <div class="col-md-3 ">
                            <label class="form-label col-md-12">Country</label>
                            <select name="CUST_COUNTRY" data-width="100%" class="selectpicker CUST_COUNTRY" data-live-search="true">
                                <option value="">Select</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label col-md-12">State</label>
                            <select name="CUST_STATE" data-width="100%" class="selectpicker CUST_STATE" data-live-search="true">
                                <option value="">Select</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label col-md-12">City</label>
                            <select name="CUST_CITY" data-width="100%" class="selectpicker CUST_CITY" data-live-search="true">
                                <option value="">Select</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Email</label>
                            <input type="text" name="CUST_EMAIL" class="form-control" placeholder="email" />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Mobile</label>
                            <input type="text" name="CUST_MOBILE" class="form-control" placeholder="mobile" />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="CUST_PHONE" class="form-control" placeholder="phone" />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Client ID</label>
                            <input type="text" name="CUST_CLIENT_ID" class="form-control" placeholder="client id" />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Postal Code</label>
                            <input type="text" name="CUST_POSTAL_CODE" class="form-control" placeholder="postal" />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">VIP</label>
                            <select name="CUST_VIP" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select VIP</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Nationality</label>
                            <select name="CUST_NATIONALITY" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Business Segment</label>
                            <select name="CUST_BUS_SEGMENT" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Communication</label>
                            <select name="CUST_COMMUNICATION" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select Communication</option>
                                <option value="WEB">Web</option>
                                <option value="WHATSAPP">Whatsapp</option>
                                <option value="FAX">Fax</option>
                                <option value="OTHER">Other</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Communcation Desc.</label>
                            <input type="text" name="CUST_COMMUNICATION_DESC" class="form-control" placeholder="communication desc" />
                        </div>
                        <div class="col-md-3">
                            <div class="form-check mt-3">
                                <input class="form-check-input flxCheckBox" type="checkbox" name="CUST_ACTIVE_CHK">
                                <input type="hidden" name="CUST_ACTIVE" value="N" class="form-control" />
                                <label class="form-check-label" for="defaultCheck1"> Active </label>
                            </div>

                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="edit-customer-submitBtn" onClick="submitEditCustomerForm('edit-customer-form','C')" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- /Modal window -->

<script>
    const month_names = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
    ];
    var form_id = '#edit-customer-form';

    $(document).ready(function() {
        $(`${form_id} input[name='CUST_DOB']`).datepicker({
            format: 'd-M-yyyy',
            autoclose: true
        });
    });

    function submitEditCustomerForm(id, mode) {
        var formSerialization = $('#' + id).serializeArray();
        var url = '<?php echo base_url('/insertCustomer') ?>';
        $.ajax({
            url: url,
            type: "post",
            data: formSerialization,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response['SUCCESS'] != '1') {

                    var ERROR = response['RESPONSE']['ERROR'];
                    var mcontent = '';
                    $.each(ERROR, function(ind, data) {
                        mcontent += '<li>' + data + '</li>';
                    });
                    showModalAlert('error', mcontent);
                } else {

                    showModalAlert('success', "Customer has been updated successfully.");
                    $('#edit-customer').modal('hide');
                    $('#dataTable_view').dataTable().fnDraw();
                }
            }
        });
    }

    $(document).on('change', `${form_id} select[name='CUST_COUNTRY']`, function() {
        var ccode = $(this).val();
        $.ajax({
            url: '<?php echo base_url('/stateList') ?>',
            type: "post",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                ccode: ccode
            },
            // dataType:'json',
            success: function(respn) {
                $(`${form_id} select[name='CUST_STATE']`).html(respn).selectpicker('refresh');
            }
        });
    });

    $(document).on('change', `${form_id} select[name='CUST_STATE']`, function() {
        var ccode = $(`${form_id} select[name='CUST_COUNTRY']`).find('option:selected').val();
        var scode = $(this).val();
        $.ajax({
            url: '<?php echo base_url('/cityList') ?>',
            type: "post",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                ccode: ccode,
                scode: scode
            },
            // dataType:'json',
            success: function(respn) {
                $(`${form_id} select[name='CUST_CITY']`).html(respn).selectpicker('refresh');
            }
        });
    });

    function editCustomerRunCountryList() {
        $.ajax({
            url: '<?php echo base_url('/countryList') ?>',
            type: "post",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            async: false,
            // dataType:'json',
            success: function(respn) {
                // $(`${form_id} select[name='CUST_COUNTRY']`).html(respn).selectpicker('refresh');
                $(`${form_id} select[name='CUST_COUNTRY']`).html(respn).selectpicker('refresh');
                $(`${form_id} select[name='CUST_NATIONALITY']`).html(respn);
            }
        });
    }

    function editCustomerRunSupportingLov() {
        $.ajax({
            url: '<?php echo base_url('/getSupportingLov') ?>',
            type: "post",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            dataType: 'json',
            async: false,
            success: function(respn) {
                var vipData = respn[0];
                var busegmt = respn[1];
                var option = '<option value="">Select Vip</option>';
                var option2 = '<option value="">Select Segment</option>';

                $(vipData).each(function(ind, data) {
                    option += '<option value="' + data['VIP_ID'] + '">' + data['VIP_DESC'] + '</option>';
                });
                $(busegmt).each(function(ind, data) {
                    option2 += '<option value="' + data['BUS_SEG_CODE'] + '">' + data['BUS_SEG_DESC'] + '</option>';
                });
                $(`${form_id} select[name='CUST_VIP']`).html(option);
                $(`${form_id} select[name='CUST_BUS_SEGMENT']`).html(option2);
            }
        });
    }

    $(document).on('click', '.editcustomer', function() {
        editCustomerRunCountryList();
        editCustomerRunSupportingLov();
        var sysid = $(this).attr('data_sysid');
        $('#edit-customer').modal('show');

        $.ajax({
            url: '<?php echo base_url('/editCustomer') ?>',
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
                        var field = $.trim(fields);
                        var dataval = $.trim(datavals);

                        if (field == 'CUST_BUS_SEGMENT')
                            dataval = datavals;

                        if (field == 'CUST_COUNTRY_DESC' || field == 'CUST_STATE_DESC' || field == 'CUST_CITY_DESC') {
                            return true;
                        };

                        if (field == 'CUST_DOB') {
                            let dob_date = new Date(dataval);
                            dataval = dob_date.getDate() + '-' + month_names[dob_date.getMonth()] + '-' + dob_date.getFullYear();
                        }

                        if (field == 'CUST_ACTIVE') {
                            if (dataval == 'Y')
                                $(`${form_id} input[name='CUST_ACTIVE_CHK']`).prop('checked', true);
                            else
                                $(`${form_id} input[name='CUST_ACTIVE_CHK']`).prop('checked', false)

                        } else if ($(`${form_id} input[name='${field}']`).length)
                            $(`${form_id} input[name='${field}']`).val(dataval);

                        else if ($(`${form_id} select[name='${field}']`).length && $(`${form_id} select[name='${field}']`).hasClass('selectpicker') && dataval) {
                            if (field == 'CUST_STATE' || field == 'CUST_CITY') {
                                var option = '<option value="' + dataval + '">' + data[field + '_DESC'] + '</option>';
                                $(`${form_id} select[name='${field}']`).html(option).selectpicker('refresh');
                            } else
                                $(`${form_id} select[name='${field}']`).val(dataval).selectpicker('refresh');

                        } else if ($(`${form_id} select[name='${field}']`).length)
                            $(`${form_id} select[name='${field}']`).val(dataval).trigger('change');
                    });
                });
                $('#edit-customer-submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
            }
        });
    });
</script>