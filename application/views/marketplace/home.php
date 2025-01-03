<!DOCTYPE html>
<html lang="pt-BR">
<?php if (isset($header)) echo $header; ?>
<body class="bg-light">
<?php if (isset($navbar)) echo $navbar; ?>
<?php if (isset($sidebar)) echo $sidebar; ?>
<div id="myCarousel" class="mb-3 carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item"
             style="background-image: url(<?php echo base_url('images/banner/banner1.jpeg') ?>); background-size: cover">
            <div class="container">
                <div class="carousel-caption text-left ">
                </div>
            </div>
        </div>
        <div class="carousel-item active"
             style="background-image: url(<?php echo base_url('images/banner/banner2.jpg') ?>); background-size: cover">
            <div class="container text-white" style="padding: 180px 0">
                <h1 class="mb-3 text-center animated fadeInRightBig" style="font-size: 80px; ">LINHA HOSPITALAR</h1>
                <h4 class="text-center animated fadeInRightBig mb-5">Reunimos as melhores marcas e os melhores
                    distribuidores para que você tenha o melhor preço.</h4>
                <div class="text-center">
                    <a href="<?php echo base_url('Compra/busca_produto_linha/Hospitalar') ?>" class="btn btn-light  animated fadeInRightBig m-auto py-3 px-5">CONFIRA AGORA</a>
                </div>
            </div>
        </div>
    </div>
    <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
<div class="container p-4 mb-3">
    <div class="text-left">
        <h3 class="titulo titulo-primary titulo-lg">Categorias</h3>
    </div>
    <div class="row">
        <div class="col-12">
            <div id="owl_categorias" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#owl_categorias" data-slide-to="0" class="active"></li>
                    <li data-target="#owl_categorias" data-slide-to="1"></li>
                </ol>
                <!-- Carousel items -->
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="row">
                            <div class="col-4">
                                <a href="#">
                                    <img class="rounded" src="<?php echo base_url('images/img/linhaodonto.jpg') ?>"
                                         alt="Image" style="border-radius: 20px; opacity: 0.5; max-width:100%;">
                                    <!-- Carousel Caption-->
                                    <div class="carousel-caption">
                                        <h3  class="text-primary titulo">Odonto</h3>
                                    </div>
                                    <!-- Fim Carousel Caption -->
                                </a>
                            </div>
                            <div class="col-4">
                                <a href="#">
                                    <img class="rounded" src="<?php echo base_url('images/img/linhafarma.jpg') ?>"
                                         alt="Image" style="border-radius: 20px; opacity: 0.5; max-width:100%;">
                                    <!-- Carousel Caption-->
                                    <div class="carousel-caption">
                                        <h3  class="text-primary titulo">Farma</h3>
                                    </div>
                                    <!-- Fim Carousel Caption -->
                                </a>
                            </div>
                            <div class="col-4">
                                <a href="#">
                                    <img class="rounded" src="<?php echo base_url('images/img/linhahospitalar.jpg') ?>"
                                         alt="Image" style="border-radius: 20px; opacity: 0.5; max-width:100%;">
                                    <!-- Carousel Caption-->
                                    <div class="carousel-caption">
                                        <h3  class="text-primary titulo">Hospitalar</h3>
                                    </div>
                                    <!-- Fim Carousel Caption -->
                                </a>
                            </div>
                        </div>
                        <!--.row-->
                    </div>
                    <!--.item-->
                    <div class="carousel-item">
                        <div class="row">
                            <div class="col-4">
                                <a href="#">
                                    <img class="rounded" src="<?php echo base_url('images/img/linhaveterinaria.jpg') ?>"
                                         alt="Image" style="border-radius: 20px; opacity: 0.5; max-width:100%;">
                                    <!-- Carousel Caption-->
                                    <div class="carousel-caption">
                                        <h3  class="text-primary titulo" >Veterinaria</h3>
                                    </div>
                                    <!-- Fim Carousel Caption -->
                                </a>
                            </div>
                            <div class="col-4">
                                <a href="#">
                                    <img class="rounded" src="<?php echo base_url('images/img/linhanutricao.jpg') ?>"
                                         alt="Image" style="border-radius: 20px; opacity: 0.5; max-width:100%;">
                                    <!-- Carousel Caption-->
                                    <div class="carousel-caption">
                                        <h3  class="text-primary titulo">Nutrição</h3>
                                    </div>
                                    <!-- Fim Carousel Caption -->
                                </a>
                            </div>
                            <div class="col-4">
                                <a href="#">
                                    <img class="rounded" src="<?php echo base_url('images/img/linhacosmetico.jpg') ?>"
                                         alt="Image" style="border-radius: 20px; opacity: 0.5; max-width:100%;">
                                    <!-- Carousel Caption-->
                                    <div class="carousel-caption ">
                                        <h3 class="text-primary titulo" >Cosméticos</h3>
                                    </div>
                                    <!-- Fim Carousel Caption -->
                                </a>
                            </div>

                        </div>
                        <!--.row-->
                    </div>
                    <!--.item-->
                </div>
                <!--.carousel-inner-->
            </div>
            <!--.Carousel-->

        </div>
    </div>
</div>
<section class="py-3 bg-primary">
    <div class="container p-4">
        <div class="text-left">
            <h3 class="titulo titulo-light titulo-lg">Produtos em Destaque</h3>
        </div>
        <div class="row ">
            <div class="col-6">
                <img src="<?php echo base_url('images/img/forn_image-2.jpg') ?>" alt="Image" style="max-width:100%;">
            </div>
            <div class="col-6">
                <img src="<?php echo base_url('images/img/forn_image-1.jpg') ?>" alt="Image" style="max-width:100%;">
            </div>
        </div>
    </div>
</section>

<div class="container p-4">
    <!-- Example row of columns -->
    <div class="row" id="animado">
        <div class="col-md-4 px-3 ">
            <p class="text-center"><i class="fas fa-check fa-3x"></i></p>
            <h5 class="titulo titulo-sm text-center">Importe sua lista</h5>
            <p style="text-align: justify">Quando você importar sua lista para nosso sistema automaticamente ele
                efetuará um match, apresentando todos os produtos com nome similar ou idêntico, basta clicar no produto
                que mostrará as opções para a compra. </p>
        </div>
        <div class="col-md-4 px-3 border-left border-right">
            <p class="text-center"><i class="fas fa-users fa-3x"></i></p>
            <h5 class="titulo titulo-sm text-center">Equipe Especializada</h5>
            <p style="text-align: justify">Possuímos equipe técnica e call center preparados para atender suas demandas
                e garantir todo o suporte necessário. </p>
        </div>
        <div class="col-md-4 px-3">
            <p class="text-center"><i class="fas fa-money-bill-alt fa-3x"></i></p>
            <h5 class="titulo titulo-sm text-center">Ja tem uma cotação?</h5>
            <p style="text-align: justify">Vamos procurar o melhor preço para te atender. <br>Possuímos serviço de busca ativa, onde o especialista avalia a possibilidade
                de cobrir os preços de seu orçamento.</p>
        </div>
    </div>
</div> <!-- /container -->

<section class="py-3" style="background: #777e91">
    <div class="container p-4 d-none d-lg-block">
        <div class="row">
            <div class="col-12 col-lg-6">
                <h3 class="titulo titulo-light titulo-lg mb-3">Newsletter</h3>
                <p class="text-white">Recebe nossas novidades e promoções direto no seu e-mail.</p>
            </div>
            <div class="col-12 col-lg-6">
                <form>
                    <div class="form-group">
                        <div class="input-group mt-5">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                            </div>
                            <input id="email" type="text" class="form-control input-sm" name="email" placeholder="Informe seu o e-mail"
                                   maxlength="50" required="true">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">Cadastrar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php if (isset($scripts)) echo $scripts; ?>
<script>
    // optional
    $('.carousel').carousel({
        interval: 12000,
    });

    $('#myCarousel').carousel({
        interval: 12000,
    });

    $(function () {
        var animacao = "animated flip";
        var fim = "webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend";
        $("#animado").hover(function (e) {
            $("#recebe1").addClass(animacao).one(fim, function () {
                $(this).removeClass(animacao);
            });
            $("#recebe2").addClass(animacao).one(fim, function () {
                $(this).removeClass(animacao);
            });
            $("#recebe3").addClass(animacao).one(fim, function () {
                $(this).removeClass(animacao);
            });
            e.preventdefault();
        });

    });
</script>
</body>
</html>

