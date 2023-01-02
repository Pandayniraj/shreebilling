  <footer class="footer-a" style="background: #203162">
      <div class="wrapper-padding">
          <div class="section">
              <div class="footer-lbl" style="fon">Get In Touch</div>
              <div class="footer-adress">Address: {{$organization->address}}</div>
              <div class="footer-phones">Telephones: {{$organization->phone}}</div>
              <div class="footer-email">E-mail: {{$organization->email}}</div>
              
          </div>
          <div class="section">
              <div class="footer-lbl" >Featured deals</div>
              <div class="footer-tours">
                  @foreach($tours_all as $ta)
                  <!-- // -->
                  <div class="footer-tour">
                      <div class="footer-tour-l"><a href="/tours/{{$ta->id}}/{{$ta->tour_slug}}"><img alt="" src="/tours-img/{{$ta->thumbnail_image}}" /></a></div>
                      <div class="footer-tour-r">
                          <div class="footer-tour-a">{{$ta->tour_title}}</div>
                          <div class="footer-tour-b" style="color: #ffffff">location: {{$ta->location->location}}</div>
                         
                      </div>
                      <div class="clear"></div>
                  </div>
                  <!-- \\ -->
                  @endforeach


              </div>
          </div>
          <div class="section">
              <div class="footer-lbl">Credit Cards</div>
              <img src="/images/credit-cards.jpg" width="auto">
<br/>
              <div class="footer-lbl" style="padding-top:20px;">Pay Direct from Bank</div>
              <img src="/frontend/img/5.png" width="70%">
                
          </div>
          <div class="section">
              <div class="footer-lbl">About Us</div>
                  <p>
                     In publishing and graphic design, Lorem ipsum is a placeholder text commonly used to demonstrate the visual form of a document or a typeface without relying on meaningful content
                  </p>
          </div>
      </div>
      <div class="clear"></div>
  </footer>

  <footer class="footer-b" style="background-color: #EA2742 !important">
      <div class="wrapper-padding">
          <div class="footer-left">© Copyright {{ date('yy')}} by {{env('APP_COMPANY')}}. All rights reserved. Built on <a href='https://www.meronetwork.com'>Meronetwork ERP</a></div>
          <div class="footer-social">
              <a href="#" class="footer-twitter"></a>
              <a href="#" class="footer-facebook"></a>
              <a href="#" class="footer-instagram"></a>
          </div>
          <div class="clear"></div>
      </div>
  </footer>
