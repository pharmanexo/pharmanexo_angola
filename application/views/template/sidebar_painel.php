<aside class="sidebar">
    <div class="scrollbar-inner">
        <div class="user">
            <div class="user__info" data-toggle="dropdown">

                <?php $avatar = $this->session->avatar ?>
                <?php $url = IMG_PATH.'avatar/'.$avatar.'.png' ?>
                <?php  $logo = (empty($this->session->foto) || is_null($this->session->foto)) ? base_url('images/usuarios/no-user.png') : $avatar; ?>
                
                <img class="user__img" src="<?php echo $url ?>" alt="avatar">
                <?php if ($this->session->verifica != "1") { ?>
                    <i style="font-size:12px;color: green;position: relative;top: 10px;left:-10px" class="fa fa-lock"></i>
                <?php } else { ?>
                    <i style="font-size:12px;color: red;position: relative;top: 10px;left:-5px" class="fa fa-exclamation"></i>
                <?php } ?>
                <div>
                    <div class="user__name"><?php echo $this->session->nickname ?></div>
                    <div class="user__email"><small><?php echo $this->session->email ?></small></div>

                </div>
            </div>
            <div class="dropdown-menu">
                <?php if ($this->session->has_userdata('administrador') && $this->session->administrador == 1) { ?>
                    <a class="dropdown-item" href="<?php echo base_url("admin/usuarios/perfil") ?>">Visualizar Perfil</a>
                <?php } elseif ($this->session->has_userdata('id_representante')) { ?>
                    <a class="dropdown-item" href="<?php echo base_url("representantes/perfil") ?>">Visualizar Perfil</a>
                <?php } else { ?>
                    <a class="dropdown-item" href="<?php echo base_url("fornecedor/usuarios/perfil") ?>">Visualizar Perfil</a>
                <?php } ?>
            </div>
        </div>
        <ul class="navigation">
            <?php if (isset($routes)) { ?>
                <?php foreach ($routes as $route) { ?>

                    <li class="<?php if (isset($route['submenu']) && !empty($route['submenu'])) echo "navigation__sub" ?>  <?php if (isset($route['class'])) echo $route['class'] ?>">
                        <a href="<?php if (isset($route['url'])) echo base_url($route['url']); ?>" class="<?php if ($route['modal'] == 1) echo 'modalOpen'; ?>">
                            <i class="<?php echo $route['icone']; ?>"></i> <?php echo $route['rotulo']; ?>
                            <?php if ($route['url'] == '#') { ?> <span class="float-right"><i class="fas fa-caret-down"></i></span> <?php } ?>
                        </a>
                        <?php if (isset($route['submenu']) && !empty($route['submenu'])) { ?>
                            <ul id="dashboard">
                                <?php foreach ($route['submenu'] as $subrota) { ?>
                                    <li class="<?php if (isset($subrota['class'])) echo $subrota['class'] ?>"><a href="<?php if ($subrota['url']) echo base_url($subrota['url']); ?>" class="<?php if ($subrota['modal'] == 1) echo 'modalOpen'; ?>"><?php echo $subrota['rotulo']; ?></a></li>
                                <?php } ?>
                            </ul>
                        <?php } ?>

                    </li>
                <?php } ?>
            <?php } ?>
            <li>
                <a href="<?php echo base_url('Login/logout') ?>"><i class="fas fa-sign-out-alt"></i>Sair</a>
            </li>
        </ul>
    </div>
</aside>