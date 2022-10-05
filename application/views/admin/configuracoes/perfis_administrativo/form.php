<!DOCTYPE html>
<html lang="pt-BR">
<?php echo $header; ?>
<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>
<div class="content">
    <?php echo $heading; ?>
    <div class="content__inner">
        <form id="formPerfil" action="<?php echo $form_action; ?>" data-return="<?php echo $url_return; ?>" method="POST" enctype="multipart/form-data">
            <div class="card">
                <div class="card-body">
                   
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="acao">Perfil</label>
                                <input type="text" class="form-control" name="titulo" id="titulo" value="<?php if(isset($perfil)) echo $perfil['titulo']; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-12">
                            
                            <ul id="lista">
                                <?php foreach ($rotas as $rota): ?>
                                    <li class="<?php if (isset($rota['subrotas'])) echo 'box'; ?>">
                                        <input type="checkbox" name="rotas[]" <?php if(isset($rota['checked'])) echo 'checked'; ?> data-select="<?php echo $rota['id']; ?>" value="<?php echo $rota['id']; ?>"> <?php echo $rota['rotulo']; ?>
                                        <?php if (isset($rota['subrotas'])): ?>
                                            <ul class="nested">
                                                <?php foreach ($rota['subrotas'] as $subrota): ?>
                                                  
                                                    <li>
                                                        <input type="checkbox" name="rotas[]" <?php if(isset($subrota['checked'])) echo 'checked'; ?> data-subrota="<?php echo $subrota['id_parente']; ?>" value="<?php echo $subrota['id'];?>"> <?php echo $subrota['rotulo']; ?>
                                                    </li>
                                                <?php  endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
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

        $('[data-select]').on('change', function () {
            var v = $(this).data('select');
            $(`[data-subrota="${v}"]`).not(this).prop('checked', this.checked);
        });

        $('#lista').treed({openedClass:'fas fa-chevron-right', closedClass:'fas fa-chevron-down'});

        $('#formPerfil').on('submit', function(e) {
            e.preventDefault();

            var $form = $(this);

            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $form.serialize(),
                dataType: "json",
                beforeSend: function(jqXHR, settings) {
                    if ( $('#titulo').val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo perfil é obrigatório!"});
                        return jqXHR.abort();
                    }
                },
                success: function(response) {
                    formWarning(response);
                    setTimeout(function() { window.location.href = $('#formPerfil').data('return'); }, 1500);
                }
            });

            return false;
        });
    });
</script>
</body>

</html>