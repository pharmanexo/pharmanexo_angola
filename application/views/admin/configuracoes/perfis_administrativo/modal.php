<div class="modal fade" id="modalPerfil" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><?php echo $title; ?></h5>
            </div>

            <div class="modal-body">
                <form id="formPerfil" method="POST" action="<?php if (isset($form_action)) echo $form_action ?>">

                    <div class="row mt-3">
                        <div class="col">
                            <div class="form-group">
                                <label for="acao">Perfil</label>
                                <input type="text" class="form-control" name="titulo" id="titulo" value="<?php if(isset($perfil)) echo $perfil['titulo']; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="rotas">Rotas</label>
                                <select class="select2 w-100" style="width: 100%" name="rotas[]" id="rotas" multiple data-placeholder="Selecione">
                                    <option></option>
                                    <?php foreach ($rotas as $rota): ?>
                                        <option value="<?php echo $rota['id']; ?>" <?php echo ( isset($perfil) && in_array($rota['id'], explode(',', $perfil['id_rotas'])) ) ? 'selected' : '' ?>  ><?php echo $rota['rotulo']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="submit" form="formPerfil" class="btn btn-primary">Salvar</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>

    $(function() {
        reloadPlugin();

        $('#rotas').select2({dropdownParent: $('#modalPerfil'), allowClear: true });

        $('li.select2-search').find('input').css('opacity', "0");

        $("#rotas").on("select2:select", function (evt) {
            var element = evt.params.data.element;
            var $element = $(element);
            $element.detach();
            $(this).append($element);
            $(this).trigger("change");
        });



        $('#formPerfil').on('submit', function(e) {
            e.preventDefault();

            var $form = $(this);

            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $form.serialize(),
                dataType: "json",
                beforeSend: function(jqXHR, settings) {
                    if ( $('#titulo').val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo perfil é obrigatório!"});
                        return jqXHR.abort();
                    }
                },
                success: function(response) {
                    formWarning(response);
                    if (response.type === 'success') { $('#modalPerfil').modal('hide'); }
                }
            });

            return false;
        });
    });
</script>