<?php
$logado = $this->session->userdata("logado");
$tipo_usuario = $this->session->userdata("tipo_usuario");
$integracao = $this->session->userdata("integracao");
?>
    <head>
        <title>Pharmanexo</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <script>
            var base_url = '<?php echo base_url() . ''?>';
        </script>

        <link rel="stylesheet" href="<?php echo THIRD_PARTY . 'theme/css/app.css' ?>">
        <link rel="stylesheet" href="<?php echo THIRD_PARTY . 'theme/css/theme_mp.css' ?>">
        <link rel="stylesheet" href="<?php echo THIRD_PARTY . 'plugins/fontawesome-5.9.0/css/all.min.css' ?>">
        <link rel="stylesheet" href="<?php echo THIRD_PARTY . 'theme/plugins/sweetalert2/sweetalert2.min.css' ?>">
        <link rel="stylesheet" href="<?php echo THIRD_PARTY . 'theme/plugins/animate.css/animate.min.css' ?>">

        <link rel="stylesheet" href="<?php echo THIRD_PARTY . 'plugins/fontawesome-5.9.0/css/all.min.css' ?>">
        <link rel="stylesheet" href="<?php echo THIRD_PARTY . 'plugins/toastr/toastr.min.css' ?>">
        <link rel="stylesheet" href="<?php echo THIRD_PARTY . 'theme/plugins/material-design-iconic-font/css/material-design-iconic-font.min.css' ?>">
        <link rel="stylesheet" href="<?php echo THIRD_PARTY . 'theme/plugins/animate.css/animate.min.css' ?>">
        <link rel="stylesheet" href="<?php echo THIRD_PARTY . 'theme/plugins/jquery-scrollbar/jquery.scrollbar.css' ?>">

        <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('images/favicon.ico') ?>"/>

        <link href="https://fonts.googleapis.com/css?family=Ubuntu:400,500,700,700i" rel="stylesheet">


        <?php if (isset($styles)) foreach ($styles as $css) { ?>
            <link rel="stylesheet" href="<?php echo $css; ?>">
        <?php } ?>
    </head>

<?php if (isset($warning)) { ?>
    <script>
        formWarning(JSON.parse('<?php echo $warning;?>'));
    </script>
<?php } ?>