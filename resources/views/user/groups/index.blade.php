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
                                    @if (Request::is('group/insurance'))
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
                    <div class="match-height">
                        <!-- Basic Tabs starts -->
                        <div class="col-xl-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row w-100">
                                        <div class="col-6 text-left">
                                            <h2>Product Grouping ({{ Str::title($type) }})</h2>
                                        </div>

                                        <div class="col-6 text-right">
                                            @authorize('create-'.$type.'-group',true)
                                            <button class="btn add-new btn-primary" tabindex="0" type="button"
                                                data-toggle="modal" data-target="#modals-slide-in"
                                                {{ Session::has('active_provision') ? 'disabled' : '' }}><i
                                                    data-feather='plus'></i><span> Add new group</span></button>
                                            @endauthorize
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive pl-1 pr-1">
                                        <table class="table data-table">
                                            <thead>
                                                <tr>
                                                    <th>S.no</th>
                                                    <th>Group name</th>
                                                    <th>Group description</th>
                                                    <th>Criteria</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($portfolioGroups as $group)
                                                    <tr>

                                                        <td>{{ $loop->iteration }}</td>
                                                        @if ($group->applicable_to == 're-insurance')
                                                            <td>
                                                                <a
                                                                    href='{{ route('groups.re-insurance.index', CustomHelper::encode($group->id)) }}'>
                                                                    {{ $group->name }}
                                                                </a>
                                                            </td>
                                                        @else
                                                            <td>
                                                                <a
                                                                    href='{{ route('groups.products.create', CustomHelper::encode($group->id)) }}'>
                                                                    {{ $group->name }}
                                                                </a>
                                                            </td>
                                                        @endif
                                                        <td>{{ $group->description }}</td>
                                                        <td>{{ $group->criteria->name }}</td>
                                                        <td>{{ Carbon\Carbon::parse($group->start_date)->format(config('constant.date_format.get')) }}
                                                        </td>
                                                        <td>{{ $group->end_date ? Carbon\Carbon::parse($group->end_date)->format(config('constant.date_format.get')) : '-' }}
                                                        </td>
                                                        <td><span
                                                                class="badge badge-{{ $group->status->color }}">{{ $group->status->title }}</span>
                                                        </td>
                                                        <td>
                                                            @authorize('delete-'.$group->applicable_to.'-group',
                                                            true)
                                                            <a data-route="{{ route('groups.destroy', CustomHelper::encode($group->id)) }}"
                                                                class="delete"><i data-feather="trash-2"
                                                                    style="color: {{ $group->status->slug == 'started' || $group->status->slug == 'expired' ? 'grey' : 'red' }} "></i></a>
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
                            <form class="add-new-user modal-content pt-0" action="{{ route('groups.store') }}"
                                method="post">
                                @csrf
                                <input id="applicable_to" name="applicable_to" value="{{ $type }}" hidden />
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                                <div class="modal-header mb-1">
                                    <h5 class="modal-title" id="exampleModalLabel">New insurance group</h5>
                                </div>
                                <div class="modal-body flex-grow-1">
                                    <div class="form-group">
                                        <label class="form-label required" for="group-name">Group name</label>
                                        <input type="text" class="form-control" id="group-name" placeholder="New-group-1"
                                            name="name" aria-label="group-name" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label required " for="group-desc">Group description</label>
                                        <input type="text" id="group-desc" class="form-control"
                                            placeholder="This is the new grouping set" aria-label="group-desc"
                                            name="description" maxlength="70" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label required" for="criteria">Portfolio Criteria</label>
                                        <select id="criteria_id" name="criteria_id" class="select2 form-control">

                                            @foreach ($criterias as $criteria)
                                                <option minDate="{{ $criteria->start_date }}"
                                                    maxDate="{{ $criteria->end_date }}"
                                                    @foreach ($groupCount as $item) @if ($item->id == $criteria->id)
                                                        groupCount="{{ $item->group_count }}" @endif @endforeach
                                                    value="{{ $criteria->id }}"
                                                    @if ($criteria->end_date != '') disabled @endif>
                                                    {{ $criteria->name }} @if ($criteria->status->slug == 'expired')
                                                        (Expired)
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group position-relative">
                                        <label class="form-label required" for="group-desc">Start Date</label>
                                        <input type="text" id="applicable-date" name="start_date"
                                            class="form-control datepicker" placeholder="31 Dec, 2021" />
                                    </div>
                                    <x-form-buttons textSubmit='Submit' textCancel='Cancel' />
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
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/pickadate/pickadate.css') }}">
@endSection

@section('scripts')
    <script src="{{ asset('app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/forms/pickers/form-pickers.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/forms/form-select2.js') }}"></script>
    <script>
   $(document).ready(function() {
    let groupsData = @json($portfolioGroups);
    let today = new Date();
    let isBoarding = Boolean(Number('{{ (bool) $isBoarding }}'));
    let isImpersonating = Boolean(Number('{{ is_impersonating() }}'));

    console.log("Impersonating:", isImpersonating);

    let $datepicker = $('.datepicker');
    let $criteriaDropdown = $('#criteria_id');

    let enabledOptions = $criteriaDropdown.find('option:not(:disabled)');
    let firstEnabledOption = enabledOptions.length > 0 ? enabledOptions.get(0) : null;

    function initializeDatepicker(option) {
        if (!option) return;

        let minDate = getDateAttribute(option, "minDate");
        let maxDate = getDateAttribute(option, "maxDate");

        if (!minDate) return;

        minDate = adjustMinDate(minDate);
        let picker = $datepicker.pickadate('picker');

        picker.set('min', minDate);
        if(isBoarding && isImpersonating)
            picker.set('max', minDate);
        else
            picker.set('max',false);
        picker.set('select', minDate);

    }

    function updateDatepicker(instance) {
        let selectedOption = instance.options[instance.selectedIndex];
        if (!selectedOption) return;

        let groupCount = selectedOption.getAttribute("groupCount");
        let minDate = getDateAttribute(selectedOption, "minDate");
        let maxDate = getDateAttribute(selectedOption, "maxDate");
        let selectedCriteriaId = instance.value;

        if (!minDate) return;

        minDate = adjustMinDate(minDate);
        let picker = $datepicker.pickadate('picker');

        picker.set('min', minDate);
        if(isBoarding && isImpersonating)
            picker.set('max', minDate);
        else
            picker.set('max',false);
        picker.set('select', minDate);

        if (groupCount > 0) {
            let latestStartDate = getLatestGroupStartDate(selectedCriteriaId);
            if (latestStartDate) {
                latestStartDate = adjustMinDate(latestStartDate);
                picker.set('min', latestStartDate);
                picker.set('max', false);
                picker.set('select', latestStartDate);
            }
        }
    }

    function getDateAttribute(option, attr) {
        let value = option.getAttribute(attr);
        return value ? new Date(value) : null;
    }

    function adjustMinDate(date) {
        if (date < today) {
            if (!isBoarding || !isImpersonating) {
                return today;
            }
        }
        return date;
    }

    function getLatestGroupStartDate(criteriaId) {
        let filteredGroups = groupsData.filter(group => group.criteria_id == criteriaId);
        if (filteredGroups.length === 0) return null;

        let latestStartDate = new Date(
            Math.max(...filteredGroups.map(group => new Date(group.start_date)))
        );
        latestStartDate.setDate(latestStartDate.getDate() + 1);
        console.log("Latest Start Date:", latestStartDate);
        return latestStartDate;
    }

    $datepicker.pickadate({
        selectYears: 100,
        selectMonths: true,
        format: 'mmm dd, yyyy'
    });

    let maxDate = '{{ optional($criterias->first())->end_date }}';
    if (maxDate) {
        $datepicker.pickadate('picker').set('max', new Date(maxDate));
    }

    initializeDatepicker(firstEnabledOption);

    if (firstEnabledOption) {
        updateDatepicker($criteriaDropdown[0]);
    }

    $criteriaDropdown.on('change', function() {
        updateDatepicker(this);
    });

            $(".delete").on('click', function() {
                $("#danger").modal();
                document.getElementById('delete-user-form').action = $(this).data('route');
            });
        });
    </script>
@endSection
