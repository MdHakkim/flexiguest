<style>
#combined_profiles .dataTables_empty {
    text-align: left !important;
    padding-left: 20% !important;
}
</style>

<div class="row">
    <div class="col-md-3 mt-1 mb-3">
        <div class="btn-group">
            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class="fa-solid fa-plus"></i>&nbsp; Add New Profile
            </button>
            <ul class="dropdown-menu" style="">
                <li><a class="dropdown-item" href="<?php echo base_url('/customer?add=1')?>"
                        target="_blank">Individual</a></li>
                <li><a class="dropdown-item" href="<?php echo base_url('/company?add=1')?>" target="_blank">Company</a>
                </li>
                <li><a class="dropdown-item" href="<?php echo base_url('/agent?add=1')?>" target="_blank">Travel
                        Agent</a></li>
                <li><a class="dropdown-item" href="<?php echo base_url('/group?add=1')?>" target="_blank">Group</a></li>
            </ul>
        </div>
    </div>
</div>

<form class="dt_adv_search mb-4" method="POST">
    <div class="border rounded p-3">
        <div class="row g-3">
            <div class="col-12 col-sm-6 col-lg-4">

                <div class="row mb-3">
                    <label
                        class="col-form-label col-md-4 d-flex justify-content-lg-end justify-content-sm-start"><b>Name:</b></label>
                    <div class="col-md-8">
                        <input type="text" id="S_PROFILE_NAME" name="S_PROFILE_NAME"
                            class="form-control dt-input dt-full-name" data-column="0" placeholder="" />
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-form-label col-md-4 d-flex justify-content-lg-end justify-content-sm-start"><b>First
                            Name:</b></label>
                    <div class="col-md-8">
                        <input type="text" id="S_PROFILE_FIRST_NAME" name="S_PROFILE_FIRST_NAME"
                            class="form-control dt-input dt-first-name" data-column="0" placeholder="" />

                    </div>
                </div>

                <div class="row mb-3">
                    <label
                        class="col-form-label col-md-4 d-flex justify-content-lg-end justify-content-sm-start"><b>Communication:</b></label>
                    <div class="col-md-8">
                        <input type="text" id="S_PROFILE_COMMUNICATION" name="S_PROFILE_COMMUNICATION"
                            class="form-control dt-input dt-communication" placeholder="" />
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-form-label col-md-4 d-flex justify-content-lg-end justify-content-sm-start"><b>View
                            By:</b></label>
                    <div class="col-md-8">
                        <select id="S_PROFILE_TYPE" name="S_PROFILE_TYPE" class="form-select dt-select dt-view-by"
                            data-column="1">
                            <option value="">View All</option>
                            <?php
                                                if($profileTypeOptions != NULL) {
                                                    foreach($profileTypeOptions as $profileTypeOption)
                                                    {
                                            ?> <option value="<?=$profileTypeOption['value']; ?>">
                                <?=$profileTypeOption['desc']; ?>
                            </option>
                            <?php   }
                                                }                                                            
                                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-4">

                <div class="row mb-3">
                    <label class="col-form-label col-md-4 d-flex justify-content-lg-end text-end justify-content-sm-start"><b>City
                            /
                            Postal Code:</b></label>
                    <div class="col-md-5" style="padding-right:  0;">
                        <input type="text" id="S_CITY_NAME" name="S_CITY_NAME"
                            class="form-control dt-input dt-city" data-column="4" placeholder="" />
                    </div>
                    <div class="col-md-3">
                        <input type="text" id="S_PROFILE_POSTAL_CODE" name="S_PROFILE_POSTAL_CODE"
                            class="form-control dt-input dt-postal-code" data-column="5" placeholder="" />
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-form-label col-md-4 d-flex justify-content-lg-end text-end justify-content-sm-start"><b>Membership
                            Type:</b></label>
                    <div class="col-md-8">
                        <select id="S_MEMBERSHIP_TYPE" name="S_MEMBERSHIP_TYPE"
                            class="form-select select2 dt-input dt-mem-type" data-allow-clear="true">
                            <option value=""></option>
                            <?php
                                                if($membershipTypes != NULL) {
                                                    foreach($membershipTypes as $membershipType)
                                                    {
                                            ?> <option value="<?=$membershipType['id']; ?>">
                                <?=$membershipType['code'].' | '.$membershipType['text']; ?>
                            </option>
                            <?php   }
                                                }                                                            
                                            ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-form-label col-md-4 d-flex justify-content-lg-end text-end justify-content-sm-start"><b>Membership
                            No:</b></label>
                    <div class="col-md-8">
                        <input type="text" id="S_MEMBERSHIP_NUMBER" name="S_MEMBERSHIP_NUMBER"
                            class="form-control dt-input dt-mem-no" placeholder="" />
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-form-label col-md-4 d-flex justify-content-lg-end justify-content-sm-start"><b>Passport
                            No:</b></label>
                    <div class="col-md-8">
                        <input type="text" id="S_PROFILE_PASSPORT" name="S_PROFILE_PASSPORT"
                            class="form-control dt-input dt-passport-no" data-column="19" placeholder="" />
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-4">

                <div class="row mb-3">
                    <label class="col-form-label col-md-4 d-flex justify-content-lg-end justify-content-sm-start"><b>Client
                            ID:</b></label>
                    <div class="col-md-8">
                        <input type="text" id="S_PROFILE_NUMBER" name="S_PROFILE_NUMBER"
                            class="form-control dt-input dt-client-id" data-column="16" placeholder="" />
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-form-label col-md-4 d-flex justify-content-lg-end justify-content-sm-start"><b>IATA
                            No:</b></label>
                    <div class="col-md-8">
                        <input type="text" id="S_AGN_IATA" name="S_AGN_IATA" class="form-control dt-input dt-iata-no"
                            placeholder="" />
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-form-label col-md-4 d-flex justify-content-lg-end justify-content-sm-start"><b>Corp
                            No:</b></label>
                    <div class="col-md-8">
                        <input type="text" id="S_COM_CORP_ID" name="S_COM_CORP_ID"
                            class="form-control dt-input dt-corp-no" placeholder="" />
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-form-label col-md-4 d-flex justify-content-lg-end justify-content-sm-start"><b>A/R
                            No:</b></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control dt-input dt-ar-no" placeholder="" disabled />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12 text-end">
                        <input type="hidden" name="S_REMOVE_PROFILES" id="S_REMOVE_PROFILES" value="" />
                        <button type="button" class="btn btn-primary submitAdvSearch">
                            <i class='bx bx-search'></i>&nbsp;
                            Search
                        </button>&nbsp;
                        <button type="button" class="btn btn-secondary clearAdvSearch">Clear</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>

<div class="combined_profiles_div table-responsive text-nowrap">
    <table id="combined_profiles" class="table table-hover table-bordered table-striped">
        <thead>
            <tr>
                <th>Action</th>
                <th>Name</th>
                <th>Type ID</th>
                <th>Type</th>
                <th>Address</th>
                <th>City</th>
                <th>Postal Code</th>
                <th>Company</th>
                <th>A/R No.</th>
                <th>VIP</th>
                <th>Rate Code</th>
                <th>Next Stay</th>
                <th>Last Stay</th>
                <th>Last Room</th>
                <th>Last Group</th>
                <th>Title</th>
                <th>Country</th>
                <th>Client ID/IATA/Corp No</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Passport</th>
            </tr>
        </thead>
    </table>
</div>

<script>
var clicked_profile_ids = [];

$(document).ready(function() {

    <?php //if(isset($rateCodeID)) { ?> // If Rate Code details page

    if ($('.use_selected_profiles').length) {

        $(document).on('click', '#combined_profiles > tbody > tr', function() {

            var profile_chk_str = $(this).attr('data-profile-type') && $(this).attr('data-profile-id') ?
                'profile_chk_' + $(this).attr('data-profile-type') + '_' + $(this)
                .attr('data-profile-id') : '';

            //If value in array
            if (jQuery.inArray(profile_chk_str, clicked_profile_ids) !==
                -1) {
                if ($(this).hasClass("table-warning")) {
                    // Remove value from array
                    clicked_profile_ids = $.grep(clicked_profile_ids, function(value) {
                        return value != profile_chk_str;
                    });
                }
            } else {
                if (!$(this).hasClass("table-warning") && profile_chk_str != '') {
                    clicked_profile_ids.push(profile_chk_str);
                }
            }

            if (clicked_profile_ids.length == 0) {
                toggleButton('.use_selected_profiles', 'btn-primary', 'btn-dark', true);
            } else {
                toggleButton('.use_selected_profiles', 'btn-dark', 'btn-primary', false);
            }

            // If single select
            if ($('#profileMergeSearch').length) {
                clicked_profile_ids = $.grep(clicked_profile_ids, function(value) {
                    return value == profile_chk_str;
                });
                $('#combined_profiles > tbody > tr').not(this).removeClass('table-warning');
            }

            //alert(clicked_profile_ids);

            $(this).toggleClass('table-warning', $(this).hasClass('selected'));
        });
    }

    <?php //} ?>

});

// Combined Profiles (Customer, Company, Agent, Group) List for Negotiated Rates

function loadProfilesTable(filterData) {

    var selectOpts = !$('.use_selected_profiles').length ? false : ($('#newNegotiatedRate').length ? {
        style: 'multi',
        info: false
    } : {
        style: 'single',
        info: false
    });

    $('#combined_profiles').DataTable({
        'processing': true,
        'serverSide': true,
        async: false,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/combinedProfilesView')?>',
            'type': 'POST',
            'data': function(d) {

                if (filterData.length) {
                    $.each(filterData, function(i, filterDetail) {
                        if ($(filterDetail['field']).length) {
                            $(filterDetail['field']).val(filterDetail['value']);
                            $(filterDetail['field']).prop("disabled", filterDetail['status'] == 1 ?
                                false : true);
                            d[$(filterDetail['field']).attr('name')] = filterDetail['value'];
                        }
                    });
                }

                var formSerialization = $('.dt_adv_search').serializeArray();
                $(formSerialization).each(function(i, field) {
                    d[field.name] = field.value;
                });
            },
        },
        'columns': [{
                data: null,
                className: "text-center",
                render: function(data, type, row, meta) {
                    var editUrl = '<?php echo base_url('/')?>/';
                    switch (data['PROFILE_TYPE']) {
                        case '1':
                            editUrl += 'customer';
                            break;
                        case '2':
                            editUrl += 'company';
                            break;
                        case '3':
                            editUrl += 'agent';
                            break;
                        case '4':
                            editUrl += 'group';
                            break;
                    }

                    return (
                        '<div class="d-inline-block dropend">' +
                        '<a href="javascript:;" class="btn btn-sm btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                        '<ul class="dropdown-menu dropdown-menu-end">' +
                        '<li><a href="' + editUrl + '?editId=' + data[
                            'PROFILE_ID'] +
                        '" target="_blank" class="dropdown-item">View / Edit Profile</a></li>' +
                        '</ul>' +
                        '</div>'
                    );
                }
            },
            {
                data: 'PROFILE_NAME'
            },
            {
                data: 'PROFILE_TYPE',
                "visible": false,
            },
            {
                data: 'PROFILE_TYPE_NAME'
            },
            {
                data: 'PROFILE_ADDRESS'
            },
            {
                data: 'CITY_NAME'
            },
            {
                data: 'PROFILE_POSTAL_CODE'
            },
            {
                data: 'PROFILE_COMP_CODE'
            },
            {
                "defaultContent": ""
            },
            {
                data: 'PROFILE_VIP'
            },
            {
                data: 'RATE_CODES'
            },
            {
                "defaultContent": ""
            },
            {
                "defaultContent": ""
            },
            {
                "defaultContent": ""
            },
            {
                "defaultContent": ""
            },
            {
                data: 'PROFILE_TITLE'
            },
            {
                data: 'COUNTRY_NAME'
            },
            {
                data: 'PROFILE_NUMBER'
            },
            {
                data: 'PROFILE_EMAIL'
            },
            {
                data: 'PROFILE_MOBILE'
            },
            {
                data: 'PROFILE_PASSPORT'
            },
        ],
        'createdRow': function(row, data, dataIndex) {
            var check_str = 'profile_chk_' + data['PROFILE_TYPE'] + '_' + data[
                'PROFILE_ID'];

            $(row).attr('data-profile-type', data['PROFILE_TYPE']);
            $(row).attr('data-profile-id', data['PROFILE_ID']);

            <?php //if(isset($rateCodeID)) { ?>

            if ($('.use_selected_profiles').length) {

                if (jQuery.inArray(check_str, clicked_profile_ids) !== -1 && !$(row).hasClass(
                        'table-warning')) {
                    $(row).addClass('table-warning');
                } else if (jQuery.inArray(check_str, clicked_profile_ids) == -1 && $(row).hasClass(
                        'table-warning')) {
                    $(row).removeClass('table-warning');
                }
            }

            <?php //} ?>
        },
        columnDefs: [{
            width: "10%"
        }, {
            width: "25%"
        }, {
            width: "20%"
        }, {
            width: "15%"
        }, {
            width: "10%"
        }, {
            width: "10%"
        }, {
            width: "10%"
        }, {
            width: "25%"
        }, {
            width: "20%"
        }, {
            width: "15%"
        }, {
            width: "10%"
        }, {
            width: "10%"
        }, {
            width: "10%"
        }, {
            width: "25%"
        }, {
            width: "20%"
        }, {
            width: "15%"
        }, {
            width: "10%"
        }, {
            width: "10%"
        }, {
            width: "10%"
        }, {
            width: "10%"
        }],
        "order": [
            [1, "asc"]
        ],
        destroy: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end">>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        language: {
            emptyTable: 'There are no profiles to display'
        }
        <?php //if(isset($rateCodeID)) { ?>,
        select: selectOpts
        <?php //} ?>
    });
}

(function() {

    // Negotiated Rate Advanced Search Functions Starts
    // --------------------------------------------------------------------

    const dt_adv_filter_table = $('#combined_profiles');

    $(document).on('click', '.submitAdvSearch', function() {

        blockLoader('.combined_profiles_div');
        dt_adv_filter_table.dataTable().fnDraw();
    });

    $(document).on('click', '.clearAdvSearch', function() {

        clearFormFields('.dt_adv_search');
        blockLoader('.combined_profiles_div');
        dt_adv_filter_table.dataTable().fnDraw();
    });

    /*
    // Filter column wise function
    function filterColumn(i, val) {
        dt_adv_filter_table.DataTable().column(i).search(val).draw();
    }

    // on key up from input field
    $(document).on('keyup', 'input.dt-input', function() {
        if ($(this).val().length == 0 || $(this).val().length >= 2)
            filterColumn($(this).attr('data-column'), $(this).val());
    });

    $(document).on('change', 'select.dt-select', function() {
        filterColumn($(this).attr('data-column'), $(this).val());
    });
    */

    // Advanced Search Functions Ends

})();

// Display function toggleButton
<?php echo isset($toggleButton_javascript) ? $toggleButton_javascript : ''; ?>

// Display function clearFormFields
<?php echo isset($clearFormFields_javascript) ? $clearFormFields_javascript : ''; ?>

// Display function blockLoader
<?php echo isset($blockLoader_javascript) ? $blockLoader_javascript : ''; ?>
</script>