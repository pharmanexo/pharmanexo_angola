<?php

$tipo_usuario = $this->session->userdata("tipo_usuario");
$logado = $this->session->userdata("logado");

if (isset($header)) echo $header;

?>

<body class="bg-light" style="background:url(<?php echo base_url('images/background_login.jpg'); ?>); background-repeat: no-repeat; background-size: cover;">
<div class="container h-100">
    <div class="row h-100 justify-content-center align-items-center">
        <div class="col-md-5">
            <div class="card text-center " id="telaLogin">
                <div class="card-body">
                    <div class="rounded-circle p-2" style=" background-color: #fff">
                        <img class=" mx-auto d-block" style="width: 50px; height: auto" src="<?php echo base_url('images/icone_pharmanexo.png'); ?>">
                    </div>

                    <form id="formRecuperarSenha" method="post" action="<?php echo $frm_action; ?>">
                        <h3>Perdeu sua senha? </h3>
                        <p class="small">Não tem problema, digite os dados abaixo e iremos te ajudar a recuperar seu acesso.</p>
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
                                <input id="login" type="text" class="form-control border-left-0 input-sm" name="login" placeholder="Digite seu E-mail/Login" maxlength="50" required="true">
                            </div>
                        </div>
                        <div style="margin-top:25px" class="form-group">
                            <!-- Button -->
                            <div class="col-12 controls" style="margin-top:25px;">
                                <button type="submit" class="btn btn-success " style="width:200px">
                                    Recuperar Senha
                                </button>
                            </div>

                            <div class="col-12 controls" style="margin-top:25px;">
                                <span style="padding-top: 10px;"><a href="<?php echo base_url('login') ?>" style="text-decoration:none">Voltar para o login</a></span>
                            </div>
                        </div>
                    </form>

                    <?php $mensagem = $this->session->flashdata("mensagem"); ?>
                    <?php if (!empty($mensagem)) : ?>
                        <div class="alert alert-danger" style="margin-top:125px;"><?php echo $mensagem; ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mb-4 w-100"  hidden style="margin-top:10px; margin-bottom:10px;">
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
                            <button type="button" class="btn float-left btn-info  "><i class="fas fa-building"></i>
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

</body>
</html>