<div class="modal fade" id="modalMatriz" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><?php echo $title; ?></h5>
            </div>

            <div class="modal-body">
                <form id="formMatriz" method="POST" action="<?php if (isset($form_action)) echo $form_action ?>">

                    <div class="row mt-3">
                        <div class="col">
                            <div class="form-group">
                                <label for="acao">Nome</label>
                                <input type="text" class="form-control" name="nome" id="nome" value="<?php if(isset($matriz)) echo $matriz['nome']; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col">
                            <div class="form-group">
                                <label for="fornecedores">Fornecedores</label>
                                <select class="select2 w-100" style="width: 100%;" name="fornecedores[]" id="fornecedores" multiple data-placeholder="Selecione" data-allow-clear="true">
                                    <option></option>
                                    <?php foreach($fornecedores as $f): ?>
                                        <option value="<?php echo $f['id']; ?>"  <?php if( isset($matriz) && in_array($f['id'], $matriz['fornecedores']) ) echo 'selected' ?> ><?php echo $f['cnpj'] . ' - ' . $f['nome_fantasia']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="submit" form="formMatriz" class="btn btn-primary">Salvar</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>

    $(function() {
        reloadPlugin();

        $('#fornecedores').select2({dropdownParent: $('#modalMatriz') });

        $('li.select2-search').find('input').css('opacity', "0");

        $('#formMatriz').on('submit', function(e) {
            e.preventDefault();

            var $form = $(this);

            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $form.serialize(),
                dataType: "json",
                beforeSend: function(jqXHR, settings) {
                    if ( $('#nome').val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo nome é obrigatório!"});
                        return jqXHR.abort();
                    }

                    if ( $('#fornecedores').val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo fornecedores é obrigatório!"});
                        return jqXHR.abort();
                    }
                },
                success: function(response) {
                    formWarning(response);
                    if (response.type === 'success') { $('#modalMatriz').modal('hide'); }
                }
            });

            return false;
        });
    });
</script>