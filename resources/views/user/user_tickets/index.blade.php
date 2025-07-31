@extends('user.layouts.app')
@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-12 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Miscellaneous</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                @if($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger" role="alert">
                            <div class="alert-body">
                                Error: {{ $error }}
                            </div>
                        </div>
                    @endforeach
                @endif

                @if (\Session::has('error'))
                    <div class="alert alert-danger" role="alert">
                        <div class="alert-body">
                            {!! \Session::get('error') !!}
                        </div>
                    </div>
                @endif

                @if (\Session::has('success'))
                    <div class="alert alert-success" role="alert">
                        <div class="alert-body">
                            {!! \Session::get('success') !!}
                        </div>
                    </div>
            @endif
            <!-- contact list start -->
            <section class="app-user-list">
                <!-- list section start -->
                <div class="card">
                    <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75 mb-75"><div class="col-lg-12 col-xl-6"><h2>Issue Tickets</h2></div>
                    @authorize('create-report-issue', true)
                    <div class="col-lg-12 col-xl-6 pl-xl-75 pl-0"><div class="dt-action-buttons text-xl-right text-lg-left text-md-right text-left d-flex align-items-center justify-content-lg-end align-items-center flex-sm-nowrap flex-wrap mr-1"><div class="mr-1"><div id="DataTables_Table_0_filter" class="dataTables_filter"></div></div><div class="dt-buttons btn-group flex-wrap"><button class="btn add-new btn-primary mt-50" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-toggle="modal" data-target="#modals-slide-in"><span>Add New Ticket</span></button> </div></div></div>
                    @endauthorize
                </div>

                    <div class="card-datatable table-responsive pt-0 pb-1 pl-1 pr-1">
                        <table class="user-list-table table data-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Severity</th>
                                    <th>Message</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            @foreach($userTickets as $ticket)
                            <tr>
                                <td>{{$ticket->title}}</td>
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
                                <td style="white-space: normal; word-wrap: break-word; max-width: 550px; text-align:justify">
                                    {{ $ticket->message }}
                                </td>
                                <td>
                                    <label class="badge badge-pill badge-light-{{($ticket->is_resolved == 1) ? 'success' : 'warning' }} mr-1">
                                        {{($ticket->is_resolved == 1) ? 'Resolved' : 'Pending' }}
                                    </label>
                                </td>
                                <td>
                                    @if(!$ticket->is_resolved)
                                    @authorize('update-report-issue', true)
                                    <a id="contact_edit" href="{{route('tickets.edit', CustomHelper::encode($ticket->id) )}}"><i data-feather="edit"></i></a>
                                    @endauthorize
                                    @authorize('delete-report-issue', true)
                                    <a id="delete" class="delete"  data-route="{{ route('tickets.destroy', [CustomHelper::encode($ticket->id)]) }}" onclick=deleteModel(this.id)><i data-feather="trash-2" style="color:red;"></i></a>
                                    @endauthorize
                                    @else
                                        <a id="contact_edit" href="{{route('tickets.edit', CustomHelper::encode($ticket->id) )}}"><i data-feather="eye"></i></a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                    <!-- Modal to add new ticket starts-->
                    <div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in">
                        <div class="modal-dialog">
                            <form class="add-new-user modal-content pt-0" method="POST" action="{{ route('tickets.store') }}">
                                @method('POST')
                                @csrf
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                                <div class="modal-header mb-1">
                                    <h5 class="modal-title" id="exampleModalLabel">New Ticket</h5>
                                </div>
                                <div class="modal-body flex-grow-1">
                                    <div class="form-group">
                                        <label class="form-label" for="basic-icon-default-fullname">Title</label>
                                        <input type="text" maxlength="50" class="form-control dt-full-name" id="basic-icon-default-fullname" placeholder="Unable to create a user" name="title" aria-label="John Doe" aria-describedby="basic-icon-default-fullname2" required />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="basic-icon-default-email">Message</label>
                                        <textarea required  class="form-control" maxlength="255" name="message" id="ticketMessage" rows="3"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="user-role">Severity</label>
                                        <select name="severity"  class="select2 form-control" required>
                                           @foreach($severities as $key => $severity)
                                                <option value="{{$key}}">{{$severity}}</option>
                                           @endforeach
                                       </select>
                                   </div>
                                   <button type="submit" class="btn btn-primary mr-1 data-submit">Submit</button>
                                   <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                               </div>
                           </form>
                       </div>
                    </div>
                    <!-- Modal to add new ticket Ends-->
                </div>
                <!-- list section end -->

                <!-- Modal -->
                <div class="modal fade modal-danger text-left" id="danger" tabindex="-1" role="dialog" aria-labelledby="myModalLabel120" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="myModalLabel120">Delete User</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete?
                            </div>
                            <form id="delete-user-form" method="POST">
                                @method('DELETE')
                                @csrf
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-danger">Yes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Modal -->
            </section>
            <!-- contact list ends -->
        </div>
    </div>
</div>
@endSection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.delete').on('click', function() {
                $("#danger").modal();
                route = $(this).data('route');
                document.getElementById('delete-user-form').action = route;
            });
        });
    </script>
@endSection
