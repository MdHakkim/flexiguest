/**
 *  Form Wizard
 */

'use strict';

(function () {



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