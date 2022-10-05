<!DOCTYPE html>
<html lang="pt-BR">
<?php echo $header; ?>
<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>
<div class="content">


    <div class="row">
        <div class="col-6">
            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <p class="card-title">Seu progresso diário</p>
                        </div>
                        <div class="card-body">
                            <p>
                            <div class="progress" style="height: 20px; text-align: center">
                                <div class="progress-bar text-center" style="width: <?php echo $percent; ?>%; color: #000; " role="progressbar" aria-valuenow="<?php echo $n; ?>" aria-valuemin="320" aria-valuemax="600"> <?php echo $n . "/" . META_DEPARA; ?> </div>
                            </div>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <p class="card-title">Meta Diária</p>
                        </div>
                        <div class="card-body">
                            <p class="text-center"><?php echo $n; ?> de <?php echo META_DEPARA; ?> produtos</p>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <p class="card-title">Total mês</p>
                        </div>
                        <div class="card-body">
                            <p class="text-center"> <?php echo(isset($meta['total']) ? $meta['total'] : 0) ?> produtos</p>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <p class="card-title">Histórico</p>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <?php foreach ($history as $k => $h) { ?>
                                    <tr>
                                        <td colspan="2" class="text-center"><?php echo $k ?></td>
                                    </tr>
                                    <tr>
                                        <td>Mês</td>
                                        <td>Total</td>
                                    </tr>
                                    <?php foreach ($h as $m) { ?>
                                        <tr>
                                            <td><?php echo $m['mes_nome']; ?></td>
                                            <td><?php echo $m['total']; ?></td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <div class="col-6">

            <div id="accordion">
                <div class="card ">
                    <div class="card-header" id="heading1">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse"
                                    data-target="#collapse1" aria-expanded="true"
                                    aria-controls="collapse1">
                                Hospitais em andamento
                            </button>
                        </h5>
                    </div>

                    <div id="collapse1" class="collapse show" aria-labelledby="heading1"
                         data-parent="#accordion">
                        <div class="card-body">
                            <?php if (!empty($hospitais_a)) { ?>
                                <table class="table table-bordered">
                                    <tr>
                                        <td>Nome Hospital</td>
                                        <td>Estado</td>
                                        <td>De/para</td>
                                        <td>Sem De/para</td>
                                        <td>Ocultos</td>
                                        <td></td>
                                    </tr>
                                    <?php foreach ($hospitais_a as $h) { ?>

                                        <tr>
                                            <td><?php echo $h['nome_fantasia']; ?></td>
                                            <td><?php echo $h['estado']; ?></td>
                                            <td><?php if(isset($h['com'])) echo $h['com']; ?></td>
                                            <td><?php if(isset($h['sem'])) {
                                                    $v = ($h['sem'] - $h['ocultos']);


                                                    if ($v > 0){
                                                        echo $v;
                                                    }else{
                                                        echo '0';
                                                    }

                                                } ?></td>
                                            <td><?php if(isset($h['ocultos'])) echo $h['ocultos']; ?></td>
                                            <td><a data-toggle="tooltip" title="Abrir De/Para" href="<?php echo base_url("admin/bionexo/clientes/index_depara/".$h['id']); ?>" class="btn btn-sm btn-primary"><i class="fas fa-link"></i></a></td>
                                        </tr>

                                    <?php } ?>
                                </table>
                            <?php } else { ?>
                                <p>Nenhum hospital em andamento</p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div id="accordion">
                <div class="card ">
                    <div class="card-header" id="heading3">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse"
                                    data-target="#collapse3" aria-expanded="true"
                                    aria-controls="collapse3">
                                Hospitais Finalizados
                            </button>
                        </h5>
                    </div>

                    <div id="collapse3" style="max-height: 500px; overflow-y: scroll" class="collapse show" aria-labelledby="heading3"
                         data-parent="#accordion">
                        <div class="card-body">
                            <div class=" table-warning" style="width: 15px; height: 15px; border-radius: 20%; border: 1px solid; display: inline-block; "></div> Novos produto sem de/para
                            <?php if (!empty($hospitais_f)) { ?>
                                <table class="table table-bordered">
                                    <tr>
                                        <td>Nome Hospital</td>
                                        <td>Estado</td>
                                        <td>Itens Novos</td>
                                        <td></td>
                                        <!-- <td>De/para</td>
                                         <td>Sem De/para</td>
                                         <td>Ocultos</td>-->
                                    </tr>
                                    <?php foreach ($hospitais_f as $h) { ?>

                                        <tr class="<?php if ($h['novos'] > 0) echo 'table-warning' ?>">
                                            <td><?php echo $h['nome_fantasia']; ?></td>
                                            <td><?php echo $h['estado']; ?></td>
                                            <!-- <td><?php /*echo $h['com']; */ ?></td>
                                                            <td><?php /*echo ($h['sem'] - $h['ocultos']); */ ?></td>
                                                            <td><?php /*echo $h['ocultos']; */ ?></td>-->
                                            <td><?php echo $h['novos']; ?></td>
                                            <td><a data-toggle="tooltip" title="Abrir De/Para" href="<?php echo base_url("admin/bionexo/clientes/index_depara/".$h['id']."?upgrade=on"); ?>" class="btn btn-sm btn-primary"><i class="fas fa-link"></i></a></td>
                                        </tr>

                                    <?php } ?>
                                </table>
                            <?php } else { ?>
                                <p>Nenhum hospital finalizado</p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


</div>

<?php echo $scripts; ?>
<script>

</script>

</body>
</html>
