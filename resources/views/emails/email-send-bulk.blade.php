Dear <?php if($lead->title != '') echo $lead->title.'. '; ?>{{$lead->name}}
<?php if(Request::get('message')) echo Request::get('message'); else echo $email_message; //echo $msg; ?>

<b>Send From {{env('APP_COMPANY')}}</b>

