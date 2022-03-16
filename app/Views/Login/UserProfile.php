
<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>
    <!-- Content wrapper -->
    <div class="content-wrapper">
      <!-- Content -->

      <div class="container-xxl flex-grow-1 container-p-y">
        <h6 class="breadcrumb-wrapper py-3 mb-2">
          <span class="text-muted fw-light"></span> User Information
        </h6>
        <!-- Multi Column with Form Separator -->
        <div class="card mb-4">
          <form class="card-body">
            <h6 class="fw-normal">Personal Info</h6>
            <div class="row g-3">
              <div class="col-md-3">
                <label class="form-label">First Name</label>
                <input type="text" name="GUST_PRF_FIRST_NAME" id="GUST_PRF_FIRST_NAME" class="form-control" placeholder="first name" />
              </div>
              <div class="col-md-3">
                <label class="form-label">Middle Name</label>
                <input type="text" name="GUST_PRF_MIDDLE_NAME"  id="GUST_PRF_MIDDLE_NAME" class="form-control" placeholder="middle name" />
              </div>
              <div class="col-md-3">
                <label class="form-label">Last Name</label>
                <input type="text" name="GUST_PRF_LAST_NAME" id="GUST_PRF_LAST_NAME" class="form-control" placeholder="last name" />
              </div>
              <div class="col-md-3">
                <label class="form-label">Language/Title</label>
                  <div class="form-group flxi_join">
                    <select name="GUST_PRF_LANG" id="GUST_PRF_LANG" class="form-select" data-allow-clear="true">
                      <option value="">Select</option>
                    </select>
                    <select name="GUST_PRF_TITLE" id="GUST_PRF_TITLE" class="form-select" data-allow-clear="true">
                      <option value="">Select</option>
                    </select>
                  </div>
              </div>
              <div class="col-md-3">
                <label class="form-label">DOB</label>
                <input type="text" name="GUST_PRF_DOB"  id="GUST_PRF_DOB" class="form-control" placeholder="DOB" />
              </div>
              <div class="col-md-3">
                <label class="form-label">Passport</label>
                <input type="text" name="GUST_PRF_PASSPORT"  id="GUST_PRF_PASSPORT" class="form-control" placeholder="passport" />
              </div>
              <div class="col-md-3">
                <label class="form-label">Address</label>
                <input type="text" name="GUST_PRF_ADDRESS1"  id="GUST_PRF_ADDRESS1" class="form-control" placeholder="addresss" />
              </div> 
              <div class="col-md-3">
                <label class="form-label"></label>
                <input type="text" name="GUST_PRF_ADDRESS2"  id="GUST_PRF_ADDRESS2" class="form-control" placeholder="address" />
              </div> 
              <div class="col-md-3">
                <label class="form-label"></label>
                <input type="text" name="GUST_PRF_ADDRESS3"  id="GUST_PRF_ADDRESS3" class="form-control" placeholder="address" />
              </div> 
              <div class="col-md-3">
                <label class="form-label">Country</label>
                <select name="GUST_PRF_COUNTRY"  id="GUST_PRF_COUNTRY" class="select2 form-select" data-allow-clear="true">
                  <option value="">Select</option>
                </select>
              </div> 
              <div class="col-md-3">
                <label class="form-label">State</label>
                <select name="GUST_PRF_STATE"  id="GUST_PRF_STATE" class="select2 form-select" data-allow-clear="true">
                  <option value="">Select</option>
                </select>
              </div> 
              <div class="col-md-3">
                <label class="form-label">City</label>
                <select name="GUST_PRF_CITY"  id="GUST_PRF_CITY" class="select2 form-select" data-allow-clear="true">
                  <option value="">Select</option>
                </select>
              </div> 
              <div class="col-md-3">
                <label class="form-label">Email</label>
                <input type="text" name="GUST_PRF_EMAIL"  id="GUST_PRF_EMAIL" class="form-control" placeholder="email" />
              </div>
              <div class="col-md-3">
                <label class="form-label">Mobile</label>
                <input type="text" name="GUST_PRF_MOBILE"  id="GUST_PRF_MOBILE" class="form-control" placeholder="mobile" />
              </div>
              <div class="col-md-3">
                <label class="form-label">Phone</label>
                <input type="text" name="GUST_PRF_PHONE"  id="GUST_PRF_PHONE" class="form-control" placeholder="phone" />
              </div>
              <div class="col-md-3">
                <label class="form-label">Client ID</label>
                <input type="text" name="GUST_PRF_CLIENT_ID"  id="GUST_PRF_CLIENT_ID" class="form-control" placeholder="client id" />
              </div>
              <div class="col-md-3">
                <label class="form-label">Postal Code</label>
                <input type="text" name="GUST_PRF_POSTAL"  id="GUST_PRF_POSTAL" class="form-control" placeholder="postal" />
              </div> 
              <div class="col-md-3">
                <label class="form-label">VIP</label>
                <select name="GUST_PRF_VIP"  id="GUST_PRF_VIP" class="select2 form-select" data-allow-clear="true">
                  <option value="">Select</option>
                </select>
              </div> 
              <div class="col-md-3">
                <label class="form-label">Nationality</label>
                <select name="GUST_PRF_VIP"  id="GUST_PRF_VIP" class="select2 form-select" data-allow-clear="true">
                  <option value="">Select</option>
                </select>
              </div> 
              <div class="col-md-3">
                <label class="form-label">Bus Segment</label>
                <select name="GUST_PRF_VIP"  id="GUST_PRF_VIP" class="select2 form-select" data-allow-clear="true">
                  <option value="">Select</option>
                </select>
              </div>
              <div class="col-md-3">
                  <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck1"> Active </label>
                  </div>
              </div> 
              
            </div>
            <!-- <hr class="mx-n4 my-4" /> -->
            <!-- <h6 class="fw-normal">2. Personal Info</h6> -->
            <div class="pt-4">
              <button type="submit" class="btn btn-primary me-1 me-sm-3">Submit</button>
              <button type="reset" class="btn btn-label-secondary">Cancel</button>
            </div>
          </form>
        </div>
        <!-- Collapsible Section -->
        
      <div class="content-backdrop fade"></div>
    </div>
<?=$this->endSection()?>