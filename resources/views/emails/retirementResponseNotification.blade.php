<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Resposta ao Pedido de Reforma</title>
</head>
<body>
    <p>Olá {{ $retirement->employee->fullName }},</p>
    <p>Seu pedido de reforma foi <strong>{{ $retirement->status }}</strong>.</p>
    <p>Observações: {{ $retirement->observations }}</p>
    <p>Obrigado,</p>
    <p>Equipe RH-INFOSI</p>
</body>
</html>
