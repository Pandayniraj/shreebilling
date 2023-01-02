<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserLogin;
use App\Events\UserLogout;
use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Organization;
use App\Models\Audit as Audit;
use App\User;
use Flash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
        attemptLogin as baseAttemptLogin;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/finance/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        // echo Hash::make('admin'); die;
        $page_title = 'Login';
        $logo = Organization::find($id = 1);
        $orgs = Organization::all();
        $loginAnnouncement = Announcement::where('placement', 'login')->orderBy('Announcements_id', 'DESC')->first();

        return view('auth.login', compact('page_title', 'orgs', 'loginAnnouncement', 'logo'));
    }

    // Hack to have the username as a required field in the validator.
    // See https://laravel.com/docs/5.4/authentication#included-authenticating
    public function username()
    {
        return 'username';
    }


    protected function attemptLogin(Request $request)
    {
       config(['database.connections.mysql.database' => $request->database]);
       config(['database.default' => $request->database]);
       \DB::purge($request->database);
       \DB::reconnect($request->database);

        return $this->baseAttemptLogin($request);
    }

    // Overrides method to fire event.
    protected function authenticated(Request $request, $user)
    {


        session(['database' => $request->database]);

        if (('root' == $user->username) || ($user->enabled)) {
            Audit::log(Auth::user()->id, trans('general.audit-log.category-login'), trans('general.audit-log.msg-login-success', ['username' => $user->username]));

            Flash::success('Welcome ' . Auth::user()->first_name);



            if (Auth::user()->hasRole(['pos-user', 'pos-manager'])) {

                return redirect('/admin/pos/dashboard');
            }

            if (Auth::user()->hasRole(['waiter', 'pos-manager'])) {

                return redirect('/admin/pos/dashboard');
            }

            if (Auth::user()->hasRole(['pm', 'payrol','hrm'])) {

                return redirect('/hrboard');
            }

            return redirect()->intended($this->redirectPath());
        } else {
            Audit::log(null, trans('general.audit-log.category-login'), trans('general.audit-log.msg-forcing-logout', ['username' => $credentials['username']]));

            Auth::logout();

            return redirect(route('login'))
                ->withInput($request->only('username', 'remember'))
                ->withErrors([
                    'username' => trans('admin/users/general.error.login-failed-user-disabled'),
                ]);
        }
    }

    protected function validateLogin(Request $request)
   {
      $request->validate([
         'database' => [
            'required',
        \Illuminate\Validation\Rule::in([env('APP_CODE').'2079', env('APP_CODE').'2078']),
         ],
         $this->username() => 'required|string',
         'password' => 'required|string',
      ]);
   }

    // Overrides method to fire event.
    public function logout(Request $request)
    {
        // Grab current user and fire event.
        $user = auth()->user();

        $this->guard()->logout();

        $request->session()->invalidate();


        return redirect('/login');
    }
}
