$(document).ready(function() {

    // Initialize the repeater plugin
    // $('#repeater-form').repeater();

    // Form submission and validation
    $('#repeater-form').submit(function(e) {
      e.preventDefault();
      var valid = true;
      var debitSum = 0;
      var creditSum = 0;

      accounting_year_id = $('#accounting-year');
      voucher_type_id = $('#voucher_type_id');
      branch_id = $('#branch');
      business_type_id = $('#business-type');
      voucher_date = $('#voucher-date');
      system_narration  =$ ('#narration')
      if (!checkRequiredField(accounting_year_id, 'Accounting Year')) {
        return;
    }
    if (!checkRequiredField(voucher_type_id, 'Voucher Type')) {
        return;
    }
    if (!checkRequiredField(branch_id, 'Branch ')) {
        return;
    }
    if (!checkRequiredField(business_type_id, 'Business Type')) {
        return;
    }
    if (!checkRequiredField(voucher_date, 'Voucher Date')) {
        return;
    }
    if (!checkRequiredField(system_narration, 'Narration')) {
        return;
    }
      // Validate each repeater item
      var repeaterItems = $('#repeater-form [data-repeater-item]');
      repeaterItems.each(function(index, item) {
        var glCodeId = $(item).find('.gl_code_id');
        var debit = $(item).find('.debit');
        var credit = $(item).find('.credit');
        var policyReference = $(item).find('.policy_reference');
        var portfolioId = $(item).find('.portfolio_id');
        var system_department = $(item).find('.system_department_id');

        var groupCodeId = $(item).find('.insurance')
        var treatyGroupCodeId = $(item).find('.re-insurance')
        var facGroupCodeId = $(item).find('.fac-reinsurance')

        if(debit.val() === '' && credit.val() === ''){
            toastr['error']('credit and debit must be filled', 'Error', {
                closeButton: true,
                tapToDismiss: false,
            });
            valid = false
            return false;
        }
         // Update the sum of credits and debits
        if (debit.val() !== '')
            debitSum += parseFloat(debit.val());
        else if (credit.val() !== '')
            creditSum += parseFloat(credit.val());

        if (!checkRequiredField(glCodeId, 'GL Code ID')) {
            return;
        }

        if (!checkRequiredField(policyReference, 'Policy Reference')) {
            return;
        }

        if (!checkRequiredField(system_department, 'System Department')) {
            return;
        }

        if (!checkRequiredField(portfolioId, 'Portfolio ID')) {
            return;
        }

        checkRequiredField(groupCodeId, 'Group Code ID');
        checkRequiredField(treatyGroupCodeId, 'Treaty Group Code ID');
        checkRequiredField(facGroupCodeId, 'Fac Group Code ID');


      });

      if (debitSum !== creditSum) {
        toastr['error']('crdeit and debit sum must be zero', 'Error', {
            closeButton: true,
            tapToDismiss: false,
        });
        valid = false;
      }
      // If the form is valid, submit it
      if (valid) {
          $('#repeater-form')[0].submit();
      }
      function checkRequiredField(field, fieldName) {
        if (!field.is(':disabled') && (field.val() === null || field.val() === '')) {
            toastr['error'](fieldName + ' is required.', 'Error', {
                closeButton: true,
                tapToDismiss: false,
            });
            // alert(fieldName + ' is required.');
            valid = false;
            return false; // Break the loop
        }
        return true;
    }
    });


  });
