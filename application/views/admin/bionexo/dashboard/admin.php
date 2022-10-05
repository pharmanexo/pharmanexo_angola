<!DOCTYPE html>
<html lang="pt-BR">
<?php echo $header; ?>
<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>
<div class="content">
    <?php if (isset($meta)) { ?>
        <div class="row">
            <?php foreach ($meta as $k => $item) { ?>
                <div class="col-6">
                    <div class="card  <?php if ($item['dia'] >= META_DEPARA) echo 'border-success' ?>">
                        <div class="card-body">
                            <p><strong><?php echo $item['nome']; ?></strong></p>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar"
                                     style="width: <?php echo ($item['dia'] * 100) / META_DEPARA; ?>%; color: #000; "
                                     role="progressbar" aria-valuenow="<?php echo $item['dia']; ?>"
                                     aria-valuemin="<?php echo META_DEPARA; ?>"
                                     aria-valuemax="600"><span style="padding-left: 20px"><?php echo $item['dia'] . "/" . META_DEPARA; ?></span></div>
                            </div>
                            <table class="table table-bordered">
                                <tr>
                                    <td class="text-center">
                                        <p><strong>Dia</strong></p>
                                        <p><?php echo $item['dia']; ?>/<?php echo META_DEPARA; ?> Produtos</p>
                                    </td>
                                    <td class="text-center">
                                        <p><strong>Mês</strong></p>
                                        <p><?php echo $item['total']; ?> Produtos</p>
                                    </td>
                                    <td class="text-center">
                                        <p><strong>Com de/para</strong></p>
                                        <p><?php echo $item['total_hosp']; ?> Hospitais</p>
                                    </td>
                                </tr>
                            </table>
                            <div id="accordion">
                                <div class="card">
                                    <div class="card-header" id="heading<?php echo $k; ?>">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link" data-toggle="collapse"
                                                    data-target="#collapse<?php echo $k; ?>" aria-expanded="true"
                                                    aria-controls="collapse<?php echo $k; ?>">
                                                Histórico Meta
                                            </button>
                                        </h5>
                                    </div>

                                    <div id="collapse<?php echo $k; ?>" class="collapse" aria-labelledby="heading<?php echo $k; ?>"
                                         data-parent="#accordion">
                                        <div class="card-body">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td>Mês</td>
                                                    <td>Total</td>
                                                </tr>
                                                <?php foreach ($item['historico'] as $hist) { ?>
                                                    <?php foreach ($hist as $h) { ?>
                                                        <tr>
                                                            <td><?php echo $h['mes_nome']; ?></td>
                                                            <td><?php echo $h['total']; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="accordion" >
                                <div class="card ">
                                    <div class="card-header" id="heading3<?php echo $k; ?>">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link" data-toggle="collapse"
                                                    data-target="#collapse3<?php echo $k; ?>" aria-expanded="true"
                                                    aria-controls="collapse3<?php echo $k; ?>">
                                                Hospitais Finalizados
                                            </button>
                                        </h5>
                                    </div>

                                    <div id="collapse3<?php echo $k; ?>" style="max-height: 300px; overflow-y: scroll" class="collapse" aria-labelledby="heading3<?php echo $k; ?>"
                                         data-parent="#accordion">
                                        <div class="card-body">
                                            <div class=" table-warning" style="width: 15px; height: 15px; border-radius: 20%; border: 1px solid; display: inline-block; "></div> Novos produto sem de/para
                                            <?php if (!empty($item['finalizados'])) { ?>
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <td>Nome Hospital</td>
                                                        <td>Estado</td>
                                                        <td>Itens Novos</td>
                                                       <!-- <td>De/para</td>
                                                        <td>Sem De/para</td>
                                                        <td>Ocultos</td>-->
                                                    </tr>
                                                    <?php foreach ($item['finalizados'] as $h) { ?>

                                                        <tr class="<?php if($h['novos'] > 0) echo 'table-warning'?>">
                                                            <td><?php echo $h['nome_fantasia']; ?></td>
                                                            <td><?php echo $h['estado']; ?></td>
                                                           <!-- <td><?php /*echo $h['com']; */?></td>
                                                            <td><?php /*echo ($h['sem'] - $h['ocultos']); */?></td>
                                                            <td><?php /*echo $h['ocultos']; */?></td>-->
                                                            <td><?php echo $h['novos']; ?></td>
                                                        </tr>

                                                    <?php } ?>
                                                </table>
                                            <?php }else{ ?>
                                                <p>Nenhum hospital em andamento</p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="accordion">
                                <div class="card ">
                                    <div class="card-header" id="heading1<?php echo $k; ?>">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link" data-toggle="collapse"
                                                    data-target="#collapse1<?php echo $k; ?>" aria-expanded="true"
                                                    aria-controls="collapse1<?php echo $k; ?>">
                                                Hospitais em andamento
                                            </button>
                                        </h5>
                                    </div>

                                    <div id="collapse1<?php echo $k; ?>" class="collapse show" aria-labelledby="heading1<?php echo $k; ?>"
                                         data-parent="#accordion">
                                        <div class="card-body">
                                            <?php if (!empty($item['hospitais'])) { ?>
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <td>Nome Hospital</td>
                                                        <td>Estado</td>
                                                        <td>De/para</td>
                                                        <td>Sem De/para</td>
                                                        <td>Ocultos</td>
                                                        <td></td>
                                                    </tr>
                                                    <?php foreach ($item['hospitais'] as $h) { ?>

                                                        <tr>
                                                            <td><?php echo $h['nome_fantasia']; ?></td>
                                                            <td><?php echo $h['estado']; ?></td>
                                                            <td><?php echo $h['com']; ?></td>
                                                            <td><?php echo ($h['sem'] - $h['ocultos']); ?></td>
                                                            <td><?php echo $h['ocultos']; ?></td>
                                                            <td><a data-toggle="tooltip" title="Finalizar de/para do hospital" href="<?php echo $urlClose . $h['id_cliente'] . '/' . $h['id_usuario']; ?>" class="btn btn-sm btn-outline-primary"><i class="far fa-stop-circle"></i></a></td>
                                                        </tr>

                                                    <?php } ?>
                                                </table>
                                            <?php }else{ ?>
                                                <p>Nenhum hospital em andamento</p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>

</div>

<?php echo $scripts; ?>
<script>


</script>

</body>
</html>
