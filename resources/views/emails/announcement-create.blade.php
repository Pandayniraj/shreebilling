<h3>New Announcement has been created</h3>
<p>Following are the details.</p>
<div>

    <table style="border:0;padding:0;margin:0;width:100%">
        <tr>
            <td width="60%" style="vertical-align:top">
                <img style="max-width: 240px" height="" src="{{env('APP_URL')}}/{{ '/org/'.$organization->logo }}">
            </td>
            <td width="40%">
                <div style="text-align:right;">
                    <strong>MERONETWORK (P) Ltd.</strong><br />
                    Kumari Marg<br />
                    Lazimpat, Kathamandu<br />
                    Nepal<br />
                    Tel: +977 (1) 4426 702<br />
                    |E|: <a href="mailto:rajendra@meronetwork.com">rajendra@meronetwork.com</a><br />
                    |W|: <a href="https://www.meronetwork.com">www.meronetwork.com</a><br />
                </div>
            </td>
        </tr>
    </table>
    <div style="clear:both;"></div>
    <br />

    <div style="clear:both;"></div>

    <p>Title:{{$announcement->title}}</p>
    @if($announcement->description)
    <p>Description :{{$announcement->description}}</p>
    @endif
    <p>Start Date :{{$announcement->start_date}}</p>
    <p>End Date :{{$announcement->end_date}}</p>


</div>
