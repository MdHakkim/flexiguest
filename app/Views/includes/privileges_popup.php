<div class="modal fade privileges-modal" style="z-index: 1100" data-bs-backdrop="static" data-keyboard="false" aria-lableledby="popModalWindowlable">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="popModalWindowlabel">Privileges</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
            </div>

            <form>
                <div class="modal-body">
                    <div class="row text-center">
                        <div class="col-md-6">
                            <label class="switch">
                                <input type="checkbox" name="RESV_NO_POST" class="switch-input" value="1" />
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="bx bx-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="bx bx-x"></i>
                                    </span>
                                </span>
                                <span class="switch-label">No Post</span>
                            </label>
                        </div>

                        <div class="col-md-6">
                            <label class="switch">
                                <input type="checkbox" name="RESV_POST_STAY_CHARGES" class="switch-input" value="1" />
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="bx bx-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="bx bx-x"></i>
                                    </span>
                                </span>
                                <span class="switch-label">Post Stay Charges</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary submit-btn">
                        Submit
                    </button>

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    var privileges_form = '.privileges-modal form';

    $(document).ready(function() {
        $(privileges_form).submit(function(e) {
            e.preventDefault();
        });

        $(document).on('change', `${privileges_form} [name=RESV_NO_POST]`, function() {
            if ($(`${privileges_form} [name=RESV_NO_POST]`).is(':checked')) {
                $(`${privileges_form} [name=RESV_POST_STAY_CHARGES]`).prop('checked', false).prop('disabled', true);
            } else
                $(`${privileges_form} [name=RESV_POST_STAY_CHARGES]`).prop('disabled', false);
        });

        $(document).on('click', `${privileges_form} .submit-btn`, function() {
            var fd = new FormData();
            fd.append('RESV_ID', <?= $reservation_id ?>);

            if (!$(`${privileges_form} [name=RESV_NO_POST]`).is(':checked'))
                fd.append('RESV_NO_POST', 'N');
            else
                fd.append('RESV_NO_POST', 'Y');

            if (!$(`${privileges_form} [name=RESV_POST_STAY_CHARGES]`).is(':checked'))
                fd.append('RESV_POST_STAY_CHARGES', 0);
            else
                fd.append('RESV_POST_STAY_CHARGES', 1);

            $.ajax({
                url: '<?= base_url('reservation/update-privileges') ?>',
                type: "post",
                data: fd,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    var mcontent = '';
                    $.each(response['RESPONSE']['REPORT_RES'], function(ind, data) {
                        mcontent += '<li>' + data + '</li>';
                    });

                    if (response['SUCCESS'] != 200) {
                        showModalAlert('error', mcontent);
                    } else {
                        showModalAlert('success', mcontent);
                        window.location.reload();
                    }
                }
            });
        });

    });

    function resetPrivilegesForm() {
        <?php if ($reservation['RESV_NO_POST'] == 'Y') :  ?>
            $(`${privileges_form} [name=RESV_NO_POST]`).prop('checked', true);
            $(`${privileges_form} [name=RESV_POST_STAY_CHARGES]`).prop('checked', false).prop('disabled', true);
        <?php else : ?>
            $(`${privileges_form} [name=RESV_NO_POST]`).prop('checked', false);
        <?php endif ?>

        <?php if ($reservation['RESV_POST_STAY_CHARGES']) :  ?>
            $(`${privileges_form} [name=RESV_POST_STAY_CHARGES]`).prop('checked', true);
        <?php else : ?>
            $(`${privileges_form} [name=RESV_POST_STAY_CHARGES]`).prop('checked', false);
        <?php endif ?>
    }

    function showPrivilegesModal() {
        resetPrivilegesForm();
        $('.privileges-modal').modal('show');
    }

    function hidePrivilegesModal() {
        $('.privileges-modal').modal('hide');
    }
</script>