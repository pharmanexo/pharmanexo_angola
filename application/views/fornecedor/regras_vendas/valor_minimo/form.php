<div class="modal fade" id="modalValorMinimo" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">Desconto e Valor Mínimo</h5>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        O percentual de DESCONTO GERAL será aplicado sobre a tabela 0 (zero) para os estados ou compradores selecionados. 
                        <br>Exceto nos produtos que tiverem configuração específica no menu (venda diferenciada).
                    </div>
                </div>
                <form id="formValorMinimo" method="POST" action="<?php if (isset($form_action)) echo $form_action ?>">
                    <?php if (isset($dados)) : ?>
                        <input type="hidden" name="id" value="<?php echo $dados['id']; ?>">
                    <?php else : ?>
                        <input type="hidden" name="elementos" id="elementos">
                    <?php endif; ?>

                    <div class="row mt-3">
                        <div class="col-4">
                            <div class="form-group">
                                <?php $value = (isset($dados)) ? $dados['valor_minimo'] : ''; ?>

                                <label for="valor_minimo">Valor Mínimo</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">R$ </div>
                                    </div>
                                    <input type="text" data-inputmask="money" class="form-control text-center" id="valor_minimo" name="valor_minimo" value="<?php echo (!empty($value) ? number_format($value, 2, ',', '.') : set_value('valor_minimo')); ?>" data-inputmask="money">
                                </div>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <label for="desconto_padrao">Desconto Geral (Difal)</label>
                                <div class="input-group">
                                    <input type="text" data-inputmask="money" class="form-control text-center" id="desconto_padrao" name="desconto_padrao" value="<?php echo (isset($dados['desconto_padrao']) ? number_format($dados['desconto_padrao'], 2, ',', '.') : '0,00'); ?>" data-inputmask="money">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            %
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <label for="opcao">Estados ou Compradores</label>
                                <?php if ($isUpdate) : ?>
                                    <?php $value = (isset($dados['estado'])) ? $dados['estado'] : $dados['cliente']; ?>
                                    <input type="text" class="form-control" value="<?php echo $value; ?>" disabled>
                                <?php else : ?>
                                    <select class="select2 w-100" style="width: 100%" name="opcao" id="selectTransfer" data-url="<?php echo $getList; ?>" data-placeholder="Selecione" data-allow-clear="true">
                                        <option></option>
                                        <option value="ESTADOS">Estados</option>
                                        <option value="CLIENTES">Compradores</option>
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

                        <?php if( isset($this->session->id_matriz) ): ?>

                            <div class="row mt-2 ml-1">
                                <div class="col-3">
                                    <div class="checkbox">
                                        <input name="replicarMatriz" type="checkbox" id="replicarMatriz">
                                        <label class="checkbox__label" data-toggle="tooltip" title="Marcar esta opção fará com que os registros sejam os mesmos para todas as matrizes" for="replicarMatriz">Replicar para Matrizes?</label>
                                    </div>
                                </div>
                            </div>

                        <?php endif; ?>
                    <?php endif; ?>
                </form>
            </div>

            <div class="modal-footer">
                <button type="submit" form="formValorMinimo" class="btn btn-primary">Salvar</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        reloadPlugin();

        $("#selectTransfer").select2({ dropdownParent: $('#modalValorMinimo') });

        var demo = $('#listElements').bootstrapDualListbox({
            nonSelectedListLabel: 'Estados ou Compradores',
            selectedListLabel: 'Selecionados',
            filterPlaceHolder: 'Pesquisar',
            filterTextClear: 'Exibir Todos',
            infoText: 'Exibindo todos {0} registros',
            infoTextFiltered: '<span class="label label-warning">Filtrado</span> {0} de {1}',
            infoTextEmpty: 'Lista vazia',
            preserveSelectionOnMove: 'moved',
            moveOnSelect: false,
            nonSelectedFilter: ''
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

        $('#formValorMinimo').on('submit', function(e) {
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