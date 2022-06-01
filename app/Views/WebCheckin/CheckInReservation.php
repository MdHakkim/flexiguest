
<?php
if(empty($condition)){
  $data=$data[0];

  if($data['RESV_STATUS']=='Due Pre Check-In'){
    $statusClass='flxy_orng';
    $icon="fa-circle-xmark";
    $documentmess="Document not verified";
  }else{
    $statusClass='flxy_green';
    $icon="fa-circle-check";
    $documentmess="Document verified";
  }
}
  $folderPath = base_url('assets/upload/');
?>
<html lang="en"
  class="light-style layout-navbar-fixed layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="<?=base_url('assets')?>/"
  data-template="vertical-menu-template">

  <head>
    <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar"></nav>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <?= $this->include('Layout/HeaderScript') ?>
    <link rel="stylesheet" href="<?php echo base_url('assets/jquery.Jcrop.min.css');?>" />
  </head>
  <body class="flex_body">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <div class="app-brand demo flexy_web_logo">
            <a href="<?php echo base_url('/') ?>" class="app-brand-link">
              <span class="app-brand-logo demo">
                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 187.6 228.8" style="enable-background:new 0 0 187.6 228.8;" xml:space="preserve">
                <style type="text/css">
                  .st0{fill-rule:evenodd;clip-rule:evenodd;fill:#18371C;}
                  .st1{fill-rule:evenodd;clip-rule:evenodd;fill:url(#SVGID_1_);}
                  .st2{fill-rule:evenodd;clip-rule:evenodd;fill:url(#SVGID_00000157279095315231982060000005211230105190083231_);}
                  .st3{fill-rule:evenodd;clip-rule:evenodd;fill:url(#SVGID_00000012451031522960550390000012626224618914400140_);}
                  .st4{fill-rule:evenodd;clip-rule:evenodd;fill:url(#SVGID_00000016070205341026246050000000039972798898783923_);}
                  .st5{fill-rule:evenodd;clip-rule:evenodd;fill:url(#SVGID_00000052805987559678658880000016781226765522586761_);}
                  .st6{fill-rule:evenodd;clip-rule:evenodd;fill:url(#SVGID_00000151513121863170215690000006295043811959257524_);}
                  .st7{opacity:0.15;fill-rule:evenodd;clip-rule:evenodd;fill:#161920;enable-background:new    ;}
                  .st8{fill-rule:evenodd;clip-rule:evenodd;fill:url(#SVGID_00000043448609969055518270000012130186912031525559_);}
                  .st9{fill-rule:evenodd;clip-rule:evenodd;fill:url(#SVGID_00000060011834154892075660000005155630478817162145_);}
                </style>
                <path class="st0" d="M4.9,126.3c0,37.8,93.7,55.2,120.3,11l23-47.2C140.6,105.6,24.2,155.9,5,61L4.9,97.4V126.3z"/>
                <linearGradient id="SVGID_1_" gradientUnits="userSpaceOnUse" x1="185.8746" y1="238.7527" x2="-43.7753" y2="111.4127" gradientTransform="matrix(1 0 0 -1 0 222.6383)">
                  <stop  offset="0" style="stop-color:#E7E754"/>
                  <stop  offset="1" style="stop-color:#5CB431"/>
                </linearGradient>
                <path class="st1" d="M90.4,57.8h44.8c28.8,0,49.2-36,49.2-54H86.9C44.2,3.8,4.8,25.9,4.8,65.1v61.2C10.5,82.7,47.7,57.8,90.4,57.8z"
                  />
                <linearGradient id="SVGID_00000001665901154226395070000017377122798177628309_" gradientUnits="userSpaceOnUse" x1="177.4547" y1="227.0634" x2="-62.9653" y2="74.5734" gradientTransform="matrix(1 0 0 -1 0 222.6383)">
                  <stop  offset="0" style="stop-color:#E7E754"/>
                  <stop  offset="1" style="stop-color:#2B893A"/>
                </linearGradient>
                <path style="fill-rule:evenodd;clip-rule:evenodd;fill:url(#SVGID_00000001665901154226395070000017377122798177628309_);" d="
                  M6.6,76.9v33.8c11.7-35.1,45.8-54.6,83.8-54.6h44.8c3.9,0,7.8-0.7,11.4-2c7.5-2.7,14-7.8,19.3-13.6c5.1-5.7,9.2-12.1,12.3-19.1
                  c1.9-4.3,3.3-8.8,4.1-13.4c-1.8,2.6-3.7,5.1-5.8,7.5c-5.3,5.9-11.8,10.9-19.3,13.6c-3.7,1.3-7.5,2-11.4,2c-14.9,0-44.3,0.9-59.3,0.9
                  C52.1,31.9,20.9,47.8,6.6,76.9z"/>
                <linearGradient id="SVGID_00000142161053142947608910000017353340766530899897_" gradientUnits="userSpaceOnUse" x1="214.0623" y1="185.2717" x2="-12.5577" y2="36.1917" gradientTransform="matrix(1 0 0 -1 0 222.6383)">
                  <stop  offset="0" style="stop-color:#E7E754"/>
                  <stop  offset="1" style="stop-color:#5CB431"/>
                </linearGradient>
                <path style="fill-rule:evenodd;clip-rule:evenodd;fill:url(#SVGID_00000142161053142947608910000017353340766530899897_);" d="
                  M86.1,137.3h27.6c2.3,0,10.5,0.5,6.7,6.6c1.8-2,3.5-4.2,4.8-6.6l22.9-47c3.2-6.6-4.8-7-7.7-7H82.5C39.7,83.5,5,118.2,4.9,161v63.3
                  c16.8,0,43.5-20.1,43.5-41.8C48.3,161.6,42,137.3,86.1,137.3z"/>
                <linearGradient id="SVGID_00000026845750694380275960000014168185075937240968_" gradientUnits="userSpaceOnUse" x1="178.1774" y1="166.3042" x2="17.2574" y2="47.4942" gradientTransform="matrix(1 0 0 -1 0 222.6383)">
                  <stop  offset="0" style="stop-color:#E7E754"/>
                  <stop  offset="1" style="stop-color:#2B893A"/>
                </linearGradient>
                <path style="fill-rule:evenodd;clip-rule:evenodd;fill:url(#SVGID_00000026845750694380275960000014168185075937240968_);" d="
                  M122.3,138.1c0.4-0.5,0.7-1.1,1-1.7l11.7-24.1c-15.2-0.2-44-0.8-51.6-0.8c-4.2,0-8.5,0.2-12.7,0.8l0,0l-2.1,0.3
                  c-25.2,4-48.5,14.3-60.4,34.1c-0.9,4.7-1.4,9.5-1.4,14.3v17.6c9.9-25.1,35.9-37.7,64.4-42.2l2.1-0.3l0,0c4.2-0.6,8.5-0.8,12.7-0.8
                  h27.6c1.5,0,3,0.2,4.5,0.5C119.7,136,121.2,136.9,122.3,138.1z"/>
                <linearGradient id="SVGID_00000110460869155740343570000017850948155766471613_" gradientUnits="userSpaceOnUse" x1="35.4944" y1="-52.6618" x2="42.5244" y2="115.9482" gradientTransform="matrix(1 0 0 -1 0 222.6383)">
                  <stop  offset="0" style="stop-color:#E7E754"/>
                  <stop  offset="1" style="stop-color:#2B893A"/>
                </linearGradient>
                <path style="fill-rule:evenodd;clip-rule:evenodd;fill:url(#SVGID_00000110460869155740343570000017850948155766471613_);" d="
                  M4.9,202v22.3c16.8,0,43.5-20.1,43.5-41.8c0-18.3-4.8-39.1,23.3-44.1C51.6,141.7,4.4,153.3,4.9,202L4.9,202z M75,137.9L75,137.9z"/>
                <linearGradient id="SVGID_00000182513730796094447060000010748656252746595768_" gradientUnits="userSpaceOnUse" x1="14.5765" y1="-41.5845" x2="25.5865" y2="96.7855" gradientTransform="matrix(1 0 0 -1 0 222.6383)">
                  <stop  offset="0" style="stop-color:#E7E754"/>
                  <stop  offset="1" style="stop-color:#5CB431"/>
                </linearGradient>
                <path style="fill-rule:evenodd;clip-rule:evenodd;fill:url(#SVGID_00000182513730796094447060000010748656252746595768_);" d="
                  M6.5,222.6c6.1-0.5,12.7-3.4,18.4-7c2.8-8.5,2-21.1,0.9-37.1s10.9-26.9,11-27C19,161.2,6.5,177.3,6.5,202.1L6.5,222.6z"/>
                <path class="st7" d="M49.9,154.2c2.6-7.5,8.5-13.5,21.8-15.8c-19.8,3.2-65.8,14.5-66.8,61.4C5.3,192.7,10,170.1,49.9,154.2
                  L49.9,154.2z M75,137.9L75,137.9z"/>
                <linearGradient id="SVGID_00000005267255372703103320000011831359240645276035_" gradientUnits="userSpaceOnUse" x1="1.1712" y1="109.9574" x2="184.1612" y2="208.8374" gradientTransform="matrix(1 0 0 -1 0 222.6383)">
                  <stop  offset="0" style="stop-color:#2B893A"/>
                  <stop  offset="0.22" style="stop-color:#2D8A3B"/>
                  <stop  offset="0.3" style="stop-color:#348E3F"/>
                  <stop  offset="0.36" style="stop-color:#3F9447"/>
                  <stop  offset="0.41" style="stop-color:#509D51"/>
                  <stop  offset="0.45" style="stop-color:#66A85F"/>
                  <stop  offset="0.48" style="stop-color:#82B76F"/>
                  <stop  offset="0.52" style="stop-color:#A2C884"/>
                  <stop  offset="0.55" style="stop-color:#C8DC9B"/>
                  <stop  offset="0.57" style="stop-color:#F1F2B4"/>
                  <stop  offset="0.57" style="stop-color:#F7F5B8"/>
                  <stop  offset="1" style="stop-color:#FDF160"/>
                </linearGradient>
                <path style="fill-rule:evenodd;clip-rule:evenodd;fill:url(#SVGID_00000005267255372703103320000011831359240645276035_);" d="
                  M178.2,21.3c-3.1,7-7.2,13.4-12.3,19.1c-5.3,5.9-11.8,10.9-19.3,13.6c-3.7,1.3-7.5,2-11.4,2H90.4c-30.1,0-57.7,12.2-73.7,34.6
                  c-4.4,6.1-7.8,12.9-10.1,20v-0.3c-0.7,2.6-1.3,5.2-1.8,7.9v8.1c1.5-12.3,6-24,13-34.1C33.4,70,60.6,57.8,90.5,57.8h44.8
                  c28.5,0,48.8-35.3,49.2-53.4l-1.8,1.4C182,11.1,180.5,16.4,178.2,21.3z"/>
                <linearGradient id="SVGID_00000099647703509215640730000017861532716248486312_" gradientUnits="userSpaceOnUse" x1="0.5407" y1="39.9869" x2="117.6607" y2="90.8269" gradientTransform="matrix(1 0 0 -1 0 222.6383)">
                  <stop  offset="0" style="stop-color:#2B893A"/>
                  <stop  offset="0.14" style="stop-color:#46974B"/>
                  <stop  offset="0.43" style="stop-color:#8BBC75"/>
                  <stop  offset="0.86" style="stop-color:#F7F5B8"/>
                  <stop  offset="0.89" style="stop-color:#F7F5B4"/>
                  <stop  offset="0.92" style="stop-color:#F8F4A9"/>
                  <stop  offset="0.95" style="stop-color:#F9F396"/>
                  <stop  offset="0.98" style="stop-color:#FBF27B"/>
                  <stop  offset="1" style="stop-color:#FDF160"/>
                </linearGradient>
                <path style="fill-rule:evenodd;clip-rule:evenodd;fill:url(#SVGID_00000099647703509215640730000017861532716248486312_);" d="
                  M28,154.9c14.7-10.6,32.8-14.7,43.5-16.4h0.1c1.1-0.2,2.2-0.4,3.4-0.5l0,0c3.7-0.4,7.3-0.6,11-0.6h27.6c2.3,0,10.5,0.5,6.7,6.6l0,0
                  c0.5-0.6,1.1-1.3,1.7-2s0.9-1.2,1.4-1.8c-0.2-0.8-0.5-1.5-0.9-2.2l-0.2,0.2c-1.1-1.2-2.6-2-4.2-2.3c-1.5-0.3-3-0.5-4.5-0.5H86.1
                  c-4.2,0-8.5,0.2-12.7,0.8l0,0l-2.1,0.3c-16.9,2.7-32.9,8.2-45.3,17.5c-8.6,6.3-15.2,14.8-19.2,24.7v-0.2c-0.7,2.4-1.4,5-2.1,7.6
                  v14.8C5,178.5,15.2,164.2,28,154.9z"/>
                </svg>

              </span>
              <span class="app-brand-text demo menu-text fw-bold ms-2">FlexiGuest</span>
            </a>
            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
              <i class="bx menu-toggle-icon fs-4 d-none d-xl-block align-middle"></i>
              <i class="bx bx-x bx-sm d-xl-none d-block align-middle"></i>
            </a>
          </div>
          <!-- <a class="navbar-brand" href="#">Navbar</a> -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
    </nav>
    <div class="container-fluid text-center flxy_content_flex">    
      <div class="container-sm">
        <?php if(empty($condition)){?>
        <div class="row justify-content-center mb-4">
          <h4 class="breadcrumb-wrapper py-3 mb-1 text-start">Reservation Detail</h4>
          <div class="col-11 flxy_web_content"> 
              <div class="flxy_wrapper">
                <div class="flxy_web-header">
                  <p class="flxy_web_status <?php echo $statusClass;?>"><?php echo $data['RESV_STATUS'];?></p>
                </div>
                <div class="flxy_web-blockcont">
                  <div class="sliderclass activeslide">
                    <div class="flxy_block_card">
                      <div class="card">
                        <div class="row g-0">
                          <div class="col-md-3 flxy_icon_mid">
                            <i class="fa-solid fa-r flxy_fa_x2"></i>
                          </div>
                          <div class="col-md-9">
                            <div class="card-body">
                              <h5 class="card-title">Reservation Number</h5>
                              <p class="card-text"><?php echo $data['RESV_NO'];?></p>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card " >
                        <div class="row g-0">
                          <div class="col-md-3 flxy_icon_mid">
                            <i class="fa-solid fa-bed flxy_fa_x2"></i>
                          </div>
                          <div class="col-md-9">
                            <div class="card-body">
                              <h5 class="card-title">Apartment Number</h5>
                              <p class="card-text"><?php echo ($data['RESV_ROOM']=='' ?'&nbsp;':$data['RESV_ROOM']);?></p>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card " >
                        <div class="row g-0">
                          <div class="col-md-3 flxy_icon_mid">
                            <i class="fa-solid fa-plane-arrival flxy_fa_x2"></i>
                          </div>
                          <div class="col-md-9">
                            <div class="card-body">
                              <h5 class="card-title">Arrival</h5>
                              <p class="card-text"><?php echo $data['RESV_ARRIVAL_DT'];?></p>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card " >
                        <div class="row g-0">
                          <div class="col-md-3 flxy_icon_mid">
                            <i class="fa-solid fa-cloud-moon flxy_fa_x2"></i>
                          </div>
                          <div class="col-md-9">
                            <div class="card-body">
                              <h5 class="card-title">Nights</h5>
                              <p class="card-text"><?php echo $data['RESV_NIGHT'];?></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="flxy_block_card">
                      <div class="card " >
                        <div class="row g-0">
                          <div class="col-md-3 flxy_icon_mid">
                            <i class="fa-solid fa-person flxy_fa_x2"></i>
                          </div>
                          <div class="col-md-9">
                            <div class="card-body">
                              <h5 class="card-title">Guest Name</h5>
                              <p class="card-text"><?php echo $data['FULLNAME'];?></p>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card " >
                        <div class="row g-0">
                          <div class="col-md-3 flxy_icon_mid">
                          <i class="fa-solid fa-building flxy_fa_x2"></i>
                          </div>
                          <div class="col-md-9">
                            <div class="card-body">
                              <h5 class="card-title">Apartment Detail</h5>
                              <p class="card-text"><?php echo $data['RM_TY_DESC'];?></p>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card " >
                        <div class="row g-0">
                          <div class="col-md-3 flxy_icon_mid">
                            <i class="fa-solid fa-plane-departure flxy_fa_x2"></i>
                          </div>
                          <div class="col-md-9">
                            <div class="card-body">
                              <h5 class="card-title">Departure</h5>
                              <p class="card-text"><?php echo $data['RESV_DEPARTURE'];?></p>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card " >
                        <div class="row g-0">
                          <div class="col-md-3 flxy_icon_mid">
                            <i class="fa-regular fa-user flxy_fa_x2"></i>
                          </div>
                          <div class="col-md-9">
                            <div class="card-body">
                              <h5 class="card-title">Adults/Children</h5>
                              <p class="card-text"><?php echo $data['RESV_ADULTS'].'/'.$data['RESV_CHILDREN'];?></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="sliderclass">
                    <div class="flxy_block_card flxy_document">
                      <div class="card">
                        <div class="card-body flxy_web_padd">
                          <h5 class="card-title"><?php echo $data['FULLNAME'];?></h5>
                          <p class="card-text document-padding-done">
                            <i class="fa-solid <?php echo $icon;?> me-1"></i><?php echo $documentmess;?>
                          </p>
                        </div>
                        <ul class="list-group list-group-flush flxy_web-ul">
                          <li class="list-group-item text-flxy">
                            <div class="flxy-data">Name</div>
                            <div class="flxy-data">: <?php echo $data['FULLNAME'];?></div>
                          </li>
                          <li class="list-group-item text-flxy">
                            <div class="flxy-data">Document Status</div>
                            <div class="flxy-data">: <span class="flxy_doc_prof flxy_doc_st <?php echo $statusClass;?>">Pending</span></div></li>
                          <li class="list-group-item text-flxy">
                            <div class="flxy-data">Vaccine Certificate</div>
                            <div class="flxy-data">: <span class="flxy_doc_vacc flxy_doc_st <?php echo $statusClass;?>">Pending</span></div></li>
                          <li class="list-group-item d-grid ">
                            <button class="btn btn-dark " onClick="docUploadClik('D')" type="button">View & Upload Documents</button>
                          </li>
                          <li class="list-group-item d-grid">
                            <button class="btn btn-dark " onClick="docUploadClik('V')" type="button">Vaccination Details</button>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="flxy_doc_block flxy_none">
                  <div class="flxy_doc_content">
                    <div class="flxy_block_card">
                      <div class="contentDesc">
                        <h4>Guest Profile & Document Details</h4>
                        <ol>
                          <li>Please upload the following Documents (file types: JPEG or PNG)</li>
                          <li>Non Residents / UAE Nationals (Valid Passport)</li>
                          <li>UAE Residents (EID both sides, make sure to scan the back side first)</li>
                        </ol>
                      </div>
                      
                      <div class="card">
                        <div class="flxy_card_head">
                            Document Images <span class="deleteUploadImage"><i class="fa-solid fa-trash-can"></i></span>
                        </div>
                        
                        <img src="#" id="cropped_img">
                        <div class="card-body">
                          <div class="flxy_image_show">
                            <ul id="listImagePresentDb"></ul>
                            <input class="form-control" type="file" onchange="loadFile(event,this)" style="display:none" id="formFile">
                            <button type="button" onClick="browseFile()" class="btn btn-secondary flxy_brows btn-sm"><i class="fa-solid fa-upload"></i> Browse</button>
                            
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
                              <a href="#contact" class="nav-link" data-bs-toggle="tab">Contact Detail</a>
                          </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active p-3" id="profile">
                            <form id="documentDetailForm">
                                <input type="hidden" value="<?php echo $data['CUST_ID'];?>" name="DOC_CUST_ID">
                                <input type="hidden" value="<?php echo $data['RESV_ID'];?>" name="DOC_RESV_ID">
                                <input type="hidden" value="PROOF" name="DOC_FILE_TYPE">
                                <div class="row ">
                                  <label for="inputEmail" class="col-sm-3 col-form-label text-start">Title</label>
                                  <div class="col-sm-9">
                                      <input type="text" value="<?php echo $data['CUST_TITLE'];?>" class="form-control form-control-sm" id="CUST_TITLE" name="CUST_TITLE" placeholder="Title">
                                  </div>
                                </div>
                                <div class="row ">
                                  <label for="inputPassword" class="col-sm-3 col-form-label text-start">First Name</label>
                                  <div class="col-sm-9">
                                      <input type="text" value="<?php echo $data['CUST_FIRST_NAME'];?>" class="form-control form-control-sm" id="CUST_FIRST_NAME" name="CUST_FIRST_NAME" placeholder="First Name">
                                  </div>
                                </div>
                                <div class="row ">
                                  <label for="inputPassword" class="col-sm-3 col-form-label text-start">Last Name</label>
                                  <div class="col-sm-9">
                                      <input type="text" value="<?php echo $data['CUST_LAST_NAME'];?>" class="form-control form-control-sm" id="CUST_LAST_NAME" name="CUST_LAST_NAME" name="CUST_LAST_NAME" placeholder="Last Name">
                                  </div>
                                </div>
                                <div class="row ">
                                  <label for="inputPassword" class="col-sm-3 col-form-label text-start">Gender</label>
                                  <div class="col-sm-9">
                                      <input type="text" value="<?php echo $data['CUST_GENDER'];?>" class="form-control form-control-sm" id="CUST_GENDER" name="CUST_GENDER" name="CUST_GENDER" placeholder="Gender">
                                  </div>
                                </div>
                                <div class="row ">
                                  <label for="inputPassword" class="col-sm-3 col-form-label text-start">Nationality</label>
                                  <div class="col-sm-9">
                                      <input type="text" value="<?php echo $data['CUST_NATIONALITY'];?>"  class="form-control form-control-sm" id="CUST_NATIONALITY" name="CUST_NATIONALITY" placeholder="Nationality">
                                  </div>
                                </div>
                                <div class="row ">
                                  <label for="inputPassword" class="col-sm-3 col-form-label text-start">Date of Birth</label>
                                  <div class="col-sm-9">
                                      <!-- <input type="text" value="<?php echo $data['CUST_DOB'];?>"  class="form-control form-control-sm" id="CUST_DOB" name="CUST_DOB" placeholder="Date of Birth"> -->
                                      <div class="input-group mb-2">
                                        <input type="text" id="CUST_DOB" name="CUST_DOB" value="<?php echo $data['CUST_DOB'];?>" class="form-control CUST_DOB form-control-sm" placeholder="DD-MM-YYYY">
                                        <span class="input-group-append">
                                          <span class="input-group-text bg-light d-block">
                                            <i class="fa fa-calendar"></i>
                                          </span>
                                        </span>
                                      </div>
                                  </div>
                                </div>
                                <div class="row mb-2">
                                  <label for="inputPassword" class="col-sm-3 col-form-label text-start">Res Country</label>
                                  <div class="col-sm-9">
                                      <!-- <input type="text" value="<?php echo $data['CUST_COUNTRY'];?>"  class="form-control form-control-sm" id="CUST_COUNTRY" name="CUST_COUNTRY" placeholder="Res Country"> -->
                                      <select name="CUST_COUNTRY"  id="CUST_COUNTRY" data-width="100%" class="selectpicker CUST_COUNTRY" data-live-search="true">
                                        <option value="">Select</option>
                                      </select>
                                  </div>
                                </div>
                                <div class="row ">
                                  <label for="inputPassword" class="col-sm-3 col-form-label text-start">Document Type</label>
                                  <div class="col-sm-9">
                                      <input type="text" value="<?php echo $data['CUST_DOC_TYPE'];?>"  class="form-control form-control-sm" id="CUST_DOC_TYPE" name="CUST_DOC_TYPE"  placeholder="Document Type">
                                  </div>
                                </div>
                                <div class="row ">
                                  <label for="inputPassword" class="col-sm-3 col-form-label text-start">Doc Number</label>
                                  <div class="col-sm-9">
                                      <input type="text" value="<?php echo $data['CUST_DOC_NUMBER'];?>"  class="form-control form-control-sm" id="CUST_DOC_NUMBER"name="CUST_DOC_NUMBER"  placeholder="Doc Number">
                                  </div>
                                </div>
                                <div class="row ">
                                  <label for="inputPassword" class="col-sm-3 col-form-label text-start">Issue Date</label>
                                    <div class="col-sm-9">
                                      <div class="input-group mb-2">
                                        <input type="text" id="CUST_DOC_ISSUE" name="CUST_DOC_ISSUE" value="<?php echo $data['CUST_DOC_ISSUE'];?>" class="form-control CUST_DOC_ISSUE form-control-sm" placeholder="DD-MM-YYYY">
                                        <span class="input-group-append">
                                          <span class="input-group-text bg-light d-block">
                                            <i class="fa fa-calendar"></i>
                                          </span>
                                        </span>
                                      </div>
                                    </div>
                                </div>
                                <div class="row ">
                                  <label for="inputPassword" class="col-sm-3 col-form-label text-start">Phone</label>
                                  <div class="col-sm-9">
                                      <input type="number" value="<?php echo $data['CUST_PHONE'];?>"  class="form-control form-control-sm" id="CUST_PHONE" name="CUST_PHONE"  placeholder="Phone">
                                  </div>
                                </div>
                                <div class="row ">
                                  <label for="inputPassword" class="col-sm-3 col-form-label text-start">Email</label>
                                  <div class="col-sm-9">
                                      <input type="text" value="<?php echo $data['CUST_EMAIL'];?>" class="form-control form-control-sm" id="CUST_EMAIL" name="CUST_EMAIL"  placeholder="Email">
                                  </div>
                                </div>
                            </form>
                            </div>
                            <div class="tab-pane fade  p-3" id="contact">
                              <form id="documentDetailForm1">
                                <div class="row ">
                                  <label for="inputPassword" class="col-sm-3 col-form-label text-start">Address Line 1</label>
                                  <div class="col-sm-9">
                                      <input type="text" value="<?php echo $data['CUST_ADDRESS_1'];?>" class="form-control form-control-sm" id="CUST_ADDRESS_1" name="CUST_ADDRESS_1" placeholder="Address Line 1">
                                  </div>
                                </div>
                                <div class="row ">
                                  <label for="inputPassword" class="col-sm-3 col-form-label text-start">Address Line 2</label>
                                  <div class="col-sm-9">
                                      <input type="text" value="<?php echo $data['CUST_ADDRESS_2'];?>" class="form-control form-control-sm" id="CUST_ADDRESS_2" name="CUST_ADDRESS_2" placeholder="Address Line 2">
                                  </div>
                                </div>
                                <div class="row mb-2">
                                  <label for="inputPassword" class="col-sm-3 col-form-label text-start">State </label>
                                    <div class="col-sm-9">
                                      <select name="CUST_STATE"  id="CUST_STATE" data-width="100%" class="selectpicker CUST_STATE" data-live-search="true">
                                        <option value="<?php echo $data['CUST_STATE'];?>"><?php echo $data['CUST_STATE_DESC'];?></option>
                                      </select>
                                    </div>
                                </div>
                                <div class="row ">
                                  <label for="inputPassword" class="col-sm-3 col-form-label text-start">City</label>
                                    <div class="col-sm-9">
                                      <select name="CUST_CITY"  id="CUST_CITY" data-width="100%" class="selectpicker CUST_CITY" data-live-search="true">
                                        <option value="<?php echo $data['CUST_CITY'];?>"><?php echo $data['CUST_CITY_DESC'];?></option>
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
                <div class="flxy_signature_block">
                  <div class="singatureContent">
                    <table class="table">
                      <tbody>
                        <tr>
                          <th class="text-end">Guest Name</th>
                          <td><?php echo $data['FULLNAME'];?></td>
                          <td class="text-end fw-bold">Apartment Number</td>
                          <td><?php echo $data['RESV_ROOM'];?></td>
                        </tr>
                        <tr>
                          <th class="text-end">Reservation Number</th>
                          <td><?php echo $data['RESV_NO'];?></td>
                          <td class="text-end fw-bold">Apartment Details</td>
                          <td><?php echo $data['RM_TY_DESC'];?></td>
                        </tr>
                        <tr>
                          <th class="text-end">Arrival</th>
                          <td><?php echo $data['RESV_ARRIVAL_DT'];?></td>
                          <td class="text-end fw-bold">Phone</td>
                          <td><?php echo $data['CUST_PHONE'];?></td>
                        </tr>
                        <tr>
                          <th class="text-end">Departure</th>
                          <td><?php echo $data['RESV_DEPARTURE'];?></td>
                          <td class="text-end fw-bold">Email</td>
                          <td><?php echo $data['CUST_EMAIL'];?></td>
                        </tr>
                        <tr>
                          <th class="text-end">Adult/Children</th>
                          <td><?php echo $data['RESV_ADULTS'].'/'.$data['RESV_CHILDREN'];?></td>
                          <td class="text-end fw-bold">Nationality</td>
                          <td><?php echo $data['CUST_NATIONALITY'];?></td>
                        </tr>
                        <tr>
                          <td colspan="4">
                            <div class="col-md-4">
                              <label class="form-label"> Please update your expected time of arrival </label>
                                <input type="time" value="<?php echo $data['RESV_ETA'];?>" name="RESV_ETA" id="RESV_ETA" class="form-control" placeholder="estime Time" />
                              </div>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                      <h4 class="text-start">Terms and Conditions</h4>
                      <p class="text-align:right;">         
                        <ol class="text-start">
                          <li >Property facilities such as swimming pool and gym are available for guests and may be used at your own risk. Hotel will not be liable for any injury as well as any lost or stolen personal belongings resulting from usage of these facilities. Any damages to the facilities and/or its equipment will result to charges.</li>
                          <li>
                          In-room safes are provided for the security of your valuables. Hotel will not be responsible for any lost, stolen or damaged personal items. Hotel is not responsible for any items left in your room or private vehicle.
                          </li>
                          <li>
                          My signature on this registration card is an authorization to use my credit card for any unpaid bills. I accept full liability for any bills associated during my stay and I agree to be held personally liable in the event that the indicated person/company or third party fails to pay for any part of the charges or full amount.
                          <li>
                          Early Departures: All Reservations are confirmed and charged in full for the entire duration of stay. No refunds will apply in the event of Early Departures.
                          </li>
                          <li>
                          I agree that I will be liable to pay for any damages or loss in the apartment assigned to me for the entire stay including apartment keys, furnishings, fixtures and equipment. All related repairs and replacements required will be subject to evaluation and/or assessment of the damage or loss.
                          </li>
                          <li>
                          All rooms and units are strictly Non-smoking, I fully understand that any violation of the non-smoking policy will result to a penalty subject to management discretion.
                          </li>
                          <li>
                          I hereby accept that any items thrown off the balcony of my occupied room or apartment will result in immediate eviction. I agree to supervise all children (below 16 years and under) on the balcony at all times and to ensure that balcony and windows are locked before exiting the room or apartment.
                          </li>
                          <li>
                          Acknowledge that I have access to the Hotel Handbook in the Hotel App which contains a guide to Hotel and the Community Rules.
                          </li>
                          <li>
                          I hereby agree that staff from Hotel housekeeping, maintenance and associated teams can enter my apartment for cleaning services or to attend to any maintenance issues. Although we endeavor to notify residents in advance, direct access may be required in an emergency situation.
                          </li>
                          <li>
                          Housekeeping cleaning services and linen change will be offered once per week at a preset date & time frame.
                          </li>
                        </ol>       
                      </p>
                      <div class="form-check" style="display: flex;">
                        <input class="form-check-input" type="checkbox" <?php echo (trim($data['RESV_ACCP_TRM_CONDI'])=='Y' ? 'checked':'');?> id="agreeTerms">
                        <label class="form-check-label" for="flexCheckDefault">
                          I accept the terms and conditions
                        </label>
                      </div>
                      <div class="row" style="justify-content: right;padding: 48px;"> 
                        <div class="col-md-3">
                          <button type="button" <?php echo $data['RESV_SINGATURE_URL']!='' ? 'style="display:none;"' : '';?> id="clickSignature" class="btn btn-secondary">Click to sign here</button>
                          <img id="captureSignature" style="width:100%;" src="<?php echo base_url('assets/upload/'.$data['RESV_SINGATURE_URL']);?>"/>
                        </div>
                      </div>
                  </div>
                </div>
                <div class="flxy_web-footer text-end">
                  <button type="button" onClick="sliderWebWid('P')" class="btn btn-blue btn-primary"><i class="fa-solid fa-chevron-left"></i> Back</button>
                  <button type="button" onClick="sliderWebWid('N')" class="btn continueDefult btn-blue btn-primary">Continue <i class="fa-solid fa-chevron-right"></i></button>
                  <button type="button" onClick="updateSignature()" class="btn btn-success updateSignature signHideClass">Continue <i class="fa-solid fa-chevron-right"></i></button>
                  <button type="button" onClick="updateCustomer()" class="btn saveContinue btn-success">Save & Continue <i class="fa-solid fa-chevron-right"></i></button>
                </div>
              </div>
          </div>
        </div>
        <?php }else{?>
          <div class="row mt-4">
            <div class="col-11">
            <p style="font-size: 40px;color: green;"><i class="fa-solid fa-circle-check"></i></p>  
            <h2>You have successfully completed your Pre-Arrival Check-in Process</h2>
            <h5 style="font-weight: inherit;">We look forward to welcoming you soon. A confirmation email with a unique QR code has been sent to your registered email address. Please check your inbox or Spam folder.</h5>
          </div>
          </div>
        <?php }?>
      </div>
    </div>
    <footer class="container-fluid text-center mt-auto">
      <div class="container-fluid d-flex flex-column flex-md-row flex-wrap justify-content-between py-2">
        <div class="mb-2 mb-md-0"> ©
          <script>
            document.write(new Date().getFullYear());
          </script>
          , Copyrights. All Rights Reserved by <a href="https://www.hitekservices.com" target="_blank" class="footer-link fw-semibold">HITEK</a>.
        </div>
      </div>
    </footer>
    <?= $this->include('Layout/FooterScript') ?>
  </body>
</html>
<?php if(empty($condition)){?>
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
              <button type="button" id="croping" class="btn btn-primary btn-sm"><i class="fa-solid fa-check"></i> Crop</button>
            </span>
            <span class="btnRight">
              <button type="button" id="saveCropping" class="btn btn-success btn-sm"><i class="fa-solid fa-floppy-disk"></i> Save</button>
            </span>
          </div>
        </div>
      </div>
    </div>

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
              <input type="hidden" value="<?php echo $data['CUST_ID'];?>" name="VACC_CUST_ID">
              <input type="hidden" value="<?php echo $data['RESV_ID'];?>" name="VACC_RESV_ID">
              <div class="row ">
                <label for="inputPassword" class="col-sm-4 col-form-label text-end">Reservation Number</label>
                <div class="col-sm-8">
                    <input type="text" readonly value="<?php echo $data['RESV_ID'];?>"  class="form-control form-control-sm" id="CUST_PHONE" name="CUST_PHONE"  placeholder="Phone">
                </div>
              </div>
              <div class="row ">
                <label for="inputPassword" class="col-sm-4 col-form-label text-end">Booking Profile</label>
                <div class="col-sm-8">
                    <input type="text" readonly value="<?php echo $data['FULLNAME'];?>"  class="form-control form-control-sm" id="CUST_PHONE" name="CUST_PHONE"  placeholder="Phone">
                </div>
              </div>
              <div class="row ">
                <label for="inputPassword" class="col-sm-4 col-form-label text-end">vaccine Detail</label>
                <div class="col-sm-8">
                <input class="form-check-input" type="hidden" value="FULLY" name="VACC_DETAILS" id="VACC_DETAILS">
                  <div class="form-check">
                    <input class="form-check-input radioCheck" checked type="radio" name="VACC_DETL" method="FULLY">
                    <label class="form-check-label"  for="flexRadioDefault1">
                    I have been fully vaccinated (Please attach the Vaccination certificate)
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input radioCheck" type="radio" name="VACC_DETL" method="EXMP">
                    <label class="form-check-label" for="flexRadioDefault1">
                    I am medically exempt from taking any Covid19 Vaccine and I have a certified exemption certification (Please attach Official Medical Exemption certificate)
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input radioCheck" type="radio" name="VACC_DETL" method="PROCE">
                    <label class="form-check-label" for="VACC_DETAILS">
                    I will be completing my vaccine in Dubai before October 1st and will provide my vaccination certificate prior to October 1st to continue staying
                    </label>
                  </div>
                </div>
              </div>
              <div class="row ">
                <label for="inputPassword" class="col-sm-4 col-form-label text-end">Last Vaccine Date</label>
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
              <div class="row ">
                <label for="inputPassword" class="col-sm-4 col-form-label text-end">Vaccine Name</label>
                <div class="col-sm-8">
                    <input type="text"  class="form-control form-control-sm" id="VACC_NAME" name="VACC_NAME"  placeholder="Vaccine Name">
                </div>
              </div>
              <div class="row ">
                <input type="hidden" class="form-control form-control-sm" id="VACC_DOC_SAVED" name="VACC_DOC_SAVED">
                <label for="inputPassword" class="col-sm-4 col-form-label text-end">Vaccine Certificate</label>
                <div class="col-sm-8">
                    <input class="form-control form-control-sm" id="fileUpload" name="files[]" multiple onChange="uploadVaccine(this)" type="file">
                </div>
                <div class="col-sm-12 text-center previewClass">
                    
                </div>
              </div>
              <div class="row ">
                <label for="inputPassword" class="col-sm-4 col-form-label text-end">Certificate Issue Country</label>
                <div class="col-sm-8">
                  <select name="VACC_ISSUED_COUNTRY"  id="VACC_ISSUED_COUNTRY" data-width="100%" class="selectpicker VACC_ISSUED_COUNTRY" data-live-search="true">
                    <option value="">Select</option>
                  </select>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer flxy_modClass">
            <button type="button" id="updateVaccine" class="btn btn-primary btn-sm"><i class="fa-solid fa-check"></i> Update</button>
          </div>
        </div>
      </div>
    </div>

      <div class="modal fade" id="signaturWindow" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="rateQueryWindowLable">Signature Here</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div id="signature" style=''>
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
                  <button class="btn btn-primary" onClick="confirmPrecheckin()" type="button">Pre-Check-in Now</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

<?php } ?>
      <link href="<?php //echo base_url('assets/signature/jquery.signature.css'); ?>" rel="stylesheet">
      <script src="<?php echo base_url('assets/signature/signature_pad.js'); ?>"></script>
      <script src="<?php //echo base_url('assets/signature/jquery.signature.js'); ?>"></script>
      <script src="<?php //echo base_url('assets/signature/jquery.ui.touch-punch.js'); ?>"></script>
  <style>
    #signature{
      width: 100%;
      height: auto;
    }
  </style>
<script>
  var condi='<?php echo $condition;?>';
  if(condi==''){
    var resrid='<?php echo $data['RESV_ID'];?>';
    var custid='<?php echo $data['CUST_ID'];?>';
    var countryCode='<?php echo $data['CUST_COUNTRY'];?>';
    var statecode='<?php echo $data['CUST_STATE'];?>';
  }
 
  $(document).ready(function() {
    $('.flxy_signature_block').hide();
    $('.continueDefult').show();
    $('.saveContinue').addClass('hideSaveCont');
    checkStatusUploadFiles();
    $('.CUST_DOB,.VACC_LAST_DT').datepicker({
        format: 'd-M-yyyy',
        autoclose: true,
    });
  });

  var size;
  var docuClick='';
  var farwrdClick=0;
  function sliderWebWid(param){
    $('.updateSignature').addClass('signHideClass');
    if(docuClick=='PROOF'){
      $('.sliderclass:eq(1)').addClass('activeslide');
      $('.flxy_web-blockcont').removeClass('flxy_none');
      $('.flxy_doc_block').addClass('flxy_none');
      $('.continueDefult').show();
      $('.saveContinue').addClass('hideSaveCont');
      return false;
    }
    if(param=='N'){
      var length = $('.sliderclass.activeslide').next('.sliderclass').length;
      if(length==0){
        $('.updateSignature').removeClass('signHideClass');
        $('.continueDefult').hide();
        continueToTaskSlide();
        farwrdClick=1;
      }
      $('.sliderclass.activeslide').removeClass('activeslide').next().addClass('activeslide');
    }else{
      $('.flxy_web-header,.flxy_web-blockcont').show();
      $('.flxy_signature_block').hide();
      if($('.sliderclass.activeslide').prev().length>0){
        $('.sliderclass.activeslide').removeClass('activeslide').prev().addClass('activeslide');  
      }
      if(farwrdClick==1){
        $('.continueDefult').show();
        $('.updateSignature').addClass('signHideClass');
        previewSlideClick(farwrdClick);
        farwrdClick=0;
      }
    }
    // console.log(farwrdClick,"AVTION");
  }

  function continueToTaskSlide(){
    $('.flxy_web-header,.flxy_web-blockcont').hide();
    $('.flxy_signature_block').show();
  }

  function previewSlideClick(){
    $('.sliderclass:eq(1)').addClass('activeslide');
    $('.flxy_web-header,.flxy_web-blockcont').show();
    $('.flxy_signature_block').hide();
  }

  function docUploadClik(param){
    runCountryList();
    var DOC_CUST_ID=$('[name="DOC_CUST_ID"]').val();
    var DOC_RESV_ID=$('[name="DOC_RESV_ID"]').val();
    if(param=='D'){
      docuClick='PROOF';
      $('.continueDefult').hide();
      $('.saveContinue').removeClass('hideSaveCont');
      $('.sliderclass').removeClass('activeslide');
      $('.flxy_doc_block').removeClass('flxy_none');
      $('.flxy_web-blockcont').addClass('flxy_none');
      $.ajax({
        url: '<?php echo base_url('/getActiveUploadImages')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{DOC_CUST_ID:DOC_CUST_ID,DOC_RESV_ID:DOC_RESV_ID},
        dataType:'json',
        success:function(respn){
          console.log(respn,"SDF");
          generaListImage(respn);
        }
      });
    }else{
      $('#fileUpload').val('');
      $.ajax({
        url: '<?php echo base_url('/getVaccinUploadImages')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{VACC_CUST_ID:DOC_CUST_ID,VACC_RESV_ID:DOC_RESV_ID},
        dataType:'json',
        success:function(respn){
          console.log(respn,"SDF");
          if(respn!=''){
            $('*#vaccinePreview').remove();
            var jsonFrmt = respn[0];
            var check = jsonFrmt.VACC_DETAILS;
            if(check=='FULLY'){
              $('.radioCheck:eq(0)').prop('checked',true);
            }else if(check=='EXMP'){
              $('.radioCheck:eq(1)').prop('checked',true);
            }else if(check=='PROCE'){
              $('.radioCheck:eq(2)').prop('checked',true);
            }
            $('#VACC_LAST_DT').val(jsonFrmt.VACC_LAST_DT);
            $('#VACC_NAME').val(jsonFrmt.VACC_NAME);
            $('#VACC_ISSUED_COUNTRY').val($.trim(jsonFrmt.VACC_ISSUED_COUNTRY)).selectpicker('refresh');
            var filePath = jsonFrmt.VACC_FILE_PATH;
            if(filePath!=''){
              var arrayPath = filePath.split(",");
              $.each(arrayPath,function(i){
                  $('.previewClass').append('<span id="vaccinePreview"><span id="'+arrayPath[i]+'" class="vaccdelete"><i class="fa-solid fa-xmark"></i></span><img src="<?php echo $folderPath;?>'+'/'+arrayPath[i]+'" id=""></span>');
              });
            }
            $('#VACC_DOC_SAVED').val(filePath);
          }
        }
      });
      $('#vaccineModal').modal('show');
    }
  }

  function browseFile(){
    $('#formFile').trigger('click');
  }

  var jcrop_api='';
  function action(){
    jcrop_api = $.Jcrop('#croppingbox', {
      aspectRatio: 1,
      boxWidth: 550,
      boxHeight: 400,
      onSelect: function(c){
        size = {x:c.x,y:c.y,w:c.w,h:c.h}; 
      },
      }, function () {
          jcrop_api = this;    
      });
  }

  var cropPathImage='';
  $(document).on('click','#clickCropping',function(){
    $(this).addClass('active');
    $('#croping').show();
    action();
    jcrop_api.setImage(''+cropPathImage+'');  
  });

  function loadFile(event){
    $('#croping').hide();
    var file = event.target.files[0];
    $('#imageCropping').modal('show');
    var formData = new FormData();                     
    formData.append('file', file); //append file to formData object
    $.ajax({
        url: "<?php echo base_url('/imageUpload')?>",
        type: "POST",
        data: formData,
        processData: false, //prevent jQuery from converting your FormData into a string
        contentType: false, //jQuery does not add a Content-Type header for you
        success: function (respn) {
          console.log($('#croppingbox'),"Jcrop");
          var imagePath="<?php echo $folderPath;?>";
          console.log(imagePath+respn,"Sdfsdf");
          if(jcrop_api!=''){
            jcrop_api.destroy();
            jcrop_api.setImage(imagePath+'/'+respn);
          }
          $('#croppingbox').attr('src',imagePath+'/'+respn);
          cropPathImage=imagePath+'/'+respn;
        }
    });
  }

  $(document).on('click','#croping,#saveCropping',function(){
      var img = $("#croppingbox").attr('src');
      var formSerialization = $('#documentDetailForm').serializeArray();
      if($('#clickCropping').hasClass('active')){
        formSerialization.push({name:'x',value:Math.round(size.x)},{name:'y',value:Math.round(size.y)},{name:'w',value:Math.round(size.w)},{name:'h',value:Math.round(size.h)},{name:'img',value:img},{name:'mode',value:'C'});
      }else{
        formSerialization.push({name:'img',value:img},{name:'mode',value:'N'});
      }
      $('#imageCropping').modal('hide');
      $.ajax({
        url: '<?php echo base_url('/croppingImage')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:formSerialization,
        dataType:'json',
        success:function(respn){
          if(respn.SUCCESS==1){
            var image = respn['RESPONSE']['OUTPUT']['IMAGEPATH'];
            $("#cropped_img").attr('src','<?php echo $folderPath;?>'+'/'+image);
            $('#clickCropping').removeClass('active');
            var listImage = respn['RESPONSE']['OUTPUT'];
            generaListImage(listImage);
          }else{
            $('#errorModal').show();
            var ERROR = respn['RESPONSE']['ERROR'];
            var error='<ul>';
            $.each(ERROR,function(ind,data){
              console.log(data,"SDF");
              error+='<li>'+data+'</li>';
            });
            error+='<ul>';
            $('#formErrorMessage').html(error);
          }
        }
      });
  });

  $(document).on('click','.linkImg',function(){
    $('.activeLink').removeClass('activeLink');
    $(this).addClass('activeLink');
    var linkTag = $(this).find('img').attr('src');
    $("#cropped_img").attr('src',linkTag);
  });

  function generaListImage(listImage){
    var liList='';
    $.each(listImage,function(inx,data){
      if($.isNumeric(inx)){
        inx==0 ? $("#cropped_img").attr('src','<?php echo $folderPath;?>'+'/'+data['DOC_FILE_PATH']) : '';
        var activeClass = (inx==0 ? 'activeLink linkImg':'linkImg');
        liList+='<li data_id="'+data['DOC_ID']+'" class="'+activeClass+'"><img id="imagesmall" class="card-img-top" src="<?php echo $folderPath;?>'+'/'+data['DOC_FILE_PATH']+'"></li>';
      }
    });
    $('#listImagePresentDb').html(liList);
  }

  $(document).on('click','.deleteUploadImage',function(){
    var sysid = $('.activeLink').attr('data_id');
    $.ajax({
        url: '<?php echo base_url('/deleteUploadImages')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{sysid:sysid},
        dataType:'json',
        success:function(respn){
          var element = $('.activeLink');
          element.next().addClass('activeLink');
          element.remove();
        }
      });
  });

  function runCountryList(){
    $.ajax({
        url: '<?php echo base_url('/countryList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        // dataType:'json',
        async:false,
        success:function(respn){
          $('#CUST_COUNTRY,#VACC_ISSUED_COUNTRY').html(respn).selectpicker('refresh');
          $('#CUST_COUNTRY').val(countryCode).selectpicker('refresh');
        }
    });
  }
  $(document).on('change','#CUST_COUNTRY',function(){
    var ccode = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/stateList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{ccode:ccode},
        // dataType:'json',
        success:function(respn){
          $('#CUST_STATE').html(respn).selectpicker('refresh');
        }
    });
  });
  $(document).on('change','#CUST_STATE',function(){
    var scode = $(this).val();
    var ccode = $('#CUST_COUNTRY').find('option:selected').val();
    $.ajax({
        url: '<?php echo base_url('/cityList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{ccode:ccode,scode:scode},
        // dataType:'json',
        success:function(respn){
            $('*#CUST_CITY').html(respn).selectpicker('refresh');
        }
    });
  });

  $(document).on('change','#CUST_COUNTRY',function(){
    $('#CUST_CITY').html('<option value="">Select</option>').selectpicker('refresh');
  });

  $(document).on('click','#updateVaccine',function(){
    var formData = new FormData($('#vaccineForm')[0]);   
    formData.append('DELETEIMAGE', formImageDeletArr);   
    console.log(formData,"ACTION");               
    $.ajax({
      url: '<?php echo base_url('/updateVaccineReport')?>',
      type: "post",
      headers: {'X-Requested-With': 'XMLHttpRequest'},
      data: formData,
      processData: false, //prevent jQuery from converting your FormData into a string
      contentType: false, //jQuery does not add a Content-Type header for you
      dataType:'json',
      success:function(respn){
        console.log(respn,"SDF");
        $('#vaccineModal').modal('hide');
        if(respn.SUCCESS!='1'){
          $('#errorModal').show();
          var ERROR = respn['RESPONSE']['ERROR'];
          var error='<ul>';
          $.each(ERROR,function(ind,data){
            console.log(data,"SDF");
            error+='<li>'+data+'</li>';
          });
          error+='<ul>';
          $('#formErrorMessage').html(error);
        }else{
          
        }
      }
    });
  });

  function updateCustomer(){
    var formSerialization = $('#documentDetailForm,#documentDetailForm1').serializeArray();
    $.ajax({
        url: '<?php echo base_url('/updateCustomerData')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:formSerialization,
        dataType:'json',
        success:function(respn){
          if(respn.SUCCESS!='1'){
            $('#errorModal').show();
            var ERROR = respn['RESPONSE']['ERROR'];
            var error='<ul>';
            $.each(ERROR,function(ind,data){
              console.log(data,"SDF");
              error+='<li>'+data+'</li>';
            });
            error+='<ul>';
            $('#formErrorMessage').html(error);
          }else{
            sliderWebWid('');
            var object = respn['RESPONSE']['OUTPUT'];
            updateStatuIconButton(object);
          }
        }
    });
  }

  function checkStatusUploadFiles(){
    $.ajax({
      url: '<?php echo base_url('/checkStatusUploadFiles')?>',
      type: "post",
      headers: {'X-Requested-With': 'XMLHttpRequest'},
      data:{custid:custid,resrid:resrid},
      dataType:'json',
      success:function(respn){
        var jsonForm = respn[0];
        // console.log(jsonForm.TOTAL_PROOF,"ACTION");
        if(jsonForm.TOTAL_PROOF>0 && jsonForm.TOTAL_VACC>0){
          $('.document-padding-done').html('<i class="fa-solid fa-circle-check me-1"></i> Document verified');
        }
        updateStatuIconButton(jsonForm);
      }
    });
  }
  
  function uploadVaccine(input){
    if (input.files) {
        var filesAmount = input.files.length;
        for (i = 0; i < filesAmount; i++) {
            var reader = new FileReader();
            reader.onload = function(event) {
                $('.previewClass').append('<span id="vaccinePreview"><img src="'+event.target.result+'" id=""></span>');
            }
            reader.readAsDataURL(input.files[i]);
        }
    }
  }

  function updateStatuIconButton(object){
    if(object.TOTAL_PROOF>0){
        $('.flxy_doc_prof').removeClass('flxy_orng').addClass('flxy_green').text('Uploaded');
      }else{
        $('.flxy_doc_prof').removeClass('flxy_green').addClass('flxy_orng').text('Pending');
      }
      if(object.TOTAL_VACC>0){
        $('.flxy_doc_vacc').removeClass('flxy_orng').addClass('flxy_green').text('Uploaded');
      }else{
        $('.flxy_doc_vacc').removeClass('flxy_green').addClass('flxy_orng').text('Pending');
      }
  }

  function updateSignature(){
    var arrivTime = $('#RESV_ETA').val();
    var signature = $('#captureSignature').attr('src');
    var DOC_RESV_ID = $('[name="DOC_RESV_ID"]').val();
    $.ajax({
      url: '<?php echo base_url('/updateSignatureReserv')?>',
      type: "POST",
      headers: {'X-RequearrivTimested-With': 'XMLHttpRequest'},
      data:{RESV_ETA:arrivTime,RESV_ACCP_TRM_CONDI:AGREE_TERMS,signature:signature,DOC_RESV_ID:DOC_RESV_ID,modesignature:newImageSignature},
      dataType:'json',
      success:function(respn){
       console.log(respn,"ACTIONSES");
       $('#checkInConfirmWindow').modal('show');
      }
    });
  }
  
  var formImageDeletArr=[];
  $(document).on('click','.vaccdelete',function(){
    $(this).parent().remove();
    var name = $(this).attr('id');
    formImageDeletArr.push(name);
  });

  $(document).on('change','.radioCheck',function(){
    var value = $(this).attr('method');
    $('#VACC_DETAILS').val(value);
  });
  var signaturePad = '';
  $(document).on('click','#clickSignature',function(){
    $('#signaturWindow').modal('show');
    signaturePad = new SignaturePad(document.getElementById('signature-pad'));
  });
  $(document).on('click','#clearButton',function(){
    signaturePad.clear();
  });
  var newImageSignature=0;
  $(document).on('click','#submitSignatureImage',function(){
    var data = signaturePad.toDataURL('image/png');
    $('#captureSignature').attr("src",data);
    $('#clickSignature').hide();
    $('#signaturWindow').modal('hide');
    newImageSignature=1;
  });
  $(document).on('click','#captureSignature',function(){
    $('#clickSignature').click();
    var image = $('#captureSignature').attr("src");
    signaturePad.fromDataURL(image);
  });
  
  var AGREE_TERMS='<?php echo ($data['RESV_ACCP_TRM_CONDI']=='N' || $data['RESV_ACCP_TRM_CONDI']=='' ? 'N':'Y')?>';
  $(document).on('click','#agreeTerms',function(){
    var checked = $(this).is(':checked');
    if(checked){
      AGREE_TERMS='Y';
    }else{
      AGREE_TERMS='N';
    }
  });

  function confirmPrecheckin(){
    var DOC_RESV_ID = $('[name="DOC_RESV_ID"]').val();
    $.ajax({
      url: '<?php echo base_url('/confirmPrecheckinStatus')?>',
      type: "POST",
      headers: {'X-RequearrivTimested-With': 'XMLHttpRequest'},
      data:{DOC_RESV_ID:DOC_RESV_ID},
      dataType:'json',
      success:function(respn){
       console.log(respn,"ACTIONSES");
       window.location = '/reservationCheckin';
      }
    });
  }
</script>