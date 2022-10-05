<div class="modal fade" id="modalValorMinimo" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">Cancelamento de Ordem de Compra</h5>
            </div>

            <div class="modal-body">

                <form id="formCancelOC" method="POST" action="<?php if (isset($dados['form_action'])) echo $dados['form_action'] ?>">
                    <?php if (isset($dados)) : ?>
                        <input type="hidden" name="id" value="<?php echo $dados['id']; ?>">
                    <?php else : ?>
                        <input type="hidden" name="elementos" id="elementos">
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="">Motivo do cancelamento</label>
                        <select name="id_status" id="id_status" class="form-control">
                            <option value="">Selecione</option>
                            <?php foreach ($dados['motivos'] as $motivo){ ?>
                                <option value="<?php echo $motivo['codigo']; ?>"><?php echo $motivo['descricao']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Observação</label>
                        <textarea name="obs" id="obs" cols="30" rows="3" class="form-control"></textarea>
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button type="submit" form="formCancelOC" class="btn btn-primary">Confirmar</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        reloadPlugin();

        $('#formCancelOC').on('submit', function(e) {
            e.preventDefault();

            var $form = $(this);

            $.ajax({
                type: $form.attr('method'),
                url: $form.attr('action'),
                data: $form.serialize(),
                dataType: "json",

                success: function(response) {
                    if (response.type === 'warning') {

                        if ( typeof response.message == 'string' ) {
                            response.message = {message: response.message };
                        }

                        $.each(response.message, function(i, v) {

                            formWarning({type: 'warning', message: v });
                        });
                    } else {

                        formWarning(response);
                        $('#modalValorMinimo').modal('hide');
                    }
                }
            });

            return false;
        });
    });
</script>
