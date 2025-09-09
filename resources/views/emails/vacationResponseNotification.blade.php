<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Resposta ao Pedido de Férias</title>
</head>
<body>
    <p>Olá {{ $vacation->employee->fullName }},</p>
    <p>Seu pedido de férias foi <strong>{{ $vacation->approvalStatus }}</strong>.</p>
    <p>Comentário: {{ $vacation->approvalComment }}</p>
    <p>Data de Início: {{ $vacation->vacationStart }}</p>
    <p>Data de Término: {{ $vacation->vacationEnd }}</p>
    <p>Obrigado,</p>
    <p>Equipe RH-INFOSI</p>
</body>
</html>
