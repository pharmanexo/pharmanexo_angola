<div class="modal fade" id="modalEmail" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="width: 100%">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><?php echo $title ?></h5>
            </div>

            <div class="modal-body">
                <form id="formEmail" method="POST" action="<?php if (isset($form_action)) echo $form_action ?>">
                        <input type="hidden" name="id_fornecedor" value="<?php echo $dados['id_fornecedor'] ?>">
                        <input type="hidden" name="id_usuario" value="<?php echo $dados['id_usuario'] ?>">

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="">Celular</label>
                                <input type="text" name="numero" id="numero" data-inputmask="cel" class="text-center form-control">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <input type="checkbox" name="modulo[oc]" value="1"> Ordens de Compra
                                <br>
                                <span class="ml-3"><input type="checkbox" name="modulo[oc][acao][aprovada]" value="1" class="ml-3"> Aprovada</span>
                                <span class="ml-3"><input type="checkbox" name="modulo[oc][acao][resgate]" value="1" class="ml-3"> Resgate</span>
                                <span class="ml-3"><input type="checkbox" name="modulo[oc][acao][cancelamento]" value="1" class="ml-3"> Cancelamento</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <input type="checkbox" name="modulo[cot]" value="1"> Ordens de Compra
                                <br>
                                <span class="ml-3"><input type="checkbox" name="modulo[cot][acao][nova]" value="1" class="ml-3"> Abertura</span>
                                <span class="ml-3"><input type="checkbox" name="modulo[cot][acao][resgate]" value="1" class="ml-3"> Respondida</span>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <input type="checkbox" name="modulo[totcot]" value="1"> Total de Cotacoes
                                <br>
                                <span class="ml-3"><input type="checkbox" name="modulo[totcot][mes]" value="1" class="ml-3"> Mensal</span>
                                <span class="ml-3"><input type="checkbox" name="modulo[totcot][dia]" value="1" class="ml-3"> Diário</span>
                            </div>
                        </div>
                    </div>


                </form>
            </div>

            <div class="modal-footer">
                <button type="submit" form="formEmail" class="btn btn-primary">Salvar</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {

        reloadPlugin();

        $('#id_cliente').select2({dropdownParent: $('#modalEmail') });

        $('#formEmail').on('submit', function(e) {
            e.preventDefault();

            var $form = $(this);

            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $form.serialize(),
                dataType: "json",
                beforeSend: function(jqXHR, settings) {

                    <?php if (!isset($dados) ) { ?> 
                       
                        if ( $('#id_cliente').val() == '' ) {
                            formWarning({ type: 'warning', message: "O campo comprador é obrigatório!"});
                            return jqXHR.abort();
                        }   
                    <?php } ?> 
                },
                success: function(response) {
                    formWarning(response);
                    if (response.type === 'success') { $('#modalEmail').modal('hide'); }
                }
            });

            return false;
        });
    });
</script>