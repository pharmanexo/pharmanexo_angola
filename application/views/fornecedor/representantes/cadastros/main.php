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
                    <div class="table-responsive">
                        <table id="data-table" class="table w-100 table-hover" data-url="<?php echo $datatables; ?>" data-update="<?php if (isset($url_update)) echo $url_update; ?>" data-delete="<?php if (isset($url_delete)) echo $url_delete; ?>">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>CNPJ</th>
                                <th>E-mail</th>
                                <th>Telefone Comercial</th>
                                <th>Telefone Celular</th>
                                <th></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<?php echo $scripts; ?>

<script>

    var url_detalhes = $('#data-table').data('detalhe');
    var url_delete = $('#data-table').data('delete');
    var url_update = $('#data-table').data('update');

    $(function() {

        var table = $('#data-table').DataTable({
            serverSide: true,
            lengthChange: false,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'post',
                dataType: 'json'
            },
            columns: [
                { name: 'id', data: 'id', visible: false },
                { name: 'representantes.nome', data: 'nome', className: 'text-nowrap'},
                { name: 'representantes.cnpj', data: 'cnpj', className: 'text-nowrap' },
                { name: 'representantes.email', data: 'email', className: 'text-nowrap' },
                { name: 'representantes.telefone_comercial', data: 'telefone_comercial', className: 'text-nowrap' },
                { name: 'representantes.telefone_celular', data: 'telefone_celular', className: 'text-nowrap' },
                { defaultContent: '', width: '100px', orderable: false, searchable: false },
            ],
            rowCallback: function(row, data) {

                $(row).data('id', data.id).css('cursor', 'pointer');
                var btnDelete = $(`<a href="${url_delete}${data.id_representante}" class="text-danger"><i class="fas fa-trash"></i></a>`);


                btnDelete.click(function (e) {
                    e.preventDefault();
                    var url = $(this).attr('href');

                    Swal.fire({
                        title: 'Deseja realmente excluir este representante?',
                        showCancelButton: true,
                        confirmButtonText: 'Sim',
                        cancelButtonText: 'Não',
                        showLoaderOnConfirm: true,
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.value) {
                            $.get(url, function (xhr) {
                                formWarning(xhr);
                                table.ajax.reload();
                            })

                        }
                    })

                });

                $('td:eq(5)', row).html(btnDelete);

                $('td:not(:last-child)', row).each(function() {
                    $(this).on('click', function () {
                        window.location.href = $('#data-table').data('update') + data.id_representante
                    });
                });

            },
            drawCallback: function() {}
        });
    });

</script>
</html>