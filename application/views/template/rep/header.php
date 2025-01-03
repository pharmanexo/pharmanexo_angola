<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />

    <title><?php echo "Pharmanexo :: {$title}"; ?></title>

    <link rel="stylesheet" href="<?php echo THIRD_PARTY . 'plugins/bootstrap-4.2.1/css/bootstrap.min.css' ?>">
    <link rel="stylesheet" href="<?php echo THIRD_PARTY . 'plugins/fontawesome-5.9.0/css/all.min.css' ?>">
    <link rel="stylesheet" href="<?php echo THIRD_PARTY . 'theme/plugins/material-design-iconic-font/css/material-design-iconic-font.min.css' ?>">
    <link rel="stylesheet" href="<?php echo THIRD_PARTY . 'theme/plugins/animate.css/animate.min.css' ?>">
    <link rel="stylesheet" href="<?php echo THIRD_PARTY . 'theme/plugins/jquery-scrollbar/jquery.scrollbar.css' ?>">
    <link rel="stylesheet" href="<?php echo THIRD_PARTY . 'plugins/sweetalert/sweetalert.css' ?>">
    <link rel="stylesheet" href="<?php echo THIRD_PARTY . 'plugins/select2-4.0.7/dist/css/select2.min.css' ?>">
    <link rel="stylesheet" href="<?php echo THIRD_PARTY . 'plugins/bootstrap-datepicker-1.9.0/css/bootstrap-datepicker.min.css' ?>">
    <link rel="stylesheet" href="<?php echo THIRD_PARTY . 'plugins/select.dataTables.min.css' ?>">

    <?php if (isset($styles)) foreach ($styles as $css) { ?>
        <link rel="stylesheet" href="<?php echo $css; ?>">
    <?php } ?>

    <link rel="stylesheet" href="<?php echo THIRD_PARTY . 'theme/css/app.min.css' ?>">
    <link rel="stylesheet" href="<?php echo THIRD_PARTY . 'theme/css/styles.custom.css' ?>">

    <link rel="stylesheet" href="<?php echo THIRD_PARTY . 'plugins/toastr/toastr.min.css' ?>">

    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style type="text/css">
        .nencontrado { background-color: #ffaeae !important; }
        .enviado { background-color: #aecbff !important; }
        .nenviado { background-color: #FFFFFF !important; }
        .semestoque { background-color: #f3ffae !important; } 
        .logofornecedor {
            width: 8em !important;
            height: 4em !important;
            margin-right: 2em !important;
            margin-top: 2em !important;
            margin-bottom: 2em !important;
            /*border-radius: 50% !important;*/
        }
    </style>


    <?php if (isset($warning)) { ?>
        <script>
            var warn = JSON.parse('<?php echo $warning; ?>');
            Swal.fire({
                icon: warn.type,
                text: warn.message,
            });
        </script>
    <?php } ?>

    <link rel="stylesheet" href="<?php echo THIRD_PARTY . 'theme/plugins/pace-1.0.2/themes/blue/pace-theme-corner-indicator.css' ?>">
</head>
