<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>
<?= $this->include('Layout/SuccessReport') ?>
<?= $this->include('Layout/ErrorReport') ?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Reservations /</span> <?=$title?>
        </h4>

        <!-- DataTable with Buttons -->
        <div class="card">

            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid p-3">

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
                                <li><a class="dropdown-item" href="<?php echo base_url('/company?add=1')?>"
                                        target="_blank">Company</a></li>
                                <li><a class="dropdown-item" href="<?php echo base_url('/agent?add=1')?>"
                                        target="_blank">Travel Agent</a></li>
                                <li><a class="dropdown-item" href="<?php echo base_url('/group?add=1')?>"
                                        target="_blank">Group</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <form class="dt_adv_search mb-4" method="POST">
                    <div class="border rounded p-3">
                        <div class="row g-3">
                            <div class="col-4 col-sm-6 col-lg-4">

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4"
                                        style="text-align: right;"><b>Name:</b></label>
                                    <div class="col-md-8">
                                        <input type="text" id="PROFILE_NAME" name="PROFILE_NAME"
                                            class="form-control dt-input dt-full-name" data-column="0" placeholder="" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4" style="text-align: right;"><b>First
                                            Name:</b></label>
                                    <div class="col-md-8">
                                        <input type="text" name="PROFILE_FIRST_NAME"
                                            class="form-control dt-input dt-first-name" data-column="0"
                                            placeholder="" />

                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4" style="text-align: right;"><b>View
                                            By:</b></label>
                                    <div class="col-md-8">
                                        <select id="searchProfileType" name="PROFILE_TYPE"
                                            class="form-select dt-select dt-view-by" data-column="1">
                                            <option value="">View All</option>
                                            <option value="1">Individual</option>
                                            <option value="2">Company</option>
                                            <option value="3">Travel Agent</option>
                                            <option value="4">Group</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-4 col-sm-6 col-lg-4">

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4" style="text-align: right;"><b>City /
                                            Postal Code:</b></label>
                                    <div class="col-md-5" style="padding-right:  0;">
                                        <input type="text" name="PROFILE_CITY" class="form-control dt-input dt-city"
                                            data-column="4" placeholder="" />
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="PROFILE_POSTAL_CODE"
                                            class="form-control dt-input dt-postal-code" data-column="5"
                                            placeholder="" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4" style="text-align: right;"><b>Mem. Type /
                                            No:</b></label>
                                    <div class="col-md-4" style="padding-right:  0;">
                                        <select id="defaultSelect" class="form-select dt-input dt-mem-type">
                                            <option></option>
                                            <option value="AA">AA | American Airlines</option>
                                            <option value="AC">AC | Air Canada</option>
                                            <option value="US">US | US Air</option>
                                            <option value="VX">Virgin American Airlines</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control dt-input dt-mem-no" placeholder="" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4"
                                        style="text-align: right;"><b>Communication:</b></label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control dt-input dt-communication"
                                            placeholder="" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4" style="text-align: right;"><b>Passport
                                            No:</b></label>
                                    <div class="col-md-8">
                                        <input type="text" name="PROFILE_PASSPORT"
                                            class="form-control dt-input dt-passport-no" data-column="19"
                                            placeholder="" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-4 col-sm-6 col-lg-4">

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4" style="text-align: right;"><b>Client
                                            ID:</b></label>
                                    <div class="col-md-8">
                                        <input type="text" name="PROFILE_NUMBER"
                                            class="form-control dt-input dt-client-id" data-column="16"
                                            placeholder="" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4" style="text-align: right;"><b>IATA
                                            No:</b></label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control dt-input dt-iata-no" placeholder="" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4" style="text-align: right;"><b>Corp
                                            No:</b></label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control dt-input dt-corp-no" placeholder="" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4" style="text-align: right;"><b>A/R
                                            No:</b></label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control dt-input dt-ar-no" placeholder="" />
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>

                <div class="table-responsive text-nowrap">
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
            </div>
        </div>

        <!--/ Multilingual -->
    </div>
    <!-- / Content -->

    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->
<script>
$(document).ready(function() {

    // Combined Profiles (Customer, Company, Agent, Group) List for Negotiated Rates

    $('#combined_profiles').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/combinedProfilesView')?>'
        },
        'columns': [{
                data: null,
                className: "text-center",
                render: function(data, type, row, meta) {
                    var editUrl = '<?php echo base_url('/')?>/';
                    switch(data['PROFILE_TYPE'])
                    {
                      case '1' : editUrl += 'customer'; break;
                      case '2' : editUrl += 'company'; break;
                      case '3' : editUrl += 'agent'; break;
                      case '4' : editUrl += 'group'; break;
                    }

                    return (
                        '<div class="d-inline-block dropend">' +
                        '<a href="javascript:;" class="btn btn-sm btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                        '<ul class="dropdown-menu dropdown-menu-end">' +
                        '<li><a href="'+editUrl+'?editId=' + data['PROFILE_ID'] + '" target="_blank" class="dropdown-item">View / Edit Profile</a></li>' +
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
                data: 'PROFILE_CITY'
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
                "defaultContent": ""
            },
            {
                data: 'PROFILE_TITLE'
            },
            {
                data: 'PROFILE_COUNTRY'
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
            var check_str = 'profile_chk_' + data['PROFILE_TYPE'] + '_' + data['PROFILE_ID'];

            $(row).attr('data-profile-type', data['PROFILE_TYPE']);
            $(row).attr('data-profile-id', data['PROFILE_ID']);

        },
        columnDefs: [{
            width: "10%"
        },{
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
    });

});

(function() {

    // Negotiated Rate Advanced Search Functions Starts
    // --------------------------------------------------------------------

    const dt_adv_filter_table = $('#combined_profiles');

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

    // Advanced Search Functions Ends

})();
</script>

<?=$this->endSection()?>