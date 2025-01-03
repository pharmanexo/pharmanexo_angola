<div class="modal fade" id="modalComissao" role="dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><?php if (isset($title)) echo $title; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php if (isset($form_action)) echo $form_action; ?>" method="POST" id="formComissao" data-delete="<?php echo base_url("admin/representantes/delete_comissao/") ?>">
                    <input type="hidden" name="id_representante" value="" id="id_representante">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Fornecedor</label><br>
                        <select class="select2 w-100" style="width: 100%" name="id_fornecedor" id="id_fornecedor" multiple required>
                            <option value="">Selecione</option>
                            <?php foreach ($fornecedores as $f) { ?>
                                <option value="<?php echo $f['id'] ?>" <?php echo (isset($dados) && ($dados['id_fornecedor'] == $f['id'])) ? 'selected' : '' ?> ><?php echo $f['razao_social'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Comissão</label>
                        <input type="number" id="comissao" name="comissao" class="form-control" value="<?php echo (isset($dados)) ? $dados['comissao'] : '' ?>" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" id="btnNovo" class="btn btn-primary" form="formComissao">Salvar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<?php if (isset($scripts)) echo $scripts; ?>

<script>

    var url_delete = $('#formComissao').data('delete');

    $(function () {
        reloadPlugin();

        $('#id_fornecedor').select2({
            placeholder: {
                id: '-1', // the value of the option
                text: 'Selecione'
            },
            containerCssClass: 'multi_select',
            dropdownParent: $('#modalComissao'),
        });

        $('li.select2-search').find('input').css('opacity', "0");

        $('#formComissao').validate({
            ignore: [],
            rules: {
                id_fornecedor: {required: true},
                comissao: {required: true},
            },
            messages: {
                id_fornecedor: {required: "O campo fornecedor é obrigatório."},
                comissao: {required: "O campo comissão é obrigatório."},
            },
            submitHandler: function (form) {
                $(form).ajaxSubmit({
                    dataType: 'json',
                    success: function (xhr) {
                        formWarning(xhr);
                        if (xhr.type == 'success') {
                            $('#modalComissao').modal('hide');
                        }
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