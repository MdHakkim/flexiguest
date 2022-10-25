<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="breadcrumb-wrapper py-3 mb-4">
                Edit Profile
            </h4>

            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <h5 class="card-header">Profile Details</h5>
                        <!-- Account -->
                        <form id="submitForm" class="needs-validation" enctype="multipart/form-data" novalidate>
                            <div class="card-body">
                                <div class="gap-4 d-flex align-items-start align-items-sm-center">
                                    <img src="<?php echo file_exists($profile_data['USR_IMAGE']) ? base_url().'/'.$profile_data['USR_IMAGE'] : base_url().'/assets/img/avatars/avatar-generic.jpg'; ?>"
                                        alt="user-avatar" class="d-block rounded" height="100" width="100"
                                        id="uploadedAvatar" />
                                    <div class="button-wrapper">
                                        <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                            <span class="d-none d-sm-block">Upload new photo</span>
                                            <i class="bx bx-upload d-sm-none d-block"></i>
                                            <input type="file" name="USR_IMAGE" id="upload" class="account-file-input"
                                                hidden accept="image/png, image/jpeg" />
                                        </label>
                                        <button type="button" class="btn btn-label-secondary account-image-reset mb-4">
                                            <i class="bx bx-reset d-sm-none d-block"></i>
                                            <span class="d-none d-sm-block">Reset</span>
                                        </button>

                                        <p class="mb-0">Allowed JPG or PNG. Max size of 2MB</p>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-0" />
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label for="html5-text-input" class="col-form-label"><b>USER NAME
                                                        *</b></label>
                                                <input type="text" id="USR_NAME" name="USR_NAME" class="form-control"
                                                    placeholder="User Name">

                                            </div>
                                            <div class="col-md-4">
                                                <label for="html5-text-input" class="col-form-label"><b>FIRST NAME
                                                        *</b></label>
                                                <input type="text" id="USR_FIRST_NAME" name="USR_FIRST_NAME"
                                                    class="form-control" placeholder="First Name">

                                            </div>

                                            <div class="col-md-4">
                                                <label for="html5-text-input" class="col-form-label"><b>LAST NAME
                                                        *</b></label>
                                                <input type="text" id="USR_LAST_NAME" name="USR_LAST_NAME"
                                                    class="form-control" placeholder="Last Name">

                                            </div>
                                        </div>

                                        <div class="row mb-3">

                                            <div class="col-md-4">
                                                <label for="html5-text-input" class="col-form-label"><b>EMAIL
                                                        *</b></label>
                                                <input type="text" id="USR_EMAIL" name="USR_EMAIL" class="form-control"
                                                    placeholder="Email">

                                            </div>

                                            <div class="col-md-4">
                                                <label for="html5-text-input" class="col-form-label"><b>PASSWORD
                                                        *</b></label>
                                                <input type="password" id="USR_PASSWORD" name="USR_PASSWORD"
                                                    class="form-control" placeholder="Password">

                                            </div>
                                            <div class="col-md-4">
                                                <label for="html5-text-input" class="col-form-label"><b>CONFIRM PASSWORD
                                                        *</b></label>
                                                <input type="password" id="USR_CONFIRM_PASSWORD"
                                                    name="USR_CONFIRM_PASSWORD" class="form-control" placeholder="">

                                            </div>

                                        </div>
                                        <div class="row mb-3">

                                            <div class="col-md-4">
                                                <label for="html5-text-input" class="col-form-label"><b>Employee Number
                                                    </b></label>
                                                <input type="text" id="USR_NUMBER" name="USR_NUMBER"
                                                    class="form-control" placeholder="Employee Number">
                                                </select>
                                            </div>


                                            <div class="col-md-4">
                                                <label for="html5-text-input" class="col-form-label"><b>User Role
                                                        *</b></label>
                                                <select id="USR_ROLE_ID" name="USR_ROLE_ID"
                                                    class="select2 form-select form-select-lg" data-allow-clear="true"
                                                    required>
                                                </select>
                                                <input type="hidden" name="USR_ROLE" id="USR_ROLE"
                                                    class="form-control" />
                                            </div>

                                            <div class="col-md-4">
                                                <label for="html5-text-input" class="col-form-label"><b>Department
                                                    </b></label>
                                                <select id="USR_DEPARTMENT" name="USR_DEPARTMENT"
                                                    class="select2 form-select form-select-lg" data-allow-clear="true"
                                                    required>
                                                    <?= $departmentList ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label for="html5-text-input" class="col-form-label"><b>DOJ
                                                    </b></label>
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <input type="text" id="USR_DOJ" name="USR_DOJ"
                                                            class="form-control" placeholder="DD-MM-YYYY">
                                                        <span class="input-group-append">
                                                            <span class="input-group-text bg-light d-block">
                                                                <i class="fa fa-calendar"></i>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="html5-text-input" class="col-form-label"><b>COUNTRY
                                                    </b></label>
                                                <select id="USR_COUNTRY" name="USR_COUNTRY"
                                                    class="select2 form-select form-select-lg" data-allow-clear="true"
                                                    required>

                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="html5-text-input" class="col-form-label"><b>STATE
                                                    </b></label>
                                                <select id="USR_STATE" name="USR_STATE"
                                                    class="select2 form-select form-select-lg" data-allow-clear="true"
                                                    required>

                                                </select>
                                            </div>


                                        </div>

                                        <div class="row mb-3">

                                            <div class="col-md-4">
                                                <label for="html5-text-input" class="col-form-label"><b>CITY
                                                    </b></label>
                                                <select id="USR_CITY" name="USR_CITY"
                                                    class="select2 form-select form-select-lg" data-allow-clear="true"
                                                    required>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="html5-text-input" class="col-form-label"><b>ADDRESS
                                                    </b></label>
                                                <textarea class="form-control" name="USR_ADDRESS" id="USR_ADDRESS"
                                                    rows="1"></textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="html5-text-input" class="col-form-label"><b>DOB
                                                        *</b></label>
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <input type="text" id="USR_DOB" name="USR_DOB"
                                                            class="form-control" placeholder="DD-MM-YYYY">
                                                        <span class="input-group-append">
                                                            <span class="input-group-text bg-light d-block">
                                                                <i class="fa fa-calendar"></i>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>


                                        <div class="row mb-3">

                                            <div class="col-md-4">
                                                <label for="html5-text-input" class="col-form-label"><b>Phone Number
                                                    </b></label>
                                                <input type="text" id="USR_PHONE" name="USR_PHONE" class="form-control"
                                                    placeholder="Phone Number">
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="html5-text-input" class="col-form-label"><b>Extension
                                                    </b></label>
                                                <input type="text" id="USR_TEL_EXT" name="USR_TEL_EXT"
                                                    class="form-control" placeholder="Extension">
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="html5-text-input" class="col-form-label"
                                                    style="display: block"><b>GENDER
                                                        *</b></label>
                                                <div class="form-check mb-2" style="float:left;margin-right:10px">
                                                    <input type="radio" id="USR_GENDER_1" name="USR_GENDER" value="1"
                                                        class="form-check-input" required="">
                                                    <label class="form-check-label"
                                                        for="bs-validation-radio-male">Male</label>
                                                </div>
                                                <div class="form-check" style="float:left">
                                                    <input type="radio" id="USR_GENDER_0" name="USR_GENDER" value="0"
                                                        class="form-check-input" required="">
                                                    <label class="form-check-label"
                                                        for="bs-validation-radio-female">Female</label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="mt-2">
                                    <button type="button" id="submitBtn" onClick="submitForm('submitForm')"
                                        class="btn btn-primary">Save changes</button>
                                    <a href="<?php echo base_url(); ?>/my-profile"
                                        class="btn btn-label-secondary">Cancel</a>
                                </div>
                            </div>
                        </form>
                        <!-- /Account -->
                    </div>
                </div>
            </div>
        </div>
        <!-- / Content -->

        <div class="content-backdrop fade"></div>
    </div>
    <!-- Content wrapper -->

    <script>
    $(document).ready(function() {

        $('#USR_DOB').datepicker({
            format: 'd-M-yyyy',
            autoclose: true,
        });
        $('#USR_DOJ').datepicker({
            format: 'd-M-yyyy',
            autoclose: true,
        });
        // Variable declaration for table
        var dt_user_table = $('.datatables-users'),
            select2 = $('.select2'),
            userView = '',
            statusObj = {
                // 0: {
                //   title: 'Pending',
                //   class: 'bg-label-warning'
                // },
                1: {
                    title: 'Active',
                    class: 'bg-label-success'
                },
                0: {
                    title: 'Inactive',
                    class: 'bg-label-secondary'
                }
            };

        if (select2.length) {
            var $this = select2;
            $this.wrap('<div class="position-relative"></div>').select2({
                placeholder: 'Select Country',
                dropdownParent: $this.parent()
            });
        }

        <?php
            if (!empty($editId)) {  ?>
        editUser(<?php echo $editId; ?>);
        <?php
            }
            ?>
    });


    // Add New or Edit Users submit Function

    function submitForm(id) {
        var user_role_text = $("#USR_ROLE_ID option:selected").text();
        $("#USR_ROLE").val(user_role_text);
        hideModalAlerts();

        var form = $('#' + id)[0];
        var formSerialization = new FormData(form);
        formSerialization.append('USR_ID', <?=$editId?>);
        formSerialization.append('USR_STATUS', 1);

        var url = '<?php echo base_url('/insertUser') ?>';
        $.ajax({
            url: url,
            type: "post",
            data: formSerialization,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            dataType: 'json',
            processData: false,
            contentType: false,
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

                    var alertText = '<li>Your profile has been updated</li>';
                    showModalAlert('success', alertText);
                    setTimeout(function() {
                        location.href = '<?php echo base_url('/my-profile') ?>';
                    }, 3000);
                }
            }
        });
    }


    function editUser(sysid) {
        countryList();
        roleList();

        $('#USR_ID').val(sysid);

        var url = '<?php echo base_url('/editUser') ?>';

        $.ajax({
            url: url,
            type: "post",
            async: false,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                sysid: sysid
            },
            dataType: 'json',
            success: function(respn) {
                var state_val = city_val = '';
                $(respn).each(function(inx, data) {
                    $.each(data, function(fields, datavals) {
                        var field = $.trim(fields); //fields.trim();
                        var dataval = $.trim(
                            datavals); //datavals.trim();                       


                        if (field == 'USR_ROLE_ID' || field == 'USR_DEPARTMENT') {

                            $('#' + field).select2("val", dataval);

                        } else if ($('#' + field).attr('type') == 'checkbox') {

                            $('#' + field).prop('checked', dataval == 1 ? true : false);

                        } else if ($('[name=' + field + ']').attr('type') == 'radio') {

                            $('#' + field + '_' + dataval).prop('checked', true);
                        } else if (field == 'USR_CITY') {

                            city_val = dataval;

                        } else if (field == 'USR_STATE') {

                            state_val = dataval;

                        } else if (field == 'USR_COUNTRY') {

                            $('#USR_COUNTRY').val(dataval).trigger('change', state_val);
                            $('#USR_STATE').val(state_val).trigger('change', city_val);

                        } else if (field == 'USR_PASSWORD') {
                            // $('#USR_PASSWORD').val(dataval);
                            // $('#USR_CONFIRM_PASSWORD').val($('#USR_PASSWORD').val());
                        } else if (field == 'USR_DOB' || field == 'USR_DOJ') {
                            //alert(dataval);
                            if (dataval == '01-Jan-1970')
                                $('#' + field).val('');
                            else
                                $('#' + field).datepicker("setDate", new Date(dataval));
                        } else if (field == 'USR_NUMBER' || field == 'USR_TEL_EXT') {
                            if (dataval == 0) {
                                $('#' + field).val('');
                            } else
                                $('#' + field).val(dataval);
                        } else {
                            $('#' + field).val(dataval);
                        }


                    });
                });
            }
        });
    }

    function countryList() {

        $.ajax({
            url: '<?php echo base_url('/userCountryList') ?>',
            type: "post",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            async: false,
            success: function(respn) {
                $('#USR_COUNTRY').html(respn);
            }
        });


    }

    $("#USR_COUNTRY").change(function(e, param = 0) {

        var ccode = $(this).val();
        $.ajax({
            url: '<?php echo base_url('/userStateList') ?>',
            type: "post",
            async: false,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                ccode: ccode,
                state_id: param
            },
            success: function(respn) {
                $('#USR_STATE').html(respn);

            }
        });
    });

    $("#USR_STATE").change(function(e, param = 0) {
        var scode = $('#USR_STATE').val();
        var ccode = $('#USR_COUNTRY').val();
        $.ajax({
            url: '<?php echo base_url('/userCityList') ?>',
            type: "post",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                ccode: ccode,
                scode: scode,
                cityid: param
            },
            success: function(respn) {
                $('#USR_CITY').html(respn);
            }
        });
    });

    function roleList() {
        $.ajax({
            url: '<?php echo base_url('/roleList') ?>',
            type: "GET",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            async: false,
            success: function(respn) {
                $('#USR_ROLE_ID').html(respn);
            }
        });

    }

    document.addEventListener('DOMContentLoaded', function(e) {
        (function() {

            // Update/reset user image of account page
            let accountUserImage = document.getElementById('uploadedAvatar');
            const fileInput = document.querySelector('.account-file-input'),
                resetFileInput = document.querySelector('.account-image-reset');

            if (accountUserImage) {
                const resetImage = accountUserImage.src;
                fileInput.onchange = () => {
                    if (fileInput.files[0]) {
                        accountUserImage.src = window.URL.createObjectURL(fileInput.files[0]);
                    }
                };
                resetFileInput.onclick = () => {
                    fileInput.value = '';
                    accountUserImage.src = resetImage;
                };
            }
        })();
    });
    </script>

    <?= $this->endSection() ?>