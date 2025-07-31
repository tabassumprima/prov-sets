@extends('user.layouts.app')

@section('content')
    <h1>Create New Node</h1>

    <!-- Display validation errors -->


    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-12 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Chart of Accounts</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item">Accounting</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <x-toast :errors="$errors" />
                <section class="app-user-edit">
                    <div class="card">
                        <div class="card-body">
                            <h2>Create Level of "{{ $level_name }}"</h2>
                            <form class="form-validate" method="POST" action="{{ route('store-level', ['parentId' => $parentId]) }}" autocomplete="off">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="required" for="dl_code">DL Code</label>
                                            <input type="text" class="form-control" placeholder="DL10110" name="dl_code" id="dl_code" >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="required" for="level">Level Name</label>
                                            <input type="text" class="form-control" placeholder="Level Name" name="level" id="level" >
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label required" for="group-desc"> Category </label>
                                            <select class="form-control select2" id="category" name="category">
                                                @foreach (config('constant.categories') as $key => $type)
                                                    <option value="{{ $key }}">{{ $type }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                        <button type="submit" class="btn btn-primary mr-1 data-submit waves-effect waves-float waves-light">
                                            Save Changes
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
