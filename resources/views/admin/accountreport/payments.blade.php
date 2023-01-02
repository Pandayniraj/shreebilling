
@extends('layouts.master')
@section('content')


    <?php
    $startOfYear = FinanceHelper::cur_fisc_yr()->start_date;

    $endOfYear   = FinanceHelper::cur_fisc_yr()->start_date;
    ?>

    <link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
    <script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

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
{{--            <div class="box-header with-border">--}}
{{--                <h3 class="box-title">Report - Payment/Receipt</h3>--}}
{{--            </div>--}}
            <!-- /.box-header -->
            <div class="box-body">
                <!-- <div id="accordion"> -->
                <!-- <h3>Options</h3> -->
                <div class="balancesheet form">
                    <form  method="GET" action="/admin/payment-report">

                        <div class="row" style="display: flex">
                            <div style="margin-left: 15px">
                                <div class="form-group">
                                    <label>Fiscal Year</label>
                                    <div class="input-group">
                                        <select id="fiscal_year_id" class="form-control input-sm" name="fiscal_year" required="required">
                                            @foreach($allFiscalYear as $key => $pk)
                                                <option value="{{ $pk->fiscal_year }}" {{$fiscal_year==$pk->fiscal_year?'selected':''}}>{{ $pk->fiscal_year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                            </div>
                            <?php
                            $fiscal=\App\Models\Fiscalyear::where('fiscal_year',$fiscal_year)->first();
                            ?>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <div class="input-group">
                                        <input id="ReportStartdate" type="text" name="startdate" class="form-control input-sm datepicker" value="{{$fiscal->start_date}}" >
                                        <div class="input-group-addon">
                                            <i>
                                                <div class="fa fa-info-circle" data-toggle="tooltip" title="Note : Leave start date as empty if you want statement from the start of the financial year.">
                                                </div>
                                            </i>
                                        </div>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>End Date</label>

                                    <div class="input-group">
                                        <input id="ReportEnddate" type="text" name="enddate" class="form-control input-sm datepicker"
                                               value="{{$fiscal->end_date}}">
                                        <div class="input-group-addon">
                                            <i>
                                                <div class="fa fa-info-circle" data-toggle="tooltip" title="Note : Leave end date as empty if you want statement till the end of the financial year.">
                                                </div>
                                            </i>
                                        </div>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                            </div>


                                <div style="margin-left: 15px">
                                <div class="form-group">
                                    <label>Type</label>
                                    <div class="input-group">
                                        <select id="type" class="form-control input-sm" name="type" required="required">
                                                <option value="payment" {{request('type')=='payment'?'selected':''}}>Payment</option>
                                                <option value="receipt" {{request('type')=='receipt'?'selected':''}}>Receipt</option>
                                        </select>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                            </div>

                            {{-- <div class="col-md-2">
                                <div class="form-group">
                                    <label>Tags</label>

                                    <div class="input-group">
                                        {!! Form::select('id', ['' => 'Select Tags'] + $tags, \Request::get('user_id'), ['id'=>'filter-user', 'class'=>'form-control', 'style'=>'width:120px; display:inline-block;']) !!}

                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Currency</label>

                                    <div class="input-group">
                            <select class="form-control select2 currency" name="currency" required="required">

                                @foreach($currency as $k => $v)
                                <option value="{{ $v->currency_code }}" @if(isset($v->currency_code) && $v->currency_code == $v->currency_code) selected="selected" @endif>
                                    {{ $v->name }} {{ $v->currency_code }} ({{ $v->currency_symbol }})</option>
                                @endforeach

                            </select>

                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                            </div> --}}
                            <div style="margin-left: 10px">
                                <label>
                                    <label></label>
                                </label>
                                <div class="form-group">
                                    {{--                            <input type="reset" name="reset" class="btn btn-danger pull-right" style="margin-left: 5px;" value="Clear">--}}
                                    <input type="submit" class="btn btn-primary" value="Submit">
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <!-- </div> -->


                <div class="box-body table-responsive">
              <table class="table table-bordered" style="border: 1px solid #c4b8b8;">
                <thead>
                    <tr class="bg-info"><th>Num</th>
                        <th>Date</th>
                        <th>Miti</th>
                        <th>Bill #</th>

                        <th>Vendor</th>

                        <th>Cash/Bank</th>
                         <th>Tag</th>
                        <th>Type</th>
                        <th>Entry Source</th>
                        <th>Amount</th>


                    </tr>
                </thead>
                <tbody id='entry_list'>
                <?php
                    $total=0
                ?>
                   @foreach($entryitems as $ei)
                        <tr><td><a href="/admin/entries/show/{{$ei->dynamicEntry()->entrytype->label}}/{{$ei->dynamicEntry()->id}}">{{$ei->dynamicEntry()->number}}</a></td>
                            <td style="white-space: nowrap;">{{$ei->dynamicEntry()->date}}</td>
                            <td style="white-space: nowrap;">{{ \TaskHelper::getNepaliDate($ei->dynamicEntry()->date) }}</td>
                            <td>{{ $ei->dynamicEntry()->dynamicSessionBillNum() }}</td>

                            <td style="white-space: nowrap;">
                                <?php
                                $vendor=\TaskHelper::getDynamicEntryLedgerName($ei->ledger_id);
                                ?>
                                {{$vendor->name}}
                            <div style="color: gray;font-size: 11px">{{$ei->narration}}</div>
                            </td>
                                <td>XXX</td>
                            <td>
                                <span class="label {{$ei->dynamicEntry()->tagname->color}}">{{$ei->dynamicEntry()->tagname->title}}</span>

                            </td>
                            <td>
                            {{$ei->dynamicEntry()->entrytype->name}}

                            </td>
                            <td>{{$ei->dynamicEntry()->source}}
                                <br>
                                {!! $ei->dynamicEntry()->checkLedger() !!}
                                @if($ei->dynamicEntry()->entry_difference!=0)
                                <br>
                                {{$ei->dynamicEntry()->entry_difference}}
                                    @endif
                            </td>

                            <td>
                                <a href="#" class="tip">{{ $ei->dynamicEntry()->currency}} {{number_format($ei->dynamicEntry()->dr_total,2)}}
                                    <span>{{$ei->dynamicEntry()->narration}}</span></a>
                            </td>


                        </tr>
                       <?php
                       $total+=$ei->amount;
                       ?>
                    @endforeach
                <tr>
                    <th colspan="9" style="text-align: right">Total</th>
                    <th>{{number_format($total,2)}}</th>
                </tr>
                  </tbody>
                </table>
                 <div style="text-align: center;"> {!! $entryitems->appends(\Request::except('page'))->render() !!} </div>
            </div>

            </div>
        </div>
    </div>


    <script type="text/javascript">
        $(function() {
            $('.datepicker').datetimepicker({
                //inline: true,
                format: 'YYYY-MM-DD',
                sideBySide: true,
                allowInputToggle: true
            });

        });
    </script>


    <script>
        $(document).ready(function () {
            $('.select2').select2()
        })

        $(document).on('change','#fiscal_year_id',function () {
            var fiscal_year = $(this).val()
            var fiscal_detail = ''
            var all_fiscal_years = {!! json_encode($allFiscalYear); !!}
            all_fiscal_years.forEach((item) => {
                if (item.fiscal_year == fiscal_year)
                    fiscal_detail = item
            });

            $('#ReportStartdate').val(fiscal_detail.start_date)
            $('#ReportEnddate').val(fiscal_detail.end_date)

        })
            // $.ajax({
            //     type: "GET",
            //     contentType: "application/json; charset=utf-8",
            //     url: "/admin/get-fiscal-year?fiscal_year=" + fiscal_year,
            //     success: function (result) {
            //         debugger
            //         if (result.fiscal_year){
            //
            //         }
            //     }
            // })
        // })


    </script>


@endsection

<!-- Optional bottom section for modals etc... -->
@section('body_bottom')


@endsection
