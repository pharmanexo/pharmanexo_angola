<div class="modal fade" id="modalElementos" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><?php echo $title; ?></h5>
            </div>

            <div class="modal-body">
                <select multiple name="options" id="options">
                    <?php foreach ($options as $k => $v) : ?>
                    <option value="<?php echo $v['id'] ?>"><?php echo $v['value']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="modal-footer">
                <button type="button" id="btnSelecionar" class="btn btn-primary">Selecionar</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        var demo = $('#options').bootstrapDualListbox({
            nonSelectedListLabel: '',
            selectedListLabel: '',
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

        $('#btnSelecionar').click(function(e) {
            e.preventDefault();

            $('#selectedElements').find('option').remove().end();

            var selected = [];
            $.each($("#options option:selected"), function() {
                var value = $(this).val();
                var text = $(this).text();

                selected.push($(this).val());
                $('#selectedElements').append(new Option(text, value));
            });

            $('#selecionados').val(selected.join(','));
            $('#modalElementos').modal('hide');
        });
    });
</script>
