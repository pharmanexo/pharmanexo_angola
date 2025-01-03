<div class="modal fade" id="modalLog" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">Log de Envio Automático</h5>
            </div>

            <div class="modal-body">
                <div id="accordion">
                    <?php if (isset($logs) && !empty($logs)) { ?>
                        <?php foreach ($logs as $log) { ?>
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"
                                                aria-expanded="true" aria-controls="collapseOne">
                                            Data do
                                            envio: <?php echo date("d/m/Y H:i", strtotime($log['data_criacao'])); ?>
                                        </button>
                                    </h5>
                                </div>

                                <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                     data-parent="#accordion">
                                    <div class="card-body">
                                        <?php if (isset($log['logs']) && !empty($log['logs'])) { ?>
                                            <p>
                                                <strong>Mensagem do envio:</strong> </br>
                                                <?php if (isset($log['logs']['MSG'])) echo $log['logs']['MSG']; ?>
                                            </p>


                                            <?php if (isset($log['logs']['PRODS-COT'])) { ?>
                                                <?php foreach ($log['logs']['PRODS-COT'] as $prod) { ?>
                                                    <table class="table table-condensed table-bordered mb-3">
                                                        <tr>
                                                            <td class="table-secondary">
                                                                <?php echo "<p>ID SINTESE: {$prod['id_produto_sintese']} </br> Codigo Produto Comprador: {$prod['cd_produto_comprador']} </br> Descrição: {$prod['ds_produto_comprador']} </p> "; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <?php if (isset($prod['produtos_fornecedor'])) { ?>

                                                                    <?php foreach ($prod['produtos_fornecedor'] as $z => $prodForn) { ?>
                                                                        <p>
                                                                            Código: <?php echo $prodForn['codigo'] . " - " . $prodForn['nome_comercial']; ?></p>
                                                                        <?php if (isset($prodForn['restricao'])) { ?>
                                                                            <?php foreach ($prodForn['restricao'] as $k => $rest) { ?>
                                                                                <?php if ($rest == false) {
                                                                                    $rest = 'Falhou';
                                                                                }; ?>

                                                                                <p class="text-danger"><?php echo "{$k}: $rest"; ?></p>

                                                                            <?php } ?>
                                                                        <?php } else {
                                                                            foreach ($prodForn as $kk => $item){
                                                                                if (!is_array($item)){
                                                                                    echo $kk . ": " . $item . "</br>";
                                                                                }
                                                                            }


                                                                        } ?>
                                                                        <hr>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" form="formControleCotacoes" class="btn btn-primary">Salvar</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        reloadPlugin();

    });
</script>