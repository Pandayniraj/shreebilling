Dear <?php if($details['title'] != '') echo $details['title'].'. '; ?>
{{$details->name}}
{{$details['message']}}
<?php if(Request::get('message')) echo Request::get('message'); 
else echo $email_message; //echo $msg; ?>

