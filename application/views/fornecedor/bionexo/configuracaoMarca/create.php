<!DOCTYPE html>
<html lang="pt-BR">
<?php echo $header; ?>
<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>
<div class="content">
    <?php echo $heading; ?>
    <div class="content__inner">
        <div class="card">
            <div class="card-body">
                <form id="formConfigMarca" method="POST" action="<?php if (isset($form_action)) echo $form_action ?>" data-return="<?php if (isset($return)) echo $return ?>">

                    <input type="hidden" name="compradores" id="compradores">
                    <input type="hidden" name="marcas" id="marcas">

                    <div class="row mt-3">
                       
                       <div class="col-4">
                            <div class="form-group">
                                <label for="tipo">Tipo</label>
                                <select class="select2" name="tipo" id="tipo" data-placeholder="Selecione" data-allow-clear="true">
                                    <option></option>
                                    <option value="1" >Maior estoque</option>
                                    <option value="2" >Menor preço</option>
                                    <option value="3" >Por marca</option>
                                </select>
                            </div>
                       </div>
                    </div>

                    <div class="row mt-3" id="campoMarcas" hidden>
                        <div class="col-12">
                            <div class="form-group">
                                <select multiple name="listMarcas[]" id="listMarcas">
                                    <?php foreach($marcas as $m): ?>
                                        <option value="<?php echo $m['id']; ?>"><?php echo $m['marca']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="form-group">
                                <select multiple name="listElements[]" id="listElements">
                                    <?php foreach($compradores as $c): ?>
                                        <option value="<?php echo $c['id']; ?>"><?php echo $c['comprador']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                  
                    <?php if( isset($this->session->id_matriz) ): ?>
                        <div class="row mt-2 ml-1">
                            <div class="col-3">
                                <div class="checkbox">
                                    <input name="replicarMatriz" value="1" type="checkbox" id="replicarMatriz">
                                    <label class="checkbox__label" data-toggle="tooltip" title="Marcar esta opção fará com que os registros sejam os mesmos para todas as matrizes" for="replicarMatriz">Replicar para Matrizes?</label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>  
    </div>
</div>

<?php echo $scripts; ?>

<script>

    $(function () {
        
        reloadPlugin();

        $('#formConfigMarca').on('submit', function(e) {
            e.preventDefault();

            var $form = $(this);

            $.ajax({
                type: $form.attr('method'),
                url: $form.attr('action'),
                data: $form.serialize(),
                dataType: "json",
                beforeSend: function(jqXHR, settings) {

                    if ( $("#tipo").val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo tipo é obrigatório"});
                        return jqXHR.abort();
                    }

                    if ( $("#compradores").val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo compradores é obrigatório"});
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
                        setTimeout(function() { window.location.href = $('#formConfigMarca').data('return'); }, 1500);
                    
                    }
                }
            });

            return false;
        });

        var demo = $('#listElements').bootstrapDualListbox({
            nonSelectedListLabel: 'Compradores',
            selectedListLabel: 'Compradores Selecionados',
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

        var demoMarca = $('#listMarcas').bootstrapDualListbox({
            nonSelectedListLabel: '<a data-toogle="tooltip" title="A prioridade das marcas é conforme a seleção.">Marcas <i class="fas fa-exclamation-circle"></i></a>',
            selectedListLabel: 'Marcas Selecionados',
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
            sortByInputOrder: 'true',
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

            $('#compradores').val(values.join(','));
        });

        $('#listMarcas').on('change', function(e) {
            e.preventDefault();

            var values = [];
            $.each($("#listMarcas option:selected"), function() {
                values.push($(this).val());
            });

            $('#marcas').val(values.join(','));
        });

        $('#tipo').on('change', function(e) {
            e.preventDefault();

            if ( $(this).val() == 3 ) {

                $("#campoMarcas").prop("hidden", false);
            } else {
                
                $("#marcas").val("");
                $("#campoMarcas").prop("hidden", true);
            }
        });
    });

</script>
</body>
</html>