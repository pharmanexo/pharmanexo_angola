<!DOCTYPE html>
<html lang="pt-BR">
<?php echo $header; ?>
<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>
<div class="content">
    <?php echo $heading; ?>
    <div class="content__inner">
        <form id="formUpdateUserdata" autocomplete="off" action="<?php echo $url_update; ?>" method="POST" enctype="multipart/form-data">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive col-sm">
                        <table id="data-table" class="table table-condensend table-hover" data-url="<?php echo $datatable_src; ?>" data-update="<?php echo $url_update ?>" data-rede="<?php echo $url_rede ?>">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Login</th>
                                <th>tipo</th>
                                <th></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?php echo $scripts; ?>
<script>
    $(function () {
        var url_update = $('#data-table').data('update');
        var url_rede = $('#data-table').data('rede');

        $(function () {
            $('#data-table').DataTable({
                serverSide: false,
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
                        name: 'nome',
                        data: 'nome'
                    },
                    {
                        name: 'email',
                        data: 'email'
                    },
                    {
                        name: 'nivel',
                        data: 'nivel'
                    },
                    { defaultContent: '', orderable: false, searchable: false },
                ],

                rowCallback: function (row, data) {
                    $(row).data('id', data.id).css('cursor', 'pointer');

                    $('td:eq(3)', row).html(`<a href="${url_rede}${data.id}" class="btn btn-sm btn-outline-primary">Configurar</a>`);

                    $('td', row).each(function() {

                        $(this).on('click', function() {
                            $.ajax({
                                type: 'get',
                                url: $('#data-table').data('update') + '/' + data.id,
                                dataType: 'html',

                                success: function(response) {
                                    $('body').append(response);
                                    $('.modal').modal({
                                        keyboard: false
                                    }, 'show').on('hide.bs.modal', function() {
                                        $('#data-table').DataTable().ajax.reload();
                                        $('.modal').remove();
                                    }).on('shown.bs.modal', function () {
                                        $('#senha').val('');
                                        $('#c_senha').val('');
                                    });
                                }
                            });
                        });
                    });
                },

                drawCallback: function () {

                }
            });
        });

        $('#btnNovo').on('click', function(e) {
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
                    }).on('shown.bs.modal', function () {
                        $('#formUsuario').resetForm();
                    });
                }
            })
        });


    });
</script>
</body>

</html>