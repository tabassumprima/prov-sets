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
                <!-- users list start -->
                <section class="app-user-list">
                    <!-- list section start -->
                    <div class="card">
                        <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75 mb-75">
                            <div class="col-lg-12 col-xl-6">
                                <h2>Organization Information</h2>
                            </div>
                            <div class="col-lg-12 col-xl-6 pl-xl-75 pl-0">
                                <div
                                    class="dt-action-buttons text-xl-right text-lg-left text-md-right text-left d-flex
                                    align-items-center justify-content-lg-end align-items-center flex-sm-nowrap
                                    flex-wrap mr-1">
                                    <div class="dt-buttons btn-group flex-wrap">
                                        <button class="btn add-new btn-primary mt-50" tabindex="0"
                                            aria-controls="DataTables_Table_0" type="button" data-toggle="modal"
                                            data-target="#modals-slide-in">
                                            <span>Add New Organization</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-datatable table-responsive pt-0">
                            <table class="user-list-table table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Country</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                @if ($organizations)
                                    @foreach ($organizations as $organization)
                                        <tr>
                                            <td>{{ $organization->id }}</td>
                                            <td>{{ $organization->name }}</td>
                                            <td>{{ $organization->country->name }}</td>
                                            <td>
                                                <a id="edit"
                                                    href="{{ route('organizations.edit', [CustomHelper::encode($organization->id)]) }}"><i
                                                        data-feather="edit"></i></a>
                                                <a id="delete" class="delete"
                                                    data-route="{{ route('organizations.destroy', [CustomHelper::encode($organization->id)]) }}"><i
                                                        data-feather="trash-2" style="color:red;"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                            </table>
                        </div>
                        <!-- Modal to add new user starts-->
                        <div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in">
                            <div class="modal-dialog">
                                <form class="add-new-user modal-content pt-0" method="POST" enctype="multipart/form-data"
                                    action="{{ route('organizations.store') }}">
                                    @csrf
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Close">×</button>
                                    <div class="modal-header mb-1">
                                        <h5 class="modal-title" id="exampleModalLabel">New Organization</h5>
                                    </div>
                                    <div class="modal-body flex-grow-1">
                                        <div class="form-group">
                                            <label class="form-label required" for="basic-icon-default-fullname">Organization
                                                Name</label>
                                            <input type="text" class="form-control dt-full-name"
                                                id="basic-icon-default-fullname" placeholder="X Organization" name="name"
                                                aria-label="John Doe" aria-describedby="basic-icon-default-fullname2" value="{{old('name')}}" />
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label required" for="basic-icon-default-fullname">Country</label>
                                            <select id="country_id" name="country_id" class="select2 form-control">
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}" {{ (old('country_id') == $country->id) ? 'selected' : '' }}>{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label required" for="basic-icon-default-fullname">Currency</label>
                                            <select id="currency_id" name="currency_id" class="select2 form-control" value="{{old('currency_id')}}">
                                                @foreach ($currencies as $currency)
                                                    <option value="{{ $currency->id }}" {{ (old('currency_id') == $currency->id) ? 'selected' : '' }}>{{ $currency->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label required" for="basic-icon-default-fullname">Type</label>
                                            <select id="type" name="type" class="select2 form-control" value="{{old('type')}}">
                                                @foreach ($insurance_types as $insurance_type )
                                                    <option value="{{$insurance_type}}" {{ (old('type') == $insurance_type)? 'selected' : '' }}> {{$insurance_type}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label required" for="basic-icon-default-fullname">Subscription Type</label>
                                            <select id="type" name="subscription_type" class="select2 form-control">
                                                <option value="Subscription1">Subscription 1</option>
                                                <option value="Subscription2">Subscription 2</option>
                                                <option value="Subscription3">Subscription 3</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label required" for="user-role">Organization Address</label>
                                            <input type="text" id="basic-icon-default-email" class="form-control dt-email"
                                                placeholder="123 Street." aria-describedby="basic-icon-default-email2"
                                                name="address" value="{{old('address')}}"/>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label required" for="user-role">Organization Shortcode</label>
                                            <input type="text" id="basic-icon-default-email" class="form-control dt-email"
                                                placeholder="JBL" aria-describedby="basic-icon-default-email2"
                                                name="shortcode" value="{{old('shortcode')}}"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="organization_logo">Upload Logo</label>
                                            <div class="custom-file">
                                                <input type="file"name="logo" class="custom-file-input" id="organization_logo">
                                                <label class="custom-file-label"  for="organization_logo">Choose file</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label required" for="user-role">Organization FBR NTN Number</label>
                                            <input type="text" id="basic-icon-default-email" class="form-control dt-email"
                                                placeholder="123456" aria-label="john.doe@example.com"
                                                aria-describedby="basic-icon-default-email2" name="ntn_number" value="{{old('ntn_number')}}"/>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label required" for="basic-icon-default-fullname">Financial Year</label>
                                            <select id="financial_year" name="financial_year" class="select2 form-control">
                                               @foreach ($financial_years as $key => $financial_year )
                                                    <option value="{{$key}}" {{ (old('financial_year') == $key)? 'selected' : '' }}>{{$financial_year}}</option>
                                               @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group ">
                                            <label class="form-label" for="user-role">Organization Sales Tax Number</label>
                                            <input type="text" id="basic-icon-default-email" class="form-control dt-email"
                                                placeholder="123456" aria-label="john.doe@example.com"
                                                aria-describedby="basic-icon-default-email2" name="sales_tax_number" value="{{old('sales_tax_number')}}"/>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label required" for="basic-icon-default-fullname">Database Config
                                                ID</label>
                                            <select id="database_config_id" name="database_config_id"
                                                class="select2 form-control">
                                                @foreach ($configs as $config)
                                                    <option value="{{ $config->id }}"{{ (old('database_config_id') == $config->id)? 'selected' : '' }}>{{ $config->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="user-role">Agent Configuration</label>
                                            <input type="text" id="basic-icon-default-email" class="form-control dt-email"
                                                placeholder="" aria-label="john.doe@example.com"
                                                aria-describedby="basic-icon-default-email2" name="agent_config" />
                                        </div>
                                        <x-form-buttons textSubmit='Submit' textCancel='Cancel'  />
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Modal to add new user Ends-->
                    </div>
                    <!-- list section end -->

                    <!-- Modal -->
                    <div class="modal fade modal-danger text-left" id="danger" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel120" aria-hidden="true">
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
            </section>
            <!-- users list ends -->

        </div>
    </div>
    </div>
@endSection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            updateUrl(`{{ Request::input('org') }}`);
            $('.delete').on('click', function() {
                $("#danger").modal();
                route = $(this).data('route');
                document.getElementById('delete-user-form').action = route;
            });
        });
    </script>
@endSection
