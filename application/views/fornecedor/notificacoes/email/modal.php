<div class="modal fade" id="modalEmail" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="width: 100%">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><?php echo $title ?></h5>
            </div>

            <div class="modal-body">
                <form id="formEmail" method="POST" action="<?php if (isset($form_action)) echo $form_action ?>">

                    <?php if (isset($dados)) { ?>
                        <input type="hidden" name="id" value="<?php echo $dados['id'] ?>">
                        <input type="hidden" name="id_cliente" value="<?php echo $dados['id_cliente'] ?>">
                    <?php } ?>

                    <div class="row mt-3">
                        <div class="col">
                            <div class="form-group">
                                <label for="id_cliente">Comprador</label>
                                <select class="select2 w-100" name="id_cliente" id="id_cliente" style="width: 100%" <?php echo (isset($dados)) ? 'disabled' : '' ?>>
                                    <option value="">Selecione</option>
                                    <?php foreach ($compradores as $c) { ?>
                                        <option value="<?php echo $c['id']; ?>" <?php echo (isset($dados) && $dados['id_cliente'] == $c['id']) ? 'selected' : '' ?>><?php echo $c['comprador']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

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
                                <label for="gerente">Gerente</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="gerente" name="gerente" value="<?php if (isset($dados)) echo $dados['gerente'] ?>">
                                    <div class="input-group-append">
                                        <div class="input-group-text bg-light">@</i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="consultor">Consultor</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="consultor" name="consultor" value="<?php if (isset($dados)) echo $dados['consultor'] ?>">
                                    <div class="input-group-append">
                                        <div class="input-group-text bg-light">@</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="geral">Geral</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="geral" name="geral" value="<?php if (isset($dados)) echo $dados['geral'] ?>">
                                    <div class="input-group-append">
                                        <div class="input-group-text bg-light">@</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="grupo">Grupo</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="grupo" name="grupo" value="<?php if (isset($dados)) echo $dados['grupo'] ?>">
                                    <div class="input-group-append">
                                        <div class="input-group-text bg-light">@</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="grupo">Notificar na abertura de cotação?</label>
                                <div class="input-group text-center">
                                    <select name="alerta_abertura" id="alerta_abertura" class="form-control">
                                        <option value="0">NÃO</option>
                                        <option value="1">SIM</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <small><i>Para adicionar vários e-mais no mesmo campo, separe-os por virgula. (EX. fulano@mail.com, beltrano@mail.com)</i></small>
                </form>
            </div>

            <div class="modal-footer">
                <button type="submit" form="formEmail" class="btn btn-primary">Salvar</button>
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

        $('#formEmail').on('submit', function(e) {
            e.preventDefault();

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