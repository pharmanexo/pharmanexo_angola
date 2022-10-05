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
                        <select class="select2" id="fornecedor" data-index="3">
                            <option value="">Selecione</option>
                            <?php foreach($fornecedores as $f) { ?>
                                <option value="<?php echo $f['id']; ?>"><?php echo $f['cnpj'] . ' - ' . $f['nome_fantasia']; ?></option>
                            <?php } ?>
                        </select>
                    </div>  
                    <div class="col-3 form-group">
                        <label for="comprador">Comprador</label>
                        <select class="select2" id="comprador" data-index="4">
                            <option value="">Selecione</option>
                            <?php foreach($compradores as $c) { ?>
                                <option value="<?php echo $c['id']; ?>"><?php echo $c['cnpj'] . ' - ' . $c['razao_social']; ?></option>
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
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="data-table" class="table table-condensed table-hover" data-url="<?php echo $datatables; ?>" data-details="<?php echo $url_detalhes; ?>">
                        <thead>
                            <tr>
                                <th>Comprador</th>
                                <th>Fornecedor</th>
                                <th class='text-center'>Total de Cotações</th>
                                <th class='text-center'>Total de cotações com produto</th>
                                <th hidden></th>
                                <th hidden></th>
                                <th hidden></th>
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

    $(function () {

        $("#filter-start-date").flatpickr({ "locale": "pt", "dateFormat": "d/m/Y", 'defaultDate': "<?php echo date('01/m/Y'); ?>" });
        $("#filter-end-date").flatpickr({ "locale": "pt", "dateFormat": "d/m/Y", 'defaultDate': "<?php echo date('t/m/Y'); ?>" });

        var data = getData();
        getDados($('#fornecedor').val(), $('#comprador').val(), data.dataini, data.datafim);


        $("#filter-start-date, #filter-end-date, #comprador, #fornecedor").on('change', function () {

            var data = getData();

            getDados($('#fornecedor').val(), $('#comprador').val(), data.dataini, data.datafim);
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

    function getDados(id_fornecedor, id_cliente, dataini, datafim) 
    {
        $.ajax({
            url: $('#data-table').data('url'),
            type: 'POST',
            data: {
                id_fornecedor: id_fornecedor,
                id_cliente: id_cliente,
                dataini: dataini,
                datafim: datafim
            },
            dataType: "json",
            responsive: true,
            success: function(response) {

                if (response.type == "success" ) {

                    if ( $.fn.DataTable.isDataTable('#data-table') ) {

                        $('#data-table').DataTable().destroy();
                    }

                    var rows = "";

                    $.map(response.data, function (row) {

                        var line = `
                        <tr>
                            <td class='text-nowrap'>${row.cnpj} - ${row.razao_social}</td>
                            <td class="text-nowrap">${row.nome_fantasia}</td>
                            <td class='text-center'>${row.total}</td>
                            <td class='text-center'>${row.totalProduto}</td>
                            <td hidden>${row.id_fornecedor}</td>
                            <td hidden>${row.id_cliente}</td>
                            <td hidden>${row.razao_social}</td>
                        </tr>`;

                        rows = rows + line;
                    });
                    
                    new_table(rows);  
                } else {

                    $('#data-table').DataTable().destroy();
                    new_table();
                }
            }
        });
    }

    function new_table(data = null) 
    {
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
                null,
                null,
                null,
                null
            ],
            order: [[ 4, "asc" ], [ 6, "asc"] ],
            rowCallback: function (row, data) {
                $(row).css('cursor', 'pointer');

                var id_fornecedor = data[4];
                var id_cliente = data[5];

                if (id_cliente != undefined && id_fornecedor != undefined) {

                    $('td', row).each(function () {
                        $(this).on('click', function () {

                            var dt = getData();

                            var url_details = $('#data-table').data('details');

                            window.location.href = `${url_details}/${id_fornecedor}/${id_cliente}/${dt.dataini}/${dt.datafim}`
                        });
                    });
                }
               
            },
            drawCallback: function () {}
        });
    }

</script>

</html>