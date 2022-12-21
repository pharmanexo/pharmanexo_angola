<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>

    <div class="content__inner">
        <div class="row">
            <div class="col-3"></div>
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <form id="frmPromo" action="<?php echo $form_action; ?>" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="">Fornecedor</label>
                                <select name="id_fornecedor" id="" class="form-control">
                                    <?php foreach ($fornecedores as $f) { ?>
                                        <option value="<?php echo $f['id']; ?>"><?php echo $f['nome_fantasia']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-8">
                                    <div class="form-group">
                                        <label for="">Arquivo</label>
                                        <input type="file" name="file" id="file" class="form-control">
                                        <br> <span class="small">Enviar apenas arquivos CSV</span>
                                        <br> <span class="small">Se precisar de ajuda para converter para CSV, solicite ao suporte.</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="">Separador</label>
                                        <select name="separador" id="separador">
                                            <option value=",">Vírgula (,)</option>
                                            <option value=";">Ponto e vírgula (;) </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <button type="submit" form="frmPromo" value="Enviar" class="btn btn-primary btn-block">Enviar</button>
            </div>
            <div class="col-3"></div>
        </div>
    </div>
</div>

<?php echo $scripts; ?>

<script>


    $(function () {


    });

</script>
</body>

</html>
