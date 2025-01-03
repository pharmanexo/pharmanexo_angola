<div class="modal fade" id="modalRestricoes" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><?php echo $title; ?></h5>
            </div>

            <div class="modal-body">
                <?php if (isset($options)) : ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <select multiple name="elementos" id="elementos" style="width: 200px">
                                <?php foreach ($options as $key => $value) : ?>
                                <option value="<?php echo $value['id'] ?>"><?php echo $value['descricao'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <?php endif;  ?>
            </div>

            <div class="modal-footer">
                <button type="button" id="btnSelecionar" class="btn btn-primary">Selecionar</button>
                <button type="button" id="btnFechar" class="btn btn-link" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        var elem = $('#elementos').bootstrapDualListbox({
            nonSelectedListLabel: '<?php echo $label; ?>',
            selectedListLabel: '<?php echo $label; ?> Selecionados',
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

        $('#btnFechar').click(function(e) {
            e.preventDefault();
            $('#selectTransfer').val('');
        });

        $('#btnSelecionar').click(function(e) {
            e.preventDefault();

            $('#selectedElements').find('option').remove().end();

            var labelOptions = [];
            var valueOptions = [];

            $('#elementos option:selected').each(function() {
                valueOptions.push($(this).val());
                $('#selectedElements').append(new Option($(this).text(), $(this).val()));
            });

            var selectedValues = valueOptions.join(',');
            var selectedLabels = labelOptions.join(', ');

            $('#opcoes').val(selectedValues);
            $('#modalRestricoes').modal('hide');
        });
    });
</script>
