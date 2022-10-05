<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>

    <div class="content__inner" id="printAll">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive col-sm">
                    <table id="data-table" class="table table-condensend table-hover" 
                    data-url="<?php echo $to_datatable; ?>" 
                    data-modal="<?php echo $url_modal ?>"
                    data-delete_multiple="<?php echo $url_delete ?>"
                    >
                        <thead>
                        <tr>
                            <th>
                                <div class="checkbox">
                                    <input type="checkbox" id="checkall">
                                    <label class="checkbox__label" for="checkall"></label>
                                </div>
                            </th>
                            <th>Comprador</th>
                            <th>Gerente</th>
                            <th>Consultor</th>
                            <th>Geral</th>
                            <th>Grupo</th>
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

    var url_delete_multiple = $('#data-table').data('delete_multiple');

    $(function () {
        var table = $('#data-table').DataTable({
            serverSide: false,
            lengthChange: false,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'post',
                dataType: 'json',
            },
            columnDefs: [
                { orderable: false, className: 'select-checkbox', targets: 0 }
            ],
            select: {
                style: "multi",
                selector: "td:first-child"
            },
            columns: [
                {defaultContent: '', orderable: false, searchable: false},
                { name: 'c.razao_social', data: 'razao_social' },
                { name: 'email_notificacao.gerente', data: 'gerente'},
                { name: 'email_notificacao.consultor', data: 'consultor'},
                { name: 'email_notificacao.geral', data: 'geral'},
                { name: 'email_notificacao.grupo', data: 'grupo'},
            ],
            order: [[ 1, 'asc' ]],
            rowCallback: function(row, data) {

                $(row).css('cursor', 'pointer');
            },
            drawCallback: function() {}
        });

        $('#data-table tbody').on('click', 'tr td:not(:first-child)', function () {
            var rowIdx = table.cell( this ).index().row;
            var data = table.row( rowIdx ).data();

            $.ajax({
                type: 'post',
                url: $('#data-table').data('modal') + '/' + data.id,
                dataType: 'html',
                success: function(response) {
                    $('body').append(response);
                    $('.modal').modal({
                        keyboard: false
                    }, 'show').on('hide.bs.modal', function() {
                        $('#data-table').DataTable().ajax.reload();
                        $('.modal').remove();
                    });
                }
            });
        });

        $('#btnAdicionar').on('click', function(e) {
            e.preventDefault();
            let me = $(this);

            $.ajax({
                url: me.attr('href'),
                type: 'get',
                dataType: 'html', 
                data: {
                },
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

        $('#btnDeleteMultiple').click(function (e) {
            e.preventDefault();
            var elementos = [];

            $.map(table.rows('.selected').data(), function (item) {
                elementos.push(item.id);
            });

            if (elementos.length > 0) {
                $.post(url_delete_multiple, {itens: elementos}, function (xhr) {
                    $('#data-table').DataTable().ajax.reload();
                    formWarning(xhr);
                    $('[data-select]').attr('checked', false)
                }, 'JSON');
            } else {
                formWarning({type: 'warning', message: "Nenhum registro selecionado!"});
            }
        });

    });
</script>
</body>

