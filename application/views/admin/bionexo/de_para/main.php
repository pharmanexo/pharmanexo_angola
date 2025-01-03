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
                <?php if ($depara_iniciado) { ?>
                <div class="table-responsive col-sm">
                    <table id="data-table" class="table table-condensend table-hover"
                           data-url="<?php echo $to_datatable; ?>" data-update="<?php echo $url_update ?>"
                           data-delete="<?php echo $url_block ?>">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Código</th>
                            <th>Descrição</th>
                            <th></th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <?php }else { ?>
                    <p><strong>Para exibir os produtos clique no botão "Iniciar De/para". </strong></p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php echo $scripts; ?>
<script>

    var url_delete = $('#data-table').data('delete');
    $(function () {

        $('.dpr').click(function (e) {
            e.preventDefault();
            var url = $(this).attr('href');

            $.get(url, function (xhr) {
                formWarning(xhr);
                if (xhr.type == 'success')
                {
                    window.location.reload(false);
                }
            });
        });


        var dt1 = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            lengthChange: false,
            pageLength: 5000,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'get',
                dataType: 'json'
            },
            columns: [
                {defaultContent: '', width: '100px', orderable: false, searchable: false},
                {name: 'codigo', data: 'codigo', className: 'text-center'},
                {name: 'descricao', data: 'descricao'},
                {defaultContent: '', width: '100px', orderable: false, searchable: false}
            ],
            order: [[1, 'asc']],
            columnDefs: [
                {orderable: false, className: 'select-checkbox', targets: 0},
                {targets: [1], visible: false}
            ],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            rowCallback: function (row, data) {
                $(row).data('id', data.codigo).css('cursor', 'pointer');

                $('td:not(:first-child):not(:last-child)', row).each(function () {
                    $(this).on('click', function () {
                        window.location.href = $('#data-table').data('update') + data.codigo
                    });
                });
            },
            drawCallback: function () {

                $('[data-toggle="tooltip"]').tooltip();

                $('[data-block]').click(function (e) {
                    e.preventDefault();
                    var url = $(this).attr('href');
                    $.get(url, function (xhr) {
                        formWarning(xhr);

                        if (xhr.type == 'success') {
                            $('#data-table').DataTable().ajax.reload();
                        }
                    }, 'JSON');

                });
            }
        });

        $('#btnOcultarSelecionados').click(function (e) {
            e.preventDefault();
            var elementos = [];
            var url = $(this).attr('href');

            $.map(dt1.rows('.selected').data(), function (item) {
                elementos.push(item);
            });

            if (elementos.length > 0) {
                $.post(url, {elementos}, function (xhr) {
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
    });
</script>
</body>

