<?php

$tipo_usuario = $this->session->userdata("tipo_usuario");
$logado = $this->session->userdata("logado");

if (isset($header)) echo $header;

?>

<body class="bg-light" style="">
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
                <div class="card text-center shadow position-absolute " style=" margin-top: 150px" id="telaAlterarSenha">
                    <div class="card-body">
                        <h3>Atualização de cadastro</h3>
                        <p class="small">Nos informe um nickname para atualizar o seu cadastro!</p>
                        <form id="form" method="post" class="frmLogin" action="<?php echo $frm_actionprimeiro; ?>">
                            <div class="form-group">
                                <label for="nickname">Nickname</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text "><i class="fa fa-user"></i></span>
                                    </div>
                                    <input id="nickname" type="text" class="form-control input-sm" name="nickname" placeholder="Como gostaria de ser chamado" maxlength="20" required="true">
                                </div>
                            </div>

                            <div style="margin-top:25px" class="form-group">
                                <!-- Button -->
                                <div class="col-12 controls" style="margin-top:25px;">
                                    <button type="submit" id="postbut" class="btn btn-primary px-3 " style="width:200px">
                                        <i class="fas fa-check"></i> Salvar
                                    </button>
                                </div>

                                <div class="col-12 controls" style="margin-top:25px;">
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

            $('#form').submit(function(e) {
                e.preventDefault();

                $('#postbut').html("<i class='fa fa-spin fa-spinner'></i> Validando Dados... ").attr('disabled', true);

                var $form = $(this),
                    term = $form.find("#nickname").val(),
                    url = $form.attr("action");

                var posting = $.post(url, {
                        nickname: term
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