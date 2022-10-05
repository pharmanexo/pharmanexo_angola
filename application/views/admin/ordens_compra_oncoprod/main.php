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
                            <label for="filtro-cliente">Filtrar por usuário</label>
                            <select class="select2" id="filtro-status" data-index="6">
                                <option value="">Selecione</option>
                                <?php foreach($usuarios as $usuario) { ?>
                                     <option value="<?php echo $usuario['id'] ?>"><?php echo $usuario['nome'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mx-auto mt-3">
                    <div class="col-12 col-sm">
                        <div class="table-responsive">
                            <?php $url = (isset($datasource)) ? $datasource : ''; ?>

                            <table id="table-log" class="table table-condensend table-hover w-100" data-url="<?php echo $url; ?>" data-delete_multiple="<?php echo $url_delete_multiple ?>">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <th>Ação</th>
                                    <th>Módulo</th>
                                    <th>URL</th>
                                    <th>Data</th>
                                    <th></th>
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
    
    var url_delete_multiple = $('#table-log').data('delete_multiple');

    $(function() {
        var table = $('#table-log').DataTable({
            processing: true,
            serverSide: false,
            lengthChange: false,
            responsive: true,
            pageLength: 10,
            ajax: {
                url: $('#table-log').data('url'),
                type: 'POST',
                dataType: 'json'
            },
            columns: [
                { defaultContent: '', orderable: false, searchable: false },
                { data: 'id', name: 'id', visible: false },
                { data: 'action', name: 'action' },
                { data: 'module', name: 'module' },
                { data: 'url', name: 'url' },
                { data: 'data', name: 'data', className: 'text-nowrap' },
                { data: 'id_usuario', name: 'id_usuario', visible: false },
            ],
            columnDefs: [
                { orderable: false, className: 'select-checkbox', targets: 0 }
            ],
            select: {
                style: "multi",
                selector: "td:first-child"
            },
            order: [[ 5, 'desc' ]],
            rowCallback: function(row, data) { $(row).data('id', data.id).css('cursor', 'pointer'); },
            drawCallback: function() { $('[data-toggle="tooltip"]').tooltip(); }
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
