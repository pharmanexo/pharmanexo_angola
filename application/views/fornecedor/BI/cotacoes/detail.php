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
                <div class="card-header">
                    <p class="text-muted border-bottom"><strong>Dados do Comprador</strong></p>
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <strong>CNPJ</strong> <br>
                            <?php if (isset($comprador['cnpj'])) echo $comprador['cnpj']; ?>
                        </div>
                        <div class="col-12 col-lg-4">
                            <strong>Razão Social</strong> <br>
                            <?php if (isset($comprador['razao_social'])) echo $comprador['razao_social']; ?>
                        </div>
                        <div class="col-12 col-lg-4">
                            <strong>Cidade/UF</strong> <br>
                            <?php if (isset($comprador['cidade'])) echo $comprador['cidade']; ?><?php if (isset($comprador['estado'])) echo  '/' . $comprador['estado']; ?>
                        </div>
                    </div>
                    <p class="text-muted mt-3 border-bottom"><strong>Dados da Cotação</strong></p>
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <strong>Periodo</strong> <br>
                            <?php if (isset($cotacao)) echo date('d/m/Y', strtotime($cotacao['dt_inicio_cotacao'])) . ' - ' .  date('d/m/Y', strtotime($cotacao['dt_fim_cotacao'])); ?>
                        </div>
                        <div class="col-12 col-lg-4 offset-4 text-center">
                            <strong>Total Itens</strong> <br>
                            <?php if (isset($total_itens)) echo $total_itens; ?>
                        </div>
                    </div>
                </div>
               
                <div class="card-body">

                    <div id="accordion">

                        <?php foreach($produtos as $kk => $produto): ?>

                            <div class="card mb-0 <?php if($kk != 0) echo 'mt-4' ?>">

                                <div class="card-header <?php if( isset($produto['cotado']['class']) ) echo $produto['cotado']['class']; ?>" style="cursor: pointer;" id="headingProduto_<?php echo $kk; ?>" data-toggle="collapse" data-target="#collapseProduto_<?php echo $kk; ?>" aria-expanded="true" aria-controls="collapseProduto_<?php echo $kk; ?>">
                                    <h6 class="mb-0 text-muted">
                                         <?php echo $produto['cotado']['ds_produto_comprador']; ?>
                                    </h6>
                                </div>

                                <div id="collapseProduto_<?php echo $kk; ?>" class="collapse show" aria-labelledby="headingProduto_<?php echo $kk; ?>" data-parent="#accordion">
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-left">ID Produto Sintese</th>
                                                            <th class="text-left">Código Produto Comprador</th>
                                                            <th class="text-center">Unidade</th>
                                                            <th class="text-center">Quantidade Solicitada</th>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left"><?php echo $produto['cotado']['id_produto_sintese']; ?></td>
                                                            <td class="text-left"><?php echo $produto['cotado']['cd_produto_comprador']; ?></td>
                                                            <td class="text-center"><?php echo $produto['cotado']['ds_unidade_compra']; ?></td>
                                                            <td class="text-center"><?php echo $produto['cotado']['qt_produto_total']; ?></td>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>

                                            <?php if( isset($produto['encontrados']) && !empty($produto['encontrados']) ): ?>
                                                <div class="table-responsive">
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>Código</th>
                                                                <th>Produto</th>
                                                                <th>Marca</th>
                                                                <th>Preço Unitário (R$)</th>
                                                                <th>Estoque</th>
                                                                <th>Enviado</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php foreach($produto['encontrados'] as $k => $p): ?>
                                                            <tr>
                                                                <td><?php echo $p['codigo']; ?></td>
                                                                <td class="text-nowrap"><small><?php echo $p['produto_descricao']; ?></small></td>
                                                                <td><?php echo $p['marca']; ?></td>
                                                                <td><?php echo number_format($p['preco_unitario'], 4, ',', '.'); ?></td>
                                                                <td><?php echo ( isset($p['estoque']) ) ? $p['estoque'] : 0; ?></td>
                                                                <td><?php echo $p['enviado']; ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php else: ?>

                                                <p class="text-muted mt-3 ml-3">Não foram encontrados marcas Pharmanexo para este produto.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach; ?>

                    </div>
                   
                </div>

            </div>
        </div>
    </div>

    <?php echo $scripts; ?>

    <script>
        $(function() {



        });
    </script>
</body>

</html>
