<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="keywords" content="HTML5 Template" />
  <meta name="keywords" content="Fondex - Business and Finance Consulting HTML5 Template, Zippco - Business and Finance Consulting WordPress Theme, Axacus - Business Agency WordPress Theme, Axacus - Business Agency HTML Template, themes & template, html5 template, html template, html, woocommerce, shopify, prestashop, eCommerce, JavaScript, best CSS theme,css3, elementor theme, latest premium themes 2023, latest premium templates 2023, Preyan Technosys Pvt.Ltd, cymol themes, themetech mount, Web 3.0, multi-theme, website theme and template, woocommerce, bootstrap template, web templates, responsive theme, services, web design and development, business accountant, advisor, business, company consultancy, creative websites, finance, financial, insurance, legal adviser, business agents, marketing, trader, trading">
  <meta name="description" content="Fondex – Business &amp; Finance Consulting HTML Template" />
  <meta name="author" content="www.themetechmount.com" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <title>INFOSI recursos humanos</title>

  <link rel="shortcut icon" href="{{ asset('auth/img/infosi3.png') }}" />


  <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/bootstrap.min.css') }}"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/animate.css') }}"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/owl.carousel.css') }}"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/font-awesome.css') }}"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/themify-icons.css') }}"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/flaticon.css') }}"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('frontend/revolution/css/layers.css') }}"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('frontend/revolution/css/settings.css') }}"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/prettyPhoto.css') }}"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/shortcodes.css') }}"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/main.css') }}"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/responsive.css') }}"/>
  

  @push('styles')
<style>
  /* Fundo do header (desktop) */
  header.ttm-header-style-classic {
    background: url('{{ asset("frontend/images/footer-bg-one.jpg") }}') center/cover no-repeat !important;
  }
  header.ttm-header-style-classic .ttm-header-wrap {
    background: transparent !important;
  }
  .site-navigation,
  .site-navigation nav.menu {
    background: transparent !important;
  }

  /* ====================== Mobile/sidebar ====================== */
  @media screen and (max-width: 1200px) {
    /* sidebar vai herdar a mesma imagem */
    header.ttm-header-style-classic .site-navigation nav.menu,
    header.ttm-header-style-classic .site-navigation nav.menu.active {
      background: url('{{ asset("frontend/images/footer-bg-one.jpg") }}') center/cover no-repeat !important;
    }
  }
</style>
@endpush


  @stack('styles')
</head>
<body>

  @if(Auth::check())
    <script>
      // Se o usuário estiver autenticado, realiza o logout automático ao carregar a página pública.
      fetch("{{ route('logout') }}", {
          method: 'POST',
          headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Content-Type': 'application/json'
          },
          keepalive: true
      })
      .then(() => console.log("Logout automático realizado."))
      .catch(error => console.error("Erro no logout automático:", error));
    </script>
  @endif

  <!-- Preloader -->
  <div id="preloader">
    <div id="status">&nbsp;</div>
  </div>

  <!-- Cabeçalho Completo (Topbar, Branding e Menu) -->
  @include('layouts.site.header')

  <!-- Conteúdo da Página -->
  <div class="page">
    @yield('content')
    <div id="contact-anchor"></div>
  </div>

  <!-- Rodapé -->
  @include('layouts.site.footer')

  <!-- Scripts -->
  <script src="{{ asset('frontend/js/jquery.min.js') }}"></script>
  <script src="{{ asset('frontend/js/tether.min.js') }}"></script>
  <script src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('frontend/js/jquery.easing.js') }}"></script>
  <script src="{{ asset('frontend/js/jquery-waypoints.js') }}"></script>
  <script src="{{ asset('frontend/js/jquery-validate.js') }}"></script>
  <script src="{{ asset('frontend/js/owl.carousel.js') }}"></script>
  <script src="{{ asset('frontend/js/jquery.prettyPhoto.js') }}"></script>
  <script src="{{ asset('frontend/js/numinate.min6959.js?ver=4.9.3') }}"></script>
  <script src="{{ asset('frontend/js/main.js') }}"></script>
  <script src="{{ asset('frontend/js/chart.js') }}"></script>
  <script src="{{ asset('frontend/revolution/js/jquery.themepunch.tools.min.js') }}"></script>
  <script src="{{ asset('frontend/revolution/js/jquery.themepunch.revolution.min.js') }}"></script>
  <script src="{{ asset('frontend/revolution/js/slider.js') }}"></script>
  <script src="{{ asset('frontend/revolution/js/extensions/revolution.extension.actions.min.js') }}"></script>
  <script src="{{ asset('frontend/revolution/js/extensions/revolution.extension.carousel.min.js') }}"></script>
  <script src="{{ asset('frontend/revolution/js/extensions/revolution.extension.kenburn.min.js') }}"></script>
  <script src="{{ asset('frontend/revolution/js/extensions/revolution.extension.layeranimation.min.js') }}"></script>
  <script src="{{ asset('frontend/revolution/js/extensions/revolution.extension.migration.min.js') }}"></script>
  <script src="{{ asset('frontend/revolution/js/extensions/revolution.extension.navigation.min.js') }}"></script>
  @stack('scripts')

</body>
</html>