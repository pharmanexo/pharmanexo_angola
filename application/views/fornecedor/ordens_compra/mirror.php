<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>

    <div class="content__inner">

        <div class="printPage">
            <?php if (isset($ordem_compra['prioridade']) && $ordem_compra['prioridade'] == 1) { ?>
                <div class="row mb-3">
                    <div class="col-12 bg-warning text-center">
                        <p><strong>ORDEM DE COMPRA URGENTE!</strong></p>
                    </div>
                </div>
            <?php } ?>
            <div class="row">
                <div class="col-6 text-center">
                    <h5><b>Ordem de Compra:</b> <?php echo $ordem_compra['Cd_Ordem_Compra']; ?></h5>
                    <h5><b>Situação: </b><?php echo $ordem_compra['situacao']; ?></h5>
                </div>
                <div class="col-6 text-center">
                    <h6><?php echo $ordem_compra['comprador']['nome_fantasia']; ?></h6>
                    <h6><b>Razão Social: </b><?php echo $ordem_compra['comprador']['razao_social']; ?></h6>
                    <h6><b>Cotação: </b><?php echo $ordem_compra['Cd_Cotacao']; ?></h6>
                    <h6><b>Pedido ERP: </b><?php echo $ordem_compra['transaction_id']; ?></h6>
                    <h6>
                        <b>Requisitante: </b> <?php echo $ordem_compra['Nm_Aprovador']; ?>

                    </h6>
                </div>
            </div>

            <div class="card border-secondary mt-3">
                <div class="card-header border-secondary">
                    <p class="card-title">Dados do Faturamento</p>
                </div>
                <div class="card-body border-secondary p-1">

                    <div class="d-flex">
                        <div class="p-2"><b>Empresa: </b><?php echo $ordem_compra['comprador']['razao_social']; ?></div>
                    </div>
                    <div class="d-flex">
                        <div class="p-2"><b>CNPJ: <?php echo $ordem_compra['comprador']['cnpj']; ?></b></div>
                    </div>
                    <div class="d-flex">
                        <div class="p-2"><b>E-mail: </b><?php echo $ordem_compra['comprador']['email']; ?></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="p-2">
                            <?php if (!empty($ordem_compra['Dt_Previsao_Entrega'])) { ?>
                                <b>Data
                                    Entrega: </b> <?php echo date("d/m/Y", strtotime($ordem_compra['Dt_Previsao_Entrega'])); ?>
                            <?php } else { ?>
                                <b>Data Entrega: </b> Consulte nos produtos
                            <?php } ?>
                        </div>
                        <div class="p-2"><b>Cond.
                                Pagto: </b><?php if (isset($ordem_compra['form_pagamento'])) echo $ordem_compra['form_pagamento']; ?>
                        </div>
                        <div class="p-2"><b>Tipo Frete: </b>CIF</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="card border-secondary">
                        <div class="card-header border-secondary">
                            <p class="card-title">Endereço de Entrega</p>
                        </div>
                        <?php if (empty($ordem_compra['endereco_entrega'])) { ?>
                            <div class="card-body p-1">

                                <div class="d-flex">
                                    <div class="p-2">
                                        <small>
                                            <?php if (isset($ordem_compra['Nm_Logradouro']) && !empty($ordem_compra['Nm_Logradouro'])) echo $ordem_compra['Nm_Logradouro'] . ' - ' ?>

                                            <?php if (isset($ordem_compra['Ds_Complemento_Logradouro']) && !empty($ordem_compra['Ds_Complemento_Logradouro'])) echo $ordem_compra['Ds_Complemento_Logradouro'] . ' - ' ?>

                                            <?php if (isset($ordem_compra['Nm_Bairro']) && !empty($ordem_compra['Nm_Bairro'])) echo $ordem_compra['Nm_Bairro'] . ' - ' ?>

                                            <?php if (isset($ordem_compra['Nm_Cidade']) && !empty($ordem_compra['Nm_Cidade'])) echo $ordem_compra['Nm_Cidade'] . ' - ' ?>

                                            <?php if (isset($ordem_compra['Id_Unidade_Federativa']) && !empty($ordem_compra['Id_Unidade_Federativa'])) echo $ordem_compra['Id_Unidade_Federativa'] ?>
                                        </small>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="p-2">
                                        <b>CEP: </b><?php if (isset($ordem_compra['Nr_Cep']) && !empty($ordem_compra['Nr_Cep'])) echo $ordem_compra['Nr_Cep']; ?>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="card-body p-1">
                                <div class="d-flex">
                                    <p class="py-3"><?php echo $ordem_compra['endereco_entrega']; ?></p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>


                    <div class="card border-secondary">
                        <div class="card-header border-secondary">
                            <p class="card-title">Observações</p>
                        </div>
                        <div class="card-body border-secondary p-1">
                            <small><?php echo $ordem_compra['Ds_Observacao']; ?></small>

                            <?php if (!empty($ordem_compra['termos'])) { ?>
                                <br>
                                <br>
                                <p><strong>Termos</strong></p>
                                <small><?php echo $ordem_compra['termos']; ?></small>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="card border-secondary">
                        <div class="card-header border-secondary">
                            <p class="card-title">Dados do Fornecedor</p>
                        </div>
                        <div class="card-body  p-1">
                            <div class="d-flex">
                                <div class="p-2"><?php echo $fornecedor['nome_fantasia']; ?></div>
                            </div>
                            <div class="d-flex">
                                <div class="p-2"><b>Razão Social: </b><?php echo $fornecedor['razao_social']; ?></div>
                            </div>
                            <div class="d-flex">
                                <div class="p-2"><b>CNPJ: </b><?php echo $fornecedor['cnpj']; ?></div>
                            </div>
                            <div class="d-flex">
                                <div class="p-2"><b>End.: </b> <small>
                                        <?php if (isset($fornecedor['endereco']) && !empty($fornecedor['endereco'])) echo $fornecedor['endereco'] . ' - '; ?><?php if (isset($fornecedor['numero']) && !empty($fornecedor['numero'])) echo $fornecedor['numero'] . ' - '; ?><?php if (isset($fornecedor['bairro']) && !empty($fornecedor['bairro'])) echo $fornecedor['bairro'] . ' - '; ?><?php if (isset($fornecedor['cidade']) && !empty($fornecedor['cidade'])) echo $fornecedor['cidade'] . ' - '; ?><?php if (isset($fornecedor['estado']) && !empty($fornecedor['estado'])) echo $fornecedor['estado']; ?>
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="p-2">
                                    <b>CEP: </b><?php if (isset($fornecedor['cep'])) echo $fornecedor['cep']; ?></div>
                            </div>
                            <div class="d-flex">
                                <div class="p-2">
                                    <b>Fone: </b><?php if (isset($fornecedor['telefone'])) echo $fornecedor['telefone']; ?>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="p-2">
                                    <b>Prazo de Entrega
                                        ofertada: </b><?php echo isset($ordem_compra['oferta']['prazo_entrega']) ? $ordem_compra['oferta']['prazo_entrega'] . " dias" : 'Não informado'; ?>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="p-2">
                                    <b>Valor do Faturamento
                                        Mínimo: <?php echo isset($ordem_compra['oferta']['valor_minimo']) ? $ordem_compra['oferta']['valor_minimo'] : 'Não informado'; ?></b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card ">
                <div class="card-header ">
                    <p class="card-title">Produtos da Ordem de Compra</p>
                </div>
                <div class="card-body p-1">
                    <div class="table-responsive col-sm">
                        <table class="table table-sm table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Produto Catálogo</th>
                                <th>Produto</th>
                                <th>Cód. Fornecedor</th>
                                <th>Marca</th>
                                <th>Embalagem</th>
                                <th>Qtd.</th>
                                <th>Preço (R$)</th>
                                <th>Total (R$)</th>
                                <th>Entrega</th>
                                <th>Situacao</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($ordem_compra['produtos'] as $oc): ?>
                                <tr class="<?php if ($oc['situacao'] == 9) echo 'table-danger'; ?>" >
                                    <td><?php echo (isset($oc['produto_catalogo'])) ? $oc['produto_catalogo'] : ''; ?></td>
                                    <td><?php echo $oc['Ds_Produto_Comprador']; ?></td>
                                    <td><?php echo $oc['codigo']; ?></td>
                                    <td><?php echo $oc['Ds_Marca']; ?></td>
                                    <td><?php echo $oc['Ds_Unidade_Compra']; ?></td>
                                    <td><?php echo $oc['Qt_Produto']; ?></td>
                                    <td><?php echo number_format($oc['Vl_Preco_Produto'], 4, ',', '.'); ?></td>
                                    <td><?php echo number_format($oc['Vl_Preco_Produto'] * $oc['Qt_Produto'], 4, ',', '.'); ?></td>
                                    <td><?php if (isset($oc['programacao']['Data'])) echo $oc['programacao']['Data']; ?>
                                        <?php if (isset($oc['programacao']['Quantidade'])) echo 'Qtd.: ' . intval($oc['programacao']['Quantidade']) ?></td>
                                    <td><?php echo ($oc['situacao'] == 9 ) ? 'Rejeitado' : 'Aprovado' ?></td>
                                </tr>
                                <?php if (isset($oc['obs_cot_produto'])) { ?>
                                    <tr>
                                        <td colspan="9"><strong>Obs produto cotação:</strong> <?php echo $oc['obs_cot_produto']; ?></td>
                                    </tr>
                                <?php } ?>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <hr>

            <div class="card">
                <div class="card-body">
                    <h4 class="text-right">Valor
                        total: <?php if (isset($ordem_compra['total'])) echo "R$ " . number_format($ordem_compra['total'], 4, ',', '.') ?></h4>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $scripts; ?>

<script>

    $(function () {
        $("#btnPrint").on('click', function (e) {
            e.preventDefault();
            window.print();
        });

    });
</script>
</body>
</html>
