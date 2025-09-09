<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('pdfTitle', 'Relatório')</title>
    <style>
      body {
        font-family: sans-serif;
        margin: 0;
        padding: 0;
        position: relative;
      }
      /* Exemplo de três fundos repetidos (bg-top, bg-middle, bg-bottom) */
      .bg-middle{
        position: fixed;
        left: 0;
        width: 100%;
        z-index: -1;
        opacity: 0.1;
        background: url("{{ public_path('images/infosi/infosiH.png') }}") no-repeat center center;
        background-size: 35em auto;
      }
     
      .bg-middle {
        top: 33%;
        height: 34%;
      }
      

      .header {
        text-align: center;
        margin-bottom: 20px;
      }
      .header img.logo {
        width: 70px;
      }
      .header h3 {
        margin: 5px 0 2px;
        font-size: 1.0rem;
      }
      .header p {
        margin: 2px 0;
        font-size: 0.9rem;
      }

      .title-section {
        text-align: center;
        margin-top: 5px;
        margin-bottom: 15px;
      }
      .title-section h4 {
        margin: 0;
        font-size: 1rem;
      }
      .title-section p {
        margin: 2px 0;
        font-size: 0.85rem;
      }

      table {
        width: 90%;
        margin: 0 auto;
        border-collapse: collapse;
        border: 1px solid #ddd;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        font-size: 0.9rem;
      }
      thead tr {
        background-color: #f8f8f8;
      }
      th, td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
      }
      th {
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #333;
      }
      tbody tr:nth-child(even) {
        background-color: #f2f2f2;
      }

      .footer {
        position: fixed;
        bottom: 20px;
        left: 20px;
        width: 100%;
        text-align: left;
      }
      .footer img {
        width: 100px;
        display: block;
        margin-bottom: 5px;
      }
      .footer p {
        margin: 0;
        font-size: 0.85rem;
      }
    </style>
</head>
<body>

  <div class="bg-top"></div>
  <div class="bg-middle"></div>
  <div class="bg-bottom"></div>

  <div class="header">
    <img src="{{ public_path('images/infosi/insigniaAngola.png') }}" class="logo" alt="Logo Angola">
    <h3>REPÚBLICA DE ANGOLA</h3>
    <p>MINISTÉRIO DAS TELECOMUNICAÇÕES, TECNOLOGIAS DE INFORMAÇÃO E COMUNICAÇÃO SOCIAL</p>
    <p>INSTITUTO NACIONAL DE FOMENTO DA SOCIEDADE DA INFORMAÇÃO</p>
    <hr>
  </div>

  <div class="title-section">
    @yield('titleSection')
  </div>

  @yield('contentTable')

  <div class="footer">
    <img src="{{ public_path('images/infosi/infosiH.png') }}" alt="Infosi Logo">
    <p><strong>Instituto Nacional de Fomento da Sociedade de Informação</strong></p>
    <p>Rua 17 de Setembro nº 59, Cidade Alta, Luanda - Angola</p>
    <p>Caixa Postal: 1412 | Tel.: +244 222 693 503 | Geral@infosi.gov.ao</p>
    <p>www.infosi.gov.ao</p> <br>
    <p style="text-align: center;">Data de Emissão: {{ date('d/m/Y') }}</p>
  </div>

</body>
</html>
