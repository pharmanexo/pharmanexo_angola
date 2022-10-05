<!--Start of Tawk.to Script-->
<script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/5dee4805d96992700fcb701c/default';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
    })();
</script>
<!-- TOPBAR -->
<nav class="navbar navbar-expand-lg navbar-light " style="background: #d8d8d8; height: 30px">
    <div class="container">
        <?php
        if ($logado == 1) {
            ?>
            <div class="navbar-collapse collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="text-muted nav-item">
                        <a href="<?php echo base_url("quem-somos"); ?>" class="nav-link">Sobre a Pharmanexo</a>
                    </li>
                    <li class="text-muted nav-item">
                        <a href="<?php echo base_url("politica-de-seguranca"); ?>" class="nav-link">Política de Segurança</a>
                    </li>
                    <li class="text-muted nav-item">
                        <a href="<?php echo base_url("termos-de-uso"); ?>" class="nav-link">Termos de Uso</a>
                    </li>
                    <li class="text-muted nav-item">
                        <a href="" class="nav-link">Fale Conosco</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <?php if ($tipo_usuario != 2) { ?>
                        <li class="nav-item"><a href="#" class="nav-link"><i class="fa fa-envelope"></i> 0</a></li>
                        <li class="nav-item"><a href="#" class="nav-link"><i class="fa fa-bell"></i> 0</a>
                        </li><?php } ?>
                    <li class="nav-item dropdown">
                        <a href="#" id="dd_user" class="nav-link dropdown-toggle text-muted" data-toggle="dropdown"><i
                                    class="fa fa-user"></i> <?php if ($logado == 1) echo "Área do Cliente"; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd_user">
                            <!-- <a href="<?php /*echo base_url('area_cliente/Painel') */?>" class="dropdown-item">Painel</a>-->
                            <a href="<?php echo base_url('Dados') ?>" class="dropdown-item">Perfil</a>
                            <a href="<?php echo base_url('Login/logout') ?>" class="dropdown-item">Sair</a>
                        </div>
                    </li>
                </ul>
            </div>
        <?php } ?>
    </div>
</nav>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm p-0">
    <div class="container">
        <a class="navbar-brand" href="<?php echo base_url(); ?>"><img src="<?php echo base_url('images/img/123456.png') ?>" alt="Image" style="max-width:100%;"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav m-auto" id="navMarketplace">
                <li class="nav-item">
                    <a href="" class="nav-link">Comprar</a>
                    <ul class="navbar-nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo base_url('marketplace/catalogo') ?>">
                                Linha Hospitalar
                            </a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="<?php /*echo base_url('Compra/busca_produto_linha/Farma') */?>">
                                Linha Farma
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href=" <?php /*echo base_url('Compra/busca_produto_linha/Odonto') */?>">
                                Linha Odonto
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href=" <?php /*echo base_url('Compra/busca_produto_linha/Cosmetico') */?>">
                                Linha Cosméticos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href=" <?php /*echo base_url('Compra/busca_produto_linha/Veterinario') */?>">
                                Linha Veterinários
                            </a>
                        </li>-->
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link " href="#" id="relatorio" >Transações e Relatórios</a>
                    <ul class="nav flex-column" aria-labelledby="relatorio">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo base_url('Pedido')  ?>">
                                Pedidos
                            </a>
                        </li>
                        <!--   <li class="nav-item">
                            <a class="nav-link" <a href="<?php /*echo base_url('Pedido/PedidoRecusado')  */?>">
                                Pedidos Recusados
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php /*echo base_url('Relatorio/aguardando_pagamento')  */?>"> Aprovados Aguardando Pagamento</a>
                        </li>-->
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo base_url('ordens-compras') ?>">
                                Ordens de Compra
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('busca-ativa') ?>"> Busca
                        Ativa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('faq') ?>"> FAQ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('Login/logout') ?>" data-toggle="tooltip" title="Fornecedores e Documentação"> Fornecedores</a>
                </li>

            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item px-3 bg-primary">
                    <a href="<?php echo base_url('Compra/Carrinho') ?>" class="nav-link text-white">
                        <i class="fas fa-shopping-cart"></i> R$ <span id="valorCarrinho">0,00</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
