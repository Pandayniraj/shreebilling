@extends('layouts.master')

@section('head_extra')
<!-- jVectorMap 1.2.2 -->
<link href="{{ asset("/bower_components/admin-lte/plugins/jvectormap/jquery-jvectormap-1.2.2.css") }}" rel="stylesheet" type="text/css" />
<style>
	.filter_date { font-size:14px; }
</style>
@endsection

@section('content')

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{!! $page_description ?? "Page description" !!}</small>
    </h1>
    Current Fiscal Year: {{-- <strong>{{ FinanceHelper::cur_fisc_yr()->fiscal_year}}</strong> --}}
    {!! Form::select('fiscal_year', $all_fiscal_year, $fiscal->id, ['id'=>'active_fiscal_year']) !!}
    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
</section>

  <?php
      $startOfYear = FinanceHelper::cur_fisc_yr()->start_date;
      $endOfYear   = FinanceHelper::cur_fisc_yr()->end_date;
  ?>


<div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>{{ number_format($data_count['quotation']) }}</h3>

              <p>Quotations</p>
            </div>
            <div class="icon">
              <i class="fa fa-shopping-cart"></i>
            </div>
            <a href="#" class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
             <h3>{{ number_format($data_count['invoices']) }}</h3>

              <p>Invoices</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>{{ number_format($data_count['tax_invoices']) }}</h3>

              <p>Tax Invoices</p>
            </div>
            <div class="icon">
              <i class="ion ion-cash"></i>
            </div>
            <a href="#" class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>{{ number_format($data_count['purchase_bill']) }}</h3>

              <p>Puchase Bills</p>
            </div>
            <div class="icon">
              <i class="ion ion-add"></i>
            </div>
            <a href="#" class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
      </div>
 <div class='row'>

        <div class='col-md-12'>
           
            <div class="row">
            	<div class="col-md-6">
		           
		            <div class="box box-warning box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Quotes sent</h3>

             
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
             <div id='quotation-chart'></div>
            </div>
            <!-- /.box-body -->
          </div>

		            

               </div>
                <div class="col-md-6">


                    <div class="box box-default box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Monthly Invoices</h3>

             
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               <div id='invoice-chart'></div>
            </div>
            <!-- /.box-body -->
          </div>


                </div> <!-- col -->
               
            </div><!-- row -->

            <div class="row">
            	
                <div class="col-md-6">
                     <div class="box box-default box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Tax Invoice Monthly</h3>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div id='tax_invoice-chart'></div>
            </div>
            <!-- /.box-body -->
          </div>
                </div> <!-- col -->

                 <div class="col-md-6">
                     <div class="box box-default box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Purchase Bill Monthly</h3>

             
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div id='purchase_bill-chart'></div>
            </div>
            <!-- /.box-body -->
          </div>
                </div> <!-- col -->
               
            </div><!-- row -->


            <div class="row">
                 <div class="col-md-12">
                     <div class="box box-default box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Monthly Purchase & Sales Tax</h3>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                  <div id="monthly_purchase_and_sales_tax-chart"></div>
            </div>
            <!-- /.box-body -->
          </div>
                </div>
            </div>




            <div class="row">
                 <div class="col-md-6">
                     <div class="box box-default box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Income By Customer</h3>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                  <div id="customer_income-chart"></div>
            </div>
            <!-- /.box-body -->
          </div>
                </div>
              <div class="col-md-6">
                     <div class="box box-default box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Purcahse By Supplier</h3>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                  <div id="supplier_purch-chart"></div>
            </div>
            <!-- /.box-body -->
          </div>
                </div>
            </div>



            <div class="row">
                 <div class="col-md-6">
                     <div class="box box-default box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Sales By Location</h3>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                  <div id="sale_location-chart"></div>
            </div>
            <!-- /.box-body -->
          </div>
                </div>
              <div class="col-md-6">
                     <div class="box box-default box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Purcahse By Location</h3>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                  <div id="purch_loaction-chart"></div>
            </div>
            <!-- /.box-body -->
          </div>
                </div>
            </div>


             <div class="row">
              
                <div class="col-md-6">
                     <div class="box box-default box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Income Renewals</h3>
              <!-- /.box-tools -->
              <select class="pull-right" id='income-renewals'>
                <option value="order">Order income</option>
                <option value="invoice">Invoice income</option>
              </select>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div id='order_income-chart'></div>
                <div id='invoice_income-chart' style="display: none"></div>
            </div>
            <!-- /.box-body -->
          </div>
                </div> <!-- col -->

                 <div class="col-md-6">
                     <div class="box box-default box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Expence Renewals</h3>

             
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div id='expense_income-chart'></div>
            </div>
            <!-- /.box-body -->
          </div>
                </div> <!-- col -->
               
            </div>

              <div class="row">
            	
                <div class="col-md-12">
                     <div class="box box-default box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Sales By Product</h3>

             
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div id='product_sales-chart'></div>
            </div>
            <!-- /.box-body -->
          </div>
                </div> <!-- col -->
               
            </div><!-- row -->


              <div class="row">
                
                <div class="col-md-12">
                     <div class="box box-default box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Purchase  By Product</h3>

             
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                 <div id='product_purchase-chart'></div>
            </div>
            <!-- /.box-body -->
          </div>
                </div> <!-- col -->
               
            </div>




        </div>
</div>






@endsection

@section('body_bottom')
  <link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
  <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
    <!-- ChartJS -->
    <!-- <script src="{{ asset ("/bower_components/admin-lte/plugins/chartjs/Chart.min.js") }}" type="text/javascript"></script> -->
    <script src="{{ asset ("/bower_components/highcharts/highcharts.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/highcharts/funnel.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/highcharts/highcharts-3d.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/highcharts/exporting.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/highcharts/export-data.js") }}" type="text/javascript"></script>
    
    <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
  <script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>

<script>

   const quotation_data =  <?php echo json_encode($quotation_data) ?>;
   const invoices_data = <?php echo json_encode($invoices_data) ?>;
   const tax_invoices_data= <?php echo json_encode($tax_invoices_data) ?>;
   const purchase_bill_data = <?php echo json_encode($purchase_bill_data) ?>;
   const order_income_data = <?php echo json_encode($order_income_data) ?>;
   const invoice_income_data = <?php echo json_encode($invoice_income_data) ?>;
   const expense_data =  <?php echo json_encode($expense_data) ?>;
   const product_sales_data =  <?php echo json_encode($product_sales_data) ?>;
   const product_purchase_data = <?php echo json_encode($product_purchase_data) ?>;
   const sales_by_loc_data = <?php echo json_encode($sales_by_loc_data) ?>;
   const purc_by_location_data = <?php echo json_encode($purc_by_location_data) ?>;
   const customer_income_data =  <?php echo json_encode($customer_income_data) ?>;
   const purch_by_supplier_data =  <?php echo json_encode($purch_by_supplier_data) ?>;
   console.log(order_income_data);
     $(function () {

  Highcharts.chart('quotation-chart', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Quotations Amount of Fiscal Year {{ $fiscal->fiscal_year }}'
    },
    subtitle: {
        text: ''
    },
    accessibility: {
        announceNewData: {
            enabled: true
        }
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: 'Total Amount By Month'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y}'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b> {{ env('APP_CURRENCY') }} {point.y}</b>'
    },

    series: [
        {
            name: "quotation",
            colorByPoint: true,
            data:quotation_data,
        }
    ],
    
});
		

Highcharts.chart('invoice-chart', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Invoice Amount of Fiscal Year {{ $fiscal->fiscal_year }}'
    },
    subtitle: {
        text: ''
    },
    accessibility: {
        announceNewData: {
            enabled: true
        }
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: 'Total Amount By Month'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y}'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b> {{ env('APP_CURRENCY') }} {point.y} </b>'
    },

    series: [
        {
            name: "invoices",
            colorByPoint: true,
            data:invoices_data,
        }
    ],
    
});



Highcharts.chart('tax_invoice-chart', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Tax Invoice Amount of Fiscal Year {{ $fiscal->fiscal_year }}'
    },
    subtitle: {
        text: ''
    },
    accessibility: {
        announceNewData: {
            enabled: true
        }
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: 'Total Amount By Month'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y}'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{{ env('APP_CURRENCY') }} {point.y} </b>'
    },

    series: [
        {
            name: "tax invoice",
            colorByPoint: true,
            data:tax_invoices_data,
        }
    ],
    
});

Highcharts.chart('purchase_bill-chart', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Purchase Bill Amount of Fiscal Year {{ $fiscal->fiscal_year }}'
    },
    subtitle: {
        text: ''
    },
    accessibility: {
        announceNewData: {
            enabled: true
        }
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: 'Total Amount By Month'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y}'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{{ env('APP_CURRENCY') }} {point.y} </b>'
    },

    series: [
        {
            name: "purchase bill",
            colorByPoint: true,
            data:purchase_bill_data,
        }
    ],
    
});



Highcharts.chart('order_income-chart', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Order Income Renewals of Fiscal Year {{ $fiscal->fiscal_year }}'
    },
    subtitle: {
        text: ''
    },
    accessibility: {
        announceNewData: {
            enabled: true
        }
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: 'Total Amount By Month'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y}'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{{ env('APP_CURRENCY') }} {point.y} </b>'
    },

    series: [
        {
            name: "order income",
            colorByPoint: true,
            data:order_income_data,
        }
    ],
    
});



Highcharts.chart('invoice_income-chart', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Invoice Income Renewals of Fiscal Year {{ $fiscal->fiscal_year }}'
    },
    subtitle: {
        text: ''
    },
    accessibility: {
        announceNewData: {
            enabled: true
        }
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: 'Total Amount By Month'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y}'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{{ env('APP_CURRENCY') }} {point.y} </b>'
    },

    series: [
        {
            name: "invoice income",
            colorByPoint: true,
            data:invoice_income_data,
        }
    ],
    
});




Highcharts.chart('expense_income-chart', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Expence Income Renewals of Fiscal Year {{ $fiscal->fiscal_year }}'
    },
    subtitle: {
        text: ''
    },
    accessibility: {
        announceNewData: {
            enabled: true
        }
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: 'Total Amount By Month'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y}'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{{ env('APP_CURRENCY') }} {point.y} </b>'
    },

    series: [
        {
            name: "Expence amount",
            colorByPoint: true,
            data:expense_data,
        }
    ],
    
});



Highcharts.chart('product_sales-chart', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Product Sales '
    },
    subtitle: {
        text: ''
    },
    accessibility: {
        announceNewData: {
            enabled: true
        }
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: 'Total Amount By Products'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y}'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{{ env('APP_CURRENCY') }} {point.y} </b>'
    },

    series: [
        {
            name: "product sale",
            colorByPoint: true,
            data:product_sales_data,
        }
    ],
    
});


Highcharts.chart('product_purchase-chart', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Product Purchase '
    },
    subtitle: {
        text: ''
    },
    accessibility: {
        announceNewData: {
            enabled: true
        }
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: 'Total Amount By Products'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y}'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{{ env('APP_CURRENCY') }} {point.y} </b>'
    },

    series: [
        {
            name: "product purchase",
            colorByPoint: true,
            data:product_purchase_data,
        }
    ],
    
});
 $('#sale_location-chart').highcharts({
      chart: {
        type: 'pie',
        options3d: {
          enabled: true,
          alpha: 45,
          beta: 0
        }
      },
      title: {
        text: 'Sales By Location'
      },
      tooltip: {
        pointFormat: '{point.name}: <b>{point.percentage:.1f}%</b> {{ env('APP_CURRENCY') }} {point.y}'
      },
      plotOptions: {
        pie: {
          allowPointSelect: true,
          cursor: 'pointer',
          depth: 35,
          dataLabels: {
            enabled: true,
            format: '{point.name}: <b>{point.percentage:.1f}%</b> {{ env('APP_CURRENCY') }} {point.y}'
          }
        }
      },
      series: [{
        type: 'pie',
        name: 'Sales By Location',
        data: sales_by_loc_data
      }]
    });


 $('#purch_loaction-chart').highcharts({
      chart: {
        type: 'pie',
        options3d: {
          enabled: true,
          alpha: 45,
          beta: 0
        }
      },
      title: {
        text: 'Purcahse By Location'
      },
      tooltip: {
        pointFormat: '{point.name}: <b>{point.percentage:.1f}%</b> {{ env('APP_CURRENCY') }} {point.y}'
      },
      plotOptions: {
        pie: {
          allowPointSelect: true,
          cursor: 'pointer',
          depth: 35,
          dataLabels: {
            enabled: true,
            format: '{point.name}: <b>{point.percentage:.1f}%</b> {{ env('APP_CURRENCY') }} {point.y}'
          }
        }
      },
      series: [{
        type: 'pie',
        name: 'Purch By Location',
        data: purc_by_location_data
      }]
    });
 $('#customer_income-chart').highcharts({
      chart: {
        type: 'pie',
        options3d: {
          enabled: true,
          alpha: 45,
          beta: 0
        }
      },
      title: {
        text: 'Income By Customer'
      },
      tooltip: {
        pointFormat: '{point.name}: <b>{point.percentage:.1f}%</b> {{ env('APP_CURRENCY') }} {point.y}'
      },
      plotOptions: {
        pie: {
          allowPointSelect: true,
          cursor: 'pointer',
          depth: 35,
          dataLabels: {
            enabled: true,
            format: '{point.name}: <b>{point.percentage:.1f}%</b> {{ env('APP_CURRENCY') }} {point.y}'
          }
        }
      },
      series: [{
        type: 'pie',
        name: 'Income By Customer',
        data: customer_income_data,
      }]
    });

 $('#supplier_purch-chart').highcharts({
      chart: {
        type: 'pie',
        options3d: {
          enabled: true,
          alpha: 45,
          beta: 0
        }
      },
      title: {
        text: 'Purchased By Supplier'
      },
      tooltip: {
        pointFormat: '{point.name}: <b>{point.percentage:.1f}%</b> {{ env('APP_CURRENCY') }} {point.y}'
      },
      plotOptions: {
        pie: {
          allowPointSelect: true,
          cursor: 'pointer',
          depth: 35,
          dataLabels: {
            enabled: true,
            format: '{point.name}: <b>{point.percentage:.1f}%</b> {{ env('APP_CURRENCY') }} {point.y}'
          }
        }
      },
      series: [{
        type: 'pie',
        name: 'Purchased By Supplier',
        data: purch_by_supplier_data,
      }]
    });


 Highcharts.chart('monthly_purchase_and_sales_tax-chart', {

    title: {
        text: 'Purchase & Sales tax Amount of fiscal year {{  $fiscal->fiscal_year }}'
    },

    subtitle: {
       enabled:false,
    },

    yAxis: {
        title: {
            text: 'Amount in {{ env('APP_CURRENCY') }} '
        }
    },

     xAxis: {
         categories: <?php echo json_encode($line_chart_purchasetax_salestax_categories); ?>
     },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },


    series:  <?php echo json_encode($line_chart_purchasetax_salestax); ?>,

    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    }

});




$('#income-renewals').change(function(){
  let type = $(this).val();
  if(type == 'order'){
    $('#invoice_income-chart').hide();
    $('#order_income-chart').show();
  }else{
    $('#invoice_income-chart').show();
    $('#order_income-chart').hide();
  }
});

});
	
$('#active_fiscal_year').change(function(){
  let val = $(this).val();
  location.href = `/admin/salesboard/?fiscal_year=${val}`;
})    
</script>


@endsection