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
                            <h2 class="content-header-title float-left mb-0">Module Status</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section>
                    <x-toast :errors="$errors" />
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <ul class="nav nav-pills" role="tablist">
                                                <h2>Provision Rules</h2>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="card-datatable table-responsive pt-0 pb-1 pb-1 pl-1 pr-1">
                                                    <table class="user-list-table table config-table">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>File Name</th>
                                                                <th>Status</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($provisionRules as $moduleName => $fileData)
                                                                <tr>
                                                                    <td>{{ $moduleName }}</td>
                                                                    <td>
                                                                        @if ($fileData['status'] == 1)
                                                                            <span class="badge badge-success">{{ $fileData['message'] }}</span>
                                                                        @else
                                                                            <span class="badge badge-danger">{{ $fileData['message'] }}</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if ($fileData['status'] == 1)
                                                                            <a href="{{ route('provison_rule.file', ['file' => $moduleName, 'module' => 'provision_rules']) }}">
                                                                                <i data-feather="download"></i>
                                                                            </a>
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <ul class="nav nav-pills" role="tablist">
                                                <h2>Provision Files</h2>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="card-datatable table-responsive pt-0 pb-1 pb-1 pl-1 pr-1">
                                                    <table class="user-list-table table ">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>Folder Name</th>
                                                                <th>Status</th>
                                                                <th>File Count</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($provisionFiles as $folderName => $provisionFile)
                                                                <tr>
                                                                    <td>{{ $folderName }}</td>
                                                                    <td>
                                                                        @if ($provisionFile['status'] == 1)
                                                                            <span class="badge badge-success">{{ $provisionFile['message'] }}</span>
                                                                        @else
                                                                            <span class="badge badge-danger">{{ $provisionFile['message'] }}</span>
                                                                        @endif
                                                                    </td>
                                                                    <td> {{ $provisionFile['file_count'] }} </td>
                                                                    <td>
                                                                        @if ($provisionFile['file_count'] > 0)
                                                                            <a href="#" data-toggle="modal" data-folder-name="{{ $folderName }}" data-provision-file="{{ $provisionFile['files'] }}"  class="files-data">
                                                                                <i data-feather="eye"></i>
                                                                            </a>
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <ul class="nav nav-pills" role="tablist">
                                                <h2>Dashboard Files</h2>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="card-datatable table-responsive pt-0 pb-1 pb-1 pl-1 pr-1">
                                                    <table class="user-list-table table config-table">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>File Name</th>
                                                                <th>Status</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($dashboardFiles as $moduleName => $fileData)
                                                                <tr>
                                                                    <td>{{ $moduleName }}</td>
                                                                    <td>
                                                                        @if ($fileData['status'] == 1)
                                                                            <span class="badge badge-success">{{ $fileData['message'] }}</span>
                                                                        @else
                                                                            <span class="badge badge-danger">{{ $fileData['message'] }}</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if ($fileData['status'] == 1)
                                                                            <a href="{{ route('dashboard.file', ['file' => $fileData['file_name']]) }}">
                                                                                <i data-feather="download"></i>
                                                                            </a>
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <ul class="nav nav-pills" role="tablist">
                                                <h2>Configuration Files</h2>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="card-datatable table-responsive pt-0 pb-1 pb-1 pl-1 pr-1">
                                                    <table class="user-list-table table config-table">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>File Name</th>
                                                                <th>Status</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($configFiles as $moduleName => $fileData)
                                                                <tr>
                                                                    <td>{{ $moduleName }}</td>
                                                                    <td>
                                                                        @if ($fileData['status'] == 1)
                                                                            <span class="badge badge-success">{{ $fileData['message'] }}</span>
                                                                        @else
                                                                            <span class="badge badge-danger">{{ $fileData['message'] }}</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if ($fileData['status'] == 1)
                                                                            <a href="{{ route('download-config-file', ['file' => $fileData['file_name']]) }}">
                                                                                <i data-feather="download"></i>
                                                                            </a>
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <ul class="nav nav-pills" role="tablist">
                                                <h2>Json Format</h2>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="card-datatable table-responsive pt-0 pb-1 pb-1 pl-1 pr-1">
                                                    <table class="user-list-table table config-table">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>File Type</th>
                                                                <th>Status</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($reportData as $moduleName => $report)
                                                                <tr>
                                                                    <td>{{ $moduleName }}</td>
                                                                    <td>
                                                                        @if ($report['status'] == 1)
                                                                            <span class="badge badge-success">{{ $report['message'] }}</span>
                                                                        @else
                                                                            <span class="badge badge-danger">{{ $report['message'] }}</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if ($report['status'] == 1)
                                                                            <a href="{{ route('report-format.file', CustomHelper::encode($report['id'])) }}">
                                                                                <i data-feather="download"></i>
                                                                            </a>
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <ul class="nav nav-pills" role="tablist">
                                                <h2>Chart of Account</h2>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="card-datatable table-responsive pt-0 pb-1 pb-1 pl-1 pr-1">
                                                    <table class="user-list-table table config-table">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>File Name</th>
                                                                <th>Status</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>Chart of account</td>
                                                                <td>
                                                                    @if ($chartOfAccountData['status'] == 1)
                                                                        <span class="badge badge-success">{{ $chartOfAccountData['message'] }}</span>
                                                                    @else
                                                                        <span class="badge badge-danger">{{ $chartOfAccountData['message'] }}</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($chartOfAccountData['status'] == 1)
                                                                        <a href="{{ route('chart-of-account.file') }}">
                                                                            <i data-feather="download"></i>
                                                                        </a>
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </section>
                <!-- files Modal -->
                <div class="modal fade" id="files-modal" tabindex="-1" role="dialog" aria-labelledby="files-modalTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-large" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h3 class="modal-title text-primary" id="filesModalLongTitle">Files</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="user-list-table table" id="files-table">
                                    <thead>
                                        <tr>
                                            <th>File Name</th>
                                            <th>Valuatio Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Table rows will go here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    </div>
                </div>
                <!-- files Modal End-->

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
    // Triggered when the files path is clicked
    $(document).on('click', '.files-data', function () {
        // Clear existing table data
        $('#files-table tbody').empty();

        // Get the files data from the data-summaries attribute
        var filesData = $(this).data('provision-file');
        var folderName = $(this).data('folder-name');

        // Iterate through the data and append rows to the table
        const fileFetchRoute = "{{ route('fetch-provision-file', ['folder' => ':folder', 'file' => ':file']) }}";
        $.each(filesData, function (index, rowData) {
            const route = fileFetchRoute.replace(':folder', folderName).replace(':file', rowData.path);
            var row = '<tr>' +
                '<td>' + rowData.name + '</td>' +
                '<td>' + rowData.valuation_date + '</td>' +
                '<td><a href="' + route + '" target="_blank">View<i data-feather="eye"></i></a></td>'+
                '</tr>';

            // Append the row to the table body
            $('#files-table tbody').append(row);
        });
        // Show the modal
        $('#files-modal').modal('show');
    });
</script>
@endsection
