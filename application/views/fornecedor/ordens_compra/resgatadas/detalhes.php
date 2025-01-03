<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>

    <div class="content__inner">
        <?php if (isset($oc['prioridade']) && $oc['prioridade'] == 1) { ?>
            <div class="row mb-3">
                <div class="col-12 bg-warning text-center">
                    <p><strong>ORDEM DE COMPRA URGENTE!</strong></p>
                </div>
            </div>
        <?php } ?>
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class=" <?php echo ($oc['cancelada']) ? 'text-danger' : 'text-muted'; ?>">Ordem de
                            Compra: <?php echo $oc['Cd_Ordem_Compra']; ?></h5>
                        <p><strong>Situação: <?php echo $oc['situacao']; ?></strong></p>
                        <?php if ($oc['cancelada']) { ?>
                            <p><strong>Motivo/Responsável: <?php echo $oc['motivo_cancelamento']; ?></strong></p>
                        <?php } ?>
                        <p><strong>Resgatada
                                por:</strong> <?php if (isset($usuario_resgate)) echo $usuario_resgate['nome']; ?>
                            em <?php if (isset($oc['data_resgate'])) echo date("d/m/Y H:i", strtotime($oc['data_resgate'])); ?>
                        </p>
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
                                Empresa: <?php if (isset($oc['comprador'])) echo $oc['comprador']['razao_social']; ?>
                                <br>
                                CNPJ: <?php if (isset($oc['comprador'])) echo $oc['comprador']['cnpj']; ?> <br>
                                E-mail: <?php if (isset($oc['comprador'])) echo $oc['comprador']['email']; ?> <br>
                            </div>
                            <div class="col-6">
                                Condição de Pagamento: <?php echo isset($oc['fp']) ? $oc['fp'] : 'Não informado' ?> <br>
                                Data de
                                Entrega: <?php echo isset($oc['Dt_Previsao_Entrega']) ? date('d/m/Y', strtotime($oc['Dt_Previsao_Entrega'])) : 'Não informado' ?>
                                <br>
                                Tipo de Frete: <?php echo isset($oc['Tp_Frete']) ? $oc['Tp_Frete'] : 'Não informado' ?>
                                <br>
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

                    <?php if (!empty($oc['endereco_entrega'])) { ?>
                        <div class="card-body">
                            Endereço de entrega: <?php echo $oc['endereco_entrega']; ?>
                        </div>
                    <?php } else { ?>
                        <div class="card-body">

                            <?php if (isset($oc['Nm_Logradouro'])) echo str_replace('"', '', $oc['Nm_Logradouro']); ?>
                            , <?php if (isset($oc['Ds_Complemento_Logradouro'])) echo str_replace('"', '', $oc['Ds_Complemento_Logradouro']); ?>
                            <br>

                            <?php if (isset($oc['Nm_Bairro'])) echo str_replace('"', '', $oc['Nm_Bairro']); ?>
                            , <?php if (isset($oc['Nm_Cidade'])) echo str_replace('"', '', $oc['Nm_Cidade']); ?>
                            , <?php if (isset($oc['Id_Unidade_Federativa'])) echo str_replace('"', '', $oc['Id_Unidade_Federativa']); ?>
                            <br>
                            CEP: <?php if (isset($oc['Nr_Cep'])) echo str_replace('"', '', $oc['Nr_Cep']); ?> <br>
                            <?php if (!empty($oc['Telefones_Ordem_Compra']) && is_array($oc['Telefones_Ordem_Compra'])) { ?>
                                Telefones: <?php foreach ($oc['Telefones_Ordem_Compra'] as $telefone) {
                                    if (!is_array($telefone["Nr_Telefone"])) echo $telefone["Nr_Telefone"] . ',';
                                } ?>
                            <?php } else { ?>
                                Telefones:  <?php echo $oc['Telefones_Ordem_Compra']; ?>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <p class="card-title"><strong>Observações</strong></p>
            </div>
            <div class="card-body">
                <?php if (isset($oc['Ds_Observacao'])) echo $oc['Ds_Observacao']; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <p class="card-title">Produtos</p>
            </div>
        </div>
        <?php if (isset($oc['produtos'])) { ?>
            <?php foreach ($oc['produtos'] as $kk => $produto) { ?>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-2">

                                <p><strong>Código</strong> <br> <?php echo $produto['codigo']; ?></p>

                            </div>
                            <div class="col-4">
                                <p><strong>Produto Comprador</strong>
                                    <br> <?php echo $produto['Ds_Produto_Comprador']; ?></p>
                            </div>
                            <div class="col-4">
                                <p><strong>Produto Catálogo</strong> <br> <?php echo (isset($produto['produto_catalogo'])) ? $produto['produto_catalogo'] : 'Sem vinculo catalogo'; ?>
                                </p>
                            </div>
                            <div class="col-2">
                                <p><strong>Marca</strong> <br> <?php echo $produto['Ds_Marca']; ?></p>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-4">
                                <p><strong>Obs Produto Cotação</strong>
                                    <br> <?php if (isset($produto['obs_cot_produto'])) echo $produto['obs_cot_produto']; ?></p>
                            </div>
                            <div class="col-2">

                                <p><strong>Und Compra</strong> <br> <?php echo $produto['Ds_Unidade_Compra']; ?></p>

                            </div>
                            <div class="col-2">

                                <p><strong>Qtd. Embalagem: </strong><?php echo $produto['Qt_Embalagem']; ?> <br>
                                    <strong>Qtd Solicitada: </strong><?php echo $produto['Qt_Produto']; ?>
                                </p>
                            </div>
                            <div class="col-2">
                                <p><strong>Preço</strong> <br> <?php echo number_format($produto['Vl_Preco_Produto'], 4, ',', '.') ?></p>
                            </div>
                            <div class="col-2">

                                <p class="text-success"><strong>Total</strong> <br> <?php echo number_format(($produto['Vl_Preco_Produto'] * $produto['Qt_Produto']), 4, ',', '.') ?></p>

                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>

        <div class="card">
            <div class="card-header">
                <p class="card-title">
                    <div class="row">
                    <div class="col-6">
                       <h2>
                           <strong>Total do Pedido</strong>
                       </h2>
                    </div>
                    <div class="col-6 text-right">
                        <h2>
                            <strong><?php if (isset($oc['total'])) echo number_format($oc['total'], "4", ',', '.'); ?></strong>
                        </h2>
                    </div>
                </div>
                </p>
            </div>
        </div>

        <div class="card" hidden>
            <div class="card-header">
                <p class="card-title">Produtos</p>
            </div>
            <div class="card-body" hidden>
                <div class="table-responsive col-sm">
                    <table id="data-table" class="table table-condensend table-hover">
                        <thead>
                        <tr>
                            <th>Código</th>
                            <th>Produto</th>
                            <th>Produto Catálogo</th>
                            <th>Marca</th>
                            <th>Unidade</th>
                            <th>Qtd Embalagem</th>
                            <th>Qtd Produto</th>
                            <th>Preço (R$)</th>
                            <th>Entrega</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (isset($oc['produtos'])) { ?>
                            <?php foreach ($oc['produtos'] as $kk => $produto) { ?>
                                <tr>
                                    <td><?php echo $produto['codigo']; ?></td>
                                    <td><?php echo $produto['Ds_Produto_Comprador']; ?>

                                        <?php if (isset($produto['obs_cot_produto'])) { ?>
                                            <i class="fa fa-info" data-toggle="tooltip"
                                               title="<?php echo $produto['obs_cot_produto']; ?>"></i>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo (isset($produto['produto_catalogo'])) ? $produto['produto_catalogo'] : ''; ?></td>
                                    <td><?php echo $produto['Ds_Marca']; ?></td>
                                    <td><?php echo $produto['Ds_Unidade_Compra']; ?></td>
                                    <td><?php echo $produto['Qt_Embalagem']; ?></td>
                                    <td><?php echo $produto['Qt_Produto']; ?></td>
                                    <td><?php echo number_format($produto['Vl_Preco_Produto'], 4, ',', '.') ?></td>
                                    <td><?php if (isset($produto['programacao']['Data'])) echo $produto['programacao']['Data']; ?>
                                        <?php if (isset($produto['programacao']['Quantidade'])) echo 'Qtd.: ' . $produto['programacao']['Quantidade'] ?></td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>

                            <tr>
                                <td colspan="8">Nenhum registro encontrado</td>
                            </tr>
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
            "order": [[1, "asc"]],
            rowCallback: function (row, data) {
            },
            drawCallback: function () {
            }
        });

        $('#btnCancel').on('click', function (e) {
            e.preventDefault();
            let me = $(this);

            $.ajax({
                url: me.attr('href'),
                type: 'get',
                dataType: 'html',

                success: function (response) {
                    $('body').append(response);
                    $('.modal').modal({
                        keyboard: false
                    }, 'show').on('hide.bs.modal', function () {
                        $('.modal').remove();
                    });
                }
            })
        });
    });
</script>

</html>
