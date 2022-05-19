/**
 *  Form Wizard
 */

'use strict';

(function () {

  const select2 = $('.select2'),
    textField = $('.textField'),
    dateField = $('.dateField'),
    tagifyElems = $('.TagifyRoomTypeList,.TagifyRateCatList');
  //const selectPicker = $('.selectpicker');

  // Wizard Validation
  // --------------------------------------------------------------------
  const wizardValidation = document.querySelector('#wizard-validation');
  if (typeof wizardValidation !== undefined && wizardValidation !== null) {
    // Wizard form
    const wizardValidationForm = wizardValidation.querySelector('#packageCode-submit-form');
    // Wizard steps
    const wizardValidationFormStep1 = wizardValidationForm.querySelector('#package-header-validation');
    const wizardValidationFormStep2 = wizardValidationForm.querySelector('#package-detail-validation');

    // Wizard next prev button
    const wizardValidationNext = [].slice.call(wizardValidationForm.querySelectorAll('.btn-next'));
    const wizardValidationPrev = [].slice.call(wizardValidationForm.querySelectorAll('.btn-prev'));

    const validationStepper = new Stepper(wizardValidation, {
      linear: true
    });

    // Account details

    const FormValidation1 = FormValidation.formValidation(wizardValidationFormStep1, {
      fields: {
        PKG_CD_CODE: {
          validators: {
            notEmpty: {
              message: 'The Package Code is required'
            },
            stringLength: {
              min: 2,
              max: 15,
              message: 'The Package Code must be more than 2 and less than 15 characters long'
            },
            regexp: {
              regexp: /^[a-zA-Z0-9\- ]+$/,
              message: 'The Rate Code can only consist of alphabets, numbers, spaces and hyphen'
            }
          }
        },
        PKG_CD_DESC: {
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
        TR_CD_ID: {
          validators: {
            notEmpty: {
              message: 'Transaction Code cannot be empty'
            }
          }
        },
        PO_RH_ID: {
          validators: {
            notEmpty: {
              message: 'Posting Rhythm cannot be empty'
            }
          }
        },
        CLC_RL_ID: {
          validators: {
            notEmpty: {
              message: 'Calculation Rule cannot be empty'
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
              case 'PKG_CD_DESC':
                return '.col-md-7';
              case 'PKG_CD_CODE':
                return '.col-md-3';
              case 'TR_CD_ID':
                return '.col-md-4';
              case 'PO_RH_ID':
              case 'CLC_RL_ID':
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

      var pkgCodeID = $('#PKG_CD_ID').val();
      if (pkgCodeID)
        showPackageCodeDetails(pkgCodeID);

      // Jump to the next step when all fields in the current step are valid
      //validationStepper.next();

    });

    // Personal info
    const FormValidation2 = FormValidation.formValidation(wizardValidationFormStep2, {
      fields: {
        PKG_CD_START_DT: {
          validators: {
            notEmpty: {
              message: 'The Start Date cannot be empty'
            }
          }
        },
        PKG_CD_END_DT: {
          validators: {
            notEmpty: {
              message: 'The End Date cannot be empty'
            }
          }
        },
        PKG_CD_DT_PRICE: {
          validators: {
            notEmpty: {
              message: 'The Price cannot be empty'
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
              case 'PKG_CD_START_DT':
              case 'PKG_CD_END_DT':
              case 'PKG_CD_DT_PRICE':
                return '.col-md-8';
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
      //validationStepper.next();

    });


    if (textField.length) {
      textField.each(function () {
        var $this = $(this);
        $this
          .on('blur,change', function () {
            FormValidation1.revalidateField($this.attr('id'));
          });
      });
    }

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
            if (jQuery.inArray($this.attr('id'), ['TR_CD_ID', 'PO_RH_ID', 'CLC_RL_ID']) !== -1)
              FormValidation1.revalidateField($this.attr('id'));
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

    // Reset form validation when add or edit form is loaded
    $(document).on('click', '.addWindow,.editWindow', function () {
      FormValidation1.resetForm();
    });

    $(document).on('click', '.saveBtn', function () {
      FormValidation1.validate();
    });

    $(document).on('click', '.add-package-code-detail', function () {
      FormValidation2.resetForm();
    });

    $(document).on('click', '.save-package-code-detail', function () {
      FormValidation2.validate();
    });

    $(document).on('click', '.btn-next', function () {

      FormValidation1.validate().then(function (status) {
        // status can be one of the following value
        // 'NotValidated': The form is not yet validated
        // 'Valid': The form is valid
        // 'Invalid': The form is invalid
        if (status == 'Valid')
          validationStepper.next();
      });

    });

    $(document).on('click', '#PKG_CD_Details > tbody > tr', function () {

      $('#PKG_CD_Details').find('tr.table-warning').removeClass('table-warning');

      $(this).addClass('table-warning');

      $.when(loadPackageCodeDetails($(this).data('packagecodeid'), $(this).data('packagedetailsid')))
        .done(function () {
          FormValidation2.revalidateField('PKG_CD_START_DT');
          FormValidation2.revalidateField('PKG_CD_END_DT');
          FormValidation2.revalidateField('PKG_CD_DT_PRICE');
        })
        .done(function () {
          blockLoader('package-detail-validation');
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


  // Users List suggestion
  //------------------------------------------------------
  var TagifyPackageCodeListPackageCodeEl = document.querySelectorAll('.TagifyPackageCodeList');

  function tagPackageCodeTemplate(tagData) {
    return `
     <tag title="${tagData.title || tagData.desc}"
       contenteditable='false'
       spellcheck='false'
       tabIndex="-1"
       class="${this.settings.classNames.tag} ${tagData.class ? tagData.class : ''}"
       ${this.getAttributes(tagData)}
     >
       <x title='' class='tagify__tag__removeBtn' role='button' aria-label='remove tag'></x>
       <div>
         <span class='tagify__tag-text'>${tagData.name}</span>
       </div>
     </tag>
   `;
  }

  function suggestionPackageCodeItemTemplate(tagData) {
    return `
     <div ${this.getAttributes(tagData)}
       class='tagify__dropdown__item align-items-center ${tagData.class ? tagData.class : ''}'
       tabindex="0"
       role="option"
     >
       <strong>${tagData.name}</strong>
       <span>${tagData.desc}</span>
     </div>
   `;
  }

  TagifyPackageCodeListPackageCodeEl.forEach(function (el) {

    // initialize Tagify on the above input node reference
    let TagifyPackageCodeList = new Tagify(el, {
      tagTextProp: 'name', // very important since a custom template is used with this property as text. allows typing a "value" or a "name" to match input with whitelist
      enforceWhitelist: true,
      skipInvalid: true, // do not temporarily add invalid tags
      fuzzySearch: false,
      dropdown: {
        closeOnSelect: false,
        enabled: 0,
        classname: 'users-list',
        maxItems: 1000,
        mapValueTo: 'name',
        searchKeys: ['name', 'desc'] // very important to set by which keys to search for suggesttions when typing
      },
      templates: {
        tag: tagPackageCodeTemplate,
        dropdownItem: suggestionPackageCodeItemTemplate
      },
      whitelist: packageCodeList,
      callbacks: {
        //"remove": (e) => $('#RT_CL_CODE').val(""),
        "blur": (e) => dropdown.hide()
      }
    });

    TagifyPackageCodeList.on('dropdown:show dropdown:updated', onDropdownPackageCodeShow);
    TagifyPackageCodeList.on('dropdown:select', onSelectPackageCodeSuggestion);

    let addAllPackageCodeSuggestionsEl;

    function onDropdownPackageCodeShow(e) {
      let dropdownContentEl = e.detail.tagify.DOM.dropdown.content;

      if (TagifyPackageCodeList.suggestedListItems.length > 1) {
        addAllPackageCodeSuggestionsEl = getAddAllSuggestionsEl();

        // insert "addAllPackageCodeSuggestionsEl" as the first element in the suggestions list
        dropdownContentEl.insertBefore(addAllPackageCodeSuggestionsEl, dropdownContentEl.firstChild);
      }
    }

    function onSelectPackageCodeSuggestion(e) {
      if (e.detail.elm == addAllPackageCodeSuggestionsEl) TagifyPackageCodeList.dropdown.selectAll.call(TagifyPackageCodeList);
    }

    // create an "add all" custom suggestion element every time the dropdown changes
    function getAddAllSuggestionsEl() {
      // suggestions items should be based on "dropdownItem" template
      return TagifyPackageCodeList.parseTemplate('dropdownItem', [{
        class: 'addAll',
        name: 'Add all',
        desc: TagifyPackageCodeList.settings.whitelist.reduce(function (remainingSuggestions, item) {
          return TagifyPackageCodeList.isTagDuplicate(item.value) ? remainingSuggestions : remainingSuggestions + 1;
        }, 0) + ' Packages'
      }]);
    }

    if (el.id == 'RT_CD_PACKAGES' && typeof selectedPackageCodes !== 'undefined' && selectedPackageCodes != '') {
      TagifyPackageCodeList.addTags(selectedPackageCodes);
    }

  });


})();