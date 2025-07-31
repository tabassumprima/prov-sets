@if($item['type'] == 'break')
    <tr>
        <td></td>
        <td></td>
        <td></td>
    </tr>
@else
    <tr class="{{ $item['class'] }}">
        <td>{!! $item['description'] !!}</td>
        <td>{{ number_format(collect($item['values'])->first()) }}</td>
        <td>{{ number_format(collect($item['values'])->last()) }}</td>
    </tr>
@endif