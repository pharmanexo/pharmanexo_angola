<?php

$tipo_usuario = $this->session->userdata("tipo_usuario");
$logado = $this->session->userdata("logado");

if (isset($header)) echo $header;

?>

<body class="bg-light" >
    <div class="container-fluid" id="frameLogin">
        <div class="row" style="height: 100vh">
            <div class="col-12 col-lg-6" style="background:url(<?php echo base_url('images/img/bglogin.jpg'); ?>); background-repeat: no-repeat; background-size: cover;">
                <div class="row my-3">
                    <div class="logo">
                        <a href="/pharmanexo">
                            <img src="<?php echo base_url('images/img/logopharmanexo.png'); ?>" alt="Logo Pharmanexo">
                        </a>
                    </div>
                    <h3 style="margin-top: 110px; padding: 100px; text-align: center">
                        <p class="bluepharma"> MAIOR PORTAL DE INTEGRAÇÃO MULTIPLAFORMAS PARA PRODUTOS FARMACÊUTICOS DO BRASIL</p><br>
                        <a href="<?php echo base_url('home') ?>" class="btn1">Conheça a Pharmanexo</a>
                    </h3>
                    <div class="text-center w-100 position-absolute" style="bottom: 0">
                        <p class="bluepharma text-center">©
                            2019 - <?php echo date('Y', time()); ?> - Pharmanexo Intermediação de Negócios</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 d-flex justify-content-center bg-light formColumn">

                <!-- card login -->
                <div class="card text-center shadow position-absolute " style=" margin-top: 50px" id="telaAlterarSenha">
                    <div class="card-body">
                        <h3>Atualização de cadastro</h3>
                        <form id="form" method="post" class="frmLogin" action="<?php echo $frm_actionprimeiro; ?>">

                            <div class="form-group">
                                <label for="nickname">Escolha um Avatar</label>
                                <div class="row">
                                    <div class="col-4">
                                        <a id="foto1" class="avatar" value="1">
                                            <img src="<?php echo IMG_PATH ?>/avatar/1.png" alt="Avatar" width="70" height="70">
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a id="foto2" class="avatar" value="2">
                                            <img src="<?php echo IMG_PATH ?>/avatar/2.png" alt="Avatar" width="70" height="70">
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a id="foto3" class="avatar" value="3">
                                            <img src="<?php echo IMG_PATH ?>/avatar/3.png" alt="Avatar" width="70" height="70">
                                        </a>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 15px;">
                                    <div class="col-4">
                                        <a id="foto4" class="avatar" value="4">
                                            <img src="<?php echo IMG_PATH ?>/avatar/4.png" alt="Avatar" width="70" height="70">
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a id="foto5" class="avatar" value="5">
                                            <img src="<?php echo IMG_PATH ?>/avatar/5.png" alt="Avatar" width="70" height="70">
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a id="foto6" class="avatar" value="6">
                                            <img src="<?php echo IMG_PATH ?>/avatar/6.png" alt="Avatar" width="70" height="70">
                                        </a>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 15px;">
                                    <div class="col-4">
                                        <a id="foto7" class="avatar" value="7">
                                            <img src="<?php echo IMG_PATH ?>/avatar/7.png" alt="Avatar" width="70" height="70">
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a id="foto8" class="avatar" value="8">
                                            <img src="<?php echo IMG_PATH ?>/avatar/8.png" alt="Avatar" width="70" height="70">
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a id="foto9" class="avatar" value="9">
                                            <img src="<?php echo IMG_PATH ?>/avatar/9.png" alt="Avatar" width="70" height="70">
                                        </a>
                                    </div>
                                </div>
                                <input hidden id="avatarfoto" type="text" class="avatarfoto" value="" name="avatarfoto" maxlength="20" required>
                            </div>


                            <div class="form-group" style="margin-top: -25px;">
                                <label for="nickname">Apelido</label>
                                <p class="small">Nos informe um apelido para atualizar o seu cadastro!</p>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text "><i class="fa fa-user"></i></span>
                                    </div>
                                    <input id="nickname" type="text" class="form-control input-sm" name="nickname" placeholder="Como gostaria de ser chamado" maxlength="20" required="true">
                                </div>
                            </div>

                            <div class="form-group">
                                <!-- Button -->
                                <div class="col-12 controls">
                                    <button type="submit" id="postbut" class="btn btn-primary px-3 " style="width:200px">
                                        <i class="fas fa-check"></i> Salvar
                                    </button>
                                </div>

                                <div class="col-12 controls">
                                    <span style="padding-top: 10px;"><a id="voltarLogin" href="<?php echo base_url('login') ?>" style="text-decoration:none">Voltar para o login</a></span>
                                </div>
                            </div>
                        </form>

                        <?php $mensagem = $this->session->flashdata("mensagem"); ?>
                        <?php if (!empty($mensagem)) : ?>
                            <div class="alert alert-danger" style="margin-top:125px;"><?php echo $mensagem; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($scripts)) echo $scripts; ?>
    <script>
        $(function() {

            $(".avatar").click(function() {
                $(".avatar").removeClass('selecao');
                if ($(this).hasClass('selecao')) {
                    $(this).removeClass('selecao');
                } else {
                    $(this).addClass('selecao');
                    $(".avatarfoto").val($(this).attr('value'));
                }
            });

            $('#form').submit(function(e) {
                e.preventDefault();

                $('#postbut').html("<i class='fa fa-spin fa-spinner'></i> Validando Dados... ").attr('disabled', true);
                var $form = $(this),
                    foto = $form.find("#avatarfoto").val(),
                    nick = $form.find("#nickname").val(),
                    url = $form.attr("action");

                var posting = $.post(url, {
                        avatar: foto,
                        nickname: nick
                    })
                    .done(function(result) {
                        console.log(result);
                        if (result.type === "success") {
                            formWarning(result);
                            setTimeout(function() {
                                window.location.href = '<?php echo base_url('login'); ?>';
                            }, 2000);

                        } else {
                            formWarning(result);
                            $('#postbut').html("<i class='fas fa-check'></i> Cadastrar").attr('disabled', false);
                        }
                    })
                    .fail(function() {
                        formWarning(result);
                    });


            });

        });
    </script>
</body>