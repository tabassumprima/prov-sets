/*=========================================================================================
    File Name: wizard-steps.js
    Description: wizard steps page specific js
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

$(function () {
    'use strict';

    var bsStepper = document.querySelectorAll('.bs-stepper'),
        select = $('.select2'),
        horizontalWizard = document.querySelector('.horizontal-wizard-example'),
        verticalWizard = document.querySelector('.vertical-wizard'),
        modernWizard = document.querySelector('.modern-wizard-example'),
        modernVerticalWizard = document.querySelector('.modern-vertical-wizard-example');

    // Adds crossed class
    if (typeof bsStepper !== undefined && bsStepper !== null) {
        for (var el = 0; el < bsStepper.length; ++el) {
            bsStepper[el].addEventListener('show.bs-stepper', function (event) {
                var index = event.detail.indexStep;
                var numberOfSteps = $(event.target).find('.step').length - 1;
                var line = $(event.target).find('.step');

                // The first for loop is for increasing the steps,
                // the second is for turning them off when going back
                // and the third with the if statement because the last line
                // can't seem to turn off when I press the first item. ¯\_(ツ)_/¯

                for (var i = 0; i < index; i++) {
                    line[i].classList.add('crossed');

                    for (var j = index; j < numberOfSteps; j++) {
                        line[j].classList.remove('crossed');
                    }
                }
                if (event.detail.to == 0) {
                    for (var k = index; k < numberOfSteps; k++) {
                        line[k].classList.remove('crossed');
                    }
                    line[0].classList.remove('crossed');
                }
            });
        }
    }

    // select2
    select.each(function () {
        var $this = $(this);
        $this.wrap('<div class="position-relative"></div>');
        $this.select2({
            placeholder: 'Select value',
            dropdownParent: $this.parent()
        });
    });

    // Horizontal Wizard
    // --------------------------------------------------------------------
    if (typeof horizontalWizard !== undefined && horizontalWizard !== null) {
        var numberedStepper = new Stepper(horizontalWizard),
            $form = $(horizontalWizard).find('form');
        $form.each(function () {
            var $this = $(this);
            $this.validate({
                rules: {
                    username: {
                        required: true
                    },
                    email: {
                        required: true
                    },
                    password: {
                        required: true
                    },
                    'confirm-password': {
                        required: true,
                        equalTo: '#password'
                    },
                    'first-name': {
                        required: true
                    },
                    'last-name': {
                        required: true
                    },
                    address: {
                        required: true
                    },
                    landmark: {
                        required: true
                    },
                    country: {
                        required: true
                    },
                    language: {
                        required: true
                    },
                    twitter: {
                        required: true,
                        url: true
                    },
                    facebook: {
                        required: true,
                        url: true
                    },
                    google: {
                        required: true,
                        url: true
                    },
                    linkedin: {
                        required: true,
                        url: true
                    }
                }
            });
        });

        $(horizontalWizard)
            .find('.btn-next')
            .each(function () {
                $(this).on('click', function (e) {
                    var isValid = $(this).parent().siblings('form').valid();
                    if (isValid) {
                        numberedStepper.next();
                    } else {
                        e.preventDefault();
                    }
                });
            });

        $(horizontalWizard)
            .find('.btn-prev')
            .on('click', function () {
                numberedStepper.previous();
            });

        $(horizontalWizard)
            .find('.btn-submit')
            .on('click', function () {
                var isValid = $(this).parent().siblings('form').valid();
                if (isValid) {
                    alert('Submitted..!!');
                }
            });
    }

    document.querySelectorAll('.mynumeral-mask').forEach(inp => new Cleave(inp, {
        numeral: true,
        numeralThousandsGroupStyle: 'thousand'
    }));

    // Vertical Wizard
    // --------------------------------------------------------------------
    if (typeof verticalWizard !== undefined && verticalWizard !== null) {
        var verticalStepper = new Stepper(verticalWizard, {
            linear: true
        });

        /**
         *
         * Employee Data
         *
         * */
        var file_processed_success = document.getElementById('file_processed_success');
        var original_file = document.getElementById('original_file');
        var json_file = document.getElementById('json_file');

        /**
         *
         * Previous Valuation
         *
         * */
        var previous_valuation_date = document.getElementById('previous_valuation_date');
        var opening_pbo = document.getElementById('opening_pbo');
        var interest_cost = document.getElementById('interest_cost');
        var service_cost = document.getElementById('service_cost');
        var is_pvaluation_updated = document.getElementById('is_pvaluation_updated');
        var reason_change_valuation = document.getElementById('reason_change_valuation');
        var currency_span = document.getElementById('currency_span');

        /**
         *
         * Assumptions
         *
         * */
        var agg_discount_rate = document.getElementById('agg_discount_rate');
        var lambda_eosb_id = document.getElementById('lambda_eosb_id');
        var termination_rate = document.getElementById('termination_rate');
        var retirement_age = document.getElementById('retirement_age');
        var mortality_rate_id = document.getElementById('mortality_rate_id');
        var withdrawal_rate_id = document.getElementById('withdrawal_rate_id');
        var long_salary_rate = document.getElementById('long_salary_rate');
        var short_salary_1 = document.getElementById('short_salary_1');
        var short_salary_2 = document.getElementById('short_salary_2');
        var short_salary_3 = document.getElementById('short_salary_3');
        var short_salary_4 = document.getElementById('short_salary_4');
        var short_salary_5 = document.getElementById('short_salary_5');
        var discount_error_div = document.getElementById('discount_error_div');
        var discount_error_message = document.getElementById('discount_error_message');
        var full_discount_rate_rec_div = document.getElementById('full_discount_rate_rec_div');


        /**
         *
         * Events
         *
         * */

        var payments = document.getElementById('payments');
        var payments_partial = document.getElementById('payments_partial');
        var payment_transferees = document.getElementById('payment_transferees');

        /**
         *
         * utils
         *
         * */
        var token = document.getElementById('token');
        var id = document.getElementById('valuation_identifier');
        var is_lambda_processing = document.getElementById('is_lambda_processing');
        var rec_discount_rate_txt_value = document.getElementById('rec_discount_rate_txt_value');
        var recommended_discount_rate = document.getElementById('recommended_discount_rate_hid');
        var agg_discount_rate = document.getElementById('agg_discount_rate');
        var recommended_salary_increase_long = document.getElementById('recommended_salary_increase_long_hid');
        var loading_modal_title = document.getElementById('loading_modal_title');
        var loading_modal_text = document.getElementById('loading_modal_text');

        var stepx = document.getElementById('current_step').value;

        if (stepx != null || stepx != "") {
            verticalStepper.to(stepx);
            updateSummary();
        }

        //check if calculation process is still running
        if (is_lambda_processing.value == true) {

            if (stepx == 1) {
                loading_modal_title.innerText = "Employee(s) Data";
                loading_modal_text.innerText = "Validating the employee(s)’ data for any errors. Please wait.";
            } else if (stepx == 2 || stepx == 3) {
                loading_modal_title.innerText = "Discount Rate";
                loading_modal_text.innerText = "Calculating Discount Rate, Please wait.";
            } else if (stepx == 4 || stepx == 5) {
                loading_modal_title.innerText = "Running Valuation";
                loading_modal_text.innerText = "Running full valuation, this may take a while, Please wait..";
            }
            $('#discount_rate_modal').modal(
                {
                    show: true,
                    backdrop: 'static',
                    keyboard: false,
                    dismissible: false,
                });

            getProcessingStatus(id.value);
        }

        //check if there is any short term salary
        var short_salary_div = document.getElementById("short_salary");

        if (short_salary_1.value != "" || short_salary_2.value != "" || short_salary_3.value != "" || short_salary_4.value != "" || short_salary_5.value != "") {
            short_salary_div.style.display = '';
        }

        $('#long_salary_rate, #termination_rate, #retirement_age, #mortality_rate_id, #withdrawal_rate_id, #short_salary_1, #short_salary_2,#short_salary_3,#short_salary_4,#short_salary_5').on('change paste keyup', toggleCurrentAssumptions);

        function toggleCurrentAssumptions() {
            agg_discount_rate.value = "";
            full_discount_rate_rec_div.style.display = 'none';
        }

        $(verticalWizard)
            .find('.get_recommended_rate_link')
            .on('click', function () {

                loading_modal_title.innerText = "Discount Rate";
                loading_modal_text.innerText = "Calculating Discount Rate, please wait..";


                $('#form_assumption').validate({
                    rules: {
                        lambda_eosb_id: {
                            required: true
                        },
                        termination_rate: {
                            required: true
                        },
                        mortality_rate_id: {
                            required: true
                        },
                        withdrawal_rate_id: {
                            required: true
                        },
                        long_salary_rate: {
                            required: true
                        },
                        retirement_age: {
                            required: true
                        }
                    }
                });

                var $isValid_discount_btn = $('#form_assumption').valid();

                if ($isValid_discount_btn) {
                    $('#discount_rate_modal').modal(
                        {
                            show: true,
                            backdrop: 'static',
                            keyboard: false,
                            dismissible: false,
                        });

                    updateWizardData(verticalStepper._currentIndex + 1);
                    transform();

                }
            });

        $(verticalWizard)
            .find('.btn-report')
            .on('click', function () {
                console.log("report");

                $('#discount_rate_modal').modal(
                    {
                        show: true,
                        backdrop: 'static',
                        keyboard: false,
                        dismissible:false,
                    });

                loading_modal_title.innerText = "Running Valuation";
                loading_modal_text.innerText = "Running full valuation, please wait..";
                transform();

            });

        function transform() {

            $.ajax({
                url: "/valuation/transform/" + id.value,
                type: "POST",
                data: {
                    id: id.value,
                    current_step: stepx.value,
                    _token: token.value,

                },
                timeout: 18000,
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    if (data.is_processing === true) {

                        $('#discount_rate_modal').modal(
                            {
                                show: true,
                                backdrop: 'static',
                                keyboard: false,
                                dismissible: false,
                            });

                        //get processing information
                        getProcessingStatus(id.value);
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log(errorThrown);
                    $('#discount_rate_modal').modal({show: false});
                    return false;
                }
            });
        }

        $(verticalWizard)
            .find('.btn-next')
            .on('click', function () {

                /**
                 *
                 * File Input
                 *
                 * */

                if (verticalStepper._currentIndex == 0) {
                    if ((file_processed_success.value == 'true' || file_processed_success.value == true) && original_file.value != '') {
                        updateWizardData(verticalStepper._currentIndex + 1);
                        verticalStepper.next();
                    }
                }

                /**
                 *
                 * Previous Valuation
                 *
                 * */

                else if (verticalStepper._currentIndex == 1) {
                    var $form = $('#valuation_form').validate({
                        rules: {
                            valuation_date: {
                                required: true
                            },
                            opening_pbo: {
                                required: true
                            },
                            interest_cost: {
                                required: true
                            },
                            service_cost: {
                                required: true
                            }
                        }
                    });

                    var $isValid = $('#valuation_form').valid();

                    //check if previous valuation has to updated
                    if (is_pvaluation_updated.checked) {
                        var $form1 = $('#valuation_form_reason').validate({
                            rules: {
                                reason_change_valuation: {
                                    required: true
                                }
                            }
                        });
                        var $isValid_reason = $('#valuation_form_reason').valid();
                        if ($isValid && $isValid_reason) {
                            updateWizardData(verticalStepper._currentIndex + 1);
                            verticalStepper.next();
                        }

                    } else if ($isValid) {
                        updateWizardData(verticalStepper._currentIndex + 1);
                        verticalStepper.next();
                    }

                }
                /**
                 *
                 * Assumptions
                 *
                 * */
                else if (verticalStepper._currentIndex == 2) {

                    $('#form_assumption').validate({
                        rules: {
                            lambda_eosb_id: {
                                required: true
                            },
                            termination_rate: {
                                required: true
                            },
                            mortality_rate_id: {
                                required: true
                            },
                            withdrawal_rate_id: {
                                required: true
                            },
                            long_salary_rate: {
                                required: true
                            },
                            retirement_age: {
                                required: true
                            }
                        }
                    });

                    var $isValid_assumption = $('#form_assumption').valid();
                    // var $isValid_discount = $('#form_discount_rate').valid();

                    if ($isValid_assumption && agg_discount_rate.value != "") {
                        updateWizardData(verticalStepper._currentIndex + 1);
                        verticalStepper.next();
                    } else if ($isValid_assumption && agg_discount_rate.value == "") {
                        $('#discount_rate_not_valid').modal(
                            {
                                show: true,
                            });
                    }


                }

                /**
                 *
                 * Events
                 *
                 * */
                else if (verticalStepper._currentIndex == 3) {

                    var $form = $('#form_events').validate({
                        rules: {
                            payments: {
                                required: true
                            },
                            payments_partial: {
                                required: true
                            },
                            payment_transferees: {
                                required: true
                            }
                        }
                    });

                    var $isValid = $('#form_events').valid();

                    if ($isValid) {
                        updateWizardData(verticalStepper._currentIndex + 1);
                        verticalStepper.next();
                    }
                }

                updateSummary();

            });


        $(verticalWizard)
            .find('.btn-prev')
            .on('click', function () {
                updateWizardData(verticalStepper._currentIndex);
                verticalStepper.previous();

            });

        $(verticalWizard)
            .find('.btn-submit')
            .on('click', function () {
                alert('Submitted..!!');
            });

        function getProcessingStatus(id) {
            console.log("im here");

            $.ajax({
                url: "/valuation/getstatus/" + id,
                type: "GET",
                data: {
                    _token: token.value,
                },
                timeout: 6000,
                dataType: "json",
                success: function (data) {
                    console.log(data);

                    // Schedule the next request when the current one's complete
                    if (data.success === false && data.is_processing === true) {
                        setTimeout(function () {
                            getProcessingStatus(id);
                        }, 2000);
                    } else {
                        setTimeout(function () {
                            $('#discount_rate_modal').modal('hide');
                        }, 1000);
                    }

                    if (data.success === true && data.is_processing === false) {
                        var response = JSON.parse(data.data);
                        console.log(response);
                        if (verticalStepper._currentIndex >= 0 ||  verticalStepper._currentIndex <= 3) {
                            recommended_discount_rate.value = response.recommended_discount_rate;
                            rec_discount_rate_txt_value.innerText = response.recommended_discount_rate;
                            agg_discount_rate.value = response.recommended_discount_rate;
                            if(verticalStepper._currentIndex == 0 ||  verticalStepper._currentIndex == 1){
                                long_salary_rate.value = response.recommended_salary_increase_long ;
                                discount_error_div.style.display = 'none';
                            }

                        }
                        if (response.command == 'report') {
                            window.location.replace("/report/show/" + response.id);
                        }
                    } else if (data.success === false && data.is_processing === false) {
                        discount_error_message.innerText = "Internal Server Error: Please Try Again";
                        discount_error_div.style.display = 'block';
                    }

                }
            });
        }

        function updateWizardData(step_number) {

            $.ajax({
                url: "/valuation/storewizard/" + id.value,
                type: "POST",
                data: {
                    id: id.value,
                    original_file: original_file.value,
                    json_file: json_file.value,
                    file_processed_success: file_processed_success.value,
                    current_step: step_number,
                    previous_valuation_date: previous_valuation_date.value,
                    opening_pbo: opening_pbo.value,
                    interest_cost: interest_cost.value,
                    service_cost: service_cost.value,
                    is_pvaluation_updated: ((is_pvaluation_updated.checked) ? 1 : 0),
                    reason_change_valuation: reason_change_valuation.value,
                    agg_discount_rate: agg_discount_rate.value,
                    lambda_eosb_id: lambda_eosb_id.value,
                    termination_rate: termination_rate.value,
                    retirement_age: retirement_age.value,
                    mortality_rate_id: mortality_rate_id.value,
                    withdrawal_rate_id: withdrawal_rate_id.value,
                    long_salary_rate: long_salary_rate.value,
                    short_salary_1: short_salary_1.value,
                    short_salary_2: short_salary_2.value,
                    short_salary_3: short_salary_3.value,
                    short_salary_4: short_salary_4.value,
                    short_salary_5: short_salary_5.value,
                    payments: payments.value,
                    payments_partial: payments_partial.value,
                    payment_transferees: payment_transferees.value,
                    recommended_discount_rate: recommended_discount_rate.value,
                    recommended_salary_increase_long: recommended_salary_increase_long.value,
                    _token: token.value,

                },
                async: true,
                timeout: 6000,
                dataType: "json",
                success: function (data) {
                    console.log(data);

                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log(errorThrown);
                    return false;
                }
            });
        }
    }

    function updateSummary(){

            document.getElementById('final_valuation_date').innerText = previous_valuation_date.value;
            document.getElementById('final_opening_pbo').innerText = opening_pbo.value + " " +currency_span.innerText;
            document.getElementById('final_interest_cost').innerText = interest_cost.value + " " +currency_span.innerText;
            document.getElementById('final_service_cost').innerText = service_cost.value + " " +currency_span.innerText;

            if(is_pvaluation_updated.checked){
                document.getElementById('div_final_reason_change').style.display = "";
                document.getElementById('final_reason_change').innerText = reason_change_valuation.value;
            }else{
                document.getElementById('div_final_reason_change').style.display = "none";
            }

            document.getElementById('final_agg_discount_rate').innerText = agg_discount_rate.value + "%";
            document.getElementById('final_termination_rate').innerText = termination_rate.value + "%";
            document.getElementById('final_retirement_age').innerText = retirement_age.value + " years";
            document.getElementById('final_mortality_rate').innerText = mortality_rate_id.options[mortality_rate_id.selectedIndex].text;
            document.getElementById('final_withdrawal_rate').innerText = withdrawal_rate_id.options[withdrawal_rate_id.selectedIndex].text;;
            document.getElementById('final_long_salary_rate').innerText = long_salary_rate.value + "%";
            document.getElementById('final_payments').innerText = payments.value + " " +currency_span.innerText;
            document.getElementById('final_payments_partial').innerText = payments_partial.value + " " +currency_span.innerText;
            document.getElementById('final_payment_transferees').innerText = payment_transferees.value + " " +currency_span.innerText;

    }

    // Modern Wizard
    // --------------------------------------------------------------------
    if (typeof modernWizard !== undefined && modernWizard !== null) {
        var modernStepper = new Stepper(modernWizard, {
            linear: false
        });
        $(modernWizard)
            .find('.btn-next')
            .on('click', function () {
                modernStepper.next();
            });
        $(modernWizard)
            .find('.btn-prev')
            .on('click', function () {
                modernStepper.previous();
            });

        $(modernWizard)
            .find('.btn-submit')
            .on('click', function () {
                alert('Salman..!!');
            });
    }

    // Modern Vertical Wizard
    // --------------------------------------------------------------------
    if (typeof modernVerticalWizard !== undefined && modernVerticalWizard !== null) {
        var modernVerticalStepper = new Stepper(modernVerticalWizard, {
            linear: false
        });
        $(modernVerticalWizard)
            .find('.btn-next')
            .on('click', function () {
                modernVerticalStepper.next();
            });
        $(modernVerticalWizard)
            .find('.btn-prev')
            .on('click', function () {
                modernVerticalStepper.previous();
            });

        $(modernVerticalWizard)
            .find('.btn-submit')
            .on('click', function () {
                alert('Submitted..!!');
            });
    }
});
