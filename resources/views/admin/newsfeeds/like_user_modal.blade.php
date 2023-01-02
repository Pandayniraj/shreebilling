<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">{{$page_title}}
{{--        <small class='start_dates'>{{$page_description}}</small>--}}
    </h4>
</div>
<div class="modal-body">

    <table class="table table-hover table-bordered" id="tasks-table">
        @forelse($user as $usr)
            <tr>

                <td >
                    <img src="{{ $usr['image'] }}" alt="User Image" width="50px" height="50px" style="border-radius: 50%;max-width: 100%;height: auto;">

                </td>
                <td>
                    {!!  $usr['name']  !!}
                </td>
                <td>
                    <i class="fa fa-clock"></i>
                    {{ \Carbon\Carbon::parse($usr['created_at'])->diffForHumans()}}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3">
                    <h1 class="bg bg-warning">No one has liked this post</h1>
                </td>
            </tr>
        @endforelse
    </table>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal" style="float: left">Close</button>

</div>



