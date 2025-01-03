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
                        <table id="data-table" class="table w-100 table-hover" data-url="<?php echo $to_datatable; ?>" data-update="<?php echo $url_update; ?>" data-delete="<?php echo $url_delete ?>" data-delete_multiple="<?php echo $url_delete_multiple ?>">
                            <thead>
                            <tr>
                                <th><input type="checkbox" data-select="estados" id="checkAll"></th>
                                <th>ID</th>
                                <th>Desconto (%)</th>
                                <th style="width: 200px;">Produto</th>
                                <th>Preço</th>
                                <th>Preço Desconto</th>
                                <th>Quantidade</th>
                                <th>Dias</th>
                                <th>Lote</th>
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
    var url_delete_estado = $('#data-table').data('delete');
    var url_delete_multiple = $('#data-table').data('delete_multiple');

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
                {defaultContent: '', orderable: false, searchable: false, sortable: false },
                { name: 'promocoes.id', data: 'id', visible: false },
                { name: 'promocoes.desconto_percentual', data: 'desconto_percentual', className: 'text-nowrap' },
                { name: 'vw_produtos_fornecedores.produto_descricao', data: 'produto_descricao', className: 'text-nowrap' },
                { name: 'vw_produtos_fornecedores.preco', data: 'preco' },
                { name: 'vw_produtos_fornecedores.preco', data: 'preco_desconto', className: 'text-nowrap' },
                { name: 'promocoes.quantidade', data: 'quantidade' },
                { name: 'promocoes.dias', data: 'dias' },
                { name: 'promocoes.lote', data: 'lote' },
                { defaultContent: '', width: '', orderable: false, searchable: false }
            ],
            columnDefs: [
                {orderable: false, className: 'select-checkbox', targets: 0 },
                {targets: [1], visible: false }
            ],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            order: [[ 1, 'asc' ]],
            rowCallback: function(row, data) {
                $(row).data('codigo', data.id_produto).css('cursor', 'pointer');

                $('td:not(:first-child):not(:last-child)', row).each(function() {
                    $(this).on('click', function() {
                        $.ajax({
                            type: 'get',
                            url: $('#data-table').data('update') + '/' + data.codigo,
                            dataType: 'html',

                            success: function(response) {
                                $('body').append(response);
                                $('.modal').modal({
                                    keyboard: false
                                }, 'show').on('hide.bs.modal', function() {
                                    $('#data-table').DataTable().ajax.reload();
                                    $('.modal').remove();
                                });
                            }
                        });
                    });
                });
            },
            drawCallback: function() {}
        });

        $('#btnDeleteMultiple').click(function (e) {
            e.preventDefault();
            var ids = [];
            
            $.map(dt1.rows('.selected').data(), function (item) {
                ids.push(item.codigo);
            });

            if (ids.length > 0) {
                $.post(url_delete_multiple, {el: ids}, function (xhr) {
                    dt1.ajax.reload();
                    formWarning(xhr);
                }, 'JSON')
                .fail(function(xhr) {
                    formWarning(xhr);
                    dt1.ajax.reload();
                });
            } else {
                formWarning({
                    type: 'warning',
                    message: "Nenhum registro selecionado!"
                });
            }
        });

        $('#checkAll').click(function(event) {checkall(dt1, document.getElementById('checkAll') ); });
    });

    function checkall(table, checkall) {
        if (checkall.checked == true) {
            table.rows().select();
        }else {
            table.rows().deselect();
        }
    }
</script>

</html>