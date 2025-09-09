<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <!-- Meta tag para responsividade -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Meu Chat - Novo Layout</title>
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome para ícones -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

  <!-- Stack para pegar os estilos customizados do Blade -->
  @stack('styles')

  <style>
    /* Estilos customizados para melhorar a responsividade */
    @media (max-width: 768px) {
      body {
        padding: 0 10px;
      }
      .navbar-brand {
        font-size: 1.2rem;
      }
      .card {
        margin: 0.5rem 0;
      }
      .container.my-4 {
        padding: 0;
      }
      /* Ajuste de espaçamentos gerais para telas menores */
      .btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.9rem;
      }
    }
  </style>
</head>
<body>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Novo Chat</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
              aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Alterna navegação">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Espaço para adicionar links ou menus, se necessário -->
      </div>
    </div>
  </nav>

  <div class="container my-4">
    @yield('content')
  </div>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Stack para pegar os scripts customizados do Blade -->
  @stack('scripts')
</body>
</html>
