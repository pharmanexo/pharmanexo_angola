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

                <div class="card-header">

                    <p class="text-muted border-bottom"><strong>Dados do Comprador</strong></p>
                    <div class="row mt-2">
                        <div class="col-12 col-lg-4">
                            <strong>CNPJ</strong> <br>
                            <?php if (isset($comprador['cnpj'])) echo $comprador['cnpj']; ?>
                        </div>
                        <div class="col-12 col-lg-4">
                            <strong>Razão Social</strong> <br>
                            <?php if (isset($comprador['razao_social'])) echo $comprador['razao_social']; ?>
                        </div>
                        <div class="col-12 col-lg-4">
                            <strong>Cidade/UF</strong> <br>
                            <?php if (isset($comprador['cidade'])) echo $comprador['cidade']; ?><?php if (isset($comprador['estado'])) echo  '/' . $comprador['estado']; ?>
                        </div>
                    </div>

                    <p class="text-muted mt-3 border-bottom"><strong>Dados da Cotação</strong></p>
                    <div class="row mt-2">
                        <div class="col-12 col-lg-4 text-left">
                            <strong>Descrição</strong> <br>
                            <?php if (isset($cotacao_sintese['ds_cotacao'])) echo $cotacao_sintese['ds_cotacao']; ?>
                        </div>
                        <div class="col-12 col-lg-2">
                            <strong>Data Inicio Cotação</strong> <br>
                            <?php if (isset($cotacao_sintese['dt_inicio_cotacao'])) echo date('d/m/Y H:i', strtotime($cotacao_sintese['dt_inicio_cotacao'])); ?>
                        </div>
                        <div class="col-12 col-lg-2">
                            <strong>Data Fim Cotação</strong> <br>
                            <?php if (isset($cotacao_sintese['dt_fim_cotacao'])) echo date('d/m/Y H:i', strtotime($cotacao_sintese['dt_fim_cotacao'])); ?>
                        </div>
                        <div class="col-12 col-lg-2">
                            <strong>UF Cotação</strong> <br>
                            <?php if (isset($cotacao_sintese['uf_cotacao'])) echo $cotacao_sintese['uf_cotacao']; ?>
                        </div>
                        <div class="col-12 col-lg-2">
                           
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                         <li class="nav-item">
                            <a class="nav-link active" id="info_mix_tab" data-toggle="tab" href="#info_mix" role="tab" aria-controls="info_mix" aria-selected="true">Informações Ofertas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="produtos_enviados_tab" data-toggle="tab" href="#produtos_enviados" role="tab" aria-controls="produtos_enviados" aria-selected="true">Produtos Aprovados</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="analitico_aprovados-tab" data-toggle="tab" href="#analitico_aprovados" role="tab" aria-controls="analitico_aprovados" aria-selected="false">Analítico Aprovados</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="produtos_rejeitados_tab" data-toggle="tab" href="#produtos_rejeitados" role="tab" aria-controls="produtos_rejeitados" aria-selected="false">Produtos Rejeitados</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="analitico_rejeitado-tab" data-toggle="tab" href="#analitico_rejeitado" role="tab" aria-controls="analitico_rejeitado" aria-selected="false">Analítico Rejeitados</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">

                        <div class="tab-pane show active" id="info_mix" role="tabpanel" aria-labelledby="info_mix_tab"> 
                           
                            <div class="card">
                                
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col">

                                            <p class="text-muted border-bottom"># Mix</p>
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                    <tr>
                                                        <td class="text-left"><b>Data do acionamento</b></td>
                                                        <td class="text-left"><b>Quantidade de produtos enviados na oferta MIX</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php if( isset($cotacao['data_criacao']) ) echo date('d/m/Y H:i', strtotime($cotacao['data_criacao'])); ?></td>
                                                        <td><?php if( isset($total_mix) ) echo $total_mix; ?></td>    
                                                    </tr>
                                                </thead>
                                            </table>

                                            <br>

                                            <p class="text-muted border-bottom"># Produtos X Estoque</p>
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                    <?php if( isset($total_produto_estoque) ): ?>
                                                        <?php foreach($total_produto_estoque as $row): ?>
                                                                <tr>
                                                                    <td class="text-left"><b>Fornecedor</b></td>
                                                                    <td class="text-left"><b>Quantidade de produtos na cotação SINTESE</b></td>
                                                                    <td class="text-left"><b>Quantidade de produtos com estoque</b></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><?php if( isset($row['fornecedor']) ) echo $row['fornecedor']; ?></td>
                                                                    <td><?php if( isset($row['total_sintese']) ) echo $row['total_sintese']; ?></td>
                                                                    <td><?php if( isset($row['total_com_estoque']) ) echo $row['total_com_estoque']; ?></td>    
                                                                </tr>
                                                           
                                                         <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </thead>
                                            </table>


                                            <br>

                                            <p class="text-muted border-bottom"># Envios</p>
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                    <?php if( isset($envios_cot) ): ?>

                                                        <?php foreach($envios_cot as $row): ?>

                                                            <tr>
                                                                <td class="text-left"><b>Fornecedor</b></td>
                                                                <td class="text-left"><b>Data do envio Manual</b></td>
                                                                <td class="text-left"><b>Data do envio Automático</b></td>
                                                                <td class="text-left"><b>Data do envio Mix</b></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-left">
                                                                    <?php echo $row['fornecedor'] ?>
                                                                </td>
                                                                <td class="text-left">
                                                                    <?php if( !empty($row['data_manual']) ) echo date("d/m/Y H:i:s", strtotime($row['data_manual'])); ?>
                                                                </td>
                                                                <td class="text-left">
                                                                    <?php if( !empty($row['data_aut']) ) echo date("d/m/Y H:i:s", strtotime($row['data_aut'])); ?>
                                                                </td>
                                                                <td class="text-left">
                                                                    <?php if( !empty($row['data_mix']) ) echo date("d/m/Y H:i:s", strtotime($row['data_mix'])); ?>
                                                                </td>
                                                            </tr>

                                                        <?php endforeach; ?>

                                                    <?php endif; ?>
                                                </thead>
                                            </table>

                                            <br>

                                            <p class="text-muted border-bottom"># Total de itens respondidos</p>
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                    <?php if( isset($relatorioQtdItensCotacao) ): ?>

                                                        <?php foreach($relatorioQtdItensCotacao as $row): ?>

                                                            <tr>
                                                                <td class="text-left"><b>Fornecedor</b></td>
                                                                <td class="text-left"><b>Manual</b></td>
                                                                <td class="text-left"><b>Automático</b></td>
                                                                <td class="text-left"><b>Mix</b></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-left">
                                                                    <?php echo $row['fornecedor'] ?>
                                                                </td>
                                                                <td class="text-left">
                                                                    <?php  echo $row['total_manual']; ?>
                                                                </td>
                                                                <td class="text-left">
                                                                    <?php  echo $row['total_automatica']; ?>
                                                                </td>
                                                                <td class="text-left">
                                                                    <?php  echo $row['total_mix']; ?>
                                                                </td>
                                                            </tr>

                                                        <?php endforeach; ?>

                                                    <?php endif; ?>
                                                </thead>
                                            </table>

                                            <br>

                                            <p class="text-muted border-bottom"># Valor total cotado</p>
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                    <?php if( isset($relatorioValorRespondido) && !empty($relatorioValorRespondido) ): ?>

                                                        <tr>
                                                            <td class="text-left"><b>Fornecedor</b></td>
                                                            <td class="text-left"><b>Manual</b></td>
                                                            <td class="text-left"><b>Automático</b></td>
                                                            <td class="text-left"><b>Mix</b></td>
                                                        </tr>

                                                        <?php foreach($relatorioValorRespondido as $row): ?>

                                                            <tr>
                                                                <td class="text-left">
                                                                    <?php echo $row['fornecedor'] ?>
                                                                </td>
                                                                <td class="text-left">
                                                                    R$<?php echo number_format($row['total_manual'], 4, ',', '.'); ?>
                                                                </td>
                                                                <td class="text-left">
                                                                    R$<?php echo number_format($row['total_automatica'], 4, ',', '.'); ?>
                                                                </td>
                                                                <td class="text-left">
                                                                    R$<?php echo number_format($row['total_mix'], 4, ',', '.'); ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="4">
                                                                    <b>Valor Total: </b>
                                                                    <?php $total = $row['total_manual'] + $row['total_automatica'] + $row['total_mix']; ?>

                                                                    <?php echo number_format($total, 4, ',', '.'); ?>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>

                                                    <?php endif; ?>
                                                </thead>
                                            </table>

                                            <p class="text-muted border-bottom"># Ordens de Compra</p>
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                    <?php if( isset($ordens_compra) ): ?>

                                                        <?php foreach($ordens_compra as $row): ?>

                                                            <tr>
                                                                <td class="text-left"><b>Fornecedor</b></td>
                                                                <td class="text-left"><b>Código OC</b></td>
                                                                <td class="text-left"><b>Pendente</b></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-left">
                                                                    <?php echo $row['fornecedor'] ?>
                                                                </td>
                                                                <td class="text-left"> <?php if( isset($row['oc']) ) echo $row['oc']['Cd_Ordem_Compra']; ?> </td>
                                                                <td class="text-left"> <?php if( isset($row['oc']) ) echo ( $row['oc']['pendente'] == 1 ) ? 'Sim' : 'Não'  ?> </td>
                                                            </tr>

                                                        <?php endforeach; ?>

                                                    <?php endif; ?>
                                                </thead>
                                            </table>

                                        </div>
                                    </div>
                                    
                                </div>

                            </div>
                        </div>

                        <div class="tab-pane fade" id="produtos_enviados" role="tabpanel" aria-labelledby="produtos_enviados_tab">
                            <div class="table-responsive">
                                <table id="data-table-aprovados" class="table table-condensed table-hover no-filtered">
                                    <thead>
                                        <tr>
                                            <th>Produto Concorrente</th>
                                            <th></th>
                                            <th></th>
                                            <th>Marca Concorrente</th>
                                            <th>Preço Concorrente (R$)</th>
                                            <th class="text-center">Qtde. Solicitada</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($produtos_cotacao['enviados']) && !empty($produtos_cotacao['enviados'])): ?>

                                            <?php foreach($produtos_cotacao['enviados'] as $produto): ?>
                                                <tr>
                                                    <td class="text-nowrap">
                                                        <?php echo (!empty($produto['complemento_produto_marca'])) ?
                                                            $produto['ds_produto_marca'] . ' - ' . $produto['complemento_produto_marca'] :
                                                            $produto['ds_produto_marca']; 
                                                        ?>
                                                        <br>
                                                        <small>
                                                            <b>Produto Ofertado: </b>
                                                            <br>
                                                            <?php echo $produto['enviado'][0]['produto']; ?>
                                                        </small>
                                                    </td>
                                                    <td class="text-nowrap">
                                                        <br>
                                                        <small>
                                                            <b>Fornecedor: </b>
                                                            <br>
                                                            <?php echo date("d/m/Y H:i", strtotime($produto['enviado'][0]['data_criacao'])); ?>
                                                        </small>
                                                    </td>
                                                    <td class="text-nowrap">
                                                        <br>
                                                        <small>
                                                            <b>Data de envio: </b>
                                                            <br>
                                                            <?php echo date("d/m/Y H:i", strtotime($produto['enviado'][0]['data_criacao'])); ?>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <?php echo $produto['ds_marca']; ?>
                                                        <br>
                                                        <small>
                                                            <b>Marca Ofertado: </b>
                                                            <br>
                                                            <?php echo $produto['enviado'][0]['marca']; ?>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <?php echo number_format($produto['vl_preco_produto'], 4, ',', '.'); ?>
                                                        <br>
                                                        <small>
                                                            <b>Preço ofertado:</b> 
                                                            <br>
                                                            <?php echo number_format($produto['enviado'][0]['preco_marca'], 4, ',', '.'); ?>
                                                        </small>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo $produto['qtd_solicitada']; ?>
                                                        <br>
                                                        <small>
                                                            <b>Quantidade ofertada: </b>
                                                            <br>
                                                            <?php echo $produto['enviado'][0]['qtd_solicitada']; ?>
                                                        </small>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>

                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="produtos_rejeitados" role="tabpanel" aria-labelledby="produtos_rejeitados_tab">
                            <div class="table-responsive">
                                <table id="data-table-rejeitados" class="table table-condensed table-hover no-filtered">
                                    <thead>
                                        <tr>
                                            <th>Produto</th>
                                            <th>Marca Oferta</th>
                                            <th>Preço Oferta (R$)</th>
                                            <th class="text-center">Qtde. Solicitada</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($produtos_cotacao['rejeitados']) && !empty($produtos_cotacao['rejeitados'])): ?>
                                            <?php foreach($produtos_cotacao['rejeitados'] as $produto): ?>
                                                <tr>
                                                    <td class="text-nowrap">
                                                        <?php echo (!empty($produto['complemento_produto_marca'])) ?
                                                            $produto['ds_produto_marca'] . ' - ' . $produto['complemento_produto_marca'] :
                                                            $produto['ds_produto_marca']; 
                                                        ?>
                                                    </td>
                                                    <td><?php echo $produto['ds_marca']; ?></td>
                                                    <td><?php echo number_format($produto['vl_preco_produto'], 4, ',', '.'); ?></td>
                                                    <td class="text-center"><?php echo $produto['qtd_solicitada']; ?></td>
                                                 </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="analitico_aprovados" role="tabpanel" aria-labelledby="analitico_aprovados-tab"> 
                           
                            <div class="accordion" id="accordionProdutosAprovados">

                                <div class="card mb-0">
                                    <div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <h5 class="mb-0 text-muted">
                                             Detalhes por produto
                                        </h5>
                                    </div>

                                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionProdutosAprovados">
                                        <div class="card-body">

                                            <?php if( isset($relatorioMixAprovados) && !empty($relatorioMixAprovados) ): ?>

                                                <div class="accordion" id="accordionProdutos">

                                                    <?php foreach($relatorioMixAprovados['produtos'] as $k => $row): ?>

                                                        <div class="card mb-0 <?php if($k != 0) echo 'mt-3' ?>">

                                                            <div class="card-header" id="headingProduto_<?php echo $k; ?>" data-toggle="collapse" data-target="#collapseProduto_<?php echo $k; ?>" aria-expanded="true" aria-controls="collapseProduto_<?php echo $k; ?>">
                                                                <h5 class="mb-0 text-muted">
                                                                    <?php echo $row['ds_produto_marca']; ?>
                                                                    <br>
                                                                    <small><strong>Marca:</strong> <?php echo $row['ds_marca']; ?></small>
                                                                </h5>
                                                            </div>

                                                            <div id="collapseProduto_<?php echo $k; ?>" class="collapse" aria-labelledby="headingProduto_<?php echo $k; ?>" data-parent="#accordionProdutos">
                                                                <div class="card-body">

                                                                    <table class="table table-hover table-bordered">

                                                                    <?php if( isset($row['restricoes']) ): ?>

                                                                        <tr><th colspan="3">RESTRIÇÕES</th> </tr>
                                                                        <tr class="table-secondary">
                                                                            <th>Produto</th>
                                                                            <th>Fornecedor</th>
                                                                            <th>Tipo Marca</th>
                                                                        </tr>

                                                                        <?php foreach($row['restricoes'] as $p): ?>
                                                                            <tr>
                                                                                <td> <?php echo "<strong>Cód:</strong> " . $p['codigo'] . ' - ' . $p['produto_pharmanexo']; ?> </td>
                                                                                <td><small><?php echo $p['fornecedor']; ?></small></td>
                                                                                <td class="text-nowrap"><small><?php echo $p['tipo']; ?></small></td>
                                                                            </tr>
                                                                        <?php endforeach; ?>
                                                                    <?php endif; ?>
                                                                    <?php if( isset($row['sem_preco']) ): ?>

                                                                        <tr><th colspan="3">SEM PREÇO</th> </tr>
                                                                        <tr class="table-secondary">
                                                                            <th>Produto</th>
                                                                            <th>Fornecedor</th>
                                                                            <th>Tipo Marca</th>
                                                                        </tr>

                                                                        <?php foreach($row['sem_preco'] as $p): ?>
                                                                            <tr>
                                                                                <td> <?php echo "<strong>Cód:</strong> " . $p['codigo'] . ' - ' . $p['produto_pharmanexo']; ?> </td>
                                                                                <td><small><?php echo $p['fornecedor']; ?></small></td>
                                                                                <td class="text-nowrap"><small><?php echo $p['tipo']; ?></small></td>
                                                                            </tr>
                                                                        <?php endforeach; ?>
                                                                    <?php endif; ?>
                                                                    <?php if( isset($row['sem_estoque']) ): ?>

                                                                        <tr><th colspan="3">SEM ESTOQUE</th> </tr>
                                                                        <tr class="table-secondary">
                                                                            <th>Produto</th>
                                                                            <th>Fornecedor</th>
                                                                            <th>Tipo Marca</th>
                                                                        </tr>

                                                                        <?php foreach($row['sem_estoque'] as $p): ?>
                                                                            <tr>
                                                                                <td> <?php echo "<strong>Cód:</strong> " . $p['codigo'] . ' - ' . $p['produto_pharmanexo']; ?> </td>
                                                                                <td><small><?php echo $p['fornecedor']; ?></small></td>
                                                                                <td class="text-nowrap"><small><?php echo $p['tipo']; ?></small></td>
                                                                            </tr>
                                                                        <?php endforeach; ?>
                                                                    <?php endif; ?>
                                                                    <?php if( isset($row['rejeitadosPorPreço']) ): ?>

                                                                        <tr><th colspan="3">REJEITADO POR PREÇO</th> </tr>
                                                                        <tr class="table-secondary">
                                                                            <th>Preço Concorrente</th>
                                                                            <th>Preço Ofertado</th> 
                                                                            <th>Tipo da Marca</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><?php echo number_format($row['rejeitadosPorPreço']['vl_preco_produto'], 4, ',', '.'); ?></td>
                                                                            <td><?php echo number_format($row['rejeitadosPorPreço']['preco_final'], 4, ',', '.'); ?></td>
                                                                            <td><?php echo $row['rejeitadosPorPreço']['tipo'] ?></td>
                                                                        </tr>

                                                                    <?php endif; ?>
                                                                    </table>

                                                                </div>
                                                            </div>
                                                        </div>


                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-0 mt-2">
                                    <div class="card-header" id="headingThree" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                        <h5 class="mb-0 text-muted">
                                             Numero de itens revertidos
                                        </h5>
                                    </div>

                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionProdutosAprovados">
                                        <div class="card-body">
                                            
                                            <div class="row">
                                                <div class="col">
                                                    <?php if( isset($relatorioProdutosRevertidos) ): ?>

                                                        <table class="table table-hover table-bordered">
                                                        <?php foreach($relatorioProdutosRevertidos as $row ): ?>

                                                            <thead>
                                                                <tr>
                                                                    <td><b>Fornecedor</b></td>
                                                                    <td><b>Quantidade</b></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-left"><?php echo $row['fornecedor']; ?> </td>
                                                                    <td class="text-let"><?php echo $row['total']; ?> </td>
                                                                </tr>


                                                            </thead>
                                                        <?php endforeach; ?>
                                                        </table>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="analitico_rejeitado" role="tabpanel" aria-labelledby="analitico_rejeitado-tab">
                            <div class="accordion" id="accordionProdutosRejeitados">

                                <div class="card">
                                    <div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <h5 class="mb-0 text-muted">
                                             Detalhes por produto
                                        </h5>
                                    </div>

                                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionProdutosRejeitados">
                                        <div class="card-body">

                                            <?php if( isset($relatorioMixRejeitados) && !empty($relatorioMixRejeitados) ): ?>

                                                <div class="accordion" id="accordionProdutos">

                                                    <?php foreach($relatorioMixRejeitados['produtos'] as $k => $row): ?>

                                                        <div class="card mb-0 <?php if($k != 0) echo 'mt-3' ?>">

                                                            <div class="card-header" id="headingProduto_<?php echo $k; ?>" data-toggle="collapse" data-target="#collapseProduto_<?php echo $k; ?>" aria-expanded="true" aria-controls="collapseProduto_<?php echo $k; ?>">
                                                                <h5 class="mb-0 text-muted">
                                                                    <?php echo $row['ds_produto_marca']; ?>
                                                                    <br>
                                                                    <small><strong>Marca:</strong> <?php echo $row['ds_marca']; ?></small>
                                                                </h5>
                                                            </div>

                                                            <div id="collapseProduto_<?php echo $k; ?>" class="collapse" aria-labelledby="headingProduto_<?php echo $k; ?>" data-parent="#accordionProdutos">
                                                                <div class="card-body">

                                                                    <table class="table table-hover table-bordered">

                                                                    <?php if( isset($row['sem_depara']) ): ?>

                                                                        <tr><th colspan="3">SEM DE -> PARA</th> </tr>
                                                                        <tr class="table-secondary">
                                                                            <th>Fornecedor</th>
                                                                        </tr>

                                                                        <?php foreach($row['sem_depara'] as $p): ?>
                                                                            <tr>
                                                                                <td><small><?php echo $p['fornecedor']; ?></small></td>
                                                                            </tr>
                                                                        <?php endforeach; ?>
                                                                    <?php endif; ?>
                                                                    <?php if( isset($row['restricoes']) ): ?>

                                                                        <tr><th colspan="3">RESTRIÇÕES</th> </tr>
                                                                        <tr class="table-secondary">
                                                                            <th>Produto</th>
                                                                            <th>Fornecedor</th>
                                                                            <th>Tipo Marca</th>
                                                                        </tr>

                                                                        <?php foreach($row['restricoes'] as $p): ?>
                                                                            <tr>
                                                                                <td> <?php echo "<strong>Cód:</strong> " . $p['codigo'] . ' - ' . $p['produto_pharmanexo']; ?> </td>
                                                                                <td><small><?php echo $p['fornecedor']; ?></small></td>
                                                                                <td class="text-nowrap"><small><?php echo $p['tipo']; ?></small></td>
                                                                            </tr>
                                                                        <?php endforeach; ?>
                                                                    <?php endif; ?>
                                                                    <?php if( isset($row['sem_preco']) ): ?>

                                                                        <tr><th colspan="3">SEM PREÇO</th> </tr>
                                                                        <tr class="table-secondary">
                                                                            <th>Produto</th>
                                                                            <th>Fornecedor</th>
                                                                            <th>Tipo Marca</th>
                                                                        </tr>

                                                                        <?php foreach($row['sem_preco'] as $p): ?>
                                                                            <tr>
                                                                                <td> <?php echo "<strong>Cód:</strong> " . $p['codigo'] . ' - ' . $p['produto_pharmanexo']; ?> </td>
                                                                                <td><small><?php echo $p['fornecedor']; ?></small></td>
                                                                                <td class="text-nowrap"><small><?php echo $p['tipo']; ?></small></td>
                                                                            </tr>
                                                                        <?php endforeach; ?>
                                                                    <?php endif; ?>
                                                                    <?php if( isset($row['sem_estoque']) ): ?>

                                                                        <tr><th colspan="3">SEM ESTOQUE</th> </tr>
                                                                        <tr class="table-secondary">
                                                                            <th>Produto</th>
                                                                            <th>Fornecedor</th>
                                                                            <th>Tipo Marca</th>
                                                                        </tr>

                                                                        <?php foreach($row['sem_estoque'] as $p): ?>
                                                                            <tr>
                                                                                <td> <?php echo "<strong>Cód:</strong> " . $p['codigo'] . ' - ' . $p['produto_pharmanexo']; ?> </td>
                                                                                <td><small><?php echo $p['fornecedor']; ?></small></td>
                                                                                <td class="text-nowrap"><small><?php echo $p['tipo']; ?></small></td>
                                                                            </tr>
                                                                        <?php endforeach; ?>
                                                                    <?php endif; ?>
                                                                    <?php if( isset($row['rejeitadosPorPreço']) ): ?>

                                                                        <tr><th colspan="3">REJEITADO POR PREÇO</th> </tr>
                                                                        <tr class="table-secondary">
                                                                            <th>Preço Concorrente</th>
                                                                            <th>Preço Ofertado</th> 
                                                                            <th>Tipo da Marca</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><?php echo number_format($row['rejeitadosPorPreço']['vl_preco_produto'], 4, ',', '.'); ?></td>
                                                                            <td><?php echo number_format($row['rejeitadosPorPreço']['preco_final'], 4, ',', '.'); ?></td>
                                                                            <td><?php echo $row['rejeitadosPorPreço']['tipo'] ?></td>
                                                                        </tr>

                                                                    <?php endif; ?>
                                                                    </table>

                                                                </div>
                                                            </div>
                                                        </div>


                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>

                                        </div>
                                    </div>
                                </div>

                               <!--  <div class="card">
                                    <div class="card-header" id="headingTwo" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        <h6 class="mb-0 text-muted">
                                            Produtos Rejeitados por preço
                                        </h6>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                        <div class="card-body">


                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header" id="headingThree">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                Collapsible Group Item #3
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                        <div class="card-body">


                                        </div>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo $scripts; ?>

    <script>

        var url_update_aprovado = "<?php echo ''; ?>";

        var url_grafico_aprovado1 = "<?php if(isset($url_grafico_aprovado1)) echo $url_grafico_aprovado1; ?>";
        var dados_grafico_aprovado1 = [];

        var url_grafico_aprovado2 = "<?php if(isset($url_grafico_aprovado2)) echo $url_grafico_aprovado2; ?>";;
        var dados_grafico_aprovado2 = [];

        // var url_grafico_rejeitado1 = "<?php if(isset($url_grafico_rejeitado1)) echo $url_grafico_rejeitado1; ?>";
        // var url_grafico_rejeitado2 = "<?php if(isset($url_grafico_rejeitado2)) echo $url_grafico_rejeitado2; ?>";

        $(function() {

            google.charts.load('current', {'packages':['corechart']});

            var dt_aprovados = $('#data-table-aprovados').DataTable({
                processing: true,
                serverSide: false,
                columns: [
                    null,
                    null,
                    null,
                    null,
                    null
                ],
                rowCallback: function(row, data) {},
                drawCallback: function() {}
            });

            var dt_rejeitados = $('#data-table-rejeitados').DataTable({
                processing: true,
                serverSide: false,
                columns: [
                    null,
                    null,
                    null,
                    null
                ],
                rowCallback: function(row, data) {
                },
                drawCallback: function() {}
            });

            setTimeout(function() { 

                $.post(url_grafico_aprovado1, function (xhr) {
                    dados_grafico_aprovado1 = xhr;
                    google.charts.setOnLoadCallback(drawChartAprovado1);
                });

                $.post(url_grafico_aprovado2, function (xhr) {
                    dados_grafico_aprovado2 = xhr;
                    google.charts.setOnLoadCallback(drawChartAprovado2);
                });

            }, 1500);
        });

        function drawChartAprovado1() {

            var data = google.visualization.arrayToDataTable(dados_grafico_aprovado1);

            var options = {
                title: 'Total Enviado',
                width: 650,
                height: 300,
            };

            var chartAprovado1 = new google.visualization.PieChart(document.getElementById('chartAprovado1'));
            chartAprovado1.draw(data, options);
        } 

        function drawChartAprovado2() {

            var data = google.visualization.arrayToDataTable(dados_grafico_aprovado2);

            var options = {
                title: 'Total Cotado',
                width: 650,
                height: 300,
            };

            var chartAprovado2 = new google.visualization.PieChart(document.getElementById('chartAprovado2'));
            chartAprovado2.draw(data, options);
        }
    </script>
</body>

</html>
