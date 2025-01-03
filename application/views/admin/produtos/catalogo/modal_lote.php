<div class="modal fade" id="modalLote" role="dialog" style="display: none;" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title pull-left"><?php if (isset($title)) echo $title; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="<?php if (isset($form_action)) echo $form_action; ?>" method="POST" id="formLote">
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="recipient-name" class="col-form-label">Validade</label><br>
                <input type="text" class="form-control" name="validade" id="validade" data-inputmask="date" value="<?php echo (isset($dados['validade'])) ? $dados['validade'] : ''  ?>" <?php echo (isset($dados)) ? 'disabled' : 'required'  ?>>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="recipient-name" class="col-form-label">Local</label><br>
                <input type="text" class="form-control" name="local" id="local" value="<?php echo (isset($dados['local'])) ? $dados['local'] : ''  ?>" <?php echo (isset($dados)) ? '' : ''  ?>>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="message-text" class="col-form-label">Estoque</label>
                <input type="text" name="estoque" class="form-control" id="estoque" value="<?php echo (isset($dados)) ? $dados['estoque'] : '' ?>" <?php echo (isset($dados)) ? '' : 'required'  ?>>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="message-text" class="col-form-label">Lote</label>
                <input type="text" name="lote" class="form-control" id="lote" value="<?php echo (isset($dados)) ? $dados['lote'] : '' ?>" <?php echo (isset($dados)) ? 'readonly' : 'required'  ?>>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" id="btnNovo" class="btn btn-primary" form="formLote">Salvar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<?php if (isset($scripts)) echo $scripts; ?>

<script>

  $(function () {
    reloadPlugin();

    $('#unidade_ab').select2({
      dropdownParent: $('#modalLote')
    });

    $('#formLote').validate({
      ignore: [],
      rules: {
        validade: { required: true },
        estoque: { required: true },
        lote: { required: true }
      },
      messages: {
        validade: { required: "O campo validade é obrigatório." },
        estoque: { required: "O campo estoque é obrigatório." },
        lote: { required: "O campo lote é obrigatório." }
      },
      submitHandler: function (form) {
        $(form).ajaxSubmit({
          dataType: 'json',
          success: function (xhr) {
            formWarning(xhr);
            if (xhr.type == 'success') { $('#modalLote').modal('hide'); }
          }
        })
      },
      showErrors: function ($map) {
        if (this.numberOfInvalids()) {
          $.each($map, function (k, v) {
            formWarning({
              type: 'warning',
              message: v
            });
          });
        }
      }
    });

  });
</script>