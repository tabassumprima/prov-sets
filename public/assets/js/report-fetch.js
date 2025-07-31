    start_date =$('#start').data('start')
    end_date =$('#end').data('end')
    var flatpickrInstance = flatpickr(".accounting_year", {
        mode: "range",
        maxDate: end_date,
        minDate: start_date
    });
    console.log(end_date)

    $('#filter').on('click', function() {
        if (!validateFilters()) {
            return;
        }
        fetch();
    })

    function fetch() {
        form_data = $('#report').serializeArray()
        modal = $('#fetch_records').modal('show')
        route = $('#route').data('route');
        $.ajax({
            type: 'get',
            url: route,
            data: form_data,
            success: function(data) {
                displayData(data)
                $('.error-wrapper').hide();
                modal = $('#fetch_records').modal('hide')

            },
            error: function(err) {
                $('.error-wrapper').show();
                $('.error').html(err.responseJSON['message'])
                setTimeout(function() {
                    $('#fetch_records').modal('hide')
                }, 2000)
            }
        })
    }

    function displayData(data)
    {
        report_body = $('#report-body')
        compare_year = $('#compare-year')
        current_year = $('#current-year')
        report_body.html('')
        $.each(data, function(i, k) {
            if (k.type == 'break') {
                report_body.append("<tr><td> </td><td> </td><td> </td></tr>")
            }
            else if (k.type == 'heading') {
                report_body.append("<tr class='" + k.class + "'><td>" + k.description + "</td><td></td><td></td></tr>")
            } else {
                keys = Object.keys(k.values)
                compare_year.html(keys[0]).css('text-align', 'right');
                
                current_year.html(keys[1]).css('text-align', 'right');
                  
                function formatValue(value) {
                    if (value < 0) {
                        return `(${Math.abs(value).toLocaleString('en-us')})`;
                    }
                    return value.toLocaleString('en-us');
                }
                
                item_value_1 = parseInt(k.values[keys[0]])
                
                if (k.values[keys[1]]) {
                    item_value_2 = parseInt(k.values[keys[1]])
                } else {
                    item_value_2 = parseInt(k.values[keys[0]])
                }
                
                item_value_1 = formatValue(item_value_1);
                item_value_2 = formatValue(item_value_2);
                
                if(item_value_1 == 0 && item_value_2 == 0) 
                {
                    return
                }
            report_body.append("<tr class='" + k.class + "'><td>" + k.description + "</td><td style='text-align: right;'>" + item_value_2 + "</td><td style='text-align: right;'>" + item_value_1 + "</td></tr>")
            }
        })
    } 
   
    // Function to validate filters
    function validateFilters() {
        var fields = [
            { field: $('#period'), name: 'Period' },
            { field: $('#portfolio'), name: 'Portfolio' },
            { field: $('#business-type'), name: 'Business Type' },
            { field: $('#date_range'), name: 'Date Range' },
        ];

        var valid = true;

        for (var i = 0; i < fields.length; i++) {
            var field = fields[i].field;
            var fieldName = fields[i].name;

            if (!verifyRequiredField(field, fieldName)) {
                valid = false;
                break; // Break the loop if any field is invalid
            }
        }

        return valid;
    }



