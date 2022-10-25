<?=$this->extend("Layout/AppView")?>
<?=$this->section("titleRender")?>
<title>Front Desk | FlexiGuest</title>
<?=$this->endSection()?>
<?=$this->section("contentRender")?>

<link rel="stylesheet" href="<?php echo base_url('assets/vendor/css/pages/page-help-center.css')?>" />


<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card overflow-hidden">
        <!-- Help Center Header -->
        <div class="help-center-header d-flex flex-column justify-content-center align-items-center">
            <h2 class="zindex-1 text-center">Front Desk</h2>
        </div>
        <!-- /Help Center Header -->

        <!-- Front Desk Sections -->
        <div class="front-desk-sections py-5">
            <div class="container-xl">
                <div class="row">
                    <div class="col-lg-10 mx-auto">
                        <div class="row mb-4">
                            <div class="col-md-4 mb-4 mb-md-0">
                                <div class="card shadow-none border border-primary cursor-pointer">
                                    <div class="card-body text-center">
                                        <img class="mb-4"
                                            src="<?php echo base_url() ?>/assets/img/icons/unicons/briefcase.png"
                                            height="48" alt="Arrivals" />
                                        <h5>Arrivals</h5>
                                        <p>View all the arrivals at the hotel today...</p>
                                        <a class="btn rounded-pill btn-label-primary" target="_blank"
                                            href="<?php echo base_url() ?>/reservation?SHOW_ARRIVALS=1">View</a>&nbsp;
                                        <a class="btn rounded-pill btn-label-primary" target="_blank"
                                            href="<?php echo base_url() ?>/reservation?CREATE_WALKIN=1">Create Walk-In</a>    
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-4 mb-md-0">
                                <div class="card shadow-none border border-secondary cursor-pointer">
                                    <div class="card-body text-center">
                                        <img class="mb-4"
                                            src="<?php echo base_url() ?>/assets/img/icons/unicons/community.png"
                                            height="48" alt="In House Guests" />
                                        <h5>In House Guests</h5>
                                        <p>See all the guests currently staying in the hotel...</p>
                                        <a class="btn btn-label-secondary" target="_blank"
                                            href="<?php echo base_url() ?>/reservation?SHOW_IN_HOUSE=1">View</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card shadow-none border border-success cursor-pointer">
                                    <div class="card-body text-center">
                                        <img class="mb-4"
                                            src="<?php echo base_url() ?>/assets/img/icons/unicons/desktop.png"
                                            height="48" alt="Room Assignment" />
                                        <h5>Room Assignment</h5>
                                        <p>View and plan the assignment of rooms to guests...</p>
                                        <a class="btn btn-label-success" target="_blank"
                                            href="<?php echo base_url() ?>/roomPlan">View</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 mb-4 mb-md-0">
                                <div class="card shadow-none border border-warning cursor-pointer">
                                    <div class="card-body text-center">
                                        <img class="mb-4"
                                            src="<?php echo base_url() ?>/assets/img/icons/unicons/cc-primary.png"
                                            height="48" alt="Notifications" />
                                        <h5>Notifications</h5>
                                        <p>Check all notifications (messages, alerts etc)</p>
                                        <a class="btn btn-label-warning" target="_blank"
                                            href="<?php echo base_url() ?>/Notifications">View</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-4 mb-md-0">
                                <div class="card shadow-none border border-danger cursor-pointer">
                                    <div class="card-body text-center">
                                        <img class="mb-4"
                                            src="<?php echo base_url() ?>/assets/img/icons/unicons/chart.png"
                                            height="48" alt="Restaurant" />
                                        <h5>Restaurant</h5>
                                        <p>View all Restaurants, Menus and Orders...</p>
                                        <a class="btn btn-label-danger" target="_blank"
                                            href="<?php echo base_url() ?>/restaurant">View</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card shadow-none border border-info cursor-pointer">
                                    <div class="card-body text-center">
                                        <img class="mb-4"
                                            src="<?php echo base_url() ?>/assets/img/icons/unicons/community.png"
                                            height="48" alt="Help center articles" />
                                        <h5>Profile Settings</h5>
                                        <p>Update your profile details and settings...</p>
                                        <a class="btn btn-label-info" target="_blank"
                                            href="<?php echo base_url() ?>/my-profile">View</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- /Front Desk Sections -->


        <!-- Help Area -->
        <div class="help-center-contact-us help-center-bg-alt">
            <div class="container-xl">
                <div class="row justify-content-center py-5 my-3">
                    <div class="col-md-8 col-lg-6 text-center">
                        <h4>Still need help?</h4>
                        <p class="mb-4">
                            Enter your keywords in the top search bar or browse through the left menu to go to your
                            required page.
                        </p>
                        <!-- <div class="gap-4 d-flex flex-wrap justify-content-center">
                            <a href="javascript:void(0);" class="btn btn-label-primary">Visit our community</a>
                            <a href="javascript:void(0);" class="btn btn-label-primary">Contact us</a>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
        <!-- /Help Area -->
    </div>
</div>

<script>
$(document).on('mouseover mouseout', '.card', function() {

    $(this).toggleClass('shadow-none');

});
</script>

<?=$this->endSection()?>