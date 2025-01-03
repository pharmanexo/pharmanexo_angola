<div class="modal fade" id="modalAlterarSenha" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><?php if (isset($title)) echo $title; ?></h5>
            </div>

            <div class="modal-body">
                <form action="<?php if (isset($form_action)) echo $form_action; ?>" method="POST"
                      id="formEstados">
                    <input type="hidden" id="id" name="id" value="<?php if (isset($id)) echo $id; ?>">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="estados">Selecionar Estados</label>
                                <select name="estados[]" id="estados" multiple class="form-control">
                                    <?php foreach ($estados as $estado) { ?>
                                        <option value="<?php echo $estado['id']; ?>"><?php echo $estado['descricao']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="submit" id="btnSalvar" class="btn btn-primary" form="formEstados">Salvar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        reloadPlugin();
        $('#estados').selectpicker();

        $('#formEstados').submit(function (e){
            e.preventDefault();

           var estados = $('#estados').val().toString();

           $.post($(this).attr('action'), {estados: estados}, function (xhr){
               formWarning(xhr);
               if (xhr.type == 'success'){
                   window.location.reload();
               }
           }, 'JSON')

        });
    });
</script>