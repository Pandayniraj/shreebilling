<?php

namespace App\Http\Controllers;

use App\Models\Audit as Audit;
use App\Models\EmailCampaign;
use App\Models\EmailSendError;
use App\Models\Lead;
use Datatables;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

/**
FOR ONLINE ENQUIRY

 **/
class EmailCampaignController extends Controller
{
    /**
     * @var
     */
    private $Application;

    public function __construct(EmailCampaign $ecampaign)
    {
        parent::__construct();
        $this->ecampaign = $ecampaign;
    }

    public function index()
    {
        Audit::log(Auth::user()->id, 'Email Campaign', 'Accessed list of Mail Campaign');

        $email_campaigns = EmailCampaign::orderBy('id', 'desc')->get();

        // To check if todays quota for sending email is exceeded or not
        $email_sent_today = EmailCampaign::select(DB::raw('SUM(today_email_sent_count) as today_success_email_count'))->where('last_sent_date', date('Y-m-d'))->first();

        $page_title = 'Email Campaign | ';
        $page_description = 'List of  Email Campaign';

        return view('admin.emailCampaign.index', compact('email_campaigns', 'email_sent_today', 'page_title', 'page_description'));
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        Session::put('campID', $id);

        $email_campaign = EmailCampaign::find($id);

        $qry = $email_campaign->db_query;
        // To change the total_email in email_campaign table if the number of leads is increased in leads
        $qry = preg_replace('/xyz/', '0', $qry, 1);
        $leads = DB::select($qry);
        $email_campaign->total_email = count($leads);
        $email_campaign->save();

        // To check if todays quota for sending email is exceeded or not
        $email_sent_today = EmailCampaign::select(DB::raw('SUM(today_email_sent_count) as today_success_email_count'))->where('last_sent_date', date('Y-m-d'))->first();

        Audit::log(Auth::user()->id, trans('admin/email-campaign/general.audit-log.category'), trans('admin/email-campaign/general.audit-log.msg-edit', ['name' => $email_campaign->title]));

        $page_title = trans('admin/email-campaign/general.page.edit.title');
        $page_description = trans('admin/email-campaign/general.page.edit.description', ['name' => $email_campaign->title]);

        if (! $email_campaign->isEditable() && ! $email_campaign->canChangePermissions()) {
            abort(403);
        }

        return view('admin.emailCampaign.edit', compact('email_campaign', 'page_title', 'page_description', 'email_sent_today'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'message'      => 'required',
        ]);

        $attributes = $request->all();

        $ecampaign = $this->ecampaign->find($id);
        if ($ecampaign->isEditable()) {
            $ecampaign->update($attributes);
        }

        Flash::success(trans('admin/email-campaign/general.status.updated'));

        return Redirect::back();
    }

    public function send_email($campId)
    {
        // To check if todays quota for sending email is exceeded or not
        $email_sent_today = EmailCampaign::select(DB::raw('SUM(today_email_sent_count) as today_success_email_count'))->where('last_sent_date', date('Y-m-d'))->first();

        if ($email_sent_today->today_success_email_count >= env('APP_EMAIL_LIMIT')) {
            Flash::warning('Email sending quota for today has been exceeded.');

            return Redirect::back();
        }

        $ecampaign = $this->ecampaign->find($campId);
        $email_message = $ecampaign->message;
        $total_email_sent = $ecampaign->total_email_sent;
        $total_email_error_count = $ecampaign->total_email_error_count;
        $last_lead_id = $ecampaign->last_lead_id;
        $campaign_type = $ecampaign->campaign_type;

        $qry = $ecampaign->db_query;
        $qry = preg_replace('/xyz/', $ecampaign->last_lead_id, $qry, 1);
        $qry = $qry.' limit '.(env('APP_EMAIL_LIMIT') - $email_sent_today->today_success_email_count);

        $leads = DB::select($qry);

        if ($ecampaign->last_sent_date == date('Y-m-d')) {
            $today_email_sent_count = $ecampaign->today_email_sent_count;
        } else {
            $today_email_sent_count = 0;
        }

        foreach ($leads as $lead) {
            $mail_to = trim($lead->email, ' ');
            $mail_to = trim($mail_to, urlencode('%A0'));

            if ($mail_to != '' && strpos($mail_to, '@') !== false) {
                try {
                    if ($campaign_type == 'lead') {
                        $mail = Mail::send('emails.email-send-bulk', ['lead' => $lead, 'email_message'=> $email_message], function ($message) use ($lead, $mail_to, $ecampaign, $email_message) {
                            $message->subject($ecampaign->subject);
                            $message->from(env('APP_EMAIL'), env('APP_COMPANY'));
                            $message->to($mail_to, '');
                            if ($ecampaign->attachement != '') {
                                $message->attach($ecampaign->attachement);
                            }
                        });

                        //if($mail)
                        $today_email_sent_count++;
                    } elseif ($campaign_type == 'contact') {
                        $contact = $lead;
                        $mail = Mail::send('emails.send-bulk-email-contact', ['contact' => $contact], function ($message) use ($mail_to, $contact, $ecampaign, $email_message) {
                            $message->subject($ecampaign->subject);
                            $message->from(env('APP_EMAIL'), env('APP_COMPANY'));
                            $message->to($mail_to, $contact->full_name);
                            if ($ecampaign->attachement != '') {
                                $message->attach($ecampaign->attachement);
                            }
                        });

                        //if($mail)
                        $today_email_sent_count++;
                    } else {
                        $mail = Mail::send('emails.email-send-bulk', ['lead' => $lead, 'email_message'=> $email_message], function ($message) use ($lead, $mail_to, $ecampaign, $email_message) {
                            $message->subject($ecampaign->subject);
                            $message->from(env('APP_EMAIL'), env('APP_COMPANY'));
                            $message->to($mail_to, '');
                            if ($ecampaign->attachement != '') {
                                $message->attach($ecampaign->attachement);
                            }
                        });

                        //if($mail)
                        $today_email_sent_count++;
                    }
                } catch (\Exception $e) {
                    //dd($e);
                    $emailSendError = new EmailSendError();
                    $emailSendError->lead_id = $lead->id;
                    $emailSendError->email_campaign_id = $campId;
                    $emailSendError->email = $mail_to;
                    $emailSendError->save();

                    $total_email_error_count++;
                }
            } else {
                $emailSendError = new EmailSendError();
                $emailSendError->lead_id = $lead->id;
                $emailSendError->email_campaign_id = $campId;
                $emailSendError->email = $mail_to;
                $emailSendError->save();

                $total_email_error_count++;
            }

            $last_lead_id = $lead->id;
            $total_email_sent++;
        }

        if (count($leads) > 0) {
            if (($total_email_error_count - $ecampaign->total_email_error_count) == 0) {
                Flash::success('Email sent Successfully!');
            } else {
                Flash::warning('Email sent Successfully except some of leads with invalid email address.');
            }

            $emailCampaign_new = EmailCampaign::where('id', $campId)->first();
            $emailCampaign_new->total_email_sent = $total_email_sent;
            $emailCampaign_new->today_email_sent_count = $today_email_sent_count;
            $emailCampaign_new->total_email_error_count = $total_email_error_count;
            $emailCampaign_new->last_lead_id = $last_lead_id;
            $emailCampaign_new->last_sent_date = date('Y-m-d');
            $emailCampaign_new->save();
        } else {
            Flash::warning('No any leads remain to send email in this Campaign.');
        }

        return redirect('admin/email/campaign/'.$campId.'/edit');
    }

    public function email_error_list($campID)
    {
        $lead_list = EmailSendError::where('email_campaign_id', $campID)->orderBy('id', 'desc')->get();

        // To check if todays quota for sending email is exceeded or not
        $email_sent_today = EmailCampaign::select(DB::raw('SUM(today_email_sent_count) as today_success_email_count'))->where('last_sent_date', date('Y-m-d'))->first();

        $page_title = 'Email Campaign | ';
        $page_description = 'List of Email Campaign leads with email error';

        return view('admin.emailCampaign.email_error_list', compact('campID', 'lead_list', 'page_title', 'page_description', 'email_sent_today'));
    }

    public function resend_error_email(Request $request)
    {
        // To check if todays quota for sending email is exceeded or not
        $email_sent_today = EmailCampaign::select(DB::raw('SUM(today_email_sent_count) as today_success_email_count'))->where('last_sent_date', date('Y-m-d'))->first();

        if ($email_sent_today->today_success_email_count >= env('APP_EMAIL_LIMIT')) {
            Flash::warning('Email sending quota for today has been exceeded.');

            return Redirect::back();
        }

        $chkclients = $request->input('chkClient');

        $ecampaign = $this->ecampaign->find(Session::get('campID'));
        $email_message = $ecampaign->message;
        $total_email_sent = $ecampaign->total_email_sent;
        $total_email_error_count = $ecampaign->total_email_error_count;
        $last_lead_id = $ecampaign->last_lead_id;
        $campaign_type = $ecampaign->campaign_type;

        if ($ecampaign->last_sent_date == date('Y-m-d')) {
            $today_email_sent_count = $ecampaign->today_email_sent_count;
        } else {
            $today_email_sent_count = 0;
        }

        if (isset($chkclients)) {
            foreach ($chkclients as $list_id) {
                $err_list = EmailSendError::find($list_id);

                $mail_to = trim($err_list->email, ' ');
                $mail_to = trim($mail_to, urlencode('%A0'));

                if ($mail_to != '' && strpos($mail_to, '@') !== false) {
                    try {
                        if ($campaign_type == 'lead') {
                            $lead = $err_list->lead;
                            $mail = Mail::send('emails.email-send-bulk', ['lead' => $lead, 'email_message'=> $email_message], function ($message) use ($lead, $mail_to, $ecampaign, $email_message) {
                                $message->subject($ecampaign->subject);
                                $message->from(env('APP_EMAIL'), env('APP_COMPANY'));
                                $message->to($mail_to, '');
                                if ($ecampaign->attachement != '') {
                                    $message->attach($ecampaign->attachement);
                                }
                            });

                            //if($mail)
                            $today_email_sent_count++;
                        } elseif ($campaign_type == 'contact') {
                            $contact = $err_list->contact;
                            $mail = Mail::send('emails.send-bulk-email-contact', ['contact' => $contact], function ($message) use ($mail_to, $contact, $ecampaign, $email_message) {
                                $message->subject($ecampaign->subject);
                                $message->from(env('APP_EMAIL'), env('APP_COMPANY'));
                                $message->to($mail_to, $contact->full_name);
                                if ($ecampaign->attachement != '') {
                                    $message->attach($ecampaign->attachement);
                                }
                            });

                            //if($mail)
                            $today_email_sent_count++;
                        } else {
                            $mail = Mail::send('emails.email-send-bulk', ['lead' => $lead, 'email_message'=> $email_message], function ($message) use ($lead, $mail_to, $ecampaign, $email_message) {
                                $message->subject($ecampaign->subject);
                                $message->from(env('APP_EMAIL'), env('APP_COMPANY'));
                                $message->to($mail_to, '');
                                if ($ecampaign->attachement != '') {
                                    $message->attach($ecampaign->attachement);
                                }
                            });

                            //if($mail)
                            $today_email_sent_count++;
                        }

                        $total_email_sent++;
                        $total_email_error_count--;

                        EmailSendError::find($list_id)->delete();  // Delete this from error list if it is success to resend email
                    } catch (\Exception $e) {
                        //dd($e);
                    }
                }
            }

            // Update Emailcampaign table once the resend email is success.
            $emailCampaign_new = EmailCampaign::where('id', Session::get('campID'))->first();
            $emailCampaign_new->total_email_sent = $total_email_sent;
            $emailCampaign_new->today_email_sent_count = $today_email_sent_count;
            $emailCampaign_new->total_email_error_count = $total_email_error_count;
            $emailCampaign_new->last_sent_date = date('Y-m-d');
            $emailCampaign_new->save();

            Flash::success('Email has been sent to the leads or contacts with valid email address.');
        } else {
            Flash::warning('Please check/select atleast one from the list.');
        }

        return redirect('/admin/email/campaign/'.\Session::get('campID').'/email_error_list');
    }

    public function getModalDelete($id)
    {
        $error = null;

        $lead = Lead::find($id);

        $modal_title = 'Delete from error list';

        $modal_route = route('admin.email-campaign.delete', ['id' => $lead->id]);

        $modal_body = trans('admin/leads/dialog.delete-confirm.body', ['id' => $lead->id, 'name' => $lead->name]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function destroy($id)
    {
        $lead = EmailSendError::find($id);

        EmailCampaign::where('id', $lead->email_campaign_id)->decrement('total_email_error_count');
        //EmailCampaign::where('id', $lead->email_campaign_id)->decrement('total_email');
        $lead->delete();

        return redirect('admin/email/campaign/'.\Session::get('campID').'/email_error_list');
    }
}
