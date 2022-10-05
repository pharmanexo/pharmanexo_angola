<!DOCTYPE html>
<html lang="pt-BR">
<?php echo $header; ?>
<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>
<div class="content">

    <div class="row mb-0">

        <div class="col-4">
            <div class="form-group">
                <label for="id_cliente">Comprador</label>
                <select class="select2" name="id_cliente" id="id_cliente" data-allow-clear="true" data-placeholder="Selecione">
                    <option></option>
                    <?php foreach ($compradores as $c): ?>
                        <option value="<?php echo $c['id']; ?>"><?php echo $c['comprador']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="col-4">
            <div class="form-group">
                <label for="uf_cotacao">Estado</label>
                <select class="select2" name="uf_cotacao" id="uf_cotacao" data-allow-clear="true" data-placeholder="Selecione">
                    <option></option>
                    <?php foreach ($estados as $estado): ?>
                        <option value="<?php echo $estado['id']; ?>"><?php echo $estado['estado']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <?php if (isset($selectMatriz)): ?>
            <div class="col-4">
                <div class="form-group">
                    <label for="id_fornecedor">Lojas</label>
                    <select class="select2" name="id_fornecedor" id="id_fornecedor" data-placeholder="Selecione">
                        <option></option>
                        <?php foreach ($selectMatriz as $f): ?>
                            <option value="<?php echo $f['id']; ?>" <?php if( $f['id'] == $this->session->id_fornecedor ) echo 'selected'; ?> ><?php echo $f['nome_fantasia']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="row mt-0">

        <div class="col-4">
            <div class="form-group">
                <label for="promocao">COM PROMOÇÃO</label>
                <select name="promocao" id="promocao" class="select2" data-allow-clear="true" data-placeholder="Não" data-minimum-results-for-search="Infinity">
                    <option></option>
                    <option value="SIM">SIM</option>
                </select>
            </div>
        </div>

        <div class="col-4">
            <div class="form-group">
                <label for="desconto">COM DESCONTO</label>
                <select name="desconto" id="desconto" class="select2" data-allow-clear="true" data-placeholder="Não" data-minimum-results-for-search="Infinity">
                    <option></option>
                    <option value="SIM">SIM</option>
                </select>
            </div>
        </div>

        <div class="col-4 mt-4">
            <div class="form-group">
                <button type="button" class="btn btn-outline-primary btn-block mt-1" id="btnFiltrar"><i class="fas fa-search"></i> Filtrar</button>
            </div>
        </div>
    </div>

    <h5 class="text-muted text-center" id="loadingCharts"> <i class="fas fa-spin fa-spinner"></i> Buscando informações no banco de dados... </h5>

    <div id="dash" hidden>
        
        <div class="row">

            <div class="col-6">
                <div class="card" id="cardChart">
                    <div class="card-body">
                        <div id="chart"></div>
                    </div>
                </div>
            </div>

            <div class="col-6">

                <div id="painel-gradient">

                    <div class="card gradient-primary painel-cotacoes">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6 border-right">
                                    <h4 class="text-center text-white mb-3">
                                        <i class="fas fa-chart-pie"></i> EM PROMOÇÃO
                                    </h4>

                                    <h2 class="text-center text-white PROMOCAO" counter="0">0</h2>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-center text-white mb-3">
                                        <i class="fas fa-robot"></i> EM AUTOMATICO
                                    </h4>

                                    <h2 class="text-center text-white AUTOMATICO" counter="0">0</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card gradient-primary painel-cotacoes">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6 border-right">
                                    <h4 class="text-center text-white mb-3">
                                        <i class="fas fa-network-wired"></i> EM DIST X DIST
                                    </h4>

                                    <h2 class="text-center text-white DISTRIBUIDOR" counter="0">0</h2>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-center text-white mb-3">
                                        <i class="fas fa-dollar-sign"></i> COM DESCONTO
                                    </h4>

                                    <h2 class="text-center text-white DESCONTO" counter="0">0</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive" id="tabelaCotacoes">
                            <table id="data-table" class="table table-condensed table-hover" data-url="<?php echo $to_datatable; ?>">
                                <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Produto</th>
                                    <th>Desconto</th>
                                    <th>Tipo</th>
                                    <th>Preço Unitário</th>
                                    <th>Preço c/ Desconto</th>
                                    <th>Regra Venda</th>
                                    <th>Promoção</th>
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
    // var heightChart = $("#painel-gradient").height() - 20;

    $(document).ajaxStop(function () { load(); });

    $(function () {

        main();

        $("#btnFiltrar").on('click', function () { main(1); });

        $("#uf_cotacao").on('change', function () { 

            $('#id_cliente').val(null);
            $('#id_cliente').trigger('change.select2');
        });
        $("#id_cliente").on('change', function () { 

            $('#uf_cotacao').val(null);
            $('#uf_cotacao').trigger('change.select2');
        });
    });

    function load() 
    {
        $("#loadingCharts").hide();
        $("#dash").attr('hidden', false);
    }

    function main(updt = null) 
    {
        var data = {
            id_cliente: $("#id_cliente").val(),
            uf_cotacao: $("#uf_cotacao").val(),
            promocao: $("#promocao").val(),
            desconto: $("#desconto").val(),
            id_fornecedor: ($("#id_fornecedor").val() != undefined) ? $("#id_fornecedor").val() : ''
        };

        $.post(url, data, function (xhr) {

            if (typeof xhr == "string") {xhr = JSON.parse(xhr); }

            createIndicadores(xhr.indicadores);

            var options = {
                series: xhr.series,
                chart: {
                    type: 'donut',
                    height: 270,
                    events: {
                        click: function (event, chartContext, config) {
                            // window.location.href = "http://www.devmedia.com.br";
                        }
                    },
                    toolbar: {
                        show: true,
                        tools: {
                            download: true
                        }
                    }
                },
                legend: {
                    show: true,
                    position: 'bottom'
                },
                colors: ['#CCC', '#766dbc', '#3795bc', '#bc9d7f'],
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                show: true,
                                total: {showAlways: true, show: true}
                            }
                        }
                    }
                },
                labels: xhr.labels,
                responsive: [
                    {
                        breakpoint: '480',
                        options: {
                            legend: {position: 'bottom'}
                        },
                    }]
            };

            if (updt != null) {

                chart.updateOptions(options);
            } else {

                chart = new ApexCharts(document.querySelector("#chart"), options);
                chart.render();
            }

            $("#cardChart").prop("hidden", false);
        });

        createTable();
    }

    function update_users_count( key, value ) 
    {
        $(`.${key}`).animate({
            counter: value
        }, {
            duration: 3000,
            easing: 'swing',
            step: function (now) {


                if ($(`.${key}`).data('number') !== undefined) {

                    $(this).text(formatReal(Math.floor(now)));
                } else {

                    $(this).text(Math.ceil(now));
                }
            },
            complete: function () {

                if ($(`.${key}`).data('number') !== undefined) {

                    var a = getMoney($(`.${key}`).text());
                    ;

                    $(`.${key}`).text(formatReal(a));
                }
            }
        });
    }

    function getMoney( str )
    {

        return parseInt( str.replace(/[\D]+/g,'') );
    }

    function formatReal( int )
    {
        var tmp = int+'';
        tmp = tmp.replace(/([0-9]{2})$/g, ",$1");
        if( tmp.length > 6 )
                tmp = tmp.replace(/([0-9]{3}),([0-9]{2}$)/g, ".$1,$2");

        return tmp;
    }

    function createIndicadores( dados ) 
    {
        Object.entries(dados).forEach(([key, value]) => {

            update_users_count(key, value);
        });
    }

    function createTable() 
    {

        if ($.fn.DataTable.isDataTable('#data-table')) {

            $('#data-table').DataTable().destroy();
        }

        var url = $('#data-table').data('url');

        var table = $('#data-table').DataTable({
            processing: false,
            serverSide: true,
            pageLength: 15,
            searching: false,
            ajax: {
                url: url,
                type: "POST",
                data: {
                    id_cliente: $("#id_cliente").val(),
                    uf_cotacao: $("#uf_cotacao").val(),
                    promocao: $("#promocao").val(),
                    desconto: $("#desconto").val(),
                    id_fornecedor: ($("#id_fornecedor").val() != undefined) ? $("#id_fornecedor").val() : ''
                }
            },
            order: [[ 1, "ASC" ]],
            columns: [
                {data: 'codigo', name: 'vd.codigo', className: 'text-nowrap'},
                {data: 'produto', name: 'produto', className: 'text-nowrap'},
                {data: 'desconto_percentual', name: 'desconto_percentual'},
                {data: 'tipo', className: 'text-nowrap', orderable: false},
                {data: 'preco_unitario', className: 'text-center', orderable: false},
                {data: 'preco_desconto', className: 'text-center', orderable: false},
                {data: 'status_regra_venda', className: 'text-center', orderable: false},
                {data: 'promocao', className: 'text-center', orderable: false},
            ],
            rowCallback: function (row, data) {
                $(row).css('cursor', 'pointer');

                var element = table.column(3).header(); 
                if ( data.id_estado != null ) {
                    
                    $(element).html('UF');
                } else {

                    $(element).html('Comprador');
                    $('td:eq(3)', row).html(`<a data-toggle="tooltip" title="${data.cnpj} - ${data.razao_social}">${data.tipo}</a>`);
                }

                $(row).on('click', function () {
                    // window.location.href = $('#data-table').data('detail') + '/' + data.cd_cotacao + '/' + data.id_fornecedor;
                });

            },
            drawCallback: function (row, data) {

                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    }
</script>