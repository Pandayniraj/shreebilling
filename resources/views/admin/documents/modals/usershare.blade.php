<div class="modal-content">
    <div id="printableArea">
        <div class="modal-header hidden-print">
            <h4 class="modal-title" id="myModalLabel">{{ trans('/admin/documents/general.page.share.title') }}
                <small><button class="btn btn-xs btn-primary" type="submit" form='sharedoc'><i class="fa fa-share"></i> {{ trans('/admin/documents/general.button.share') }}</button></small>
                <div class="pull-right ">

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
            </h4>
        </div>  
        <form method="POST" action="{{route('admin.documents.share',$docid)}}" id='sharedoc'>
            {{ csrf_field() }}
        <div class="modal-body wrap-modal wrap">
            <div class='row'>
                <div class='col-md-12'>
                    <div class="box-body">
                        <table class="table table-striped" id='users-table'>
                            <thead>
                                <tr>
                                    <th>{{ trans('/admin/documents/general.columns.name') }}</th>
                                    <th class="text-center">{{ trans('/admin/documents/general.columns.designation') }}</th>
                                    <th  class="text-center">{{ trans('/admin/documents/general.columns.actions') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $key=>$user)
                                        <tr>
                                            <td>
                                                <img src="{{$user->avatar}}" style="height: 30px;width: 30px;"> 
                                                &nbsp;{{$user->first_name}}&nbsp;{{$user->last_name}}
                                            </td>
                                            <td style="text-align: center;">{{$user->designation->designations}}</td>
                                            <td  style="text-align: center;"><input type="checkbox" 
                                                name="user_id[]" value="{{ $user->id }}"></td>
                                        </tr>
                                    @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        </form>

        <script type="text/javascript">
            $('#users-table').DataTable();
        </script>