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
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/libs/animate-css/animate.css') ?>" />
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/libs/sweetalert2/sweetalert2.css') ?>" />
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/libs/apex-charts/apex-charts.css') ?>" />
<link rel="stylesheet" href="<?php echo base_url('assets/css/flexiguest.css')?>" />
<link rel="stylesheet" href="<?php echo base_url('assets/fontawesome/css/all.min.css')?>" />
<link rel="stylesheet" href="<?php echo base_url('assets/fontawesome/css/fontawesome.min.css')?>" />
<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css')?>" />
<link rel="stylesheet" href="<?php echo base_url('assets/css/dataTables.bootstrap5.min.css')?>" />
<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap-datepicker.min.css')?>" />
<!-- Added by Deleep -->
<link rel="stylesheet"
        href="<?php echo base_url('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')?>" />
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/libs/bs-stepper/bs-stepper.css')?>" />
<link rel="stylesheet"
    href="<?php echo base_url('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')?>" />
<link rel="stylesheet"
    href="<?php echo base_url('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')?>" />
<link rel="stylesheet"
    href="<?php echo base_url('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')?>" />
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')?>" />
<link rel="stylesheet"
    href="<?php echo base_url('assets/vendor/libs/datatables-select-bs5/select.bootstrap5.css')?>" />
<link rel="stylesheet"
        href="<?php echo base_url('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css')?>" />


<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap-datetimepicker.min.css')?>" />
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css')?>" />
<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap-select.css')?>" />
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/libs/select2/select2.css')?>" />
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/libs/tagify/tagify.css')?>" />


<!-- text editor -->
<link rel="stylesheet" href="<?= base_url('assets/vendor/libs/quill/editor.css') ?>" />
<link rel="stylesheet" href="<?php // echo base_url('assets/vendor/libs/fullcalendar/fullcalendar.css') ?>" />
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/libs/flatpickr/flatpickr.css') ?>" />
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/libs/jquery-timepicker/jquery-timepicker.css') ?>" />
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/libs/pickr/pickr-themes.css') ?>" />
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/css/pages/app-calendar.css')?>" >


<!-- Page CSS -->
<script src="<?php echo base_url('assets/js/jquery-3.6.0.min.js')?>"></script>
<!-- Helpers -->
<script src="<?php echo base_url('assets/vendor/js/helpers.js') ?>"></script>
<script src="<?php echo base_url('assets/vendor/js/template-customizer.js') ?>"></script>
<!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
<script src="<?php echo base_url('assets/js/config.js') ?>"></script>

<?php if (isset($css_to_load)) {
        foreach ($css_to_load as $row) {
    ?>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/' . $row); ?>" />
    <?php
        }
    }?>
   
    <?php if (isset($css_vendor_load)) {
        foreach ($css_vendor_load as $row) {
    ?>
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/
    ' . $row); ?>" />
    <?php
        }
    }?>
<style>
/* #template-customizer{
      display: none;
    } */
</style>
<!-- Header Script End -->