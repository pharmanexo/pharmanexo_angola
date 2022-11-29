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
    <link rel="stylesheet" href="<?php echo THIRD_PARTY . 'plugins/bootstrap-select-1.13.14/dist/css/bootstrap-select.min.css' ?>">

    <?php if (isset($styles)) foreach ($styles as $css) { ?>
        <link rel="stylesheet" href="<?php echo $css; ?>">
    <?php } ?>

    <!-- Favicons -->
    <link href="<?php echo ASSETS_PATH ?>/img/main-master-favicon.ico" rel="icon">
    <link href="<?php echo ASSETS_PATH ?>/img/main-master-favicon.ico" rel="apple-touch-icon">

    <link rel="stylesheet" href="<?php echo THIRD_PARTY . 'theme/css/app.min.css' ?>">
    <link rel="stylesheet" href="<?php echo THIRD_PARTY . 'theme/css/styles.custom.css' ?>">

    <link rel="stylesheet" href="<?php echo THIRD_PARTY . 'plugins/toastr/toastr.min.css' ?>">
    <link rel="stylesheet" href="<?php echo THIRD_PARTY . 'plugins/Material-Design-Calculator-jQuery/css/creative.css' ?>">

    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">

    <style type="text/css">
        .alert {
            padding: 20px;
            text-align: center;
            border-radius: 0 0 10px 10px;
            margin-left: 13%;
            margin-right: 13%;
            background-color: #192069;
            color: white;
        }

        .closebtn {
            margin-left: 15px;
            color: white;
            font-weight: bold;
            float: right;
            font-size: 22px;
            line-height: 20px;
            cursor: pointer;
            transition: 0.3s;
        }

        .closebtn:hover {
            color: #ed3237;
        }

        .avatar {
            border: 2px solid white;
            cursor: pointer;
        }

        .selecao {
            border: 3px solid #ed3237;
            border-bottom: none;
            border-top: none;
        }

        .card {
            border-radius: 30px;
        }

        .nencontrado {
            background-color: #ffaeae !important;
        }

        .enviado {
            background-color: #aecbff !important;
        }

        .nenviado {
            background-color: #FFFFFF !important;
        }

        .semestoque {
            background-color: #f3ffae !important;
        }

        .bluepharma {

            color: #ed3237;
            text-shadow: 1px 1px #FFFFFF;
        }

        .logofornecedor {
            width: 8em !important;
            height: 4em !important;
            margin-right: 2em !important;
            margin-top: 2em !important;
            margin-bottom: 2em !important;
            /*border-radius: 50% !important;*/
        }

        .multi_select {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
        }

        .btn1 {
            display: inline-block;
            padding: 10px 15px;
            background-color: #27298a;
            font-weight: 300;
            color: #ffffff;
            border-radius: 5px;
            -webkit-transition: all 0.3s;
            transition: all 0.3s;
            border: 1px solid #27298a;
            text-shadow: 0px 0px #FFFFFF;
        }

        .cc {
            white-space: nowrap;
            position: relative;
            text-align: center;
            font-size: 30px;
            font-weight: bold;
            padding-left: 15px;
            color: #27298a;
        }

        .btn1:hover {
            background-color: transparent;
            color: #27298a;
        }

        .btn2 {
            font-size: 1rem;
            line-height: 2;
            font-weight: 400;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            border: none;
            background: rgb(110, 188, 255);
            background: linear-gradient(180deg, rgba(110, 188, 255, 1) 0%, rgba(7, 140, 255, 1) 47%, rgba(2, 117, 216, 1) 100%);
            box-shadow: 0px 0px 1px 1px rgb(153, 238, 255);
        }

        .logo img {
            position: relative;
            padding-left: 60px;
            width: 100%;
            height: 100%;
            -o-object-fit: cover;
            object-fit: cover;
            -o-object-position: bottom right;
            object-position: bottom right;
        }

        body.modal-open .supreme-container {
            -webkit-filter: blur(3px);
            -moz-filter: blur(3px);
            -o-filter: blur(3px);
            -ms-filter: blur(3px);
            filter: blur(3px);
        }

        .input-label {
            margin-left: -370px;
            font-size: 15px;
        }

        .modal-content {
            border-radius: 30px;
        }

        .modal-title {
            font-size: 30px;
            margin-top: -20px;
        }

        .btn2:hover {
            background: linear-gradient(180deg, rgba(34, 153, 255, 1) 0%, rgba(0, 121, 226, 1) 47%, rgba(3, 90, 164, 1) 100%);
            transition: 0.5s;
        }

        .hover-4 {
            --h: 1.2em;
            /* the height */
            --c: #1095c1;
            /* the color */

            line-height: var(--h);
            color: #0000;
            text-shadow:
                0 var(--h) #fff,
                0 0 #000;
            clip-path: inset(0 0 1px 0);
            transition: 0.4s;
        }

        .hover-4:hover {
            clip-path: inset(0 0 calc(-1*var(--h)) 0);
        }

        .hover-4:hover:before {
            content: "";
        }

        .hover-4:hover:after {
            content: "teste";
        }

        .d-1 {
            --c: #1095c1;
            /* the color */
            --b: .1em;
            /* border length*/
            --d: 20px;
            /* the cube depth */

            --_s: calc(var(--d) + var(--b));

            color: var(--c);
            border: solid #0000;
            border-width: var(--b) var(--b) var(--_s) var(--_s);
            background:
                conic-gradient(at left var(--d) bottom var(--d),
                    #0000 90deg, rgb(255 255 255 /0.3) 0 225deg, rgb(255 255 255 /0.6) 0) border-box,
                conic-gradient(at left var(--_s) bottom var(--_s),
                    #0000 90deg, var(--c) 0) 0 100%/calc(100% - var(--b)) calc(100% - var(--b)) border-box;
            transform: translate(calc(var(--d)/-1), var(--d));
            clip-path:
                polygon(var(--d) 0%,
                    var(--d) 0%,
                    100% 0%,
                    100% calc(100% - var(--d)),
                    100% calc(100% - var(--d)),
                    var(--d) calc(100% - var(--d)));
            transition: 0.5s;
        }

        .d-1:hover {
            transform: translate(0, 0);
            clip-path:
                polygon(0% var(--d),
                    var(--d) 0%,
                    100% 0%,
                    100% calc(100% - var(--d)),
                    calc(100% - var(--d)) 100%,
                    0% 100%);
        }
    </style>


    <?php if (isset($warning)) { ?>
        <script>
            formWarning(JSON.parse('<?php echo $warning; ?>'));
        </script>
    <?php } ?>
    <script>
        var initDestroyTimeOutPace = function() {
            var counter = 0;
            var refreshIntervalId = setInterval(function() {
                var progress;
                if (typeof document.querySelector('.pace-progress').getAttribute('data-progress-text') !== 'undefined') {
                    progress = Number(document.querySelector('.pace-progress').getAttribute('data-progress-text').replace("%", ''));
                }
                if (progress === 99) {
                    counter++;
                }
                if (counter > 50) {
                    clearInterval(refreshIntervalId);
                    Pace.stop();
                }
            }, 100);
        }
        initDestroyTimeOutPace();
    </script>

    <link rel="stylesheet" href="<?php echo THIRD_PARTY . 'theme/plugins/pace-1.0.2/themes/blue/pace-theme-corner-indicator.css' ?>">
</head>