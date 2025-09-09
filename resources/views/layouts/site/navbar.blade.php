<style>

  
  .site-navigation,
.site-navigation nav.menu {
  background: transparent !important;
}


  /* Estilos gerais para a navbar */
  .site-navigation nav.menu {
      display: flex; 
      align-items: center;
  }

  /* Esconde a versão mobile do botão de área restrita por padrão */
  .restricted-mobile {
      display: none;
  }

  /* Exibe o botão de área restrita para desktop */
  .restricted-desktop {
      display: inline-block;
  }

  /* ====================== Estilos para telas menores ====================== */

  
  @media screen and (max-width: 1200px) { /* ← breakpoint ampliado para 1200 */
      .site-navigation nav.menu {
         display: none;
         position: fixed;
         top: 0;
         right: -300px;
         width: 300px;
         height: 100%;
         background: #fff;
         padding: 80px 20px 20px 20px;
         overflow-y: auto;
         z-index: 1000;
         transition: right 0.3s ease;
      }
      .site-navigation nav.menu.active {
         display: block;
         right: 0;
      }
      .ttm-menu-toggle {
         position: fixed;
         top: 50px;
         right: 20px;
         z-index: 1100;
         cursor: pointer;
      }
      .ttm-menu-toggle span {
         display: block;
         width: 30px;
         height: 4px;
         background: #8b3e03;
         margin: 5px 0;
         transition: all 0.3s ease;
      }
      .ttm-menu-toggle.open span.toggle-blocks-1 {
         transform: rotate(45deg) translate(5px, 5px);
      }
      .ttm-menu-toggle.open span.toggle-blocks-2 {
         opacity: 0;
      }
      .ttm-menu-toggle.open span.toggle-blocks-3 {
         transform: rotate(-45deg) translate(5px, -5px);
      }
      .restricted-mobile {
         display: block;
         text-align: center;
         margin-bottom: 20px;
      }
      .restricted-mobile span.restricted-label {
         display: block;
         margin-top: 5px;
         font-size: 14px;
         font-weight: bold;
         color: #f27602;
      }
      .restricted-desktop {
         display: none;
      }

      /* Torna o submenu estático dentro da sidebar */
      .site-navigation nav.menu .has-submenu .sub-menu {
        position: static;
        border: none;
        background: none;
        padding-left: 15px;
        margin-bottom: 10px;
      }
      .site-navigation nav.menu .has-submenu .sub-menu li a {
        padding: 5px 0;
      }
  }

  /* Estilos para submenu (desktop) */
  .has-submenu > a::after {
    content: '\f107';
    font-family: 'FontAwesome';
    margin-left: 5px;
  }
  .sub-menu {
    display: none;
    position: absolute;
    background: #fff;
    border: 1px solid #ddd;
    padding: 10px 0;
    list-style: none;
    min-width: 200px;
    z-index: 100;
  }
  .has-submenu:hover > .sub-menu {
    display: block;
  }
  .sub-menu li a {
    display: block;
    padding: 5px 20px;
    color: #333;
    text-decoration: none;
  }
  .sub-menu li a:hover {
    background: #f7f7f7;
  }
</style>

<div id="site-navigation" class="site-navigation">
  <!-- Botão da Área Restrita para desktop -->
  <div class="header-btn restricted-desktop">
    <div id="restricted-area-container" style="position: relative; display: inline-block;">
      <a id="restricted-area-link"
         class="ttm-btn ttm-btn-size-md ttm-btn-shape-square ttm-btn-style-border ttm-btn-color-black"
         href="{{ route('dashboard') }}"
         style="width: 50px; height: 50px; border-radius: 50% !important; display: inline-flex; align-items: center; justify-content: center; padding: 0 !important;">
        <i class="fa fa-user" style="color: #f27602; font-size: 20px; padding: 8px; border: 2px solid #f27602; border-radius: 50%;"></i>
      </a>
      <div id="restricted-tooltip" style="
            display: none;
            position: absolute;
            top: calc(100% + 5px);
            left: 50%;
            transform: translateX(-50%);
            background: #fff;
            border: 1px solid #ccc;
            padding: 8px 12px;
            border-radius: 4px;
            white-space: nowrap;
            font-size: 12px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 2000;">
        Área restrita (só para funcionários do INFOSI)
      </div>
    </div>
  </div>
  
  <!-- Botão Hamburger para telas pequenas -->
  <div class="ttm-menu-toggle" id="mobileMenuToggle">
    <span class="toggle-blocks-1"></span>
    <span class="toggle-blocks-2"></span>
    <span class="toggle-blocks-3"></span>
  </div>
  
  <!-- Menu de Navegação (mobile) -->
  <nav id="menu" class="menu">
    <!-- Botão de área restrita no mobile -->
    <div class="restricted-mobile">
      <a href="{{ route('dashboard') }}" 
         style="display: inline-flex; align-items: center; justify-content: center; width: 50px; height: 50px; border: 2px solid #f27602; border-radius: 50%; margin: 0 auto;">
        <i class="fa fa-user" style="color: #f27602; font-size: 20px; padding: 8px;"></i>
      </a>
      <span class="restricted-label">Área Restrita</span>
    </div>
    <ul class="dropdown">
      <li class="active"><a href="{{ route('frontend.index') }}">Início</a></li>
      <li class="has-submenu">
        <a href="#">Sobre Nós</a>
        <ul class="sub-menu">
           <li><a href="{{ route('frontend.about') }}">Sobre o INFOSI</a></li>
           <li><a href="{{ route('frontend.statute') }}">Nosso Estatuto</a></li>
           <li><a href="{{ route('frontend.directors') }}">Nossa Diretoria</a></li>
         </ul>
      </li>
      <li><a href="{{ route('frontend.index') }}#services">Nossos Serviços</a></li>
      <li><a href="#contact-anchor">Contato</a></li>
    </ul>
  </nav>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
      var mobileMenuToggle = document.getElementById('mobileMenuToggle');
      var menu = document.getElementById('menu');
      
      // Ao clicar no hamburger, exibe ou oculta o menu mobile
      mobileMenuToggle.addEventListener('click', function() {
          menu.classList.toggle('active');
          mobileMenuToggle.classList.toggle('open');
      });
      
      // Fecha o menu mobile ao clicar em algum item, exceto se for submenu
      var menuLinks = menu.querySelectorAll('a');
      menuLinks.forEach(function(link) {
          link.addEventListener('click', function(e) {
              var parentLi = this.parentElement;
              if ( menu.classList.contains('active') && !parentLi.classList.contains('has-submenu') ) {
                  menu.classList.remove('active');
                  mobileMenuToggle.classList.remove('open');
              }
          });
      });

      // ==================
      // Toggle de sub‐menus
      // ==================
      var submenuToggles = menu.querySelectorAll('.has-submenu > a');
      submenuToggles.forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
          e.preventDefault();               // não navega
          var sub = this.nextElementSibling; 
          sub.style.display = sub.style.display === 'block' ? 'none' : 'block';
        });
      });
      

      // Tooltip para a Área Restrita (desktop)
      var restrictedContainer = document.getElementById('restricted-area-container');
      var restrictedTooltip = document.getElementById('restricted-tooltip');
      
      restrictedContainer.addEventListener('mouseenter', function() {
          restrictedTooltip.style.display = 'block';
      });
      restrictedContainer.addEventListener('mouseleave', function() {
          restrictedTooltip.style.display = 'none';
      });
  });
</script>
