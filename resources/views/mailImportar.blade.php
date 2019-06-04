<!DOCTYPE html>
<html>
    <head>
        <title>Relança - Sistema de Relatório de Finança</title>
    </head>
    <body>
        <h3>Prezado sócio,</h3>
        <br><br>
        <b>Segue a seguir o relatorio de faturamento mensal:</b><br>
        <br>
        <b>Saldo Inicial: {{ $dados['saldo_inicial'] }}</b><br>
        <b>Receitas: {{ $dados['receita'] }}</b> <br>
        <b>Despesa: {{ $dados['despesa'] }}</b><br>
        <b>Saldo Final: {{ $dados['saldo_final'] }}</b><br>
        <br>
        <b>Atenciosamente,<br>Relança - Sistema de Relatório de Finança</b>
    </body>
</html>