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
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="data-table-estado" class="table table-condensend table-hover w-100" data-delete_multiple="<?php echo $url_delete_multiple ?>" data-url="<?php echo $to_datatable_estado; ?>">
                                <thead>
                                <tr>
                                    <th class="text-center">
                                        <div class="checkbox">
                                            <input type="checkbox" id="checkall-estados">
                                            <label class="checkbox__label" for="checkall-estados"></label>
                                        </div>
                                    </th>
                                    <th>Estado</th>
                                    <th>Codigo</th>
                                    <th>Produto</th>
                                    <th>Estado</th>
                                    <th>id</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<?php echo $scripts; ?>

<script>
    var url_delete_estado = $('#data-table-estado').data('delete');
    var url_delete_multiple = $('#data-table-estado').data('delete_multiple');

    $(function () {
        var dt1 = $('#data-table-estado').DataTable({
            processing: true,
            serverSide: false,
            lengthChange: false,
            ajax: {
                url: $('#data-table-estado').data('url'),
                type: 'post',
                dataType: 'json'
            },
            columns: [
                {defaultContent: '', width: '120px', orderable: false, searchable: false},
                {name: 'estado', data: 'estado', visible: false},
                {name: 'codigo', data: 'codigo'},
                {name: 'produto', data: 'produto'},
                {name: 'estado', data: 'estado'},
                {name: 'id', data: 'id', visible: false},
            ],
            columnDefs: [
                {orderable: false, className: 'select-checkbox', targets: 0},
                {targets: [1], visible: false}
            ],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            order: [[2, 'asc']],
            rowCallback: function (row, data) {
            },

            drawCallback: function () {
            }
        });


        $('#btnDeleteMultiple').click(function (e) {
            e.preventDefault();
            var elementos = [];
            var url = $();

            $.map(dt1.rows('.selected').data(), function (item) {
                elementos.push(item.id);
            });

            if (elementos.length > 0) {
                $.post(url_delete_multiple, {el: elementos}, function (xhr) {
                    $('#data-table-estado').DataTable().ajax.reload();
                    formWarning(xhr);
                }, 'JSON');
            } else {
                formWarning({
                    type: 'warning',
                    message: "Nenhum registro selecionado!"
                });
            }
        });

        $('#checkall-estados').click(function (event) {
            checkall(dt1, document.getElementById('checkall-estados'));
        });

        function checkall(table, checkall) {
            if (checkall.checked == true) {

                table.rows({search: 'applied'}).select();
            } else {

                table.rows().deselect();
            }
        }

    });
</script>

</html>