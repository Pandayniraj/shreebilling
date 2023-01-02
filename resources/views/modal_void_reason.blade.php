<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">{{ $modal_title }}</h4>
</div>
<form method="post" action="{{ $modal_route }}">
        {{ csrf_field() }}
<div class="modal-body">
    @if($error)
        <div>{{{ $error }}}</div>
    @else
        <textarea name="reason" rows="4" cols="60" placeholder=" Reason Here" required></textarea>
    @endif
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('general.button.cancel') }}</button>
    @if(!$error)
     <button type="Submit" name="submit" class="btn btn-primary">Ok</button>
</form>
    @endif
</div>