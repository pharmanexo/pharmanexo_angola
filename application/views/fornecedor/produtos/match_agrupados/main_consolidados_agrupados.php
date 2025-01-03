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

                $('td', row).each(function() {
                    $(this).on('click', function () {
                        window.location.href = $('#data-table').data('update') + data.codigo
                    });
                });

                //$('td:eq(3)', row).html(btnDelete);
            },
            drawCallback: function () {

            }
        });
    });
</script>
</body>

