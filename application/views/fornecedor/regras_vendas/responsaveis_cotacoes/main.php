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
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="data-table-estado" class="table table-condensend table-hover w-100"
                                   data-url="<?php echo $to_datatable; ?>"
                                   data-update="<?php echo $url_update; ?>"
                                   data-delete="<?php echo $url_delete ?>"
                                   data-delete_multiple="<?php echo $url_delete_multiple ?>
">
                                <thead>
                                <tr>
                                    <th>
                                        <div class="checkbox">
                                            <input type="checkbox" id="checkall-estados">
                                            <label class="checkbox__label" for="checkall-estados"></label>
                                        </div>
                                    </th>
                                    <th>ID</th>
                                    <th>Comprador</th>
                                    <th>Assistente</th>
                                    <th>Gerente</th>
                                    <th>Consultor</th>
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
</div>
</body>

<?php echo $scripts; ?>

<script>
    var url_delete_multiple = $('#data-table-estado').data('delete_multiple');

    $(function () {
        var dt1 = $('#data-table-estado').DataTable({
            processing: true,
            serverSide: false,
            lengthChange: false,
            ajax: {
                url: $('#data-table-estado').data('url'),
                type: 'get',
                dataType: 'json'
            },
            columns: [
                {defaultContent: '', width: '100px', orderable: false, searchable: false},
                {name: 'id', data: 'id', visible: false},
                {name: 'comprador_razao_social', data: 'comprador_razao_social'},
                {name: 'assistente', data: 'assistente'},
                {name: 'gerente', data: 'gerente'},
                {name: 'consultor', data: 'consultor'},
            ],
            columnDefs: [
                {orderable: false, className: 'select-checkbox', targets: 0},
                {targets: [1], visible: false}
            ],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            order: [[2, 'asc']],
            rowCallback: function (row, data) {
                $(row).css('cursor', 'pointer');
            },

            drawCallback: function () {
            }
        });

        var dt2 = $('#data-table-cnpj').DataTable({
            processing: true,
            serverSide: false,
            lengthChange: false,
            ajax: {
                url: $('#data-table-cnpj').data('url'),
                type: 'get',
                dataType: 'json'
            },
            columns: [
                {defaultContent: '', width: '100px', orderable: false, searchable: false},
                {name: 'controle_cotacoes.id', data: 'id', visible: false},
                {name: 'compradores.razao_social', data: 'razao_social'},
                {name: 'compradores.cnpj', data: 'cnpj'},
                {name: 'controle_cotacoes.regra_venda', data: 'regra_venda'},
            ],
            columnDefs: [
                {orderable: false, className: 'select-checkbox', targets: 0},
                {targets: [1], visible: false}
            ],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            order: [[2, 'asc']],
            rowCallback: function (row, data) {
                $(row).css('cursor', 'pointer');
            },

            drawCallback: function () {
            }
        });

        $('#data-table-estado tbody').on('click', 'tr td:not(:first-child)', function (e) {
            e.preventDefault();
            var rowIdx = dt1.cell(this).index().row;
            var data = dt1.row(rowIdx).data();

            $.ajax({
                type: 'post',
                url: $('#data-table-estado').data('update') + '/' + data.id,
                dataType: 'html',
                success: function (response) {
                    $('body').append(response);
                    $('.modal').modal({
                        keyboard: false
                    }, 'show').on('hide.bs.modal', function () {
                        $('#data-table-estado').DataTable().ajax.reload();
                        $('.modal').remove();
                    });
                }
            });
        });

        $('#data-table-cnpj tbody').on('click', 'tr td:not(:first-child)', function (e) {
            e.preventDefault();
            var rowIdx = dt2.cell(this).index().row;
            var data = dt2.row(rowIdx).data();

            $.ajax({
                type: 'post',
                url: $('#data-table-cnpj').data('update') + '/' + data.id,
                dataType: 'html',
                success: function (response) {
                    $('body').append(response);
                    $('.modal').modal({
                        keyboard: false
                    }, 'show').on('hide.bs.modal', function () {
                        $('#data-table-cnpj').DataTable().ajax.reload();
                        $('.modal').remove();
                    });
                }
            });
        });

        $('#btnAdicionar').on('click', function (e) {
            e.preventDefault();
            let me = $(this);

            $.ajax({
                url: me.attr('href'),
                type: 'get',
                dataType: 'html',

                success: function (response) {
                    $('body').append(response);
                    $('.modal').modal({
                        keyboard: false
                    }, 'show').on('hide.bs.modal', function () {
                        $('.modal').remove();
                        $('#data-table-estado').DataTable().ajax.reload();
                        $('#data-table-cnpj').DataTable().ajax.reload();
                    });
                }
            })
        });

        $('#btnDeleteMultiple').click(function (e) {
            e.preventDefault();
            var elementos = [];
            var url = $();


            $.map(dt1.rows('.selected').data(), function (item) {
                elementos.push(item.id);
            });

            $.map(dt2.rows('.selected').data(), function (item) {
                elementos.push(item.id);
            });

            if (elementos.length > 0) {
                $.post(url_delete_multiple, {el: elementos}, function (xhr) {
                    $('#data-table-estado').DataTable().ajax.reload();
                    $('#data-table-cnpj').DataTable().ajax.reload();
                    formWarning(xhr);
                }, 'JSON');
            } else {
                formWarning({
                    type: 'warning',
                    message: "Nenhum registro selecionado!"
                });
            }
        });


        $('#checkall-estados').click(function (event) {
            checkall(dt1, document.getElementById('checkall-estados'));
        });
        $('#checkall-cnpjs').click(function (event) {
            checkall(dt2, document.getElementById('checkall-cnpjs'));
        });

    });

    function checkall(table, checkall) {
        if (checkall.checked == true) {
            table.rows({search: 'applied'}).select();
        } else {
            table.rows().deselect();
        }
    }

</script>

</html>