<div>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                <div class="alert-body">
                    Error: {{ $error }}
                </div>
            </div>
        @endforeach
    @endif

    @if (\Session::has('error') && \Session::get('error') )
        <div class="alert alert-danger" role="alert">
            <div class="alert-body row">
                <div class="col-12">
                    {!! \Session::get('error') !!}
                    @if (\Session::has('file') && \Session::get('file'))
               
                    <a href="{{route( $type .'.error.file', ['type' => $type ])}}" class="btn btn-danger btn-sm" >
                        Download error file
                    </a>
               
                @endif
                </div>
                
            </div>

        </div>
    @endif

    @if (\Session::has('success') && \Session::get('success'))
        <div class="alert alert-success" role="alert">
            <div class="alert-body">
                {!! \Session::get('success') !!}
            </div>
        </div>
    @endif
</div>
