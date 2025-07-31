var table = $('#trial-table').DataTable({
    "order": [],
    "lengthMenu": [100, 200, 500], // Set the available page lengths
    "pageLength": 200,
    deferRender: true
});
start_date =$('#start').data('start')
end_date =$('#end').data('end')
var flatpickrInstance = flatpickr(".accounting_year", {
    mode: "range",
    maxDate: end_date,
    minDate: start_date
});

$('#filter').on('click', function() {
    fetch();
})

function fetch() {
    table.clear()
    form_data = $('#trial').serializeArray()
    modal = $('#fetch_records').modal('show')
    route = $('#route').data('route');
    $.ajax({
        type: 'get',
        url: route,
        data: form_data,
        success: function(data) {
            console.log(data)
            if(data.error == 500)
            {

                $('.error-wrapper').show();
                $('.error').html(data.message)

            }
            else{
                displayData(data)


            }
            setTimeout(function() {
                $('#fetch_records').modal('hide')
            }, 1000)
        },
        error: function(err) {
            setTimeout(function() {
                $('#fetch_records').modal('hide')
            }, 2000)
            $('.error-wrapper').show();
            $('.error').html(err.responseJSON['message'])
        }
    })
}

function displayData(data)
{
    $.each(data, function(i, k) {
        table.row.add([k.date, k.voucher, k.description, k.credit, k.debit, k.balance]).draw()

    })
}
