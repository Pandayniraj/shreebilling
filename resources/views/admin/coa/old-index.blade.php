@extends('layouts.master')
@section('content')
    <style>
        .td-click{
            cursor:pointer;
            color: darkblue;
        }
    </style>
<?php

function CategoryTree($parent_id=null,$sub_mark=''){

  $groups= \App\Models\COAgroups::orderBy('code', 'asc')->where('parent_id',$parent_id)->where('org_id',\Auth::user()->org_id)->get();

    if(count($groups)>0){
      foreach($groups as $group){
          $class=$group->children->count()<1?'td-click':'';

        if($group->id<=4){

           echo '<tr id="'.$group->id.'" class="group-class bg-purple">
                <td><b>'.$sub_mark.$group->code.'</b></td>
                <td class="'.$class.'" onclick="{{if('.$group->children->count().'<1)'.'getLedgers(this,'.$group->id.')'.'}}"><h5><b>
 <i class="fa fa-spinner fa-spin" style="display:none"></i>&nbsp;'.$group->name.'&nbsp;&nbsp;</b>
<span style="margin-left:10px;display:none;color:grey">No Ledgers Created for this group.</span>
    '.(($group->children->count()<1)?'<i class="fa fa-chevron-down"></i>':"").'</h5>
</td>
                <td><b>Group</b></td>
                <td>-</td>
                <td>-</td>
                <td>
                <a href="'.route('admin.chartofaccounts.detail.groups', $group->id).'" title="View"><i class="fa fa-eye"></i></a>
                <a href="'.route('admin.chartofaccounts.edit.groups', $group->id).'" title="Edit"><i class="fa fa-edit"></i></a>
                 <a href="'.route('admin.chartofaccounts.groups.confirm-delete', $group->id).'" title="Delete" data-toggle="modal" data-target="#modal_dialog"><i class="fa fa-trash deletable"></i></a>
                </td>
               </tr>';
        }else{

           echo '<tr id="'.$group->id.'" class="group-class">
                <td><b>'.$sub_mark.$group->code.'</b></td>
                <td class="'.$class.'" onclick="{{if('.$group->children->count().'<1)'.'getLedgers(this,'.$group->id.')'.'}}"><h5><b>
                    <i class="fa fa-spinner fa-spin" style="display:none"></i>&nbsp;'.$group->name.'</b>
    <span style="margin-left:10px;display:none;color:grey">No Ledgers Created for this group.</span>
    '.(($group->children->count()<1)?'<i class="fa fa-chevron-down"></i>':"").'</h5></td>
                <td><b>Group</b></td>
                <td>-</td>
                <td>-</td>

                <td>
                <a href="'.route('admin.chartofaccounts.detail.groups', $group->id).'" title="View"><i class="fa fa-eye"></i></a>
                <a href="'.route('admin.chartofaccounts.edit.groups', $group->id).'" title="Edit"><i class="fa fa-edit"></i></a>
                 <a href="'.route('admin.chartofaccounts.groups.confirm-delete', $group->id).'" title="Delete" data-toggle="modal" data-target="#modal_dialog"><i class="fa fa-trash deletable"></i></a>
                </td>
               </tr>';
        }

//        $ledgers= \App\Models\COALedgers::orderBy('code', 'asc')->where('group_id',$group->id)->where('org_id',\Auth::user()->org_id)->get();
//        if(count($ledgers) >0){
//            $submark= $sub_mark;
//            $sub_mark = $sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
//
//              foreach($ledgers as $ledger){
//
//                $closing_balance =TaskHelper::getLedgerBalance($ledger->id);
//
//             echo '<tr style="color: #009551;">
//                <td></td>
//                <td class="bg-warning"><a href="'.route('admin.chartofaccounts.detail.ledgers', $ledger->id).'" style="color:#009551;"><b>'.$sub_mark.$ledger->code.'</b></a></td>
//                <td class="bg-warning"><a href="'.route('admin.chartofaccounts.detail.ledgers', $ledger->id).'" style="color:#009551;"><h5><b>'.$ledger->name.'</b></h5></a></td>
//                <td class="bg-warning"><b>Ledgers</b></td>
//                <td class="bg-warning">'.$ledger->op_balance.'</td>
//                <td class="bg-warning">'.$closing_balance.'</td>
//                <td class="bg-warning"><a href="'.route('admin.chartofaccounts.edit.ledgers', $ledger->id).'" title="Edit"><i class="fa fa-edit"></i></a>
//                <a href="'.route('admin.chartofaccounts.ledgers.confirm-delete', $ledger->id).'" title="Delete" data-toggle="modal" data-target="#modal_dialog"><i class="fa fa-trash deletable"></i></a>
//                </td>
//
//               </tr>';
//
//           }
//           $sub_mark=$submark;
//
//
//        }


        CategoryTree($group->id,$sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
      }
    }


}



 ?>

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

        <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Charts of Account
                <small> Manage charts of account</small>
            </h1>

            {{ TaskHelper::topSubMenu('topsubmenu.accounts')}}


            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
             <div class="box-header with-border">
                        <a class="btn btn-social btn-foursquare btn-sm" href="{!! route('admin.chartofaccounts.create.ledgers') !!}" title="Create CoaLedgers">
                            <i class="fa fa-plus"></i> Create New Ledger
                        </a>
                        &nbsp;
                        <a class="btn btn-social btn-foursquare btn-sm" href="{!! route('admin.chartofaccounts.create.groups') !!}" title="Create CoaGroups">
                            <i class="fa fa-plus"></i> Create New Ledger Group
                        </a>
                        &nbsp;
                        <a class="btn btn-social  btn-success btn-sm" href="{!! route('admin.ExcelLedgergroups.index') !!}" title="Import/Export CoaLedgers">
                            <i class="fa fa-download"></i>Import/Export Ledger Group
                        </a>
                        &nbsp;

                        <a class="btn btn-social  btn-success btn-sm" href="{!! route('admin.ExcelLedger.index') !!}" title="Import/Export CoaLedgers">
                            <i class="fa fa-download"></i>Import/Export Ledger
                        </a>
                        &nbsp;


                        <a class="btn btn-social  btn-success btn-sm" href="{!! route('admin.filter.coa.groups') !!}">
                            <i class="fa fa-filter"></i>Filter Ledger
                        </a>
                        &nbsp;

                         <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>

                    <div class="box box-header box-danger">

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="orders-table">
                                <thead>
                                    <tr class="bg-info">
                                        <th>Account Code</th>
                                        <th>Account Name</th>
                                        <th>Type</th>
                                        <th>O/P Balance({{env('APP_CURRENCY')}})</th>
                                        <th>C/L Balance({{env('APP_CURRENCY')}})</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   {{ CategoryTree() }}
                                </tbody>
                            </table>

                        </div> <!-- table-responsive -->

                    </div><!-- /.box-body -->

        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection

<!-- Optional bottom section for modals etc... -->
@section('body_bottom')

    <script>
        var disabled=false;

        function getLedgers(el,group_id) {
            if (disabled)
                return
            disabled=true
            $(el).find('i').show()
            $(el).attr('disabled',true)
            $.ajax({
                url: "chartofaccounts/getLedgersAjax?group_id=" + group_id,
                type: "GET",
                dataType: 'html',
                success: function (data) {
                    if (data) {
                        if ($(el).parent().next('tr').hasClass('ledger-class'))
                            $(el).parent().nextUntil('.group-class','tr').remove()
                        $(data).insertAfter($(el).parent().parent().find('#' + group_id))
                    }
                    else $(el).find('span').show()
                    $(el).find('i').hide()
                    $(el).removeAttr('disabled')
                    disabled=false

                },
                error:function (){
                    $(el).find('i').hide()
                    $(el).removeAttr('disabled')
                    disabled=false
                }
            })
        }
    </script>
@endsection
