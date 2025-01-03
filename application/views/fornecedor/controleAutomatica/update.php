<div class="modal fade" id="modalControleAutomatica" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">Atualizar configuração da automática</h5>
            </div>

            <div class="modal-body">
                <form id="formControleAutomatica" method="POST" action="<?php if (isset($form_action)) echo $form_action ?>">

                    <input type="hidden" name="elementos" id="elementos">

                    <div class="row mx-auto mt-3">

                        <div class="col-3">
                            <div class="form-group">
                                <label for="opcao">Tipo</label>
                                <input type="text" name="opcao" id="opcao" class="form-control" value="<?php echo ($option == 1) ? 'Estados' : 'CNPJs'; ?>" readonly>
                            </div>
                        </div>

                        <div class="col-9">
                            <div class="form-group">
                                <label for="codigo">Produto</label>
                                <input type="text" name="codigo" id="codigo" class="form-control" value="<?php echo $produto['codigo'] . ' - ' . $produto['nome_comercial']; ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row mx-auto mt-3">
                        <div class="col-12">
                            <div class="form-group">
                                <select multiple name="listElements" id="listElements">
                                    <?php foreach($list as $item): ?>
                                        <option value="<?php echo $item['id']; ?>" <?php if($item['selected'] == 1) echo 'selected'; ?>><?php echo $item['value']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <?php if( isset($this->session->id_matriz) ): ?>

                        <div class="row mt-2 ml-1">
                            <div class="col-3">
                                <div class="checkbox">
                                    <input name="replicarMatriz" type="checkbox" id="replicarMatriz" value="1">
                                    <label class="checkbox__label" data-toggle="tooltip" title="Marcar esta opção fará com que os registros sejam os mesmos para todas as matrizes" for="replicarMatriz">Replicar para Matrizes?</label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </form>
            </div>

            <div class="modal-footer">
                <button type="submit" form="formControleAutomatica" class="btn btn-primary">Salvar</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        reloadPlugin();

        $("#selectTransfer").select2({ dropdownParent: $('#modalControleAutomatica') });

    
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

        $("#listElements").bootstrapDualListbox('refresh', true);      

        $('#formControleAutomatica').on('submit', function(e) {
            e.preventDefault();

            var $form = $(this);

            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $form.serialize(),
                dataType: "json",
                beforeSend: function(jqXHR, settings) {

                    if ( $("#elementos").val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo elementos é obrigatório!"});
                        return jqXHR.abort();
                    } 
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

                        formWarning(response);
                        $('#modalControleAutomatica').modal('hide');
                    }
                }
            });

            return false;
        });
    });
</script>