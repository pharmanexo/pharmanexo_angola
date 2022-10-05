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
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="filtro-cliente">Comprador</label>
                        <select class="select2" id="filtro-cliente" data-index="1">
                            <option value="">Selecione</option>
                            <?php foreach($compradores as $comprador) { ?>
                             <option value="<?php echo $comprador['id'] ?>"><?php echo "{$comprador['razao_social']} - {$comprador['cnpj']}" ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="cd_cotacao">Cotação</label>
                        <select class="select2" id="cd_cotacao">
                            <option value="">Selecione</option>
                             <?php foreach($cotacoes as $cot) { ?>
                             <option value="<?php echo $cot['cd_cotacao'] ?>"><?php echo $cot['cd_cotacao'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Data Inicio</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="zmdi zmdi-calendar"></i></span>
                            </div>
                            <input type="date" class="form-control hidden-md-up" placeholder="Selecione uma data">
                            <input type="text" id="filter-start-date" class="form-control date-picker hidden-sm-down" placeholder="Selecione">
                        </div>
                    </div>  
                    <div class="col-md-3">
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
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="data-table" class="table table-condensed table-hover no-filtered" data-update="<?php echo $url_detalhes; ?>" data-url="<?php echo $dataTable; ?>">
                        <thead>
                        <tr>
                            <th>Codigo Cotação</th>
                            <th>Comprador</th>
                            <th>Data da Cotação</th>
                            <th>Valor Total</th>
                            <th></th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<?php echo $scripts; ?>
<script>
    $(function () {

        $("#filter-start-date").flatpickr({ "locale": "pt", "dateFormat": "d/m/Y", 'defaultDate': "<?php echo date('01/m/Y'); ?>" });
        $("#filter-end-date").flatpickr({ "locale": "pt", "dateFormat": "d/m/Y", 'defaultDate': "<?php echo date('t/m/Y'); ?>" });


        var dt = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 50,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'post',
                dataType: 'json',
                data: function (data) {
                    let nw_data = data;
            
                    if ($('#filter-start-date').val() !== '') {
                        let dt1 = $('#filter-start-date').val().split('/');
                        let dt2 = ($('#filter-end-date').val() !== '') ? $('#filter-end-date').val().split('/') : dt1;
                        nw_data.columns[2].search.value = `${dt1[2]}-${dt1[1]}-${dt1[0]},${dt2[2]}-${dt2[1]}-${dt2[0]}`;
                        nw_data.columns[2].search.type = 'date';
                    }

                    if ($('#filtro-cliente').val() !== '') {
                        nw_data.columns[4].search.value = $('#filtro-cliente').val();
                        nw_data.columns[4].search.type = 'equal';
                    }

                    if ($('#cd_cotacao').val() !== '') {
                        nw_data.columns[0].search.value = $('#cd_cotacao').val();
                        nw_data.columns[0].search.type = 'equal';
                    }

                    return nw_data;
                }
            },
            "order": [[ 2, "desc" ]],
            columns: [
                {name: 'cd_cotacao', data: 'cd_cotacao', searchable: true},
                {name: 'razao_social', data: 'razao_social', searchable: true},
                {name: 'data_cotacao', data: 'data_cotacao', searchable: true},
                {name: 'valor_total', data: 'valor_total', searchable: true },
                {name: 'id_cliente', data: 'id_cliente', searchable: true, visible: false},
            ],
            rowCallback: function (row, data) {
                $(row).data('cd_cotacao', data.cd_cotacao).css('cursor', 'pointer');

            },
            drawCallback: function () {
                $(".dataTables_filter").hide(); 
                $('table tbody tr').each(function () {
                    $(this).on('click', function () {
                        if ($(this).data('cd_cotacao') != undefined) {
                            window.location.href = $('#data-table').data('update') + $(this).data('cd_cotacao');
                        }
                    })
                })
            }
        });

        $('[data-index]').on('change', function () {
            dt.ajax.reload();
        });

        $('#filter-start-date, #filter-end-date, #cd_cotacao').on('change', function() {
             dt.ajax.reload();
        });

        // remove filter
        $('[data-action="reset-filter"]').click(function (e) {
            e.preventDefault();
            $('[data-index]').val(null);
            $('#data-table').columns([0, 1, 2, 4]).search('').draw();
        });
    });
</script>

</html>