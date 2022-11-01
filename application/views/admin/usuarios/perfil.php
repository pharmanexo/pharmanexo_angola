<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
    <?php echo $navbar; ?>
    <?php echo $sidebar; ?>

    <div class="content">
        <?php echo $heading; ?>

        <div class="content__inner">
            <form action="<?php if (isset($form_action)) echo $form_action; ?>" method="POST" id="formUsuario" enctype="multipart/form-data">
                <div class="row mx-auto mt-3">
                    <div class="col-12 col-lg-4 text-center">
                        <div class="card">
                            <div class="card-body">
                                <div class="imgPreview">
                                    <?php $avatar = $this->session->avatar ?>
                                    <?php $url = IMG_PATH . 'avatar/' . $avatar . '.png' ?>
                                    <img id="imgPrev" src="<?php echo $url ?>" alt="Imagem" class="img-fluid rounded-circle w-50">
                                    <?php if ($this->session->verifica != "1") { ?>
                                        <i style="font-size:24px;color: green;position: relative;top: 40px;left:-10px" class="fa fa-lock"></i>
                                    <?php } else { ?>
                                        <i style="font-size:24px;color: red;position: relative;top: 40px;left:-5px" class="fa fa-exclamation"></i>
                                    <?php } ?>
                                </div>
                                <label class="btn btn-outline-primary btn-block mt-3" for="foto">
                                    <input type="file" name="foto" id="foto" class="d-none">
                                    Trocar Imagem
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">Dados Pessoais</h6>
                            </div>
                            <div class="card-body">
                                <div class="content__inner">
                                    <input type="hidden" id="id" name="id" value="<?php if (isset($usuario['id'])) echo $usuario['id']; ?>">
                                    <div class="row">
                                        <div class="form-group col-6">
                                            <label for="">Apelido</label>
                                            <input type="text" id="nome" name="nome" class="form-control" value="<?php if ($this->session->nickname) echo $this->session->nickname; ?>" readonly style="cursor: not-allowed">
                                        </div>

                                        <?php if ($this->session->verifica == "1") { ?>
                                            <form id="formVerifica" method="post" action="<?php $frm_actionverifica ?>">
                                                <div class="form-group col-6 input-group">
                                                    <label for="">E-mail</label>
                                                    <div class="input-group mb-2">
                                                        <input type="text" id="email" name="email" class="form-control email" value="<?php if (isset($usuario['email'])) echo $usuario['email']; ?>" readonly style="cursor: not-allowed">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text" style="color: red;"><i class="fas fa-exclamation"></i></div>
                                                        </div>
                                                    </div>

                                                    <span><a id="verificaEmail" style="border:0px solid white;background-color:white;color:#ed3237;font-weight:bold;font-size:15px;text-decoration:none">Verifique seu e-mail!</a></span>

                                                </div>
                                            </form>
                                        <?php } else { ?>

                                            <div class="form-group col-6">
                                                <label for="">E-mail</label>
                                                <div class="input-group mb-2">
                                                    <input type="search" id="email" name="email" class="form-control email" value="<?php if (isset($usuario['email'])) echo $usuario['email']; ?>" readonly style="cursor: not-allowed">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text" style="color: green;"><i class="fas fa-check"></i></div>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php } ?>

                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">Telefone Comercial</label>
                                                <input type="text" id="telefone" name="telefone" class="form-control text-center" data-inputmask="tel" value="<?php if (isset($usuario['telefone'])) echo $usuario['telefone']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">Celular</label>
                                                <input type="text" id="celular" name="celular" class="form-control text-center" data-inputmask="cel" value="<?php if (isset($usuario['celular'])) echo $usuario['celular']; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">Trocar Senha</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="senha">Senha</label>
                                            <input type="password" id="senha" name="senha" placeholder="******" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="c_senha">Confirme a senha</label>
                                            <input type="password" id="c_senha" name="c_senha" placeholder="******" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php $mensagem = $this->session->flashdata("mensagem"); ?>
    <?php if (!empty($mensagem)) : ?>
        <div class="alert alert-danger" style="margin-top:125px;"><?php echo $mensagem; ?></div>
    <?php endif; ?>

    <?php if (isset($scripts)) echo $scripts; ?>

    <script>
        $(function() {

            $('#verificaEmail').click(function(e) {
                e.preventDefault();
                $('#verificaEmail').html("<i class='fa fa-spin fa-spinner'></i> Validando Dados... ").attr('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>/login/verificar_email",
                    data: {
                        id: $("#id").val(),
                        email: $("#email").val()
                    },
                    success: function(response) {
                        console.log(response)
                        if (response.type === 'success') {
                            formWarning(response);
                            $('#verificaEmail').html("<i class='fas fa-check' style='color:green'> </i>").attr('disabled', false);
                        } else {
                            formWarning(response)
                        }
                    }
                });


            });

            password_popover('#senha', '#c_senha');

            $("#foto").change(function() {
                readURL(this);
            });

            var senha = document.getElementById("senha");
            var c_senha = document.getElementById("c_senha");

            senha.addEventListener('keyup', function() {
                checkPassword(senha.value, c_senha.value);
            });
            senha.addEventListener('focus', function() {
                checks(null, senha.value, c_senha.value);
            });

            $('#formUsuario').submit(function(e) {

                var resp = validaPassword($('#senha').val(), $('#c_senha').val());

                if ($('#senha').val() != "" && resp != 1) {
                    e.preventDefault();
                    formWarning({
                        type: 'warning',
                        message: "Senha inválida!"
                    });
                }
            });
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#imgPrev').attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>