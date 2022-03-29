<?= $this->extend('Layout/AppView') ?>

<?= $this->section('contentRender') ?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Rate Management / Rate
                Classifications / Rate Codes / </span> Add Rate Code</h4>
        <!-- Default -->
        <div class="row">

            <!-- Validation Wizard -->
            <div class="col-12 mb-4">
                <div id="wizard-validation" class="bs-stepper mt-2">
                    <div class="bs-stepper-header">
                        <div class="step" data-target="#account-details-validation">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle">1</span>
                                <span class="bs-stepper-label">Rate Header</span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#personal-info-validation">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle">2</span>
                                <span class="bs-stepper-label">Rate Detail</span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#social-links-validation">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle">3</span>
                                <span class="bs-stepper-label">Negotiated</span>
                            </button>
                        </div>
                    </div>
                    <div class="bs-stepper-content">
                        <form id="wizard-validation-form" onSubmit="return false" novalidate>
                            <!-- Account Details -->
                            <div id="account-details-validation" class="content">



                                <div class="row g-3">

                                    <div class="col-md-7">
                                        <div class="row mb-3">
                                            <label for="html5-text-input" class="col-form-label col-md-3"><b>Rate
                                                    Code *</b></label>
                                            <div class="col-md-5">
                                                <input class="form-control" type="text" value="" maxlength="10"
                                                    placeholder="eg: OTA" id="html5-text-input" />
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="html5-search-input"
                                                class="col-form-label col-md-3"><b>Description *</b></label>
                                            <div class="col-md-9">
                                                <input class="form-control" type="search" value="" maxlength="50"
                                                    placeholder="eg: Online Travel Agent" id="html5-search-input" />
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="html5-email-input" class="col-form-label col-md-3"><b>Rate
                                                    Category *</b></label>
                                            <div class="col-md-9">
                                                <select id="RT_CT_ID" name="RT_CT_ID"
                                                    class="select2 form-select form-select-lg" data-allow-clear="true"
                                                    required>
                                                    <option label=""></option>
                                                    <option value="sfsdf">LSTAY | Wholesale Dynamic | GRPL | 2021-12-07
                                                        | 2025-12-24</option>
                                                    <option value="sfsdf">OTH | Others | OTH | 2021-12-07 |
                                                        2025-12-24</option>
                                                    <option value="sfsdf">HOUSE | House Use | HOUSE | 2021-12-07
                                                        | 2025-12-24</option>
                                                    <option value="sfsdf">WSD | Wholesale Dynamic | WSD | 2021-12-07 |
                                                        2025-12-24</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="html5-url-input" class="col-form-label col-md-3">Rate
                                                Class</label>
                                            <div class="col-md-5">
                                                <input class="form-control" type="url" value="GRPL" id="html5-url-input"
                                                    readonly />
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="html5-tel-input" class="col-form-label col-md-3">Folio
                                                Text</label>
                                            <div class="col-md-9">
                                                <input class="form-control" type="tel" value="" id="html5-tel-input" />
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="html5-date-input" class="col-form-label col-md-3"><b>Begin Sell
                                                    Date *</b></label>
                                            <div class="col-md-9">
                                                <input class="form-control" type="date" value="2022-02-18"
                                                    id="html5-date-input" />
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="html5-date-input" class="col-form-label col-md-3"><b>End Sell
                                                    Date *</b></label>
                                            <div class="col-md-9">
                                                <input class="form-control" type="date" value="2021-11-30"
                                                    id="html5-date-input" />
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="html5-password-input"
                                                class="col-form-label col-md-3">Market</label>
                                            <div class="col-md-5">
                                                <select id="marketId" name="RT_CL_ID"
                                                    class="select2 form-select form-select-lg" data-allow-clear="true"
                                                    required>
                                                    <option label=""></option>
                                                    <option value="1">OTA | Online Travel Agent</option>
                                                    <option value="2">LSTAY | Long Stay</option>
                                                    <option value="3">CORP | Corporate</option>
                                                    <option value="4">WSD | Wholesale Dynamic</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="html5-number-input"
                                                class="col-form-label col-md-3">Source</label>
                                            <div class="col-md-5">
                                                <select id="sourceId" name="RT_CL_ID"
                                                    class="select2 form-select form-select-lg" data-allow-clear="true"
                                                    required>
                                                    <option label=""></option>
                                                    <option value="sfsdf">LSTAY | Wholesale Dynamic | GRPL | 2021-12-07
                                                        | 2025-12-24</option>
                                                    <option value="sfsdf">OTH | Others | OTH | 2021-12-07 |
                                                        2025-12-24</option>
                                                    <option value="sfsdf">HOUSE | House Use | HOUSE | 2021-12-07
                                                        | 2025-12-24</option>
                                                    <option value="sfsdf">WSD | Wholesale Dynamic | WSD | 2021-12-07 |
                                                        2025-12-24</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="html5-datetime-local-input"
                                                class="col-form-label col-md-3">Display Sequence</label>
                                            <div class="col-md-3">
                                                <input type="number" name="RT_CL_DIS_SEQ" id="RT_CL_DIS_SEQ"
                                                    class="form-control" min="0" placeholder="eg: 3" />
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="html5-month-input" class="col-form-label col-md-3"><b>Room
                                                    Types *</b></label>
                                            <div class="col-md-9">
                                                <input id="TagifyUserList" name="TagifyUserList" class="form-control"
                                                    value="R1B1BR, Residences 2 Tower B 1-BR, R4BSTD, Residences 3 Tower D 4-BR Duplex" />
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="html5-week-input"
                                                class="col-form-label col-md-3">Package</label>
                                            <div class="col-md-5">
                                                <select id="pckgId" name="RT_CL_ID"
                                                    class="select2 form-select form-select-lg" data-allow-clear="true"
                                                    required>
                                                    <option label=""></option>
                                                    <option value="1">FOOD | Breakfast</option>
                                                    <option value="2">HALF | Long Stay</option>
                                                    <option value="3">FULL | Corporate</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="html5-time-input" class="col-form-label col-md-3">Commission
                                                %</label>
                                            <div class="col-md-3">
                                                <div class="input-group input-group-merge">
                                                    <input type="number" name="RT_CL_DIS_SEQ" id="RT_CL_DIS_SEQ"
                                                        class="form-control" min="0" placeholder="eg: 3" />
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="html5-color-input"
                                                class="col-form-label col-md-3">Addition</label>
                                            <div class="col-md-3">
                                                <input type="number" name="RT_CL_DIS_SEQ" id="RT_CL_DIS_SEQ"
                                                    class="form-control" min="0" placeholder="eg: 3" />
                                            </div>

                                            <label for="html5-color-input" class="col-form-label col-md-3"
                                                style="text-align: right;">Multiplication</label>
                                            <div class="col-md-3">
                                                <input type="number" name="RT_CL_DIS_SEQ" id="RT_CL_DIS_SEQ"
                                                    class="form-control" min="0" placeholder="eg: 3" />
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="html5-color-input" class="col-form-label col-md-3">Min
                                                Occupancy</label>
                                            <div class="col-md-3">
                                                <input type="number" name="RT_CL_DIS_SEQ" id="RT_CL_DIS_SEQ"
                                                    class="form-control" min="0" placeholder="eg: 3" />
                                            </div>

                                            <label for="html5-range" class="col-form-label col-md-3"
                                                style="text-align: right;">Max
                                                Occupancy</label>
                                            <div class="col-md-3">
                                                <input type="number" name="RT_CL_DIS_SEQ" id="RT_CL_DIS_SEQ"
                                                    class="form-control" min="0" placeholder="eg: 3" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <div class="border rounded p-3 mb-3">
                                            <h6>Transaction Details</h6>
                                            <div class="row g-3 mb-3">
                                                <label for="html5-tel-input"
                                                    class="col-form-label col-md-4"><b>Transaction Code *</b></label>
                                                <div class="col-md-8">
                                                    <select id="transId" name="RT_CL_ID"
                                                        class="select2 form-select form-select-lg"
                                                        data-allow-clear="true" required>
                                                        <option label=""></option>
                                                        <option value="sfsdf">LSTAY | Wholesale Dynamic | GRPL |
                                                            2021-12-07
                                                            | 2025-12-24</option>
                                                        <option value="sfsdf">OTH | Others | OTH | 2021-12-07 |
                                                            2025-12-24</option>
                                                        <option value="sfsdf">HOUSE | House Use | HOUSE | 2021-12-07
                                                            | 2025-12-24</option>
                                                        <option value="sfsdf">WSD | Wholesale Dynamic | WSD | 2021-12-07
                                                            |
                                                            2025-12-24</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="switch">
                                                        <input type="checkbox" class="switch-input" />
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
                                                <label for="html5-tel-input" class="col-form-label col-md-4"><b>Package
                                                        Transaction Code *</b></label>
                                                <div class="col-md-8">
                                                    <select id="ptransId" name="RT_CL_ID"
                                                        class="select2 form-select form-select-lg"
                                                        data-allow-clear="true" required>
                                                        <option label=""></option>
                                                        <option value="sfsdf">LSTAY | Wholesale Dynamic | GRPL |
                                                            2021-12-07
                                                            | 2025-12-24</option>
                                                        <option value="sfsdf">OTH | Others | OTH | 2021-12-07 |
                                                            2025-12-24</option>
                                                        <option value="sfsdf">HOUSE | House Use | HOUSE | 2021-12-07
                                                            | 2025-12-24</option>
                                                        <option value="sfsdf">WSD | Wholesale Dynamic | WSD | 2021-12-07
                                                            |
                                                            2025-12-24</option>
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
                                                        <input type="checkbox" class="switch-input" />
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
                                                        <input type="checkbox" class="switch-input" />
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
                                                        <input type="checkbox" class="switch-input" />
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
                                                        <input type="checkbox" class="switch-input" />
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
                                                        <input type="checkbox" class="switch-input" />
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
                                                        <input type="checkbox" class="switch-input" />
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
                                    </div>

                                    <div class="d-flex col-12 justify-content-between">
                                        <button class="btn btn-label-secondary btn-prev" disabled>
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
                            <!-- Personal Info -->
                            <div id="personal-info-validation" class="content">

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <div class="border rounded p-4 mb-3">
                                            <h6>Dates</h6>

                                            <div class="row mb-3">
                                                <label for="html5-date-input" class="col-form-label col-md-3"><b>Start
                                                        Date *</b></label>
                                                <div class="col-md-6">
                                                    <input class="form-control" type="date" value="2022-02-18"
                                                        id="html5-date-input" />
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="html5-date-input" class="col-form-label col-md-3"><b>End
                                                        Date *</b></label>
                                                <div class="col-md-6">
                                                    <input class="form-control" type="date" value="2021-11-30"
                                                        id="html5-date-input" />
                                                </div>
                                            </div><br />
                                            <div class="row mb-3">

                                                <div class="col-md-2">
                                                    <label class="switch">
                                                        <input type="checkbox" class="switch-input" checked />
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
                                                        <input type="checkbox" class="switch-input" checked />
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
                                                        <input type="checkbox" class="switch-input" checked />
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
                                                        <input type="checkbox" class="switch-input" checked />
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
                                                        <input type="checkbox" class="switch-input" checked />
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
                                                        <input type="checkbox" class="switch-input" checked />
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
                                                        <input type="checkbox" class="switch-input" checked />
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
                                                        <label for="html5-color-input" class="col-form-label col-md-5"
                                                            style="text-align: right;"><b>1 Adult *</b></label>
                                                        <div class="col-md-7">
                                                            <input type="number" name="RT_CL_DIS_SEQ" id="RT_CL_DIS_SEQ"
                                                                class="form-control" min="0.00" step=".01"
                                                                placeholder="eg: 430.50" />
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="html5-color-input" class="col-form-label col-md-5"
                                                            style="text-align: right;">2 Adult</label>
                                                        <div class="col-md-7">
                                                            <input type="number" name="RT_CL_DIS_SEQ" id="RT_CL_DIS_SEQ"
                                                                class="form-control" min="0.00" step=".01"
                                                                placeholder="eg: 430.50" />
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="html5-color-input" class="col-form-label col-md-5"
                                                            style="text-align: right;">3 Adult</label>
                                                        <div class="col-md-7">
                                                            <input type="number" name="RT_CL_DIS_SEQ" id="RT_CL_DIS_SEQ"
                                                                class="form-control" min="0.00" step=".01"
                                                                placeholder="eg: 430.50" />
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="html5-color-input" class="col-form-label col-md-5"
                                                            style="text-align: right;">4 Adult</label>
                                                        <div class="col-md-7">
                                                            <input type="number" name="RT_CL_DIS_SEQ" id="RT_CL_DIS_SEQ"
                                                                class="form-control" min="0.00" step=".01"
                                                                placeholder="eg: 430.50" />
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="html5-color-input" class="col-form-label col-md-5"
                                                            style="text-align: right;">5 Adult</label>
                                                        <div class="col-md-7">
                                                            <input type="number" name="RT_CL_DIS_SEQ" id="RT_CL_DIS_SEQ"
                                                                class="form-control" min="0.00" step=".01"
                                                                placeholder="eg: 430.50" />
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="html5-color-input" class="col-form-label col-md-5"
                                                            style="text-align: right;">Extra Adult</label>
                                                        <div class="col-md-7">
                                                            <input type="number" name="RT_CL_DIS_SEQ" id="RT_CL_DIS_SEQ"
                                                                class="form-control" min="0.00" step=".01"
                                                                placeholder="eg: 430.50" />
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="html5-color-input" class="col-form-label col-md-5"
                                                            style="text-align: right;">Extra Child</label>
                                                        <div class="col-md-7">
                                                            <input type="number" name="RT_CL_DIS_SEQ" id="RT_CL_DIS_SEQ"
                                                                class="form-control" min="0.00" step=".01"
                                                                placeholder="eg: 430.50" />
                                                        </div>
                                                    </div>



                                                </div>
                                                <div class="col-md p-4">
                                                    <h6 class="">Children on Own</h6>

                                                    <div class="row mb-3">
                                                        <label for="html5-color-input" class="col-form-label col-md-5"
                                                            style="text-align: right;">1 Child</label>
                                                        <div class="col-md-7">
                                                            <input type="number" name="RT_CL_DIS_SEQ" id="RT_CL_DIS_SEQ"
                                                                class="form-control" min="0.00" step=".01"
                                                                placeholder="eg: 430.50" />
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="html5-color-input" class="col-form-label col-md-5"
                                                            style="text-align: right;">2 Children</label>
                                                        <div class="col-md-7">
                                                            <input type="number" name="RT_CL_DIS_SEQ" id="RT_CL_DIS_SEQ"
                                                                class="form-control" min="0.00" step=".01"
                                                                placeholder="eg: 430.50" />
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="html5-color-input" class="col-form-label col-md-5"
                                                            style="text-align: right;">3 Children</label>
                                                        <div class="col-md-7">
                                                            <input type="number" name="RT_CL_DIS_SEQ" id="RT_CL_DIS_SEQ"
                                                                class="form-control" min="0.00" step=".01"
                                                                placeholder="eg: 430.50" />
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="html5-color-input" class="col-form-label col-md-5"
                                                            style="text-align: right;">4 Children</label>
                                                        <div class="col-md-7">
                                                            <input type="number" name="RT_CL_DIS_SEQ" id="RT_CL_DIS_SEQ"
                                                                class="form-control" min="0.00" step=".01"
                                                                placeholder="eg: 430.50" />
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
                                                <div class="col-md-5">
                                                    <select id="marketId" name="RT_CL_ID"
                                                        class="select2 form-select form-select-lg"
                                                        data-allow-clear="true" required>
                                                        <option label=""></option>
                                                        <option value="1">OTA | Online Travel Agent</option>
                                                        <option value="2">LSTAY | Long Stay</option>
                                                        <option value="3">CORP | Corporate</option>
                                                        <option value="4">WSD | Wholesale Dynamic</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="html5-number-input"
                                                    class="col-form-label col-md-3">Source</label>
                                                <div class="col-md-5">
                                                    <select id="sourceId" name="RT_CL_ID"
                                                        class="select2 form-select form-select-lg"
                                                        data-allow-clear="true" required>
                                                        <option label=""></option>
                                                        <option value="sfsdf">LSTAY | Wholesale Dynamic | GRPL |
                                                            2021-12-07
                                                            | 2025-12-24</option>
                                                        <option value="sfsdf">OTH | Others | OTH | 2021-12-07 |
                                                            2025-12-24</option>
                                                        <option value="sfsdf">HOUSE | House Use | HOUSE | 2021-12-07
                                                            | 2025-12-24</option>
                                                        <option value="sfsdf">WSD | Wholesale Dynamic | WSD | 2021-12-07
                                                            |
                                                            2025-12-24</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="html5-month-input" class="col-form-label col-md-3"><b>Room
                                                        Types *</b></label>
                                                <div class="col-md-9">
                                                    <input id="TagifyUserList" name="TagifyUserList"
                                                        class="form-control"
                                                        value="R1B1BR, Residences 2 Tower B 1-BR, R4BSTD, Residences 3 Tower D 4-BR Duplex" />
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="html5-color-input"
                                                    class="col-form-label col-md-3">Packages</label>
                                                <div class="col-md-3">
                                                    <input type="number" name="RT_CL_DIS_SEQ" id="RT_CL_DIS_SEQ"
                                                        class="form-control" min="0" placeholder="eg: 3" />
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
                                                            <th>Start</th>
                                                            <th>End</th>
                                                            <th>Room Types</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="table-border-bottom-0">
                                                        <tr>
                                                            <td>
                                                                02/02/21
                                                            </td>
                                                            <td>30/09/22</td>
                                                            <td>
                                                                <span class="badge bg-label-primary">R1-DUP</span>
                                                                <span class="badge bg-label-secondary">R1-DUP</span>
                                                                <span class="badge bg-label-success">R1-DUP</span>
                                                                <span class="badge bg-label-danger">R1-DUP</span>
                                                                <span class="badge bg-label-secondary">R1-DUP</span>
                                                                <span class="badge bg-label-success">R1-DUP</span>
                                                                <span class="badge bg-label-danger">R1-DUP</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                02/02/21
                                                            </td>
                                                            <td>30/09/22</td>
                                                            <td>
                                                                <span class="badge bg-label-danger">R1-DUP</span>
                                                                <span class="badge bg-label-secondary">R1-DUP</span>
                                                                <span class="badge bg-label-primary">R1-DUP</span>
                                                                <span class="badge bg-label-secondary">R1-DUP</span>
                                                                <span class="badge bg-label-primary">R1-DUP</span>
                                                                <span class="badge bg-label-success">R1-DUP</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                02/02/21
                                                            </td>
                                                            <td>30/09/22</td>
                                                            <td>
                                                                <span class="badge bg-label-secondary">R1-DUP</span>
                                                                <span class="badge bg-label-danger">R1-DUP</span>
                                                                <span class="badge bg-label-primary">R1-DUP</span>
                                                                <span class="badge bg-label-success">R1-DUP</span>
                                                                <span class="badge bg-label-danger">R1-DUP</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                02/02/21
                                                            </td>
                                                            <td>30/09/22</td>
                                                            <td>
                                                                <span class="badge bg-label-success">R1-DUP</span>
                                                                <span class="badge bg-label-secondary">R1-DUP</span>
                                                                <span class="badge bg-label-primary">R1-DUP</span>
                                                                <span class="badge bg-label-danger">R1-DUP</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                02/02/21
                                                            </td>
                                                            <td>30/09/22</td>
                                                            <td>
                                                                <span class="badge bg-label-secondary">R1-DUP</span>
                                                                <span class="badge bg-label-danger">R1-DUP</span>
                                                                <span class="badge bg-label-primary">R1-DUP</span>
                                                                <span class="badge bg-label-success">R1-DUP</span>
                                                            </td>
                                                        </tr>


                                                    </tbody>
                                                </table>
                                            </div>

                                            <br />

                                            <button type="button" class="btn btn-primary">
                                                <i class="fa-solid fa-circle-plus"></i>&nbsp; Create New
                                            </button>&nbsp;

                                            <button type="button" class="btn btn-secondary">
                                                <i class="fa-solid fa-copy"></i>&nbsp; Repeat Selected
                                            </button>&nbsp;

                                            <button type="button" class="btn btn-danger">
                                                <i class="fa-solid fa-ban"></i>&nbsp; Delete Selected
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
                            <div id="social-links-validation" class="content">
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
</div>

<script>
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
        responsive: true
    });

});
</script>


<?= $this->endSection() ?>