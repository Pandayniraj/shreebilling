
<div class="modal fade show" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-md modal-login">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalCenterTitle">Customer Login</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('login') }}">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <input type="text" id="username" name="username" class="form-control" placeholder="User name" value="{{ old('username') }}" required autofocus />
                    </div>
                    <div class="form-group">
                        <input type="password" id="password" name="password" class="form-control" placeholder="********" required />
                       <small> <a href="{{route(recover_password)}}" >Forgot Password?</a></small>
                    </div>
                    <div class="form-group"  >
                        <button type="submit" class="btn btn-danger btn-lg btn-block login-btn" id="btnLogin">Login</button>

                         <button type="button" class="btn btn-success btn-lg btn-block login-btn" 
                          id="guest_booking" key='' style="display: none;">Book Now</button>
                    </div>
                    <div class="form-group"><small>
                        <em href="text-muted" style="color: #8C8685;">Not Registered?</em>&nbsp;<a href="javascript::void" class="guestregister">Signup now</a>

                    </small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>