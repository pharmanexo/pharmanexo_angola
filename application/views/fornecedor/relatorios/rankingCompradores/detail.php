<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>
    <div class="content__inner" id="printAll">

        <div class="card">
            <div class="card-body">
                <p class="text-muted border-bottom"><strong>Dados do Cliente</strong></p>
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <strong>CNPJ</strong> <br>
                        <?php if (isset($row['cnpj'])) echo $row['cnpj']; ?>
                    </div>
                    <div class="col-12 col-lg-4">
                        <strong>Razão Social</strong> <br>
                        <?php if (isset($row['razao_social'])) echo $row['razao_social']; ?>
                    </div>
                    <div class="col-12 col-lg-4">
                        <strong>Cidade/UF</strong> <br>
                        <?php if (isset($row['cidade'])) echo $row['cidade']; ?>/<?php if (isset($row['uf'])) echo $row['uf']; ?>
                    </div>
                </div>
                <p class="text-muted mt-3 border-bottom"><strong>Dados da Compra</strong></p>
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <strong>Data da Compra</strong> <br>
                        <?php if (isset($row['data_criacao'])) echo date("d/m/Y", strtotime($row['data_criacao'])); ?>
                    </div>
                    <div class="col-12 col-lg-4">
                        <strong>Condições de Pagamento</strong> <br>
                        <?php echo (isset($row['condicao_pagamento']))  ? $row['condicao_pagamento'] : 'Não informado'; ?>
                    </div>
                    <div class="col-12 col-lg-4">
                        <strong>Valor Total</strong> <br>
                        R$ <?php if (isset($row['total'])) echo number_format($row['total'], 2, ',', '.') ?>
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
                                <td><?php echo $produto['quantidade']; ?></td>
                                <td><?php echo number_format($produto['valor'], 2, ',', '.'); ?></td>
                                <td><?php echo number_format($produto['total'], 2, ',', '.'); ?></td>
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
<script>
    $(function () {
        $('#btnPrintAll').click(function (e) {
            e.preventDefault();
            console.log('ola');
            window.open().document.write(content);

        });
    })
</script>
</body>

</html>