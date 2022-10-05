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
                    <img class="d-block" src="<?php echo base_url('images/img/logo-branca.png'); ?>">
                </div>
                <div class="col text-right">
                    <div id="social" class="d-none d-sm-block" style="color: #fff">
                        <ul>
                            <li><a href="https://facebook.com/pharmanexo" target="_blank"><i class="fab fa-2x fa-facebook"></i></a></li>
                            <li><a href="https://instagram.com/pharmanexo" target="_blank"><i class="fab fa-2x fa-instagram"></i></a></li>
                            <li><a href="https://linkedin.com/company/pharmanexo" target="_blank"><i class="fab fa-2x fa-linkedin"></i></a></li>
                        </ul>
                    </div>
                </div>
                <h3 class="text-white" style="margin-top: 180px; padding: 100px; text-align: center">
                    O MAIOR PORTAL DE OPORTUNIDADES EM PRODUTOS FARMACÊUTICOS E MATERIAIS MÉDICO HOSPITALARES DO BRASIL
                    <br><br>
                    <a href="https://pharmanexo.com.br" target="_blank" class="btn btn-light mt-3 px-5">Conheça a Pharmanexo</a>
                </h3>
                <div class="text-center w-100 position-absolute" style="bottom: 0"><p class="text-white text-center">© 2019 Pharmanexo</p></div>
            </div>
        </div>
        <div class="col-12 col-lg-6 bg-light formColumn">
            <div class="card text-center shadow " style="background-color: rgba(255,255,255,.7); margin-top: 150px" id="telaLogin">
                <div class="card-body">
                    <div class="p-2">
                        <img class=" mx-auto d-block" style="width: 50px; height: auto" src="<?php echo base_url('images/icone_pharmanexo.png'); ?>">
                    </div>
                    <h3>Selecionar Empresa</h3>
                    <p>O seu login está cadastrado em mais de um fornecedor, então selecione <br> abaixo a empresa que deseja acessar.</p>

                    <form id="formSelecionarEmpresa" method="post" class="frm" action="<?php echo $frm_action; ?>">
                        <div class="form-group">
                            <select name="empresa" id="empresa" class="select2">
                                <?php if (isset($empresas)) { ?>
                                    <?php foreach ($empresas as $empresa){ ?>
                                        <option value="<?php echo $empresa['id']; ?>"><?php echo strtoupper($empresa['nome_fantasia']) . " | {$empresa['cidade']}-{$empresa['estado']} - {$empresa['cnpj']}"?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                        <button type="submit" form="formSelecionarEmpresa" class="btn btn-primary px-3">
                            <i class="fas fa-check"></i> Selecionar Empresa
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (isset($scripts)) echo $scripts; ?>

</body>
