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
                               <img id="imgPrev" src="<?php echo ( !empty($this->session->foto) && !is_null($this->session->foto)  ) ? base_url("public/usuarios/{$this->session->id_usuario}/{$this->session->foto}") : base_url("/images/usuarios/no-user.png"); ?>" alt="Imagem" class="img-fluid rounded-circle w-50">
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
                                        <label for="">Nome</label>
                                        <input type="text" id="nome" name="nome" class="form-control" value="<?php if (isset($usuario['nome'])) echo $usuario['nome']; ?>" readonly style="cursor: not-allowed">
                                    </div>

                                    <div class="form-group col-6">
                                        <label for="">E-mail</label>
                                        <input type="search" id="email" name="email" class="form-control" value="<?php if (isset($usuario['email'])) echo $usuario['email']; ?>" readonly style="cursor: not-allowed">
                                    </div>
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

<?php if (isset($scripts)) echo $scripts; ?>

<script>
    $(function () {

        password_popover('#senha', '#c_senha');

        $("#foto").change(function () { readURL(this); });
        
        var senha = document.getElementById("senha");
        var c_senha = document.getElementById("c_senha");

        senha.addEventListener('keyup', function() { checkPassword(senha.value, c_senha.value); });
        senha.addEventListener('focus', function() { checks(null, senha.value, c_senha.value); });

        $('#formUsuario').submit(function(e) {

            var resp = validaPassword($('#senha').val(), $('#c_senha').val());

            if ( $('#senha').val() != "" && resp != 1 ) {
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
            reader.onload = function (e) {
                $('#imgPrev').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

</script>
</body>

</html>