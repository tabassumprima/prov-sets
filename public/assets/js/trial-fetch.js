    
    var table = $('#trial-table').DataTable({
        "autoWidth": false,  // Prevent automatic column width adjustment
        "columnDefs": [
            { 
                "targets": [2, 3, 4, 5],  // Columns with numeric data
                "createdCell": function(td) {
                    $(td).css({ 
                        'text-align': 'right',   // Right alignment for table body
                    });
                }
            }
        ],
        "headerCallback": function(thead) {
            // Apply styling to header if needed
            $(thead).find('th').each(function(index) {
                if ([2, 3, 4, 5].includes(index)) {
                    $(this).css({
                        'text-align': 'right', // Center-align header text
                        'padding-right': '20px'      // Add padding to headers
                    });
                }
            });
        }
    });
     
    start_date =$('#start').data('start')
    end_date =$('#end').data('end')
    var flatpickrInstance = flatpickr(".accounting_year", {
        mode: "range",
        maxDate: end_date,
        minDate: start_date
    });
    

    $('#filter').on('click', function() {
        if (!validateFilters()) {
            return;
        }
        fetch();
    })

    function fetch() {
        form_data = $('#trial').serializeArray()
        modal = $('#fetch_records').modal('show')
        route = $('#route').data('route');
        table.clear()
        $.ajax({
            type: 'get',
            url: route,
            data: form_data,
            success: function(data) {
                displayData(data)
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
        function formatNumber(value) {
            // Convert value to number in case it's a string
            value = parseFloat(value);

            // Check if the number is negative
            if (value < 0) {
                return `(${Math.abs(value).toLocaleString()})`; // Format negative numbers in brackets
            }
            return value.toLocaleString(); // Format positive numbers
        }

        $.each(data, function(i, k) {
          table.row.add([k.code, k.description, formatNumber(k.opening), formatNumber(k.debit), formatNumber(k.credit), formatNumber(k.closing)]).draw()
        
        })
    }

    // Function to validate filters
    function validateFilters() {
        var fields = [
            { field: $('#accounting-year'), name: 'Accounting Year' },
            { field: $('#portfolio'), name: 'Portfolio' },
            { field: $('#branch'), name: 'Branch' },
            { field: $('#business-type'), name: 'Business Type' },
            { field: $('#date-range'), name: 'Date Range' },
            { field: $('#glcode'), name: 'GL Code' }
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



