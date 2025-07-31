@if ($formAction ?? '')
<button type="submit" data-type="save" class="btn btn-primary mr-1 data-submit"  formaction="{{$formAction }}">{{ $textSubmit }}</button>
@else
<button type="submit" data-type="save" class="btn btn-primary mr-1 data-submit" >{{ $textSubmit }}</button>
@endif
@if ($textCancel ?? '')
<button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">{{ $textCancel }}</button>
@endif

