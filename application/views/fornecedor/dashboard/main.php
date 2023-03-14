<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
    <?php echo $navbar; ?>
    <?php echo $sidebar; ?>

    <div class="content">


        <div class="row quick-stats">
            <div class="col-sm-6 col-md-3">

                <div class="quick-stats__item" style="background-image: linear-gradient(#3b85ce, #41b0eb);border-radius: 15px;box-shadow: 3px 5px 19px 0px rgba(0,0,0,0.32);">
                    <div class="quick-stats__info">
                        <h3 style="color: white"><?php if (isset($indicadores['totalOfertado'])) echo $indicadores['totalOfertado']; ?></h3>
                        <small class="text-white">Total Ofertado</small>
                    </div>

                    <div class="quick-stats__chart sparkline-bar-stats text-white">
                        <i class="fas fa-hand-holding-usd fa-3x" style="opacity: 0.5;"></i>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="quick-stats__item" style="background-image: linear-gradient(#0c965b, #32c787);border-radius: 15px; box-shadow: 3px 5px 19px 0px rgba(0,0,0,0.32);">

                    <div class="quick-stats__info">
                        <h3 style="color: white"><?php if (isset($indicadores['totalCotacoesAberto'])) echo $indicadores['totalCotacoesAberto']; ?></h3>
                        <small class="text-white">Total de cotações em aberto</small>
                    </div>

                    <div class="quick-stats__chart sparkline-bar-stats text-white">
                        <i class="fas fa-chart-bar fa-3x" style="opacity: 0.5;"></i>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="quick-stats__item" style="background-image: linear-gradient(#c1a44c, #ffc721);border-radius: 15px;box-shadow: 3px 5px 19px 0px rgba(0,0,0,0.32);">

                    <div class="quick-stats__info">
                        <h3 style="color: white">
                            R$<?php if (isset($indicadores['valorTotalOfertado'])) echo $indicadores['valorTotalOfertado']; ?></h3>
                        <small class="text-white">Valor Total Ofertado</small>
                    </div>

                    <div class="quick-stats__chart sparkline-bar-stats text-white">
                        <i class="fas fa-comment-dollar fa-3x" style="opacity: 0.5;"></i>
                    </div>

                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="quick-stats__item" style="background-image: linear-gradient(#c44543, #ff6b69);border-radius: 15px;box-shadow: 3px 5px 19px 0px rgba(0,0,0,0.32);">

                    <div class="quick-stats__info">
                        <h3 style="color: white">
                            R$<?php if (isset($indicadores['totalOc'])) echo $indicadores['totalOc']; ?></h3>
                        <small class="text-white">Total Convertido</small>
                    </div>

                    <div class="quick-stats__chart sparkline-bar-stats text-white">
                        <i class="fas fa-money-bill fa-3x" style="opacity: 0.5;"></i>
                    </div>

                </div>
            </div>
        </div>

        <div id="graficos" data-url="<?php echo $urlCharts; ?>">

            <div class="row">
                <div class="col-md-12">
                    <div class="card" style="border-radius: 15px;!Important">
                        <div class="card-header text-center" style="background-image: linear-gradient(#757779,#868e96);border-radius: 15px 15px 0px 0px;box-shadow: 3px 5px 19px 0px rgba(0,0,0,0.32);">
                            <h5 class="text-white font-weight-bold mt-1">TOTAL COTAÇÕES MENSAL</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2 offset-md-10">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="filtroAno"><b>Ano</b></label>
                                        </div>
                                        <select class="custom-select" id="filtroAno">
                                            <?php foreach ($filtroAno as $ano) : ?>
                                                <option value="<?php echo $ano['ano']; ?>" <?php if (date("Y") == $ano['ano']) echo 'selected'; ?>><?php echo $ano['ano']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="chartLine"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card" style="height: 550px;border-radius: 15px;!Important">
                        <div class="card-header bg-secondary text-center" style="background-image: linear-gradient(#757779,#868e96);height: 550px;padding: 10px;border-radius: 15px 15px 0px 0px;box-shadow: 3px 5px 19px 0px rgba(0,0,0,0.32);">
                            <h5 class="text-white font-weight-bold">COTAÇÕES EM ABERTO POR ESTADO</h5>
                        </div>

                        <div class="card-body">
                            <div class="chart-container">
                                <div id="chartMap" style="height: 360px;" data-url="<?php echo $url_info_mapa; ?>"></div>
                            </div>
                            <br>
                            <p class="small">Clique sobre o estado para listar as cotações.
                                <br> Podem haver diferenças entre o número de cotações mostradas no portal síntese x
                                pharmanexo, pois não exibimos cotações com contrato específico de fornecimento e nem
                                cotações de OPMEs
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card" style="height: 550px;border-radius: 15px;!Important">
                        <div class="card-header bg-secondary text-center" style="background-image: linear-gradient(#757779,#868e96);border-radius: 15px 15px 0px 0px;box-shadow: 3px 5px 19px 0px rgba(0,0,0,0.32);">
                            <h5 class="text-white font-weight-bold mt-1">PRODUTOS A VENCER </span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <span id="loadingChart">
                                <div id="chartColumn"></div>
                                <br>
                                <p class="small">Clique sobre o gráfico para listar os produtos de cada categoria.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <p>Ultima atualização de cotações</p>
                <span class="badge pull-right badge-secondary"><?php if (isset($updateCotacoes['SINTESE'])) echo "Sintese: " . date('d/m/Y H:i', strtotime($updateCotacoes['SINTESE'])); ?></span>
                <span class="badge pull-right badge-secondary"><?php if (isset($updateCotacoes['BIONEXO'])) echo "Bionexo: " . date('d/m/Y H:i', strtotime($updateCotacoes['BIONEXO'])); ?></span>
                <span class="badge pull-right badge-secondary"><?php if (isset($updateCotacoes['APOIO'])) echo "Apoio: " . date('d/m/Y H:i', strtotime($updateCotacoes['APOIO'])); ?></span>

            </div>
        </div>

        <?php /*if (isset($_SESSION['compra_distribuidor']) && $_SESSION['compra_distribuidor'] == '1') { */ ?><!--
        <div class="row mb-5">
            <div class="col-12 text-center">
                <a href="fornecedor/b2b/ofertas"
                   style="background-image: linear-gradient(#3b85ce, #41b0eb);border-radius: 10px !Important"
                   class="btn btn-lg text-white"><b>ACESSO PARA VENDA </b>
                    <br><small>DISTRIBUIDOR x DISTRIBUIDOR</small></a>
            </div>
        </div>
    --><?php /*} */ ?>
    </div>

    <?php echo $scripts; ?>

    <script>
        var chartLine;

        var urlLine = "<?php echo $urlLine; ?>";
        var urlProdutosVencer = "<?php echo $urlProdutosVencer; ?>";

        $(function() {

            main();

            $("#filtroAno").on('change', function() {

                var url = urlLine + $(this).val() + '/1';

                $.post(url, {}, function(xhr) {

                    if (typeof xhr == "string") {
                        xhr = JSON.parse(xhr);
                    }

                    chartline(xhr, 1);
                });
            })
        });

        function main() {

            var data = {
                ano: $("#filtroAno").val()
            };

            $.post($("#graficos").data('url'), data, function(xhr) {

                if (typeof xhr == "string") {
                    console.log(xhr);
                    xhr = JSON.parse(xhr);
                }

                chartline(xhr.chartLine);
                chartColumn(xhr.chartColumn);
                chartMap(xhr.chartMap);
            });
        }

        function chartMap(response) {
            $('#chartMap').vectorMap({
                map: 'brazil_br',
                backgroundColor: null,
                // color: '#f5f5f5',
                color: '#e1eedd',
                hoverColor: '#ccf2ff',
                enableZoom: false,
                showTooltip: true,
                showLabels: true,
                onLabelShow: function(event, label, code) {
                    response.forEach(element => {

                        if (element.code.toLowerCase() === code) {

                            var texto = label[0].innerHTML + "<br />";

                            if (element.sintese != '') {

                                texto = texto + "Sintese: " + element.sintese;
                            }

                            if (element.bionexo != '') {

                                texto = texto + "<br />Bionexo: " + element.bionexo;
                            }

                            if (element.apoio != '') {

                                texto = texto + "<br />Apoio: " + element.apoio;
                            }

                            label[0].innerHTML = texto;
                        }
                    });
                },
                <?php if (isset($_SESSION['tipo_empresa']) && $_SESSION['tipo_empresa'] != '1') { ?>
                    onRegionClick: function(event, label, code) {

                        <?php if (in_array($this->session->id_fornecedor, explode(',', ONCOPROD))) { ?>
                            window.location = "<?php echo base_url("fornecedor/cotacoes_oncoprod"); ?>" + `?uf=${label}`;
                        <?php } elseif (in_array($this->session->id_fornecedor, explode(',', ONCOEXO))) { ?>
                            window.location = "<?php echo base_url("fornecedor/cotacoes_oncoexo"); ?>" + `?uf=${label}`;
                        <?php } else { ?>
                            window.location = "<?php echo base_url("fornecedor/cotacoes"); ?>" + `?uf=${label}`;
                        <?php } ?>
                    }
                <?php } ?>
            });
        }

        function chartColumn(series, updt = null) {

            var formatados = series.format

            var options = {
                series: series.value,
                chart: {
                    type: 'bar',
                    height: 400,
                    toolbar: {
                        show: false,
                    },
                    events: {
                        dataPointSelection: function(chartContext, seriesIndex, config) {

                            var new_url;
                            switch (config.dataPointIndex) {
                                case 0:
                                    new_url = urlProdutosVencer + '/1/3';
                                    break;
                                case 1:
                                    new_url = urlProdutosVencer + '/3/6';
                                    break;
                                case 2:
                                    new_url = urlProdutosVencer + '/6/9';
                                    break;
                                case 3:
                                    new_url = urlProdutosVencer + '/9/12';
                                    break;
                                case 4:
                                    new_url = urlProdutosVencer + '/12/18';
                                    break;
                            }

                            setTimeout(function(e) {
                                $('#loadingChart').html('<i class="fa fa-spin fa-spinner"></i> Carregando produtos...');

                                window.location.href = new_url;
                            }, 2000);
                        }
                    }
                },
                plotOptions: {
                    bar: {
                        barHeight: '100%',
                        distributed: true,
                        horizontal: false,
                        dataLabels: {
                            position: 'bottom'
                        },
                    }
                },
                dataLabels: {
                    enabled: false,
                },
                colors: ['#ED553b', '#F6D55C', '#3CAEA3', '#20639B', '#173F5F'],
                legend: {
                    show: true,
                    floating: true,
                    position: 'left',
                    onItemClick: {
                        toggleDataSeries: true
                    },
                    onItemHover: {
                        highlightDataSeries: true
                    },
                    formatter: function(seriesName, opts) {
                        return 'R$ ' + formatados[opts.seriesIndex];
                    }
                },
                xaxis: {
                    labels: {
                        rotate: 0
                    },
                    categories: ["1 a 3 meses", "3 a 6 meses", "6 a 9 meses", "9 a 12 meses", "12 a 18 meses"],
                },
                yaxis: {
                    labels: {
                        show: false
                    }
                },
                grid: {
                    show: false
                },
                tooltip: {
                    custom: function({
                        series,
                        seriesIndex,
                        dataPointIndex,
                        w
                    }) {
                        return ('<table class="table"><tr><th>Total: ' + formatados[dataPointIndex] + '</th></tr></table>');
                    }
                }
            };

            if (updt != null) {

                chartColumn.updateOptions(options);
            } else {

                chartColumn = new ApexCharts(document.querySelector("#chartColumn"), options);
                chartColumn.render();
            }

            $(".apexcharts-legend-text").each(function(index, value) {

                if (index > 2) {

                    $(value).click(function() {

                        $("#chartColumn").html(`<p class="text-center"><i class="fa fa-spin fa-spinner" style="font-size: 36px"></i></p>`);

                        var new_url;

                        switch (index) {
                            case 3:
                                new_url = urlProdutosVencer + '/1/3';
                                break;
                            case 4:
                                new_url = urlProdutosVencer + '/3/6';
                                break;
                            case 5:
                                new_url = urlProdutosVencer + '/6/9';
                                break;
                            case 6:
                                new_url = urlProdutosVencer + '/9/12';
                                break;
                            case 7:
                                new_url = urlProdutosVencer + '/12/18';
                                break;
                        }
                        setTimeout(function(e) {
                            window.location.href = new_url
                        }, 2000);
                    });
                }
            })
        }

        function chartline(series, updt = null) {
            var options = {
                series: series,
                chart: {
                    height: 250,
                    type: 'line',
                    dropShadow: {
                        enabled: true,
                        color: '#000',
                        top: 18,
                        left: 7,
                        blur: 10,
                        opacity: 0.2
                    },
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#77B6EA', '#2196F3', '#545454', '#192069'],
                dataLabels: {
                    enabled: true,
                    enabledOnSeries: [3],
                    offsetY: -20,
                    formatter: function(val) {
                        return val + "%";
                    },
                },
                stroke: {
                    curve: 'smooth'
                },
                grid: {
                    borderColor: '#e7e7e7',
                    row: {
                        colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                    },
                },
                xaxis: {
                    categories: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                    title: {
                        text: 'Meses'
                    }
                },
                yaxis: {
                    show: true
                },
                legend: {
                    position: 'top'
                },
                grid: {
                    show: true
                }
            };

            if (updt != null) {

                chartLine.updateOptions(options);
            } else {

                chartLine = new ApexCharts(document.querySelector("#chartLine"), options);
                chartLine.render();
            }
        }
    </script>
</body>

</html>