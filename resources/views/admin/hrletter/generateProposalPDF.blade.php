<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>{{ env('APP_COMPANY')}} | PROPOSAL</title>
    <style>

      h1 {
          border-top: 1px solid  #5D6975;
          border-bottom: 1px solid  #5D6975;
          color: #5D6975;
          font-size: 2.4em;
          line-height: 1.4em;
          font-weight: normal;
          text-align: center;
          margin: 0 0 20px 0;
        }
      #logo {
        text-align: center;
        margin-bottom: 10px;
      }

      #logo img {
        max-width: 300px;
      }
    </style>
  </head>
  <body>

    <header>
      <div id="logo">
          <img width="" src="{{env('APP_URL')}}/{{ '/org/'.$organization->logo }}">
      </div>

                <strong>{{ env('APP_COMPANY') }} </strong><br>
               
                HR Reference #: {{env('APP_CODE')}}{{date(y)}}-0{{ $hrletter->id}}<br/>
                Created at:  {{ date('D, dS M Y', strtotime($hrletter->created_at)) }}

                   
              {!! $hrletter->body !!}
  </body>
</html>