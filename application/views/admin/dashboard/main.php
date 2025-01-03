<!DOCTYPE html>
<html lang="pt-BR">
<?php echo $header; ?>
<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>
<div class="content">

    <div class="row" id="graficos">
        <div class="col-6">
            <div class="card">
                <div class="card-header text-center" style="background-image: linear-gradient(#757779,#868e96); ">
                    <h4 class="card-title text-white">Cotações</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 offset-md-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="filtroChartCotacoesIntegrador">
                                        <a data-toggle="tooltip" title="Integrador">
                                            <i class="fas fa-cogs"></i>
                                        </a>
                                    </label>
                                </div>
                                <select class="custom-select" id="filtroChartCotacoesIntegrador">
                                    <option value="BIONEXO">Bionexo</option>
                                    <option value="SINTESE" selected>Sintese</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="filtroChartCotacoes"><b>Período</b></label>
                                </div>
                                <select class="custom-select" id="filtroChartCotacoes">
                                    <option value="current">Mês Atual</option>
                                    <option value="30days">30 dias</option>
                                    <option value="6months">6 meses</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="chartCotacoes"></div>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
                <div class="card-header text-center" style="background-image: linear-gradient(#757779,#868e96); ">
                    <h4 class="card-title text-white">Valor total Ofertado</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 offset-md-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="filtroChartValorCotadoIntegrador">
                                        <a data-toggle="tooltip" title="Integrador">
                                            <i class="fas fa-cogs"></i>
                                        </a>
                                    </label>
                                </div>
                                <select class="custom-select" id="filtroChartValorCotadoIntegrador">
                                    <option value="BIONEXO">Bionexo</option>
                                    <option value="SINTESE" selected>Sintese</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="filtroChartValorCotado"><b>Período</b></label>
                                </div>
                                <select class="custom-select" id="filtroChartValorCotado">
                                    <option value="current">Mês Atual</option>
                                    <option value="30days">30 dias</option>
                                    <option value="6months">6 meses</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="chartValorCotado"></div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header text-center" style="background-image: linear-gradient(#757779,#868e96); ">
                    <h4 class="card-title text-white">Cotações Manuais x Cotações Automáticas x MIX</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 offset-md-6">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="filtroChartTipoIntegrador">
                                        <a data-toggle="tooltip" title="Integrador">
                                            <i class="fas fa-cogs"></i>
                                        </a>
                                    </label>
                                </div>
                                <select class="custom-select" id="filtroChartTipoIntegrador">
                                    <option value="BIONEXO">Bionexo</option>
                                    <option value="SINTESE" selected>Sintese</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="filtroChartTipoCotacao"><b>Período</b></label>
                                </div>
                                <select class="custom-select" id="filtroChartTipoCotacao">
                                    <option value="current">Mês Atual</option>
                                    <option value="30days">30 dias</option>
                                    <option value="6months">6 meses</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="chartTipoCotacao"></div>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($meta)) { ?>
        <div class="row">
            <?php foreach ($meta as $k => $item) { ?>
                <div class="col-4">
                    <div class="card  <?php if ($item['dia'] >= META_DEPARA) echo 'border-success' ?>">
                        <div class="card-body">
                            <p><strong><?php echo $item['nome']; ?></strong></p>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar"
                                     style="width: <?php echo ($item['dia'] * 100) / META_DEPARA; ?>%; color: #000; "
                                     role="progressbar" aria-valuenow="<?php echo $item['dia']; ?>"
                                     aria-valuemin="<?php echo META_DEPARA; ?>"
                                     aria-valuemax="600"><?php echo $item['dia'] . "/" . META_DEPARA; ?></div>
                            </div>
                            <table class="table table-bordered">
                                <tr>
                                    <td class="text-center">
                                        <p><strong>Dia</strong></p>
                                        <p><?php echo $item['dia']; ?>/<?php echo META_DEPARA; ?> Produtos</p>
                                    </td>
                                    <td class="text-center">
                                        <p><strong>Mês</strong></p>
                                        <p><?php echo $item['total']; ?> Produtos</p>
                                    </td>
                                </tr>
                            </table>
                            <div id="accordion">
                                <div class="card">
                                    <div class="card-header" id="heading<?php echo $k; ?>">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link" data-toggle="collapse"
                                                    data-target="#collapse<?php echo $k; ?>" aria-expanded="true"
                                                    aria-controls="collapse<?php echo $k; ?>">
                                                Histórico
                                            </button>
                                        </h5>
                                    </div>

                                    <div id="collapse<?php echo $k; ?>" class="collapse" aria-labelledby="heading<?php echo $k; ?>"
                                         data-parent="#accordion">
                                        <div class="card-body">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td>Mês</td>
                                                    <td>Total</td>
                                                </tr>
                                                <?php foreach ($item['historico'] as $hist) { ?>
                                                    <?php foreach ($hist as $h) { ?>
                                                        <tr>
                                                            <td><?php echo $h['mes_nome']; ?></td>
                                                            <td><?php echo $h['total']; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>

</div>

<?php echo $scripts; ?>
<script>

    var chartCotacoes;
    var chartTipoCotacao;
    var chartValorCotado;

    var urlCharts = "<?php if (isset($urlCharts)) echo $urlCharts; ?>";

    var urlChartCotacoes = "<?php if (isset($urlChartCotacoes)) echo $urlChartCotacoes; ?>";
    var urlChartTipoCotacao = "<?php if (isset($urlChartTipoCotacao)) echo $urlChartTipoCotacao; ?>";
    var urlChartValorCotado = "<?php if (isset($urlChartValorCotado)) echo $urlChartValorCotado; ?>";

    $(function () {

        main();

        $('#filtroChartCotacoes, #filtroChartCotacoesIntegrador').change(function () {
            updateChart('chartCotacoes')
        });
        $('#filtroChartValorCotado, #filtroChartValorCotadoIntegrador').change(function () {
            updateChart('chartValorCotado')
        });
        $('#filtroChartTipoCotacao, #filtroChartTipoIntegrador').change(function () {
            updateChart('chartTipoCotacao')
        });
    })

    function main() {

        $.post(urlCharts, {}, function (xhr) {

            if (typeof xhr == "string") {
                xhr = JSON.parse(xhr);
            }

            createChartValorCotado(xhr.chartValorCotado);
            createChartTipoCotacao(xhr.chartTipoCotacao);
            createChartCotacoes(xhr.chartCotacoes);
        });
    }

    function updateChart(chart) {

        switch (chart) {

            case 'chartCotacoes':

                var url = urlChartCotacoes + '/' + $("#filtroChartCotacoesIntegrador").val() + '/' + $("#filtroChartCotacoes").val() + '/1';
                $.post(url, {}, function (xhr) {
                    createChartCotacoes(xhr, 1);
                });

                break;
            case 'chartValorCotado':

                var url = urlChartValorCotado + '/' + $("#filtroChartValorCotadoIntegrador").val() + '/' + $("#filtroChartValorCotado").val() + '/1';
                $.post(url, {}, function (xhr) {
                    createChartValorCotado(xhr, 1);
                });

                break;
            case 'chartTipoCotacao':

                var url = urlChartTipoCotacao + '/' + $("#filtroChartTipoIntegrador").val() + '/' + $("#filtroChartTipoCotacao").val() + '/1';
                $.post(url, {}, function (xhr) {
                    createChartTipoCotacao(xhr, 1);
                });

                break;
            default:
                return '';
        }
    }

    function createChartValorCotado(series, updt = null) {

        var formatados = series.formatado;

        var options = {
            series: series.data,
            chart: {
                height: 300,
                type: 'bar',
            },
            plotOptions: {
                bar: {
                    columnWidth: '45%',
                    distributed: true
                }
            },
            dataLabels: {enabled: false},
            legend: {show: true},
            xaxis: {
                categories: series.labels,
                labels: {show: false}
            },
            yaxis: {
                labels: {
                    formatter: function (val, index) {
                        return 'R$ ' + formatReal(val);
                    }
                }
            },
            tooltip: {
                custom: function ({series, seriesIndex, dataPointIndex, w}) {

                    return ('<table class="table"><tr><th>Total: ' + formatados[dataPointIndex] + '</th></tr></table>');

                }
            }
        };

        if (updt != null) {

            chartValorCotado.updateOptions(options);
        } else {

            chartValorCotado = new ApexCharts(document.querySelector("#chartValorCotado"), options);
            chartValorCotado.render();
        }
    }

    function createChartTipoCotacao(series, updt = null) {
        var options = {
            series: series.data,
            chart: {
                height: 300,
                type: 'line',
            },
            colors: ['#77B6EA', '#2196F3', '#545454'],
            dataLabels: {
                enabled: true,
                enabledOnSeries: [3]
            },
            labels: series.labels
        };


        if (updt != null) {

            chartTipoCotacao.updateOptions(options);
        } else {

            chartTipoCotacao = new ApexCharts(document.querySelector("#chartTipoCotacao"), options);
            chartTipoCotacao.render();
        }
    }

    function createChartCotacoes(series, updt = null) {

        var options = {
            series: series.data,
            chart: {
                type: 'bar',
                height: 300,
                stacked: true,
                toolbar: {
                    show: true
                }
            },
            colors: ['#77B6EA', '#2196F3', '#545454'],
            plotOptions: {bar: {horizontal: false,},},
            labels: series.labels,
            xaxis: {labels: {show: false}},
            legend: {position: 'bottom',}
        };

        if (updt != null) {

            chartCotacoes.updateOptions(options);
        } else {

            chartCotacoes = new ApexCharts(document.querySelector("#chartCotacoes"), options);
            chartCotacoes.render();
        }
    }

    function formatReal(int) {
        var tmp = int + '';
        tmp = tmp.replace(/([0-9]{2})$/g, ",$1");
        if (tmp.length > 6)
            tmp = tmp.replace(/([0-9]{3}),([0-9]{2}$)/g, ".$1,$2");

        return tmp;
    }

</script>

</body>
</html>
