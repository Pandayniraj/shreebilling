<?php

namespace App\Http\Controllers;

use App\DataTables\LeadsDataTable;
use App\Models\Attachment as Attachment;
use App\Models\Audit as Audit;
use App\Models\Campaigns;
use App\Models\Contact;
use App\Models\File as LeadFile;
use App\Models\Lead as Lead;
use App\Models\LeadMobile;
use App\Models\LeadTransfer;
use App\Models\Leadtype;
use App\Models\Mail as Mails;
use App\Models\Note as LeadNote;
use App\Models\Pax;
use App\Models\Product as ProductModel;
use App\Models\Proposal;
use App\Models\Query;
use App\Models\QueryPax;
use App\Models\Role as Permission;
use App\Models\Role as Role;
use App\Models\Sms as Sms;
use App\User as User;
use Datatables;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class LeadsController extends Controller
{
    /**
     * @var Lead
     */
    private $lead;
    private $LeadFile;
    private $Mail;
    private $Attachment;
    private $LeadNote;
    private $Sms;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @var User
     */
    private $user;

    private $company;

    /**
     * @param Lead $lead
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(LeadFile $LeadFile, Lead $lead, Mails $Mails, Attachment $attachment, LeadNote $LeadNote, Permission $permission, Role $role, User $user, Sms $Sms)
    {
        parent::__construct();
        $this->LeadFile = $LeadFile;
        $this->lead = $lead;
        $this->mail = $Mails;
        $this->attachment = $attachment;
        $this->permission = $permission;
        $this->LeadNote = $LeadNote;
        $this->role = $role;
        $this->user = $user;
        $this->sms = $Sms;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Audit::log(Auth::user()->id, trans('admin/leads/general.audit-log.category'), trans('admin/leads/general.audit-log.msg-index'));

        $leads = \App\Models\Lead::orderBy('id', 'desc')
            ->where('org_id', \Auth::user()->org_id)
            ->where(function ($qry) {
                $type = \Request::get('type');
                if ($type != null) {
                    $leadType = Leadtype::where('name', ucfirst($type))->first();
                    if ($leadType) {
                        $qry->where('lead_type_id', $leadType->id);
                    }
                }
            })
            ->where(function ($query) {
                if (\Request::get('start_date') != '' && \Request::get('end_date') != '') {
                    return $query->whereBetween('created_at', [\Request::get('start_date'), \Request::get('end_date')]);
                }
            })
            ->where(function ($query) {
                if (\Request::get('product_id') && \Request::get('product_id') != '') {
                    return $query->where('product_id', \Request::get('product_id'));
                }
            })
            ->where(function ($query) {
                if (\Request::get('rating') && \Request::get('rating') != '') {
                    return $query->where('rating', \Request::get('rating'));
                }
            })
            ->where(function ($query) {
                if (\Request::get('source_id') && \Request::get('source_id') != '') {
                    return $query->where('communication_id', \Request::get('source_id'));
                }
            })
            ->where(function ($query) {
                if (\Request::get('user_id') && \Request::get('user_id') != '') {
                    return $query->where('user_id', \Request::get('user_id'));
                }
            })
            ->where(function ($query) {
                if (\Request::get('status_id') && \Request::get('status_id') != '') {
                    return $query->where('status_id', \Request::get('status_id'));
                }
            })
            ->where(function($query){
                if(!Auth::user()->hasRole('admins')){
                    return $query->where('user_id',Auth::user()->id);
                }
            })
            ->paginate(30);

        $courses = \App\Models\Product::where('enabled', '1')
            ->where('org_id', Auth::user()->org_id)
            ->pluck('name', 'id')->all();

        $sources = \App\Models\Communication::where('enabled', '1')
            ->pluck('name', 'id')->all();

        $users = \App\User::where('enabled', '1')->pluck('first_name', 'id')->all();
        $lead_status = \App\Models\Leadstatus::where('enabled', '1')->pluck('name', 'id')->all();
        $lead_rating = \App\Models\Rating::where('enabled', '1')->pluck('name', 'id')->all();
        $total_target_amount = DB::table('leads')->select('total_target_amount')->where('lead_type_id', '1')
            ->select(DB::raw('SUM(price_value) as total_target_amount'))->get();
        $total_lead_amount = DB::table('leads')->where('lead_type_id', '2')
            ->select(DB::raw('SUM(price_value) as total_lead_amount'))->get();

        if (!null == \Request::get('type')) {
            $type = ucfirst(\Request::get('type'));
        } else {
            $type = 'Lead';
        }

        $page_title = 'Admin | ' . $type . '';
        $page_description = 'List of  ' . $type;

        $total_enquiry = \App\Models\Lead::count();
        $target_enquiry = \App\Models\Lead::where(['lead_type_id' => 1])->count();
        $lead_enquiry = \App\Models\Lead::where(['lead_type_id' => 2])->count();
        $qualified_enquiry = \App\Models\Lead::where(['lead_type_id' => 3])->count();

        $campaigns = Campaigns::pluck('name', 'id');

        $courses_count = ProductModel::where('enabled', 1)->where('org_id', Auth::user()->org_id)->get();
        $stages = \App\Models\Stage::where('enabled', '1')->orderby('ordernum')->pluck('name', 'id');

        return view('admin.leads.index', compact('leads', 'sources', 'courses', 'courses_count', 'users',  'lead_status', 'page_title', 'page_description', 'total_target_amount', 'total_lead_amount', 'total_enquiry', 'qualified_enquiry', 'lead_enquiry', 'target_enquiry', 'campaigns', 'lead_rating', 'stages'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function DownloadExcelFilter()
    {
        Audit::log(Auth::user()->id, trans('admin/leads/general.audit-log.category'), trans('admin/leads/general.audit-log.msg-index'));

        $data = \App\Models\Lead::select('leads.id', 'lead_types.name as LeadType', 'lead_stages.name as stage_name', 'lead_company.name as Company', 'price_value', 'title', 'leads.name', 'department', 'position', 'description', 'mob_phone', 'mob_phone2', 'home_phone', 'address_line_1', 'country', 'city', 'sector', 'target_date', 'leads.email', 'products.name as CourseName', 'lead_communications.name as Source', 'lead_status.name as Status', 'users.first_name as OwnerFirstName', 'users.last_name as OwnerLastName')
            ->leftjoin('lead_stages', 'leads.stage_id', '=', 'lead_stages.id')
            ->leftjoin('products', 'leads.product_id', '=', 'products.id')
            ->leftjoin('lead_communications', 'leads.communication_id', '=', 'lead_communications.id')
            ->leftjoin('lead_status', 'leads.status_id', '=', 'lead_status.id')
            ->leftjoin('users', 'leads.user_id', '=', 'users.id')
            ->leftjoin('lead_types', 'leads.lead_type_id', '=', 'lead_types.id')
            ->leftjoin('lead_company', 'leads.company_id', '=', 'lead_company.id')
            ->orderBy('id', 'desc')
            ->where('leads.org_id', Auth::user()->org_id)
            ->where(function ($qry) {
                $type = \Request::get('type');
                if ($type != null) {
                    $leadType = Leadtype::where('name', ucfirst($type))->first();
                    if ($leadType) {
                        $qry->where('lead_type_id', $leadType->id);
                    }
                }
                //return $qry->where('status_id', '!=', '17');
                //return $qry->where('created_at', '>=', '2017-01-01 00:01')->where('status_id', '!=', '17');
            })
            ->where(function ($query) {
                if (\Request::get('start_date') != '' && \Request::get('end_date') != '') {
                    return $query->whereBetween('created_at', [\Request::get('start_date'), \Request::get('end_date')]);
                }
            })
            ->where(function ($query) {
                if (\Request::get('product_id') && \Request::get('product_id') != '') {
                    return $query->where('product_id', \Request::get('product_id'));
                }
            })
            ->where(function ($query) {
                if (\Request::get('rating') && \Request::get('rating') != '') {
                    return $query->where('rating', \Request::get('rating'));
                }
            })
            ->where(function ($query) {
                if (\Request::get('source_id') && \Request::get('source_id') != '') {
                    return $query->where('communication_id', \Request::get('source_id'));
                }
            })

            ->where(function ($query) {
                if (\Request::get('user_id') && \Request::get('user_id') != '') {
                    return $query->where('user_id', \Request::get('user_id'));
                }
            })
            ->where(function ($query) {
                if (\Request::get('status_id') && \Request::get('status_id') != '') {
                    return $query->where('status_id', \Request::get('status_id'));
                }
            })
            ->get()->toArray();

        return \Excel::download(new \App\Exports\ExcelExport($data), 'Filter_leads.xls');
    }

    public function todays_action()
    {
        $leads = \App\Models\Lead::orderBy('id', 'desc')
            ->where('org_id', Auth::user()->org_id)
            ->where('user_id', Auth::user()->id)
            ->where('target_date', \Carbon\Carbon::today())
            ->paginate(25);
        $lead_status = \App\Models\Leadstatus::where('enabled', '1')->pluck('name', 'id')->all();
        //dd($leads);

        $page_title = 'Todays  Action';
        $page_description = 'List todays action ';

        return view('admin.leads.todays_action', compact('leads', 'lead_status', 'page_title', 'page_description'));
    }

    public function overdue_leads()
    {
        $leads = \App\Models\Lead::orderBy('id', 'desc')
            ->where('org_id', Auth::user()->org_id)
            ->where('user_id', Auth::user()->id)
            ->whereDate('target_date', '<=', \Carbon\Carbon::today())
            ->whereDate('target_date', '!=', '0000-00-00')
            ->whereIn('status_id', ['2', '28'])
            ->paginate(25);
        $lead_status = \App\Models\Leadstatus::where('enabled', '1')->pluck('name', 'id')->all();

        $page_title = 'Overdue Leads';
        $page_description = 'List todays action ';

        return view('admin.leads.overdue_leads', compact('leads', 'lead_status', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $lead = $this->lead->find($id);
        $leads = \App\Models\Lead::orderBy('id', 'DESC')->where('lead_type_id', $lead->lead_type_id)->where('org_id', \Auth::user()->org_id)
        ->where(function($query){
                if(!Auth::user()->hasRole('admins')){
                    return $query->where('user_id',Auth::user()->id);
                }
            })->take(20)->get();

        if (!$leads->find($id)) {
            $leads->prepend($lead);
            //for lead transfer notification
        }
        if (\Request::get('transfernotify') == '1') {
            DB::table('lead_transfers')->where('lead_id', $id)->where('to_user_id', Auth::user()->id)->update(['notify' => 1]);
            //dd(Request::get('transfernotify'));
        }

        //for lead transfer notification
        if (\Request::get('query_action_notify') == '1') {
            DB::table('lead_query')->where('id', \Request::get('query_id'))->where('lead_id', $id)->where('user_id', Auth::user()->id)->update(['notify_next_action' => 1]);
            //dd(Request::get('transfernotify'));
        }

        if (Auth::user()->org_id == $lead->org_id) {
            $lead = $this->lead->find($id);
        } else {
            Flash::warning('Ussh!! Not Allowed');

            return redirect('/');
        }

        if ($lead->viewed == 0) {
            DB::table('leads')->where('id', $id)->update(['viewed' => 1]);
        }

        // Audit::log(Auth::user()->id, trans('admin/leads/general.audit-log.category'), trans('admin/leads/general.audit-log.msg-show', ['name' => $lead->name]));

        if (!null == \Request::get('type')) {
            $type = ucfirst(\Request::get('type'));
        } else {
            $type = 'Lead';
        }

        $page_title = 'Admin | ' . $type . ' | Show';
        $page_description = 'Displaying ' . $type . ': ' . $lead->name;

        $phone_logs = \App\Models\Phonelogs::where('lead_id', $id)->get();

        // dd($phone_logs);

        $notes = \App\Models\Note::where('lead_id', $id)->orderBy('id', 'desc')->get();
        $files = \App\Models\File::where('lead_id', $id)->orderBy('id', 'desc')->get();
        $emails = \App\Models\Mail::where('lead_id', $id)->orderBy('id', 'desc')->get();
        $smses = \App\Models\Sms::where('lead_id', $id)->orderBy('created_at', 'desc')->get();
        $users = \App\User::where('enabled', '1')->where('org_id', Auth::user()->org_id)->pluck('first_name', 'id');

        $lead_status = \App\Models\Leadstatus::where('enabled', '1')->pluck('name', 'id')->all();

        $lead_rating = \App\Models\Rating::where('enabled', '1')->pluck('name', 'id')->all();

        $proposal = Proposal::where('client_lead_id', $id)->get();
        $lead_type = \App\Models\Leadtype::where('enabled', '1')->pluck('name', 'id');
        $cases = \App\Models\Cases::where('lead_id', $id)->get();
        $quotations = \App\Models\Orders::where('order_type', 'quotation')->where('client_id', $id)->orderBy('id', 'DESC')->get();

        //dd($quotations);
        $orders = \App\Models\Orders::where('order_type', 'order')->where('client_id', $id)->orderBy('id', 'DESC')->get();
        $queries = Query::where('lead_id', $id)->get();

        $sources = \App\Models\Communication::where('enabled', '1')->pluck('name', 'id')->all();

        $stages = \App\Models\Stage::where('enabled', '1')->orderby('ordernum')->pluck('name', 'id');
        $campaigns = Campaigns::pluck('name', 'id')->all();

        $follow_up = \App\Models\LeadActivityStream::where('lead_id', $id)->orderBy('created_at', 'desc')->take(10)->get()->groupBy('date');
        $closure_reason = \App\Models\LeadClosureReason::pluck('reason as name', 'id')->all();
        $tasks = \App\Models\Task::where('lead_id', $id)->get();
        //dd($follow_up);
        return view('admin.leads.show', compact('lead', 'leads',  'page_title', 'page_description', 'notes', 'files', 'emails', 'users', 'smses', 'lead_status', 'proposal', 'lead_type', 'cases', 'quotations', 'orders', 'queries', 'phone_logs', 'stages', 'sources', 'campaigns', 'follow_up', 'tasks', 'phone_logs', 'closure_reason', 'lead_rating'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if (!null == \Request::get('type')) {
            $type = ucfirst(\Request::get('type'));
        } else {
            $type = 'Lead';
        }

        $page_title = 'Admin | ' . $type . ' | Create';
        $page_description = 'Creating a new ' . $type . '';

        $lead = new \App\Models\Lead();
        $perms = $this->permission->all();

        $lead_types = \App\Models\Leadtype::where('enabled', '1')->pluck('name', 'id');
        $contacts = \App\Models\Contact::where('enabled', '1')->pluck('full_name', 'id');
        $courses = \App\Models\Product::orderBy('ordernum')->where('enabled', '1')
            ->where('org_id', Auth::user()->org_id)->orderBy('name', 'ASC')
            ->pluck('name', 'id');
        //$courses = \App\Models\Course::orderBy('ordernum')->pluck('name', 'id');
        // dd($courses);
        //$courses->prepend('Select Product');
        $communications = \App\Models\Communication::orderBy('ordernum')->where('enabled', '1')->pluck('name', 'id');
        $lead_status = \App\Models\Leadstatus::orderBy('ordernum')->where('enabled', '1')->pluck('name', 'id');
        $campaigns = Campaigns::pluck('name', 'id');

        $users = \App\User::where('enabled', '1')->pluck('first_name', 'id');
        $cust = \App\Models\Lead::select('id', 'name')->whereIn('lead_type_id', ['4', '6'])->orderBy('id', 'DESC')->get();
        $stages = \App\Models\Stage::where('enabled', '1')->orderby('ordernum')->pluck('name', 'id');
        $lead_rating = \App\Models\Rating::where('enabled', '1')->pluck('name', 'id')->all();

        return view('admin.leads.create', compact('lead', 'perms', 'page_title', 'page_description', 'lead_types', 'contacts', 'courses', 'communications', 'lead_status', 'users', 'cust', 'stages', 'campaigns', 'lead_rating'));
    }

    //create for popup
    public function createModal()
    {
        if (!null == \Request::get('type')) {
            $type = ucfirst(\Request::get('type'));
        } else {
            $type = 'Lead';
        }

        $page_title = 'Admin | Sales | Create';
        $page_description = 'Creating a new sales ' . $type . '';

        $lead = new \App\Models\Lead();
        $perms = $this->permission->all();

        $lead_types = \App\Models\Leadtype::where('enabled', '1')->pluck('name', 'id');
        $contacts = \App\Models\Contact::where('enabled', '1')->pluck('full_name', 'id');
        $courses = \App\Models\Product::orderBy('ordernum')->where('enabled', '1')
            ->where('org_id', Auth::user()->org_id)->orderBy('name', 'ASC')
            ->pluck('name', 'id');
        //$courses = \App\Models\Course::orderBy('ordernum')->pluck('name', 'id');
        // dd($courses);
        //$courses->prepend('Select Product');
        $communications = \App\Models\Communication::orderBy('ordernum')->where('enabled', '1')->pluck('name', 'id');
        $lead_status = \App\Models\Leadstatus::orderBy('ordernum')->where('enabled', '1')->pluck('name', 'id');
        $campaigns = Campaigns::pluck('name', 'id');

        $users = \App\User::where('enabled', '1')->pluck('first_name', 'id');
        $cust = \App\Models\Lead::select('id', 'name')->whereIn('lead_type_id', ['4', '6'])->orderBy('id', 'DESC')->get();
        $stages = \App\Models\Stage::where('enabled', '1')->orderby('ordernum')->pluck('name', 'id');

        return view('admin.leads.modals.create', compact('lead', 'perms', 'page_title', 'page_description', 'lead_types', 'contacts', 'courses', 'communications', 'lead_status',  'users', 'cust', 'stages', 'campaigns'));
    }

    public function postModal(Request $request)
    {
        $attributes = $request->all();
        $attributes['email'] = trim($request['email']);
        $attributes['org_id'] = Auth::user()->org_id;
        $attributes['user_id'] = Auth::user()->id;
        $lead = $this->lead->create($attributes);
        $clients = \App\Models\Lead::select('id', 'name', 'org_id')->orderBy('id', 'DESC')->get();
        $lead['_stage'] = $lead->stage->name ?? '';

        return ['clients' => $clients, 'lastcreated' => $lead->id, 'leads' => $lead];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required',
            'target_date' => 'required',
            'email' => 'email:rfc,dns,spoof',
            'address_line_1' => 'required']);

        $attributes = $request->all();

        $attributes['email'] = trim($request['email']);
        $attributes['org_id'] = Auth::user()->org_id;

        if ($request['contact_id'] != '') {
            $temp_contact = Contact::where('full_name', $request['contact_id'])->first();
            //dd($temp_contact);

            if (!$temp_contact) {
                Flash::warning('Please select the valid Contact.');

                return Redirect::back()->withInput(\Request::all());
            } else {
                $attributes['contact_id'] = $temp_contact->id;
            }
        }

        Audit::log(Auth::user()->id, trans('admin/leads/general.audit-log.category'), trans('admin/leads/general.audit-log.msg-store', ['name' => $attributes['name']]));

        $lead = $this->lead->create($attributes);

        $extra_name = $request->extra_name;
        $extra_mobile = $request->extra_mobile;
        $extra_email = $request->extra_email;

        foreach ($extra_name as $key => $value) {
            if ($value != '') {
                $detail = new LeadMobile();
                $detail->lead_id = $lead->id;
                $detail->name = $extra_name[$key];
                $detail->mobile = $extra_mobile[$key];
                $detail->email = $extra_email[$key];
                $detail->save();
            }
        }

        // Mail::send('emails.lead-create', compact('lead'), function ($message) use ($attributes, $lead, $request) {
        //     $message->subject('New '.ucfirst($request->type).' Created');
        //     $message->from(env('APP_EMAIL'), env('APP_COMPANY'));
        //     $message->to(\TaskHelper::getUser(Auth::user()->id)->email, '');
        // });

        Flash::success(trans('admin/leads/general.status.created')); // 'Lead successfully created');

        return redirect('/admin/leads?type=' . $attributes['type']);
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $lead = $this->lead->find($id);

        if ($lead->viewed == 0) {
            DB::table('leads')->where('id', $id)->update(['viewed' => 1]);
        }

        Audit::log(Auth::user()->id, trans('admin/leads/general.audit-log.category'), trans('admin/leads/general.audit-log.msg-edit', ['name' => $lead->name]));

        if (!null == \Request::get('type')) {
            $type = ucfirst(\Request::get('type'));
        } else {
            $type = 'Lead';
        }

        $page_title = 'Admin | ' . $type . ' | Edit';
        $page_description = 'Editing ' . $type . ': ' . $lead->name;

        if (!$lead->isEditable() && !$lead->canChangePermissions()) {
            abort(403);
        }

        $lead_types = \App\Models\Leadtype::where('enabled', '1')->pluck('name', 'id');
        $contacts = \App\Models\Contact::where('enabled', '1')->pluck('full_name', 'id');
        $courses = \App\Models\Product::where('enabled', '1')->where('org_id', Auth::user()->org_id)->pluck('name', 'id');
        $communications = \App\Models\Communication::orderBy('ordernum')->where('enabled', '1')->pluck('name', 'id');
        $lead_status = \App\Models\Leadstatus::orderBy('ordernum')->where('enabled', '1')->pluck('name', 'id');
        $campaigns = Campaigns::pluck('name', 'id');
        $users = \App\User::where('enabled', '1')->pluck('first_name', 'id');
        $lead_mobiles = \App\Models\LeadMobile::where('lead_id', $id)->get();
        $stages = \App\Models\Stage::where('enabled', '1')->orderby('ordernum')->pluck('name', 'id');

        $lead_rating = \App\Models\Rating::where('enabled', '1')->pluck('name', 'id')->all();

        return view('admin.leads.edit', compact('lead', 'lead_mobiles', 'stages', 'page_title', 'page_description', 'lead_types', 'contacts', 'contacts', 'courses', 'communications', 'lead_status', 'users', 'campaigns', 'lead_rating'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, ['name' => 'required']);

        $lead = $this->lead->find($id);

        //ledger
        if (!$request->ledger_id && $request->lead_type_id == 4) {
            $ledger_id = \TaskHelper::AddCityLedgerCustomer($request->name);
            $attributes['ledger_id'] = $ledger_id;
        }

        Audit::log(Auth::user()->id, trans('admin/leads/general.audit-log.category'), trans('admin/leads/general.audit-log.msg-update', ['name' => $lead->name]));

        $attributes = $request->all();
        //dd($attributes);
        $attributes['email'] = trim($request['email']);
        $attributes['org_id'] = Auth::user()->org_id;
        //dd($attributes);
        if ($request['contact_id'] != '' && $request['contact_id'] != '0') {
            $temp_contact = Contact::where('full_name', $request['contact_id'])->first();
            //dd($temp_contact);
            if (!$temp_contact) {
                Flash::warning('Please select the valid Contact.');

                return Redirect::back()->withInput(\Request::all());
            } else {
                $attributes['contact_id'] = $temp_contact->id;
            }
        }

        if ($lead->isEditable()) {
            $lead->update($attributes);
        }

        \App\Models\LeadMobile::where('lead_id', $id)->delete();

        $extra_name = $request->extra_name;
        $extra_mobile = $request->extra_mobile;
        $extra_email = $request->extra_email;

        foreach ($extra_name as $key => $value) {
            if ($value != '') {
                $detail = new LeadMobile();
                $detail->lead_id = $lead->id;
                $detail->name = $extra_name[$key];
                $detail->mobile = $extra_mobile[$key];
                $detail->email = $extra_email[$key];
                $detail->save();
            }
        }
        \App\Models\LeadActivityStream::create([
            'lead_id' => $id,
            'column_name' => '',
            'related_status_or_rating_id' => '',
            'change_type' => 'Some field',
            'activity' => '<b>Some Fields</b> were updated',
            'icons' => 'fa-edit',
            'color' => 'bg-red',
            'user_id' => Auth::User()->id,
        ]);
        Flash::success(trans('admin/leads/general.status.updated')); // 'Lead successfully updated');

        return redirect()->back();
    }

    /*
    * Receive SMS Report
    *
    */
    public function storeLogo(Request $request, $id)
    {
        $lead = $this->lead->find($id);

        //  dd($request->file('logo'));

        if ($request->file('logo')) {
            $stamp = time();
            $destinationPath = public_path() . '/leads/';
            //file_upload
            $file = \Request::file('logo');
            //base_path() is proj root in laravel
            $filename = $file->getClientOriginalName();
            \Request::file('logo')->move($destinationPath, $stamp . $filename);

            //create second image as big image and delete original
            $image = \Image::make($destinationPath . $stamp . $filename)
                ->save($destinationPath . $stamp . $filename);

            $lead->logo = $stamp . $filename;
            //dd($lead);
        }
        if ($lead->isEditable()) {
            //dd($lead);
            $lead->save();
        }
        Flash::success('Profile Successfully Added'); // 'Lead successfully updated');

        return redirect('/admin/leads/{{$lead->id}}');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $type = \Request::get('type');
        $lead = $this->lead->find($id);

        // if(!isset($leads) && empty($leads)) {
        //     abort(404);
        // }

        if (!$lead->isdeletable()) {
            abort(403);
        }

        // Delete the Notes related to the lead
        $LeadNote = \App\Models\Note::where('lead_id', $id)->delete();

        // Delete the File related to the lead
        $LeadFile = \App\Models\File::where('lead_id', $id)->orderBy('id', 'desc')->get();
        if ($LeadFile) {
            foreach ($LeadFile as $k => $v) {
                $fileUrl = public_path() . '/files/' . $v->file;
                File::delete($fileUrl);
                $this->LeadFile->where('id', $v->id)->delete();
            }
        }

        // Delete mail data and attached file related to the lead
        $mail = \App\Models\Mail::where('lead_id', $id)->orderBy('id', 'desc')->get();
        if ($mail) {
            foreach ($mail as $k => $v) {
                // To delete the related attachment file while deleting the mail.
                $attachments = \App\Models\Attachment::where('mail_id', $v->id)->orderBy('id', 'desc')->get();
                if ($attachments) {
                    if ($attachments) {
                        foreach ($attachments as $key => $val) {
                            // Delete the attachment file from its location
                            $fileUrl = public_path() . '/sent_attachments/' . $val->file;
                            File::delete($fileUrl);
                            $this->attachment->delete($val->id);
                        }
                    }
                }
                $this->mail->where('id', $v->id)->delete();
            }
        }

        Audit::log(Auth::user()->id, trans('admin/leads/general.audit-log.category'), trans('admin/leads/general.audit-log.msg-destroy', ['name' => $lead->name]));
        $data = [$lead, 'username', Auth::User()->username, 'del'];
        $title = 'Lead ' . $lead->name . 'was deleted by ' . \Auth::user()->username;
        $this->lead->find($id)->delete();
        try {
            $from = env('APP_EMAIL');
            $to = env('REPORT_EMAIL');
            $mail = Mail::send(
                'emails.lead-task-update',
                compact('title'),
                function ($message) use ($data, $from, $to) {
                    $message->subject('Lead deleted - ' . $data[0]->name);
                    $message->from($from, env('APP_COMPANY'));
                    $message->to($to, '');
                }
            );
        } catch (\Exception $e) {
        }
        \App\Models\LeadMobile::where('lead_id', $id)->delete();
        Flash::success(trans('admin/leads/general.status.deleted')); // 'Lead successfully deleted');

        return redirect()->back();
    }

    /**
     * Delete Confirm.
     *
     * @param   int   $id
     * @return  View
     */
    public function getModalDelete($id)
    {
        $error = null;

        $lead = $this->lead->find($id);

        // dd($lead);

        if (!$lead->isdeletable()) {
            abort(403);
        }

        $modal_title = trans('admin/leads/dialog.delete-confirm.title');

        $lead = $this->lead->find($id);
        $type = \Request::get('type');
        $modal_route = route('admin.leads.delete', ['leadId' => $lead->id]) . '?type=' . $type;

        // dd($modal_route);

        $modal_body = trans('admin/leads/dialog.delete-confirm.body', ['id' => $lead->id, 'name' => $lead->name]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }


    public function confirmMultiDelete(Request $request){

        $ids = $request->chkLead;
        if(!is_array($ids)){
            Flash::error("Nothing To delete");
            return redirect()->back();
        }
        $this->lead->whereIn('id',$ids)->delete();
        Flash::success("Deleted selected leads");
        return redirect()->back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $lead = $this->lead->find($id);

        Audit::log(Auth::user()->id, trans('admin/leads/general.audit-log.category'), trans('admin/leads/general.audit-log.msg-enable', ['name' => $lead->name]));

        $lead->enabled = true;
        $lead->save();

        Flash::success(trans('admin/leads/general.status.enabled'));

        return redirect('/admin/leads');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        //TODO: Should we protect 'admins', 'users'??

        $lead = $this->lead->find($id);

        Audit::log(Auth::user()->id, trans('admin/leads/general.audit-log.category'), trans('admin/leads/general.audit-log.msg-disabled', ['name' => $lead->name]));

        $lead->enabled = false;
        $lead->save();

        Flash::success(trans('admin/leads/general.status.disabled'));

        return redirect('/admin/leads');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkLeads = $request->input('chkLead');

        Audit::log(Auth::user()->id, trans('admin/leads/general.audit-log.category'), trans('admin/leads/general.audit-log.msg-enabled-selected'), $chkLeads);

        if (isset($chkLeads)) {
            foreach ($chkLeads as $lead_id) {
                $lead = $this->lead->find($lead_id);
                $lead->enabled = true;
                $lead->save();
            }
            Flash::success(trans('admin/leads/general.status.global-enabled'));
        } else {
            Flash::warning(trans('admin/leads/general.status.no-lead-selected'));
        }

        return redirect('/admin/leads');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        //TODO: Should we protect 'admins', 'users'??

        $chkLeads = $request->input('chkLead');

        Audit::log(Auth::user()->id, trans('admin/leads/general.audit-log.category'), trans('admin/leads/general.audit-log.msg-disabled-selected'), $chkLeads);

        if (isset($chkLeads)) {
            foreach ($chkLeads as $lead_id) {
                $lead = $this->lead->find($lead_id);
                $lead->enabled = false;
                $lead->save();
            }
            Flash::success(trans('admin/leads/general.status.global-disabled'));
        } else {
            Flash::warning(trans('admin/leads/general.status.no-lead-selected'));
        }

        return redirect('/admin/leads');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function sendSMS(Request $request)
    {
        //TODO: Should we protect 'admins', 'users'??

        $chkLeads = $request->input('chkLead');

        Audit::log(Auth::user()->id, trans('admin/sms/general.audit-log.category'), trans('admin/leads/general.audit-log.send-sms'), $chkLeads);

        $recipients = '';
        if (isset($chkLeads)) {
            foreach ($chkLeads as $lead_id) {
                $lead = $this->lead->find($lead_id);
                $recipients .= $lead->mob_phone . ',';
                $lead->enabled = false;
                $lead->save();
            }
            rtrim($recipients, ',');

            $username = 'tbctest';
            $password = '*q&?JA%}b=+7';
            $from = '35006';    // Shortcode through which sms is sent.
            if (trim(\Request::get('message')) != '') {
                $message = trim(\Request::get('message')) . ' - ' . env('APP_COMPANY');

                // Authorization headers.
                // Using php's lib curl for sending request.
                $ch = curl_init();
                $curlUrl = 'http://smsprima.com/api/api/index?username=tbc&password=123TBC123&sender=TBC&destination=' . $recipients . '&type=1&message=' . urlencode($message) . ''; // api url as provided in the document.
                curl_setopt($ch, CURLOPT_URL, $curlUrl);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $response = curl_exec($ch);

                //dd($response);
                if (!curl_errno($ch)) {
                    if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200) {
                        foreach (json_decode($response) as $sms) {
                            if (!$sms->recipient || isset($sms->error)) {
                                continue;
                            }

                            $lead_phone = \App\Models\Lead::select('id')->where('mob_phone', $sms->recipient)->first();
                            if ($lead_phone) {
                                $attributes['lead_id'] = $lead_phone->id;
                            }
                            $attributes['recipient'] = $sms->recipient;
                            $attributes['uuid'] = $sms->id;
                            $attributes['status'] = '0';
                            $attributes['message'] = $message;
                            $this->sms->create($attributes);
                        }
                        Flash::success(trans('admin/sms/general.status.sms-sent'));
                    } else {
                        Flash::warning(trans('admin/sms/general.status.sms-err'));
                    }
                } else {
                    Flash::warning(trans('admin/sms/general.status.no-msg'));
                }
            } else {
                Flash::warning(trans('admin/sms/general.status.no-lead-selected'));
            }
        } else {
            return redirect('/admin/leads');
        }
    }

    /**
     * @return \Illuminate\View\View
     */
    public function sendLeadSMS(Request $request)
    {
        $lead_id = \Request::get('lead_id');

        if (trim(\Request::get('message')) != '') {
            if (\Request::get('recipients_no') == '') {
                Flash::warning('Mobile number is empty');
            } else {
                $message = trim(\Request::get('message')) . ' - ' . env('APP_COMPANY');
                $recipients = trim(\Request::get('recipients_no'));

                // Authorization headers.
                // Using php's lib curl for sending request.
                $ch = curl_init();
                $curlUrl = 'http://smsprima.com/api/api/index?username=tbc&password=123TBC123&sender=TBC&destination=' . $recipients . '&type=1&message=' . urlencode($message) . ''; // api url as provided in the document.
                curl_setopt($ch, CURLOPT_URL, $curlUrl);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $response = curl_exec($ch);
                //dd($response);
                if (!curl_errno($ch)) {
                    if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200) {
                        foreach (json_decode($response) as $sms) {
                            if (!$sms->recipient || isset($sms->error)) {
                                continue;
                            }

                            $attributes['lead_id'] = $lead_id;
                            $attributes['recipient'] = $sms->recipient;
                            $attributes['uuid'] = '';
                            $attributes['status'] = '0';
                            $attributes['message'] = $message;
                            $this->sms->create($attributes);
                        }
                    }
                    Flash::success(trans('admin/sms/general.status.sms-sent'));

                    // To update the last followed date
                    \App\Models\Lead::where('id', $lead_id)->update(['last_followed_by' => Auth::user()->id, 'last_followed_date' => date('Y-m-d H:i:s')]);
                } else {
                    Flash::warning(trans('admin/sms/general.status.sms-err'));
                }
            }
        } else {
            Flash::warning(trans('admin/sms/general.status.no-msg'));
        }

        return redirect('/admin/leads/' . $lead_id);
    }

    /*
    * Receive SMS Report
    *
    */
    public function receiveSMSReport()
    {
        $sms = \App\Models\Sms::where('uuid', \Request::get('id'))->first();
        $attributes['status'] = \Request::get('status');
        $sms->update($attributes);
    }

    /**
     * @param Request $request
     * @return array|static[]
     */
    public function searchByName(Request $request)
    {
        $return_arr = null;
        $query = $request->input('query');

        $leads = $this->lead->where('email', $query)->orWhere('name', 'LIKE', '%' . $query . '%')->get();

        foreach ($leads as $lead) {
            $id = $lead->id;
            $name = $lead->name;
            $email = $lead->email;

            $entry_arr = ['id' => $id, 'text' => "$name ($email)"];
            $return_arr[] = $entry_arr;
        }

        return $return_arr;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getInfo(Request $request)
    {
        $id = $request->input('id');
        $lead = $this->lead->find($id);

        return $lead;
    }

    /*public function sendsms()
    {
        \SMS::send('This is my message', [], function($sms) {
            $sms->to('+9779841858178');
        });
    }*/

    public function today_followed()
    {
        // $note_udpated = DB::table('users')
        //             ->select(DB::raw('count(lead_notes.id) as total, users.first_name as name, lead_notes.lead_id as leads'))
        //                 ->join('lead_notes', 'users.id', '=', 'lead_notes.user_id')
        //                 ->where('lead_notes.updated_at', '>=', date('Y-m-d 00:01'))
        //                 ->where('lead_notes.updated_at', '<=', date('Y-m-d 23:59'))
        //                 ->groupBy('users.id')
        //                 ->orderBy('total','desc')
        //                 ->limit(20)
        //                 ->get();

        $note_udpated = DB::table('lead_notes')
            ->select(DB::raw('users.first_name as name, leads.name as lead, lead_notes.lead_id, lead_notes.note, lead_notes.updated_at'))
            ->join('users', 'users.id', '=', 'lead_notes.user_id')
            ->join('leads', 'lead_notes.lead_id', '=', 'leads.id')
            ->where('lead_notes.updated_at', '>=', \Carbon\Carbon::now()->subDays(7))
            //->where('lead_notes.updated_at', '<=', \Carbon\Carbon::now()->endOfWeek())
            ->where('leads.org_id', Auth::user()->org_id)
            ->orderBy('lead_notes.id', 'desc')
            ->limit(20)
            ->get();

        $page_title = 'Total Leads Followed';
        $page_description = 'List of total leads followed by users today';

        return view('admin.leads.today_followed', compact('note_udpated', 'page_title', 'page_description'));
    }

    // For ajax autoload of the cities
    public function getCities()
    {
        $term = strtolower(trim(\Request::get('term')));

        $cities = DB::table('cities_master')->select('city', 'id', 'country')->where('city', 'LIKE', '%' . $term . '%')->groupBy('city')->take(7)->get();

        foreach ($cities as $v) {
            if (strpos(strtolower($v->city), $term) !== false) {
                $return_array[] = ['value' => ucfirst($v->city), 'id' => $v->id, 'country' => $v->country];
            }
        }

        return Response::json($return_array);
    }

    public function search()
    {
        $term = trim(\Request::get('search'));

        $leads = \App\Models\Lead::where('name', 'LIKE', '%' . $term . '%')
            ->where('org_id', Auth::user()->org_id)
            ->orWhere('email', 'LIKE', '%' . $term . '%')
            ->orWhere('mob_phone', 'LIKE', '%' . $term . '%')
            ->orWhere('id', 'LIKE', '%' . $term . '%')
            ->orderBy('name', 'asc')->paginate(30);

        $page_title = 'Admin | Search';
        $page_description = 'List of Lead by Keyword: ' . $term;

        return view('admin.leads.search', compact('leads', 'page_title', 'page_description'));
    }

    public function ajaxLeadStatus(Request $request)
    {
        $lead = $this->lead->find($request->id);
        $attributes['status_id'] = $request->status_id;

        $lead->update($attributes);

        return ['status' => 1];
    }

    public function ajaxLeadRating(Request $request)
    {
        $lead = $this->lead->find($request->id);
        $attributes['rating'] = $request->rating;
        $lead->update($attributes);

        return ['status' => 1];
    }

    public function transferLead($lead_id, Request $request)
    {
        $page_title = 'Admin | Lead | Transfer';
        $page_description = 'Transfer Lead';

        //dd($request->lead_id);

        $lead_id = $request->lead_id;
        $transfer_history = LeadTransfer::where('lead_id', $lead_id)->orderBy('id', 'DESC')->get();

        //dd($transfer_history);

        $users = \App\User::where('enabled', '1')->where('org_id', Auth::user()->org_id)->pluck('first_name', 'id')->all();

        //dd($transfer_history);

        return view('admin.leads.transfer', compact('page_title', 'page_description', 'transfer_history', 'users', 'lead_id'));
    }

    public function postTransferLead(Request $request)
    {
        $transfer = new LeadTransfer();

        $transfer->from_user_id = Auth::user()->id;
        $transfer->to_user_id = $request->to_user_id;
        $transfer->lead_id = $request->lead_id;

        // dd($transfer);

        //insert into transfer table
        $transfer->save();

        //now update lead table and change the user_id
        $updateLeadTable = \App\Models\Lead::find($request->lead_id);
        $updateLeadTable->user_id = $request->to_user_id;
        $updateLeadTable->save();

        //and send email to new lead owner

        Mail::send('emails.lead-transfer', compact('transfer'), function ($message) use ($attributes, $transfer, $request) {
            $message->subject(env('APP_NAME') . ' Num #' . $request->lead_id . ' Leads Transferred to you');
            $message->from(env('APP_EMAIL'), env('APP_COMPANY'));
            $message->to(\TaskHelper::getUser($request->to_user_id)->email, '');
        });

        Flash::success('Lead has been Transferred');

        return redirect('/admin/leads/' . $request->lead_id);
    }

    public function convertLead($lead_id, Request $request)
    {
        $page_title = 'Admin | Lead | Convert';
        $page_description = 'convert Potential Lead';

        //dd($request->lead_id);

        $lead_id = $this->lead->find($lead_id);
        $lead_status = \App\Models\Leadstatus::where('enabled', '1')->pluck('name', 'id');

        //dd($transfer_history);

        $users = \App\User::where('enabled', '1')->where('org_id', Auth::user()->org_id)->pluck('first_name', 'id')->all();

        //dd($transfer_history);

        return view('admin.leads.convert', compact('page_title', 'page_description', 'lead_status', 'users', 'lead_id'));
    }

    public function postConvertLead(Request $request)
    {

        //now update lead table and change the user_id
        $updateLeadTable = \App\Models\Lead::find($request->lead_id);
        $updateLeadTable->user_id = $request->user_id;
        $updateLeadTable->status_id = $request->status_id;
        $updateLeadTable->amount = $request->amount;
        $updateLeadTable->lead_type_id = '4';
        $updateLeadTable->target_date = $request->target_date;

        $lead = \App\Models\Lead::find($request->lead_id);

        if (!$lead->ledger_id) {
            $ledger_id = \TaskHelper::AddCityLedgerCustomer($lead->name);
            $updateLeadTable->ledger_id = $ledger_id;
        }

        $updateLeadTable->save();

        //and send email to new lead owner

        // Mail::send('emails.lead-convert', compact('updateLeadTable'), function ($message)
        //                                                     use ($attributes, $potential, $request) {
        //     $message->subject(env('APP_NAME'). ' Num #'. $request->lead_id. ' Leads Converted to Potential');
        //     $message->from(env('APP_EMAIL'), env('APP_COMPANY'));
        //     $message->to(\TaskHelper::getUser($request->user_id)->email, '');
        // });

        Flash::success('Lead converted to Customer');

        return redirect('/admin/leads/' . $request->lead_id . '?type=customer');
    }

    public function listingquery(Request $request, $lead_id)
    {
        $users = Query::where('lead_id', $lead_id)->get();
        $lead = $this->lead->find($lead_id);

        //dd($users);
        $page_title = 'Query Listing';
        $page_description = 'Displaying for: ' . $lead->name;

        return view('admin.leads.query_list', compact('page_title', 'page_description', 'lead_status', 'users', 'lead_id', 'lead'));
    }

    public function queryLead(Request $request, $lead_id)
    {
        $lead = $this->lead->find($lead_id);

        $page_title = 'Admin | New | Query';
        $page_description = 'New query for: ' . $lead->name;

        //dd($request->lead_id);
        //dd($lead_id);

        $lead = $this->lead->find($lead_id);
        $lead_status = \App\Models\Leadstatus::where('enabled', '1')->pluck('name', 'id');
        $products = \App\Models\Product::orderBy('ordernum')->where('enabled', '1')
            ->where('org_id', Auth::user()->org_id)->orderBy('name', 'ASC')
            ->pluck('name', 'id');

        // dd($lead_id);

        //dd($transfer_history);

        $users = \App\User::where('enabled', '1')
            //->where('org_id', Auth::user()->org_id)
            ->pluck('first_name', 'id')->all();

        //dd($transfer_history);

        return view('admin.leads.query', compact('page_title', 'page_description', 'lead_status', 'users', 'lead_id', 'products'));
    }

    public function postQueryLead(Request $request, $lead_id)
    {
        $attributes = $request->all();
        $attributes['lead_id'] = $lead_id;
        $attributes['user_id'] = Auth::user()->id;
        //  dd($lead_id);

        $lead = new Query();

        $lead = $lead->create($attributes);

        $paxes = $attributes['paxName'];
        //dd($attributes);
        $pax_name = $request->paxName;
        $mileage_card = $request->mileageCard;

        //Now Insert data in Pax table

        foreach ($paxes as $key => $value) {
            if ($value != '') {
                $paxTable = new QueryPax();

                $paxTable->lead_query_id = $lead->id;
                $paxTable->pax_name = $pax_name[$key];
                $paxTable->mileage_card = $mileage_card[$key];
                $paxTable->save();
            }
        }

        return redirect('/admin/lead_query_list/' . $request->lead_id);
    }

    public function queryLeadEdit(Request $request, $query_id)
    {
        $query = Query::find($query_id);
        // dd($query);

        $page_title = 'Admin | Edit | Query';
        $page_description = 'Edit query for: ' . $query->lead->name;

        $pax = QueryPax::where('lead_query_id', $query_id)->get();
        // dd($pax);

        // dd($lead);

        //dd($transfer_history);
        $lead = $this->lead->find($lead_id);
        $lead_status = \App\Models\Leadstatus::where('enabled', '1')->pluck('name', 'id');
        $products = \App\Models\Product::orderBy('ordernum')->where('enabled', '1')
            ->where('org_id', Auth::user()->org_id)->orderBy('name', 'ASC')
            ->pluck('name', 'id');

        // dd($lead_id);

        //dd($transfer_history);

        $users = \App\User::where('enabled', '1')
            //->where('org_id', Auth::user()->org_id)
            ->pluck('first_name', 'id')->all();

        return view('admin.leads.edit_query', compact('page_title', 'page_description', 'lead_status', 'query', 'query_id', 'pax', 'users', 'products', 'lead'));
    }

    public function updateQueryLead(Request $request, $query_id)
    {
        $attributes = $request->all();
        $query = Query::find($query_id);

        $query = $query->update($attributes);

        QueryPax::where('lead_query_id', $query_id)->delete();

        $paxes = $attributes['paxName'];
        //dd($attributes);
        $pax_name = $request->paxName;
        $mileage_card = $request->mileageCard;

        //Now Insert data in Pax table

        foreach ($paxes as $key => $value) {
            if ($value != '') {
                $paxTable = new QueryPax();

                $paxTable->lead_query_id = $query_id;
                $paxTable->pax_name = $pax_name[$key];
                $paxTable->mileage_card = $mileage_card[$key];
                $paxTable->save();
            }
        }

        return redirect('/admin/lead_query_list/' . $request->lead_id);
    }

    public function querydestroy($query_id)
    {
        $query = Query::find($query_id);
        $query_pax = QueryPax::where('lead_query_id', $query_id)->delete();
        // dd($query_pax);

        $lead_id = $query->lead_id;
        //   dd($lead_id);
        $query->delete();

        //$query_pax->delete();

        Flash::success('Query successfully Deleted'); // 'Lead successfully deleted');

        return redirect()->back();
    }

    public function getModalQueryDelete($query_id)
    {
        $error = null;

        $query = Query::find($query);

        $modal_title = 'Delete Query';

        $modal_route = route('admin.leads.delete', ['id' => $lead->id]) . '?type=' . $type;

        $modal_body = 'Do you really want to delete this query';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    // To get the data for autocomplete of Company
    public function get_company()
    {
        $term = strtolower(\Request::get('term'));
        $products = \App\Models\Company::select('id', 'name')->where('name', 'LIKE', '%' . $term . '%')->groupBy('name')->take(5)->get();
        $return_array = [];

        foreach ($products as $v) {
            if (strpos(strtolower($v->name), $term) !== false) {
                $return_array[] = ['value' => $v->name, 'id' => $v->id];
            }
        }

        return Response::json($return_array);
    }

    public function convertToClients($id)
    {
        $leads = $this->lead->find($id);
        if ($leads->company->name) {
            $clients_name = $leads->name;
        } else {
            $clients_name = $leads->name;
        }
        if ($leads->mob_phone) {
            $mobile = $leads->mob_phone;
        } else {
            $mobile = '';
        }
        $clients = [
            'org_id' => Auth::user()->id,
            'name' => $clients_name,
            'phone' => $mobile,
            'email' => $leads->email,
            'type' => $leads->leadType->name,
            'enabled' => '1',
        ];
        $client = \App\Models\client::create($clients);
        $full_name = $client->name;
        $_ledgers = \TaskHelper::PostLedgers($full_name, \FinanceHelper::get_ledger_id('CUSTOMER_LEDGER_GROUP'));
        $attributes['ledger_id'] = $_ledgers;
        $client->update($attributes);
        $leads->update(['moved_to_client' => '1']);
        Audit::log(Auth::user()->id, trans('admin/clients/general.audit-log.category'), trans('admin/clients/general.audit-log.msg-store', ['name' => $client->name]));
        Flash::success('Leads #' . $id . ' Successfully moved to client');

        return Redirect::back();
    }

    public function ConfirmconvertToClients($id)
    {
        $error = null;

        $lead = $this->lead->find($id);

        $modal_title = 'Convert to clients';

        $lead = $this->lead->find($id);
        $type = \Request::get('type');
        $modal_route = route('admin.lead.convert_lead_clients',$lead->id);

        $modal_body = 'Are you sure you want to convert lead with name ' . $lead->name . 'to clients';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function getcontactinfo(Request $request)
    {
        $temp_contact = Contact::where('full_name', $request->contact_id)->first();
        if ($temp_contact) {
            return ['data' => $temp_contact];
        } else {
            $data = [
                'full_name' => '',
                'phone' => '',
                'phone_2' => '',
                'email_1' => '',
                'address' => '',
                'department' => '',
                'position' => '',
                'landline' => '',
                'skype' => '',
                'website' => '',
                'city' => '',
                'salutation' => 'Mr',
            ];

            return ['data' => $data];
        }
    }

    public function getcountry()
    {
        $country = DB::table('cities_master')->select('country')->distinct('country')->get();

        return ['country' => $country];
    }

    public function getcity($country)
    {
        $city = DB::table('cities_master')->select('city', 'id')->where('country', $country)->get();

        return ['cities' => $city];
    }

    private function updateLeads($attributes, $id)
    {
        $lead = $this->lead->find($id);
        $lead->update($attributes);
        $updated = $this->lead->find($id);

        return $updated;
    }

    public function ajaxLeadUpdate(Request $request)
    {
        $changetype = $request->type;
        $leadId = $request->id;
        $old_lead = $this->lead->find($leadId);
        switch ($changetype) {
            case 'stages':
                $attributes['stage_id'] = $request->update_value;
                if ($attributes['stage_id'] == 5 || $attributes['stage_id'] == 6) {
                    $attributes['lead_type_id '] = 4;
                }
                $updated = $this->updateLeads($attributes, $leadId);
                $updated['stages_color'] = $updated->stage->color;
                $old_value = $old_lead->stage->name;
                $new_value = $updated->stage->name;
                $icons = 'fa-eject';
                $column_name = 'stage_id';
                $color = 'bg-blue';
                break;
            case 'products':
                $attributes['product_id'] = $request->update_value;
                $updated = $this->updateLeads($attributes, $leadId);
                $old_value = $old_lead->course->name;
                $new_value = $updated->course->name;
                $icons = 'fa-book';
                $column_name = 'courses';
                $color = 'bg-blue';
                break;
            case 'mob_phone':
                $attributes['mob_phone'] = $request->update_value;
                $updated = $this->updateLeads($attributes, $leadId);
                $old_value = $old_lead->mob_phone;
                $new_value = $updated->mob_phone;
                $column_name = 'mob_phone';
                $icons = 'fa-mobile';
                $color = 'bg-aqua';
                break;
            case 'sources':
                $attributes['communication_id'] = $request->update_value;
                $updated = $this->updateLeads($attributes, $leadId);
                $old_value = $old_lead->communication->name;
                $new_value = $updated->communication->name;
                $column_name = 'communication_id';
                $icons = 'fa-signal';
                $color = 'bg-aqua';
                break;
            case 'status':
                $attributes['status_id'] = $request->update_value;
                $updated = $this->updateLeads($attributes, $leadId);
                $updated['status_color'] = $updated->status->color;
                $old_value = $old_lead->status->name;
                $new_value = $updated->status->name;
                $column_name = 'status_id';
                $icons = 'fa-bar-chart';
                $color = 'bg-aqua';
                break;
            case 'rating':
                $attributes['rating'] = $request->update_value;
                $updated = $this->updateLeads($attributes, $leadId);
                $updated['rating_color'] = $updated->ratings->bg_color;
                $old_value = $old_lead->rating;
                $new_value = $updated->rating;
                $column_name = 'rating';
                $icons = 'fa-hand-peace-o';
                $color = 'bg-yellow';
                break;
            case 'target_date':
                $date = date_create($request->update_value);
                $attributes['target_date'] = date_format($date, 'Y-m-d');
                $updated = $this->updateLeads($attributes, $leadId);
                $old_value = $old_lead->target_date;
                $new_value = $updated->target_date;
                $column_name = 'target_date';
                $icons = 'fa-calendar-plus-o';
                $color = 'bg-yellow';
                break;
            case 'email':
                $attributes['email'] = $request->update_value;
                $updated = $this->updateLeads($attributes, $leadId);
                $old_value = $old_lead->email;
                $new_value = $updated->email;
                $column_name = 'email';
                $icons = 'fa-envelope-o';
                $color = 'bg-purple';
                break;
            case 'campaign':
                $attributes['campaign_id'] = $request->update_value;
                $updated = $this->updateLeads($attributes, $leadId);
                $old_value = $old_lead->campaign->name;
                $new_value = $updated->campaign->name;
                $column_name = 'campaign';
                $icons = 'fa-fire';
                $color = 'bg-red';
                break;
            case 'dob':
                $date = date_create($request->update_value);
                $attributes['dob'] = date_format($date, 'Y-m-d');
                $updated = $this->updateLeads($attributes, $leadId);
                $old_value = $old_lead->dob;
                $new_value = $updated->dob;
                $column_name = 'dob';
                $icons = 'fa-calendar-plus-o';
                $color = 'bg-green';
                break;
            case 'description':
                $attributes['description'] = $request->update_value;
                $updated = $this->updateLeads($attributes, $leadId);
                $old_value = $old_lead->description;
                $new_value = $updated->description;
                $column_name = 'description';
                $icons = 'fa-file-code-o';
                $color = 'bg-maroon';
                break;
            default:
                return false;
                break;
        }

        $title = 'Status update on' . $lead->name . ' at ' . $changetype . ' With val ' . $new_value . ' By ' . \Auth::User()->username;
        $old_value = (strlen($old_value) > 100) ? substr($old_value, 0, 100) . '...' : $old_value;
        $new_value = (strlen($new_value) > 100) ? substr($new_value, 0, 100) . '...' : $new_value;
        \App\Models\LeadActivityStream::create([
            'lead_id' => $leadId,
            'column_name' => $column_name,
            'related_status_or_rating_id' => $request->update_value,
            'change_type' => $changetype,
            'activity' => $changetype . ' was updated ' . ($old_value ? 'From <b>' . $old_value . '</b>' : '') . ' To <b>' . $new_value . '</b>',
            'icons' => $icons,
            'color' => $color,
            'user_id' => Auth::User()->id,
        ]);

        //send email with exception
        try {
            $from = env('APP_EMAIL');
            $to = env('REPORT_EMAIL');
            $mail = Mail::send(
                'emails.lead-task-update',
                compact('title'),
                function ($message) use ($data, $from, $to) {
                    $message->subject('Status changed - ' . $data[0]->name);
                    $message->from($from, env('APP_COMPANY'));
                    $message->to($to, '');
                }
            );
        } catch (\Exception $e) {
            echo $e;
        }

        return  ['status' => 1, 'data' => $updated];
    }
}
