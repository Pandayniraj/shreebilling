<div class="clearfix"></div>
@foreach($apprisalObjTypes as $aotk => $aotv)
    @if($aotv->objectives->count())
    <div class="col-sm-6">
        <div class="panel panel-custom">
            <div class="bg-info box-header">
                <span class="panel-title pull-left">{{ $aotv->name }}</span>
                <span class="panel-title pull-right">
                    <span class="badge bg-yellow">{{ $aotv->points }}</span>
                    Marks
                </span>
                <input type="hidden" name="marks[]" value="{{ $aotv->points }}">
            </div>
            <div class="box-body" style="padding-left: 0;padding-right: 0;">
                @foreach($aotv->objectives as $obk => $obv)
                <div class="row" style="padding: 1rem 0.2rem;border: 1px solid #eee;margin-bottom: 1rem !important;margin: 0;">
                    <div class="form-group" id="border-none">
                        <span class="col-sm-8">{{ $obv->objective }}</span>
                        <div class="col-sm-4">
                            <input type="number" class="form-control date_in" min="1" max="{{$obv->marks}}" value="" placeholder="Mark max {{$obv->marks}}" name="appraisal_marks[{{$aotv->id}}][{{$obv->id}}]" required>
                        </div>
                        <div class="col-sm-12" style="margin-top: 1rem;">
                            <textarea name="comments[{{$aotv->id}}][{{$obv->id}}]" class="form-control" placeholder="Enter your comment" required></textarea>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
@endforeach
