<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>

<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>

    <div class="content__inner">
        <form id="formProdutos" action="<?php echo (isset($form_action)) ? $form_action : ''; ?>" method="POST">
            <?php if (isset($dados['clientes'])) { ?>
                <input type="hidden" name="clientes" value="<?php echo $dados['clientes']; ?>">
            <?php } ?>
            <?php if (isset($dados['estados'])) { ?>
                <input type="hidden" name="estados" value="<?php echo $dados['estados']; ?>">
            <?php } ?>

            <?php if ( isset($dados['all']) && $dados['all'] == 1 ) { ?>
                <div class="card">
                    <input type="hidden" name="codigo">
                    <input type="hidden" name="id_estado">
                    <input type="hidden" name="all" value="1">
                    <input type="hidden" name="produtos" value="<?php echo $dados['produtos'] ?>">
                    <div class="card-header">
                        <button type="button" data-toggle="tooltip" title="Remover este item" class="close" id="1" data-close="1" aria-label="Close">
                            <span aria-hidden="true" style="font-size: 15px"><i class="fas fa-trash"></i></span>
                        </button>

                        <h6 class="card-title">Todos os Produtos</h6>
                    </div>

                    <div class="card-body">
                        <div class="row">

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="regra_venda">Regras de Venda</label>
                                    <select class="select2" id="regra_venda" name="regra_venda" data-placeholder="Selecione" data-allow-clear="true" required <?php echo (count($options) == 1) ?  'readonly' : '' ?>>
                                        <option></option>
                                        <?php if(isset($options)) { ?>
                                            <?php foreach($options as $indice => $valor) { ?>
                                                 <option value="<?php echo $indice ?>" <?php echo (count($options) == 1) ?  'selected' : '' ?>> <?php echo $valor ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="desconto">Desconto Percentual</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" data-inputmask="money" name="desconto_percentual" value="<?php echo number_format(0, 2, ',', '.') ?>">
                                        <div class="input-group-append">
                                            <div class="input-group-text">%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <?php foreach ($dados['produtos'] as $p) : ?>
                    <div class="card">
                        <input type="hidden" name="produtos[<?php echo $p['id'] ?>][codigo]" value="<?php echo $p['codigo']; ?>">
                        <input type="hidden" name="produtos[<?php echo $p['id'] ?>][id_estado]" value="<?php if (isset($p['estados'])) echo $p['estados']; ?>">
                        <div class="card-header">
                            <button type="button" data-toggle="tooltip" title="Remover este item" class="close" id="close_<?php echo $p['id']; ?>" data-close="<?php echo $p['id']; ?>" aria-label="Close">
                                <span aria-hidden="true" style="font-size: 15px"><i class="fas fa-trash"></i></span>
                            </button>

                            <h6 class="card-title"><strong>Código: </strong><?php echo $p['codigo'] . ' - ' . $p['nome_comercial'] . ' - ' . $p['apresentacao']; ?></h6>

                            <span class="d-flex justify-content-start">

                                <p class="mr-3"><strong>Marca: </strong><?php echo $p['marca']; ?></p>
                                <p><strong>Estoque: </strong><?php echo ( isset($p['estoque_uf']) && !empty($p['estoque_uf']) ) ? $p['estoque_uf'] : 0; ?></p>

                            </span>
                        </div>

                        <div class="card-body">
                            <div class="row">

                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="regra_venda_<?php echo $p['id']; ?>">Regras de Venda</label>
                                        <select class="select2" id="regra_venda_<?php echo $p['id']; ?>" name="produtos[<?php echo $p['id'] ?>][regra_venda]" data-placeholder="Selecione" required <?php echo (count($options) == 1) ?  'readonly' : '' ?>>
                                            <option></option>
                                            <?php if(isset($options)) { ?>
                                                <?php foreach($options as $indice => $valor) { ?>
                                                     <option value="<?php echo $indice ?>" <?php echo (count($options) == 1) ?  'selected' : '' ?>> <?php echo $valor ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="desconto">Dias</label>
                                        <input type="number" class="form-control" min="0" name="produtos[<?php echo $p['id'] ?>][dias]">
                                    </div>
                                </div>

                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="desconto">Desconto Percentual</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" data-desconto="<?php echo $p['id'] ?>" name="produtos[<?php echo $p['id'] ?>][desconto_percentual]" data-inputmask="money" value="<?php echo number_format(0, 2, ',', '.') ?>">
                                            <div class="input-group-append">
                                                <div class="input-group-text">%</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="desconto">Preço Unidade</label>
                                        <input type="text" class="form-control"  data-price="<?php echo $p['id'] ?>" value="<?php echo number_format($p['preco_unidade'], 4, ',', '.') ?>" disabled data-inputmask="money4">
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="desconto">Preço c/ Desconto</label>
                                        <input type="text" class="form-control" data-priceDiscount="<?php echo $p['id'] ?>"  data-dd="<?php echo $p['id'] ?>" data-inputmask="money4">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php } ?>
        </form>
    </div>
</div>


<?php echo $scripts; ?>

<script>
    $(function () {

        $('[data-desconto]').each(function (i, v) {
            var me = $(v);

            me.on('blur', function () {
                var id = $(this).data('desconto');
                var desconto = $(this).val();
                var price = $(`[data-price="${id}"]`).val();
                var target = $(`[data-pricediscount="${id}"]`);

                desconto = desconto.replace(".","").replace(",",".");
                price = price.replace(".","").replace(",",".");

                var resultado = eval(`${price} - (${price} * (${desconto} / 100) )`);

                target.val(mascaraValor(resultado.toFixed(4)));
                target.maskMoney({
                    thousands: ".",
                    decimal: ",",
                    precision: 4
                }).maskMoney( 'mask' );

                target.addClass('input-success');
            });
        });

        $('[data-priceDiscount]').each(function (i, v) {
            var me = $(v);

            me.on('blur', function () {

                var id = $(this).data('dd');

                var preco_desconto = $(this).val();
                var preco_inicial = $(`[data-price="${id}"]`).val();
                var target = $(`[data-desconto="${id}"]`);


                preco_desconto = preco_desconto.replace(".","").replace(",",".");
                preco_inicial = preco_inicial.replace(".","").replace(",",".");


                var subtracao = eval(`${preco_inicial} - ${preco_desconto}`);
                var divisao = eval(`${subtracao} / ${preco_inicial}`);
                var resultado = eval(`${divisao} * 100`);

                target.val(jsMaskMoney(resultado.toFixed(2)));
                target.addClass('input-success')
            });
        });

        $('[data-close]').each(function (i, v) {
            $(v).click(function (e) {
                e.preventDefault();

                $(this).parent().parent().remove();
            });
        });

        $('#formProdutos').on('submit', function (e) {
            e.preventDefault();

            var $form = $(this);

            $.ajax({
                type: 'POST',
                url: $form.attr('action'),
                data: $form.serialize(),
                dataType: 'json',
                beforeSend: function(jqXHR, settings) {

                    if ( $("#regra_venda").val() == '' ) {

                        formWarning({ type: 'warning', message: "O campo regra de venda é obrigatório!"});
                        return jqXHR.abort();
                    }

                    if ( $("#desconto_percentual").val() == '' ) {

                        formWarning({ type: 'warning', message: "O campo desconto percentual é obrigatório!"});
                        return jqXHR.abort();
                    }
                },
                success: function (response) {

                    if (response.type === 'warning') {

                        formWarning({
                            type: 'warning',
                            message: response.message
                        });
                    } else {
                        formWarning({
                            type: 'success',
                            message: response.message
                        });

                        if (response.redirect != 0) {
                            setTimeout(function () {
                                window.location.href = response.redirect;
                            }, 1000);
                        } else {
                            setTimeout(function () {
                                window.location.href = response.route;
                            }, 1000);
                        }
                    }
                }
            });

            return false;
        });
    });

    function mascaraValor(valor) 
    {
        valor = valor.toString().replace(/\D/g,"");
        valor = valor.toString().replace(/(\d)(\d{8})$/,"$1.$2");
        valor = valor.toString().replace(/(\d)(\d{5})$/,"$1.$2");
        valor = valor.toString().replace(/(\d)(\d{2})$/,"$1,$2");
        return valor
    }
</script>
</body>

</html>
