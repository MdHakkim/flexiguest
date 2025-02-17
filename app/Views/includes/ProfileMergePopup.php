<style>
.tagify__input {
    padding-left: 6px;
}

.table-hover>tbody>tr:hover {
    cursor: pointer;
}

.table-warning {
    color: #000 !important;
}

#compareProfiles>tbody>tr>td:first-of-type {
    font-weight: bold;
}
</style>

<!-- Combined Profiles Modal -->

<div class="modal fade" id="profileMergeSearch" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileMergeSearchLabel">Select a Profile to Merge with Current Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <?= $this->include('includes/CombinedProfilesTable') ?>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary close_selected_profiles" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn btn-dark use_selected_profiles" disabled>Use Selected
                    Profile</button>
            </div>
        </div>
    </div>
</div>

<!-- Compare Merge Profiles Modal -->

<div class="modal fade" id="profileMergeCompare" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="pricing-plans-comparison">
                    <div class="container mb-3 mt-0">
                        <div class="row">
                            <div class="col-12 text-center mb-4">
                                <h3 class="mb-4">Merge Profiles</h3>
                            </div>
                        </div>
                        <div class="row mx-0">
                            <div class="col-12">
                                <div class="table-responsive compareProfiles_div">
                                    <table id="compareProfiles" class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col"></th>
                                                <th scope="col">
                                                    <p class="h6 mb-2">Profile to Merge</p>
                                                </th>
                                                <th scope="col">
                                                    <p class="h6 mb-2">Copy / Replace To</p>
                                                </th>
                                                <th scope="col">
                                                    <p class="h6 mb-2">Original Profile</p>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Name</td>
                                                <td>
                                                    <span id="1_CUST_FULL_NAME"></span>
                                                </td>
                                                <td class="px-4">
                                                    <label class="switch switch-lg switch-info">
                                                        <input id="chk_CUST_FULL_NAME" name="chk_CUST_FULL_NAME"
                                                            type="checkbox" class="switch-input" value="1">
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bxs-right-arrow-alt bx-sm"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                            </span>
                                                        </span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <span id="0_CUST_FULL_NAME"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Title</td>
                                                <td>
                                                    <span id="1_CUST_TITLE"></span>
                                                </td>
                                                <td class="px-4">
                                                    <label class="switch switch-lg switch-info">
                                                        <input id="chk_CUST_TITLE" name="chk_CUST_TITLE" type="checkbox"
                                                            class="switch-input" value="1">
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bxs-right-arrow-alt bx-sm"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                            </span>
                                                        </span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <span id="0_CUST_TITLE"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Language</td>
                                                <td>
                                                    <span id="1_CUST_LANG"></span>
                                                </td>
                                                <td class="px-4">
                                                    <label class="switch switch-lg switch-info">
                                                        <input id="chk_CUST_LANG" name="chk_CUST_LANG" type="checkbox"
                                                            class="switch-input" value="1">
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bxs-right-arrow-alt bx-sm"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                            </span>
                                                        </span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <span id="0_CUST_LANG"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Address 1</td>
                                                <td>
                                                    <span id="1_CUST_ADDRESS_1"></span>
                                                </td>
                                                <td class="px-4">
                                                    <label class="switch switch-lg switch-info">
                                                        <input id="chk_CUST_ADDRESS_1" name="chk_CUST_ADDRESS_1"
                                                            type="checkbox" class="switch-input" value="1">
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bxs-right-arrow-alt bx-sm"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                            </span>
                                                        </span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <span id="0_CUST_ADDRESS_1"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Address 2</td>
                                                <td>
                                                    <span id="1_CUST_ADDRESS_2"></span>
                                                </td>
                                                <td class="px-4">
                                                    <label class="switch switch-lg switch-info">
                                                        <input id="chk_CUST_ADDRESS_2" name="chk_CUST_ADDRESS_2"
                                                            type="checkbox" class="switch-input" value="1">
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bxs-right-arrow-alt bx-sm"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                            </span>
                                                        </span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <span id="0_CUST_ADDRESS_2"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>City</td>
                                                <td>
                                                    <span id="1_CUST_CITY_DESC"></span>
                                                </td>
                                                <td class="px-4">
                                                    <label class="switch switch-lg switch-info">
                                                        <input id="chk_CUST_CITY_DESC" name="chk_CUST_CITY_DESC"
                                                            type="checkbox" class="switch-input" value="1">
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bxs-right-arrow-alt bx-sm"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                            </span>
                                                        </span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <span id="0_CUST_CITY_DESC"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>State</td>
                                                <td>
                                                    <span id="1_CUST_STATE_DESC"></span>
                                                </td>
                                                <td class="px-4">
                                                    <label class="switch switch-lg switch-info">
                                                        <input id="chk_CUST_STATE_DESC" name="chk_CUST_STATE_DESC"
                                                            type="checkbox" class="switch-input" value="1">
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bxs-right-arrow-alt bx-sm"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                            </span>
                                                        </span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <span id="0_CUST_STATE_DESC"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Country</td>
                                                <td>
                                                    <span id="1_CUST_COUNTRY_DESC"></span>
                                                </td>
                                                <td class="px-4">
                                                    <label class="switch switch-lg switch-info">
                                                        <input id="chk_CUST_COUNTRY_DESC" name="chk_CUST_COUNTRY_DESC"
                                                            type="checkbox" class="switch-input" value="1">
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bxs-right-arrow-alt bx-sm"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                            </span>
                                                        </span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <span id="0_CUST_COUNTRY_DESC"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Postal Code</td>
                                                <td>
                                                    <span id="1_CUST_POSTAL_CODE"></span>
                                                </td>
                                                <td class="px-4">
                                                    <label class="switch switch-lg switch-info">
                                                        <input id="chk_CUST_POSTAL_CODE" name="chk_CUST_POSTAL_CODE"
                                                            type="checkbox" class="switch-input" value="1">
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bxs-right-arrow-alt bx-sm"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                            </span>
                                                        </span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <span id="0_CUST_POSTAL_CODE"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Mobile No</td>
                                                <td>
                                                    <span id="1_CUST_MOBILE"></span>
                                                </td>
                                                <td class="px-4">
                                                    <label class="switch switch-lg switch-info">
                                                        <input id="chk_CUST_MOBILE" name="chk_CUST_MOBILE"
                                                            type="checkbox" class="switch-input" value="1">
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bxs-right-arrow-alt bx-sm"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                            </span>
                                                        </span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <span id="0_CUST_MOBILE"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Phone No</td>
                                                <td>
                                                    <span id="1_CUST_PHONE"></span>
                                                </td>
                                                <td class="px-4">
                                                    <label class="switch switch-lg switch-info">
                                                        <input id="chk_CUST_PHONE" name="chk_CUST_PHONE" type="checkbox"
                                                            class="switch-input" value="1">
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bxs-right-arrow-alt bx-sm"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                            </span>
                                                        </span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <span id="0_CUST_PHONE"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Memberships</td>
                                                <td>
                                                    <span id="1_CUST_MEMBERSHIPS"></span>
                                                </td>
                                                <td class="px-4">
                                                    <label class="switch switch-lg switch-info">
                                                        <input id="chk_CUST_MEMBERSHIPS" name="chk_CUST_MEMBERSHIPS"
                                                            type="checkbox" class="switch-input" value="1">
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bxs-right-arrow-alt bx-sm"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                            </span>
                                                        </span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <span id="0_CUST_MEMBERSHIPS"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Preferences</td>
                                                <td>
                                                    <span id="1_CUST_PREFERENCES"></span>
                                                </td>
                                                <td class="px-4">
                                                    <label class="switch switch-lg switch-info">
                                                        <input id="chk_CUST_PREFERENCES" name="chk_CUST_PREFERENCES"
                                                            type="checkbox" class="switch-input" value="1">
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bxs-right-arrow-alt bx-sm"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                            </span>
                                                        </span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <span id="0_CUST_PREFERENCES"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>VIP</td>
                                                <td>
                                                    <span id="1_CUST_VIP_DESC"></span>
                                                </td>
                                                <td class="px-4">
                                                    <label class="switch switch-lg switch-info">
                                                        <input id="chk_CUST_VIP_DESC" name="chk_CUST_VIP_DESC"
                                                            type="checkbox" class="switch-input" value="1">
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bxs-right-arrow-alt bx-sm"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                            </span>
                                                        </span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <span id="0_CUST_VIP_DESC"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Last Stay</td>
                                                <td>
                                                    <span id="1_LAST_STAY"></span>
                                                </td>
                                                <td class="px-4">
                                                    <label class="switch switch-lg switch-info">
                                                        <input id="chk_LAST_STAY" name="chk_LAST_STAY" type="checkbox"
                                                            class="switch-input" value="1">
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bxs-right-arrow-alt bx-sm"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                            </span>
                                                        </span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <span id="0_LAST_STAY"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Rate Codes (Negotiated)</td>
                                                <td>
                                                    <span id="1_RATE_CODES"></span>
                                                </td>
                                                <td class="px-4">
                                                    <label class="switch switch-lg switch-info">
                                                        <input id="chk_RATE_CODES" name="chk_RATE_CODES" type="checkbox"
                                                            class="switch-input" value="1">
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bxs-right-arrow-alt bx-sm"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                            </span>
                                                        </span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <span id="0_RATE_CODES"></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <br />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary close_merge_profiles" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn btn-primary merge_profiles_go" data-orig-profile=""
                    data-merge-profile="">Merge Profiles</button>
            </div>
        </div>
    </div>
</div>

<script>
// Click Merge button

$(document).on('click', '.merge-profile', function() {

    var custOptId = $(this).attr('data_sysid');
    var custName = $(this).attr('data_custname');

    $('#profileMergeSearch').modal('show');
    $('#profileMergeSearchLabel').html('Select a Profile to Merge with Profile: ' + custName);

    var filterData = [{
        field: '#S_PROFILE_TYPE',
        value: '1',
        status: '0' // Disable select field
    }, {
        field: '#S_REMOVE_PROFILES',
        value: '[{"prof_id":' + custOptId + ',"prof_type":1}]',
        status: '1' // Disable select field
    }];

    toggleButton('.use_selected_profiles', 'btn-primary', 'btn-dark', true);
    loadProfilesTable(filterData);

});

$(document).on('hide.bs.modal', '#profileMergeSearch', function() {

    clicked_profile_ids = [];
    clearFormFields('.dt_adv_search');
    $('#combined_profiles').DataTable().columns('').search('').draw();

});

$(document).on('click', '.use_selected_profiles', function() {

    var clicked_prId_sections = clicked_profile_ids.toString().split('_');

    var profile_to_merge = parseInt(clicked_prId_sections[3]);
    var original_profile = $('.merge-profile').attr('data_sysid');

    //$('#profileMergeSearch').modal('hide');
    $('#profileMergeCompare').modal('show');

    blockLoader('.compareProfiles_div');

    showCompareProfiles(profile_to_merge, original_profile);

    $('.merge_profiles_go').attr({
        'data-orig-profile': original_profile,
        'data-merge-profile': profile_to_merge
    });

});

// Show Package Code Detail

function showCompareProfiles(pmCustId, ogCustId) {

    var mergeFields = ['CUST_FULL_NAME', 'CUST_TITLE', 'CUST_LANG', 'CUST_ADDRESS_1', 'CUST_ADDRESS_2',
        'CUST_CITY_DESC', 'CUST_STATE_DESC', 'CUST_COUNTRY_DESC', 'CUST_POSTAL_CODE', 'CUST_MOBILE', 'CUST_PHONE',
        'CUST_MEMBERSHIPS', 'CUST_PREFERENCES', 'CUST_VIP_DESC', 'LAST_STAY', 'RATE_CODES'
    ];

    $('#compareProfiles').find('.switch-input').prop('disabled', false);

    var url = '<?php echo base_url('/showCompareProfiles')?>';
    $.ajax({
        url: url,
        type: "get",
        async: false,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            pmCustId: pmCustId,
            ogCustId: ogCustId
        },
        dataType: 'json',
        success: function(respn) {

            $(respn).each(function(inx, data) {
                $.each(data, function(fields, datavals) {
                    var field = $.trim(fields); //fields.trim();
                    var dataval = $.trim(datavals); //datavals.trim();

                    if ($('#' + inx + '_' + field).length) {
                        $('#' + inx + '_' + field).html(dataval);
                    }
                });
            });

            var ogCustData = respn[0];
            var pmCustData = respn[1];

            $(mergeFields).each(function(i, mergeField) {

                if ($.trim(pmCustData[mergeField]) != '' && pmCustData[mergeField] != null && ($
                        .trim(ogCustData[mergeField]) == '' ||
                        ogCustData[mergeField] == null))
                    $('#chk_' + mergeField).prop('checked', true);

                if ($.trim(pmCustData[mergeField]) == '' || pmCustData[mergeField] == null)
                    $('#chk_' + mergeField).prop('disabled', true);
            });
        }
    });
}

function mergeProfiles(pmCustId, ogCustId) {

    var formData = {};
    $('#compareProfiles').find('.switch-input').each(function(i, data) {
        var field = $(data).attr('id');
        var values = $(this).val();

        if ($(this).is(":checked"))
            formData[field] = values;
    });

    formData['pmCustId'] = pmCustId;
    formData['ogCustId'] = ogCustId;

    var url = '<?php echo base_url('/mergeProfileTables')?>';
    $.ajax({
        url: url,
        type: "post",
        async: false,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: formData,
        dataType: 'json',
        success: function(respn) {
            return respn;
        }
    });
}

confirmText = document.querySelector('.merge_profiles_go');

confirmText.onclick = function() {
    Swal.fire({
        title: '',
        html: '<h4>Are you sure you want to merge these profiles?</h4>',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, merge them!',
        customClass: {
            confirmButton: 'btn btn-primary me-3',
            cancelButton: 'btn btn-label-secondary'
        },
        buttonsStyling: false
    }).then(function(result) {
        if (result.value) {

            var timerInterval, mergeResult;
            Swal.fire({
                title: '',
                html: '<h4 class="mt-5">Merging Profiles...</h4>',
                timer: 2500,
                showCloseButton: false,
                showConfirmButton: false,
                customClass: {
                    confirmButton: 'btn btn-primary mb-3'
                },
                buttonsStyling: false,
                willOpen: function() {
                    Swal.showLoading();

                    var profile_to_merge = $('.merge_profiles_go').attr('data-merge-profile');
                    var original_profile = $('.merge_profiles_go').attr('data-orig-profile');

                    mergeProfiles(profile_to_merge, original_profile);
                },
                willClose: function() {
                    //clearInterval(timerInterval);
                }
            }).then(function() {

                Swal.fire({
                    icon: 'success',
                    title: 'Merge Completed!',
                    text: 'The profiles have been merged.',
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                });

                $('#profileMergeCompare').modal('hide');
                $('#profileMergeSearch').modal('hide');
                $('#custOptionsWindow').modal('hide');

                $('#dataTable_view').dataTable().fnDraw(); // Reload customer table

            });
        }
    });
};

$('#compareProfiles').find('.switch-input').change(function() {
    var numCheckedFields = $('#compareProfiles').find('.switch-input:checked').length;
    if (numCheckedFields == 0) {
        toggleButton('.merge_profiles_go', 'btn-primary', 'btn-dark', true);
    } else {
        toggleButton('.merge_profiles_go', 'btn-dark', 'btn-primary', false);
    }
});
</script>