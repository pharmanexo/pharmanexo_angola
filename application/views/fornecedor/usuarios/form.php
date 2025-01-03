<div class="modal fade" id="modalUsuario" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">Cadastro de Usuário</h5>
            </div>

            <div class="modal-body">
                <form action="<?php if (isset($form_action)) echo $form_action; ?>" method="POST" onload="javascript: document.formulario.reset()" id="formUsuario" autocomplete="anyrandomstring">
                    <input type="hidden" id="id" name="id" value="<?php if (isset($id)) echo $id; ?>">
                    <div class="row">
                        <div class="form-group col-12 col-lg-5">
                            <label for="">Nome</label>
                            <input type="text" id="nome" name="nome" required class="form-control" value="<?php if (isset($usuario['nome'])) echo $usuario['nome']; ?>">
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label for="">Tipo</label>
                            <select name="nivel" id="nivel" required class="form-control">
                                <option value="">Selecione</option>
                                <option <?php if (isset($usuario['tipo']) && $usuario['tipo'] == '1') echo 'selected' ?> value="1">Administrador</option>
                                <option <?php if (isset($usuario['tipo']) && $usuario['tipo'] == '2') echo 'selected' ?> value="2">Comercial</option>
                                <option <?php if (isset($usuario['tipo']) && $usuario['tipo'] == '3') echo 'selected' ?> value="3">Financeiro</option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label for="">E-mail</label>
                            <input type="search" id="email" name="email" required autocomplete="random" class="form-control" value="<?php if (isset($usuario['email'])) echo $usuario['email']; ?>" <?php echo (isset($atualizacao)) ? 'readonly' : '' ?> >
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label for="">Telefone Comercial</label>
                            <input type="text" id="telefone" name="telefone" REQUIRED class="form-control text-center" data-inputmask="tel" value="<?php if (isset($usuario['telefone'])) echo $usuario['telefone']; ?>">
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label for="">Celular</label>
                            <input type="text" id="celular" name="celular" required class="form-control text-center" data-inputmask="cel" value="<?php if (isset($usuario['celular'])) echo $usuario['celular']; ?>">
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <?php if ($this->session->tipo == 1) { ?>
                    <a href="<?php if (isset($url_resetPass)) echo $url_resetPass ?>" id="btnResetSenha" class="btn btn-warning"><i class="fas fa-unlock-alt"></i></i> Resetar Senha</a>
                    <a href="<?php if (isset($url_delete)) echo $url_delete ?>" id="btnDelete" class="btn btn-danger"><i class="fas fa-trash"></i></a>
                <?php } ?>
                <button type="submit" id="btnSalvar" class="btn btn-primary" form="formUsuario">Salvar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<?php if (isset($scripts)) echo $scripts; ?>

<script>

    $(function () {
        reloadPlugin();

        password_popover('#senha', '#c_senha');

        $('#btnDelete').showConfirm({
            title: 'Excluir Registro',
            closeOnConfirm: true,
            ajax: {
                type: 'post',
                url: $('#btnDelete').attr('href'),
                dataType: 'json',

                success: function (response) {
                    formWarning(response);
                    $('#modalUsuario').modal('hide');
                }
            }
        });

        $('#btnResetSenha').showConfirm({
            title: 'Resetar Senha do Usuário',
            closeOnConfirm: true,
            ajax: {
                type: 'post',
                url: $('#btnResetSenha').attr('href'),
                dataType: 'json',

                success: function (response) {
                    formWarning(response);
                }
            }
        });

        $('#formUsuario').validate({
            ignore: [],
            rules: {
                nome: {
                    required: true
                },
                email: {
                    required: true
                },
                senha: {
                    minlength: 8
                },
                c_senha: {
                    minlength: 8,
                    equalTo: "#senha"
                }
            },
            submitHandler: function (form) {
                $(form).ajaxSubmit({
                    dataType: 'json',
                    success: function (xhr) {
                        formWarning(xhr);
                        if (xhr.type == 'success') {
                            $('#modalUsuario').modal('hide');
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



        var pass = document.getElementById("senha");

        if (pass != null) {

            var senha = document.getElementById("senha");
            var c_senha = document.getElementById("c_senha");

            senha.addEventListener('keyup', function() { checkPassword(senha.value, c_senha.value); });
            senha.addEventListener('focus', function() { checks(null, senha.value, c_senha.value); });
        } 
    });
</script>