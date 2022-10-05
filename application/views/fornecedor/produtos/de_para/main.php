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
                            <th></th>
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
            pageLength: 5000,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'get',
                dataType: 'json'
            },
            columns: [
                {
                    defaultContent: '',
                    width: '100px',
                    orderable: false,
                    searchable: false
                },
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
                    name: 'descricao',
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
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            columnDefs: [
                {orderable: false, className: 'select-checkbox', targets: 0 },
                {targets: [1], visible: false }
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


        $('#ocultarSelecionados').click(function (e) {
            e.preventDefault();
            var elementos = [];
            var url = $(this).attr('href');


            $.map(dt1.rows('.selected').data(), function (item) {
                elementos.push(item.codigo);
            });

            if (elementos.length > 0) {
                $.post(url, {el: elementos}, function (xhr) {
                    $('#data-table').DataTable().ajax.reload();
                    formWarning(xhr);
                }, 'JSON');
            } else {
                formWarning({
                    type: 'warning',
                    message: "Nenhum registro selecionado!"
                });
            }
        });

    });
</script>
</body>

