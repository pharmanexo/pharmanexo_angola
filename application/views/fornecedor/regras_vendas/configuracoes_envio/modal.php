<div class="modal fade" id="modalConfiguracoes" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">Configurações de envio</h5>
            </div>

            <div class="modal-body">
                <form id="formConfiguracoes" method="POST" action="<?php if (isset($form_action)) echo $form_action ?>">

                    <input type="hidden" name="id" value="<?php if (isset($dados)) echo $dados['id']; ?>">

                    <div class="row mx-auto mt-3">

                        <div class="col-4">
                            <div class="form-group">
                                <label for="tipo">Tipo</label>
                                <select class="select2 w-100" id="tipo" name="tipo" style="width: 100%" data-placeholder="Selecione" data-allow-clear="true" <?php if (isset($dados)) echo 'disabled'; ?>>
                                    <option></option>
                                    <option value="1" <?php if (isset($dados) && $dados['tipo'] == 1) echo 'selected'; ?>>Automática</option>
                                    <option value="2" <?php if (isset($dados) && $dados['tipo'] == 2) echo 'selected'; ?>>Manual</option>
                                    <option value="3" <?php if (isset($dados) && $dados['tipo'] == 3) echo 'selected'; ?>>Manual e Automática</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <label for="estados"><?php echo (isset($dados)) ? 'Estado' : 'Estados'; ?></label>
                                <select class="form-control" name="estados[]" id="estados" multiple="multiple" style="heigth: 60%" data-live-search="true" title="Selecione" <?php if (isset($dados)) echo 'disabled'; ?>>
                                    <option value="0" <?php if (isset($dados) && $dados['id_estado'] == 0) echo 'selected'; ?>>Todos</option>
                                    <?php foreach ($estados as $estado) : ?>
                                        <option value="<?php echo $estado['id']; ?>" <?php if (isset($dados) && $dados['id_estado'] == $estado['id']) echo 'selected'; ?>><?php echo $estado['estado']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <label for="estados"><?php echo (isset($dados)) ? 'Integrador' : 'Integradores'; ?></label>
                                <select class="form-control" name="integradores[]" id="integradores" multiple="multiple" style="heigth: 60%" data-live-search="true" title="Selecione" <?php if (isset($dados)) echo 'disabled'; ?>>
                                    <option value="0" <?php if (isset($dados) && $dados['integrador'] == 0) echo 'selected'; ?>>Todos</option>
                                    <?php foreach ($integradores as $integrador) : ?>
                                        <option value="<?php echo $integrador['id']; ?>" <?php if (isset($dados) && $dados['integrador'] == $integrador['id']) echo 'selected'; ?>><?php echo $integrador['desc']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mx-auto">
                        <div class="col">
                            <div class="form-group">
                                <label for="observacao">Observação</label>
                                <textarea class="form-control" name="observacao" maxlength="500" id="observacao"><?php if (isset($dados)) echo $dados['observacao']; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row mx-auto">
                        <div class="col">
                            <div class="form-group">
                                <label for="observacao">Enviar validade na observação: </label>
                                <input type="checkbox" <?php if (isset($dados['validade']) && $dados['validade'] == 1) echo 'checked'; ?> value="1" name="validade" id="validade"> SIM
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="submit" form="formConfiguracoes" class="btn btn-primary">Salvar</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        reloadPlugin();

        $('#estados').selectpicker();
        $('#integradores').selectpicker();
        $("#tipo").select2({
            dropdownParent: $('#modalConfiguracoes')
        });

        $('#formConfiguracoes').on('submit', function(e) {
            e.preventDefault();

            var $form = $(this);

            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $form.serialize(),
                dataType: "json",
                beforeSend: function(jqXHR, settings) {
                    if ($("#tipo").val() == '') {
                        formWarning({
                            type: 'warning',
                            message: "O campo tipo é obrigatório!"
                        });
                        return jqXHR.abort();
                    }

                    if ($("#observacao").val() == '') {
                        formWarning({
                            type: 'warning',
                            message: "O campo observação é obrigatório!"
                        });
                        return jqXHR.abort();
                    }

                    if ($("#integradores").val() == '') {
                        formWarning({
                            type: 'warning',
                            message: "O campo integradores é obrigatório!"
                        });
                        return jqXHR.abort();
                    }

                    if ($("#estados").val() == '') {
                        formWarning({
                            type: 'warning',
                            message: "O campo estados é obrigatório!"
                        });
                        return jqXHR.abort();
                    }
                },
                success: function(response) {
                    if (response.type === 'warning') {

                        if (typeof response.message == 'string') {
                            response.message = {
                                message: response.message
                            };
                        }

                        $.each(response.message, function(i, v) {

                            formWarning({
                                type: 'warning',
                                message: v
                            });
                        });
                    } else {

                        formWarning(response);
                        $('#modalConfiguracoes').modal('hide');
                    }
                }
            });

            return false;
        });
    });
</script>