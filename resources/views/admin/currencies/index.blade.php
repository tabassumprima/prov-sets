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
                                <h2>Currencies</h2>
                            </div>
                            <div class="col-lg-12 col-xl-6 pl-xl-75 pl-0">
                                <div
                                    class="dt-action-buttons text-xl-right text-lg-left text-md-right text-left d-flex align-items-center justify-content-lg-end align-items-center flex-sm-nowrap flex-wrap mr-1">
                                    <div class="mr-1">
                                        <div id="DataTables_Table_0_filter" class="dataTables_filter">

                                        </div>
                                    </div>
                                    <div class="dt-buttons btn-group flex-wrap">
                                        <form action={{ route('currencies.create') }}>
                                            <button class="btn add-new btn-primary mt-50" tabindex="0"
                                                aria-controls="DataTables_Table_0" type="submit">
                                                <span>Create New Currency</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-datatable table-responsive pt-0 pb-1 pl-1 pr-1">
                            <table class="user-list-table table data-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Currency Name</th>
                                        <th>Symbol</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                @foreach ($currencies as $currency)
                                    <tr>
                                        <td>{{ $currency->name }}</td>
                                        <td>{{ $currency->symbol }}</td>
                                        <td>
                                            <a id="currency_edit:{{ $currency->id }}"
                                                href="{{ route('currencies.edit', [CustomHelper::encode($currency->id)]) }}">
                                                <i data-feather="edit"></i></a>
                                            <a class="delete"
                                                data-route="{{ route('currencies.destroy', [CustomHelper::encode($currency->id)]) }}"><i
                                                    data-feather="trash-2" style="color:red;"></i></a>

                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    <!-- list section end -->

                </section>
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
        </div>
    </div>
@endSection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('.delete').on('click', function() {
                route = $(this).data('route');
                $('#danger').modal('show');
                $('#delete-user-form').attr('action', route);
            });
        });
    </script>
@endsection
