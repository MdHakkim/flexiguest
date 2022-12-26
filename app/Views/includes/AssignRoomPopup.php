<!-- Assign Room Modal -->
<style>

.light-style .swal2-container {
    z-index: 1110;
}
</style>
<div class="modal fade" id="assignRoomSearch" tabindex="-1" aria-hidden="true" data-backdrop="static"
    data-keyboard="false">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignRoomSearchLabel">Select a Room for the Current Reservation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= $this->include('includes/RoomsStatusList') ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary close_selected_room" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
var rmTypeIdVal, orig_room_type;


$(document).on('select2:open', "#RESV_RM_TYPE", function() {
    // Store the current value on focus and on change
    rmTypeIdVal = $(this).find(":selected").data('room-type-id');
    orig_room_type = rmTypeIdVal;
    //alert(orig_room_type);
});

$(document).on('click', '.assignRoom', function() {

    var errMsg = '';

    var assign_room = $(".assignRoom").attr('rel');
    var resv_id = $(".assignRoom").attr('data_sysid');
    
    $("#S_ARRIVAL_DATE").val($(".assignRoom").attr('data_arrival'));


    if ($('[name="RESV_ARRIVAL_DT"]').val() == '')
        errMsg += '<li>You must select an arrival date before assigning a room</li>';
    if ($('[name="RESV_NIGHT"]').val() == '')
        errMsg += '<li>You must enter the number of nights before assigning a room</li>';
    if ($('#RESV_RM_TYPE').val() == null)
        errMsg += '<li>You must select a room type before assigning a room</li>';

    if (errMsg != '' && assign_room == 0 ) {
        showModalAlert('error', errMsg);
    } else {

        $('#assignRoomSearch').modal('show');

        clearFormFields('.dt_adv_rm_search');

        $('.resv_stat_search_div,.service_stat_search_div').hide();
        //$('.active_rm_stat_div').find('.switch-input').prop('disabled', true);
        $('.check_out_chk,.arriv_date_div').removeClass('d-none');
        $('.occ_chk').addClass('d-none');
        $('.fo_stat_search_div').removeClass('pb-4').addClass('pb-3');


        resetRoomSelectButton('single');

        //$('#combined_profiles').dataTable().fnDraw();

        var filterData = setRmAssignSearchDefault();
        
        filterData.push({
                field: '#S_RM_TYPES',
                value: $('#RESV_RM_TYPE').val(),
                status: '1'
            }, {
                field: '#S_RESV_ID',
                value: resv_id ?? $('#reservationDetail').find('#RESV_ID').val(),
                status: '1'
            }
            // {
            //     field: '#S_RM_ID',
            //     value: $('#RESV_ROOM').val(),
            //     status: '1',
            //     class: 'selectpicker',
            //     htmlOption: '<option value="' + $('#RESV_ROOM').val() + '" data-room-id="' + $('#RESV_ROOM_ID')
            //         .val() + '">' + $('#RESV_ROOM').val() + '</option>'
            // }
        );

        loadRoomsTable(filterData);
    }
});

$(document).on('click', '.assign_selected_room', function() {

    var assign_room = $(".assignRoom").attr('rel');
    var resv_id = $('#room_assign').attr('data_sysid');
   
    var selected_room_type = parseInt($('.assign_selected_room').attr(
        'data-room-type-id'));
    orig_room_type = orig_room_type === undefined ? parseInt($('#RESV_RM_TYPE').find(":selected").data(
        'room-type-id')) : orig_room_type;

   // alert(orig_room_type + ' ' + selected_room_type);

    Swal.fire({
        title: '',
        html: '<h4 class="lh-lg">Are you sure you want to assign Room No: ' + $('.assign_selected_room')
            .attr(
                'data-room-no') + ' for this reservation?</h4>',
        text: "",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        customClass: {
            confirmButton: 'btn btn-primary me-3',
            cancelButton: 'btn btn-label-secondary',
            container: 'my-swal'
        },
        buttonsStyling: false
    }).then(function(result) {
        if (result.value) {

            //If room with different room type selected 

            if (selected_room_type != orig_room_type) { // if Room Type changed
                $("#RESV_RM_TYPE option[data-room-type-id='" + selected_room_type + "']").prop(
                    "selected", true);
                $("#RESV_RM_TYPE").trigger('change');

                Swal.fire({
                    title: '',
                    html: '<h4 class="lh-lg">Room Type has been changed to ' + $(
                        "#RESV_RM_TYPE").val() + '. Do you also want to change RTC to ' + $(
                        "#RESV_RM_TYPE").val() + '?</h4>',
                    text: "",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    customClass: {
                        confirmButton: 'btn btn-primary me-3',
                        cancelButton: 'btn btn-label-secondary',
                        container: 'my-swal'
                    },
                    buttonsStyling: false
                }).then(function(result2) {
                    if (result2.value) {

                        // ---- Change Rate Code here ----- //

                        $("#RESV_RTC option[data-room-type-id='" + selected_room_type + "']")
                            .prop("selected", true);
                        $("#RESV_RTC").trigger('change');

                        setUpdatedRate();
                    }
                });
            }

            $('#RESV_ROOM').val($('.assign_selected_room').attr('data-room-no'));
            $('#RESV_ROOM_ID').val($('.assign_selected_room').attr('data-room-id'));
            

            

            clicked_room_ids = [];

            $('#assignRoomSearch').modal('hide');
        }

    });

});

function updateRoomFromOptions(RESV_ROOM,RESV_ROOM_ID,RESV_RM_TYPE,RESV_RM_TYPE_ID,RESV_RATE,RESV_RATE_CODE,RESV_ID){
    
    $.ajax({
        url: '<?php echo base_url('/assignRoomFromOptions') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data:{room_no:RESV_ROOM,room_id:RESV_ROOM_ID,room_type:RESV_RM_TYPE,room_type_id:RESV_RM_TYPE_ID,resv_rate:RESV_RATE,resv_rate_code:RESV_RATE_CODE,resv_id:RESV_ID},
        async: false,
        success: function(respn) {
            $('#dataTable_view').dataTable().fnDraw();
        }
    });
}

function setUpdatedRate() {
    var assign_room = $(".assignRoom").attr('rel');

    var resv_id = $('#room_assign').attr('data_sysid');

    var room_rate = $('.assign_selected_room').attr('data_rate');
    var room_rate_code = $('.assign_selected_room').attr('data_rate_code');

    var currentRmType = $('#RESV_RTC').val();
    var currentRmTypeId = $('#RESV_RTC').data('room-type-id');

    var currentRate =  $('#RESV_RATE').val()??room_rate;
    var currentRateCode = $('[name="RESV_RATE_CODE"]').val()??room_rate_code;

    var custId = $('[name="RESV_NAME"]').find('option:selected').val();

    var adults = $('#room_assign').attr('data_adults') ?? $('#RESV_ADULTS').val();
    var children = $('#room_assign').attr('data_children') ?? $('#RESV_CHILDREN').val();

    var arrivalDate = $('#room_assign').attr('data_arrival') ?? $('[name="RESV_ARRIVAL_DT"]').val();
    var departureDate = $('#room_assign').attr('data_departure') ?? $('[name="RESV_DEPARTURE"]').val();

    $('#rateQueryTable .active').removeClass('active');

    $.ajax({
        url: '<?php echo base_url('/setUpdatedRateCode') ?>',
        type: 'POST',
        async: false,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            resv_room_type: currentRmType,
            resv_rate_code: currentRateCode,
            arrivalDate: arrivalDate,
            departureDate: departureDate,
            adults_num: adults,
            children_num: children
        },
        dataType: 'json'
    }).done(function(response) {
        console.log(response);

        if (response) {

            $(response).each(function(inx, data) {
                $('[name="RESV_RATE_CODE"]').val(data.RT_CD_CODE);
                $('#RESV_RATE_CODE_OPTIONS').val(data.RT_CD_CODE);
                $('#RESV_RATE').val(data.ACTUAL_GUEST_PRICE);  
                $('#RESV_RATE_OPTIONS').val(data.ACTUAL_GUEST_PRICE);  
                
                rate_code = data.RT_CD_CODE;
                resv_rate = data.ACTUAL_GUEST_PRICE;


            });

            if(assign_room == 1)
            {                 
                 var room_no      = $('.assign_selected_room').attr('data-room-no');
                 var room_id      = $('.assign_selected_room').attr('data-room-id');
                 var room_type    = $('.assign_selected_room').attr('data-room-type');
                 var room_type_id = $('.assign_selected_room').attr('data-room-type-id');
                
                updateRoomFromOptions(room_no,room_id,room_type,room_type_id,resv_rate,rate_code,resv_id);
            }

            Swal.fire({
                title: '',
                html: '<h4 class="lh-lg">Please verify the Rate Code and Room type availability on the Rate Query Screen</h4>',
                text: '',
                icon: 'info',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            });
        } else showModalAlert('error', '<li>The Rate could not be changed. Please try again</li>');

    }).fail(function(jqXHR, textStatus, errorThrown) {
        showModalAlert('error', '<li>The Rate could not be changed. Please try again</li>');
    });
}
</script>