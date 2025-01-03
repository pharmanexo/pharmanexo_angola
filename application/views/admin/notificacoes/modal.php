<div class="modal fade" id="modalNotificacao" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><?php echo $title; ?></h5>
            </div>

            <div class="modal-body">
                <form id="formNotificacao" method="POST" action="<?php if (isset($form_action)) echo $form_action ?>">

                    <div class="row mt-3">
                        <div class="col">
                            <div class="form-group">
                                <label for="acao">Titulo</label>
                                <input type="text" class="form-control" name="titulo" id="titulo" value="<?php if(isset($modulo)) echo $modulo['titulo']; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col">
                            <div class="form-group">
                                <label>Mensagem</label>
                                <textarea name="mensagem" class="form-control textarea-autosize" rows="4" ><?php if(isset($modulo)) echo $modulo['mensagem']; ?></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="submit" form="formNotificacao" class="btn btn-primary">Salvar</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>

    $(function() {
        reloadPlugin();

        $('#formNotificacao').on('submit', function(e) {
            e.preventDefault();

            var $form = $(this);

            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $form.serialize(),
                dataType: "json",
                beforeSend: function(jqXHR, settings) {
                    if ( $('#titulo').val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo título é obrigatório!"});
                        return jqXHR.abort();
                    }

                    if ( $('#tipo').val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo mensagem é obrigatório!"});
                        return jqXHR.abort();
                    }
                },
                success: function(response) {
                    formWarning(response);
                    if (response.type === 'success') { $('#modalNotificacao').modal('hide'); }
                }
            });

            return false;
        });
    });
</script>