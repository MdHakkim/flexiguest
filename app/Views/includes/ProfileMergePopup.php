<!-- Combined Profiles Modal -->

<div class="modal fade" id="profileMergeSearch" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileMergeSearchLabel">Select Profiles to Merge with Current Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <?= $this->include('includes/CombinedProfilesTable') ?>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary close_selected_profiles" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn btn-dark use_selected_profiles" disabled>Use Selected
                    Profile</button>
            </div>
        </div>
    </div>
</div>

<script>

// Click Merge button

$(document).on('click', '.merge-profile', function() {

    var custOptId = $(this).attr('data_sysid');
    var custName = $(this).attr('data_custname');

    $('#profileMergeSearch').modal('show');
    $('#profileMergeSearchLabel').html('Select Profiles to Merge with Profile: ' + custName);

});

</script>