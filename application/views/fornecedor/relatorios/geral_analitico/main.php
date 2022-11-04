<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>
<div class="content">
    <?php echo $heading; ?>
    <div class="content__inner">
        <form action="<?php echo $dataTable; ?>" name="fitros" id="filters" method="post" enctype="multipart/form-data">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Período</label>
                                <div class="input-group mb-3">
                                    <input type="date" class="form-control"
                                           value="<?php if (isset($post['dataini'])) echo $post['dataini']; ?>"
                                           name="dataini"
                                           id="dataini">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">a</span>
                                    </div>
                                    <input type="date" class="form-control" name="datafim"
                                           value="<?php if (isset($post['datafim'])) echo $post['datafim']; ?>"
                                           id="datafim">
                                </div>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group text-center">
                                <label for=""></label><br>
                                <button type="submit" form="filters" id="btnBuscar"
                                        class="btn pull-right mt-2 btn-primary">Solicitar</button>
                            </div>
                        </div>
                    </div>
                    <p id="msg"></p>
                </div>
            </div>

        </form>

    </div>
</div>


<?php echo $scripts; ?>

<script>
    var url;
    $(function () {


        $('#uf').selectpicker();
        $('#clientes').selectpicker();


        $('#data_fim, #loja').change(function (e) {

            if ($('#data_ini').val() == '') {
                formWarning({type: 'warning', 'message': 'Informe da data de início'});
                return false;
            }

            if ($('#data_fim').val() == '') {
                formWarning({type: 'warning', 'message': 'Informe da data fim'});
                return false;
            }


        });

        $('#btnBuscar, #btnExcel').click(function () {
            $('#message').removeClass('d-none').html("<strong><i class='fa fa-spin fa-spinner'></i> Carregando informações ...</strong>");
        });


        $('#btnBuscar').click(function (e) {
            e.preventDefault();
            var action = '<?php if (isset($dataTable)) echo $dataTable?>';
            $('#msg').html("<i class='fa fa-spin fa-spinner'></i> Enviando solicitação... ")

            $.post(action, $('#filters').serialize(), function (xhr){
                if (xhr.type == 'success'){
                    $('#msg').html("<strong>Relatório solicitado com sucesso!</strong> <br> Como o processamento desse relatório é um pouco mais demorado devido ao grande fluxo de dados consultados, enviaremos o resutlado para seu e-mail cadastrado, assim que concluirmos.")
                }
            }, 'JSON');
        });

        $('#uf').change(function () {
            $('#estados').val($(this).val().toString())
        })

        $('#clientes').change(function () {
            $('#id_clientes').val($(this).val().toString())
        })

    })
    ;
</script>
</body>
