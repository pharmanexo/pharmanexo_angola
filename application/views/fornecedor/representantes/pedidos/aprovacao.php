<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>

    <div class="content__inner">
        <form action="<?php if (isset($form_action)) echo $form_action; ?>" id="form" method="post">
            <input type="hidden" id="id" name="id" value="<?php echo $pedido['id']; ?>" class="form-control">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label for="">Comprador</label>
                                <input type="text" readonly value="<?php echo $pedido['razao_social']; ?>"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-lg-3">
                            <label for="">UF Comprador</label>
                            <input type="text" class="form-control text-center" name="uf_comprador"
                                   value="<?php if (isset($pedido['estado'])) echo $pedido['estado']; ?>"
                                   id="uf_comprador" readonly placeholder="Selecione o comprador">
                        </div>
                        <div class="col-12 col-lg-3">
                            <label for="">Data</label>
                            <input type="text" class="form-control text-center" readonly
                                   value="<?php echo date("d/m/Y H:i", time()) ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <div class="form-group">
                                <label for="">Condição de Pagamento</label>

                                <select name="id_forma_pagamento" id="id_forma_pagamento" class="select2">
                                    <?php foreach ($formas_pagamento as $item) { ?>
                                        <option value="<?php echo $item['id']; ?>" <?php if ($item['id'] == $pedido['id_forma_pagamento']) echo 'selected'; ?>><?php echo $item['descricao']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="form-group">
                                <label for="">Prazo de entrega</label>
                                <div class="input-group">
                                    <input type="text" id="prazo_entrega" name="prazo_entrega"
                                           value="<?php if (isset($pedido['prazo_entrega'])) echo $pedido['prazo_entrega']; ?>"
                                           class="form-control text-center" <?php echo (isset($dados)) ? 'disabled' : '' ?>
                                           required>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            dias
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="form-group">
                                <label for="">Valor mínimo</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            R$
                                        </div>
                                    </div>
                                    <input type="text" id="valor_minimo" name="valor_minimo"
                                           value="<?php if (isset($pedido['valor_minimo'])) echo $pedido['valor_minimo']; ?>"
                                           class="form-control text-right"
                                           data-inputmask="money">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">

                        <table id="produtos" class="table w-100 table-hover"
                               data-reject="">
                            <thead>
                            <tr>
                                <th>Código Produto</th>
                                <th>Produto</th>
                                <th>Quantidade Solicitada</th>
                                <th>Preço unidade</th>
                                <th>Desconto (%)</th>
                                <th>Preço Desconto</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                            </thead>
                            <?php foreach ($pedido['produtos'] as $k => $produto) { ?>
                                <tr id="<?php echo 'prod_' . $k; ?>"
                                    class="<?php if (isset($produto['class'])) echo $produto['class']; ?>">
                                    <th><?php echo $produto['cd_produto_fornecedor']; ?></th>
                                    <th><?php echo $produto['nome_comercial']; ?></th>
                                    <th>
                                        <?php echo $produto['quantidade_solicitada']; ?>
                                        <input type="hidden" class="qtd_sol"
                                               name="produtos[<?php echo $produto['cd_produto_fornecedor']; ?>][quantidade_solicitada]"
                                               value="<?php echo $produto['quantidade_solicitada']; ?>">
                                    </th>
                                    <th>

                                        <input class="form-control preco_unidade text-right" readonly
                                               name="produtos[<?php echo $produto['cd_produto_fornecedor']; ?>][preco_unidade]"
                                               data-inputmask="money4" type="text"
                                               value="<?php echo number_format($produto['preco_unidade'], 4, ',', '.'); ?>">
                                    </th>
                                    <th>
                                        <input class="form-control desconto text-right" <?php if ($produto['status'] != '0') echo 'readonly' ?>
                                               name="produtos[<?php echo $produto['cd_produto_fornecedor']; ?>][desconto]"
                                               type="text" data-type="desconto"
                                               value="<?php echo (isset($produto['desconto']) && !is_null($produto['desconto'])) ? number_format($produto['desconto'], 2, ',', '.') : '0,00'; ?>">
                                    </th>
                                    <th>
                                        <input class="form-control preco_desconto text-right" <?php if ($produto['status'] != '0') echo 'readonly' ?>
                                               name="produtos[<?php echo $produto['cd_produto_fornecedor']; ?>][preco_desconto]"
                                               data-inputmask="money4" type="text" data-type="preco_desconto"
                                               value="<?php echo number_format($produto['preco_desconto'], 4, ',', '.'); ?>">
                                    </th>
                                    <th><input class="form-control total text-right" type="text" readonly
                                               value="<?php echo number_format($produto['total'], '4', ',', '.'); ?>">
                                    </th>
                                    <th>
                                        <?php if ($produto['status'] == 0){ ?>
                                        <button data-href="<?php echo $url_reject . "/"; ?>" data-toggle="tooltip"
                                                data-codigo="<?php echo $produto['cd_produto_fornecedor']; ?>"
                                                data-pedido="<?php echo $pedido['id']; ?>"
                                                data-row="<?php echo '#prod_' . $k; ?>" data-btnCancel="true"
                                                title="Rejeitar item"
                                                class="btn btn-link text-danger"><i class="fas fa-ban"></i></button>
                                    </th>
                                        <?php } ?>

                                </tr>
                            <?php } ?>
                        </table>

                    </div>
                </div>
            </div>
        </form>
    </div>

</div>
</body>

<?php echo $scripts; ?>

<script>
    var url_reject = $('#data-table').data('reject');

    $(function () {

        $('[data-btnCancel]').click(function (e) {
            e.preventDefault();
            var url = $(this).data('href');
            var codigo = $(this).data('codigo');
            var id_pedido = $(this).data('pedido');
            var row = $(this).data('row');


            Swal.fire({
                title: 'Informe o motivo para rejeitar este item',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Fechar',
                showLoaderOnConfirm: true,
                allowOutsideClick: false,
            }).then((result) => {
                if (result.value) {
                    var text = result.value;

                    if (text.length > 5) {
                        $.post(url, {motivo: result.value, codigo: codigo, pedido: id_pedido}, function (xhr) {
                            if (xhr.type == 'success') {
                                $(row).addClass('table-danger');
                                $(row).attr('data-cancel', 1);
                                $('input', row).attr('readonly', true);
                            }
                        })
                    } else {
                        formWarning({type: 'warning', message: 'Não informou o motivo'})
                    }
                }
            })
        });

        $('#btnApprove').click(function (e) {
            e.preventDefault();

            var url = $(this).attr('href');

            Swal.fire({
                title: 'Deseja realmente aprovar os itens (não rejeitados)?',
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
                showLoaderOnConfirm: true,
                allowOutsideClick: false,
            }).then((result) => {
                if (result.value) {
                    $('#form').submit();
                }
            });


        }).tooltip({
            title: 'Apenas itens não rejeitados serão aprovados.'
        });

        $('[data-type]').change(function (e) {
            e.preventDefault();
            var type = $(this).data('type');
            var row = $(this).parent().parent();
            var pu = $('.preco_unidade', row).val();
            var desc = $('.desconto', row).val();
            var pd = $('.preco_desconto', row).val();
            var pd = $('.preco_desconto', row).val();
            var qtd = $('.qtd_sol', row).val();


            pu = pu.replace(".", "").replace(",", ".");
            desc = desc.replace(".", "").replace(",", ".");
            pd = pd.replace(".", "").replace(",", ".");


            if (type == 'preco_desconto') {

                desc = (pu - pd);
                var dcp = (desc * 100) / pu;

                $('.desconto', row).val(jsMaskMoney(dcp.toFixed(2)));
            }

            if (type == 'desconto') {

                var dc = (pu * (desc / 100));
                pd = pd - dc;

                $('.preco_desconto', row).val(mascaraValor(pd.toFixed(4)));
                $('.preco_desconto', row).maskMoney({
                    thousands: ".",
                    decimal: ",",
                    precision: 4
                }).maskMoney('mask');
            }

            $('.total', row).val(mascaraValor((pd * qtd).toFixed(4)));
            $('.total', row).maskMoney({
                thousands: ".",
                decimal: ",",
                precision: 4
            }).maskMoney('mask');

        });


        $('#btnReject').click(function (e) {
            e.preventDefault();

            var elementos = [];

            $.map(table.rows().data(), function (item) {
                elementos.push(item.cd_produto_fornecedor);
            });

            if (elementos.length > 0) {
                var url = $(this).attr('href');

                Swal.fire({
                    title: 'Informe o motivo para rejeitar o pedido',
                    input: 'text',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Fechar',
                    showLoaderOnConfirm: true,
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.value) {
                        var text = result.value;

                        if (text.length > 5) {
                            $.post(url, {motivo: result.value}, function (xhr) {
                                formWarning(xhr);
                                table.ajax.reload();
                            })
                        } else {
                            formWarning({type: 'warning', message: 'Não informou o motivo'})
                        }

                    }
                })
            } else {
                formWarning({type: 'warning', message: "Pedido sem produto"});
            }
        }).tooltip({
            title: 'Rejeitar todo o pedido'
        });
    });

    function mascaraValor(valor) {
        valor = valor.toString().replace(/\D/g, "");
        valor = valor.toString().replace(/(\d)(\d{8})$/, "$1.$2");
        valor = valor.toString().replace(/(\d)(\d{5})$/, "$1.$2");
        valor = valor.toString().replace(/(\d)(\d{2})$/, "$1,$2");
        return valor
    }
</script>

</html>