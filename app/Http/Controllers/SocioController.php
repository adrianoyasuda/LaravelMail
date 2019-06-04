<?php

namespace App\Http\Controllers;

use Request;
use App\Mail\EnviarEmail;
use App\Socio;

class SocioController extends Controller{
    
    public function cadastrar() {

        $socio = new Socio();

        $socio->nome = Request::input('nome');
        $socio->email = Request::input('email');

        $socio->save();

        return view('messagebox')->with('tipo', 'alert alert-success')
                    ->with('titulo', 'Cadastro')
                    ->with('msg', 'Socio Cadastrado com Sucesso')
                    ->with('acao', "/socios");
    }

    public function listar() {

        $socios = Socio::all();

        return view ('socios')->with('socios', $socios);
    }

    public function enviar() {

        // Título do E-mail
        $titulo = "Relatório de Faturamento Mensal";
        // Arquivo Selecionado
        $arquivo = Request::file('arq_alunos');
        // Nenhum Arquivo Selecionado
        if (empty($arquivo)) {
            $msg = "Selecione o ARQUIVO para Importação dos E-mails!";

            return view('messagebox')->with('tipo', 'alert alert-danger')
                    ->with('titulo', 'ENTRADA DE DADOS INVÁLIDA')
                    ->with('msg', $msg)
                    ->with('acao', "/");
        }
        // Efetua o Upload do Arquivo
        $path = $arquivo->store('uploads');

        // Efetua a Leitura do Arquivo
        $fp = fopen("../storage/app/".$path, "r");

        if ($fp != false) {
            // Array que receberá os dados do Arquivo
            $dados = array();

            $total = 0;
            $saldo = 0;
            $despesa = 0;
            $receita = 0;
            $saldo_f = 0;

            while(!feof($fp)) {

                $linha = utf8_decode(fgets($fp));

                if(!empty($linha)) {
                    // Separa os dados
                    $dados = explode("#", $linha);

                    if($dados[0] == 'R'){
                        $receita += $dados[1];
                    }

                    if($dados[0] == 'D'){
                        $despesa += $dados[1];
                    }

                    if($dados[0] == 'S'){
                        $saldo += $dados[1];
                    }
                }
            }
            $saldo_f = $saldo - $despesa + $receita;
        }

        $dados = array();

        $cb_box = Request::input('ok');

        foreach($cb_box as $check) {

            $dados = explode('_', $check);
            $tmp_id = $dados[1];
            $socio = Socio::find($tmp_id);

            if(!empty($socio)) {
                $dados_mail = array();
                $dados_mail['saldo_inicial'] = 'R$' . number_format($saldo, 2, ',', '.');
                $dados_mail['receita'] = 'R$' . number_format($receita, 2, ',', '.');
                $dados_mail['despesa'] = 'R$' . number_format($despesa, 2, ',', '.');
                $dados_mail['saldo_final'] = 'R$' . number_format($saldo_f, 2, ',', '.');
                $email = mb_strtolower($socio->email, 'UTF-8');
                \Mail::to($email)->send( new EnviarEmail("mailImportar", $dados_mail, $titulo) );
                sleep(2);
                $total++;
            }
        }

        // Importação Finalizada com Sucesso
        $msg = "Um total de '$total' gênios(s) foi importado com sucesso!";

        return view('messagebox')->with('tipo', 'alert alert-success')
                ->with('titulo', 'IMPORTAÇÃO DE DADOS')
                ->with('msg', $msg)
                ->with('acao', "/");
    }
}
