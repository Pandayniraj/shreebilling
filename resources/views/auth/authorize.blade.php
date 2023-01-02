@extends('layouts.master')

{{-- @section('head')
    <meta http-equiv="refresh" content="15">
@stop
 --}}
@section('content')
{{-- <div class="box box-primary">
    <div class="box-header with-border">
       <div class='row'>
            <div class='col-md-12'>
               
                <b><font size="4">{!!  $page_title !!}</font></b>
                <div style="display: inline; float: right;">
                
            </div>
        </div>
    <div class="container">
        <div class="authorize_holder">
            <div class="authorize__holder--section">
                <div class="text">

                    <h4>It looks like you're signing in to <a href="{{ url('/') }}">{{ url('/') }}</a> from a computer or device we haven't seen before, or for some time.</h4>
                    <p>
                        Please <strong>click the confirmation link in the email we just sent you.</strong> This is a process that protects the security of your account.
                    </p>
                    <p>Note that you need to access this email with the same device that you are confirming.</p>
                </div>
                <div class="authorize__resend">
                        <form action="{{ route('authorize.resend') }}" method="POST">
                            {{ csrf_field() }}

                            <div class="alert">
                                @if (session('status'))
                                    <div class="message alert alert-success"> {{ session('status') }}</div>
                                @endif
                                @if (session('error'))
                                    <div class="message alert alert-danger"> {{ session('error') }}</div>
                                @endif
                            </div>


                            <button type="submit" class="btn btn-primary">Email didn't arrived ?</button>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}

    <section class="content" style="border: 1px dashed red;">
        <form action="{{ route('authorize.resend') }}" method="POST">
      <div class="error-page">
        <h2 class="headline text-yellow"><i class="fa fa-lock"></i> 401 &nbsp;</h2>

        <div class="error-content">
          <h3><i class="fa  fa-tv (alias) text-yellow"></i> {!!  $page_title !!}</h3>

          <p>
            It looks like you're signing in to <a href="{{ url('/') }}">{{ url('/') }}</a> from a computer or device we haven't seen before.
          </p>
          <p>
                         <strong>Click  confirmation link in the email we just sent you.</strong> 
                    </p>
                   

       
            @csrf
            <div class="input-group">

            <button type="submit" class="btn btn-warning btn-block">Email didn't arrived ?</button>

             {{--  <input type="text" name="search" class="form-control" placeholder="Search">

              <div class="input-group-btn">
                <button type="submit" name="submit" class="btn btn-warning btn-flat"><i class="fa fa-search"></i>
                </button>
              </div> --}}
            </div>
            <!-- /.input-group -->
        
        </div>
        <!-- /.error-content -->
      </div>
      <!-- /.error-page -->
  </form>
    </section>
@endsection