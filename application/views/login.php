<?php

$tipo_usuario = $this->session->userdata("tipo_usuario");
$logado = $this->session->userdata("logado");

if (isset($header)) echo $header;

?>

<body class="bg-light">
<div hidden class="loading" id="loading">
    <img src="<?php echo IMG_PATH ?>loading.gif" alt="Loading...">
</div>
<div class="supreme-container">
    <div class="container-fluid" id="frameLogin">
        <div class="row" style="height: 100vh">
            <div class="col-12 col-lg-6"
                 style="background:url(<?php echo base_url('images/img/bglogin.jpeg'); ?>); background-repeat: no-repeat; background-size: cover;">
                <div class="row my-3">
                    <div class="logo">
                        <img src="public/home/assets/images/ubuntu.png" style="width: 40%; margin-top: 10%; float: right; margin-right: 50px" class="img-fluid" alt="">
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6" style="padding-top: 100px">
                <div class="alert" id="alertaSessao" style="margin-bottom:-60px;" hidden>
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                    Sua sessão foi encerrada por inatividade.
                </div>
                <div class="formLogin">
                    <div class="formLoginContent">
                        <form method="post" id="form" class="frmLogin"
                              action="<?php if (isset($frm_action)) echo $frm_action; ?>" class="form">
                            <h3 class="text-center"
                                style="border-bottom: 1px solid #F5AA37; color: #F5AA37; padding: 20px; margin-bottom: 20px">
                                Login</h3>
                            <div class="form-group">
                                <label for="">Selecione o perfil</label>
                                <select name="grupo" id="grupo" required class="form-control">
                                    <option value="">Selecione...</option>
                                    <option value="">Governo Federal</option>
                                    <option value="">Regionais de Saúde</option>
                                    <option value="">Hospitais</option>
                                    <option value="">Postos de Saúde</option>
                                    <option value="">Portais de Concurso</option>
                                    <option value="">Fornecedores</option>


                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Usuário</label>
                                <input type="text" id="login" name="login" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Senha</label>
                                <input type="password" id="senha" name="senha" class="form-control">
                            </div>
                            <div class="form-group text-center">
                                <input type="submit" value="Entrar" style="background-color: #F5AA37"
                                       class="btn btn-block btn-success">
                            </div>
                        </form>
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
            var grupo = $('#grupo').val();
            // needs for recaptacha ready
            grecaptcha.ready(function () {
                // do request for recaptcha token
                // response is promise with passed token
                grecaptcha.execute('6LcSlLkUAAAAAKocRTGaJgQeId06vGmoVwyTIspn', {
                    action: 'login'
                }).then(function (token) {
                    // add token to form
                    $('#form').prepend('<input type="hidden" name="g-recaptcha-response" value="' + token + '">');
                    $.post($('#form').attr('action'), {
                        login: login,
                        senha: senha,
                        token: token,
                        grupo: grupo
                    }, function (result) {
                        if (result.type === 'success') {
                            if (result.action === 'empresas') {
                                window.location = '<?php echo base_url('login/selecionar_empresa'); ?>'
                            } else {
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

        $('#formRecuperarSenha').submit(function (e) {

            $('#btnRecuperarSenha').html("<i class='fa fa-spin fa-spinner'></i> Validando Dados... ").attr('disabled', true);
            // we stoped it
            e.preventDefault();
            var email = $('#loginUser').val();
            // needs for recaptacha ready
            grecaptcha.ready(function () {
                // do request for recaptcha token
                // response is promise with passed token
                grecaptcha.execute('6LcSlLkUAAAAAKocRTGaJgQeId06vGmoVwyTIspn', {
                    action: 'login'
                }).then(function (token) {
                    // add token to form
                    $('#formRecuperarSenha').prepend('<input type="hidden" name="g-recaptcha-response" value="' + token + '">');
                    $.post($('#formRecuperarSenha').attr('action'), {
                        login: email,
                        token: token
                    }, function (result) {
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

        $('#formRecuperarSenhaRepresentante').submit(function (e) {

            $('#btnRecuperarSenhaRep').html("<i class='fa fa-spin fa-spinner'></i> Validando Dados... ").attr('disabled', true);
            // we stoped it
            e.preventDefault();
            var email = $('#loginUserRep').val();
            // needs for recaptacha ready
            grecaptcha.ready(function () {
                // do request for recaptcha token
                // response is promise with passed token
                grecaptcha.execute('6LcSlLkUAAAAAKocRTGaJgQeId06vGmoVwyTIspn', {
                    action: 'login'
                }).then(function (token) {
                    // add token to form
                    $('#formRecuperarSenhaRepresentante').prepend('<input type="hidden" name="g-recaptcha-response" value="' + token + '">');
                    $.post($('#formRecuperarSenhaRepresentante').attr('action'), {
                        login: email,
                        token: token
                    }, function (result) {
                        console.log(result);

                        if (result.type === 'success') {

                            formWarning(result);

                            setTimeout(function () {
                                window.location.href = '<?php echo base_url('login'); ?>';
                            }, 1800);
                        } else {
                            formWarning(result);

                            $('#btnRecuperarSenhaRep').html("<i class='fas fa-check'></i> Recuperar Senha ").attr('disabled', false);
                        }
                    });
                });
            });
        });

    });
</script>
</body>