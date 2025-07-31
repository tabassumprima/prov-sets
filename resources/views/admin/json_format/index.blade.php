@extends('admin.layouts.app')

@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">Json Formats</a>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <x-toast :errors="$errors" />
                @if (!$formatJsonReports->first()?->is_validate)
                <div class="alert alert-warning" role="alert">
                    <div class="alert-body">
                        Reports are not valid. Please contact administrator
                    </div>
                </div>
                @endif
                <section id="basic-tabs-components">
                    <div class="row match-height">
                        <div class="col-xl-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row w-100">
                                        <div class="col-6 text-left">
                                            <h2 class="content-header-title ">Json Format Reports</h2>
                                        </div>
                                        <div class="col-6 text-right pt-1">
                                            <a href="{{ route('report-format.create') }}" class="btn add-new btn-primary">
                                                <i data-feather='plus'></i><span style="color: white"> Add Json
                                                    Format</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive pl-1 pr-1">
                                        <table class="table data-table">
                                            <thead>
                                                <tr>
                                                    <th>S.no</th>
                                                    <th>Filname</th>
                                                    <th>Validated</th>
                                                    <th>Created at</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($formatJsonReports as $item)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $item->file_name }}</td>
                                                        <td>{{ $item->is_validate ? 'Yes' : 'No' }}</td>
                                                        </td>
                                                        <td>{{ $item->created_at }}</td>
                                                        <td>
                                                            <a data-route="{{ route('report-format.destroy', CustomHelper::encode($item->id)) }}"
                                                                class="delete">
                                                                <i data-feather="trash-2" style="color:red;"></i>
                                                            </a>

                                                            <a href="{{ route('report-format.file', CustomHelper::encode($item->id)) }}">
                                                                <i data-feather="download" style="color:#003399;"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Modal -->
                <div class="modal fade modal-danger text-left" id="danger" tabindex="-1" role="dialog"
                    aria-labelledby="myModalLabel120" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="myModalLabel120">Delete File</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete?
                            </div>
                            <form id="delete-user-form" method="post">
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
            </div>
        </div>
    </div>
@endSection

@section('scripts')
    <script>
        $(document).ready(function() {
            //Load road tenancy param
            updateUrl(`{{ Request::input('org') }}`);
     });
        $(".delete").on('click', function() {
            $("#danger").modal();
            route = $(this).data('route');
            document.getElementById('delete-user-form').action = route;
        });
    </script>
@endSection
