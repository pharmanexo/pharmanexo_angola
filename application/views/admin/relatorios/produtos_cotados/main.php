<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>

    <div class="content__inner" id="printAll">
        <div class="card-header">
            <div class="card-header">
                <h4 class="card-title">Selecione um fornecedor para filtrar</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 form-group">
                        <label for="filtro-cliente">Fornecedor</label>
                        <select class="select2 w-100" name="id_fornecedor" id="fornecedores">
                            <option value="">Selecione</option>
                            <?php foreach ($fornecedores as $fornecedor) { ?>
                                <option value="<?php echo $fornecedor['id'] ?>"><?php echo $fornecedor['razao_social'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="table-responsive col-sm">
                    <table id="data-table" class="table table-condensend table-hover" data-url="<?php echo $url_mcotados; ?>" data-export="<?php echo $url_exportar ?>">
                        <thead>
                        <tr>
                            <th>Código</th>
                            <th>Produto</th>
                            <th>Preço Unit.</th>
                            <th>Total</th>
                            <th>Preço Total</th>
                            <th class="text-nowrap">Qtd Solicitada Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="5"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $scripts; ?>

<script>

    var url_mcotados = $('#data-table').data('url');
    var url_exportar = $('#data-table').data('export');

    $(function () {
        new_table();


        $('#fornecedores').on('change', function () {

            $('#btnExport').attr('href', url_exportar + $(this).val());

            if ($(this).val() != "") {
                $.ajax({
                    url: url_mcotados,
                    type: 'POST',
                    data: {id_fornecedor: $(this).val()},
                    dataType: "json",
                    responsive: true,
                    success: function (response) {

                        if (response.type == "success") {

                            $('#data-table').DataTable().destroy();

                            var rows = "";

                            $.map(response.data, function (row) {

                                if (row.preco_unit == null) {
                                    row.preco_unit = '0,0000';
                                }

                                var line = `<tr>
                                    <td> ${row.id_pfv}</td>
                                    <td class="text-nowrap">${row.produto}</td>
                                    <td class="text-nowrap">${row.preco_unit}</td>
                                    <td>${row.total}</td>
                                    <td class="text-nowrap">${row.preco_total}</td>
                                    <td>${row.qtd_total}</td>
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
        })
    });

    function new_table(data = null) {

        $('#data-table').find('tbody').html('');

        if (data != null) {
            $('#data-table').find('tbody').append(`${data}`);
        }

        var table = $('#data-table').DataTable({
            serverSide: false,
            lengthChange: false,
            responsive: true,
            order: [[2, "desc"]],
            columns: [
                null,
                null,
                {width: '120px'},
                null,
                null
            ],
            rowCallback: function (row, data) {
            },
            drawCallback: function () {
            }
        });
    }
</script>
</body>

