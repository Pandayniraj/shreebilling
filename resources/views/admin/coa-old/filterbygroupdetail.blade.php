@extends('layouts.master')
@section('content')

<?php

function CategoryTree($parent_id=null,$sub_mark=''){

  $groups= \App\Models\COAgroups::orderBy('code', 'asc')->where('parent_id',$parent_id)->where('org_id',\Auth::user()->org_id)->get();

    if(count($groups)>0){
      foreach($groups as $group){

        if($group->id<=4){

           echo '<tr class="bg-success">
                <td></td>
                <td>'.$sub_mark.$group->code.'</b></td>
                <td><b>'.$group->name.'</td>
                <td><b>Group</b></td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
               </tr>';
        }else{

           echo '<tr class="bg-success">
                <td></td>
                <td>'.$sub_mark.$group->code.'</b></td>
                <td><b>'.$group->name.'</b></td>
                <td><b>Group</b></td>
                <td>-</td>
                <td>-</td>
                <td>-</td>

                <td><a href="'.route('admin.chartofaccounts.edit.groups', $group->id).'" title="Edit"><i class="fa fa-edit"></i></a>
                 <a href="'.route('admin.chartofaccounts.groups.confirm-delete', $group->id).'" title="Delete" data-toggle="modal" data-target="#modal_dialog"><i class="fa fa-trash deletable"></i></a>
                </td>
               </tr>';
        }

        $ledgers= \App\Models\COALedgers::orderBy('code', 'asc')->where('group_id',$group->id)->where('org_id',\Auth::user()->org_id)->get();
        if(count($ledgers>0)){
            $submark= $sub_mark;
            $sub_mark = $sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

              foreach($ledgers as $ledger){

                $closing_balance =TaskHelper::getLedgerBalance($ledger->id);

             echo '<tr>
                <td><input type="checkbox" name="ledger_ids[]" value="'.$ledger->id.'"></td>
                <td>'. $ledger->id. '</td>
                <td><a href="/admin/accounts/reports/ledger_statement?ledger_id='.$ledger->id.'">'.$sub_mark.$ledger->code.'</a></td>
                <td><a href="/admin/accounts/reports/ledger_statement?ledger_id='.$ledger->id.'">'.$ledger->name.'</a></td>
                <td>Ledgers</td>
                <td>'.($ledger->op_balance_dc=='D'?'Dr ':'Cr ').$ledger->op_balance.'</td>
                <td>'.$closing_balance.'</td>
                <td><a href="'.route('admin.chartofaccounts.edit.ledgers', $ledger->id).'" title="Edit"><i class="fa fa-edit"></i></a>
                <a href="'.route('admin.chartofaccounts.ledgers.confirm-delete', $ledger->id).'" title="Delete" data-toggle="modal" data-target="#modal_dialog"><i class="fa fa-trash deletable"></i></a>
                </td>

               </tr>';

           }
           $sub_mark=$submark;


        }


        CategoryTree($group->id,$sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
      }
    }


}

 ?>


<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {!! $page_title ?? "Page description" !!}
                <small>{!! $page_description ?? "Page description" !!}


                </small>
            </h1>

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

   <div class="col-xs-12">
          <div class="box">

            <!-- /.box-header -->
            <div class="box-body">
                    <!-- <div id="accordion"> -->
                        <!-- <h3>Options</h3> -->
                        <div class="balancesheet form">
                            <form  method="GET" action="/admin/coa/filterbygroups">
                                {{ csrf_field() }}
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">

                                        <select class="form-control customer_id select2" id="ReportLedgerId" name="parent_id" aria-hidden="true" required>
                                            <option value="">Select Parent Group</option>
                                            @foreach($parentgroups as $pg)
                                            <option value="{{$pg->id}}" @if($pg->id== request('parent_id')) selected @endif>{{$pg->name}}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                                <div class="col-md-5">
                                <div class="form-group">

                                    <a href="/admin/coa/filterbygroups" class="btn btn-primary pull-right" style="margin-left: 5px;">Clear</a>
                                <input type="submit" class="btn btn-primary pull-right" value="Submit">

                            </div>
                                </div>

                            </div>

                            </form>
                            @if($parent_id)
                            <button class="btn btn-danger pull-right" style="margin-left: 5px; display: inline-flex" onclick="submitDelete()">Delete</button>
                                @endif
                        </div>
                    <!-- </div> -->
                 </div>
          </div>
      </div>


      <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->

                    <div class="box box-header box-default">

                        <div>
                            <table class="table table-hover table-bordered table-responsive table-striped" id="orders-table">
                                <thead>
                                    <tr class="bg-info">
                                        <th style="text-align:center;width:20px !important">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        </th>
                                        <th>Ledger Id</th>
                                        <th>Account Code</th>
                                        <th>Account Name</th>
                                        <th>Type</th>
                                        <th>O/P Balance</th>
                                        <th>C/L Balance</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <form style="margin: 16px 37px 0 0;" id="deleteForm" method="get" action="{{route('admin.ledgers.deleteSelected')}}">

                                @if($maingroup)
                                    <tr class="bg-success">
                                        <td>

                                        </td>
                                        <td><b>{{$maingroup->code}}</b>

                                        </td>
                                        <td>
                                             <b>{{$maingroup->name}}</b>
                                        </td>
                                        <td>
                                            <b>Group</b>
                                        </td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                    </tr>
                                    @endif

                                    @if(count($maingroupledgers)>0)
                                    @foreach($maingroupledgers as $ledger)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="ledger_ids[]" value="{{$ledger->id}}">
                                        </td>
                                        <td>{{$ledger->id}}</td>
                                        <td><a href="/admin/accounts/reports/ledger_statement?ledger_id={{$ledger->id}}">{{$ledger->code}}</a></td>
                                        <td><a href="/admin/accounts/reports/ledger_statement?ledger_id={{$ledger->id}}">{{$ledger->name}}</a></td>
                                        <td>Ledgers</td>
                                        <td>{{ ($ledger->op_balance_dc=='D'?'Dr ':'Cr ').$ledger->op_balance}}</td>
                                        <td></td>
                                        <td><a href="{{route('admin.chartofaccounts.edit.ledgers', $ledger->id)}}" title="Edit"><i class="fa fa-edit"></i></a>
                                        <a href="{{route('admin.chartofaccounts.ledgers.confirm-delete', $ledger->id)}}" title="Delete" data-toggle="modal" data-target="#modal_dialog"><i class="fa fa-trash deletable"></i></a>
                                        </td>

                                    </tr>

                                    @endforeach
                                    @endif
                                    @if($parent_id)
                                   {{ CategoryTree($parent_id,'') }}
                                        @endif
                                        </form>

                                </tbody>
                            </table>

                        </div> <!-- table-responsive -->

                    </div><!-- /.box-body -->

        </div><!-- /.col -->

    </div><!-- /.row -->






 @endsection

<!-- Optional bottom section for modals etc... -->
@section('body_bottom')


@endsection
<script>
    function submitDelete(){
        $('#deleteForm').submit()
    }
</script>
