<!DOCTYPE html>
<html lang="pt-BR">
<?php echo $header; ?>
<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>
<div class="content">

   
    <div id="dash">

        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        
                        <h4 class="card-title">Gráfico de produtos por validade</h4>

                    </div>
                    <div class="card-body">
                        <?php if( isset($selectMatriz) ): ?>

                            <div class="row">
                                <div class="col-3">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="id_fornecedor">Lojas</label>
                                        </div>
                                        <select class="custom-select" id="id_fornecedor">
                                            <?php foreach($selectMatriz as $f): ?>
                                                <option value="<?php echo $f['id']; ?>" <?php if( $f['id'] == $this->session->id_fornecedor ) echo 'selected'; ?>  ><?php echo $f['nome_fantasia']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?> 
                        <div id="chart">
                            <p id="loadingChart">
                                <i class="fas fa-spin fa-spinner"></i> Carregando dados do gráfico...
                            </p>
                        </div>
                        <br>
                        <p class="small">Clique sobre o gráfico para listar os produtos de cada categoria.</p>
                    </div>
                </div>
            </div>
        </div>

        <a href="#" id="ancora"></a>
        <div class="row" id="relatorio">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive" id="tabelaCotacoes">
                            <table id="data-table" class="table table-condensed table-hover" data-url="<?php echo $urlRelatorioProdutosValidade; ?>">
                                <thead>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Produto</th>
                                        <th>Marca</th>
                                        <th>Lote</th>
                                        <th>Estoque</th>
                                        <th>Qtd Unidade</th>
                                        <th>Preço Unitário</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $scripts; ?>

<script>

    var chart;

    var url =  "<?php echo $url; ?>/";

    $(function () {

        main();

        $("#id_fornecedor").on('change', function () { main(1); });    
    })

    function main( updt = null ) 
    {
        $.post(url, {id_fornecedor: $("#id_fornecedor").val()}, function( xhr ) {

            if ( typeof xhr == "string" ) { xhr = JSON.parse(xhr); }

            var formatados = xhr.chart.format;

            var options = {
                series: xhr.chart.value,
                chart: {
                    type: 'bar',
                    height: 370,
                    events: {
                        dataPointSelection: function(chartContext, seriesIndex, config) { 

                            createTable(config.dataPointIndex);
                            ancora();
                        }
                    }
                },
                plotOptions: {
                    bar: {
                        barHeight: '100%',
                        distributed: true,
                        horizontal: false,
                        dataLabels: { position: 'bottom'},
                    }
                },
                dataLabels: { enabled: false },
                colors: ['#ED553b', '#F6D55C', '#3CAEA3', '#20639B', '#173F5F', '#0c2132'],
                legend: {
                    show: true,
                    floating: true,
                    position: 'left',
                    onItemClick: { toggleDataSeries: true },
                    onItemHover: { highlightDataSeries: true },
                    formatter: function (seriesName, opts) {

                        return (`<a data-href="${opts.seriesIndex}">R$ ${formatados[opts.seriesIndex]}</a>`);
                    }
                },
                xaxis: {  
                    labels: { rotate: 0 },
                    categories: ["1 a 3 meses", "3 a 6 meses", "6 a 9 meses", "9 a 12 meses", "12 a 18 meses", "Acima de 18 Meses"], 
                },
                yaxis: { labels: {show: false }},
                grid: { show: false },
                tooltip: {
                    custom: function ({series, seriesIndex, dataPointIndex, w}) {

                        return ('<table class="table"><tr><th>Total: ' + formatados[dataPointIndex] + '</th></tr></table>');
                    }
                }
            };

            if (updt != null) {

                chart.updateOptions(options); 
            } else {

                chart = new ApexCharts(document.querySelector("#chart"), options); 
                chart.render(); 

                $("#loadingChart").hide();
            }

            $(".apexcharts-legend-text").each(function ( index, value ) {

                $(value).click(function () {
                    createTable(index);
                    ancora();
                });
            });
        });
    }

    function createTable( filtro  ) 
    {
        if ( $.fn.DataTable.isDataTable('#data-table') ) { $('#data-table').DataTable().destroy(); }

        var table = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 20,
            searching: false,
            ajax: {
                url: $('#data-table').data('url'),
                type: "POST",
                data: {
                    id_fornecedor: $("#id_fornecedor").val(),
                    filtro: filtro
                }
            },
             order: [[1, "ASC"]],
            columns: [
                { data: 'codigo'},
                { data: 'nome_comercial', className: 'text-nowrap' },
                { data: 'marca'},
                { data: 'lote'},
                { data: 'estoque'},
                { data: 'qtd_unidade'},
                { data: 'preco_unitario', className: 'text-left'},
            ],
            rowCallback: function (row, data) {

            },
            drawCallback: function() {

                $('[data-toggle="tooltip"]').tooltip();
            },
        });
    }

    function ancora() 
    {
        var target_offset = $("#ancora").offset();
        var target_top = target_offset.top;
        $('html,body').animate({scrollTop: target_top},'slow');
    }
</script>