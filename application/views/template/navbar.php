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
    <ul class="top-nav">
        <li class="p-5 hidden-lg-down" id="search-cot">
            <div class="input-group" style="margin-top: 20px !important;">
                <?php
                $tempo = $this->db->select("timestamp")->from('ci_sessions')->where("id= '{$this->session->id_sessao}'")->get()->row_array();
                ?>
                <div class="input-group-prepend">
                    <select name="s_integrador" data-toggle="tooltip" title="Selecione qual integrador da cotação" id="s_integrador" style="" class="form-control py-5">
                        <option value="SINTESE">SINTESE</option>
                        <option value="BIONEXO">BIONEXO</option>
                        <option value="APOIO">APOIO</option>
                    </select>
                </div>
                <input type="text" class="form-control busca" data-toggle="tooltip" title="Ex. COT9999-8899" id="inptGetCotacao" placeholder="Buscar Cotacão">

                <div class="input-group-append">
                    <button class="btn btn-light" id="btnGetCotacao" type="button"><i class="fas fa-search" style="font-size: 20px;
                    color: #868e96"></i></button>
                </div>
            </div>
        </li>
        <li class="hidden-xl-up"><a href="" data-ma-action="search-open"><i class="zmdi zmdi-search"></i></a></li>
        <?php if ($this->session->administrador == 0) { ?>
            <li class="" data-toggle="tooltip" <?php if (isset($_SESSION['empresas']) && $this->session->administrador == 0) { ?> title="Clique na seta para trocar de empresa" data-placement="top" <?php } ?>>
                <strong>CNPJ:</strong> <?php echo $this->session->cnpj ?> <br>
                <strong>Empresa:</strong> <?php echo $this->session->nome_fantasia ?> <br>
            </li>
        <?php } ?>
        <?php if (isset($_SESSION['empresas']) && $this->session->administrador == 0) { ?>
            <li>
                <div class="dropdown">
                    <a href="#" class="btn btn-link text-white" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-chevron-down"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" style="height: auto; overflow: scroll; max-height: 400px" aria-labelledby="dropdownMenuButton">

                        <?php foreach ($_SESSION['empresas'] as $empresa) { ?>
                            <a class="dropdown-item" href="<?php echo base_url("login/selecionar_empresa/{$empresa['id']}") ?>">
                                <?php echo $empresa['cnpj']; ?> - <?php echo $empresa['nome_fantasia']; ?> <br>
                                <?php echo $empresa['razao_social']; ?> <br>
                                <?php echo $empresa['cidade']; ?> - <?php echo $empresa['estado']; ?>
                            </a>
                        <?php } ?>

                    </div>
                </div>
            </li>
        <?php } ?>

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
        <li class="top-nav">
            <a class="renovarSessao" id="tempo_sessao" value="<?php $tempo['timestamp'] ?>" title="Sessão expira as <?php echo date('H:i:s', $tempo['timestamp'] + 3600) ?> ">
                <?php echo date('h:i:s', $tempo['timestamp']) ?>
            </a>
        </li>
    </ul>
<!--    <div id="alerta_sessao" >
        <div class="modal fade text-center" id="modalAlerta" role="dialog" aria-hidden="true" style="z-index:100">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content align-content-center" style="border-radius:30px;background-color:#fff;box-shadow:0px 1px 2px 1px #000;">
                    <div class="modal-header text-center">
                        <h3 class="modal-title w-100" style="margin-top: 2px;position: absolute;left: 0px;">
                            Sua sessão vai expirar!</h3>
                        <h5 id="timer_sessao" class="modal-title w-100" style="margin-top: 35px;position: absolute;left: 0px;">
                            <?php /*$timer = strtotime("+10 minutes");
                            echo date('i:s', $timer) */?></h5>
                    </div>
                    <div class="modal-body" style="margin-top: 40px;">
                        <div class="col-12 controls" style="margin-top:25px;">
                            <button class="btn btn-primary renovarSessao" id="renovarSessao" style="background-color: #192069;;color:#fff;font-size:15px;">
                                Atualizar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade text-center" id="modalTimeOut" role="dialog" aria-labelledby="modalTimeOut" aria-hidden="true" style="z-index:100">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content align-content-center" style="border-radius:30px;background-color:#7b7e82;box-shadow:0px 3px 6px 4px #ed3732;">
                    <div class="modal-header text-center">
                        <h3 class="modal-title w-100 text-white" style="margin-top: 5px;
                    position: absolute;
                    left: 0px;">Sua sessão expirou!</h3>
                    </div>
                    <div class="modal-body" style="margin-top: 40px;">
                        <div class="col-12 controls" style="margin-top:25px;">
                            <a href="/login" style="color:#fff;font-size:20px;font-weight:bolder">
                                Clique para entrar novamente
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>-->
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