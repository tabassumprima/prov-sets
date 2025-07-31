$(document).ready(function() {
    var data = $('#spreadsheet').data('department');
    var ibnr = $('#ibnr').data('ibnr');
    var salvage = $('#ibnr').data('ibnr');
    var portfolio = $('#portfolio').data('portfolio');
    var discount_rates = $('#discount_rates').data('discount_rates');
    var risk_adjustments = $('#risk_adjustments').data('risk_adjustments');
    var claim_patterns = $('#claim_patterns').data('claim_patterns');
    var route = $('#route').data('route');
    var token = $('#route').data('token');
    var onbeforeinsertrow = function(instance) {
        return false;
    }
    console.log(data);
    var js = jspreadsheet(document.getElementById('spreadsheet'), {
        data: data,
        search: true,
        tableHeight: "600px",
        columns: [
            {
                type: 'text',
                title: 'Product Code',
                width: 200,
                readOnly: true,
            },
            {
                type: 'text',
                title: 'Description',
                width: 200,
                readOnly: true,
            },


            {
                type: 'text',
                title: 'System Department',
                width: 200,
                readOnly: true,
            },
            {
                type: 'dropdown',
                title: 'Discount Rates',
                width: 150,
                source: discount_rates,
            },
            {
                type: 'dropdown',
                title: 'IBNR',
                width: 150,
                source: ibnr,
                selected: 1
            },
            {
                type: 'dropdown',
                title: 'Salvage',
                width: 150,
                source: salvage,
                selected: 1,
                default:1
            },
            {
                type: 'numeric',
                title: 'ULAE',
                width: 150,
                mask: '0.00',
            },
            {
                type: 'numeric',
                title: 'ENID',
                width: 150,
                mask: '0.00',
            },

            {
                type: 'dropdown',
                title: 'Risk Adjustments',
                width: 150,
                autocomplete: true,
                source: risk_adjustments,
            },
            {
                type: 'dropdown',
                title: 'Claim Patterns',
                width: 150,
                autocomplete: true,
                source: claim_patterns,
            },
            {
                type: 'numeric',
                title: 'Expense Allocation',
                width: 100,
            },
            {
                type: 'dropdown',
                title: 'Earning Pattern',
                width: 100,
                source: ["Uniform Exposure", "Increasing Exposure"]
            },
            {
                type: 'numeric',
                title: 'ULR',
                width: 100,
            },
            {
                type: 'hidden',
                title: 'system_department_id',
            },
            {
                type: 'hidden',
                title: 'provisionMapping_id',
            },
            {
                type: 'hidden',
                title: 'product_code_id',
            },

        ],
        onbeforeinsertrow: onbeforeinsertrow,
        onload: onload
    });


    $('#save').click(function() {
        toastr['warning']("Processing Data.. Please wait", 'Warning!', {
            closeButton: false,
            tapToDismiss: false,
        });
        var data = js.getJson();
        var validate = true;
        var route = $('#route').data('route');
        var token = $('#route').data('token');
        $.each(data, function(i, value) {
            // console.log(value);
            var errorCount = 0;
            keys = ['description', 'system_department_name', 'portfolio_code', 'organization_name'];
            var col = 0;
            for (var key in value) {
                if (value[key] == null || value[key] == "") {
                    validate = false;
                    if (key == 'provisionMapping_id') {
                        validate = true;
                    }
                    errorCount++;
                    if (errorCount > 0 && key != 'provisionMapping_id') {
                        toastr['error']('Column "' + key + '" in ' + parseInt(i + 1) + " Row cannot be empty", 'Error!', {
                            closeButton: true,
                            tapToDismiss: false,
                        });
                        invalidColumn(col, i)
                        return false;
                    }
                }
                if (key === 'ulr' || key === 'expense_allocation') {
                    if (!$.isNumeric(value[key])) {
                        toastr['error']('Column "' + key + '" in ' + parseInt(i + 1) + " must be a numeric value.", 'Error!', {
                            closeButton: true,
                            tapToDismiss: false,
                        });
                        invalidColumn(col, i);
                        validate = false;
                        return false;
                    }
                }
                validColumn(col, i)
                //Delete unnecessary columns
                if (jQuery.inArray(key, keys) !== -1) {
                delete value[key];
                }
                col++;
            }
        });
        data = JSON.stringify(data);
        console.log(data);
        if (validate) {
            $('#save').prop('disabled', true);
            $.ajax({
                url: route,
                type: "POST",
                data: {
                    _token: token,
                    data: data,
                },
                success: function(response) {
                    console.log(response);
                    toastr['success']('Provision saved successfully', 'Mapping Saved!', {
                        closeButton: true,
                        tapToDismiss: false,
                    });
                    setTimeout(function() {
                        location.reload();

                    }, 1500);
                },
                error: function(response, error) {
                    $('.validate-msg').addClass('alert-danger');
                    toastr['error'](response.responseJSON.message, 'Error!',{
                        closeButton: true,
                        tapToDismiss: false,
                    })
                    $('.validate-msg').show();
                }
            });
        } else {
            $('.validate-msg').addClass('alert-danger');
            toastr['error']('Please fill all the fields', 'Error!', {
                closeButton: true,
                tapToDismiss: false,
            });
            $('.validate-msg').show();
        }

    });

    function validColumn(col, i) {
        var cell = js.getCell([col, i]);
        js.setStyle([cell], 'background-color', '#ffffff')
    }

    function invalidColumn(col, i) {
        var cell = js.getCell([col, i]);
        js.setStyle([cell], 'background-color', '#dc3545')
    }
});
