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
        function mudaLabel(cbName, labelId) {
            var lb = labelId;
            var id = cbName;
            var cb = document.getElementById(id);
            if(cb.checked) {
                document.getElementById(lb).style.color = "green";
                document.getElementById(lb).textContent = "SIM";
            }
            else {
                document.getElementById(lb).style.color = "red";
                document.getElementById(lb).textContent = "NÃO";
            }
        }

</script>

@stop


@section('cabecalho')
<div>
        <img src=" {{ url('/img/socios_ico.png') }}" width="75" height="75" >
        &nbsp;SRF &raquo; Administração
</div>
@stop

@section('conteudo')
    <form action="{{ action('SocioController@cadastrar') }}" method="POST" enctype="multipart/form-data">
        <input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
        <input type ="hidden" name="cadastrar" value="C">

        <div class="row">
            <div class="col-sm-6">
                <label>Nome: </label>
                <input type="text" class="form-control" name="nome" maxlength="25" required>
            </div>

            <div class="col-sm-6">
                <label>E-Mail: </label>
                <input type="email" class="form-control" name="email" maxlength="50" required>
            </div>
        </div>

        <br>

    </form>

    <form action="{{ action('SocioController@enviar') }}" method="POST" enctype="multipart/form-data">
            <input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
            <input type ="hidden" name="importar" value="I">

        <table class='table table-striped'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NOME</th>
                    <th>EMAIL</th>
                    <th>ENVIAR RELATÓRIO</th>
                </tr>
            </thead>
            
            <tbody>
                @foreach($socios as $dados)
                    <tr>
                        <td>{{$dados->id}}</td>
                        <td>{{$dados->nome}}</td>
                        <td>{{$dados->email}}</td>

                        <td>
                        <input type="checkbox" id="cb_{{$dados->id}}" name="ok[]" value="enviar_{{$dados->id}}" onchange="javascript: mudaLabel('cb_{{$dados->id}}', 'label_enviar_{{$dados->id}}')" >
                            &nbsp;
                            <label id="label_enviar_{{$dados->id}}" style="color: red">NÃO</label>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary btn-block"><b>Cadastrar</b></button>
        
        <br><h4><strong>Enviar Relatório Financeiro</strong></h4><br>
        
        <div class="row">
            <div class="col-sm-4 form-group">
                <div class="input-group input-file" name="arq_alunos">
                    <span class="input-group-btn">
                        <button class="btn btn-success btn-choose" type="button">Abrir Navegador</button>
                    </span>
                    <input type="text" class="form-control" placeholder='Nenhum arquivo selecionado...' />
                </div>
            </div>

            <div class="col-sm-8">
                <button type="submit" class="btn btn-success btn-block"><b>Enviar Relatório por E-mail</b></button>
            </div>
        </div>
        <br>
    </form>
    
@stop