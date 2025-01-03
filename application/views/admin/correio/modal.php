<div class="modal fade" id="modalNovaMensagem" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">Nova Mensagem</h5>
            </div>

            <div class="modal-body">
                <form id="formCorreio" method="POST" action="<?php if (isset($form_action)) echo $form_action ?>">

                    <div class="row">
                        <div class="col">
                            <label for="destinatario">Destinatario</label>
                            <input type="text" name="destinatario" id="destinatario">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <label for="assunto">Assunto</label>
                            <input type="text" name="assunto" id="assunto">
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="submit" form="formCorreio" class="btn btn-primary">Salvar</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {

        $('#formCorreio').on('submit', function(e) {
            e.preventDefault();

            var $form = $(this);

            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $form.serialize(),
                dataType: "json",

                success: function(response) {
                    if (response.status === false) {
                        $.each(response.errors, function(i, v) {
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

                        $('#modalPrazoEntrega').modal('hide');
                    }
                }
            });

            return false;
        });
    });
</script>