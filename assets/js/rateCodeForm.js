/**
 *  Form Wizard
 */

'use strict';

(function () {

  const select2 = $('.select2'),
    dateField = $('.dateField'),
    tagifyElems = $('.TagifyRoomTypeList,.TagifyRateCatList');
  //const selectPicker = $('.selectpicker');

  // Wizard Validation
  // --------------------------------------------------------------------
  const wizardValidation = document.querySelector('#wizard-validation');
  if (typeof wizardValidation !== undefined && wizardValidation !== null) {
    // Wizard form
    const wizardValidationForm = wizardValidation.querySelector('#rateCode-submit-form');
    // Wizard steps
    const wizardValidationFormStep1 = wizardValidationForm.querySelector('#rate-header-validation');
    const wizardValidationFormStep2 = wizardValidationForm.querySelector('#rate-detail-validation');
    const wizardValidationFormStep3 = wizardValidationForm.querySelector('#negotiated-rate-validation');
    // Wizard next prev button
    const wizardValidationNext = [].slice.call(wizardValidationForm.querySelectorAll('.btn-next'));
    const wizardValidationPrev = [].slice.call(wizardValidationForm.querySelectorAll('.btn-prev'));

    const validationStepper = new Stepper(wizardValidation, {
      linear: true
    });

    // Account details

    const FormValidation1 = FormValidation.formValidation(wizardValidationFormStep1, {
      fields: {
        RT_CD_CODE: {
          validators: {
            notEmpty: {
              message: 'The Rate Code is required'
            },
            stringLength: {
              min: 2,
              max: 15,
              message: 'The Rate Code must be more than 2 and less than 15 characters long'
            },
            regexp: {
              regexp: /^[a-zA-Z0-9\- ]+$/,
              message: 'The Rate Code can only consist of alphabets, numbers, spaces and hyphen'
            }
          }
        },
        RT_CD_DESC: {
          validators: {
            notEmpty: {
              message: 'Description is required'
            },
            stringLength: {
              min: 5,
              max: 50,
              message: 'The Description must be more than 5 and less than 50 characters long'
            }
          }
        },
        RT_CT_ID: {
          validators: {
            notEmpty: {
              message: 'Rate Category cannot be empty'
            }
          }
        },
        RT_CD_BEGIN_SELL_DT: {
          validators: {
            notEmpty: {
              message: 'The Begin Sell Date cannot be empty'
            }
          }
        },
        RT_CD_END_SELL_DT: {
          validators: {
            notEmpty: {
              message: 'The End Sell Date cannot be empty'
            }
          }
        },
        RT_CD_ROOM_TYPES: {
          validators: {
            notEmpty: {
              message: 'Room Types cannot be empty'
            }
          }
        },
        TR_CD_ID: {
          validators: {
            notEmpty: {
              message: 'Transaction Code cannot be empty'
            }
          }
        }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          // Use this for enabling/changing valid/invalid class
          // eleInvalidClass: '',
          eleValidClass: '',
          rowSelector: function (field, ele) {
            // field is the field name & ele is the field element
            switch (field) {
              case 'RT_CD_DESC':
              case 'RT_CD_FOLIO':
              case 'RT_CD_ROOM_TYPES':
                return '.col-md-9';
              case 'RT_CD_CODE':
              case 'RT_CL_CODE':
              case 'RT_CD_BEGIN_SELL_DT':
              case 'RT_CD_END_SELL_DT':
                return '.col-md-5';
              case 'TR_CD_ID':
              case 'PKG_TR_CD_ID':
                return '.col-md-8';
              default:
                return '.row';
            }
          }
        }),
        autoFocus: new FormValidation.plugins.AutoFocus(),
        submitButton: new FormValidation.plugins.SubmitButton()
      },
      init: instance => {
        instance.on('plugins.message.placed', function (e) {
          //* Move the error message out of the `input-group` element
          if (e.element.parentElement.classList.contains('input-group')) {
            e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
          }
        });
      }
    }).on('core.form.valid', function () {
      // Jump to the next step when all fields in the current step are valid
      if (!rateCodeID)
        submitForm('rateCode-submit-form');
      else {
        if (rateCodeDetailID) {
          rateCodeDetailID = $('#RT_CD_DT_ID').val() != '' ? $('#RT_CD_DT_ID').val() : rateCodeDetailID;
          $('#RD_Room_Types').find('tr.table-warning').removeClass('table-warning');
          $('#RD_Room_Types').find("[data-ratedetailsid='" + rateCodeDetailID + "']").addClass('table-warning');
          loadRateCodeDetails(rateCodeDetailID);
        }
        validationStepper.next();
      }
    });

    // Personal info
    const FormValidation2 = FormValidation.formValidation(wizardValidationFormStep2, {
      fields: {
        RT_CD_START_DT: {
          validators: {
            notEmpty: {
              message: 'The Start Date cannot be empty'
            }
          }
        },
        RT_CD_END_DT: {
          validators: {
            notEmpty: {
              message: 'The End Date cannot be empty'
            }
          }
        },
        RT_CD_DT_1_ADULT: {
          validators: {
            notEmpty: {
              message: 'Rate for 1 Adult cannot be empty'
            }
          }
        },
        "RT_CD_DT_ROOM_TYPES[]": {
          validators: {
            notEmpty: {
              message: 'Room Types cannot be empty'
            }
          }
        },
        "RT_CD_DT_DAYS[]": {
          validators: {
            notEmpty: {
              message: false
            }
          }
        },
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          // Use this for enabling/changing valid/invalid class
          // eleInvalidClass: '',
          eleValidClass: '',
          rowSelector: function (field, ele) {
            // field is the field name & ele is the field element
            switch (field) {
              case 'RT_CD_START_DT':
              case 'RT_CD_END_DT':
                return '.col-md-6';
              case 'RT_CD_DT_1_ADULT':
                return '.col-md-7';
              default:
                return '.row';
            }
          }
        }),
        autoFocus: new FormValidation.plugins.AutoFocus(),
        submitButton: new FormValidation.plugins.SubmitButton()
      }
    }).on('core.form.valid', function () {
      // Jump to the next step when all fields in the current step are valid
      // You can submit the form
      // wizardValidationForm.submit()
      // or send the form data to server via an Ajax request
      // To make the demo simple, I just placed an alert
      //alert('Submitted..!!');
      validationStepper.next();

      //submitForm('rateCode-submit-form');

    });


    if (dateField.length) {
      dateField.each(function () {
        var $this = $(this);
        $this
          .on('blur,change', function () {
            if (jQuery.inArray($this.attr('id'), ['RT_CD_BEGIN_SELL_DT', 'RT_CD_END_SELL_DT']) !== -1)
              FormValidation1.revalidateField($this.attr('id'));
            else
              FormValidation2.revalidateField($this.attr('id'));
          });
      });
    }

    // select2
    if (select2.length) {
      select2.each(function () {
        var $this = $(this);
        $this
          .on('change.select2', function () {
            // Revalidate the color field when an option is chosen
            if ($this.attr('id') == 'TR_CD_ID')
              FormValidation1.revalidateField('TR_CD_ID');
            else if ($this.attr('id') == 'RT_CD_DT_ROOM_TYPES')
              FormValidation2.revalidateField('RT_CD_DT_ROOM_TYPES[]');
          });
      });
    }

    if (tagifyElems.length) {
      tagifyElems.each(function () {
        var $this = $(this);
        $this
          .on('change', function () {
            // Revalidate the color field when an option is chosen
            if ($this.attr('id') == 'RT_CT_ID')
              FormValidation1.revalidateField('RT_CT_ID');
            else if ($this.attr('id') == 'RT_CD_ROOM_TYPES')
              FormValidation1.revalidateField('RT_CD_ROOM_TYPES');
          });
      });
    }

    $(document).on('click', '#RD_Room_Types > tbody > tr', function () {

      $('#RD_Room_Types').find('tr.table-warning').removeClass('table-warning');

      $(this).addClass('table-warning');

      $.when(loadRateCodeDetails($(this).data('ratedetailsid')))
        .done(function () {
          FormValidation2.revalidateField('RT_CD_START_DT');
          FormValidation2.revalidateField('RT_CD_END_DT');
          FormValidation2.revalidateField('RT_CD_DT_1_ADULT');
        })
        .done(function () {
          blockLoader('rate-detail-validation');
        });
    });

    // Bootstrap Select (i.e Language select)
    /*
    if (selectPicker.length) {
      selectPicker.each(function () {
        var $this = $(this);
        $this.selectpicker().on('change', function () {
          FormValidation2.revalidateField('formValidationLanguage');
        });
      });
    }
    */

    wizardValidationNext.forEach(item => {
      item.addEventListener('click', event => {
        // When click the Next button, we will validate the current step
        switch (validationStepper._currentIndex) {
          case 0:
            FormValidation1.validate();
            break;

          case 1:
            FormValidation2.validate();
            break;

          default:
            break;
        }
      });
    });

    wizardValidationPrev.forEach(item => {
      item.addEventListener('click', event => {
        switch (validationStepper._currentIndex) {
          case 2:
          case 1:
            validationStepper.previous();
            break;

          case 0:

          default:
            break;
        }
      });
    });
  }

  // Negotiated Rate Advanced Search Functions Starts
  // --------------------------------------------------------------------

  /*
  const dt_adv_filter_table = $('#combined_profiles');

  // Filter column wise function
  function filterColumn(i, val) {
    dt_adv_filter_table.DataTable().column(i).search(val).draw();
  }

  // on key up from input field
  $(document).on('keyup', 'input.dt-input', function () {
    if ($(this).val().length == 0 || $(this).val().length >= 2)
      filterColumn($(this).attr('data-column'), $(this).val());
  });

  $(document).on('change', 'select.dt-select', function () {
    filterColumn($(this).attr('data-column'), $(this).val());
  });
  */
 
  // Advanced Search Functions Ends


})();