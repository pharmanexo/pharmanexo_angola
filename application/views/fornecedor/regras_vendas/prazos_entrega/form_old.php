<div class="modal fade" id="modalPrazoEntrega" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">Prazo de Entrega</h5>
            </div>

            <div class="modal-body">
                <form id="formPrazoEntrega" method="POST" action="<?php if (isset($form_action)) echo $form_action ?>">
                    <?php if (isset($dados)) : ?>
                        <input type="hidden" name="id" value="<?php echo $dados['id']; ?>">
                    <?php endif; ?>

                    <div class="d-flex flex-row mx-auto col-12">
                        <div class="col-6">
                            <?php $value = (isset($dados)) ? $dados['prazo'] : ''; ?>

                            <label for="prazo">Prazo de Entrega</label>
                            <input type="number" class="form-control" id="prazo" name="prazo" value="<?php echo set_value('prazo', $value); ?>">
                        </div>

                        <div class="col-6">
                            <?php if ($isUpdate) : ?>
                                <?php $value = (isset($dados['estado'])) ? $dados['estado'] : $dados['cliente']; ?>
                                <label for=""><?php echo (isset($dados['estado'])) ? 'Estado' : 'CNPJ'; ?></label>
                                <input type="text" class="form-control" value="<?php echo $value ?>" disabled>
                            <?php else : ?>
                                <?php $label = ($this->session->userdata('integracao') != 0) ? 'Estado ou CNPJ' : 'Estado'; ?>
                                <label for="opcao"><?php echo $label; ?></label>
                                <select class="form-control" name="opcao" id="opcao" data-url="<?php if (isset($lista_estados_cnpj)) echo $lista_estados_cnpj; ?>">
                                    <option value="">Selecione...</option>
                                    <option value="TODOSESTADOS">Todos os Estados</option>
                                    <option value="ESTADOS">Estados Específicos</option>
                                    <?php if ($this->session->userdata('integracao') != 0) : ?>
                                        <option value="TODOSCNPJ">Todos os CNPJs</option>
                                        <option value="CNPJ">CNPJs Específicos</option>
                                    <?php endif; ?>
                                </select>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div id="list" class="row mt-4" style="max-height: 400px; overflow-y: scroll"></div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="submit" form="formPrazoEntrega" class="btn btn-link">Salvar</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        reloadPlugin();

        $('#opcao').on('change', function(e) {
            e.preventDefault();

            $('#list').html('');

            var value = $(this).val();
            $.ajax({
                type: "GET",
                url: $(this).data('url') + value,
                success: function(response) {
                    $.each(response, function(idx, val) {
                        var check = $(`<div class="col-lg-12"></div>`);
                        var input = $(`<input type="checkbox" value="${val.id}" name="elementos[]" />`);
                        var label = $(`<label class="pl-2">${val.descricao}</label>`);

                        $('#list').append(check.append(input, label));
                    });

                }
            });
        });

        $('#formPrazoEntrega').on('submit', function(e) {
            e.preventDefault();

            var $form = $(this);

            $.ajax({
                type: $form.attr('method'),
                url: $form.attr('action'),
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