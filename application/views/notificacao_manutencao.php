<?php

$tipo_usuario = $this->session->userdata("tipo_usuario");
$logado = $this->session->userdata("logado");

if (isset($header)) echo $header;

?>

<body class="bg-light" style="">
<div class="container-fluid" id="frameEmpresas">
    <div class="row" style="height: 100vh">
        <div class="col-12 col-lg-6" style="background:url(<?php echo base_url('images/medicamentos.jpg'); ?>); background-repeat: no-repeat; background-size: cover;">
            <div class="row my-3">
                <div class="col">
                    <img class="d-block" src="<?php echo base_url('images/img/logo-white.png'); ?>">
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
            </div>
        </div>
        <div class="col-12 col-lg-6 bg-light formColumn">
            <div class="card text-center shadow " style="background-color: rgba(255,255,255,.7); margin-top: 150px" id="telaLogin">
                <div class="card-body">
                    <div class="p-2">
                        <img class=" mx-auto d-block" style="width: 50px; height: auto" src="<?php echo base_url('images/icone_pharmanexo.png'); ?>">
                    </div>
                    <h3>INFORMAÇÃO IMPORTANTES</h3>
                    <br>
                    <p>O sistema Pharmanexo está passando por melhorias de estrutura, banco de dados e páginas, isso não impossibilita o uso, mas
                    podem ocorrer lentidões ao acessar algumas páginas.</p>
                    <br>
                    <p>Iremos trabalhar o mais rápido possível para normalizar o sistema.</p>
                    <br>
                    <p>Agracemos a compreensão.</p>
                    <br><br>
                    <a href="<?php if (isset($url_dash)) echo $url_dash; ?>" class="btn btn-primary">Ir para Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>