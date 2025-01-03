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
                            <table id="data-table" class="table table-condensend table-hover" data-delete_multiple="<?php echo $url_delete_multiple ?>" data-url="<?php echo $datatable; ?>" data-update="<?php echo $url_update; ?>" >
                                <thead>
                                <tr>
                                    <th class="text-center">
                                        <div class="checkbox">
                                            <input type="checkbox" id="checkall">
                                            <label class="checkbox__label" for="checkall"></label>
                                        </div>
                                    </th>
                                    <th>Estado</th>
                                    <th>Tipo</th>
                                    <th>Portal</th>
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
    var url_delete = $('#data-table').data('delete_multiple');

    $(function () {
        var table = $('#data-table').DataTable({
            processing: true,
            serverSide: false,
            lengthChange: false,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'post',
                dataType: 'json'
            },
            columns: [
                { defaultContent: '', width: '120px', orderable: false, searchable: false},
                { name: 'e.estado', data: 'estado'},
                { name: 'config.tipo', data: 'tipo'},
                { name: 'config.integrador', data: 'integrador'},
            ],
            columnDefs: [
                {orderable: false, className: 'select-checkbox', targets: 0 },
            ],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            order: [[ 1, 'asc' ]],
            rowCallback: function (row, data) {

                $(row).css('cursor', 'pointer');
            },
            drawCallback: function () {}
        });

        $('#data-table tbody').on('click', 'tr td:not(:first-child)', function (e) {
            e.preventDefault();
            var rowIdx = table.cell( this ).index().row;
            var data = table.row( rowIdx ).data();

            $.ajax({
                type: 'post',
                url: $('#data-table').data('update') + '/' + data.id,
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

        $('#btnAdicionar').on('click', function(e) {
            e.preventDefault();
            let me = $(this);

            $.ajax({
                url: me.attr('href'),
                type: 'get',
                dataType: 'html',
                success: function(response) {
                    $('body').append(response);
                    $('.modal').modal({
                        keyboard: false
                    }, 'show').on('hide.bs.modal', function() {
                        $('.modal').remove();
                        $('#data-table').DataTable().ajax.reload();
                    });
                }
            })
        });

        $('#btnDeleteMultiple').click(function (e) {
            e.preventDefault();
            var elementos = [];
            var url = $();

            $.map(table.rows('.selected').data(), function (item) {   
                elementos.push(item.id);
            });

            if (elementos.length > 0) {
                $.post(url_delete, {el: elementos}, function (xhr) {
                    $('#data-table').DataTable().ajax.reload();
                    formWarning(xhr);
                }, 'JSON');
            } else {

                formWarning({type: 'warning', message: "Nenhum registro selecionado!"});
            }
        });

        $('#checkall').click(function (event) { checkall(table, document.getElementById('checkall')); });

        function checkall(table, checkall) 
        {
            if (checkall.checked == true) {

                table.rows({search:'applied'}).select();
            } else {

                table.rows().deselect();
            }
        }

    });
</script>

</html>