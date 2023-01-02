@extends('layouts.master')
@section('content')

<?php

function CategoryTree($parent_id=null,$sub_mark=''){

  $groups= \App\Models\COAgroups::orderBy('code', 'asc')->where('org_id',auth()->user()->org_id)->where('parent_id',$parent_id)->get();

    if(count($groups)>0){
      foreach($groups as $group){ 

           echo '<tr>
                   
                    <td><b>'.$sub_mark.'['.$group->code.']'.$group->name.'</b></td>
                    <td><b>0.00</b></td>
                 </tr>';

        $ledgers= \App\Models\COALedgers::orderBy('code', 'asc')->where('group_id',$group->id)->get(); 
        if(count($ledgers)>0){
            $submark= $sub_mark;
            $sub_mark = $sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 

              foreach($ledgers as $ledger){
             $closing_balance =TaskHelper::getLedgerDebitCredit($ledger->id); 
             if ($closing_balance['amount'] > 0) {
                if($closing_balance['dc'] == 'D'){

                    echo '<tr style="color: #009551;">
                     
                      <td class="bg-warning"><a href="'.route('admin.chartofaccounts.detail.ledgers', $ledger->id).'" style="color:#009551;">'.$sub_mark.'['.$ledger->code.']'.$ledger->name.'</a></td>
                       <td class="bg-warning"><b>Dr '.$closing_balance['amount'].'</b></td>
                     </tr>';

               }else{

                    echo '<tr style="color: #009551;">
                        
                        <td class="bg-danger"><a href="'.route('admin.chartofaccounts.detail.ledgers', $ledger->id).'" style="color:#009551;">'.$sub_mark.'['.$ledger->code.']'.$ledger->name.'</a></td>
                         <td class="bg-danger"><b>Cr '.$closing_balance['amount'].'</b></td>
                     </tr>';
              }
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
                {{ $page_title }}
                <small>  {!! $page_description ?? "Page description" !!}</small>  
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

      <div class="box">
    <div class='row'>
        <div class='col-md-6'>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="orders-table">
                                <thead>
                                    <tr class="bg-blue">
                                       
                                        <th>Gross Expenses (Dr)</th>
                                
                                        <th>Amount(P)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   {{ CategoryTree(4,'') }}
                                </tbody>
                            </table>
                        </div> <!-- table-responsive -->
                    </div><!-- /.box-body -->
        </div><!-- /.col -->
        <div class='col-md-6'>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="orders-table">
                                <thead>
                                    <tr class="bg-olive">
                                        
                                        <th>Gross Incomes (Cr)</th>

                                        <th>Amount (P)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   {{ CategoryTree(3,'') }}
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
