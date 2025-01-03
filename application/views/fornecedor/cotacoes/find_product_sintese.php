<!DOCTYPE html>
<html lang="pt-BR">
<?php echo $header; ?>

<body class="bg-light">
    <?php echo $navbar; ?>
    <?php echo $sidebar; ?>
    <div class="content">
        <?php echo $heading; ?>

        <div class="content__inner">
            <input type="hidden" name="id_cliente" id="id_cliente" value="<?php if (isset($id_cliente)) echo $id_cliente; ?>">
            <input type="hidden" name="id_prod_cot" id="id_prod_cot" value="<?php if (isset($id_prod_cot)) echo $id_prod_cot; ?>">

            <form>
                <div class="card">
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="data-table" class="table w-100 table-hover" data-url="<?php echo $datatables; ?>" data-url2="<?php echo $url_combinar; ?>" data-sintese="<?php echo $produto['id_sintese'] ?>">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Código</th>
                                        <th>Produto</th>
                                        <th>Marca</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php echo $scripts; ?>

    <script>
        var url_combinar = $('#data-table').data('url2');

        $(function() {

            var table = $('#data-table').DataTable({
                serverSide: false,
                lengthChange: false,
                ajax: {
                    url: $('#data-table').data('url'),
                    type: 'post',
                    dataType: 'json',
                },
                columns: [{
                        defaultContent: '',
                        orderable: false,
                        searchable: false
                    },
                    {
                        name: 'codigo',
                        data: 'codigo'
                    },
                    {
                        name: 'apresentacao',
                        data: 'apresentacao'
                    },
                    {
                        name: 'marca',
                        data: 'marca'
                    },
                ],
                columnDefs: [{
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                }, ],
                select: {
                    style: 'multiple',
                    selector: 'td'
                },
                order: [
                    [1, 'asc']
                ],
                rowCallback: function(row, data) {
                    $(row).data('id', data.id_produto).css('cursor', 'pointer');
                },
                drawCallback: function() {}
            });

            $('#btnCombinar').on('click', function(e) {
                e.preventDefault();

                var dados = [];

                $.map(table.rows('.selected').data(), function(item) {

                    dados.push({
                        id_fornecedor: item.id_fornecedor,
                        cd_produto: item.codigo,
                        id_sintese: $('#data-table').data('sintese'),
                        id_cliente: $('#id_cliente').val(),
                        id_produto_comprado: $('#id_prod_cot').val(), //produto cotado
                    });
                });

                if (dados.length > 0) {

                    $.post(url_combinar, {
                            dados
                        }, function(xhr) {
                            table.ajax.reload();
                            formWarning(xhr);
                        }, 'JSON')
                        .fail(function(xhr) {
                            formWarning(xhr);
                            table.ajax.reload();
                        });
                } else {

                    formWarning({
                        type: 'warning',
                        message: "Nenhum registro selecionado!"
                    });
                }
            });
        });
    </script>
</body>

</html>