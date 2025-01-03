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
                <div class="table-responsive col-sm">


                    <table id="data-table" class="table table-condensend table-hover" data-url="<?php echo $to_datatable; ?>" data-update="<?php echo $url_update ?>" data-delete="<?php echo $url_block ?>">
                        <thead>
                        <tr>
                            <th >Código</th>
                            <th>Descrição</th>
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

    var url_delete = $('#data-table').data('delete');
    $(function () {
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
                {name: 'codigo', data: 'codigo', className: 'text-center'},
                {name: 'descricao', data: 'descricao'},
                {defaultContent: '', width: '100px', orderable: false, searchable: false }
            ],
            rowCallback: function (row, data) {
                $(row).data('id', data.codigo).css('cursor', 'pointer');
                var btnDelete = $(`<a href="${url_delete}${data.codigo}" data-block='${data.codigo}' data-toggle="tooltip" data-title="Inativar produto" title="Inativar este produto" class="btn btn-sm btn-danger"><i class="fas fa-ban"></i></a>`);

                $('td:eq(3)', row).html(btnDelete);


                $('td:not(:first-child):not(:last-child)', row).each(function() {
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

                        if(xhr.type == 'success'){
                            $('#data-table').DataTable().ajax.reload();
                        }
                    }, 'JSON');

                });
            }
        });
    });
</script>
</body>

