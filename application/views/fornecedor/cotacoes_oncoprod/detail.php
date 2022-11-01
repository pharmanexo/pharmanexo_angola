<!DOCTYPE html>
<html lang="pt-BR">
<?php echo $header; ?>
<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>
<div class="content">
    <?php echo $heading; ?>
    <form action="<?php if (isset($form_action)) echo $form_action; ?>" id="respostaCotacao" method="post"
          data-ocultar="<?php if (isset($url_ocultar)) echo $url_ocultar; ?>"
          data-historico="<?php if (isset($url_historico)) echo $url_historico; ?>"
          data-save="<?php if (isset($url_saveme)) echo $url_saveme; ?>"
          data-dados="<?php echo "{$cotacao['cd_cotacao']}|{$this->session->id_fornecedor}"; ?>"
          data-revisao="<?php echo $url_revisar; ?>" data-urlprice="<?php echo $url_price; ?>">
        <div class="content__inner">
            <input type="hidden" name="integrador" id="integrador" value="<?php echo $integrador; ?>">
            <input type="hidden" name="cd_cotacao" id="cd_cotacao"
                   value="<?php if (isset($cotacao['cd_cotacao'])) echo $cotacao['cd_cotacao']; ?>"> <input
                    type="hidden" name="dt_inicio_cotacao" id="dt_inicio_cotacao"
                    value="<?php if (isset($cotacao['data_inicio'])) echo $cotacao['data_inicio']; ?>">

            <?php if ($cotacao['data_fim'] < date('Y-m-d H:i:s', strtotime("-1 hour"))) { ?>
                <div class="alert alert-danger" role="alert"><i
                            class="fas fa-exclamation-triangle"></i> Esta cotação se encontra encerrada.
                </div>
            <?php } ?>

            <?php if (isset($recusa['motivo_recusa']) && $recusa['motivo_recusa'] > 0) { ?>
                <div class="alert alert-danger" role="alert"><i
                            class="fas fa-exclamation-triangle"></i> Essa cotação foi descartada
                    por <?php if (isset($recusa['usuario'])) echo $recusa['usuario']; ?>
                    em <?php echo date('d/m/Y H:i:s'); ?> -
                    MOTIVO: <?php echo getMotivosRecusa($recusa['motivo_recusa']); ?>
                </div>
            <?php } ?>

            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col text-left">
                            <div class="checkbox">
                                <input type="checkbox" id="revisarCotacao"
                                       data-cotacao="<?php echo $cotacao['cd_cotacao']; ?>" <?php if ($cotacao['revisao'] == 1) echo 'checked' ?> >
                                <label class="checkbox__label mt-2" id="<?php echo $cotacao['cd_cotacao']; ?>"
                                       for="revisarCotacao"> <?php echo ($cotacao['revisao'] == 1) ? 'Cotação revisada' : 'Marcar como revisada'; ?> </label>
                            </div>
                        </div>
                        <div class="col text-right">
                            <!-- <?php /*if ($cotacao['data_fim'] > date('Y-m-d H:i:s', strtotime("-1 hour")) || $_SESSION['id_usuario'] == 187) { */ ?>
                                <button type="submit" form="respostaCotacao"
                                        class="btn btn-primary formulario">ENVIAR RESPOSTA DA COTAÇÃO
                                </button>
                            --><?php /*} */ ?>

                            <?php if (!isset($recusa['motivo_recusa'])) { ?>
                                <button type="submit" form="respostaCotacao"
                                        class="btn btn-primary formulario">ENVIAR RESPOSTA DA COTAÇÃO
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <p><strong>CNPJ Comprador </strong></p>
                            <?php if (isset($cotacao['cnpj'])) echo $cotacao['cnpj']; ?>
                        </div>
                        <div class="col-12 col-lg-5">
                            <p><strong>Razão Social</strong></p>
                            <?php if (isset($cotacao['cliente'])) echo (!empty($cotacao['cliente']['nome_fantasia'])) ? $cotacao['cliente']['nome_fantasia'] : $cotacao['cliente']['razao_social']; ?>
                        </div>
                        <div class="col-12 col-lg-3">
                            <p><strong>Cidade/Estado</strong></p>
                            <?php if (isset($cotacao['cliente']['cidade']) && !empty($cotacao['cliente']['cidade'])) echo $cotacao['cliente']['cidade'] . ' - '; ?><?php if (isset($cotacao['cliente']['estado'])) echo $cotacao['cliente']['estado'] . ' - '; ?>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-12 col-lg-3">
                            <p><strong>Data Início</strong></p>
                            <?php if (isset($cotacao['data_inicio'])) echo date('d/m/Y H:i:s', strtotime($cotacao['data_inicio'])) ?>
                        </div>
                        <div class="col-12 col-lg-3">
                            <p><strong>Data Fim</strong></p>
                            <?php if (isset($cotacao['data_fim'])) echo date("d/m/Y H:i:s", strtotime($cotacao['data_fim'])) ?>
                        </div>
                        <div class="col-12 col-lg-3">
                            <p><strong>Total de Itens</strong></p>
                            <?php if (isset($cotacao['itens'])) echo $cotacao['itens'] ?>
                        </div>
                        <div class="col-12 col-lg-3">
                            <p><strong>Condição de Pagamento</strong></p>
                            <?php if (isset($cotacao['condicao_pagamento'])) echo $cotacao['condicao_pagamento'] ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <label>Forma de Pagamento</label> <select class="form-control w-100" id="formasPagamento"
                                                                      name="forma_pagto"
                                                                      data-url="<?php echo $select_formas_pagamento; ?>"
                                                                      data-value="<?php if (!empty($forma_pagamento)) echo $forma_pagamento ?>"
                                                                      style="width: 100%"></select>
                        </div>
                        <div class="col-6">
                            <label>Prazo de Entrega</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="prazo_entrega"
                                       value="<?php if (!empty($prazo_entrega)) echo $prazo_entrega ?>">
                                <div class="input-group-append">
                                    <div class="input-group-text bg-light">Dias</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="obs">Observações da cotação</label> <input type="text" name="obs" id="obs"
                                                                                       class="form-control"
                                                                                       maxlength="500"
                                                                                       value="<?php if (isset($observacao)) echo $observacao ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row mb-3">
                <div class="col-12">
                    <div class="enviado"
                         style="width: 15px; height: 15px; border-radius: 20%; border: 1px solid; display: inline-block"></div>
                    &nbsp;Respondida&nbsp;&nbsp;
                    <div class="nenviado"
                         style="width: 15px; height: 15px; border-radius: 20%; border: 1px solid; display: inline-block"></div>
                    Sem responder&nbsp;&nbsp;
                    <div class="nencontrado"
                         style="width: 15px; height: 15px; border-radius: 20%; border: 1px solid; display: inline-block"></div>
                    Produto não cadastrado
                </div>
            </div>

            <input type="hidden" name="cd_condicao_pgto" id="cd_condicao_pgto"
                   value="<?php if (isset($cotacao['cd_condicao_pgto'])) echo $cotacao['cd_condicao_pgto']; ?>"> <input
                    type="hidden" name="cnpj_comprador" id="cnpj_comprador"
                    value="<?php if (isset($cotacao['cnpj'])) echo $cotacao['cnpj']; ?>">
            <?php if (isset($cotacao['produtos'])) { ?><?php foreach ($cotacao['produtos'] as $k => $produto) { ?>
                <div class="card">
                    <div class="card-header <?php echo(is_null($produto['encontrados']) ? 'nencontrado' :
                        (in_array(1, array_column($produto['encontrados'], 'enviado')) ? 'enviado' :
                            ($produto['cotado']['encontrados'] != 0 && !in_array(1, array_column($produto['encontrados'], 'enviado')) ? 'nenviado' : ''))) ?>"
                         id="heading<?php echo $k; ?>">
                        <p class="mb-0">
                            <?php if (isset($produto['encontrados']) && $produto['cotado']['encontrados'] >= 0) { ?>
                        <div class="row">
                            <div class="col">
                                <div class="checkbox  checkbox--inline mt-1">
                                    <input type="checkbox" class="restritock"
                                           id="<?php echo "rest_{$k}_{$produto['cotado']['id_produto_sintese']}_{$produto['cotado']['cd_produto_comprador']}"; ?>"
                                           name="produtos[<?php echo $produto['cotado']['cd_produto_comprador'] ?>][restricao]"
                                           data-restricao="<?php echo "rest_{$k}_{$produto['cotado']['id_produto_sintese']}_{$produto['cotado']['cd_produto_comprador']}"; ?>"
                                           value="" autocomplete="off"
                                        <?php if (isset($produto['cotado']['restricao']) && $produto['cotado']['restricao'] == 1) echo 'checked' ?>
                                    > <label class="checkbox__label" data-toggle="tooltip"
                                             title="<?php echo (isset($produto['cotado']['restricao']) && $produto['cotado']['restricao'] == 1) ? 'Produto com restrição' : 'Marcar restrição para este produto'; ?>"
                                             for="<?php echo "rest_{$k}_{$produto['cotado']['id_produto_sintese']}_{$produto['cotado']['cd_produto_comprador']}"; ?>">Restrição
                                        de Venda</label>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="row mt-3 <?php echo(!isset($produto['encontrados']) ? 'nencontrado' :
                            (in_array(1, array_column($produto['encontrados'], 'enviado')) ? 'enviado' :
                                ($produto['cotado']['encontrados'] != 0 && !in_array(1, array_column($produto['encontrados'], 'enviado')) ? 'nenviado' : ''))) ?>">
                            <div class="col-12 col-lg-4">
                                <strong>Descrição Padrão do Produto</strong><br>
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
                            <?php if (isset($produto['encontrados']) && $produto['cotado']['encontrados'] >= 0) { ?>

                                <div class="col-12 col-lg-2">

                                    <div class="checkbox checkbox--inline mr-2">
                                        <input type="checkbox" class="olck"
                                               id="<?php echo "ol_{$k}_{$produto['cotado']['id_produto_sintese']}_{$produto['cotado']['cd_produto_comprador']}"; ?>"
                                               data-ol="<?php echo "ol_{$k}_{$produto['cotado']['id_produto_sintese']}_{$produto['cotado']['cd_produto_comprador']}"; ?>"
                                               name="produtos[<?php echo $produto['cotado']['cd_produto_comprador'] ?>][ol]"
                                            <?php echo (isset($produto['cotado']['ol']) && $produto['cotado']['ol'] == 1) ? 'checked' : '' ?>
                                            <?php if (isset($produto['cotado']['restricao']) && $produto['cotado']['restricao'] == 1 && isset($produto['encontrados']) && count($produto['encontrados']) == 1) echo 'disabled' ?>
                                        > <label class="checkbox__label mb-0" data-toggle="tooltip"
                                                 title="<?php echo (isset($produto['cotado']['ol']) && $produto['cotado']['ol'] == 1) ? 'Operação logística' : 'Marcar operação logística' ?> "
                                                 for="<?php echo "ol_{$k}_{$produto['cotado']['id_produto_sintese']}_{$produto['cotado']['cd_produto_comprador']}"; ?>">OL</label>
                                    </div>
                                    <div class="checkbox checkbox--inline">
                                        <input type="checkbox" class="semestoqueck"
                                               id="<?php echo "semestq_{$k}_{$produto['cotado']['id_produto_sintese']}_{$produto['cotado']['cd_produto_comprador']}"; ?>"
                                               data-semestq="<?php echo "semestq_{$k}_{$produto['cotado']['id_produto_sintese']}_{$produto['cotado']['cd_produto_comprador']}"; ?>"
                                               name="produtos[<?php echo $produto['cotado']['cd_produto_comprador'] ?>][sem_estoque]"
                                            <?php if (isset($produto['cotado']['sem_estoque']) && $produto['cotado']['sem_estoque'] == 1) echo 'checked' ?>
                                            <?php if (isset($produto['cotado']['restricao']) && $produto['cotado']['restricao'] == 1 && isset($produto['encontrados']) && count($produto['encontrados']) == 1) echo 'disabled' ?>
                                        > <label class="checkbox__label mb-0" data-toggle="tooltip"
                                                 title=" <?php echo (isset($produto['cotado']['sem_estoque']) && $produto['cotado']['sem_estoque'] == 1) ? 'Sem estoque' : 'Marcar como sem estoque' ?>"
                                                 for="<?php echo "semestq_{$k}_{$produto['cotado']['id_produto_sintese']}_{$produto['cotado']['cd_produto_comprador']}"; ?>">S.E.</label>
                                    </div>

                                    <a href="<?php echo $url_findProduct ?><?php echo $produto['cotado']['id_produto_sintese'] ?>"
                                       data-depara="" data-toggle="tooltip" title="Upgrade De -> Para"> <i
                                                class="fas fa-arrow-circle-up"></i> </a>
                                </div>

                                <?php if (!empty($produto['cotado']['cd_produto_comprador'])): ?>
                                    <div class="col-12 col-lg-2">
                                        <button class="btn btn-block btn-secondary" type="button" data-toggle="collapse"
                                                data-target="#collapse<?php echo $k; ?>" aria-expanded="true"
                                                aria-controls="collapse<?php echo $k; ?>">
                                            Produtos <i class="fas fa-chevron-down ml-3"></i>
                                        </button>
                                    </div>
                                <?php else: ?>
                                    <div class="col-12 col-lg-2">
                                        <button class="btn btn-block btn-danger" type="button" data-toggle="tooltip"
                                                title="Produto sem código do comprador na sintese">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </button>
                                    </div>
                                <?php endif; ?><?php } else { ?>
                                <div class="col-12 col-lg-2"></div>
                                <div class="col-12 col-lg-2">
                                    <a href="<?php echo $url_findProduct ?><?php echo $produto['cotado']['id_produto_sintese'] ?>"
                                       data-depara="" class="btn btn-block btn-danger">Fazer De -> Para</a>
                                </div>
                            <?php } ?>
                        </div>
                        </p>
                    </div>
                    <div id="collapse<?php echo $k; ?>"
                         class="collapse <?php if (isset($produto['encontrados']) && $produto['cotado']['encontrados'] >= 0 && !empty($produto['cotado']['cd_produto_comprador'])) echo 'show' ?>"
                         aria-labelledby="heading<?php echo $k; ?>" data-parent="#heading<?php echo $k; ?>">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-success"
                                         style="width: 15px; height: 15px; border: 1px solid; border-radius: 20%; display: inline-block"></div>
                                    Disponível &nbsp; &nbsp; &nbsp;
                                    <div class="table-warning"
                                         style="width: 15px; height: 15px; border: 1px solid; border-radius: 20%; display: inline-block"></div>
                                    Insuficiente &nbsp; &nbsp; &nbsp;
                                    <div class="table-danger"
                                         style="width: 15px; height: 15px; border: 1px solid; border-radius: 20%; display: inline-block"></div>
                                    Sem estoque
                                </div>
                            </div>
                            <table class="table table-striped">
                                <tr>
                                    <th>Código Kraft</th>
                                    <th>Marca</th>
                                    <th>Preço Unit.</th>
                                    <th>Qtd Emb.</th>
                                    <th class="text-nowrap">Desconto (%)</th>
                                    <th class="text-nowrap">Preço Caixa</th>
                                    <th class="text-nowrap">Preço Unit.</th>
                                    <th class="text-nowrap">CNPJ da Oferta</th>
                                    <th></th>
                                </tr>
                                <?php if (isset($produto['encontrados']) && !empty($produto['encontrados'])) { ?><?php foreach ($produto['encontrados'] as $jj => $prod) { ?>

                                    <?php $cd_produto_comprador = $produto['cotado']['cd_produto_comprador']; ?><?php $produto['cotado']['cd_produto_comprador'] = str_replace('.', '', $produto['cotado']['cd_produto_comprador']); ?>


                                    <input type="hidden"
                                           name="produtos[<?php echo $produto['cotado']['cd_produto_comprador'] ?>][id_produto_sintese]"
                                           value="<?php echo $produto['cotado']['id_produto_sintese']; ?>"><input
                                            type="hidden"
                                            name="produtos[<?php echo $produto['cotado']['cd_produto_comprador'] ?>][estoque]"
                                            value="<?php echo $produto['cotado']['encontrados']; ?>">

                                    <?php if (isset($prod['nivel'])): ?>
                                        <input type="hidden"
                                               name="produtos[<?php echo $produto['cotado']['cd_produto_comprador'] ?>][marcas][<?php echo $jj ?>][nivel]"
                                               value="<?php echo $prod['nivel']; ?>"><input type="hidden"
                                                                                            name="produtos[<?php echo $produto['cotado']['cd_produto_comprador'] ?>][marcas][<?php echo $jj ?>][ocultar]"
                                                                                            value="<?php echo $prod['ocultar']; ?>">
                                        <input type="hidden"
                                               name="produtos[<?php echo $produto['cotado']['cd_produto_comprador'] ?>][marcas][<?php echo $jj ?>][id_cotacao]"
                                               value="<?php echo $prod['id_cotacao']; ?>">
                                    <?php endif; ?>

                                    <input type="hidden"
                                           name="produtos[<?php echo $produto['cotado']['cd_produto_comprador'] ?>][marcas][<?php echo $jj ?>][codigo]"
                                           value="<?php echo $prod['codigo']; ?>"><input type="hidden"
                                                                                         name="produtos[<?php echo $produto['cotado']['cd_produto_comprador'] ?>][marcas][<?php echo $jj ?>][cd_produto_comprador]"
                                                                                         value="<?php echo $cd_produto_comprador; ?>">
                                    <input type="hidden"
                                           name="produtos[<?php echo $produto['cotado']['cd_produto_comprador'] ?>][marcas][<?php echo $jj ?>][ds_unidade_compra]"
                                           value="<?php echo $produto['cotado']['ds_unidade_compra']; ?>"><input
                                            type="hidden"
                                            name="produtos[<?php echo $produto['cotado']['cd_produto_comprador'] ?>][marcas][<?php echo $jj ?>][ds_produto_comprador]"
                                            value="<?php echo $produto['cotado']['ds_produto_comprador']; ?>"><input
                                            type="hidden"
                                            name="produtos[<?php echo $produto['cotado']['cd_produto_comprador'] ?>][marcas][<?php echo $jj ?>][qt_produto_total]"
                                            value="<?php echo $produto['cotado']['qt_produto_total']; ?>"><input
                                            type="hidden"
                                            name="produtos[<?php echo $produto['cotado']['cd_produto_comprador'] ?>][marcas][<?php echo $jj ?>][id_produto]"
                                            value="<?php echo $prod['id_produto']; ?>"><input type="hidden"
                                                                                              name="produtos[<?php echo $produto['cotado']['cd_produto_comprador'] ?>][marcas][<?php echo $jj ?>][id_marca]"
                                                                                              value="<?php echo $prod['id_marca']; ?>">
                                    <input type="hidden"
                                           name="produtos[<?php echo $produto['cotado']['cd_produto_comprador'] ?>][marcas][<?php echo $jj ?>][obs]"
                                           id="obs_<?php echo "{$k}_{$jj}_{$prod['codigo']}" ?>"
                                           value="<?php echo (isset($prod['obs'])) ? $prod['obs'] : '' ?>">


                                    <tr class="<?php if (isset($prod['class'])) echo $prod['class']; ?>">

                                        <td colspan="9" class="ml-0">
                                            <?php if (isset($prod['nivel']) && $prod['enviado'] == 1) { ?><?php if ($prod['nivel'] == 1) { ?>
                                                <i style="margin-right: 7px" data-toggle="tooltip"
                                                   title="Cotado via Manual" class="fas fa-keyboard"></i>
                                            <?php } else { ?>
                                                <i style="margin-right: 7px" data-toggle="tooltip"
                                                   title="Cotado via Automática" class="fas fa-robot"></i>
                                            <?php } ?><?php } ?>

                                            <?php if (isset($prod['produto_descricao'])) echo $prod['produto_descricao'] ?>

                                            <small id="label_alert">
                                                <?php if ($prod['restricao'] == 1) { ?>
                                                    <i style="color : #ff0000; font-size: 16px; margin-left: 10px"
                                                       data-toggle="tooltip"
                                                       title="Existe uma restrição no painel de controle"
                                                       class="fas fa-exclamation-circle"></i>&nbsp;&nbsp;&nbsp;
                                                <?php } ?>
                                            </small> <br> <small
                                                    class="<?php echo (isset($prod['nivel'])) ? ' ml-4' : '' ?>">
                                                <b>ESTOQUES: </b>
                                                <?php foreach ($prod['estoques'] as $estq) { ?>

                                                    <?php echo "{$estq['name']}: {$estq['value']}" ?> &nbsp; &nbsp; &nbsp;
                                                <?php } ?>
                                            </small>
                                        </td>
                                    </tr>

                                    <tr class="<?php if (isset($prod['class'])) echo $prod['class']; ?> "
                                        data-qtdsolicitada="<?php echo $produto['cotado']['qt_produto_total'] ?>"
                                        id="<?php echo "row_{$k}_{$jj}_{$prod['codigo']}"; ?>">
                                        <td>
                                            <div class="checkbox">
                                                <input type="checkbox"
                                                       id="prod[<?php echo $k; ?>][<?php echo $jj; ?>][<?php echo $prod['codigo']; ?>]"
                                                       data-check="produto"
                                                       name="produtos[<?php echo $produto['cotado']['cd_produto_comprador'] ?>][marcas][<?php echo $jj ?>][marcado]"
                                                       value="" autocomplete="off"
                                                       class="<?php if ($prod['restricao'] == 1) echo 'notdisabled'; ?>"
                                                    <?php echo (($prod['enviado'] == 1 || $prod['rascunho'] == 1) && $produto['cotado']['restricao'] == 0) ? 'checked' : ''; ?>
                                                    <?php if (isset($produto['cotado']['restricao']) && $produto['cotado']['restricao'] == 1) echo 'disabled' ?>
                                                > <label class="checkbox__label"
                                                         for="prod[<?php echo $k; ?>][<?php echo $jj; ?>][<?php echo $prod['codigo']; ?>]"><?php echo $prod['codigo']; ?></label>
                                            </div>
                                        </td>

                                        <td class="text-nowrap"><small
                                                    id="label_marca"><?php echo $prod['marca']; ?></small></td>

                                        <td>
                                            <small id="label_preco"><?php echo number_format($prod['preco_unitario'], 4, ',', '.') ?></small>
                                        </td>

                                        <td><small id="label_qtd"><?php echo $prod['quantidade_unidade']; ?></small>
                                        </td>

                                        <!-- CAMPO DESCONTO -->
                                        <td>
                                            <input type="text" value="0,00" id="desconto"
                                                   data-desconto="<?php echo "{$k}_{$jj}_{$prod['id']}" ?>"
                                                   data-precounidade="<?php echo number_format($prod['preco_unitario'], 4, ',', '.') ?>"
                                                   data-qnt="<?php echo $prod['quantidade_unidade'] ?>"
                                                   data-inputmask="money"
                                                   class="text-center form-control <?php if ($prod['restricao'] == 1) echo 'notdisabled'; ?>"
                                                <?php if (isset($produto['cotado']['restricao']) && $produto['cotado']['restricao'] == 1) echo 'readonly' ?>
                                            >
                                        </td>

                                        <!-- CAMPO PREÇO CAIXA -->
                                        <td>
                                            <input type="text" id="preco_caixa"
                                                   value="<?php echo number_format($prod['preco_caixa'], 4, ',', '.') ?>"
                                                   data-precocaixa="<?php echo number_format($prod['preco_caixa'], 4, ',', '.') ?>"
                                                   data-inputmask="money4"
                                                   class=" text-center form-control <?php if ($prod['restricao'] == 1) echo 'notdisabled'; ?>"
                                                <?php if (isset($produto['cotado']['restricao']) && $produto['cotado']['restricao'] == 1) echo 'readonly' ?>
                                            >
                                        </td>

                                        <!-- CAMPO PRECO UNITARIO -->
                                        <td>
                                            <div class="input-group">
                                                <input type="text"
                                                       name="produtos[<?php echo $produto['cotado']['cd_produto_comprador'] ?>][marcas][<?php echo $jj ?>][preco_oferta]"
                                                       id="preco_unitario"
                                                       data-preco="<?php echo "{$k}_{$jj}_{$prod['id']}" ?>"
                                                       value="<?php echo number_format($prod['preco_unitario'], 4, ',', '.') ?>"
                                                       data-inputmask="money4"
                                                       class=" text-center form-control <?php if ($prod['restricao'] == 1) echo 'notdisabled'; ?>"
                                                    <?php if (isset($produto['cotado']['restricao']) && $produto['cotado']['restricao'] == 1) echo 'readonly' ?>
                                                       title="<?php echo (isset($prod['ultima_oferta'])) ? 'Oferta Ganhadora: R$' . number_format($prod['ultima_oferta'], 4, ',', '.') : 'Sem oferta'; ?>"
                                                       data-toggle="tooltip">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary"
                                                            data-url="<?php echo $save_price; ?>"
                                                            data-action="savePrice" data-toggle="tooltip"
                                                            title="Salvar preço fixo" type="button" id="button-addon2">
                                                        <i class="fas fa-check"></i></button>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <select class="select2 selectDropDown <?php if ($prod['restricao'] == 1) echo 'notdisabled'; ?>"
                                                    name="produtos[<?php echo $produto['cotado']['cd_produto_comprador'] ?>][marcas][<?php echo $jj ?>][id_fornecedor]"
                                                <?php foreach ($prod['estoques'] as $estq) { ?><?php echo "data-{$estq['label']}='{$estq['value']}'"; ?><?php } ?>
                                                    data-selectfornecedor=""
                                                    data-codigo="<?php echo $prod['codigo']; ?>"
                                                    data-uf="<?php echo $cotacao['estado']['id']; ?>"
                                                    data-idcliente="<?php echo $cotacao['cliente']['id']; ?>"
                                                <?php if (isset($produto['cotado']['restricao']) && $produto['cotado']['restricao'] == 1) echo 'disabled' ?>
                                            >
                                                <?php foreach ($options_fornecedores as $f) { ?>
                                                    <option value="<?php echo $f['id'] ?>" <?php echo (isset($prod['fornecedor_cotacao']) && $f['id'] == $prod['fornecedor_cotacao']) ? 'selected dd' : ($this->session->id_fornecedor == $f['id']) ? 'selected' : '' ?> ><?php echo $f['fornecedor'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>

                                        <td class="text-nowrap ml-0">

                                            <div class="dropdown">
                                                <a href="#" data-toggle="dropdown"
                                                   class="dropdown-toggle text-secondary"> <i class="fas fa-ellipsis-v"
                                                                                              role="button"
                                                                                              id="dropdownMenuLink"
                                                                                              data-toggle="dropdown"
                                                                                              aria-haspopup="true"
                                                                                              aria-expanded="false"></i>
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                    <a class="dropdown-item <?php if ($prod['restricao'] == 1) echo 'notdisabled'; ?>"
                                                       data-toggle="modal"
                                                        <?php if (isset($produto['cotado']['restricao']) && $produto['cotado']['restricao'] == 1) echo 'style="pointer-events: none"' ?>
                                                       data-target="#obsModal" title="Inserir Observação"
                                                       data-produto="<?php echo "obs_{$k}_{$jj}_{$prod['codigo']}" ?>"><i
                                                                class="far fa-sticky-note"></i>
                                                        <small>&nbsp;&nbsp;Observação</small>
                                                    </a>
                                                    <a class="dropdown-item <?php if ($prod['restricao'] == 1) echo 'notdisabled'; ?>"
                                                       data-toggle="modal"
                                                        <?php if (isset($produto['cotado']['restricao']) && $produto['cotado']['restricao'] == 1) echo 'style="pointer-events: none"' ?>
                                                       data-target="#historicoModal" title="Histórico de ofertas"
                                                       data-idproduto="<?php echo $prod['id_produto'] ?>"><i
                                                                class="fas fa-eye"></i>
                                                        <small>&nbsp;&nbsp;Histórico</small></a>
                                                    <a class="dropdown-item btn-lote"
                                                       data-toggle="modal"
                                                       data-target="#lotesModal" title="Validades"
                                                       data-idproduto="<?php echo $prod['codigo'] ?>"><i
                                                                class="fas fa-eye"></i>
                                                        <small>&nbsp;&nbsp;Lotes</small></a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?><?php } else { ?>
                                    <td colspan="10" class="text-center">Produto não encontrado em sua base de dados.
                                    </td>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
                </div>
            <?php } ?><?php } ?>
        </div>
    </form>

    <?php if ($cotacao['data_fim'] > date('Y-m-d H:i:s')) { ?>
        <div class="row border-top my-3">
            <div class="col-12 text-right py-3">
                <button type="submit" form="respostaCotacao"
                        class="btn btn-primary formulario">ENVIAR RESPOSTA DA COTAÇÃO
                </button>
            </div>
        </div>
    <?php } ?>
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
                        <input type="hidden" id="target" class="modal-obs"> <textarea class="form-control"
                                                                                      name="obsProduto"
                                                                                      id="obsProduto"></textarea>
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

<div class="modal fade" id="historicoModal" tabindex="-1" role="dialog" aria-labelledby="historicoModalLabel"
     aria-hidden="true">
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

<div class="modal fade" id="lotesModal" tabindex="-1" role="dialog" aria-labelledby="lotesModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="testeModalLabel"></h5>
            </div>
            <div class="modal-body">
                <table class="table table-striped" id="tableLotes">
                    <thead>
                    <th>Loja</th>
                    <th>Lote</th>
                    <th>Qtd</th>
                    <th>Validade</th>
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
    var url_saveme = $('#respostaCotacao').data('save');
    var url_revisar = $('#respostaCotacao').data('revisao');
    var url_price = $('#respostaCotacao').data('urlprice');

    $(function () {

        $('#btnDescarte').on('click', function (e) {
            e.preventDefault();
            let me = $(this);

            $.ajax({
                url: me.attr('href'),
                type: 'get',
                dataType: 'html',

                success: function (response) {
                    $('body').append(response);

                    $('#descarteModal').modal({
                        keyboard: false
                    }, 'show').on('hide.bs.modal', function () {
                        $('#descarteModal').remove();
                        window.location.reload();
                    });
                }
            })
        });

        $("#btnCount").html($('[data-check]:checked').length);

        $('[data-action="savePrice"]').click(function (e) {

            var me = $(this);

            me.html('<i class="fas fa-spinner"></i>');
            me.prop("disabled", true);

            var url = me.data('url');

            var tr = me.parent().parent().parent().parent();
            var selct = $(tr).find('.select2')[0];
            var cod = $(tr).find('.checkbox__label')[0];

            var elem = $(tr).find('#preco_caixa');
            var data = {
                'price': $(elem[0]).val(),
                'cd_cotacao': $('#cd_cotacao').val(),
                'id_fornecedor': $(selct).val(),
                'codigo': $(cod).text()
            };

            $.post(url, data, function (xhr) {
                me.html('<i class="fas fa-check"></i>');
                me.prop("disabled", false);
                formWarning(xhr);

            }, 'JSON')


        });

        $('#btn_obs').on('click', function () {
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
            $('#obsProduto').val($('#' + button.data('produto')).val());

            modal.find('.modal-title').text('Nova Observação');
            // Passa o ID do campo observacao do form para o value do campo oculto do modal
            modal.find('.modal-obs').val(button.data('produto'));
        });

        $('.btn-lote').on('click', function () {


            var cod = $(this).data('idproduto');
            var target = $('#tableLotes tbody');
            target.html('');

            $.get(`https://pharmanexo.com.br/fornecedor/cotacoes_oncoprod/getValidades/` + cod, {}, function (xhr) {

                $.each(xhr, function (index, value) {
                    var line = `<tr> 
                    <td>${value.nome_fantasia}</td>
                    <td>${value.lote}</td>
                    <td>${value.estoque}</td>
                    <td>${value.validade}</td> </tr>`;

                    target.append(line);
                });
            });

            $('#obsModal').modal('hide');
        });

        $('#lotesModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            // Define o value do campo textarea pelo valor do campo oculto do form
            $('#obsProduto').val($('#' + button.data('produto')).val());

            modal.find('.modal-title').text('Lotes e Validaes');
            // Passa o ID do campo observacao do form para o value do campo oculto do modal
            modal.find('.modal-obs').val(button.data('produto'));
        });

        $('#historicoModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var modal = $(this);


            var row = button.parent().parent().parent().parent();

            var dropdown = row.find('select');

            var data = {
                id_fornecedor: dropdown.val(),
                id_produto: button.data('idproduto')
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
        }).on('hidden.bs.modal', function (event) {
            var modal = $(this);

            modal.find('tbody').html('');
        });

        $('#respostaCotacao').submit(function () {
            var check = [];

            $("input:checked").not("#revisarCotacao").each(function () {
                $(this).val("1");
            });

            $("input:checked").not("#revisarCotacao, .olck, .restritock, .semestoqueck").each(function () {
                $(this).val("1");
                check.push($(this).id);
            });

            var action = $('#respostaCotacao').attr('action');

            if (check.length < 1 && action.indexOf('/1') == -1) {
                formWarning({type: 'warning', message: "Nenhum produto selecionado!"});
                event.preventDefault();
            }
        });

        $('#btn_ocultar').on('click', function (e) {
            e.preventDefault();

            var data = $('#respostaCotacao').data('dados').split("|");

            var dados = {
                cd_cotacao: data[0],
                id_fornecedor: data[1]
            };

            $.post(url_ocultar, dados, function (xhr) {

                formWarning(xhr);

                if (xhr.type == 'success') {

                    setTimeout(function () {
                        document.getElementById('btnVoltar').click();
                    }, 1500);
                } else {

                    setTimeout(function () {
                        window.location.reload()
                    }, 1500);
                }
            }, 'JSON')
                .fail(function (xhr) {
                    formWarning({type: 'warning', message: "Erro ao salvar as informações!"});
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

                // Converte os campos pra float (troca a virgula por ponto)
                preco_unidade = preco_unidade.replace(".", "").replace(",", ".");
                var preco = campo_preco_unitario.val().replace(".", "").replace(",", ".");
                var desconto = campo_desconto.val().replace(".", "").replace(",", ".");
                var preco_caixa = campo_precocaixa.data('precocaixa').replace(".", "").replace(",", ".");

                if (typeof preco_unidade == 'string') {
                    preco_unidade = parseFloat(preco_unidade);
                }
                if (typeof preco == 'string') {
                    preco = parseFloat(preco);
                }
                if (typeof desconto == 'string') {
                    desconto = parseFloat(desconto);
                }
                if (typeof preco_caixa == 'string') {
                    preco_caixa = parseFloat(preco_caixa);
                }


                if (desconto == '' || desconto == 0 || desconto == '0,00') {
                    campo_desconto.val(0);
                    campo_desconto.maskMoney({
                        thousands: ".",
                        decimal: ",",
                        precision: 2
                    }).maskMoney('mask');

                    campo_preco_unitario.val(mascaraValor(preco_unidade.toFixed(4)));
                    campo_preco_unitario.maskMoney({
                        thousands: ".",
                        decimal: ",",
                        precision: 4
                    }).maskMoney('mask');

                    campo_precocaixa.val(mascaraValor(preco_caixa.toFixed(4)));
                    campo_precocaixa.maskMoney({
                        thousands: ".",
                        decimal: ",",
                        precision: 4
                    }).maskMoney('mask');
                } else {

                    var result = eval(`${preco_unidade} - (${preco_unidade} * (${desconto} / 100))`);

                    campo_preco_unitario.val(mascaraValor(result.toFixed(4)));
                    campo_preco_unitario.maskMoney({
                        thousands: ".",
                        decimal: ",",
                        precision: 4
                    }).maskMoney('mask');


                    var resultado = eval(`${result} * ${quantidade_unidade}`);

                    campo_precocaixa.val(mascaraValor(resultado.toFixed(4)));
                    campo_precocaixa.maskMoney({
                        thousands: ".",
                        decimal: ",",
                        precision: 4
                    }).maskMoney('mask');
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
                var preco_caixa = $(this).data('precocaixa').replace(".", "").replace(",", ".");

                // Valor inserido no input
                var preco = campo_preco_caixa.val().replace(".", "").replace(",", ".");

                if (preco != preco_caixa) {

                    preco_caixa = parseFloat(preco_caixa);
                    preco = parseFloat(preco);

                    var subtracao = preco_caixa - preco;
                    var divisao = subtracao / preco_caixa;
                    var result = divisao * 100;


                    if (result < 1) {
                        result = 0;
                    }

                    campo_desconto.val(Math.round(result));
                    campo_desconto.maskMoney({
                        thousands: ".",
                        decimal: ",",
                        precision: 2
                    }).maskMoney('mask');

                    // campo preco unitario
                    var resultado = preco / quantidade_unidade;

                    campo_preco_unitario.val(mascaraValor(resultado.toFixed(4)));
                    campo_preco_unitario.maskMoney({
                        thousands: ".",
                        decimal: ",",
                        precision: 4
                    }).maskMoney('mask');
                } else {

                    // campo_desconto.val(0);
                    // campo_desconto.maskMoney({
                    //     thousands: ".",
                    //     decimal: ",",
                    //     precision: 2
                    // }).maskMoney( 'mask' );

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

                var tr = $(this).parent().parent().parent();

                var campo_desconto = tr.find('input#desconto');
                var campo_preco_unitario = tr.find('input#preco_unitario');
                var campo_precocaixa = tr.find('input#preco_caixa');

                var quantidade_unidade = campo_desconto.data('qnt');

                var preco_unitario = campo_preco_unitario.val().replace('.', '').replace(",", ".");
                var preco_caixa = campo_precocaixa.data('precocaixa').replace('.', '').replace(",", ".");

                if (preco_unitario != '' && preco_unitario != 0 && preco_unitario != "0.0000") {

                    var resultado = preco_unitario * quantidade_unidade;

                    campo_precocaixa.val(mascaraValor(resultado.toFixed(4)));
                    campo_precocaixa.maskMoney({
                        thousands: ".",
                        decimal: ",",
                        precision: 4
                    }).maskMoney('mask');

                    // desconto
                    var subtracao = preco_caixa - resultado;
                    var divisao = subtracao / preco_caixa;
                    var result = divisao * 100;

                    campo_desconto.val(Math.round(result));
                    campo_desconto.maskMoney({
                        thousands: ".",
                        decimal: ",",
                        precision: 2
                    }).maskMoney('mask');
                } else {
                    campo_precocaixa.val(0);
                    campo_precocaixa.maskMoney({
                        thousands: ".",
                        decimal: ",",
                        precision: 4
                    }).maskMoney('mask');

                    campo_desconto.val(100);
                    campo_desconto.maskMoney({
                        thousands: ".",
                        decimal: ",",
                        precision: 2
                    }).maskMoney('mask');
                }
            });
        });

        $('#btnRascunho').on('click', function (e) {
            e.preventDefault();

            var action = $('#respostaCotacao').attr('action');

            var url = action + "/1";

            if (action.indexOf('/1') == -1) {
                $('#respostaCotacao').attr('action', url);
                $('#respostaCotacao').submit();
            }
        });

        $('#revisarCotacao').on('change', function (e) {
            e.preventDefault();

            var s = ($(this).prop("checked") == true) ? "1" : "0";

            if (s == 1) {
                $(`label#${$(this).data('cotacao')}`).text('Cotação revisada');
            } else {
                $(`label#${$(this).data('cotacao')}`).text('Marcar como revisada');
            }

            $.ajax({
                url: url_revisar + $(this).data('cotacao'),
                type: 'post',
                data: {status: s},
                beforeSend: function (jqXHR, settings) {
                },
                success: function (xhr) {
                    formWarning(xhr);
                },
                error: function (xhr) {
                    console.log(xhr);
                }
            })
        });

        $("[data-check]").change(function (index, element) {

            $("#btnCount").html($('[data-check]:checked').length);

            setTimeout(function () {
                saveme();
            }, 5000);
        });

        $("[data-restricao]").change(function (index, element) {

            var card = $(this).parent().parent().parent().parent().parent();

            var inputs = card.find('input').not('.restritock');
            var dropdown = card.find('select');
            var links = card.find('a');

            if ($(this).prop("checked") == true) {

                $(`label[for=${$(this).prop('id')}]`).attr('data-original-title', 'Produto com restrição');

                $.each(inputs, function (index, input) {

                    if ($(input).prop('checked') == true) {
                        $(input).prop('checked', false);
                    }

                    if ($(this).attr('type') == 'checkbox') {

                        $(this).attr("disabled", true);
                    } else {
                        $(this).attr("readonly", true);
                    }

                });

                dropdown.attr("disabled", true);

                $.each(links, function (index, value) {
                    $(this).attr("style", 'pointer-events: none');
                });
            } else {

                $(`label[for=${$(this).prop('id')}]`).attr('data-original-title', 'Marcar restrição para este produto');


                inputs = card.find('input').not('.restritock, .notdisabled');
                links = card.find('a').not('.restritock, .notdisabled');

                $.each(inputs, function (index, value) {

                    if ($(this).attr('type') == 'checkbox') {

                        $(this).attr("disabled", false);
                    } else {
                        $(this).attr("readonly", false);
                    }

                });

                dropdown.prop("disabled", false);

                $.each(links, function (index, value) {
                    $(this).attr("style", '');
                });
            }
        });

        $("[data-ol]").change(function (index, element) {

            var card = $(this).parent().parent().parent().parent().parent();

            var inputs = card.find('input').not('.restritock');
            var dropdown = card.find('select');
            var links = card.find('a');

            if ($(this).prop("checked") == true) {

                $(`label[for=${$(this).prop('id')}]`).attr('data-original-title', 'Operação Logística');
            } else {

                $(`label[for=${$(this).prop('id')}]`).attr('data-original-title', 'Marcar operação logística');
            }
        });

        $("[data-semestq]").change(function (index, element) {

            if ($(this).prop("checked") == true) {

                $(`label[for=${$(this).prop('id')}]`).attr('data-original-title', 'Sem estoque');

            } else {

                $(`label[for=${$(this).prop('id')}]`).attr('data-original-title', 'Marcar como sem estoque');
            }
        });

        $("[data-selectfornecedor]").change(function (index, element) {

            var row = $(this).parent().parent();
            var inputs = row.find("input")
            var links = row.find('a');

            var estoque = $(this).data($(this).val());

            var qtd_solicitada = row.data('qtdsolicitada');
            var codigo = $(this).data('codigo');
            var id_estado = $(this).data('uf');
            var id_cliente = $(this).data('idcliente');

            $.ajax({
                url: url_price,
                type: 'post',
                data: {codigo: codigo, id_fornecedor: $(this).val(), id_estado: id_estado, id_cliente: id_cliente},
                success: function (xhr) {

                    if (xhr.type == 'success') {

                        // Retira o alerta e os disabled dos campos
                        row.prev().find("#label_alert").html("");

                        // Resposta
                        var precocaixa = xhr.data['preco_caixa'];
                        var preco_unidade = xhr.data['preco_unidade'];
                        var qnt = xhr.data['qtd'];
                        var marca = xhr.data['marca'];

                        // Campos Input
                        var input_desconto = row.find('[data-desconto]');
                        var input_precocx = row.find('[data-precocaixa]');
                        var input_precounit = row.find('[data-preco]');


                        // data attribute CAMPO DESCONTO
                        input_desconto.data('precounidade', preco_unidade);
                        input_desconto.data('qnt', qnt);
                        input_desconto.val(0);
                        input_desconto.maskMoney({
                            thousands: ".",
                            decimal: ",",
                            precision: 2
                        }).maskMoney('mask');

                        // data attribute CAMPO PRECO CAIXA
                        input_precocx.data('precocaixa', precocaixa);
                        input_precocx.val(mascaraValor(precocaixa));
                        input_precocx.maskMoney({
                            thousands: ".",
                            decimal: ",",
                            precision: 4
                        }).maskMoney('mask');

                        // data attribute CAMPO PRECO UNIDADE
                        input_precounit.val(mascaraValor(preco_unidade));
                        input_precounit.maskMoney({
                            thousands: ".",
                            decimal: ",",
                            precision: 4
                        }).maskMoney('mask');

                        var ultima_oferta = (xhr.data['ultima_oferta'] == '0,0000') ? 'Sem oferta' : 'Ultima oferta: R$ ' + xhr.data['ultima_oferta'];
                        input_precounit.attr('data-original-title', ultima_oferta).tooltip('update');

                        // Altera label qtd e preco unitario
                        row.find("#label_preco").html(preco_unidade);
                        row.find("#label_qtd").html(qnt);
                        row.find("#label_marca").html(marca);
                    } else {

                        if (xhr.type == 'warning') {

                            var alert = `<i style="color : #ff0000; font-size: 16px; margin-left: 10px"
                                data-toggle="tooltip"
                                title="Existe uma restrição no painel de controle"
                                class="fas fa-exclamation-circle"></i>&nbsp;&nbsp;&nbsp;`;

                            row.prev().find("#label_alert").html(alert);
                        }
                    }

                },
                error: function (xhr) {
                }
            })


            if (estoque > 0 && estoque >= qtd_solicitada) {

                row.removeClass(row[0].className).addClass('table-success');
                row.prev().removeClass(row.prev()[0].className).addClass('table-success');
            } else if (estoque > 0 && estoque < qtd_solicitada) {

                row.removeClass(row[0].className).addClass('table-warning');
                row.prev().removeClass(row.prev()[0].className).addClass('table-warning');
            } else {

                row.removeClass(row[0].className).addClass('table-danger');
                row.prev().removeClass(row.prev()[0].className).addClass('table-danger');
            }

            row.find("[data-check]").data('estoque', estoque);
        });

        $("[data-depara]").on('click', function (e) {

            e.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'post',
                contentType: false,
                processData: false,
                data: {},
                success: function (xhr) {
                    if (xhr.type == 'success') {
                        window.open(xhr.link, '_blank');
                    } else {

                        formWarning(xhr);
                    }

                },
                error: function (xhr) {
                }
            });
        });

        var slct_group = $('#formasPagamento');

        slct_group.select2({
            placeholder: 'SELECIONE ...',

            ajax: {
                url: slct_group.data('url'),
                type: 'get',
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    return {
                        columns: [{
                            name: 'descricao',
                            search: params.term
                        }],
                        page: params.page || 1
                    }
                }
            },

            processResults: function (data) {
                return {
                    results: data
                }
            },

            templateResult: function (data) {
                if (!data.id) {
                    return data.text;
                }

                return data.descricao;
            },

            templateSelection: function (data) {
                if (!data.id) {
                    return data.text;
                }
                return (typeof data.descricao !== 'undefined') ? data.descricao : data.text;
            }
        });

        initSelect2(slct_group);
    });

    function saveme(e) {

        $("input:checked").each(function () {
            $(this).val("1");
        });

        var myForm = document.getElementById('respostaCotacao');

        formData = new FormData(myForm);

        $.ajax({
            url: url_saveme,
            type: 'post',
            contentType: false,
            processData: false,
            data: formData,
            beforeSend: function (jqXHR, settings) {

                $('#btnRascunho, .formulario').prop('disabled', true);
            },
            success: function (xhr) {

                $('#btnRascunho, .formulario').prop('disabled', false);
            },
            error: function (xhr) {
                // console.log(xhr);
            }
        })
    }

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
        valor = valor.toString().replace(/\D/g, "");
        valor = valor.toString().replace(/(\d)(\d{8})$/, "$1.$2");
        valor = valor.toString().replace(/(\d)(\d{5})$/, "$1.$2");
        valor = valor.toString().replace(/(\d)(\d{2})$/, "$1,$2");
        return valor
    }
</script>
</body>

</html>
