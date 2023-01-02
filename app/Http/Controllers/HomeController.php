<?php

namespace App\Http\Controllers;

use App\Helpers\TaskHelper;
use App\Jobs\SendEmiDuesJob;
use App\Models\Announcement;
use App\Models\Audit as Audit;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\OrderPaymentTerms;
use App\Models\ProjectTask;
use App\Notifications\SendEmiDueNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Flash;
class HomeController extends Controller
{
    /**
     * @param Application $app
     * @param Audit $audit
     */
    public function __construct(Application $app, Audit $audit)
    {
        parent::__construct($app, $audit);
        // Set default crumbtrail for controller.
        session(['crumbtrail.leaf' => 'home']);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    // public function index(Request $request)
    // {
    //     $homeRouteName = 'welcome';

    //     try {
    //         $homeCandidateName =\Config::get('settings.app_home_route');
    //         $homeRouteName = $homeCandidateName;
    //     }
    //     catch (Exception $ex) { } // Eat the exception will default to the welcome route.

    //     $request->session()->reflash();
    //     return Redirect::route($homeRouteName);
    // }

    public function index()
    {

     //dd(\SettingHelper::set('User.suman.name','wosti'));
        // dd(\MenuBuilder::renderMenu('home'));
        if (! \Auth::check()) {
            return \Redirect::to('login')->with('message', 'Login to Start');
        }   
       // dd( file_get_contents('https://nepsealpha.com/trading/1/config'));

       //  dd(file_get_contents('https://nepsealpha.com/trading/1/history?symbol=NEPSE&resolution=1D&from=1537794687&to=1544417099&currencyCode=NRS'));
    


        $create_newsFeed = TaskHelper::watchNewEvents();
        $page_title = 'Home';
        $page_description = 'Welcome to <strong>'.TaskHelper::GetOrgName(\Auth::user()->org_id)->organization_name.'</strong>';

        $newsfeeds = \App\Models\NewsFeed::whereNull('schedule')->orWhere('schedule','<=',date('Y-m-d'))->orderBy('id','desc')->paginate(5);

        if(\Request::ajax() && \Request::get('page')){

            $feeds = view('admin.newsfeeds.feeds_partials',compact('newsfeeds'))->render();

            return ['html'=>$feeds,'newsfeeds'=>$newsfeeds];
        };

        //return [$newsfeeds];


        $annoucements = Announcement::where('org_id', \Auth::user()->org_id)->orderBy('created_at', 'desc')->take(7)->get();
        
        $attendance_log = \App\Models\ShiftAttendance::where('user_id', \Auth::user()->id)->where('date', date('Y-m-d'))->orderBy('attendance_status', 'desc')->first();

        if (! $attendance_log) {
            //check for next day night shift
            $check_night_shift = \App\Models\ShiftAttendance::where('user_id', \Auth::user()->id)
                            ->orderBy('date', 'desc')
                            ->first();

            if ($check_night_shift->shift->is_night ?? null) {
                $previous_day = date('Y-m-d', strtotime(date('Y-m-d').' -1 day'));

                $attendance_log = \App\Models\ShiftAttendance::where('user_id', \Auth::user()->id)->where('date', $previous_day)->orderBy('attendance_status', 'desc')->first();
            }
        }

        $a_p_l = \AttendanceHelper::getAbsentPesentHolidayUser(); //absent present leave

        $present_user = $a_p_l['p'];

        $on_leave = $a_p_l['l'];

        $absent_user = $a_p_l['a'];

        $today =  date('Y-m-d');
        
        $thisYear = date('Y');
        $birthdays = \App\User::select('*',\DB::raw('DATE_FORMAT(dob,"'.$thisYear.'-%m-%d") as birthdayDate '))
                    ->whereMonth("dob",date('m',strtotime($today)))
                    ->orderByRaw('DATE_FORMAT(dob, "%m-%d")')
                    ->get();
        $birthdays = $birthdays->where('birthdayDate','>=',$today)->sortBy('birthdayDate');
        // dd($birthdays);
        $duration = \Carbon\Carbon::now()->addDays(20);

        $holidays = \App\Models\Holiday::where('start_date', '>=', $today)->where('start_date', '<=', $duration)->get();

        $greetings = $this->greetings();

        return view('home', compact('page_title', 'page_description','annoucements', 'attendance_log','newsfeeds','present_user','on_leave','absent_user','birthdays','holidays','greetings'));
    }

   public function greetings(){
 
   if(date("H") < 12){
 
     return "Good Morning";
 
   }elseif(date("H") > 11 && date("H") < 18){
 
     return "Good Afternoon";
 
   }elseif(date("H") > 17){
 
     return "Good Evening";
 
   }
 
} 

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function welcome(Request $request)
    {
        $page_title = trans('general.text.welcome');
        $page_description = 'This is the welcome page';

        $request->session()->reflash();

        return view('welcome', compact('page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function notViewedCases(Request $request)
    {
        $page_title = 'Not Viewed Cases';
        $page_description = 'This is the Lists of Not Viewed Cases';

        $cases = \App\Models\Cases::where('status', '!=', 'closed')
                                    ->where('org_id', Auth::user()->org_id)
                                    ->where('enabled', '1')
                                    ->orderBy('id', 'desc')->paginate(20);

        // dd($cases);

        return view('notviewedcases', compact('page_title', 'page_description', 'cases'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function NotViewedLeads(Request $request)
    {
        $page_title = 'Not Viewed Cases';
        $page_description = 'This is the Lists of Not Viewed Cases';

        $leads = \App\Models\Lead::where('viewed', '0')
                                        ->where('rating', 'active')
                                        ->where('enabled', '1')
                                        ->where('org_id', Auth::user()->org_id)
                                        ->orderBy('id', 'desc')->paginate(20);

        $stages = \App\Models\Stage::where('enabled', '1')->orderby('ordernum')->pluck('name', 'id');
        $lead_status = \App\Models\Leadstatus::where('enabled', '1')->pluck('name', 'id')->all();

        return view('notviewedleads', compact('page_title', 'page_description', 'leads', 'stages', 'lead_status'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function NotViewedMarketingTasks(Request $request)
    {
        $page_title = 'Not Viewed Marketing Tasks';
        $page_description = 'This is the Lists of Not Marketing Tasks';

        $tasks = \App\Models\Task
                            ::whereIn('task_assign_to', [\Auth::user()->id])
                            ->where('enabled', '1')
                            ->where('task_status', '!=', 'Completed')
                            ->whereBetween('task_due_date', [\Carbon\Carbon::yesterday(), \Carbon\Carbon::now()->addDays(30)])
                            ->orderBy('id', 'DESC')
                            ->paginate(20);

        return view('notviewedmarketingtasks', compact('page_title', 'page_description', 'tasks'));
    }

    public function duePayments(Request $request)
    {
        $page_title = 'Due Receive Payments';
        $page_description = 'EMI payment dues';

        $payment_dues = OrderPaymentTerms::where('term_date','<', date('Y-m-d'))
                    ->get();

        return view('payment_dues', compact('page_title', 'page_description', 'payment_dues'));
    }




    public function postFeeds(Request $request){


        $attributes = $request->all();

        $attributes['user_id'] = \Auth::user()->id;

        

        $news = \App\Models\NewsFeed::create($attributes);


        $files = $request->file('attachment');

        $destinationPath = public_path('/news_feeds/');

        if (! \File::isDirectory($destinationPath)) {
            \File::makeDirectory($destinationPath, 0777, true, true);
        }

        foreach ($files as $key=>$doc_) {
            if ($doc_) {
                $doc_name = time().''.$doc_->getClientOriginalName();
                $doc_->move($destinationPath, $doc_name);
                $attachment = ['images'=>$doc_name,'news_feeds_id'=>$news->id];
                \App\Models\NewsFeedFiles::create($attachment);
            }
        }
        Flash::success("NewsFeed Successfully added");
       return redirect()->back();

    }


    public function postdislikelikes($id,$type){

        $news = \App\Models\NewsFeed::find($id);
        $checkLikes = $news->checkLikes();
        if($checkLikes){

            $checkLikes->delete();

        }else{

            \App\Models\NewsFeedLikes::create([

                'news_feeds_id'=>$news->id,
                'user_id'=>\Auth::user()->id,

            ]);

        }

        return ['success'=>true];


    }


    public function post_comments(Request $request,$pid){

        $attributes = $request->all();
        $attributes['news_feeds_id'] = $pid;
        $attributes['user_id'] = \Auth::user()->id;
        $comment = \App\Models\NewsFeedComments::create($attributes);
        $commenthtml = view('admin.newsfeeds.feed_comments_partials',compact('comment'))->render();

        return ['comment'=>$commenthtml];



    }


    public function viewallcomment($pid){

        $comments = \App\Models\NewsFeedComments::where('news_feeds_id',$pid)->get();

        $allcomments = '';

        foreach ($comments as $key => $comment) {
            $allcomments .= view('admin.newsfeeds.feed_comments_partials',compact('comment'))->render();
        }

        return ['html'=>$allcomments];



    }


    public function removeComment($cid){

        $comment = \App\Models\NewsFeedComments::find($cid);

        if(!$comment->isDeletable()){

            abort(403);

        }
        $comment->delete();

        return ['success'=>true];


    }

    public function removenews($id){



        $newsfeeds = \App\Models\NewsFeed::find($id);

        if(!$newsfeeds->isEditable()){

            abort(403);
        }

        $newsfeeds->delete();

        \App\Models\NewsFeedComments::where('news_feeds_id',$id)->delete(); 
        
        \App\Models\NewsFeedLikes::where('news_feeds_id',$id)->delete(); 

        \App\Models\NewsFeedFiles::where('news_feeds_id',$id)->delete(); 


        return ['success'=>true];

    }
}
