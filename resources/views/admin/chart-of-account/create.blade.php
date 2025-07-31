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
                            <h2 class="content-header-title float-left mb-0">Upload Chart of Account </h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">Upload Chart of Account</a>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section class="app-user-edit">
                    <x-toast :errors="$errors" />
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills" role="tablist">
                                <h2>Upload Chart of Account</h2>
                            </ul>
                            <div class="tab-content">
                                <!-- Account Tab starts -->
                                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                                    <!-- users edit account form start -->
                                    <form class="form-validate" method="POST"
                                    action="{{ route('chart-of-accounts.store') }}" enctype="multipart/form-data"
                                        autocomplete="off">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="customFile">Upload CSV</label>
                                                            <div class="custom-file">
                                                                <input type="file"name="chart_of_account_file" class="custom-file-input" id="customFile">
                                                                <label class="custom-file-label"  for="customFile">Choose file</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                               <x-form-buttons textSubmit="Save Changes" />
                                               <a href="{{ route('chart-of-account.file') }}" class="btn btn-primary">
                                                <i data-feather='download'></i>
                                                Download File
                                            </a>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- users edit account form ends -->
                                </div>
                                <!-- Account Tab ends -->
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endSection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            updateUrl(`{{ Request::input('org') }}`);
        });
    </script>
@endSection
