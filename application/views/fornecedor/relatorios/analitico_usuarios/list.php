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

            <div class="card">
                <div class="card-body">
                    <table id="data-table" class="table table-striped">
                        <thead>
                        <tr>
                            <th>COTAÇÃO</th>
                            <th>COMPRADOR</th>
                            <th>UF</th>
                            <th>DATA</th>
                            <th>PORTAL</th>
                            <th>CONVERTIDO</th>
                            <th>VALOR CONVERTIDO</th>
                        </tr>
                        </thead>
                        <?php foreach ($dados as $dado) { ?>
                            <tr>
                                <td><?php echo $dado['cd_cotacao']; ?></td>
                                <td><?php echo $dado['razao_social']; ?></td>
                                <td><?php echo $dado['estado_comprador']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($dado['data_criacao'])); ?></td>
                                <td><?php echo $dado['integrador']; ?></td>
                                <td><?php echo $dado['CONVERTIDO']; ?></td>
                                <td><?php echo (!empty($dado['total_vendido'])) ? "R$: " . $dado['total_vendido'] : "R$ 0,00"; ?></td>
                            </tr>
                        <?php } ?>
                    </table>

                </div>
            </div>
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
        $('#data-table').DataTable({
            processing: true,
            serverSide: false,
            lengthChange: false,
            responsive: true,
        });
    })
    ;
</script>
</body>
