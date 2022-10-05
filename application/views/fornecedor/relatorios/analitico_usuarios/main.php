<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <div class="container">
        <?php if (isset($heading)) echo $heading; ?>
        <div class="card">
            <div class="card-header">
                <form action="<?php echo $form_action; ?>" method="post" id="form_s">

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Período</label>
                                <div class="input-group mb-3">
                                    <input type="date" class="form-control"
                                           value="<?php echo (isset($post['dataini'])) ? $post['dataini'] : date('Y-m-01', time()); ?>"
                                           name="dataini" id="dataini">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">a</span>
                                    </div>
                                    <input type="date" class="form-control"
                                           value="<?php echo (isset($post['datafim'])) ? $post['datafim'] :  date('Y-m-d', time()); ?>"
                                           name="datafim" id="datafim">
                                </div>
                            </div>
                        </div>
                        <div class="col-6 text-center align-items-center">
                            <?php if (isset($post['dataini']) && isset($post['datafim'])) { ?>
                                <p>Mostrando registros no
                                    período <?php echo date('d/m/Y', strtotime($post['dataini'])) . " a " . date('d/m/Y', strtotime($post['datafim'])) ?></p>
                            <?php }else{ ?>
                                <p>Mostrando registros no
                                    período <?php echo date('01/m/Y', time()) . " a " . date('d/m/Y', time()) ?></p>
                            <?php } ?>
                            <a href="<?php echo base_url('fornecedor/relatorios/analitico_usuarios/limparFiltros'); ?>" class="btn btn-outline-primary">Limpar filtro</a>
                        </div>
                    </div>

                </form>
            </div>
            <div class="card-body">
                <?php if (!empty($dados)) { ?>
                    <?php foreach ($dados as $k => $dado) { ?>
                        <table class="table table-striped table-condensed">
                            <tr>
                                <td colspan="6"><a data-btnDetalhes="true"
                                            href="<?php echo $url_detalhes . $k; ?>"><?php echo $dado['nome']; ?></a>
                                </td>
                            </tr>
                            <tr>
                                <th>Cotações Respondidas</th>
                                <th>Itens Respondidos</th>
                                <th>Cotações Convertidas</th>
                                <th>Itens Convertidos</th>
                                <th>Total Convertido (R$)</th>
                                <th>Estados Atendidos</th>
                            </tr>
                            <tr>
                                <td class="text-center"><?php echo $dado['qtd_cotacoes']; ?></td>
                                <td class="text-center"><?php echo $dado['qtd_itens_ofertados']; ?></td>
                                <td class="text-center"><?php echo $dado['qtd_pedidos_convertidos']; ?></td>
                                <td class="text-center"><?php echo $dado['qtd_itens_convertidos']; ?></td>
                                <td class="text-right"><?php echo number_format($dado['total_vendido'], 2, ',', '.'); ?></td>
                                <td><?php if (!empty($dado['estados'])) {
                                        foreach ($dado['estados'] as $estado) {
                                            echo $estado . ",";
                                        }
                                    } ?></td>
                            </tr>
                        </table>
                    <?php } ?>
                <?php } else { ?> class="text-center"
                    <table>
                        <tr>
                            <td colspan="6">Não foram encontrados registros</td>
                        </tr>
                    </table>
                <?php } ?>

            </div>
        </div>
    </div>

</div>

<?php echo $scripts; ?>

<script>
    var url;
    $(function () {

        $('#datafim').change(function (e) {
            e.preventDefault();

            $('#form_s').submit();

        });


        $('#btnExport').click(function (e) {
            e.preventDefault();

            $('#form_s').prop('action', $(this).attr('href')).submit();

        });

        $('[data-btnDetalhes]').click(function (e){
            e.preventDefault();
            var dataini = $('#dataini').val();
            var datafim = $('#datafim').val();

            window.location = $(this).attr('href') + `?dataini=${dataini}&datafim=${datafim}`;

        });

    })
    ;
</script>
</body>
