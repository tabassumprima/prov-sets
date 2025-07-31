@extends('user.layouts.app')

@section('content')
    <div class="app-content content ">
        <x-toast :errors="$errors" />
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-12 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Select Reinsurance Grouping</h2>
                            <div class="breadcrumb-wrapper w-100">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a>
                                    </li>
                                    @if ($group->applicable_to == 'insurance')
                                        <li class="breadcrumb-item">Insurance</li>
                                    @else
                                        <li class="breadcrumb-item">Reinsurance</li>
                                    @endif
                                    <li class="breadcrumb-item"><a href="{{ route('group.index', ['type' => $group->applicable_to]) }}">Groups</a>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="dashboard-ecommerce">
                    <div class="row match-height">
                        <div class="col-md-4 col-sm-12 col-lg-4 col-xl-4">
                            <a href="{{ route('treaty.create', ['group' => CustomHelper::encode($group->id)]) }}">
                                <div class="card">
                                    <div class="card-header {{$group->treaty ? 'bg-success' : 'bg-warning'}}">
                                        <h4 class="card-title text-white">Status: {{$group->treaty ? 'Set' : 'Not-Set'}}</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="py-2">
                                            <h5><b>Treaty Groupings</b></h5>
                                            <p class="text-muted pt-1">Select the appropirate groupings and assumptions for Reinsurance treaty groupings</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 col-sm-12 col-lg-4 col-xl-4">
                            <a href="{{ route('facultative.create', ['group' => CustomHelper::encode($group->id)]) }}">
                                <div class="card">
                                    <div class="card-header {{$group->facultative ? 'bg-success' : 'bg-warning'}}">
                                        <h4 class="card-title text-white">Status: {{$group->facultative ? 'Set' : 'Not-Set'}}</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="py-2">
                                            <h5><b>Facultative Groupings</b></h5>
                                            <p class="text-muted pt-1">Select the appropirate groupings and assumptions for Reinsurance facultative groupings</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endSection
@section('page-css')
    <livewire:styles />
@endsection
@section('scripts')
    <livewire:scripts />
    <script src="{{ asset('app-assets/js/scripts/components/components-modals.js') }}"></script>
@endSection
