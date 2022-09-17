/**
 *  Form Wizard
 */

'use strict';

(function () {

  const wizardIcons = document.querySelector('.wizard-icons-example');

  if (typeof wizardIcons !== undefined && wizardIcons !== null) {
    const wizardIconsBtnNextList = [].slice.call(wizardIcons.querySelectorAll('.btn-next')),
      wizardIconsBtnPrevList = [].slice.call(wizardIcons.querySelectorAll('.btn-prev')),
      amenityOrderRequest = document.querySelector('.amenityOrderRequest'),
      confirmProducts = document.querySelector('.confirmProducts'),
      confirmReservDetails = document.querySelector('.confirmReservDetails');

    const iconsStepper = new Stepper(wizardIcons, {
      linear: true
    });
    if (wizardIconsBtnNextList) {
      wizardIconsBtnNextList.forEach(wizardIconsBtnNext => {
        wizardIconsBtnNext.addEventListener('click', event => {
          iconsStepper.next();
        });
      });
    }
    if (wizardIconsBtnPrevList) {
      wizardIconsBtnPrevList.forEach(wizardIconsBtnPrev => {
        wizardIconsBtnPrev.addEventListener('click', event => {
          iconsStepper.previous();
        });
      });
    }
    
    amenityOrderRequest.addEventListener('click', event => {
      var currentTab = parseInt(iconsStepper._currentIndex);
      for (var tabStep = currentTab; tabStep >= 0; tabStep--)
        iconsStepper.previous();
    });

    confirmReservDetails.addEventListener('click', event => {

      if (!$('#LAO_RESERVATION_ID').val()) {
        showModalAlert('error', '<li>Please select a reservation</li>');
      } else if (!$('#LAO_ROOM_ID').val()) {
        showModalAlert('error', '<li>Please select a room</li>');
      } else if (!$('#LAO_CUSTOMER_ID').val()) {
        showModalAlert('error', '<li>Please select a customer</li>');
      } else
        iconsStepper.next();

    });

    confirmProducts.addEventListener('click', event => {
      if (selectedProductDetails.length !== 0)
        iconsStepper.next();
      else
        showModalAlert('error', '<li>Please select at least one product</li>');
    });

  }

})();


document.addEventListener('DOMContentLoaded', function () {
  (function () {
    const verticalExamples = document.getElementsByClassName('vertical-scroll');

    // Vertical Example
    // --------------------------------------------------------------------
    if (verticalExamples) {
      for (let i = 0; i < verticalExamples.length; i++) {
        new PerfectScrollbar(verticalExamples[i], {
          wheelPropagation: false
        });
      }
    }
  })();
});