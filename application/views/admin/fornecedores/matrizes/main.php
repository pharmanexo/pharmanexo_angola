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
                            <table id="data-table" class="table table-condensend table-hover w-100" 
                                data-url="<?php echo $datasource; ?>" 
                                data-update="<?php echo $url_update; ?>"
                                data-delete_multiple="<?php echo $url_delete_multiple ?>">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="checkbox">
                                                <input type="checkbox" id="checkall">
                                                <label class="checkbox__label" for="checkall"></label>
                                            </div>
                                        </th>
                                        <th>Nome</th>
                                        <th>Fornecedores</th>
                                        <th>Criado em</th>
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

    var url_delete_multiple = $("#data-table").data('delete_multiple');

    $(function() {
        var table = $('#data-table').DataTable({
            processing: true,
            serverSide: false,
            lengthChange: false,
            responsive: true,
            pageLength: 10,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'POST',
                dataType: 'json'
            },
            columns: [
                {defaultContent: '', orderable: false, searchable: false},
                { data: 'nome', name: 'nome', width: '150px' },
                { data: 'fornecedores', name: 'fornecedores', searchable: false },
                { data: 'data_criacao', name: 'data_criacao', className: 'text-nowrap' }
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
                $(row).css('cursor', 'pointer');

                $('td:not(:first-child):not(:last-child)', row).each(function() {
                    $(this).on('click', function() {
                        $.ajax({
                            type: 'post',
                            url: $('#data-table').data('update') + '/' + data.id,
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
                });

            },
            drawCallback: function() {}
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

        $('#checkall').click(function (event) { checkall(table, document.getElementById('checkall')); });

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

</html>
