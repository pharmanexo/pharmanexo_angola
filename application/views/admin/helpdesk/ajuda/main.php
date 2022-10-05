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
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="filtro-cliente">Filtrar por status</label>
                            <select class="select2" id="filtro-status" data-index="5">
                                <option value="">Selecione</option>
                                <option value="0">Inativo</option>
                                <option value="1">Ativo</option>
                                <option value="2">Bloqueado</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mx-auto mt-3">
                    <div class="col-12 col-sm">
                        <div class="table-responsive">
                            <?php $url = (isset($datasource)) ? $datasource : ''; ?>
                            <?php $update = (isset($url_update)) ? $url_update : ''; ?>
                            <table id="table-fornecedor" class="table table-condensend table-hover w-100"
                                   data-url="<?php echo $url; ?>"
                                   data-update="<?php echo $update; ?>">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <th>Titulo</th>
                                    <th>Categoria</th>
                                    <th>Data</th>
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
</body>

<?php echo $scripts; ?>

<script>
    var url_status = $('#table-fornecedor').data('status');
    var url_update = $('#table-fornecedor').data('update');

    $(function() {
        var table = $('#table-fornecedor').DataTable({
            processing: true,
            serverSide: false,
            lengthChange: false,
            responsive: true,
            pageLength: 10,
            ajax: {
                url: $('#table-fornecedor').data('url'),
                type: 'POST',
                dataType: 'json'
            },
            columns: [
                { defaultContent: '', orderable: false, searchable: false },
                { data: 'id', name: 'id', visible: false },
                { data: 'titulo', name: 'titulo' },
                { data: 'categoria', name: 'nome'},
                { data: 'data_criacao', name: 'created_at', className: 'text-nowrap' },
            ],
            columnDefs: [
                { orderable: false, className: 'select-checkbox', targets: 0 }
            ],
            select: { style: "multi", selector: "td:first-child"},
            order: [[ 1, 'asc' ]],
            rowCallback: function(row, data) {
                $(row).data('id', data.id).css('cursor', 'pointer');
                

                $('td:not(:first-child):not(:last-child)', row).each(function() {
                    $(this).on('click', function() {
                        window.location.href = `${url_update}/${data.id}`
                    });
                });
            },
            drawCallback: function() {
            }
        });

        $('#btnDeleteMultiple').click(function (e) {
            e.preventDefault();
            var ids = [];
            var selectedRows = $.map(table.rows('.selected').data(), function (item) {
                ids.push(item.id);
            });

            if (ids.length > 0) {
                $.post(url_delete_multiple, {el: ids}, function (xhr) {
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

        $('[data-index]').on('change', function() {
            var col = $(this).data('index');
            var value = $('#filtro-status').val();
            table.columns(col).search(value).draw();
        });
    });
</script>

</html>
