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
                            <th >Código</th>
                            <th>ID Produto</th>
                            <th>CD Comprador</th>
                            <th>Descrição</th>
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
                {name: 'codigo_fornecedor', data: 'codigo_fornecedor', className: 'text-center'},
                 {name: 'id_produto', data: 'id_produto', visible: true },
                 {name: 'codigo_hospital', data: 'codigo_hospital', visible: true },
                {name: 'produto_comprador', data: 'produto_comprador'},
                {defaultContent: '', width: '100px', orderable: false, searchable: false },
            ],
            order: [[ 0, "ASC" ]],
            rowCallback: function (row, data) {
                $(row).data('id', data.id).css('cursor', 'pointer');
                var btnLinkar = $(`<button href="${url_update}${data.codigo}/${data.id_sintese}" data-toggle="tooltip" title="Remover combinação dos produtos." data- class="btn btn-sm btn-danger"><i class="fas fa-unlink"></i></button>`);




                btnLinkar.click(function (e) {
                    e.preventDefault();

                    var url = $(this).attr('href');
                    $.post(url, {}, function (xhr) {
                        formWarning(xhr);
                        setTimeout(function () {
                            window.location.href = "<?php echo base_url('fornecedor/estoque/consolidacao/consolidados')?>";
                        }, 3000)
                    }, 'JSON')
                });

                $('td:eq(4)', row).html(btnLinkar);
            },
            drawCallback: function () {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    });
</script>
</body>

