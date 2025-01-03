<div class="modal fade" id="modalControleCotacoes" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">Controle de Cotações</h5>
            </div>

            <div class="modal-body">
                <form id="formControleCotacoes" method="POST"
                      action="<?php if (isset($form_action)) echo $form_action ?>">
                    <?php if (isset($dados)) : ?>
                        <input type="hidden" name="id" value="<?php echo $dados['id']; ?>">
                    <?php else : ?>
                        <input type="hidden" name="elementos" id="elementos">
                    <?php endif; ?>

                    <div class="row mx-auto mt-3">

                        <?php if (!empty($integradores)) { ?>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="integrador">Selecione o integrador</label>
                                    <div class="form-group">
                                        <?php foreach ($integradores as $integrador) { ?>

                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" name="integrador[]" type="checkbox"
                                                       id="inlineCheckbox<?php echo $integrador['id']; ?>"
                                                       value="<?php echo $integrador['id']; ?>">
                                                <label class="form-check-label"
                                                       for="inlineCheckbox<?php echo $integrador['id']; ?>"> <?php echo $integrador['desc']; ?></label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="regra_venda">Regra de venda</label>
                                <select class="select2 w-100" style="width: 100%" name="regra_venda" id="regra_venda"
                                        data-placeholder="Selecione" data-allow-clear="true">
                                    <option></option>
                                    <option value="0" <?php if (isset($dados['regra_venda']) && $dados['regra_venda'] == 0) echo 'selected'; ?> >
                                        Manual
                                    </option>
                                    <option value="1" <?php if (isset($dados['regra_venda']) && $dados['regra_venda'] == 1) echo 'selected'; ?>>
                                        Automática
                                    </option>
                                    <option value="2" <?php if (isset($dados['regra_venda']) && $dados['regra_venda'] == 2) echo 'selected'; ?>>
                                        Manual e Automática
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">

                                <?php if ($isUpdate) : ?>

                                    <?php $label = (isset($dados['estado'])) ? 'Estado' : 'CNPJ'; ?>

                                    <label for=""><?php echo $label; ?></label>
                                    <input type="text" class="form-control"
                                           value="<?php echo (isset($dados['estado'])) ? $dados['estado'] : $dados['cliente']; ?>"
                                           disabled>
                                <?php else : ?>
                                    <?php $label = ($this->session->userdata('id_tipo_venda') != 1) ? 'Estado ou CNPJ' : 'Estado'; ?>

                                    <label for="selectTransfer"><?php echo $label; ?></label>
                                    <select class="select2 w-100" style="width: 100%;" name="opcao" id="selectTransfer"
                                            data-placeholder="Selecione" data-allow-clear="true"
                                            data-url="<?php if (isset($getList)) echo $getList; ?>">
                                        <option></option>
                                        <option value="ESTADOS">Estados</option>
                                        <?php if ($this->session->userdata('id_tipo_venda') != 1) : ?>
                                            <option value="CLIENTES">CNPJs</option>
                                        <?php endif; ?>
                                    </select>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?php if (!$isUpdate) : ?>
                        <div class="row mx-auto mt-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <select multiple name="listElements" id="listElements"></select>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
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

        $("#regra_venda, #selectTransfer").select2({dropdownParent: $('#modalControleCotacoes')});

        var demo = $('#listElements').bootstrapDualListbox({
            nonSelectedListLabel: 'Elementos',
            selectedListLabel: 'Elementos Selecionados',
            filterPlaceHolder: 'Pesquisar',
            filterTextClear: 'Exibir Todos',
            infoText: 'Exibindo todos {0} registros',
            infoTextFiltered: '<span class="label label-warning">Filtrado</span> {0} de {1}',
            infoTextEmpty: 'Lista vazia',
            moveAllLabel: 'Selecionar Todos',
            moveSelectedLabel: 'Adicionar Selecionado',
            removeSelectedLabel: 'Remover Selecionado',
            removeAllLabel: 'Remover Todos',
            preserveSelectionOnMove: 'moved',
            moveOnSelect: false,
            iconMove: 'Selecionar',
            iconRemove: 'Remover'
        });

        $('.move').html('Selecionar Marcados').removeClass('btn-outline-secondary').addClass('btn-primary');
        $('.moveall').html('Selecionar Todos').removeClass('btn-outline-secondary').addClass('btn-secondary');
        $('.remove').html('Remover Marcados').removeClass('btn-outline-secondary').addClass('btn-primary');
        $('.removeall').html('Remover Todos').removeClass('btn-outline-secondary').addClass('btn-secondary');

        $('#listElements').on('change', function (e) {
            e.preventDefault();

            var values = [];
            $.each($("#listElements option:selected"), function () {
                values.push($(this).val());
            });

            $('#elementos').val(values.join(','));
        });

        $('#selectTransfer').on('change', function (e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: $(this).data('url') + '/' + $(this).val(),
                dataType: 'json',
                success: function (response) {
                    $('#listElements').children().remove();

                    $.each(response, function (i, v) {
                        $('<option>').text(v.descricao).val(v.id).appendTo("#listElements");
                    });

                    $("#listElements").bootstrapDualListbox('refresh', true);
                }
            });
        });

        $('#formControleCotacoes').on('submit', function (e) {
            e.preventDefault();

            var $form = $(this);

            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $form.serialize(),
                dataType: "json",
                beforeSend: function (jqXHR, settings) {

                    if ($("#regra_venda").val() == '') {
                        formWarning({type: 'warning', message: "O campo regra de venda é obrigatório!"});
                        return jqXHR.abort();
                    }

                    <?php if (!$isUpdate): ?>

                    <?php $label = ($this->session->userdata('id_tipo_venda') != 1) ? 'Estado ou CNPJ' : 'Estado'; ?>

                    if ($("#selectTransfer").val() == '') {
                        formWarning({type: 'warning', message: "O campo <?php echo $label; ?> é obrigatório!"});
                        return jqXHR.abort();
                    }
                    <?php endif; ?>
                },
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