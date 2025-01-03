<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>

    <div class="content__inner" id="printAll">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive col-sm">
                    <table id="data-table" class="table table-condensend table-hover" data-url="<?php echo $datatables; ?>" data-update="<?php echo $url_update ?>">
                        <thead>
                        <tr>
                            <th>Código</th>
                            <th>Descrição</th>
                            <th>Marca</th>
                            <th></th>
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
    $(function () {
        var dt1 = $('#data-table').DataTable({
            "processing": true,
            "serverSide": false,
            lengthChange: false,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'get',
                dataType: 'json'
            },
            columns: [
                { name: 'id', data: 'id', visible: false },
                { name: 'produto_descricao', data: 'produto_descricao' },
                { name: 'marca', data: 'marca' },
                { name: 'codigo', data: 'codigo', visible: false },
                { defaultContent: '', width: '100px', orderable: false, searchable: false },
            ],
            rowCallback: function (row, data) {
                $(row).data('id', data.id).css('cursor', 'pointer');

                $('td:not(:first-child):not(:last-child)', row).each(function() {
                    $(this).on('click', function () {
                        window.location.href = $('#data-table').data('update') + data.codigo
                    });
                });

            },
            drawCallback: function () {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    });
</script>
</body>

