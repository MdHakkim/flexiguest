<div class="modal fade confirm-password-modal" style="z-index: 1100" data-backdrop="static" data-keyboard="false" aria-lableledby="popModalWindowlable">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="popModalWindowlabel">Confirm your password</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
            </div>

            <form>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" value="<?= $session->user['USR_NAME'] ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
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
    $(document).ready(function() {
        $('.confirm-password-modal form').submit(function(e) {
            e.preventDefault();
        });

        $('.confirm-password-modal .submit-btn').click(function() {

            var fd = new FormData($(`.confirm-password-modal form`)[0]);

            $.ajax({
                url: '<?= base_url('user/confirm-password') ?>',
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

                    if (response['SUCCESS'] != 200)
                        showModalAlert('error', mcontent);
                    else
                        window.location.reload();
                }
            });
        });
    });

    function showConfirmPasswordModal() {
        $('.confirm-password-modal').modal('show');
    }
</script>