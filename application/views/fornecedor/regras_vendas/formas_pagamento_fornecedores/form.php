<div class="modal fade" id="modalFormasPagamento" style="display: none;" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">Formas de Pagamentos de Fornecedores</h5>
            </div>

            <div class="modal-body">
                <form id="formFormasPagamento" method="POST" action="<?php if (isset($form_action)) echo $form_action ?>">
                    <?php if (isset($dados['id'])) : ?>
                        <input type="hidden" name="id" value="<?php echo $dados['id']; ?>">
                    <?php else : ?>
                        <input type="hidden" name="elementos" id="elementos">
                    <?php endif; ?>

                    <div class="row mx-auto mt-3">
                        <div class="col-6">
                            <div class="form-group">
                                <?php $value = (isset($forma_pagto)) ? $dados['id_forma_pagamento'] : ''; ?>

                                <label for="">Formas Pagamento</label>
                                <select class="select2 w-100" style="width: 100%" id="formasPagamento" name="id_forma_pagamento" data-url="<?php echo $select_formas_pagamento; ?>" data-placeholder="Selecione" data-allow-clear="true">
                                    <option value="<?php echo (isset($forma_pagto)) ? $forma_pagto['id'] : ''; ?>" selected=""><?php echo (isset($forma_pagto)) ? $forma_pagto['descricao'] : ''; ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <?php if ($isUpdate) : ?>
                                    <?php $value = (isset($dados['estado'])) ? $dados['estado'] : $dados['cliente']; ?>
                                    <?php $label = (isset($dados['estado'])) ? 'Estado' : 'CNPJ'; ?>

                                    <label for=""><?php echo $label ?></label>
                                    <input type="text" class="form-control" value="<?php echo $value ?>" disabled>
                                <?php else : ?>
                                    <?php $label = ($this->session->userdata('id_tipo_venda') != 1) ? 'Estado ou CNPJ' : 'Estado'; ?>

                                    <label for="opcao"><?php echo $label; ?></label>
                                    <select class="select2 w-100" style="width: 100%;" name="opcao" id="selectTransfer" data-url="<?php if (isset($getList)) echo $getList; ?>" data-placeholder="Selecione" data-allow-clear="true">
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
                <button type="submit" form="formFormasPagamento" class="btn btn-primary">Salvar</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>


<script>
    $(function() {
        reloadPlugin();

        $("#selectTransfer, #formasPagamento").select2({ dropdownParent: $('#modalFormasPagamento') });

        var demo = $('#listElements').bootstrapDualListbox({
            nonSelectedListLabel: 'Elementos',
            selectedListLabel: 'Elementos Selecionados',
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

        $('#formFormasPagamento').on('submit', function(e) {
            e.preventDefault();

            var $form = $(this);

            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $form.serialize(),
                dataType: "json",
                beforeSend: function(jqXHR, settings) {
                    if ( $("#formasPagamento").val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo Formas de pagamento é obrigatório!"});
                        return jqXHR.abort();
                    }  

                    <?php if (!$isUpdate): ?>

                        var label = "<?php echo ($this->session->userdata('id_tipo_venda') != 1) ? 'Estado ou CNPJ' : 'Estado'; ?>";

                        if ( $("#selectTransfer").val() == '' ) {
                            formWarning({ type: 'warning', message: `O campo ${label} é obrigatório!`});
                            return jqXHR.abort();
                        }  

                        if ( $("#elementos").val() == '' ) {
                            formWarning({ type: 'warning', message: "O campo elementos é obrigatório!"});
                            return jqXHR.abort();
                        } 
                    <?php endif; ?> 
                },
                success: function(response) {

                    if (response.type === 'warning') {
                        if ( typeof response.message == 'string' ) {
                            response.message = {message: response.message };
                        }

                        $.each(response.message, function(i, v) {

                            formWarning({type: 'warning', message: v });
                        });
                    } else {

                        formWarning({type: 'success', message: response.message });
                        $('#modalFormasPagamento').modal('hide');
                    }
                }
            });

            return false;
        });

        var slct_group = $('#formasPagamento');

        slct_group.select2({
            placeholder: 'SELECIONE ...',
            dropdownParent: $('#modalFormasPagamento'),

            ajax: {
                url: slct_group.data('url'),
                type: 'get',
                dataType: 'json',
                delay: 300,
                data: function(params) {
                    return {
                        columns: [{
                            name: 'descricao',
                            search: params.term
                        }],
                        page: params.page || 1
                    }
                }
            },

            processResults: function(data) {
                return {
                    results: data
                }
            },

            templateResult: function(data) {
                if (!data.id) {
                    return data.text;
                }
                return data.descricao;
            },

            templateSelection: function(data) {
                if (!data.id) {
                    return data.text;
                }
                return (typeof data.descricao !== 'undefined') ? data.descricao : data.text;
            }
        });

        initSelect2(slct_group);
    });

    function initSelect2(e) 
    {
        $.ajax({
            url: e.data('url'),
            type: 'get',
            dataType: "json",
            data: {
                columns: [{
                    name: 'id',
                    search: e.data('value'),
                    equal: true
                }]
            }
        }).then(function(data) {
            var id;
            data.results.forEach(function(entry) {

                // id = entry.id;
                // var $option = new Option(entry.descricao, entry.id, false, false);
                // $(e).append($option).val(id).trigger('change');
            });
        });
    }
</script>