<?php

$tipo_usuario = $this->session->userdata("tipo_usuario");
$logado = $this->session->userdata("logado");

if (isset($header)) echo $header;

?>

<body class="bg-light">
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
                                <ul class="pagination">
                                    <li><a style="text-align:left;margin-right:75px;margin-left:10px;" id="avatarbut">Avatar</a></li>
                                    <li><a style="text-align:center;margin-right:75px;" id="gifsbut">Gifs</a></li>
                                    <li><a style="position:right;" id="animalbut">Animais</a></li>
                                </ul>
                                <!-- Avatar -->
                                <?php foreach ($fotos as $foto) {
                                    $array_fotos[] = $foto;
                                }
                                $coluna = 3;
                                $i = 0;
                                $pedaços = array_chunk($array_fotos, $coluna); ?>
                                <?php foreach ($pedaços as $pedaço) { ?>
                                    <div class="row justify-content-start" style="margin-top: 15px;">
                                        <?php foreach ($pedaço as $foto) : $i++; ?>
                                            <?php if ($i <= 9) {  ?>
                                                <div class="col-4 avatarimg">
                                                    <a id="foto" class="avatar avatarimg" value="<?php echo $foto['foto'] ?>">
                                                        <img src="<?php echo IMG_PATH . 'avatar/' . $foto['foto'] ?>" alt="Avatar" width="70" height="70">
                                                    </a>
                                                </div>
                                                <!-- Gifs -->
                                            <?php } elseif ($i > 9 && $i <= 18) { ?>
                                                <div class="col-4 gifsimg" hidden style="top:-45px;left:1px">
                                                    <a id="foto" class="avatar gifsimg" value="<?php echo $foto['foto'] ?>">
                                                        <img src="<?php echo IMG_PATH . 'avatar/' . $foto['foto'] ?>" alt="Avatar" width="70" height="70">
                                                    </a>
                                                </div>
                                                <!-- Animais -->
                                            <?php } elseif ($i > 18) { ?>
                                                <div class="col-4 animalimg" hidden style="top:-90px;left:1px">
                                                    <a id="foto" class="avatar animalimg" value="<?php echo $foto['foto'] ?>">
                                                        <img src="<?php echo IMG_PATH . 'avatar/' . $foto['foto'] ?>" alt="Avatar" width="70" height="70">
                                                    </a>
                                                </div>
                                            <?php } ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php }  ?>

                            </div>


                            <input hidden id="avatarfoto" type="text" class="avatarfoto" value="" name="avatarfoto" maxlength="100" required>



                            <div class="form-group" style="margin-top: -100px;">
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
                    </div>

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
            $("#avatarbut").click(function() {
                $(".avatarimg").attr("hidden", false);
                $(".gifsimg").attr("hidden", true);
                $(".animalimg").attr("hidden", true);
            });
            $("#gifsbut").click(function() {
                $(".gifsimg").attr("hidden", false);
                $(".avatarimg").attr("hidden", true);
                $(".animalimg").attr("hidden", true);
            });
            $("#animalbut").click(function() {
                $(".animalimg").attr("hidden", false);
                $(".gifsimg").attr("hidden", true);
                $(".avatarimg").attr("hidden", true);
            });

            $('#form').submit(function(e) {
                e.preventDefault();

                $('#postbut').html("<i class='fa fa-spin fa-spinner'></i> Validando Dados... ").attr('disabled', true);
                var $form = $(this),
                    id_avatar = $form.find("#avatarfoto").val(),
                    nick = $form.find("#nickname").val(),
                    url = $form.attr("action");

                var posting = $.post(url, {
                        id_avatar: id_avatar,
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