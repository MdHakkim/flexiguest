
<title>Guest Registration Card</title>
<meta name="description" content="" />

<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="<?php echo base_url('assets/img/favicon/favicon.ico') ?>" />

<!-- Fonts -->

<link
    href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet" media="all" />

<!-- Core CSS -->
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/css/rtl/core.css'); ?>"
    class="template-customizer-core-css" media="all" />
<link rel="stylesheet" href="<?php echo base_url('assets/css/demo.css') ?>" />



<!-- Header Script End -->
  
<?php if (isset($css_to_load)) {
        foreach ($css_to_load as $row) {
    ?> <script type="text/javascript" src="<?php echo base_url('assets/css/' . $row); ?>"></script>
<?php
        }
    }
?>
</head>

<!-- Header Script End -->
