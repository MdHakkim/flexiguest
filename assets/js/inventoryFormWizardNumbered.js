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
     const wizardValidationForm = wizardValidation.querySelector('#item-submit-form');
     // Wizard steps
     const wizardValidationFormStep1 = wizardValidationForm.querySelector('#select_items');
 
     // Wizard next prev button
     const wizardValidationNext = [].slice.call(wizardValidationForm.querySelectorAll('.btn-next'));
     const wizardValidationPrev = [].slice.call(wizardValidationForm.querySelectorAll('.btn-prev'));
 
     const validationStepper = new Stepper(wizardValidation, {
       linear: false
     });
 
 
 
     // Personal info
     const FormValidation1 = FormValidation.formValidation(wizardValidationFormStep1, {
       fields: {
        RSV_ITM_CL_ID: {
          validators: {
            notEmpty: {
              message: 'Class cannot be empty'
            }
          }
        },
        RSV_ITM_ID: {
          validators: {
            notEmpty: {
              message: 'Item cannot be empty'
            }
          }
        },
        RSV_ITM_BEGIN_DATE: {
           validators: {
             notEmpty: {
               message: 'The Start Date cannot be empty'
             }
           }
         },
         RSV_ITM_END_DATE: {
           validators: {
             notEmpty: {
               message: 'The End Date cannot be empty'
             }
           }
         },
         RSV_ITM_QTY: {
           validators: {
             notEmpty: {
               message: 'The QTY cannot be empty'
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
              
              case 'RSV_ITM_CL_ID':
                case 'RSV_ITM_ID':
               case 'RSV_ITM_BEGIN_DATE':
               case 'RSV_ITM_END_DATE':
               case 'RSV_ITM_QTY':
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
             if (jQuery.inArray($this.attr('id'), ['RSV_ITM_BEGIN_DATE', 'RSV_ITM_END_DATE']) !== -1)
             FormValidation1.revalidateField($this.attr('id'));
             else
             FormValidation1.revalidateField($this.attr('id'));
           });
       });
     }
 

     
 
    
 
  
 
    
 
     $(document).on('click', '.save-item-detail', function () {
 
      FormValidation1.validate().then(function (status) {

        //alert(status);return;
         // status can be one of the following value
         // 'NotValidated': The form is not yet validated
         // 'Valid': The form is valid
         // 'Invalid': The form is invalid
         if (status == 'Valid'){
           //validationStepper.next();
           submitDetailsForm('item-submit-form');
         }
       });
 
     });
 
     $(document).on('click', '#Inventory_Details > tbody > tr', function () {
 
       $('#Inventory_Details').find('tr.table-warning').removeClass('table-warning');
       $(this).addClass('table-warning');
       
       
       $.when(loadInventoryDetails($(this).data('itemid')))
         .done(function () {
          FormValidation1.revalidateField('RSV_ITM_CL_ID');
          FormValidation1.revalidateField('RSV_ITM_ID');
          FormValidation1.revalidateField('RSV_ITM_BEGIN_DATE');
          FormValidation1.revalidateField('RSV_ITM_END_DATE');
          FormValidation1.revalidateField('RSV_ITM_QTY');
          
         })
         .done(function () {
          
          blockLoader('select_items');
         });
     });
 
     // Bootstrap Select (i.e Language select)
     /*
     if (selectPicker.length) {
       selectPicker.each(function () {
         var $this = $(this);
         $this.selectpicker().on('change', function () {
           FormValidation.revalidateField('formValidationLanguage');
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
            FormValidation1.validate();
             break;
 
           default:
             break;
         }
       });
     });
 
 
   }
 

 
 
 })();