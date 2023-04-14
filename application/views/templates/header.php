<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-WBDX2MKN3P"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag(){dataLayer.push(arguments);}

        gtag('js', new Date());

        gtag('config', 'G-WBDX2MKN3P');
    </script>

    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title> <?php if (isset($page_title)) echo $page_title; ?> </title>
    <meta content="A Pharmanexo intermediadora de negócios, é um projeto disruptivo que utiliza tecnologia de ponta e inteligência artificial para buscar as melhores oportunidades de negócio" name="descriptison">
    <meta content="pharmanexo, negocios, tecnlogia, produtos hospitalares, hospital, oportunidades, intermediação de negócios, leilão reverso, medicamentos, melhore medicamentos, oncoprod, exomed, oncoexo, biohosp, sintese, bionexo, apoio, integração sintese, integracao sintese, integração bionexo, integracao bionexo, integração apoio, integrador, integrador sintese, integrador apoio, integrador bionexo" name="keywords">

    <!-- Favicons -->
    <link href="<?php echo ASSETS_PATH ?>/img/ubuntu-icone.png" rel="icon">
    <link href="<?php echo ASSETS_PATH ?>/img/ubuntu-icone.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="<?php echo ASSETS_PATH ?>/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo ASSETS_PATH ?>/vendor/icofont/icofont.min.css" rel="stylesheet">
    <link href="<?php echo ASSETS_PATH ?>/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="<?php echo ASSETS_PATH ?>/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="<?php echo ASSETS_PATH ?>/vendor/venobox/venobox.css" rel="stylesheet">
    <link href="<?php echo ASSETS_PATH ?>/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="<?php echo ASSETS_PATH ?>/vendor/aos/aos.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="<?php echo ASSETS_PATH ?>/css/style.css" rel="stylesheet">

    <?php if (isset($styles)) foreach ($styles as $css) { ?>
        <link rel="stylesheet" href="<?php echo $css; ?>">
    <?php } ?>


    <style type="text/css">
        .box-cookies1.hide {
            display: none !important;
        }

        .box-cookies1 {
            position: fixed;
            background: #fff;
            width: 100%;
            height: 100px;
            z-index: 998;
            bottom: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .box-cookies1 .msg-cookies1,
        .box-cookies1 .btn-cookies1 {
            text-align: center;
            color: #fff;
            font-size: 15px;
            font-weight: bold;
        }

        .msg-cookies1 {
            margin: 0 auto !important;
        }

        .btn-cookies1 {
            background: #0274cd;
            cursor: pointer;
            align-self: center;
            border: none;
            border-radius:100px;
            height: 40px;
            width: 150px;
            margin-right: 5px;
        }

        .btn-cookies {
            text-align: center;
            color: #fff;
            font-size: 15px;
            font-weight: bold;
        }

        .btn-cookies {
            background: #0274cd;
            cursor: pointer;
            align-self: center;
            border: none;
            border-radius:100px;
            height: 40px;
            width: 150px;
            margin-right: 15px;
            
        }

        @media screen and (max-width: 600px) {
            .box-cookies1 {
                flex-direction: column;
            }

            /*--------------------------------------------------------------
            # Back to top button
            --------------------------------------------------------------*/
            .back-to-top1 {
                position: fixed;
                display: none;
                right: 15px;
                bottom: 15px;
                z-index: 99999;
            }

            .back-to-top1 i {
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 24px;
                width: 40px;
                height: 40px;
                border-radius: 4px;
                background: #85807e;
                color: rgb(39, 23, 184);
                transition: all 0.4s;
            }

            .back-to-top1 i:hover {
                background: #e62b25;
                color: #fff;
            }
    </style>

    <?php if (isset($warning)) { ?>
        <script>
            formWarning(JSON.parse('<?php echo $warning; ?>'));
        </script>
    <?php } ?>

</head>

