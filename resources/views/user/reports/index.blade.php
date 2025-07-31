@extends('user.layouts.app')

@section('content')
    <div class="app-content content ">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Reports</h2>
                            <div class="breadcrumb-wrapper w-100">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Reports
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="dashboard-ecommerce">
                    <div class="row py-1">
                        <div class="col-md-12">
                            <h4>IFRS17 Reports</h4>
                        </div>
                    </div>
                    <div class="row match-height">
                        <div class="col-md-3 col-sm-12 col-lg-3 col-xl-3">
                            <a href="{{ route('report.index',['slug' => 'PNL']) }}">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="text-center py-2">
                                            <h5><b>Profit & Loss</b></h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-12 col-lg-3 col-xl-3">
                            <a href="{{ route('report.index',['slug' => 'BS']) }}">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="text-center py-2">
                                            <h5><b>Financial Position</b></h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-12 col-lg-3 col-xl-3">
                            <a href="{{ route('disclosure.index') }}">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="text-center py-2">
                                            <h5><b>Disclosure</b></h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="row py-1">
                        <div class="col-md-12">
                            <h4>Statutory Reporting</h4>
                        </div>
                    </div>
                    <div class="row match-height">
                        <div class="col-md-3 col-sm-12 col-lg-3 col-xl-3">
                            <a href="{{ route('report.index',['slug' => 'SOP']) }}">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="text-center py-2">
                                            <h5><b>Statement of Premiums</b></h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-12 col-lg-3 col-xl-3">
                            <a href="{{ route('report.index',['slug' => 'SOC']) }}">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="text-center py-2">
                                            <h5><b>Statement of Claims</b></h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-12 col-lg-3 col-xl-3">
                            <a href="{{ route('report.index',['slug' => 'SOE']) }}">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="text-center py-2">
                                            <h5><b>Statement of Expenses</b></h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="row py-1">
                        <div class="col-md-12">
                            <h4>IFRS17 Disclosure</h4>
                        </div>
                    </div>
                    <div class="row match-height">
                    <div class="col-md-3 col-sm-12 col-lg-3 col-xl-3">
                        <a href="{{ route('report.index',['slug' => 'LRC']) }}">
                                <div class="card" style="height:102px;">
                                    <div class="card-body">
                                        <div class="text-center py-2">
                                            <h5><b>Liability for Remaining Coverage</b></h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-12 col-lg-3 col-xl-3">
                        <a href="{{ route('report.index',['slug' => 'LIC']) }}">
                                <div class="card" style="height:102px;">
                                    <div class="card-body">
                                        <div class="text-center py-2">
                                            <h5><b>Liability for Incurred Claims </b></h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-12 col-lg-3 col-xl-3">
                        <a href="{{ route('report.index',['slug' => 'BREAKUP']) }}">
                                <div class="card" style="height:102px;">
                                    <div class="card-body">
                                        <div class="text-center py-2">
                                            <h5><b>Breakup of Insurance Liabilities / Assets</b></h5>
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
