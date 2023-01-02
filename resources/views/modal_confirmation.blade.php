
<div class="modal-header">
    <h4 class="modal-title">{{ $modal_title }}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <p> @if(isset($error))
        <div>{{ $error }}</div>
    @else
        {!! $modal_body !!}
    @endif</p>
</div>
<div class="modal-footer justify-content-between">
    <button type="button" class="btn  btn-default" data-dismiss="modal">{{ trans('general.button.cancel') }}</button>
    @if(!isset($error))
        <a href="{{ $modal_route }}" type="button" class="btn  btn-primary">{{ trans('general.button.ok') }}</a>
    @endif
</div>
