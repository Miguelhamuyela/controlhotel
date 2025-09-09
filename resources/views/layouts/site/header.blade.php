<header id="masthead" class="header ttm-header-style-classic">
  <!-- Barra Superior -->
  <div style="background-color: #E46705; color: #fff; padding: 10px 20px; width: 100%;">
    <div style="display: flex; justify-content: flex-end; align-items: center; gap: 20px;">
      <!-- RH-INFOSI -->
      <div style="white-space: nowrap;">
        <i class="fas fa-company"></i>
        <em><strong>RH</strong>-INFOSI </em>
      </div>
      <!-- E-mail -->
      <div style="white-space: nowrap;">
        <i class="fa fa-envelope-o"></i>
        <a href="mailto:rhinfosi@gmail.com" style="color: #fff; text-decoration: none; margin-left: 5px;">
          rhinfosi@gmail.com
        </a>
      </div>
      <!-- Telefone -->
      <div style="white-space: nowrap;">
        <i class="fa fa-phone"></i>
        <span style="margin-left: 5px;">(+244) 222 692 971</span>
      </div>
      <!-- Redes Sociais -->
      <div style="display: flex; gap: 10px;">
        <a href="https://www.facebook.com/TEC.DIGITAL.AO" target="_blank" rel="noopener noreferrer" style="color: #fff;">
          <i class="fa fa-facebook"></i>
        </a>
        <a href="https://www.instagram.com/infosi01/" target="_blank" rel="noopener noreferrer" style="color: #fff;">
          <i class="fa fa-instagram"></i>
        </a>
      </div>
    </div>
  </div>
  <!-- Fim da Barra Superior -->

  <!-- Cabeçalho Principal (Branding e Navbar) -->
  <div class="ttm-header-wrap">
    <div id="ttm-stickable-header-w" class="ttm-stickable-header-w clearfix">
      <div id="site-header-menu" class="site-header-menu">
        <div class="site-header-menu-inner ttm-stickable-header">
          <div class="container">
            <!-- Branding -->
            <br>
            <div class="site-branding">
              <a class="home-link" href="{{ route('frontend.index') }}" title="RH-INFOSI" rel="home">
                <img id="logo-img" class="img-center" src="{{ asset('auth/img/infosi2.png') }}" alt="logo-img">
              </a>
            </div>
            <!-- Navbar -->
            @include('layouts.site.navbar')
          </div>
        </div>
      </div>
    </div>
  </div>
  
</header>
