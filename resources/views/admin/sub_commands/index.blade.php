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
                                <h2>Sub Commands</h2>
                            </div>
                            <div class="col-lg-12 col-xl-6 pl-xl-75 pl-0">
                                <div
                                    class="dt-action-buttons text-xl-right text-lg-left text-md-right text-left d-flex align-items-center justify-content-lg-end align-items-center flex-sm-nowrap flex-wrap mr-1">
                                    <div class="mr-1">
                                        <div id="DataTables_Table_0_filter" class="dataTables_filter"></div>
                                    </div>
                                    <div class="dt-buttons btn-group flex-wrap">
                                        <a class="btn add-new btn-primary mt-50" href="{{route('lambda-sub-functions.create')}}" tabindex="0"><span>Add New Function</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-datatable table-responsive pt-0 px-1 py-1">
                            <table class="user-list-table table data-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Sub Command</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                @foreach ($subCommands as $subCommand)
                                    <tr>
                                       <td>{{$subCommand->command}}</td>
                                        <td>
                                            <a id="user_edit:{{ $subCommand->id }}"
                                                href="{{route('lambda-sub-functions.edit', ['lambda_sub_function' => $subCommand->id])}}"><i
                                                    data-feather="edit"></i>
                                            </a>
                                            <a data-route="{{ route('lambda-sub-functions.destroy', [$subCommand->id]) }}"
                                                class="delete"><i data-feather="trash-2" style="color:red;"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
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
                                <form id="delete-user-form" method="POST">
                                    @csrf
                                    @method('DELETE')
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
            $(".delete").on('click', function() {
            $("#danger").modal();
            route = $(this).data('route');
            console.log(route)
            document.getElementById('delete-user-form').action = route;
        });
    </script>
@endSection
