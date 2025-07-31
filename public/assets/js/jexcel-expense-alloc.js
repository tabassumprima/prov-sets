$(document).ready(function() {
    var glData = $('#gl_code_desc').data('gl_code_desc');
    var expenseTypes = $('#expense_types').data('expense_types');
    var allocationBasis = $('#allocation_basis').data('allocation_basis');
    var route = $('#route').data('route'); // Save route URL
    var token = $('#route').data('token'); // CSRF token


    var js = jspreadsheet(document.getElementById('spreadsheet'), {
        data: glData,
        search: true,
        tableHeight: "600px",
        columns: [
            {
                type: 'hidden',
                title: 'ID',

            },
            {
                type: 'text',
                title: 'GL Code',
                width: 150,
                readOnly: true,
            },
            {
                type: 'text',
                title: 'Description',
                width: 250,
                readOnly: true,
            },
            {
                type: 'dropdown',
                title: 'Expense Type',
                width: 200,
                source: expenseTypes,
            },
            {
                type: 'dropdown',
                title: 'Allocation Basis',
                width: 200,
                source: allocationBasis,
            },
            {
                type: 'numeric',
                title: 'Allocation Rate (%)',
                width: 150,
            },
            {
                type: 'hidden',
                title: 'Expense Allocation ID',

            },
        ],
        onbeforeinsertrow: function(instance) {
            return false;
        },
        onload: onload
    });

    $('#save').click(function() {
        toastr['warning']("Processing Data.. Please wait", 'Warning!', {
            closeButton: false,
            tapToDismiss: false,
        });

        var data = js.getJson();
        var validate = true;
        $.each(data, function(i, value) {
            var errorCount = 0;
            keys = ['gl_code','description'];
            var col = 0;
            for (var key in value) {
                if (value[key] == null || value[key] == "") {
                    validate = false;
                    if (value['expense_allocation_id'] == "") {
                        validate = true;
                    }
                    errorCount++;
                    if (errorCount > 0 && key != 'expense_allocation_id' && (!$.isNumeric(value['allocation_rate']) )) {
                        toastr['error']('Column "' + key + '" in ' + parseInt(i + 1) + " Row cannot be empty", 'Error!', {
                            closeButton: true,
                            tapToDismiss: false,
                        });
                        invalidColumn(col, i)
                        validate = false;
                        return false;
                    }
                }
                if (key === 'allocation_rate') {
                    var allocationRate = parseFloat(value[key]);
                    if (isNaN(allocationRate) || allocationRate < 1 || allocationRate > 100) {
                        errorCount++;
                        validate = false;
                        toastr['error']('Allocation Rate (%) in Row ' + (i + 1) + ' must be between 1 and 100.', 'Error!', {
                            closeButton: true,
                            tapToDismiss: false,
                        });
                        invalidColumn(col, i);
                        return false;
                    }
                }
                if (
                    (key == 'expense_type' && value[key] == '') ||
                    (key == 'allocation_basis' && value[key] == '') 
                ) {
                    toastr["error"](
                        'Column "' + key + '" in ' + parseInt(i + 1) + " Row cannot be empty",
                        "Error!",
                        {
                            closeButton: true,
                            tapToDismiss: false,
                        }
                    );
                    invalidColumn(col, i);
                    validate = false;
                    return false;
                }

                validColumn(col, i)
                //Delete unnecessary columns
                if (jQuery.inArray(key, keys) !== -1) {
                    delete value[key];
                }
                col++;
            }
        });

        if (validate) {
            $('#save').prop('disabled', true);
            $.ajax({
                url: route,
                type: "POST",
                data: {
                    _token: token,
                    data: JSON.stringify(data),
                },
                success: function(response) {
                    toastr['success']('Expense Allocation saved successfully', 'Mapping Saved!', {
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
