<?php

$tipo_usuario = $this->session->userdata("tipo_usuario");
$logado = $this->session->userdata("logado");

if (isset($header)) echo $header;

?>

<body class="bg-light" style="">
<div class="container-fluid" id="frameLogin">
    <div class="row" style="height: 100vh">
        <div class="col-12 col-lg-6" style="background:url(<?php echo base_url('images/bg-login.jpg'); ?>); background-repeat: no-repeat; background-size: cover;">
            <div class="row my-3">
                <div class="col">
                    <a href="/"><img class="d-block" src="<?php echo base_url('images/img/logo-white.png'); ?>"></a>
                </div>
                <div class="col text-right">
                    <div id="social" class="d-none d-sm-block" style="color: #fff">
                        <ul>
                            <li><a href="https://facebook.com/pharmanexo" target="_blank"><i class="fab fa-2x fa-facebook"></i></a></li>
                            <li><a href="https://instagram.com/pharmanexo" target="_blank"><i class="fab fa-2x fa-instagram"></i></a></li>
                            <li><a href="https://linkedin.com/company/pharmanexo" target="_blank"><i class="fab fa-2x fa-linkedin"></i></a></li>
                        </ul>
                    </div>
                    <div>
                    </div>

                </div>
                <h3 class="text-white" style="margin-top: 180px; padding: 100px; text-align: center">
                    O MAIOR PORTAL DE OPORTUNIDADES EM PRODUTOS FARMACÊUTICOS E MATERIAIS MÉDICO HOSPITALARES DO BRASIL
                    <br><br>
                    <a  href="https://pharmanexo.com.br" target="_blank"class="btn btn-light mt-3 px-5">Conheça a Pharmanexo</a>
                </h3>
                <div class="text-center w-100 position-absolute" style="bottom: 0"><p class="text-white text-center">© 2019 Pharmanexo</p></div>
            </div>
        </div>
        <div class="col-12 col-lg-6 d-flex justify-content-center bg-light formColumn">


            <div class="card text-center shadow position-absolute " hidden style=" margin-top: 150px" id="telaLogin">
                <div class="card-body">
                    <h3>Manutenção do Banco de Dados</h3>
                    <br><br>
                    <p class="">Nosso sistema encontra-se em manutenção de banco de dados, previsão de retorno as 08:00h do dia 11/10/2019.</p>

                </div>
            </div>
            <!-- card login -->
            <div class="card text-center shadow position-absolute "  style=" margin-top: 150px" id="telaLogin">
                <div class="card-body">
                    <h3>Portal do Representante</h3>
                    <p class="small">Para acessar o sistema Pharmanexo é necessário digitar o login e senha.</p>
                    <form id="form" method="post" class="frmLogin" action="<?php echo $frm_action; ?>">
                        <input type="hidden" name="capcode" id="capcode" value="false"/>
                        <div class="form-group">
                            <div class="input-group mb-3 mt-5">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                                </div>
                                <input id="email" type="text" class="form-control input-sm" name="email" placeholder="Digite seu Login" maxlength="50" required="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text "><i class="fa fa-key"></i></span>
                                </div>
                                <input id="senha" type="password" class="form-control input-sm" name="senha" placeholder="Digite sua Senha" maxlength="20" required="true">
                            </div>
                        </div>


                        <div style="margin-top:25px" class="form-group">
                            <!-- Button -->
                            <div class="col-12 controls" style="margin-top:25px;">
                                <button type="submit" id="postbut" class="btn btn-primary px-3 " style="width:200px">
                                    <i class="fas fa-check"></i> Acessar Sistema
                                </button>
                            </div>

                            <div class="col-12 controls" style="margin-top:25px;">
                                <span style="padding-top: 10px;"><a id="esqueceuSenha" style="text-decoration:none">Esqueceu sua senha?</a></span>
                            </div>
                        </div>
                    </form>

                    <?php $mensagem = $this->session->flashdata("mensagem"); ?>
                    <?php if (!empty($mensagem)) : ?>
                        <div class="alert alert-danger" style="margin-top:125px;"><?php echo $mensagem; ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- card nova senha -->
            <div class="card text-center position-absolute shadow "  style=" margin-top: 150px" id="telaNovaSenha">
                <div class="card-body">
                    <form id="formRecuperarSenha" method="post" action="<?php echo $frm_novasenha; ?>">
                        <h3>Perdeu sua senha? </h3>
                        <p class="small">Não tem problema, informe o e-mail cadastrado e iremos te ajudar a recuperar seua senha.</p>
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
                                    <span class="input-group-text border-right-0"><i class="fa fa-envelope"></i></span>
                                </div>
                                <input id="loginUser" type="text" class="form-control border-left-0 input-sm" placeholder="Digite seu E-mail/Login" maxlength="50" required="true">
                            </div>
                        </div>
                        <div style="margin-top:25px" class="form-group">
                            <!-- Button -->
                            <div class="col-12 controls" style="margin-top:25px;">
                                <button type="submit" id="btnRecuperarSenha" form="formRecuperarSenha" class="btn btn-success " style="width:200px">
                                    Recuperar Senha
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

            <div class="mb-4 w-100" hidden style="margin-top:10px; margin-bottom:10px;">
                <h5 class=" mx-auto text-center"> Não tem acesso? Cadastre-se...</h5>

                <div class="row  text-center">
                    <div class="col-6  ">
                        <a href="<?php echo base_url('Cliente/cadastro') ?>" style="text-decoration: none;">
                            <button type="button" class="btn float-right btn-info  " style="width:132px;"><i class="fas fa-users"></i> Cliente
                            </button>
                        </a>
                    </div>

                    <div class="col-6 ">
                        <a href="<?php echo base_url('Fornecedor') ?>" style="text-decoration: none;">
                            <button type="button"  class="btn float-left btn-info " data-sitekey="6LcnlLkUAAAAAPg2mSRJPKhYX_mrRCx99FUwFuj2" data-callback='onSubmit'><i class="fas fa-building"></i>
                                Fornecedor
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if (isset($scripts)) echo $scripts; ?>
<script src="https://www.google.com/recaptcha/api.js?render=6LcSlLkUAAAAAKocRTGaJgQeId06vGmoVwyTIspn"></script>
<script>

    $(function () {

        $('#telaNovaSenha').css('opacity', '0').css('top', '-800px');

        $('#esqueceuSenha').click(function (e) {
            e.preventDefault();

            $( "#telaLogin" ).animate({
                opacity: 0,
                duration: 50,
                top: '-800'
            }, 1500, function() {
                // Animation complete.
            });

            $( "#telaNovaSenha" ).animate({
                opacity: 1,
                duration: 50,
                top: '100'
            }, 1500, function() {
                // Animation complete.
            });
        });

        $('#voltarLogin').click(function (e) {
            e.preventDefault();

            $( "#telaNovaSenha" ).animate({
                opacity: 0,
                duration: 50,
                top: '-800'
            }, 1500, function() {
                // Animation complete.
            });

            $( "#telaLogin" ).animate({
                opacity: 1,
                duration: 50,
                top: '100'
            }, 1500, function() {
                // Animation complete.
            });
        });

        $('#form').submit(function (e) {

            $('#postbut').html("<i class='fa fa-spin fa-spinner'></i> Validando Dados... ").attr('disabled', true);
            // we stoped it
            e.preventDefault();
            var email = $('#email').val();
            var senha = $('#senha').val();
            // needs for recaptacha ready
            grecaptcha.ready(function () {
                // do request for recaptcha token
                // response is promise with passed token
                grecaptcha.execute('6LcSlLkUAAAAAKocRTGaJgQeId06vGmoVwyTIspn', {action: 'login'}).then(function (token) {
                    // add token to form
                    $('#form').prepend('<input type="hidden" name="g-recaptcha-response" value="' + token + '">');
                    $.post($('#form').attr('action'), {email: email, senha: senha, token: token}, function (result) {
                        if (result.type === 'success') {
                            if (result.action === 'empresas'){
                                Pace.start();

                                window.location = '<?php echo base_url('representantes/login/selecionar_empresa'); ?>'
                            } else{
                                Pace.start();
                                window.location = '/representantes/dashboard'

                            }
                        } else {
                            $('#postbut').html("<i class='fas fa-check'></i> Acessar Sistema ").attr('disabled', false);
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
                    $.post($('#formRecuperarSenha').attr('action'), {email: email, token: token}, function (result) {
                        console.log(result);

                        if (result.type === 'success') {

                            formWarning(result);

                            setTimeout(function() {
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
