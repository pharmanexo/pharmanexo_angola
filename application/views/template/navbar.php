<div class="header">
    <div class="navigation-trigger" data-ma-action="aside-open" data-ma-target=".sidebar">
        <div class="navigation-trigger__inner">
            <i class="navigation-trigger__line"></i>
            <i class="navigation-trigger__line"></i>
            <i class="navigation-trigger__line"></i>
        </div>
    </div>

    <div class="header__logo ">
        <h1><a href="<?php echo base_url('dashboard') ?>"><img style="width: 70%;" class="img-fluid" src="<?php echo base_url('images/img/logo-branca.png'); ?>" alt=""></a></h1>
    </div>
    <ul class="top-nav text-right">

        <li class="hidden-xl-up"><a href="" data-ma-action="search-open"><i class="zmdi zmdi-search"></i></a></li>


        <!-- Icone de notiicações -->
        <li class="dropdown top-nav__notifications">
            <a href="" data-toggle="dropdown" class="<?php if (isset($notificacoes) && count($notificacoes) > 0) echo 'top-nav__notify'; ?>" id="btnNotfifications" aria-expanded="false"><i class="zmdi zmdi-notifications"></i></a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu--block" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(50px, 36px, 0px);">
                <div class="listview listview--hover">

                    <?php if (isset($notificacoes) && !empty($notificacoes)) : ?>

                        <div class="listview__header">
                            Notificações
                            <div class="actions">
                                <a href="" class="actions__item zmdi zmdi-check-all" data-ma-action="notifications-clear" data-url="<?php echo $read_all; ?>"></a>
                            </div>
                        </div>

                        <div class="listview__scroll scrollbar-inner">

                            <?php foreach ($notificacoes as $notificacao) : ?>

                                <a href="<?php if (!empty($notificacao['url'])) echo $notificacao['url']; ?>" class="listview__item">

                                    <img src="<?php echo base_url("images/info/{$notificacao['type']}.png"); ?>" class="listview__img" alt="">

                                    <div class="listview__content" data-toggle="tooltip" title="<?php echo $notificacao['message']; ?> ">
                                        <div class="listview__heading"></div>
                                        <p><?php echo $notificacao['message']; ?></p>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <div class="listview__header">
                            <i class="fas fa-bell-slash"></i> Nenhuma notificação
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </li>
        <!-- Icone de logout -->
        <li class=top-nav">
            <a href="<?php echo base_url('Login/logout') ?>" aria-expanded="false">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </li>

    </ul>
</div>
<aside class="chat">
    <div class="chat__header">
        <h2 class="chat__title">Calculadora
        </h2>
    </div>

    <div class="scroll-wrapper scrollbar-inner" style="position: relative;">
        <div class="calculator">
            <div class="row displayBox">
                <input class="displayText" id="display" style="background-color: transparent; border: none !important;" value="0" onkeypress="return numeros(event)" />
            </div>
            <div class="row numberPad">
                <div class="col-md-9">
                    <div class="row">
                        <button class="btn clear hvr-back-pulse" id="clear">C</button>
                        <button class="btn btn-calc hvr-radial-out" id="sqrt">√</button>
                        <button class="btn btn-calc hvr-radial-out hvr-radial-out" id="square">x<sup>2</sup></button>
                    </div>
                    <div class="row">
                        <button class="btn btn-calc hvr-radial-out" id="seven">7</button>
                        <button class="btn btn-calc hvr-radial-out" id="eight">8</button>
                        <button class="btn btn-calc hvr-radial-out" id="nine">9</button>
                    </div>
                    <div class="row">
                        <button class="btn btn-calc hvr-radial-out" id="four">4</button>
                        <button class="btn btn-calc hvr-radial-out" id="five">5</button>
                        <button class="btn btn-calc hvr-radial-out" id="six">6</button>
                    </div>
                    <div class="row">
                        <button class="btn btn-calc hvr-radial-out" id="one">1</button>
                        <button class="btn btn-calc hvr-radial-out" id="two">2</button>
                        <button class="btn btn-calc hvr-radial-out" id="three">3</button>
                    </div>
                    <div class="row">
                        <button class="btn btn-calc hvr-radial-out" id="plus_minus">&#177;</button>
                        <button class="btn btn-calc hvr-radial-out" id="zero">0</button>
                        <button class="btn btn-calc hvr-radial-out" id="decimal">,</button>
                    </div>
                </div>
                <div class="col-md-3 operationSide">
                    <button id="divide" class="btn btn-operation hvr-fade">÷</button>
                    <button id="multiply" class="btn btn-operation hvr-fade">×</button>
                    <button id="subtract" class="btn btn-operation hvr-fade">−</button>
                    <button id="add" class="btn btn-operation hvr-fade">+</button>
                    <button id="equals" class="btn btn-operation equals hvr-back-pulse">=</button>
                </div>
            </div>
        </div>
    </div>
</aside>