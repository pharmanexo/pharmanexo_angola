<?php

$tipo_usuario = $this->session->userdata("tipo_usuario");
$logado = $this->session->userdata("logado");

if (isset($header)) echo $header;

?>

<body class="bg-light" style="background:url(<?php echo base_url('images/background-login.jpg'); ?>); background-repeat: no-repeat; background-size: cover;">
    <div class="container h-100">
                <div class="card  text-center ">
                    <div class="card-body">
                        <h1 class="text-center text-primary">Ops... :(</h1>
                        <h3 class="text-muted">A página solicitada não foi encontrada, vefique o endereço digitado ou <a href="dashboard">retorne a página inicial.</a></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>