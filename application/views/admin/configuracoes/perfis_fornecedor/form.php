<!DOCTYPE html>
<html lang="pt-BR">
<?php echo $header; ?>
<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>
<div class="content">
    <?php echo $heading; ?>
    <div class="content__inner">
        <form id="formUpdateUserdata" autocomplete="off" action="<?php echo $form_action; ?>" method="POST" enctype="multipart/form-data">
            <div class="card">
                <div class="card-body">
                    <p>Após atualizar as rotas, será necessário fazer login novamente.</p>
                    <div class="row">
                        <div class="col-4">
                            <h5>Administrativo</h5>
                            <ul id="ulAdm">
                                <?php foreach ($rotas['adm'] as $rota){ ?>
                                    <li class="<?php if (isset($rota['subrotas'])) echo 'box'; ?>">
                                        <input type="checkbox" name="adm[]" <?php if(isset($rota['checked'])) echo 'checked'; ?> data-select-adm="<?php echo $rota['id'];?>" value="<?php echo $rota['id'];?>"> <?php echo $rota['rotulo']; ?>
                                        <?php if (isset($rota['subrotas'])){ ?>
                                            <ul class="nested">
                                                <?php foreach ($rota['subrotas'] as $subrota){ ?>

                                                    <?php $subrota['rotulo'] = ($subrota['id'] == 159) ? "{$subrota['rotulo']} (oncoprod) " : $subrota['rotulo'] ?>

                                                    <li>
                                                        <input type="checkbox" name="adm[]" <?php if(isset($subrota['checked'])) echo 'checked'; ?> data-id-adm="<?php echo $subrota['id_parente']; ?>" value="<?php echo $subrota['id'];?>"> <?php echo $subrota['rotulo']; ?>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        <?php } ?>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="col-4">
                            <h5>Financeiro</h5>
                            <ul id="ulFin">
                                <?php foreach ($rotas['fin'] as $rota){ ?>
                                    <li class="<?php if (isset($rota['subrotas'])) echo 'box'; ?>">
                                        <input type="checkbox" name="fin[]" <?php if(isset($rota['checked'])) echo 'checked'; ?> data-select-fin="<?php echo $rota['id'];?>" value="<?php echo $rota['id'];?>"> <?php echo $rota['rotulo']; ?>
                                        <?php if (isset($rota['subrotas'])){ ?>
                                            <ul class="nested">
                                                <?php foreach ($rota['subrotas'] as $subrota){ ?>

                                                    <?php $subrota['rotulo'] = ($subrota['id'] == 159) ? "{$subrota['rotulo']} (oncoprod) " : $subrota['rotulo'] ?>

                                                    <li>
                                                        <input type="checkbox" name="fin[]" <?php if(isset($subrota['checked'])) echo 'checked'; ?> data-id-fin="<?php echo $subrota['id_parente']; ?>" value="<?php echo $subrota['id'];?>"> <?php echo $subrota['rotulo']; ?>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        <?php } ?>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="col-4">
                            <h5>Comercial</h5>
                            <ul id="ulCom">
                                <?php foreach ($rotas['com'] as $rota){ ?>
                                    <li class="<?php if (isset($rota['subrotas'])) echo 'box'; ?>">
                                        <input type="checkbox" name="com[]" <?php if(isset($rota['checked'])) echo 'checked'; ?> data-select-com="<?php echo $rota['id'];?>" value="<?php echo $rota['id'];?>"> <?php echo $rota['rotulo']; ?>
                                        <?php if (isset($rota['subrotas'])){ ?>
                                            <ul class="nested">
                                                <?php foreach ($rota['subrotas'] as $subrota){ ?>

                                                    <?php $subrota['rotulo'] = ($subrota['id'] == 159) ? "{$subrota['rotulo']} (oncoprod) " : $subrota['rotulo'] ?>
                                                    
                                                    <li>
                                                        <input type="checkbox" name="com[]" <?php if(isset($subrota['checked'])) echo 'checked'; ?> data-id-com="<?php echo $subrota['id_parente']; ?>" value="<?php echo $subrota['id'];?>"> <?php echo $subrota['rotulo']; ?>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        <?php } ?>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?php echo $scripts; ?>
<script>
    $(function () {
        reloadPlugin();

        $('[data-select-adm]').on('change', function () {
            var v = $(this).data('select-adm');
            $(`[data-id-adm="${v}"]`).not(this).prop('checked', this.checked);
        });

        $('[data-select-fin]').on('change', function () {
            var v = $(this).data('select-fin');
            $(`[data-id-fin="${v}"]`).not(this).prop('checked', this.checked);
        });

        $('[data-select-com]').on('change', function () {
            var v = $(this).data('select-com');
            $(`[data-id-com="${v}"]`).not(this).prop('checked', this.checked);
        });

        $('#ulAdm').treed({openedClass:'fas fa-chevron-right', closedClass:'fas fa-chevron-down'});
        $('#ulFin').treed({openedClass:'fas fa-chevron-right', closedClass:'fas fa-chevron-down'});
        $('#ulCom').treed({openedClass:'fas fa-chevron-right', closedClass:'fas fa-chevron-down'});
    });
</script>
</body>

</html>