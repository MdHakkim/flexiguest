<?= $this->extend('Layout/AppView') ?>
<?= $this->section('contentRender') ?>

<style>
.tagify__input {
    padding-left: 6px;
}

.tagify__tag>div {
    cursor: pointer;
}

.table-hover>tbody>tr:hover {
    cursor: pointer;
}

.table-warning {
    color: #000 !important;
}
</style>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <?= $this->include('Layout/ErrorReport') ?>
        <?= $this->include('Layout/SuccessReport') ?>

        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Masters / Rate Codes / </span>
            <?php echo isset($rateCodeID) ? 'Edit' : 'Add'; ?>
            Rate Code</h4>
        <!-- Default -->
        <div class="row">

            <!-- Validation Wizard -->
            <div class="col-12 mb-4">
                <div id="wizard-validation" class="bs-stepper mt-2">
                    <div class="bs-stepper-header">
                        <div class="step" data-target="#rate-header-validation">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle">1</span>
                                <span class="bs-stepper-label">Rate Header</span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#rate-detail-validation">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle">2</span>
                                <span class="bs-stepper-label">Rate Detail</span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#negotiated-rate-validation">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle">3</span>
                                <span class="bs-stepper-label">Negotiated</span>
                            </button>
                        </div>
                    </div>
                    <div class="bs-stepper-content">
                        <form id="rateCode-submit-form" onSubmit="return false">
                            <!-- Account Details -->
                            <div id="rate-header-validation" class="content">

                                <div class="row g-3">

                                    <div class="col-md-7">
                                        <div class="row mb-3">
                                            <label for="RT_CD_CODE" class="col-form-label col-md-3"><b>Rate
                                                    Code *</b></label>
                                            <div class="col-md-5">
                                                <input class="form-control" type="text" value="" maxlength="10"
                                                    placeholder="eg: OTA" id="RT_CD_CODE" name="RT_CD_CODE" />
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="RT_CD_DESC" class="col-form-label col-md-3"><b>Description
                                                    *</b></label>
                                            <div class="col-md-9">
                                                <input class="form-control" type="search" value="" maxlength="50"
                                                    placeholder="eg: Online Travel Agent" id="RT_CD_DESC"
                                                    name="RT_CD_DESC" />
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="RT_CT_ID" class="col-form-label col-md-3"><b>Rate
                                                    Category *</b></label>
                                            <div class="col-md-9">
                                                <input id="RT_CT_ID" name="RT_CT_ID"
                                                    class="form-control TagifyRateCatList"
                                                    value="<?php echo isset($rateCodeDetails['RT_CT_CODE']) ? $rateCodeDetails['RT_CT_CODE'] : ''; ?>"
                                                    placeholder="Select a value" />

                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="html5-url-input" class="col-form-label col-md-3">Rate
                                                Class</label>
                                            <div class="col-md-5">
                                                <input class="form-control" type="text" id="RT_CL_CODE"
                                                    name="RT_CL_CODE" readonly />
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="RT_CD_FOLIO" class="col-form-label col-md-3">Folio
                                                Text</label>
                                            <div class="col-md-9">
                                                <input class="form-control" type="tel" value="" id="RT_CD_FOLIO"
                                                    name="RT_CD_FOLIO" />
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="RT_CD_BEGIN_SELL_DT" class="col-form-label col-md-3"><b>Begin
                                                    Sell
                                                    Date *</b></label>
                                            <div class="col-md-5">
                                                <input class="form-control dateField" type="text"
                                                    placeholder="d-Mon-yyyy" id="RT_CD_BEGIN_SELL_DT"
                                                    name="RT_CD_BEGIN_SELL_DT" />
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="RT_CD_END_SELL_DT" class="col-form-label col-md-3"><b>End Sell
                                                    Date *</b></label>
                                            <div class="col-md-5">
                                                <input class="form-control dateField" type="text"
                                                    placeholder="d-Mon-yyyy" id="RT_CD_END_SELL_DT"
                                                    name="RT_CD_END_SELL_DT" />
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="MK_CD_ID" class="col-form-label col-md-3">Market</label>
                                            <div class="col-md-7">
                                                <select id="MK_CD_ID" name="MK_CD_ID"
                                                    class="select2 form-select form-select-lg" data-allow-clear="true">
                                                    <?=$marketCodeOptions?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="SOR_ID" class="col-form-label col-md-3">Source</label>
                                            <div class="col-md-5">
                                                <select id="SOR_ID" name="SOR_ID"
                                                    class="select2 form-select form-select-lg" data-allow-clear="true">
                                                    <?=$sourceCodeOptions?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="RT_CD_DIS_SEQ" class="col-form-label col-md-3">Display
                                                Sequence</label>
                                            <div class="col-md-3">
                                                <input type="number" name="RT_CD_DIS_SEQ" id="RT_CD_DIS_SEQ"
                                                    class="form-control" min="0" placeholder="eg: 3" />
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="RT_CD_ROOM_TYPES" class="col-form-label col-md-3"><b>Room
                                                    Types *</b></label>
                                            <div class="col-md-9">
                                                <input id="RT_CD_ROOM_TYPES" name="RT_CD_ROOM_TYPES"
                                                    class="form-control TagifyRoomTypeList" />
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="RT_CD_PACKAGES" class="col-form-label col-md-3">Package</label>
                                            <div class="col-md-9">
                                                <input id="RT_CD_PACKAGES" name="RT_CD_PACKAGES"
                                                    class="form-control TagifyPackageCodeList" />
                                                <small><?php echo isset($rateCodeID) ? 'Click each tag to edit Packgage Rates' : ''; ?></small>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="RT_CD_COMMISSION" class="col-form-label col-md-3">Commission
                                                %</label>
                                            <div class="col-md-3">
                                                <div class="input-group input-group-merge">
                                                    <input type="number" name="RT_CD_COMMISSION" id="RT_CD_COMMISSION"
                                                        class="form-control" min="0.00" step=".01"
                                                        placeholder="eg: 3" />
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="RT_CD_ADDITION" class="col-form-label col-md-3">Addition</label>
                                            <div class="col-md-3">
                                                <input type="number" name="RT_CD_ADDITION" id="RT_CD_ADDITION"
                                                    class="form-control" min="0.00" step=".01" placeholder="eg: 3" />
                                            </div>

                                            <label for="RT_CD_MULTIPLICATION" class="col-form-label col-md-3"
                                                style="text-align: right;">Multiplication</label>
                                            <div class="col-md-3">
                                                <input type="number" name="RT_CD_MULTIPLICATION"
                                                    id="RT_CD_MULTIPLICATION" class="form-control" min="0.00" step=".01"
                                                    placeholder="eg: 3" />
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="RT_CD_MIN_OCCUPANCY" class="col-form-label col-md-3">Min
                                                Occupancy</label>
                                            <div class="col-md-3">
                                                <input type="number" name="RT_CD_MIN_OCCUPANCY" id="RT_CD_MIN_OCCUPANCY"
                                                    class="form-control" min="0" placeholder="eg: 3" />
                                            </div>

                                            <label for="RT_CD_MAX_OCCUPANCY" class="col-form-label col-md-3"
                                                style="text-align: right;">Max
                                                Occupancy</label>
                                            <div class="col-md-3">
                                                <input type="number" name="RT_CD_MAX_OCCUPANCY" id="RT_CD_MAX_OCCUPANCY"
                                                    class="form-control" min="0" placeholder="eg: 3" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <div class="border rounded p-3 mb-3">
                                            <h6>Transaction Details</h6>
                                            <div class="row g-3 mb-3">
                                                <label for="TR_CD_ID" class="col-form-label col-md-4"><b>Transaction
                                                        Code *</b></label>
                                                <div class="col-md-8">
                                                    <select id="TR_CD_ID" name="TR_CD_ID"
                                                        class="select2 form-select form-select-lg">
                                                        <?=$transactionCodeOptions?>
                                                    </select>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="switch">
                                                        <input type="checkbox" class="switch-input"
                                                            id="RT_CD_TAX_INCLUDED" name="RT_CD_TAX_INCLUDED"
                                                            value="1" />
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bx-check"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                                <i class="bx bx-x"></i>
                                                            </span>
                                                        </span>
                                                        <span class="switch-label">Tax Included</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <hr class="mt-0" />

                                            <div class="row g-3 mb-3">
                                                <label for="PKG_TR_CD_ID" class="col-form-label col-md-4">Package
                                                    Transaction Code </label>
                                                <div class="col-md-8">
                                                    <select id="PKG_TR_CD_ID" name="PKG_TR_CD_ID"
                                                        class="select2 select2Pkg form-select form-select-lg"
                                                        data-allow-clear="true">
                                                        <?=$pkgTransactionCodeOptions?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="border rounded p-3 mb-3">
                                            <h6>Components</h6>
                                            <div class="row g-3 mb-3">
                                                <div class="col-md-6">
                                                    <label class="switch">
                                                        <input type="checkbox" class="switch-input" disabled />
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bx-check"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                                <i class="bx bx-x"></i>
                                                            </span>
                                                        </span>
                                                        <span class="switch-label">Package</span>
                                                    </label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="switch">
                                                        <input type="checkbox" class="switch-input" id="RT_CD_DAY_USE"
                                                            name="RT_CD_DAY_USE" value="1" />
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bx-check"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                                <i class="bx bx-x"></i>
                                                            </span>
                                                        </span>
                                                        <span class="switch-label">Day Use</span>
                                                    </label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="switch">
                                                        <input type="checkbox" class="switch-input"
                                                            id="RT_CD_NEGOTIATED" name="RT_CD_NEGOTIATED" value="1" />
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bx-check"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                                <i class="bx bx-x"></i>
                                                            </span>
                                                        </span>
                                                        <span class="switch-label">Negotiated</span>
                                                    </label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="switch">
                                                        <input type="checkbox" class="switch-input"
                                                            id="RT_CD_COMPLIMENTARY" name="RT_CD_COMPLIMENTARY"
                                                            value="1" />
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bx-check"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                                <i class="bx bx-x"></i>
                                                            </span>
                                                        </span>
                                                        <span class="switch-label">Complimentary</span>
                                                    </label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="switch">
                                                        <input type="checkbox" class="switch-input"
                                                            id="RT_CD_SUPPRESS_RATE" name="RT_CD_SUPPRESS_RATE"
                                                            value="1" />
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bx-check"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                                <i class="bx bx-x"></i>
                                                            </span>
                                                        </span>
                                                        <span class="switch-label">Suppress Rate</span>
                                                    </label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="switch">
                                                        <input type="checkbox" class="switch-input" id="RT_CD_HOUSE_USE"
                                                            name="RT_CD_HOUSE_USE" value="1" />
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bx-check"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                                <i class="bx bx-x"></i>
                                                            </span>
                                                        </span>
                                                        <span class="switch-label">House Use</span>
                                                    </label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="switch">
                                                        <input type="checkbox" class="switch-input"
                                                            id="RT_CD_PRINT_RATE" name="RT_CD_PRINT_RATE" value="1" />
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bx-check"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                                <i class="bx bx-x"></i>
                                                            </span>
                                                        </span>
                                                        <span class="switch-label">Print Rate</span>
                                                    </label>
                                                </div>
                                            </div>

                                        </div>

                                        <?php if(isset($rateCodeID)) { ?>

                                        <div class="text-center">

                                            <button type="button" onclick="submitForm('rateCode-submit-form')"
                                                class="btn btn-success">
                                                <i class="fa-solid fa-floppy-disk"></i>&nbsp; Save
                                            </button>&nbsp;

                                        </div>

                                        <?php } ?>


                                    </div>

                                    <div class="d-flex col-12 justify-content-between">
                                        <a class="btn btn-danger" href="<?php echo base_url('/rateCode')?>">
                                            <i class="fa-solid fa-ban"></i>&nbsp;
                                            <span class="d-none d-sm-inline-block">Cancel</span>
                                        </a>

                                        <button class="btn btn-primary btn-next">
                                            <span class="d-none d-sm-inline-block me-sm-1">Next</span>
                                            <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- Personal Info -->
                            <div id="rate-detail-validation" class="content">

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <div class="border rounded p-4 mb-3">
                                            <h6>Dates</h6>

                                            <div class="row mb-3">
                                                <label for="RT_CD_START_DT" class="col-form-label col-md-3"><b>Start
                                                        Date *</b></label>
                                                <div class="col-md-6">
                                                    <input class="form-control dateField" type="text"
                                                        placeholder="d-Mon-yyyy" id="RT_CD_START_DT"
                                                        name="RT_CD_START_DT" />
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="RT_CD_END_DT" class="col-form-label col-md-3"><b>End
                                                        Date *</b></label>
                                                <div class="col-md-6">
                                                    <input class="form-control dateField" type="text"
                                                        placeholder="d-Mon-yyyy" id="RT_CD_END_DT"
                                                        name="RT_CD_END_DT" />
                                                </div>
                                            </div><br />
                                            <div class="row mb-3">

                                                <div class="col-md-2">
                                                    <label class="switch">
                                                        <input type="checkbox" id="RT_CD_DT_DAYS_SUN"
                                                            name="RT_CD_DT_DAYS[]" class="switch-input RT_CD_DT_DAYS"
                                                            value="SUN" />
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bx-check"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                                <i class="bx bx-x"></i>
                                                            </span>
                                                        </span>
                                                        <span class="switch-label">Sun</span>
                                                    </label>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="switch">
                                                        <input type="checkbox" id="RT_CD_DT_DAYS_TUE"
                                                            name="RT_CD_DT_DAYS[]" class="switch-input RT_CD_DT_DAYS"
                                                            value="TUE" />
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bx-check"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                                <i class="bx bx-x"></i>
                                                            </span>
                                                        </span>
                                                        <span class="switch-label">Tue</span>
                                                    </label>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="switch">
                                                        <input type="checkbox" id="RT_CD_DT_DAYS_THU"
                                                            name="RT_CD_DT_DAYS[]" class="switch-input RT_CD_DT_DAYS"
                                                            value="THU" />
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bx-check"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                                <i class="bx bx-x"></i>
                                                            </span>
                                                        </span>
                                                        <span class="switch-label">Thu</span>
                                                    </label>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="switch">
                                                        <input type="checkbox" id="RT_CD_DT_DAYS_SAT"
                                                            name="RT_CD_DT_DAYS[]" class="switch-input RT_CD_DT_DAYS"
                                                            value="SAT" />
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bx-check"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                                <i class="bx bx-x"></i>
                                                            </span>
                                                        </span>
                                                        <span class="switch-label">Sat</span>
                                                    </label>
                                                </div>

                                                <div class="col-md-12">&nbsp;</div>

                                                <div class="col-md-2">
                                                    <label class="switch">
                                                        <input type="checkbox" id="RT_CD_DT_DAYS_MON"
                                                            name="RT_CD_DT_DAYS[]" class="switch-input RT_CD_DT_DAYS"
                                                            value="MON" />
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bx-check"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                                <i class="bx bx-x"></i>
                                                            </span>
                                                        </span>
                                                        <span class="switch-label">Mon</span>
                                                    </label>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="switch">
                                                        <input type="checkbox" id="RT_CD_DT_DAYS_WED"
                                                            name="RT_CD_DT_DAYS[]" class="switch-input RT_CD_DT_DAYS"
                                                            value="WED" />
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bx-check"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                                <i class="bx bx-x"></i>
                                                            </span>
                                                        </span>
                                                        <span class="switch-label">Wed</span>
                                                    </label>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="switch">
                                                        <input type="checkbox" id="RT_CD_DT_DAYS_FRI"
                                                            name="RT_CD_DT_DAYS[]" class="switch-input RT_CD_DT_DAYS"
                                                            value="FRI" />
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on">
                                                                <i class="bx bx-check"></i>
                                                            </span>
                                                            <span class="switch-off">
                                                                <i class="bx bx-x"></i>
                                                            </span>
                                                        </span>
                                                        <span class="switch-label">Fri</span>
                                                    </label>
                                                </div>


                                            </div>
                                        </div>

                                        <div class="border rounded mb-4">

                                            <!-- Checkboxes and Radios -->
                                            <div class="row row-bordered g-0">
                                                <div class="col-md p-4">
                                                    <h6 class="">Amounts</h6>

                                                    <div class="row mb-3">
                                                        <label for="RT_CD_DT_1_ADULT" class="col-form-label col-md-5"
                                                            style="text-align: right;"><b>1 Adult *</b></label>
                                                        <div class="col-md-7">
                                                            <input type="number" name="RT_CD_DT_1_ADULT"
                                                                id="RT_CD_DT_1_ADULT" class="form-control" min="0.00"
                                                                step=".01" placeholder="eg: 430.50" />
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="html5-color-input" class="col-form-label col-md-5"
                                                            style="text-align: right;">2 Adult</label>
                                                        <div class="col-md-7">
                                                            <input type="number" name="RT_CD_DT_2_ADULT"
                                                                id="RT_CD_DT_2_ADULT" class="form-control" min="0.00"
                                                                step=".01" placeholder="eg: 430.50" />
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="html5-color-input" class="col-form-label col-md-5"
                                                            style="text-align: right;">3 Adult</label>
                                                        <div class="col-md-7">
                                                            <input type="number" name="RT_CD_DT_3_ADULT"
                                                                id="RT_CD_DT_3_ADULT" class="form-control" min="0.00"
                                                                step=".01" placeholder="eg: 430.50" />
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="html5-color-input" class="col-form-label col-md-5"
                                                            style="text-align: right;">4 Adult</label>
                                                        <div class="col-md-7">
                                                            <input type="number" name="RT_CD_DT_4_ADULT"
                                                                id="RT_CD_DT_4_ADULT" class="form-control" min="0.00"
                                                                step=".01" placeholder="eg: 430.50" />
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="html5-color-input" class="col-form-label col-md-5"
                                                            style="text-align: right;">5 Adult</label>
                                                        <div class="col-md-7">
                                                            <input type="number" name="RT_CD_DT_5_ADULT"
                                                                id="RT_CD_DT_5_ADULT" class="form-control" min="0.00"
                                                                step=".01" placeholder="eg: 430.50" />
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="html5-color-input" class="col-form-label col-md-5"
                                                            style="text-align: right;">Extra Adult</label>
                                                        <div class="col-md-7">
                                                            <input type="number" name="RT_CD_DT_EXTRA_ADULT"
                                                                id="RT_CD_DT_EXTRA_ADULT" class="form-control"
                                                                min="0.00" step=".01" placeholder="eg: 430.50" />
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="html5-color-input" class="col-form-label col-md-5"
                                                            style="text-align: right;">Extra Child</label>
                                                        <div class="col-md-7">
                                                            <input type="number" name="RT_CD_DT_EXTRA_CHILD"
                                                                id="RT_CD_DT_EXTRA_CHILD" class="form-control"
                                                                min="0.00" step=".01" placeholder="eg: 430.50" />
                                                        </div>
                                                    </div>



                                                </div>
                                                <div class="col-md p-4">
                                                    <h6 class="">Children on Own</h6>

                                                    <div class="row mb-3">
                                                        <label for="html5-color-input" class="col-form-label col-md-5"
                                                            style="text-align: right;">1 Child</label>
                                                        <div class="col-md-7">
                                                            <input type="number" name="RT_CD_DT_1_CHILD"
                                                                id="RT_CD_DT_1_CHILD" class="form-control" min="0.00"
                                                                step=".01" placeholder="eg: 430.50" />
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="html5-color-input" class="col-form-label col-md-5"
                                                            style="text-align: right;">2 Children</label>
                                                        <div class="col-md-7">
                                                            <input type="number" name="RT_CD_DT_2_CHILD"
                                                                id="RT_CD_DT_2_CHILD" class="form-control" min="0.00"
                                                                step=".01" placeholder="eg: 430.50" />
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="html5-color-input" class="col-form-label col-md-5"
                                                            style="text-align: right;">3 Children</label>
                                                        <div class="col-md-7">
                                                            <input type="number" name="RT_CD_DT_3_CHILD"
                                                                id="RT_CD_DT_3_CHILD" class="form-control" min="0.00"
                                                                step=".01" placeholder="eg: 430.50" />
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="html5-color-input" class="col-form-label col-md-5"
                                                            style="text-align: right;">4 Children</label>
                                                        <div class="col-md-7">
                                                            <input type="number" name="RT_CD_DT_4_CHILD"
                                                                id="RT_CD_DT_4_CHILD" class="form-control" min="0.00"
                                                                step=".01" placeholder="eg: 430.50" />
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="border rounded p-4 mb-3">
                                            <h6>Attributes</h6>

                                            <div class="row mb-3">
                                                <label for="html5-password-input"
                                                    class="col-form-label col-md-3">Market</label>
                                                <div class="col-md-9">
                                                    <select id="RT_CD_DT_MK_CD_ID" name="RT_CD_DT_MK_CD_ID"
                                                        class="select2 form-select form-select-lg"
                                                        data-allow-clear="true">
                                                        <?=$marketCodeOptions?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="html5-number-input"
                                                    class="col-form-label col-md-3">Source</label>
                                                <div class="col-md-6">
                                                    <select id="RT_CD_DT_SOR_ID" name="RT_CD_DT_SOR_ID"
                                                        class="select2 form-select form-select-lg"
                                                        data-allow-clear="true">
                                                        <?=$sourceCodeOptions?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="html5-month-input" class="col-form-label col-md-3"><b>Room
                                                        Types *</b></label>
                                                <div class="col-md-9">
                                                    <select id="RT_CD_DT_ROOM_TYPES" name="RT_CD_DT_ROOM_TYPES[]"
                                                        class="select2 form-select form-select-lg" multiple>
                                                        <?php
                                                            if($roomTypeOptions != NULL) {
                                                                foreach($roomTypeOptions as $roomTypeOption)
                                                                {
                                                        ?> <option value="<?=$roomTypeOption['value']; ?>">
                                                            <?=$roomTypeOption['name'] .' | '. $roomTypeOption['desc']; ?>
                                                        </option>
                                                        <?php   }
                                                            }                                                            
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="html5-color-input"
                                                    class="col-form-label col-md-3">Packages</label>
                                                <div class="col-md-9">
                                                    <select id="RT_CD_DT_PACKAGES" name="RT_CD_DT_PACKAGES[]"
                                                        class="select2 form-select form-select-lg" multiple>
                                                        <?php
                                                            if($packageCodeOptions != NULL) {
                                                                foreach($packageCodeOptions as $packageCodeOption)
                                                                {
                                                        ?> <option value="<?=$packageCodeOption['value']; ?>">
                                                            <?=$packageCodeOption['name'] .' | '. $packageCodeOption['desc']; ?>
                                                        </option>
                                                        <?php   }
                                                            }                                                            
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                    <div class="col-md-6">

                                        <div class="border rounded p-4 mb-3">

                                            <div class="table-responsive text-nowrap">
                                                <table id="RD_Room_Types" class="table table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th class="all">Start</th>
                                                            <th class="all">End</th>
                                                            <th class="all">Room Types</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>

                                            <br />

                                            <input type="hidden" name="RT_CD_DT_ID" id="RT_CD_DT_ID"
                                                class="form-control" readonly />

                                            <button type="button" class="btn btn-primary add-rate-code-detail">
                                                <i class="fa-solid fa-circle-plus"></i>&nbsp; Add New
                                            </button>&nbsp;

                                            <button type="button" class="btn btn-success save-rate-code-detail">
                                                <i class="fa-solid fa-floppy-disk"></i>&nbsp; Save
                                            </button>&nbsp;

                                            <button type="button" class="btn btn-secondary repeat-rate-code-detail">
                                                <i class="fa-solid fa-copy"></i>&nbsp; Repeat
                                            </button>&nbsp;

                                            <button type="button" class="btn btn-danger delete-rate-code-detail">
                                                <i class="fa-solid fa-ban"></i>&nbsp; Delete
                                            </button>&nbsp;


                                        </div>



                                    </div>

                                    <div class="d-flex col-12 justify-content-between">
                                        <button class="btn btn-primary btn-prev">
                                            <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                                            <span class="d-none d-sm-inline-block">Previous</span>
                                        </button>
                                        <button class="btn btn-primary btn-next">
                                            <span class="d-none d-sm-inline-block me-sm-1">Next</span>
                                            <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- Social Links -->
                            <div id="negotiated-rate-validation" class="content">
                                <div class="row g-3">
                                    <div class="col-md-12">

                                        <div class="dt-action-buttons text-end pt-3 pt-md-0 mb-6"
                                            style="margin-bottom: 15px;">
                                            <div class="dt-buttons">
                                                <button class="dt-button new-negotiated-rate btn btn-primary"
                                                    tabindex="0" type="button" data-bs-toggle="modal"
                                                    data-bs-target="#newNegotiatedRate"><span><i
                                                            class="bx bx-plus me-sm-2"></i>
                                                        <span class="d-none d-sm-inline-block">Add New
                                                            Negotiated Rate</span></span></button>
                                            </div>
                                        </div>

                                        <table id="negotiated_rates" class="table table-bordered table-hover"
                                            style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Profile Type</th>
                                                    <th>Client ID</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th class="all">Action</th>
                                                </tr>
                                            </thead>

                                        </table>

                                    </div>
                                    <div class="d-flex col-12 justify-content-between">
                                        <input type="hidden" name="RT_CD_ID" id="RT_CD_ID" class="form-control" />
                                        <button class="btn btn-primary btn-prev">
                                            <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                                            <span class="d-none d-sm-inline-block">Previous</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /Validation Wizard -->

            <div class="content-backdrop fade"></div>
        </div>

    </div>

    <!-- Modal Window -->

    <div class="modal fade" id="repeatModalWindow" data-backdrop="static" data-keyboard="false"
        aria-lableledby="repeatModalWindowlable">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="repeatModalWindowlabel">Repeat Rate Detail</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="repeatForm" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <input type="hidden" name="rep_RT_CD_DT_ID" id="rep_RT_CD_DT_ID" class="form-control" />
                            <input type="hidden" name="rep_RT_CD_ID" id="rep_RT_CD_ID"
                                value="<?php if(isset($rateCodeID)){ echo $rateCodeID; }?>" />

                            <div class="col-md-6">
                                <label class="form-label"><b>Start Date *</b></label>
                                <div class="input-group mb-6">
                                    <input type="text" id="rep_RT_CD_START_DT" name="rep_RT_CD_START_DT"
                                        class="form-control dateField" placeholder="d-Mon-yyyy" required />
                                    <span class="input-group-append">
                                        <span class="input-group-text bg-light d-block">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><b>End Date *</b></label>
                                <div class="input-group mb-6">
                                    <input type="text" id="rep_RT_CD_END_DT" name="rep_RT_CD_END_DT"
                                        class="form-control dateField" placeholder="d-Mon-yyyy" />
                                    <span class="input-group-append">
                                        <span class="input-group-text bg-light d-block">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-12"></div>

                            <div class="col-md-2">
                                <label class="switch">
                                    <input type="checkbox" id="rep_RT_CD_DT_DAYS_SUN" name="rep_RT_CD_DT_DAYS[]"
                                        class="switch-input rep_RT_CD_DT_DAYS" value="SUN" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label">Sun</span>
                                </label>
                            </div>

                            <div class="col-md-2">
                                <label class="switch">
                                    <input type="checkbox" id="rep_RT_CD_DT_DAYS_TUE" name="rep_RT_CD_DT_DAYS[]"
                                        class="switch-input rep_RT_CD_DT_DAYS" value="TUE" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label">Tue</span>
                                </label>
                            </div>

                            <div class="col-md-2">
                                <label class="switch">
                                    <input type="checkbox" id="rep_RT_CD_DT_DAYS_THU" name="rep_RT_CD_DT_DAYS[]"
                                        class="switch-input rep_RT_CD_DT_DAYS" value="THU" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label">Thu</span>
                                </label>
                            </div>

                            <div class="col-md-6">
                                <label class="switch">
                                    <input type="checkbox" id="rep_RT_CD_DT_DAYS_SAT" name="rep_RT_CD_DT_DAYS[]"
                                        class="switch-input rep_RT_CD_DT_DAYS" value="SAT" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label">Sat</span>
                                </label>
                            </div>

                            <div class="col-md-2">
                                <label class="switch">
                                    <input type="checkbox" id="rep_RT_CD_DT_DAYS_MON" name="rep_RT_CD_DT_DAYS[]"
                                        class="switch-input rep_RT_CD_DT_DAYS" value="MON" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label">Mon</span>
                                </label>
                            </div>

                            <div class="col-md-2">
                                <label class="switch">
                                    <input type="checkbox" id="rep_RT_CD_DT_DAYS_WED" name="rep_RT_CD_DT_DAYS[]"
                                        class="switch-input rep_RT_CD_DT_DAYS" value="WED" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label">Wed</span>
                                </label>
                            </div>

                            <div class="col-md-2">
                                <label class="switch">
                                    <input type="checkbox" id="rep_RT_CD_DT_DAYS_FRI" name="rep_RT_CD_DT_DAYS[]"
                                        class="switch-input rep_RT_CD_DT_DAYS" value="FRI" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label">Fri</span>
                                </label>
                            </div>

                            <div class="col-md-12"></div>

                            <div class="col-md-12">
                                <label class="form-label"><b>Room Types *</b></label>
                                <select id="rep_RT_CD_DT_ROOM_TYPES" name="rep_RT_CD_DT_ROOM_TYPES[]"
                                    class="select2 form-select form-select-lg" multiple>
                                    <?php
                                            if($roomTypeOptions != NULL) {
                                                foreach($roomTypeOptions as $roomTypeOption)
                                                {
                                              ?> <option value="<?=$roomTypeOption['value']; ?>">
                                        <?=$roomTypeOption['name'] .' | '. $roomTypeOption['desc']; ?>
                                    </option>
                                    <?php       }
                                            }                                                            
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-12"></div>

                            <div class="col-md-2">
                                <label class="switch">
                                    <input type="checkbox" id="rep_PACKAGES" name="rep_PACKAGES"
                                        class="switch-input rep_PACKAGES" value="1" checked />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label">Packages</span>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="submitBtn" onClick="repeatForm()" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Combined Profiles Modal -->
    <div class="modal fade" id="newNegotiatedRate" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel4">Select Profiles</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="dt_adv_search" method="POST">
                        <div class="border rounded p-3">
                            <div class="row g-3">
                                <div class="col-4 col-sm-6 col-lg-4">

                                    <div class="row mb-3">
                                        <label class="col-form-label col-md-4"
                                            style="text-align: right;"><b>Name:</b></label>
                                        <div class="col-md-8">
                                            <input type="text" id="PROFILE_NAME" name="PROFILE_NAME"
                                                class="form-control dt-input dt-full-name" data-column="0"
                                                placeholder="" />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-form-label col-md-4" style="text-align: right;"><b>First
                                                Name:</b></label>
                                        <div class="col-md-8">
                                            <input type="text" name="PROFILE_FIRST_NAME"
                                                class="form-control dt-input dt-first-name" data-column="0"
                                                placeholder="" />

                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-form-label col-md-4" style="text-align: right;"><b>View
                                                By:</b></label>
                                        <div class="col-md-8">
                                            <select id="searchProfileType" name="PROFILE_TYPE"
                                                class="form-select dt-select dt-view-by" data-column="1">
                                                <option value="">View All</option>
                                                <option value="1">Individual</option>
                                                <option value="2">Company</option>
                                                <option value="3">Travel Agent</option>
                                                <option value="4">Group</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-4 col-sm-6 col-lg-4">

                                    <div class="row mb-3">
                                        <label class="col-form-label col-md-4" style="text-align: right;"><b>City /
                                                Postal Code:</b></label>
                                        <div class="col-md-5" style="padding-right:  0;">
                                            <input type="text" name="PROFILE_CITY" class="form-control dt-input dt-city"
                                                data-column="4" placeholder="" />
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="PROFILE_POSTAL_CODE"
                                                class="form-control dt-input dt-postal-code" data-column="5"
                                                placeholder="" />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-form-label col-md-4" style="text-align: right;"><b>Mem. Type /
                                                No:</b></label>
                                        <div class="col-md-4" style="padding-right:  0;">
                                            <select id="defaultSelect" class="form-select dt-input dt-mem-type">
                                                <option></option>
                                                <option value="AA">AA | American Airlines</option>
                                                <option value="AC">AC | Air Canada</option>
                                                <option value="US">US | US Air</option>
                                                <option value="VX">Virgin American Airlines</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control dt-input dt-mem-no" placeholder="" />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-form-label col-md-4"
                                            style="text-align: right;"><b>Communication:</b></label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control dt-input dt-communication"
                                                placeholder="" />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-form-label col-md-4" style="text-align: right;"><b>Passport
                                                No:</b></label>
                                        <div class="col-md-8">
                                            <input type="text" name="PROFILE_PASSPORT"
                                                class="form-control dt-input dt-passport-no" data-column="19"
                                                placeholder="" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-4 col-sm-6 col-lg-4">

                                    <div class="row mb-3">
                                        <label class="col-form-label col-md-4" style="text-align: right;"><b>Client
                                                ID:</b></label>
                                        <div class="col-md-8">
                                            <input type="text" name="PROFILE_NUMBER"
                                                class="form-control dt-input dt-client-id" data-column="16"
                                                placeholder="" />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-form-label col-md-4" style="text-align: right;"><b>IATA
                                                No:</b></label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control dt-input dt-iata-no"
                                                placeholder="" />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-form-label col-md-4" style="text-align: right;"><b>Corp
                                                No:</b></label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control dt-input dt-corp-no"
                                                placeholder="" />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-form-label col-md-4" style="text-align: right;"><b>A/R
                                                No:</b></label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control dt-input dt-ar-no" placeholder="" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>

                    <div class="table-responsive text-nowrap">
                        <table id="combined_profiles" class="table table-hover table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type ID</th>
                                    <th>Type</th>
                                    <th>Address</th>
                                    <th>City</th>
                                    <th>Postal Code</th>
                                    <th>Company</th>
                                    <th>A/R No.</th>
                                    <th>VIP</th>
                                    <th>Rate Code</th>
                                    <th>Next Stay</th>
                                    <th>Last Stay</th>
                                    <th>Last Room</th>
                                    <th>Last Group</th>
                                    <th>Title</th>
                                    <th>Country</th>
                                    <th>Client ID/IATA/Corp No</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Passport</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary close_selected_profiles"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="button" class="btn btn-dark use_selected_profiles" disabled>Use Selected
                        Profiles</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Add Negotiated Rate Modal -->
    <div class="modal fade" id="addNegotiatedRate" data-backdrop="static" data-keyboard="false"
        aria-lableledby="addNegotiatedRatelable">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addNegotiatedRatelabel">Negotiated Rate</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="negRateForm" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <input type="hidden" name="neg_PROFILE_IDS" id="neg_PROFILE_IDS" />
                            <input type="hidden" name="neg_RT_CD_ID" id="neg_RT_CD_ID" class="form-control"
                                value="<?php echo isset($rateCodeID) ? $rateCodeID : 0; ?>" />
                            <input type="hidden" name="NG_RT_ID" id="NG_RT_ID" />

                            <div class="col-md-7">
                                <label class="form-label"><b>Rate Code </b></label>
                                <input type="text" name="neg_RT_CD_CODE" id="neg_RT_CD_CODE"
                                    value="<?php echo isset($rateCodeDetails['RT_CD_CODE']) ? $rateCodeDetails['RT_CD_CODE'] : ''; ?>"
                                    class="form-control bootstrap-maxlength" readonly />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Display Sequence</label>
                                <input type="number" name="NG_RT_DIS_SEQ" id="NG_RT_DIS_SEQ" class="form-control"
                                    min="0" placeholder="eg: 3" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><b>Begin Date *</b></label>
                                <div class="input-group mb-6">
                                    <input type="text" id="NG_RT_START_DT" name="NG_RT_START_DT"
                                        class="form-control dateField" placeholder="d-Mon-yyyy" required />
                                    <span class="input-group-append">
                                        <span class="input-group-text bg-light d-block">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">End Date</label>
                                <div class="input-group mb-6">
                                    <input type="text" id="NG_RT_END_DT" name="NG_RT_END_DT"
                                        class="form-control dateField" placeholder="d-Mon-yyyy" />
                                    <span class="input-group-append">
                                        <span class="input-group-text bg-light d-block">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="submitBtn" onClick="submitNegotiatedForm('negRateForm')"
                        class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>


    <?php if($rateCodeDetails != NULL) { ?>

    <!-- Rate Package Settings -->

    <div class="modal fade" id="popModalWindow" data-backdrop="static" data-keyboard="false"
        aria-labelledby="popModalWindowlabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">Rate Package</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div id="rtpkg-wizard-validation" class="bs-stepper mt-2">
                        <div class="bs-stepper-header">
                            <div class="step" data-target="#package-header-validation">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-circle">1</span>
                                    <span class="bs-stepper-label">Package Header</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            <div class="step" data-target="#package-detail-validation">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-circle">2</span>
                                    <span class="bs-stepper-label">Package Detail</span>
                                </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content">

                            <form id="packageCode-submit-form" onSubmit="return false">

                                <div id="package-header-validation" class="content">

                                    <div class="row g-3">
                                        <input type="hidden" name="PKG_CD_ID" id="PKG_CD_ID" />
                                        <input type="hidden" name="RT_CD_PKG_ID" id="RT_CD_PKG_ID" />

                                        <div class="border rounded p-3">

                                            <div class="col-md-12">
                                                <div class="row mb-3">
                                                    <label for="html5-text-input"
                                                        class="col-form-label col-md-3"><b>Code
                                                            *</b></label>
                                                    <div class="col-md-3">
                                                        <input type="text" name="PKG_CD_CODE" id="PKG_CD_CODE"
                                                            class="form-control bootstrap-maxlength textField"
                                                            maxlength="10" placeholder="eg: 1001" required />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="html5-text-input" class="col-form-label col-md-3">Short
                                                        Description</label>
                                                    <div class="col-md-5">
                                                        <input type="text" name="PKG_CD_SHORT_DESC"
                                                            id="PKG_CD_SHORT_DESC"
                                                            class="form-control bootstrap-maxlength" maxlength="50"
                                                            placeholder="eg: Online Travel Agent" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="html5-text-input"
                                                        class="col-form-label col-md-3"><b>Description
                                                            *</b></label>
                                                    <div class="col-md-7">
                                                        <input type="text" name="PKG_CD_DESC" id="PKG_CD_DESC"
                                                            class="form-control bootstrap-maxlength textField"
                                                            maxlength="50" placeholder="eg: Online Travel Agent" />
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="border rounded p-3">
                                            <h6>Transaction Details</h6>
                                            <div class="row g-3 mb-3">
                                                <label for="PKG_RT_TR_CD_ID"
                                                    class="col-form-label col-md-3"><b>Transaction
                                                        Code *</b></label>
                                                <div class="col-md-4">
                                                    <select id="PKG_RT_TR_CD_ID" name="PKG_RT_TR_CD_ID"
                                                        class="select2 form-select form-select-lg" disabled>
                                                        <?=$transactionCodeOptions?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="border rounded p-3">
                                            <h6>Attributes</h6>
                                            <div class="row mb-3">

                                                <div class="col-md-4">

                                                    <?php if($rateInclusionRules != NULL) { 
                                                            foreach($rateInclusionRules as $rateInclusionRule) { ?>

                                                    <div class="form-check mt-3">
                                                        <input class="form-check-input" type="radio"
                                                            value="<?=$rateInclusionRule['value']?>"
                                                            id="RT_INCL_ID<?=$rateInclusionRule['value']?>"
                                                            name="RT_INCL_ID"
                                                            <?php if($rateInclusionRule['value'] == 1) echo ' checked'; ?> />
                                                        <label class="form-check-label"
                                                            for="RT_INCL_ID<?=$rateInclusionRule['value']?>">
                                                            <?=$rateInclusionRule['name']?> </label>
                                                    </div>
                                                    <?php   }
                                                    }   
                                                    ?>
                                                </div>

                                                <div class="col-md-8">
                                                    <div class="row mb-3">

                                                        <label for="PO_RH_ID" class="col-form-label col-md-4"
                                                            style="text-align: right;"><b>Posting Rhythm *</b></label>
                                                        <div class="col-md-8">
                                                            <select id="PO_RH_ID" name="PO_RH_ID"
                                                                class="select2 select2Pkg form-select form-select-lg">
                                                                <?php
                                                            if($postingRhythmOptions != NULL) {
                                                                foreach($postingRhythmOptions as $postingRhythmOption)
                                                                {
                                                        ?> <option value="<?=$postingRhythmOption['value']; ?>">
                                                                    <?=$postingRhythmOption['name']; ?>
                                                                </option>
                                                                <?php   }
                                                            }                                                            
                                                        ?>
                                                            </select>
                                                        </div>

                                                    </div>

                                                    <div class="row mb-3">

                                                        <label for="CLC_RL_ID" class="col-form-label col-md-4"
                                                            style="text-align: right;"><b>Calculation Rule *</b></label>
                                                        <div class="col-md-8">
                                                            <select id="CLC_RL_ID" name="CLC_RL_ID"
                                                                class="select2 select2Pkg form-select form-select-lg">
                                                                <?php
                                                            if($calcInclusionRules != NULL) {
                                                                foreach($calcInclusionRules as $calcInclusionRule)
                                                                {
                                                        ?> <option value="<?=$calcInclusionRule['value']; ?>">
                                                                    <?=$calcInclusionRule['name']; ?>
                                                                </option>
                                                                <?php   }
                                                            }                                                            
                                                        ?>
                                                            </select>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label class="switch">
                                                <input id="PKG_CD_SELL_SEP" name="PKG_CD_SELL_SEP" type="checkbox"
                                                    value="1" class="switch-input" disabled />
                                                <span class="switch-toggle-slider">
                                                    <span class="switch-on">
                                                        <i class="bx bx-check"></i>
                                                    </span>
                                                    <span class="switch-off">
                                                        <i class="bx bx-x"></i>
                                                    </span>
                                                </span>
                                                <span class="switch-label">Sell Separately</span>
                                            </label>
                                        </div>

                                        <div class="d-flex col-12 justify-content-between">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>

                                            <button type="button"
                                                onclick="submitRatePackageForm('packageCode-submit-form')"
                                                class="btn btn-success saveBtn">
                                                <i class="fa-solid fa-floppy-disk"></i>&nbsp; Save
                                            </button>

                                            <button type="button" class="btn btn-primary btn-next">
                                                <span class="d-none d-sm-inline-block me-sm-1">Next</span>
                                                <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
                                            </button>
                                        </div>

                                    </div>
                                </div>

                                <div id="package-detail-validation" class="content">

                                    <div class="row g-3">

                                        <div class="col-md-5">
                                            <div class="border rounded p-4 mb-3">

                                                <div class="row mb-3">
                                                    <label for="PKG_CD_START_DT"
                                                        class="col-form-label col-md-4"><b>Start
                                                            Date *</b></label>
                                                    <div class="col-md-8">
                                                        <input class="form-control datePkgField" type="text"
                                                            placeholder="d-Mon-yyyy" id="PKG_CD_START_DT"
                                                            name="PKG_CD_START_DT" />
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="PKG_CD_END_DT" class="col-form-label col-md-4"><b>End
                                                            Date *</b></label>
                                                    <div class="col-md-8">
                                                        <input class="form-control datePkgField" type="text"
                                                            placeholder="d-Mon-yyyy" id="PKG_CD_END_DT"
                                                            name="PKG_CD_END_DT" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="PKG_CD_DT_PRICE"
                                                        class="col-form-label col-md-4"><b>Price *</b></label>
                                                    <div class="col-md-8">
                                                        <input type="number" name="PKG_CD_DT_PRICE" id="PKG_CD_DT_PRICE"
                                                            class="form-control" min="0.00" step=".01"
                                                            placeholder="eg: 430.50" />
                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                        <div class="col-md-7">

                                            <div class="border rounded p-4 mb-3">

                                                <div class="table-responsive text-nowrap">
                                                    <table id="PKG_CD_Details" class="table table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="all">Start</th>
                                                                <th class="all">End</th>
                                                                <th class="all">Price</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>

                                                <br />

                                                <input type="hidden" name="PKG_CD_DT_ID" id="PKG_CD_DT_ID" readonly />

                                                <button type="button" class="btn btn-primary add-package-code-detail">
                                                    <i class="fa-solid fa-circle-plus"></i>&nbsp; Add New
                                                </button>&nbsp;

                                                <button type="button" class="btn btn-success save-package-code-detail">
                                                    <i class="fa-solid fa-floppy-disk"></i>&nbsp; Save
                                                </button>&nbsp;

                                                <button type="button" class="btn btn-danger delete-package-code-detail">
                                                    <i class="fa-solid fa-ban"></i>&nbsp; Delete
                                                </button>&nbsp;


                                            </div>



                                        </div>

                                        <div class="d-flex col-12 justify-content-between">

                                            <button class="btn btn-primary btn-prev">
                                                <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                                                <span class="d-none d-sm-inline-block">Previous</span>
                                            </button>

                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                        </div>

                                    </div>

                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php } ?>

</div>

<script>
const rateCategoryList = <?php echo json_encode($rateCategoryOptions); ?>;
const roomTypeList = <?php echo json_encode($roomTypeOptions); ?>;
const packageCodeList = <?php echo json_encode($packageCodeOptions); ?>;
const colorArray = <?php echo isset($color_array) ? '["'.implode('","', $color_array).'"]' : '[]'; ?>;

var rateCodeID = <?php echo isset($rateCodeID) ? $rateCodeID : 0; ?>;
var rateCodeDetailID =
    <?php echo (isset($rateCodeDetailsList) && $rateCodeDetailsList != NULL) ? $rateCodeDetailsList[0]['RT_CD_DT_ID'] : 0; ?>;

<?php echo (isset($selectedRoomTypes) && $selectedRoomTypes != NULL) ? 'const selectedRoomTypes = '.json_encode($selectedRoomTypes).';' : '' ?>
<?php echo (isset($selectedPackageCodes) && $selectedPackageCodes != NULL) ? 'const selectedPackageCodes = '.json_encode($selectedPackageCodes).';' : '' ?>

$(document).ready(function() {

    $('.dateField').datepicker({
        format: 'dd-M-yyyy',
        autoclose: true,
        <?php if($rateCodeDetails == NULL) { ?>
        startDate: '-0m',
        <?php } ?>
        onSelect: function() {
            $(this).change();
        }
    });

    <?php if($rateCodeDetails != NULL) { ?>

    $("#RT_CD_CODE").prop("readonly", true);

    var url = '<?php echo base_url('/showRateCodeInfo')?>';
    $.ajax({
        url: url,
        type: "get",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            sysid: rateCodeID
        },
        dataType: 'json',
        success: function(respn) {
            $(respn).each(function(inx, data) {
                $.each(data, function(fields, datavals) {
                    var field = $.trim(fields); //fields.trim();
                    var dataval = $.trim(datavals); //datavals.trim();

                    if (jQuery.inArray(field, ['MK_CD_ID',
                            'SOR_ID', 'TR_CD_ID', 'PKG_TR_CD_ID'
                        ]) !== -1) {
                        $('#' + field).val(dataval).trigger('change');
                    } else if ($('#' + field).attr('type') == 'checkbox') {
                        $('#' + field).prop('checked', dataval == 1 ? true : false);
                    } else if (field == 'RT_CD_BEGIN_SELL_DT' || field ==
                        'RT_CD_END_SELL_DT') {
                        $('#' + field).datepicker("setDate", new Date(dataval));
                    } else if (field != 'RT_CT_ID' && field != 'RT_CD_ROOM_TYPES' &&
                        field != 'RT_CD_PACKAGES') {
                        $('#' + field).val(dataval);
                    }
                });
            });
        }
    });

    // Rate Code Details List

    $('#RD_Room_Types').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/rateCodeDetailsView') ?>',
            'data': {
                "sysid": rateCodeID
            }
        },
        'columns': [{
                data: 'RT_CD_START_DT'
            },
            {
                data: 'RT_CD_END_DT'
            },
            {
                data: null
            },
        ],
        columnDefs: [{
            // Label
            targets: -1,
            render: function(data, type, full, meta) {
                var roomTypes = full['RT_CD_DT_ROOM_TYPES'];
                return show_color_badges(roomTypes);
            }
        }],
        'createdRow': function(row, data, dataIndex) {
            $(row).attr('data-ratedetailsid', data['RT_CD_DT_ID']);

            if ($('#RT_CD_DT_ID').val() != '') {
                if ($('#RT_CD_DT_ID').val() == data['RT_CD_DT_ID']) {
                    $(row).addClass('table-warning');
                    loadRateCodeDetails(data['RT_CD_DT_ID']);
                }
            } else if (dataIndex == 0) {
                $(row).addClass('table-warning');
                loadRateCodeDetails(data['RT_CD_DT_ID']);
            }
        },
        "ordering": false,
        autowidth: true,
        responsive: true
    });

    $(document).on('click', '#RD_Room_Types > tbody > tr', function() {


    });


    // Negotiated Rates List

    $('#negotiated_rates').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/negotiatedRateView')?>',
            'data': {
                "sysid": rateCodeID
            }
        },
        'columns': [{
                data: 'PROFILE_NAME'
            },
            {
                data: 'PROFILE_TYPE_NAME'
            },
            {
                data: 'CUST_CLIENT_ID'
            },
            {
                data: 'NG_RT_START_DT'
            },
            {
                data: 'NG_RT_END_DT'
            },
            {
                data: null,
                "orderable": false,
                render: function(data, type, row, meta) {
                    return (
                        '<div class="d-inline-block">' +
                        '<a href="javascript:;" title="Edit or Delete" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                        '<ul class="dropdown-menu dropdown-menu-end">' +
                        '<li><a href="javascript:;" data_sysid="' + data['NG_RT_ID'] +
                        '" data-profile-type="' + data['PROFILE_TYPE'] +
                        '" data-profile-id="' + data['PROFILE_ID'] +
                        '" data-start-date="' + data['NG_RT_START_DT'] +
                        '" data-edit-date="' + data['NG_RT_END_DT'] +
                        '" data-display-seq="' + data['NG_RT_DIS_SEQ'] +
                        '" class="dropdown-item edit-negotiated-rate text-primary"><i class="fa-solid fa-pen-to-square"></i> Edit</a></li>' +
                        '<div class="dropdown-divider"></div>' +
                        '<li><a href="javascript:;" data_sysid="' + data['NG_RT_ID'] +
                        '" class="dropdown-item text-danger delete-negotiated-rate"><i class="fa-solid fa-ban"></i> Delete</a></li>' +
                        '</ul>' +
                        '</div>'
                    );
                }
            },
        ],
        columnDefs: [{
            width: "25%"
        }, {
            width: "20%"
        }, {
            width: "15%"
        }, {
            width: "10%"
        }, {
            width: "10%"
        }, {
            width: "10%"
        }],
        "order": [
            [3, "asc"]
        ],
        responsive: true,
        dom: "<'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-6'i><'col-sm-12 col-md-6 dataTables_pager'p>>",
        language: {
            emptyTable: 'There are no negotiated rates added'
        }
    });

    var clicked_profile_ids = [];

    $(document).on('click', '#combined_profiles > tbody > tr', function() {

        var profile_chk_str = 'profile_chk_' + $(this).attr('data-profile-type') + '_' + $(this).attr(
            'data-profile-id');

        //If value in array
        if (jQuery.inArray(profile_chk_str, clicked_profile_ids) !==
            -1) {
            if ($(this).hasClass("table-warning")) {
                // Remove value from array
                clicked_profile_ids = $.grep(clicked_profile_ids, function(value) {
                    return value != profile_chk_str;
                });
            }
        } else {
            if (!$(this).hasClass("table-warning")) {
                clicked_profile_ids.push(profile_chk_str);
            }
        }

        if (clicked_profile_ids.length == 0) {
            toggleButton('.use_selected_profiles', 'btn-primary', 'btn-dark', true);
        } else {
            toggleButton('.use_selected_profiles', 'btn-dark', 'btn-primary', false);
        }

        //alert(clicked_profile_ids);
        $(this).toggleClass('table-warning');
    });

    function getProfileName() {
        return $('#PROFILE_NAME').val();
    }

    // Combined Profiles (Customer, Company, Agent, Group) List for Negotiated Rates

    $('#combined_profiles').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/combinedProfilesView')?>',
            'data': {
                "sysid": rateCodeID
            }
        },
        'columns': [{
                data: 'PROFILE_NAME'
            },
            {
                data: 'PROFILE_TYPE',
                "visible": false,
            },
            {
                data: 'PROFILE_TYPE_NAME'
            },
            {
                data: 'PROFILE_ADDRESS'
            },
            {
                data: 'PROFILE_CITY'
            },
            {
                data: 'PROFILE_POSTAL_CODE'
            },
            {
                data: 'PROFILE_COMP_CODE'
            },
            {
                "defaultContent": ""
            },
            {
                data: 'PROFILE_VIP'
            },
            {
                "defaultContent": ""
            },
            {
                "defaultContent": ""
            },
            {
                "defaultContent": ""
            },
            {
                "defaultContent": ""
            },
            {
                "defaultContent": ""
            },
            {
                data: 'PROFILE_TITLE'
            },
            {
                data: 'PROFILE_COUNTRY'
            },
            {
                data: 'PROFILE_NUMBER'
            },
            {
                data: 'PROFILE_EMAIL'
            },
            {
                data: 'PROFILE_MOBILE'
            },
            {
                data: 'PROFILE_PASSPORT'
            },
        ],
        'createdRow': function(row, data, dataIndex) {
            var check_str = 'profile_chk_' + data['PROFILE_TYPE'] + '_' + data['PROFILE_ID'];

            $(row).attr('data-profile-type', data['PROFILE_TYPE']);
            $(row).attr('data-profile-id', data['PROFILE_ID']);

            if (jQuery.inArray(check_str, clicked_profile_ids) !== -1 && !$(row).hasClass(
                    'table-warning')) {
                $(row).addClass('table-warning');
            } else if (jQuery.inArray(check_str, clicked_profile_ids) == -1 && $(row).hasClass(
                    'table-warning')) {
                $(row).removeClass('table-warning');
            }
        },
        columnDefs: [{
            width: "25%"
        }, {
            width: "20%"
        }, {
            width: "15%"
        }, {
            width: "10%"
        }, {
            width: "10%"
        }, {
            width: "10%"
        }, {
            width: "25%"
        }, {
            width: "20%"
        }, {
            width: "15%"
        }, {
            width: "10%"
        }, {
            width: "10%"
        }, {
            width: "10%"
        }, {
            width: "25%"
        }, {
            width: "20%"
        }, {
            width: "15%"
        }, {
            width: "10%"
        }, {
            width: "10%"
        }, {
            width: "10%"
        }, {
            width: "10%"
        }],
        "order": [
            [1, "asc"]
        ],
        destroy: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end">>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        language: {
            emptyTable: 'There are no profiles in the system'
        },
        select: {
            // Select style
            style: 'multi',
            info: false
        }
    });

    $(document).on('click', '.new-negotiated-rate', function() {

        hideModalAlerts();
        clearFormFields('.dt_adv_search');
        $('#combined_profiles').dataTable().fnDraw();

    });

    $(document).on('click', '.edit-negotiated-rate', function() {

        hideModalAlerts();

        var profile_chk_str = 'profile_chk_' + $(this).attr('data-profile-type') + '_' + $(this).attr(
            'data-profile-id');

        $('#NG_RT_ID').val($(this).attr('data_sysid'));
        $('#neg_PROFILE_IDS').val(profile_chk_str);
        $("#NG_RT_START_DT").datepicker("setDate", new Date($(this).attr('data-start-date')));
        $("#NG_RT_END_DT").datepicker("setDate", new Date($(this).attr('data-edit-date')));
        $("#NG_RT_DIS_SEQ").val($(this).attr('data-display-seq'));

        $('#addNegotiatedRate').modal('show');
    });

    $(document).on('click', '.use_selected_profiles', function() {

        $('#neg_PROFILE_IDS').val(clicked_profile_ids);
        $("#NG_RT_ID").val("");
        $("#NG_RT_START_DT").datepicker("setDate", new Date(<?php date('d-M-Y'); ?>));
        $("#NG_RT_END_DT").datepicker("setDate", new Date(
            <?php date('d-M-Y', strtotime('+1 day')); ?>));
        $("#NG_RT_DIS_SEQ").val("");

        clicked_profile_ids = [];
        $('#combined_profiles').dataTable().fnDraw();

        $('#addNegotiatedRatelabel').html('Add New Negotiated Rate');

        $('#newNegotiatedRate').modal('hide');
        $('#addNegotiatedRate').modal('show');
    });


    $(document).on('click', '.delete-negotiated-rate', function() {
        hideModalAlerts();
        $('.dtr-bs-modal').modal('hide');

        var sysid = $(this).attr('data_sysid');
        bootbox.confirm({
            message: "Are you sure you want to delete this negotiated rate?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function(result) {
                if (result) {
                    $.ajax({
                        url: '<?php echo base_url('/deleteNegotiatedRate')?>',
                        type: "post",
                        data: {
                            sysid: sysid
                        },
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        dataType: 'json',
                        success: function(respn) {
                            showModalAlert('warning',
                                '<li>The Negotiated Rate has been deleted</li>'
                            );
                            $('#negotiated_rates').dataTable().fnDraw();
                        }
                    });
                }
            }
        });
    });

    $(document).on('hide.bs.modal', '#newNegotiatedRate', function() {

        clicked_profile_ids = [];
        clearFormFields('.dt_adv_search');
        $('#combined_profiles').DataTable().columns('').search('').draw();

    });


    <?php } ?>

});


// Show Edit Package Code Form

function showRatePackageForm(rcId, pkgId, rcPkgId) {

    $('.dtr-bs-modal').modal('hide');

    $('#PKG_CD_ID').val(pkgId);

    $("#PKG_CD_CODE").prop("readonly", true);

    $('#popModalWindow').modal('show');

    $('#package-detail-validation').find('.btn-prev').trigger('click'); // Go to first tab

    var url = '<?php echo base_url('/showRateCodePackageDetails') ?>';
    $.ajax({
        url: url,
        async: false,
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            rcId: rcId,
            pkgId: pkgId,
            rcPkgId: rcPkgId,
        },
        dataType: 'json',
        success: function(respn) {
            $(respn).each(function(inx, data) {
                $.each(data, function(fields, datavals) {
                    var field = $.trim(fields); //fields.trim();
                    var dataval = $.trim(datavals); //datavals.trim();

                    if (field == 'PKG_RT_TR_CD_ID' || field == 'PO_RH_ID' || field ==
                        'CLC_RL_ID') {
                        $('#' + field).select2("val", dataval);
                    } else if ($('#' + field).attr('type') == 'checkbox') {
                        $('#' + field).prop('checked', dataval == 1 ? true :
                            false);
                    } else if ($('#' + field + dataval).attr('type') ==
                        'radio') {
                        $('#' + field + dataval).prop('checked', true);
                    } else {
                        $('#' + field).val(dataval);
                    }

                });
            });

            //$('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
        }
    });
}


// Add New or Edit Rate Package Code submit Function

function submitRatePackageForm(id) {
    hideModalAlerts();
    var formSerialization = $('#' + id).serializeArray();
    formSerialization.push({
        name: "RT_CD_ID",
        value: rateCodeID
    });

    var url = '<?php echo base_url('/insertRateCodePackage') ?>';
    $.ajax({
        url: url,
        type: "post",
        data: formSerialization,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        success: function(respn) {
            console.log(respn, "testing");
            var response = respn['SUCCESS'];
            if (response != '1') {
                var ERROR = respn['RESPONSE']['ERROR'];
                var mcontent = '';
                $.each(ERROR, function(ind, data) {
                    console.log(data, "SDF");
                    mcontent += '<li>' + data + '</li>';
                });
                showModalAlert('error', mcontent);
            } else {
                var alertText = '<li>The Package Code settings have been saved</li>';

                showModalAlert('success', alertText);
                //$('#popModalWindow').modal('hide');

                var rcPkgCodeID = respn['RESPONSE']['OUTPUT'];
                $('#RT_CD_PKG_ID').val(rcPkgCodeID);
            }
        }
    });
}

function deleteRatePackageCode(rcId, pkgId, rcPkgId) {
    $.ajax({
        url: '<?php echo base_url('/deleteRatePackageCode')?>',
        type: "post",
        async: false,
        data: {
            rcId: rcId,
            pkgId: pkgId,
            rcPkgId: rcPkgId
        },
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        success: function(respn) {
            return true;
        },
        error: function() {
            return false;
        }
    });
}


// Show Package Code Detail

function loadPackageCodeDetails(packageCodeID, id) {

    var url = '<?php echo base_url('/showPackageCodeDetails')?>';
    $.ajax({
        url: url,
        type: "get",
        async: false,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            sysid: packageCodeID,
            dtID: id
        },
        dataType: 'json',
        success: function(respn) {

            //Enable Repeat and Delete buttons
            toggleButton('.delete-package-code-detail', 'btn-dark', 'btn-danger', false);

            $(respn).each(function(inx, data) {
                $.each(data, function(fields, datavals) {
                    var field = $.trim(fields); //fields.trim();
                    var dataval = $.trim(datavals); //datavals.trim();

                    if (field == 'PKG_CD_START_DT' || field ==
                        'PKG_CD_END_DT') {
                        $('#' + field).datepicker("setDate", new Date(dataval));
                    } else {
                        $('#' + field).val(dataval);
                    }
                });
            });
        }
    });
}

function showPackageCodeDetails(pkgCodeID, dtID = 0) {
    $('#PKG_CD_Details').find('tr.table-warning').removeClass('table-warning');

    $('#PKG_CD_Details').DataTable({
        'processing': true,
        async: false,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/packageCodeDetailsView') ?>',
            'data': {
                "sysid": pkgCodeID
            }
        },
        'columns': [{
                data: 'PKG_CD_START_DT'
            },
            {
                data: 'PKG_CD_END_DT'
            },
            {
                data: 'PKG_CD_DT_PRICE'
            },
        ],
        'createdRow': function(row, data, dataIndex) {
            $(row).attr('data-packagedetailsid', data['PKG_CD_DT_ID']);
            $(row).attr('data-packagecodeid', pkgCodeID);

            if (dtID != 0) {
                if (data['PKG_CD_DT_ID'] == dtID) {
                    $(row).addClass('table-warning');
                    loadPackageCodeDetails(pkgCodeID, dtID);
                }
            } else if (dataIndex == 0) {
                $(row).addClass('table-warning');
                loadPackageCodeDetails(pkgCodeID, data['PKG_CD_DT_ID']);
            }
        },
        destroy: true,
        "ordering": false,
        "searching": false,
        autowidth: true,
        responsive: true
    });
}

// Add / Edit Rate Code

function submitForm(id) {
    hideModalAlerts();
    var formSerialization = $('#' + id).serializeArray();
    var url = '<?php echo base_url('/insertRateCode')?>';
    $.ajax({
        url: url,
        type: "post",
        data: formSerialization,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        success: function(respn) {

            var response = respn['SUCCESS'];
            if (response != '1') {
                var ERROR = respn['RESPONSE']['ERROR'];
                var mcontent = '';
                $.each(ERROR, function(ind, data) {
                    console.log(data, "SDF");
                    mcontent += '<li>' + data + '</li>';
                });
                showModalAlert('error', mcontent);
            } else {
                blockLoader('rate-header-validation');

                if (respn['RESPONSE']['OUTPUT'] != '') // if Add Rate Code
                {
                    location.href = '<?php echo base_url('/editRateCode'); ?>/' + respn['RESPONSE'][
                        'OUTPUT'
                    ];
                } else {
                    var alertText = $('#RT_CD_ID').val() == '' ? '<li>The new Rate Code \'' + $(
                            '#RT_CD_CODE')
                        .val() + '\' has been created</li>' : '<li>The Rate Code \'' + $('#RT_CD_CODE')
                        .val() +
                        '\' has been updated</li>';
                    showModalAlert('success', alertText);
                }
            }
        }
    });
}

function getRoomTypes(codes, field) {

    var url = '<?php echo base_url('/showRoomTypeList')?>';
    $.ajax({
        url: url,
        type: "get",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            codes: codes
        },
        dataType: 'json',
        success: function(respn) {

            var selRoomTypes = [];
            $(respn).each(function(inx, data) {
                selRoomTypes.push(data['value']);
            });

            selRoomTypes = codes == '' ? [] : selRoomTypes;

            $('#' + field).val(selRoomTypes).trigger('change');
            $('#rep_' + field).val(selRoomTypes).trigger('change');
        }
    });
}

function getPackageCodes(codes, field) {

    var url = '<?php echo base_url('/showPackageCodeList')?>';
    $.ajax({
        url: url,
        type: "get",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            codes: codes
        },
        dataType: 'json',
        success: function(respn) {

            var selPackageCodes = [];
            $(respn).each(function(inx, data) {
                selPackageCodes.push(data['value']);
            });

            selPackageCodes = codes == '' ? [] : selPackageCodes;

            $('#' + field).val(selPackageCodes).trigger('change');
        }
    });
}


// Add / Edit Negotiated Rate

function submitNegotiatedForm(id) {
    hideModalAlerts();
    var formSerialization = $('#' + id).serializeArray();
    var url = '<?php echo base_url('/insertNegotiatedRate')?>';
    $.ajax({
        url: url,
        type: "post",
        data: formSerialization,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        success: function(respn) {

            var response = respn['SUCCESS'];
            if (response != '1') {
                var ERROR = respn['RESPONSE']['ERROR'];
                var mcontent = '';
                $.each(ERROR, function(ind, data) {
                    console.log(data, "SDF");
                    mcontent += '<li>' + data + '</li>';
                });
                showModalAlert('error', mcontent);
            } else {

                if (respn['RESPONSE']['OUTPUT'] != '0') {

                    var alertText = $('#NG_RT_ID').val() == '' ? '<li>' + respn['RESPONSE']['OUTPUT'] +
                        ' new Negotiated Rates have been created</li>' :
                        '<li>The Negotiated Rate has been updated</li>';

                    showModalAlert('success', alertText);
                } else
                    showModalAlert('error',
                        '<li>No new Negotiated Rates could be created. Please try again</li>');

                $('#addNegotiatedRate').modal('hide');
                $('#negotiated_rates').dataTable().fnDraw();
            }
        }
    });
}


// Show Rate Code Detail

function loadRateCodeDetails(id) {

    var url = '<?php echo base_url('/showRateCodeDetails')?>';
    $.ajax({
        url: url,
        type: "get",
        async: false,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            sysid: rateCodeID,
            dtID: id
        },
        dataType: 'json',
        success: function(respn) {

            $('#rep_RT_CD_DT_ID').val(id);

            //Enable Repeat and Delete buttons
            toggleButton('.repeat-rate-code-detail', 'btn-dark', 'btn-secondary', false);
            toggleButton('.delete-rate-code-detail', 'btn-dark', 'btn-danger', false);

            $(respn).each(function(inx, data) {
                $.each(data, function(fields, datavals) {
                    var field = $.trim(fields); //fields.trim();
                    var dataval = $.trim(datavals); //datavals.trim();

                    if (jQuery.inArray(field, ['RT_CD_DT_MK_CD_ID', 'RT_CD_DT_SOR_ID']) !==
                        -1) {
                        $('#' + field).val(dataval).trigger('change');
                    } else if (field == 'RT_CD_DT_DAYS') {
                        $('.' + field).prop('checked', true);
                        $('.rep_' + field).prop('checked', true);

                        if (dataval != 'ALL') {
                            var unCheckedDays = dataval.split(',');
                            $.each(unCheckedDays, function(i, day) {
                                if ($('#RT_CD_DT_DAYS_' + day).length)
                                    $('#RT_CD_DT_DAYS_' + day).prop('checked',
                                        false);

                                if ($('#rep_RT_CD_DT_DAYS_' + day).length)
                                    $('#rep_RT_CD_DT_DAYS_' + day).prop('checked',
                                        false);
                            });
                        }
                    } else if (field == 'RT_CD_START_DT' || field ==
                        'RT_CD_END_DT') {
                        $('#' + field).datepicker("setDate", new Date(dataval));
                    } else if (field == 'RT_CD_DT_ROOM_TYPES') {
                        getRoomTypes(dataval, 'RT_CD_DT_ROOM_TYPES');
                    } else if (field == 'RT_CD_DT_PACKAGES') {
                        getPackageCodes(dataval, 'RT_CD_DT_PACKAGES');
                    } else {
                        $('#' + field).val(dataval);
                    }
                });
            });
        }
    });
}


// Add new Rate Code Detail

$(document).on('click', '.add-rate-code-detail', function() {
    hideModalAlerts();
    $('.dtr-bs-modal').modal('hide');

    bootbox.dialog({
        message: "Do you want to add a new Rate Code Detail?",
        buttons: {
            ok: {
                label: 'Yes',
                className: 'btn-success',
                callback: function(result) {
                    if (result) {
                        clearFormFields('#rate-detail-validation');
                        $('#RD_Room_Types').find('tr.table-warning').removeClass('table-warning');

                        //Disable Repeat and Delete buttons
                        toggleButton('.repeat-rate-code-detail', 'btn-secondary', 'btn-dark', true);
                        toggleButton('.delete-rate-code-detail', 'btn-danger', 'btn-dark', true);

                        showModalAlert('info',
                            'Fill in the form and click the \'Save\' button to add the new Rate Detail'
                        );
                    }
                }
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        }
    });
});


// Update existing Rate Code Detail

$(document).on('click', '.save-rate-code-detail', function() {

    //var formSerialization = $('#rate-detail-validation').find("input,select,textarea").serializeArray();
    submitDetailsForm('rateCode-submit-form');
});


// Add / Edit Rate Code Detail

function submitDetailsForm(id) {
    hideModalAlerts();
    var formSerialization = $('#' + id).serializeArray();
    var url = '<?php echo base_url('/updateRateCodeDetail')?>';
    $.ajax({
        url: url,
        type: "post",
        data: formSerialization,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        success: function(respn) {

            var response = respn['SUCCESS'];
            if (response != '1') {
                var ERROR = respn['RESPONSE']['ERROR'];
                var mcontent = '';
                $.each(ERROR, function(ind, data) {
                    console.log(data, "SDF");
                    mcontent += '<li>' + data + '</li>';
                });
                showModalAlert('error', mcontent);
            } else {

                blockLoader('rate-detail-validation');

                var alertText = $('#RT_CD_DT_ID').val() == '' ?
                    '<li>The new Rate Code Detail has been created</li>' :
                    '<li>The Rate Code Detail has been updated</li>';

                showModalAlert('success', alertText);

                if (respn['RESPONSE']['OUTPUT'] != '') {

                    $('#RT_CD_DT_ID').val(respn['RESPONSE']['OUTPUT']);
                    $('#rep_RT_CD_DT_ID').val(respn['RESPONSE']['OUTPUT']);

                    $('#RD_Room_Types').dataTable().fnDraw();
                }
            }
        }
    });
}


// Repeat Rate Code Detail

$(document).on('click', '.repeat-rate-code-detail', function() {

    $('#repeatModalWindow').modal('show');
});

function repeatForm() {

    hideModalAlerts();
    var formSerialization = $('#repeatForm').serializeArray();
    var url = '<?php echo base_url('/copyRateCodeDetail')?>';
    $.ajax({
        url: url,
        type: "post",
        data: formSerialization,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        success: function(respn) {

            var response = respn['SUCCESS'];
            if (response != '1') {
                var ERROR = respn['RESPONSE']['ERROR'];
                var mcontent = '';
                $.each(ERROR, function(ind, data) {
                    console.log(data, "SDF");
                    mcontent += '<li>' + data + '</li>';
                });
                showModalAlert('error', mcontent);
            } else {

                blockLoader('rate-detail-validation');

                var alertText = '<li>The Rate Code Detail has been repeated</li>';

                showModalAlert('success', alertText);

                if (respn['RESPONSE']['OUTPUT'] != '') {

                    $('#RT_CD_DT_ID').val(respn['RESPONSE']['OUTPUT']);
                    $('#rep_RT_CD_DT_ID').val(respn['RESPONSE']['OUTPUT']);

                    $('#RD_Room_Types').dataTable().fnDraw();
                }

                $('#repeatModalWindow').modal('hide');
            }
        }
    });

    //alert('submitted');
    //$('#repeatForm').submit();
}


// Delete Rate Code Detail

$(document).on('click', '.delete-rate-code-detail', function() {
    hideModalAlerts();
    $('.dtr-bs-modal').modal('hide');

    var sysid = $('#RD_Room_Types').find("tr.table-warning").data("ratedetailsid");

    bootbox.confirm({
        message: "Rate Code Detail is active. Do you want to Delete?",
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function(result) {
            if (result) {
                $.ajax({
                    url: '<?php echo base_url('/deleteRateCodeDetail')?>',
                    type: "post",
                    data: {
                        sysid: sysid,
                        rateCodeID: rateCodeID
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    dataType: 'json',
                    success: function(respn) {
                        var response = respn['SUCCESS'];
                        if (response == '0') {
                            showModalAlert('error',
                                '<li>The Rate Detail cannot be deleted</li>');
                        } else {
                            showModalAlert('warning',
                                '<li>The Rate Code Detail has been deleted</li>');

                            $('#RD_Room_Types').dataTable().fnDraw();
                        }
                    }
                });
            }
        }
    });
});


// Display function jumbleArray() 
<?php echo isset($jumble_array_javascript) ? $jumble_array_javascript : ''; ?>

// Display function show_color_badges(str) 
<?php echo isset($color_badges_javascript) ? $color_badges_javascript : ''; ?>

// Display function toggleButton
<?php echo isset($toggleButton_javascript) ? $toggleButton_javascript : ''; ?>

// Display function clearFormFields
<?php echo isset($clearFormFields_javascript) ? $clearFormFields_javascript : ''; ?>

// Display function blockLoader
<?php echo isset($blockLoader_javascript) ? $blockLoader_javascript : ''; ?>
</script>


<?= $this->endSection() ?>