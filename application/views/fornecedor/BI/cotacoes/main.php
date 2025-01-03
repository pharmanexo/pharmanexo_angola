<!DOCTYPE html>
<html lang="pt-BR">
<?php echo $header; ?>
<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>
<div class="content">

     <div class="row">

            <div class="col-4">
                <div class="form-group">
                    <label for="id_cliente">Comprador</label>
                    <select class="select2" name="id_cliente" id="id_cliente" data-allow-clear="true" data-placeholder="Selecione">
                        <option></option>
                        <?php foreach($compradores as $c): ?>
                            <option value="<?php echo $c['id']; ?>"><?php echo $c['comprador']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-4">
                <label>Data Inicio</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="zmdi zmdi-calendar"></i></span>
                    </div>
                    <input type="date" class="form-control hidden-md-up" placeholder="Selecione uma data">
                    <input type="text" name="dataini" id="dataini" class="form-control date-picker hidden-sm-down" placeholder="Selecione">
                </div>
            </div>

            <div class="col-4">
                <label>Data fim</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="zmdi zmdi-calendar"></i></span>
                    </div>
                    <input type="date" class="form-control hidden-md-up" placeholder="Selecione uma data">
                    <input type="text" name="datafim" id="datafim" class="form-control date-picker hidden-sm-down" placeholder="Selecione">
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-3">
                <div class="form-group">
                    <label for="uf_cotacao">Estado</label>
                    <select class="select2" name="uf_cotacao" id="uf_cotacao" data-allow-clear="true" data-placeholder="Selecione">
                        <option></option>
                        <?php foreach($estados as $estado): ?>
                            <option value="<?php echo $estado['uf']; ?>"><?php echo $estado['estado']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-3">
                <label>Integrador</label>
                <div class="form-group">
                    <select class="select2" name="integrador" id="integrador">
                        <?php if ($this->session->has_userdata('credencial_bionexo')): ?>
                            <option value="BIONEXO">Bionexo</option>
                        <?php endif; ?>
                        <option value="SINTESE" selected>Sintese</option>
                    </select>
                </div>
            </div>

            <?php if( isset($selectMatriz) ): ?>
                <div class="col-3">
                    <div class="form-group">
                        <label for="id_fornecedor">Lojas</label>
                        <select class="select2" name="id_fornecedor" id="id_fornecedor" data-placeholder="Selecione">
                            <option></option>
                            <?php foreach($selectMatriz as $f): ?>
                                <option value="<?php echo $f['id']; ?>" <?php if( $this->session->id_fornecedor == $f['id'] ) echo 'selected'; ?>  ><?php echo $f['nome_fantasia']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php endif; ?>

            <div class="col-3 mt-4">
                <div class="form-group">
                    <button type="button" class="btn btn-outline-primary btn-block mt-1" id="btnFiltrar"><i class="fas fa-search"></i> Filtrar</button>
                </div>
            </div>
        </div>

    <h5 class="text-muted text-center" id="loadingCharts"> <i class="fas fa-spin fa-spinner"></i> Buscando informações no banco de dados... </h5>

    <div id="dash" hidden>

        <div class="row">

            <div class="col-6">
                <div class="card" id="cardChart" hidden>
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
                                        <i class="fas fa-chart-pie"></i> COTAÇÕES
                                    </h4>

                                    <h2 class="text-center text-white total_cotacao" counter="0">0</h2>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-center text-white mb-3">
                                        <i class="fas fa-balance-scale"></i> DE -> PARA
                                    </h4>

                                    <h2  class="text-center text-white total_depara" counter="0">0</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card gradient-primary painel-cotacoes">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6 border-right">
                                    <h4 class="text-center text-white mb-3">
                                        <i class="fas fa-chart-pie"></i> TOTAL OFERTADO
                                    </h4>

                                    <h2 class="text-center text-white total_oferta" counter="0">0</h2>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-center text-white mb-3">
                                        <i class="fas fa-dollar-sign"></i> TOTAL OFERTADO (R$)
                                    </h4>

                                    <h2 class="text-center text-white total_ofertado" counter="0" data-formatado="" data-number="1">0</h2>
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
                            <table id="data-table" class="table table-condensed table-hover" data-url="<?php echo $urlRelatorioCotacoes; ?>" data-detail="<?php echo $urlRelatorioProdutosCotacao; ?>">
                                <thead>
                                    <tr>
                                        <th>Cotação</th>
                                        <th>Comprador</th>
                                        <th>Data Início</th>
                                        <th>Data Término</th>
                                        <th>UF</th>
                                        <th>Itens</th>
                                        <th></th>
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

    $(document).ajaxStop(function () { load(); });

    $(function () {

        $("#dataini").flatpickr({ "locale": "pt", "dateFormat": "d/m/Y", 'defaultDate': "<?php echo date('01/m/Y'); ?>" });
        $("#datafim").flatpickr({ "locale": "pt", "dateFormat": "d/m/Y", 'defaultDate': "<?php echo date('t/m/Y'); ?>" });

        main();

        $("#btnFiltrar").on('click', function () { main(1); });
    })

    function load() 
    {
        $("#loadingCharts").hide();
        $("#dash").attr('hidden', false);
    }

    function main( updt = null ) 
    {
        var periodo = getData();

        var data = {
            dataini: periodo.dataini,
            datafim: periodo.datafim,
            id_cliente: $("#id_cliente").val(),
            uf_cotacao: $("#uf_cotacao").val(),
            id_fornecedor: ( $("#id_fornecedor").val() != undefined ) ? $("#id_fornecedor").val() : ''
        };

        $.post(url, data, function(xhr) {  

            if ( typeof xhr == "string" ) { xhr = JSON.parse(xhr); }

            createIndicadores(xhr.indicadores, xhr.valor_formatado);

            var options = {
                series: xhr.series,
                chart: {
                    type: 'donut',
                    height: 270,
                    events: {
                        dataPointSelection: function( event, chartContext, config ) {

                            if ( config.dataPointIndex == 0 ) { createTable(1); }
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
                colors: ['#CCC', '#766dbc'],
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                show: true,
                                total: {showAlways: true, show: true }
                            }
                        }
                    }
                },
                labels: xhr.labels,
                responsive: [
                {
                    breakpoint: '480',
                    options: {
                        legend: { position: 'bottom' }
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
            step: function(now) {

                if (  $(`.${key}`).data('number') !== undefined ) {

                    $(this).text(formatReal(Math.floor(now)));
                } else {

                    $(this).text(Math.ceil(now));
                }
            },
            complete: function () {

                if ( $(`.${key}`).data('number') !== undefined ) {

            
                    $(this).text( $(this).data('formatado') );
                }
            }
        });
    };

    function getMoney( str )
    {

        return parseInt( str.replace(/[\D]+/g,'') );
    }

    function mascaraValor(valor) 
    {
        valor = valor.toString().replace(/\D/g,"");
        valor = valor.toString().replace(/(\d)(\d{8})$/,"$1.$2");
        valor = valor.toString().replace(/(\d)(\d{5})$/,"$1.$2");
        valor = valor.toString().replace(/(\d)(\d{2})$/,"$1,$2");
        return valor;
    }

    function formatReal( int )
    {
        var tmp = int+'';
        tmp = tmp.replace(/([0-9]{2})$/g, ",$1");
        if( tmp.length > 6 )
                tmp = tmp.replace(/([0-9]{3}),([0-9]{2}$)/g, ".$1,$2");

        return tmp;
    }

    function createIndicadores( dados, valorFormatado ) 
    {
        $(".total_ofertado").data('formatado', valorFormatado);

        Object.entries(dados).forEach(([key, value]) => {

            update_users_count(key, value);
        });
    }

    function getData() 
    {

        var dt1 = $("#dataini").val().split('/');
        var dt2 = $("#datafim").val().split('/');

        var dataini =  `${dt1[2]}-${dt1[1]}-${dt1[0]}`;
        var datafim =  `${dt2[2]}-${dt2[1]}-${dt2[0]}`;

        return { dataini: dataini, datafim: datafim };
    }

    function createTable(restricao = null) 
    {

        if ( $.fn.DataTable.isDataTable('#data-table') ) {

            $('#data-table').DataTable().destroy();
        }

        if ( restricao == 1 ) {

            var columns = [
                { name: 'cot.cd_cotacao', data: 'cd_cotacao', className: 'text-nowrap' },
                { name: 'comp.razao_social', data: 'comprador', className: 'text-nowrap'},
                { name: 'cot.dt_inicio_cotacao', data: 'dt_inicio_cotacao', className: 'text-nowrap'},
                { name: 'cot.dt_fim_cotacao', data: 'dt_fim_cotacao', className: 'text-nowrap'},
                { name: 'cot.uf_cotacao', data: 'uf_cotacao'},
                { name: 'qtd_itens', data: 'qtd_itens', className: 'text-center'},
                { defaultContent: '', orderable: false, searchable: false },
            ];

            var url = $('#data-table').data('url') + '/1';
        } else {

            var columns = [
                { name: 'x.cd_cotacao', data: 'cd_cotacao', className: 'text-nowrap' },
                { name: 'x.razao_social', data: 'comprador', className: 'text-nowrap'},
                { name: 'x.dt_inicio_cotacao', data: 'dt_inicio_cotacao', className: 'text-nowrap'},
                { name: 'x.dt_fim_cotacao', data: 'dt_fim_cotacao', className: 'text-nowrap'},
                { name: 'x.uf_cotacao', data: 'uf_cotacao'},
                { name: 'qtd_itens', data: 'qtd_itens', className: 'text-center', orderable: false},
                { defaultContent: '', orderable: false, searchable: false },
            ];


            var url = $('#data-table').data('url');
        }

        var periodo = getData();

        var table = $('#data-table').DataTable({
            processing: false,
            serverSide: true,
            pageLength: 5,
            searching: false,
            ajax: {
                url: url,
                type: "POST",
                data: {
                    dataini: periodo.dataini,
                    datafim: periodo.datafim,
                    id_cliente: $("#id_cliente").val(),
                    uf_cotacao: $("#uf_cotacao").val(),
                    id_fornecedor: ( $("#id_fornecedor").val() != undefined ) ? $("#id_fornecedor").val() : ''
                }
            },
            columns: columns,
            rowCallback: function (row, data) {
                $('td:eq(1)', row).html(`<a data-toggle="tooltip" title="${data.cnpj} - ${data.razao_social}">${data.comprador}</a>`);


                if ( restricao != 1 ) {

                    var icon = "";

                    if ( data.depara == "N" ) {

                        var bolinha_branca = '<a class="mr-2" data-toggle="tooltip" title="Sem De -> Para"><i class="far fa-circle"></i></a>';

                        icon = icon + bolinha_branca;
                    }

                    if ( data.depara == "S" && data.oferta == "S" ) {

                        bolinha_verde = '<a class="mr-2" data-toggle="tooltip" title="De -> Para/ Cotado"><i class="fas fa-circle" style="color: #008000"></i></a>';
                        icon = icon + bolinha_verde;
                    }

                    if( data.depara == "S" && data.oferta == "N" ) {

                        bolinha_vermelha = '<a data-toggle="tooltip" title="De -> Para/ Não Cotado"><i class="fas fa-circle" style="color: #FF0000"></i></a>';
                        icon = icon + bolinha_vermelha;
                    }

                    $(row).css('cursor', 'pointer');

                    $('td:eq(6)', row).html(icon);

                    $(row).on('click', function () { window.location.href = $('#data-table').data('detail') + '/' + data.cd_cotacao + '/' + data.id_fornecedor; });
                }
            },
            drawCallback: function() {

                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    }
</script>