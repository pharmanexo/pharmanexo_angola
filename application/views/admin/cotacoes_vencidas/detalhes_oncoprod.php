<!DOCTYPE html>
<html lang="pt-BR">
<?php echo $header; ?>
<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>
<div class="content">
    <?php echo $heading; ?>
    <form action="<?php if (isset($form_action)) echo $form_action; ?>" id="respostaCotacao" method="post" data-ocultar="<?php if(isset($url_ocultar)) echo $url_ocultar ?>" data-historico="<?php if(isset($url_historico)) echo $url_historico ?>" data-dados="<?php echo "{$cot}|{$id_fornecedor}" ?>">
        <div class="content__inner">

            <?php if($cotacao['data_fim'] < date('Y-m-d H:i:s', strtotime('-1 hour'))) { ?>
            <div class="alert alert-danger" role="alert"> <i class="fas fa-exclamation-triangle"></i> Esta cotação se encontra encerrada.</div>
            <?php } ?>

            <div class="card">
                <div class="card-header text-right">
                    <button type="submit" form="respostaCotacao" class="btn btn-primary">ENVIAR RESPOSTA DA COTAÇÃO</button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <p><strong>CNPJ Comprador </strong></p>
                            <?php if (isset($cotacao['cnpj'])) echo $cotacao['cnpj']; ?>
                        </div>
                        <div class="col-12 col-lg-4">
                            <p><strong>Razão Social</strong></p>
                            <?php if (isset($cotacao['cliente']['razao_social'])) echo $cotacao['cliente']['razao_social']; ?>
                        </div>
                        <div class="col-12 col-lg-4">
                            <p><strong>Cidade/Estado</strong></p>
                            <?php if (isset($cotacao['cliente']['cidade']) && !empty($cotacao['cliente']['cidade'])) echo $cotacao['cliente']['cidade'] . ' - '; ?><?php if (isset($cotacao['cliente']['estado'])) echo $cotacao['cliente']['estado'] . ' - '; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <p><strong>Condição de Pagamento</strong></p>
                            <?php if (isset($cotacao['condicao_pagamento'])) echo $cotacao['condicao_pagamento'] ?>
                        </div>
                        <div class="col-12 col-lg-6">
                            <p><strong>Validade do Preço</strong></p>
                            <?php if (isset($cotacao['dt_validade_preco'])) echo $cotacao['dt_validade_preco'] ?>
                        </div>
                        <div class="col-12 col-lg-4">
                            <p><strong>Data Início</strong></p>
                            <?php if (isset($cotacao['data_inicio'])) echo date('d/m/Y H:i:s', strtotime($cotacao['data_inicio'])) ?>
                        </div>
                        <div class="col-12 col-lg-4">
                            <p><strong>Data Fim</strong></p>
                            <?php if (isset($cotacao['data_fim'])) echo date("d/m/Y H:i:s", strtotime($cotacao['data_fim'])) ?>
                        </div>
                        <div class="col-12 col-lg-4">
                            <p><strong>Total de Itens</strong></p>
                            <?php if (isset($cotacao['itens'])) echo $cotacao['itens'] ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <label>Forma de Pagamento</label>
                            <select class="form-control w-100" id="formasPagamento" name="forma_pagto" data-url="<?php echo $select_formas_pagamento; ?>" data-value="<?php if(!empty($forma_pagamento)) echo $forma_pagamento ?>" style="width: 100%"></select>
                        </div>
                        <div class="col-6">
                            <label>Prazo de Entrega</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="prazo_entrega" value="<?php if(!empty($prazo_entrega)) echo $prazo_entrega ?>">
                                <div class="input-group-append">
                                    <div class="input-group-text bg-light">Dias</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="obs">Observações da cotação</label>
                                <input type="text" name="obs" id="obs" class="form-control" maxlength="500" value="<?php if(isset($observacao)) echo $observacao ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label>Data da cotação</label>
                                <input type="text" class="form-control" name="data_cotacao" data-inputmask="datetimeSeconds">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row mb-3">
                <div class="col-12">
                    <div class="enviado" style="width: 15px; height: 15px; border-radius: 20%; border: 1px solid; display: inline-block"></div>
                    &nbsp;Respondida&nbsp;
                    <div class="nenviado" style="width: 15px; height: 15px; border-radius: 20%; border: 1px solid; display: inline-block"></div>
                    Sem responder&nbsp;
                    <div class="nencontrado" style="width: 15px; height: 15px; border-radius: 20%; border: 1px solid; display: inline-block"></div>
                    Produto não cadastrado
                </div>
            </div>

            <input type="hidden" name="id_cotacao" id="id_cotacao" value="<?php if (isset($cotacao['id_cotacao'])) echo $cotacao['id_cotacao']; ?>">
            <input type="hidden" name="cd_condicao_pgto" id="cd_condicao_pgto" value="<?php if (isset($cotacao['cd_condicao_pgto'])) echo $cotacao['cd_condicao_pgto']; ?>">
            <input type="hidden" name="cnpj_comprador" id="cnpj_comprador" value="<?php if (isset($cotacao['cnpj'])) echo $cotacao['cnpj']; ?>">
            <div class="accordion" id="accordionExample">
                <?php if (isset($cotacao['produtos'])) { ?>
                    <?php foreach ($cotacao['produtos'] as $k => $produto) { ?>
                        <div class="card">
                            <div class="card-header <?php echo ( is_null($produto['encontrados']) ? 'nencontrado' :
                                ( in_array(1, array_column($produto['encontrados'], 'enviado')) ? 'enviado' :
                                    ( $produto['cotado']['encontrados'] != 0 && !in_array(1, array_column($produto['encontrados'], 'enviado')) ? 'nenviado' : '' ))) ?>" id="heading<?php echo $k; ?>">
                                <p class="mb-0">
                                <div class="row mt-3 <?php echo ( !isset($produto['encontrados']) ? 'nencontrado' :
                                    ( in_array(1, array_column($produto['encontrados'], 'enviado')) ? 'enviado' :
                                        ( $produto['cotado']['encontrados'] != 0 && !in_array(1, array_column($produto['encontrados'], 'enviado')) ? 'nenviado' : '' ))) ?>">
                                    <div class="col-12 col-lg-4">
                                        <strong><?php echo $descricao_produto ?></strong><br>
                                        <?php echo $produto['cotado']['id_produto_sintese'] . ' - ' . $produto['cotado']['ds_produto_comprador'] ?>
                                    </div>
                                    <div class="col-12 col-lg-2">
                                        <strong>Embalagem</strong><br>
                                        <?php echo $produto['cotado']['ds_unidade_compra'] ?>
                                    </div>
                                    <div class="col-12 col-lg-2 text-center">
                                        <strong>Quantidade Solicitada</strong><br>
                                        <?php echo $produto['cotado']['qt_produto_total'] ?>
                                    </div>
                                    <?php if( isset($produto['encontrados']) && $produto['cotado']['encontrados'] >= 0 ) { ?>
                                   
                                    <div class="col-12 col-lg-2 text-right mt-2">
                                        <a href="<?php echo $url_findProduct ?><?php echo $produto['cotado']['id_produto_sintese'] ?>" target="_blank" data-toggle="tooltip" title="Upgrade De -> Para">
                                            <i class="fas fa-arrow-circle-up"></i>
                                        </a>
                                    </div>
                                    
                                    <div class="col-12 col-lg-2">
                                            <button class="btn btn-block btn-secondary" type="button" data-toggle="collapse" data-target="#collapse<?php echo $k; ?>" aria-expanded="true" aria-controls="collapse<?php echo $k; ?>">
                                                Produtos <i class="fas fa-chevron-down ml-3"></i>
                                            </button>
                                    </div>
                                    <?php } else { ?>
                                        <div class="col-12 col-lg-2"></div>
                                        <div class="col-12 col-lg-2">
                                            <a href="<?php echo $url_findProduct ?><?php echo $produto['cotado']['id_produto_sintese'] ?>" target="_blank" class="btn btn-block btn-danger">Fazer De -> Para</a>
                                        </div>
                                   <?php } ?>
                                </div>
                                </p>
                            </div>
                            <div id="collapse<?php echo $k; ?>" class="collapse" aria-labelledby="heading<?php echo $k; ?>" data-parent="#accordionExample">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-success" style="width: 15px; height: 15px; border: 1px solid; border-radius: 20%; display: inline-block"></div>
                                            Disponível
                                            <div class="table-warning" style="width: 15px; height: 15px; border: 1px solid; border-radius: 20%; display: inline-block"></div>
                                            Insuficiente
                                            <div class="table-danger" style="width: 15px; height: 15px; border: 1px solid; border-radius: 20%; display: inline-block"></div>
                                            Sem estoque
                                        </div>
                                    </div>
                                    <table class="table table-striped">
                                        <tr>
                                            <th></th>
                                            <th><?php echo $descricao_codigo ?></th>
                                            <th>Filial/Marca</th>
                                            <th>Preço Unit.</th>
                                            <th>Qtd Emb.</th>
                                            <th class="text-nowrap">Desconto (%)</th>
                                            <th class="text-nowrap">Preço Caixa</th>
                                            <th class="text-nowrap">Preço Unit.</th>
                                            <th></th>
                                        </tr>
                                        <?php if (isset($produto['encontrados']) && !empty($produto['encontrados'])) { ?>
                                            <?php foreach ($produto['encontrados'] as $jj => $prod) { ?>


                                                <!-- VALOR QUANTIDADE UNIDADE -->
                                                <?php 
                                                    $prod['quantidade_unidade'] = 
                                                        ($prod['quantidade_unidade'] == 0 || $prod['quantidade_unidade'] == '' || $prod['quantidade_unidade'] == null) ?
                                                            1 : $prod['quantidade_unidade'];  
                                                ?>

                                                <!-- VALOR PRECO UNITARIO -->
                                                <?php
                                                    $preco_unitario = 
                                                        ($prod['quantidade_unidade'] != '' || $prod['quantidade_unidade'] != null) ?
                                                            ($prod['preco_unidade'] / $prod['quantidade_unidade']) : $prod['preco_unidade'];
                                                ?>

                                                <!-- VALOR PRECO CAIXA -->
                                                <?php $preco_caixa = $prod['preco_unidade']; ?>


                                                <?php $produto['cotado']['cd_produto_comprador'] = str_replace('.', '', $produto['cotado']['cd_produto_comprador']) ?>

                                                <input type="hidden" name="produtos[<?php echo $produto['cotado']['cd_produto_comprador'] ?>][<?php echo $jj?>][codigo]" value="<?php echo $prod['codigo']; ?>">
                                                <input type="hidden" name="produtos[<?php echo $produto['cotado']['cd_produto_comprador'] ?>][<?php echo $jj?>][filial]" value="<?php echo $prod['id_fornecedor']; ?>">
                                                <input type="hidden" name="produtos[<?php echo $produto['cotado']['cd_produto_comprador'] ?>][<?php echo $jj?>][id_marca]" value="<?php echo $prod['id_marca']; ?>">
                                                <input type="hidden" name="produtos[<?php echo $produto['cotado']['cd_produto_comprador'] ?>][<?php echo $jj?>][id_produto]" value="<?php echo $prod['id_produto']; ?>">

                                                <input type="hidden" name="produtos[<?php echo $produto['cotado']['cd_produto_comprador'] ?>][<?php echo $jj?>][obs]" id="obs_<?php echo "{$k}_{$jj}_{$prod['codigo']}" ?>" value="<?php echo ( isset($prod['obs']) ) ? $prod['obs'] : '' ?>" >

                                                <tr class="<?php if (isset($prod['class'])) echo $prod['class']; ?> ">
                                                    <td></td>
                                                    <td colspan="6">
                                                        <?php if(isset($prod['produto_descricao'])) echo $prod['produto_descricao'] ?>
                                                    </td>
                                                    <td colspan="4"><small><b>Estoque:</b> <?php echo $prod['estoque']; ?></small></td>
                                                </tr>
                                             
                                                <tr class="<?php if (isset($prod['class'])) echo $prod['class']; ?> ">
                                                    <td>
                                                        <div class="checkbox">
                                                            <input type="checkbox" id="prod[<?php echo $k; ?>][<?php echo $jj; ?>][<?php echo $prod['codigo']; ?>]" name="produtos[<?php echo $produto['cotado']['cd_produto_comprador'] ?>][<?php echo $jj?>][marcado]" value="" autocomplete="off"
                                                                <?php echo ( $prod['enviado'] == 1 || $prod['rascunho'] == 1 ) ? 'checked' : ''; ?> >
                                                            <label class="checkbox__label" for="prod[<?php echo $k; ?>][<?php echo $jj; ?>][<?php echo $prod['codigo']; ?>]"></label>
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <small><?php echo $prod['codigo']; ?></small>
                                                    </td>

                                                    <td class="text-nowrap"><small><?php echo "{$prod['nome_fantasia']} - {$prod['marca']}"; ?></small></td>

                                                    <td><small><?php echo number_format($preco_unitario, 4, ',', '.') ?></small></td>

                                                    <td><small><?php echo $prod['quantidade_unidade']; ?></small></td>

                                                    <!-- CAMPO DESCONTO -->
                                                    <td>
                                                        <input type="text" value="0,00"
                                                        id="desconto"
                                                        data-desconto="<?php echo "{$k}_{$jj}_{$prod['id']}" ?>"
                                                        data-precounidade="<?php echo number_format($preco_unitario, 4, ',', '.') ?>"
                                                        data-qnt="<?php echo $prod['quantidade_unidade'] ?>"
                                                        data-inputmask="money"
                                                        class="text-center form-control">
                                                    </td>

                                                    <!-- CAMPO PREÇO CAIXA -->
                                                    <td>
                                                        <input type="text"
                                                        id="preco_caixa"
                                                        value="<?php echo number_format($preco_caixa, 4, ',', '.')?>"
                                                        data-precocaixa="<?php echo number_format($preco_caixa, 4, ',', '.') ?>"
                                                        data-inputmask="money4"
                                                        class=" text-center form-control">
                                                    </td>

                                                    <!-- CAMPO PRECO UNITARIO -->
                                                    <td>
                                                        <input type="text"
                                                        name="produtos[<?php echo $produto['cotado']['cd_produto_comprador'] ?>][<?php echo $jj?>][preco_oferta]"
                                                        id="preco_unitario"
                                                        data-preco="<?php echo "{$k}_{$jj}_{$prod['id']}" ?>"
                                                        value="<?php echo number_format($preco_unitario, 4, ',', '.')?>"
                                                        data-inputmask="money4"
                                                        class=" text-center form-control">
                                                    </td>

                                                    <td class="text-nowrap">
                                                        <a data-toggle="modal" class="mr-4" data-target="#obsModal" title="Inserir Observação" data-produto="<?php echo "obs_{$k}_{$jj}_{$prod['codigo']}"  ?>"><i class="far fa-sticky-note"></i></a>
                                                        <a data-toggle="modal" data-target="#historicoModal" title="Histórico de ofertas" data-dados="<?php echo "{$id_fornecedor}|{$prod['id_fornecedor']}|{$prod['id_produto']}" ?>" ><i class="fas fa-eye"></i></a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <td colspan="10" class="text-center">Produto não encontrado em sua base de dados.</td>
                                        <?php } ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
    </form>

    <div class="row border-top my-3">
        <div class="col-12 text-right py-3">
            <button type="submit" form="respostaCotacao" class="btn btn-primary">ENVIAR RESPOSTA DA COTAÇÃO</button>
        </div>
    </div>
</div>


<div class="modal fade" id="obsModal" tabindex="-1" role="dialog" aria-labelledby="obsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="obsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <input type="hidden" id="target" class="modal-obs">
                        <textarea class="form-control" name="obsProduto" id="obsProduto"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" id="btn_obs" class="btn btn-primary">Salvar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="historicoModal" tabindex="-1" role="dialog" aria-labelledby="historicoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historicoModalLabel"></h5>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <thead>
                        <th>Cotação</th>
                        <th>Oferta (R$)</th>
                        <th>Registrado em</th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<?php echo $scripts; ?>

<script>

    var url_historico = $('#respostaCotacao').data('historico');
    var url_ocultar = $('#respostaCotacao').data('ocultar');

    $(function () {

        $('#btn_obs').on('click', function() {
            // Pega o Valor do campo textarea
            var obs = $('#obsProduto').val();

            // Pega o valor do campo hidden no modal
            var target = $('#target').val();

            // Atualiza o input hidden de observacao do formulario
            $(`#${target}`).val(obs);

            $('#obsModal').modal('hide');
        });

        $('#obsModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            // Define o value do campo textarea pelo valor do campo oculto do form
            $('#obsProduto').val( $('#' + button.data('produto') ).val() );

            modal.find('.modal-title').text('Nova Observação');
            // Passa o ID do campo observacao do form para o value do campo oculto do modal
            modal.find('.modal-obs').val(button.data('produto'));
        });

        $('#historicoModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            modal.find('.modal-title').text('Histórico de ofertas');

            var dados = button.data('dados').split("|");

            var data = {
                id_fornecedor: dados[0],
                filial: dados[1],
                id_produto: dados[2]
            };

            $.post(url_historico, data, function (xhr) {

                if (xhr.data.length > 0) {
                    modal.find('.modal-title').text("Histórico de ofertas - Preço Médio: R$" + xhr.media);
                    $.each(xhr.data, function (index, value) {
                        modal.find('tbody').append(`<tr><td>${value.cd_cotacao}</td><td>${value.preco_marca}</td><td>${value.data}</td></tr>`);
                    })
                } else {
                    modal.find('.modal-title').text("Histórico de ofertas");
                    modal.find('tbody').append(`<tr><td colspan="3">Não encontramos ofertas anteriores para este produto.</td></tr>`);
                }
            }, 'JSON')
                .fail(function (xhr) {
                  console.log(xhr);
            });
        }).on('hidden.bs.modal', function(event) {
            var modal = $(this);

            modal.find('tbody').html('');
        });

        $('#respostaCotacao').submit(function(){
            var check = [];

            $("input:checked").each(function(){
                $(this).val("1");
                check.push($(this).id);
            });

            var action =  $('#respostaCotacao').attr('action');
            
            if ( check.length < 1 && action.indexOf('/1') == -1 ) {
                formWarning({ type: 'warning', message: "Nenhum produto selecionado!" });
                event.preventDefault();
            }
        });

        $('#btn_ocultar').on('click', function(e) {
            e.preventDefault();

            var data = $('#respostaCotacao').data('dados').split("|");

            var dados = {
                cd_cotacao: data[0],
                id_fornecedor: data[1]
            };

            $.post(url_ocultar, dados, function (xhr) {
                if (xhr.type == 'success') {
                    formWarning({ type: xhr.type, message: xhr.message });
                    setTimeout(function() { window.location.href = xhr.route; }, 1500);
                } else {
                    console.log("Erro ao salvar as informações!");
                    setTimeout(function() { window.location.reload() }, 1500);
                }
            }, 'JSON')
                .fail(function(xhr) {
                    formWarning({ type: 'warning', message: "Erro ao salvar as informações!" });
            });
        })

        $('[data-desconto]').each(function (i, v) {
            var me = $(v);

            me.on('blur', function () {

                var tr = $(this).parent().parent();

                var campo_desconto = tr.find('input#desconto');
                var campo_preco_unitario = tr.find('input#preco_unitario');
                var campo_precocaixa = tr.find('input#preco_caixa');

                var quantidade_unidade = campo_desconto.data('qnt');
                var preco_unidade = $(this).data('precounidade');

                preco_unidade = preco_unidade.replace(".","");
                preco_unidade = preco_unidade.replace(",",".");
                preco_unidade = parseFloat(preco_unidade);

                var preco = campo_preco_unitario.val();
                var desconto = campo_desconto.val();
                var preco_caixa = campo_precocaixa.data('precocaixa');

                if ( desconto == '' || desconto == 0 ) {

                    campo_desconto.val(0);
                    campo_desconto.maskMoney({
                        thousands: ".",
                        decimal: ",",
                        precision: 2
                    }).maskMoney( 'mask' );

                    campo_preco_unitario.val(mascaraValor(preco_unidade.toFixed(4)));
                    campo_preco_unitario.maskMoney({
                        thousands: ".",
                        decimal: ",",
                        precision: 4
                    }).maskMoney( 'mask' );

                    campo_precocaixa.val(mascaraValor(preco_caixa.toFixed(4)));
                    campo_precocaixa.maskMoney({
                        thousands: ".",
                        decimal: ",",
                        precision: 4
                    }).maskMoney( 'mask' );
                } else {

                    desconto = desconto.replace(".","");
                    desconto = desconto.replace(",",".");
                    desconto = parseFloat(desconto);

                    var result = preco_unidade - (preco_unidade * (desconto / 100));

                    campo_preco_unitario.val(mascaraValor(result.toFixed(4)));
                    campo_preco_unitario.maskMoney({
                        thousands: ".",
                        decimal: ",",
                        precision: 4
                    }).maskMoney( 'mask' );

                    var resultado = result * quantidade_unidade;

                    campo_precocaixa.val(mascaraValor(resultado.toFixed(4)));
                    campo_precocaixa.maskMoney({
                        thousands: ".",
                        decimal: ",",
                        precision: 4
                    }).maskMoney( 'mask' );
                }
            });
        });

        $('[data-precocaixa]').each(function (i, v) {
            var me = $(v);

            me.on('blur', function () {

                var tr = $(this).parent().parent();

                var campo_desconto = tr.find('input#desconto');
                var campo_preco_unitario = tr.find('input#preco_unitario');
                var campo_preco_caixa = tr.find('input#preco_caixa');

                var quantidade_unidade = campo_desconto.data('qnt');

                // Valor vindo do backend
                var preco_caixa = $(this).data('precocaixa');

                // Valor inserido no input
                var preco = campo_preco_caixa.val();

                if ( preco != preco_caixa ) {

                    preco_caixa = preco_caixa.replace(".","");
                    preco_caixa = preco_caixa.replace(",",".");
                    preco_caixa = parseFloat(preco_caixa);

                    preco = preco.replace(".","");
                    preco = preco.replace(",",".");
                    preco = parseFloat(preco);

                    var subtracao = preco_caixa - preco;
                    var divisao = subtracao / preco_caixa;
                    var result = divisao * 100;

                    campo_desconto.val(Math.round(result));
                    campo_desconto.maskMoney({
                        thousands: ".",
                        decimal: ",",
                        precision: 2
                    }).maskMoney( 'mask' );

                    // campo preco unitario
                    var resultado = preco / quantidade_unidade;

                    campo_preco_unitario.val(mascaraValor(resultado.toFixed(4)));
                    campo_preco_unitario.maskMoney({
                        thousands: ".",
                        decimal: ",",
                        precision: 4
                    }).maskMoney( 'mask' );
                } else {
                    campo_desconto.val(0);
                    campo_desconto.maskMoney({
                        thousands: ".",
                        decimal: ",",
                        precision: 2
                    }).maskMoney( 'mask' );

                    // campo_precocaixa.val(mascaraValor(campo_precocaixa.data('prcaixa').toFixed(4)));
                    // campo_precocaixa.maskMoney({
                    //     thousands: ".",
                    //     decimal: ",",
                    //     precision: 4
                    // }).maskMoney( 'mask' );
                }
            });
        });

        $('[data-preco]').each(function (i, v) {
            var me = $(v);

            me.on('blur', function () {

                var tr = $(this).parent().parent();

                var campo_desconto = tr.find('input#desconto');
                var campo_preco_unitario = tr.find('input#preco_unitario');
                var campo_precocaixa = tr.find('input#preco_caixa');

                var quantidade_unidade = campo_desconto.data('qnt');

                var preco_unitario = campo_preco_unitario.val();
                var preco_caixa = campo_precocaixa.data('precocaixa');

                preco_unitario = preco_unitario.replace(".","");
                preco_unitario = preco_unitario.replace(",",".");
                preco_unitario = parseFloat(preco_unitario);

                preco_caixa = preco_caixa.replace(".","");
                preco_caixa = preco_caixa.replace(",",".");
                preco_caixa = parseFloat(preco_caixa);

                if ( preco_unitario != '' || preco_unitario != 0 ) {

                    var resultado = preco_unitario * quantidade_unidade;

                    campo_precocaixa.val(mascaraValor(resultado.toFixed(4)));
                    campo_precocaixa.maskMoney({
                        thousands: ".",
                        decimal: ",",
                        precision: 4
                    }).maskMoney( 'mask' );

                    // desconto
                    var subtracao = preco_caixa - resultado;
                    var divisao = subtracao / preco_caixa;
                    var result = divisao * 100;

                    campo_desconto.val(Math.round(result));
                    campo_desconto.maskMoney({
                        thousands: ".",
                        decimal: ",",
                        precision: 2
                    }).maskMoney( 'mask' );

                } else {
                    campo_precocaixa.val(0);
                    campo_precocaixa.maskMoney({
                        thousands: ".",
                        decimal: ",",
                        precision: 4
                    }).maskMoney( 'mask' );

                    campo_desconto.val(Math.round(100));
                    campo_desconto.maskMoney({
                        thousands: ".",
                        decimal: ",",
                        precision: 2
                    }).maskMoney( 'mask' );
                }
            });
        }); 

        $('#btnRascunho').on('click', function(e) {
            e.preventDefault();

            var action =  $('#respostaCotacao').attr('action');

            var url = action + "/1";
            
            if ( action.indexOf('/1') == -1 ) {
                $('#respostaCotacao').attr('action', url);
                $('#respostaCotacao').submit();
            }
        });

        var slct_group = $('#formasPagamento');
      
        slct_group.select2({
            placeholder: 'SELECIONE ...',

            ajax: {
                url: slct_group.data('url'),
                type: 'get',
                dataType: 'json',
                delay: 300,
                data: function(params) {
                    return {
                        columns: [{
                            name: 'descricao',
                            search: params.term
                        }],
                        page: params.page || 1
                    }
                }
            },

            processResults: function(data) {
                return {
                    results: data
                }
            },

            templateResult: function(data) {
                if (!data.id) {
                    return data.text;
                }

                return data.descricao;
            },

            templateSelection: function(data) {
                if (!data.id) {
                    return data.text;
                }
                return (typeof data.descricao !== 'undefined') ? data.descricao : data.text;
            }
        });

        initSelect2(slct_group);
    });

    function initSelect2(e) {
        $.ajax({
            url: e.data('url'),
            type: 'get',
            dataType: "json",
            data: {
                columns: [{
                    name: 'id',
                    search: e.data('value'),
                    equal: true
                }]
            }
        }).then(function (data) {
            var id;
            data.results.forEach(function (entry) {
                id = entry.id;
                var $option = new Option(entry.descricao, entry.id, false, false);
                $(e).append($option).val(id).trigger('change');
            });
        });
    }

    function mascaraValor(valor) {
        valor = valor.toString().replace(/\D/g,"");
        valor = valor.toString().replace(/(\d)(\d{8})$/,"$1.$2");
        valor = valor.toString().replace(/(\d)(\d{5})$/,"$1.$2");
        valor = valor.toString().replace(/(\d)(\d{2})$/,"$1,$2");
        return valor
    }
</script>
</body>

</html>
