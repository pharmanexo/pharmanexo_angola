<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <div class="container">
        <?php if (isset($heading)) echo $heading; ?>

        <?php if (!empty($dados)) { ?>
            <?php foreach ($dados as $k => $dado) { ?>
                <div class="card">
                    <div class="card-header">
                        <?php echo $k; ?>
                    </div>
                    <div class="card-body">

                        <table id="data-table" class="table table-striped table-condensed">
                            <tr>
                                <th>Cotações Respondidas</th>
                                <th>Itens Respondidos</th>
                                <th>Cotações Convertidas</th>
                                <th>Itens Convertidos</th>
                                <th>Total Convertido (R$)</th>
                                <th>Estados Atendidos</th>
                            </tr>
                            <?php foreach ($dado as $k => $item) { ?>
                                <tr>
                                    <td class="text-center"><a href="<?php echo $url_detalhes . "/" . $post['usuario'] . "?dataini={$post['dataini']}&datafim={$post['datafim']}&uf={$item['estado']}"; ?>"><?php echo $item['qtd_cotacoes']; ?></a></td>
                                    <td class="text-center"><?php echo $item['qtd_itens_ofertados']; ?></td>
                                    <td class="text-center"><?php echo $item['qtd_pedidos_convertidos']; ?></td>
                                    <td class="text-center"><?php echo $item['qtd_itens_convertidos']; ?></td>
                                    <td class="text-right"><?php echo number_format($item['total_vendido'], 2, ',', '.'); ?></td>
                                    <td><?php echo $item['estado']; ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <table>
                <tr>
                    <td colspan="6">Não foram encontrados registros</td>
                </tr>
            </table>
        <?php } ?>
    </div>
</div>

<?php echo $scripts; ?>

<script>
    var url;
    $(function () {
        var table = $('#data-table').DataTable({
            "processing": true,
            "serverSide": false,
            "bFilter": false,
            lengthChange: false,
            pageLength: 100
        });
    })
    ;
</script>
</body>
