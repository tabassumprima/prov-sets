@extends('errors.layouts.app',['title'=> 'Something Went Wrong'])
@section('content')
<!-- Error page-->
<div class="misc-wrapper">
    <div class="misc-inner p-2 p-sm-3">
        <div class="w-100 text-center">
            <h1 class="mb-3">Something Went Wrong 🕵🏻‍♀️</h1>
            <div class="text-left">
                <h4 class="my-1">Kindly try below steps:</h4>
                <ul>
                    <li class="mb-1">Clear Browser Cache and Cookies.</li>
                    <li class="mb-1">Refresh by clicking <a href="{{url()->previous()}}">here</a>.</li>
                </ul>
                <p class="pt-2">For further assistance, reach out to Customer Support by <a href="{{route('tickets.index')}}">creating a ticket</a> or emailing us at <a href="mailto:support@ifrstech.com">support@ifrstech.com</a> </p>
                <div class="text-center">
                    <a href="{{ route('logout') }}" class="btn btn-danger mb-2 btn-sm-block"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="mr-50"
                        data-feather="power"></i>Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>
<!-- / Error page-->
@endsection