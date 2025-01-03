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
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="data-table" class="table w-100 table-hover" data-url="<?php echo $to_datatable; ?>">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Código</th>
                                <th>Desconto (%)</th>
                                <th style="width: 200px;">Produto</th>
                                <th>Preço</th>
                                <th>Preço Desconto</th>
                                <th>Quantidade</th>
                                <th>Dias</th>
                                <th>Lote</th>
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
    $(function() {
        var buttonCommon = {
            exportOptions: {
                format: {
                    body: function ( data, row, column, node ) {
                        return (column == 4 || column == 5) ? data.replace( /[.]/g, '' ).replace( /[,]/g, '.' ) : data;
                    }
                }
            }
        };
        var dt1 = $('#data-table').DataTable({
            serverSide: false,
            lengthChange: false,
            dom: 'Bfrtip',
            buttons: [ $.extend( true, {}, buttonCommon, { extend: 'excelHtml5'} ) ],
            ajax: {
                url: $('#data-table').data('url'),
                type: 'post',
                dataType: 'json',
            },
            columns: [
                { name: 'promocoes.id', data: 'id', visible: false },
                { name: 'promocoes.codigo', data: 'codigo' },
                { name: 'promocoes.desconto_percentual', data: 'desconto_percentual', className: 'text-nowrap' },
                { name: 'produtos_catalogo.produto_descricao', data: 'produto_descricao', className: 'text-nowrap' },
                { name: 'produtos_preco.preco_unitario', data: 'preco' },
                { name: 'produtos_preco.preco_unitario', data: 'preco_desconto', className: 'text-nowrap' },
                { name: 'promocoes.quantidade', data: 'quantidade' },
                { name: 'promocoes.dias', data: 'dias' },
                { name: 'promocoes.lote', data: 'lote' },
            ],
            order: [[ 1, 'asc' ]],
            rowCallback: function(row, data) {},
            drawCallback: function() {}
        });
    });
</script>
</html>