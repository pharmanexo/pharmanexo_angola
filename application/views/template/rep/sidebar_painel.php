<aside class="sidebar">
    <div class="scrollbar-inner">
        <div class="user">



            <div class="user__info" data-toggle="dropdown">
                <?php if (isset($_SESSION['pharma']) && $_SESSION['pharma'] == 1){ ?>
                    <?php $url_logo = base_url("public/clientes/{$cliente['id']}/{$cliente['logo']}") ?>
                    <?php $logo = (empty($cliente['logo']) || is_null($this->session->foto)) ? base_url('images/usuarios/user-default.png') : $url_logo; ?>

                    <img class="user__img" src="<?php echo $url_logo ?>" alt="">
                    <div>
                        <div class="user__name"><?php if (isset($cliente['razao_social'])) echo $cliente['razao_social']; ?></div>
                    </div>
                <?php }else{ ?>
                    <?php $url_logo = base_url("public/representantes/{$this->session->id}/{$this->session->foto}") ?>
                    <?php $logo = (empty($this->session->foto) || is_null($this->session->foto)) ? base_url('images/usuarios/user-default.png') : $url_logo; ?>

                    <img class="user__img" src="<?php echo $logo ?>" alt="">
                    <div>
                        <div class="user__name"><?php echo $this->session->nome ?></div>
                        <div class="user__email"><small><?php echo $this->session->email ?></small></div>

                    </div>
                <?php  } ?>
            </div>

        </div>
        <ul class="navigation">
            <?php if (isset($_SESSION['pharma']) && $_SESSION['pharma'] == 1) { ?>

                <li>
                    <a href="<?php echo base_url('pharma/dashboard') ?>"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
                </li>
                <li>
                    <a href="<?php echo base_url('pharma/pedidos') ?>"><i class="fas fa-plus"></i>Novo
                        Orçamento</a>
                </li>

                <li>
                    <a href="<?php echo base_url('pharma/pedidos_realizados') ?>"><i
                                class="fas fa-money-bill"></i>Orçamentos Realizados</a>
                </li>

                <li>
                    <a href="<?php echo base_url('pharma/faturas') ?>"><i
                                class="fas fa-file"></i>Faturas</a>
                </li>

                <li>
                    <a href="<?php echo base_url('pharma/pedidos_urgentes') ?>" hidden><i
                                class="fas fa-file"></i>Pedidos Urgentes</a>
                </li>

                <li>
                    <a href="<?php echo base_url('pharma/Login/logout') ?>"><i class="fas fa-sign-out-alt"></i>Sair</a>
                </li>
            <?php } else { ?>

                <li>
                    <a href="<?php echo base_url('representantes/dashboard') ?>"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
                </li>
                <li>
                    <a href="<?php echo base_url('representantes/pedidos') ?>"><i class="fas fa-plus"></i>Novo
                        Pedido</a>
                </li>
                <li>
                    <a href="<?php echo base_url('representantes/catalogo') ?>"><i class="fas fa-box-open"></i>Catálogo</a>
                </li>
                <li>
                    <a href="<?php echo base_url('representantes/pedidos_representantes') ?>"><i
                                class="fas fa-money-bill"></i>Pedidos Realizados</a>
                </li>

                <li>
                    <a href="<?php echo base_url('representantes/promocoes') ?>"><i class="fas fa-funnel-dollar"></i>Promoções</a>
                </li>

                <li>
                    <a href="<?php echo base_url('representantes/vendas_diferenciadas') ?>"><i class="fas fa-award"></i>Vendas
                        Diferenciadas</a>
                </li>
                <li>
                    <a href="<?php echo base_url('representantes/perfil') ?>"><i class="fas fa-user"></i>Meu Perfil</a>
                </li>
                <li>
                    <a href="<?php echo base_url('Login/logout') ?>"><i class="fas fa-sign-out-alt"></i>Sair</a>
                </li>

            <?php } ?>
        </ul>
    </div>
</aside>