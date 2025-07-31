@extends('admin.layouts.app')

@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Provision Rules</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">Upload Rules</a>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <x-toast :errors="$errors" />
                <section>
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills" role="tablist">
                                <h2>Discount Rate</h2>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                                    <form class="form-validate" method="POST" action="{{ route('provision-rules.store') }}"
                                        enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Upload Json File</label>
                                                            <input type="hidden" value="discount_rates" name="type">
                                                            <div class="custom-file">
                                                                <input type="file"name="rule_file"
                                                                    class="custom-file-input">
                                                                <label class="custom-file-label">Choose file</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <a href="{{ route('provison_rule.file', ['module' => 'provision_rules', 'file' => 'discount_rates']) }}">
                                                                <i data-feather='download'></i>
                                                                Download File
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                                <x-form-buttons textSubmit="Save Changes" />
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section>
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills" role="tablist">
                                <h2>Risk Adjustment</h2>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                                    <form class="form-validate" method="POST" action="{{ route('provision-rules.store') }}"
                                        enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Upload Json File</label>
                                                            <input type="hidden" value="risk_adjustments" name="type">
                                                            <div class="custom-file">
                                                                <input type="file" name="rule_file"
                                                                    class="custom-file-input">
                                                                <label class="custom-file-label">Choose file</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <a href="{{ route('provison_rule.file', ['module' => 'provision_rules', 'file' => 'risk_adjustments']) }}">
                                                                <i data-feather='download'></i>
                                                                Download File
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                                <x-form-buttons textSubmit="Save Changes" />
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section>
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills" role="tablist">
                                <h2>IBNR Assumption</h2>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                                    <form class="form-validate" method="POST" action="{{ route('provision-rules.store') }}"
                                        enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Upload Json File</label>
                                                            <input type="hidden" value="ibnr_assumptions" name="type">
                                                            <div class="custom-file">
                                                                <input type="file" name="rule_file"
                                                                    class="custom-file-input">
                                                                <label class="custom-file-label">Choose file</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <a href="{{ route('provison_rule.file', ['module' => 'provision_rules', 'file' => 'ibnr_assumptions']) }}">
                                                                <i data-feather='download'></i>
                                                                Download File
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                                <x-form-buttons textSubmit="Save Changes" />
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section>
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills" role="tablist">
                                <h2>Claim Pattern</h2>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="account" aria-labelledby="account-tab"
                                    role="tabpanel">
                                    <form class="form-validate" method="POST"
                                        action="{{ route('provision-rules.store') }}" enctype="multipart/form-data"
                                        autocomplete="off">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Upload Json File</label>
                                                            <input type="hidden" value="claim_patterns" name="type">
                                                            <div class="custom-file">
                                                                <input type="file" name="rule_file"
                                                                    class="custom-file-input">
                                                                <label class="custom-file-label">Choose file</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <a href="{{ route('provison_rule.file', ['module' => 'provision_rules', 'file' => 'claim_patterns']) }}">
                                                                <i data-feather='download'></i>
                                                                Download File
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                                <x-form-buttons textSubmit="Save Changes" />
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section>
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills" role="tablist">
                                <h2>Lambda Command</h2>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="account" aria-labelledby="account-tab"
                                    role="tabpanel">
                                    <form class="form-validate" method="POST"
                                        action="{{ route('provision-rules.store') }}" enctype="multipart/form-data"
                                        autocomplete="off">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Upload Json File</label>
                                                            <input type="hidden" value="lambda_commands" name="type">
                                                            <div class="custom-file">
                                                                <input type="file" name="rule_file"
                                                                    class="custom-file-input">
                                                                <label class="custom-file-label">Choose file</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <a href="{{ route('provison_rule.file', ['module' => 'provision_rules', 'file' => 'lambda_commands']) }}">
                                                                <i data-feather='download'></i>
                                                                Download File
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                                <x-form-buttons textSubmit="Save Changes" />
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section>
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills" role="tablist">
                                <h2>Graph Json</h2>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="account" aria-labelledby="account-tab"
                                    role="tabpanel">
                                    <form class="form-validate" method="POST"
                                        action="{{ route('graph_json.file') }}" enctype="multipart/form-data"
                                        autocomplete="off">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Upload Json File</label>
                                                            <input type="hidden" value="new_graph" name="type">
                                                            <div class="custom-file">
                                                                <input type="file" name="rule_file"
                                                                    class="custom-file-input">
                                                                <label class="custom-file-label">Choose file</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <a href="{{ route('provison_rule.file', ['module' => 'dashboard']) }}">
                                                                <i data-feather='download'></i>
                                                                Download File
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                                <x-form-buttons textSubmit="Save Changes" />
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endSection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            updateUrl(`{{ Request::input('org') }}`);
        });
    </script>
@endSection
