@extends('layouts.master')
@section('content')

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Sales Reports
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

<div class='row'>
    <div class='col-md-12'>
        <!-- Box -->
        {!! Form::open( array('route' => 'admin.cases.enable-selected', 'id' => 'frmClientList') ) !!}
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h2> All Sales and Marketing Reports</h2>
                </div>
                <div class="box-body">

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="cases-table">
                            <thead>
                                <tr>
                                    <th>Report Name</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td> {!! link_to_route('admin.reports.reports_leads_today', 'Daily Sales Report', [], []) !!}</td>
                                    <td>Leads obtained today</td>
                                </tr>
                                <tr>
                                    <td> {!! link_to_route('admin.reports.reports_leads_by_status', 'Leads By Status (Filter by Date)', [], []) !!}</td>
                                    <td>List of Leads By Status</td>
                                </tr>
                                <tr>
                                    <td> {!! link_to_route('admin.reports.reports_converted_leads', 'Converted Leads (Filter by Date)', [], []) !!}</td>
                                    <td>List of Converted Leads</td>
                                </tr>
                                <tr>
                                    <td> {!! link_to_route('admin.reports.reports_all_activities', 'Todays Activities', [], []) !!}</td>
                                    <td>List of all Actvities</td>
                                </tr>
                                <tr>
                                    <td> {!! link_to_route('admin.reports.reports_today_calls', 'Todays Calls', [], []) !!}</td>
                                    <td>Todays Calls</td>
                                </tr>
                                <tr>
                                    <td> {!! link_to_route('admin.reports.reports_all_contacts', 'Contacts', [], []) !!}</td>
                                    <td>List of all Contacts</td>
                                </tr>
                                <tr>
                                    <td> {!! link_to_route('admin.reports.reports_all_clients', 'Clients', [], []) !!}</td>
                                    <td>List of all Clients</td>
                                </tr>
                            </tbody>
                        </table>

                    </div> <!-- table-responsive -->

                </div><!-- /.box-body -->
            </div><!-- /.box -->
        {!! Form::close() !!}
    </div><!-- /.col -->

</div><!-- /.row -->
@endsection

