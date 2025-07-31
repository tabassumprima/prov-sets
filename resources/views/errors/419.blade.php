@extends('errors.layouts.app',['title'=>' Page Expired'])
@section('content')
<!-- Error page-->
<div class="misc-wrapper">
    <div class="misc-inner p-2 p-sm-3">
        <div class="w-100 text-center">
            <h2 class="mb-1">Page Expired! ⏳</h2>
        <p class="mb-2">
            <b>Sorry, the page you were trying to access has expired.</b> Please try again or contact support if the issue persists.
        </p>
        <a class="btn btn-primary mb-1 btn-sm-block" href="{{url()->previous()}}">Back</a>
        </div>
    </div>
</div>
<!-- / Error page-->
@endsection