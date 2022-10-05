<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>
    <div class="content__inner">

        <div class="card">
            <div class="card-body">
                <p class="text-muted border-bottom"><strong>Dados do Cliente</strong></p>
                <div class="row">
                    <div class="col-12 col-lg-4">
                        CNPJ <br>
                        <?php if (isset($row['cnpj'])) echo $row['cnpj']; ?>
                    </div>
                    <div class="col-12 col-lg-4">
                        Razão Social <br>
                        <?php if (isset($row['razao_social'])) echo $row['razao_social']; ?>
                    </div>
                    <div class="col-12 col-lg-4">
                        Cidade/UF <br>
                        <?php if (isset($row['cidade'])) echo $row['cidade']; ?>/<?php if (isset($row['estado'])) echo $row['estado']; ?>
                    </div>
                </div>
                <p class="text-muted mt-3 border-bottom"><strong>Dados da Compra</strong></p>
                <div class="row">
                    <div class="col-12 col-lg-4">
                        Data da Compra <br>
                        <?php if (isset($row['data_emissao'])) echo date("d/m/Y", strtotime($row['data_emissao'])); ?>
                    </div>
                    <div class="col-12 col-lg-4">
                        Condições de Pagamento <br>
                        <?php if (isset($row['condicao_pagamento'])) echo $row['condicao_pagamento']; ?>
                    </div>
                    <div class="col-12 col-lg-4">
                        Valor Total <br>
                        R$ <?php if (isset($row['valor_total'])) echo number_format($row['valor_total'], 2, ',', '.') ?>
                    </div>
                </div>
                <table class="table w-100 table-striped mt-5">
                    <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descrição</th>
                        <th>Quantidade</th>
                        <th>Valor Unit.</th>
                        <th>Valor Total</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php if (isset($produtos) && !empty($produtos)) { ?>
                        <?php foreach ($produtos as $produto) { ?>
                            <tr>
                                <td><?php echo $produto['codigo']; ?></td>
                                <td><?php echo $produto['produto_descricao']; ?></td>
                                <td><?php echo $produto['qtd_solicitada']; ?></td>
                                <td><?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                                <td><?php echo number_format($produto['total'], 2, '',''); ?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                </table>

            </div>
        </div>

    </div>
</div>
<?php echo $scripts; ?>
</body>

</html>