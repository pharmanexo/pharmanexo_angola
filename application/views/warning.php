<?php

$tipo_usuario = $this->session->userdata("tipo_usuario");
$logado = $this->session->userdata("logado");

if (isset($header)) echo $header;

?>

<body class="bg-light" style="background:url(<?php echo base_url('images/background-login.jpg'); ?>); background-repeat: no-repeat; background-size: cover;">
    <div class="container h-100">
                <div class="card  text-center " style="margin-top: 200px">
                    <div class="card-body">
                        <h1 class="text-center text-primary mb-3">Ops... :(</h1>
                        <h4 class="text-muted">Você não ter permissão para acessar está página, fale com o administrador ou <a href="dashboard">retorne a página inicial.</a></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>