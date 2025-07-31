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
                                <h2>Import Detail Config</h2>
                            </div>
                            @if ($isBoarding && $latest_id->id == $id && $completedSlug == 'completed' && $mappingExist && $type == 'provision') 
                            <form action="{{route('import_detail.rollBack')}}" method = 'post'>
                                @csrf
                                <input type="hidden" name="import_id" value="{{$id}}">    
                                <div class="col-lg-12 col-xl-6 text-right">
                                    <button type="submit" class="btn btn-danger">Rollback</button>
                                </div>
                            </form>
                            @endif
                        </div>

                        <div class="card-datatable table-responsive pt-0">
                            <table class="user-list-table table">
                                <thead class="thead-light">
                                    <tr>
                                       <th>Tables</th>
                                       <th>Total Count</th>
                                    </tr>
                                </thead>
                                        <tr>
                                            <td>Acounting Years</td>
                                            <td>{{$countAll->accounting_year_count}}</td>  
                                        </tr>
                                        <tr>
                                            <td>Branches</td>
                                            <td>{{$countAll->branch_count}}</td>
                                        </tr>
                                        <tr>
                                            <td>Business Type</td>
                                            <td>{{$countAll->business_type_count}}</td>
                                        </tr>
                                        <tr>
                                            <td>Claim Paid Registers</td>
                                            <td>{{$countAll->claim_paid_register_count}}</td>
                                        </tr>
                                        <tr>
                                            <td>System Departments</td>
                                            <td>{{$countAll->system_department_count}}</td>
                                        </tr>
                                        <tr>
                                            <td>Insurance Type</td>
                                            <td>{{$countAll->insurance_type_count}}</td>
                                        </tr>
                                        <tr>
                                            <td>Document Type</td>
                                            <td>{{$countAll->document_type_count}}</td>
                                        </tr>
                                        <tr>
                                            <td>Endorsement Type</td>
                                            <td>{{$countAll->endorsement_type_count}}</td>
                                        </tr>
                                        <tr>
                                            <td>Transaction Type</td>
                                            <td>{{$countAll->transaction_type_count}}</td>
                                        </tr>
                                        <tr>
                                            <td>Product Codes</td>
                                            <td>{{$countAll->product_code_count}}</td>
                                        </tr>
                                        <tr>
                                            <td>Premium Rergister</td>
                                            <td>{{$countAll->premium_register_count}}</td>
                                        </tr>
                                        <tr>
                                            <td>Gl Codes</td>
                                            <td>{{$countAll->gl_code_count}}</td>
                                        </tr>
                                        <tr>
                                            <td>Voucher Types</td>
                                            <td>{{$countAll->voucher_type_count}}</td>
                                        </tr>
                                        <tr>
                                            <td>Journal Entries</td>
                                            <td>{{$countAll->journal_entry_count}}</td>
                                        </tr> 
                            </table>
                        </div>
                        <!-- Modal to add new user starts-->
                        {{-- <div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in">
                            <div class="modal-dialog">
                                <form class="add-new-user modal-content pt-0" method="POST"
                                    action="{{ route('users.store') }}">
                                    @csrf
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Close">×</button>
                                    <div class="modal-header mb-1">
                                        <h5 class="modal-title" id="exampleModalLabel">New User</h5>
                                    </div>
                                    <div class="modal-body flex-grow-1">
                                        <div class="form-group">
                                            <label class="form-label required" for="basic-icon-default-fullname">Full
                                                Name</label>
                                            <input type="text" class="form-control dt-full-name"
                                                id="basic-icon-default-fullname" placeholder="John Doe" name="name"
                                                aria-label="John Doe" aria-describedby="basic-icon-default-fullname2" />
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label required" for="basic-icon-default-fullname">Phone
                                                Number</label>
                                            <input type="text" class="form-control dt-full-name"
                                                id="basic-icon-default-fullname" placeholder="+923123456" name="phone"
                                                aria-label="+923123456" aria-describedby="basic-icon-default-fullname2" />
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label required" for="user-role">Companies</label>
                                            <select id="company_id" name="company_id" class="select2 form-control">
                                                @foreach ($companies as $company)
                                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label required" for="basic-icon-default-email">Email</label>
                                            <input type="text" id="basic-icon-default-email" class="form-control dt-email"
                                                placeholder="john.doe@example.com" aria-label="john.doe@example.com"
                                                aria-describedby="basic-icon-default-email2" name="email" />
                                            <small class="form-text text-muted"> You can use letters, numbers & periods
                                            </small>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label required" for="user-role">User Role</label>
                                            <select id="user_role" name="user_role" class="select2 form-control">
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role }}">{{ $role }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <x-form-buttons textSubmit="Save Changes" textCancel="Cancel" />
                                    </div>
                                </form>
                            </div>
                        </div> --}}
                        <!-- Modal to add new user Ends-->
                    </div>
                    <!-- list section end -->

                    <!-- Modal -->
                    <div class="modal fade modal-danger text-left" id="danger" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel120" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="myModalLabel120">Delete Provision</h5>
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
        updateUrl(`{{ Request::input('org') }}`);     //Load road tenancy param
    });
    </script>
@endSection
