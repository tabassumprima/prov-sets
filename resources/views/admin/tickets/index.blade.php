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
            <!-- contact list start -->
            <section class="app-user-list">
                <!-- list section start -->
                <div class="card">
                    <!-- BEGIN: Tickets Table -->
                    @include('admin.partials.tickets')
                    <!-- END: Tickets Table -->
                </div>
                <!-- list section end -->
            </section>
            <!-- contact list ends -->
        </div>
    </div>
</div>
@endSection

@section('scripts')
@endSection
