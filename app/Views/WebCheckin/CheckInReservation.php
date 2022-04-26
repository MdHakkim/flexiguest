<!DOCTYPE html>
<?php
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
  
?>
<html lang="en"
  class="light-style layout-navbar-fixed layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="assets/"
  data-template="vertical-menu-template">

  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />
    <?= $this->include('Layout/HeaderScript') ?>
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
        <div class="row justify-content-center">
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
                          <p class="card-text">
                            <i class="fa-solid <?php echo $icon;?> me-1"></i><?php echo $documentmess;?></p>
                        </div>
                        <ul class="list-group list-group-flush flxy_web-ul">
                          <li class="list-group-item text-flxy">
                            <div class="flxy-data">Name</div>
                            <div class="flxy-data">: <?php echo $data['FULLNAME'];?></div>
                          </li>
                          <li class="list-group-item text-flxy">
                            <div class="flxy-data">Document Status</div>
                            <div class="flxy-data">: <span class="flxy_doc_st <?php echo $statusClass;?>">Pending</span></div></li>
                          <li class="list-group-item text-flxy">
                            <div class="flxy-data">Vaccine Certificate</div>
                            <div class="flxy-data">: <span class="flxy_doc_st <?php echo $statusClass;?>">Pending</span></div></li>
                          <li class="list-group-item d-grid ">
                            <button class="btn btn-dark" type="button">View & Upload Documents</button>
                          </li>
                          <li class="list-group-item d-grid">
                            <button class="btn btn-dark" type="button">Vaccination Details</button>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="flxy_web-footer text-end">
                  <button type="button" onClick="sliderWebWid('P')" class="btn btn-blue btn-primary"><i class="fa-solid fa-chevron-left"></i> Back</button>
                  <button type="button" onClick="sliderWebWid('N')" class="btn btn-blue btn-primary">Continue <i class="fa-solid fa-chevron-right"></i></button>
                </div>
              </div>
          </div>
        </div>
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
        <!-- <div>
          <a
            href="#"
            target="_blank"
            class="footer-link me-4"
            >Documentation</a
          >
          <a href="#" target="_blank" class="footer-link d-none d-sm-inline-block"
            >Support</a
          >
        </div> -->
      </div>
    </footer>
    <?= $this->include('Layout/FooterScript') ?>
  </body>
</html>

<script>
  function sliderWebWid(param){
    if(param=='N'){
      $('.sliderclass').removeClass('activeslide').next().addClass('activeslide');
    }else{
      $('.sliderclass').removeClass('activeslide').prev().addClass('activeslide');
    }
  }
</script>
