<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">

    <!-- Badges -->
    <div class="card">
        <div class="card-header">
            <div class=" row card-title">
                <div class="col-12 col-lg-6">
                    <div class="form-group" hidden>
                        <label for="">Período</label>
                        <div class="input-group">
                            <input type="date" id="data_ini" class="form-control">
                            <div class="input-group-append">
                                <div class="input-group-text"> a</div>
                            </div>
                            <input type="date" id="data_fim" class="form-control">
                            <div class="input-group-append">
                                <button id="uploadDash" class="btn btn-primary">Buscar</button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">

            <div class="row quick-stats">
                <div class="col-sm-6 col-md-2">
                    <div class="quick-stats__item bg-blue">
                        <div class="quick-stats__info">
                            <h2 class="counter" id="badgeTotalCotacoes"><?php echo $badgeTotalCotacoes; ?></h2>
                            <small>Pedidos</small>
                        </div>

                        <div class="quick-stats__chart sparkline-bar-stats">
                            <i class="fas fa-signal"></i>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <div class="quick-stats__item bg-orange">
                        <div class="quick-stats__info">
                            <h2 class="counter"
                                id="badgeTotalCotacoesMonetario"><?php echo number_format($badgeTotalCotacoesMonetario, 4, ',', '.'); ?></h2>
                            <small>Valor Pedidos</small>
                        </div>

                        <div class="quick-stats__chart sparkline-bar-stats">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <div class="quick-stats__item bg-green">
                        <div class="quick-stats__info">
                            <h2 class="counter"
                                id="badgeTotalConvertido"><?php echo number_format($badgeTotalConvertido, 4, ',', '.'); ?></h2>
                            <small>Ordens Compra (R$)</small>
                        </div>

                        <div class="quick-stats__chart sparkline-bar-stats">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-2">
                    <div class="quick-stats__item bg-red">
                        <div class="quick-stats__info">
                            <h2 class="counter" id="badge"><?php echo $badge; ?></h2>
                            <small>Cancelados</small>
                        </div>

                        <div class="quick-stats__chart sparkline-bar-stats">
                            <i class="fas fa-signal"></i>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-2">
                    <div class="quick-stats__item bg-blue-grey">
                        <div class="quick-stats__info">
                            <h2 class="counter" id="badge"><?php echo $badgeTotalParcial; ?></h2>
                            <small>Parcial</small>
                        </div>

                        <div class="quick-stats__chart sparkline-bar-stats">
                            <i class="fas fa-signal"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-5">
            <div class="card" style="height: 500px;">
                <div class="card-header">
                    <h4 class="card-title">Meta do mês global</h4>
                </div>
                <div class="card-body">
                    <div id="chartMeta" data-url="<?php echo $url_grafico; ?>"></div>
                </div>
            </div>
        </div>
        <div class="col-7">
            <div class="card" style="height: 500px;">
                <div class="card-header">
                    <h4 class="card-title">Meta por representante</h4>
                </div>
                <div class="card-body">
                    <div id="chart"></div>
                </div>
            </div>
        </div>
    </div>


</div>

<?php echo $scripts; ?>

<script>


    $(function () {
        main();
    });


    function main() {
        $.get($("#chartMeta").data('url'), function (xhr) {

         /*   if (typeof xhr == "string") {
                xhr = JSON.parse(xhr);
            }*/


            chartMeta(xhr.chartMeta);
            chartRepTotal(xhr.chartMetaRep);
        });
    }

    function chartMeta(series, updt = null) {


        var options = {
            series: [series.valor],
            chart: {
                height: 400,
                type: 'radialBar',
            },
            plotOptions: {
                radialBar: {
                    hollow: {
                        size: '70%',
                    },
                    dataLabels: {
                        show: true,
                        value: {
                            show: true,
                            formatter: function (val) {
                                return 'R$ ' + series.meta + " (" + series.valor.toFixed(1) + "%)"
                            }
                        },
                    }
                },
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    type: 'horizontal',
                    shadeIntensity: 0.5,
                    gradientToColors: ['#ABE5A1'],
                    inverseColors: true,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100]
                }
            },
            stroke: {
                lineCap: 'round'
            },
            labels: ['Meta'],
        };

        var chartMeta = new ApexCharts(document.querySelector("#chartMeta"), options);

        chartMeta.render();
    }

    function chartRepTotal(series)
    {
        console.log(series);
        var options = {
            series: series.valor,
            chart: {
                height: 400,
                type: 'radialBar',
            },
            plotOptions: {
                radialBar: {
                    dataLabels: {
                        name: {
                            fontSize: '22px',
                        },
                        value: {
                            fontSize: '16px',
                            formatter: function (w) {
                                return parseFloat(w).toFixed(2) + "%";
                            }
                        },
                        total: {
                            show: true,
                            label: 'Total',
                            formatter: function (w) {
                                // By default this function returns the average of all series. The below is just an example to show the use of custom formatter function
                                return "R$ " + series.total
                            }
                        }
                    }
                }
            },
            labels: series.labels,
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();

    }
</script>
</body>

</html>
