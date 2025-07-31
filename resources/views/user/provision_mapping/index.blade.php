@extends('user.layouts.app')

@section('content')
    <div class="app-content content ">
        <x-toast :errors="$errors" />
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-12 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Select Provision Mapping</h2>
                            <div class="breadcrumb-wrapper w-100">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">System Provision
                                    </li>
                                    <li class="breadcrumb-item"><a href="{{ route('provision-setting.index') }}">Provision Setting</a>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="dashboard-ecommerce">
                  <div class="bg-white border rounded text-uppercase text-center text-secondary py-1 mb-2 " style="font-size: 20px; font-weight: bold;">PAA</div>
                    <div class="row match-height">
              
                        <div class="col-md-4 col-sm-12 col-lg-4 col-xl-4">
                            <a href="{{ route('provision.treaty.create', ['provision' => CustomHelper::encode($provisionSetting->id)]) }}">
                                <div class="card">
                                    <div class="card-header {{$provisionSetting->reProvisionTreatyMappings && $count['reProductTreaty'] ? 'bg-success' : 'bg-warning'}} ">
                                        <!-- <h4 class="card-title text-white">Status: {{$provisionSetting->reProvisionTreatyMappings && $count['reProductTreaty'] ? 'Set' : 'Not-Set'}}</h4> -->
                                    </div>
                                    <div class="card-body">
                                        <div class="py-2">
                                            <h5><b>Product Treaty</b></h5>
                                            <h6>Re Inusrance</h6>
                                            <!-- <p class="text-muted pt-1">Select the appropirate groupings and assumptions for Reinsurance treaty groupings</p> -->
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 col-sm-12 col-lg-4 col-xl-4">
                            <a href="{{ route('provision.facultative.create', ['provision' => CustomHelper::encode($provisionSetting->id)]) }}">
                                <div class="card">
                                    <div class="card-header {{$provisionSetting->reProvisionFacultativeMappings && $count['reProductFacultative'] ? 'bg-success' : 'bg-warning'}}">
                                        <!-- <h4 class="card-title text-white">Status: {{$provisionSetting->reProvisionFacultativeMappings && $count['reProductFacultative'] ? 'Set' : 'Not-Set'}}</h4> -->
                                    </div>
                                    <div class="card-body">
                                        <div class="py-2">
                                            <h5><b>Product Facultative</b></h5>
                                            <h6>Re Inusrance</h6>
                                            <!-- <p class="text-muted pt-1">Select the appropirate mappings and assumptions for Re Inusrance facultative mappings</p> -->
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                            <div class="col-md-4 col-sm-12 col-lg-4 col-xl-4">
                            <a href="{{ route('provision.insurance.create', ['provision' => CustomHelper::encode($provisionSetting->id)]) }}">
                                <div class="card">
                                    <div class="card-header {{$provisionSetting->mappings && $count['ProductInsurance'] ? 'bg-success' : 'bg-warning'}}">
                                        <!-- <h4 class="card-title text-white">Status: {{$provisionSetting->mappings && $count['ProductInsurance'] ? 'Set' : 'Not-Set'}}</h4> -->
                                    </div>
                                    <div class="card-body">
                                        <div class="py-2">
                                            <h5><b>Product Insurance</b></h5>
                                            <h6>Inusrance</h6>
                                            <!-- <p class="text-muted pt-1">Select the appropirate mappings and assumptions for Inusrance facultative mappings</p> -->
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                          <div class="bg-white border rounded text-uppercase text-center text-secondary py-1 mb-2" style="font-size: 20px; font-weight: bold;">GMM</div>
                    <div class="row match-height">
                 
                          <div class="col-md-4 col-sm-12 col-lg-4 col-xl-4">
                            <a href="{{ route('provision.treaty_gmm.create', ['provision' => CustomHelper::encode($provisionSetting->id)]) }}">
                                <div class="card">
                                    <div class="card-header {{$provisionSetting->reProvisionTreatyMappings && $count['reProductTreaty'] ? 'bg-success' : 'bg-warning'}} ">
                                        <!-- <h4 class="card-title text-white">Status: {{$provisionSetting->reProvisionTreatyMappings && $count['reProductTreaty'] ? 'Set' : 'Not-Set'}}</h4> -->
                                    </div>
                                    <div class="card-body">
                                        <div class="py-2">
                                            <h5><b>Product Treaty</b></h5>
                                            <h6>Re Inusrance</h6>
                                            <!-- <p class="text-muted pt-1">Select the appropirate groupings and assumptions for Reinsurance treaty groupings</p> -->
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                           <div class="col-md-4 col-sm-12 col-lg-4 col-xl-4">
                            <a href="{{ route('provision.facultative_gmm.create', ['provision' => CustomHelper::encode($provisionSetting->id)]) }}">
                                <div class="card">
                                    <div class="card-header {{$provisionSetting->reProvisionFacultativeMappings && $count['reProductFacultative'] ? 'bg-success' : 'bg-warning'}}">
                                        <!-- <h4 class="card-title text-white">Status: {{$provisionSetting->reProvisionFacultativeMappings && $count['reProductFacultative'] ? 'Set' : 'Not-Set'}}</h4> -->
                                    </div>
                                    <div class="card-body">
                                        <div class="py-2">
                                            <h5><b>Product Facultative</b></h5>
                                            <h6>Re Inusrance</h6>
                                            <!-- <p class="text-muted pt-1">Select the appropirate mappings and assumptions for Re Inusrance facultative mappings</p> -->
                                            
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 col-sm-12 col-lg-4 col-xl-4">
                            <a href="{{ route('provision.insurance_gmm.create', ['provision' => CustomHelper::encode($provisionSetting->id)]) }}">
                                <div class="card">
                                    <div class="card-header {{$provisionSetting->mappings && $count['ProductInsurance'] ? 'bg-success' : 'bg-warning'}}">
                                        <!-- <h4 class="card-title text-white">Status: {{$provisionSetting->mappings && $count['ProductInsurance'] ? 'Set' : 'Not-Set'}}</h4> -->
                                    </div>
                                    <div class="card-body">
                                        <div class="py-2">
                                            <h5><b>Product Insurance</b></h5>
                                            <h6>Inusrance</h6>
                                            <!-- <p class="text-muted pt-1">Select the appropirate mappings and assumptions for Inusrance facultative mappings</p> -->
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                       <div class="bg-white border rounded text-uppercase text-center text-secondary py-1 mb-2" style="font-size: 20px; font-weight: bold;">Expense Allocation</div>
                    
                    <div class="row match-height">
                
                        <div class="col-md-4 col-sm-12 col-lg-4 col-xl-4">
                            <a href="{{ route('provision-setting.expense-allocation.create', ['provision_setting' => CustomHelper::encode($provisionSetting->id)]) }}">
                                <div class="card">
                                    <div class="card-header {{$provisionSetting->ExpenseAllocations && $count['ExpenseAllocation'] ? 'bg-success' : 'bg-warning'}}">
                                        <!-- <h4 class="card-title text-white">Status: {{$provisionSetting->ExpenseAllocations && $count['ExpenseAllocation']  ? 'Set' : 'Not-Set'}}</h4> -->
                                    </div>
                                    <div class="card-body">
                                        <div class="py-2">
                                            <h5><b>Expense Allocation</b></h5>
                                            <h6>Inusrance</h6>
                                            <!-- <p class="text-muted pt-1">Select the appropirate mappings and assumptions for Inusrance facultative mappings</p> -->
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
