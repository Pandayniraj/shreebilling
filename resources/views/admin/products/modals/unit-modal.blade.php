<style>
    .modal-dialog {
        height: 90%; /* = 90% of the .modal-backdrop block = %90 of the screen */
        width: 45%;
    }
    .select2-container{
        text-align: left!important;
        width: 100%!important;
    }
    /*.modal-content {*/
    /*    height: 100%; !* = 100% of the .modal-dialog block *!*/
    /*}*/
</style>
{{--<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />--}}
{{--<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />--}}
{{--<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>--}}

<div class="modal dialog fade" role="dialog" id="modal_dialog_unit" style="min-height: 500px;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header" style="display: flex;justify-content: space-between;">
                <h3>Create Product Unit</h3>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
{{--                <form method="post" action="{{route('admin.production.products-unit')}}">--}}
{{--                    {{ csrf_field() }}--}}
{{--                    <div class="panel panel-custom">--}}
{{--                        <div class="panel-heading">--}}
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="control-label col-sm-12">Name</label>
                                        <div class="input-group ">
                                            <input type="text" name="name" placeholder="Name" id="name" class="form-control" required>
                                            <div class="input-group-addon">
                                                <a href="#"><i class="fa fa-stack-exchange"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="control-label col-sm-12">Symbol</label>
                                        <div class="input-group ">
                                            <input type="text" name="symbol" placeholder="Symbol" id="symbol" class="form-control" required>
                                            <div class="input-group-addon">
                                                <a href="#"><i class="fa fa-lastfm"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="control-label col-sm-12">Quantity Count</label>
                                        <div class="input-group ">
                                            <input type="text" name="qty_count" placeholder="Symbol" id="qty_count" class="form-control" value="1" required>
                                            <div class="input-group-addon">
                                                <a href="#"><i class="fa fa-lastfm"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

{{--                    </div>--}}
{{--                </form>--}}


            <div class="modal-footer" id='form_edit_part'>
                <button class="btn btn-success btn-sm" data-dismiss="modal" id="submitbtn"><i class="fa  fa-check"></i> Create </button>
                <button class="btn btn-danger btn-sm" data-dismiss="modal" ><i class="fa fa-time"></i> Cancel </button>
                {{--               <input type="submit" name="submit_option" id='formSubmit' style="visibility: hidden;">--}}
{{--            </div>--}}
        </div>
    </div>
        </div>
</div>
</div>
<script>
    $('#submitbtn').on('click',function (){
        var name=$('#name').val()
        var symbol=$('#symbol').val()
        var qty_count=$('#qty_count').val()
        if(!name||!symbol||!qty_count) {
            alert('All fields are required')
            return false
        }
        $.ajax({
            type: "POST"
            , contentType: "application/json; charset=utf-8"
            , url: "/admin/production/product-unit?name="+name+"&symbol="+symbol+"&qty_count="+qty_count
            , success: function(result) {
                if(result.data) {
                    var unit_el = $('#product-unit');
                    unit_el.prepend($('<option>', {
                        value: result.data.id,
                        text: result.data.name
                    }));
                    unit_el.val(result.data.id).change()
                }
                $('#name').val('')
                $('#symbol').val('')
                $('#qty_count').val('')
            }
        });
    })
</script>
