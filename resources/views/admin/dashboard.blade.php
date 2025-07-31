@extends('admin.layouts.app')

@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- Dashboard Ecommerce Starts -->
                <section id="dashboard-ecommerce">
                    <div class="row match-height">
                        <!-- Statistics Card -->
                        <div class="col-xl-12 col-md-6 col-12">
                            <div class="card card-statistics">
                                <div class="card-header">
                                    <h4 class="card-title">Statistics</h4>
                                </div>
                                <div class="card-body statistics-body">
                                    <div class="row">
                                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                            <div class="media">
                                                <div class="avatar bg-light-primary mr-2">
                                                    <div class="avatar-content">
                                                        <i data-feather="trending-up" class="avatar-icon"></i>
                                                    </div>
                                                </div>
                                                <div class="media-body my-auto">
                                                    <h4 class="font-weight-bolder mb-0">230k</h4>
                                                    <p class="card-text font-small-3 mb-0">Sales</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                            <div class="media">
                                                <div class="avatar bg-light-info mr-2">
                                                    <div class="avatar-content">
                                                        <i data-feather="user" class="avatar-icon"></i>
                                                    </div>
                                                </div>
                                                <div class="media-body my-auto">
                                                    <h4 class="font-weight-bolder mb-0">8.549k</h4>
                                                    <p class="card-text font-small-3 mb-0">Customers</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0">
                                            <div class="media">
                                                <div class="avatar bg-light-danger mr-2">
                                                    <div class="avatar-content">
                                                        <i data-feather="box" class="avatar-icon"></i>
                                                    </div>
                                                </div>
                                                <div class="media-body my-auto">
                                                    <h4 class="font-weight-bolder mb-0">1.423k</h4>
                                                    <p class="card-text font-small-3 mb-0">Products</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-sm-6 col-12">
                                            <div class="media">
                                                <div class="avatar bg-light-success mr-2">
                                                    <div class="avatar-content">
                                                        <i data-feather="dollar-sign" class="avatar-icon"></i>
                                                    </div>
                                                </div>
                                                <div class="media-body my-auto">
                                                    <h4 class="font-weight-bolder mb-0">$9745</h4>
                                                    <p class="card-text font-small-3 mb-0">Revenue</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Statistics Card -->
                    </div>
                        <div class="col-12 col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Provision calculation</h4>
                                </div>
                                <form action="{{ route('importData.lambda') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="type" value="provision">
                                    <div class="card-body">
                                        <p>Calculate provision as at the specified date. Last calculation was carried out on
                                            <span class="text-primary">{{ $lastProvision }}</span>
                                        </p>
                                        <div class="row">
                                            <div class="col-12 col-lg-8 pt-1 position-relative">
                                                <input type="text" id="pd-disable" class="form-control pickadate-disable"
                                                    placeholder="{{ $lastSync }}" readonly />
                                            </div>
                                            <div class="col-12 col-lg-4 position-relative pt-1">

                                                <button type="submit" class="btn btn-primary" data-toggle="modal"
                                                    id="onshownbtn" data-target="#onshown"
                                                    {{ $provisionAllowed ? '' : 'disabled' }}>
                                                    <i data-feather='refresh-cw'></i>
                                                    &nbsp; Run
                                                </button>
                                            </div>
                                            {{-- <div class="shown-event-ex">
                                                <!-- Button trigger modal -->
                                                <!-- Modal -->
                                                <div class="modal fade text-left" id="onshown" tabindex="-1"
                                                    role="dialog" aria-labelledby="myModalLabel22" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myModalLabel22">Provision
                                                                    calculations</h4>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p class="counter text-center h3 py-1">0</p>
                                                                <div class="progress progress-bar-primary">
                                                                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                                        role="progressbar" aria-valuenow="25"
                                                                        aria-valuemin="25" aria-valuemax="100">

                                                                    </div>

                                                                </div>
                                                                <p class="updates text-center pt-1"></p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-outline-primary"
                                                                    data-dismiss="modal">Cancel</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> --}}
                                            <!-- Shown Event End -->
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title" >Boarding</h4>
                                    <h6>Status: <span class="badge badge-{{ $organization->isBoarding ? 'danger' : 'success' }}"> {{ $organization->isBoarding ? 'Allowed' : 'Not Allowed' }}</span></h6>
                                </div>
                                <div class="card-body">
                                    <p>Allow admins to setup organization for the first time. This option is recommended for one time use only</p>
                                    <div class="row">
                                        <div class="col-12 col-lg-4 position-relative pt-1">
                                            <button type="submit" class="btn btn-primary" onclick="verificationModal('Boarding', 'Boarding', '')" id="onshownbtn" >
                                                {{ $organization->isBoarding ? 'Restrict Boarding' : 'Allow Boarding' }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Dashboard Ecommerce ends -->
                <!-- Modal -->
                <div class="modal fade text-left" id="inlineForm" tabindex="-1" role="dialog"
                aria-labelledby="myModalLabel33" aria-hidden="true">
               <div class="modal-dialog modal-dialog-centered" role="document">
                   <div class="modal-content">
                       <div class="modal-header">
                           <h4 class="modal-title" id="verification_modal_title"></h4>
                           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                               <span aria-hidden="true">&times;</span>
                           </button>
                       </div>
                       <form action="{{route('organization.boarding')}}" id="verification_form" method="POST">
                           @csrf
                           <div class="modal-body">
                               <div class="form-group">
                                   <span id="verification_modal_message"></span>
                                   <h5><br/>Please type <i id="vf_code" style="color:red; user-select: none;"></i> in the following text box to
                                       proceed </h5>
                               </div>

                               <div class="form-group">
                                   <input type="input" id="vf_code_input" placeholder="Verification Code"
                                          class="form-control" oninput="verifyCode()"/>
                               </div>
                           </div>
                           <div class="modal-footer">
                               <button type="submit" id="btn_verification" class="btn btn-primary" onclick="signedReport(this.id)" disabled></button>
                               <button class="btn btn-outline-primary" type="button" id="btn_loading" disabled style="display: none">
                                   <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                   <span class="ml-25 align-middle">Preparing Signed Report...</span>
                               </button>
                           </div>
                       </form>
                   </div>
               </div>
           </div>
                <!-- Modal -->
            </div>
        </div>
    </div>
@endSection
@section('page-css')
<livewire:styles />
@endsection
@section('scripts')
<livewire:scripts />
<script>
        $(document).ready(function() {
            updateUrl(`{{ Request::input('org') }}`);     //Load road tenancy param
        });
        var vf_code = document.getElementById('vf_code');
        var vf_code_input = document.getElementById('vf_code_input');
        var btn_verification = document.getElementById('btn_verification');
        var verification_modal_title = document.getElementById('verification_modal_title');
        var verification_modal_message = document.getElementById('verification_modal_message');
        var btn_loading = document.getElementById('btn_loading');

        function verificationModal($type, $btn_type, $my_message) {
            verification_modal_title.innerText = $type;
            btn_verification.innerText = $btn_type;
            verification_modal_message.innerText = $my_message;
            let r = (Math.random() + 1).toString(36).substring(7);
            vf_code_input.value = "";
            vf_code.innerText = r;
            $("#inlineForm").modal({backdrop: 'static', keyboard: false});
        }

        function verifyCode() {
            console.log("im here" + vf_code_input.value + " " + vf_code.innerText);
            if (vf_code_input.value == vf_code.innerText) {
                btn_verification.disabled = false;
            } else {
                btn_verification.disabled = true;
            }
        }

</script>
@endSection
