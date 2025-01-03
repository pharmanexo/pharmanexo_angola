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
                <form class="col-12">
                    <div class="row">

                        <div class="form-group col-md-4 ">
                            <label>Condição de Pagamento</label>
                            <input type="text" readonly class="form-control" value="<?php if ( isset($pedido['condicao_pagamento']) ) echo $pedido['condicao_pagamento'] ?>" />
                        </div>

                        <div class="form-group col-md-3 offset-1">
                            <label> Data </label>
                            <input type="text" class="form-control text-center" readonly value="<?php if ( isset($pedido['data_abertura']) ) echo date('d/m/Y', strtotime($pedido['data_abertura'])) ?>" />
                        </div>

                        <div class="col-12 col-md-3 offset-1">
                            <div class="form-group">
                                <label>Prazo de entrega</label>
                                <div class="input-group">
                                    <input type="text" id="valor_minimo" value="<?php if ( isset($pedido['prazo_entrega']) ) echo $pedido['prazo_entrega'] ?>" class="form-control text-center" data-inputmask="money" disabled />
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            Dias
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="">Total do Pedido</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            R$
                                        </div>
                                    </div>
                                    <input type="text" name="valor_minimo" value="<?php if ( isset($pedido['total']) ) echo $pedido['total'] ?>" class="form-control text-right" data-inputmask="money" disabled />
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="">Valor mínimo</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            R$
                                        </div>
                                    </div>
                                    <input type="text" name="valor_minimo" value="<?php if ( isset($pedido['valor_minimo']) ) echo $pedido['valor_minimo'] ?>" class="form-control text-right" data-inputmask="money" disabled />
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header Cursor" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                <h6 class="card-title">Dados Fornecedor e Comprador &nbsp;&nbsp;<i class="fas fa-angle-down rotate-icon"></i></h6>
            </div>

            <div class="collapse" id="collapseExample">
                <div class="card-body">
                    <form class="col-md-12">
                        <div class="row">

                            <div class="col-md-12">
                                <h5 class="text-muted"> Dados Fornecedor </h5>
                                <hr />
                            </div>

                            <div class="form-group col-md-5">
                                <label> CNPJ </label>
                                <input type="text" class="form-control" readonly value="<?php if ( isset($fornecedor['cnpj']) ) echo $fornecedor['cnpj'] ?>" />
                            </div>

                            <div class="form-group col-md-5">
                                <label> Razão Social </label>
                                <input type="text" class="form-control" readonly value="<?php if ( isset($fornecedor['razao_social']) ) echo $fornecedor['razao_social'] ?>" />
                            </div>

                            <div class="form-group col-md-2">
                                <label> UF </label>
                                <input type="text" class="form-control" readonly value="<?php if ( isset($fornecedor['estado']) ) echo $fornecedor['estado'] ?>" />
                            </div>

                            <div class="col-md-12">
                                <h5 class="text-muted"> Dados Comprador </h5>
                                <hr />
                            </div>

                            <div class="form-group col-md-5">
                                <label> CNPJ </label>
                                <input type="text" class="form-control" readonly value="<?php if ( isset($comprador['cnpj']) ) echo $comprador['cnpj'] ?>" />
                            </div>

                            <div class="form-group col-md-5">
                                <label> Nome </label>
                                <input type="text" class="form-control" readonly value="<?php if ( isset($comprador['razao_social']) ) echo $comprador['razao_social'] ?>" />
                            </div>

                            <div class="form-group col-md-2">
                                <label> UF </label>
                                <input type="text" class="form-control" readonly value="<?php if ( isset($comprador['estado']) ) echo $comprador['estado'] ?>" />
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div>

        <div class="card" id="datatable_produtos">
            <div class="card-header">
                <h6 class="card-title">Itens do Pedido</h6>
            </div>
            <div class="card-body">
                <div class="row mx-auto mt-3">
                    <div class="col-12 col-sm">
                        <div class="table-responsive">
                            <table id="table" class="table table-condensend table-hover w-100"
                                   data-url="<?php echo $datatables ?>">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Código Produto</th>
                                        <th>Produto</th>
                                        <th>Preço Unid.</th>
                                        <th>Quantidade</th>
                                        <th>Desconto</th>
                                        <th>preço Desconto</th>
                                        <th>Total</th>
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

<script type="text/javascript">

    $(function() {
        dt = $('#table').DataTable({
            processing: true,
            serverSide: false,
            lengthChange: false,
            responsive: true,
            pageLength: 10,
            searching: false,
            ajax: {
                url: $('#table').data('url'),
                type: 'POST',
                dataType: 'json'
            },
            columns: [
                {data: 'id_pedido', name: 'id_pedido', visible: false},
                {data: 'cd_produto_fornecedor', name: 'cd_produto_fornecedor'},
                {data: 'descricao', name: 'descricao'},
                {data: 'preco_unidade', name: 'preco_unidade'},
                {data: 'quantidade_solicitada', name: 'quantidade_solicitada'},
                {data: 'desconto', name: 'desconto'},
                {data: 'preco_desconto', name: 'preco_desconto'},
                {data: 'total', name: 'total'},
            ],
            rowCallback: function (row, data) {

            },
            drawCallback: function () {

            }
        });
                
        $('.Cursor').css('cursor', 'pointer');
    });
</script>

</html>
