<div class="modal fade" id="modalPreco"  role="dialog" style="display: none;" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title pull-left"><?php if (isset($title)) echo $title; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="<?php if (isset($form_action)) echo $form_action; ?>" method="POST" id="formPreco">
          <div class="row">  
            <div class="col-7">
              <label for="recipient-name" class="col-form-label">Estado</label><br>
                <select class="select2 w-100" name="id_estado" id="id_estado" style="width: 100%" required>
                  <option value="">Selecione</option>
                  <option value="30" <?php echo (isset($dados) && $dados['id_estado'] == null ) ? 'selected' : '' ?> >Todos</option>
                  <?php foreach ($estados as $estado) { ?>
                      <option value="<?php echo $estado['id'] ?>" <?php echo (isset($dados) && $dados['id_estado'] == $estado['id'] ) ? 'selected' : '' ?>><?php echo $estado['descricao'] ?></option>
                  <?php } ?>
                </select>
            </div>
            <div class="col-5">
              <label for="message-text" class="col-form-label">Preço Unitário</label>
              <input type="text" name="preco" class="form-control" id="preco" <?php if(!isset($dados)) echo 'data-inputmask="money4"' ?> value="<?php echo (isset($dados)) ? number_format($dados['preco_unitario'], 2, ",", ".") : '' ?>" <?php (isset($dados)) ? '' : 'required'  ?>>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" id="btnNovo" class="btn btn-primary" form="formPreco">Salvar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<?php if (isset($scripts)) echo $scripts; ?>

<script>

  var url_delete = $('#formPreco').data('delete');

  $(function () {
    reloadPlugin();

    $('#id_estado').select2({
      dropdownParent: $('#modalPreco')
    });

    $('#formPreco').validate({
      ignore: [],
      rules: {
        id_estado: { required: true },
        preco: { required: true },
      },
      messages: {
        id_estado: { required: "O campo estado é obrigatório." },
        preco: { required: "O campo preço é obrigatório." },
      },
      submitHandler: function (form) {
        $(form).ajaxSubmit({
          dataType: 'json',
          success: function (xhr) {
            formWarning(xhr);
            if (xhr.type == 'success') { $('#modalPreco').modal('hide'); }
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