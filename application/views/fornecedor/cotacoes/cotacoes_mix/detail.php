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
                    <div class="row mt-2">
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
                    <div class="row mt-2">
                        <div class="col-12 col-lg-4 text-left">
                            <strong>Descrição</strong> <br>
                            <?php if (isset($cotacao_sintese['ds_cotacao'])) echo $cotacao_sintese['ds_cotacao']; ?>
                        </div>
                        <div class="col-12 col-lg-2">
                            <strong>Data Inicio Cotação</strong> <br>
                            <?php if (isset($cotacao_sintese['dt_inicio_cotacao'])) echo date('d/m/Y', strtotime($cotacao_sintese['dt_inicio_cotacao'])); ?>
                        </div>
                        <div class="col-12 col-lg-2">
                            <strong>Data Fim Cotação</strong> <br>
                            <?php if (isset($cotacao_sintese['dt_fim_cotacao'])) echo date('d/m/Y', strtotime($cotacao_sintese['dt_fim_cotacao'])); ?>
                        </div>
                        <div class="col-12 col-lg-2">
                            <strong>UF Cotação</strong> <br>
                            <?php if (isset($cotacao_sintese['uf_cotacao'])) echo $cotacao_sintese['uf_cotacao']; ?>
                        </div>
                        <div class="col-12 col-lg-2">
                           
                        </div>
                    </div>
                    <p class="text-muted mt-3 border-bottom"><strong>Dados Ofertas</strong></p>
                    <div class="row mt-2">
                        <div class="col-12 col-lg-4 text-left">
                            <strong>Data do Acionamento MIX</strong> <br>
                            <?php if (isset($cotacao['data_criacao'])) echo date("d/m/Y H:i:s", strtotime($cotacao['data_criacao'])); ?>
                        </div>
                        <div class="col-12 col-lg-4">
                            <strong>Data do Ultimo Envio Automático</strong> <br>
                            <?php if (isset($data_envio_automatica)) echo date("d/m/Y H:i:s", strtotime($data_envio_automatica)); ?>
                        </div>
                        <div class="col-12 col-lg-4"></div>
                    </div>
                </div>

                <div class="card-body">

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="produtos_enviados_tab" data-toggle="tab" href="#produtos_enviados" role="tab" aria-controls="produtos_enviados" aria-selected="true">Produtos Aprovados</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="analitico_aprovados-tab" data-toggle="tab" href="#analitico_aprovados" role="tab" aria-controls="analitico_aprovados" aria-selected="false">Analítico Aprovados</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="produtos_rejeitados_tab" data-toggle="tab" href="#produtos_rejeitados" role="tab" aria-controls="produtos_rejeitados" aria-selected="false">Produtos Rejeitados</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="analitico_rejeitado-tab" data-toggle="tab" href="#analitico_rejeitado" role="tab" aria-controls="analitico_rejeitado" aria-selected="false">Analítico Rejeitados</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">

                        <div class="tab-pane fade show active" id="produtos_enviados" role="tabpanel" aria-labelledby="produtos_enviados_tab">
                            <div class="table-responsive">
                                <table id="data-table-aprovados" class="table table-condensed table-hover no-filtered">
                                    <thead>
                                        <tr>
                                            <th>Produto</th>
                                            <th>Marca Oferta</th>
                                            <th>Preço Oferta (R$)</th>
                                            <th class="text-center">Qtde. Solicitada</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if( isset($produtos_cotacao['enviados']) && !empty($produtos_cotacao['enviados']) ): ?>

                                            <?php foreach($produtos_cotacao['enviados'] as $produto): ?>
                                                <tr>
                                                    <td class="text-nowrap">
                                                        <?php echo (!empty($produto['complemento_produto_marca'])) ?
                                                            $produto['ds_produto_marca'] . ' - ' . $produto['complemento_produto_marca'] :
                                                            $produto['ds_produto_marca']; 
                                                        ?>
                                                        <br>
                                                        <b>Produto ofertado: </b><?php echo $produto['enviado'][0]['produto']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $produto['ds_marca']; ?>
                                                        <br>
                                                        <b>Marca ofertada: </b><?php echo $produto['enviado'][0]['marca']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo number_format($produto['vl_preco_produto'], 4, ',', '.'); ?>
                                                        <br>
                                                        <b>Preço ofertado:</b> <?php echo number_format($produto['enviado'][0]['preco_marca'], 4, ',', '.'); ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo $produto['qt_produto_total_solicitado']; ?>
                                                        <br>
                                                        <b>Quantidade ofertada: </b><?php echo $produto['enviado'][0]['qtd_solicitada']; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>

                                            <tr><td colspan="4">Nenhum registro encontrado</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="produtos_rejeitados" role="tabpanel" aria-labelledby="produtos_rejeitados_tab">
                            <div class="table-responsive">
                                <table id="data-table-rejeitados" class="table table-condensed table-hover no-filtered">
                                    <thead>
                                        <tr>
                                            <th>Produto</th>
                                            <th>Marca Oferta</th>
                                            <th>Preço Oferta (R$)</th>
                                            <th class="text-center">Qtde. Solicitada</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if( isset($produtos_cotacao['rejeitados']) && !empty($produtos_cotacao['rejeitados']) ): ?>

                                            <?php foreach($produtos_cotacao['rejeitados'] as $produto): ?>
                                                <tr>
                                                    <td class="text-nowrap">
                                                        <?php echo (!empty($produto['complemento_produto_marca'])) ?
                                                            $produto['ds_produto_marca'] . ' - ' . $produto['complemento_produto_marca'] :
                                                            $produto['ds_produto_marca']; 
                                                        ?>
                                                        <br>
                                                        <b>Motivo da Rejeição:</b>
                                                    </td>
                                                    <td><?php echo $produto['ds_marca']; ?></td>
                                                    <td><?php echo number_format($produto['vl_preco_produto'], 4, ',', '.'); ?></td>
                                                    <td class="text-center"><?php echo $produto['qt_produto_total_solicitado']; ?></td>
                                                 </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>

                                            <tr><td colspan="4">Nenhum registro encontrado</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="analitico_aprovados" role="tabpanel" aria-labelledby="analitico_aprovados-tab">

                            <div class="accordion" id="accordionExample">
                                <div class="card mb-2">
                                    <div class="card-header" id="headingOne">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                Ofertas enviados
                                            </button>
                                        </h2>
                                    </div>

                                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-6">

                                                    <table class="table table-striped table-hover table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <td><b>Número de itens da cotação</b></td>
                                                                <td class="text-right" colspan="3"><?php echo $totais['total_itens_cotacao'] ?> </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Numero de itens respondidos Aut.</b></td>
                                                                <td class="text-right" colspan="3"><?php echo $totais['total_itens_aprovados_aut']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Número de itens respondidos Mix</b></td>
                                                                <td class="text-right" colspan="3"><?php echo $totais['total_itens_aprovados_mix']; ?></td>
                                                            </tr>
                                                        </thead>
                                                        <tfoot>
                                                            <tr>
                                                                <td><b>Total de itens respondidos: </b></td>
                                                                <td class="text-right"><?php echo intval($totais['total_itens_aprovados_aut']) + intval($totais['total_itens_aprovados_mix']); ?></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>

                                                <!-- Grafico -->
                                                <div class="col-lg-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div id="chartAprovado1" style="width: 100%;">
                                                                <h5 class="text-center"><i class="fas fa-spin fa-spinner"></i> Gerando gráfico </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mt-2">
                                    <div class="card-header" id="headingTwo">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                Total Cotado
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <table class="table table-striped table-hover table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <td><b>Valor Total Cotado Aut.</b></td>
                                                                <td class="text-right" colspan="3"><?php echo number_format($valores_totais['valor_total_automatica'], 4, ',', '.'); ?> </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Valor Total Cotado Mix</b></td>
                                                                <td class="text-right" colspan="3"><?php echo number_format($valores_totais['valor_total_mix'], 4, ',', '.'); ?> </td>
                                                            </tr>
                                                        </thead>
                                                        <tfoot>
                                                            <tr>
                                                                <td><b>Valor Total respondido: </b></td>
                                                                <?php $total = floatval($valores_totais['valor_total_automatica']) + floatval($valores_totais['valor_total_mix']); ?>
                                                                <td class="text-right">R$ <?php echo number_format($total, 4, ',', '.'); ?></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>

                                                <!-- Grafico -->
                                                <div class="col-lg-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div id="chartAprovado2" style="width: 100%;">
                                                                <h5 class="text-center"><i class="fas fa-spin fa-spinner"></i> Gerando gráfico </h5>
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

                        <div class="tab-pane fade" id="analitico_rejeitado" role="tabpanel" aria-labelledby="analitico_rejeitado-tab">

                            <div class="card">

                                <div class="card-header"></div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6">

                                            <table class="table table-striped table-hover table-bordered">
                                                <thead>
                                                    <tr>
                                                        <td><b>Número de itens rejeitados</b></td>
                                                        <td class="text-right" colspan="3"><?php echo $totais['total_itens_cotacao'] ?> </td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Numero de itens respondidos Aut.</b></td>
                                                        <td class="text-right" colspan="3"><?php echo $totais['total_itens_aprovados_aut']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Número de itens respondidos Mix</b></td>
                                                        <td class="text-right" colspan="3"><?php echo $totais['total_itens_aprovados_mix']; ?></td>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <td><b>Total de itens respondidos: </b></td>
                                                        <td class="text-right"><?php echo intval($totais['total_itens_aprovados_aut']) + intval($totais['total_itens_aprovados_mix']); ?></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>

                                        <!-- Grafico -->
                                        <div class="col-lg-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div id="chartAprovado1" style="width: 100%;">
                                                        <h5 class="text-center"><i class="fas fa-spin fa-spinner"></i> Gerando gráfico </h5>
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
            </div>
        </div>
    </div>

    <?php echo $scripts; ?>

    <script>

        var url_grafico_aprovado1 = "<?php if(isset($url_grafico_aprovado1)) echo $url_grafico_aprovado1; ?>";
        var dados_grafico_aprovado1 = [];

        var url_grafico_aprovado2 = "<?php if(isset($url_grafico_aprovado2)) echo $url_grafico_aprovado2; ?>";
        var dados_grafico_aprovado2 = [];

        var url_grafico_rejeitado1 = "<?php if(isset($url_grafico_rejeitado1)) echo $url_grafico_rejeitado1; ?>";
        var url_grafico_rejeitado2 = "<?php if(isset($url_grafico_rejeitado2)) echo $url_grafico_rejeitado2; ?>";

        $(function() {

            google.charts.load('current', {'packages':['corechart']});

            var dt_aprovados = $('#data-table-aprovados').DataTable({
                processing: true,
                serverSide: false,
                columns: [
                    null,
                    null,
                    null,
                    null
                ],
                rowCallback: function(row, data) {
                },
                drawCallback: function() {}
            });

            var dt_rejeitados = $('#data-table-rejeitados').DataTable({
                processing: true,
                serverSide: false,
                columns: [
                    null,
                    null,
                    null,
                    null
                ],
                rowCallback: function(row, data) {
                },
                drawCallback: function() {}
            });

            setTimeout(function() { 

                $.post(url_grafico_aprovado1, function (xhr) {
                    dados_grafico_aprovado1 = xhr;
                    google.charts.setOnLoadCallback(drawChartAprovado1);
                });

                $.post(url_grafico_aprovado2, function (xhr) {
                    dados_grafico_aprovado2 = xhr;
                    google.charts.setOnLoadCallback(drawChartAprovado2);
                });

            }, 1500);
        });

        function drawChartAprovado1() {

            var data = google.visualization.arrayToDataTable(dados_grafico_aprovado1);

            var options = {
                title: 'Total Enviado',
                width: 650,
                height: 300,
            };

            var chartAprovado1 = new google.visualization.PieChart(document.getElementById('chartAprovado1'));
            chartAprovado1.draw(data, options);
        } 

        function drawChartAprovado2() {

            var data = google.visualization.arrayToDataTable(dados_grafico_aprovado2);

            var options = {
                title: 'Total Cotado',
                width: 650,
                height: 300,
            };

            var chartAprovado2 = new google.visualization.PieChart(document.getElementById('chartAprovado2'));
            chartAprovado2.draw(data, options);
        }

    </script>
</body>

</html>
