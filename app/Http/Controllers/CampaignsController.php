<?php

namespace App\Http\Controllers;

use App\Jobs\SendBulkEmailToCampainLead;
use App\Models\Campaigns;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CampaignsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = 'Campaigns | index';
        $campaigns = Campaigns::with('leads')->orderBy('id', 'desc')->paginate(30);

        return view('admin.campaigns.index', compact('campaigns', 'page_title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = 'Campaigns | create';

        return view('admin.campaigns.create', compact('page_title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $camp = $request->all();
        $camp['user_id'] = Auth::user()->id;
        Campaigns::create($camp);
        Flash::success('Campaigns successfully created');

        return redirect('/admin/campaigns/index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page_title = 'Campaigns | show';
        $show = Campaigns::find($id);
        $leads = \App\Models\Lead::where('campaign_id', Request::segment(4))->paginate('25');

        return view('admin.campaigns.show', compact('show', 'page_title', 'leads'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page_title = 'Campaigns | edit';
        $edit = Campaigns::find($id);

        return view('admin.campaigns.edit', compact('edit', 'page_title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $update = $request->all();
        Campaigns::find($id)->update($update);
        Flash::success('Campaigns successfully updated');

        return redirect('/admin/campaigns/index/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Campaigns::find($id)->delete();
        Flash::success('Campaigns successfully deleted');

        return redirect('/admin/campaigns/index/');
    }

    public function getModalDelete($id)
    {
        $error = null;
        $camp = Campaigns::find($id);

        // if (!$event->isdeletable())
        // {
        //     abort(403);
        // }

        $modal_title = 'Delete Campaigns';
        $modal_body = 'Are you sure that you want to delete Campaigns ID '.$camp->id.' with the name '.$camp->name.'? This operation is irreversible';
        // $lead = $this->lead->find($id);
        // $type = \Request::get('type');
        $modal_route = route('admin.campaigns.delete', [$camp->id]);

        // $modal_body = trans('admin/leads/dialog.delete-confirm.body', ['id' => $lead->id, 'name' => $lead->name]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function getbulkmail($campaign_id)
    {
        $route = route('admin.campaigns.bulk-mail', ['id'=>$campaign_id]);

        return view('admin.campaigns.bulk-mail', compact('route'));
    }

    public function postbulkmail(Request $request, $campaign_id)
    {
        $leads = \App\Models\Lead::where('campaign_id', $campaign_id)->get();

        if (count($leads) == 0) {
            return Redirect::back()->withErrors(['error'=>'No any Lead to send Message !!']);
        }

        foreach ($leads as $lead) {
            $mail_to = trim($lead->email, ' ');
            $mail_to = trim($mail_to, urlencode('%A0'));

            if ($mail_to != '' && strpos($mail_to, '@') !== false) {
                $jobs = (new SendBulkEmailToCampainLead($lead, $request->all(), $mail_to, $fields));
                $this->dispatch($jobs);
            }
        }

        Flash::success('Message Sucessfully added to queue');

        return redirect()->back();
    }
}
