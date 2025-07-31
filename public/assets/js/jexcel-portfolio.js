$(document).ready(function() {
    var data = $('#spreadsheet').data('department');
    var portfolio = $('#spreadsheet').data('portfolios');
    var onbeforeinsertrow = function(instance) {
            return false;
    }
    var js = jspreadsheet(document.getElementById('spreadsheet'), {
        data: data,
        search: true,
        columns: [{
                type: 'text',
                title: 'System Departments',
                width: 200,
                readOnly:true,
            },
            {
                type: 'dropdown',
                title: 'Portfolio',
                width: 200,
                source: portfolio,
            },
            {
                type: 'hidden',
                title: 'id',
                width: 120
            },
            {
                type: 'hidden',
                title: 'portfolio_id',
                width: 120
            },

        ],
        onbeforeinsertrow: onbeforeinsertrow,
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
        var refresh = $('#route').data('refresh');
        $.each(data, function(i, value) {
            var errorCount = 0;
            var col = 0;
            for (var key in value) {
                if (value[key] == null || value[key] == "") {
                    validate = false;
                    if (key == 'description' || key == 'id') {
                        validate = true;
                    }
                    errorCount++;
                    if (errorCount > 0 && key != 'description' && key != 'id') {
                        toastr['error']('Column "' + key + '" in ' + parseInt(i + 1) + " Row cannot be empty", 'Error!', {
                            closeButton: true,
                            tapToDismiss: false,
                        });
                        invalidColumn(col, i)
                        return false;
                    }
                }

                validColumn(col, i)
                col++;
            }
        });

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
                    toastr['success']('Provision saved successfully', 'Mapping Saved!', {
                        closeButton: true,
                        tapToDismiss: false,
                    });
                    setTimeout(function() {
                        location.reload();

                    }, 1500);
                },
                error: function(response, error) {
                    console.log(response);
                    $('.validate-msg').addClass('alert-danger');
                    toastr['error'](response.responseJSON.message, 'Error!', {
                        closeButton: true,
                        tapToDismiss: false,
                    });
                    $('.validate-msg').show();
                    $('#save').prop('disabled', false);
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



  


