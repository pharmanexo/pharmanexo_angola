<div class="modal fade" id="modalVendaDiferenciada" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><?php echo $title; ?></h5>
            </div>

            <div class="modal-body">
                <form method="POST" id="formUpdate" action="<?php echo (isset($form_action)) ? $form_action : ''; ?>">
                    <input type="hidden" name="id" value="<?php echo $dados['id']; ?>">

                    <div class="row mx-auto mt-3">
                        <div class="col-md-8">
                            <label for="produto">Produto</label>
                            <input type="text" class="form-control" value="<?php echo $dados['produto_descricao']; ?>" disabled>
                        </div>

                        <div class="col-md-4">
                            <label for="">Estoque</label>
                            <input type="text" class="form-control" value="<?php echo $dados['estoque_uf']; ?>" disabled>
                        </div>
                    </div>

                    <?php if(isset($dados['estado'])) { ?>
                        <div class="row mx-auto mt-3">
                            <div class="col-md-3">
                                <label for="">Estado</label>
                                <input type="text" class="form-control" value="<?php echo $dados['estado']; ?>" disabled>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="desconto">Desconto Percentual</label>
                                    <input type="text" class="form-control" name="desconto_percentual" id="desconto_percentual" value="<?php echo number_format($dados['desconto_percentual'], 2, ',', '.') ?>" data-inputmask="money">
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="row mx-auto mt-3">

                            <div class="col-md-8">
                                <label for="">CNPJ</label>
                                <input type="text" class="form-control" value="<?php echo $dados['cliente']; ?>" disabled>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="desconto">Desconto Percentual</label>
                                    <input type="text" class="form-control" name="desconto_percentual" id="desconto_percentual" value="<?php echo number_format($dados['desconto_percentual'], 2, ',', '.'); ?>" data-inputmask="money">
                                </div>
                            </div>
                        </div>
                    <?php }  ?>
                </form>
            </div>

            <div class="modal-footer">
                <button type="submit" id="btnSalvar" form="formUpdate" class="btn btn-primary">Salvar</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {

        reloadPlugin();

        $('#lote').select2({
            dropdownParent: $('#modalVendaDiferenciada'),
            dropdownAutoWidth: true,
            width: '100%'
        });

        $('#formUpdate').on('submit', function(e) {
            e.preventDefault();

            var $form = $(this);

            $.ajax({
                type: $form.attr('method'),
                url: $form.attr('action'),
                data: $form.serialize(),
                dataType: 'json',
                beforeSend: function(jqXHR, settings) {
                    if ( $("#desconto_percentual").val() == '' ) {

                        formWarning({ type: 'warning', message: "O campo desconto percentual é obrigatório!"});
                        return jqXHR.abort();
                    }
                },
                success: function(response) {

                    if (response.type === 'warning') {

                        if ( typeof response.message == 'string' ) {
                            response.message = {message: response.message };
                        }

                        $.each(response.message, function (i, v) {
                            formWarning({
                                type: 'warning',
                                message: v
                            });
                        });
                    } else {

                        formWarning({type: 'success', message: response.message });
                        $('#modalVendaDiferenciada').modal('hide');
                    }
                }
            });
        })
    })
</script>