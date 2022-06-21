<!-- Shares Popup -->
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

                <!-- <button type="button" class="btn btn-success" onclick="saveShareReservations()">Save</button> -->
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Combine-Popup -->
<div class="modal fade" id="combine-popup" tabindex="-1" aria-labelledby="combineLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="combineLabel">Combine Share Reservation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">

                    <div class="card">

                        <div class="card-header border-bottom">
                            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-tab" role="tab" aria-selected="true">
                                        Profile
                                    </button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#reservation-tab" role="tab" aria-selected="false">
                                        Reservation
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content">

                            <div class="tab-pane fade show active" id="profile-tab" role="tabpanel">
                                <form id="share-by-profile-form">
                                    <input type="hidden" name="CUST_ID" />

                                    <div class="row g-3">

                                        <div class="col-md-6">
                                            <lable class="form-lable">Title / First Name</lable>
                                            <div class="input-group">
                                                <select name="CUST_TITLE" class="form-select" data-allow-clear="true" disabled>
                                                    <option value="">Select</option>
                                                    <option value="Mr.">Mr.</option>
                                                    <option value="Ms.">Ms.</option>
                                                    <option value="Shiekh.">Shiekh.</option>
                                                    <option value="Shiekha.">Shiekha.</option>
                                                    <option value="Dr.">Dr.</option>
                                                    <option value="Ambassador.">Ambassador.</option>
                                                    <option value="Madam Ambassadress">Madam Ambassadress</option>
                                                    <option value="Prince.">Prince.</option>
                                                    <option value="Princess.">Princess.</option>
                                                    <option value="President">President</option>
                                                    <option value="Prof.">Prof.</option>
                                                    <option value="Minister.">Minister.</option>
                                                    <option value="Admiral">Admiral</option>
                                                    <option value="Lieutenant.">Lieutenant.</option>
                                                    <option value="Consul.">Consul.</option>
                                                </select>

                                                <input type="text" name="CUST_FIRST_NAME" class="form-control" placeholder="First Name" style="flex-basis: fit-content;" readonly />

                                                <div class="invalid-feedback">
                                                    Title required can't empty.
                                                </div>

                                                <button type="button" onClick="childReservation('C')" class="btn flxi_btn btn-sm btn-primary">
                                                    <i class="fa fa-search" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Last Name</label>
                                            <input type="text" name="CUST_LAST_NAME" class="form-control" placeholder="Last Name" readonly />
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Adult</label>
                                            <input type="number" name="RESV_ADULTS" class="form-control" />
                                        </div>


                                        <div class="col-md-6">
                                            <label class="form-label">Children</label>
                                            <input type="number" name="RESV_CHILDREN" class="form-control" />
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Reservation Type</label>
                                            <select name="RESV_RESRV_TYPE" class="select2 form-select" data-allow-clear="true">
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Payment Type</label>
                                            <select name="RESV_PAYMENT_TYPE" class="select2 form-select" data-allow-clear="true">
                                            </select>
                                        </div>
                                    </div>

                                    <div class="pt-4 text-right">
                                        <button class="btn btn-primary me-1 me-sm-3" onclick="submitShareByProfileForm()">Submit</button>
                                        <button class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="reservation-tab" role="tabpanel">
                                <form id="share-by-reservation-form">
                                    <input type="hidden" name="RESV_ID" />

                                    <div class="row g-3">

                                        <div class="col-md-6">
                                            <lable class="form-lable">Title / First Name</lable>
                                            <div class="input-group">
                                                <select name="CUST_TITLE" class="form-select" data-allow-clear="true" disabled>
                                                    <option value="">Select</option>
                                                    <option value="Mr.">Mr.</option>
                                                    <option value="Ms.">Ms.</option>
                                                    <option value="Shiekh.">Shiekh.</option>
                                                    <option value="Shiekha.">Shiekha.</option>
                                                    <option value="Dr.">Dr.</option>
                                                    <option value="Ambassador.">Ambassador.</option>
                                                    <option value="Madam Ambassadress">Madam Ambassadress</option>
                                                    <option value="Prince.">Prince.</option>
                                                    <option value="Princess.">Princess.</option>
                                                    <option value="President">President</option>
                                                    <option value="Prof.">Prof.</option>
                                                    <option value="Minister.">Minister.</option>
                                                    <option value="Admiral">Admiral</option>
                                                    <option value="Lieutenant.">Lieutenant.</option>
                                                    <option value="Consul.">Consul.</option>
                                                </select>

                                                <input type="text" name="CUST_FIRST_NAME" class="form-control" placeholder="First Name" style="flex-basis: fit-content;" readonly />

                                                <div class="invalid-feedback">
                                                    Title required can't empty.
                                                </div>

                                                <button type="button" onClick="showSearchReservationPopup()" class="btn flxi_btn btn-sm btn-primary">
                                                    <i class="fa fa-search" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Last Name</label>
                                            <input type="text" name="CUST_LAST_NAME" class="form-control" placeholder="Last Name" readonly />
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Adult</label>
                                            <input type="number" name="RESV_ADULTS" class="form-control" readonly />
                                        </div>


                                        <div class="col-md-6">
                                            <label class="form-label">Children</label>
                                            <input type="number" name="RESV_CHILDREN" class="form-control" readonly />
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Payment Type</label>
                                            <select name="RESV_PAYMENT_TYPE" class="select2 form-select" data-allow-clear="true">
                                            </select>
                                        </div>
                                    </div>

                                    <div class="pt-4 text-right">
                                        <button class="btn btn-primary me-1 me-sm-3" onclick="submitShareByReservationForm()">Submit</button>
                                        <button class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-success combine-save-btn">Save</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div> -->
        </div>
    </div>
</div>


<script>
    let share_popup_id = "#shares-popup";
    var share_by_profile_form_id = "#share-by-profile-form";
    var share_by_reservation_form_id = "#share-by-reservation-form";
    var share_reservation = false;
    var newly_added_reservation_ids = [];

    // disable button if reservation is not shared
    function enableDisableButtons() {
        let reservation_ids = $(`${share_popup_id} input[name='share_reservations[]']`).map(function() {
            return $(this).val();
        }).get();

        $('.entire-btn').prop('disabled', false);
        $('.split-btn').prop('disabled', false);
        $('.full-btn').prop('disabled', false);
        $('.break-share-btn').prop('disabled', false);

        if (reservation_ids.length == 1) {
            $('.entire-btn').prop('disabled', true);
            $('.split-btn').prop('disabled', true);
            $('.full-btn').prop('disabled', true);
            $('.break-share-btn').prop('disabled', true);
        }
    }

    function displaySharePopup() {
        setBtnsAttr(ressysId);
        getReservationDetails();
        $("#shares-popup").modal('show');
    }

    function hideSharePopup() {
        $("#shares-popup").modal('hide');
    }

    function getReservationDetails() {
        // ressysId, roomType, roomTypedesc;
        let data = {
            'reservation_id': ressysId
        };

        $.ajax({
            url: '<?= base_url('reservation/shares/get-reservation-details') ?>',
            type: "get",
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response['SUCCESS'] == 200) {
                    let output = response['RESPONSE']['OUTPUT'];

                    $('.room-details').html(output.room_details);
                    $('.nightly-rate-details').html(output.nightly_rate_details);
                    $('.reservation-arrival-details').html(output.reservation_arrival_details);

                    enableDisableButtons();
                    setBtnsAttr(ressysId);
                }
            }
        });
    }

    function changeReservationId(e, reservation_id) {
        $('.active-tr').removeClass('active-tr');
        $(e).addClass('active-tr');
        // ressysId = reservation_id;
        setBtnsAttr(reservation_id);        
    }

    function setBtnsAttr(reservation_id) {
        $('#shares-popup .reservation-btn').attr('data_sysid', reservation_id);
        $('#shares-popup .break-share-btn').attr('data_sysid', reservation_id);
        $('#shares-popup .entire-btn').attr('data_sysid', reservation_id);
        $('#shares-popup .split-btn').attr('data_sysid', reservation_id);
        $('#shares-popup .full-btn').attr('data_sysid', reservation_id);
    }

    function displayCombinePopup() {
        runInitializeConfig();
        $("#combine-popup").modal('show');
    }

    function hideCombinePopup() {
        $("#combine-popup").modal('hide');
    }

    function submitShareByProfileForm() {
        hideCombinePopup();

        let form_data = new FormData($(share_by_profile_form_id)[0]);
        form_data.append('other_reservation_id', ressysId);

        $.ajax({
            url: '<?= base_url('reservation/shares/create-reservation') ?>',
            type: "post",
            data: form_data,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response['SUCCESS'] != 200) {
                    let errors = '';
                    $.each(response['RESPONSE']['REPORT_RES'], function(ind, data) {
                        errors += '<li>' + data + '</li>';
                    });

                    showModalAlert('error', errors);
                } else {
                    let reservation_id = response['RESPONSE']['OUTPUT'];
                    newly_added_reservation_ids.push(reservation_id);
                    saveShareReservations();
                }
            }
        });
    }

    function submitShareByReservationForm() {
        hideCombinePopup();

        if (share_reservation) {
            newly_added_reservation_ids.push(share_reservation['RESV_ID']);
            saveShareReservations();
        }
    }

    function saveShareReservations() {
        let reservation_ids = $(`${share_popup_id} input[name='share_reservations[]']`).map(function() {
            return $(this).val();
        }).get();

        reservation_ids = reservation_ids.concat(newly_added_reservation_ids);

        $.ajax({
            url: '<?= base_url('reservation/shares/add-share-reservations') ?>',
            type: 'post',
            data: {
                reservation_ids
            },
            dataType: 'json',
            success: function(response) {
                if (response['SUCCESS'] == 200) {
                    newly_added_reservation_ids = [];
                    getReservationDetails();
                    showModalAlert('success', `<li>${response.RESPONSE.REPORT_RES.msg}</li>`);
                } else {
                    showModalAlert('error', `<li>${response.RESPONSE.REPORT_RES.msg}</li>`);
                }
            }
        });
    }

    $(document).on('click', '.break-share-btn', function() {
        let selected_reservation_id = $(this).attr('data_sysid');
        let main_reservation_id = ressysId;
        
        let reservation_ids = $(`${share_popup_id} input[name='share_reservations[]']`).map(function() {
            return $(this).val();
        }).get();

        $.ajax({
            url: '<?= base_url('reservation/shares/break-share-reservation') ?>',
            type: 'post',
            data: {
                selected_reservation_id,
                main_reservation_id,
                reservation_ids,
                share_rate: 'full',
            },
            dataType: 'json',
            success: function(response) {
                if (response['SUCCESS'] == 200) {
                    showModalAlert('success', `<li>${response.RESPONSE.REPORT_RES.msg}</li>`);
                    getReservationDetails();
                }else{
                    showModalAlert('error', `<li>${response.RESPONSE.REPORT_RES.msg}</li>`);
                }
            }
        });
    });

    function changeShareRate(selected_reservation_id, share_rate) {
        let reservation_ids = $(`${share_popup_id} input[name='share_reservations[]']`).map(function() {
            return $(this).val();
        }).get();

        $.ajax({
            url: '<?= base_url('reservation/shares/change-share-rate') ?>',
            type: 'post',
            data: {
                selected_reservation_id,
                reservation_ids,
                share_rate,
            },
            dataType: 'json',
            success: function(response) {
                if (response['SUCCESS'] == 200) {
                    showModalAlert('success', `<li>${response.RESPONSE.REPORT_RES.msg}</li>`);
                    getReservationDetails();
                }
            }
        });
    }

    $(document).ready(function() {
        enableDisableButtons();

        $(".shares-btn").click(displaySharePopup);
        $(".combine-btn").click(displayCombinePopup);

        $(".entire-btn").click(function() {
            changeShareRate($(this).attr('data_sysid'), 'entire');
        });

        $(".split-btn").click(function() {
            changeShareRate($(this).attr('data_sysid'), 'split');
        });

        $(".full-btn").click(function() {
            changeShareRate($(this).attr('data_sysid'), 'full');
        });


        $('#combine-popup li.nav-item').click(function(e) {
            e.preventDefault();
        });

        $(`${share_by_profile_form_id}, ${share_by_reservation_form_id}`).submit(function(e) {
            e.preventDefault();
        });

        // for overlapping modals
        $(document).on('show.bs.modal', '.modal', function(event) {
            var zIndex = 1090 + (10 * $('.modal:visible').length);
            $(this).css('z-index', zIndex);
            setTimeout(function() {
                $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
            }, 0);
        });
    });
</script>