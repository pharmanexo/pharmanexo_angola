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

                    <h5 class="card-title text-muted">Selecione o fornecedor para exibir as cotações</h5>
                    
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label for="fornecedor">Fornecedor</label>
                            <select class="select2" id="fornecedor">
                                <option value="">Selecione</option>
                                <?php foreach($fornecedores as $f) { ?>
                                    <option value="<?php echo $f['id']; ?>"><?php echo $f['nome_fantasia']; ?></option>
                                <?php } ?>
                            </select>
                        </div>  
                        <div class="col-md-3 form-group">
                            <label for="comprador">Comprador</label>
                            <select class="select2" id="comprador">
                                <option value="">Selecione</option>
                                <?php foreach($compradores as $c) { ?>
                                    <option value="<?php echo $c['id']; ?>"><?php echo $c['comprador']; ?></option>
                                <?php } ?>
                            </select>
                        </div> 

                        <div class="col-3">
                            <label>Data Inicio</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="zmdi zmdi-calendar"></i></span>
                                </div>
                                <input type="date" class="form-control hidden-md-up" placeholder="Selecione uma data">
                                <input type="text" id="filter-start-date" class="form-control date-picker hidden-sm-down" placeholder="Selecione">
                            </div>
                        </div>  
                        <div class="col-3">
                            <label>Data fim</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="zmdi zmdi-calendar"></i></span>
                                </div>
                                <input type="date" class="form-control hidden-md-up" placeholder="Selecione uma data">
                                <input type="text" id="filter-end-date" class="form-control date-picker hidden-sm-down" placeholder="Selecione">
                            </div>
                        </div>  
                    </div>

                    <div class="row mb-4">
                       
                        <div class="col-4 offset-md-4">
                            <button type="button" id="buscarCotacoes" class="btn btn-primary btn-block">Buscar Cotações</button>
                        </div>
                    </div>
                </div>

                <div class="card-body" id="cardCotacoes" hidden>

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="respondidos-tab" data-toggle="tab" href="#tabRespondidos" role="tab" aria-controls="respondidos" aria-selected="true">Ofertados</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="rejeitados-tab" data-toggle="tab" href="#tabRejeitados" role="tab" aria-controls="rejeitados" aria-selected="false">Não ofertados</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <!-- Tab respondidos -->
                        <div class="tab-pane fade show active" id="tabRespondidos" role="tabpanel" aria-labelledby="respondidos-tab">
                            <div class="row mx-auto mt-3">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="data-table-respondidos" class="table w-100 table-hover"
                                        data-url="<?php echo $url_respondidos; ?>">
                                            <thead>
                                                <tr>
                                                    <th class="text-nowrap">Cotação</th>
                                                    <th class="text-nowrap">Descrição</th>
                                                    <th class="text-nowrap">Comprador</th>
                                                    <th class="text-nowrap">Data inicio</th>
                                                    <th class="text-nowrap">Data Término</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab rejeitados -->
                        <div class="tab-pane fade" id="tabRejeitados" role="tabpanel" aria-labelledby="rejeitados-tab">
                            <div class="row mx-auto mt-3">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="data-table-rejeitados" class="table w-100 table-hover" data-url="<?php echo $url_rejeitados; ?>">
                                            <thead>
                                                <tr>
                                                    <th class="text-nowrap">Cotação</th>
                                                    <th class="text-nowrap">Descrição</th>
                                                    <th class="text-nowrap">Comprador</th>
                                                    <th class="text-nowrap">Data inicio</th>
                                                    <th class="text-nowrap">Data Término</th>
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
</body>

<?php echo $scripts; ?>

<script>
    var url_update = "<?php if(isset($url_update)) echo $url_update; ?>";

    $(function() {

        $("#filter-start-date").flatpickr({ "locale": "pt", "dateFormat": "d/m/Y", 'defaultDate': "<?php echo date('01/m/Y'); ?>" });
        $("#filter-end-date").flatpickr({ "locale": "pt", "dateFormat": "d/m/Y", 'defaultDate': "<?php echo date('t/m/Y'); ?>" });

        $("#buscarCotacoes").on('click', function(e) {

            e.preventDefault();

            if ( $('#fornecedor').val() == '' ) {

                formWarning({ type: 'warning', message: "O campo fornecedor é obrigatório!"});
            }  else {

                $("#cardCotacoes").attr('hidden', true);
                $('#buscarCotacoes').prop('disabled', true);
                $("#buscarCotacoes").html(`<i class='fas fa-spinner fa-spin '></i> Carregando cotações...`);

                var data = getData();

                var id_fornecedor = $('#fornecedor').val();
                var id_cliente = $('#comprador').val();

                if ( id_cliente == '' ) {

                    var url_respondidos = $('#data-table-respondidos').data('url') + `${data.dataini}/${data.datafim}/${id_fornecedor}`;
                    var url_rejeitados = $('#data-table-rejeitados').data('url') + `${data.dataini}/${data.datafim}/${id_fornecedor}`;
                } else {

                    var url_respondidos = $('#data-table-respondidos').data('url') + `${data.dataini}/${data.datafim}/${id_fornecedor}/${id_cliente}`;
                    var url_rejeitados = $('#data-table-rejeitados').data('url') + `${data.dataini}/${data.datafim}/${id_fornecedor}/${id_cliente}`;
                }

                var urls = {respondidos: url_respondidos, rejeitados: url_rejeitados, };

                new_table(urls);
            }
        });
    });

    function getData() {

        if ( $('#filter-start-date').val() !== '' ) {

            var dt1 = $('#filter-start-date').val().split('/');
            var dt2 = ($('#filter-end-date').val() !== '') ? $('#filter-end-date').val().split('/') : dt1;
        } else {

            var dt1 = $('#filter-start-date').data('dt').split('/');
            var dt2 = ($('#filter-end-date').val() !== '') ? $('#filter-end-date').val().split('/') : dt1;
        }

        var dataini =  `${dt1[2]}-${dt1[1]}-${dt1[0]}`;
        var datafim =  `${dt2[2]}-${dt2[1]}-${dt2[0]}`;

        return { dataini: dataini, datafim: datafim };
    }

    function new_table(urls) 
    {
        if ( $.fn.DataTable.isDataTable('#data-table-respondidos') )  $('#data-table-respondidos').DataTable().destroy();
        if ( $.fn.DataTable.isDataTable('#data-table-rejeitados') )  $('#data-table-rejeitados').DataTable().destroy();


        var dt1 = $('#data-table-respondidos').DataTable({
            serverSide: true,
            processing: true,
            lengthChange: false,
            responsive: true,
            searching: false,
            ajax: {
                url: urls.respondidos,
                type: 'post',
                dataType: 'json',
            },
            columns: [
                {name: 'cot.cd_cotacao', data: 'cd_cotacao', className: 'text-nowrap'},
                {name: 'cot.ds_cotacao', data: 'ds_cotacao', className: 'text-nowrap'},
                {name: 'cot.id_cliente', data: 'comprador', searchable: false, className: 'text-nowrap'},
                {name: 'cot.dt_inicio_cotacao', data: 'dt_inicio_cotacao', searchable: false, className: 'text-nowrap'},
                {name: 'cot.dt_fim_cotacao', data: 'dt_fim_cotacao', searchable: false, className: 'text-nowrap'},
            ],
            order: [[ 0, "asc" ]],
            rowCallback: function (row, data) {
                $(row).css('cursor', 'pointer');

                $('td').each(function () {
                    $(this).on('click', function () {
                        window.location.href = url_update + data.id_fornecedor  + '/' + data.cd_cotacao
                    })
                })
            },
            drawCallback: function () {}
        });

        var dt2 = $('#data-table-rejeitados').DataTable({
            serverSide: true,
            processing: true,
            lengthChange: false,
            responsive: true,
            searching: false,
            ajax: {
                url: urls.rejeitados,
                type: 'post',
                dataType: 'json',
            },
            columns: [
                {name: 'cot.cd_cotacao', data: 'cd_cotacao', className: 'text-nowrap'},
                {name: 'cot.ds_cotacao', data: 'ds_cotacao', className: 'text-nowrap'},
                {name: 'cot.id_cliente', data: 'comprador', searchable: false, className: 'text-nowrap'},
                {name: 'cot.dt_inicio_cotacao', data: 'dt_inicio_cotacao', searchable: false, className: 'text-nowrap'},
                {name: 'cot.dt_fim_cotacao', data: 'dt_fim_cotacao', searchable: false, className: 'text-nowrap'},
            ],
            order: [[ 0, "asc" ]],
            rowCallback: function (row, data) {
                $(row).css('cursor', 'pointer');

                $('td').each(function () {
                    $(this).on('click', function () {
                        window.location.href = url_update + data.id_fornecedor  + '/' + data.cd_cotacao
                    })
                })

            },
            drawCallback: function () {},
            initComplete: function(settings, json) {

                $('#buscarCotacoes').prop('disabled', false);
                $("#buscarCotacoes").html("Buscar Cotações");
                $("#cardCotacoes").attr('hidden', false);
            }
        });
    }
</script>

</html>