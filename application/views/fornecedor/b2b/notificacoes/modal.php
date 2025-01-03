<div class="modal fade" id="modalEmail" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="width: 100%">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><?php echo $title ?></h5>
            </div>

            <div class="modal-body">
                <form id="formNotificacoes" method="POST" action="<?php if (isset($form_action)) echo $form_action ?>">

                    <?php if (isset($dados)) { ?>
                        <input type="hidden" name="id" value="<?php echo $dados['id'] ?>">
                        <input type="hidden" name="id_cliente" value="<?php echo $dados['id_cliente'] ?>">
                    <?php } ?>

                    <div class="row mt-2">
                        <div class="col">
                            <div class="form-group">
                                <label for="gerente">Celular</label>
                                <div class="input-group">
                                    <input type="text" class="phone form-control" id="celular" name="celular" value="<?php if (isset($dados)) echo $dados['celular'] ?>">
                                    <div class="input-group-append">
                                        <div class="input-group-text bg-light"><i class="fas fa-phone"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col">
                            <div class="form-group">
                                <label for="gerente">E-mail</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="email" name="email" value="<?php if (isset($dados)) echo $dados['email'] ?>">
                                    <div class="input-group-append">
                                        <div class="input-group-text bg-light">@</i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="submit" form="formNotificacoes" class="btn btn-primary">Salvar</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('.phone').mask('(00) 0000-00009');
        $('.phone').blur(function(event) {
            if ($(this).val().length == 15) { // Celular com 9 dígitos + 2 dígitos DDD e 4 da máscara
                $('.phone').mask('(00) 00000-0009');
            } else {
                $('.phone').mask('(00) 0000-00009');
            }
        });

        $('#id_cliente').select2({
            dropdownParent: $('#modalEmail')
        });

        $('#formNotificacoes').on('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                    title: 'Registro adicionado',
                    text: "Pendente logica de envio de ofertas e promoções",
                    icon: 'info',
                });
            var $form = $(this);

            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $form.serialize(),
                dataType: "json",
                beforeSend: function(jqXHR, settings) {

                    <?php if (!isset($dados)) { ?>

                        if ($('#id_cliente').val() == '') {
                            formWarning({
                                type: 'warning',
                                message: "O campo comprador é obrigatório!"
                            });
                            return jqXHR.abort();
                        }
                    <?php } ?>
                },
                success: function(response) {
                    formWarning(response);
                    if (response.type === 'success') {
                        $('#modalEmail').modal('hide');
                    }
                }
            });

            return false;
        });
    });
</script>