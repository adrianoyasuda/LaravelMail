@extends('principal')

@section('script')

<script type="text/javascript" src="{{ url('/js/plugins/mask/jquery.mask.js') }}"></script>

<script type="text/javascript">

    // Função de abertura do arquivo
    function bs_input_file() {

        $(".input-file").before(
            function() {
                if ( ! $(this).prev().hasClass('input-ghost') ) {
                    var element = $("<input type='file' class='input-ghost' style='visibility:hidden; height:0'>");
                    element.attr("name", $(this).attr("name"));
                    element.change(function(){
                        element.next(element).find('input').val((element.val()).split('\\').pop());
                    });
                    $(this).find("button.btn-choose").click(function(){
                        element.click();
                    });
                    $(this).find("button.btn-reset").click(function(){
                        element.val(null);
                        $(this).parents(".input-file").find('input').val('');
                    });
                    $(this).find('input').css("cursor","pointer");
                    $(this).find('input').mousedown(function() {
                        $(this).parents('.input-file').prev().click();
                        return false;
                    });
                    return element;
                }
            }
        );
    }

    $(function() {
        bs_input_file();
    });

</script>

@stop

@section('cabecalho')
<div>
        <img src=" {{ url('/img/mailp_ico.png') }}" >
        &nbsp;Importar / Enviar E-mail
</div>
@stop

@section('conteudo')

<form action="{{ action('MailController@concluir') }}" method="POST" enctype="multipart/form-data">
    <input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
    <input type ="hidden" name="importar" value="I">

    <div class="row">
        <div class="col-sm-8">
            <label>Título: </label>
            <input type="text" class="form-control" name="titulo" maxlength="50" required>
        </div>
        <div class="col-sm-4 form-group">
            <label>Arquivo de e-mails: </label>
            <div class="input-group input-file" name="arq_alunos">
                <span class="input-group-btn">
                    <button class="btn btn-success btn-choose" type="button">Abrir Navegador</button>
                </span>
                <input type="text" class="form-control" placeholder='Nenhum arquivo selecionado...' />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <label>Conteúdo/Mensagem: </label>
            <textarea class="form-control" rows="3" name="conteudo" required></textarea>
        </div>
    </div>
    <br>
    <button type="submit" class="btn btn-success btn-block"><b>Importar / Enviar</b></button>
</form>
@stop
