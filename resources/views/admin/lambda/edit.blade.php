@extends('admin.layouts.app')
@section('content')
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            @if($errors->any())
            @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                <div class="alert-body">
                    Error: {{ $error }}
                </div>
            </div>
            @endforeach
            @endif
            @if (\Session::has('error'))
            <div class="alert alert-danger" role="alert">
                <div class="alert-body">
                    {!! \Session::get('error') !!}
                </div>
            </div>
            @endif
            @if (\Session::has('success'))
            <div class="alert alert-success" role="alert">
                <div class="alert-body">
                    {!! \Session::get('success') !!}
                </div>
            </div>
            @endif
            <!-- users edit start -->
            <section class="app-user-edit">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-pills" role="tablist">
                            <h2>Edit Function</h2>
                        </ul>
                        <div class="tab-content">
                            <!-- Account Tab starts -->
                            <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                                <!-- users edit account form start -->
                                <form class="form-validate" method="POST" action="{{ route('lambda.update', ["lambda" => $function->id])}}" autocomplete="off">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label" for="basic-icon-default-fullname">
                                                    Name
                                                </label>
                                                <input type="text" class="form-control dt-full-name" id="basic-icon-default-fullname" placeholder="KSA-EOSB-XX" name="name" value="{{ $function->name}}" required />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label required" for="basic-icon-default-email">Command</label>
                                                <select id="command" name="command"
                                                    class="select2 form-control" required>
                                                    @if (isset($commands) && !empty($commands))
                                                        @foreach ($commands->lambda_function_commands as $command)
                                                            <option value="{{ $command->name }}"
                                                                {{ $function->command == $command->name ? 'selected="selected"' : '' }}>
                                                                {{ $command->name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="status">Status</label>
                                                <select class="form-control" id="is_active" name="is_active">
                                                    <option value="1"
                                                        {{ $function->is_active == 1 ? 'selected="selected"' : '' }}>
                                                        Enabled</option>
                                                    <option value="0"
                                                        {{ $function->is_active == 0 ? 'selected="selected"' : '' }}>
                                                        Disabled</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="basic-icon-default-email">Config</label>
                                                <textarea type="text" id="basic-icon-default-email" class="form-control dt-email" placeholder="EOSB-Rule-KSA" aria-label="john.doe@example.com" value="" aria-describedby="basic-icon-default-email2" name="config" required >{{ $function->config}} </textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                        <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">Save Changes</button>
                                    </div>
                                </form>
                                <!-- users edit account form ends -->

                            </div>

                            <!-- Account Tab ends -->
                        </div>
                    </div>
            </section>
            <!-- users edit ends -->
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
</script>
@endSection
