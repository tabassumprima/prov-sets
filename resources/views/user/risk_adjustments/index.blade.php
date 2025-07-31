@extends('user.layouts.app')

@section('content')
<!-- BEGIN: Content-->
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
                                <li class="breadcrumb-item">Actuarial Assumption</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="content-body">
            <x-toast :errors="$errors" />

            <!-- Basic tabs start -->
            <section id="basic-tabs-components">
                <div class="row match-height">
                    <!-- Basic Tabs starts -->
                    <div class="col-xl-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row w-100">
                                    <div class="col-md-6 text-left">
                                        <h2>Risk Adjustment</h2>
                                    </div>
                                    @authorize('create-risk-adjustment', true)
                                    <div class="col-md-6 text-right">
                                        <button class="btn add-new btn-primary" tabindex="0" type="button"
                                            data-toggle="modal" data-target="#modals-slide-in"><i
                                                data-feather='plus'></i><span> Add new</span></button>
                                    </div>
                                    @endauthorize
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive pl-1 pr-1">
                                    <table class="table data-table">
                                        <thead>
                                            <tr>
                                                <th>S.no</th>
                                                <th>Name</th>
                                                <th>File Count</th>
                                                <th>TRIANGLE TYPE</th>
                                                <th>FREQUENCY</th>
                                                <th>Last modified</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($records as $record)
                                            <tr>
                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td>
                                                    @can('view-risk-adjustment-file', true)
                                                    <a href="{{ route('risk-adjustments.files.index', ['risk_adjustment' => CustomHelper::encode($record->id)]) }}">{{ $record->name }}</a>
                                                    @else
                                                    {{ $record->name }}
                                                    @endcan
                                                </td>
                                                <td>{{$record->files_count}}</td>
                                                <td>{{ $record->triangle_type }}</td>
                                                <td>{{ $record->frequency }}</td>
                                                <td>{{ $record->updated_at }}</td>
                                                <td>

                                                    <div class="dropdown">
                                                        <button type="button"
                                                            class="btn btn-sm dropdown-toggle hide-arrow"
                                                            data-toggle="dropdown">
                                                            <i data-feather="more-vertical"></i>
                                                        </button>

                                                        <div class="dropdown-menu">
                                                            @if($isboarding)
                                                            <a class="dropdown-item" href="{{ route('risk-adjustments.edit', ['risk_adjustment' => CustomHelper::encode($record->id)]) }}">
                                                                <i data-feather="edit-2" class="mr-50"></i>
                                                                <span>Edit</span>
                                                            </a>
                                                            <a class="dropdown-item delete" data-route="{{ route('risk-adjustments.destroy', ['risk_adjustment' => CustomHelper::encode($record->id)]) }}">
                                                                <i data-feather="trash" class="mr-50"></i>
                                                                <span>Delete</span>
                                                            </a>
                                                            @endif
                                                            @authorize('update-status-risk-adjustment', true)
                                                            <a class="dropdown-item update-file"
                                                                id="update_file:{{ CustomHelper::encode($record->id) }}"
                                                                data-status="{{ $record->status->slug }}"
                                                                style="cursor: pointer; display: flex; align-items: center; gap: 5px;">
                                                                <i data-feather="{{ $record->status->slug == 'started' ? 'eye-off' : 'eye' }}" class="mr-50"></i>
                                                                <span>{{ $record->status->slug == 'started' ? 'Disable' : 'Enable' }}</span>
                                                            </a>

                                                            @endauthorize
                                                        </div>

                                                    </div>

                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Basic Tabs ends -->
                </div>
                <!-- Modal to add new user starts-->
                <div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in">
                    <div class="modal-dialog">
                        <form class="add-new-user modal-content pt-0" method="post"
                            action="{{ route('risk-adjustments.store') }}">
                            @csrf
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                            <div class="modal-header mb-1">
                                <h5 class="modal-title">New Risk Adjustment Factors</h5>
                            </div>
                            <div class="modal-body flex-grow-1">
                                <div class="form-group">
                                    <label class="form-label" for="name">Name</label>
                                    <input type="text" class="form-control" id="name" placeholder="Risk - Motor"
                                        name="name" aria-label="name" />
                                </div>
                                <div class="form-group">
                                    <label class="form-label required" for="group-desc">Triangle type</label>
                                    <select class="form-control" name="triangle_type">
                                        <option value="paid">Paid</option>
                                        <option value="incurred">Incurred</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label required" for="group-desc">Frequency</label>
                                    <select class="form-control" name="frequency">
                                        <option value="annual">Annual</option>
                                        <option value="semiannual">Semi-Annual</option>
                                        <option value="quarter">Quarter</option>
                                        <option value="month">Month</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary mr-1 data-submit">Create</button>
                                <button type="reset" class="btn btn-outline-secondary"
                                    data-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Modal to add new user Ends-->

                <!-- Modal -->
                <div class="modal fade modal-danger text-left" id="danger" tabindex="-1" role="dialog"
                    aria-labelledby="myModalLabel120" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="myModalLabel120">Delete Risk Adjustment</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete?
                            </div>
                            <form id="delete-risk-form" method="post">
                                @csrf
                                @method('delete')
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-danger">Yes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Modal -->
            </section>
        </div>
    </div>
</div>
<!-- END: Content-->
@endSection

@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/pickadate/pickadate.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('app-assets/css/plugins/forms/pickers/form-pickadate.min.css') }}">
@endSection

@section('scripts')
<script src="{{ asset('app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/forms/pickers/form-pickers.min.js') }}"></script>
<script>
    $(document).on('click', '.update-file', function() {
    var id = $(this).attr('id').split(':')[1];
    var value = $(this).data('status');
    var url = '{{ route('risk.file.status', ['risk_adjustments' => ':id']) }}';
    url = url.replace(':id', id);

    $.ajax({
        url: url,
        method: 'POST',
        data: {
            value: value,
            _token: '{{ csrf_token() }}'
        },
        success: function(res) {
            location.reload();
        },
        error: function(err) {
            console.log(err);
        }
    });
});


    $(".delete").on('click', function() {
        $("#danger").modal();
        route = $(this).data('route');
        document.getElementById('delete-risk-form').action = route;
    });
</script>
@endSection