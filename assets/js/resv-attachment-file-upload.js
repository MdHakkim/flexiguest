/**
 * File Upload
 */

'use strict';

(function () {
  // previewTemplate: Updated Dropzone default previewTemplate
  // ! Don't change it unless you really know what you are doing
  const previewTemplate = `<div class="dz-preview dz-file-preview">
    <div class="dz-details">
      <div class="dz-thumbnail">
        <img data-dz-thumbnail>
        <span class="dz-nopreview">No preview</span>
        <div class="dz-success-mark"></div>
        <div class="dz-error-mark"></div>
        <div class="dz-error-message"><span data-dz-errormessage></span></div>
        <div class="progress">
          <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
        </div>
      </div>
      <div class="dz-filename" data-dz-name></div>
      <div class="dz-size" data-dz-size></div>
    </div>
    </div>`;

  // ? Start your code from here

  // Multiple Dropzone
  // --------------------------------------------------------------------

  var dropzoneMulti = new Dropzone('#dropzone-multi', {
    url: mainUrl + '/uploadResvAttachments',
    previewTemplate: previewTemplate,
    parallelUploads: 100,
    maxFilesize: 5, // MB
    maxFiles: 100,
    addRemoveLinks: true,
    paramName: "attachmentFile",
    acceptedFiles: "image/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document",
    autoProcessQueue: false,
    uploadMultiple: true,
    accept: function (file, done) {
      console.log("uploaded");
      done();
    },
    error: function (file, msg) {
      console.log(msg);
    },
    init: function () {

      var submitButton = document.querySelector("#submitResvAttchBtn")
      var myDropzone = this;

      submitButton.addEventListener("click", function () {

        /* Check if file is selected for upload */
        if (myDropzone.getUploadingFiles().length === 0 && myDropzone.getQueuedFiles().length === 0) {
          alert('No file selected for upload');
          return false;
        } else {
          /* Remove event listener and start processing */
          //myDropzone.removeEventListeners();
          myDropzone.processQueue();
        }
      });


      /* On Success, do whatever you want */
      this.on("success", function (file, responseText) {
        alert('Success');
      });
    }

  });

})();