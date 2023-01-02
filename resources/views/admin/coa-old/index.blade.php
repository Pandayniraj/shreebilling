@extends('layouts.master')
@section('content')

<?php

function CategoryTree($parent_id=null,$sub_mark=''){

  $groups= \App\Models\COAgroups::orderBy('code', 'asc')->where('parent_id',$parent_id)->where('org_id',\Auth::user()->org_id)->get();

    if(count($groups)>0){
      foreach($groups as $group){

        if($group->id<=4){

           echo '<tr class="bg-purple" style="font-size:16px">

                <td>'.$sub_mark.$group->code.'</td>
                <td>'.$group->name.'</td>'.
//                <td><b>'.$group->account_types->name.'</b></td>
                '<td>Group</td>
                <td>-</td>
                <td>-</td>
               <td><a href="'.route('admin.chartofaccounts.edit.groups', $group->id).'" title="Edit"><i class="fa fa-edit"></i></a>
                 <a href="'.route('admin.chartofaccounts.groups.confirm-delete', $group->id).'" title="Delete" data-toggle="modal" data-target="#modal_dialog"><i class="fa fa-trash deletable"></i></a>
                </td>
               </tr>';
        }else{
            $op_balance= \App\Models\COALedgers::orderBy('code', 'asc')->where('group_id',$group->id)->where('org_id',\Auth::user()->org_id)->sum('op_balance');

           echo '<tr>

                <td><b>'.$sub_mark.$group->code.'</b></td>
                <td><b>'.$group->name.'</b></td>'.
//                <td><b>'.$group->account_types->name.'</b></td>
                '<td><b>Group</b></td>
                <td>'.$op_balance.'</td>
                <td>-</td>

                <td><a href="'.route('admin.chartofaccounts.edit.groups', $group->id).'" title="Edit"><i class="fa fa-edit"></i></a>
                 <a href="'.route('admin.chartofaccounts.groups.confirm-delete', $group->id).'" title="Delete" data-toggle="modal" data-target="#modal_dialog"><i class="fa fa-trash deletable"></i></a>
                </td>
               </tr>';
        }

        $ledgers= \App\Models\COALedgers::orderBy('code', 'asc')->where('group_id',$group->id)->where('org_id',\Auth::user()->org_id)->get();
        if(count($ledgers) >0){
            $submark= $sub_mark;
            $sub_mark = $sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

              foreach($ledgers as $ledger){

                // $closing_balance = TaskHelper::getLedgerBalance($ledger->id);
                $closing_balance =TaskHelper::getLedgerTotal($ledger->id);
            if ($closing_balance['amount'] > 0) {

              if( $closing_balance['dc'] == 'D'){

             echo '<tr style="color: #009551;">

                <td><a href="'.route('admin.chartofaccounts.detail.ledgers', $ledger->id).'">'.$sub_mark.$ledger->code.'</a></td>
                <td><a href="'.route('admin.chartofaccounts.detail.ledgers', $ledger->id).'">'.$ledger->name.'</a></td>'.
//                <td><b>'.$ledger->group->account_types->name.'</b></td>
                '<td><b>Ledgers</b></td>
                <td>'.$ledger->op_balance.'</td>
                <td> Dr '.$closing_balance['amount'].'</td>
                <td><a href="'.route('admin.chartofaccounts.edit.ledgers', $ledger->id).'" title="Edit"><i class="fa fa-edit"></i></a>
                <a href="'.route('admin.chartofaccounts.ledgers.confirm-delete', $ledger->id).'" title="Delete" data-toggle="modal" data-target="#modal_dialog"><i class="fa fa-trash deletable"></i></a>
                </td>

               </tr>';
           }else{
             echo '<tr style="color: #009551;">

                <td><a href="'.route('admin.chartofaccounts.detail.ledgers', $ledger->id).'">'.$sub_mark.$ledger->code.'</a></td>
                <td><a href="'.route('admin.chartofaccounts.detail.ledgers', $ledger->id).'">'.$ledger->name.'</a></td>'.
//                <td><b>'.$ledger->group->account_types->name.'</b></td>
                '<td><b>Ledgers</b></td>
                <td>'.$ledger->op_balance.'</td>
                <td> Cr '.$closing_balance['amount'].'</td>
                <td><a href="'.route('admin.chartofaccounts.edit.ledgers', $ledger->id).'" title="Edit"><i class="fa fa-edit"></i></a>
                <a href="'.route('admin.chartofaccounts.ledgers.confirm-delete', $ledger->id).'" title="Delete" data-toggle="modal" data-target="#modal_dialog"><i class="fa fa-trash deletable"></i></a>
                </td>

               </tr>';
           }
           }else{
             echo '<tr style="color: #009551;">

                <td><a href="'.route('admin.chartofaccounts.detail.ledgers', $ledger->id).'">'.$sub_mark.$ledger->code.'</a></td>
                <td><a href="'.route('admin.chartofaccounts.detail.ledgers', $ledger->id).'">'.$ledger->name.'</a></td>'.
//                <td><b>'.$ledger->group->account_types->name.'</b></td>
                '<td><b>Ledgers</b></td>
                <td>'.$ledger->op_balance.'</td>
                <td>'.$closing_balance['amount'].'</td>
                <td><a href="'.route('admin.chartofaccounts.edit.ledgers', $ledger->id).'" title="Edit"><i class="fa fa-edit"></i></a>
                <a href="'.route('admin.chartofaccounts.ledgers.confirm-delete', $ledger->id).'" title="Delete" data-toggle="modal" data-target="#modal_dialog"><i class="fa fa-trash deletable"></i></a>
                </td>

               </tr>';
           }

           }
           $sub_mark=$submark;


        }


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
                        <a class="btn btn-foursquare btn-xs" href="{!! route('admin.chartofaccounts.create.ledgers') !!}" title="Create CoaLedgers">
                            <i class="fa fa-plus"></i> Create New Ledger
                        </a>
                        &nbsp;
                        <a class="btn btn-foursquare btn-xs" href="{!! route('admin.chartofaccounts.create.groups') !!}" title="Create CoaGroups">
                            <i class="fa fa-plus"></i> Create New Ledger Group
                        </a>
                        &nbsp;
                        <a class="btn btn-default btn-xs" href="{!! route('admin.ExcelLedgergroups.index') !!}" title="Import/Export CoaLedgers">
                            <i class="fa fa-download"></i> Import/Export Ledger Group
                        </a>
                        &nbsp;
                        
                        <a class="btn btn-default btn-xs" href="{!! route('admin.ExcelLedger.index') !!}" title="Import/Export CoaLedgers">
                            <i class="fa fa-download"></i> Import/Export Ledger
                        </a>
                        &nbsp;

                        
                        <a class="btn  btn-default btn-xs" href="{!! route('admin.filter.coa.groups') !!}">
                            <i class="fa fa-filter"></i> Filter Ledger
                        </a>
                        &nbsp;

                        
                    </div>
            
                    <div class="box box-header box-danger">

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-striped" id="orders-table">
                                <thead>
                                    <tr class="bg-danger">
                                        
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


<script type="text/javascript">
    $(function() {

       });

</script>

@endsection

<!-- Optional bottom section for modals etc... -->
@section('body_bottom')


@endsection  
