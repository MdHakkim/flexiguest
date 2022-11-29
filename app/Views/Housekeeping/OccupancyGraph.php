<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/SuccessReport') ?>
<?= $this->include('Layout/ErrorReport') ?>

<style>
.roomTypeSelDiv .select2-search--inline {
    display: contents;
    /*this will make the container disappear, making the child the one who sets the width of the element*/
}

.roomTypeSelDiv .select2-search__field:placeholder-shown {
    width: 100% !important;
    /*makes the placeholder to be 100% of the width while there are no options selected*/
}
</style>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Housekeeping /</span> Occupancy Graph
        </h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid p-3">

                <form class="dt_adv_search mb-2" method="POST">

                    <div class="row g-3">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="row border rounded p-3 pb-1 m-1">

                                <div class="col-12 col-sm-6 col-lg-4">

                                    <div class="row mb-3">

                                        <label
                                            class="col-form-label col-md-4 d-flex justify-content-lg-end justify-content-sm-start"><b>Start
                                                Date:</b></label>
                                        <div class="col-md-8">
                                            <div class="sk-wave sk-primary">
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                            </div>
                                            <div class="d-none">
                                                <input type="text" id="S_START_DATE" name="S_START_DATE"
                                                    class="form-control dt-date" placeholder="" />
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row mb-3">

                                        <label
                                            class="col-form-label col-md-4 d-flex justify-content-lg-end justify-content-sm-start"><b>Room
                                                Class:</b></label>
                                        <div class="col-md-8">
                                            <div class="sk-wave sk-primary">
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                            </div>
                                            <div class="d-none">
                                                <select id="S_RM_CLASS" name="S_RM_CLASS"
                                                    class="select2 form-select dt-input"
                                                    data-placeholder="All Room Classes" data-allow-clear="true">
                                                    <option value=""></option>
                                                    <?php foreach ($room_class_list as $row) {
                                                        echo '<option value="' . $row['RM_CL_ID'] . '" data-rmclass-id="' . $row['RM_CL_CODE'] . '">' . $row['RM_CL_CODE'] . ' | ' . $row['RM_CL_DESC'] . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-12 col-sm-6 col-lg-4">
                                    <div class="row mb-3">

                                        <label
                                            class="col-form-label col-md-4 d-flex justify-content-lg-end justify-content-sm-start"><b>Bar
                                                Display:</b></label>
                                        <div class="col-md-8">
                                            <div class="sk-wave sk-primary">
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                            </div>
                                            <div class="d-none">
                                                <select id="S_BAR_DISPLAY" name="S_BAR_DISPLAY"
                                                    class="selectpicker w-100" data-style="btn-default">
                                                    <option value="TOT">Total</option>
                                                    <option value="RSRV">Reservations</option>
                                                    <option value="BLK">Block</option>
                                                    <option value="NPBLK">Non Picked Up Blocks</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">

                                        <label
                                            class="col-form-label col-md-4 d-flex justify-content-lg-end justify-content-sm-start"><b>Number
                                                of:</b></label>
                                        <div class="col-md-4">
                                            <div class="sk-wave sk-primary">
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                            </div>
                                            <div class="d-none">
                                                <select id="S_TIME_PERIOD" name="S_TIME_PERIOD"
                                                    class="selectpicker w-100" data-style="btn-default">
                                                    <option value="1">Days</option>
                                                    <option value="7">Weeks</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="sk-wave sk-primary">
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                            </div>
                                            <div class="d-none">
                                                <select id="S_NO_OF" name="S_NO_OF" class="selectpicker w-100"
                                                    data-style="btn-default">
                                                    <option value="8">8</option>
                                                    <option value="16">16</option>
                                                    <option value="24">24</option>
                                                    <option value="40">40</option>
                                                    <option value="60">60</option>
                                                    <option value="90">90</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <div class="col-12 col-sm-4 col-lg-4">
                                    <div class="row mb-3">
                                        <div class="col-md">
                                            <div class="sk-wave sk-primary">
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                            </div>
                                            <div class="d-none">
                                                <div class="switches-stacked">
                                                    <label class="switch">
                                                        <input type="checkbox" class="switch-input S_GRID_LINES"
                                                            id="S_GRID_LINES" name="S_GRID_LINES" value="1" checked>
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on"></span>
                                                            <span class="switch-off"></span>
                                                        </span>
                                                        <span class="switch-label fw-bold form-label">Grid Lines</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <div class="sk-wave sk-primary">
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                            </div>
                                            <div class="d-none">
                                                <div class="switches-stacked">
                                                    <label class="switch">
                                                        <input type="checkbox" class="switch-input S_PERCENT"
                                                            id="S_PERCENT" name="S_PERCENT" value="1">
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on"></span>
                                                            <span class="switch-off"></span>
                                                        </span>
                                                        <span class="switch-label fw-bold form-label">Percentage</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-4 mb-3">
                                        <label
                                            class="col-form-label col-md-6 d-flex justify-content-lg-end justify-content-sm-start"><b>Max
                                                Level of Rooms:</b></label>
                                        <div class="col-md-5">
                                            <div class="sk-wave sk-primary">
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                            </div>
                                            <div class="d-none">
                                                <select id="S_GRAPH_MAX" name="S_GRAPH_MAX" class="selectpicker w-100"
                                                    data-style="btn-default">
                                                    <option value="100">100</option>
                                                    <option value="500" selected>500</option>
                                                    <option value="1000">1,000</option>
                                                    <option value="" data-graph-max="1">Total</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-8">

                                    <div class="row mb-3">
                                        <label
                                            class="col-form-label col-md-2 d-flex justify-content-lg-end justify-content-sm-start"><b>Room
                                                Types:</b></label>
                                        <div class="col-md-10 roomTypeSelDiv">
                                            <div class="sk-wave sk-primary">
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                                <div class="sk-wave-rect"></div>
                                            </div>
                                            <div class="d-none">
                                                <select name="S_RM_TYPES[]" id="S_RM_TYPES" data-width="100%" multiple
                                                    class="select2 form-select" data-placeholder="All Room Types"
                                                    data-allow-clear="true">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-4">

                                    <div class="mb-3 d-flex justify-content-end">

                                        <button type="button" id="submitAdvSearch"
                                            class="btn btn-primary submitAdvSearch me-2">
                                            <i class='bx bx-search'></i>&nbsp;
                                            Search
                                        </button>
                                        <button type="button" id="clearAdvSearch"
                                            class="btn btn-secondary clearAdvSearch">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>

                <div class="card-body chartDiv">
                    <div id="barChart"></div>
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
var mainUrl = '<?php echo base_url(); ?>';

var totalRooms = 0;

$(document).ready(function() {

    $.ajax({
        url: '<?php echo base_url('/roomTypeList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        // dataType:'json',
        success: function(respn) {
            $('#S_RM_TYPES').html(respn);
        }
    });

    $('#S_START_DATE').datepicker({
        format: 'dd-M-yyyy',
        autoclose: true,
        startDate: '-0m',
        onSelect: function() {
            $(this).change();
        }
    });

    $("#S_START_DATE").datepicker("setDate", new Date(<?php date('d-M-Y'); ?>));

    // Get total number of Rooms for Max value of Graph
    $.ajax({
        url: '<?php echo base_url('/HkRoomStatistics') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        async: false
    }).done(function(respn) {
        var rmCountData = respn[0];
        var rmStatusList = respn[1];

        totalRooms = parseInt(rmCountData[0]['HKRooms'] - rmCountData[0]['TotRooms5']);
        $('#S_GRAPH_MAX option[data-graph-max="1"]').attr('value', totalRooms);
    });

});

//Select room class to load room types
$(document).on('change.select2', '#S_RM_CLASS', function() {

    var selectedRoomClass = $(this).val();

    $.ajax({
        url: '<?php echo base_url('/roomTypeList') ?>',
        async: false,
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            room_class_id: selectedRoomClass
        },
        dataType: 'html'
    }).done(function(respn) {
        $('#S_RM_TYPES').html(respn);
        $('#S_RM_TYPES').val(null).trigger('change');
    });

});

$(window).on('load', function() {
    // executes when complete page is fully loaded, including all frames, objects and images
    $('.sk-wave').hide().next().removeClass('d-none');
});


// Display function toggleButton
<?php echo isset($toggleButton_javascript) ? $toggleButton_javascript : ''; ?>

// Display function clearFormFields
<?php echo isset($clearFormFields_javascript) ? $clearFormFields_javascript : ''; ?>

// Display function blockLoader
<?php echo isset($blockLoader_javascript) ? $blockLoader_javascript : ''; ?>
</script>

<?= $this->endSection() ?>