<?php

$tipo_usuario = $this->session->userdata("tipo_usuario");
$logado = $this->session->userdata("logado");

if (isset($header)) echo $header;

?>

<body class="bg-light" style="background:url(<?php echo base_url('images/bg_site.png'); ?>); background-repeat: no-repeat; background-size: cover;">
<div class="container h-100">
    <div class="row h-100 justify-content-center align-items-center">
        <div class="col-md-6">
            <div class="card text-center " style="background-color: rgba(255,255,255,.7)" id="telaLogin">
                <div class="card-body">
                    <div class="p-2">
                        <img class=" mx-auto d-block" style="width: 50px; height: auto" src="<?php echo base_url('images/icone_pharmanexo.png'); ?>">
                    </div>
                    <h3>Auditoria de Segurança</h3>
                    <h4 class="small">Nosso sistema estará indispinível para acessos do dias 30/08/2019 as 16:30 até 01/09/2019 as 20:00</h4>
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>