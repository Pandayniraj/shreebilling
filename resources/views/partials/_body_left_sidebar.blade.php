<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            @if (Auth::check())
                <div class="pull-left image">

                    @if(\Auth::user()->image)
                    <img src="/images/profiles/{{\Auth::user()->image}}" class="img-circle" alt="User Image" />
                    @else
                         <img src="{{ Gravatar::get(Auth::user()->email , 'small') }}" class="img-circle" alt="User Image" />
                    @endif


                </div>
                <div class="pull-left info">
                    <p>{{ Auth::user()->full_name }}</p>
                    <!-- Status -->
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            @endif
        </div>

        @if ( \Config::get('settings.app_search_box') )
            <!-- search form (Optional) -->
             {!! Form::open(['route' => 'admin.search.leads', 'id' => 'search_leads','class'=>'sidebar-form', 'method' => 'GET']) !!}
                 <div class="input-group"> 
              <input type="text" name="search" class="form-control" placeholder="Search Leads...">
                  <span class="input-group-btn">
                    <button type="submit" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                    </button>
                  </span>
            </div>
          {!! Form::close() !!}
            <!-- /.search form -->
        @endif


        {!! MenuBuilder::renderMenu('home')  !!}
        @auth
            @if(\Auth::user()->org_id == '1')
            {!! MenuBuilder::renderMenu('admin', true)  !!}
            @endif
        @endauth
    </section>
    <!-- /.sidebar -->
</aside>
