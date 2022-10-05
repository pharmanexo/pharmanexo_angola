<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">

    <div class="content-fluid">
        <div class="content__inner">
            <div class="row">

                <!-- Relatorio cotações automaticas -->
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <h4 class="card-title">Ultimos registros de cotação automática</h4>
                            </h5>
                        </div>
                        <div class="card-body" style="height: 450px; overflow: scroll; ">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Fornecedor</th>
                                        <th>Data</th>
                                    </tr>
                                </thead>
                               <tbody>
                                    <?php foreach($relatorioCotacaoAutomatico as $row) { ?>

                                        <?php $ultima_atualizacao = (isset($row['data_criacao']) && !empty($row['data_criacao'])) ? date('Y-m-d', strtotime($row['data_criacao'])) : '' ?>
                                        <?php $resultado = strtotime(date('Y-m-d')) - strtotime($ultima_atualizacao); ?>

                                        <tr class="<?php echo (floor($resultado / (60 * 60 * 24)) == 0) ? '' : 'table-danger' ?>">
                                            <td><?php echo $row['nome_fantasia']; ?></td>
                                            <td><?php echo date('d/m/Y H:i:s', strtotime($row['data_criacao'])) ?></td>
                                        </tr>
                                    <?php } ?>
                               </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Relatorio cotaçoes manuais -->
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-header" >
                            <h5 class="mb-0">
                                <h4 class="card-title">Ultimos registros de cotação manual</h4>
                            </h5>
                        </div>
                        <div class="card-body" style="height: 450px; overflow: scroll; ">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Fornecedor</th>
                                    <th>Data</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($relatorioCotacaoManual as $row) { ?>

                                    <?php $ultima_atualizacao = (isset($row['data_criacao']) && !empty($row['data_criacao'])) ? date('Y-m-d', strtotime($row['data_criacao'])) : '' ?>
                                    <?php $resultado = strtotime(date('Y-m-d')) - strtotime($ultima_atualizacao); ?>

                                    <tr class="<?php echo (floor($resultado / (60 * 60 * 24)) == 0) ? '' : 'table-danger' ?>">
                                        <td><?php echo $row['nome_fantasia']; ?></td>
                                        <td><?php echo date('d/m/Y H:i:s', strtotime($row['data_criacao'])) ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">

                <!-- Relatorio de estoque por fornecedor-->
                <div class="col-12 col-lg-6">
                    <div class="card">

                        <div class="card-header">
                            <h5 class="mb-0">
                                <h4 class="card-title">Ultima atualização de estoque por fornecedor</h4>
                            </h5>
                        </div>
                        <div class="card-body" style="height: 450px; overflow: scroll; ">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Fornecedor</th>
                                    <th>Registrado em</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (isset($relatorioEstoque)) { ?>
                                    <?php foreach ($relatorioEstoque as $row) { ?>

                                        <?php $ultima_atualizacao = (isset($row['termino_atualizacao_estoque']) && !empty($row['termino_atualizacao_estoque'])) ? date('Y-m-d', strtotime($row['termino_atualizacao_estoque'])) : '' ?>
                                        <?php $resultado = strtotime(date('Y-m-d')) - strtotime($ultima_atualizacao); ?>

                                        <tr class="<?php echo (floor($resultado / (60 * 60 * 24)) == 0) ? '' : 'table-danger' ?>">
                                            <td>
                                                <small><?php echo $row['nome_fantasia'] ?></small>
                                            </td>
                                            <td class="text-nowrap">
                                                <?php $data = (isset($row['termino_atualizacao_estoque']) && !empty($row['termino_atualizacao_estoque'])) ? date('d/m/Y H:i:s', strtotime($row['termino_atualizacao_estoque'])) : '' ?>
                                                <small><?php echo $data ?></small>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="3">Nenhum registro encontrado</td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Relatorio de preço por fornecedor -->
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <h4 class="card-title">Ultima atualização de preço por fornecedor</h4>
                            </h5>
                        </div>
                        <div class="card-body" style="height: 450px; overflow: scroll; ">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Fornecedor</th>
                                    <th>Registrado em</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($relatorioPreco as $row): ?>

                                    <?php if ( isset($row['data_criacao']) ): ?>

                                        <?php $ultima_atualizacao = (isset($row['data_criacao']) && !empty($row['data_criacao'])) ? date('Y-m-d', strtotime($row['data_criacao'])) : '' ?>
                                        <?php $resultado = strtotime(date('Y-m-d')) - strtotime($ultima_atualizacao); ?>

                                        <tr class="<?php echo (floor($resultado / (60 * 60 * 24)) == 0) ? '' : 'table-danger' ?>">
                                            <td><small><?php echo $row['nome_fantasia']; ?></small></td>
                                            <td><small><?php echo date('d/m/Y H:i:s', strtotime($row['data_criacao'])) ?></small></td>
                                        </tr>
                                    <?php else: ?>

                                        <tr class="table-danger">
                                            <td><small><?php echo $row['nome_fantasia']; ?></small></td>
                                            <td><small>Sem preço cadastrado</small></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">

                <!-- Relatorio cotacoes sintese -->
                <div class="col-12 col-lg-6">
                    <div class="card">

                        <div class="card-header">
                            <h5 class="mb-0">
                                <h4 class="card-title">Ultima atualização de cotações por fornecedor</h4>
                            </h5>
                        </div>
                        <div class="card-body" style="height: 450px; overflow: scroll; ">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Fornecedor</th>
                                    <th>Ultimo registro em</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (isset($relatorioCotacaoSintese)) { ?>
                                    <?php foreach ($relatorioCotacaoSintese as $row) { ?>

                                        <?php $ultima_atualizacao = (isset($row['data_criacao']) && !empty($row['data_criacao'])) ? date('Y-m-d', strtotime($row['data_criacao'])) : '' ?>
                                        <?php $resultado = strtotime(date('Y-m-d')) - strtotime($ultima_atualizacao); ?>

                                        <tr class="<?php echo (floor($resultado / (60 * 60 * 24)) == 0) ? '' : 'table-danger' ?>">
                                            <td>
                                                <small><?php echo $row['nome_fantasia'] ?></small>
                                            </td>
                                            <td class="text-nowrap">
                                                <?php $data = (isset($row['data_criacao']) && !empty($row['data_criacao'])) ? date('d/m/Y H:i:s', strtotime($row['data_criacao'])) : '' ?>
                                                <small><?php echo $data ?></small>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>


<?php echo $scripts; ?>

<script>
    $(function() {
        setTimeout(function() {
            window.location.reload(1);
        }, 60000); // 3 minutos
        
    });
</script>

</html>
