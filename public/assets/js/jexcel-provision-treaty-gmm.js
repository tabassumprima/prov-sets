$(document).ready(function() {
    // Hardcoded data with 5 rows
    var data = [
        {
            'GMM Product Code': 'E01001',
            'Description': 'Erection All Risk Insurance (EAR)',
            'System Department': 'miscellaneous',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Conventional',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },
        {
            'GMM Product Code': 'E01002',
   'Description': 'Contractors All Risk Insurance (CAR)',
            'System Department': 'Engineering',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Conventional',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },
         {
            'GMM Product Code': 'X01002',
            'Description': 'Performance Bond',
            'System Department': 'miscellaneous',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Conventional',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },
        {
            'GMM Product Code': 'X01003',
            'Description': 'Mobilization Advance Bond',
            'System Department': 'miscellaneous',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Conventional',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },
        {
            'GMM Product Code': 'X01005',
            'Description': 'Custom Bond',
            'System Department': 'miscellaneous',
           'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Conventional',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },
        {
            'GMM Product Code': 'X01005',
            'Description': 'Custom Bond',
            'System Department': 'miscellaneous',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Conventional',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },
        {
            'GMM Product Code': 'X01006',
            'Description': 'Retention Money Bond',
            'System Department': 'miscellaneous',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Conventional',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },
          {
            'GMM Product Code': 'E01501',
            'Description': 'Erection All Risk Takaful (EAR)',
            'System Department': 'Engineering',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Takaful',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },  {
            'GMM Product Code': 'E01502',
            'Description': 'Contractors All Risk Takaful (CAR)',
            'System Department': 'Engineering',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Takaful',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
            
        },
         {
            'GMM Product Code': 'X01005',
            'Description': 'Custom Bond',
            'System Department': 'miscellaneous',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Conventional',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },
        {
            'GMM Product Code': 'X01006',
            'Description': 'Retention Money Bond',
            'System Department': 'miscellaneous',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Conventional',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },
          {
            'GMM Product Code': 'E01501',
            'Description': 'Erection All Risk Takaful (EAR)',
            'System Department': 'Engineering',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Takaful',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },  {
            'GMM Product Code': 'E01502',
            'Description': 'Contractors All Risk Takaful (CAR)',
            'System Department': 'Engineering',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Takaful',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
            
        },
        {
            'GMM Product Code': 'X01005',
            'Description': 'Custom Bond',
            'System Department': 'miscellaneous',
           'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Conventional',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },
        {
            'GMM Product Code': 'X01005',
            'Description': 'Custom Bond',
            'System Department': 'miscellaneous',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Conventional',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },
        {
            'GMM Product Code': 'X01006',
            'Description': 'Retention Money Bond',
            'System Department': 'miscellaneous',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Conventional',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },
        {
            'GMM Product Code': 'E01502',
            'Description': 'Contractors All Risk Takaful (CAR)',
            'System Department': 'Engineering',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Takaful',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
            
        },
         {
            'GMM Product Code': 'X01005',
            'Description': 'Custom Bond',
            'System Department': 'miscellaneous',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Conventional',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },
        {
            'GMM Product Code': 'X01006',
            'Description': 'Retention Money Bond',
            'System Department': 'miscellaneous',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Conventional',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },
             {
            'GMM Product Code': 'E01001',
            'Description': 'Erection All Risk Insurance (EAR)',
            'System Department': 'miscellaneous',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Conventional',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },
        {
            'GMM Product Code': 'E01002',
   'Description': 'Contractors All Risk Insurance (CAR)',
            'System Department': 'Engineering',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Conventional',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },
         {
            'GMM Product Code': 'X01002',
            'Description': 'Performance Bond',
            'System Department': 'miscellaneous',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Conventional',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },
        {
            'GMM Product Code': 'X01003',
            'Description': 'Mobilization Advance Bond',
            'System Department': 'miscellaneous',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Conventional',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },
        {
            'GMM Product Code': 'X01005',
            'Description': 'Custom Bond',
            'System Department': 'miscellaneous',
           'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Conventional',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },
        {
            'GMM Product Code': 'X01005',
            'Description': 'Custom Bond',
            'System Department': 'miscellaneous',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Conventional',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },
        {
            'GMM Product Code': 'X01006',
            'Description': 'Retention Money Bond',
            'System Department': 'miscellaneous',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Conventional',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },
          {
            'GMM Product Code': 'E01501',
            'Description': 'Erection All Risk Takaful (EAR)',
            'System Department': 'Engineering',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Takaful',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },  {
            'GMM Product Code': 'E01502',
            'Description': 'Contractors All Risk Takaful (CAR)',
            'System Department': 'Engineering',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Takaful',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
            
        },
         {
            'GMM Product Code': 'X01005',
            'Description': 'Custom Bond',
            'System Department': 'miscellaneous',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Conventional',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },
        {
            'GMM Product Code': 'X01006',
            'Description': 'Retention Money Bond',
            'System Department': 'miscellaneous',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Conventional',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },
          {
            'GMM Product Code': 'E01501',
            'Description': 'Erection All Risk Takaful (EAR)',
            'System Department': 'Engineering',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Takaful',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },  {
            'GMM Product Code': 'E01502',
            'Description': 'Contractors All Risk Takaful (CAR)',
            'System Department': 'Engineering',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Takaful',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
            
        },
        {
            'GMM Product Code': 'X01005',
            'Description': 'Custom Bond',
            'System Department': 'miscellaneous',
           'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Conventional',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },
        {
            'GMM Product Code': 'X01005',
            'Description': 'Custom Bond',
            'System Department': 'miscellaneous',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Conventional',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },
        {
            'GMM Product Code': 'X01006',
            'Description': 'Retention Money Bond',
            'System Department': 'miscellaneous',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Conventional',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },
        {
            'GMM Product Code': 'E01502',
            'Description': 'Contractors All Risk Takaful (CAR)',
            'System Department': 'Engineering',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Takaful',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
            
        },
         {
            'GMM Product Code': 'X01005',
            'Description': 'Custom Bond',
            'System Department': 'miscellaneous',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Conventional',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },
        {
            'GMM Product Code': 'X01006',
            'Description': 'Retention Money Bond',
            'System Department': 'miscellaneous',
            'Discount Rates (LIC)': 'discount rate curve for IFRS 17',
            'Discount Rates (LRC)': 'discount rate curve for IFRS 17',
            'IBNR': 'CDF for Misc Conventional',
            'Risk Adjustments (LIC)': 'Risk adjustment loadings for Misc',
            'Risk Adjustments (LRC)': 'Risk adjustment loadings for Misc',
            'GMM Inputs': 'GMM Input file',
            'Claim Patterns': 'Claim pattern for Misc',
        },
    ];
    
    // Hardcoded dropdown options
    var ibnr = ['CDF for Misc Conventional', 'CDF for Fire Conventional', 'CDF for Health Conventional','CDF for Misc Takaful'];
    var discount_rates = ['discount rate curve for IFRS 17' ];
    var risk_adjustments = ['Risk adjustment loadings for Fire', 'Risk adjustment loadings for Health', 'Risk adjustment loadings for Misc', 'Risk adjustment loadings for Fire Motor'];
    var claim_patterns = ['Claim pattern for Fire', 'Claim pattern for Marine', 'Claim pattern for Motor', 'Claim pattern for Misc', 'Claim pattern for Health'];
    var Gmm_input= ['GMM Input file'];

    // Hardcoded route and token
    var route = '/api/save-provisions';
    var token = 'your_csrf_token_here';

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
                title: 'GMM Product Code',
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
                title: 'Discount Rates (LIC)',
                width: 150,
                source: discount_rates,
            },
            {
                type: 'dropdown',
                title: 'Discount Rates (LRC)',
                width: 150,
                source: discount_rates,
            },
            {
                type: 'dropdown',
                title: 'IBNR',
                width: 150,
                source: ibnr,
                selected: 0
            },
           
            {
                type: 'dropdown',
                title: 'Risk Adjustments (LIC) ',
                width: 150,
                autocomplete: true,
                source: risk_adjustments,
            },
            {
                type: 'dropdown',
                title: 'Risk Adjustments (LRC)',
                width: 150,
                autocomplete: true,
                source: risk_adjustments,
            },
             {
                type: 'dropdown',
                title: 'GMM Inputs',
                width: 150,
                source: Gmm_input,
            },
            {
                type: 'dropdown',
                title: 'Claim Patterns',
                width: 150,
                autocomplete: true,
                source: claim_patterns,
            },
         
        ],
        onbeforeinsertrow: onbeforeinsertrow
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