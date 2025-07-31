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
                <div class="alert" style="display: none;" role="alert">
                    <div class="alert-body">

                    </div>
                </div>
                <!-- users edit start -->
                <section class="app-user-edit">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills" role="tablist">
                                <h2>Edit Config</h2>
                            </ul>
                            <div class="tab-content">
                                <!-- Account Tab starts -->
                                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                                    <!-- users edit account form start -->
                                    <form class="form-validate" method="POST"
                                        action="{{ route('import-detail-configs.update', [CustomHelper::encode($config_model->id)]) }}"
                                        autocomplete="off">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <input type="hidden" name='type' id="type" />
                                                        <div class="form-group">
                                                            <label for="username" class="required">Config</label>
                                                            <textarea type="text" class="form-control" placeholder="config" name="config" id="config">{{!!$config!!}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                               <x-form-buttons textSubmit="Save Changes" />
                                               <button type="submit" id="import" data-type='import' class="btn btn-primary">Import</button>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- users edit account form ends -->
                                </div>
                                <!-- Account Tab ends -->
                            </div>
                        </div>
                    </div>
                </section>
                <!-- users edit ends -->

            </div>
        </div>
    </div>
@endSection

@section('scripts')
<script>
    $(document).ready(function() {
        $('button[type=submit]').click(function(){
            $('#type').val($(this).data('type'))
        })

    });
</script>
@endSection
