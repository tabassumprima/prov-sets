@extends('errors.layouts.app',['title'=>' Access Denied'])
@section('content')
<!-- Error page-->
<div class="misc-wrapper">
    <div class="misc-inner p-2 p-sm-3">
        <div class="w-100 text-center">
            <h2 class="mb-1">You are not authorized! 🔐</h2>
            <p class="mb-2">
                <b>Access Denied</b> You do not have the necessary authorization to proceed. Please contact the administrator for assistance.
            </p><a class="btn btn-primary mb-1 btn-sm-block" href="/">Back to login</a>
        </div>
    </div>
</div>
<!-- / Error page-->
@endsection