<?= $this->extend('Layout/AppView') ?>
<?= $this->section('contentRender') ?>

<style>
.tagify__input {
    padding-left: 6px;
}

.table-hover>tbody>tr:hover {
    cursor: pointer;
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
                                                        class="select2 form-select form-select-lg"
                                                        data-allow-clear="true">
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
                                                        class="select2 form-select form-select-lg"
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
                                        <table id="dataTable_view" class="table table-bordered table-hover"
                                            style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Client ID</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
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
                                        <button class="btn btn-success btn-next btn-submit">Submit</button>
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
                                <lable class="form-label"><b>Room Types *</b></lable>
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

    $('#dataTable_view').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/customerView')?>'
        },
        'columns': [{
                data: 'CUST_FIRST_NAME'
            },
            {
                data: 'CUST_CLIENT_ID'
            },
            {
                data: null,
                render: function() {
                    return '12/09/2021';
                }
            },
            {
                data: null,
                render: function() {
                    return '25/08/2022';
                }
            },
        ],
        autowidth: true,
        responsive: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
    });

    $('.dateField').datepicker({
        format: 'dd-M-yyyy',
        autoclose: true,
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

    $('#RD_Room_Types tbody').on('click', 'tr', function() {

        $('#RD_Room_Types').find('tr.table-warning').removeClass('table-warning');
        $(this).addClass('table-warning');

        loadRateCodeDetails($(this).data('ratedetailsid'));

        blockLoader('rate-detail-validation');
    });


    <?php } ?>

});

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
                        showModalAlert('warning',
                            '<li>The Rate Code Detail has been deleted</li>');

                        $('#RD_Room_Types').dataTable().fnDraw();
                    }
                });
            }
        }
    });
});

function hideModalAlerts() {
    $('#errorModal').hide();
    $('#successModal').hide();
    $('#infoModal').hide();
    $('#warningModal').hide();
}

function showModalAlert(modalType, modalContent) {
    $('#' + modalType + 'Modal').show();
    $('#form' + modalType.charAt(0).toUpperCase() + modalType.slice(1) + 'Message').html('<ul>' + modalContent +
        '</ul>');
}

function clearFormFields(elem) {

    var formSerialization = $(elem).find("input,select,textarea").serialize();
    // alert(formSerialization);

    $(elem).find('input,select').each(function() {
        switch ($(this).attr('type')) {
            case 'password':
            case 'text':
            case 'textarea':
            case 'file':
            case 'date':
            case 'number':
            case 'tel':
            case 'date':
            case 'email':
                $(this).val('');
                break;
            case 'checkbox':
                $(this).prop('checked', true);
                break;
            case 'radio':
                //this.checked = false;
                break;
            default:
                if (!$(this).closest(".table-responsive").length)
                    $(this).val(null).trigger('change');
                break;
        }
    });
}

function blockLoader(elem, duration = 500, alert = '') {
    $('#' + elem).block({
        message: '<div class="spinner-border text-white" role="status"></div>',
        timeout: duration,
        css: {
            backgroundColor: 'transparent',
            border: '0'
        },
        overlayCSS: {
            opacity: 0.5
        },
        onUnblock: function() {

        }
    });
}

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

function loadRateCodeDetails(id) {

    var url = '<?php echo base_url('/showRateCodeDetails')?>';
    $.ajax({
        url: url,
        type: "get",
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

// Update existing Rate Code Detail
$(document).on('click', '.save-rate-code-detail', function() {

    //var formSerialization = $('#rate-detail-validation').find("input,select,textarea").serializeArray();
    submitDetailsForm('rateCode-submit-form');
});

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

$(document).on('click', '.repeat-rate-code-detail', function() {

    $('#repeatModalWindow').modal('show');


});


// Display function jumbleArray() 
<?php echo isset($jumble_array_javascript) ? $jumble_array_javascript : ''; ?>

// Display function show_color_badges(str) 
<?php echo isset($color_badges_javascript) ? $color_badges_javascript : ''; ?>
</script>


<?= $this->endSection() ?>