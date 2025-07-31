@extends('user.layouts.app')
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
                            <h2>Edit Ticket Information</h2>
                        </ul>
                        <div class="tab-content">
                            <!-- Account Tab starts -->
                            <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                                <!-- users edit account form start -->
                                <form class="form-validate" method="POST" action="/contact/{{$ticket->id}}" autocomplete="off">
                                    @method('PUT')
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="username">Title</label>
                                                <input type="text" class="form-control" placeholder="Unable to create a user" value="{{$ticket->title}}" name="title" id="title" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="username">Message</label>
                                                <textarea class="form-control" name="message" id="ticketMessage" rows="3">{{$ticket->message}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="withdrawal_rate_id">Severity</label>
                                                <select class="select2 form-control" name="severity">
                                                    @foreach($severities as $severity)
                                                        <option value="{{strtolower($severity)}}" {{ ($ticket->severity == strtolower($severity)) ? 'selected="selected"' : '' }}>
                                                            {{$severity}}
                                                        </option>
                                                   @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                            <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">Save Changes</button>
                                        </div>
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
    </script>
    @endSection
