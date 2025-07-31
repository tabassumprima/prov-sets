@extends('admin.layouts.app')

@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <x-toast :errors="$errors" />
                <!-- users list start -->
                <section class="app-user-list">
                    <!-- list section start -->
                    <div class="card">
                        <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75 mb-75">
                            <div class="col-lg-12 col-xl-6">
                                <h2>Processing Functions</h2>
                            </div>
                            <div class="col-lg-12 col-xl-6 pl-xl-75 pl-0">
                                <div
                                    class="dt-action-buttons text-xl-right text-lg-left text-md-right text-left d-flex align-items-center justify-content-lg-end align-items-center flex-sm-nowrap flex-wrap mr-1">
                                    <div class="mr-1">
                                        <div id="DataTables_Table_0_filter" class="dataTables_filter"></div>
                                    </div>
                                    <div class="dt-buttons btn-group flex-wrap">
                                        <a class="btn add-new btn-primary mt-50" href="{{route('lambda.create')}}" tabindex="0"><span>Add New Function</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-datatable table-responsive pt-0 pb-1 pl-1 pr-1">
                            <table class="user-list-table table data-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Command</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                @foreach ($functions as $function)
                                    <tr>
                                        <td>{{ $function->name }}</td>
                                        <td>{{ $function->command }}</td>
                                        <td><span class="badge badge-{{$function->is_active ? 'success' : 'danger'}}">{{$function->is_active ? 'active' : 'disabled'}}</span></td>
                                        <td>
                                            <a id="user_edit:{{ $function->id }}"
                                                href="{{route('lambda.edit', ['lambda' => $function->id])}}"><i
                                                    data-feather="edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <!-- Modal to add new user starts-->
                        <div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in">
                            <div class="modal-dialog">
                                <form class="add-new-user modal-content pt-0" method="POST"
                                    action="{{ route('lambda.store') }}">
                                    @csrf
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×
                                    </button>
                                    <div class="modal-header mb-1">
                                        <h5 class="modal-title" id="exampleModalLabel">New Function</h5>
                                    </div>
                                    <div class="modal-body flex-grow-1">
                                        <div class="form-group">
                                            <label class="form-label" for="basic-icon-default-fullname">
                                                Identifier
                                            </label>
                                            <input type="text" class="form-control dt-full-name"
                                                id="basic-icon-default-fullname" placeholder="KSA-EOSB-XX" name="identifier"
                                                aria-label="John Doe" aria-describedby="basic-icon-default-fullname2"
                                                required  value="{{old('identifier')}}" />
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="basic-icon-default-email">Human Readable
                                                Name</label>
                                            <input type="text" id="basic-icon-default-email"
                                                class="form-control dt-email" placeholder="KSA Labor Benefits"
                                                aria-label="john.doe@example.com"
                                                aria-describedby="basic-icon-default-email2" name="name"  value="{{old('name')}}"required />
                                        </div>
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label class="form-label" for="basic-icon-default-email">Function Name
                                                    (ARN)</label>
                                                <input type="text" id="basic-icon-default-email"
                                                    class="form-control dt-email" placeholder="EOSB-Rule-KSA"
                                                    aria-label="john.doe@example.com"
                                                    aria-describedby="basic-icon-default-email2" name="arn" value="{{old('arn')}}"
                                                    required />
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary mr-1 data-submit">Submit</button>
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
                                <form id="delete-user-form" method="GET">
                                    @csrf
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
            //Load road tenancy param
            updateUrl(`{{ Request::input('org') }}`);
     });
     </script>
@endSection
