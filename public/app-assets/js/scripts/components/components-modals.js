/*=========================================================================================
    File Name: components-modal.js
    Description: Modals are streamlined, but flexible, dialog prompts with the minimum
				required functionality and smart defaults.
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
    Author: Pixinvent
    Author URL: hhttp://www.themeforest.net/user/pixinvent
==========================================================================================*/
(function (window, document, $) {
  'use strict';

  var onShowEvent = $('#onshow'),
    onShownEvent = $('#onshown'),
    onHideEvent = $('#onhide'),
    onHiddenEvent = $('#onhidden');

  // onShow event
  onShowEvent.on('show.bs.modal', function () {
    alert('onShow event fired.');
  });

  // onShown event
  onShownEvent.on('shown.bs.modal', function () {
    alert('onShown event fired.');
  });
  // onShown event
  // onShownEvent.on('shown.bs.modal', function () {
  //   var progress = $('.progress .progress-bar');
  //   var greeting = 'Estimating provisions';
  //   function counterInit( fValue, lValue ) {
  //     var counter_value = parseInt( $('.counter').text() );
  //     counter_value++;
  
  //     if( counter_value >= fValue && counter_value <= lValue ) {
  
  //       $('.counter').text( counter_value + '%' );
  //       progress.css({ 'width': counter_value + '%' });

  //       if (counter_value < 10) {
  //         greeting = "Calculating UPR";
  //       } else if (counter_value < 30) {
  //         greeting = "Generating IBNR triangles";
  //       } else if (counter_value < 50) {
  //         greeting = "Calculating risk adjustments";
  //       } else if (counter_value < 70) {
  //         greeting = "Projecting cashflows";
  //       } else if (counter_value < 90) {
  //         greeting = "Preparing accounting entries";
  //       } else {
  //         greeting = "Finalizing provisions";
  //       }

  //       if (counter_value > 99) {
  //         $('.modal').modal('hide');
  //         $('.alert').addClass('show');
  //       };
  //       $('.updates').text( greeting );

  //       setTimeout( function() {
  //         counterInit( fValue, lValue );
  //       }, 150 );
  //     }
  //   }
  //   counterInit( 0, 100 );
  // });

  // onHide event
  onHideEvent.on('hide.bs.modal', function () {
    alert('onHide event fired.');
  });

  // onHidden event
  onHiddenEvent.on('hidden.bs.modal', function () {
    alert('onHidden event fired.');
  });
})(window, document, jQuery);
