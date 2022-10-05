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
                    <div class="dropdown text-right">
                        <button class="btn btn-light" data-toggle="dropdown" aria-expanded="true"><i class="fas fa-download"></i> Exportar</button>
                        <div class="dropdown-menu " x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 34px, 0px);">
                            <a href="" class="dropdown-item">Planilha Excel/XLS</a>
                            <a href="" class="dropdown-item">PDF</a>
                        </div>
                    </div>

                    <table id="data-table" class="table table-condensend table-hover" data-url="<?php echo $to_datatable; ?>" data-update="<?php echo $url_update ?>" data-delete="<?php echo $url_block ?>">
                        <thead>
                        <tr>
                            <th></th>
                            <th >Código</th>
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

    var url_delete = $('#data-table').data('delete');
    $(function () {
        var dt1 = $('#data-table').DataTable({
            "processing": true,
            "serverSide": true,
            lengthChange: false,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'get',
                dataType: 'json'
            },
            columns: [
                {
                    name: 'id',
                    data: 'id',
                    visible: false
                },
                {
                    name: 'codigo',
                    data: 'codigo',
                    className: 'text-center'
                },
                {
                    name: 'produto_descricao',
                    data: 'produto_descricao'
                },
                {
                    name: 'marca',
                    data: 'marca',
                    visible: false
                },
                {
                    defaultContent: '',
                    width: '100px',
                    orderable: false,
                    searchable: false
                }
            ],
            rowCallback: function (row, data) {
                $(row).data('id', data.codigo).css('cursor', 'pointer');
                var btnDelete = $(`<a href="${url_delete}${data.codigo}" data-block='${data.codigo}' data-toggle="tooltip" data-title="Inativar produto" title="Inativar este produto" class="text-warning"><i class="fas fa-ban"></i></a>`);

                $('td:eq(3)', row).html(btnDelete);


                $('td:not(:first-child):not(:last-child)', row).each(function() {
                    $(this).on('click', function () {
                        window.location.href = $('#data-table').data('update') + data.codigo
                    });
                });
            },
            drawCallback: function () {
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

