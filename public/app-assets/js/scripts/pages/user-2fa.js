/**
 *  Page auth two steps
 */

'use strict';

document.addEventListener('DOMContentLoaded', function () {
  (function () {
    let maskWrapper = document.querySelector('.numeral-mask-wrapper');
    let firstPin = maskWrapper.children[0];

    for (let pin of maskWrapper.children) {
      // Handling paste event
      pin.addEventListener('paste', function (e) {
        e.preventDefault();
        let clipboardData = e.clipboardData || window.clipboardData;
        let pastedData = clipboardData.getData('text');

        // Check if the pasted data contains only numbers
        if (/^\d+$/.test(pastedData)) {
          // Check if the pasted data has the correct length
          if (pastedData.length === maskWrapper.children.length) {
            maskWrapper.querySelectorAll('.numeral-mask').forEach(function (el, index) {
              el.value = pastedData[index] || '';
            });

            // Focus on the last box after pasting
            maskWrapper.lastElementChild.focus();

            // Clear any previous error messages
            clearErrorMessage();
          }
          //  else {
          //   // Show an error message for incorrect length
          //   showErrorMessage('Incorrect length. Please paste a valid code.');
          // }
        } else {
          // Show an error message for invalid characters
          showErrorMessage('Invalid characters. Please paste numbers only.');
        }
      });

      // Handling input event
      pin.addEventListener('input', function () {
        // Move focus to the next input'
        if (pin.nextElementSibling && pin.value.length === parseInt(pin.attributes['maxlength'].value)) {
          pin.nextElementSibling.focus();
        }

        if (pin.value.length > parseInt(pin.attributes['maxlength'].value)) {
            pin.value = pin.value.slice(0, parseInt(pin.attributes['maxlength'].value));
        }

        // Get the typed value and set it to the corresponding input
        let typedValue = pin.value;
        let currentIndex = Array.from(maskWrapper.children).indexOf(pin);

        maskWrapper.querySelectorAll('.numeral-mask').forEach(function (el, index) {
          el.value = currentIndex === index ? typedValue : el.value;
        });

        // Clear any previous error messages
        clearErrorMessage();
      });

      // Handling keydown event
      pin.addEventListener('keydown', function (e) {
        // While deleting entered value, go to previous
        if (pin.previousElementSibling && (e.keyCode === 8 || e.keyCode === 46) && pin.value === '') {
          pin.previousElementSibling.focus();
        }

        // Clear any previous error messages
        clearErrorMessage();
      });
    }

    const twoStepsForm = document.querySelector('#twoStepsForm');

    // Form validation for Add new record
    if (twoStepsForm) {
      const numeralMaskList = twoStepsForm.querySelectorAll('.numeral-mask');
      const keyupHandler = function () {
        let otpFlag = true,
          otpVal = '';
        numeralMaskList.forEach(numeralMaskEl => {
          if (numeralMaskEl.value === '') {
            otpFlag = false;
            twoStepsForm.querySelector('[name="one_time_password"]').value = '';
          }
          otpVal = otpVal + numeralMaskEl.value;
        });

        // Check if the entered code has the correct length
        if (/^\d+$/.test(otpVal)) {
          if (otpVal.length === maskWrapper.children.length) {
            twoStepsForm.querySelector('[name="one_time_password"]').value = otpVal;
            document.getElementById('twoStepsForm').submit();
            // Clear any previous error messages
            clearErrorMessage();
          }
          // else {
          //   // Show an error message for incorrect length
          //   showErrorMessage('Incorrect length. Please enter a valid code.');
          // }
        } else {
          // Show an error message for invalid characters
          // showErrorMessage('Invalid characters. Please enter numbers only.');
        }
      };
      numeralMaskList.forEach(numeralMaskEle => {
        numeralMaskEle.addEventListener('keyup', keyupHandler);
      });
    }

    // Function to show an error message
    function showErrorMessage(message) {
      let errorMessageElement = document.getElementById('error-message');
      if (!errorMessageElement) {
        errorMessageElement = document.createElement('div');
        errorMessageElement.id = 'error-message';
        errorMessageElement.style.color = 'red';
        errorMessageElement.style.marginTop = '5px';
        maskWrapper.parentNode.insertBefore(errorMessageElement, maskWrapper.nextSibling);
      }
      errorMessageElement.textContent = message;
    }

    // Function to clear any previous error messages
    function clearErrorMessage() {
      let errorMessageElement = document.getElementById('error-message');
      if (errorMessageElement) {
        errorMessageElement.textContent = '';
      }
    }
  })();
});
