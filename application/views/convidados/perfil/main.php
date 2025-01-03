<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>

    <div class="content__inner">
        <form action="<?php if (isset($form_action)) echo $form_action; ?>" method="POST" id="formRep" enctype="multipart/form-data">
                <div class="col-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title">Dados Pessoais</h6>
                        </div>
                        <div class="card-body">
                            <div class="content__inner">
                                <input type="hidden" id="id" name="id" value="<?php if (isset($rep['id'])) echo $rep['id']; ?>">
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="">Nome</label>
                                        <input type="text" id="nome" name="nome" class="form-control" value="<?php if (isset($rep['nome'])) echo $rep['nome']; ?>" readonly style="cursor: not-allowed">
                                    </div>

                                    <div class="form-group col-6">
                                        <label for="">E-mail</label>
                                        <input type="search" id="email" name="email" class="form-control" value="<?php if (isset($rep['email'])) echo $rep['email']; ?>" readonly style="cursor: not-allowed">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Telefone Comercial</label>
                                            <input type="text" id="telefone" name="telefone_comercial" class="form-control text-center" data-inputmask="tel" value="<?php if (isset($rep['telefone_comercial'])) echo $rep['telefone_comercial']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Celular</label>
                                            <input type="text" id="celular" name="telefone_celular" class="form-control text-center" data-inputmask="cel" value="<?php if (isset($rep['telefone_celular'])) echo $rep['telefone_celular']; ?>">
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

        $('#formRep').submit(function(e) {

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
