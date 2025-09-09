{{-- resources/views/frontend/about.blade.php --}}
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="keywords" content="HTML5 Template" />
  <meta name="description" content="INFOSI Recursos Humanos" />
  <meta name="author" content="INFOSI" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <title>Sobre o INFOSI - INFOSI</title>
  <link rel="shortcut icon" href="{{ asset('auth/img/infosi3.png') }}" />

  <!-- CSS -->
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

    @stack('styles')

  <!-- ===================== Sobrepor fundo da navbar ===================== -->
  <style>
    /* Aplica a tua imagem em todo o header (barra superior + navbar) */
    header.ttm-header-style-classic {
      background: url('{{ asset("frontend/images/footer-bg-one.jpg") }}') center/cover no-repeat !important;
    }
    /* Garante que wrappers internos não metam branco por cima */
    header.ttm-header-style-classic .ttm-header-wrap,
    .site-navigation,
    .site-navigation nav.menu {
      background: transparent !important;
    }

    /* Também no mobile/sidebar */
    @media screen and (max-width: 1200px) {
      header.ttm-header-style-classic .site-navigation nav.menu,
      header.ttm-header-style-classic .site-navigation nav.menu.active {
        background: url('{{ asset("frontend/images/footer-bg-one.jpg") }}') center/cover no-repeat !important;
      }
    }

    /* Dropdowns transparentes para a imagem aparecer por trás */
    header.ttm-header-style-classic .sub-menu {
      background: transparent !important;
      border: none !important;
      box-shadow: none !important;
    }
  </style>
  <!-- =================================================================== -->

  @stack('styles')
</head>
<body>
  <!-- Preloader -->
  <div id="preloader">
    <div id="status">&nbsp;</div>
  </div>

  <!-- Cabeçalho -->
  @include('layouts.site.header')

  <!-- Conteúdo Principal -->
  <div class="page">
    <div class="container my-4">
      <div class="text-center">
        <h2 style="font-weight: bold;">Sobre o INFOSI</h2>
        <p style="max-width: 800px; margin: 0 auto;">
          O <strong>Instituto Nacional de Fomento da Sociedade da Informação (INFOSI)</strong> impulsiona a inovação e a inclusão digital em Angola, atuando como agente transformador nos serviços públicos de TI e telecomunicações.
        </p>
      </div>

      <div class="mt-4">
        <h3 style="font-weight: bold; text-align: center;">Missão</h3>
        <p style="text-align: justify; margin-top: 20px;">
          O INFOSI tem por missão a execução e distribuição dos serviços públicos de tecnologias de informação e de telecomunicações administrativas, conforme as diretrizes do Executivo. Essa missão abrange a implementação de políticas que promovam o desenvolvimento, o conhecimento e a inclusão digital em todo o país.
        </p>
      </div>

      <div class="mt-4">
        <h3 style="font-weight: bold; text-align: center;">Histórico</h3>
        <p style="text-align: justify; margin-top: 20px;">
          O INFOSI foi criado a 20 de Abril de 2016, através do Decreto Presidencial nº 86/16, como resultado da fusão do Centro Nacional das Tecnologias de Informação (CNTI) e do Instituto de Telecomunicações Administrativas (INATEL). Com sede em Luanda e atuação em todo o território nacional, o INFOSI tem se consolidado como um pilar estratégico para a modernização das infraestruturas de TI e telecomunicações.
        </p>
      </div>
    </div>
  </div>

  <!-- Rodapé -->
  @include('layouts.site.footer')
  <script src="{{ asset('frontend/js/jquery.min.js') }}"></script>
  <script src="{{ asset('frontend/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('frontend/js/main.js') }}"></script>
  @stack('scripts')
</body>
</html>
