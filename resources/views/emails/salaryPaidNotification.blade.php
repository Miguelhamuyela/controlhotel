<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Pagamento de Salário</title>
</head>
<body>
  <p>Olá {{ $payment->employee->fullName }},</p>
  <p>Seu salário do mês de <strong>{{ \Carbon\Carbon::parse($payment->workMonth)->translatedFormat('F/Y') }}</strong> foi registrado com sucesso.</p>
  <ul>
    <li>Salário Básico: Kz {{ number_format($payment->baseSalary,2,',','.') }}</li>
    <li>Subsídios: Kz {{ number_format($payment->subsidies,2,',','.') }}</li>
    <li>Desconto: Kz {{ number_format($payment->discount,2,',','.') }}</li>
    <li><strong>Salário Líquido: Kz {{ number_format($payment->salaryAmount,2,',','.') }}</strong></li>
    <li>Status: {{ $payment->paymentStatus }}</li>
    <li>Data de Pagamento: {{ $payment->paymentDate }}</li>
  </ul>
  <p>Qualquer dúvida, entre em contato com o RH.</p>
  <p>Atenciosamente,<br>Equipe RH-INFOSI</p>
</body>
</html>
