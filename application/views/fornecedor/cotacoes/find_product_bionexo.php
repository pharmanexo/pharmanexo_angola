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
                    <table id="data-table" class="table w-100 table-hover" data-url="<?php echo $datatables; ?>" data-url2="<?php echo $url_combinar; ?>"data-cliente="<?php echo $id_cliente; ?>"  data-codigo="<?php echo $produto['codigo']; ?>">
                        <thead>
                            <tr>
                                <th></th>
                                <th>ID Produto</th>
                                <th>Descrição</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $scripts; ?>

<script>

    var url_combinar = $('#data-table').data('url2');
    
    $(function () {
        var table = $('#data-table').DataTable({
            serverSide: false,
            lengthChange: false,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'post',
                dataType: 'json',
            },
            columns: [
                {defaultContent: '', orderable: false, searchable: false},
                { name: 'id_produto', data: 'id_produto' },
                { name: 'descricao', data: 'descricao'},
            ],
            columnDefs: [
                {orderable: false, className: 'select-checkbox', targets: 0 },
            ],
            select: {style: 'multiple', selector: 'td'},
            order: [[ 1, 'asc' ]],
            rowCallback: function(row, data) {
                $(row).data('id', data.id_produto).css('cursor', 'pointer');
            },
            drawCallback: function() {}
        });

        $('#btnCombinar').on('click', function(e) {
            e.preventDefault();

            var dados = [];

            $.map(table.rows('.selected').data(), function (item) {

                dados.push({
                    id_produto: item.id_produto,
                    codigo: $('#data-table').data('codigo'),
                    id_cliente: $('#data-table').data('cliente'),
                });
            });

            if (dados.length > 0) {

                $.post(url_combinar, {dados}, function (xhr) {
                    table.ajax.reload();
                    formWarning(xhr);
                }, 'JSON')
                .fail(function(xhr) {
                    formWarning(xhr);
                    table.ajax.reload();
                });
            } else {

                formWarning({type: 'warning', message: "Nenhum registro selecionado!"});
            }
        });
    });
</script>
</body>
</html>