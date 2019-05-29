<?php

namespace App\Http\Controllers;

use Request;

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

        $saldo = 0;
        $despesa = 0;
        $receita = 0;
        $saldo_final = 0;

        // Título do E-mail
        $titulo = Request::input('titulo');
        // Conteúdo do E-mail
        $conteudo = Request::input('conteudo');

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

            while(!feof($fp)) {

                $linha = utf8_decode(fgets($fp));

                if(!empty($linha)) {
                    // Separa os dados
                    $dados = explode("#", $linha);
                    if($dados[0] == 'R'){
                        $receita += $dados[1];
                    }

                    if($dados[0] == 'D'){
                        $receita += $dados[1];
                    }

                    if($dados[0] == 'S'){
                        $receita += $dados[1];
                    }
                    // Nova instância Genio - Configura dados
                    $objGenio = new GenioModel();
                    $objGenio->nome = mb_strtoupper($dados[0], 'UTF-8');
                    $objGenio->nascimento = self::getDataBD($dados[1]);
                    $objGenio->save();

                    // Envia e-mail com a senha para os gênios importados do .txt
                    $dados_mail = array();
                    $dados_mail['nome'] = mb_strtoupper($dados[0], 'UTF-8');
                    $dados_mail['senha'] = $senha;
                    $dados_mail['conteudo'] = $conteudo;
                    $email = mb_strtolower($dados[2], 'UTF-8');
                    \Mail::to($email)->send( new EnviarEmail("mailImportar", $dados_mail, $titulo) );
                    sleep(5);
                    $total++;
                }
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
