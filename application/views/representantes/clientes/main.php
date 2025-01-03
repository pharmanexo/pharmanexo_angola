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
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="filtro-cliente">Filtrar por status</label>
                            <select class="select2" id="filtro-status" data-index="5">
                                <option value="">Selecione</option>
                                <option value="0">Inativo</option>
                                <option value="1">Ativo</option>
                                <option value="2">Bloqueado</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mx-auto mt-3">
                    <div class="col-12 col-sm">
                        <div class="table-responsive">
                            <?php $url = (isset($datasource)) ? $datasource : ''; ?>
                            <?php $update = (isset($url_update)) ? $url_update : ''; ?>

                            <table id="table-cliente" class="table table-condensend table-hover w-100" 
                                data-url="<?php echo $url; ?>" 
                                data-update="<?php echo $update; ?>"
                                data-status="<?php echo $url_status ?>" 
                                data-delete_multiple="<?php echo $url_delete_multiple ?>">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <th>CNPJ</th>
                                    <th>Razão Social</th>
                                    <th>Telefone</th>
                                    <th>Status</th>
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
    var url_status = $('#table-cliente').data('status');
    var url_delete_multiple = $('#table-cliente').data('delete_multiple');
    var url_update = $('#table-cliente').data('update');

    $(function() {
        var table = $('#table-cliente').DataTable({
            processing: true,
            serverSide: false,
            lengthChange: false,
            responsive: true,
            pageLength: 10,
            ajax: {
                url: $('#table-cliente').data('url'),
                type: 'POST',
                dataType: 'json'
            },
            columns: [
                {defaultContent: '', orderable: false, searchable: false},
                {data: 'id', name: 'id', visible: false},
                {data: 'cnpj', name: 'cnpj'},
                {data: 'razao_social', name: 'razao_social'},
                {data: 'telefone', name: 'telefone'},
                {data: 'status', name: 'status', className: 'text-center', orderable: false}
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

                var classe = (data.status == 0 ? "text-danger" : (data.status == 1 ? 'text-success' : 'text-warning'));
                var icone =  (data.status == 0 ? "user-times" : (data.status == 1 ? 'user-check' : 'ban'));
                var status = (data.status == 0 ? "Inativo" : (data.status == 1 ? 'Ativo' : 'Bloqueado'));

                var dp = $('<div class="dropdown"></div>');
                var dp_link = $('<a href="#" data-toggle="dropdown" title="' + status + '" class="dropdown-toggle ' + classe + '"><i class="fas fa-' + icone + '" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i></a>');

                var dp_menu = `<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                   <a class="dropdown-item setStatus" href="${url_status}${data.id}/1">Ativar</a>
                   <a class="dropdown-item setStatus" href="${url_status}${data.id}/2">Bloquear</a>
                   <a class="dropdown-item setStatus" href="${url_status}${data.id}/0">Inativar</a>
                </div>`;

               dp.append(dp_link).append(dp_menu);

               $('td:eq(4)', row).html(dp);

                $('td:not(:first-child):not(:last-child)', row).each(function () {
                    $(this).on('click', function () {
                        window.location.href = `${url_update}/${data.id}`
                    });
                });
            },
            drawCallback: function() {
                $('.setStatus').click(function (e) {
                    e.preventDefault();
                    var url = $(this).attr("href");
                    $.get(url, function (xhr) {
                        table.ajax.reload();
                    })
                });

                $('[data-toggle="tooltip"]').tooltip();
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
        $('[data-index]').on('change', function() {
            var col = $(this).data('index');
            var value = $('#filtro-status').val();

            table.columns(col).search(value).draw();
        });
    });
</script>

</html>
