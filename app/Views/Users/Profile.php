<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>

<!-- Page CSS -->
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/vendor/css/pages/page-profile.css" />

<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>
<?= $this->include('Layout/image_modal') ?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4">
            My Profile
        </h4>

        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="user-profile-header-banner">
                        <img src="<?php echo base_url(); ?>/assets/img/pages/profile-banner.png" alt="Banner image"
                            class="rounded-top" />
                    </div>
                    <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                        <div class="mt-n2 flex-shrink-0 mx-auto mx-sm-0">
                            <img src="<?php echo file_exists($profile_data['USR_IMAGE']) ? base_url().'/'.$profile_data['USR_IMAGE'] : base_url().'/assets/img/avatars/avatar-generic.jpg'; ?>"
                                alt="user image" class="rounded-3 user-profile-img d-block h-auto cursor-pointer ms-0 ms-sm-4" onclick="displayImagePopup('<?=$profile_data['USR_IMAGE']?>')" />
                        </div>
                        <div class="flex-grow-1 mt-3 mt-sm-5">
                            <div
                                class="gap-4 d-flex flex-column flex-md-row justify-content-start justify-content-md-between align-items-center align-items-sm-start align-items-md-end mx-4">
                                <div class="user-profile-info">
                                    <h4><?= $profile_data['USR_FIRST_NAME'] . ' ' . $profile_data['USR_LAST_NAME']; ?>
                                    </h4>
                                    <ul
                                        class="list-inline gap-2 d-flex flex-wrap justify-content-center justify-content-sm-start align-items-center mb-0">
                                        <li class="list-inline-item fw-semibold"><i class="bx bx-pen"></i>
                                            <?= $profile_data['ROLE_NAME']?>
                                        </li>
                                        <li class="list-inline-item fw-semibold">
                                            <i class="bx bx-calendar-alt"></i> Joined
                                            <?= date('F Y', strtotime($profile_data['USR_DOJ']))?>
                                        </li>
                                    </ul>
                                </div>
                                <a href="<?php echo base_url(); ?>/my-profile/edit" class="btn btn-primary text-nowrap">
                                    <i class="bx bx-pencil"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Header -->

        <!-- Navbar pills -->
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-sm-row mb-4">
                    <li class="nav-item">
                        <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user"></i> Profile</a>
                    </li>
                </ul>
            </div>
        </div>
        <!--/ Navbar pills -->

        <?php //echo "<pre>"; print_r($profile_data); echo "</pre>"; ?>

        <!-- User Profile Content -->
        <div class="row">
            <div class="col-md-5 col-lg-5 col-xl-4">
                <!-- About User -->
                <div class="card mb-4">
                    <div class="card-body">
                        <small class="text-muted text-uppercase">About</small>
                        <ul class="list-unstyled mt-3 mb-4">
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-user"></i><span class="fw-semibold mx-2">Full Name:</span>
                                <span><?= $profile_data['USR_FIRST_NAME'] . ' ' . $profile_data['USR_LAST_NAME']; ?></span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-check"></i><span class="fw-semibold mx-2">Status:</span>
                                <span
                                    class="badge rounded-pill bg-label-<?= $profile_data['USR_STATUS'] ? 'success' : 'danger' ?>"><?= $profile_data['USR_STATUS'] ? 'Active' : 'Inactive' ?></span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-star"></i><span class="fw-semibold mx-2">Role:</span>
                                <span><?= $profile_data['ROLE_NAME']?></span>
                            </li>
                        </ul>
                        <small class="text-muted text-uppercase">Contacts</small>
                        <ul class="list-unstyled mt-3 mb-4">
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-phone"></i><span class="fw-semibold mx-2">Contact:</span>
                                <span><?= $profile_data['USR_PHONE']?></span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-envelope"></i><span class="fw-semibold mx-2">Email:</span>
                                <span><?= $profile_data['USR_EMAIL']?></span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-calendar"></i><span class="fw-semibold mx-2">DOB:</span>
                                <span><?= $profile_data['USR_DOB']?></span>
                            </li>
                        </ul>
                    </div>
                </div>
                <!--/ About User -->
                <!-- Profile Overview -->
                <div class="card mb-4">
                    <div class="card-body">
                        <small class="text-muted text-uppercase">Overview</small>
                        <ul class="list-unstyled mt-3 mb-0">
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-check"></i><span class="fw-semibold mx-2">Task Compiled:</span>
                                <span>13.5k</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-customize"></i><span class="fw-semibold mx-2">Projects Compiled:</span>
                                <span>146</span>
                            </li>
                            <li class="d-flex align-items-center">
                                <i class="bx bx-user"></i><span class="fw-semibold mx-2">Connections:</span>
                                <span>897</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <!--/ Profile Overview -->
            </div>
            <div class="col-md-7 col-lg-7 col-xl-8">
                <!-- Activity Timeline -->
                <div class="card card-action mb-4">
                    <div class="card-header align-items-center">
                        <h5 class="card-action-title mb-0"><i class="bx bx-list-ul bx-sm me-2"></i>Activity Timeline
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="timeline ms-2">
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point timeline-point-warning"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-1">
                                        <h6 class="mb-0">Client Meeting</h6>
                                        <small class="text-muted">Today</small>
                                    </div>
                                    <p class="mb-2">Project meeting with john @10:15am</p>
                                    <div class="d-flex flex-wrap">
                                        <div class="avatar me-3">
                                            <img src="<?php echo base_url(); ?>/assets/img/avatars/3.png" alt="Avatar"
                                                class="rounded-circle" />
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Lester McCarthy (Client)</h6>
                                            <span>CEO of Infibeam</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point timeline-point-info"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-1">
                                        <h6 class="mb-0">Create a new project for client</h6>
                                        <small class="text-muted">2 Day Ago</small>
                                    </div>
                                    <p class="mb-0">Add files to new design folder</p>
                                </div>
                            </li>
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point timeline-point-primary"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-1">
                                        <h6 class="mb-0">Shared 2 New Project Files</h6>
                                        <small class="text-muted">6 Day Ago</small>
                                    </div>
                                    <p class="mb-2">
                                        Sent by Mollie Dixon
                                        <img src="<?php echo base_url(); ?>/assets/img/avatars/4.png"
                                            class="rounded-circle ms-3" alt="avatar" height="20" width="20" />
                                    </p>
                                    <div class="gap-2 d-flex flex-wrap">
                                        <a href="javascript:void(0)" class="me-3">
                                            <img src="<?php echo base_url(); ?>/assets/img/icons/misc/pdf.png"
                                                alt="Document image" width="20" class="me-2" />
                                            <span class="h6">App Guidelines</span>
                                        </a>
                                        <a href="javascript:void(0)">
                                            <img src="<?php echo base_url(); ?>/assets/img/icons/misc/doc.png"
                                                alt="Excel image" width="20" class="me-2" />
                                            <span class="h6">Testing Results</span>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point timeline-point-success"></span>
                                <div class="timeline-event pb-0">
                                    <div class="timeline-header mb-1">
                                        <h6 class="mb-0">Project status updated</h6>
                                        <small class="text-muted">10 Day Ago</small>
                                    </div>
                                    <p class="mb-0">Woocommerce iOS App Completed</p>
                                </div>
                            </li>
                            <li class="timeline-end-indicator">
                                <i class="bx bx-check-circle"></i>
                            </li>
                        </ul>
                    </div>
                </div>
                <!--/ Activity Timeline -->
            </div>
        </div>
        <!--/ User Profile Content -->
    </div>
    <!-- / Content -->

    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->


<?= $this->endSection() ?>