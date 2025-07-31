
    $('#filters').on('click', function() {
        fetch_data();
    });
    $('#reset').on('click', function() {
        filterReset();
    });

    // Set all cards types
    const chartTypes = ['loss-ratio-chart', 'segment-breakup-chart', 'business-snapshot-chart', 'card-stats', 'geojson'];

    // Fetch
    function fetch_data(title = null, body = null) {
        form_data = $('#dashboard').serializeArray()
        if (title != null){
            $('#loading_modal_title').html(title)
            $('#loading_modal_text').html(body)
        }
        modal = $('#fetch_records').modal('show')
        chartTypes.forEach(chartType => {
            toggleShimmer(true, chartType);
        });
        route = $('#route').data('route');
        $.ajax({
            type: 'get',
            async: true,
            url: route,
            data: form_data,
            success: function(data) {
                displayData(data);
            },
            error: function(err) {
                $('.error-wrapper').show();
                $('.error').html(err.responseJSON['message']);
                setTimeout(function() {
                    $('#fetch_records').modal('hide');
                    chartTypes.forEach(chartType => {
                        toggleShimmer(false, chartType);
                    });
                }, 1000);
            }
        });
        modal = $('#fetch_records').modal('hide');

    }
    // Set filters
    function displayFilter(filters)
    {
        $('#accounting-year').val(filters['accounting_year_id']).change()
        $('#branch').val(filters['branch_id']).change()
        $('#business-type').val(filters['business_type_id']).change()

        // Set portfolio filter
        let portfolioSelect = $('#portfolio');
        let portfolioData = filters['portfolio_id'];
        portfolioSelect.val(null).trigger('change');

        // Check if portfolioData is an array (multi-select) or a single value
        if (Array.isArray(portfolioData)) {
            // Set multiple selected values for multi-select
            portfolioSelect.val(portfolioData).trigger('change');
            displaySelectedOptionsMessage(portfolioSelect, portfolioData.map(value => portfolioSelect.find(`[value="${value}"]`).text()));
        } else if (portfolioData !== null) {
            // Set single selected value for regular select
            portfolioSelect.val(portfolioData).trigger('change');
            displaySelectedOptionsMessage(portfolioSelect, [portfolioSelect.find(`[value="${portfolioData}"]`).text()]);
        } else {
            // No selection
            displaySelectedOptionsMessage(portfolioSelect, []);
        }

    }
    // Reset Filters
    function filterReset() {
        $('#accounting-year').val($('#accounting-year option:eq(0)').val()).change();
        $('#portfolio').val('').change()
        $('#branch').val('All').change()
        $('#business-type').val($('#business-type option:eq(0)').val()).change()

        fetch_data();
    }

    // Set Filters
    function setFilter() {
        $('#accounting-year').val($('#accounting-year option:eq(0)').val()).change();
        $('#portfolio').val('').change()
        $('#branch').val('All').change()
        $('#business-type').val($('#business-type option:eq(0)').val()).change()
    }

    // Display selected options message
    function displaySelectedOptionsMessage(selectElement, selectedValues)
    {
        var optionsCount = selectedValues.length;

        // Display only the first two selected options
        var displayedOptions = selectedValues.slice(0, 1).join(', ');

        selectElement.next('span.select2').find('ul').html(function () {
            if (optionsCount > 0) {
                var message = "<li>" + displayedOptions;

                if (optionsCount > 1) {
                    var remainingCount = optionsCount - 1;
                    message += " +" + remainingCount + " more option" + (remainingCount > 1 ? "s" : "");
                }
                return message;
            } else {
                return "<li>All</li>";
            }
        });
    }

    // Set Accounting Years
    function setAccountingYear()
    {
        var date = $("#accounting-year option:selected").text();
        var last = Number(date.slice(-4));
        $('.selected-date').html(last);
    }

    // Display Data
    function displayData(data,title = null, body = null)
    {
        if (title != null){
            $('#loading_modal_title').html(title)
            $('#loading_modal_text').html(body)
        }
        modal = $('#fetch_records').modal('show')
        setAccountingYear();
        setTimeout(function() {
            $('#jsonData').attr('data-json',data);
            reRender(data);
            renderBusinessSnapshot();
            modal = $('#fetch_records').modal('hide');
            chartTypes.forEach(chartType => {
                toggleShimmer(false, chartType);
            });
        }, 700);
    }

    function toggleShimmer(show, chartType) {
        const shimmerWrappers = document.querySelectorAll('.shimmer-wrapper');
    
        shimmerWrappers.forEach(wrapper => {
            const chartWrapper = wrapper.closest('.chart-wrapper');
    
            if (chartWrapper && chartWrapper.classList.contains(chartType)) {
                if (show) {
                    wrapper.style.display = 'block';
                } else {
                    wrapper.style.display = 'none';
                }
            }
        });
    }
