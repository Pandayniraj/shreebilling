<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Proposal;
use App\Models\Role as Permission;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class ProposalController extends Controller
{
    /**
     * @var Client
     */
    private $proposal;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Client $proposal
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(Proposal $proposal, Permission $permission)
    {
        parent::__construct();
        $this->proposal = $proposal;
        $this->permission = $permission;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $proposal = Proposal::orderBy('id', 'DESC')->where('org_id', Auth::user()->org_id)->get();
        $page_title = 'Proposals';
        $page_description = 'proposal and contracts';

        return view('admin.proposal.index', compact('proposal','page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $proposal = $this->proposal->find($id);

        $page_title = 'Admin | Proposal | Show';
        $page_description = 'Proposal Detail';

        return view('admin.proposal.show', compact('proposal', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = 'Proposal or Contract'; // "Admin | Client | Create";
        $page_description = 'Create'; // "Creating a new client";

        $proposal = new \App\Models\Proposal();
        $perms = $this->permission->all();

        $clients = Client::select('id', 'name', 'location')
            ->where('type', 'Customer')->orderBy('id', 'DESC')->get();
        $contacts = \App\Models\Contact::select('id', 'full_name')
            ->where('enabled', '1')->get();
        $products = \App\Models\Product::select('id', 'name')
            ->where('enabled', '1')->get();

        $leads = \App\Models\Lead::select('id', 'name')
            ->orderBy('id', 'DESC')->take(100)->get();

        return view('admin.proposal.create', compact('proposal', 'perms', 'page_title', 'page_description', 'clients', 'contacts', 'products', 'leads'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'subject'      => 'required',
            'product_id'    => 'required',
            'type'    => 'required',
            'status'    => 'required',
            'body'    => 'required',
        ]);

        $attributes = $request->all();
        $attributes['org_id'] = Auth::user()->org_id;
        $attributes['user_id'] = Auth::user()->id;

        $proposal = $this->proposal->create($attributes);

        Flash::success('Proposal created successfully.');

        return redirect('/admin/proposal');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $proposal = $this->proposal->find($id);
        $perms = $this->permission->all();

        $clients = Client::select('id', 'name', 'location')
            ->where('type', 'Customer')->orderBy('id', DESC)->get();
        $contacts = \App\Models\Contact::select('id', 'full_name')
            ->where('enabled', '1')->get();
        $products = \App\Models\Product::select('id', 'name')
            ->where('enabled', '1')->get();

        $leads = \App\Models\Lead::select('id', 'name')
            ->orderBy('id', DESC)->take(100)->get();

        $page_title = 'Edit Proposal'; // "Admin | Client | Edit";
        $page_description = 'Edit Proposal';

        if (!$proposal->isEditable() && !$proposal->canChangePermissions()) {
            abort(403);
        }

        return view('admin.proposal.edit', compact('proposal', 'perms', 'clients', 'contacts', 'products', 'leads', 'page_title', 'page_description', 'clients', 'contacts', 'products', 'leads'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'subject'      => 'required',
            'product_id'    => 'required',
            'type'    => 'required',
            'status'    => 'required',
            'body'    => 'required',
        ]);

        $attributes = $request->all();

        $proposal = Proposal::where('id', $id)->first();
        if ($proposal->isEditable()) {
            $proposal->update($attributes);
        }

        Flash::success('Proposal updated successfully.'); // 'Client successfully updated');

        return redirect('/admin/proposal');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $proposal = Proposal::where('id', $id)->first();
        if (!$proposal->isdeletable()) {
            abort(403);
        }
        $proposal->delete();

        Flash::success('Proposal successfully deleted.');

        return redirect('/admin/proposal');
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

        $proposal = $this->proposal->find($id);

        if (!$proposal->isdeletable()) {
            abort(403);
        }

        $modal_title = 'Delete Proposal';

        $proposal = $this->proposal->find($id);
        $modal_route = route('admin.proposal.delete', ['proposalId' => $proposal->id]);

        $modal_body = 'Are you sure to delete Proposal with subject = ' . $proposal->subject . ', id = ' . $proposal->id . '.';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $proposal = $this->proposal->find($id);

        $proposal->enabled = true;
        $proposal->save();

        Flash::success('Proposal is enabled');

        return redirect('/admin/proposal');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        //TODO: Should we protect 'admins', 'users'??

        $proposal = $this->proposal->find($id);

        $proposal->enabled = false;
        $proposal->save();

        Flash::success('Proposal disabled successfully.');

        return redirect('/admin/proposal');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkproposal = $request->input('chkClient');

        if (isset($chkproposal)) {
            foreach ($chkproposal as $proposal_id) {
                $proposal = $this->proposal->find($proposal_id);
                $proposal->enabled = true;
                $proposal->save();
            }
            Flash::success('Proposal is enabled successfully.');
        } else {
            Flash::warning('Sorry! No any proposal selected.');
        }

        return redirect('/admin/proposal');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        //TODO: Should we protect 'admins', 'users'??

        $chkproposal = $request->input('chkClient');

        if (isset($chkproposal)) {
            foreach ($chkproposal as $proposal_id) {
                $proposal = $this->proposal->find($proposal_id);
                $proposal->enabled = false;
                $proposal->save();
            }
            Flash::success('Proposal is disabled successfully.');
        } else {
            Flash::warning('Sorry! No any proposal selected.');
        }

        return redirect('/admin/proposal');
    }

    /**
     * @param Request $request
     * @return array|static[]
     */
    public function searchByName(Request $request)
    {
        $return_arr = null;

        $query = $request->input('query');

        $proposal = $this->proposal->pushCriteria(new proposalWhereDisplayNameLike($query))->all();

        foreach ($proposal as $proposal) {
            $id = $proposal->id;
            $name = $proposal->name;
            $email = $proposal->email;

            $entry_arr = ['id' => $id, 'text' => "$name ($email)"];
            $return_arr[] = $entry_arr;
        }

        return $return_arr;
    }

    public function printInvoice($id)
    {
        $proposal = Proposal::where('id', $id)->first();
        //dd($orderDetails);
        return view('admin.proposal.print', compact('proposal'));
    }

    public function copyDoc($id)
    {
        $proposal = Proposal::where('id', $id)->first();

        $new_item = $proposal->replicate(); //copy attributes
        $new_item->push();

        Flash::success('Proposal successfully duplicated.');

        return redirect('/admin/proposal');
    }

    public function generatePDF($id)
    {
        $proposal = Proposal::where('id', $id)->first();
        //dd($proposal);
        $pdf = \PDF::loadView('admin.proposal.generateProposalPDF', compact('proposal'));
        $file = 'Proposal_' . $id . '.pdf';
        if (File::exists('proposal/' . $file)) {
            File::Delete('proposal/' . $file);
        }

        return $pdf->download($file);
    }

    public function getModalMail($proposalId)
    {
        $error = null;

        $proposal = Proposal::where('id', $proposalId)->first();

        if ($proposal->client_type == 'lead') {
            $email = $proposal->lead->email;
        } else {
            $email = $proposal->client->email;
        }

        if (!$email) {
            abort(403);
        }

        $modal_title = trans('admin/mails/dialog.send-mail.title', ['email' => $email]);
        $modal_route = route('admin.proposalMail.send-mail-modal', ['id' => $proposalId]);
        $to_email = $email;

        return view('admin.proposal.modal_mail', compact('proposal', 'modal_title', 'to_email', 'modal_route'));
    }

    public function postModalMail(Request $request, $proposalId)
    {
        $this->validate(
            $request,
            [
                'mail_from' => 'required',
                'mail_to'  => 'required',
                'subject'  => 'required',
                'message'  => 'required',
            ]
        );

        $attributes = [
            'id'   =>  $proposalId,
            'mail_from' =>  $request['mail_from'],
            'mail_to'   =>  $request['mail_to'],
            'subject'   =>  $request['subject'],
            'message'   =>  $request['message'],
        ];

        $proposal = Proposal::where('id', $proposalId)->first();

        //$pdf = App::make('dompdf.wrapper');
        $pdf = \PDF::loadHTML($request['message']);
        $file = 'Proposal_' . $proposalId . '.pdf';
        if (File::exists('proposal/' . $file)) {
            File::Delete('proposal/' . $file);
        }
        $pdf->save('proposal/' . $file);

        //send email
        $mail = Mail::send('admin.proposal.email-send', [], function ($message) use ($attributes, $proposal, $request) {
            $message->subject($attributes['subject']);
            $message->from($attributes['mail_from'], env('APP_COMPANY'));
            $message->to($attributes['mail_to'], '');
            $message->attach('proposal/' . $file);
        });

        Flash::success(trans('admin/mails/general.status.sent'));

        return redirect('/admin/proposal/' . $proposalId);
    }

    public function loadTemplate(Request $request)
    {
        $template = $request->template . '.php';

        $content = File::get(base_path('resources/views/admin/proposal/templates/' . $template));

        if ($content) {
            return ['success' => 1, 'data' => $content];
        } else {
            return ['success' => 0, 'data' => ''];
        }
    }
}
