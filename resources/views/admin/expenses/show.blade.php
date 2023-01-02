@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {{ ucfirst($page_title)}}
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            <p><i class="fa fa-money"></i> MONEY OUT. Record all the expenses, this will automatically maintain AP.</p>

          {{ TaskHelper::topSubMenu('topsubmenu.accounts')}}

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

    <div class='row'>
        <div class='col-md-12'>
            <div class="box box-header">

                {!! Form::open(['route' => ['admin.expenses.patch', $expenses->id], 'method' => 'PATCH', 'id' => 'form_edit_client','class' => 'myfor form-horizontal'] ) !!}
                @include('partials._expenses_form')

      
            @if(count($history) > 0)
            <div class="col-md-12">
                
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="clients-table">
                                <caption>Expenses History</caption>
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>From Amount</th>
                                        <th>To Amount</th>
                                        <th>User</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($history as $hy)
                                    <tr>
                                        <td>#{{$hy->id}}</td>
                                        <td>{{$hy->from_amount}}</td>
                                        <td>{{$hy->to_amount}}</td>
                                        <td>{{$hy->user->username}}</td>
                                        <td>{{date('dS Y M',strtotime($hy->created_at))}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
            </div>
            @endif
                        <div class="row">
                    <div class="col-md-12">

                        <div class="form-group">
                            @if($expenses->isEditable())
                                <a href="/admin/expenses/{{$expenses->id}}/edit" class="btn btn-primary">Edit</a>
                            @else
                             <button type="button" disabled="" class="btn btn-danger" disabled="">Edit</button> 
                            @endif

                            <a href="/admin/expenses/" title="{{ trans('general.button.cancel') }}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                        </div>
                        </div>
                 </div> 
                   {!! Form::close() !!}
            </div>
            </div><!-- /.box-body -->

        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection

@section('body_bottom')
    <!-- form submit -->
    @include('partials._body_bottom_submit_client_edit_form_js')

      <script type="text/javascript">
const dateRange = {
    <?php $currentFiscalyear = FinanceHelper::cur_fisc_yr();?>
    minDate: `{{ $currentFiscalyear->start_date }}`,
    maxDate: `{{ $currentFiscalyear->end_date }}`
}
function setnepalidate(){
    $(".nepalidatepicker").nepaliDatePicker({
        dateFormat: "%y-%m-%d",
        closeOnDateSelect: true
    });
}
function setenglishdate(){
   $('.datepicker').datetimepicker({
          //inline: true,
          //format: 'YYYY-MM-DD',
            format: 'YYYY-MM-DD', 
            sideBySide: true,
            minDate: dateRange.minDate,
            maxDate: dateRange.maxDate,
            allowInputToggle: true
        });
}

 $('#selectdatetype').change(function(){
  let type = $(this).val();
  if(type =='nep'){
    let html = $('#nepalidate').html();
    console.log(html);
    $('#dateselectors').html(html);
    setnepalidate();
  }else{
    let html = $('#englishdate').html();
    $('#dateselectors').html(html);
    setenglishdate();
  }
 });

function setExpenseAccountOptions(type){
    let html = $(`#expense_account_options #${type}-id`).html();
    $('#expenses_account').html(html);
    $('#expenses_account').val('{{ $expenses->expenses_account }}');  
}

$(function() {
        const required_account = <?php echo json_encode($required_account); ?>;
        console.log(required_account);
        setExpenseAccountOptions('{{ $expenses->expense_type ?? 'expense' }}');

        setenglishdate();
        $('.select2').select2();
        $('#expense_type').change(function(){
          $('.select2').select2('destroy');
          let type = $(this).val();
          setExpenseAccountOptions(type);
          $('.select2').select2();
        });

$(document).on('show.bs.modal', '#modal_dialog' , function(e){
    let expenses_type = $('#expense_type').val();
     var selected_value = required_account[expenses_type];
     console.log(selected_value); 
    $(this).find(".modal-content").load(`/admin/chartofaccounts/create/ledgers?expenses_type=${expenses_type}&selected_value=${selected_value}`);
});

$(document).on('hidden.bs.modal', '#modal_dialog' , function(e){
        $('#modal_dialog .modal-content').html('');    
});

});


function handleModalResults(result){
  var options = $(result.data).html();
  console.log(options);
  $('#expenses_account').html(options);
   let type = $('#expense_type').val();
   $(`#expense_account_options #${type}-id`).html(options);
  $('#expenses_account').val(result.lastcreated.id);
  $('#modal_dialog').modal('hide');
}

function openwindow(){
    var win =  window.open('/admin/clients/modals?relation_type=supplier', '_blank','toolbar=yes, scrollbars=yes, resizable=yes, top=500,left=500,width=600, height=650');
    
}

 function HandlePopupResult(result) {
  if(result){
    let clients = result.clients;
    let types = $(`input[name=source]:checked`).val();
    if( types == 'lead'){
        lead_clients = clients;
    }
    else{
        customer_clients = clients;
    }            
    var option = '';
    for(let c of clients){
        option = option + `<option value='${c.id}'>${c.name}</option>`; 
    }
    $('#customers_id select').html(option);
    setTimeout(function(){
        $('#customers_id select').val(result.lastcreated);
        $('#pan_no').val(result.pan_no);
        $("#ajax_status").after("<span style='color:green;' id='status_update'>client sucessfully created</span>");
        $('#status_update').delay(3000).fadeOut('slow');
    },500);
  }
  else{
    $("#ajax_status").after("<span style='color:red;' id='status_update'>failed to create clients</span>");
    $('#status_update').delay(3000).fadeOut('slow');
  }
}

</script>


@endsection

