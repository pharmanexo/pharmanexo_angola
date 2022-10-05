<div class="modal fade" id="modalMix" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><?php echo $title; ?></h5>
            </div>

            <div class="modal-body">
                <form id="formMix" method="POST" action="<?php if (isset($form_action)) echo $form_action ?>">

                    <?php if( !isset($isUpdate) ) { ?>

                        <input type="hidden" name="elementos" id="elementos">

                        <div class="row mx-auto mt-3">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="fornecedor">Fornecedor</label>
                                    <select class="select2 w-100" name="id_fornecedor" id="fornecedor" style="width: 100%">
                                        <option value="">Selecione</option>
                                        <?php foreach($fornecedores as $f) { ?>
                                            <option value="<?php echo $f['id']; ?>" <?php if( isset($dados) && $dados['id_fornecedor'] == $f['id'] ) echo 'selected' ?> ><?php echo $f['fornecedor'];?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="selectTransfer">Estado ou CNPJ</label>
                                    <select class="select2 w-100" name="type" id="selectTransfer" style="width: 100%">
                                        <option value="">Selecione</option>
                                        <option value="1">Estados</option>
                                        <option value="2">CNPJs</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mx-auto mt-1">

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="desconto_mix">Desconto</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="desconto_mix" name="desconto_mix" data-inputmask="money" value="<?php if( isset($dados) ) echo $dados['desconto_mix'] ?>">
                                        <div class="input-group-append">
                                            <div class="input-group-text bg-light">%</div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="prioridade">Prioridade</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="prioridade" name="prioridade" value="<?php if( isset($dados) ) echo $dados['prioridade'] ?>">
                                        <div class="input-group-append">
                                            <div class="input-group-text bg-light"><i class="fas fa-sort-numeric-up"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mx-auto mt-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <select multiple name="listElements" id="listElements"></select>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>

                        <?php if( isset($dados) ) { ?>
                            <input type="hidden" name="id" value="<?php echo $dados['id'] ?>">
                        <?php }?>

                        <div class="row mt-3">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="desconto_mix">Desconto</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="desconto_mix" name="desconto_mix" data-inputmask="money" value="<?php if( isset($dados) ) echo number_format($dados['desconto_mix'], 2, ',', '.'); ?>">
                                        <div class="input-group-append">
                                            <div class="input-group-text bg-light">%</div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="prioridade">Prioridade</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="prioridade" name="prioridade" value="<?php if( isset($dados) ) echo $dados['prioridade'] ?>">
                                        <div class="input-group-append">
                                            <div class="input-group-text bg-light"><i class="fas fa-sort-numeric-up"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </form>
            </div>

            <div class="modal-footer">
                <button type="submit" form="formMix" class="btn btn-primary">Salvar</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>

    var url_list = "<?php if(isset($url_list)) echo $url_list; ?>";

    $(function() {
        reloadPlugin();

        $('#fornecedor, #id_estado').select2({dropdownParent: $('#modalMix') });

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
                url: url_list,
                dataType: 'json',
                data: {
                    type: $(this).val()
                },
                success: function(response) {
                    $('#listElements').children().remove();

                    if ( response.type == 'success' ) {

                        $.each(response.data, function(i, v) {
                            $('<option>').text(v.value).val(v.id).appendTo("#listElements");
                        });
                    }

                    $("#listElements").bootstrapDualListbox('refresh', true);
                }
            });
        });

        $('#formMix').on('submit', function(e) {
            e.preventDefault();

            var $form = $(this);

            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $form.serialize(),
                dataType: "json",
                beforeSend: function(jqXHR, settings) {

                    <?php if (!isset($dados) ) { ?>

                    if ( $('#fornecedor').val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo fornecedor é obrigatório!"});
                        return jqXHR.abort();
                    }

                    if ( typeof($('#estados').val()) != 'undefined' && $('#estados').val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo estado é obrigatório!"});
                        return jqXHR.abort();
                    }

                    if ( typeof($('#clientes').val()) != 'undefined' && $('#clientes').val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo estado é obrigatório!"});
                        return jqXHR.abort();
                    }

                    if ( $('#desconto_mix').val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo desconto é obrigatório!"});
                        return jqXHR.abort();
                    }

                    if ( $('#prioridade').val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo prioridade é obrigatório!"});
                        return jqXHR.abort();
                    }
                    <?php } else { ?>

                    if ( $('#desconto_mix').val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo desconto é obrigatório!"});
                        return jqXHR.abort();
                    }

                    if ( $('#prioridade').val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo prioridade é obrigatório!"});
                        return jqXHR.abort();
                    }
                    <?php } ?>
                },
                success: function(response) {
                    formWarning(response);
                    if (response.type === 'success') { $('#modalMix').modal('hide'); }
                }
            });

            return false;
        });
    });
</script>