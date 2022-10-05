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

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="semestoque-tab" data-toggle="tab" href="#tabSemestoque" role="tab" aria-controls="semestoque" aria-selected="true">Sem Estoque</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="restricao-tab" data-toggle="tab" href="#tabRestricao" role="tab" aria-controls="restricao" aria-selected="false">Restrição</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="ol-tab" data-toggle="tab" href="#tabOl" role="tab" aria-controls="ol" aria-selected="false">OL</a>
                    </li>
                   
                </ul>

                <div class="tab-content" id="myTabContent">
                    <!-- sem estoque -->
                    <div class="tab-pane fade show active" id="tabSemestoque" role="tabpanel" aria-labelledby="semestoque-tab">
                        <div class="row mx-auto mt-3">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="data-table-semestoque" class="table w-100 table-hover" data-url="<?php echo $to_datatable_se; ?>">
                                        <thead>
                                            <tr>
                                                <th>Produto</th>
                                                <th>Marca</th>
                                                <th>Preço</th>
                                                <th>Estoque</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Restricao -->
                    <div class="tab-pane fade" id="tabRestricao" role="tabpanel" aria-labelledby="restricao-tab">
                        <div class="row mx-auto mt-3">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="data-table-restricao" class="table w-100 table-hover" data-url="<?php echo $to_datatable_res; ?>">
                                        <thead>
                                            <tr>
                                                <th>Produto</th>
                                                <th>Estoque</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ol -->
                    <div class="tab-pane fade" id="tabOl" role="tabpanel" aria-labelledby="ol-tab">
                        <div class="row mx-auto mt-3">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="data-table-ol" class="table w-100 table-hover" data-url="<?php echo $to_datatable_ol; ?>">
                                        <thead>
                                            <th>Produto</th>
                                            <th>Marca</th>
                                            <th>Preço</th>
                                            <th>Estoque</th>
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

<?php echo $scripts; ?>

<script>

    $(function () {
        var dt1 = $('#data-table-semestoque').DataTable({
            processing: true,
            serverSide: false,
            pageLength: 10,
            "order": [[ 0, "asc" ]],
            ajax: {
                url: $('#data-table-semestoque').data('url'),
                type: 'post',
                dataType: 'json',
            },
            
            columns: [
                { name: 'CP.ds_produto_comprador', data: 'ds_produto_comprador', className: 'text-nowrap' },
                { name: 'RPC.id_marca', data: 'id_marca', orderable: false, searchable: false },
                { name: 'RPC.preco_marca', data: 'preco_marca', orderable: false, searchable: false },
                { name: 'RPC.estoque', data: 'estoque', orderable: false, searchable: false },
            ],
            rowCallback: function (row, data) {},
            drawCallback: function () {}
        });

        var dt2 = $('#data-table-restricao').DataTable({
            processing: true,
            serverSide: false,
            pageLength: 10,
            "order": [[ 0, "asc" ]],
            ajax: {
                url: $('#data-table-restricao').data('url'),
                type: 'post',
                dataType: 'json',
            },
            columns: [
                { name: 'CP.ds_produto_comprador', data: 'ds_produto_comprador', className: 'text-nowrap' },
                { name: 'RPC.estoque', data: 'estoque' },
            ],
            rowCallback: function (row, data) {},
            drawCallback: function () {}
        });

        var dt3 = $('#data-table-ol').DataTable({
            processing: true,
            serverSide: false,
            pageLength: 10,
            "order": [[ 0, "asc" ]],
            ajax: {
                url: $('#data-table-ol').data('url'),
                type: 'post',
                dataType: 'json',
            },
            columns: [
                { name: 'CP.ds_produto_comprador', data: 'ds_produto_comprador', className: 'text-nowrap' },
                { name: 'RPC.id_marca', data: 'id_marca', orderable: false, searchable: false },
                { name: 'RPC.preco_marca', data: 'preco_marca', orderable: false, searchable: false },
                { name: 'RPC.estoque', data: 'estoque', orderable: false, searchable: false },
            ],
            rowCallback: function (row, data) {},
            drawCallback: function () {}
        });
    });
</script>
</body>

