<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Notificação de Destacamento - RH-INFOSI</title>
</head>
<body>
    <h1>Olá, {{ $employee->fullName }}</h1>
    <p>Você foi destacado para o instituto {{ $institution }}.</p>
    @if($causeOfTransfer)
      <p><strong>Causa do Destacamento:</strong> {{ $causeOfTransfer }}</p>
    @endif
    <p>Atenciosamente,</p>
    <p>Equipe RH-INFOSI</p>
</body>
</html>
