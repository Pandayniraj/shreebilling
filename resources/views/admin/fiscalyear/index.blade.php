@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {!! $page_title ?? "Fiscal Year" !!}
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
              Current Fiscal Year: <strong>{{ FinanceHelper::cur_fisc_yr()->fiscal_year}}</strong>

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

    <div class='row'>
        <div class='col-md-12'>

            <?php
            $tables_for_migration = [
            'credit_notes',
            'credit_notes_detail',
            'expenses',
            'expenses_edit_history',
            'bank_income',
            'product_stock_master',
            'invoice_payment',
            'payments',
            'bill_print_invoice',
            'coa_ledgers',
            'product_stock_moves',
            'entries',
            'entryitems',
            'entry_item_details',
            'fin_orders',
            'fin_order_detail',
            'fin_order_payment_terms',
            'invoice',
            'invoice_detail',
            'invoice_meta',
            'purch_orders',
            'purch_order_details',
            ];
            ?>

            <div class="btn-group">
                <button type="button" style="margin-left: 10px;" class="btn btn-default btn-sm dropdown-toggle pull-right" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Migrate Data</button>
                <ul class="dropdown-menu">
                    @php
                        $last_fiscal_year = App\Models\Fiscalyear::where('org_id', \Auth::user()->org_id)->where('current_year',0)->latest()->first();
                    @endphp
                    @foreach($tables_for_migration as $table)
                        @if (!Schema::hasTable($last_fiscal_year->numeric_fiscal_year??'' . '_' .$table))
                        <li> <a href="/admin/migrate-data/{{$table}}">{{ $table}}</a></li>
                        @endif
                    @endforeach
                </ul>
            </div>
            <!-- Box -->
            {!! Form::open( array('route' => 'admin.fiscalyear.enable-selected', 'id' => 'frmFiscalYearList') ) !!}
                <div class="box box-primary">
                    <div class="box-header with-border">
                       @if(\Auth::user()->hasRole('admins'))
                        <a class="btn btn-default btn-sm" href="{!! route('admin.fiscalyear.create') !!}" title="{{ trans('admin/fiscalyear/general.button.create') }}">
                            <i class="fa fa-plus-square"></i> &nbsp;Add
                        </a>
                        @endif
                        &nbsp;


                        <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmFiscalYearList'].action = '{!! route('admin.fiscalyear.enable-selected') !!}';  document.forms['frmFiscalYearList'].submit(); return false;" title="{{ trans('general.button.enable') }}">
                            <i class="fa fa-check-circle"></i>
                        </a>


                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="FiscalYear-table">
                                <thead>
                                    <tr class="bg-maroon">
                                        <th style="text-align: center">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        </th>
                                        <th>Fiscal Year</th>
                                        <th> Numeric Year # </th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th> Action </th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th style="text-align: center">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        </th>
                                        <th>Fiscal Year</th>
                                        <th> Numeric Year # </th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th> Action </th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach($allFiscalYear as $v)
                                      @if($v->current_year == 1)
                                        <tr>
                                            <td align="center" class="bg-success">
                                                <input type="radio" name="chkFiscalYear" value="{{$v->id}}" @if($v->current_year) checked @endif>
                                                <!-- {!! Form::radio('chkFiscalYear[]', $v->id); !!} -->
                                            </td>
                                            <td class="bg-success">{!! $v->fiscal_year !!}</td>
                                            <td class="bg-success"> {{ $v->numeric_fiscal_year}}</td>
                                            <td class="bg-success"> {{ $v->start_date}}</td>
                                            <td class="bg-success"> {{ $v->end_date}}</td>
                                            <td class="bg-success">
                                                @if( $v->isEditable() || $v->canChangePermissions() )
                                                    <a href="{!! route('admin.fiscalyear.edit', $v->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                                @else
                                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/fiscalyear/general.error.cant-edit-this-FiscalYear') }}"></i>
                                                @endif
                                                @if( $v->isDeletable() )
                                                    <a href="{!! route('admin.fiscalyear.confirm-delete', $v->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                                @else
                                                    <i class="fa fa-trash text-muted" title="{{ trans('admin/fiscalyear/general.error.cant-delete-this-FiscalYear') }}"></i>
                                                @endif
                                            </td>
                                        </tr>
                                        @else
                                         <tr>
                                        <td align="center">
                                      <input type="radio" name="chkFiscalYear" value="{{$v->id}}" @if($v->current_year) checked @endif>
                                              <!--   {!! Form::radio('chkFiscalYear[]', $v->id); !!} -->
                                          </td>
                                            <td>{!! $v->fiscal_year !!}</td>
                                            <td> {{ $v->numeric_fiscal_year}}</td>
                                            <td> {{ $v->start_date}}</td>
                                            <td> {{ $v->end_date}}</td>
                                            <td>
                                                @if( $v->isEditable() || $v->canChangePermissions() )
                                                    <a href="{!! route('admin.fiscalyear.edit', $v->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                                @else
                                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/fiscalyear/general.error.cant-edit-this-FiscalYear') }}"></i>
                                                @endif
                                                @if( $v->isDeletable() )
                                                    <a href="{!! route('admin.fiscalyear.confirm-delete', $v->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                                @else
                                                    <i class="fa fa-trash text-muted" title="{{ trans('admin/fiscalyear/general.error.cant-delete-this-FiscalYear') }}"></i>
                                                @endif
                                            </td>
                                        </tr>
                                        @endif

                                    @endforeach
                                </tbody>
                            </table>
                            {!! $allFiscalYear->render() !!}
                        </div> <!-- table-responsive -->

                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            {!! Form::close() !!}
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<!-- DataTables -->
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

    <script language="JavaScript">
        function toggleCheckbox() {
            checkboxes = document.getElementsByName('chkFiscalYear[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = !checkboxes[i].checked;
            }
        }
    </script>

    <script>
    $(function() {
        $('#FiscalYear-table').DataTable({

        });
    });
    </script>

@endsection
