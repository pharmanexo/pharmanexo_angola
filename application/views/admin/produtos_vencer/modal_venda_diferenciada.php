<div class="modal fade" id="modalVenda" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><?php if (isset($title)) echo $title; ?></h5>

            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <p><strong>Produto:</strong> <?php if (isset($produto)) echo $produto['codigo'] . " - " . $produto['nome_comercial'];?></p>
                    </div>
                    <div class="col-3">
                        <p><strong>Preço</strong></p>
                        R$ <?php echo number_format($produto['preco_unidade'], 4, ',', '.'); ?>
                    </div>
                    <div class="col-3">
                        <p><strong>Marca</strong></p>
                        <?php echo $produto['marca']; ?>
                    </div>
                    <div class="col-3">
                        <p><strong>Validade</strong></p>
                        <?php echo date("d/m/Y", strtotime($produto['validade'])); ?>
                    </div>
                    <div class="col-3">
                        <p><strong>Lote</strong></p>
                        <?php echo $produto['lote']; ?>
                    </div>
                </div>
                <hr class="my-3">
                <form action="<?php if (isset($form_action)) echo $form_action; ?>" method="POST" id="formVenda">
                    <input type="hidden" name="id_produto" value="<?php echo $produto['id']; ?>">
                    <input type="hidden" name="id_estado" value="<?php echo $produto['id_estado']; ?>">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="desconto">Preço c/ Desconto</label>
                                <?php $default = ( isset($produto['preco_desconto']) ) ? number_format($produto['preco_desconto'], 4, ',', '.') : number_format($produto['preco_unidade'], 4, ',', '.');  ?>
                                <input type="text" class="form-control" id="preco" data-preco="<?php echo $produto['preco_unidade'] ?>" value="<?php echo $default ?>">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="desconto">Desconto Percentual</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="desconto_percentual" value="<?php echo (isset($produto['desconto_percentual'])) ? number_format($produto['desconto_percentual'],2, ',', '.') : '0,00' ?>" id="desconto" data-inputmask="money">
                                    <div class="input-group-append">
                                        <div class="input-group-text">%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if ($this->session->tipo_usuario == '1'){ ?>
                        <div class="col">
                            <div class="form-group">
                                <label for="comissao">Comissão Fixa
                                    <span class="pull-right" data-toggle="tooltip" title="" data-original-title="Esse campo é apenas informativo, referente à comissão acordada em contrato.">
                                                    <i class="fas fa-info-circle ml-2"></i>
                                                </span>
                                </label>

                                <div class="input-group">

                                    <input type="text" class="form-control text-center" id="comissao" value="3,00" disabled="" data-inputmask="money">
                                    <div class="input-group-append">
                                        <div class="input-group-text">%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="col">
                            <div class="form-group">
                                <label for="comissao">Comissão Adicional
                                    <span class="pull-right" data-toggle="tooltip" title="" data-original-title="A comissão adicional será somada à comissão fixa. Ex.: Fixa 3% + adicional 2% = comissão final 5%">
                                                    <i class="fas fa-info-circle ml-2"></i>
                                                </span>
                                </label>

                                <div class="input-group">
                                    <select class="form-control" name="comissao" id="comissao">
                                        <option value="">SELECIONE ...</option>
                                        <option <?php if (isset($produto['comissao']) && $produto['comissao'] == '0') echo "selected"?> value="0">Nenhuma</option>
                                        <option <?php if (isset($produto['comissao']) && $produto['comissao'] == '0.5') echo "selected"?> value="0.5">0,5</option>
                                        <option <?php if (isset($produto['comissao']) && $produto['comissao'] == '1') echo "selected"?> value="1">1</option>
                                        <option <?php if (isset($produto['comissao']) && $produto['comissao'] == '1.5') echo "selected"?> value="1.5">1,5</option>
                                        <option <?php if (isset($produto['comissao']) && $produto['comissao'] == '2') echo "selected"?> value="2">2</option>
                                        <option <?php if (isset($produto['comissao']) && $produto['comissao'] == '2.5') echo "selected"?> value="2.5">2,5</option>
                                        <option <?php if (isset($produto['comissao']) && $produto['comissao'] == '3') echo "selected"?> value="3">3</option>
                                        <option <?php if (isset($produto['comissao']) && $produto['comissao'] == '3.5') echo "selected"?> value="3.5">3,5</option>
                                        <option <?php if (isset($produto['comissao']) && $produto['comissao'] == '4') echo "selected"?> value="4">4</option>
                                        <option <?php if (isset($produto['comissao']) && $produto['comissao'] == '4.5') echo "selected"?> value="4.5">4,5</option>
                                        <option <?php if (isset($produto['comissao']) && $produto['comissao'] == '5') echo "selected"?> value="5">5</option>
                                    </select>
                                    <div class="input-group-append">
                                        <div class="input-group-text">%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="periodo">Dias</label>
                                <input type="number" class="form-control" name="dias" value="<?php if (isset($produto['dias'])) echo $produto['dias']?>" id="dias">
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <label for="quantidade">Quantidade</label>
                                <input type="number" class="form-control" name="quantidade" value="<?php if (isset($produto['quantidade'])) echo $produto['quantidade']?>" id="quantidade">
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <label for="quantidade">Estoque Atual</label>
                                <input type="number" class="form-control text-center" readonly value="<?php if (isset($produto['estoque']) && isset($produto['quantidade_unidade'])) echo (intval($produto['estoque']) * intval($produto['quantidade_unidade']))?>" id="quantidade">
                            </div>
                        </div>
                    </div>
                </form>
            </div>


            <div class="modal-footer">

                <button type="submit" id="btnSalvar" class="btn btn-primary" form="formVenda">Salvar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>

    $(function () {
        reloadPlugin();
        $('#formVenda').validate({
            ignore: [],
            rules: {
                desconto: {
                    required: true
                },
            },
            submitHandler: function (form) {
                $(form).ajaxSubmit({
                    dataType: 'json',
                    success: function (xhr) {
                        formWarning(xhr);
                        if (xhr.type == 'success') {
                            $('.modal').modal('hide');
                        }
                    }
                })
            },
            showErrors: function ($map) {
                if (this.numberOfInvalids()) {
                    $.each($map, function (k, v) {
                        formWarning({
                            type: 'warning',
                            message: v
                        });
                    });
                }
            }
        });

        $('#desconto').on('blur', function () {

            var preco = $('#preco').data('preco');

            var desconto = $(this).val();

            var target = $('#preco');

            if ( desconto == '' ) {

                target.val(mascaraValor(preco));
                target.maskMoney({
                    thousands: ".",
                    decimal: ",",
                    precision: 4
                }).maskMoney( 'mask' );

                return;
            }

            desconto = desconto.replace(",",".");
            desconto = parseFloat(desconto);

            var result = preco - (preco * (desconto / 100));

            target.val(mascaraValor(result.toFixed(4)));
            target.maskMoney({
                thousands: ".",
                decimal: ",",
                precision: 4
            }).maskMoney( 'mask' );
        });

        $('#preco').on('blur', function () {

            var preco_oferta = $('#preco').data('preco'); 

            var preco = $(this).val();

            var target = $('#desconto');

            if ( preco == '') {
                return;
            }

            if ( preco != preco_oferta ) {

                preco = preco.replace(",",".");
                preco = parseFloat(preco);

                var subtracao = preco_oferta - preco;
                var divisao = subtracao / preco_oferta;
                var result = divisao * 100;

                target.val( Math.round(result) );
            } else {
                target.val(0);
            }
        });

    });
    function mascaraValor(valor) {
        valor = valor.toString().replace(/\D/g,"");
        valor = valor.toString().replace(/(\d)(\d{8})$/,"$1.$2");
        valor = valor.toString().replace(/(\d)(\d{5})$/,"$1.$2");
        valor = valor.toString().replace(/(\d)(\d{2})$/,"$1,$2");
        return valor                    
    }
</script>