@extends('admin.layouts.app')

@section('content')
    <div class="app-content content ">
        <x-toast :errors="$errors"/>
        <div class="content-wrapper">
            <div class="content-body">
                <section id="dashboard-ecommerce">
                    <div class="search-row">
                        <form method="get" action="{{ route('tenant.index') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control" value="{{ Request::get('search') }}" name="search" id="searchInput" placeholder="Search...">
                                        <a href="{{ route('tenant.index') }}" id="clearSearch" class="clear-icon">&times;</a>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <button class="btn btn-icon btn-primary form-control" id="searchBtn" type="submit">Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    @if (count($organizations) > 0 && isset($organizations))
                        <div id="organizationsContainer" class="row match-height">
                            @foreach ($organizations as $item)
                                <div class="col-sm-6 col-md-4 col-lg-4">
                                    <a href="{{ route('dashboard.index', ['org' => CustomHelper::encode($item->id)]) }}">
                                        <div class="card organization-card">
                                            <div class="card-header">
                                                <div class="col-md-12 mt-1">
                                                    @if($item->logo)
                                                        <img class="img-fluid org-img" src="{{$item->logo}}" />
                                                    @else
                                                    <img  class="img-fluid org-img" src="{{asset('app-assets/images/logo/delta-logo.svg')}}" >
                                                    @endif
                                                    <h5 class="card-title pt-1">{{ $item->name }}</h5>
                                                    <p class="org-p">
                                                        @if($item->type == '0')
                                                            Life Insurance
                                                        @elseif($item->type == '1')
                                                            Non-Life Insurance
                                                        @elseif($item->type == '2')
                                                            Composite
                                                        @else
                                                            {{ $item->type }}
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-12">
                            @if ($organizations->lastPage() > 1)
                            <nav aria-label="Page navigation example">
                                <ul class="pagination mt-2 justify-content-center">
                                    <li class="page-item prev"><a class="page-link" href="{{ $organizations->withQueryString()->previousPageUrl() }}">Prev</a></li>
                                    @if(1 != $organizations->currentPage() )
                                    <li class="page-item"><a class="page-link" href="{{ $organizations->url(1)}}">1</a></li>
                                    @endif
                                    <li class="page-item active"><a class="page-link" href="javascript:void(0);">{{ $organizations->currentPage() }}</a></li>
                                    @if($organizations->lastPage() != $organizations->currentPage() )
                                    <li class="page-item"><a class="page-link" href="{{ $organizations->url($organizations->lastPage())}}">{{ $organizations->lastPage() }}</a></li>
                                    @endif
                                    <li class="page-item next"><a class="page-link" href="{{ $organizations->withQueryString()->nextPageUrl() }}">Next</a></li>
                                </ul>
                            </nav>
                            @endif
                        </div>
                    @else
                        <h4 class="text-center">No Organizations Found</h4>
                    @endif
                </section>
            </div>
        </div>
    </div>
@endSection
@section('page-css')
    <livewire:styles />
@endsection
@section('scripts')
    <livewire:scripts />
    <script src="{{ asset('app-assets/js/scripts/components/components-modals.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Show/hide clear icon based on input value
            $('#searchInput').on('input', function() {
                var inputValue = $(this).val();
                if (inputValue.length > 0) {
                    $('#clearSearch').show();
                } else {
                    $('#clearSearch').hide();
                }
            });
        });
    </script>
@endSection
