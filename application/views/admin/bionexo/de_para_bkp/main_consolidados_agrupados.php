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


                    <table id="data-table" class="table table-condensend table-hover" data-url="<?php echo $to_datatable; ?>" data-update="<?php echo $url_update ?>">
                        <thead>
                        <tr>
                            <th></th>
                            <th >Código</th>
                            <th>ID Produto</th>
                            <th>Id Sintese</th>
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

    var url_update = $('#data-table').data('update');
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
                    className: 'text-center',
                    orderable: true
                },
                {
                    name: 'id_produto',
                    data: 'id_produto',
                    visible: false
                },
                {
                    name: 'id_sintese',
                    data: 'id_sintese',
                    visible: false
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
                    name: 'data_atualizacao',
                    data: 'data_atualizacao',
                    visible: false
                },
            ],
            "order": [[ 1, "ASC" ]],
            rowCallback: function (row, data) {
                $(row).data('id', data.id).css('cursor', 'pointer');
                var btnLinkar = $(`<button href="${url_update}${data.codigo}/${data.id_sintese}" data-toggle="tooltip" title="Revisar De/Para" data- class="btn btn-sm btn-secondary"><i class="fas fa-pencil"></i></button>`);
                var btnDelete = $(`<a href="${url_update}${data.codigo}" data-block='${data.codigo}' data-toggle="tooltip" data-title="Inativar produto" title="Inativar este produto" class="text-warning"><i class="fas fa-ban"></i></a>`);

                $('td:not(:first-child):not(:last-child)', row).each(function() {
                    $(this).on('click', function () {
                        window.location.href = $('#data-table').data('update') + data.id
                    });
                });

                //$('td:eq(3)', row).html(btnDelete);
            },
            drawCallback: function () {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    });
</script>
</body>

