<div class="modal fade" id="modalAlterarSenha" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><?php if (isset($title)) echo $title; ?></h5>
            </div>

            <div class="modal-body">
                <p>Sua senha deve conter no mínimo 6 (seis) caracteres.</p>
                <form action="<?php if (isset($url_change_password)) echo $url_change_password; ?>" method="POST" id="formUpdatePassword">
                    <input type="hidden" id="id" name="id" value="<?php if (isset($id)) echo $id; ?>">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="senha">Nova Senha</label>
                                <input type="password" class="form-control" name="senha" id="senha" value="">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="c_senha">Confirmar nova senha</label>
                                <input type="password" class="form-control" name="c_senha" id="c_senha" value="">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">                
                <button type="submit" id="btnSalvar" class="btn btn-primary" form="formUpdatePassword">Salvar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        reloadPlugin();

        $('#formUpdatePassword').validate({
            ignore: [],
            rules : {
                senha : {
                    minlength : 6
                },
                c_senha : {
                    minlength : 6,
                    equalTo : "#senha"
                }
            },
            submitHandler: function(form) {
                $(form).ajaxSubmit({
                    dataType: 'json',
                    success: function(xhr) {
                        formWarning(xhr);
                        if (xhr.type == 'success') {
                            $('#modalAlterarSenha').modal('hide');
                        }
                    }
                })
            },
            showErrors: function($map) {
                if (this.numberOfInvalids()) {
                    $.each($map, function(k, v) {
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