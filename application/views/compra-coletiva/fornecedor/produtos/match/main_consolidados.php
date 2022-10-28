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
                            <th>Id Produto</th>
                            <th>Id Marca</th>
                            <th>Descrição</th>
                            <th>Marca</th>
                            <th></th>
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
                    className: 'text-center'
                },
                {
                    name: 'id_produto',
                    data: 'id_produto',
                    visible: true
                },
                {
                    name: 'id_sintese',
                    data: 'id_sintese',
                    visible: true
                },

                {
                    name: 'produto_descricao',
                    data: 'produto_descricao'
                },
                {
                    name: 'marca',
                    data: 'marca',
                    visible: false,
                },
                {
                    defaultContent: '',
                    width: '100px',
                    orderable: false,
                    searchable: false
                },
                {
                    name: 'data_atualizacao',
                    data: 'data_atualizacao',
                    visible: false
                },
            ],
            "order": [[ 1, "desc" ]],
            rowCallback: function (row, data) {
                $(row).data('id', data.id).css('cursor', 'pointer');
                var btnLinkar = $(`<button href="${url_update}${data.codigo}/${data.id_sintese}" data-toggle="tooltip" title="Remover combinação dos produtos." data- class="btn btn-sm btn-secondary"><i class="fas fa-unlink"></i></button>`);

                btnLinkar.click(function (e) {
                    e.preventDefault();

                    var url = $(this).attr('href');
                    $.post(url, {}, function (xhr) {
                        formWarning(xhr);
                       
                    }, 'JSON')
                });

                $('td:eq(5)', row).html(btnLinkar);
            },
            drawCallback: function () {

            }
        });
    });
</script>
</body>

