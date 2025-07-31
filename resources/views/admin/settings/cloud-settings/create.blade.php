@extends('admin.layouts.app')
@section('content')
<div class="app-content content ">
    <div class="content-wrapper">
        <div class="content-body">
            <x-toast :errors="$errors" />
            <!-- valuation input start -->
            <form class="form-validate" method="POST" action="{{ route('cloud.update') }}" autocomplete="off">
                <section class="currency-store">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills" role="tablist">
                                <h2>Cloud Setting</h2>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                                    @csrf
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="access_key">Access Key <span class="badge badge-danger">{{ $isExpired ? 'expired' : ''}}</span></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="access_key" placeholder="Access key" name="access_token" value="{{ $accessKeys->secret_key ?? '' }}" readonly>
                                                        @if (isset($accessKeys))
                                                            <div class="input-group-append">
                                                                <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('access_key')">
                                                                    <i data-feather="clipboard"></i>
                                                                    <i style="display: none" data-feather="check"></i>
                                                                </button>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="expiry_date">Expires At</label>
                                                    <input type="text" class="form-control" id="expiry_date_" name="access_token_expiry" value="{{ isset($accessKeys) ? Carbon\Carbon::parse($accessKeys->expires_at)->format(config('constant.datetime_format')) : null }}" readonly>


                                                </div>
                                            </div>
                                            <div class="col-md-2 mt-2">
                                                @if (isset($accessKeys) && !$isExpired)
                                                <button class="btn btn-danger" data-toggle="modal" data-target="#danger" id="revoke_key"  type="button" >Revoke</button>
                                                @else
                                                <a class="btn btn-success" href="{{ route('generate-access-key') }}" type="button" id="generate_key">Generate</a>
                                                @endif
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="access_key">Tenant ID</label>
                                                    <div class="input-group">
                                                    <input type="text" class="form-control" id="tenant_id" placeholder="Access key" name="rds_tentant_id" value="{{ $tenantID }}" readonly>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('tenant_id')">
                                                            <i data-feather="clipboard"></i>
                                                            <i style="display: none" data-feather="check"></i>
                                                         </button>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="access_key">Rds Database Name</label>
                                                    <input type="text" class="form-control" id="access_key" placeholder="Access key" name="rds_db_name" value="{{ $items['rds_db_name']['S'] ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="access_key">Rds User</label>
                                                    <input type="text" class="form-control" id="access_key" placeholder="Access key" name="rds_user" value="{{ $items['rds_user']['S'] ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="access_key">Rds Password</label>
                                                    <input type="text" class="form-control" id="access_key" placeholder="Access key" name="rds_password" value="{{ $items['rds_password']['S'] ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="access_key">Rds Host</label>
                                                    <input type="text" class="form-control" id="access_key" placeholder="Access key" name="rds_host" value="{{ $items['rds_host']['S'] ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="access_key">Rds Port</label>
                                                    <input type="text" class="form-control" id="access_key" placeholder="Access key" name="rds_port" value="{{ $items['rds_port']['S'] ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="access_key">Aws Bucket</label>
                                                    <input type="text" class="form-control" id="access_key" placeholder="Access key" name="bucket" value="{{ $items['bucket']['S'] ?? '' }}">
                                                </div>
                                            </div>

                                        </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                        <x-form-buttons textSubmit='Update Setting' />
                                    </div>
                                </div>
                            </div>
                     </div>
                    </div>
                </section>
            </form>
        </div>
    </div>
</div>
<div class="modal fade modal-danger text-left" id="danger" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel120" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel120">Revoke Key</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to revoke your key?
            </div>
            <form id="delete-user-form" action="{{ route('revoke-access-key')}}" method="post">
                @csrf
                @method('delete')
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endSection
@section('scripts')
<script>
    $(document).ready(function() {
            //Load road tenancy param
            updateUrl(`{{ Request::input('org') }}`);
     });
        function copyToClipboard(elementId) {
            var inputElement = document.getElementById(elementId);
            inputElement.select();
            inputElement.setSelectionRange(0, 99999);
            document.execCommand("copy");
            window.getSelection().removeAllRanges();
        }


        $('#generate_key').click(function(e){
            $(this).addClass('disabled')
            // e.preventDefault();
        })



</script>
@endsection
