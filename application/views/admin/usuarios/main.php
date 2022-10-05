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
                <div class="row mx-auto mt-3">
                    <div class="col-12 col-sm">
                        <div class="table-responsive">
                            <?php $url = (isset($datasource)) ? $datasource : ''; ?>
                            <?php $update = (isset($url_update)) ? $url_update : ''; ?>

                            <table id="table-usuario" class="table table-condensend table-hover w-100" 
                                data-url="<?php echo $url; ?>" 
                                data-update="<?php echo $update; ?>" 
                                data-bloqueio="<?php echo $url_bloqueio; ?>" 
                                data-newpassword="<?php echo $url_new_password; ?>" 
                                data-delete_multiple="<?php echo $url_delete_multiple ?>">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>CPF</th>
                                    <th></th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

<?php echo $scripts; ?>

<script>

    var url_delete_multiple = $('#table-usuario').data('delete_multiple');
    var url_update = $('#table-usuario').data('update');
    var url_newpassword = $('#table-usuario').data('newpassword');
    var url_bloqueio = $('#table-usuario').data('bloqueio');

    $(function() {
        var table = $('#table-usuario').DataTable({
            processing: true,
            serverSide: true,
            lengthChange: false,
            responsive: true,
            pageLength: 10,
            ajax: {
                url: $('#table-usuario').data('url'),
                type: 'POST',
                dataType: 'json'
            },
            columns: [
                { defaultContent: '', orderable: false, searchable: false },
                { data: 'id', name: 'id', visible: false },
                { data: 'nome', name: 'nome' },
                { data: 'email', name: 'email' },
                { data: 'cpf', name: 'cpf', className: 'text-nowrap' },
                { defaultContent: '', orderable: false, searchable: false },
            ],
            columnDefs: [
                { orderable: false, className: 'select-checkbox', targets: 0 }
            ],
            select: {
                style: "multi",
                selector: "td:first-child"
            },
            order: [[ 1, 'asc' ]],
            rowCallback: function(row, data) {
                $(row).data('id', data.id).css('cursor', 'pointer');
               
                var link_situacao = '';

                if ( data.situacao == 1 ) {

                    link_situacao = `<a href="${url_bloqueio}/${data.id}" data-block="bloquear" class="dropdown-item">Bloquear usuário</a>`;
                } else {
                    $(row).addClass('table-danger');
                    link_situacao = `<a href="${url_bloqueio}/${data.id}/1" data-block="desbloquear" class="dropdown-item">Desbloquear usuário</a>`;
                }

                var id_aleatorio = new Date().getTime();

                var icons = `
                    <div class="dropdown-demo mr-4">
                        <a href="#" data-toggle="dropdown" id="dropdownMenuLink_${id_aleatorio}" class="dropdown-toggle position-absolute">
                            <i class="fas fa-ellipsis-v" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                        </a>
                        <div class="dropdown-menu">
                            ${link_situacao}
                            <a href="${url_newpassword}${data.id}" data-reset="" class="dropdown-item">Resetar Senha</a>
                        </div>
                    </div>
                `;

                $('td:eq(4)', row).html(icons);

                $('td:not(:first-child):not(:last-child)', row).each(function() {
                    $(this).on('click', function() {
                        window.location.href = `${url_update}/${data.id}`
                    });
                });
            },
            drawCallback: function() {

                $('[data-toggle="tooltip"]').tooltip();

                $('[data-reset]').click(function (e) {
                    e.preventDefault();
                    var url = $(this).attr('href');

                    Swal.fire({
                        title: 'Tem certeza que deseja resetar a senha?',
                        text: "",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sim, resetar!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {

                        if (result.value) {
                           
                           $.ajax({
                                url: url,
                                type: 'post',
                                contentType: false,
                                processData: false,
                                data: {},
                                success: function(xhr) {

                                    formWarning(xhr);
                                    $('#table-usuario').DataTable().ajax.reload();
                                },
                                error: function(xhr) {}
                            });
                        }
                    })
                });

                $('[data-block]').click(function (e) {
                    e.preventDefault();
                    var url = $(this).attr('href');

                    var text = $(this).data('block');

                    Swal.fire({
                        title: `Tem certeza que deseja ${text} este usuário?`,
                        text: "",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: `Sim, ${text}`,
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {

                        if (result.value) {
                            $.ajax({
                                url: url,
                                type: 'post',
                                contentType: false,
                                processData: false,
                                data: {},
                                success: function(xhr) {

                                    formWarning(xhr);
                                    $('#table-usuario').DataTable().ajax.reload();
                                },
                                error: function(xhr) { console.log(xhr);}
                            });
                        }
                    })
                });
            }
        });

        $('#btnDeleteMultiple').click(function (e) {
            e.preventDefault();
            var ids = [];
            var selectedRows = $.map(table.rows('.selected').data(), function (item) {
                ids.push(item.id);
            });

            if (ids.length > 0) {
                $.post(url_delete_multiple, {el: ids}, function (xhr) {
                    table.ajax.reload();
                    formWarning(xhr);
                }, 'JSON')
                .fail(function(xhr) {
                    formWarning(xhr);
                    table.ajax.reload();
                });
            } else {
                formWarning({
                    type: 'warning',
                    message: "Nenhum registro selecionado!"
                });
            }
        });
    });
</script>

</html>
