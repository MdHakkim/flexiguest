<title><?=isset($title) ? $title : '';?></title>
<meta name="description" content="" />

<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="<?php echo base_url('assets/img/favicon/favicon.ico') ?>" />

<!-- Fonts -->
<!-- <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin /> -->
<link
    href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet" />
<!-- Icons -->
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/fonts/boxicons.css') ?>" />
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/fonts/fontawesome.css') ?>" />
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/fonts/flag-icons.css') ?>" />

<!-- Core CSS -->
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/css/rtl/core.css') ?>"
    class="template-customizer-core-css" />
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/css/rtl/theme-default.css') ?>"
    class="template-customizer-theme-css" />
<link rel="stylesheet" href="<?php echo base_url('assets/css/demo.css') ?>" />

<!-- Vendors CSS -->
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') ?>" />

<link rel="stylesheet" href="<?php echo base_url('assets/vendor/libs/typeahead-js/typeahead.css') ?>" />

<link rel="stylesheet" href="<?php echo base_url('assets/vendor/css/pages/app-invoice-print.css') ?>" />


<!-- Helpers -->
<script src="<?php echo base_url('assets/vendor/js/helpers.js') ?>"></script>
<script src="<?php echo base_url('assets/vendor/js/template-customizer.js') ?>"></script>
<!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
<script src="<?php echo base_url('assets/js/config.js') ?>"></script>

<?php if (isset($css_to_load)) {
        foreach ($css_to_load as $row) {
    ?> <script type="text/javascript" src="<?php echo base_url('assets/css/' . $row); ?>"></script>
    <?php
        }
    }?>
<style>
/* #template-customizer{
      display: none;
    } */
</style>
<!-- Header Script End -->