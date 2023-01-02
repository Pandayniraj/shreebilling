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

<div class="modal dialog fade" role="dialog" id="modal_dialog_tags" style="min-height: 500px;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header" style="display: flex;justify-content: space-between;">
                <h3>Create Expense Tag</h3>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                            <div class="row">
                               <div class="col-md-6">
                                   <label for="">Type</label>
                                   <input type="text" name="type" value="Expense" disabled class="form-control input-sm">
                               </div>
                                <div class="col-md-6">
                                    <label for="">Name</label>
                                   <input type="text" name="name" class="form-control input-sm" id="tag_name" required>
                               </div>
                            </div>


            <div class="modal-footer" id='form_edit_part'>
                <button class="btn btn-success btn-sm" data-dismiss="modal" id="submitbtn"><i class="fa  fa-check"></i> Create </button>
                <button class="btn btn-danger btn-sm" data-dismiss="modal" ><i class="fa fa-time"></i> Cancel </button>
                {{--               <input type="submit" name="submit_option" id='formSubmit' style="visibility: hidden;">--}}
            </div>
        </div>
    </div>
</div>
</div>
<script>
    $('#submitbtn').on('click',function (){
        var tag_name=$('#tag_name').val()
        $.ajax({
            type: "POST"
            , contentType: "application/json; charset=utf-8"
            , url: "/admin/expenses/postCreateModal?name="+tag_name
            , success: function(result) {
                if(result.data) {
                    var tag_el = $('#select_tag');
                    tag_el.prepend($('<option>', {
                        value: result.data.id,
                        text: result.data.name
                    }));
                    tag_el.val(result.data.id).change()
                }
            }
        });
    })
</script>
