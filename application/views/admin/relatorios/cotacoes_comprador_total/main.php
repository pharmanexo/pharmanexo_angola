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
                <form action="<?php echo $form_action; ?>" method="post">
                    <h5 class="card-title text-muted">Filtros</h5>

                    <div class="row">
                        <div class="col-3 form-group">
                            <label for="integrador">Integrador</label>
                            <select class="select2" id="integrador" name="integrador" data-index="4">
                                <option value="">Selecione</option>
                                <?php foreach ($integradores as $c) { ?>
                                    <option value="<?php echo $c['id']; ?>"><?php echo $c['desc']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-3">
                            <label>Data Inicio</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="zmdi zmdi-calendar"></i></span>
                                </div>
                                <input type="date" class="form-control" name="data_ini" placeholder="Selecione uma data">
                            </div>
                        </div>
                        <div class="col-3">
                            <label>Data fim</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="zmdi zmdi-calendar"></i></span>
                                </div>
                                <input type="date" class="form-control" name="data_fim" placeholder="Selecione uma data">
                            </div>
                        </div>
                    </div>
                    <input type="submit" value="Pesquisar">
                </form>
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