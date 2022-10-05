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

                    <div class="col-12 mb-3">
                        <small>Selecione um fornecedor para exibir os dados</small>
                    </div>

                    <div class="col-6 form-group">
                        <label for="fornecedor">Fornecedor</label>
                        <select class="select2" id="fornecedor" data-placeholder="Selecione" data-allow-clear="true">
                            <option></option>
                            <?php foreach($fornecedores as $f) { ?>
                                <option value="<?php echo $f['id']; ?>"><?php echo $f['cnpj'] . ' - ' . $f['nome_fantasia']; ?></option>
                            <?php } ?>
                        </select>
                    </div>  

                    <div class="col-6 form-group">
                        <label for="comprador">Comprador</label>
                        <select class="select2" id="comprador" data-placeholder="Selecione" data-allow-clear="true">
                            <option></option>
                            <?php foreach($compradores as $c) { ?>
                                <option value="<?php echo $c['id']; ?>"><?php echo $c['cnpj'] . ' - ' . $c['razao_social']; ?></option>
                            <?php } ?>
                        </select>
                    </div>  
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="data-table" class="table table-condensed table-hover" data-url="<?php echo $datatables; ?>" data-exportar="<?php echo $url_exportar; ?>">
                        <thead>
                            <tr>
                                <th>Cotação</th>
                                <th>Descrição</th>
                                <th>Comprador</th>
                                <th>Data Inicio</th>
                                <th>Data Término</th>
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
</body>
<?php echo $scripts; ?>
<script>

    var url_exportar = $("#data-table").data('exportar');

    $(function () {

       
        new_table();

        $("#fornecedor, #comprador").on('change', function () {

            if ( $("#fornecedor").val() != "" ) {


                $('#btnExport').attr('href', `${url_exportar}/${$('#fornecedor').val()}/${$('#comprador').val()}` );

                getDados($('#fornecedor').val(), $('#comprador').val());
            }
        });
    });

    function getDados(id_fornecedor, id_cliente) 
    {
        $.ajax({
            url: $('#data-table').data('url'),
            type: 'POST',
            data: {
                id_fornecedor: id_fornecedor,
                id_cliente: id_cliente
            },
            dataType: "json",
            responsive: true,
            success: function(response) {
               
                var rows = [];
                $.map(response, function (row) {

                    var line = `
                    <tr>
                        <td class='text-nowrap'>${row.cd_cotacao}</td>
                        <td class='text-nowrap'><small>${row.ds_cotacao}</small></td>
                        <td><small>${row.comprador}</small></td>
                        <td>${row.dataini}</td>
                        <td>${row.datafim}</td>
                        <td hidden>${row.dt_fim_cotacao}</td>
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
                null,
                null,
                null
            ],
            order: [[ 5, "asc" ]],
            rowCallback: function (row, data) {},
            drawCallback: function () {}
        });
    }
</script>
</html>