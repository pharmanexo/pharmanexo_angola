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

                <h5 class="card-title text-muted">Filtros</h5>

                <div class="row">
                    <div class="col-3 form-group">
                        <label for="fornecedor">Fornecedor</label>
                        <select class="select2" id="fornecedor" data-placeholder="Selecione" data-allow-clear="true">
                            <option></option>
                            <?php foreach($fornecedores as $f) { ?>
                                <option value="<?php echo $f['id']; ?>"><?php echo $f['cnpj'] . ' - ' . $f['nome_fantasia']; ?></option>
                            <?php } ?>
                        </select>
                    </div>  

                    <div class="col-3 form-group">
                        <label for="nivel">Tipo de envio</label>
                        <select class="select2" id="nivel" data-placeholder="Selecione" data-allow-clear="true">
                            <option></option>
                            <option value="2">Automática</option>
                            <option value="1">Manual</option>
                            <option value="3">Mix</option>
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
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="data-table" class="table table-condensed table-hover" 
                    data-url="<?php echo $datatables; ?>" 
                    data-exportar="<?php echo $url_exportar; ?>"
                    data-cotacoes="<?php echo $url_cotacoes; ?>"
                    >
                        <thead>
                            <tr>
                                <th hidden></th>
                                <th>Fornecedor</th>
                                <th class='text-center'>Total de Cotações</th>
                                <th class='text-center'>Valor Total</th>
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
</body>
<?php echo $scripts; ?>
<script>

    var url_exportar = $("#data-table").data('exportar');
    var url_cotacoes = $("#data-table").data('cotacoes');

    $(function () {

        $("#filter-start-date").flatpickr({ "locale": "pt", "dateFormat": "d/m/Y", 'defaultDate': "<?php echo date('01/m/Y'); ?>" });
        $("#filter-end-date").flatpickr({ "locale": "pt", "dateFormat": "d/m/Y", 'defaultDate': "<?php echo date('t/m/Y'); ?>" });

        var data = getData();
        getDados(data.dataini, data.datafim, $('#fornecedor').val(), $('#nivel').val());

        $("#filter-start-date, #filter-end-date, #fornecedor, #nivel").on('change', function () {

            var data = getData();

            $('#btnExport').attr('href', `${url_exportar}/${data.dataini}/${data.datafim}/${$('#fornecedor').val()}/${$('#nivel').val()}` );

            getDados(data.dataini, data.datafim, $('#fornecedor').val(), $('#nivel').val());
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

    function getDados(dataini, datafim, id_fornecedor, nivel) 
    {
        $.ajax({
            url: $('#data-table').data('url'),
            type: 'POST',
            data: {
                id_fornecedor: id_fornecedor,
                dataini: dataini,
                datafim: datafim,
                nivel: nivel
            },
            dataType: "json",
            responsive: true,
            success: function(response) {

                var rows = [];
                $.map(response, function (row) {

                    var line = `
                    <tr>
                        <td hidden>${row.id}</td>
                        <td class='text-nowrap'>${row.fornecedor}</td>
                        <td class='text-center'>${row.qtd_total}</td>
                        <td class='text-center'>${row.valor_total}</td>
                    </tr>`;

                    rows = rows + line;
                });
                    
                new_table(rows); 
            }
        });
    }

    function new_table(data = null) 
    {

        if ( $.fn.DataTable.isDataTable('#data-table') ) {

            $('#data-table').DataTable().destroy();
        }

        $('#data-table').find('tbody').html('');

        if(data != null) {
            $('#data-table').find('tbody').append(`${data}`);
        }
        
        var table = $('#data-table').DataTable({
            serverSide: false,
            lengthChange: false,
            responsive: true,
            searching: false,
            columns: [
                null,
                null,
                null,
                null
            ],
            rowCallback: function (row, data) {
                $(row).css('cursor', 'pointer');

                $('td', row).each(function () {
                    $(this).on('click', function (e) {

                        e.preventDefault();

                        if ( data[0] != undefined ) {

                            var date = getData();
                            var id_fornecedor = data[0];

                            window.location.href = `${url_cotacoes}${date.dataini}/${date.datafim}/${id_fornecedor}`;
                        } 
                    });
                });
            },
            drawCallback: function () {}
        });
    }

</script>

</html>