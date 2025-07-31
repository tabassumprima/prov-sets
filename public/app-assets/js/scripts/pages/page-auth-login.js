/*=========================================================================================
  File Name: form-validation.js
  Description: jquery bootstrap validation js
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: PIXINVENT
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

$(function () {
  'use strict';

    var pageLoginForm = $('.auth-login-form');

    function validateForm(element) {
      if (!$(element).valid()) {
          $('#loginFormButton').prop('disabled', true);
          $('#loginFormButton').find('.spinner-grow').hide();
          $('#loginFormButton').find('.loading-text').text('Sign in');
      } else {
          $('#loginFormButton').prop('disabled', false);
      }
    }

    // jQuery Validation
    // --------------------------------------------------------------------
    if (pageLoginForm.length) {
      pageLoginForm.validate({

        onkeyup: function (element) {
          validateForm(element);
        },

        onfocusout: function (element) {
          validateForm(element);
        },

        onchange: function (element) {
          validateForm(element);
        },


        input: function(element) {
          validateForm(element);
        },

        rules: {
          'login-email': {
            required: true,
            email: true
          },
          'login-password': {
            required: true
          }
        }
      });
    }
  });
