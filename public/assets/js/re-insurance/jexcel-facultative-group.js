$(document).ready(function() {
    var data = $('#spreadsheet').data('department');
    var cohorts = $('#cohorts').data('cohorts');
    var portfolios = $('#portfolios').data('portfolios');
    var measurement = $('#measurement').data('measurement');
    var grouping = $('#grouping').data('grouping');
    var onbeforeinsertrow = function(instance) {
            return false;
    }
    var js = jspreadsheet(document.getElementById('spreadsheet'), {
        data: data,
        search: true,
        tableHeight: "600px",
        columns: [{
                type: 'text',
                title: 'Product Code',
                width: 200,
                readOnly:true,
            },

            {
                type: 'text',
                title: 'Product Description',
                width: 200,
                readOnly:true,
            },
            {
                type: 'text',
                title: 'Business Type',
                width: 200,
                readOnly:true,
            },
            {
                type: 'dropdown',
                title: 'Measurement Model',
                width: 200,
                source: measurement,
            },
            {
                type: 'dropdown',
                title: 'Underwriting Cohort',
                width: 200,
                source: cohorts,
                selected: 1
            },
            {
                type: 'dropdown',
                title: 'Product Grouping',
                width: 200,
                autocomplete: true,
                source: grouping,
            },
            {
                type: 'numeric',
                title: 'Onerous Threshold',
                width: 200,
                defualt: 1.0,
                mask:'0.000', decimal:'.'
            },
            {
                type: 'text',
                title: 'System Deparment',
                width: 200,
                readOnly:true,
            },
             {
                type: 'dropdown',
                title: 'Portfolio Code',
                width: 200,
                source: portfolios,
            },
            {
                type: 'hidden',
                title: 'product_id',
            },
            {
                type: 'hidden',
                title: 'groupFacultative_id',
            },

        ],
        onbeforeinsertrow: onbeforeinsertrow,
        onload: onload
    });


    $('#save').click(function() {
        var data = js.getJson();
        var validate = true;
        var route = $('#route').data('route');
        var token = $('#route').data('token');
        console.log(data);
        $.each(data, function(i, value) {
            var errorCount = 0;
            keys = ['code', 'description', 'system_department_name', 'portfolio_code', 'business_type_name'];
            var col = 0 ;
            for (var key in value) {

                if (value[key] == null || value[key] == "") {
                    validate = false;
                    if(value['groupFacultative_id'] == "")
                    {
                        validate = true;
                    }
                    errorCount++;
                    if(errorCount > 0 && key !=  'groupFacultative_id' && !$.isNumeric(value['onerous_threshold']))
                    {
                        toastr['error']('Column "' + key + '" in ' + parseInt(i+1) + " Row cannot be empty" , 'Error!', {
                            closeButton: true,
                            tapToDismiss: false,
                        });
                        invalidColumn(col, i);
                        validate = false;
                        return false;
                    }
                }
                if (
                    (key == 'measurement_model_id' && value[key] == '') ||
                    (key == 'product_grouping' && value['product_grouping'] == '') ||
                    (key == 'cohorts_code_id' && value['cohorts_code_id'] == '') ||
                    (key == 'portfolio_id' && value['portfolio_id'] == '')
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
                validate = true;
                //Delete unnecessary columns
                if(jQuery.inArray(key, keys) !== -1){
                    delete value[key];
                }
                col++;
            }
        });
        data = JSON.stringify(data);
        if (validate) {
            $('#save').prop('disabled', true);
            $.ajax({
                url: route,
                type: "POST",
                data: {
                    _token: token,
                    data: data,
                },
                success: function (response) {
                    console.log(response);
                    toastr['success']('Mapping saved successfully', 'Mapping Saved!', {
                        closeButton: true,
                        tapToDismiss: false,
                    });
                    location.reload();
                },
                error: function(response, error) {
                    console.log(response.responseText['message']);
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
            toastr['error']( 'Please fill all the fields', 'Error!', {
                closeButton: true,
                tapToDismiss: false,
            });
            $('.validate-msg').show();
        }

    });

    function validColumn(col, i)
    {
        var cell = js.getCell([col, i]);
        js.setStyle([cell], 'background-color', '#ffffff')
    }

    function invalidColumn(col, i)
    {
        var cell = js.getCell([col, i]);
        js.setStyle([cell], 'background-color', '#dc3545')
    }
});
