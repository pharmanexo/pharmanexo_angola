<?php if (isset($cotacao['produtos'])) : ?>
    <?php foreach ($cotacao['produtos'] as $k => $produto) : ?>

        <?php $cd_produto_comprador = $produto['cotado']['cd_produto_comprador']; ?>
        <?php $id_produto_sintese = (isset($produto['cotado']['id_produto_sintese']) ?  $produto['cotado']['id_produto_sintese'] : ''); ?>
        <?php $identificacao_produto = ($integrador == 'SINTESE') ? $id_produto_sintese : $produto['cotado']['cd_produto_comprador']; ?>

        <?php if (isset($produto['encontrados']) && $produto['cotado']['encontrados'] >= 0) : ?>

            <form action="<?php if (isset($url_saveItem)) echo $url_saveItem; ?>" method="post" enctype="multipart/form-data" data-formproduto="form-produto-<?php echo $k; ?>" id="form-produto-<?php echo $k; ?>">

                <?php if ($integrador == 'SINTESE') { ?>
                    <input type="hidden" name="id_produto_sintese" value="<?php if (isset($produto['cotado']['id_produto_sintese'])) echo $produto['cotado']['id_produto_sintese']; ?>">
                <?php } else { ?>
                    <input type="hidden" name="id_produto_sintese" value="<?php if (isset($produto['cotado']['id_artigo'])) echo $produto['cotado']['id_artigo']; ?>">
                <?php } ?>
                <input type="hidden" name="cd_produto_comprador" value="<?php echo $produto['cotado']['cd_produto_comprador']; ?>">
                <input type="hidden" name="ds_unidade_compra" value="<?php echo $produto['cotado']['ds_unidade_compra']; ?>">
                <input type="hidden" name="ds_produto_comprador" value="<?php echo $produto['cotado']['ds_produto_comprador']; ?>">
                <input type="hidden" name="qt_produto_total" value="<?php echo $produto['cotado']['qt_produto_total']; ?>">
                <input type="hidden" name="estoque" value="<?php echo $produto['cotado']['encontrados']; ?>">

                <div class="card" id="card-produto-<?php echo $k; ?>">

                    <div class="card-header <?php if (isset($produto['classCard'])) echo $produto['classCard']; ?>" id="heading<?php echo $k; ?>">

                        <p class="mb-0">

                        <div class="row">
                            <div class="col">
                                <div class="checkbox  checkbox--inline mt-1">
                                    <input type="checkbox" class="restritock" id="<?php echo "rest_{$k}_{$produto['cotado']['id_produto_sintese']}_{$produto['cotado']['cd_produto_comprador']}"; ?>" name="restricao" data-key="produto-<?php echo $k; ?>" data-restricao="<?php echo "rest_{$k}_{$produto['cotado']['id_produto_sintese']}_{$produto['cotado']['cd_produto_comprador']}"; ?>" value="1" <?php if (isset($produto['cotado']['restricao']) && $produto['cotado']['restricao'] == 1) echo 'checked' ?>>
                                    <label class="checkbox__label" data-toggle="tooltip" title="<?php echo (isset($produto['cotado']['restricao']) && $produto['cotado']['restricao'] == 1) ? 'Produto com restrição' : 'Marcar restrição para este produto'; ?>" for="<?php echo "rest_{$k}_{$produto['cotado']['id_produto_sintese']}_{$produto['cotado']['cd_produto_comprador']}"; ?>">Restrição de Venda</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3 <?php if (isset($produto['classCard'])) echo $produto['classCard']; ?>">
                            <div class="col-12 col-lg-4">
                                <strong>Descrição Padrão do Produto</strong><br>
                                <?php echo $identificacao_produto . ' - ' . $produto['cotado']['ds_produto_comprador'] ?>
                            </div>
                            <div class="col-12 col-lg-2">
                                <strong>Embalagem</strong><br>
                                <?php echo $produto['cotado']['ds_unidade_compra'] ?>
                            </div>
                            <div class="col-12 col-lg-2 text-center">
                                <strong>Quantidade Solicitada</strong><br>
                                <?php echo $produto['cotado']['qt_produto_total'] ?>
                            </div>

                            <div class="col-12 col-lg-2">

                                <div class="checkbox checkbox--inline mr-2">
                                    <input type="checkbox" class="olck" value="1" id="<?php echo "ol_{$k}_{$produto['cotado']['id_produto_sintese']}_{$produto['cotado']['cd_produto_comprador']}"; ?>" data-ol="<?php echo "ol_{$k}_{$produto['cotado']['id_produto_sintese']}_{$produto['cotado']['cd_produto_comprador']}"; ?>" name="ol" <?php echo (isset($produto['cotado']['ol']) && $produto['cotado']['ol'] == 1) ? 'checked' : '' ?> <?php if (isset($produto['cotado']['restricao']) && $produto['cotado']['restricao'] == 1) echo 'disabled' ?>>
                                    <label class="checkbox__label mb-0" data-toggle="tooltip" title="<?php echo (isset($produto['cotado']['ol']) && $produto['cotado']['ol'] == 1) ? 'Operação logística' : 'Marcar operação logística' ?> " for="<?php echo "ol_{$k}_{$produto['cotado']['id_produto_sintese']}_{$produto['cotado']['cd_produto_comprador']}"; ?>">OL</label>
                                </div>
                                <div class="checkbox checkbox--inline">
                                    <input type="checkbox" class="semestoqueck" value="1" id="<?php echo "semestq_{$k}_{$produto['cotado']['id_produto_sintese']}_{$produto['cotado']['cd_produto_comprador']}"; ?>" data-semestq="<?php echo "semestq_{$k}_{$produto['cotado']['id_produto_sintese']}_{$produto['cotado']['cd_produto_comprador']}"; ?>" name="sem_estoque" <?php if (isset($produto['cotado']['sem_estoque']) && $produto['cotado']['sem_estoque'] == 1) echo 'checked' ?> <?php if (isset($produto['cotado']['restricao']) && $produto['cotado']['restricao'] == 1) echo 'disabled' ?>>
                                    <label class="checkbox__label mb-0" data-toggle="tooltip" title=" <?php echo (isset($produto['cotado']['sem_estoque']) && $produto['cotado']['sem_estoque'] == 1) ? 'Sem estoque' : 'Marcar como sem estoque' ?>" for="<?php echo "semestq_{$k}_{$produto['cotado']['id_produto_sintese']}_{$produto['cotado']['cd_produto_comprador']}"; ?>">S.E.</label>
                                </div>

                                <a href="<?php echo $url_findProduct; ?><?php echo $identificacao_produto ?>/<?php echo $cotacao['cliente']['id']; ?>" data-depara="" data-idelem="<?php echo $k; ?>" data-codproduto="<?php if (isset($produto['cotado']['cd_produto_comprador'])) echo $produto['cotado']['cd_produto_comprador']; ?>" data-produto="<?php echo explode(' ', $produto['cotado']['ds_produto_comprador'])[0]; ?>" data-toggle="tooltip" title="Upgrade De -> Para">
                                    <i class="fas fa-arrow-circle-up btn_depara"></i>
                                </a>
                            </div>

                            <?php if (!empty($produto['cotado']['cd_produto_comprador'])) : ?>
                                <div class="col-12 col-lg-2">
                                    <button class="btn btn-block btn-secondary" type="button" data-toggle="collapse" data-target="#collapse<?php echo $k; ?>" aria-expanded="true" aria-controls="collapse<?php echo $k; ?>">
                                        Produtos <i class="fas fa-chevron-down ml-3"></i>
                                    </button>
                                </div>
                            <?php else : ?>
                                <div class="col-12 col-lg-2">
                                    <button class="btn btn-block btn-danger" type="button" data-toggle="tooltip" title="Produto sem código do comprador na sintese">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                        </p>
                    </div>

                    <div id="collapse<?php echo $k; ?>" class="collapse  <?php if (!empty($produto['cotado']['cd_produto_comprador'])) echo 'show' ?>" aria-labelledby="heading<?php echo $k; ?>" data-parent="#heading<?php echo $k; ?>">

                        <div class="card-body">

                            <div class="row">
                                <div class="col-12">
                                    <div class="table-success" style="width: 15px; height: 15px; border: 1px solid; border-radius: 20%; display: inline-block"></div>
                                    Disponível &nbsp; &nbsp; &nbsp;
                                    <div class="table-warning" style="width: 15px; height: 15px; border: 1px solid; border-radius: 20%; display: inline-block"></div>
                                    Insuficiente &nbsp; &nbsp; &nbsp;
                                    <div class="table-danger" style="width: 15px; height: 15px; border: 1px solid; border-radius: 20%; display: inline-block"></div>
                                    Sem estoque
                                </div>
                            </div>

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-nowrap"><?php echo $label_codigo; ?></th>
                                        <th class="text-nowrap">Marca</th>
                                        <th class="text-nowrap">Preço Unit.</th>
                                        <th class="text-nowrap">Qtd Emb.</th>
                                        <th class="text-nowrap">Desconto (%)</th>
                                        <th class="text-nowrap">Preço Caixa</th>
                                        <th class="text-nowrap">Preço Oferta.</th>
                                        <th class="text-nowrap"><?php echo ($checkFilial) ? 'CNPJ da Oferta' : ''; ?></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($produto['encontrados'] as $jj => $prod) : ?>

                                        <input type="hidden" name="marcas[<?php echo $jj ?>][codigo]" value="<?php echo $prod['codigo']; ?>">
                                        <input type="hidden" name="marcas[<?php echo $jj ?>][id_marca]" value="<?php echo $prod['id_marca']; ?>">
                                        <input type="hidden" name="marcas[<?php echo $jj ?>][obs]" id="obs_<?php echo "{$k}_{$jj}_{$prod['codigo']}" ?>" value="<?php echo (isset($prod['obs'])) ? $prod['obs'] : '' ?>">
                                        <input type="hidden" name="marcas[<?php echo $jj ?>][produto_descricao]" value="<?php if (isset($prod['produto_descricao'])) echo $prod['produto_descricao']; ?>">
                                        <input type="hidden" name="marcas[<?php echo $jj ?>][estoque]" value="<?php if (isset($prod['estoque'])) echo $prod['estoque']; ?>">
                                        <input type="hidden" name="marcas[<?php echo $jj ?>][quantidade_unidade]" value="<?php if (isset($prod['quantidade_unidade'])) echo $prod['quantidade_unidade']; ?>">

                                        <tr class="<?php if (isset($prod['class'])) echo $prod['class']; ?>">

                                            <td colspan="10" class="ml-0">
                                                <?php if (isset($prod['usuario']['nome'])) echo "<p> Respondido por: {$prod['usuario']['nome']} </p>"; ?>
                                                <?php if (isset($prod['nivel']) && $prod['enviado'] == 1) { ?>
                                                    <?php if ($prod['nivel'] == 1) { ?>
                                                        <i style="margin-right: 7px" data-toggle="tooltip" title="Cotado via Manual" class="fas fa-keyboard"></i>
                                                    <?php } else { ?>
                                                        <i style="margin-right: 7px" data-toggle="tooltip" title="Cotado via Automática" class="fas fa-robot"></i>
                                                    <?php } ?>
                                                <?php } ?>

                                                <?php if (isset($prod['produto_descricao'])) echo strtoupper(str_replace(['%28', '%29'], ['(', ')'], $prod['produto_descricao'])); ?>

                                                <small id="label_alert">
                                                    <?php if ($prod['restricao'] == 1) { ?>
                                                        <i style="color : #ff0000; font-size: 16px; margin-left: 10px" data-toggle="tooltip" title="Existe uma restrição no painel de controle" class="fas fa-exclamation-circle"></i>&nbsp;&nbsp;&nbsp;
                                                    <?php } ?>
                                                </small>
                                                <br>
                                                <small class="<?php echo (isset($prod['nivel'])) ? ' ml-4' : '' ?>">
                                                    <?php if (isset($prod['estoques'])) : ?>
                                                        <b>ESTOQUES: </b>
                                                        <?php foreach ($prod['estoques'] as $estq) : ?>

                                                            <?php echo "{$estq['name']}: {$estq['value']}" ?> &nbsp; &nbsp; &nbsp;
                                                        <?php endforeach; ?>
                                                    <?php else : ?>
                                                        <b>ESTOQUE: </b>
                                                        <?php echo (!isset($prod['estoque']) || empty($prod['estoque'])) ? 0 : $prod['estoque']; ?>
                                                    <?php endif; ?>
                                                </small>

                                                <?php if (isset($produto['cotado']['marca_favorita'])) : ?>
                                                    <br>
                                                    <small class="<?php echo (isset($prod['nivel'])) ? ' ml-4' : '' ?>">
                                                        Marcas favoritas: <?php if (isset($produto['cotado']['marca_favorita'])) echo $produto['cotado']['marca_favorita']; ?>
                                                    </small>
                                                <?php endif; ?>
                                            </td>
                                        </tr>

                                        <tr class="<?php if (isset($prod['class'])) echo $prod['class']; ?> " data-qtdsolicitada="<?php echo $produto['cotado']['qt_produto_total'] ?>" id="<?php echo "row_{$k}_{$jj}_{$prod['codigo']}"; ?>">
                                            <td>
                                                <div class="checkbox">
                                                    <input type="checkbox" id="prod[<?php echo $k; ?>][<?php echo $jj; ?>][<?php echo $prod['codigo']; ?>]" data-check="produto" value="1" data-key="produto-<?php echo $k; ?>" data-integrador="<?php echo $integrador; ?>" data-cd_comprador="<?php echo $produto['cotado']['cd_produto_comprador']; ?>" name="marcas[<?php echo $jj ?>][marcado]" <?php echo (($prod['enviado'] == 1 || $prod['rascunho'] == 1) && $produto['cotado']['restricao'] == 0) ? 'checked' : ''; ?> <?php if (isset($produto['cotado']['restricao']) && $produto['cotado']['restricao'] == 1) echo 'disabled' ?>>
                                                    <label class="checkbox__label" for="prod[<?php echo $k; ?>][<?php echo $jj; ?>][<?php echo $prod['codigo']; ?>]"><?php echo $prod['codigo']; ?></label>
                                                </div>
                                            </td>

                                            <td class="text-nowrap"><small id="label_marca"><?php echo $prod['marca']; ?></small></td>

                                            <td><small id="label_preco"><?php echo number_format($prod['preco_unitario'], 4, ',', '.') ?></small></td>

                                            <td><small id="label_qtd"><?php echo $prod['quantidade_unidade']; ?></small></td>

                                            <!-- CAMPO DESCONTO -->
                                            <td>
                                                <input type="text" value="0,00" id="desconto" data-desconto="<?php echo "{$k}_{$jj}_{$prod['id']}" ?>" data-precounidade="<?php echo number_format($prod['preco_unitario'], 4, ',', '.') ?>" data-qnt="<?php echo $prod['quantidade_unidade'] ?>" data-inputmask="money" class="text-center form-control" <?php if (isset($produto['cotado']['restricao']) && $produto['cotado']['restricao'] == 1) echo 'readonly' ?>>
                                            </td>

                                            <!-- CAMPO PREÇO CAIXA -->
                                            <td>
                                                <input type="text" id="preco_caixa" value="<?php echo number_format($prod['preco_caixa'], 4, ',', '.') ?>" data-precocaixa="<?php echo number_format($prod['preco_caixa'], 4, ',', '.') ?>" data-inputmask="money4" class=" text-center form-control" <?php if (isset($produto['cotado']['restricao']) && $produto['cotado']['restricao'] == 1) echo 'readonly' ?>>
                                            </td>

                                            <!-- CAMPO PRECO UNITARIO -->

                                            <td>
                                                <input type="text" name="marcas[<?php echo $jj ?>][preco_oferta]" id="preco_unitario" data-preco="<?php echo "{$k}_{$jj}_{$prod['id']}" ?>" value="<?php echo number_format($prod['preco_unitario'], 4, ',', '.') ?>" data-inputmask="money4" data-key="produto-<?php echo $k; ?>" class=" text-center form-control" title="<?php echo $prod['ultima_oferta']; ?>" data-toggle="tooltip" <?php if (isset($produto['cotado']['restricao']) && $produto['cotado']['restricao'] == 1) echo 'readonly' ?>>
                                            </td>

                                            <!-- CAMPO SELECT FILIAL -->
                                            <td>
                                                <?php if ($checkFilial) : ?>
                                                    <select class="select2 selectDropDown" name="marcas[<?php echo $jj ?>][id_fornecedor]" <?php foreach ($prod['estoques'] as $estq) : ?> <?php echo "data-{$estq['label']}='{$estq['value']}'"; ?> <?php endforeach; ?> data-selectfornecedor="a" data-key="produto-<?php echo $k; ?>" data-codigo="<?php echo $prod['codigo']; ?>" <?php if (isset($produto['cotado']['restricao']) && $produto['cotado']['restricao'] == 1) echo 'readonly' ?>>
                                                        <?php foreach ($options_fornecedores as $f) : ?>
                                                            <option value="<?php echo $f['id'] ?>" <?php echo (isset($prod['fornecedor_cotacao']) && $f['id'] == $prod['fornecedor_cotacao']) ? 'selected' : ($this->session->id_fornecedor == $f['id']) ? 'selected' : '' ?>><?php echo $f['fornecedor'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                <?php else : ?>
                                                    <select hidden name="marcas[<?php echo $jj; ?>][id_fornecedor]" id="selectForn">
                                                        <option value="<?php echo $this->session->id_fornecedor; ?>" <?php echo (isset($prod['fornecedor_cotacao']) && $f['id'] == $prod['fornecedor_cotacao']) ? 'selected dd' : ($this->session->id_fornecedor == $f['id']) ? 'selected' : '' ?>><?php echo $this->session->nome_fantasia; ?></option>
                                                    </select>
                                                <?php endif; ?>
                                            </td>

                                            <!-- CAMPO OPTIONS -->
                                            <td class="text-nowrap ml-0">

                                                <div class="dropdown">
                                                    <a href="#" data-toggle="dropdown" class="dropdown-toggle text-secondary" style="<?php if (isset($produto['cotado']['restricao']) && $produto['cotado']['restricao'] == 1) echo 'pointer-events: none' ?>">
                                                        <i class="fas fa-ellipsis-v" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                                    </a>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#obsModal" title="Inserir Observação" data-key="produto-<?= $k; ?>" data-produto="<?php echo "obs_{$k}_{$jj}_{$prod['codigo']}" ?>">
                                                            <i class="far fa-sticky-note"></i> <small>&nbsp;&nbsp;Observação</small>
                                                        </a>
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#historicoModal" title="Histórico de ofertas" data-cod="<?php echo $prod['codigo'] ?>" data-cliente="<?php echo $cotacao['cliente']['id']; ?>">
                                                            <i class="fas fa-eye"></i> <small>&nbsp;&nbsp;Histórico</small>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <?php if (isset($prod['nivel'])) : ?>
                                            <input type="hidden" name="marcas[<?php echo $jj ?>][nivel]" value="<?php echo $prod['nivel']; ?>">
                                            <input type="hidden" name="marcas[<?php echo $jj ?>][ocultar]" value="<?php echo $prod['ocultar']; ?>">
                                            <input type="hidden" name="marcas[<?php echo $jj ?>][id_cotacao]" value="<?php echo $prod['id_cotacao']; ?>">
                                        <?php endif; ?>

                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </form>
        <?php else : ?>

            <div class="card" id="card-produto-<?php echo $k; ?>">
                <div class="card-header <?php if (isset($produto['classCard'])) echo $produto['classCard']; ?>" id="heading<?php echo $k; ?>">
                    <div class="row mt-3 <?php if (isset($produto['classCard'])) echo $produto['classCard']; ?>">
                        <div class="col-12 col-lg-4">
                            <strong>Descrição Padrão do Produto</strong><br>
                            <?php echo $identificacao_produto . ' - ' . $produto['cotado']['ds_produto_comprador'] ?>
                        </div>

                        <div class="col-12 col-lg-2">
                            <strong>Embalagem</strong><br>
                            <?php echo $produto['cotado']['ds_unidade_compra'] ?>
                        </div>

                        <div class="col-12 col-lg-2 text-center">
                            <strong>Quantidade Solicitada</strong><br>
                            <?php echo $produto['cotado']['qt_produto_total'] ?>
                        </div>

                        <div class="col-12 col-lg-1"></div>

                        <div class="col-12 col-lg-3">
                            <button id="fazerDePara<?php echo $k; ?>" data-idelem="<?php echo $k; ?>" data-codproduto="<?php if (isset($produto['cotado']['cd_produto_comprador'])) echo $produto['cotado']['cd_produto_comprador']; ?>" data-produto="<?php echo explode(' ', $produto['cotado']['ds_produto_comprador'])[0]; ?>" class="btn btn-block btn-secondary btn_depara" type="button" data-toggle="collapse" data-target="#collapseDePara<?php echo $k; ?>" aria-expanded="false" aria-controls="collapseDePara">
                                Fazer De -> Para <i class="fas fa-chevron-down ml-3"></i>
                            </button>
                            <!-- <a href="<?php echo $url_findProduct; ?><?php echo $identificacao_produto ?>/<?php echo $cotacao['cliente']['id']; ?>/<?php echo $produto['cotado']['cd_produto_comprador']; ?>" data-depara="" class="btn btn-block btn-danger">Fazer De -> Para</a> -->
                        </div>
                    </div>
                </div>

                <!-- De Para -->

                <div id="collapseDePara<?php echo $k; ?>" class="collapse">
                    <div class="col-12 mt-2 text-right">
                        <a href id="btnCombinar<?php echo $k; ?>" style="position: relative;z-index:1;width:100px;height: 40px;right: 40px;top: 11px;" title="Combinar Produtos" class="btn btn-primary" data-original-title="Combinar Produtos">
                            <i style="font-size:20px;padding-top: 3px;" class="fas fa-random"></i>
                        </a>
                    </div>

                    <form>
                        <div class="card">
                            <div class="card-header">
                            </div>
                            <div class="card-body">
                                <div class="table" style="margin-top: -110px;">
                                    <table id="data-tableDePara<?php echo $k; ?>" class="table w-100 table-hover" data-url="<?php echo $datatables; ?>" data-url2="<?php echo $url_combinar; ?>" data-sintese="<?php echo $identificacao_produto; ?>">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Código</th>
                                                <th>Produto</th>
                                                <th>Marca</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>''
                </div>
            </div>
        <?php endif; ?>

    <?php endforeach; ?>
<?php endif; ?>