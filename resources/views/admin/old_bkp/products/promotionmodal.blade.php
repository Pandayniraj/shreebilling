<div class="panel panel-custom">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Promotion</h4>
        <p>Please fill in the information below.</p>
    </div>
    <div class="modal-body wrap-modal wrap">
        <form role="form" id="announcement_form" action="/admin/product/promotion/save" method="post" class="form-horizontal form-groups-bordered">
            {{ csrf_field() }}
            <div class="col-md-12">
                <label class=" control-label">Promotion Price <span class="required">*</span></label>
                <input type="text" name="promotion_price" value="{{$id}}" class="form-control" required />
            </div>

            <div class="col-md-12">
                <label class=" control-label">Start Date <span class="required">*</span></label>
                <input type="text" name="start_date" value="" class="form-control datepicker" required />
            </div>

            <div class="col-md-12">
                <label class=" control-label">End Date <span class="required">*</span></label>
                <input type="text" name="end_date" value="" class="form-control datepicker" required />
            </div>

    </div>

    <div class="modal-footer">
        <input type="submit" name="submit" value="Set Promotion" class="btn btn-success input-xs" id="update_promotion">
    </div>
</div>
<!--hidden input values -->

</form>
</div>
</div>

<script type="text/javascript">
    $(function() {
        $('.datepicker').datetimepicker({
            //inline: true,
            format: 'YYYY-MM-DD'
            , sideBySide: true
        });
    });

</script>
