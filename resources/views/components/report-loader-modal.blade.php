<div class="modal fade text-left modal-primary" data-backdrop="static" id="fetch_records" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loading_modal_title">Fetching Records</h5>
            </div>
            <div class="modal-body">
                <span id="loading_modal_text">Please wait. While the results are prepared. </span>
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <div class="modal-footer">
                {{--<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>--}}
            </div>
        </div>
    </div>
</div>
<div id="route" data-route="{{$route}}"></div>