<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
    <?php echo $navbar; ?>
    <?php echo $sidebar; ?>

    <div class="content">
        <div class="row quick-stats">
            <div class="col-sm-6 col-md-3">
                <div class="quick-stats__item bg-secondary">
                    <div class="quick-stats__info position-absolute">
                        <h2>R$ <?php echo isset($total_pedidos_abertos) ? number_format($total_pedidos_abertos, 2, ',', '.') : '0,00'?></h2>
                        <small>Pedidos em aberto</small>
                    </div>

                    <div class="text-center text-white quick-stats__chart pr-3">
                        <i class="fas fa-chart-line fa-3x"></i>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="quick-stats__item bg-blue">
                    <div class="quick-stats__info position-absolute">
                        <h2>R$ <?php echo isset($total_pedidos_enviados) ? number_format($total_pedidos_enviados, 2, ',', '.') : '0,00'?></h2>
                        <small>Pedidos aguardando faturamento</small>
                    </div>

                    <div class="text-center text-white quick-stats__chart pr-3">
                        <i class="fas fa-chart-line fa-3x"></i>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="quick-stats__item bg-green">
                    <div class="quick-stats__info position-absolute">
                        <h2>R$ <?php echo isset($total_pedidos_faturados) ? number_format($total_pedidos_faturados, 2, ',', '.') : '0,00'?></h2>
                        <small>Pedidos Faturados</small>
                    </div>

                    <div class="text-center text-white quick-stats__chart pr-3">
                        <i class="fas fa-chart-line fa-3x"></i>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="quick-stats__item bg-orange">
                    <div class="quick-stats__info position-absolute">
                        <h2>R$ <?php echo isset($total_pedidos_cancelados) ? number_format($total_pedidos_cancelados, 2, ',', '.') : '0,00'?></h2>
                        <small>Pedidos Cancelados</small>
                    </div>

                    <div class="text-center text-white quick-stats__chart pr-3">
                        <i class="fas fa-chart-line fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-5">
                <div class="card" style="height: 500px;">
                    <div class="card-header">
                         <h4 class="card-title">Meta do mês</h4>
                    </div>
                    <div class="card-body">
                        <div id="chartMeta"></div>
                    </div>
                </div>
            </div>
            <div class="col-7">
                <div class="card" style="height: 500px;">
                    <div class="card-header">
                        <h4 class="card-title">Produtos a Vencer</h4>
                    </div>
                    <div class="card-body">
                        <div id="chartColumn" data-url="<?php echo $url_grafico; ?>"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Lista de Clientes e Estados</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col text-right">
                                <a href="<?php if(isset($url_exportar_clientes_estados)) echo "{$url_exportar_clientes_estados}" ?>" data-toggle="toggle" title="Exportar Excels" id="btn_exportar_clientes_estados" class="btn btn-primary">
                                    <i class="far fa-file-excel"></i>                
                                </a>
                            </div>
                        </div>
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="clientes-tab" data-toggle="tab" href="#clientes" role="tab" aria-controls="clientes" aria-selected="true">Clientes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="estados-tab" data-toggle="tab" href="#estados" role="tab" aria-controls="estados" aria-selected="false">Estados</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="clientes" role="tabpanel" aria-labelledby="clientes-tab">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="table-cliente" class="table table-condensend table-hover w-100" data-url="<?php echo $to_datatable_cnpjs; ?>">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>CNPJ</th>
                                                        <th>Razão Social</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="estados" role="estados" aria-labelledby="estados-tab">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row mx-auto mt-3">
                                            <div class="col-12 col-sm">
                                                <div class="table-responsive">
                                                    <table id="table-estados" class="table table-condensend table-hover w-100" data-url="<?php echo $to_datatable_estados; ?>">
                                                        <thead>
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>UF</th>
                                                                <th>Estado</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
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
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Promoções</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col text-right">
                                <a href="<?php if(isset($url_promocoes)) echo "{$url_promocoes}" ?>" data-toggle="toggle" title="Exportar Excel" id="btn_exportar_promocoes" class="btn btn-primary">
                                    <i class="far fa-file-excel"></i>                
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="table-promocoes" class="table w-100 table-hover" data-promocoes="<?php echo $to_datatable_promocoes; ?>">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Código</th>
                                    <th>Desconto (%)</th>
                                    <th style="width: 200px;">Produto</th>
                                    <th>Preço</th>
                                    <th>Preço Desconto</th>
                                    <th>Quantidade</th>
                                    <th>Dias</th>
                                    <th>Lote</th>
                                    <th>Regra Venda</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo $scripts; ?>

    <script>

        $(function() {

            $('#loadinChart').html('<h3 class="text-center py-3"><i class="fas fa-spinner fa-spin"></i> Carregando...</h3>');

            var table_clientes = $('#table-cliente').DataTable({
                processing: true,
                serverSide: false,
                lengthChange: false,
                responsive: true,
                pageLength: 5,
                ajax: {
                    url: $('#table-cliente').data('url'),
                    type: 'POST',
                    dataType: 'json'
                },
                columns: [
                    {data: 'id', name: 'id', visible: false},
                    {data: 'cnpj', name: 'cnpj', className: 'text-nowrap'},
                    {data: 'razao_social', name: 'razao_social', className: 'text-nowrap'},
                ],
                order: [[ 1, 'asc' ]],
                rowCallback: function(row, data) {},
                drawCallback: function() {}
            });

            var table_estados = $('#table-estados').DataTable({
                processing: true,
                serverSide: false,
                lengthChange: false,
                responsive: true,
                pageLength: 5,
                ajax: {
                    url: $('#table-estados').data('url'),
                    type: 'POST',
                    dataType: 'json'
                },
                columns: [
                    {data: 'id', name: 'id', visible: false},
                    {data: 'uf', name: 'uf', className: 'text-nowrap'},
                    {data: 'descricao', name: 'descricao', className: 'text-nowrap'},
                ],
                order: [[ 1, 'asc' ]],
                rowCallback: function(row, data) {},
                drawCallback: function() {}
            });

            var table_promocoes = $('#table-promocoes').DataTable({
                serverSide: false,
                lengthChange: false,
                responsive: true,
                ajax: {
                    url: $('#table-promocoes').data('promocoes'),
                    type: 'post',
                    dataType: 'json',
                },
                columns: [
                    { name: 'promocoes.id', data: 'id', visible: false },
                    { name: 'promocoes.codigo', data: 'codigo' },
                    { name: 'promocoes.desconto_percentual', data: 'desconto_percentual', className: 'text-nowrap' },
                    { name: 'produtos_catalogo.produto_descricao', data: 'produto_descricao', className: 'text-nowrap' },
                    { name: 'produtos_preco.preco_unitario', data: 'preco' },
                    { name: 'produtos_preco.preco_unitario', data: 'preco_desconto', className: 'text-nowrap' },
                    { name: 'promocoes.quantidade', data: 'quantidade' },
                    { name: 'promocoes.dias', data: 'dias' },
                    { name: 'promocoes.lote', data: 'lote' },
                    { name: 'promocoes.regra_venda', data: 'regra_venda' }
                ],
                order: [[ 1, 'asc' ]],
                rowCallback: function(row, data) {},
                drawCallback: function() {}
            });


            main();            
        }); 

        function main() 
        {
            $.post($("#chartColumn").data('url'), {}, function(xhr) {  

                if ( typeof xhr == "string" ) { xhr = JSON.parse(xhr); }

                chartColumn(xhr.chartProdutosVencer);
                chartMeta(xhr.chartMeta);
            });
        }

        function chartColumn( series, updt = null ) 
        {

            var formatados = series.format

            var options = {
                series: series.value,
                chart: {
                    type: 'bar',
                    height: 400
                },
                plotOptions: {
                    bar: {
                        barHeight: '100%',
                        distributed: true,
                        horizontal: false,
                        dataLabels: { position: 'bottom'},
                    }
                },
                dataLabels: { enabled: false, },
                colors: ['#ED553b', '#F6D55C', '#3CAEA3', '#20639B', '#173F5F'],
                legend: {
                    show: true,
                    floating: true,
                    position: 'top',
                    onItemClick: { toggleDataSeries: true },
                    onItemHover: { highlightDataSeries: true },
                    formatter: function (seriesName, opts) {

                        return (`<a>R$ ${formatados[opts.seriesIndex]}</a>`);
                    }
                },
                xaxis: {  
                    labels: { rotate: 0 },
                    categories: ["1 a 3 meses", "3 a 6 meses", "6 a 9 meses", "9 a 12 meses", "12 a 18 meses"], 
                },
                yaxis: { labels: {show: false }},
                grid: { show: false },
                tooltip: {
                    custom: function ({series, seriesIndex, dataPointIndex, w}) {
                        return ('<table class="table"><tr><th>Total: ' + formatados[dataPointIndex] + '</th></tr></table>');
                    }
                }
            };

            if ( updt != null ) {

                chartColumn.updateOptions(options); 
            } else {

                chartColumn = new ApexCharts(document.querySelector("#chartColumn"), options);
                chartColumn.render();
            }
        }

        function chartMeta( series, updt = null ) 
        {
           
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
                                    return 'R$ ' + series.meta
                                }
                            },
                        }
                    },
                },
                labels: ['Meta'],
            };

            var chartMeta = new ApexCharts(document.querySelector("#chartMeta"), options);

            chartMeta.render();
        }
    </script>
</body>

</html>