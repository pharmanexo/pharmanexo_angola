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
                            <th>
                                <div class="checkbox">
                                    <input type="checkbox" id="checkall">
                                    <label class="checkbox__label" for="checkall"></label>
                                </div>
                            </th>
                            <th>

                            </th>
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
                    visible: false
                },
                {
                    defaultContent: '',
                    width: '100px',
                    orderable: false,
                    searchable: false
                },
            ],
            "order": [[ 1, "desc" ]],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            columnDefs: [
                {orderable: false, className: 'select-checkbox', targets: 0 },
                {targets: [1], visible: false }
            ],
            rowCallback: function (row, data) {
                $(row).data('id', data.id).css('cursor', 'pointer');
            },
            drawCallback: function () {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });


        $('#btnAprovar').click(function (e) {
            e.preventDefault();
            var elementos = [];
            var url = $(this).attr('href');


            $.map(dt1.rows('.selected').data(), function (item) {
                elementos.push({
                    codigo: item.codigo,
                    id_sintese: item.id_sintese
                });
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

        $('#btnRejeitar').click(function (e) {
            e.preventDefault();
            var elementos = [];
            var url = $(this).attr('href');


            $.map(dt1.rows('.selected').data(), function (item) {
                elementos.push({
                    codigo: item.codigo,
                    id_sintese: item.id_sintese
                });
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
        $('#checkall').click(function (event) { checkall(dt1, document.getElementById('checkall')); });
    });

    function checkall(table, checkall)
    {
        if (checkall.checked == true) {
            table.rows({search:'applied'}).select();
        } else {
            table.rows().deselect();
        }
    }
</script>
</body>

