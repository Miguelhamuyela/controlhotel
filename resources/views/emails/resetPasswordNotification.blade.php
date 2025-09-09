<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <title>Redefinir Senha - RH-INFOSI</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 16px;
            color: #333;
            line-height: 1.5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <p>Olá,</p>
        <p>Você solicitou a redefinição de sua senha no sistema RH-INFOSI. Para prosseguir, clique no link abaixo:</p>
        <p>
            <a class="button" href="{{ url('resetPassword/'.$token) }}">
                Redefinir Senha
            </a>
        </p>
        <p>Se você não solicitou essa alteração, por favor, ignore este e-mail.</p>
        <p>Atenciosamente,<br>RH-INFOSI</p>
    </div>
</body>
</html>
