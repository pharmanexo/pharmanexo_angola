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
                        <div id="opc_estado" class="col-12">
                            <div class="table-responsive col-sm">

                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                  <li class="nav-item">
                                    <a class="nav-link active" id="inbox" data-toggle="tab" data-action="inbox" href="#home" role="tab" aria-controls="home" aria-selected="true">Caixa de Entrada</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="sendbox" data-toggle="tab" data-action="sendbox" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Enviados</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="trashbox" data-toggle="tab" data-action="trashbox" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Lixeira</a>
                                </li>
                            </ul>

                            <table id="data-table" class="table table-condensend table-hover" data-del_mais="<?php echo $url_delete_multiplo; ?> " data-url="<?php echo $datatable_src; ?>" data-update="<?php echo $url_open_message; ?>">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Data</th>
                                        <th>Assunto</th>
                                        <th>Prioridade</th>
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

    var url_delete = $('#data-table').data('delete');
    var url_delete_multiplo = $('#data-table').data('delete_multiplo');
    var url_open_message = $('#data-table').data('update');
    var newUrl = $('#data-table').data('url');

    $(function() {

        newDataTable(newUrl);

        $('[data-action]').click(function (){

            let me = $(this);

            let urlAtt = newUrl + '/' + me.data('action');

            $('#data-table').DataTable().destroy();

            newDataTable(urlAtt);
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

        $('#btnAdicionar').on('click', function(e) {
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
                    });
                }
            })
        });


    });

    function newDataTable(newUrl) {
        $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            lengthChange: false,
            responsive: true,
            pageLength: 10,
            ajax: {
                url: newUrl,
                type: 'POST',
                dataType: 'json'
            },
            columns: [
            { defaultContent: '', orderable: false, searchable: false },
            { name: 'dt_registro', data: 'dt_registro', searchable: false },
            { name: 'assunto', data: 'assunto' },
            { name: 'prioridade', data: 'prioridade', className: 'text-center' },
            { data: 'action', width: '100px', orderable: false, searchable: false }
            ],
            columnDefs: [
            {
                orderable: false,
                className: 'select-checkbox',
                targets: 0
            }
            ],
            select: {
                style: "multi",
                selector: "td:first-child"
            },
            order: [[ 1, 'asc' ]],
            rowCallback: function(row, data) {
                $(row).data('id', data.id).css('cursor', 'pointer');

                $('td:not(:first-child):not(:last-child)', row).each(function() {
                    $(this).on('click', function() {
                        window.location.href = `${url_open_message}/${data.id}`
                    });
                });
            },
            drawCallback: function() {
                $('.btn_delete').each(function(index, valor) {
                    var me = $(this);
                    me.showConfirm({
                        title: 'Deseja Excluir esse Registro?',
                        closeOnConfirm: true,
                        ajax: {
                            type: 'post',
                            url: me.attr('href'),
                            dataType: 'json',
                            success: function(xhr) {
                                formWarning(xhr);
                                $('#data-table').DataTable().ajax.reload();
                            }
                        }
                    });
                });

                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    }
</script>

</html>