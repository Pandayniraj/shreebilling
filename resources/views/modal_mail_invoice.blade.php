<style>
.modal-mail {
    margin: 30px auto !important;
    width: 800px !important;
}
.modal-mail {
    margin: 10px;
    position: relative;
    width: auto;
}
h2 { font-size:23px; margin-top:0 !important; margin-left:10px;}

.form-horizontal .control-label{
    text-align: left !important;
}

</style>
<link href="{{ asset("/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css") }}" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{{ asset ("/js/tinymce/tinymce.min.js") }}"></script>
<script>
    tinymce.init({
		relative_urls: false,
		remove_script_host: false,
		selector: "textarea#compose-textarea",
		theme: "modern",
		skin: 'light',
		media_strict: false,
		media_filter_html: false,
		extended_valid_elements: "i[class],div[class|style],iframe[src|width|height|name|align], embed[width|height|name|flashvars|src|bgcolor|align|play|loop|quality|allowscriptaccess|type|pluginspage],script[type|src|async]",
		//height: 200,
		menubar:true,
		//statusbar: false,
		menu: {
			file: {title: 'File', items: 'newdocument'},
			edit: {title: 'Edit', items: 'undo redo | cut copy paste pastetext | selectall'},
			insert: {title: 'Insert', items: 'link media | template hr'},
			view: {title: 'View', items: 'visualaid'},
			format: {title: 'Format', items: 'bold italic underline strikethrough superscript subscript | formats | removeformat'},
			table: {title: 'Table', items: 'inserttable tableprops deletetable | cell row column'},
			tools: {title: 'Tools', items: 'spellchecker code'}
		},

		subfolder : "",
		plugins: [
			 "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
			 "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
			 "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
	   ],
	   image_advtab: true,
	   filemanager_title:"MeroCRM Filemanager",
		external_filemanager_path:"/js/tinymce/plugins/filemanager/",
		external_plugins: { "filemanager" : "/js/tinymce/plugins/filemanager/plugin.min.js"},
	  // content_css: "css/content.css",
	   toolbar: "forecolor | bold italic | blockquote alignleft aligncenter alignright alignjustify | bullist numlist | link image |" + " code"
	 });

</script>
<div class="modal-header bg-blue">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <span class="lead">Compose New Email</span>
</div>
<div class="modal-body">
  @if($error)
  	<div>{{{ $error }}}</div>
  @else
      {!! Form::open( ['url' => $modal_route, 'id' => 'mail-modal', 'class'=>'form-horizontal','enctype' => 'multipart/form-data'] ) !!}
      <div class="mail-body">
        
        <div class="box-body">
          <div class="form-group">
            <label class="control-label">From: </label>
            <div class="controls">
            <input placeholder="From:" class="form-control" name="mail_from" value="{!! env('APP_EMAIL') !!}" required>
            </div>
          </div>
          <div class="form-group">
            <label>To: </label>
            <input placeholder="To:" class="form-control" name="mail_to" value="{!! $to_email !!}" required>
          </div>
          <div class="form-group">
          	<label>Subject: </label>
            <input placeholder="Subject:" name="subject" class="form-control" value="{!! env('APP_COMPANY') !!} Proposal for you" required>
          </div>
          <div class="form-group">
            <textarea rows="3" name="message" class="form-control" id="compose-textarea" placeholder="Type your message."></textarea>
          </div>
          <div class="form-group">
            <div class="btn btn-default btn-file"> <i class="fa fa-paperclip"></i> Attachment
              <input type="file" name="attachment">
            </div>
            <p class="help-block">Max. 32MB</p>
          </div>
          <div class="form-group">
          	<input type="checkbox" name="use_proposal" id="use_proposal">
          	<label>Use Proposal Template &nbsp;</label>
            <br/>
            <input type="checkbox" name="use_quote" id="use_quote">
            <label>Use Quote Template &nbsp;</label>
            <br/>
            <input type="checkbox" name="use_contract" id="use_contract">
            <label>Use Contract Template &nbsp;</label>
          </div>
        </div>
      </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('general.button.cancel') }}</button>
          <button type="submit" class="btn btn-primary">{{ trans('general.button.send') }}</button>
        </div>
      {!! Form::close() !!}
      <script>
	  	$('#use_proposal').click(function(){
  			if($('#use_proposal').is(':checked'))
  			{
  				tinymce.get('compose-textarea').setContent($('#proposal_template').html());
  				$('#compose-textarea_ifr').css('height', '400px');
  			}
  			else
  			{
  				tinymce.get('compose-textarea').setContent('');
  				$('#compose-textarea_ifr').css('height', '150px');
  			}
  		});

      $('#use_quote').click(function()
      {
        if($('#use_quote').is(':checked'))
        {
          tinymce.get('compose-textarea').setContent($('#quote_template').html());
          $('#compose-textarea_ifr').css('height', '400px');
        }
        else
        {
          tinymce.get('compose-textarea').setContent('');
          $('#compose-textarea_ifr').css('height', '150px');
        }
      });

      $('#use_contract').click(function()
      {
        if($('#use_contract').is(':checked'))
        {
          tinymce.get('compose-textarea').setContent($('#contract_template').html());
          $('#compose-textarea_ifr').css('height', '400px');
        }
        else
        {
          tinymce.get('compose-textarea').setContent('');
          $('#compose-textarea_ifr').css('height', '150px');
        }
      });
	  </script>
  @endif
</div>

<div id="proposal_template" style="display:none;">
  <div>

      <table style="border:0;padding:0;margin:0;width:100%">
      	<tr>
          	<td width="60%" style="vertical-align:top">
              	<img src="/images/logo.png" alt="" style="width:150px;">
              </td>
              <td width="40%">
              	<div style="text-align:right;">
                      <strong>MERONETWORK (P) Ltd.</strong><br/>
                      Kumari Marg<br/>
                      Lazimpat, Kathamandu<br/>
                      Nepal<br/>
                      Tel: +977 (1) 4426 702<br/>
                      |E|: <a href="mailto:rajendra@meronetwork.com">rajendra@meronetwork.com</a><br/>
                      |W|: <a href="https://www.meronetwork.com">www.meronetwork.com</a><br/>
                  </div>
              </td>
          </tr>
      </table>
      <div style="clear:both;"></div>
      <br/>

      <table style="border:0;padding:0;margin:0;width:100%">
      	<tr>
          	<td>
                  <p>
                      Dear {!! $Lead->title !!}. {!! $Lead->name !!},<br/>
                      {!! $Lead->department !!}</p>
                      {!! $Lead->address_line_1 ? $Lead->address_line_1.'<br/>' : '' !!}
                      {!! $Lead->city ? $Lead->city.'<br/>' : '' !!}
                      Nepal<br/><br/>
                      <?php echo date('dS M Y'); ?>
                       | Proposal. No. # MN{!! $Lead->id !!}/{!! date('y') !!}
                  </p>
               </td>
          </tr>
          <tr>
               <td><strong>Re: Proposal Document</strong></td>
          </tr>
      </table>
      
      <div style="clear:both;"></div>
      <p>Dear {!! $Lead->title !!}. {!! $Lead->name !!},</p>
      <p>
      We at Mero Network are committed to provide you the best solution in digital marketing
and also best support in the industry. Mero Network is a web design and Digital Marketing Company. We have expertise in web design, web development, ecommerce, Search Engine Optimization (SEO), Search engine marketing (SEM), Social Branding and online marketing solutions. Our online marketing strategies ensure the maximum exposure of your business.<br/><br/>

    <h2>Why choose us?</h2>
  
1. 12+ years of experienced professionals <br/>
2. Full Service Company, we have developers, designers, system engineers to analyses service from different angle <br/>
3. Already giving services to top brands <br/>
4. Following the best and the latest industrial practices <br/>
5. Develop bespoke software or customize third parties depending on cost<br/>

    <h2>What you get?</h2>
    1. With an insights-driven, strategic approach, we can act as your Online Marketing Officer. <br/>
    2. Our wealth of knowledge on best practices means you get reliable results. <br/>
    3. We strive for client relationships built on trust, respect and mutual success. <br/>
    4. Our Monthly custom report gives you the comprehensive details about the performance of your Marketing Campaigns.<br/>

    <h2> Executive Summary </h2>

    This Digital Marketing plan offers an effective, realistic and comprehensive improvement to the current online presence of your company along with strategies to promote your product and services across various digital platforms resulting in higher conversion rate. Our objective is to increase your search engine presence and rankings, attract more visitors and make more sales through our website optimization & PPC advertising which in turn increase the numbers of people visiting your website. This ultimately increases new customers for your company. We have also noted your requirements.<br/></br/>


    <h2> Our Products and Services </h2>
    <ul>
    <li>IT Services Consulting </li>
    <li>Search Engine Optimization</li>
    <li>Google Adwords Management </li>
    <li>SMM & Facebook Adverts Management</li>
    <li>Video Production and Marketing </li>
    <li>Email and SMS Marketing </li>
    <li>MeroCRM </li>
    <li>Web Design and Mobile Apps</li>
    </ul>
    <br/><br/>

    <h2> Next Step </h2>

    <ul>
    <li>We will send you quotation</li>
    <li>Accept our Quotes by signing it </li>
    <li>The quoted product or service will be provided to you as specified. </li>
    </ul>

    <br/><br/>

      We look forward to your email or sms for the quotes.<br/><br/>
      Yours Sincerely,<br/><br/>
      <img src="/images/logo.png" width="200px"><br/><br/>
      Saugat Basnet<br/>
      Marketing Executive<br>
      Sent by MeroCRM
  	</p>
  </div>
</div>

<!-- quote template starts -->
<div id="quote_template" style="display:none;">

<!-- CSS for quote template -->
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <style>
      @import url(http://fonts.googleapis.com/css?family=Bree+Serif);
      body, h1, h2, h3, h4, h5, h6{
      font-family: 'Bree Serif', serif;
      }
    </style>
<!-- CSS Ends for quote template -->    
  
   <div class="container">
      <div class="row">
        <div class="col-xs-6">
          <h1>
            <img src="https://www.meronetwork.com/images/uploaded/logo/logo-mn.png">
          </h1>
        </div>
        <div class="col-xs-6 text-right">
          <h1>Quotation</h1>
          <h1><small>Quote # MN{!! $Lead->id !!}/{!! date('y') !!}</small></h1>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-5">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4>From: <a href="#">Your Name</a></h4>
            </div>
            <div class="panel-body">
              <p>
                Address <br>
                details <br>
                more <br>
              </p>
            </div>
          </div>
        </div>
        <div class="col-xs-5 col-xs-offset-2 text-right">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4>To : <a href="#">{!! $Lead->title !!}. {!! $Lead->name !!}</a></h4>
            </div>
            <div class="panel-body">
              <p>
              {!! $Lead->department !!}
                {!! $Lead->address_line_1 ? $Lead->address_line_1.'<br/>' : '' !!} <br>
                {!! $Lead->city ? $Lead->city.'<br/>' : '' !!}<br/>
                details <br>
                more <br>
              </p>
            </div>
          </div>
        </div>
      </div>
      <!-- / end client details section -->
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>
              <h4>Service</h4>
            </th>
            <th>
              <h4>Description</h4>
            </th>
            <th>
              <h4>Hrs/Qty</h4>
            </th>
            <th>
              <h4>Rate/Price</h4>
            </th>
            <th>
              <h4>Sub Total</h4>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Template Design</td>
            <td>Details of project here</td>
            <td class="text-right">10</td>
            <td class="text-right">75.00</td>
            <td class="text-right">$750.00</td>
          </tr>
          <tr>
            <td>Development</td>
            <td><a href="#">WordPress Blogging theme</a></td>
            <td class="text-right">5</td>
            <td class="text-right">50.00</td>
            <td class="text-right">$250.00</td>
          </tr>
        </tbody>
      </table>
      <div class="row text-right">
        <div class="col-xs-2 col-xs-offset-8">
          <p>
            <strong>
            Sub Total : <br>
            TAX : <br>
            Total : <br>
            </strong>
          </p>
        </div>
        <div class="col-xs-2">
          <strong>
          $1200.00 <br>
          N/A <br>
          $1200.00 <br>
          </strong>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-5">
          <div class="panel panel-info">
            <div class="panel-heading">
              <h4>Bank details</h4>
            </div>
            <div class="panel-body">
              <p>Company Name: Mero Network Pvt. Ltd</p>
              <p>Bank Name: Nepal Investment Bank Ltd, Lazimpat</p>
              <p>SWIFT : --------</p>
              <p>Account Number : 036010 4025 8173</p>
              <p>IBAN : --------</p>
            </div>
          </div>
        </div>
        <div class="col-xs-7">
          <div class="span7">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h4>Contact Details</h4>
              </div>
              <div class="panel-body">
                <p>
                  Email : you@example.com <br><br>
                  Mobile : -------- <br> <br>
                </p>
                <h4>Payment should be made by Bank Transfer</h4>
              </div>


            </div>

          </div>

        </div>
      </div>
       <div class="panel-body">
                <p>
                  Payment Terms : 15 Days <br>
                  Quote Valid Until : -------- <br> <br> 
                  Customer Acceptance (sign below):<br> <br> 
                  x________________________________________
                  
                </p>
                <p>

                  Notes: Cost for the additional components, when used, will be calculated at the end of the billing period and included in the next invoice.<br><br>
                  Terms & Conditions:<br>
                  1. Above quoted prices are exclusive of the applicable VAT.<br>
                  2. Minimum term for this service is 1 year.<br>
                  3. Invoice will be raised in advance.<br>
                  4. Payment should be made in advance.<br>

                </p>
                
              </div>
    </div>

</div>
<!-- quote template ends -->


<div id="contract_template" style="display:none;">
  <div>

      <table style="border:0;padding:0;margin:0;width:100%">
        <tr>
            <td width="50%" style="vertical-align:top">
                <img src="/images/logo.png" alt="" style="width:150px;">
              </td>
              <td width="50%">
                <div style="text-align:right;">
                       <strong>MERONETWORK (P) Ltd.</strong><br/>
                       <strong>CONTRACT</strong>
                      Kumari Marg<br/>
                      Lazimpat, Kathamandu<br/>
                      Nepal<br/>
                      Tel: +977 (1) 4426 702<br/>
                      |E|: <a href="mailto:rajendra@meronetwork.com">rajendra@meronetwork.com</a><br/>
                      |W|: <a href="https://www.meronetwork.com">www.meronetwork.com</a><br/>
                  </div>
              </td>
          </tr>
      </table>


      <h2>Search Engine Optimization (SEO) Contract: client Name </h2>
      Contract. No. # MN{!! $Lead->id !!}/{!! date('y') !!}<br/><br/>

      This contract is entered into between Mero Network Pvt. Ltd. and {client}
       (hereinafter referred to as “Client”) on the 1st day of May, 2017. <br/><br/>

     1. Mero Network Pvt. Ltd. will provide Client with Search Engine Optimization Services
(hereinafter referred to as “SEO”) as described in this contract. Mero Network Pvt. Ltd.
will use specific keywords and/or phrases to improve the search engine ranking of,
and/or position the contents of the Client's website .  <br/><br/>

2. The total fee for the SEO services to be provided is Rs 000,000 / mo . All fees must be
paid in full prior to the start of campaign.<br/><br/>

Mero Network Pvt. Ltd's SEO services are intended to serve two main purposes: 1) to
provide the Client with increased exposure in search engines, and 2) to drive targeted
online traffic to the site. <br/><br/>

<h3>Mero Network Pvt. Ltd 's SEO Services will include (but are not limited to ):</h3>

<ul>
<li>Researching keywords and phrases to select appropriate, relevant search terms (up
to 15 phrases).</li>
<li> Obtaining “back links” from other related websites and directories in order to
generate link popularity and traffic.</li>
<li> Editing and/or optimization of text for various html tags, meta data, page titles, and
page text as necessary.</li>
<li> Analysis and recommendations on optimal website structure, navigation, code, etc.
for best SEO purposes.</li>
<li> Recommend, as required, additional web pages or content for the purpose of
“catching” keyword/phrase searches.</li>

<li> Create traffic and ranking reports for clientsite.tld and any associated pages showing
rankings in the major search engines.</li>
</ul>
<br/><br/>

3. For the purposes of receiving professional SEO services, Client agrees to
provide the following:<br/><br/>

Administrative/backend access to the website for analysis of content and
structure.
• Permission to make changes for the purpose of optimization, and to
communicate directly with any third parties, e.g., your web designer, if necessary.
• Unlimited access to existing website traffic statistics for analysis and tracking
purposes.
• A [clients] email address for the purposes of requesting links
(something like [client email] )
• Authorization to use client pictures, logos, trademarks, web site images,
pamphlets, content, etc., for any use as deemed necessary by Mero Network Pvt. Ltd.
for search engine optimization purposes.
• If Client’s site is lacking in textual content, Client will provide additional text
content in electronic format for the purpose of creating additional or richer web pages.
Mero Network Pvt. Ltd. can create site content at additional cost to the Client. If Client is
interested in purchasing content from Mero Network Pvt. Ltd., please contact Mero
Network Pvt. Ltd. for a cost estimate.<br/><br/>


4. Client must acknowledge the following with respect to SEO services:
<br/><br/>
<ul>
<li>All fees are non-refundable.</li>
<li>All fees, services, documents, recommendations, and reports are confidential.</li>
<li>Mero Network Pvt. Ltd. has no control over the policies of search engines with
respect to the type of sites and/or content that they accept now or in the future. The
Client’s website may be excluded from any directory or search engine at any time at the
sole discretion of the search engine or directory.</li>

<li>Due to the competitiveness of some keywords/phrases, ongoing changes in search
engine ranking algorithms, and other competitive factors, ( Company name ) does not
guarantee #1 positions or consistent top 10 positions for any particular keyword, phrase,
or search term. However, if Mero Network Pvt. Ltd. fails to increase traffic to the site by
20% after four weeks of services, Mero Network Pvt. Ltd. will continue SEO services at
no added cost to the Client until such a percentage is met. </li>

<li>Google has been known to hinder the rankings of new websites (or pages) until they
have proven their viability to exist for more than “x” amount of time. This is referred to as
the “Google Sandbox.” Mero Network Pvt. Ltd. assumes no liability for
ranking/traffic/indexing issues related to Google Sandbox penalties.</li>

<li>Occasionally, search engines will drop listings for no apparent or predictable reason.
Often, the listing will reappear without any additional SEO. Should a listing be dropped
during the SEO campaign and does not reappear within 30 days of campaign
completion, Mero Network Pvt. Ltd. will re-optimize the website/page based on the
current policies of the search engine in question. </li>

<li>Some search directories offer expedited listing services for a fee. If the Client wishes
to engage in said expedited listing services (e.g., paid directories), the Client is
responsible for all paid for inclusion or expedited service fees. Mero Network Pvt. Ltd.
can offer a list of expedited listing services upon request.</li>

<li>Linking to “bad neighborhoods” or getting links from “link farms” can seriously
damage all SEO efforts. Mero Network Pvt. Ltd. does not assume liability for the Client’s
choice to link to or obtain a link from any particular website without prior consultation.
</li>
</ul>

<br/><br/>
5. Mero Network Pvt. Ltd. is not responsible for changes made to the website by other
parties that adversely affect the search engine rankings of the Client’s website.

<br/><br/>
    6. Additional Services not listed herein (such as managing pay-per click campaigns,
copywriting, link baiting, etc.)

<br/><br/>
7. The Client guarantees any elements of text, graphics, photos, designs, trademarks,
or other artwork provided to Mero Network Pvt. Ltd. for inclusion on the website above
are owned by the Client, or that the Client has received permission from the rightful
owner(s) to use each of the elements, and will hold harmless, protect, and defend Mero
Network Pvt. Ltd. and its subcontractors from any liability or suit arising from the use of
such elements.
<br/><br/>
8. Mero Network Pvt. Ltd. is not responsible for the Client overwriting SEO work to the
Client’s site. (e.g., Client/webmaster uploading over work already provided/optimized).
The Client will be charged an additional fee for re-constructing content. Notwithstanding
any other provision of this Agreement, Mero Network Pvt. Ltd. obligation to provide free
SEO services shall cease in the event the Client’s conduct overwrites the SEO services
provided.
<br/><br/>
<br/><br/>
Client Name: ________________________________________<br/>
Client Signature ___________________________________<br/>
Date: _______________<br/><br/><br/>


Company Name: Mero Network Pvt. Ltd<br/>
Signature _________________________________________
<br/>
Date: <?php echo date('dS M Y'); ?>




  </div>
</div>
