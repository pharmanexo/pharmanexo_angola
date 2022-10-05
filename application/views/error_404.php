<?php

$tipo_usuario = $this->session->userdata("tipo_usuario");
$logado = $this->session->userdata("logado");

if (isset($header)) echo $header;

?>

<body class="bg-light">
<div class="container-fluid h-100 text-center mt-5">
    <img src="<?php echo $img; ?>" alt="" class="img-fluid">
    <br>
    <a href="<?php echo base_url("dashboard"); ?>" class="btn blt-lg btn-primary">Voltar para o site</a>
</div>
</body>

</html>