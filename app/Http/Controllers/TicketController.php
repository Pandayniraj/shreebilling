<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketFile;
use Flash;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(Ticket $ticket, TicketFile $ticketFile)
    {
        $this->ticket = $ticket;
        $this->ticketFile = $ticketFile;
    }

    public function index()
    {
        $page_title = 'Admin|Ticket';
        $tickets = $this->ticket->select('tickets.*')
                ->where(function ($query) {
                    $terms = \Request::get('term');
                    if ($terms) {
                        return $query->where('tickets.ticket_number', $terms)
                                ->orWhere('tickets.issue_summary', 'LIKE', '%'.$terms.'%')
                                ->orWhere('tickets.customer', 'LIKE', '%'.$terms.'%')
                                ->orWhere('tickets.serial_no', 'LIKE', '%'.$terms.'%')
                                ->orWhere('tickets.model_no', 'LIKE', '%'.$terms.'%')
                                ->orWhere('tickets.from_email', $terms)
                                ->orWhere('users.email', $terms)
                                ->orWhere('tickets.from_user', $terms)
                                ->orWhere('users.username', 'LIKE', $terms.'%');
                    }
                })->leftjoin('users', 'users.id', '=', 'tickets.user_id')
                ->orderBy('tickets.id', 'desc')
                ->groupBy('tickets.id')
                ->paginate(30);
                
        return view('admin.ticket.index', compact('page_title', 'tickets'));
    }

    public function cust_index()
    {
        $page_title = 'Admin| Customer Tickets';
        $tickets = $this->ticket->select('tickets.*')
                ->where('customer_id','=', request()->segment(4))
                ->leftjoin('users', 'users.id', '=', 'tickets.user_id')
                ->orderBy('tickets.id', 'desc')
                ->groupBy('tickets.id')
                ->paginate(30);
                
        return view('admin.ticket.index', compact('page_title', 'tickets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = 'Admin|Ticket';
        $sla_plan = ['24hrs'=>'24Hours'];
        $users = \App\User::where('enabled', '1')->pluck('username as name', 'id');
        $department = \App\Models\Department::pluck('deptname as name', 'departments_id as id');
        $customer = $clients = \App\Models\Client::where('relation_type','=','customer')->pluck('name','id');

        return view('admin.ticket.create', compact('page_title', 'users', 'department', 'sla_plan','customer'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = $request->all();

        $attributes['model_no'] = json_encode($attributes['model_no']);
        $attributes['serial_no'] = json_encode($attributes['serial_no']);
        $attributes['issue_summary'] = json_encode($attributes['issue_summary']);
        $attributes['ticket_number'] = rand(1000000, 9999999);
        $attributes['cc_users'] = json_encode($attributes['cc_users']);

        $ticket = $this->ticket->create($attributes);

        $files = $request->file('attachment');

        $destinationPath = public_path('/tickets/');

        if (! \File::isDirectory($destinationPath)) {
            \File::makeDirectory($destinationPath, 0777, true, true);
        }

        foreach ($files ?? [] as $key=>$doc_) {
            if ($doc_) {
                $doc_name = time().''.$doc_->getClientOriginalName();
                $doc_->move($destinationPath, $doc_name);
                $ticket_attachment = ['type'=>'summary', 'attachment'=>$doc_name, 'ticket_id'=>$ticket->id];
                $this->ticketFile->create($ticket_attachment);
            }
        }

        Flash::success('Tickets Successfully created');

        return redirect('/admin/ticket');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticket = $this->ticket->find($id);
        $page_title = 'Admin|Ticket';
        $cc_users = json_decode($ticket->cc_users);
        $sla_plan = ['24hrs'=>'24Hours'];
        $ticketFile = $this->ticketFile->where('ticket_id', $id)->get();
        $users = \App\User::where('enabled', '1')->pluck('username as name', 'id');
        $department = \App\Models\Department::pluck('deptname as name', 'departments_id as id');
        $responseMessage = \App\Models\TicketResponseMessage::where('ticket_id', $id)->get();

        return view('admin.ticket.show', compact('page_title', 'users', 'department', 'ticket', 'cc_users', 'sla_plan', 'ticketFile', 'responseMessage'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ticket = $this->ticket->find($id);
        $page_title = 'Admin|Ticket';
        $cc_users = json_decode($ticket->cc_users);
        $sla_plan = ['24hrs'=>'24Hours'];
        $ticketFile = $this->ticketFile->where('ticket_id', $id)->get();
        $users = \App\User::where('enabled', '1')->pluck('username as name', 'id');
        $department = \App\Models\Department::pluck('deptname as name', 'departments_id as id');
        $customer = $clients = \App\Models\Client::where('relation_type','=','customer')->pluck('name','id');

        return view('admin.ticket.edit', compact('page_title', 'users', 'department', 'ticket', 'cc_users', 'sla_plan', 'ticketFile','customer'));
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
        $ticket = $this->ticket->find($id);
        if (! $ticket->isEditable()) {
            abort(404);
        }
        $attributes = $request->all();
        $attributes['cc_users'] = json_encode($attributes['cc_users']);
        $attributes['model_no'] = json_encode($attributes['model_no']);
        $attributes['serial_no'] = json_encode($attributes['serial_no']);
        $attributes['issue_summary'] = json_encode($attributes['issue_summary']);

        $files = $request->file('attachment');

        $destinationPath = public_path('/tickets/');

        if (! \File::isDirectory($destinationPath)) {
            \File::makeDirectory($destinationPath, 0777, true, true);
        }

        foreach ($files??[] as $key=>$doc_) {
            if ($doc_) {
                $doc_name = time().''.$doc_->getClientOriginalName();
                $doc_->move($destinationPath, $doc_name);
                $ticket_attachment = ['type'=>'summary', 'attachment'=>$doc_name, 'ticket_id'=>$ticket->id];
                $this->ticketFile->create($ticket_attachment);
            }
        }
        $ticket->update($attributes);
        Flash::success('Ticket Successfully Updated');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ticket = $this->ticket->find($id);
        if (! $ticket->isDeletable()) {
            abort(404);
        }
        $ticket->delete();
        $this->ticketFile->where('ticket_id', $id)->delete();
        Flash::success('Ticket Successfully Deleted');

        return redirect()->back();
    }

    public function destroyFile($id)
    {
        $ticketFile = $this->ticketFile->find($id);
        if ($ticketFile->isEditable()) {
            \File::delete(public_path('tickets/'.$ticketFile->attachment));
            $ticketFile->delete();
        } else {
            abort(404);
        }

        return ['success'=>true];
    }

    public function getModalDelete($id)
    {
        $error = null;

        $ticket = $this->ticket->find($id);
        $modal_title = trans('/admin/ticket/dialog.delete-confirm.title');
        $modal_body = trans('/admin/ticket/dialog.delete-confirm.body')." #{$ticket->ticket_number}";
        $modal_route = route('admin.ticket.delete', ['id' => $id]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function sendResponse(Request $request)
    {
        $attributes = $request->all();

        $ticket = $this->ticket->find($request->ticket_id);

        $attributes['user_id'] = \Auth::user()->id;

        $attributes['ticket_id'] = $ticket->id;

        $response = \App\Models\TicketResponseMessage::create($attributes);

        // return view('admin.ticket.response_email',compact('response'));
        $from = env('APP_EMAIL');
        // $to = 'suman9841.thapa@gmail.com';
        $to = $ticket->from_email ?? $ticket->user->email;
        $mail = \Mail::send('admin.ticket.response_email', compact('response'),
        function ($message) use ($response, $from, $to,$ticket) {
            $message->subject($ticket->issue_summary);
            $message->from($from, env('APP_COMPANY'));
            $message->to($to, '');
        });
        Flash::success('Response Message Successfully Sent');

        return redirect()->back();
    }
}
