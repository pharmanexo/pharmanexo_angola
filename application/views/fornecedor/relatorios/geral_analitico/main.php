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

        <div class="card">
            <div class="card-header">
                <p class="card-title">Ultimos relatórios emitidos</p>
            </div>
            <div class="card-body">
                <?php if (!empty($reportsHistory)) { ?>
                    <table class="table">
                        <tr>
                            <th>Emitido em</th>
                            <th>Emitido para</th>
                            <th>Data Inicial / Data Final</th>
                            <th>Arquivo</th>
                        </tr>
                        <?php foreach ($reportsHistory as $report) { ?>
                            <tr>
                                <td><?php echo $report['data']; ?></td>
                                <td><?php echo implode(',', $report['extra']['sent_to']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($report['extra']['data-inicial'])); ?> /
                                    <?php echo date('d/m/Y', strtotime($report['extra']['data-final'])); ?></td>
                                <td>
                                    <?php $i=1; foreach ($report['arquivos'] as $k => $file) { ?>
                                        <a href="<?php echo "http://reports2.pharmanexo.com.br/reports/" . $file; ?>" target="_blank" data-href="" class="btnDownload"><?php echo "Arquivo {$i}"?></a>
                                        <?php $i++; } ?>


                                </td>
                            </tr>
                        <?php } ?>
                    </table>

                <?php } else { ?>
                    <p>Não encontramos relatórios solicitados</p>
                <?php } ?>
            </div>
        </div>

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
                    $('#msg').html("<strong>Relatório solicitado com sucesso!</strong> <br> Esse relatório processa inúmeras informações em nossa base de dados envolvendo todos portais integrados, para não prejudicar a capacidade de processamento de nossa aplicação e gerar lentidão em seu usuário, estamos enviando por email o relatório solicitado. Obrigado.")
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
