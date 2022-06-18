<?=$this->extend("Layout/PrintView")?>

<?=$this->section("contentRender")?>

<style>
body {
    font-family: 'Helvetica';
    font-size: 15px !important;
}

.mb-2 {
    margin-bottom: 0.5rem !important;
}

.mb-4 {
    margin-bottom: 1.5rem !important;
}

.mb-5 {
    margin-bottom: 3rem !important;
}

.w-50 {
    width: 50% !important;
}

.w-70 {
    width: 70% !important;
}

.justify-content-between {
    justify-content: space-between !important;
}

.d-flex {
    display: flex !important;
}

.text-nowrap {
    white-space: nowrap !important;
}

td,
th {
    padding: 0.625rem 1.5rem;
    background-color: var(--bs-table-bg);
    border-bottom-width: 1px;
    box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
}

h5,
.h5 {
    font-size: 1.125rem;
}

html,
body {
    background: #fff !important;
}

body> :not(.invoice-print) {
    display: none !important;
}

.invoice-print {
    min-width: 768px !important;
    font-size: 15px !important;
}

.invoice-print svg {
    fill: #677788 !important;
}

.invoice-print * {
    border-color: rgba(38, 60, 85, 0.5) !important;
    color: #677788 !important;
}
</style>

<div class="invoice-print p-5">

    <!-- Doc Header  -->
    <div class="d-flex flex-row justify-content-between">
        <div class="">
            <div class="svg-illustration d-flex mb-3">
                <span class="app-brand-logo demo" style="height: auto;">

                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 187.6 228.8"
                        style="enable-background:new 0 0 187.6 228.8;" xml:space="preserve">
                        <style type="text/css">
                        .st0 {
                            fill-rule: evenodd;
                            clip-rule: evenodd;
                            fill: #18371C;
                        }

                        .st1 {
                            fill-rule: evenodd;
                            clip-rule: evenodd;
                            fill: url(#SVGID_1_);
                        }

                        .st2 {
                            fill-rule: evenodd;
                            clip-rule: evenodd;
                            fill: url(#SVGID_00000157279095315231982060000005211230105190083231_);
                        }

                        .st3 {
                            fill-rule: evenodd;
                            clip-rule: evenodd;
                            fill: url(#SVGID_00000012451031522960550390000012626224618914400140_);
                        }

                        .st4 {
                            fill-rule: evenodd;
                            clip-rule: evenodd;
                            fill: url(#SVGID_00000016070205341026246050000000039972798898783923_);
                        }

                        .st5 {
                            fill-rule: evenodd;
                            clip-rule: evenodd;
                            fill: url(#SVGID_00000052805987559678658880000016781226765522586761_);
                        }

                        .st6 {
                            fill-rule: evenodd;
                            clip-rule: evenodd;
                            fill: url(#SVGID_00000151513121863170215690000006295043811959257524_);
                        }

                        .st7 {
                            opacity: 0.15;
                            fill-rule: evenodd;
                            clip-rule: evenodd;
                            fill: #161920;
                            enable-background: new;
                        }

                        .st8 {
                            fill-rule: evenodd;
                            clip-rule: evenodd;
                            fill: url(#SVGID_00000043448609969055518270000012130186912031525559_);
                        }

                        .st9 {
                            fill-rule: evenodd;
                            clip-rule: evenodd;
                            fill: url(#SVGID_00000060011834154892075660000005155630478817162145_);
                        }
                        </style>
                        <path class="st0"
                            d="M4.9,126.3c0,37.8,93.7,55.2,120.3,11l23-47.2C140.6,105.6,24.2,155.9,5,61L4.9,97.4V126.3z">
                        </path>
                        <linearGradient id="SVGID_1_" gradientUnits="userSpaceOnUse" x1="185.8746" y1="238.7527"
                            x2="-43.7753" y2="111.4127" gradientTransform="matrix(1 0 0 -1 0 222.6383)">
                            <stop offset="0" style="stop-color:#E7E754"></stop>
                            <stop offset="1" style="stop-color:#5CB431"></stop>
                        </linearGradient>
                        <path class="st1"
                            d="M90.4,57.8h44.8c28.8,0,49.2-36,49.2-54H86.9C44.2,3.8,4.8,25.9,4.8,65.1v61.2C10.5,82.7,47.7,57.8,90.4,57.8z">
                        </path>
                        <linearGradient id="SVGID_00000001665901154226395070000017377122798177628309_"
                            gradientUnits="userSpaceOnUse" x1="177.4547" y1="227.0634" x2="-62.9653" y2="74.5734"
                            gradientTransform="matrix(1 0 0 -1 0 222.6383)">
                            <stop offset="0" style="stop-color:#E7E754"></stop>
                            <stop offset="1" style="stop-color:#2B893A"></stop>
                        </linearGradient>
                        <path
                            style="fill-rule:evenodd;clip-rule:evenodd;fill:url(#SVGID_00000001665901154226395070000017377122798177628309_);"
                            d="
M6.6,76.9v33.8c11.7-35.1,45.8-54.6,83.8-54.6h44.8c3.9,0,7.8-0.7,11.4-2c7.5-2.7,14-7.8,19.3-13.6c5.1-5.7,9.2-12.1,12.3-19.1
c1.9-4.3,3.3-8.8,4.1-13.4c-1.8,2.6-3.7,5.1-5.8,7.5c-5.3,5.9-11.8,10.9-19.3,13.6c-3.7,1.3-7.5,2-11.4,2c-14.9,0-44.3,0.9-59.3,0.9
C52.1,31.9,20.9,47.8,6.6,76.9z"></path>
                        <linearGradient id="SVGID_00000142161053142947608910000017353340766530899897_"
                            gradientUnits="userSpaceOnUse" x1="214.0623" y1="185.2717" x2="-12.5577" y2="36.1917"
                            gradientTransform="matrix(1 0 0 -1 0 222.6383)">
                            <stop offset="0" style="stop-color:#E7E754"></stop>
                            <stop offset="1" style="stop-color:#5CB431"></stop>
                        </linearGradient>
                        <path
                            style="fill-rule:evenodd;clip-rule:evenodd;fill:url(#SVGID_00000142161053142947608910000017353340766530899897_);"
                            d="
M86.1,137.3h27.6c2.3,0,10.5,0.5,6.7,6.6c1.8-2,3.5-4.2,4.8-6.6l22.9-47c3.2-6.6-4.8-7-7.7-7H82.5C39.7,83.5,5,118.2,4.9,161v63.3
c16.8,0,43.5-20.1,43.5-41.8C48.3,161.6,42,137.3,86.1,137.3z"></path>
                        <linearGradient id="SVGID_00000026845750694380275960000014168185075937240968_"
                            gradientUnits="userSpaceOnUse" x1="178.1774" y1="166.3042" x2="17.2574" y2="47.4942"
                            gradientTransform="matrix(1 0 0 -1 0 222.6383)">
                            <stop offset="0" style="stop-color:#E7E754"></stop>
                            <stop offset="1" style="stop-color:#2B893A"></stop>
                        </linearGradient>
                        <path
                            style="fill-rule:evenodd;clip-rule:evenodd;fill:url(#SVGID_00000026845750694380275960000014168185075937240968_);"
                            d="
M122.3,138.1c0.4-0.5,0.7-1.1,1-1.7l11.7-24.1c-15.2-0.2-44-0.8-51.6-0.8c-4.2,0-8.5,0.2-12.7,0.8l0,0l-2.1,0.3
c-25.2,4-48.5,14.3-60.4,34.1c-0.9,4.7-1.4,9.5-1.4,14.3v17.6c9.9-25.1,35.9-37.7,64.4-42.2l2.1-0.3l0,0c4.2-0.6,8.5-0.8,12.7-0.8
h27.6c1.5,0,3,0.2,4.5,0.5C119.7,136,121.2,136.9,122.3,138.1z"></path>
                        <linearGradient id="SVGID_00000110460869155740343570000017850948155766471613_"
                            gradientUnits="userSpaceOnUse" x1="35.4944" y1="-52.6618" x2="42.5244" y2="115.9482"
                            gradientTransform="matrix(1 0 0 -1 0 222.6383)">
                            <stop offset="0" style="stop-color:#E7E754"></stop>
                            <stop offset="1" style="stop-color:#2B893A"></stop>
                        </linearGradient>
                        <path
                            style="fill-rule:evenodd;clip-rule:evenodd;fill:url(#SVGID_00000110460869155740343570000017850948155766471613_);"
                            d="
M4.9,202v22.3c16.8,0,43.5-20.1,43.5-41.8c0-18.3-4.8-39.1,23.3-44.1C51.6,141.7,4.4,153.3,4.9,202L4.9,202z M75,137.9L75,137.9z">
                        </path>
                        <linearGradient id="SVGID_00000182513730796094447060000010748656252746595768_"
                            gradientUnits="userSpaceOnUse" x1="14.5765" y1="-41.5845" x2="25.5865" y2="96.7855"
                            gradientTransform="matrix(1 0 0 -1 0 222.6383)">
                            <stop offset="0" style="stop-color:#E7E754"></stop>
                            <stop offset="1" style="stop-color:#5CB431"></stop>
                        </linearGradient>
                        <path
                            style="fill-rule:evenodd;clip-rule:evenodd;fill:url(#SVGID_00000182513730796094447060000010748656252746595768_);"
                            d="
M6.5,222.6c6.1-0.5,12.7-3.4,18.4-7c2.8-8.5,2-21.1,0.9-37.1s10.9-26.9,11-27C19,161.2,6.5,177.3,6.5,202.1L6.5,222.6z">
                        </path>
                        <path class="st7" d="M49.9,154.2c2.6-7.5,8.5-13.5,21.8-15.8c-19.8,3.2-65.8,14.5-66.8,61.4C5.3,192.7,10,170.1,49.9,154.2
L49.9,154.2z M75,137.9L75,137.9z"></path>
                        <linearGradient id="SVGID_00000005267255372703103320000011831359240645276035_"
                            gradientUnits="userSpaceOnUse" x1="1.1712" y1="109.9574" x2="184.1612" y2="208.8374"
                            gradientTransform="matrix(1 0 0 -1 0 222.6383)">
                            <stop offset="0" style="stop-color:#2B893A"></stop>
                            <stop offset="0.22" style="stop-color:#2D8A3B"></stop>
                            <stop offset="0.3" style="stop-color:#348E3F"></stop>
                            <stop offset="0.36" style="stop-color:#3F9447"></stop>
                            <stop offset="0.41" style="stop-color:#509D51"></stop>
                            <stop offset="0.45" style="stop-color:#66A85F"></stop>
                            <stop offset="0.48" style="stop-color:#82B76F"></stop>
                            <stop offset="0.52" style="stop-color:#A2C884"></stop>
                            <stop offset="0.55" style="stop-color:#C8DC9B"></stop>
                            <stop offset="0.57" style="stop-color:#F1F2B4"></stop>
                            <stop offset="0.57" style="stop-color:#F7F5B8"></stop>
                            <stop offset="1" style="stop-color:#FDF160"></stop>
                        </linearGradient>
                        <path
                            style="fill-rule:evenodd;clip-rule:evenodd;fill:url(#SVGID_00000005267255372703103320000011831359240645276035_);"
                            d="
M178.2,21.3c-3.1,7-7.2,13.4-12.3,19.1c-5.3,5.9-11.8,10.9-19.3,13.6c-3.7,1.3-7.5,2-11.4,2H90.4c-30.1,0-57.7,12.2-73.7,34.6
c-4.4,6.1-7.8,12.9-10.1,20v-0.3c-0.7,2.6-1.3,5.2-1.8,7.9v8.1c1.5-12.3,6-24,13-34.1C33.4,70,60.6,57.8,90.5,57.8h44.8
c28.5,0,48.8-35.3,49.2-53.4l-1.8,1.4C182,11.1,180.5,16.4,178.2,21.3z"></path>
                        <linearGradient id="SVGID_00000099647703509215640730000017861532716248486312_"
                            gradientUnits="userSpaceOnUse" x1="0.5407" y1="39.9869" x2="117.6607" y2="90.8269"
                            gradientTransform="matrix(1 0 0 -1 0 222.6383)">
                            <stop offset="0" style="stop-color:#2B893A"></stop>
                            <stop offset="0.14" style="stop-color:#46974B"></stop>
                            <stop offset="0.43" style="stop-color:#8BBC75"></stop>
                            <stop offset="0.86" style="stop-color:#F7F5B8"></stop>
                            <stop offset="0.89" style="stop-color:#F7F5B4"></stop>
                            <stop offset="0.92" style="stop-color:#F8F4A9"></stop>
                            <stop offset="0.95" style="stop-color:#F9F396"></stop>
                            <stop offset="0.98" style="stop-color:#FBF27B"></stop>
                            <stop offset="1" style="stop-color:#FDF160"></stop>
                        </linearGradient>
                        <path
                            style="fill-rule:evenodd;clip-rule:evenodd;fill:url(#SVGID_00000099647703509215640730000017861532716248486312_);"
                            d="
M28,154.9c14.7-10.6,32.8-14.7,43.5-16.4h0.1c1.1-0.2,2.2-0.4,3.4-0.5l0,0c3.7-0.4,7.3-0.6,11-0.6h27.6c2.3,0,10.5,0.5,6.7,6.6l0,0
c0.5-0.6,1.1-1.3,1.7-2s0.9-1.2,1.4-1.8c-0.2-0.8-0.5-1.5-0.9-2.2l-0.2,0.2c-1.1-1.2-2.6-2-4.2-2.3c-1.5-0.3-3-0.5-4.5-0.5H86.1
c-4.2,0-8.5,0.2-12.7,0.8l0,0l-2.1,0.3c-16.9,2.7-32.9,8.2-45.3,17.5c-8.6,6.3-15.2,14.8-19.2,24.7v-0.2c-0.7,2.4-1.4,5-2.1,7.6
v14.8C5,178.5,15.2,164.2,28,154.9z"></path>
                    </svg>

                </span>
                <span class="app-brand-text demo menu-text fw-bold ms-2">FlexiGuest</span>
            </div>
        </div>
        <div>
            <h3 class="pt-2">Profile Data</h3>
        </div>
        <div>
            <div class="mb-2">
                <span>Date:</span>
                <span class="fw-semibold"><?=date('M d, Y H:i a'); ?></span>
            </div>
        </div>
    </div>

    <hr class="mb-5" />

    <div class="row">
        <div class="col-sm-6 w-50 table-responsive text-nowrap" style="float: left; display: inline;">
            <table class="table table-bordered" border="1" style="border-collapse:collapse">
                <tbody>
                    <tr>
                        <td class="pe-3"><b>Last:</b></td>
                        <td><?=$CUST_LAST_NAME?></td>
                    </tr>
                    <tr>
                        <td class="pe-3"><b>First / Middle:</b></td>
                        <td><?=$CUST_FIRST_NAME?> <?=$CUST_MIDDLE_NAME?> </td>
                    </tr>
                    <tr>
                        <td class="pe-3"><b>Language / title:</b></td>
                        <td><?=$CUST_LANG?> / <?=$CUST_TITLE?></td>
                    </tr>
                    <tr>
                        <td class="pe-3"><b>Gender:</b></td>
                        <td><?=$CUST_GENDER?></td>
                    </tr>
                    <tr>
                        <td class="pe-3"><b>VIP:</b></td>
                        <td><?=$CUST_VIP?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-sm-6 w-50" style="float: right; display: inline;">
            <table class="table table-bordered" border="1" style="border-collapse:collapse">
                <tbody>
                    <tr>
                        <td class="pe-3"><b>Nationality:</b></td>
                        <td><?=$CUST_NATIONALITY?></td>
                    </tr>
                    <tr>
                        <td class="pe-3"><b>Country:</b></td>
                        <td><?=$CUST_COUNTRY_DESC?></td>
                    </tr>
                    <tr>
                        <td class="pe-3"><b>Bus. Seg:</b></td>
                        <td><?=$CUST_BUS_SEGMENT?></td>
                    </tr>
                    <tr>
                        <td class="pe-3"><b>Client Id:</b></td>
                        <td><?=$CUST_CLIENT_ID?></td>
                    </tr>
                    <tr>
                        <td class="pe-3"><b>Date Of Birth:</b></td>
                        <td><?=$CUST_DOB?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="table-responsive mb-4" style="padding-top: 30px;">

        <h5>Address Information:</h5>
        <table class="table table-bordered mb-2" border="1" style="border-collapse:collapse; width: 94%;">
            <thead>
                <tr>
                    <th>Address1</th>
                    <th>Address2</th>
                    <th>Address3</th>
                    <th>City</th>
                    <th>Country</th>
                    <th>State</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?=$CUST_ADDRESS_1?></td>
                    <td><?=$CUST_ADDRESS_2?></td>
                    <td><?=$CUST_ADDRESS_3?></td>
                    <td><?=$CUST_CITY_DESC?></td>
                    <td><?=$CUST_COUNTRY_DESC?></td>
                    <td><?=$CUST_STATE_DESC?></td>
                </tr>
            </tbody>
        </table>

        <div class="col-sm-8 w-70 table-responsive text-nowrap">
            <table class="table table-bordered" border="1" style="border-collapse:collapse">
                <thead>
                    <tr>
                        <th>Postal Code</th>
                        <th>Created On</th>
                        <th>Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?=$CUST_POSTAL_CODE?></td>
                        <td><?=$CUST_CREATE_DT?></td>
                        <td><?=$CUST_UPDATE_DT?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row d-flex justify-content-between mb-4">
        <div class="col-sm-7 w-70 table-responsive text-nowrap">

            <h5>Communication Information:</h5>
            <table class="table table-bordered" border="1" style="border-collapse:collapse">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>EMAIL</td>
                        <td><?=$CUST_ADDRESS_2?></td>
                    </tr>
                    <?php if(!empty(trim($CUST_MOBILE))) { ?>
                    <tr>
                        <td>MOBILE</td>
                        <td><?=$CUST_MOBILE?></td>
                    </tr>
                    <?php } ?>
                    <?php if(!empty(trim($CUST_PHONE))) { ?>
                    <tr>
                        <td>PHONE</td>
                        <td><?=$CUST_PHONE?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
(function() {
    window.print();
})();
</script>

<?=$this->endSection()?>