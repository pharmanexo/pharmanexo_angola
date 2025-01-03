<?php

$tipo_usuario = $this->session->userdata("tipo_usuario");
$logado = $this->session->userdata("logado");

if (isset($header)) echo $header;

?>

<body class="bg-light">
<div class="supreme-container">
    <div class="container-fluid" id="frameLogin">
        <div class="row" style="height: 100vh">
            <div class="col-12 col-lg-6"
                 style="background:url(<?php echo base_url('images/img/bglogin.jpg'); ?>); background-repeat: no-repeat; background-size: cover;">
                <div class="row my-3">
                    <div class="logo">
                        <a href="/pharmanexo">
                            <img src="<?php echo base_url('images/img/logopharmanexo.png'); ?>" alt="Logo Pharmanexo">
                        </a>
                    </div>
                    <h3 style="margin-top: 110px; padding: 100px; text-align: center">
                        <p class="bluepharma"> MAIOR PORTAL DE INTEGRAÇÃO MULTIPLAFORMAS PARA PRODUTOS FARMACÊUTICOS DO
                            BRASIL</p><br>
                        <a href="<?php echo base_url('home') ?>" class="btn1">Conheça a Pharmanexo</a>
                    </h3>
                    <div class="text-center w-100 position-absolute" style="bottom: 0">
                        <p class="bluepharma text-center">©
                            2019 - <?php echo date('Y', time()); ?> - Pharmanexo Intermediação de Negócios</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6" style="padding-top: 50px; padding-left: 100px; padding-right: 100px">
                <h3>Atualização de Dados</h3>
                <br>
                <p>Comprador: <?php if (isset($_SESSION['dados']['cnpj'])) echo $_SESSION['dados']['cnpj']; ?>
                    - <?php if (isset($_SESSION['dados']['razao_social'])) echo $_SESSION['dados']['razao_social']; ?></p>
                <hr>
                <form action="<?php echo $frm_action; ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id_comprador" value="<?php if (isset($_SESSION['dados']['id'])) echo $_SESSION['dados']['id']; ?>">
                    <div class="form-group">
                        <label for="">Seu Nome</label>
                        <input type="text" id="nome" name="nome"  required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Seu E-mail</label>
                        <input type="text" id="email" name="email" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Seu Telefone Comercial</label>
                        <input type="text" id="telefone" required name="telefone" class="form-control">
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="">Nova Senha</label>
                        <div class="input-group mb-3">
                            <input id="senhaconvidado"  type="password" class="form-control input-sm senha"
                                   placeholder="Digite sua Senha" maxlength="20" required="true">
                            <div class="input-group-append">
                                <div class="input-group-text olho" id="olho"><i class="fas fa-eye"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Confirme a senha</label>
                        <div class="input-group mb-3">
                            <input id="c_senhaconvidado"  type="password" class="form-control input-sm senha"
                                   name="senha" placeholder="Digite sua Senha" maxlength="20" required="true">
                            <div class="input-group-append">
                                <div class="input-group-text olho" id="olho"><i class="fas fa-eye"></i></div>
                            </div>
                        </div>

                        <input type="submit" value="Enviar Dados" class="btn btn-info btn-block" style="background-color: #0b0f3d">
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

<?php if (isset($scripts)) echo $scripts; ?>
<script src="https://www.google.com/recaptcha/api.js?render=6LcSlLkUAAAAAKocRTGaJgQeId06vGmoVwyTIspn"></script>
<script>
    $(function () {
        $(".olho").mousedown(function() {
            $(".senha").attr("type", "text");
        });

        $(".olho").mouseup(function() {
            $(".senha").attr("type", "password");
        });

    });
</script>
</body>