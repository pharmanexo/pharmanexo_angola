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

                    <div class="row mt-3">
                       
                       <div class="col-4">
                            <div class="form-group">
                                <label for="tipo">Tipo</label>
                                <select class="select2" name="tipo" id="tipo" data-placeholder="Selecione" data-allow-clear="true">
                                    <option></option>
                                    <option value="1" <?php if(isset($dados) && $dados['tipo'] == 1 ) echo 'selected'; ?> >Maior estoque</option>
                                    <option value="2" <?php if(isset($dados) && $dados['tipo'] == 2 ) echo 'selected'; ?> >Menor preço</option>
                                    <option value="3" <?php if(isset($dados) && $dados['tipo'] == 3 ) echo 'selected'; ?> >Por marca</option>
                                </select>
                            </div>
                       </div>
                    </div>

                    <div id="campoMarcas" <?php if( !isset($dados['marcas']) ) echo 'hidden' ?> >
                        <div class="form-row">
                            <div class="col"><label>Marca</label></div>
                            <div class="col"><label class="m">Prioridade</label></div>
                        </div>
                        <div id="marcasRow">
                            <div class="form-row">
                                <div class="col mb-2">
                                    <select class="select2 w-100" style="width: 100%" name="marcas[]" id="marcas" data-placeholder="Selecione">
                                        <option></option>
                                        <?php foreach ($marcas as $marca): ?>
                                            <option value="<?php echo $marca['id']; ?>"><?php echo $marca['marca']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col mb-2">
                                    <input type="number" name="prioridade[]" min="0" class="form-control prioridade" id="prioridade">
                                </div>
                                <div class="col-1 mt-1">
                                    <button type="button" class="btn btn-primary" id="btn-plus-marca">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php if( isset($dados['marcas']) ): ?>

                            <?php foreach($dados['marcas'] as $row ): ?>
                                <div id="marcasRow_<?php time(); ?>">
                                    <div class="form-row">
                                        <div class="col mb-2">
                                            <select class="select2 w-100" style="width: 100%" name="marcas[]" id="marcas" data-placeholder="Selecione">
                                                <option></option>
                                                <?php foreach ($marcas as $marca): ?>
                                                    <option value="<?php echo $marca['id']; ?>" <?php if ($marca['id'] == $row['id_marca']) echo 'selected'; ?> ><?php echo $marca['marca']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col mb-2">
                                            <input type="number" name="prioridade[]" min="0" class="form-control prioridade" id="prioridade" value="<?php echo $row['prioridade']; ?>">
                                        </div>
                                        <div class="col-1 mt-1">
                                            <button type="button" class="btn btn-danger btn-minus-marca">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="comprador">Comprador</label>
                                <input type="text" class="form-control" id="comprador" value="<?php echo $comprador['comprador']; ?>" disabled>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>  
    </div>
</div>

<?php echo $scripts; ?>

<script>


    $(function () {
        reloadPlugin();
        
        $('#btn-plus-marca').on('click', function (e) {
            e.preventDefault();

            var count_input = $('#marcasRow input.prioridade').length;
            var formSelect = $('#marcasRow').find('select');

            if (count_input >= 5) { return; }

            if ( formSelect.val() == '' ) {
                formWarning({type: "warning", message: "O campo marca é obrigatório!"});
                return;
            } 

            var selected = formSelect.find(':selected');
            var elements = $('#marcasRow').clone();

            if ( elements.find('input.prioridade').val() == '' ) {
                formWarning({type: "warning", message: "O campo prioridade é obrigatório!"});
                return;
            }

          
            elements.find('select').val(selected.val()).attr('readonly', true);
            elements.find('input').attr('readonly', true);
            elements.find('select option:not(:selected)').remove();


            $('#marcasRow').find('input').val('');

            elements.find('.btn').html('<i class="fas fa-minus"></i>').addClass('btn-danger').click(function (e) {
                var elementSelected = elements.find('select').find(':selected');

                var option = $('<option></option>');
                option.val(elementSelected.val());
                option.text(elementSelected.text());

                formSelect.append(option);

                formSelect.html($("option", formSelect).sort(function (a, b) {
                    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                }));


                formSelect.find('option:first-child').attr('selected', true);

                elements.remove();
            });

            $('#campoMarcas').append(elements);
            selected.remove();
        });

        $(".btn-minus-marca").each(function(i, v) {
            console.log(v);
            $(v).on("click", function (e) {
                e.preventDefault();

                var elements = $(this).parent().parent().parent();
                elements.remove();
            });

        });

        $('#formConfigMarca').on('submit', function(e) {
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
                        setTimeout(function() { window.location.href = $('#formConfigMarca').data('return'); }, 1500);
                    
                    }
                }
            });

            return false;
        });

        $('#tipo').on('change', function(e) {
            e.preventDefault();

            if ( $(this).val() == 3 ) {

                $("#campoMarcas").prop("hidden", false);
            } else {

                $("#campoMarcas").prop("hidden", true);
            }
        });
    });

</script>
</body>
</html>