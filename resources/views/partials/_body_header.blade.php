<style>
    #notviewed-leads_length {
        float: left;
    }

    #notviewed-leads_filter {
        float: right;
    }

    @media (max-width: 767px) {
        .MobileView {
            display: none;
        }
    }

    @media (min-width: 767px) {

        .DeskView {
            display: none;
        }
    }

</style>
<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />

<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>
<script type="text/javascript">
    function getCookie(name) {

        const value = `; ${document.cookie}`;

        const parts = value.split(`; ${name}=`);

        if (parts.length === 2) return parts.pop().split(';').shift();

    }



    if (getCookie(('sidebar_collapse'))) {

        $('.sidebar-mini').addClass('sidebar-collapse');

    }

</script>
<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="{{ route('home') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->

        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">@auth
            <?php  echo TaskHelper::GetOrgName(\Auth::user()->org_id)->organization_name ?>
            @endauth
        </span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <ul class="nav navbar-nav MobileView">


            <li>
                <a href="/admin/finance/dashboard" class=""><i class="fa fa-home"></i>  {{ trans('general.navbar.home') }} </a>
            </li>
           @auth
            <li>
                <a href="#"><i class="fa fa-calendar-plus-o"></i>&nbsp;{{ FinanceHelper::cur_fisc_yr()->fiscal_year }}</a>
            </li>
            @if(\Auth::user()->hasRole(['admins','account-staff']))
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">  Account <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
{{--                 <li><a href="/admin/entries?&entries_type_id=3"><i class="fa fa-check-circle"> </i> Bank Transfer</a></li>--}}
                <li><a href="/admin/entries/add/salary"><i class="fa fa-check-circle"> </i> Salary</a></li>
{{--                <li><a href="/admin/bank"><i class="fa fa-file"> </i>Income</a></li>--}}
                <li><a href="/admin/entries/add/receipt"><i class="fa fa-file"> </i>Receipt</a></li>
                <li><a href="/admin/entries/add/journal"><i class="fa fa-file"> </i>Journal Voucher</a></li>
<li><a href="/admin/orders/payment_term/emi_list"><i class="fa fa-file"> </i>EMI Collection</a></li>

                <li class="divider"></li>
                <li><a href="/admin/expenses"><i class="fa fa-file"> </i>Expense</a></li>
                 <li><a href="/admin/entries/add/paymanet"><i class="fa fa-file"> </i>Payments</a></li>
                <li class="divider"></li>
                <li><a href="/admin/cash_in_out"><i class="fa fa-book"> </i>Daybook</a></li>
                <li><a href="/admin/finance/dashboard"><i class="fa fa-pie-chart"> </i>Analysis</a></li>


              </ul>
            </li>
            @endif

            @if(\Auth::user()->hasRole(['admins','hr-staff']))
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">  HRM <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                 <li><a href="/admin/employee/directory"><i class="fa fa-check-circle"> </i> Employee Directory</a></li>
                <li><a href="/admin/hrcalandar"><i class="fa fa-check-circle"> </i> HR Calendar</a></li>
                <li><a href="/admin/leavereport"><i class="fa fa-file"> </i>Leave Report</a></li>
                <li><a href="/admin/job_applied"><i class="fa fa-file"> </i>Job Applications</a></li>
                <li class="divider"></li>
                <li><a href="/admin/shiftAttendance"><i class="fa fa-file"> </i>Daily Register</a></li>

              </ul>
            </li>
            @endif

            @if(\Auth::user()->hasRole(['admins','sales-staff']))
             <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">  CRM <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                 <li><a href="/admin/leads?type=leads"><i class="fa fa-check-circle"> </i> Leads</a></li>
                <li><a href="/admin/leads?type=target"><i class="fa fa-check-square"> </i> Online Leads</a></li>
                <li><a href="/admin/orders?type=quotation"><i class="fa fa-file"> </i>Quotations</a></li>
                <li><a href="/admin/proposal"><i class="fa fa-file"> </i>Contracts</a></li>
                <li class="divider"></li>
                <li><a href="/admin/orders?type=invoice"><i class="fa fa-file"> </i>Proforma Invoice</a></li>
                <li><a href="/admin/ticket"><i class="fa fa-file"> </i>Support Ticket</a></li>



              </ul>
            </li>
            @endif

            <?php $orgs=\App\Models\Organization::pluck('organization_name','id')->all();
            ?>
            @if(\Auth::user()->super_manager == 1)

            <form class="navbar-form navbar-left" role="search">
                <div class="form-group">
                    {!! Form::select('org_id', [''=>'Select Org']+$orgs, \Auth::user()->org_id, ['class' => 'form-control input-sm label-default', 'id' => "header_org_id"]) !!}
                    <input type="hidden" name="header_user_id" id="header_user_id" value="{{\Auth::user()->id}}">
                </div>
            </form>

            @endif
            @endauth

        </ul>


        <div class="navbar-custom-menu DeskView">

            <ul class="nav navbar-nav">

                <li>
                    <a href="#" class=""><i class="fa fa-home"></i> Home </a>
                </li>
                <li>
                    <a href="/admin/talk"> Chat </a>
                </li>

                @if (Auth::check())

                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->

                        @if(\Auth::user()->image)
                        <img src="/images/profiles/{{\Auth::user()->image}}" class="user-image" alt="User Image" />
                        @else
                        <img src="{{ Gravatar::get(Auth::user()->email , 'tiny') }}" class="user-image" alt="User Image" />
                        @endif


                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">{{ Auth::user()->username }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            @if(\Auth::user()->image)
                            <img src="/images/profiles/{{\Auth::user()->image}}" wi class="img-circle" alt="User Image" />
                            @else
                            <img src="{{ Gravatar::get(Auth::user()->email , 'tiny') }}" class="img-circle" alt="User Image" />
                            @endif
                            <p>
                                {{ Auth::user()->full_name }}
                                <small>
                                    {{ PayrollHelper::getDepartment(\Auth::user()->departments_id) }},
                                    {{ PayrollHelper::getDesignation(\Auth::user()->designations_id) }}
                                </small>
                            </p>



                        </li>

                        @if( \Config::get('settings.app_extended_user_menu') )
                        <!-- Menu Body -->
                        <!--      <li class="user-body">
                                    <div class="col-xs-4 text-center">
                                        <a href="#">My PIS</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Sales</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Friends</a>
                                    </div>
                                </li> -->
                        @endif

                        <!-- Menu Footer-->
                        <li class="user-footer">

                            @if ( \Config::get('settings.app_user_profile_link') )
                            <div class="pull-left">
                                {!! link_to_route('user.profile', 'Profile', [], ['class' => "btn btn-default btn-flat"]) !!}
                            </div>
                            @endif

                            <div class="pull-right">
                                <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" class="btn btn-default btn-flat">
                                    Logout
                                </a>
                            </div>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </li>

                @else
                <li>{!! link_to_route('login', 'Sign in') !!}</li>
                @if (\Config::get('settings.app_allow_registration'))
                <li>{!! link_to_route('register', 'Register') !!}</li>
                @endif

                @endif


            </ul>
        </div>

        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu MobileView">


            <ul class="nav navbar-nav ">

                @if (Auth::check())





            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"> <span class="material-icons">add_box</span> <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                 <li><a href="/admin/leads/create?type=leads"><i class="fa fa-check-square"> </i> New Lead</a></li>
                <li><a href="/admin/contacts/create"><i class="fa fa-check-square"> </i> New Contact</a></li>
                <li><a href="/admin/entries"><i class="fa fa-file"> </i> New Voucher</a></li>
                <li class="divider"></li>
                <li><a href="/admin/orders?type=quotation"><i class="fa fa-file"> </i> New Quotation</a></li>
                <li class="divider"></li>
                <li><a href="/admin/leave_management"><i class="fa fa-clock-o"> </i> New Leave</a></li>
              </ul>
            </li>




                @if ( \Config::get('settings.app_context_help_area') && (isset($context_help_area)))
                {!! $context_help_area !!}
                @endif

                @if(\Config::get('settings.app_notification_area'))
                <!-- Messages: style can be found in dropdown.less-->


                <!-- Notifications Menu -->
                <li class="dropdown notifications-menu ">
                    <a href="/admin/due_payments">
                         <i class="material-icons">money</i>
                        <span class="label label-warning">{!! isset($not_received_emi) ? sizeof($not_received_emi) : '' !!}</span>
                    </a>
                </li>

                <!-- Tasks Menu -->
                <li class="dropdown tasks-menu" style="margin-left: -11px;">
                    <!-- Menu Toggle Button -->
                    <a href="/admin/due_marketing_tasks">
                        <i class="material-icons">emoji_flags</i>
                        <span class="label label-danger">{!! isset($due_marketing_tasks) ? sizeof($due_marketing_tasks) :"" !!}</span>
                    </a>
                </li>
                <li style="margin-left: -11px;">
                    <a href="/admin/stickynote" ><i class="material-icons">sticky_note_2</i></a>
                </li>
                <li style="margin-left: -11px;">
                    <a href="/admin/calendar" ><i class="material-icons">date_range</i></a>
                </li>
                <li style="margin-left: -11px;">
                    <a href="/admin/talk" > <i class="material-icons">chat</i></a>
                </li>



                @endif
                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->

                        @if(\Auth::user()->image)
                        <img src="/images/profiles/{{\Auth::user()->image}}" class="user-image" alt="User Image" />
                        @else
                        <img src="{{ Gravatar::get(Auth::user()->email , 'tiny') }}" class="user-image" alt="User Image" />
                        @endif


                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">{{ Auth::user()->username }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            @if(\Auth::user()->image)
                            <img src="/images/profiles/{{\Auth::user()->image}}" wi class="img-circle" alt="User Image" />
                            @else
                            <img src="{{ Gravatar::get(Auth::user()->email , 'tiny') }}" class="img-circle" alt="User Image" />
                            @endif
                            <p>
                                {{ Auth::user()->full_name }}
                                <small>
                                    {{ PayrollHelper::getDepartment(\Auth::user()->departments_id) }},
                                    {{ PayrollHelper::getDesignation(\Auth::user()->designations_id) }}
                                </small>
                            </p>



                        </li>

                        @if( \Config::get('settings.app_extended_user_menu') )
                        <!-- Menu Body -->
                        <!--      <li class="user-body">
                                    <div class="col-xs-4 text-center">
                                        <a href="#">My PIS</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Sales</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Friends</a>
                                    </div>
                                </li> -->
                        @endif

                        <!-- Menu Footer-->
                        <li class="user-footer">

                            @if ( \Config::get('settings.app_user_profile_link') )
                            <div class="pull-left">
                                {!! link_to_route('user.profile', 'Profile', [], ['class' => "btn btn-default btn-flat"]) !!}
                            </div>
                            @endif

                            <div class="pull-right">
                                <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" class="btn btn-default btn-flat">
                                    Logout
                                </a>
                            </div>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </li>


                @else
                <li>{!! link_to_route('login', 'Sign in') !!}</li>
                @if (\Config::get('settings.app_allow_registration'))
                <li>{!! link_to_route('register', 'Register') !!}</li>
                @endif
                @endif
            </ul>
        </div>
    </nav>

</header>

<script type="text/javascript">
    $(document).on('change', '#header_org_id', function() {
        var id = $('#header_user_id').val();
        var org_id = $(this).val();

        $.post("{{route('admin.organization.ajaxorg')}}", {
                id: id
                , org_id: org_id
                , _token: $('meta[name="csrf-token"]').attr('content')
            }
            , function(data, status) {
                if (data.status == '1')
                    location.reload();
                else
                    $("#header_ajax_status").after("<span style='color:red;' id='header_status_update'>Problem in updating org; Please try again.</span>");

                $('#header_status_update').delay(3000).fadeOut('slow');
                //alert("Data: " + data + "\nStatus: " + status);
            }).fail(function() {
            alert("Error In changing location");
        });
    });
    $(document).ready(function() {
        $('#chatpopover').click(function() {
            var e = $(this);
            if (!e.attr('aria-describedby')) {

                $.get(e.data('poload'), function(d) {
                    e.popover({
                        container: 'body'
                        , content: d.message
                    }).popover('show');

                });

            } else {
                e.popover('destroy');
                $('.popover').html('');
                e.attr('aria-describedby', undefined);
            }
        });
    });

    $('.sidebar-toggle').click(function() {


        if ($('body').hasClass('sidebar-collapse')) {

            //user has removed collapse
            document.cookie = "sidebar_collapse=;path=/"

        } else {

            console.log("OK");
            document.cookie = "sidebar_collapse=true;path=/"
        }

        //console.log(document.cookie);

    });

</script>
