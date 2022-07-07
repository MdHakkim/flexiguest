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

                <?= $this->include('includes/CombinedProfilesTable') ?>

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

    var filterData = [
        /*{
                field: '#S_PROFILE_TYPE',
                value: '1',
                status: '0'
            }*/
    ];

    loadProfilesTable(filterData);

});
</script>

<?=$this->endSection()?>