<div class="modal fade" id="modalImport" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">Importar Arquivo de preços</h5>
            </div>

            <div class="modal-body">
                <form id="formImport" method="POST" action="<?php if (isset($form_action)) echo $form_action ?>">
                   
                    <div class="row">
                        <?php if( isset($filiais) ): ?>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="fornecedores">Lojas</label>
                                    <select class="form-control" id="fornecedores" name="fornecedores[]" multiple="multiple" style="heigth: 60%" data-live-search="true" title="Selecione">
                                        <?php foreach($filiais as $f): ?>
                                            <option value="<?php echo $f['id']; ?>"><?php echo $f['nome_fantasia']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div> 
                        <?php else: ?>
                            <div class="col-12 d-none">
                                <div class="form-group">
                                    <label for="fornecedores">Lojas</label>
                                    <input type="text" name="fornecedores[]" id="fornecedores" value="<?php echo $this->session->id_fornecedor; ?>">
                                </div>
                            </div> 
                        <?php endif ?>

                        <div class="col-12 mt-2">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="file" id="file">
                                <label class="custom-file-label" for="file">Escolher Arquivo</label>
                            </div>
                        </div>

                    </div>
                       
                </form>
            </div>

            <div class="modal-footer">
                <button type="submit" form="formImport" class="btn btn-primary">Salvar</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        reloadPlugin();

        $('.content__inner').find('[data-toggle="dropdown"]').dropdown();

        $('#fornecedores').selectpicker();

        $(".custom-file-input").on("change", function() {
              var fileName = $(this).val().split("\\").pop();
          $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

        $('li.select2-search').find('input').css('opacity', "0");

        $('#formImport').on('submit', function(e) {
            e.preventDefault();

            var $form = $(this);

            var formData = new FormData(this);

            $.ajax({
                type: $form.attr('method'),
                url: $form.attr('action'),
                contentType: false,
                processData: false,
                cache: false,
                data: formData,
                beforeSend: function(jqXHR, settings) {

                    if ( $("#fornecedores").val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo lojas é obrigatório"});
                        return jqXHR.abort();
                    }  

                    if ( $("#file").val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo arquivo é obrigatório"});
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
                        $('#modalImport').modal('hide');
                    }
                }
            });

            return false;
        });
    });
</script>