@extends('user.layouts.app')

@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-12 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Chart of accounts</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Accounting</a>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="content-body">
                <section class="basic-custom-icons-tree">
                    <div class="row">
                        <!-- Basic Tree -->
                        <div class="col-12">
                            <div class="card">

                                <div class="card-body">
                                    <div class="row pb-2 ">
                                        <div class="col-6 col-md-4">
                                            <label class="form-label" for="search">Search &nbsp;</label>
                                            <input id="chartofaccount_search" type="text" class="search-input form-control">
                                        </div>
                                        <div class="col-6 col-md-8 pt-2 text-right">
                                            <a href="{{ route('coa.file')}}" class="btn btn-success">Download Xlsx</a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <hr>
                                            <div id="chartofaccount"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <hr>
                                            <button id='submit-json' class="btn btn-primary float-right">Save
                                                Changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Basic Tree -->
                    </div>

                </section>
            </div>
            <div id="treeData" data-tree='{{ $chartOfAccounts }}'>
            </div>
        </div>
    </div>

    <!-- Loader Modal -->
    <div id="fetch_records" class="modal fade text-left modal-primary" 
     data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Fetching Records</h5>
            </div>
            <div class="modal-body">
                <span>Please wait while the results are prepared.</span>
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="modal-footer">
                {{--<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>--}}
            </div>
            </div>
        </div>
    </div>
</div> 
    <div data-create="{{ route('create-level', ['parentId'=>'?']) }}" id="create-level">  </div>
    <div data-edit= "{{ route('edit-level', ['parentId'=>'?']) }}" id="edit-level">  </div>

    <!-- END: Content-->
@endSection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/extensions/ext-component-tree.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/jstree.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/fonts/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/toastr.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/extensions/ext-component-toastr.css') }}">

@endSection

@section('scripts')
    <script></script>
    <script src="{{ asset('app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/extensions/jstree.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/extensions/tree-config.js') }}"></script>

    <script>
        var result;
        var plugins = ['types', 'state', 'wholerow', 'search'];
        @authorize('update-coa', true)
            plugins.push('contextmenu', 'dnd'),
        @endauthorize
        @authorize('update-coa-level', true)
            plugins.push('dnd'),
        @endauthorize
        $(document).ready(function() {
            $('#submit-json').click(function() {
                json = $('#chartofaccount').jstree(true).get_json('#', {
                    'no_data': true,
                    'no_a_attr': false,
                    'no_li_attr': false,

                });
                // change property names for laravel nested package
                const getNewTree = (json) => json.map(({id, text, children, type}) => ({
                id,
                level: text ,
                type: type,
                children: getNewTree(children)
                }));

                json = getNewTree(json)
                console.log(json);
                data = JSON.stringify(json);

                $("#fetch_records").modal("show");
                //send call to rebuildTree method
                $.ajax({
                    url: '{{ route('coa.rebuild') }}',
                    method: 'POST',
                    data: {
                        node: {data,new_node, rename_node, move_parent},
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(obj) {
                        toastr['success']('Changes Has Been Published','Saved', {
                            closeButton: true,
                            tapToDismiss: false,
                        });
                        result = obj;
                    },
                    error: function(err) {
                        toastr['error'](err, 'Something Went Wrong', {
                            closeButton: true,
                            tapToDismiss: false,
                        });
                        console.log(err);
                    },
                    complete: function() {
                // Hide the loading modal when request is done
                $("#fetch_records").modal("hide");
            }
                })
            });
        });
    </script>
@endSection
