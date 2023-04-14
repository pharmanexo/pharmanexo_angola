    
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
                    <div class="col-2">
                        <p><strong>Preço</strong></p>
                        R$ <?php echo (isset($produto['preco_unitario'])) ? number_format($produto['preco_unitario'], 4, ',', '.') : number_format(0, 4, ',', '.'); ?>
                    </div>
                    <div class="col-3">
                        <p><strong>Marca</strong></p>
                        <small><?php echo $produto['marca']; ?></small>
                    </div>
                    <div class="col-2">
                        <p><strong>Validade</strong></p>
                        <?php echo date("d/m/Y", strtotime($produto['validade'])); ?>
                    </div>
                    <div class="col-2">
                        <p><strong>Lote</strong></p>
                        <?php echo $produto['lote']; ?>
                    </div>
                    <div class="col-3">
                        <p><strong>Estoque Atual</strong></p>
                        <?php echo (isset($produto['estoque']) && isset($produto['quantidade_unidade'])) ? (intval($produto['estoque']) * intval($produto['quantidade_unidade'])) : intval($produto['estoque']); ?>
                    </div>
                </div>
                <hr class="my-3">
                <form action="<?php if (isset($form_action)) echo $form_action; ?>" method="POST" id="formVenda">
                    <input type="hidden" name="lote" value="<?php echo $produto['lote']; ?>">
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="desconto">Preço c/ Desconto</label>
                                <input type="text" class="form-control" id="preco" data-preco="<?php if(isset($produto['preco_unitario'])) echo $produto['preco_unitario']  ?>" value="<?php if(isset($produto['preco_desconto'])) echo number_format($produto['preco_desconto'], 4, ',', '.'); ?>">
                            </div>
                        </div>
                        <div class="col-4">
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

                        <div class="col-4">
                            <label for="regra_venda">Regra de Venda</label>
                            <div class="form-group">
                                <select class="select2 w-100" style="width: 100%;" name="regra_venda" id="regra_venda" data-allow-clear="true" data-placeholder="Selecione">
                                    <option value="" <?php if(isset($produto['regra_venda']) && !in_array($produto['regra_venda'], [0, 1, 2, 3, 4, 5, 6])) echo 'selected'; ?> >Selecione</option>
                                    <option value="0" <?php if(isset($produto['regra_venda']) && $produto['regra_venda'] == 0) echo 'selected';  ?> >Todos os tipos</option>
                                    <option value="1" <?php if(isset($produto['regra_venda']) && $produto['regra_venda'] == 1) echo 'selected';  ?> >Manual</option>
                                    <option value="2" <?php if(isset($produto['regra_venda']) && $produto['regra_venda'] == 2) echo 'selected';  ?> >Automático</option>
                                    <option value="3" <?php if(isset($produto['regra_venda']) && $produto['regra_venda'] == 3) echo 'selected';  ?> >Manual e Automático</option>
                                    <option value="4" <?php if(isset($produto['regra_venda']) && $produto['regra_venda'] == 4) echo 'selected';  ?> >Distribuidor x Distribuidor</option>
                                    <option value="5" <?php if(isset($produto['regra_venda']) && $produto['regra_venda'] == 5) echo 'selected';  ?> >Distribuidor e Manual</option>
                                    <option value="6" <?php if(isset($produto['regra_venda']) && $produto['regra_venda'] == 6) echo 'selected';  ?> >Distribuidor e Automático</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        
                        <div class="col">
                            <div class="form-group">
                            <label for="selectType"><?php echo $produto['label_campo']; ?></label>
                                <input type="text" class="form-control" value="<?php echo $produto['campo']; ?>" disabled>
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

        $('#regra_venda').select2({dropdownParent: $('#modalVenda'), });

        $('#formVenda').submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: $(this).attr("action"),
                type: 'post',
                contentType: false,
                processData: false,
                data: formData,
                beforeSend: function(jqXHR, settings) {

                    if ( $('#regra_venda').val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo regra de venda é obrigatório"});
                        return jqXHR.abort();
                    }   

                    if ( $('#selectType').val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo Estados ou CNPJs é obrigatório"});
                        return jqXHR.abort();
                    }  
                },
                success: function(xhr) {
                    formWarning(xhr);
                    if (xhr.type == 'success') {
                        $('.modal').modal('hide');
                    }
                },
                error: function(xhr) {
                    console.log(xhr);
                    formWarning({ type: 'warning', message: "Erro ao salvar as informações!" });
                }
            })
        });

        $('#desconto').on('blur', function () {

            var preco = $('#preco').data('preco');
            var desconto = $(this).val();
            var target = $('#preco');

            desconto = desconto.replace(".","").replace(",",".");
            var resultado = eval(`${preco} - (${preco} * (${desconto} / 100) )`);

            target.val(mascaraValor(resultado.toFixed(4)));
            target.maskMoney({
                thousands: ".",
                decimal: ",",
                precision: 4
            }).maskMoney( 'mask' );
        });

        $('#preco').on('blur', function () {

            var preco_inicial = $('#preco').data('preco'); 
            var preco = $(this).val();
            var target = $('#desconto');

            preco = preco.replace(".","").replace(",",".");
           
            var subtracao = eval(`${preco_inicial} - ${preco}`);
            var divisao = eval(`${subtracao} / ${preco_inicial}`);
            var resultado = eval(`${divisao} * 100`);
        
            target.val(jsMaskMoney(resultado.toFixed(2)));
        });
    });

    function mascaraValor(valor) 
    {
        valor = valor.toString().replace(/\D/g,"");
        valor = valor.toString().replace(/(\d)(\d{8})$/,"$1.$2");
        valor = valor.toString().replace(/(\d)(\d{5})$/,"$1.$2");
        valor = valor.toString().replace(/(\d)(\d{2})$/,"$1,$2");
        return valor;                   
    }
</script>