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
                        <div class="col-6">
                            <div class="form-group">
                                <label for="id_fornecedor">Selecione um fornecedor para filtrar</label>
                                <select class="select2" id="id_fornecedor">
                                    <option value="">Selecione</option>
                                    <?php foreach($fornecedores as $fornecedor) { ?>
                                        <option value="<?php echo $fornecedor['id'] ?>"> <?php echo $fornecedor['nome_fantasia']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
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
                                        <table id="data-table-estado" class="table w-100 table-hover" 
                                            data-url="<?php echo $to_datatable_estado; ?>" 
                                            data-export="<?php echo $url_exportar; ?>" 
                                            data-delete_multiple="<?php echo $url_delete_multiple ?>">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="checkallEstado"></th>
                                                    <th hidden>ID</th>
                                                    <th class="text-nowrap">Desconto (%)</th>
                                                    <th class="text-nowrap">Produto</th>
                                                    <th class="text-nowrap">Preço</th>
                                                    <th class="text-nowrap">Preço Desconto</th>
                                                    <th class="text-nowrap">Quantidade</th>
                                                    <th class="text-nowrap">Dias</th>
                                                    <th class="text-nowrap">Estado</th>
                                                    <th class="text-nowrap">Regra Venda</th>
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
                                        <table id="data-table-cnpj" class="table w-100 table-hover" data-url="<?php echo $to_datatable_cnpj; ?>" >
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="checkallCnpj"></th>
                                                    <th hidden>ID</th>
                                                    <th class="text-nowrap">Desconto (%)</th>
                                                    <th class="text-nowrap">Produto</th>
                                                    <th class="text-nowrap">Preço</th>
                                                    <th class="text-nowrap">Preço Desconto</th>
                                                    <th class="text-nowrap">Quantidade</th>
                                                    <th class="text-nowrap">Dias</th>
                                                    <th class="text-nowrap">Cliente</th>
                                                    <th class="text-nowrap">Regra Venda</th>
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
    var url_estado = $('#data-table-estado').data('url');
    var url_cnpj = $('#data-table-cnpj').data('url');
    var url_delete_multiple = $('#data-table-estado').data('delete_multiple');

    var url_exportar = $('#data-table-estado').data('export');

    $(function() {

        newtable();

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

        $('#id_fornecedor').on('change', function () {

            $('#btnExport').attr('href', url_exportar + $(this).val());

            if ($(this).val() != "") {

                $('#data-table-estado').DataTable().destroy();
                $('#data-table-cnpj').DataTable().destroy();

                newtable( $(this).val() ); 

                $('.dataTables_filter').find(':input[type=search]').on('keyup', function () {
                    $(this).val($(this).val().toUpperCase());
                });
            }
        })

        $('.dataTables_filter').find(':input[type=search]').on('keyup', function () {
            $(this).val($(this).val().toUpperCase());
        });

        $('#checkallEstado').click(function(event) {checkall(dt1, document.getElementById('checkallEstado') ); });
        $('#checkallCnpj').click(function(event) {checkall(dt2, document.getElementById('checkallCnpj') ); });
    });

    function checkall(table, checkall) {
        if (checkall.checked == true) {
            table.rows().select();
        }else {
            table.rows().deselect();
        }
    }

    function newtable(id_fornecedor = null) {

        if ( id_fornecedor != null) {

            var url1 = url_estado + id_fornecedor;
            var url2 = url_cnpj + id_fornecedor;

            var dt1 = $('#data-table-estado').DataTable({
                processing: true,
                serverSide: true,
                lengthChange: false,
                ajax: {
                    url: url1,
                    type: 'post',
                    dataType: 'json',
                },
                columns: [
                    {defaultContent: '', orderable: false, searchable: false, sortable: false },
                    {name: 'vd.id', data: 'id', visible: false, searchable: false },
                    {name: 'vd.desconto_percentual', data: 'desconto_percentual', searchable: false, className: 'text-nowrap'},
                    {name: 'pc.nome_comercial', data: 'produto_descricao', className: 'text-nowrap'},
                    {name: 'vd.id_estado', data: 'preco', searchable: false, className: 'text-nowrap'},
                    {name: 'vd.id_cliente', data: 'preco_desconto', searchable: false, className: 'text-nowrap'},
                    {name: 'vd.quantidade', data: 'quantidade', searchable: false, className: 'text-nowrap'},
                    {name: 'vd.dias', data: 'dias', searchable: false, className: 'text-nowrap'},
                    {name: 'e.descricao', data: 'descricao', searchable: false, className: 'text-nowrap'},
                    {name: 'vd.regra_venda', data: 'regra_venda', className: 'text-center text-nowrap', searchable: false },
                ],
                columnDefs: [
                    {orderable: false, className: 'select-checkbox', targets: 0 },
                    {targets: [1], visible: false }
                ],
                select: {
                    style: 'multi',
                    selector: 'td:first-child'
                },
                order: [[ 3, 'asc' ]],
                rowCallback: function(row, data) {
                },
                drawCallback: function() {}
            });

            var dt2 = $('#data-table-cnpj').DataTable({
                processing: true,
                serverSide: true,
                lengthChange: false,
                ajax: {
                    url: url2,
                    type: 'post',
                    dataType: 'json',
                },
                columns: [
                    {defaultContent: '', orderable: false, searchable: false, sortable: false },
                    {name: 'vd.id', data: 'id', visible: false, searchable: false },
                    {name: 'vd.desconto_percentual', data: 'desconto_percentual', searchable: false, className: 'text-nowrap'},
                    {name: 'pc.nome_comercial', data: 'produto_descricao', className: 'text-nowrap'},
                    {name: 'vd.id_estado', data: 'preco', searchable: false, className: 'text-nowrap'},
                    {name: 'vd.id_cliente', data: 'preco_desconto', searchable: false, className: 'text-nowrap'},
                    {name: 'vd.quantidade', data: 'quantidade', searchable: false, className: 'text-nowrap'},
                    {name: 'vd.dias', data: 'dias', searchable: false, className: 'text-nowrap'},
                    {name: 'c.razao_social', data: 'razao_social', className: 'text-nowrap', searchable: false},
                    {name: 'vd.regra_venda', data: 'regra_venda', className: 'text-center text-nowrap', searchable: false },
                ],
                columnDefs: [
                    {orderable: false, className: 'select-checkbox', targets: 0 },
                    {targets: [1], visible: false }
                ],
                select: {
                    style: 'multi',
                    selector: 'td:first-child'
                },
                order: [[ 3, 'asc' ]],
                rowCallback: function(row, data) {
                },
                drawCallback: function() {}
            });
        } else {
            var dt1 = $('#data-table-estado').DataTable({
                serverSide: false,
                lengthChange: false,
                oLanguage: {
                    sEmptyTable: "Selecione um fornecedor na lista"
                },
                ordering: false,
                rowCallback: function(row, data) {},
                drawCallback: function() {}
            });

            var dt2 = $('#data-table-cnpj').DataTable({
                serverSide: false,
                lengthChange: false,
                oLanguage: {
                    sEmptyTable: "Selecione um fornecedor na lista"
                },
                ordering: false,
                rowCallback: function(row, data) {},
                drawCallback: function() {}
            });
        }
    }
</script>

</html>