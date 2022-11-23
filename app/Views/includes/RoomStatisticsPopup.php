<!-- Notification Modal window -->
<div class="modal fade" id="HKRoomStatisticsModal" data-backdrop="static" data-keyboard="false"
    aria-labelledby="popModalWindowlabel">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="popModalWindowlabel1">Housekeeping Room Statistics</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="" onSubmit="return false">
                    <div id="HKRoomStatistics" class="content">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="border rounded p-4 roomStatsDiv">

                                    <div class="row g-3">

                                        <div class="col-lg-6 p-4 mb-3">
                                            <h5 class="text-light fw-semibold">Totals</h5>
                                            <div class="demo-inline-spacing mt-3">
                                                <div class="list-group totRoomStatsDiv">
                                                    <!-- Totals Here -->
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 p-4">
                                            <h5 class="text-light fw-semibold">Details</h5>
                                            <div class="demo-inline-spacing mt-3">
                                                <div class="list-group detRoomStatsDiv">
                                                    <!-- Details Here -->
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="d-flex col-12 justify-content-end">
                                <button type="button" class="btn btn-primary me-2 refreshRoomStats"
                                    onclick="loadRoomStatistics()"><i class="fas fa-refresh me-1"></i>
                                    Refresh</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                            </div>

                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- /Modal  window -->

<script>
// Click Housekeeping Room Statistics menu link
$(document).on('click', '.hkRoomStats', function() {

    $('.hkRoomStats').parent().addClass('active');
    $('#HKRoomStatisticsModal').modal('show');
    loadRoomStatistics();
});

// Close Housekeeping Room Statistics popup
$(document).on('hide.bs.modal', '#HKRoomStatisticsModal', function() {
    $('.hkRoomStats').parent().removeClass('active');
});

function blockLoader(elem, duration = 500, alert = '') {
    $(elem).block({
        message: '<div class=\"spinner-border text-white\" role=\"status\"></div>',
        timeout: duration,
        css: {
            backgroundColor: 'transparent',
            border: '0'
        },
        overlayCSS: {
            opacity: 0.5
        },
        onUnblock: function() {

        }
    });
}

function loadRoomStatistics() {

    blockLoader('.roomStatsDiv');

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

        var totRoomsHtml = detRRoomsHtml = detNRoomsHtml = '';

        totRoomsHtml = ` <a href="javascript:void(0);"
                            class="list-group-item list-group-item-action d-flex justify-content-between">
                                <div
                                    class="li-wrapper d-flex justify-content-start align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        <span
                                            class="avatar-initial bg-label-primary rounded-circle">TR</span>
                                    </div>
                                    <div class="list-content">
                                        <h6 class="mb-1">Total Housekeeping Rooms</h6>
                                    </div>
                                </div>
                                <div class="fw-semibold">` + rmCountData[0]['HKRooms'] + `</div>
                        </a>`;

        $.each(rmStatusList, function(i, rmStatus) {

            totRoomsHtml += `<a href="javascript:void(0);"
                                class="list-group-item list-group-item-action d-flex justify-content-between">
                                    <div
                                        class="li-wrapper d-flex justify-content-start align-items-center">
                                        <div class="avatar avatar-sm me-3">
                                            <span
                                                class="avatar-initial bg-label-` + rmStatus['RM_STATUS_COLOR_CLASS'] +
                ` rounded-circle">T` + rmStatus['RM_STATUS_CODE'].charAt(0) + `</span>
                                        </div>
                                        <div class="list-content">
                                            <h6 class="mb-1">Total ` + rmStatus['RM_STATUS_CODE'] + `</h6>
                                        </div>
                                    </div>
                                    <div class="fw-semibold">` + rmCountData[0]['TotRooms' + rmStatus[
                    'RM_STATUS_ID']] + `</div>
                            </a>`;

            if (rmStatus['RM_STATUS_ID'] == '5') return true;

            detNRoomsHtml += `<a href="javascript:void(0);"
                                class="list-group-item list-group-item-action d-flex justify-content-between">
                                    <div
                                        class="li-wrapper d-flex justify-content-start align-items-center">
                                        <div class="avatar avatar-sm me-3">
                                            <span
                                                class="avatar-initial bg-label-` + rmStatus['RM_STATUS_COLOR_CLASS'] +
                ` rounded-circle">N` + rmStatus['RM_STATUS_CODE'].charAt(0) + `</span>
                                        </div>
                                        <div class="list-content">
                                            <h6 class="mb-1">Not Reserved - ` + rmStatus['RM_STATUS_CODE'] + `</h6>
                                        </div>
                                    </div>
                                    <div class="fw-semibold">` + rmCountData[0]['NRTotRooms' + rmStatus[
                    'RM_STATUS_ID']] + `</div>
                            </a>`;

            detRRoomsHtml += `<a href="javascript:void(0);"
                                class="list-group-item list-group-item-action d-flex justify-content-between">
                                    <div
                                        class="li-wrapper d-flex justify-content-start align-items-center">
                                        <div class="avatar avatar-sm me-3">
                                            <span
                                                class="avatar-initial bg-label-` + rmStatus['RM_STATUS_COLOR_CLASS'] +
                ` rounded-circle">R` + rmStatus['RM_STATUS_CODE'].charAt(0) + `</span>
                                        </div>
                                        <div class="list-content">
                                            <h6 class="mb-1">Reserved - ` + rmStatus['RM_STATUS_CODE'] + `</h6>
                                        </div>
                                    </div>
                                    <div class="fw-semibold">` + rmCountData[0]['RTotRooms' + rmStatus[
                    'RM_STATUS_ID']] + `</div>
                            </a>`;
        });

        $('.totRoomStatsDiv').html(totRoomsHtml);
        $('.detRoomStatsDiv').html(detNRoomsHtml + detRRoomsHtml);

    });
}
</script>