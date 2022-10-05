<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php if (isset($heading)) echo $heading; ?>
    <form action="<?php echo $to_datatable; ?>" name="fitros" id="filters" method="post"
          enctype="multipart/form-data">

        <div class="row">
            <div class="col-4">
                <div class="form-group">
                    <label for="">Período</label>
                    <div class="input-group mb-3">
                        <input type="date" class="form-control"
                               value="<?php if (isset($filtros['dataini'])) echo $filtros['dataini']; ?>" name="dataini"
                               id="dataini">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">a</span>
                        </div>
                        <input type="date" class="form-control"
                               value="<?php if (isset($filtros['datafim'])) echo $filtros['datafim']; ?>" name="datafim"
                               id="datafim">
                    </div>
                </div>
            </div>

            <?php if (isset($selectMatriz)): ?>
                <div class="col-4">
                    <div class="form-group">
                        <label for="">Lojas</label>
                        <input type="hidden" name="id_fornecedor" id="fornecedor">
                        <select id="id_fornecedor" multiple="multiple" data-live-search="true"
                                title="Selecione" class="form-control">
                            <option value="">Selecione...</option>
                            <?php foreach ($selectMatriz as $f): ?>
                                <option <?php if (isset($f['select'])) echo 'selected'; ?>
                                        value="<?php echo $f['id']; ?>"><?php echo $f['nome_fantasia']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (isset($filtros['datafim']) && isset($filtros['dataini'])) { ?>
                <div class="col-4">
                    <div class="form-group text-center py-3">
                        Resultados do
                        período: <?php echo date("d/m/Y", strtotime($filtros['dataini'])) . " a " . date("d/m/Y", strtotime($filtros['datafim'])) ?>
                    </div>
                </div>
            <?php } ?>
        </div>
        <br>
        <p id="msg" class="text-center h3 my-3 text-muted"></p>
    </form>


    <div class="row">
        <div class="col-12 col-lg-6">
            <div class="card" hidden>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <td>
                                Vendas no dia
                            </td>
                            <td><?php if (isset($dia['ocs'])) echo $dia['ocs'] ?></td>
                            <td>
                                R$ <?php echo (isset($dia['total']) && !is_null($dia['total'])) ? number_format($dia['total'], 4, ',', '.') : '0,00' ?></td>
                        </tr>
                        <tr>
                            <td>
                                Vendas no período
                            </td>
                            <td><?php if (isset($periodo['ocs'])) echo $periodo['ocs'] ?></td>
                            <td>
                                R$ <?php echo (isset($periodo['total']) && !is_null($periodo['total'])) ? number_format($periodo['total'], 4, ',', '.') : '0,00' ?></td>
                        </tr>
                        <tr>
                            <td>
                                Vendas no ano
                            </td>
                            <td><?php if (isset($ano['ocs'])) echo $ano['ocs'] ?></td>
                            <td>
                                R$ <?php echo (isset($ano['total']) && !is_null($ano['total'])) ? number_format($ano['total'], 4, ',', '.') : '0,00' ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="quick-stats__item"
                 style="background-image: linear-gradient(#0c965b, #32c787);border-radius: 15px;box-shadow: 3px 5px 19px 0px rgba(0,0,0,0.32);">
                <div class="quick-stats__info">
                    <small class="text-white">Vendas no dia</small>
                    <h3 style="color: white"><?php echo (isset($dia['total']) && !is_null($dia['total'])) ? number_format($dia['total'], 4, ',', '.') : '0,00' ?></h3>
                    <small class="text-white">Qtde.: <?php if (isset($dia['ocs'])) echo $dia['ocs'] ?></small>
                </div>

                <div class="quick-stats__chart sparkline-bar-stats text-white">
                    <i class="fas fa-hand-holding-usd fa-3x" style="opacity: 0.5;"></i>
                </div>
            </div>

            <div class="quick-stats__item"
                 style="background-image: linear-gradient(#3b85ce, #41b0eb);border-radius: 15px;box-shadow: 3px 5px 19px 0px rgba(0,0,0,0.32);">
                <div class="quick-stats__info">
                    <small class="text-white">Vendas no período</small>
                    <h3 style="color: white"><?php echo (isset($periodo['total']) && !is_null($periodo['total'])) ? number_format($periodo['total'], 4, ',', '.') : '0,00' ?></h3>
                    <small class="text-white">Qtde.: <?php if (isset($periodo['ocs'])) echo $periodo['ocs'] ?></small>
                </div>

                <div class="quick-stats__chart sparkline-bar-stats text-white">
                    <i class="fas fa-hand-holding-usd fa-3x" style="opacity: 0.5;"></i>
                </div>
            </div>

            <div class="quick-stats__item"
                 style="background-image: linear-gradient(#3a3e42, #83888d);border-radius: 15px;box-shadow: 3px 5px 19px 0px rgba(0,0,0,0.32);">
                <div class="quick-stats__info">
                    <small class="text-white">Vendas no ano</small>
                    <h3 style="color: white"><?php echo (isset($ano['total']) && !is_null($ano['total'])) ? number_format($ano['total'], 4, ',', '.') : '0,00' ?></h3>
                    <small class="text-white">Qtde.: <?php if (isset($ano['ocs'])) echo $ano['ocs'] ?></small>
                </div>

                <div class="quick-stats__chart sparkline-bar-stats text-white">
                    <i class="fas fa-hand-holding-usd fa-3x" style="opacity: 0.5;"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">

                    <div class="row">
                        <div class="col-md-10">
                            <h3 class="w-75 p-3 card-title">Cotações Recebidas X Respondidas</h3>
                        </div>
                        <div class="col-md-2 float-right">
                            <button type="button" class="btn btn-primary" data-toggle="tooltip" title="Exportar" form="filters" id="btnExcel"><i class="fa fa-file-excel"></i></button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (isset($ranking['ocs']) && !empty($ranking['ocs'])) { ?>
                        <table class="table table-bordered">
                            <tr>
                                <td>Mês/Ano</td>
                                <td>Abertas</td>
                                <td>Respondidas</td>
                                <td>% Respondida</td>
                            </tr>
                            <?php foreach ($cotacoes as $cot) { ?>
                                <tr>
                                    <td><?php echo $cot['data']; ?></td>
                                    <td><?php echo $cot['abertas']; ?></td>
                                    <td><?php echo $cot['respondidas']; ?></td>
                                    <td><?php echo number_format($cot['percent'], 2, '.', ''); ?>%</td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } ?>
                </div>
            </div>
        </div hi>
        <div class="col-12 col-lg-4" hidden>
            <div class="card">
                <div class="card-header">
                    <div class="card-title">10 maiores pedidos (por itens)</div>
                </div>
                <div class="card-body" style="overflow-x: scroll">
                    <?php if (isset($ranking['produtos']) && !empty($ranking['produtos'])) { ?>
                        <table class="table table-bordered">
                            <tr>
                                <td>Cod. Ordem Compra</td>
                                <td>Itens</td>
                                <td>Código</td>
                                <td>Total</td>
                            </tr>
                            <?php foreach ($ranking['produtos'] as $produto) { ?>
                                <tr>
                                    <td><?php echo $produto['oc']; ?></td>
                                    <td><?php echo $produto['itens']; ?></td>
                                    <td><?php echo $produto['codigo']; ?></td>
                                    <td>R$ <?php echo number_format($produto['total'], 2, ',', '.'); ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>


</div>

<?php echo $scripts; ?>

<script>
    var url;
    $(function () {

        $('#id_fornecedor').selectpicker();

        url = $('#data-table').data('url');

        $('#btnExcel').click(function (){
            $('#filters').prop('action', '<?php echo $urlExport; ?>');
            $('#filters').submit();
        })

        $('#datafim, #id_fornecedor').change(function (e) {
            $('#msg').html("<i class='fas fa-spinner fa-spin'></i> Aguarde... ")

            if ($('#dataini').val() == '') {
                formWarning({type: 'warning', 'message': 'Informe da data de início'});
                $('#msg').html("")
                return false;
            }

            if ($('#datafim').val() == '') {
                formWarning({type: 'warning', 'message': 'Informe da data fim'});
                $('#msg').html("")
                return false;
            }



            // console.log($("#id_fornecedor").val().toString());

            if ($("#id_fornecedor").val() != null) {
                if ($("#id_fornecedor").val().toString() == '') {
                    formWarning({type: 'warning', 'message': 'Informe uma loja'});
                    $('#msg').html("")
                    return false;
                }

                $('#fornecedor').val($("#id_fornecedor").val().toString());
            }


            $('#filters').submit();
        });

    })
    ;
</script>
</body>
