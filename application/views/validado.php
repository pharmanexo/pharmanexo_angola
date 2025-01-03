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
                <div class="card text-center shadow position-absolute " style=" margin-top: 150px" id="telaAlterarSenha">
                    <div class="card-body">
                        <h3 style="color:green">E-mail verificado com sucesso! <i class='fas fa-check' style='color:green'> </i></h3>

                        <div class="col-12 controls" style="margin-top:25px;">
                            <span style="padding-top: 10px;"><a id="voltarLogin" href="<?php echo base_url('login') ?>" style="text-decoration:none">Voltar para o login</a></span>
                        </div>
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
    <script src="https://www.google.com/recaptcha/api.js?render=6Le5i60qAAAAAKHqfRAWymsBeoyGiSf-BSIOhIvU"></script>
    <script>
        $(function() {
           
        });
    </script>
</body>