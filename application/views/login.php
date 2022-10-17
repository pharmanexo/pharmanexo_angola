<?php

$tipo_usuario = $this->session->userdata("tipo_usuario");
$logado = $this->session->userdata("logado");

if (isset($header)) echo $header;

?>

<body class="bg-light" style="">
<div class="supreme-container">
    <div class="container-fluid" id="frameLogin">
        <div class="row" style="height: 100vh">
            <div class="col-12 col-lg-6"
                 style="background:url(<?php echo base_url('images/bg-login.jpg'); ?>); background-repeat: no-repeat; background-size: cover;">
                <div class="row my-3">
                    <div class="co mt-4 ml-3">
                        <a href="/pharmanexo"><img class="d-block"
                                                   src="<?php echo base_url('images/img/logo-branca.png'); ?>"></a>
                    </div>
                    <div class="col text-right">
                        <div id="social" class="d-none d-sm-block" style="color: #fff">
                            <ul>
                                <li><a href="https://facebook.com/pharmanexo" target="_blank"><i
                                                class="fab fa-2x fa-facebook"></i></a></li>
                                <li><a href="https://instagram.com/pharmanexo" target="_blank"><i
                                                class="fab fa-2x fa-instagram"></i></a></li>
                                <li><a href="https://linkedin.com/company/pharmanexo" target="_blank"><i
                                                class="fab fa-2x fa-linkedin"></i></a></li>
                            </ul>
                        </div>
                        <div>
                        </div>

                    </div>
                    <h3 class="text-white" style="margin-top: 180px; padding: 100px; text-align: center">
                        O MAIOR PORTAL DE OPORTUNIDADES EM PRODUTOS FARMACÊUTICOS E MATERIAIS MÉDICO HOSPITALARES DO
                        BRASIL
                        <br><br>
                        <a href="<?php echo base_url('home') ?>" class="btn btn-light mt-3 px-5">Conheça a
                            Pharmanexo</a>
                    </h3>
                    <div class="text-center w-100 position-absolute" style="bottom: 0"><p
                                class="text-white text-center">©
                            2019 - <?php echo date('Y', time()); ?> - Pharmanexo Intermediação de Negócios</p></div>
                </div>
            </div>
            <div class="col-12 col-lg-6 d-flex justify-content-center bg-light formColumn">

                <div class="container" style="margin-top: 150px; margin-left: -80px;">
                    <div class="row justify-content-md-center">
                        <div class="col col-lg-6 mx-4">
                            <a id="acessoIntegranexo" data-toggle="modal" data-target="#modalIntegranexo">
                                <img src="<?php echo ASSETS_PATH ?>/img/integranexo.jpg"
                                     alt="Integranexo"
                                     width="250" height="250">
                            </a>
                        </div>
                        <div class="col col-lg-3 mx-4">
                            <a id="acessoRepresentante" data-toggle="modal" data-target="#modalRepresentante">
                                <img src="<?php echo ASSETS_PATH ?>/img/representantes.jpg"
                                     alt="Representantes"
                                     width="250" height="250">
                            </a>
                        </div>
                    </div>
                    <div class="row justify-content-md-center mt-4">
                        <div class="col col-lg-6 mx-4">
                            <a id="acessoDistribuidor" data-toggle="modal" data-target="#modalDistribuidor">
                                <img src="<?php echo ASSETS_PATH ?>/img/distribuidor.jpg"
                                     alt="Distribuidor"
                                     width="250" height="250">
                            </a>
                        </div>
                        <div class="col col-lg-3 mx-4">
                            <a id="acessoCompraColetiva" data-toggle="modal" data-target="#modalCompraColetiva">
                                <img src="<?php echo ASSETS_PATH ?>/img/compracoletiva.jpg"
                                     alt="Compra Coletiva"
                                     width="250" height="250">
                            </a>
                        </div>
                    </div>

                </div>

                <!-- Cadastre-se -->
                <div class="mb-4 w-100" hidden style="margin-top:10px; margin-bottom:10px;">
                    <h5 class=" mx-auto text-center"> Não tem acesso? Cadastre-se...</h5>

                    <div class="row  text-center">
                        <div class="col-6  ">
                            <a href="<?php echo base_url('Cliente/cadastro') ?>" style="text-decoration: none;">
                                <button type="button" class="btn float-right btn-info  " style="width:132px;"><i
                                            class="fas fa-users"></i> Cliente
                                </button>
                            </a>
                        </div>

                        <div class="col-6 ">
                            <a href="<?php echo base_url('Fornecedor') ?>" style="text-decoration: none;">
                                <button type="button" class="btn float-left btn-info "
                                        data-sitekey="6LcnlLkUAAAAAPg2mSRJPKhYX_mrRCx99FUwFuj2"
                                        data-callback='onSubmit'><i
                                            class="fas fa-building"></i>
                                    Fornecedor
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal login Integranexo -->
<div class="modal fade text-center" id="modalIntegranexo" tabindex="-1" role="dialog" aria-labelledby="modalIntegranexo"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content align-content-center" style="box-shadow:0px 3px 6px 4px #0041a0;">
            <div class="modal-header text-center">
                <h3 class="modal-title w-100" style="margin-top: 10px; position: absolute; left: 2px;">
                    Portal Pharmanexo</h3>
            </div>
            <div class="modal-body">
                <form id="form" method="post" class="frmLogin" action="<?php echo $frm_action; ?>">
                    <div class="form-group">
                        <div class="input-group mb-3 mt-5">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                            </div>
                            <input id="login" type="text" class="form-control input-sm" name="login"
                                   placeholder="Digite seu Login" maxlength="50" required="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text "><i class="fa fa-key"></i></span>
                            </div>
                            <input id="senha" type="password" class="form-control input-sm senha" name="senha"
                                   placeholder="Digite sua Senha" maxlength="20" required="true">
                            <div class="input-group-append">
                                <div class="input-group-text olho" id="olho"><i class="fas fa-eye"></i></div>
                            </div>
                        </div>
                    </div>


                    <div style="margin-top:25px" class="form-group">
                        <!-- Acesso ao sistema -->
                        <div class="col-12 controls" style="margin-top:25px;">
                            <button type="submit" id="postbut" class="btn btn-primary px-3 "
                                    style="width:200px">
                                <i class="fas fa-check"></i> Acessar Sistema
                            </button>
                        </div>
                        <!-- Cadastro -->
                        <div class="col-12 controls" style="margin-top:25px;">
                            <button type="submit" id="cadPharmanexo" class="btn btn-secondary px-3 "
                                    style="width:150px">
                                <i class="fas fa-paperclip"></i> Cadastre-se
                            </button>
                        </div>
                        <!-- Recuperação de senha -->
                        <div class="col-12 controls" style="margin-top:25px;">
                            <span style="padding-top: 10px;">
                                <a data-toggle="modal"
                                   data-dismiss="modal"
                                   data-target="#modalNovaSenha"
                                   style="text-decoration:none">Esqueceu sua senha?
                                </a>
                            </span>
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

<!-- Modal nova senha-->
<div class="modal fade text-center" id="modalNovaSenha" tabindex="-1" role="dialog"
     aria-labelledby="modalNovaSenha"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content align-content-center">
            <div class="modal-header text-center">
                <h3 class="modal-title w-100"
                    style="margin-top: 5px;
                    position: absolute;
                    left: 0px;">
                    Recuperação de Senha</h3>
            </div>
            <div class="modal-body" style="margin-top: 40px">
                <form id="formRecuperarSenha" method="post" action="<?php echo $frm_novasenha; ?>">
                    <h3>Perdeu sua senha? </h3>
                    <p class="small">Não tem problema, informe o e-mail cadastrado e iremos te ajudar a
                        recuperar sua senha.</p>
                    <!-- <div class="form-group">
                         <div class="input-group mb-3 mt-5">
                             <div class="input-group-prepend border-0">
                                 <span class="input-group-text border-right-0"><i class="fa fa-user"></i></span>
                             </div>
                             <input id="cnpj" type="text" class="form-control border-left-0 input-sm" name="cnpj" placeholder="Digite seu CNPJ" maxlength="50" required="true">
                         </div>
                     </div>-->
                    <div class="form-group">
                        <div class="input-group mb-3 mt-5">
                            <div class="input-group-prepend border-0">
                                        <span class="input-group-text border-right-0"><i
                                                    class="fa fa-envelope"></i></span>
                            </div>
                            <input id="loginUser" type="text" class="form-control border-left-0 input-sm"
                                   placeholder="Digite seu E-mail/Login" maxlength="50" required="true">
                        </div>
                    </div>
                    <div style="margin-top:25px" class="form-group">
                        <!-- Button -->
                        <div class="col-12 controls" style="margin-top:25px;">
                            <button type="submit" id="btnRecuperarSenha" form="formRecuperarSenha"
                                    class="btn btn-success " style="width:200px">
                                Recuperar Senha
                            </button>
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

<!-- Modal login Representante -->
<div class="modal fade text-center" id="modalRepresentante" tabindex="-1" role="dialog"
     aria-labelledby="modalRepresentante"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content align-content-center" style="box-shadow:0px 3px 6px 4px #008701;">
            <div class="modal-header text-center">
                <h3 class="modal-title w-100" style="margin-top: 10px; position: absolute; left: 2px;">
                    Portal do Representante</h3>
            </div>
            <div class="modal-body">
                <form id="formRepresentante" method="post" class="frmLoginRepresentante"
                      action="<?php echo $frm_representante; ?>">

                    <div class="form-group">
                        <div class="input-group mb-3 mt-5">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                            </div>
                            <input id="emailRepresentante" type="text" class="form-control input-sm"
                                   name="email"
                                   placeholder="Digite seu Login" maxlength="50" required="true">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text "><i class="fa fa-key"></i></span>
                            </div>
                            <input id="senhaRepresentante" type="password" class="form-control input-sm senha"
                                   name="senha" placeholder="Digite sua Senha" maxlength="20"
                                   required="true">
                            <div class="input-group-append">
                                <div class="input-group-text olho" id="olho"><i class="fas fa-eye"></i></div>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top:25px" class="form-group">

                        <div class="col-12 controls" style="margin-top:25px;">
                            <button type="submit" id="postRepresentante" class="btn btn-primary px-3 "
                                    style="width:200px">
                                <i class="fas fa-check"></i> Acessar Sistema
                            </button>
                        </div>

                        <div class="col-12 controls" style="margin-top:25px;">
                            <button type="submit" id="cadRepresentante" class="btn btn-secondary px-3 "
                                    style="width:150px">
                                <i class="fas fa-paperclip"></i> Cadastre-se
                            </button>
                        </div>

                        <div class="col-12 controls" style="margin-top:25px;">
                                <span style="padding-top: 10px;">
                                    <a id="esqueceuSenhaRepresentante"
                                       style="text-decoration:none"
                                       data-toggle="modal"
                                       data-dismiss="modal"
                                       data-target="#modalNovaSenha">Esqueceu sua senha?
                                    </a>
                                </span>
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

<!-- Modal login Distribuidor -->
<div class="modal fade text-center" id="modalDistribuidor" tabindex="-1" role="dialog"
     aria-labelledby="modalDistribuidor"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content align-content-center" style="box-shadow:0px 3px 6px 4px #fd7401;">
            <div class="modal-header text-center">
                <h3 class="modal-title w-100" style="margin-top: 10px;position: absolute;left: 0px;">
                    Portal do Distribuidor</h3>
            </div>
            <div class="modal-body">
                <form id="formDistribuidor" method="post" class="frmLoginDistribuidor"
                      action="<?php echo $frm_distribuidor; ?>">

                    <div class="form-group">
                        <div class="input-group mb-3 mt-5">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                            </div>
                            <input id="emailDistribuidor" type="text" class="form-control input-sm"
                                   name="email"
                                   placeholder="Digite seu Login" maxlength="50" required="true">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text "><i class="fa fa-key"></i></span>
                            </div>
                            <input id="senhaDistribuidor" type="password" class="form-control input-sm senha"
                                   name="senha" placeholder="Digite sua Senha" maxlength="20"
                                   required="true">
                            <div class="input-group-append">
                                <div class="input-group-text olho" id="olho"><i class="fas fa-eye"></i></div>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top:25px" class="form-group">

                        <div class="col-12 controls" style="margin-top:25px;">
                            <button type="submit" id="postDistribuidor" class="btn btn-primary px-3 "
                                    style="width:200px">
                                <i class="fas fa-check"></i> Acessar Sistema
                            </button>
                        </div>

                        <div class="col-12 controls" style="margin-top:25px;">
                            <button type="submit" id="cadDistribuidor" class="btn btn-secondary px-3 "
                                    style="width:150px">
                                <i class="fas fa-paperclip"></i> Cadastre-se
                            </button>
                        </div>

                        <div class="col-12 controls" style="margin-top:25px;">
                                <span style="padding-top: 10px;">
                                    <a id="esqueceuSenhaDistribuidor"
                                       style="text-decoration:none"
                                       data-toggle="modal"
                                       data-dismiss="modal"
                                       data-target="#modalNovaSenha">Esqueceu sua senha?
                                    </a>
                                </span>
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

<!-- Modal login Compra Coletiva -->
<div class="modal fade text-center" id="modalCompraColetiva" tabindex="-1" role="dialog"
     aria-labelledby="modalCompraColetiva"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content align-content-center" style="box-shadow:0px 3px 6px 4px #da0e59;">
            <div class="modal-header text-center">
                <h3 class="modal-title w-100" style="margin-top: 10px; position: absolute; left: 0px;">
                    Portal de Compras Coletivas</h3>
            </div>
            <div class="modal-body" style="margin-top: 10px">
                <form id="formCompraColetiva" method="post" class="frmLoginCompraColetiva"
                      action="<?php echo $frm_compracoletiva; ?>">

                    <div class="form-group">
                        <div class="input-group mb-3 mt-5">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                            </div>
                            <input type="text" name="loginCompraColetiva" id="loginCompraColetiva" value=""
                                   required="true" data-inputmask="cnpj" class="form-control">

                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text "><i class="fa fa-key"></i></span>
                            </div>
                            <input id="senhaCompraColetiva" type="password" class="form-control input-sm senha"
                                   name="senhaCompraColetiva" placeholder="Digite sua Senha" maxlength="20"
                                   required="true">
                            <div class="input-group-append">
                                <div class="input-group-text olho" id="olho"><i class="fas fa-eye"></i></div>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top:25px" class="form-group">

                        <div class="col-12 controls" style="margin-top:25px;">
                            <button type="submit" id="postCompraColetiva" class="btn btn-primary px-3 "
                                    style="width:200px">
                                <i class="fas fa-check"></i> Acessar Sistema
                            </button>
                        </div>

                        <div class="col-12 controls" style="margin-top:25px;">
                            <button type="submit" id="cadCompraColetiva" class="btn btn-secondary px-3 "
                                    style="width:150px">
                                <i class="fas fa-paperclip"></i> Cadastre-se
                            </button>
                        </div>

                        <div class="col-12 controls" style="margin-top:25px;">
                                <span style="padding-top: 10px;">
                                    <a id="esqueceuSenhaCC"
                                       style="text-decoration:none"
                                       data-toggle="modal"
                                       data-dismiss="modal"
                                       data-target="#modalNovaSenhaCC">Esqueceu sua senha?
                                    </a>
                                </span>
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

<!-- Modal nova senha Compra Coletiva -->
<div class="modal fade text-center" id="modalNovaSenhaCC" tabindex="-1" role="dialog"
     aria-labelledby="modalNovaSenha"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content align-content-center">
            <div class="modal-header text-center">
                <h3 class="modal-title w-100"
                    style="margin-top: 5px;
                    position: absolute;
                    left: 0px;">
                    Recuperação de Senha</h3>
            </div>
            <div class="modal-body" style="margin-top: 40px">
                <form id="formRecuperarSenha" method="post" action="<?php echo $frm_novasenha; ?>">
                    <h3>Perdeu sua senha? </h3>
                    <p class="small">Não tem problema, informe o CNPJ cadastrado e iremos te ajudar a
                        recuperar sua senha.</p>
                    <div class="form-group">
                        <div class="input-group mb-3 mt-5">
                            <div class="input-group-prepend border-0">
                                <span class="input-group-text border-right-0"><i class="fa fa-user"></i></span>
                            </div>
                            <input id="cnpj" type="text" class="form-control border-left-0 input-sm" name="cnpj"
                                   placeholder="Digite seu CNPJ" maxlength="50" required="true">
                        </div>
                    </div>
                    <div style="margin-top:25px" class="form-group">
                        <!-- Button -->
                        <div class="col-12 controls" style="margin-top:25px;">
                            <button type="submit" id="btnRecuperarSenha" form="formRecuperarSenha"
                                    class="btn btn-success " style="width:200px">
                                Recuperar Senha
                            </button>
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

<?php if (isset($scripts)) echo $scripts; ?>
<script src="https://www.google.com/recaptcha/api.js?render=6LcSlLkUAAAAAKocRTGaJgQeId06vGmoVwyTIspn"></script>
<script>

    $(function () {

        $(".olho").mousedown(function () {
            $(".senha").attr("type", "text");
        });

        $(".olho").mouseup(function () {
            $(".senha").attr("type", "password");
        });

        $('#form').submit(function (e) {

            $('#postbut').html("<i class='fa fa-spin fa-spinner'></i> Validando Dados... ").attr('disabled', true);
            // we stoped it
            e.preventDefault();
            var login = $('#login').val();
            var senha = $('#senha').val();
            // needs for recaptacha ready
            grecaptcha.ready(function () {
                // do request for recaptcha token
                // response is promise with passed token
                grecaptcha.execute('6LcSlLkUAAAAAKocRTGaJgQeId06vGmoVwyTIspn', {action: 'login'}).then(function (token) {
                    // add token to form
                    $('#form').prepend('<input type="hidden" name="g-recaptcha-response" value="' + token + '">');
                    $.post($('#form').attr('action'), {login: login, senha: senha, token: token}, function (result) {
                        if (result.type === 'success') {
                            if (result.action === 'empresas') {
                                Pace.start();

                                window.location = '<?php echo base_url('login/selecionar_empresa'); ?>'
                            } else {

                                Pace.start();
                                window.location = 'dashboard';
                            }
                        } else {
                            $('#postbut').html("<i class='fas fa-check'></i> Acessar Sistema ").attr('disabled', false);
                            formWarning(result)
                        }
                    });
                });
            });
        });

        $('#formDistribuidor').submit(function (e) {

            $('#postDistribuidor').html("<i class='fa fa-spin fa-spinner'></i> Validando Dados... ").attr('disabled', true);
            // we stoped it
            e.preventDefault();
            var login = $('#emailDistribuidor').val();
            var senha = $('#senhaDistribuidor').val();
            // needs for recaptacha ready
            grecaptcha.ready(function () {
                // do request for recaptcha token
                // response is promise with passed token
                grecaptcha.execute('6LcSlLkUAAAAAKocRTGaJgQeId06vGmoVwyTIspn', {action: 'login'}).then(function (token) {
                    // add token to form
                    $('#formDistribuidor').prepend('<input type="hidden" name="g-recaptcha-response" value="' + token + '">');
                    $.post($('#formDistribuidor').attr('action'), {login: login, senha: senha, token: token}, function (result) {
                        if (result.type === 'success') {
                            if (result.action === 'empresas') {
                                Pace.start();

                                window.location = '<?php echo base_url('login/selecionar_empresa'); ?>'
                            } else {

                                Pace.start();
                                window.location = 'dashboard';
                            }
                        } else {
                            $('#postDistribuidor').html("<i class='fas fa-check'></i> Acessar Sistema ").attr('disabled', false);
                            formWarning(result)
                        }
                    });
                });
            });
        });

        $('#formRepresentante').submit(function (e) {

            $('#postRepresentante').html("<i class='fa fa-spin fa-spinner'></i> Validando Dados... ").attr('disabled', true);
            // we stoped it
            e.preventDefault();
            var email = $('#emailRepresentante').val();
            var senha = $('#senhaRepresentante').val();
            // needs for recaptacha ready
            grecaptcha.ready(function () {
                // do request for recaptcha token
                // response is promise with passed token
                grecaptcha.execute('6LcSlLkUAAAAAKocRTGaJgQeId06vGmoVwyTIspn', {action: 'login'}).then(function (token) {
                    // add token to form
                    $('#formRepresentante').prepend('<input type="hidden" name="g-recaptcha-response" value="' + token + '">');
                    $.post($('#formRepresentante').attr('action'), {
                        email: email,
                        senha: senha,
                        token: token
                    }, function (result) {
                        if (result.type === 'success') {
                            if (result.action === 'empresas') {
                                Pace.start();

                                window.location = '<?php echo base_url('login/selecionar_empresa'); ?>'
                            } else {
                                Pace.start();
                                window.location = 'representantes/dashboard'

                            }
                        } else {
                            $('#postRepresentante').html("<i class='fas fa-check'></i> Acessar Sistema ").attr('disabled', false);
                            formWarning(result)
                        }
                    });
                });
            });
        });


        $('#formRecuperarSenha').submit(function (e) {

            $('#btnRecuperarSenha').html("<i class='fa fa-spin fa-spinner'></i> Validando Dados... ").attr('disabled', true);
            // we stoped it
            e.preventDefault();
            var email = $('#loginUser').val();
            // needs for recaptacha ready
            grecaptcha.ready(function () {
                // do request for recaptcha token
                // response is promise with passed token
                grecaptcha.execute('6LcSlLkUAAAAAKocRTGaJgQeId06vGmoVwyTIspn', {action: 'login'}).then(function (token) {
                    // add token to form
                    $('#formRecuperarSenha').prepend('<input type="hidden" name="g-recaptcha-response" value="' + token + '">');
                    $.post($('#formRecuperarSenha').attr('action'), {login: email, token: token}, function (result) {
                        console.log(result);

                        if (result.type === 'success') {

                            formWarning(result);

                            setTimeout(function () {
                                window.location.href = '<?php echo base_url('login'); ?>';
                            }, 1800);
                        } else {
                            formWarning(result);

                            $('#btnRecuperarSenha').html("<i class='fas fa-check'></i> Recuperar Senha ").attr('disabled', false);
                        }
                    });
                });
            });
        });


    });
</script>
</body>
