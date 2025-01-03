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
                            <?php if (isset($row['cnpj_comprador'])) echo $row['cnpj_comprador']; ?>
                        </div>

                        <div class="col-12 col-lg-4">
                            <strong>Razão Social</strong> <br>
                            <?php if (isset($row['razao_social'])) echo $row['razao_social']; ?>
                        </div>

                        <div class="col-12 col-lg-4">
                            <strong>Cidade/UF</strong> <br>
                            <?php echo (isset($row['cidade'])) ? $row['cidade'] : 'Cidade Não Informada'; ?>/<?php echo (isset($row['estado'])) ? $row['estado'] : 'UF Não Informado'; ?>
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
                            <?php echo (isset($row['condicao_pagamento'])) ? $row['condicao_pagamento'] : 'Não informado'; ?>
                        </div>

                        <div class="col-12 col-lg-4">
                            <strong>Valor Total</strong> <br>
                            <?php echo (isset($totais['valor_total_cotacao'])) ? 'R$ ' . number_format($totais['valor_total_cotacao'], 2, ',', '.') : 'Não Informado'; ?>
                        </div>
                    </div>

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Produtos Aprovados</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="analitico_aprovados-tab" data-toggle="tab" href="#analitico_aprovados" role="tab" aria-controls="analitico_aprovados" aria-selected="false">Analítico Aprovados</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#perfil" role="tab" aria-controls="profile" aria-selected="false">Produtos Rejeitados</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="analitico_rejeitado-tab" data-toggle="tab" href="#analitico_rejeitado" role="tab" aria-controls="analitico_rejeitado" aria-selected="false">Analítico Rejeitados</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <table class="table w-100 table-striped">
                                <thead>
                                    <tr>
                                        <th>Descrição</th>
                                        <th>Qtde. Solicitada</th>
                                        <th>Preço Oferta</th>
                                        <th>Marca</th>
                                        <th>Vlr. Origem marca/marca</th>
                                        <th>Preço marca</th>
                                        <th>Vlr. Origem outra/marca</th>
                                        <th>Preço Outra marca</th>
                                        <th>Outra marca</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php if (isset($produtos) && !empty($produtos)) { ?>
                                    <?php foreach ($produtos as $produto) { ?>
                                    <tr>
                                        <td><?php echo utf8_decode($produto['produto']); ?></td>
                                        <td><?php echo $produto['qtd_solicitada']; ?></td>
                                        <td><?php echo number_format($produto['preco_oferta'], 2, ',', '.'); ?></td>
                                        <td><?php echo utf8_encode($produto['marca_solicitada']); ?></td>
                                        <td><?php echo number_format($produto['valor_origem_marca_marca'], 2, ',', '.'); ?></td>
                                        <td><?php echo number_format($produto['preco_marca'], 2, ',', '.'); ?></td>
                                        <td><?php echo number_format($produto['valor_origem_outra_marca'], 2, ',', '.'); ?></td>
                                        <td><?php echo number_format($produto['preco_outra_marca'], 2, ',', '.'); ?></td>
                                        <td><?php echo $produto['outra_marca']; ?></td>
                                        <?php } ?>
                                        <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade" id="perfil" role="tabpanel" aria-labelledby="profile-tab">
                            <table class="table w-100 table-striped">
                                <thead>
                                    <tr>
                                        <th>Descrição</th>
                                        <th>Qtde. Solicitada</th>
                                        <th>Preço Oferta</th>
                                        <th>Marca</th>
                                        <th>Vlr. Origem marca/marca</th>
                                        <th>Preço marca</th>
                                        <th>Vlr. Origem outra/marca</th>
                                        <th>Preço Outra marca</th>
                                        <th>Outra marca</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php if (isset($produtos_rejeitados) && !empty($produtos_rejeitados)) { ?>
                                        <?php foreach ($produtos_rejeitados as $produto_rejeitado) { ?>
                                        <tr>
                                            <td><?php echo $produto_rejeitado['produto']; ?></td>
                                            <td><?php echo $produto_rejeitado['qtd_solicitada']; ?></td>
                                            <td><?php echo number_format($produto_rejeitado['preco_oferta'], 2, ',', '.'); ?></td>
                                            <td><?php echo $produto_rejeitado['marca_solicitada']; ?></td>
                                            <td><?php echo number_format($produto_rejeitado['valor_origem_marca_marca'], 2, ',', '.'); ?></td>
                                            <td><?php echo number_format($produto_rejeitado['preco_marca'], 2, ',', '.'); ?></td>
                                            <td><?php echo number_format($produto_rejeitado['valor_origem_outra_marca'], 2, ',', '.'); ?></td>
                                            <td><?php echo number_format($produto_rejeitado['preco_outra_marca'], 2, ',', '.'); ?></td>
                                            <td><?php echo $produto_rejeitado['outra_marca']; ?></td>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade" id="analitico_aprovados" role="tabpanel" aria-labelledby="analitico_aprovados-tab">
                            <h3 class="text-muted mb-3">Relatório da Cotação - Aprovados</h3>

                            <div class="row">
                                <div class="col-lg-8">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>
                                            <tr style="border-top: 2px solid #797979;">
                                                <td><b>Valor total da cotação </b></td>
                                                <td class="text-right" colspan="3">R$ <?php echo number_format($totais['valor_total_cotacao'], 4, ',', '.'); ?> </td>
                                            </tr>
                                            <tr style="border-top: 2px solid #797979;">
                                                <td><b>Participação na cotação</b></td>
                                                <td class="text-right" colspan="3">R$ <?php echo number_format($totais['participacao'], 4, ',', '.'); ?></td>
                                            </tr>
                                            <tr style="border-top: 2px solid #797979;">
                                                <td><b>Total ganho</b></td>
                                                <td class="text-right" colspan="3">R$ <?php echo number_format($totais['total_ganho'], 4, ',', '.'); ?></td>
                                            </tr>
                                        </thead>
                                    </table>

                                    <hr>

                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr style="border-top: 2px solid #797979;">
                                                <th>Pharmanexo</th>
                                                <th class="text-center">Valor</th>
                                                <th class="text-center">
                                                    % Valor Total
                                                </th>
                                                <th class="text-center">
                                                    % Valor Cotado
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><b>Marca/Marca </b></td>
                                                <td class="text-right">R$ <?php echo number_format($totais['participacoes']['marca']['total'], 4, ',', '.') ?></td>
                                                <td class="text-center"><?php echo number_format($totais['participacoes']['marca']['percentual_total'], 2, ',', '.') ?>%</td>
                                                <td class="text-center"><?php echo number_format($totais['participacoes']['marca']['percentual_cotado'], 2, ',', '.') ?>%</td>
                                            </tr>
                                            <tr>
                                                <td><b>Outras Marcas </b></td>
                                                <td class="text-right">R$ <?php echo number_format($totais['participacoes']['outra_marca']['total'], 4, ',', '.') ?></td>
                                                <td class="text-center"><?php echo number_format($totais['participacoes']['outra_marca']['percentual_total'], 2, ',', '.') ?>%</td>
                                                <td class="text-center"><?php echo number_format($totais['participacoes']['outra_marca']['percentual_cotado'], 2, ',', '.') ?>%</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Grafico -->
                                <div class="col-lg-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <canvas id="myChart" height="300px"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="analitico_rejeitado" role="tabpanel" aria-labelledby="analitico_rejeitado-tab">
                            <h3 class="text-muted mb-3">Relatório da Cotação - Rejeitados</h3>
                            <div class="row">
                                <div class="col-lg-8">
                                    <table class="table table-striped table-hover table-bordered table-hover">
                                        <thead>
                                            <tr style="border-top: 2px solid #797979;">
                                                <td><b>Valor total da cotação</b></td>
                                                <td class="text-right" colspan="2">R$ <?php echo number_format($totais['valor_total_cotacao'], 4, ',', '.'); ?></td>
                                            </tr>
                                            <tr>
                                                <td><b>Não cotados</b></td>
                                                <td class="text-right" colspan="2">R$ <?php echo number_format($totais['total_rejeitado'], 4, ',', '.'); ?></td>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>

                                <div class="col-lg-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <canvas id="myOtherChart" height="300px"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo $scripts; ?>

    <script>
        var totalCotacao = <?php echo (isset($totais['valor_total_cotacao']) && !empty($totais['valor_total_cotacao'])) ? $totais['valor_total_cotacao'] : 0; ?>;
        var totalParticipacao = <?php echo (isset($totais['participacao']) && !empty($totais['participacao'])) ? $totais['participacao'] : 0; ?>;
        var totalGanho = <?php echo (isset($totais['total_ganho']) && !empty($totais['total_ganho'])) ? $totais['total_ganho'] : 0; ?>;
        var valor_total_cotacao = <?php echo (isset($totais['valor_total_cotacao']) && !empty($totais['valor_total_cotacao'])) ? $totais['valor_total_cotacao'] : 0; ?>;
        var total_rejeitado = <?php echo (isset($totais['total_rejeitado']) && !empty($totais['total_rejeitado'])) ? $totais['total_rejeitado'] : 0; ?>;

        var context = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(context, {
            type: 'pie',
            data: {
                labels: ['Valor Total da Cotação', 'Participação na Cotação', 'Total Ganho'],
                datasets: [{
                    data: [
                        totalCotacao,
                        totalParticipacao,
                        totalGanho
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                hover: 'index',
                legend: {
                    display: false
                },
                pieceLabel: {
                    mode: (!'%') ? 'value' : '%',
                    precision: 0,
                    fontSize: 18,
                    fontColor: '#fff',
                    fontStyle: 'bold',
                    fontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif"
                }
            }
        });

        var ctx = document.getElementById('myOtherChart').getContext('2d');
        var myOtherChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Valor Total Cotação', 'Valor Não Cotado'],
                datasets: [{
                    data: [
                        valor_total_cotacao,
                        total_rejeitado
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                hover: 'index',
                legend: {
                    display: false
                },
                pieceLabel: {
                    mode: (!'%') ? 'value' : '%',
                    precision: 0,
                    fontSize: 18,
                    fontColor: '#fff',
                    fontStyle: 'bold',
                    fontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif"
                }
            }
        });

        $(function() {
            $('#btnPrintAll').click(function(e) {
                e.preventDefault();
                window.open().document.write(content);
            });
        });
    </script>
</body>

</html>
