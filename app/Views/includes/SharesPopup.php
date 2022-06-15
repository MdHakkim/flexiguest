<style>
    .active-tr {
        background-color: #d1e7ff !important;
        --bs-table-striped-bg: none;
    }
</style>
<!-- <div class="modal fade show" id="optionWindow" data-bs-backdrop="static"  -->
<!-- data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-modal="true" role="dialog" style="display: block;"> -->
        
<div class="modal fade" id="shares-popup" tabindex="-1" aria-labelledby="sharesLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sharesLabel">Shares</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row g-3">

                        <!-- Room Details -->
                        <div class="col-md-12">
                            <h5 class="mb-2">Room Details</h5>
                            <div class="table-responsive text-nowrap">
                                <table class="table card-table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Room</th>
                                            <th>Room Type</th>
                                            <th>Occupied From</th>
                                            <th>Occupied To</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0 room-details"></tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Nightly Rate Details -->
                        <div class="col-md-12">
                            <h5 class="mb-2">Nightly Rate Details</h5>
                            <div class="table-responsive text-nowrap">
                                <table class="table card-table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Nightly Rate</th>
                                            <th>Share Rates</th>
                                            <th>Effective From</th>
                                            <th>Effective To</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0 nightly-rate-details"></tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Reservation Arrival Details -->
                        <div class="col-md-12">
                            <h5 class="mb-2">Reservation Arrival Details</h5>
                            <div class="table-responsive text-nowrap">
                                <table class="table card-table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Name</th>
                                            <th>Arrival</th>
                                            <th>Departure</th>
                                            <th>Status</th>
                                            <th>Adults</th>
                                            <th>Children</th>
                                            <th>Rate Code</th>
                                            <th>Rate</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0 reservation-arrival-details"></tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info entire-btn">Entire</button>
                <button type="button" class="btn btn-info split-btn">Split</button>
                <button type="button" class="btn btn-info full-btn">Full</button>
                <button type="button" class="btn btn-warning combine-btn">Combine</button>
                <button type="button" class="btn btn-danger break-share-btn">Break Share</button>
                <button type="button" class="btn btn-primary reservation-btn editReserWindow">Reservation</button>

                <button type="button" class="btn btn-success share-save-btn">Save</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- /Modal window -->

<script>
    let spform_id = "#shares-popup form";

    // disable button if reservation is not shared
    function disableButtons() {
        $('.entire-btn').prop('disabled', true);
        $('.split-btn').prop('disabled', true);
        $('.full-btn').prop('disabled', true);
        $('.break-share-btn').prop('disabled', true);
    }

    function sharesPopup() {
        // ressysId, roomType, roomTypedesc;
        ressysId = 2032;

        let data = {
            'reservation_id': ressysId
        };

        $.ajax({
            url: '<?= base_url('/reservation/get-reservation-details') ?>',
            type: "get",
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response['SUCCESS'] == 200) {
                    let output = response['RESPONSE']['OUTPUT'];

                    $('.room-details').html(output.room_details);
                    $('.nightly-rate-details').html(output.nightly_rate_details);
                    $('.reservation-arrival-details').html(output.reservation_arrival_details);
                }
            }
        });

        $("#shares-popup").modal('show');
    }

    function changeReservationId(e, reservation_id){
        $(e).addClass('active-tr');
        ressysId = reservation_id;
        setReservationBtnAttr();
    }

    function setReservationBtnAttr(){
        $('.reservation-btn').attr('data_sysid', ressysId);
    }

    $(document).ready(function() {
        disableButtons();
        setReservationBtnAttr();

        $(".shares-popup").click(sharesPopup);
    });
</script>