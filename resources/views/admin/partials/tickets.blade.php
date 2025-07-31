<div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75 mb-75"><div class="col-lg-12 col-xl-6"><h2>Tickets</h2></div><div class="col-lg-12 col-xl-6 pl-xl-75 pl-0"><div class="dt-action-buttons text-xl-right text-lg-left text-md-right text-left d-flex align-items-center justify-content-lg-end align-items-center flex-sm-nowrap flex-wrap mr-1"><div class="mr-1"><div id="DataTables_Table_0_filter" class="dataTables_filter"></div></div></div></div></div>
<div class="card-datatable table-responsive pt-0 pb-1 pl-1 pr-1">
    <table class="user-list-table table data-table">
        <thead class="thead-light">
            <tr>
                <th>Reported By</th>
                <th>Title</th>
                <th>Severity</th>
                <th>Message</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        @foreach($userTickets as $ticket)
        <tr>
            <td>{{ $ticket->user->name }}</td>
            <td>{{ $ticket->title }}</td>
            @if($ticket->severity == "high")
                <td>
                    <label class="badge badge-pill badge-light-danger mr-1">
                        {{ ucfirst($ticket->severity) }}
                    </label>
                </td>
            @elseif($ticket->severity == "medium")
                <td>
                    <label class="badge badge-pill badge-light-warning mr-1">
                        {{ ucfirst($ticket->severity) }}
                    </label>
                </td>
            @else
                <td>
                    <label class="badge badge-pill badge-light-info mr-1">
                        {{ ucfirst($ticket->severity) }}
                    </label>
                </td>
            @endif
            <td style="white-space: normal; word-wrap: break-word; max-width: 400px; text-align:justify">
                @if(strlen($ticket->message) > 100)
                    <span>{{ substr($ticket->message, 0, 99) }}
                        ...
                    </span>
                @else
                    {{ $ticket->message }}
                @endif
            </td>

            <td>
                <label class="badge badge-pill badge-light-{{($ticket->is_resolved == 1) ? 'success' : 'warning' }} mr-1">
                    {{($ticket->is_resolved == 1) ? 'Resolved' : 'Pending' }}
                </label>
            </td>
            <td class="d-flex align-items-center">
                <a href="{{ route('user_ticket.show', CustomHelper::encode($ticket->id)) }}"
                    class="btn btn-outline-success btn-sm waves-effect waves-float waves-light mr-1"
                    style="border-color: #003399 !important; color: #003399 !important;">
                     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                          stroke="#003399" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          class="feather feather-eye">
                         <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                         <circle cx="12" cy="12" r="3"></circle>
                     </svg>
                 </a>
                    @if(!$ticket->is_resolved)
                    <a href="{{ route('tickets.status-update', CustomHelper::encode($ticket->id)) }}" class="btn btn-success btn-sm waves-effect waves-float waves-light">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </a>
                @endif
            </td>

        </tr>
        @endforeach
    </table>
</div>
