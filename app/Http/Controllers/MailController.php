<?php

namespace App\Http\Controllers;

use App\Jobs\SendBulkEmail;
use App\Jobs\SendBulkEmailToAll;
use App\Jobs\SendBulkEmailToContact;
use App\Jobs\SendReminderEmail;
use App\Models\Audit as Audit;
use App\Models\Imap as Imap;
use App\Models\Lead as Lead;
use App\Models\LeaveApplication as Attachment;
use App\Models\Mail as MailModal;
use App\Models\Role as Permission;
use App\Models\SalesOrder;
use Ddeboer\Imap\Message;
use Ddeboer\Imap\Server;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class MailController extends Controller
{
    /**
     * @var Mail
     */
    private $Mail;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Mail $Mail
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(MailModal $Mail, Lead $Lead, Permission $permission, Attachment $attachment, Imap $imap)
    {
        parent::__construct();
        $this->mail = $Mail;
        $this->lead = $Lead;
        $this->permission = $permission;
        $this->attachment = $attachment;
        $this->imap = $imap;
    }


        public function getBulkEmailContact()
    {
       $page_title = 'Send the Bulk Email to Contact';
        $page_description = 'Send the Bulk Email to all the contacts';

        $clients = \App\Models\Client::where('org_id', Auth::user()->org_id)->select('id', 'name')->where('enabled', '1')->get();
        $groups = \App\Models\CustomerGroup::where('enabled', '1')->get();

        $valid_addresses = \App\Models\Contact::where('email_1', 'like', '%_@__%.__%')->count();
        $total_leads = \App\Models\Contact::count();

        return view('admin.email.bulkEmailContact', compact('page_title', 'page_description','groups', 'clients','valid_addresses','total_leads'));
    }



    public function postBulkMailContact(Request $request)
    {
            if ($request['contact_id'] != '') {
                $contacts = \App\Models\Contact::where('email_1', 'like', '%_@__%.__%')->where('enabled', '1')->where('id', $request['contact_id'])->get();
            } elseif($request['client_id'] != '' && $request['contact_id'] == '') {
                $contacts = \App\Models\Contact::where('email_1', 'like', '%_@__%.__%')->where('enabled', '1')->where('client_id', $request['client_id'])->get();
            }elseif($request['group_id'] != '' && $request['client_id'] == '' && $request['contact_id'] == '')
            {
                $client_ids = \App\Models\Client::where('customer_group',$request['group_id'])->pluck(id);
                $contacts = \App\Models\Contact::whereIn('client_id',$client_ids)->where('email_1', 'like', '%_@__%.__%')->where('enabled', '1')->get();
            }else{
                $contacts = \App\Models\Contact::where('email_1', 'like', '%_@__%.__%')->where('enabled', '1')->get();
            }



                    // Save the attachment data and file
                        if ($request->file('attachment')) {
                            $stamp = time();
                            $file = $request->file('attachment');
                            $destinationPath = public_path().'/sent_attachments/';
                            $filename = $file->getClientOriginalName();

                            $fields = ['file' => $stamp.$filename];

                            $request->file('attachment')->move($destinationPath, $stamp.$filename);
                            $data2['attachment'] =  $stamp.$filename;
                        } else {
                            $fields = '';
                        }
                        //dd($request->all());
                        // $mail = 0;

                        // if ($contacts == null || $contacts->count() == '0') {
                        //     Flash::warning('Sorry ! No any contacts to send email.');
                        //     return Redirect::back();
                        // }

                        $data2['title']         = $request['title'];
                        $data2['subject']       = $request['subject'];
                        $data2['message']       = $request['message'];
                        $data2['product_id']    = $request['course_no'];
                        $data2['status_id']     = $request['status_no'];
                        $data2['created_at']    = \Carbon\Carbon::now();

                        DB::table('bulk_email_campaign')->insert($data2);

                    $validatedData = $request->validate([
                                                    'title' => 'required|max:255',
                                                    'subject' => 'required|max:255',
                                                    'message'  => 'required',]);

                        if($validatedData){
                            $details=[
                                // "email"=> "rajunpl@gmail.com",
                                "title"=>$request->title,
                                "subject"=>$request->subject,
                                "message"=>$request->message,
                                'attachments'=>$data2['attachment']
                            ];

                            dispatch(new SendBulkEmailToContact($details,$contacts));
                            //return redirect()->route('email.index');
                            Flash::success('Bulk mail send successfully in the background...');

                              return Redirect::back();
                    }
}

    public function getModalMail($taskId)
    {
        $error = null;

        $Lead = $this->lead->find($taskId);

        if (! $Lead->user->email) {
            abort(403);
        }

        $modal_title = trans('admin/mails/dialog.send-mail.title', ['email' => $Lead->user->email]);
        $modal_route = route('admin.mail.send-mail-modal', ['id' => $taskId]);
        $to_email = $Lead->email;

        return view('modal_mail', compact('error', 'modal_route', 'modal_title', 'to_email', 'Lead'));
    }

    public function getModalMailQuotation($taskId)
    {
        $error = null;

        $saleorder = SalesOrder::where('order_no', $taskId)->first();

        $modal_title = trans('admin/mails/dialog.send-mail.title', ['email' => 'bimal@meronetwork.com']);
        $modal_route = route('admin.mail.quotation.show-mailmodal', ['id' => $taskId]);
        $to_email = 'bimal@meronetwork.com';

        return view('modal_mail', compact('error', 'modal_route', 'modal_title', 'to_email', 'Lead'));
    }

    public function postModalMail(Request $request, $leadId)
    {
        $this->validate($request, ['mail_from' => 'required',
                                            'mail_to'  => 'required',
                                            'subject'  => 'required',
                                            'message'  => 'required',
        ]);

        $attributes = [
                        'lead_id'   =>  $leadId,
                        'user_id'   =>  Auth::user()->id,
                        'mail_from' =>  $request['mail_from'],
                        'mail_to'   =>  $request['mail_to'],
                        'subject'   =>  $request['subject'],
                        'message'   =>  $request['message'],
                    ];

        $Lead = $this->lead->find($leadId);

        //$pdf = App::make('dompdf.wrapper');
        $pdf = \PDF::loadHTML($request['message']);
        $file = str_replace(' ', '_', trim($Lead->name)).'_CRM_'.$Lead->id.'.pdf';
        if (File::exists('uploads/'.$file)) {
            File::Delete('uploads/'.$file);
        }
        $pdf->save('uploads/'.$file);
        //dd($file);

        Audit::log(Auth::user()->id, trans('admin/mails/general.audit-log.category'), trans('admin/mails/general.audit-log.msg-store', ['name' => $attributes['mail_from']]));

        $mail = $this->mail->create($attributes);

        // Save the attachment data and file
        if ($request->file('attachment')) {
            $stamp = time();
            $file = $request->file('attachment');
            $destinationPath = public_path().'/sent_attachments/';
            $filename = $file->getClientOriginalName();

            $fields = [
                        'mail_id'   =>  $mail->id,
                        'file'  =>  $stamp.$filename,
                    ];

            $attachments = $this->attachment->create($fields);

            $request->file('attachment')->move($destinationPath, $stamp.$filename);
        } else {
            $fields = '';
        }

        //send email
        $mail = Mail::send('emails.email-send', [], function ($message) use ($attributes, $file, $Lead, $request, $fields) {
            //$mail = Mail::send('emails.email-send', [], function ($message) use ($attributes, $Lead, $request, $fields) {
            $message->subject($attributes['subject']);
            $message->from($attributes['mail_from'], env('APP_COMPANY'));
            $message->to($attributes['mail_to'], '');
            $message->attach('uploads/'.$file);
            if ($request->file('attachment')) {
                $message->attach('sent_attachments/'.$fields['file']);
            }
        });

        Flash::success(trans('admin/mails/general.status.sent'));

        return redirect('/admin/leads/'.$leadId);
    }

    public function postModalMailQuotation(Request $request, $leadId)
    {
        $this->validate($request, ['mail_from' => 'required',
                                    'mail_to'  => 'required',
                                    'subject'  => 'required',
                                    'message'  => 'required',
                                            ]);

        $attributes = [
                    'lead_id' => $leadId,
                    'user_id' => Auth::user()->id,
                    'mail_from' => $request['mail_from'],
                    'mail_to' => $request['mail_to'],
                    'subject' => $request['subject'],
                    'message' => $request['message'],
                    ];

        //dd($attributes);

        $Lead = \App\Models\Orders::find($leadId);

        //dd($Lead);

        //$pdf = App::make('dompdf.wrapper');
        // $pdf = \PDF::loadHTML($request['message']);

        $ord = \App\Models\Orders::find($leadId);
        $orderDetails = \App\Models\OrderDetail::where('order_id', $ord->id)->get();
        $imagepath = Auth::user()->organization->logo;

        $pdf = \PDF::loadView('admin.orders.generateInvoicePDF', compact('ord', 'imagepath', 'orderDetails'));

        $file = str_replace(' ', '_', trim($Lead->name)).'_CRM_'.$Lead->id.'.pdf';
        if (File::exists('uploads/'.$file)) {
            File::Delete('uploads/'.$file);
        }
        $pdf->save('uploads/'.$file);
        //dd($file);

        Audit::log(Auth::user()->id, trans('admin/mails/general.audit-log.category'), trans('admin/mails/general.audit-log.msg-store', ['name' => $attributes['mail_from']]));

        $mail = $this->mail->create($attributes);

        // Save the attachment data and file
        if ($request->file('attachment')) {
            $stamp = time();
            $file = $request->file('attachment');
            $destinationPath = public_path().'/sent_attachments/';
            $filename = $file->getClientOriginalName();

            $fields = [
        'mail_id' => $mail->id,
        'file' => $stamp.$filename,
        ];

                    $attachments = $this->attachment->create($fields);

                    $request->file('attachment')->move($destinationPath, $stamp.$filename);
                } else {
                    $fields = '';
                }

                //send email
                $mail = Mail::send('emails.email-send', [], function ($message) use ($attributes, $file, $Lead, $request, $fields) {
                    //$mail = Mail::send('emails.email-send', [], function ($message) use ($attributes, $Lead, $request, $fields) {
                    $message->subject($attributes['subject']);
                    $message->from($attributes['mail_from'], env('APP_COMPANY'));
                    $message->to($attributes['mail_to'], '');
                    $message->attach('uploads/'.$file);
                    if ($request->file('attachment')) {
                        $message->attach('sent_attachments/'.$fields['file']);
                    }
                });

                Flash::success(trans('admin/mails/general.status.sent'));

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

        $mail = $this->mail->find($id);

        if (! $mail->isdeletable()) {
            abort(403);
        }

        $modal_title = trans('admin/mails/dialog.delete-confirm.title');

        $modal_route = route('admin.mail.delete', ['id' => $mail->id]);

        $modal_body = trans('admin/mails/dialog.delete-confirm.body', ['from' => $mail->mail_from, 'subject' => $mail->subject]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $mail = $this->mail->find($id);

        if (! $mail->isdeletable()) {
            abort(403);
        }

        // To delete the related attachment file while deleting the mail.
        $attachments = \App\Models\Attachment::where('mail_id', $id)->orderBy('id', 'desc')->get();
        if ($attachments) {
            foreach ($attachments as $key => $val) {
                // Delete the attachment file from its location
                $fileUrl = public_path().'/sent_attachments/'.$val->file;
                File::delete($fileUrl);
                $this->attachment->delete($val->id);
            }
        }

        $this->mail->delete($id);

        Audit::log(Auth::user()->id, trans('admin/mails/general.audit-log.category'), trans('admin/mails/general.audit-log.msg-destroy', ['mail_from' => $mail->mail_from]));

        Flash::success(trans('admin/mails/general.status.deleted')); // 'Lead successfully deleted');

        return redirect('/admin/leads/'.$mail->lead_id);
    }

    public function inbox()
    {
        // if there is the cache of the inbox_messages, bypass the zoho mail connection and show the inbox messages stored in cache
        //\Cache::forget('inbox_size'.\Auth::user()->id);
        if (! Cache::has('inbox_size'.\Auth::user()->id)) {
            $imap = $this->imap->where('user_id', Auth::user()->id)->first();
            if (! $imap) {
                Flash::error('Please fill your login detail.');

                return redirect('/admin/myprofile/editimap');
            }
            if ((strpos($imap->imap_email, 'zoho') !== false) || (strpos($imap->imap_email, 'worksuk') !== false)) {
                $server = new Server('imap.zoho.com');
            } else {
                $server = new Server('imap.googlemail.com');
            }
            $connection = $server->authenticate($imap->imap_email, $imap->imap_password);
            $mailbox = $connection->getMailbox('INBOX');
            $messages = $mailbox->getMessages();
        }
        $page_title = trans('admin/mails/general.inbox.index.title');
        $page_description = trans('admin/mails/general.inbox.index.description');

        return view('admin.mails.index', compact('page_title', 'page_description', 'messages'));
    }

    public function reloadinbox()
    {
        Cache::flush();

        return redirect('/admin/mail/inbox');
    }

    public function show($mailid)
    {
        $server = new Server('imap.zoho.com');
        // $connection is instance of \Ddeboer\Imap\Connection
        $connection = $server->authenticate('youremail@email.com', 'password');
        $mailbox = $connection->getMailbox('INBOX');
        $message = $mailbox->getMessage($mailid);
        $attached = '';
        $attachments = $message->getAttachments();
        if ($attachments) {
            // Empty the attachments folder
            File::cleanDirectory('attachments');

            $attached .= '<br/><label style="margin-bottom:0;">Attachment</label><br/>';
            foreach ($attachments as $attachment) {
                // Save the attachment files to public/attachments folder, so that they can be downloaded.
                file_put_contents('attachments/'.$attachment->getFilename(), $attachment->getDecodedContent());
                $attached .= '<a href="/admin/mail/attachment/'.$attachment->getFilename().'">'.$attachment->getFilename().'</a><br/>';
            }
        }
        $data = '<tr id="show-mail">
                    <td colspan="6">
                        <div class="mail-body">
                            <div style="background:#fff; padding:10px;">
                                <div class="pad-10 header">
                                    <div><i class="lbl">From: </i> <i id="email_from">'.$message->getFrom().'</i> <i class="close-msg" style="float:right;">x</i></div>
                                    <div><i class="lbl">Subject: </i> <i id="email_subject">'.$message->getSubject().'</i></div>
                                    <div><i class="lbl">Date: </i>'.$message->getDate()->format('Y-m-d H:i:s').'</div>
                                </div>
                                <div>'.$message->getBodyHtml().$attached.'
                                        <div class="mail-reply" style="border-top:1px dashed #999; padding-top:5px; margin-top:22px;">
                                        <label>Reply </label><br/>
                                        <textarea cols="50" name="reply" rows="3" id="reply_msg" class="form-control"></textarea>
                                        <span style="float:left; color:#090; margin-top:5px" id="reply_noti"></span>
                                        <button style="float:right; margin-top:5px;" id="reply_btn" class="btn btn-primary" type="submit">Reply</button>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                             </div>
                        </div>
                    </td>
                </tr>';

        return ['messages' => $data];
    }

    public function sent()
    {
        $server = new Server('imap.zoho.com');

        // $connection is instance of \Ddeboer\Imap\Connection
        $connection = $server->authenticate('youremail@email.com', 'password');
        $mailbox = $connection->getMailbox('Sent');
        $messages = $mailbox->getMessages();

        foreach ($messages as $message) {
            print_r($message);
            exit;
            // $mailbox is instance of \Ddeboer\Imap\Mailbox
            echo 'Subject: '.$message->getSubject().'<br/>';
            echo 'From: '.$message->getFrom().'<br/>';
            //echo 'To: '.$message->getTo().'<br/>';
            //echo 'Date: '.$message->getDate().'<br/>';
            echo 'Body: '.$message->getBodyHtml().'<br/>';
            echo 'Seen: '.$message->isSeen().'<br/><br/>';
        }
    }

    public function reply(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $subject = $request->input('subject');
        $msg = $request->input('msg');

        //send email
        $mail = Mail::send('emails.email-send', ['msg'=>$msg], function ($message) use ($from, $to, $subject) {
            $message->subject($subject);
            $message->from($from, 'TBC');
            $message->to($to, '');
        });

        if ($mail) {
            return ['messages' => 'Successfuly Replied...'];
        } else {
            return ['messages' => 'Error on mail reply...'];
        }
        //return ['messages' => $from.' '.$to.' '.$subject.' '.$msg];
    }

    /*
    * Download the mail attachment
    */
    public function attachment($filename)
    {
        return response()->download('attachments/'.$filename);
    }

    /*
    * Download the mail sent attachment
    */
    public function sent_attachment($filename)
    {
        return response()->download('sent_attachments/'.$filename);
    }

    /*
    * Show Mail from lead in modal
    */
    public function showModalLeadMail($id)
    {
        $error = null;

        $mail = $this->mail->find($id);

        if (! $mail) {
            abort(403);
        }

        $modal_title = trans('admin/mails/dialog.show-mail.title', ['mail_from' => $mail->mail_from]);
        $data = '';
        $attached = '';
        $attachments = $mail->attachment;
        if (count($attachments)) {
            $attached .= '<span style="font-weight:bold;">Attachments:</span><br/>';
            foreach ($attachments as $k => $v) {
                $attached .= '<p style="margin-bottom:0; font-weight:bold;"><a href="/admin/mail/sent_attachment/'.$v->file.'">'.$v->file.'</a></p>';
            }
        }

        if ($mail) {
            $data .= '<div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4>Mail Detail</h4>
                     </div>
                     <div class="modal-body"> 
                        <div style="background:#fff; padding:10px;">
                            <div class="pad-10 header">
                                <div><i class="lbl">From: </i> <i id="email_from">'.$mail->mail_from.'</i></div>
                                <div><i class="lbl">Subject: </i> <i id="email_subject">'.$mail->subject.'</i></div>
                                <div><i class="lbl">Date: </i>'.$mail->created_at->format('Y-m-d H:i:s').'</div>
                            </div>
                            <div><hr style="margin-bottom:10px; border:1px solid #000;"><strong>Body: </strong><br/>
                                '.$mail->message.'<br/>'.$attached.'                    
                            </div>
                       </div>
                    </div>
                    ';
        }

        return ['messages' => $data];
    }

    /*
    *
    *   For Online Leads
    *
    */
    public function getOfferLetterModalMail($taskId)
    {
        $error = null;
       
        $Lead = $this->lead->find($taskId);

        if (! $Lead->user->email) {
            abort(403);
        }

        $modal_title = trans('admin/mails/dialog.send-mail.title', ['email' => $Lead->user->email]);
        $modal_route = route('admin.mail.send-offerlettermodal',  $taskId);
        $to_email = $Lead->email;

        return view('modal_offerletter', compact('error', 'modal_route', 'modal_title', 'to_email', 'Lead'));
    }

    public function postOfferLetterModalMail(Request $request, $leadId)
    {
        $this->validate($request, ['mail_from' => 'required',
                                            'mail_to'  => 'required',
                                            'subject'  => 'required',
                                            'message'  => 'required',
        ]);

        $attributes = [
                        'lead_id'   =>  $leadId,
                        'user_id'   =>  Auth::user()->id,
                        'mail_from' =>  $request['mail_from'],
                        'mail_to'   =>  $request['mail_to'],
                        'subject'   =>  $request['subject'],
                        'message'   =>  $request['message'],
                    ];

        //dd($request['mail_to']);

        $Lead = $this->lead->find($leadId);
        //$pdf = App::make('dompdf.wrapper');
        $pdf = \PDF::loadHTML($request['message']);
        $file = str_replace(' ', '_', trim($Lead->name)).'_CRM'.$Lead->id.'.pdf';
        if (File::exists('uploads/'.$file)) {
            File::Delete('uploads/'.$file);
        }
        $pdf->save('uploads/'.$file);

        Audit::log(Auth::user()->id, trans('admin/mails/general.audit-log.category'), trans('admin/mails/general.audit-log.msg-store', ['name' => $attributes['mail_from']]));

        $mail = $this->mail->create($attributes);

        // Save the attachment data and file
        if ($request->file('attachment')) {
            $stamp = time();
            $file = $request->file('attachment');
            $destinationPath = public_path().'/sent_attachments/';
            $filename = $file->getClientOriginalName();

            $fields = [
                        'mail_id'   =>  $mail->id,
                        'file'  =>  $stamp.$filename,
                    ];

            $attachments = $this->attachment->create($fields);

            $request->file('attachment')->move($destinationPath, $stamp.$filename);
        } else {
            $fields = '';
        }

        //send email
        $mail = Mail::send('emails.email-send', [], function ($message) use ($attributes, $file, $Lead, $request, $fields) {
            $message->subject($attributes['subject']);
            $message->from($attributes['mail_from'], env('APP_NAME'));
            $message->to($attributes['mail_to'], '');
            $message->attach('uploads/'.$file);
            if ($request->file('attachment')) {
                $message->attach('sent_attachments/'.$fields['file']);
            }
        });

        Flash::success(trans('admin/mails/general.status.sent'));

        $update_attribute['status_id'] = '18';  // After sending offer letter mail, set the status_id = 18 , i.e Offer Letter Sent
        $Lead->update($update_attribute);

        return Redirect::back();
    }

    public function getUnsuccessAppModalMail($taskId)
    {
        $error = null;

        $Lead = $this->lead->find($taskId);

        if (! $Lead->user->email) {
            abort(403);
        }

        $modal_title = trans('admin/mails/dialog.send-mail.title', ['email' => $Lead->user->email]);
        $modal_route = route('admin.mail.send-unsuccessfulapplicationmodal',  $taskId);
        $to_email = $Lead->email;

        return view('modal_unsuccessfulapp', compact('error', 'modal_route', 'modal_title', 'to_email', 'Lead'));
    }

    public function postUnsuccessAppModalMail(Request $request, $leadId)
    {
        $this->validate($request, ['mail_from' => 'required',
                                            'mail_to'  => 'required',
                                            'subject'  => 'required',
                                            'message'  => 'required',
        ]);

        $attributes = [
                        'lead_id'   =>  $leadId,
                        'user_id'   =>  Auth::user()->id,
                        'mail_from' =>  $request['mail_from'],
                        'mail_to'   =>  $request['mail_to'],
                        'subject'   =>  $request['subject'],
                        'message'   =>  $request['message'],
                    ];

        $Lead = $this->lead->find($leadId);

        Audit::log(Auth::user()->id, trans('admin/mails/general.audit-log.category'), trans('admin/mails/general.audit-log.msg-store', ['name' => $attributes['mail_from']]));

        $mail = $this->mail->create($attributes);

        // Save the attachment data and file
        if ($request->file('attachment')) {
            $stamp = time();
            $file = $request->file('attachment');
            $destinationPath = public_path().'/sent_attachments/';
            $filename = $file->getClientOriginalName();

            $fields = [
                        'mail_id'   =>  $mail->id,
                        'file'  =>  $stamp.$filename,
                    ];

            $attachments = $this->attachment->create($fields);

            $request->file('attachment')->move($destinationPath, $stamp.$filename);
        } else {
            $fields = '';
        }

        //send email
        $mail = Mail::send('emails.email-send', [], function ($message) use ($attributes, $Lead, $request, $fields) {
            $message->subject($attributes['subject']);
            $message->from($attributes['mail_from'], 'Mero Network Pvt Ltd');
            $message->to($attributes['mail_to'], '');
            if ($request->file('attachment')) {
                $message->attach('sent_attachments/'.$fields['file']);
            }
        });

        Flash::success(trans('admin/mails/general.status.sent'));

        $update_attribute['status_id'] = '19';  // After sending offer letter mail, set the status_id = 19 , i.e Unsuccessful Application
        $Lead->update($update_attribute);

        return redirect('/admin/leads/'.$leadId);
    }

    public function getPendingModalMail($taskId)
    {
        $error = null;

        $Lead = $this->lead->find($taskId);

        if (! $Lead->user->email) {
            abort(403);
        }

        $modal_title = trans('admin/mails/dialog.send-mail.title', ['email' => $Lead->user->email]);
        $modal_route = route('admin.mail.send-pendingmodal', $taskId);
        $to_email = $Lead->email;

        return view('modal_pending', compact('error', 'modal_route', 'modal_title', 'to_email', 'Lead'));
    }

    public function postPendingModalMail(Request $request, $leadId)
    {
        $this->validate($request, ['mail_from' => 'required',
                                            'mail_to'  => 'required',
                                            'subject'  => 'required',
                                            'message'  => 'required',
        ]);

        $attributes = [
                        'lead_id'   =>  $leadId,
                        'user_id'   =>  Auth::user()->id,
                        'mail_from' =>  $request['mail_from'],
                        'mail_to'   =>  $request['mail_to'],
                        'subject'   =>  $request['subject'],
                        'message'   =>  $request['message'],
                    ];

        $Lead = $this->lead->find($leadId);

        Audit::log(Auth::user()->id, trans('admin/mails/general.audit-log.category'), trans('admin/mails/general.audit-log.msg-store', ['name' => $attributes['mail_from']]));

        $mail = $this->mail->create($attributes);

        // Save the attachment data and file
        if ($request->file('attachment')) {
            $stamp = time();
            $file = $request->file('attachment');
            $destinationPath = public_path().'/sent_attachments/';
            $filename = $file->getClientOriginalName();

            $fields = [
                        'mail_id'   =>  $mail->id,
                        'file'  =>  $stamp.$filename,
                    ];

            $attachments = $this->attachment->create($fields);

            $request->file('attachment')->move($destinationPath, $stamp.$filename);
        } else {
            $fields = '';
        }

        //send email
        $mail = Mail::send('emails.email-send', [], function ($message) use ($attributes, $Lead, $request, $fields) {
            $message->subject($attributes['subject']);
            $message->from($attributes['mail_from'], 'Mero Network Pvt Ltd');
            $message->to($attributes['mail_to'], '');
            if ($request->file('attachment')) {
                $message->attach('sent_attachments/'.$fields['file']);
            }
        });

        Flash::success(trans('admin/mails/general.status.sent'));

        $update_attribute['status_id'] = '17';  // After sending offer letter mail, set the status_id = 17 , i.e Pending
        $Lead->update($update_attribute);

        return Redirect::back();
    }

    // SEND A BULK MAIL PRODUCT WISE

    public function getBulkMailForm()
    {
        $page_title = 'Send the Bulk Email';
        $page_description = 'Send the Bulk Email Product and Status wise';

        $courses = \App\Models\Product::where('org_id', Auth::user()->org_id)->where('enabled', '1')->pluck('name', 'id');
        $lead_status = \App\Models\Leadstatus::where('enabled', '1')->pluck('name', 'id');

        return view('admin.email.bulkEmailForm', compact('page_title', 'page_description', 'courses', 'lead_status'));
    }



    public function postBulkMail(Request $request)
    {

        // Save the attachment data and file
        if ($request->file('attachment')) {
            $stamp = time();
            $file = $request->file('attachment');
            $destinationPath = public_path().'/sent_attachments/';
            $filename = $file->getClientOriginalName();

            $fields = ['file' => $stamp.$filename];

            $request->file('attachment')->move($destinationPath, $stamp.$filename);
             $data2['attachment'] =  $stamp.$filename;
        } else {
            $fields = '';
        }

        $mail = 0;

        $leads = \App\Models\Lead::where('id', '>', '0')->where('enabled', '1')->where('email_opt_out', '0')->where('product_id', $request['course_no'])->where('status_id', $request['status_no'])->get();

        if ($leads == null || $leads->count() == '0') {
            Flash::warning('Sorry ! No leads to send email.');

            return Redirect::back();
        }

        $leads = \App\Models\Lead::where('id', '>', '0')->where('enabled', '1')->where('email_opt_out', '0')->where('product_id', $request['course_no'])->where('status_id', $request['status_no'])->get();

        //send form iformation to table bulk_email_campaign

        $data2['title'] = $request['title'];
        $data2['subject'] = $request['subject'];
        $data2['message'] = $request['body'];
        $data2['product_id'] = $request['course_no'];
        $data2['status_id'] = $request['status_no'];
        $data2['created_at'] = \Carbon\Carbon::now();

        DB::table('bulk_email_campaign')->insert($data2);

        $validatedData = $request->validate([
                                    'title' => 'required|max:255',
                                    'subject' => 'required|max:255',
                                    'message'  => 'required',]);

        if($validatedData){
            $details=[
                "email"=> "rajunpl@gmail.com",
                "title"=>$request->title,
                "subject"=>$request->subject,
                "message"=>$request->message
            ];

            dispatch(new SendBulkEmail($details));
            //return redirect()->route('email.index');
             Flash::success('Bulk mail send successfully in the background...');
            return Redirect::back();
        }

        // if ($leads->count()) {
        //     Flash::success('Email sent Successfully!');
        // } else {
        //     Flash::warning('No leads found related to this campaign.');
        // }

        // return Redirect::back();
        //return redirect('admin/email/campaign/'.$email_campaign_id.'/edit');
    }




    // send a mail for all
    public function getBulkMailAllForm()
    {
        $page_title = 'Send the Bulk Email to All Leads';
        $page_description = 'Send the Bulk Email to all the leads';
        $valid_addresses = \App\Models\Lead::where('email', 'like', '%_@__%.__%')->count();
        $total_leads = \App\Models\Lead::count();

        return view('admin.email.bulkEmailAllForm', compact('page_title', 'page_description','valid_addresses','total_leads'));
    }


    //Send Bulk Mail to all the leads

    public function postBulkMailAll(Request $request)
    {

        // Save the attachment data and file
        if ($request->file('attachment')) {
            $stamp = time();
            $file = $request->file('attachment');
            $destinationPath = public_path().'/sent_attachments/';
            $filename = $file->getClientOriginalName();

            
            $request->file('attachment')->move($destinationPath, $stamp.$filename);
            $data2['attachment'] =  $stamp.$filename;

            } else {
                    $fields = '';
            }


        // dd($request->all());
        $data2['title'] = $request['title'];
        $data2['subject'] = $request['subject'];
        $data2['message'] = $request['message'];
        $data2['product_id'] = $request['course_no'];
        $data2['status_id'] = $request['status_no'];
        $data2['created_at'] = \Carbon\Carbon::now();
        $file = $request->file('attachment');

        
        DB::table('bulk_email_campaign')->insert($data2);
        
        $validatedData = $request->validate(['title' => 'required|max:255',
                                    'subject' => 'required|max:255',
                                    'message'  => 'required',]);

        if($validatedData){
            $details=[
                "email"=> "rajunpl@gmail.com",
                "title"=>$request->title,
                "subject"=>$request->subject,
                "message"=>$request->message,
                'attachments'=>$data2['attachment']
            ];
            //dd($request->all());


            dispatch(new SendBulkEmailToAll($details));
            //return redirect()->route('email.index');
             Flash::success('Bulk mail send successfully in the background...');
            return Redirect::back();
        }
    


        // Flash::success('Bulk mail send successfully in the background...');
        // return Redirect::back();
    }



    public function loadTemplate(Request $request)
    {
        $template = $request->template.'.php';

        $content = File::get(base_path('resources/views/admin/email/templates/'.$template));

        if ($content) {
            return ['success' => 1, 'data' => $content];
        } else {
            return ['success' => 0, 'data' => ''];
        }
    }

    public function mailLogIndex()
    {
        $mails = \App\Models\EmailCampainLogs::orderBy('id', 'desc')->get();
        //$mails = DB::table('bulk_email_campaign')->orderBy('id', DESC)->get();
        $page_title = 'Mass Mail Campaign Logs';
        $page_description = 'Bulk Email sent History';

        return view('admin.email.index', compact('mails', 'page_title', 'page_description'));
    }

    public function getLeadsTotal(Request $request)
    {
        $course_no = $request->course_no;
        $status_no = $request->status_no;

        $total_lead = \App\Models\Lead::where('id', '>', '0')->where('enabled', '1')->where('email_opt_out', '0')->where('product_id', $request['course_no'])->where('status_id', $request['status_no'])->count();

        if ($total_lead > 0) {
            return ['success' => 1, 'data' => $total_lead];
        } else {
            return ['success' => 0, 'data' => 0];
        }
    }

    public function downloadExcel(Request $request)
    {
        $course_no = $request->course_no;
        $status_no = $request->status_no;
        $data = \App\Models\Lead::select('name', 'mob_phone', 'city', 'email', 'email_opt_out')->where('id', '>', '0')->where('enabled', '1')->where('email_opt_out', '0')->where('product_id', $request['course_no'])->where('status_id', $request['status_no'])->get();

        if (count($data) > 0) {
            $name = 'lead_info'.date('Y-m-d').'csv';

            return \Excel::download(new \App\Exports\ExcelExport($data), $name);
        }

        return redirect()->back()->withErrors(['error'=>'No any Data']);
    }
}