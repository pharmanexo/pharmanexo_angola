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
                <div class="col-sm-6 col-md-3">
                    <div class="quick-stats__item bg-blue">
                        <div class="quick-stats__info">
                            <h2 class="counter" id="badgeTotalCotacoes"><?php echo $badgeTotalCotacoes; ?></h2>
                            <small>Total cotações enviadas</small>
                        </div>

                        <div class="quick-stats__chart sparkline-bar-stats">
                            <i class="fas fa-signal"></i>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <div class="quick-stats__item bg-amber">
                        <div class="quick-stats__info">
                            <h2 class="counter"
                                id="badgeTotalCotacoesMonetario"><?php echo number_format($badgeTotalCotacoesMonetario, 4, ',', '.'); ?></h2>
                            <small>Total cotações enviadas (R$)</small>
                        </div>

                        <div class="quick-stats__chart sparkline-bar-stats">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <div class="quick-stats__item bg-purple">
                        <div class="quick-stats__info">
                            <h2 class="counter"
                                id="badgeTotalConvertido"><?php echo number_format($badgeTotalConvertido, 4, ',', '.'); ?></h2>
                            <small>Total Convertido (R$)</small>
                        </div>

                        <div class="quick-stats__chart sparkline-bar-stats">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <div class="quick-stats__item bg-red">
                        <div class="quick-stats__info">
                            <h2 class="counter" id="badge"><?php echo $badge; ?></h2>
                            <small>Total Acionamentos MIX</small>
                        </div>

                        <div class="quick-stats__chart sparkline-bar-stats">
                            <i class="fas fa-signal"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- graficos -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Total cotações mensal</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <div id="chartTotalCotacao" style="width: 100%; height: 450px;">
                        <h5 class="text-center"><i class="fas fa-spin fa-spinner"></i> Gerando gráfico </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <p class="card-title">
                        <span class="float-lg-left d-inline-block">Cotações X Ordem de compra</span>

                    </p>
                </div>
                <div class="card-body">
                    <div id="chartCotOc" style="width: 100%; height: 400px;">
                        <h5 class="text-center"><i class="fas fa-spin fa-spinner"></i> Gerando gráfico </h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <p class="card-title">
                        <span class="float-lg-left d-inline-block">Total de cotações por tipo</span>
                    </p>
                </div>
                <div class="card-body">
                    <div id="chart" style="width: 100%; height: 400px;">
                        <h5 class="text-center"><i class="fas fa-spin fa-spinner"></i> Gerando gráfico </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Listas -->
    <div id="accordion">
        <div class="card">
            <div class="card-header" id="headingOne">
                <p class="card-title" style="cursor: pointer;">
                        <span class="float-left" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                              aria-controls="collapseOne">
                            Volume de compra por comprador <i class="fas fa-caret-down"></i>
                        </span>
                    <span class="float-right">
                            <label for="filtroVolumeComprador6months" data-id="filtroVolumeComprador6months"
                                   class="btn btn-outline-primary btn-sm">
                               6 meses
                                <input type="radio"
                                       data-url="<?php if (isset($urlVolumeCompraCliente)) echo $urlVolumeCompraCliente ?>/6months"
                                       class="d-none" id="filtroVolumeComprador6months" name="filtroVolumeComprador">
                            </label>
                            <label for="filtroVolumeComprador30days" data-id="filtroVolumeComprador30days"
                                   class="btn btn-outline-primary btn-sm">
                               30 dias
                                <input type="radio"
                                       data-url="<?php if (isset($urlVolumeCompraCliente)) echo $urlVolumeCompraCliente ?>/30days"
                                       class="d-none" id="filtroVolumeComprador30days" name="filtroVolumeComprador">
                            </label>
                            <label for="filtroVolumeCompradorAtual" class="btn btn-outline-primary active btn-sm">
                                mês atual
                                <input type="radio" checked class="d-none"
                                       data-url="<?php if (isset($urlVolumeCompraCliente)) echo $urlVolumeCompraCliente ?>/current"
                                       id="filtroVolumeCompradorAtual" name="filtroVolumeComprador">
                            </label>
                        </span>
                </p>
            </div>

            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dt-volumecompra" class="table table-condensend table-hover">
                            <thead>
                            <tr>
                                <th>Comprador</th>
                                <th>Quantidade</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td colspan="2">Nenhum registro encontrado</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header" id="headingTwo">
                <p class="card-title" style="cursor: pointer;">
                        <span class="float-left" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false"
                              aria-controls="collapseTwo">
                            Total cotado por comprador <i class="fas fa-caret-down"></i>
                        </span>
                    <span class="float-right">
                            <label for="filtroTotalCotado6months" data-id="filtroTotalCotado6months"
                                   class="btn btn-outline-primary btn-sm">
                               6 meses
                                <input type="radio"
                                       data-url="<?php if (isset($urlTotalCotadoCliente)) echo $urlTotalCotadoCliente ?>/6months"
                                       class="d-none" id="filtroTotalCotado6months" name="filtroTotalCotado">
                            </label>
                            <label for="filtroTotalCotado30days" data-id="filtroTotalCotado30days"
                                   class="btn btn-outline-primary btn-sm">
                               30 dias
                                <input type="radio"
                                       data-url="<?php if (isset($urlTotalCotadoCliente)) echo $urlTotalCotadoCliente ?>/30days"
                                       class="d-none" id="filtroTotalCotado30days" name="filtroTotalCotado">
                            </label>
                            <label for="filtroTotalCotadoAtual" class="btn btn-outline-primary active btn-sm">
                                mês atual
                                <input type="radio" checked class="d-none"
                                       data-url="<?php if (isset($urlTotalCotadoCliente)) echo $urlTotalCotadoCliente ?>/current"
                                       id="filtroTotalCotadoAtual" name="filtroTotalCotado">
                            </label>
                        </span>
                </p>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                <div class="card-body">

                    <div class="table-responsive">
                        <table id="dt-totalcotado" class="table table-condensend table-hover">
                            <thead>
                            <tr>
                                <th>Comprador</th>
                                <th>Quantidade</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td colspan="2">Nenhum registro encontrado</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header" id="headingThree" data-toggle="collapse" data-target="#collapseThree"
                 aria-expanded="false" aria-controls="collapseThree">
                <p class="card-title" style="cursor: pointer;">
                        <span class="float-left">
                            Produtos mais cotados <i class="fas fa-caret-down"></i>
                        </span>
                </p>
            </div>
            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dt-maiscotado" class="table table-condensend table-hover">
                            <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Quantidade</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td colspan="2">Nenhum registro encontrado</td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr class="text-center">
                                <th colspan="2" scope="row">
                                    <label>
                                        Exibir&nbsp;
                                        <select id="lengthChange-maiscotado">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                        </select>
                                        &nbsp;registros
                                    </label>
                                </th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header" id="headingFour" data-toggle="collapse" data-target="#collapseFour"
                 aria-expanded="false" aria-controls="collapseFour">
                <p class="card-title" style="cursor: pointer;">
                        <span class="float-left">
                            Principais cotadores <i class="fas fa-caret-down"></i>
                        </span>
                </p>
            </div>
            <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dt-principalcomprador" class="table table-condensend table-hover">
                            <thead>
                            <tr>
                                <th>Comprador</th>
                                <th>Quantidade</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td colspan="2">Nenhum registro encontrado</td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr class="text-center">
                                <th colspan="2" scope="row">
                                    <label>
                                        Exibir&nbsp;
                                        <select id="lengthChange-principalcomprador">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                        </select>
                                        &nbsp;registros
                                    </label>
                                </th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $scripts; ?>

<script>

    var urlGraficoTotalCotacoes = "<?php echo $urlGraficoTotalCotacoes; ?>";
    var urlMaisCotado = "<?php echo $urlMaisCotado; ?>";
    var urlPrincipaisClientes = "<?php echo $urlPrincipaisClientes; ?>";

    var urlGraficoCotOc = "<?php echo $urlGraficoCotOc . '/current'; ?>";
    var urlGraficoCotacoes = "<?php echo $urlGraficoCotacoes . '/current'; ?>";
    var urlTotalCotadoCliente = "<?php echo $urlTotalCotadoCliente . '/current'; ?>";
    var urlVolumeCompraCliente = "<?php echo $urlVolumeCompraCliente . '/current'; ?>";

    var dadosChartTotalCotacao = [];
    var dadosChartCotOc = [];
    var dadosChart = [];




    $(function () {
        newtable_totalcotado(urlTotalCotadoCliente);
        newtable_volumecompra(urlVolumeCompraCliente);
        newtable_maiscotado(urlMaisCotado);
        newtable_principalcomprador(urlPrincipaisClientes);

        google.charts.load('current', {'packages': ['corechart']});

        
        $('#uploadDash').click(function (e){
            e.preventDefault();
            
            var data_ini = $('#data_ini').val();
            var data_fim = $('#data_fim').val();

            newtable_volumecompra(urlVolumeCompraCliente, 1);
            newtable_totalcotado(urlVolumeCompraCliente, 1);
        });

        $('[name="filtroVolumeComprador"]').on('click', function (e) {
            url = urlVolumeCompraCliente

        });

        $('[name="filtroTotalCotado"]').on('click', function (e) {

            url = urlTotalCotadoCliente

        });

        $('[name="filtroGraficoCotOc"]').on('click', function (e) {
            url = urlGraficoCotOc;
            $.post(urlGraficoCotOc, function (xhr) {
                dadosChartCotOc = xhr;
                google.charts.setOnLoadCallback(drawChart2);
            })
        });

        $('[name="filtroGrafico"]').on('click', function (e) {
            $(this).parent().parent().find('label').removeClass('active');
            var target = $(this).parent();
            target.addClass('active');
            url = $(this).data('url');
            $.post(url, function (xhr) {
                dadosChart = xhr;
                google.charts.setOnLoadCallback(drawChart3);
            })
        });

        $('[name="filtroGraficoBadge"]').on('click', function (e) {
            url = urlGraficoCotacoes;
            $.post(url, function (xhr) {

                $('#badgeTotalCotacoes').text(xhr['badgeTotalCotacoes']);
                $('#badgeTotalCotacoesMonetario').text(xhr['badgeTotalCotacoesMonetario']);
                $('#badgeTotalConvertido').text(xhr['badgeTotalConvertido']);
                $('#badge').text(xhr['badge']);
            })
        });


        $('#lengthChange-maiscotado').on('change', function (e) {
            newtable_maiscotado(urlMaisCotado, 1)
        });
        $('#lengthChange-principalcomprador').on('change', function (e) {
            newtable_principalcomprador(urlPrincipaisClientes, 1)
        });


        setTimeout(function () {

            $.post(urlGraficoTotalCotacoes, function (xhr) {
                dadosChartTotalCotacao = xhr;
                google.charts.setOnLoadCallback(drawChart);
            });

            $.post(urlGraficoCotOc, function (xhr) {
                dadosChartCotOc = xhr;
                google.charts.setOnLoadCallback(drawChart2);
            });

            $.post(urlGraficoCotacoes, function (xhr) {
                dadosChart = xhr;
                google.charts.setOnLoadCallback(drawChart3);
            })
        }, 1500);

    });

    function newtable_volumecompra(url, first = null) {
        $.ajax({
            url: url,
            type: 'POST',
            dataType: "json",
            responsive: true,
            serverSide: true,
            language: {
                "decimal": ",",
                "thousands": "."
            },
            success: function (response) {

                if (response.type == "success") {

                    if (first != null) {
                        $('#dt-volumecompra').DataTable().destroy();
                    }

                    $('#dt-volumecompra').find('tbody').html('');

                    var rows = "";

                    $.map(response.data, function (row) {

                        var line = `<tr>
                                <td> ${row.comprador}</td>
                                <td>${numberToReal(row.preco_total)}</td>
                            </tr>`;

                        rows = rows + line;
                    });

                    $('#dt-volumecompra').find('tbody').append(`${rows}`);

                    var dt_volumecompra = $('#dt-volumecompra').DataTable({
                        serverSide: false,
                        responsive: true,
                        order: [[0, "asc"]],
                        columns: [null, null],
                        language: {
                            "decimal": ",",
                            "thousands": "."
                        },
                        rowCallback: function (row, data) {
                        },
                        drawCallback: function () {
                        }
                    });
                } else {
                    $('#dt-volumecompra').find('tbody').html('');
                    $('#dt-volumecompra').find('tbody').append(`<tr><td colspan="2">Nenhum registro encontrado</td></tr>`);
                }
            }
        });
    }

    function newtable_totalcotado(url, first = null) {

        $.ajax({
            url: url,
            type: 'POST',
            dataType: "json",
            responsive: true,
            success: function (response) {

                if (response.type == "success") {

                    if (first != null) {
                        $('#dt-totalcotado').DataTable().destroy();
                    }

                    $('#dt-totalcotado').find('tbody').html('');

                    var rows = "";

                    $.map(response.data, function (row) {

                        var line = `<tr>
                                <td> ${row.comprador}</td>
                                <td>${row.qtd_cotacao}</td>
                            </tr>`;

                        rows = rows + line;
                    });

                    $('#dt-totalcotado').find('tbody').append(`${rows}`);

                    var dt_totalcotado = $('#dt-totalcotado').DataTable({
                        serverSide: false,
                        responsive: true,
                        order: [[1, "desc"]],
                        columns: [null, null],
                        language: {
                            "decimal": ",",
                            "thousands": "."
                        },
                        rowCallback: function (row, data) {
                        },
                        drawCallback: function () {
                        }
                    });
                } else {
                    $('#dt-totalcotado').find('tbody').html('');
                    $('#dt-totalcotado').find('tbody').append(`<tr><td colspan="2">Nenhum registro encontrado</td></tr>`);
                }
            }
        });
    }

    function newtable_maiscotado(url, first = null) {
        $.ajax({
            url: url,
            type: 'POST',
            dataType: "json",
            responsive: true,
            data: {
                length: $('#lengthChange-maiscotado').val()
            },
            success: function (response) {

                if (response.type == "success") {

                    if (first != null) {
                        $('#dt-maiscotado').DataTable().destroy();
                    }

                    $('#dt-maiscotado').find('tbody').html('');

                    var rows = "";

                    $.map(response.data, function (row) {

                        var line = `<tr>
                                <td> ${row.produto}</td>
                                <td>${row.total}</td>
                            </tr>`;

                        rows = rows + line;
                    });

                    $('#dt-maiscotado').find('tbody').append(`${rows}`);

                    var dt_maiscotado = $('#dt-maiscotado').DataTable({
                        serverSide: false,
                        responsive: true,
                        order: [[1, "desc"]],

                        columns: [null, null],
                        rowCallback: function (row, data) {
                        },
                        drawCallback: function () {
                        }
                    });
                } else {
                    $('#dt-maiscotado').find('tbody').html('');
                    $('#dt-maiscotado').find('tbody').append(`<tr><td colspan="2">Nenhum registro encontrado</td></tr>`);
                }
            }
        });
    }

    function newtable_principalcomprador(url, first = null) {

        $.ajax({
            url: url,
            type: 'POST',
            dataType: "json",
            responsive: true,
            data: {
                length: $('#lengthChange-principalcomprador').val()
            },
            success: function (response) {

                if (response.type == "success") {

                    if (first != null) {
                        $('#dt-principalcomprador').DataTable().destroy();
                    }

                    $('#dt-principalcomprador').find('tbody').html('');

                    var rows = "";

                    $.map(response.data, function (row) {

                        var line = `<tr>
                                <td> ${row.comprador}</td>
                                <td>${row.total}</td>
                            </tr>`;

                        rows = rows + line;
                    });

                    $('#dt-principalcomprador').find('tbody').append(`${rows}`);

                    var dt_principalcomprador = $('#dt-principalcomprador').DataTable({
                        serverSide: false,
                        responsive: true,
                        order: [[1, "desc"]],
                        columns: [null, null],
                        rowCallback: function (row, data) {
                        },
                        drawCallback: function () {
                        }
                    });
                } else {
                    $('#dt-principalcomprador').find('tbody').html('');
                    $('#dt-principalcomprador').find('tbody').append(`<tr><td colspan="2">Nenhum registro encontrado</td></tr>`);
                }
            }
        });
    }

    function drawChart() {

        var data = google.visualization.arrayToDataTable(dadosChartTotalCotacao);

        var options = {
            legend: {position: 'bottom'},
            hAxis: {title: 'Meses', titleTextStyle: {color: '#333'}},
            vAxis: {minValue: 0},
            animation: {
                startup: true,
                duration: 2000,
                easing: 'out',
            }
        };

        var chartTotalCotacoes = new google.visualization.ColumnChart(document.getElementById('chartTotalCotacao'));
        chartTotalCotacoes.draw(data, options);
    }

    function drawChart2() {

        var data = google.visualization.arrayToDataTable(dadosChartCotOc);

        var options = {
            pieHole: 0.4
        };

        var chartCotOc = new google.visualization.PieChart(document.getElementById('chartCotOc'));
        chartCotOc.draw(data, options);
    }

    function drawChart3() {

        var data = google.visualization.arrayToDataTable(dadosChart);

        var options = {
            legend: {position: "none"},
            chart: {
                title: 'Company Performance',
                subtitle: 'Sales, Expenses, and Profit: 2014-2017',
            }
        };

        var chart = new google.visualization.BarChart(document.getElementById('chart'));
        chart.draw(data, options);
    }


    function numberToReal(numero) {
        console.log(numero);
        var numero = numero.split('.');
        numero[0] = "" + numero[0].split(/(?=(?:...)*$)/).join('.');
        return numero.join(',');
    }
</script>
</body>

</html>
