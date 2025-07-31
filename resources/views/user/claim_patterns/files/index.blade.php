@extends('user.layouts.app')

@section('content')
<!-- BEGIN: Content-->
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
                                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item">Actuarial Assumption</li>
                                <li class="breadcrumb-item"> <a href="{{ route('claim-patterns.index') }}">Claim Pattern</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="content-body">
            <x-toast :errors="$errors" type="claim_pattern"/>


            <!-- Basic tabs start -->
            <section id="basic-tabs-components">
                <div class="row match-height">
                    <!-- Basic Tabs starts -->


                    <div class="col-xl-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row w-100">
                                    <div class="col-md-6">
                                        <h2>Claim Pattern File Detail</h2>
                                    </div>
                                    @authorize('create-claim-pattern-file', true)
                                    <div class="col-md-6 text-right">

                                        <button class="btn add-new btn-primary" tabindex="0" type="button"
                                            data-toggle="modal" data-target="#modals-slide-in"><i
                                                data-feather='plus'></i><span> Upload new</span></button>
                                    </div>
                                    @endauthorize
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>S.no</th>
                                                <th>Name</th>
                                                <th>Uploaded file</th>
                                                <th>Valuation date</th>
                                                <th>Upload date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($claim_pattern->files as  $file)
                                            <tr>
                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td><span class="font-weight-bold">{{ $file->name }}</span></td>
                                                <td>
                                                    <a href="{{route('claim_pattern.file', CustomHelper::encode($file->id))}}">{{$file->path}}</a>
                                                </td>
                                                <td>
                                                    {{ $file->valuation_date }}
                                                </td>
                                                <td>
                                                    {{ $file->created_at}}
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button type="button"
                                                            class="btn btn-sm dropdown-toggle hide-arrow"
                                                            data-toggle="dropdown">
                                                            <i data-feather="more-vertical"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            @authorize('update-claim-pattern-file', true)
                                                            <a class="dropdown-item" href="{{ route('claim-patterns.files.edit', ['claim_pattern' => CustomHelper::encode($claim_pattern->id), 'file' => CustomHelper::encode($file->id)])}}">
                                                                <i data-feather="edit-2" class="mr-50"></i>
                                                                <span>Edit</span>
                                                            </a>
                                                            @endauthorize
                                                            @authorize('delete-claim-pattern-file', true)
                                                            <a class="dropdown-item delete" data-route="{{ route('claim-patterns.files.destroy', ['claim_pattern' => CustomHelper::encode($claim_pattern->id), 'file' => CustomHelper::encode($file->id)])}}">
                                                                <i data-feather="trash" class="mr-50"></i>
                                                                <span>Delete</span>
                                                            </a>
                                                            @endauthorize
                                                            @authorize('download-claim-pattern-file', true)
                                                            <a class="dropdown-item" href="{{route('claim_pattern.file', CustomHelper::encode($file->id))}}">
                                                                <i data-feather="download" class="mr-50"></i>
                                                                <span>Download</span>
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
                        <form class="add-new-user modal-content pt-0" enctype="multipart/form-data" action="{{ route('claim-patterns.files.store', ['claim_pattern' => CustomHelper::encode($claim_pattern->id)]) }}" method='post'>
                            @csrf
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                            <div class="modal-header mb-1">
                                <h5 class="modal-title">Upload new Claim Pattern</h5>
                            </div>
                            <div class="modal-body flex-grow-1">
                                <div class="form-group">
                                    <label for="name" class="required">Name</label>
                                    <input type="text" class="form-control" placeholder="Name"
                                             name="name" id="name" />
                                </div>
                                <div class="form-group">
                                    <label>Choose File</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="claim_file">
                                        <label class="custom-file-label">Choose file</label>
                                    </div>
                                </div>
                                <div class="form-group position-relative">
                                    <label class="form-label" for="valuation_date">Valuation date</label>
                                    <input type="text" id="valuation_date" name="valuation_date" class="form-control datepicker"
                                        placeholder="31 Dec, 2021" />
                                </div>
                                <button type="submit" class="btn btn-primary mr-1 data-submit">Upload</button>
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
                                <h5 class="modal-title" id="myModalLabel120">Delete IBNR File</h5>
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
            </section>

        </div>
    </div>
</div>
<!-- END: Content-->
@endSection

@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/pickadate/pickadate.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('app-assets/css/plugins/forms/pickers/form-pickadate.min.css') }}">
@endSection

@section('scripts')
<script src="{{ asset('app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/forms/pickers/form-pickers.min.js') }}"></script>
<script>
    $(document).ready(function(){
        $('.datepicker').pickadate({
                selectYears: 100,
                selectMonths: true,
                format: 'mmm dd, yyyy'
        });

        $(".delete").on('click', function() {
            $("#danger").modal();
            route = $(this).data('route');
            document.getElementById('delete-user-form').action = route;
        });
    })
</script>

@endSection
