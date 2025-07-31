@extends('admin.layouts.app')

@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <x-toast :errors="$errors"/>
                <div class="alert" style="display: none;" role="alert">
                    <div class="alert-body">

                    </div>
                </div>
                <!-- users edit start -->
                <section class="app-user-edit">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills" role="tablist">
                                <h2>Add Roles With Permission</h2>
                            </ul>
                            <div class="tab-content">
                                <!-- Account Tab starts -->
                                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                                    <!-- users edit account form start -->
                                    <form class="form-validate" method="POST"
                                        action="{{ route('roles.store') }}"
                                        autocomplete="off">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="username" class="required">Role Name</label>
                                                            <input type="text" class="form-control" placeholder="Ex: Manager" name="name" id="name" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    @foreach ($permissions as $moduleType => $modules)
                                                        <div class="col-md-12 mb-3">
                                                            <ul class="nav nav-pills justify-content-between" role="tablist">
                                                                <li class="nav-item d-lg-block"> <h3>{{ $moduleType }}</h3></li>
                                                                <li class="nav-item d-lg-block">
                                                                    <button type="button" id="select-{{ Str::slug($moduleType, '-') }}" class="btn btn-primary select-all">Select All</button>
                                                                    <button type="button" id="unselect-{{ Str::slug($moduleType, '-') }}" class="btn btn-primary unselect-all">Unselect All</button> 
                                                                 </li>
                                                             </ul>
                                                            <div class="row mt-2">
                                                                @foreach ($modules as $moduleName => $modulePermissions)
                                                                    <div class="col-md-4 mb-1">
                                                                        <h6>{{ $moduleName }}</h6>
                                                                        @foreach ($modulePermissions as $permission)
                                                                            <div class="col-md-12 mb-1 mt-1">
                                                                                <div class="form-group">
                                                                                    <div class="custom-control custom-control-success custom-checkbox">
                                                                                        <input type="checkbox" name="permissions[]" class="custom-control-input"
                                                                                            id="{{ Str::slug($permission, '-') }}"
                                                                                            value="{{ $permission }}" />
                                                                                        <label class="custom-control-label"
                                                                                            for="{{ Str::slug($permission, '-') }}">{{ $permission }}</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                               <x-form-buttons textSubmit="Save Changes" />
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
                <!-- users edit ends -->

            </div>
        </div>
    </div>
@endSection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.select-all').click(function () {
                var moduleTypeElement = $(this).closest('.col-md-12');
                moduleTypeElement.find(':checkbox').prop('checked', true);
            });
            $('.unselect-all').click(function () {
                var moduleTypeElement = $(this).closest('.col-md-12');
                moduleTypeElement.find(':checkbox').prop('checked', false);
            });
            updateUrl(`{{ Request::input('org') }}`);
        });
    </script>
@endSection
