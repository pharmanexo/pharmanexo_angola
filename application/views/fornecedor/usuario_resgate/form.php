<div class="modal fade" id="modalControleCotacoes" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><?php if(isset($title)) echo $title ?></h5>
            </div>

            <div class="modal-body">
                <form id="formControleCotacoes" method="POST"
                      action="<?php if (isset($form_action)) echo $form_action ?>">
                    <?php if (isset($dados)) : ?>
                        <input type="hidden" name="id" value="<?php echo $dados['id']; ?>">
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-12 form-group">
                            <label>Nome</label>
                            <input class='form-control' type="text" name="nome" placeholder="Nome do usuário" value="<?php if(isset($dados['nome'])) echo $dados['nome'] ?>">
                        </div>
                        <div class="col-12 form-group">
                            <label>Usuario</label>
                            <input class='form-control' type="text" name="usuario" placeholder="Usuario kraft" value="<?php if(isset($dados['usuario'])) echo $dados['usuario'] ?>">
                        </div>
                    </div>
                </form>
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

        $('#formControleCotacoes').on('submit', function (e) {
            e.preventDefault();

            var $form = $(this);

            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $form.serialize(),
                dataType: "json",
                success: function (response) {
                    if (response.status === false) {
                        $.each(response.errors, function (i, v) {
                            formWarning({
                                type: 'warning',
                                message: v
                            });
                        });
                    } else {
                        formWarning({
                            type: 'success',
                            message: response.message
                        });

                        $('#modalControleCotacoes').modal('hide');
                    }
                }
            });

            return false;
        });
    });
</script>