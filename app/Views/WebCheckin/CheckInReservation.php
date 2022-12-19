<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="<?= base_url('assets') ?>/" data-template="vertical-menu-template">
<link rel="icon" type="image/x-icon" href="https://flexiguest.hitekservices.com/assets/img/favicon/favicon.ico" />
<head>
    <!-- <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar"></nav> -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <?= $this->include('Layout/HeaderScript') ?>
    <link rel="stylesheet" href="<?php echo base_url('assets/jquery.Jcrop.min.css'); ?>" />
    <style>
        /* Flexi Loder css */
#loader_flex_bg{
  width: 100%;
  height: 100%;
  background-color: rgba(83, 77, 77, 0.58);
  position: fixed;
  z-index: 2000;
}
#loader_flex_bg{
  display: none;
}
#loader_flex_bg .loader_flexi{
  position: fixed;
  left: 50%;
  top: 50%;
}
.loader_flexi  {
  animation: rotate 1s infinite;  
  height: 50px;
  width: 50px;
}
.loader_flexi:before,
.loader_flexi:after {   
  border-radius: 50%;
  content: '';
  display: block;
  height: 20px;  
  width: 20px;
}
.loader_flexi:before {
  animation: ball1 1s infinite;  
  background-color: #cb2025;
  box-shadow: 30px 0 0 #f8b334;
  margin-bottom: 10px;
}
.loader_flexi:after {
  animation: ball2 1s infinite; 
  background-color: #00a096;
  box-shadow: 30px 0 0 #97bf0d;
}

@keyframes rotate {
  0% { 
    -webkit-transform: rotate(0deg) scale(0.8); 
    -moz-transform: rotate(0deg) scale(0.8);
  }
  50% { 
    -webkit-transform: rotate(360deg) scale(1.2); 
    -moz-transform: rotate(360deg) scale(1.2);
  }
  100% { 
    -webkit-transform: rotate(720deg) scale(0.8); 
    -moz-transform: rotate(720deg) scale(0.8);
  }
}

@keyframes ball1 {
  0% {
    box-shadow: 30px 0 0 #f8b334;
  }
  50% {
    box-shadow: 0 0 0 #f8b334;
    margin-bottom: 0;
    -webkit-transform: translate(15px,15px);
    -moz-transform: translate(15px, 15px);
  }
  100% {
    box-shadow: 30px 0 0 #f8b334;
    margin-bottom: 10px;
  }
}

@keyframes ball2 {
  0% {
    box-shadow: 30px 0 0 #97bf0d;
  }
  50% {
    box-shadow: 0 0 0 #97bf0d;
    margin-top: -20px;
    -webkit-transform: translate(15px,15px);
    -moz-transform: translate(15px, 15px);
  }
  100% {
    box-shadow: 30px 0 0 #97bf0d;
    margin-top: 0;
  }
}

/* Flexi Loder css */
    </style>
</head>

<body class="flex_body">
<div id="loader_flex_bg"><div class="loader_flexi"></div></div>
<?= $this->include('Layout/image_modal') ?>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <div class="app-brand demo flexy_web_logo">
                <a href="<?php echo base_url('/') ?>" class="app-brand-link">                    
                    <img src="<?= $brandingLogo; ?>" width="190px;" />
                </a>

                <!-- <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                    <i class="bx menu-toggle-icon fs-4 d-none d-xl-block align-middle"></i>
                    <i class="bx bx-x bx-sm d-xl-none d-block align-middle"></i>
                </a> -->
            </div>
            <!-- <a class="navbar-brand" href="#">Navbar</a> -->
            <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span> -->
            <!-- </button> -->
        </div>
    </nav>

    <?= $this->include('Layout/ErrorReport') ?>
    <?= $this->include('Layout/SuccessReport') ?>

    <?php
    if (empty($condition)) {
        // $data = $data[0];

        if ($data['RESV_STATUS'] == 'Due Pre Check-In') {
            $statusClass = 'flxy_orng';
            // $icon = "fa-circle-xmark";
            // $documentmess = "Document not verified";
        } else {
            $statusClass = 'flxy_green';
            // $icon = "fa-circle-check";
            // $documentmess = "Document verified";
        }
    }

    $folderPath = base_url('assets/Uploads/');
    ?>

    <div class="container-fluid text-center flxy_content_flex">
        <div class="container-sm">
            <?php if (empty($condition)) { ?>
                <div class="row justify-content-center mb-4">
                    <h4 class="breadcrumb-wrapper py-3 mb-1 text-start">Reservation Detail</h4>
                    <div class="col-11 flxy_web_content">
                        <div class="flxy_wrapper">
                            <div class="flxy_web-header">
                                <p class="flxy_web_status <?php echo $statusClass; ?>"><?php echo $data['RESV_STATUS']; ?>
                                </p>
                            </div>

                            <div class="flxy_web-blockcont">

                                <!-- Reservation Details -->
                                <div class="sliderclass">
                                    <div class="flxy_block_card">
                                        <div class="card">
                                            <div class="row g-0">
                                                <div class="col-md-3 flxy_icon_mid">
                                                    <i class="fa-solid fa-r flxy_fa_x2"></i>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Reservation Number</h5>
                                                        <p class="card-text"><?php echo $data['RESV_NO']; ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card ">
                                            <div class="row g-0">
                                                <div class="col-md-3 flxy_icon_mid">
                                                    <i class="fa-solid fa-bed flxy_fa_x2"></i>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Apartment Number</h5>
                                                        <p class="card-text">
                                                            <?php echo ($data['RESV_ROOM'] == '' ? '&nbsp;' : $data['RESV_ROOM']); ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card ">
                                            <div class="row g-0">
                                                <div class="col-md-3 flxy_icon_mid">
                                                    <i class="fa-solid fa-plane-arrival flxy_fa_x2"></i>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Arrival</h5>
                                                        <p class="card-text"><?php echo $data['RESV_ARRIVAL_DT']; ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card ">
                                            <div class="row g-0">
                                                <div class="col-md-3 flxy_icon_mid">
                                                    <i class="fa-solid fa-cloud-moon flxy_fa_x2"></i>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Nights</h5>
                                                        <p class="card-text"><?php echo $data['RESV_NIGHT']; ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flxy_block_card">
                                        <div class="card ">
                                            <div class="row g-0">
                                                <div class="col-md-3 flxy_icon_mid">
                                                    <i class="fa-solid fa-person flxy_fa_x2"></i>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Guest Name</h5>
                                                        <p class="card-text"><?php echo $data['FULLNAME']; ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card ">
                                            <div class="row g-0">
                                                <div class="col-md-3 flxy_icon_mid">
                                                    <i class="fa-solid fa-building flxy_fa_x2"></i>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Apartment Detail</h5>
                                                        <p class="card-text"><?php echo $data['RM_TY_DESC']; ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card ">
                                            <div class="row g-0">
                                                <div class="col-md-3 flxy_icon_mid">
                                                    <i class="fa-solid fa-plane-departure flxy_fa_x2"></i>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Departure</h5>
                                                        <p class="card-text"><?php echo $data['RESV_DEPARTURE']; ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card ">
                                            <div class="row g-0">
                                                <div class="col-md-3 flxy_icon_mid">
                                                    <i class="fa-regular fa-user flxy_fa_x2"></i>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Adults/Children</h5>
                                                        <p class="card-text">
                                                            <?php echo $data['RESV_ADULTS'] . '/' . $data['RESV_CHILDREN']; ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <!-- Document Status -->
                                <div class="sliderclass">

                                    <div class="flxy_block_card flxy_document document-status-card customer-card-<?= $data['CUST_ID'] ?>">
                                        <div class="card">

                                            <div class="card-body flxy_web_padd">

                                                <h5 class="card-title d-flex justify-content-between">
                                                    <span><?= $data['FULLNAME'] ?></span>
                                                    <small>(Main)</small>
                                                </h5>

                                                <p class="card-text document-verified-status">
                                                </p>
                                            </div>

                                            <ul class="list-group list-group-flush flxy_web-ul">
                                                <li class="list-group-item text-flxy">
                                                    <div class="flxy-data">
                                                        Name
                                                    </div>

                                                    <div class="flxy-data">
                                                        : <?= $data['FULLNAME'] ?>
                                                    </div>
                                                </li>

                                                <li class="list-group-item text-flxy">
                                                    <div class="flxy-data">
                                                        Document Status
                                                    </div>

                                                    <div class="flxy-data">
                                                        : <span class="flxy_doc_prof flxy_doc_st <?= $statusClass ?>">Pending</span>
                                                    </div>
                                                </li>

                                                <li class="list-group-item text-flxy">
                                                    <div class="flxy-data">
                                                        Vaccine Certificate
                                                    </div>

                                                    <div class="flxy-data">
                                                        : <span class="flxy_doc_vacc flxy_doc_st <?= $statusClass ?>">Pending</span>
                                                    </div>
                                                </li>

                                                <li class="list-group-item d-grid ">
                                                    <button class="btn btn-dark " onClick="docUploadClik('D', <?= $data['CUST_ID'] ?>)" type="button">
                                                        View & Upload Documents
                                                    </button>
                                                </li>

                                                <li class="list-group-item d-grid">
                                                    <button class="btn btn-dark" onClick="docUploadClik('V', <?= $data['CUST_ID'] ?>)" type="button">
                                                        Vaccination Details
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Accompany Profiles -->
                                    <?php
                                    foreach ($data['ACCOMPANY_PROFILES'] as $accompany_profile) {
                                    ?>
                                        <div class="flxy_block_card flxy_document document-status-card customer-card-<?= $accompany_profile['CUST_ID'] ?>">
                                            <div class="card">

                                                <div class="card-body flxy_web_padd">

                                                    <h5 class="card-title d-flex justify-content-between">
                                                        <span><?= $accompany_profile['FULLNAME'] ?></span>
                                                        <small>(Accompany)</small>
                                                    </h5>

                                                    <p class="card-text document-verified-status">
                                                    </p>
                                                </div>

                                                <ul class="list-group list-group-flush flxy_web-ul">
                                                    <li class="list-group-item text-flxy">
                                                        <div class="flxy-data">
                                                            Name
                                                        </div>

                                                        <div class="flxy-data">
                                                            : <?= $accompany_profile['FULLNAME'] ?>
                                                        </div>
                                                    </li>

                                                    <li class="list-group-item text-flxy">
                                                        <div class="flxy-data">
                                                            Document Status
                                                        </div>

                                                        <div class="flxy-data">
                                                            : <span class="flxy_doc_prof flxy_doc_st <?= $statusClass ?>">Pending</span>
                                                        </div>
                                                    </li>

                                                    <li class="list-group-item text-flxy">
                                                        <div class="flxy-data">
                                                            Vaccine Certificate
                                                        </div>

                                                        <div class="flxy-data">
                                                            : <span class="flxy_doc_vacc flxy_doc_st <?= $statusClass ?>">Pending</span>
                                                        </div>
                                                    </li>

                                                    <li class="list-group-item d-grid ">
                                                        <button class="btn btn-dark " onClick="docUploadClik('D', <?= $accompany_profile['CUST_ID'] ?>)" type="button">
                                                            View & Upload Documents
                                                        </button>
                                                    </li>

                                                    <li class="list-group-item d-grid">
                                                        <button class="btn btn-dark" onClick="docUploadClik('V', <?= $accompany_profile['CUST_ID'] ?>)" type="button">
                                                            Vaccination Details
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>


                                </div>
                            </div>

                            <!-- Guest Profile & Document Details -->
                            <div class="flxy_doc_block flxy_none">
                                <div class="flxy_doc_content">
                                    <div class="flxy_block_card">
                                        <div class="contentDesc">
                                            <h4>Guest Profile & Document Details</h4>
                                            <ol>
                                                <li>Please upload the following Documents (file types: JPEG or PNG)</li>
                                                <li>Non Residents / UAE Nationals (Valid Passport)</li>
                                                <li>UAE Residents (EID both sides, make sure to scan the back side first)
                                                </li>
                                            </ol>
                                        </div>

                                        <div class="card">
                                            <div class="flxy_card_head">
                                                Document Images

                                                <?php
                                                if ($data['RESV_STATUS'] == 'Due Pre Check-In' || $data['RESV_STATUS'] == 'Pre Checked-In' || (isset($session->USR_ROLE_ID) && $session->USR_ROLE_ID == '1')) {
                                                ?>
                                                    <span class="deleteUploadImage">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </span>
                                                <?php
                                                }
                                                ?>
                                            </div>

                                            <img src="" id="cropped_img">
                                            <div class="card-body">
                                                <div class="flxy_image_show">
                                                    <ul id="listImagePresentDb"></ul>

                                                    <?php
                                                    if ($data['RESV_STATUS'] == 'Due Pre Check-In' || $data['RESV_STATUS'] == 'Pre Checked-In' || (isset($session->USR_ROLE_ID) && $session->USR_ROLE_ID == '1')) {
                                                    ?>
                                                        <input class="form-control" type="file" onchange="loadFile(event,this)" style="display:none" id="formFile">
                                                        <button type="button" onClick="browseFile()" class="btn btn-secondary flxy_brows btn-sm"><i class="fa-solid fa-upload"></i> Browse</button>
                                                    <?php
                                                    }
                                                    ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flxy_block_card flxy_cust_detil">
                                        <div class="row">
                                            <div class="col-12">
                                                <ul class="nav nav-tabs" id="myTab">
                                                    <li class="nav-item">
                                                        <a href="#profile" class="nav-link active" data-bs-toggle="tab">Profile Detail</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="#contact" class="nav-link" data-bs-toggle="tab">Contact
                                                            Detail</a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active p-3" id="profile">
                                                        <form id="documentDetailForm">
                                                            <input type="hidden" value="<?php echo $data['CUST_ID']; ?>" name="DOC_CUST_ID">
                                                            <input type="hidden" value="<?php echo $data['RESV_ID']; ?>" name="DOC_RESV_ID">
                                                            <input type="hidden" value="PROOF" name="DOC_FILE_TYPE">
                                                            <div class="row ">
                                                                <label for="CUST_TITLE" class="col-sm-3 col-form-label text-start">Title</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" value="<?php echo $data['CUST_TITLE']; ?>" class="form-control form-control-sm" id="CUST_TITLE" name="CUST_TITLE" placeholder="Title">
                                                                </div>
                                                            </div>
                                                            <div class="row ">
                                                                <label for="CUST_FIRST_NAME" class="col-sm-3 col-form-label text-start">First
                                                                    Name</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" value="<?php echo $data['CUST_FIRST_NAME']; ?>" class="form-control form-control-sm" id="CUST_FIRST_NAME" name="CUST_FIRST_NAME" placeholder="First Name">
                                                                </div>
                                                            </div>
                                                            <div class="row ">
                                                                <label for="CUST_LAST_NAME" class="col-sm-3 col-form-label text-start">Last
                                                                    Name</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" value="<?php echo $data['CUST_LAST_NAME']; ?>" class="form-control form-control-sm" id="CUST_LAST_NAME" name="CUST_LAST_NAME" name="CUST_LAST_NAME" placeholder="Last Name">
                                                                </div>
                                                            </div>
                                                            <div class="row ">
                                                                <label for="CUST_GENDER" class="col-sm-3 col-form-label text-start">Gender</label>
                                                                <div class="col-sm-9">
                                                                    <div class="form-check mb-2" style="float:left;margin-right:10px">
                                                                        <input type="radio" id="CUST_GENDER_Male" name="CUST_GENDER" value="Male" class="form-check-input" required="" <?php echo $data['CUST_GENDER'] == 'Male' ? 'checked' : ''; ?>>
                                                                        <label class="form-check-label" for="CUST_GENDER_Male">Male</label>
                                                                    </div>
                                                                    <div class="form-check" style="float:left">
                                                                        <input type="radio" id="CUST_GENDER_Female" name="CUST_GENDER" value="Female" class="form-check-input" required="" <?php echo $data['CUST_GENDER'] == 'Female' ? 'checked' : ''; ?>>
                                                                        <label class="form-check-label" for="CUST_GENDER_Female">Female</label>
                                                                    </div>

                                                                    <!-- <input type="text"
                                                                    value="<?php echo $data['CUST_GENDER']; ?>"
                                                                    class="form-control form-control-sm"
                                                                    id="CUST_GENDER" name="CUST_GENDER"
                                                                    name="CUST_GENDER" placeholder="Gender"> -->
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <label for="CUST_NATIONALITY" class="col-sm-3 col-form-label text-start">Nationality</label>
                                                                <div class="col-sm-9">
                                                                    <!-- <input type="text" value="<?php echo $data['CUST_NATIONALITY']; ?>" class="form-control form-control-sm" id="CUST_NATIONALITY" name="CUST_NATIONALITY" placeholder="Nationality"> -->

                                                                    <select name="CUST_NATIONALITY" id="CUST_NATIONALITY" data-width="100%" class="selectpicker CUST_NATIONALITY" data-live-search="true">
                                                                        <option value="">Select</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row ">
                                                                <label for="CUST_DOB" class="col-sm-3 col-form-label text-start">Date of
                                                                    Birth</label>
                                                                <div class="col-sm-9">
                                                                    <!-- <input type="text" value="<?php echo $data['CUST_DOB']; ?>"  class="form-control form-control-sm" id="CUST_DOB" name="CUST_DOB" placeholder="Date of Birth"> -->
                                                                    <div class="input-group mb-2">
                                                                        <input type="text" id="CUST_DOB" name="CUST_DOB" value="<?php echo $data['CUST_DOB']; ?>" class="form-control CUST_DOB form-control-sm" placeholder="DD-MM-YYYY">
                                                                        <span class="input-group-append">
                                                                            <span class="input-group-text bg-light d-block">
                                                                                <i class="fa fa-calendar"></i>
                                                                            </span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <label for="CUST_COUNTRY" class="col-sm-3 col-form-label text-start">Res
                                                                    Country</label>
                                                                <div class="col-sm-9">
                                                                    <!-- <input type="text" value="<?php echo $data['CUST_COUNTRY']; ?>"  class="form-control form-control-sm" id="CUST_COUNTRY" name="CUST_COUNTRY" placeholder="Res Country"> -->
                                                                    <select name="CUST_COUNTRY" id="CUST_COUNTRY" data-width="100%" class="selectpicker CUST_COUNTRY" data-live-search="true">
                                                                        <option value="">Select</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <label for="CUST_DOC_TYPE" class="col-sm-3 col-form-label text-start">Document
                                                                    Type</label>
                                                                <div class="col-sm-9">
                                                                    <!--
                                                                <input type="text"
                                                                    value="<?php echo $data['CUST_DOC_TYPE']; ?>"
                                                                    class="form-control form-control-sm"
                                                                    id="CUST_DOC_TYPE" name="CUST_DOC_TYPE"
                                                                    placeholder="Document Type"> -->
                                                                    <select name="CUST_DOC_TYPE" id="CUST_DOC_TYPE" data-width="100%" class="selectpicker CUST_DOC_TYPE" data-live-search="true">
                                                                        <option value="">Select</option>
                                                                        <?php
                                                                        if ($doc_types != NULL) {
                                                                            foreach ($doc_types as $doc_type) {
                                                                        ?> <option value="<?= $doc_type['label'] ?>">
                                                                                    <?= $doc_type['label'] ?></option>
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row ">
                                                                <label for="CUST_DOC_NUMBER" class="col-sm-3 col-form-label text-start">Doc
                                                                    Number</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" value="<?php echo $data['CUST_DOC_NUMBER']; ?>" class="form-control form-control-sm" id="CUST_DOC_NUMBER" name="CUST_DOC_NUMBER" placeholder="Doc Number">
                                                                </div>
                                                            </div>
                                                            <div class="row ">
                                                                <label for="CUST_DOC_ISSUE" class="col-sm-3 col-form-label text-start">Issue
                                                                    Date</label>
                                                                <div class="col-sm-9">
                                                                    <div class="input-group mb-2">
                                                                        <input type="text" id="CUST_DOC_ISSUE" name="CUST_DOC_ISSUE" value="<?php echo $data['CUST_DOC_ISSUE']; ?>" class="form-control CUST_DOC_ISSUE form-control-sm" placeholder="DD-MM-YYYY">
                                                                        <span class="input-group-append">
                                                                            <span class="input-group-text bg-light d-block">
                                                                                <i class="fa fa-calendar"></i>
                                                                            </span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row ">
                                                                <label for="CUST_PHONE" class="col-sm-3 col-form-label text-start">Phone</label>
                                                                <div class="col-sm-9">
                                                                    <input type="number" value="<?php echo $data['CUST_PHONE']; ?>" class="form-control form-control-sm" id="CUST_PHONE" name="CUST_PHONE" placeholder="Phone">
                                                                </div>
                                                            </div>
                                                            <div class="row ">
                                                                <label for="CUST_EMAIL" class="col-sm-3 col-form-label text-start">Email</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" value="<?php echo $data['CUST_EMAIL']; ?>" class="form-control form-control-sm" id="CUST_EMAIL" name="CUST_EMAIL" placeholder="Email">
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="tab-pane fade  p-3" id="contact">
                                                        <form id="documentDetailForm1">
                                                            <div class="row ">
                                                                <label for="CUST_ADDRESS_1" class="col-sm-3 col-form-label text-start">Address Line
                                                                    1</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" value="<?php echo $data['CUST_ADDRESS_1']; ?>" class="form-control form-control-sm" id="CUST_ADDRESS_1" name="CUST_ADDRESS_1" placeholder="Address Line 1">
                                                                </div>
                                                            </div>
                                                            <div class="row ">
                                                                <label for="CUST_ADDRESS_2" class="col-sm-3 col-form-label text-start">Address Line
                                                                    2</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" value="<?php echo $data['CUST_ADDRESS_2']; ?>" class="form-control form-control-sm" id="CUST_ADDRESS_2" name="CUST_ADDRESS_2" placeholder="Address Line 2">
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <label for="CUST_STATE" class="col-sm-3 col-form-label text-start">State
                                                                </label>
                                                                <div class="col-sm-9">
                                                                    <select name="CUST_STATE" id="CUST_STATE" data-width="100%" class="selectpicker CUST_STATE" data-live-search="true">
                                                                        <option value="<?php echo $data['CUST_STATE']; ?>">
                                                                            <?php echo $data['CUST_STATE_DESC']; ?></option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row ">
                                                                <label for="CUST_CITY" class="col-sm-3 col-form-label text-start">City</label>
                                                                <div class="col-sm-9">
                                                                    <select name="CUST_CITY" id="CUST_CITY" data-width="100%" class="selectpicker CUST_CITY" data-live-search="true">
                                                                        <option value="<?php echo $data['CUST_CITY']; ?>">
                                                                            <?php echo $data['CUST_CITY_DESC']; ?></option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Signature Page -->
                            <div class="flxy_signature_block">
                                <div class="singatureContent">

                                    <div class="guest-data">
                                        <div class="row">
                                            <div class="col-md-3 col-6"><b>Guest Name</b></div>
                                            <div class="col-md-3 col-6"><?= $data['FULLNAME'] ?></div>
                                            <div class="col-md-3 col-6"><b>Apartment Number</b></div>
                                            <div class="col-md-3 col-6"><?= $data['RESV_ROOM'] ?></div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3 col-6"><b>Reservation Number</b></div>
                                            <div class="col-md-3 col-6"><?= $data['RESV_NO'] ?></div>
                                            <div class="col-md-3 col-6"><b>Apartment Details</b></div>
                                            <div class="col-md-3 col-6"><?= $data['RM_TY_DESC'] ?></div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3 col-6"><b>Arrival</b></div>
                                            <div class="col-md-3 col-6"><?= $data['RESV_ARRIVAL_DT'] ?></div>
                                            <div class="col-md-3 col-6"><b>Phone</b></div>
                                            <div class="col-md-3 col-6"><?= $data['CUST_PHONE'] ?></div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3 col-6"><b>Departure</b></div>
                                            <div class="col-md-3 col-6"><?= $data['RESV_DEPARTURE'] ?></div>
                                            <div class="col-md-3 col-6"><b>Email</b></div>
                                            <div class="col-md-3 col-6"><?= $data['CUST_EMAIL'] ?></div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3 col-6"><b>Adult/Children</b></div>
                                            <div class="col-md-3 col-6"><?= $data['RESV_ADULTS'] . '/' . $data['RESV_CHILDREN'] ?></div>
                                            <div class="col-md-3 col-6"><b>Nationality</b></div>
                                            <div class="col-md-3 col-6"><?= $data['CUST_NATIONALITY'] ?></div>
                                        </div>
                                        <?php
                                            if ($data['RESV_STATUS'] == 'Due Pre Check-In' || $data['RESV_STATUS'] == 'Pre Checked-In' || (isset($session->USR_ROLE_ID) && $session->USR_ROLE_ID == '1')) {}else{
                                            ?>
                                        <div class="row">
                                            <div class="col-md-4 text-start">
                                                <label class="form-label">Please update your expected time of arrival</label>
                                                <input type="time" value="<?php echo $data['RESV_ETA']; ?>" name="RESV_ETA" id="RESV_ETA" class="form-control" placeholder="estime Time" />
                                            </div>

                                            <div class="col-md-4"></div>
                                            <div class="col-md-4"></div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <h4 class="text-start">Terms and Conditions</h4>
                                <p class="text-align:right;">
                                <ol class="text-start">
                                    <li>Property facilities such as swimming pool and gym are available for guests and may
                                        be used at your own risk. Hotel will not be liable for any injury as well as any
                                        lost or stolen personal belongings resulting from usage of these facilities. Any
                                        damages to the facilities and/or its equipment will result to charges.</li>
                                    <li>
                                        In-room safes are provided for the security of your valuables. Hotel will not be
                                        responsible for any lost, stolen or damaged personal items. Hotel is not responsible
                                        for any items left in your room or private vehicle.
                                    </li>
                                    <li>
                                        My signature on this registration card is an authorization to use my credit card for
                                        any unpaid bills. I accept full liability for any bills associated during my stay
                                        and I agree to be held personally liable in the event that the indicated
                                        person/company or third party fails to pay for any part of the charges or full
                                        amount.
                                    <li>
                                        Early Departures: All Reservations are confirmed and charged in full for the entire
                                        duration of stay. No refunds will apply in the event of Early Departures.
                                    </li>
                                    <li>
                                        I agree that I will be liable to pay for any damages or loss in the apartment
                                        assigned to me for the entire stay including apartment keys, furnishings, fixtures
                                        and equipment. All related repairs and replacements required will be subject to
                                        evaluation and/or assessment of the damage or loss.
                                    </li>
                                    <li>
                                        All rooms and units are strictly Non-smoking, I fully understand that any violation
                                        of the non-smoking policy will result to a penalty subject to management discretion.
                                    </li>
                                    <li>
                                        I hereby accept that any items thrown off the balcony of my occupied room or
                                        apartment will result in immediate eviction. I agree to supervise all children
                                        (below 16 years and under) on the balcony at all times and to ensure that balcony
                                        and windows are locked before exiting the room or apartment.
                                    </li>
                                    <li>
                                        Acknowledge that I have access to the Hotel Handbook in the Hotel App which contains
                                        a guide to Hotel and the Community Rules.
                                    </li>
                                    <li>
                                        I hereby agree that staff from Hotel housekeeping, maintenance and associated teams
                                        can enter my apartment for cleaning services or to attend to any maintenance issues.
                                        Although we endeavor to notify residents in advance, direct access may be required
                                        in an emergency situation.
                                    </li>
                                    <li>
                                        Housekeeping cleaning services and linen change will be offered once per week at a
                                        preset date & time frame.
                                    </li>
                                </ol>
                                </p>

                                <div class="form-check" style="display: flex;">
                                    <input class="form-check-input" type="checkbox" <?php echo (trim($data['RESV_ACCP_TRM_CONDI']) == 'Y' ? 'checked' : ''); ?> id="agreeTerms">
                                    <label class="form-check-label" for="flexCheckDefault"> 
                                       &nbsp;&nbsp; I accept the terms and conditions
                                    </label>
                                </div>

                                <div class="row" style="justify-content: right;padding: 48px;">
                                    <div class="col-md-3">
                                        <button type="button" <?php echo $data['DOC_FILE_PATH'] != '' ? 'style="display:none;"' : ''; ?> id="clickSignature" class="btn btn-secondary">Click to sign here</button>

                                        <img id="captureSignature" class="<?= $data['DOC_FILE_PATH'] ? '' : 'd-none' ?>" style="width:100%;" src="<?= $data['DOC_FILE_PATH'] ? base_url('assets/Uploads/UserDocuments/signature/' . $data['DOC_FILE_PATH']) : '' ?>" />
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="flxy_web-footer text-end">
                            <button type="button" onClick="sliderWebWid('P')" class="btn btn-blue btn-primary"><i class="fa-solid fa-chevron-left"></i> Back</button>
                            <button type="button" onClick="sliderWebWid('N')" class="btn continueDefult btn-blue btn-primary">Continue <i class="fa-solid fa-chevron-right"></i></button>
                            <?php
                            if ($data['RESV_STATUS'] == 'Due Pre Check-In' || (isset($session->USR_ROLE_ID) && $session->USR_ROLE_ID == '1' && $data['RESV_STATUS'] == 'Pre Checked-In')) {
                            ?>
                                <button type="button" onClick="updateSignature('<?= $data['RESV_STATUS'] ?>')" class="btn btn-success updateSignature signHideClass">Continue <i class="fa-solid fa-chevron-right"></i></button>
                            <?php
                            }
                            if ($data['RESV_STATUS'] == 'Due Pre Check-In' || $data['RESV_STATUS'] == 'Pre Checked-In' || (isset($session->USR_ROLE_ID) && $session->USR_ROLE_ID == '1')) {
                            ?>
                                <button type="button" onClick="updateCustomer()" class="btn saveContinue btn-success hideSaveCont">Save & Continue <i class="fa-solid fa-chevron-right"></i></button>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
        </div>
    <?php } else { ?>
        <div class="row mt-4">
            <div class="col-11">
                <p style="font-size: 40px;color: green;"><i class="fa-solid fa-circle-check"></i></p>

                <?php
                if (isset($session->USR_ROLE_ID) && $session->USR_ROLE_ID == '1') {
                ?>
                    <h2>You have successfully completed Check-In Process.</h2>
                <?php
                } else {
                ?>
                    <h2>You have successfully completed your Pre-Arrival Check-in Process</h2>
                    <h5 style="font-weight: inherit;">We look forward to welcoming you soon. A confirmation email with a
                        unique QR code has been sent to your registered email address. Please check your inbox or Spam
                        folder.</h5>
                <?php
                }
                ?>
            </div>
        </div>
    <?php } ?>
    </div>
    </div>

    <footer class="container-fluid text-center mt-auto">
        <div class="container-fluid d-flex flex-column flex-md-row flex-wrap justify-content-between py-2">
            <div class="mb-2 mb-md-0">
                ©<?= date('Y') ?>, Copyrights. All Rights Reserved by
                <a href="https://www.hitekservices.com" target="_blank" class="footer-link fw-semibold">HITEK</a>.
            </div>
        </div>
    </footer>

    <?= $this->include('Layout/FooterScript') ?>
</body>

</html>
<?php if (empty($condition)) { ?>
    <div class="modal fade" id="imageCropping" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" style="min-width:600px!important;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageCroppingWindowLable">Cropping Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="">
                        <img src="" class="card-img-top cropboxclass" id="croppingbox">
                    </div>
                </div>
                <div class="modal-footer flxy_modClass">
                    <span class="btnLeft">
                        <button type="button" id="clickCropping" class="btn btn-primary btn-sm"><i class="fa-solid fa-crop"></i></button>
                        <button type="button" id="croping" class="btn btn-primary btn-sm"><i class="fa-solid fa-check"></i>
                            Crop</button>
                    </span>
                    <span class="btnRight">
                        <button type="button" id="saveCropping" class="btn btn-success btn-sm"><i class="fa-solid fa-floppy-disk"></i> Save</button>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Vaccine Details -->
    <div class="modal fade" id="vaccineModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header flxy_padding">
                    <h5 class="modal-title" id="rateQueryWindowLable">Vaccine Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>
                <div id="userInfoDate"></div>
                <div class="modal-body flxy_padding">
                    <form id="vaccineForm">
                        <input type="hidden" value="<?php echo $data['CUST_ID']; ?>" name="VACC_CUST_ID">

                        <div class="row ">
                            <label for="inputPassword" class="col-sm-4 col-form-label">Reservation Number</label>
                            <div class="col-sm-8">
                                <input type="text" readonly value="<?php echo $data['RESV_ID']; ?>" class="form-control form-control-sm" name="VACC_RESV_ID" placeholder="Phone">
                            </div>
                        </div>

                        <div class="row ">
                            <label for="inputPassword" class="col-sm-4 col-form-label">Booking Profile</label>
                            <div class="col-sm-8">
                                <input type="text" readonly value="<?php echo $data['FULLNAME']; ?>" class="form-control form-control-sm" name="FULLNAME" placeholder="Phone">
                            </div>
                        </div>

                        <div class="row ">
                            <label for="inputPassword" class="col-sm-4 col-form-label">vaccine Detail</label>

                            <div class="col-sm-8">
                                <input class="form-check-input" type="hidden" value="vaccinated" name="VACC_DETAILS" id="VACC_DETAILS">

                                <div class="form-check">
                                    <input class="form-check-input radioCheck" type="radio" id="VACC_DETL_1" name="VACC_DETL" method="vaccinated">
                                    <label class="form-check-label" for="VACC_DETL_1">
                                        I have been fully vaccinated (Please attach the Vaccination certificate)
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input radioCheck" type="radio" id="VACC_DETL_2" name="VACC_DETL" method="medicallyExempt">
                                    <label class="form-check-label" for="VACC_DETL_2">
                                        I am medically exempt from taking any Covid19 Vaccine and I have a certified
                                        exemption certification (Please attach Official Medical Exemption certificate)
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input radioCheck" type="radio" id="VACC_DETL_3" name="VACC_DETL" method="vaccinationLater">
                                    <label class="form-check-label" for="VACC_DETL_3">
                                        I will be completing my vaccine in Dubai before October 1st and will provide my
                                        vaccination certificate prior to October 1st to continue staying
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row ">
                            <label class="col-sm-4 col-form-label">Last Vaccine Date</label>

                            <div class="col-sm-8">
                                <div class="input-group mb-2">
                                    <input type="text" id="VACC_LAST_DT" name="VACC_LAST_DT" class="form-control VACC_LAST_DT form-control-sm" placeholder="DD-MM-YYYY">
                                    <span class="input-group-append">
                                        <span class="input-group-text bg-light d-block">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-sm-4 col-form-label">Vaccine Name</label>
                            <div class="col-sm-8">
                                <!--<input type="text" class="form-control form-control-sm" id="VACC_NAME" name="VACC_NAME"
                                placeholder="Vaccine Name">-->
                                <select name="VACC_TYPE" id="VACC_TYPE" data-width="100%" class="selectpicker VACC_TYPE" data-live-search="true">
                                    <option value="">Select</option>
                                    <?php if ($vacc_types != NULL) {
                                        foreach ($vacc_types as $vacc_type) {
                                    ?> <option value="<?= $vacc_type['id'] ?>"><?= $vacc_type['label'] ?></option>
                                    <?php   }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row ">
                            <input type="hidden" class="form-control form-control-sm" id="VACC_DOC_SAVED" name="VACC_DOC_SAVED">
                            <label class="col-sm-4 col-form-label">Vaccine Certificate</label>
                            <div class="col-sm-8">
                                <input class="form-control form-control-sm" id="fileUpload" name="files[]" multiple onChange="uploadVaccine(this)" type="file">
                            </div>

                            <div class="col-sm-4"></div>
                            <div class="col-sm-8 text-center previewClass" style="flex-wrap: wrap">
                            </div>
                        </div>
                        <div class="row ">
                            <label class="col-sm-4 col-form-label">Certificate Issue Country</label>
                            <div class="col-sm-8">
                                <select name="VACC_ISSUED_COUNTRY" id="VACC_ISSUED_COUNTRY" data-width="100%" class="selectpicker VACC_ISSUED_COUNTRY" data-live-search="true">
                                    <option value="">Select</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>

                <?php
                if ($data['RESV_STATUS'] == 'Due Pre Check-In' || $data['RESV_STATUS'] == 'Pre Checked-In' || (isset($session->USR_ROLE_ID) && $session->USR_ROLE_ID == '1')) {
                ?>
                    <div class="modal-footer flxy_modClass">
                        <button type="button" id="updateVaccine" class="btn btn-primary btn-sm"><i class="fa-solid fa-check"></i> Update</button>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Upload Signature -->
    <div class="modal fade" id="signaturWindow" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rateQueryWindowLable">Signature Here</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div id="signature">
                            <canvas id="signature-pad" class="signature-pad" width="460px" height="200px"></canvas>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="clearButton" class="btn btn-secondary">Clear</button>
                    <button type="button" id="submitSignatureImage" class="btn btn-secondary">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- CheckIn Confirmation -->
    <div class="modal fade" id="checkInConfirmWindow" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rateQueryWindowLable">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" onClick="confirmPrecheckin()" type="button">
                                <?= (isset($session->USR_ROLE_ID) && $session->USR_ROLE_ID == '1')  ? 'Check-In Now' : 'Pre Check-In Now' ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php } ?>

<link href="<?php //echo base_url('assets/signature/jquery.signature.css'); 
            ?>" rel="stylesheet">
<script src="<?php echo base_url('assets/signature/signature_pad.js'); ?>"></script>
<script src="<?php //echo base_url('assets/signature/jquery.signature.js'); 
                ?>"></script>
<script src="<?php //echo base_url('assets/signature/jquery.ui.touch-punch.js'); 
                ?>"></script>
<style>
    #signature {
        width: 100%;
        height: auto;
    }
</style>
<script>
    var condi = '<?php echo $condition; ?>';
    if (condi == '') {
        var resrid = '<?php echo $data['RESV_ID']; ?>';
        var custid = '<?php echo $data['CUST_ID']; ?>';
        var nationalityCode = '<?php echo $data['CUST_NATIONALITY']; ?>';
        var countryCode = '<?php echo $data['CUST_COUNTRY']; ?>';
        var statecode = '<?php echo $data['CUST_STATE']; ?>';
    }

    $(document).ready(function() {
        if (localStorage.getItem('customer_updated')) {
            $('.sliderclass:eq(1)').addClass('activeslide');
            localStorage.removeItem('customer_updated');
        } else {
            $('.sliderclass:eq(0)').addClass('activeslide');
        }

        $('#cropped_img').hide();

        $('.flxy_signature_block').hide();
        $('.continueDefult').show();
        $('.saveContinue').addClass('hideSaveCont');
        checkStatusUploadFiles();

        $('.CUST_DOB,.VACC_LAST_DT,.CUST_DOC_ISSUE').datepicker({
            format: 'd-M-yyyy',
            autoclose: true,
        });
    });

    var size;
    var docuClick = '';
    var farwrdClick = 0;

    function sliderWebWid(param) {
        $('.updateSignature').addClass('signHideClass');
        checkStatusUploadFiles();

        if (docuClick == 'PROOF') {
            docuClick = '';
            $('.sliderclass:eq(1)').addClass('activeslide');
            $('.flxy_web-blockcont').removeClass('flxy_none');
            $('.flxy_doc_block').addClass('flxy_none');
            $('.continueDefult').show();
            $('.saveContinue').addClass('hideSaveCont');
            return false;
        }

        if (param == 'N') {
            var length = $('.sliderclass.activeslide').next('.sliderclass').length;
            if (length == 0) {
                // if ($('.flxy_doc_prof:contains(Pending)').length && $('.flxy_doc_vacc:contains(Pending)').length) {
                if ($('.flxy_doc_prof:contains(Pending)').length) {
                    // alert('Please upload required Guest details, Identity and Vaccine documents.');
                    alert('Please upload required Guest details and Identity documents.');
                    return false;
                }

                if ($('.flxy_doc_prof:contains(Pending)').length) {
                    alert('Please upload required Guest details & Identity documents.');
                    return false;
                }

                // if ($('.flxy_doc_vacc:contains(Pending)').length) {
                //     alert('Please upload required Vaccine documents.');
                //     return false;
                // }

                <?php
                if (isset($session->USR_ROLE_ID) && $session->USR_ROLE_ID == '1') {
                ?>
                    if ($('.document-verified-status:contains(Document not verified)').length) {
                        alert('All Documents are not verified.');
                        return false;
                    }
                <?php
                }
                ?>

                $('.updateSignature').removeClass('signHideClass');
                $('.continueDefult').hide();
                continueToTaskSlide();
                farwrdClick = 1;
            }

            <?php
            if (empty($condition) && $data['RESV_STATUS'] != 'Due Pre Check-In' && (empty($session->USR_ROLE_ID) || $session->USR_ROLE_ID != '1')) {
            ?>
                $('.continueDefult').hide();
            <?php
            }
            ?>
            $('.sliderclass.activeslide').removeClass('activeslide').next().addClass('activeslide');
        } else {
            $('.flxy_web-header,.flxy_web-blockcont').show();
            $('.flxy_signature_block').hide();
            if ($('.sliderclass.activeslide').prev().length > 0) {
                $('.sliderclass.activeslide').removeClass('activeslide').prev().addClass('activeslide');
            }
            if (farwrdClick == 1) {
                $('.continueDefult').show();
                $('.updateSignature').addClass('signHideClass');
                previewSlideClick(farwrdClick);
                farwrdClick = 0;
            }
        }
    }

    function continueToTaskSlide() {
        $('.flxy_web-header,.flxy_web-blockcont').hide();
        $('.flxy_signature_block').show();
    }

    function previewSlideClick() {
        $('.sliderclass:eq(1)').addClass('activeslide');
        $('.flxy_web-header,.flxy_web-blockcont').show();
        $('.flxy_signature_block').hide();
    }

    function docUploadClik(param, customer_id) {
        runCountryList();
        // var DOC_CUST_ID = $('[name="DOC_CUST_ID"]').val();
        var DOC_CUST_ID = customer_id;
        var DOC_RESV_ID = $('[name="DOC_RESV_ID"]').val();

        let data = <?= json_encode($data) ?>;
        if (data['CUST_ID'] == DOC_CUST_ID)
            customer_data = data;
        else {
            for (let accompany_profile of data['ACCOMPANY_PROFILES']) {
                if (accompany_profile['CUST_ID'] == DOC_CUST_ID)
                    customer_data = accompany_profile;
            }
        }
        //console.log(customer_data);

        if (param == 'D') {
            docuClick = 'PROOF';
            $('.continueDefult').hide();
            $('.saveContinue').removeClass('hideSaveCont');
            $('.sliderclass').removeClass('activeslide');
            $('.flxy_doc_block').removeClass('flxy_none');
            $('.flxy_web-blockcont').addClass('flxy_none');

            let id = '#documentDetailForm';
            $(`${id} input[name='DOC_CUST_ID']`).val(customer_data.CUST_ID);
            $(`${id} input[name='CUST_TITLE']`).val(customer_data.CUST_TITLE);
            $(`${id} input[name='CUST_FIRST_NAME']`).val(customer_data.CUST_FIRST_NAME);
            $(`${id} input[name='CUST_LAST_NAME']`).val(customer_data.CUST_LAST_NAME);
            $(`${id} input[name='CUST_GENDER'][value=` + customer_data.CUST_GENDER + `]`).prop('checked', true);
            $(`${id} input[name='CUST_NATIONALITY']`).val(customer_data.CUST_NATIONALITY).trigger('change');
            $(`${id} input[name='CUST_DOB']`).val(customer_data.CUST_DOB);
            $(`${id} select[name='CUST_COUNTRY']`).val(customer_data.CUST_COUNTRY).selectpicker('refresh').trigger(
                'change');
            $(`${id} select[name='CUST_DOC_TYPE']`).val(customer_data.CUST_DOC_TYPE).selectpicker('refresh').trigger(
                'change');
            $(`${id} input[name='CUST_DOC_NUMBER']`).val(customer_data.CUST_DOC_NUMBER);
            $(`${id} input[name='CUST_DOC_ISSUE']`).val(customer_data.CUST_DOC_ISSUE);
            $(`${id} input[name='CUST_PHONE']`).val(customer_data.CUST_PHONE);
            $(`${id} input[name='CUST_EMAIL']`).val(customer_data.CUST_EMAIL);

            id = '#documentDetailForm1';
            $(`${id} input[name='CUST_ADDRESS_1']`).val(customer_data.CUST_ADDRESS_1);
            $(`${id} input[name='CUST_ADDRESS_2']`).val(customer_data.CUST_ADDRESS_2);

            setTimeout(() => {
                $(`${id} select[name='CUST_STATE']`).val(customer_data.CUST_STATE).selectpicker('refresh').trigger(
                    'change');

                setTimeout(() => {
                    $(`${id} select[name='CUST_CITY']`).val(customer_data.CUST_CITY).selectpicker(
                        'refresh');
                }, 20000);
            }, 500);

            $.ajax({
                url: '<?php echo base_url('/getActiveUploadImages') ?>',
                type: "post",
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                data: {
                    DOC_CUST_ID: DOC_CUST_ID,
                    DOC_RESV_ID: DOC_RESV_ID
                },
                dataType: 'json',
                success: function(respn) {

                    if (respn.length) {
                        listImage = respn[0]['DOC_FILE_PATH'].split(',');
                        listImageArr = [];
                        for (let img of listImage) {
                            listImageArr.push({
                                'DOC_FILE_PATH': img,
                                'DOC_ID': respn[0]['DOC_ID']
                            });
                        }

                        generaListImage(listImageArr);
                    } else
                        $('#listImagePresentDb').html('');
                }
            });
        } else {
            $('#fileUpload').val('');
            $.ajax({
                url: '<?php echo base_url('/getVaccinUploadImages') ?>',
                type: "post",
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                data: {
                    VACC_CUST_ID: DOC_CUST_ID,
                    VACC_RESV_ID: DOC_RESV_ID
                },
                dataType: 'json',
                success: function(respn) {
                    let vaccForm = '#vaccineForm';

                    if (respn.customer_details.length) {
                        let customer_details = respn.customer_details[0];
                        $(`${vaccForm} input[name="VACC_CUST_ID"]`).val(customer_details.CUST_ID);
                        $(`${vaccForm} input[name="FULLNAME"]`).val(customer_details.FULLNAME);

                    }

                    // reset form
                    $('.radioCheck:eq(0)').prop('checked', true);
                    $('#VACC_LAST_DT').val('');
                    $('#VACC_TYPE').val('').selectpicker('refresh');
                    $('#VACC_ISSUED_COUNTRY').val('').selectpicker('refresh');
                    $('.previewClass').html('');
                    $('#VACC_DOC_SAVED').val('');

                    if (respn.vacc_details.length) {
                        $('*#vaccinePreview').remove();
                        var jsonFrmt = respn.vacc_details[0];
                        var check = jsonFrmt.VACC_DETAILS;

                        if (check == 'vaccinated') {
                            $('#VACC_DETAILS').val(check);
                            $('.radioCheck:eq(0)').prop('checked', true);
                        } else if (check == 'medicallyExempt') {
                            $('#VACC_DETAILS').val(check);
                            $('.radioCheck:eq(1)').prop('checked', true);
                        } else if (check == 'vaccinationLater') {
                            $('#VACC_DETAILS').val(check);
                            $('.radioCheck:eq(2)').prop('checked', true);
                        }
                        
                        

                        $('#VACC_LAST_DT').val(jsonFrmt.VACC_LAST_DT);
                        $('#VACC_TYPE').val(jsonFrmt.VACC_TYPE).selectpicker(
                            'refresh');
                        $('#VACC_ISSUED_COUNTRY').val($.trim(jsonFrmt.VACC_ISSUED_COUNTRY)).selectpicker(
                            'refresh');
                        var filePath = jsonFrmt.VACC_FILE_PATH;
                        if (filePath != '') {
                            var arrayPath = filePath.split(",");
                            $.each(arrayPath, function(i) {
                                if (arrayPath[i].includes('.jpg') || arrayPath[i].includes('.jpeg') || arrayPath[i].includes('.png')) {
                                    $('.previewClass').append(`
                                    <span id="vaccinePreview">
                                        <span id="${arrayPath[i]}" class="vaccdelete">
                                            <i class="fa-solid fa-xmark"></i>
                                        </span>
                                        <img src="<?= $folderPath . '/UserDocuments/vaccination/' ?>${arrayPath[i]}" 
                                        onClick="displayImagePopup('<?= $folderPath . '/UserDocuments/vaccination/' ?>${arrayPath[i]}')" id="">
                                    </span>`);
                                } else {
                                    $('.previewClass').append(`
                                    <span id="vaccinePreview">
                                        <span id="${arrayPath[i]}" class="vaccdelete">
                                            <i class="fa-solid fa-xmark"></i>
                                        </span>

                                        <a href="<?= $folderPath . '/UserDocuments/vaccination/' ?>${arrayPath[i]}" target="_blank">
                                            <img src="<?= base_url('assets/img/icons/misc/pdf.png') ?>"/>
                                        </a>
                                    </span>`);
                                }
                            });
                        }
                        $('#VACC_DOC_SAVED').val(filePath);
                    }
                }
            });

            $('#vaccineModal').modal('show');
        }
    }

    function browseFile() {
        $('#formFile').trigger('click');
    }

    var jcrop_api = '';

    function action() {
        jcrop_api = $.Jcrop('#croppingbox', {
            aspectRatio: 1,
            boxWidth: 550,
            boxHeight: 400,
            onSelect: function(c) {
                size = {
                    x: c.x,
                    y: c.y,
                    w: c.w,
                    h: c.h
                };
            },
        }, function() {
            jcrop_api = this;
        });
    }

    var cropPathImage = '';
    $(document).on('click', '#clickCropping', function() {
        $(this).addClass('active');
        $('#croping').show();
        action();
        jcrop_api.setImage('' + cropPathImage + '');
    });

    function loadFile(event) {
        $('#croping').hide();
        var file = event.target.files[0];
        $('#imageCropping').modal('show');

        var formData = new FormData();
        formData.append('file', file); //append file to formData object

        $.ajax({
            url: "<?php echo base_url('/imageUpload') ?>",
            type: "POST",
            data: formData,
            processData: false, //prevent jQuery from converting your FormData into a string
            contentType: false, //jQuery does not add a Content-Type header for you
            success: function(respn) {

                var imagePath = "<?php echo $folderPath; ?>/UserDocuments/proof";

                if (jcrop_api != '') {
                    jcrop_api.destroy();
                    jcrop_api.setImage(imagePath + '/' + respn);
                }
                $('#croppingbox').attr('src', imagePath + '/' + respn);
                cropPathImage = imagePath + '/' + respn;
            }
        });
    }

    $(document).on('click', '#croping,#saveCropping', function() {
        var img = $("#croppingbox").attr('src');
        var formSerialization = $('#documentDetailForm').serializeArray();
        if ($('#clickCropping').hasClass('active')) {
            formSerialization.push({
                name: 'x',
                value: Math.round(size.x)
            }, {
                name: 'y',
                value: Math.round(size.y)
            }, {
                name: 'w',
                value: Math.round(size.w)
            }, {
                name: 'h',
                value: Math.round(size.h)
            }, {
                name: 'img',
                value: img
            }, {
                name: 'mode',
                value: 'C'
            });
        } else {
            formSerialization.push({
                name: 'img',
                value: img
            }, {
                name: 'mode',
                value: 'N'
            });
        }
        $('#imageCropping').modal('hide');
        $.ajax({
            url: '<?php echo base_url('/croppingImage') ?>',
            type: "post",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: formSerialization,
            dataType: 'json',
            success: function(respn) {
                if (respn.SUCCESS == 1) {
                    listImage = respn['RESPONSE']['OUTPUT']['DOC_FILE_PATH'].split(',');
                    listImageArr = [];
                    for (let img of listImage) {
                        listImageArr.push({
                            'DOC_FILE_PATH': img,
                            'DOC_ID': respn['RESPONSE']['OUTPUT']['DOC_ID']
                        });
                    }
                    // var image = respn['RESPONSE']['OUTPUT']['IMAGEPATH'];
                    // $("#cropped_img").attr('src', '<?php echo $folderPath; ?>' + '/UserDocuments/proof/' + image);

                    $('#clickCropping').removeClass('active');
                    // var listImage = respn['RESPONSE']['OUTPUT'];
                    generaListImage(listImageArr);
                    checkStatusUploadFiles();
                } else {
                    $('#errorModal').show();
                    var ERROR = respn['RESPONSE']['ERROR'];
                    var error = '<ul>';
                    $.each(ERROR, function(ind, data) {

                        error += '<li>' + data + '</li>';
                    });
                    error += '<ul>';
                    $('#formErrorMessage').html(error);
                }
            }
        });
    });

    $(document).on('click', '.linkImg', function() {
        $('.activeLink').removeClass('activeLink');
        $(this).addClass('activeLink');
        var linkTag = $(this).find('img').attr('src');
        $("#cropped_img").attr('src', linkTag);
    });

    function generaListImage(listImage) {
        var liList = '';
        $.each(listImage, function(inx, data) {
            if ($.isNumeric(inx)) {
                inx == 0 ? $("#cropped_img").attr('src', '<?php echo $folderPath; ?>' + '/UserDocuments/proof/' +
                    data['DOC_FILE_PATH']) : '';
                var activeClass = (inx == 0 ? 'activeLink linkImg' : 'linkImg');
                liList += ` <li data-file_name="${data['DOC_FILE_PATH']}" data_id="${data['DOC_ID']}" class="${activeClass}">
                                <img id="imagesmall" class="card-img-top" 
                                    onClick="displayImagePopup('<?= $folderPath . '/UserDocuments/proof/' ?>${data['DOC_FILE_PATH']}')"
                                    src="<?= $folderPath . '/UserDocuments/proof/' ?>${data['DOC_FILE_PATH']}">
                            </li>`;
            }
        });
        $('#listImagePresentDb').html(liList);
    }

    $(document).on('click', '.deleteUploadImage', function() {
        var sysid = $('.activeLink').attr('data_id');
        var file_name = $('.activeLink').attr('data-file_name');

        if (!sysid) {
            alert('please select an image to delete');
            return false;
        }

        $.ajax({
            url: '<?php echo base_url('/deleteUploadImages') ?>',
            type: "post",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                sysid: sysid,
                file_name: file_name
            },
            dataType: 'json',
            success: function(respn) {
                var element = $('.activeLink');
                // element.next().addClass('activeLink');
                element.remove();
                $('.linkImg').last().addClass('activeLink');
            }
        });
    });

    function runCountryList() {
        $.ajax({
            url: '<?php echo base_url('/countryList') ?>',
            type: "post",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            // dataType:'json',
            async: false,
            success: function(respn) {
                $('#CUST_NATIONALITY').html(respn);
                $('#CUST_NATIONALITY').val(nationalityCode).selectpicker('refresh');

                $('#CUST_COUNTRY,#VACC_ISSUED_COUNTRY').html(respn).selectpicker('refresh');
                $('#CUST_COUNTRY').val(countryCode).selectpicker('refresh');
            }
        });
    }
    $(document).on('change', '#CUST_COUNTRY', function() {
        var ccode = $(this).val();
        $.ajax({
            url: '<?php echo base_url('/stateList') ?>',
            type: "post",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                ccode: ccode
            },
            // dataType:'json',
            success: function(respn) {
                $('#CUST_STATE').html(respn).selectpicker('refresh');
            }
        });
    });
    $(document).on('change', '#CUST_STATE', function() {
        var scode = $(this).val();
        var ccode = $('#CUST_COUNTRY').find('option:selected').val();
        $.ajax({
            url: '<?php echo base_url('/cityList') ?>',
            type: "post",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                ccode: ccode,
                scode: scode
            },
            // dataType:'json',
            success: function(respn) {
                $('*#CUST_CITY').html(respn).selectpicker('refresh');
            }
        });
    });

    $(document).on('change', '#CUST_COUNTRY', function() {
        $('#CUST_CITY').html('<option value="">Select</option>').selectpicker('refresh');
    });

    $(document).on('click', '#updateVaccine', function() {
        var formData = new FormData($('#vaccineForm')[0]);

        formData.append('DELETEIMAGE', formImageDeletArr);
        formData.append('VACC_NAME', $("#VACC_TYPE option:selected").text());

        formData.delete('files[]');

        let files = $(`#vaccineForm input[name='files[]']`)[0].files;
        for (let i = 0; i < files.length; i++)
            formData.append('files[]', files[i]);

        $.ajax({
            url: '<?php echo base_url('/updateVaccineReport') ?>',
            type: "post",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: formData,
            processData: false, //prevent jQuery from converting your FormData into a string
            contentType: false, //jQuery does not add a Content-Type header for you
            dataType: 'json',
            success: function(respn) {

                if (respn.SUCCESS != '1') {
                    // $('#errorModal').show();
                    var ERROR = respn['RESPONSE']['ERROR'];
                    var error = '';
                    $.each(ERROR, function(ind, data) {
                        error += '<li>' + data + '</li>';
                    });
                    showModalAlert('error', error);
                    // $('#formErrorMessage').html(error);
                } else {
                    showModalAlert('success', respn['RESPONSE']['ERROR']);

                    formImageDeletArr = []; // reset array after updating
                    checkStatusUploadFiles();
                    $('#vaccineModal').modal('hide');
                }
            }
        });
    });

    function updateCustomer() {
        var formSerialization = $('#documentDetailForm,#documentDetailForm1').serializeArray();

        $.ajax({
            url: '<?php echo base_url('/updateCustomerData') ?>',
            type: "post",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: formSerialization,
            dataType: 'json',
            success: function(respn) {
                if (respn.SUCCESS != '1') {
                    // $('#errorModal').show();

                    var ERROR = respn['RESPONSE']['ERROR'];
                    var error = '';
                    $.each(ERROR, function(ind, data) {
                        error += '<li>' + data + '</li>';
                    });
                    showModalAlert('error', error);
                    // $('#formErrorMessage').html(error);
                } else {
                    alert('Guest details are updated successfully.');
                    // showModalAlert('success', '<li>Guest details are updated successfully.</li>');

                    sliderWebWid('');
                    var object = respn['RESPONSE']['OUTPUT'];
                    updateStatuIconButton(object);

                    localStorage.setItem('customer_updated', 1);
                    window.location.reload();
                }
            }
        });
    }

    function checkStatusUploadFiles() {
        $.ajax({
            url: '<?php echo base_url('/checkStatusUploadFiles') ?>',
            type: "post",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                custid: custid,
                resrid: resrid
            },
            dataType: 'json',
            success: function(response) {
                // var jsonForm = respn[0];
                // if (jsonForm.TOTAL_PROOF > 0 && jsonForm.TOTAL_VACC > 0) {
                //     $('.document-padding-done').html('<i class="fa-solid fa-circle-check me-1"></i> Document verified');
                // }

                // $('.document-verified-status').html('<i class="fa-solid fa-circle-xmark me-1"></i> Document not verified');
                $.each(response, (index, row) => {
                    let text = '<i class="fa-solid fa-circle-xmark me-1"></i> Document not verified';

                    <?php
                    if (isset($session->USR_ROLE_ID) && $session->USR_ROLE_ID == '1') {
                    ?>
                        text +=
                            `&nbsp<a href="#" class="text-info" onclick="verifyDocuments(${row.CUST_ID}, ${row.RESV_ID})"><u>click here to verify</u></a>`;
                    <?php
                    }
                    ?>

                    // if (row.DOC_IS_VERIFY && row.VACC_IS_VERIFY)
                    if (row.DOC_IS_VERIFY)
                        text = '<i class="fa-solid fa-circle-check me-1"></i> Document verified';

                    $(`.customer-card-${row.CUST_ID} .document-verified-status`).html(text);
                });

                updateStatuIconButton(response);
            }
        });
    }

    function verifyDocuments(customer_id, reservation_id) {
        $.ajax({
            url: "<?= base_url('checkin/verify-documents') ?>",
            type: 'post',
            data: {
                customer_id,
                reservation_id
            },
            dataType: 'json',
            success: function(response) {
                if (response.SUCCESS != 200) {
                    var ERROR = response['RESPONSE']['REPORT_RES'];
                    var error = '';
                    $.each(ERROR, function(ind, data) {
                        error += '<li>' + data + '</li>';
                    });
                    showModalAlert('error', error);
                } else {
                    showModalAlert('success', response['RESPONSE']['REPORT_RES']['msg']);
                    checkStatusUploadFiles();
                }

            }
        })
    }

    function uploadVaccine(input) {
        if (input.files) {
            var filesAmount = input.files.length;
            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    $('.previewClass').append('<span id="vaccinePreview"><img src="' + event.target.result +
                        '" id=""></span>');
                }
                reader.readAsDataURL(input.files[i]);
            }
        }
    }

    function updateStatuIconButton(object) {
        $(`.flxy_doc_prof`).removeClass('flxy_green').addClass('flxy_orng').text('Pending');
        $(`.flxy_doc_vacc`).removeClass('flxy_green').addClass('flxy_orng').text('Pending');

        $.each(object, (index, row) => {
            if (row.DOC_IS_VERIFY != null)
                $(`.customer-card-${row.CUST_ID} .flxy_doc_prof`).removeClass('flxy_orng').addClass('flxy_green')
                .text('Uploaded');

            if (row.VACC_IS_VERIFY != null)
                $(`.customer-card-${row.CUST_ID} .flxy_doc_vacc`).removeClass('flxy_orng').addClass('flxy_green')
                .text('Uploaded');
        });

        // if (object.TOTAL_PROOF > 0) {
        //     $('.flxy_doc_prof').removeClass('flxy_orng').addClass('flxy_green').text('Uploaded');
        // } else {
        //     $('.flxy_doc_prof').removeClass('flxy_green').addClass('flxy_orng').text('Pending');
        // }

        // if (object.TOTAL_VACC > 0) {
        //     $('.flxy_doc_vacc').removeClass('flxy_orng').addClass('flxy_green').text('Uploaded');
        // } else {
        //     $('.flxy_doc_vacc').removeClass('flxy_green').addClass('flxy_orng').text('Pending');
        // }
    }

    function updateSignature(reservation_status) {
        if (reservation_status == 'Pre Checked-In') {
            $('#checkInConfirmWindow').modal('show');
            return;
        }

        let errors = '';

        var arrivTime = $('#RESV_ETA').val();
        var signature = $('#captureSignature').attr('src');
        var DOC_RESV_ID = $('[name="DOC_RESV_ID"]').val();
        var agree_terms = $('#agreeTerms').is(':checked');

        if (!arrivTime)
            errors += '<li>Please select a arrive time.</li>';

        if (!signature)
            errors += '<li>Please add your signature.</li>';

        if (!agree_terms)
            errors += '<li>Please accept terms & conditions.</li>';

        if (errors) {
            showModalAlert('error', errors);
            return false;
        }

        $.ajax({
            url: '<?php echo base_url('/updateSignatureReserv') ?>',
            type: "POST",
            headers: {
                'X-RequearrivTimested-With': 'XMLHttpRequest'
            },
            data: {
                RESV_ETA: arrivTime,
                RESV_ACCP_TRM_CONDI: AGREE_TERMS,
                signature: signature,
                DOC_RESV_ID: DOC_RESV_ID,
                modesignature: newImageSignature,
                customer_id: <?= $data['CUST_ID'] ?>
            },
            dataType: 'json',
            // processData: false,
            // contentType: false,
            success: function(respn) {

                $('#checkInConfirmWindow').modal('show');
            }
        });
    }

    var formImageDeletArr = [];
    $(document).on('click', '.vaccdelete', function() {
        $(this).parent().remove();
        var name = $(this).attr('id');
        formImageDeletArr.push(name);
    });

    $(document).on('change', '.radioCheck', function() {
        var value = $(this).attr('method');
        $('#VACC_DETAILS').val(value);
    });
    var signaturePad = '';
    <?php
    if ($data['RESV_STATUS'] == 'Due Pre Check-In') {
    ?>
        $(document).on('click', '#clickSignature', function() {
            $('#signaturWindow').modal('show');
            signaturePad = new SignaturePad(document.getElementById('signature-pad'));
        });
    <?php
    }
    ?>

    $(document).on('click', '#clearButton', function() {
        signaturePad.clear();
    });
    var newImageSignature = 0;
    $(document).on('click', '#submitSignatureImage', function() {
        var data = signaturePad.toDataURL('image/png');
        $('#captureSignature').attr("src", data);
        $('#captureSignature').removeClass('d-none');

        $('#clickSignature').hide();
        $('#signaturWindow').modal('hide');
        newImageSignature = 1;
    });

    <?php
    if ($data['RESV_STATUS'] == 'Due Pre Check-In') {
    ?>
        $(document).on('click', '#captureSignature', function() {
            $('#clickSignature').click();
            var image = $('#captureSignature').attr("src");
            signaturePad.fromDataURL(image);
        });
    <?php
    }
    ?>

    var AGREE_TERMS =
        '<?php echo ($data['RESV_ACCP_TRM_CONDI'] == 'N' || $data['RESV_ACCP_TRM_CONDI'] == '' ? 'N' : 'Y') ?>';
    $(document).on('click', '#agreeTerms', function() {
        var checked = $(this).is(':checked');
        if (checked) {
            AGREE_TERMS = 'Y';
        } else {
            AGREE_TERMS = 'N';
        }
    });

    function confirmPrecheckin() {
        $('#loader_flex_bg').show();
        var DOC_RESV_ID = $('[name="DOC_RESV_ID"]').val();
        $.ajax({
            url: '<?php echo base_url('/confirmPrecheckinStatus') ?>',
            type: "POST",
            headers: {
                'X-RequearrivTimested-With': 'XMLHttpRequest'
            },
            data: {
                DOC_RESV_ID: DOC_RESV_ID
            },
            dataType: 'json',
            success: function(respn) {
                if (respn.SUCCESS == '1')
                    {
                        $('#loader_flex_bg').hide();
                        window.location.href = '<?= base_url('/reservationCheckin') ?>';
                    }
            }
        });
    }
</script>