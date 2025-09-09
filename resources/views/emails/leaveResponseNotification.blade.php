<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Resposta ao Pedido de Licença</title>
</head>
<body>
    <p>Olá {{ $leave->employee->fullName }},</p>
    <p>Seu pedido de licença foi <strong>{{ $leave->approvalStatus }}</strong>.</p>
    <p>Comentário: {{ $leave->approvalComment }}</p>
    <p>Obrigado,</p>
    <p>Equipe RH-INFOSI</p>
</body>
</html>
