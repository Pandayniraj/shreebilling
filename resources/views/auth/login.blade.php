<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{!! env('SHORT_NAME') !!} Login</title>
  <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="/css/bs4/bootstrap.min.css">
  <style type="text/css">
    body {
  font-family: "Karla", sans-serif;
  background-color: #d0d0ce;
  min-height: 100vh; }

.brand-wrapper {
  margin-bottom: 10px; }
  .brand-wrapper .logo {
    height: 37px; }

.login-card {
  border: 0;
  border-radius: 27.5px;
  box-shadow: 0 10px 30px 0 rgba(172, 168, 168, 0.43);
  overflow: hidden; }
  .login-card-img {
    border-radius: 0;
    position: absolute;
    width: 100%;
    height: 100%;
    -o-object-fit: cover;
       object-fit: cover; }
  .login-card .card-body {
    padding: 85px 60px 60px; }
    @media (max-width: 422px) {
      .login-card .card-body {
        padding: 35px 24px; } }
  .login-card-description {
    font-size: 25px;
    color: #000;
    font-weight: normal;
    margin-bottom: 23px; }
  .login-card form {
    max-width: 326px; }
  .login-card .form-control {
    border: 1px solid #d5dae2;
    padding: 15px 25px;
    margin-bottom: 20px;
    min-height: 45px;
    font-size: 13px;
    line-height: 15;
    font-weight: normal; }
    .login-card .form-control::-webkit-input-placeholder {
      color: #919aa3; }
    .login-card .form-control::-moz-placeholder {
      color: #919aa3; }
    .login-card .form-control:-ms-input-placeholder {
      color: #919aa3; }
    .login-card .form-control::-ms-input-placeholder {
      color: #919aa3; }
    .login-card .form-control::placeholder {
      color: #919aa3; }
  .login-card .login-btn {
    padding: 13px 20px 12px;
    background-color: #000;
    border-radius: 4px;
    font-size: 17px;
    font-weight: bold;
    line-height: 20px;
    color: #fff;
    margin-bottom: 24px; }
    .login-card .login-btn:hover {
      border: 1px solid #000;
      background-color: transparent;
      color: #000; }
  .login-card .forgot-password-link {
    font-size: 14px;
    color: #919aa3;
    margin-bottom: 12px; }
  .login-card-footer-text {
    font-size: 16px;
    color: #0d2366;
    margin-bottom: 60px; }
    @media (max-width: 767px) {
      .login-card-footer-text {
        margin-bottom: 24px; } }
  .login-card-footer-nav a {
    font-size: 14px;
    color: #919aa3; }
  </style>
</head>
<body>
  <main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
    <div class="container">
      <div class="card login-card">
        <div class="row no-gutters">
          <div class="col-md-5">
            <img  src="{!! url('/') !!}{!! '/org/'. $logo->login_bg !!}"  alt="login" class="login-card-img">
          </div>
          <div class="col-md-7">
            <div class="card-body">
              <div class="brand-wrapper">
                <img src="{!! url('/') !!}{!! '/org/'. $logo->logo !!}" alt="logo" class="">
                <p class="login-box-msg">{!! $loginAnnouncement ? $loginAnnouncement->description:'' !!}</p>

              </div>
              <p class="login-card-description">Sign into {{env('TAGLINE')}}</p>
              <div class="box-body">
                        @include('flash::message')
                        @include('partials._errors')
                    </div>
              <form class="login100-form validate-form" id="" method="POST" action="{{ route('login') }}" >
                {!! csrf_field() !!}
                  <div class="form-group">
                    <label for="email" class="sr-only">Username</label>

                    <input type="text" id="username" name="username" class="form-control" placeholder="User name" value="{{ old('username') }}" required autofocus/>
                  </div>
                  <div class="form-group mb-4">
                    <label for="password" class="sr-only">Password</label>

                    <input type="password" id="password" name="password" class="form-control" placeholder="********" required/>
                  </div>

                  <select name="database" class="form-control @error('database') is-invalid @enderror" required>
                      <option {{ old('database') === env('APP_CODE').'2079' ? 'selected' : '' }} value="{{env('APP_CODE')}}2079">{{strtoupper(env('APP_CODE'))}}2079</option>
                      <option {{ old('database') ===  env('APP_CODE').'2078' ? 'selected' : '' }} value="{{env('APP_CODE')}}2078">{{strtoupper(env('APP_CODE'))}}2078</option>
                      <option {{ old('database') ===  'training' ? 'selected' : '' }} value="training">Training</option>
                  </select>

                  <button type="submit" class="btn btn-block login-btn mb-4">Sign In</button>
                </form>

                {!! link_to_route('password.request', 'I forgot my password', [], ['class' => "text-center"]) !!}

                | <a target="_blank" href="/enquiry">Hotel Booking Engine</a> | <a target="_blank" href="/eventsenquiry">Events Booking</a> | <a target="_blank" href="https://customers.meronetwork.com/ticket">System Support</a>
      

                <nav class="login-card-footer-nav">
                  v {{ App::version()}}
                  <a href="#!">Terms of use.</a>
                  <a href="#!">Privacy policy</a>
                </nav>
            </div>
          </div>
        </div>
      </div>

    </div>
  </main>

</body>
</html>
