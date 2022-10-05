<div class="modal fade" id="modalResponsaveisCotacoes" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">Responsáveis Cotações</h5>
            </div>

            <div class="modal-body">
                <form id="formControleCotacoes" method="POST" action="<?php if (isset($form_action)) echo $form_action ?>">
                    <?php if (isset($dados)) : ?>
                        <input type="hidden" name="id" value="<?php echo $dados['id']; ?>">
                    <?php else : ?>
                        <input type="hidden" name="elementos" id="elementos">
                    <?php endif; ?>

                    <div class="row mx-auto mt-3">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="consultor">Consultores</label>
                                <select class="select2 w-100" style="width: 100%" name="consultor" id="consultor" data-placeholder="Selecione" data-allow-clear="true">
                                    <option></option>
                                    <?php foreach ($consultores as $reponsavel): ?>
                                        <option value="<?= $reponsavel['id'] ?>" <?= (!empty($dados['id_consultor'])
                                        AND $dados['id_consultor'] == $reponsavel['id']) ? 'selected' : ''; ?> ><?= $reponsavel['nome'] ?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <label for="gerente">Gerente</label>
                                <select class="select2 w-100" style="width: 100%" name="gerente" id="gerente" data-placeholder="Selecione" data-allow-clear="true">
                                    <option></option>
                                    <?php foreach ($gerentes as $reponsavel): ?>
                                        <option value="<?= $reponsavel['id'] ?>" <?= (!empty($dados['id_consultor'])
                                            AND $dados['id_gerente'] == $reponsavel['id']) ? 'selected' : ''; ?> ><?= $reponsavel['nome'] ?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="assistente">Assistente</label>
                                <select class="select2 w-100" style="width: 100%" name="assistente" id="assistente" data-placeholder="Selecione" data-allow-clear="true">
                                    <option></option>
                                    <?php foreach ($assistentes as $reponsavel): ?>
                                        <option value="<?= $reponsavel['id'] ?>" <?= (!empty($dados['id_consultor'])
                                            AND $dados['id_assistente'] == $reponsavel['id']) ? 'selected' : ''; ?>><?= $reponsavel['nome'] ?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>


                    </div>

                    <?php if (!$isUpdate) : ?>
                        <div class="row mx-auto mt-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <select multiple name="listElements" id="listElements">
                                        <?php foreach ($compradores as $comprador): ?>
                                            <option value="<?= $comprador['id'] ?>"><?=  $comprador['cnpj']. ' - '. $comprador['razao_social'] ?></option>
                                        <?php endforeach;?>

                                    </select>
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
    $(function() {
        reloadPlugin();

        $("#comprador,#consultor, #gerente, #assistente").select2({ dropdownParent: $('#modalResponsaveisCotacoes') });

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

        $('#listElements').on('change', function(e) {
            e.preventDefault();

            var values = [];
            $.each($("#listElements option:selected"), function() {
                values.push($(this).val());
            });

            $('#elementos').val(values.join(','));
        });

        $('#selectTransfer').on('change', function(e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: $(this).data('url') + '/' + $(this).val(),
                dataType: 'json',
                success: function(response) {
                    $('#listElements').children().remove();

                    $.each(response, function(i, v) {
                        $('<option>').text(v.descricao).val(v.id).appendTo("#listElements");
                    });

                    $("#listElements").bootstrapDualListbox('refresh', true);
                }
            });
        });

        $('#formControleCotacoes').on('submit', function(e) {
            e.preventDefault();

            var $form = $(this);

            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $form.serialize(),
                dataType: "json",
                beforeSend: function(jqXHR, settings) {

                    if ( $("#regra_venda").val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo regra de venda é obrigatório!"});
                        return jqXHR.abort();
                    }

                    <?php if (!$isUpdate): ?>

                    <?php $label = ($this->session->userdata('id_tipo_venda') != 1) ? 'Estado ou CNPJ' : 'Estado'; ?>

                    if ( $("#selectTransfer").val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo <?php echo $label; ?> é obrigatório!"});
                        return jqXHR.abort();
                    }
                    <?php endif; ?>
                },
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

                        $('#modalResponsaveisCotacoes').modal('hide');
                    }
                }
            });

            return false;
        });
    });
</script>