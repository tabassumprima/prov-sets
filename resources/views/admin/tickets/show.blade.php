@extends('admin.layouts.app')
@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-12 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item">Others</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            @if($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger" role="alert">
                        <div class="alert-body">Error: {{ $error }}</div>
                    </div>
                @endforeach
            @endif

            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    <div class="alert-body">{!! session('error') !!}</div>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    <div class="alert-body">{!! session('success') !!}</div>
                </div>
            @endif

            <!-- Ticket Details Section -->
            <section class="app-user-edit">
                <div class="card">
                    <div class="card-body">
                        <h2 class="mb-3">Ticket Information</h2>

                        <form class="form-validate">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Reported By</label>
                                        <h6 class="form-control-plaintext">{{ $ticket->user->name }}</h6>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Reported At</label>
                                        <h6 class="form-control-plaintext">{{ date('d-m-Y', strtotime($ticket->created_at)) }}</h6>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Title</label>
                                        <h6 class="form-control-plaintext">{{ $ticket->title }}</h6>
                                    </div>
                                </div>
                            </div>



                            <div class="row">
                                <!-- Severity -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Severity</label>
                                        <span class="badge badge-pill
                                            {{ $ticket->severity == 'high' ? 'badge-light-danger' : ($ticket->severity == 'medium' ? 'badge-light-warning' : 'badge-light-info') }}">
                                            {{ ucfirst($ticket->severity) }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Status</label>
                                        <span class="badge badge-pill
                                            {{ $ticket->is_resolved ? 'badge-light-success' : 'badge-light-warning' }}">
                                            {{ $ticket->is_resolved ? 'Resolved' : 'Pending' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Message -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Message</label>
                                        <h6 class="form-control-plaintext">{{ $ticket->message }}</h6>
                                    </div>
                                </div>

                            </div>
                            <hr>

                            @if(!$ticket->is_resolved)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <a class="btn add-new btn-primary waves-effect waves-float waves-light" href="{{route('tickets.status-update', CustomHelper::encode($ticket->id))}}">Mark as Resolved</a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
