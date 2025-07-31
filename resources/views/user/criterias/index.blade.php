@extends('user.layouts.app')

@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-12 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <div class="breadcrumb-wrapper w-100">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a>
                                    </li>
                                    @if (Request::is('criteria/insurance'))
                                        <li class="breadcrumb-item">Insurance</li>
                                    @else
                                        <li class="breadcrumb-item">Reinsurance</li>
                                    @endif
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="content-body">
                <x-toast :errors="$errors" />

                <!-- Basic tabs start -->
                <section id="basic-tabs-components">
                    <div class="row match-height">
                        <!-- Basic Tabs starts -->
                        <div class="col-xl-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row w-100">
                                        <div class="col-6 text-left">
                                            <h2>Portfolio Criteria {{ Str::title($type) }}</h2>
                                        </div>
                                        @authorize('create-'.$type.'-portfolio-criteria',true)
                                        <div class="col-6 text-right">
                                            <button class="btn add-new btn-primary" tabindex="0" type="button"
                                                data-toggle="modal" data-target="#modals-slide-in"
                                                {{ Session::has('active_provision') ? 'disabled' : '' }}><i
                                                    data-feather='plus'></i><span> Add new criteria</span></button>

                                        </div>
                                        @endauthorize
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive pl-1 pr-1">
                                        <table class="table data-table">
                                            <thead>
                                                <tr>
                                                    <th>S.no</th>
                                                    <th>Criteria name</th>
                                                    <th>Criteria description</th>
                                                    <th>Applicable To</th>
                                                    <th>Start date</th>
                                                    <th>End date</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($criterias as $criteria)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td> <a href='{{ route('portfolio.showMapping', $criteria->id) }}'>
                                                                {{ $criteria->name }} </a></td>
                                                        <td>{{ $criteria->description }}</td>
                                                        <td>{{ Str::title($criteria->applicable_to) }}</td>
                                                        <td>{{ Carbon\Carbon::parse($criteria->start_date)->format(config('constant.date_format.get')) }}
                                                        </td>
                                                        <td>{{ $criteria->end_date ? Carbon\Carbon::parse($criteria->end_date)->format(config('constant.date_format.get')) : '-' }}
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="badge badge-{{ $criteria->status->color }}">{{ $criteria->status->title }}</span>
                                                        </td>
                                                        <td>
                                                            @authorize('delete-'.$type.'-portfolio-criteria',true)
                                                            <a data-route="{{ route('criterias.destroy', $criteria->id) }}"
                                                                class="delete"><i data-feather="trash-2"
                                                                    style="color: {{ $criteria->status->slug == 'started' || $criteria->status->slug == 'expired' ? 'grey' : 'red' }} "></i></a>
                                                            @endauthorize
                                                        </td>
                                                @endforeach
                                            </tbody>
                                        </table>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- Basic Tabs ends -->
                    </div>
                    <!-- Modal to add new user starts-->
                    <div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in">
                        <div class="modal-dialog">
                            <form class="add-new-user modal-content pt-0" method="post"
                                action="{{ route('criterias.store') }}">
                                @csrf
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                                <div class="modal-header mb-1">
                                    <h5 class="modal-title" id="exampleModalLabel">New {{ $type }} Portfolio
                                        Criteria</h5>
                                </div>
                                <div class="modal-body flex-grow-1">
                                    <div class="form-group">
                                        <label class="form-label required" for="group-name">Criteria name</label>
                                        <input type="text" class="form-control" id="group-name"
                                            placeholder="New-criteria" name="name" aria-label="group-name" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label required" for="group-desc">Criteria description</label>
                                        <input type="text" id="group-desc" class="form-control"
                                            placeholder="This is the new portfolio criteria set" aria-label="group-desc"
                                            name="description" maxlength="70" />
                                    </div>
                                    <input class="form-control" id="applicable_to" name="applicable_to"
                                        value="{{ $type }}" hidden readonly />
                                    <div class="form-group position-relative">
                                        <label class="form-label required" for="group-desc">Start Date</label>
                                        <input type="text" id="applicable-date" name="start_date"
                                            class="form-control datepicker" placeholder="31 Dec, 2021" />
                                    </div>
                                    <x-form-buttons textSubmit="Submit" textCancel="Cancel" />
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Modal to add new user Ends-->
                </section>
                <!-- Modal -->
                <div class="modal fade modal-danger text-left" id="danger" tabindex="-1" role="dialog"
                    aria-labelledby="myModalLabel120" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="myModalLabel120">Delete User</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete?
                            </div>
                            <form id="delete-user-form" method="post">
                                @csrf
                                @method('delete')
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-danger">Yes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Modal -->
            </div>
        </div>
    </div>
    <!-- END: Content-->
@endSection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/pickadate/pickadate.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets/css/plugins/forms/pickers/form-pickadate.min.css') }}">
@endSection

@section('scripts')
    <script src="{{ asset('app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/forms/pickers/form-pickers.min.js') }}"></script>

    <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/forms/form-select2.js') }}"></script>
    <script>
        $(document).ready(function() {
            var isImpersonating = Boolean(Number('{{ is_impersonating() }}'));
            var insurance = '{{ $dateStartFrom->insurance }}';
            var reInsurance = '{{ $dateStartFrom->reInsurance }}';
            var groupInsurance  = '{{ $dateStartFrom->groupInsurance }}';
            var groupReInsurance  = '{{ $dateStartFrom->groupReInsurance }}';
            var isBoarding = Boolean(Number('{{ (bool) $isBoarding }}'));
            var currentDate = ''

            function test_function(type) {}

            function checkDate(date, type = '', groupdate) {
                // Check if date new date is empty (first criteria) then allow future dates
                if (isBoarding && isImpersonating) {
                    if ((type == 'insurance' && insurance == '') || (type == 're-insurance' && reInsurance == '')) {
                        return '';
                    }
                    else{
                        if (groupdate != ""){
                            groupdate = new Date(groupdate);
                            groupdate.setDate(groupdate.getDate() + 1);
                            return groupdate
                        }
                        else{

                            return new Date(date)
                        }

                    }
                }
                else{
                    previous_date = new Date(date)
                    today_date = new Date();
                    groupdate = new Date(groupdate);
                    if (groupdate != "" && groupdate > today_date ){
                            groupdate.setDate(groupdate.getDate() + 1);
                            return groupdate
                        }
                    console.log(today_date > previous_date)
                    if(today_date > previous_date){
                        date = today_date;
                        date.setDate(date.getDate() + 1)
                    }
                    else {
                        date = previous_date
                    }
                    currentDate = date;
                    return new Date(currentDate)
                }
            }

            $('.datepicker').pickadate({
                selectYears: 100,
                selectMonths: true,
                today: '',
                format: 'mmm dd, yyyy'
            })
            checkType('{{$type}}')

            $("#applicable_to").on('change', function() {
                checkType(this.value)
            });

            function checkType(type) {
                if (type == 'insurance') {
                    date = "{{ $dateStartFrom->insurance }}"
                    groupdate = "{{ $dateStartFrom->groupInsurance }}"
                }
                else {
                    date = "{{ $dateStartFrom->reInsurance }}"
                    groupdate = "{{ $dateStartFrom->groupReInsurance }}"
                }
                setMinValue(date, type, groupdate)
            }

            function setMinValue(date, type, groupdate) {
                min_date = checkDate(date, type, groupdate)
                $('.datepicker').pickadate('picker').set('min', min_date)
                $('.datepicker').pickadate('picker').set('select', min_date);
            }
        });



        $(".delete").on('click', function() {
            $("#danger").modal();
            route = $(this).data('route');
            document.getElementById('delete-user-form').action = route;
        });
    </script>
@endSection
