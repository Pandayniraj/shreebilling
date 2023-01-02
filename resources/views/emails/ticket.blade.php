<table style="width:100%; text-align:center; padding: 30px; box-shadow: 0 1.2rem 1.8rem 0 rgba(0,0,0,0.24),0 1.7rem 5rem 0 rgba(0,0,0,0.19); -webkit-box-shadow: 0 1.2rem 1.8rem 0 rgba(0,0,0,0.24),0 1.7rem 5rem 0 rgba(0,0,0,0.19); font-family:Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif;">
    <tr>
        <td colspan="4">
            <a href="#"><img src="{{ env('APP_LOGO1') }}" alt="" class="img-responsive" style="width:200px; margin:0 auto;"></a>
            <h1 style="font-size:30px; padding:15px; margin:0;font-weight: bold; color: #00aef0;">Ticket Form</h1>
        </td>
    </tr> 
    <tr style="background-color: #f9f9f9; border:1px solid #ccc;">
        <td colspan="2" style="text-align:left;"><strong>ID:</strong>TKT-{{ $case->id }}</td>
        <td style="text-align:right;" colspan="2"><strong>Submission Date:</strong> {{ date('D, M, d, Y') }}</td>
    </tr>
    <tr>
        <td colspan="4">
            <h3 style="color: #5cb85c;font-weight: bold;font-size: 24px;">New Ticket enquiry has been submitted successfully. </h3>
            <p style="font-size: 16px;line-height: 25px;">Please quote this enquiry number for future reference. </p>
            <h4 style="color: #1d5a9a; font-size: 18px; font-weight: bold;">Registered No is : TKT-{{ $case->id }} </h4>
        </td>
    </tr>
    <tr style="background-color: #f9f9f9; border:1px solid #ccc;">
        <th width="50%" style="text-align:right; padding:10px;" colspan="2">Priority</th>
        <td width="50%" colspan="2" style="text-align:left;">{{ $case->priority }}</td>
    </tr>    
    <tr style="background-color: #f9f9f9; border:1px solid #ccc;">
        <th width="50%" style="text-align:right; padding:10px;" colspan="2">Type:</th>
        <td width="50%" colspan="2" style="text-align:left;">{{ $case->type }}</td>
    </tr>
    <tr>
        <th width="50%" style="text-align:right; padding:10px;" colspan="2">Client:</th>
        <td width="50%" colspan="2" style="text-align:left;">{{ $case->client->name }}</td>
    </tr>
    <tr>
        <th width="50%" style="text-align:right; padding:10px;" colspan="2">Subject:</th>
        <td width="50%" colspan="2" style="text-align:left;">{{ $case->subject }}</td>
    </tr>
    <tr>
        <th width="50%" style="text-align:right; padding:10px;" colspan="2">Description:</th>
        <td width="50%" colspan="2" style="text-align:left;">{{ $case->description }}</td>
    </tr>
    @if($case->attachment != '')
    <tr>
        <th width="50%" style="text-align:right; padding:10px;" colspan="2">Attachement:</th>
        <td width="50%" colspan="2" style="text-align:left;">
            <a href="{{url('/'). '/case_attachments/'.$case->attachment}}" target="_blank">{{url('/'). '/case_attachments/'.$case->attachment}}</a>
        </td>
    </tr>
    @endif
</table>

<hr>
<p style="text-align: center;">Sent from MEROCRM</p>')}}