<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>

    <div class="content__inner">
        <div class="row  mt-3">
            <div class="col-6 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <form action="<?php echo $formAction; ?>" enctype="multipart/form-data" method="post" id="form">
                            <div class="form-group">
                                <label for="">Margem de segurança</label>
                                <div class="input-group mb-3">
                                    <input type="number" name="margem" id="margem" max="100" value="<?php if (isset($dados['margem'])) echo $dados['margem']; ?>" required class="form-control text-center" placeholder="Informe o valor (Ex. 70)" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                <small>Defina a margem de segurança para os envios de ofertas, em caso de 100% somente serão enviadas ofertas de itens que o estoque disponível atenda
                                100% da quatidade solicitada pelo comprador.</small>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" <?php if (isset($dados['responder_zerados']) && $dados['responder_zerados'] == 1) echo 'checked'?> name="sem_estoque" id="sem_estoque" value="1"> <label for="">Desejo ofertar itens com estoque zerado (sem estoque)</label>
                                <br>
                                <input type="checkbox" <?php if (isset($dados['envia_obs_parcial']) && $dados['envia_obs_parcial'] == 1) echo 'checked'?> name="envia_obs" id="envia_obs" value="1"> <label for="">Em casos de estoque parcial, desejo enviar observação "atendido parcialmente"</label>
                                <br>
                                <input type="checkbox" <?php if (isset($dados['notificar_zerado']) && $dados['notificar_zerado'] == 1) echo 'checked'?> name="alerta_sem_estoque" id="alerta_sem_estoque" value="1"> <label for="">Desejo receber alerta quando algum item sem estoque for ofertado</label><br>
                                <label for="">Destinatários</label>
                                <input type="text" name="emails" id="emails"  value="<?php if (isset($dados['destinatarios'])) echo $dados['destinatarios']; ?>" class="form-control" placeholder="informe separado por virgula (Ex. fulano@gmail.com,ciclano@hotmail.com)">
                                <br>
                            </div>
                            <button form="form" class="btn btn-primary btn-block">Salvar Configuração</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

<?php echo $scripts; ?>

<script>


    $(function () {


    });
</script>

</html>