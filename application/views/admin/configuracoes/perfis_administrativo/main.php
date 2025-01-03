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
                <div class="row mx-auto mt-3">
                    <div class="col-12 col-sm">
                        <div class="table-responsive">
                            <table id="data-table" class="table table-condensend table-hover w-100" 
                            data-url="<?php if (isset($datatable)) echo $datatable; ?>" 
                            data-update="<?php echo $url_update; ?>"
                            data-delete_multiple="<?php echo $url_delete_multiple; ?>">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="checkbox">
                                                <input type="checkbox" id="checkall">
                                                <label class="checkbox__label" for="checkall"></label>
                                            </div>
                                        </th>
                                        <th></th>
                                        <th>Perfil</th>
                                        <th>Rotas</th>
                                        <th>Criado em</th>
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

    var url_delete_multiple = $('#data-table').data('delete_multiple');

    $(function() {
        var table = $('#data-table').DataTable({
            processing: true,
            serverSide: false,
            lengthChange: false,
            responsive: true,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'POST',
                dataType: 'json'
            },
            columns: [
                {defaultContent: '', orderable: false, searchable: false, sortable: false },
                { data: 'id', name: 'id', visible: false },
                { data: 'titulo', name: 'titulo', width: '150px' },
                { data: 'id_rotas', name: 'id_rotas', searchable: false },
                { data: 'data_criacao', name: 'data_criacao', className: 'text-nowrap' }
            ],
            columnDefs: [
                {orderable: false, className: 'select-checkbox', targets: 0 },
                {targets: [1], visible: false }
            ],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            order: [[ 2, 'asc' ]],
            rowCallback: function(row, data) { 
                $(row).css('cursor', 'pointer');

                $('td:not(:first-child)', row).each(function () {
                    
                    $(this).on('click', function () {

                        window.location.href = $('#data-table').data('update') + '/' + data.id;
                    });
                });
            },
            drawCallback: function() {}
        });

        $('#btnDeleteMultiple').click(function(e) {
            e.preventDefault();
            var elementos = [];
            var url = $();

            $.map(table.rows('.selected').data(), function (item) {
                elementos.push(item.id);
            });

            if (elementos.length > 0) {
                $.post(url_delete_multiple, {
                    el: elementos
                }, function(xhr) {
                    $('#data-table').DataTable().ajax.reload();
                    formWarning(xhr);
                }, 'JSON');
            } else {
                formWarning({
                    type: 'warning',
                    message: "Nenhum registro selecionado!"
                });
            }
        });

        $('#checkall').click(function(event) {checkall(table, document.getElementById('checkall') ); });
    });

    function checkall(table, checkall) {
        if (checkall.checked == true) {
            table.rows({search:'applied'}).select();
        }else {
            table.rows().deselect();
        }
    }
</script>

</html>
