<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>

    <div class="content__inner">

        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-muted">Ordem de Compra: <?php echo $oc['Cd_Ordem_Compra']; ?></h5>
                        <p><strong>Situação: <?php echo $oc['situacao']; ?></strong></p>
                    </div>
                </div>
            </div>
            <div class="col-6 text-center">
                <div class="card">
                    <div class="card-body">
                        <h5><?php if (isset($oc['comprador'])) echo $oc['comprador']['razao_social']; ?></h5>
                        <p>Cotação: <?php echo $oc['Cd_Cotacao'] ?> |
                            Nome Aprovacdor: <?php echo $oc['Nm_Aprovador']; ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <p class="card-title">Dados de Faturamento</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                Empresa: <?php if (isset($oc['comprador'])) echo $oc['comprador']['razao_social']; ?> <br>
                                CNPJ: <?php if (isset($oc['comprador'])) echo $oc['comprador']['cnpj']; ?> <br>
                                E-mail: <?php if (isset($oc['comprador'])) echo $oc['comprador']['email']; ?> <br>
                            </div>
                            <div class="col-6">
                                Condição de Pagamento: <?php echo isset($oc['condicao_pagamento']) ? $oc['condicao_pagamento'] : 'Não informado' ?> <br>
                                Data de Entrega: <?php echo isset($oc['Dt_Previsao_Entrega']) ? date('d/m/Y', strtotime($oc['Dt_Previsao_Entrega'])) : 'Não informado' ?> <br>
                                Tipo de Frete: <?php echo isset($oc['Tp_Frete']) ? $oc['Tp_Frete'] : 'Não informado' ?> <br>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <p class="card-title">Dados de Entrega</p>
                    </div>
                    <div class="card-body">

                        <?php if (isset($oc['Nm_Logradouro'])) echo str_replace('"', '', $oc['Nm_Logradouro']); ?>,  <?php if (isset($oc['Ds_Complemento_Logradouro'])) echo str_replace('"', '', $oc['Ds_Complemento_Logradouro']); ?> <br>

                        <?php if (isset($oc['Nm_Bairro'])) echo str_replace('"', '', $oc['Nm_Bairro']); ?>, <?php if (isset($oc['Nm_Cidade'])) echo str_replace('"', '', $oc['Nm_Cidade']); ?>, <?php if (isset($oc['Id_Unidade_Federativa'])) echo str_replace('"', '', $oc['Id_Unidade_Federativa']); ?> <br>
                        CEP: <?php if (isset($oc['Nr_Cep'])) echo str_replace('"', '', $oc['Nr_Cep']); ?> <br>
                        Telefones: <?php foreach ($oc['Telefones_Ordem_Compra'] as $telefone){
                             if (!is_array($telefone["Nr_Telefone"])) echo $telefone["Nr_Telefone"] . ',';
                        } ?>

                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <p class="card-title">Produtos</p>
            </div>
            <div class="card-body">
                <div class="table-responsive col-sm">
                    <table id="data-table" class="table table-condensend table-hover">
                        <thead>
                        <tr>
                            <th>Código</th>
                            <th>Produto</th>
                            <th>Marca</th>
                            <th>Unidade</th>
                            <th>Qtd Embalagem</th>
                            <th>Qtd Produto</th>
                            <th>Preço (R$)</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($oc['produtos'])) { ?>
                                <?php foreach($oc['produtos'] as $kk => $produto) { ?>
                                    <tr>
                                        <td><?php echo $produto['codigo']; ?></td>
                                        <td><?php echo $produto['Ds_Produto_Comprador']; ?></td>
                                        <td><?php echo $produto['Ds_Marca']; ?></td>
                                        <td><?php echo $produto['Ds_Unidade_Compra']; ?></td>
                                        <td><?php echo $produto['Qt_Embalagem']; ?></td>
                                        <td><?php echo $produto['Qt_Produto']; ?></td>
                                        <td><?php echo number_format($produto['Vl_Preco_Produto'], 4, ',', '.') ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>

                                <tr><td colspan="8">Nenhum registro encontrado</td></tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<?php echo $scripts; ?>
<script>

    $(function () {

        var table = $('#data-table').DataTable({
            processing: true,
            serverSide: false,
            lengthChange: false,
            "order": [[ 1, "asc" ]],
            rowCallback: function (row, data) {},
            drawCallback: function () {}
        });
    });
</script>

</html>
