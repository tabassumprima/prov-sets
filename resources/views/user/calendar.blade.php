@extends('user.layouts.app')

@section('content')
    <div class="app-content content ">
        <div class="card app-calendar-wrapper">
            <div class="app-calendar overflow-hidden border">
                <div class="row no-gutters">
                    <!-- Sidebar -->
                    <div class="col app-calendar-sidebar flex-grow-0 overflow-hidden d-flex flex-column" id="app-calendar-sidebar">
                        <div class="sidebar-wrapper">
                            <div class="card-body pb-0">
                                <h5 class="section-label mb-1">
                                    <span class="align-middle">Filter</span>
                                </h5>
                                <div class="custom-control custom-checkbox mb-1">
                                    <input type="checkbox" class="custom-control-input select-all" id="select-all" checked />
                                    <label class="custom-control-label" for="select-all">View All</label>
                                </div>
                                <div class="calendar-events-filter">
                                    @foreach ($calendars as $key => $calendarData)
                                    <div class="custom-control custom-control-{{$calendarData->color}} custom-checkbox mb-1">
                                        <input type="checkbox" class="custom-control-input input-filter" data-value="{{$calendarData->type}}" id="calendar_{{$key}}" checked />
                                        <label class="custom-control-label" for="calendar_{{$key}}">{{$calendarData->title}}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Sidebar -->

                    <!-- Calendar -->
                    <div class="col position-relative">
                        <div class="card shadow-none border-0 mb-0 rounded-0">
                            <div class="card-body pb-0">
                                <div id="calendar"></div>
                            </div>
                        </div>
                    </div>
                    <!-- /Calendar -->
                    <div class="body-content-overlay"></div>
                </div>
            </div>
        </div>
    </div>
@endSection
@section('page-css')

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/calendars/fullcalendar.min.css') }}">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/app-calendar.css') }}">
    <!-- END: Page CSS-->

@endSection


@section('scripts')
    <script>
        var calendarDataUrl = '{{ route('calendar.show') }}';
        var csrfToken = '{{ csrf_token() }}';
    </script>

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('app-assets/vendors/js/calendar/helpers.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/calendar/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/extensions/moment.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/calendar/main.js') }}"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Page JS-->
    <script src="{{ asset('app-assets/js/scripts/pages/app-calendar.js') }}"></script>
    <!-- END: Page JS-->
@endSection
