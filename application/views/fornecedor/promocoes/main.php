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
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="estado-tab" data-toggle="tab" href="#tabEstado" role="tab" aria-controls="estado" aria-selected="true">Estado</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="cnpj-tab" data-toggle="tab" href="#tabCnpj" role="tab" aria-controls="cnpj" aria-selected="false">CNPJ</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <!-- Tab Estado -->
                        <div class="tab-pane fade show active" id="tabEstado" role="tabpanel" aria-labelledby="estado-tab">
                            <div class="row mx-auto mt-3">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="data-table-estado" class="table w-100 table-hover" data-url="<?php echo $to_datatable_estado; ?>" data-update="<?php echo $url_update; ?>">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="checkbox">
                                                            <input type="checkbox" id="checkall-estados">
                                                            <label class="checkbox__label" for="checkall-estados"></label>
                                                        </div>
                                                    </th>
                                                    <th>ID</th>
                                                    <th>Código</th>
                                                    <th>Desconto (%)</th>
                                                    <th style="width: 200px;">Produto</th>
                                                    <th>Preço</th>
                                                    <th>Preço Desconto</th>
                                                    <th>Lote</th>
                                                    <th>Estado</th>
                                                    <th>Regra Venda</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab CNPJ -->
                        <div class="tab-pane fade" id="tabCnpj" role="tabpanel" aria-labelledby="cnpj-tab">
                            <div class="row mx-auto mt-3">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="data-table-cnpj" class="table w-100 table-hover" data-url="<?php echo $to_datatable_cnpj; ?>" data-update="<?php echo $url_update; ?>">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="checkbox">
                                                            <input type="checkbox" id="checkall-cnpjs">
                                                            <label class="checkbox__label" for="checkall-cnpjs"></label>
                                                        </div>
                                                    </th>
                                                    <th>ID</th>
                                                    <th>Código</th>
                                                    <th>Desconto (%)</th>
                                                    <th style="width: 200px;">Produto</th>
                                                    <th>Preço</th>
                                                    <th>Preço Desconto</th>
                                                    <th>Lote</th>
                                                    <th>Comprador</th>
                                                    <th>Regra Venda</th>
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
    </div>
</body>

<?php echo $scripts; ?>

<script>
    var url_delete_multiple = "<?php if(isset($url_delete_multiple)) echo $url_delete_multiple; ?>";

    $(function() {
       
        var dt1 = $('#data-table-estado').DataTable({
            serverSide: false,
            lengthChange: false,
            paginate: false,
            ajax: {
                url: $('#data-table-estado').data('url'),
                type: 'post',
                dataType: 'json',
            },
            columns: [
                {defaultContent: '', orderable: false, searchable: false, sortable: false },
                { name: 'vd.id', data: 'id', visible: false },
                { name: 'vd.codigo', data: 'codigo' },
                { name: 'vd.desconto_percentual', data: 'desconto_percentual', className: 'text-nowrap' },
                { name: 'pc.nome_comercial', data: 'nome_comercial', className: 'text-nowrap' },
                { name: 'preco', data: 'preco', searchable: false },
                { name: 'preco_desconto', data: 'preco_desconto', className: 'text-nowrap', searchable: false },
                { name: 'vd.lote', data: 'lote' },
                { name: 'e.estado', data: 'estado', className: 'text-nowrap'},
                { name: 'vd.regra_venda', data: 'regra_venda', className: 'text-nowrap' },
            ],
            columnDefs: [
                {orderable: false, className: 'select-checkbox', targets: 0 },
                {targets: [1], visible: false }
            ],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            order: [[ 1, 'asc' ]],
            rowCallback: function(row, data) {
                $(row).css('cursor', 'pointer');
            },
            drawCallback: function() {}
        });

        var dt2 = $('#data-table-cnpj').DataTable({
            serverSide: false,
            lengthChange: false,
            paginate: false,
            ajax: {
                url: $('#data-table-cnpj').data('url'),
                type: 'post',
                dataType: 'json',
            },
            columns: [
                {defaultContent: '', orderable: false, searchable: false, sortable: false },
                { name: 'vd.id', data: 'id', visible: false },
                { name: 'vd.codigo', data: 'codigo' },
                { name: 'vd.desconto_percentual', data: 'desconto_percentual', className: 'text-nowrap' },
                { name: 'pc.nome_comercial', data: 'nome_comercial', className: 'text-nowrap' },
                { name: 'preco', data: 'preco', searchable: false },
                { name: 'preco_desconto', data: 'preco_desconto', className: 'text-nowrap', searchable: false },
                { name: 'vd.lote', data: 'lote' },
                { name: 'c.razao_social', data: 'comprador', className: 'text-nowrap'},
                { name: 'vd.regra_venda', data: 'regra_venda', className: 'text-nowrap' },
            ],
            columnDefs: [
                {orderable: false, className: 'select-checkbox', targets: 0 },
                {targets: [1], visible: false }
            ],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            order: [[ 1, 'asc' ]],
            rowCallback: function(row, data) {
                $(row).css('cursor', 'pointer');
            },
            drawCallback: function() {}
        });

        $('#data-table-estado tbody').on('click', 'tr td:not(:first-child)', function (e) {
            e.preventDefault();
            var rowIdx = dt1.cell( this ).index().row;
            var data = dt1.row( rowIdx ).data();

            $.ajax({
                type: 'post',
                url: $('#data-table-estado').data('update') + '/' + data.id,
                dataType: 'html',
                success: function(response) {
                    $('body').append(response);
                    $('.modal').modal({
                        keyboard: false
                    }, 'show').on('hide.bs.modal', function() {
                        $('#data-table-estado').DataTable().ajax.reload();
                        $('.modal').remove();
                    });
                }
            });
        });

        $('#data-table-cnpj tbody').on('click', 'tr td:not(:first-child)', function (e) {
            e.preventDefault();
            var rowIdx = dt2.cell( this ).index().row;
            var data = dt2.row( rowIdx ).data();

            $.ajax({
                type: 'post',
                url: $('#data-table-cnpj').data('update') + '/' + data.id,
                dataType: 'html',
                success: function(response) {
                    $('body').append(response);
                    $('.modal').modal({
                        keyboard: false
                    }, 'show').on('hide.bs.modal', function() {
                        $('#data-table-cnpj').DataTable().ajax.reload();
                        $('.modal').remove();
                    });
                }
            });
        });

        $('#btnDeleteMultiple').click(function(e) {
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
                $.post(url_delete_multiple, {
                    el: elementos
                }, function(xhr) {
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

        $('#checkall-estados').click(function(event) {checkall(dt1, document.getElementById('checkall-estados') ); });
        $('#checkall-cnpjs').click(function(event) {checkall(dt2, document.getElementById('checkall-cnpjs') ); });
    });

    function checkall(table, checkall) {
        if (checkall.checked == true) {
            table.rows({search:'applied'}).select();
        }else {
            table.rows().deselect();
        }
    }
</script>

</html>