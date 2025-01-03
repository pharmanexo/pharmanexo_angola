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

                        <?php if ($this->session->userdata('integracao') != 0) : ?>
                        <li class="nav-item">
                            <a class="nav-link" id="cnpj-tab" data-toggle="tab" href="#tabCnpj" role="tab" aria-controls="cnpj" aria-selected="false">CNPJ</a>
                        </li>
                        <?php endif; ?>
                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <!-- Tab Estado -->
                        <div class="tab-pane fade show active" id="tabEstado" role="tabpanel" aria-labelledby="estado-tab">
                            <div class="row mx-auto mt-3">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="data-table-estado" class="table w-100 table-hover" data-url="<?php echo $to_datatable_estado; ?>" data-delete_multiple="<?php echo $url_delete_multiple ?>" >
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="checkallEstado"></th>
                                                    <th>ID</th>
                                                    <th>Código</th>
                                                    <th>Desconto (%)</th>
                                                    <th style="width: 200px;">Produto</th>
                                                    <th>Marca</th>
                                                    <th>Preço</th>
                                                    <th>Preço Desconto</th>
                                                    <th>Estado</th>
                                                  
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if ($this->session->userdata('integracao') != 0) : ?>
                        <!-- Tab CNPJ -->
                        <div class="tab-pane fade" id="tabCnpj" role="tabpanel" aria-labelledby="cnpj-tab">
                            <div class="row mx-auto mt-3">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="data-table-cnpj" class="table w-100 table-hover w-100" data-url="<?php echo $to_datatable_cnpj; ?>">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="checkallCnpj"></th>
                                                    <th>ID</th>
                                                    <th>Código</th>
                                                    <th>Desconto (%)</th>
                                                    <th>Produto</th>
                                                    <th>Marca</th>
                                                    <th>Preço</th>
                                                    <th>Preço Desconto</th>
                                                    <th>Cliente</th>
                                                  
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>


        </div>
    </div>
</body>

<?php echo $scripts; ?>

<script>
    $(function() {

        var url_delete_multiple = $('#data-table-estado').data('delete_multiple');

        var buttonCommon = {
            exportOptions: {
                format: {
                    body: function ( data, row, column, node ) {
                        return (column == 2 || column == 4 || column == 5) ? data.replace( /[.]/g, '' ).replace( /[,]/g, '.' ) : data;
                    }
                }
            }
        };
        
        var dt1 = $('#data-table-estado').DataTable({
            serverSide: true,
            lengthChange: false,
            dom: 'Bfrtip',
            buttons: [ $.extend( true, {}, buttonCommon, { extend: 'excelHtml5'} ) ],
            ajax: {
                url: $('#data-table-estado').data('url'),
                type: 'post',
                dataType: 'json',
            },
            columns: [
                {defaultContent: '', orderable: false, searchable: false, sortable: false },
                {name: 'vendas_diferenciadas.id', data: 'id', visible: false },
                {name: 'vendas_diferenciadas.codigo', data: 'codigo'},
                {name: 'vendas_diferenciadas.desconto_percentual', data: 'desconto_percentual', className: 'text-nowrap'},
                {name: 'produtos_catalogo.nome_comecial', data: 'produto_descricao'},
                {name: 'produtos_catalogo.marca', data: 'marca'},
                {name: 'vendas_diferenciadas.preco_unidade', data: 'preco', searchable: false,},
                {name: 'vendas_diferenciadas.preco_unidade', data: 'preco_desconto', searchable: false, className: 'text-nowrap'},
                {name: 'estados.descricao', data: 'descricao'},
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
                $(row).data('id', data.id).css('cursor', 'pointer');  
            },
            drawCallback: function() {}
        });

        var dt2 = $('#data-table-cnpj').DataTable({
            serverSide: false,
            lengthChange: false,
            dom: 'Bfrtip',
            buttons: [ $.extend( true, {}, buttonCommon, { extend: 'excelHtml5'} ) ],
            ajax: {
                url: $('#data-table-cnpj').data('url'),
                type: 'post',
                dataType: 'json',
            },
            columns: [
                {defaultContent: '', width: '', orderable: false, searchable: false, sortable: false },
                {name: 'vendas_diferenciadas.id', data: 'id', visible: false },
                {name: 'vendas_diferenciadas.codigo', data: 'codigo'},
                {name: 'vendas_diferenciadas.desconto_percentual', data: 'desconto_percentual', className: 'text-nowrap'},
                {name: 'vendas_diferenciadas.produto_descricao', data: 'produto_descricao'},
                {name: 'produtos_catalogo.marca', data: 'marca'},
                {name: 'produtos_catalogo.preco_unidade', data: 'preco', searchable: false,},
                {name: 'produtos_catalogo.preco_unidade', data: 'preco_desconto', searchable: false, className: 'text-nowrap'},
                {name: 'compradores.razao_social', data: 'razao_social', className: 'text-nowrap'},
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
                $(row).data('id', data.id).css('cursor', 'pointer');
            },

            drawCallback: function() { }
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

        $('#checkallEstado').click(function(event) {checkall(dt1, document.getElementById('checkallEstado') ); });
        $('#checkallCnpj').click(function(event) {checkall(dt2, document.getElementById('checkallCnpj') ); });
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