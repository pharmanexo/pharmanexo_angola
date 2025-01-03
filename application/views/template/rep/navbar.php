<!--Start of Tawk.to Script-->
<script type="text/javascript">
    var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
    (function () {
        var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/5dee4805d96992700fcb701c/default';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
</script>
<div class="header" style="background: #b5c4d0;">
    <div class="navigation-trigger hidden-xl-up" data-ma-action="aside-open" data-ma-target=".sidebar">
        <div class="navigation-trigger__inner">
            <i class="navigation-trigger__line"></i>
            <i class="navigation-trigger__line"></i>
            <i class="navigation-trigger__line"></i>
        </div>
    </div>

    <div class="header__logo ">
        <h1><a href="<?php echo base_url('dashboard') ?>"><img src="<?php echo $logoSistema; ?>" alt=""></a></h1>
    </div>

    <ul class="top-nav">
        <?php if (!empty($this->session->logo) && !is_null($this->session->logo)) { ?>
            <li class="p-3 text-right"><img style="width: 150px !important; height: auto !important;"
                                            class="logofornecedor img-fluid"
                                            src="<?php echo base_url("public/fornecedores/{$this->session->id_fornecedor}/{$this->session->logo}") ?>"
                                            alt=""></li>
        <?php } ?>

        <li class="hidden-xl-up"><a href="" data-ma-action="search-open"><i class="zmdi zmdi-search"></i></a></li>
        <?php if (isset($_SESSION['cnpj']) && isset($_SESSION['razao_social'])) { ?>
            <li class=""
                data-toggle="tooltip" <?php if (isset($_SESSION['empresas'])) { ?> title="Clique na seta para trocar de empresa" data-placement="top" <?php } ?>>
                <strong>CNPJ:</strong> <?php echo $this->session->cnpj ?> <br>
                <strong>Empresa:</strong> <?php echo $this->session->razao_social ?> <br>
            </li>
        <?php } ?>
        <?php if (isset($_SESSION['empresas'])) { ?>
            <li>
                <div class="dropdown">
                    <a href="#" class="btn btn-link text-white" id="dropdownMenuButton" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-chevron-down"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right"
                         style="height: auto; overflow: scroll; max-height: 400px" aria-labelledby="dropdownMenuButton">

                        <?php foreach ($_SESSION['empresas'] as $empresa) { ?>
                            <a class="dropdown-item"
                               href="<?php echo base_url("representantes/login/selecionar_empresa/{$empresa['id']}") ?>">
                                <?php echo $empresa['cnpj']; ?> - <?php echo $empresa['nome_fantasia']; ?> <br>
                                <?php echo $empresa['razao_social']; ?> <br>
                                <?php echo $empresa['cidade']; ?> - <?php echo $empresa['estado']; ?>
                            </a>
                        <?php } ?>

                    </div>
                </div>
            </li>
        <?php } ?>
        <li class="dropdown">
            <a href="" data-toggle="dropdown" id="btnNotfifications" aria-expanded="false"><i
                        class="zmdi zmdi-notifications"></i></a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu--block" x-placement="bottom-end"
                 style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(50px, 36px, 0px);">
                <div class="listview listview--hover">
                    <div class="listview__header">
                        Notificações

                        <div class="actions">
                            <a href="" class="actions__item zmdi zmdi-check-all"
                               data-ma-action="notifications-clear"></a>
                        </div>
                    </div>
                    <div class="scrollbar-inner">
                        <div id="containerNotifications" style="height: auto; max-height: 400px;">
                        </div>
                    </div>

                </div>
            </div>
        </li>

        <li class="dropdown top-nav__notifications" hidden>
            <a href="" data-toggle="dropdown" class="top-nav__notify" aria-expanded="false">
                <i class="zmdi zmdi-email"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu--block" x-placement="bottom-end"
                 style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(50px, 36px, 0px);">
                <div class="listview listview--hover">
                    <div class="listview__header">
                        Mensagens

                        <div class="actions">
                            <a href="" class="actions__item zmdi zmdi-plus"></a>
                        </div>
                    </div>
                    <div class="scrollbar-inner">
                        <a href="" class="listview__item">
                            <img src="<?php echo base_url('images/profile-pics/1.jpg') ?>" class="listview__img" alt="">

                            <div class="listview__content">
                                <div class="listview__heading">
                                    David Belle
                                    <small>12:01 PM</small>
                                </div>
                                <p>Cum sociis natoque penatibus et magnis dis parturient montes</p>
                            </div>
                        </a>
                        <a href="" class="listview__item">
                            <img src="<?php echo base_url('images/profile-pics/2.jpg') ?>" class="listview__img" alt="">

                            <div class="listview__content">
                                <div class="listview__heading">
                                    David Belle
                                    <small>12:01 PM</small>
                                </div>
                                <p>Cum sociis natoque penatibus et magnis dis parturient montes</p>
                            </div>
                        </a>
                        <a href="" class="listview__item">
                            <img src="<?php echo base_url('images/profile-pics/3.jpg') ?>" class="listview__img" alt="">

                            <div class="listview__content">
                                <div class="listview__heading">
                                    David Belle
                                    <small>12:01 PM</small>
                                </div>
                                <p>Cum sociis natoque penatibus et magnis dis parturient montes</p>
                            </div>
                        </a>

                    </div>
                    <div class="p-1"></div>
                </div>
            </div>
        </li>

        <li class=top-nav">

            <?php if (isset($_SESSION['pharma']) && $_SESSION['pharma'] == 1) { ?>
                <a href="<?php echo base_url('pharma/Login/logout') ?>" aria-expanded="false">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            <?php } else { ?>
                <a href="<?php echo base_url('representantes/Login/logout') ?>" aria-expanded="false">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            <?php } ?>
        </li>

    </ul>
</div>