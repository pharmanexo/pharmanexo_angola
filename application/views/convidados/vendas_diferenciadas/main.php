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
                                        <table id="data-table-estado" class="table w-100 table-hover" data-url="<?php echo $to_datatable_estado; ?>">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Desconto (%)</th>
                                                    <th style="width: 200px;">Produto</th>
                                                    <th>Preço</th>
                                                    <th>Preço Desconto</th>
                                                    <th>Comissão</th>
                                                    <th>Quantidade</th>
                                                    <th>Dias</th>
                                                    <th>Estado</th>
                                                    <th>Regra Venda</th>
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
                                        <table id="data-table-cnpj" class="table w-100 table-hover" data-url="<?php echo $to_datatable_cnpj; ?>">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Desconto (%)</th>
                                                    <th>Produto</th>
                                                    <th>Preço</th>
                                                    <th>Preço Desconto</th>
                                                    <th>Comissão</th>
                                                    <th>Quantidade</th>
                                                    <th>Dias</th>
                                                    <th>Cliente</th>
                                                    <th>Regra Venda</th>
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
            serverSide: false,
            lengthChange: false,
            dom: 'Bfrtip',
            buttons: [ $.extend( true, {}, buttonCommon, { extend: 'excelHtml5'} ) ],
            ajax: {
                url: $('#data-table-estado').data('url'),
                type: 'post',
                dataType: 'json',
            },
            columns: [
                {name: 'vendas_diferenciadas.id', data: 'id', visible: false },
                {name: 'vendas_diferenciadas.desconto_percentual', data: 'desconto_percentual'},
                {name: 'produtos_catalogo.nome_comecial', data: 'produto_descricao'},
                {name: 'vendas_diferenciadas.preco_unidade', data: 'preco', searchable: false,},
                {name: 'vendas_diferenciadas.preco_unidade', data: 'preco_desconto', searchable: false,},
                {name: 'vendas_diferenciadas.comissao', data: 'comissao', searchable: false,},
                {name: 'vendas_diferenciadas.quantidade', data: 'quantidade', searchable: false,},
                {name: 'vendas_diferenciadas.dias', data: 'dias'},
                {name: 'estados.descricao', data: 'descricao'},
                {name: 'vendas_diferenciadas.regra_venda', data: 'status_regra_venda', className: 'text-center text-nowrap' },
            ],
            order: [[ 1, 'asc' ]],
            rowCallback: function(row, data) {},
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
                {name: 'vendas_diferenciadas.id', data: 'id', visible: false },
                {name: 'vendas_diferenciadas.desconto_percentual', data: 'desconto_percentual' , className: 'text-nowrap'},
                {name: 'vendas_diferenciadas.produto_descricao', data: 'produto_descricao'},
                {name: 'produtos_catalogo.preco_unidade', data: 'preco', searchable: false,},
                {name: 'produtos_catalogo.preco_unidade', data: 'preco_desconto', searchable: false, className: 'text-nowrap'},
                {name: 'vendas_diferenciadas.comissao', data: 'comissao', searchable: false,},
                {name: 'vendas_diferenciadas.quantidade', data: 'quantidade', searchable: false,},
                {name: 'vendas_diferenciadas.dias', data: 'dias', searchable: false,},
                {name: 'vendas_diferenciadas.razao_social', data: 'razao_social', className: 'text-nowrap'},
                {name: 'vendas_diferenciadas.regra_venda', data: 'status_regra_venda', className: 'text-center text-nowrap' },
            ],
            order: [[ 1, 'asc' ]],
            rowCallback: function(row, data) {},
            drawCallback: function() {}
        });
    });
</script>
</html>