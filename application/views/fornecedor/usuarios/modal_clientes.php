<div class="modal fade" id="modalAlterarSenha" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><?php if (isset($title)) echo $title; ?></h5>
            </div>

            <div class="modal-body">
                <form action="<?php if (isset($form_action)) echo $form_action; ?>" method="POST"
                      id="formClientes">
                    <input type="hidden" id="id" name="id" value="<?php if (isset($id)) echo $id; ?>">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="clientes">Selecionar Clientes</label>
                                <select data-actions-box="true" data-live-search="true" name="clientes[]" id="clientes" multiple class="form-control">
                                    <?php foreach ($clientes as $cliente) { ?>
                                        <option value="<?php echo $cliente['id']; ?>">
                                            <?php echo $cliente['cnpj']; ?> - <?php echo $cliente['nome_fantasia']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="submit" id="btnSalvar" class="btn btn-primary" form="formClientes">Salvar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        reloadPlugin();
        $('#clientes').selectpicker();

        $('#formClientes').submit(function (e){
            e.preventDefault();

           var clientes = $('#clientes').val().toString();

           $.post($(this).attr('action'), {clientes: clientes}, function (xhr){
               formWarning(xhr);
               if (xhr.type == 'success'){
                   window.location.reload();
               }
           }, 'JSON')

        });
    });
</script>