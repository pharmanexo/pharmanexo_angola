<aside class="sidebar">
    <div class="scrollbar-inner">
        <ul class="navigation">
            <?php if (isset($routes)) { ?>
                <?php foreach ($routes as $route) { ?>

                    <li class="<?php if (isset($route['submenu']) && !empty($route['submenu'])) echo "navigation__sub" ?>  <?php if (isset($route['class'])) echo $route['class'] ?>">
                        <a href="<?php if (isset($route['url']) && $route['url'] != '#') echo base_url($route['url']); ?>" class="<?php if (isset($route['modal']) && $route['modal'] == 1) echo 'modalOpen'; ?>">
                            <i class="<?php if(isset($route['icone'])) echo $route['icone']; ?>"></i> <?php if (isset( $route['rotulo'])) echo $route['rotulo']; ?>
                            <?php if ( isset($route['url']) && $route['url'] == '#') { ?> <span class="float-right"><i class="fas fa-caret-down"></i></span> <?php } ?>
                        </a>
                        <?php if (isset($route['submenu']) && !empty($route['submenu'])) { ?>
                            <ul id="dashboard">
                                <?php foreach ($route['submenu'] as $subrota) { ?>
                                    <li class="<?php if (isset($subrota['class'])) echo $subrota['class'] ?>"><a href="<?php if ($subrota['url']) echo base_url($subrota['url']); ?>" class="<?php if (isset($subrota['modal']) && $subrota['modal'] == 1) echo 'modalOpen'; ?>"><?php echo $subrota['rotulo']; ?></a></li>
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