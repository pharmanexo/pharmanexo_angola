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
                                <label for="filtro-cliente">Filtrar por fornecedor</label>
                                <select class="select2" id="filtro-status" data-index="10">
                                    <option value="">Selecione</option>
                                    <?php foreach($fornecedores as $fornecedor) { ?>
                                        <option value="<?php echo $fornecedor['id'] ?>"> <?php echo $fornecedor['razao_social'] ?></option>
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
                                        <table id="data-table-estado" class="table w-100 table-hover" data-url="<?php echo $to_datatable_estado; ?>" data-delete_multiple="<?php echo $url_delete_multiple ?>" >
                                            <thead>
                                                <tr>
                                                    <!-- <th><input type="checkbox" id="checkallEstado"></th> -->
                                                    <th>ID</th>
                                                    <th>Desconto (%)</th>
                                                    <th style="width: 200px;">Produto</th>
                                                    <th>Preço</th>
                                                    <th>Preço Desconto</th>
                                                    <th>Comissão</th>
                                                    <th>Quantidade</th>
                                                    <th>Dias</th>
                                                    <th>Estado</th>
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
                                        <table id="data-table-cnpj" class="table w-100 table-hover w-100" data-url="<?php echo $to_datatable_cnpj; ?>">
                                            <thead>
                                                <tr>
                                                    <!-- <th><input type="checkbox" id="checkallCnpj"></th> -->
                                                    <th>ID</th>
                                                    <th>Desconto (%)</th>
                                                    <th>Produto</th>
                                                    <th>Preço</th>
                                                    <th>Preço Desconto</th>
                                                    <th>Comissão</th>
                                                    <th>Quantidade</th>
                                                    <th>Dias</th>
                                                    <th>Cliente</th>
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


        var oldExportAction = function (self, e, dt, button, config) {
            if (button[0].className.indexOf('buttons-excel') >= 0) {
                if ($.fn.dataTable.ext.buttons.excelHtml5.available(dt, config)) {
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config);
                }
                else {
                    $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                }
            } else if (button[0].className.indexOf('buttons-print') >= 0) {
                $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
            }
        };

        var newExportAction = function (e, dt, button, config) {
            var self = this;
            var oldStart = dt.settings()[0]._iDisplayStart;

            dt.one('preXhr', function (e, s, data) {
                // Just this once, load all data from the server...
                data.start = 0;
                data.length = 2147483647;

                dt.one('preDraw', function (e, settings) {
                    // Call the original action function 
                    oldExportAction(self, e, dt, button, config);

                    dt.one('preXhr', function (e, s, data) {
                        // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                        // Set the property to what it was before exporting.
                        settings._iDisplayStart = oldStart;
                        data.start = oldStart;
                    });

                    // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                    setTimeout(dt.ajax.reload, 0);

                    // Prevent rendering of the full data to the DOM
                    return false;
                });
            });

            // Requery the server with the new one-time export settings
            dt.ajax.reload();
        };
        
        var dt1 = $('#data-table-estado').DataTable({
            serverSide: true,
            lengthChange: false,
            dom: 'Bfrtip',
            buttons: [ $.extend( true, {extend: 'excel', action: newExportAction}, buttonCommon, { extend: 'excelHtml5'} ) ],
            ajax: {
                url: $('#data-table-estado').data('url'),
                type: 'post',
                dataType: 'json',
                data: function (data) {
                    let nw_data = data;
                 
                    if ($('#filtro-status').val() !== '') {
                        let dt1 = $('#filtro-status').val();
                       
                        nw_data.columns[9].search.value =  dt1;
                        nw_data.columns[9].search.type = 'equal';
                    }

                    return nw_data;
                }
            },
            columns: [
                {name: 'vendas_diferenciadas.id', data: 'id', visible: false },
                {name: 'vendas_diferenciadas.desconto_percentual',  width: '100px', data: 'desconto_percentual', className: 'text-nowrap'},
                {name: 'produtos_catalogo.nome_comecial', data: 'produto_descricao'},
                {name: 'produtos_preco.preco_unitario', data: 'preco', searchable: false,},
                {name: 'produtos_preco.preco_unitario', data: 'preco_desconto', searchable: false, className: 'text-nowrap'},
                {name: 'vendas_diferenciadas.comissao', data: 'comissao', searchable: false,},
                {name: 'vendas_diferenciadas.quantidade', data: 'quantidade', searchable: false},
                {name: 'vendas_diferenciadas.dias', data: 'dias'},
                {name: 'estados.descricao', data: 'descricao', className: 'text-nowrap'},
                {name: 'vendas_diferenciadas.id_fornecedor', data: 'id_fornecedor', visible: false},
            ],
            order: [[ 1, 'asc' ]],
            rowCallback: function(row, data) {
                 
            },
            drawCallback: function() {}
        });

        var dt2 = $('#data-table-cnpj').DataTable({
            serverSide: true,
            lengthChange: false,
            dom: 'Bfrtip',
            buttons: [ $.extend( true, {}, buttonCommon, { extend: 'excelHtml5'} ) ],
            ajax: {
                url: $('#data-table-cnpj').data('url'),
                type: 'post',
                dataType: 'json',
                data: function (data) {
                    let nw_data2 = data;

                    if ($('#filtro-status').val() !== '') {
                        let dt2 = $('#filtro-status').val();
                        
                        nw_data2.columns[9].search.value =  dt2;
                        nw_data2.columns[9].search.type = 'equal';
                    }

                    return nw_data2;
                }
            },
            columns: [
                {name: 'vendas_diferenciadas.id', data: 'id', visible: false },
                {name: 'vendas_diferenciadas.desconto_percentual',  width: '100px', data: 'desconto_percentual'},
                {name: 'vendas_diferenciadas.produto_descricao', data: 'produto_descricao'},
                {name: 'produtos_preco.preco_unitario', data: 'preco', searchable: false,},
                {name: 'produtos_preco.preco_unitario', data: 'preco_desconto', searchable: false, className: 'text-nowrap'},
                {name: 'vendas_diferenciadas.comissao', data: 'comissao', searchable: false,},
                {name: 'vendas_diferenciadas.quantidade', data: 'quantidade', searchable: false,},
                {name: 'vendas_diferenciadas.dias', data: 'dias', searchable: false,},
                {name: 'vendas_diferenciadas.razao_social', data: 'razao_social', className: 'text-nowrap'},
                {name: 'vendas_diferenciadas.id_fornecedor', data: 'id_fornecedor', visible: false},
            ],
            order: [[ 1, 'asc' ]],
            rowCallback: function(row, data) {
               
            },

            drawCallback: function() { }
        });

        $('#filtro-status').on('change', function() {
            dt1.ajax.reload();
            dt2.ajax.reload();
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
            table.rows().select();
        }else {
            table.rows().deselect();
        }
    }
</script>

</html>