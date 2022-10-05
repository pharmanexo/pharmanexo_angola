<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">

    <div class="content-fluid">
        <div class="content__inner">
            <div class="row">

                <!-- Relatorio cotações automaticas -->
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <h4 class="card-title">Ultimos registros de cotação mix enviado</h4>
                            </h5>
                        </div>
                        <div class="card-body" style="height: 450px; overflow: scroll; ">
                            <table class="table table-hover">
                                <thead>
                                    <tr> 
                                        <th>Comprador</th>
                                        <th>Cotação</th>
                                        <th>Data</th>
                                    </tr>
                                </thead>
                               <tbody>
                                    <?php foreach($relatorioMixPorComprador as $row): ?>


                                        <?php $ultima_atualizacao = (isset($row['data_criacao']) && !empty($row['data_criacao'])) ? date('Y-m-d', strtotime($row['data_criacao'])) : '' ?>
                                        <?php $resultado = strtotime(date('Y-m-d')) - strtotime($ultima_atualizacao); ?>

                                        <tr class="<?php echo (floor($resultado / (60 * 60 * 24)) == 0) ? '' : 'table-danger' ?>">
                                            <td><?php echo $row['razao_social']; ?></td>
                                            <td class="text-nowrap"><?php echo $row['cd_cotacao']; ?></td>
                                            <td><?php echo date('d/m/Y H:i:s', strtotime($row['data_criacao'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                               </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Relatorio cotaçoes manuais -->
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-header" >
                            <h5 class="mb-0">
                                <h4 class="card-title">Acionamentos do MIX do dia</h4>
                            </h5>
                        </div>
                        <div class="card-body" style="height: 450px; overflow: scroll; ">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Cotação</th>
                                        <th>Comprador</th>
                                        <th>Data</th>
                                    </tr>
                                </thead>
                               <tbody>
                                   <?php if( isset($relatorioAcionamentos) && !empty($relatorioAcionamentos) ): ?>
                                       <?php foreach($relatorioAcionamentos as $row): ?>
                                            <tr>
                                                <td><?php echo $row['cd_cotacao']; ?></td>
                                                <td><?php echo $row['razao_social']; ?></td>
                                                <td><?php echo date('d/m/Y H:i:s', strtotime($row['data_criacao'])) ?></td>
                                            </tr>
                                       <?php endforeach; ?>
                                   <?php else: ?>
                                        <tr><td colspan="3">Nenhum reigstro encontrado</td></tr>
                                   <?php endif; ?>
                               </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div id="accordion">
                    
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Cotações em aberto de CASA DE SAUDE SANTA MARCELINA
                                </button>
                            </h5>
                        </div>

                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                <div class="card-body" style="height: 450px; overflow: scroll; ">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr> 
                                                <th>Cotação</th>
                                                <th>Fornecedor</th>
                                                <th>Data Inicio</th>
                                                <th>Data Término</th>
                                            </tr>
                                        </thead>
                                       <tbody>
                                            <?php if( isset($relatorio1) && !empty($relatorio1) ): ?>
                                                <?php foreach($relatorio1 as $row): ?>

                                                    <tr>
                                                        <td class="text-nowrap"><?php echo $row['cd_cotacao']; ?></td>
                                                        <td><?php echo $row['nome_fantasia']; ?></td>
                                                        <td><?php echo date('d/m/Y H:i:s', strtotime($row['dt_inicio_cotacao'])) ?></td>
                                                        <td><?php echo date('d/m/Y H:i:s', strtotime($row['dt_fim_cotacao'])) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="">Nenhum registro encontrado</td>
                                                </tr>
                                            <?php endif; ?>
                                       </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Cotações em aberto de UNIMED DE FEIRA DE SANTANA
                                </button>
                            </h5>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                            <div class="card-body">
                                <div class="card-body" style="height: 450px; overflow: scroll; ">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr> 
                                                <th>Cotação</th>
                                                <th>Fornecedor</th>
                                                <th>Data Inicio</th>
                                                <th>Data Término</th>
                                            </tr>
                                        </thead>
                                       <tbody>
                                            <?php if( isset($relatorio2) && !empty($relatorio2) ): ?>
                                                <?php foreach($relatorio2 as $row): ?>

                                                    <tr>
                                                        <td class="text-nowrap"><?php echo $row['cd_cotacao']; ?></td>
                                                        <td><?php echo $row['nome_fantasia']; ?></td>
                                                        <td><?php echo date('d/m/Y H:i:s', strtotime($row['dt_inicio_cotacao'])) ?></td>
                                                        <td><?php echo date('d/m/Y H:i:s', strtotime($row['dt_fim_cotacao'])) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="4">Nenhum registro encontrado</td>
                                                </tr>
                                            <?php endif; ?>
                                       </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header" id="headingThree">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Cotações em aberto de INSTITUTO DE CANCER DE LONDRINA
                                </button>
                            </h5>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                            <div class="card-body">
                                <div class="card-body" style="height: 450px; overflow: scroll; ">
                                    <table class="table table-hover table-sm">
                                        <thead>
                                            <tr> 
                                                <th>Cotação</th>
                                                <th>Fornecedor</th>
                                                <th>Data Inicio</th>
                                                <th>Data Término</th>
                                            </tr>
                                        </thead>
                                       <tbody>
                                            <?php if( isset($relatorio3) && !empty($relatorio3) ): ?>
                                                <?php foreach($relatorio3 as $row): ?>
                                                    <tr>
                                                        <td class="text-nowrap"><?php echo $row['cd_cotacao']; ?></td>
                                                        <td><?php echo $row['nome_fantasia']; ?></td>
                                                        <td><?php echo date('d/m/Y H:i:s', strtotime($row['dt_inicio_cotacao'])) ?></td>
                                                        <td><?php echo date('d/m/Y H:i:s', strtotime($row['dt_fim_cotacao'])) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="4">Nenhum registro encontrado</td>
                                                </tr>
                                            <?php endif; ?>
                                       </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

        </div>
    </div>
</body>


<?php echo $scripts; ?>

<script>
    $(function() {
       
       setInterval(function(){ location.reload(); }, 60000);
        
    });
</script>

</html>
