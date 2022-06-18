<div class="modal fade" id="search-reservation-popup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="searchReservationPopup" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Search Reservation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="search-reservation-form">
                    <div class="row">

                        <div class="col-md-3 mb-2">
                            <label class="form-label">First Name</label>
                            <input type="text" name="CUST_FIRST_NAME" class="form-control" placeholder="First Name" />
                        </div>

                        <div class="col-md-3 mb-2">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="CUST_LAST_NAME" class="form-control" placeholder="Last Name" />
                        </div>

                        <div class="col-md-2 mb-2">
                            <label class="form-label">Room No</label>
                            <input type="text" name="RESV_ROOM" class="form-control" placeholder="Room No" />
                        </div>

                        <div class="col-md-4 mt-4">
                            <button type="button" class="btn btn-info" onclick="searchReservation()">Search</button>
                            <button type="reset" class="btn btn-warning">Clear</button>
                            <button type="button" class="btn btn-primary" onclick="addResvation()">New</button>
                        </div>
                    </div>
                </form>

                <div class="row profileSearch mt-4">
                    <div class="flxy_table_resp">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" style="width:50px">Edit</th>
                                    <th scope="col" style="width:50px">Name</th>
                                    <th scope="col" style="width:250px">Status</th>
                                    <th scope="col" style="width:250px">Arrival</th>
                                    <th scope="col" style="width:150px">Departure</th>
                                    <th scope="col" style="width:250px">Rate Code</th>
                                    <th scope="col" style="width:150px">Room Type</th>
                                    <th scope="col" style="width:250px">Room No</th>
                                </tr>
                            </thead>

                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var search_reservation_popup_id = "#search-reservation-popup";
    var search_reservation_form_id = "#search-reservation-form";

    function showSearchReservationPopup() {
        $(search_reservation_popup_id).modal('show');
    }

    function hideSearchReservationPopup() {
        $(search_reservation_popup_id).modal('hide');
    }

    function searchReservation() {
        let form_data = new FormData($(search_reservation_form_id)[0]);
        form_data.append('current_reservation_id', ressysId);

        $.ajax({
            url: '<?= base_url('reservation/search-reservation') ?>',
            type: "post",
            data: form_data,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response['SUCCESS'] == 200) {
                    let output = response['RESPONSE']['OUTPUT'];
                    $(`${search_reservation_popup_id} tbody`).html(output.searched_reservations);
                }
            }
        });
    }

    $(document).on('click', `${search_reservation_popup_id} .select-reservation .select`, function() {
        $(`${search_reservation_popup_id} .select-reservation`).removeClass('active-tr');

        $(this).parent().addClass('active-tr');
        reservation_id = $(this).parent().attr('data_sysid');

        $.ajax({
            url: '<?= base_url('reservation/search-reservation') ?>',
            type: "post",
            data: {
                "RESV_ID": reservation_id,
                'current_reservation_id': ressysId,
            },
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            dataType:'json',
            success: function(response) {
                if (response['SUCCESS'] == 200) {
                    hideSearchReservationPopup();

                    share_reservation = response.RESPONSE.OUTPUT;
                    let reservation = response.RESPONSE.OUTPUT.reservations[0];
                    
                    if ($(`#combine-popup`).is(':visible')) {
                        $(`#combine-popup #reservation-tab input[name='RESV_ID']`).val(reservation['RESV_ID']);
                        $(`#combine-popup #reservation-tab select[name='CUST_TITLE']`).val(reservation['CUST_TITLE']);
                        $(`#combine-popup #reservation-tab input[name='CUST_FIRST_NAME']`).val(reservation['CUST_FIRST_NAME']);
                        $(`#combine-popup #reservation-tab input[name='CUST_LAST_NAME']`).val(reservation['CUST_LAST_NAME']);
                        $(`#combine-popup #reservation-tab input[name='RESV_ADULTS']`).val(reservation['RESV_ADULTS']);
                        $(`#combine-popup #reservation-tab input[name='RESV_CHILDREN']`).val(reservation['RESV_CHILDREN']);
                        $(`#combine-popup #reservation-tab select[name='RESV_PAYMENT_TYPE']`).val(reservation['RESV_PAYMENT_TYPE']).trigger('change');
                    }
                }
            }
        });
    });
</script>