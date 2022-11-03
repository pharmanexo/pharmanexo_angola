<!DOCTYPE html>
<html lang="en">

<?php if (isset($header)) echo $header; ?>

<head>
    <!-- Basic -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Site Metas -->
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="shortcut icon" href="<?php echo HOME_PATH ?>images/favicon.png" type="">

    <title> Portal Pharmanexo </title>

    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

    <!-- fonts style -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

    <!--owl slider stylesheet -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

    <!-- font awesome style -->
    <link href="<?php echo HOME_PATH ?>css/font-awesome.min.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="<?php echo HOME_PATH ?>css/style.css" rel="stylesheet" />
    <!-- responsive style -->
    <link href="<?php echo HOME_PATH ?>css/responsive.css" rel="stylesheet" />

</head>

<body>
    <div class="scroll-container">

        <div class="hero_area">

            <div class="hero_bg_box">
                <img src="<?php echo HOME_PATH ?>images/hero.png" alt="">
            </div>

            <!-- header section strats -->
            <header class="header_section">
                <div class="container">
                    <nav class="navbar navbar-expand-lg custom_nav-container ">
                        <a class="logo mr-auto"><img src="<?php echo HOME_PATH ?>images/logopharmanexo.png" alt="Logo Pharmanexo" class="img-fluid"></a>

                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class=""> </span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav">
                                <li class="nav-item active">
                                    <a class="nav-link" href="/home">Home <span class="sr-only">(current)</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#portais">PORTAIS</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#sobre"> SOBRE</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#sistemas">SISTEMAS</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#contato">CONTATOS</a>
                                </li>
                            </ul>
                        </div>
                        
                    </nav>
                </div>
            </header>
            <!-- end header section -->
            <!-- slider section -->
            <section class="slider_section ">
                <div id="customCarousel1" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="container ">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="detail-box">
                                            <h1>
                                                O MAIOR PORTAL DE INTEGRAÇÃO MULTIPLAFORMAS PARA PRODUTOS FARMACÊUTICOS DO BRASIL
                                            </h1>
                                            <p>
                                                INTEGRANDO E AUTOMATIZANDO A RESPOSTA DE COTAÇÕES DA<br> INDÚSTRIA E DISTRIBUIDORES CONVIDADOS
                                            </p>
                                            <div class="btn-box">
                                                <a href="<?php echo base_url('login') ?>" class="btn1">
                                                    ENTRAR
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
            <!-- end slider section -->
        </div>


        <!-- department section -->

        <section id="portais" class="department_section layout_padding">
            <div class="department_container">
                <div class="container ">
                    <div class="heading_container heading_center">
                        <h2>
                            PORTAIS
                        </h2>
                        <p>
                            Todos os portais em uma única tela, incluindo cotações e ordens de compras.
                        </p>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="logos ">
                                <img src="<?php echo HOME_PATH ?>images/bionexo.png" alt="">
                                <div class="detail-box">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="logos ">
                                <img src="<?php echo HOME_PATH ?>images/sintese.png" alt="">
                                <div class="detail-box">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="logos ">
                                <img src="<?php echo HOME_PATH ?>images/apoio cotacao.png" alt="">
                                <div class="detail-box">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="logos">
                                <img src="<?php echo HOME_PATH ?>images/huma.jpg" alt="">
                                <div class="detail-box">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- end department section -->

        <!-- about section -->

        <section id="sobre" class="about_section layout_margin-bottom">
            <div class="container  ">
                <div class="row">
                    <div class="col-md-6 ">
                        <div class="img-box">
                            <img src="<?php echo HOME_PATH ?>images/about-img.jpg" alt="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-box">
                            <div class="heading_container">
                                <h2>
                                    SOBRE <span>NÓS</span>
                                </h2>
                            </div>
                            <p>
                                O Pharmanexo é a maior ferramenta de integração transparente do Brasil, conectando distribuidores aos
                                maiores portais de cotação.
                            </p>
                            <p>
                                Possuímos
                                integração com diversos ERP's como: Totvs (Winthor, Protheus), Sankhya, SAP, Conta Azul, assim como,
                                disponibilizamos
                                API para integração com os mais diversos sistemas.
                            </p>
                            <p>
                                Entregamos aos nossos clientes uma ferramenta validada e homologada pelos 4 maiores portais de
                                cotação do mercado (Bionexo, Síntese, Apoio Cotações e Huma).
                            <p>
                                Nossos numeros falam um pouco sobre nosso resultado: mais de 1 milhão cotações processadas, com
                                aproximadamente R$ 8,2 bilhões em respostas enviadas, sendo 94,3% respondidas de forma totalmente
                                automática.
                            </p>
                            <p>
                                Utilizando um modelo disruptivo desde sua concepção até suas entregas, integramos somente empresas
                                convidadas
                                por nossos parceiros, sem custo de integração, sem limite de usuários, sem cobranças variáveis, e
                                totalmente
                                customizada para cada cliente.
                            </p>
                            <p>

                                Não oferecemos a nossos clientes um software ou uma licença, mas sim uma ferramenta de automação e
                                inteligência
                                de mercado.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- end about section -->

        <!-- systems section -->

        <section id="sistemas" class="doctor_section layout_padding">
            <div class="container">
                <div class="heading_container heading_center">
                    <h2>
                        Sistemas (ERPs)
                    </h2>
                    <p class="col-md-10 mx-auto px-0">
                        Temos integração com os maiores ERPs do mercado como: SAP, TOTVs, Sankhya, entre outros.
                    </p>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-lg-4 mx-auto">
                        <div class="box">
                            <div class="img-box">
                                <img src="<?php echo HOME_PATH ?>images/TOTVS.jpg" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 mx-auto">
                        <div class="box">
                            <div class="img-box">
                                <img src="<?php echo HOME_PATH ?>images/SAP.png" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 mx-auto">
                        <div class="box">
                            <div class="img-box">
                                <img src="<?php echo HOME_PATH ?>images/sankhya.png" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- end doctor section -->

        <!-- contact section -->
        <section id="contato" class="contact_section layout_padding">
            <div class="container">
                <div class="heading_container">
                    <h2>
                        CONTATO
                    </h2>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form_container">
                            <form id="FormContact" action="<?php echo base_url('contato/sendMessage'); ?>" method="post" role="form">
                                <div class="form-row">
                                    <div class="col-lg-6">
                                        <div>
                                            <input required type="text" placeholder="Seu Nome *" />
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div>
                                            <input required type="text" placeholder="Seu melhor número *" />
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <input type="email" required placeholder="Seu melhor Email *" />
                                </div>
                                <div>
                                    <input type="text" required class="message-box" placeholder="Nos conte do que precisa *" />
                                </div>
                                <div class="btnsubmit">
                                    <button  type="submit">
                                        ENVIAR
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <a href="#" class="back-to-top"><i class="ri-arrow-up-line"></i></a>
                    <div class="col-md-6">
                        <div class="map_container">
                            <div class="map">
                                <iframe style="border:0; width: 100%; height: 370px;" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7483.256305779943!2d-40.292836!3d-20.315653!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x2f91e8604faf31ef!2sPharmanexo%20Portal%20de%20Oportunidades%20de%20Produtos%20Farmac%C3%AAuticos!5e0!3m2!1spt-BR!2sbr!4v1590624053465!5m2!1spt-BR!2sbr" frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end contact section -->

        <!-- client section 

  <section class="client_section layout_padding-bottom">
    <div class="container">
      <div class="heading_container heading_center ">
        <h2>
          Testimonial
        </h2>
      </div>
      <div id="carouselExample2Controls" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div class="row">
              <div class="col-md-11 col-lg-10 mx-auto">
                <div class="box">
                  <div class="img-box">
                    <img src="images/client.jpg" alt="" />
                  </div>
                  <div class="detail-box">
                    <div class="name">
                      <h6>
                        Alan Emerson
                      </h6>
                    </div>
                    <p>
                      Enim consequatur odio assumenda voluptas voluptatibus esse nobis officia. Magnam, aspernatur
                      nostrum explicabo, distinctio laudantium delectus deserunt quia quidem magni corporis earum
                      inventore totam consectetur corrupti! Corrupti, nihil sunt? Natus.
                    </p>
                    <i class="fa fa-quote-left" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="row">
              <div class="col-md-11 col-lg-10 mx-auto">
                <div class="box">
                  <div class="img-box">
                    <img src="images/client.jpg" alt="" />
                  </div>
                  <div class="detail-box">
                    <div class="name">
                      <h6>
                        Alan Emerson
                      </h6>
                    </div>
                    <p>
                      Enim consequatur odio assumenda voluptas voluptatibus esse nobis officia. Magnam, aspernatur
                      nostrum explicabo, distinctio laudantium delectus deserunt quia quidem magni corporis earum
                      inventore totam consectetur corrupti! Corrupti, nihil sunt? Natus.
                    </p>
                    <i class="fa fa-quote-left" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="row">
              <div class="col-md-11 col-lg-10 mx-auto">
                <div class="box">
                  <div class="img-box">
                    <img src="images/client.jpg" alt="" />
                  </div>
                  <div class="detail-box">
                    <div class="name">
                      <h6>
                        Alan Emerson
                      </h6>
                    </div>
                    <p>
                      Enim consequatur odio assumenda voluptas voluptatibus esse nobis officia. Magnam, aspernatur
                      nostrum explicabo, distinctio laudantium delectus deserunt quia quidem magni corporis earum
                      inventore totam consectetur corrupti! Corrupti, nihil sunt? Natus.
                    </p>
                    <i class="fa fa-quote-left" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="carousel_btn-container">
          <a class="carousel-control-prev" href="#carouselExample2Controls" role="button" data-slide="prev">
            <i class="fa fa-long-arrow-left" aria-hidden="true"></i>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carouselExample2Controls" role="button" data-slide="next">
            <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
            <span class="sr-only">Next</span>
          </a>
        </div>
      </div>
    </div>
  </section>-->

        <!-- end client section -->

        <!-- footer section -->
        <footer class="footer_section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-lg-12 footer_col">
                        <div class="footer_contact">
                            <h4>
                                Nos contate
                            </h4>
                            <div class="contact_link_box">
                                <a href="">
                                    <i class="fa fa-envelope" aria-hidden="true"></i>
                                    <span>
                                        administracao@pharmanexo.com.br
                                    </span>
                                </a>
                                <a href="#">
                                    <i class="fa fa-phone" aria-hidden="true"></i>
                                    <span>
                                        Telefone (27) 2464-0012
                                    </span>
                                </a>
                                <a href="https://www.google.com/maps/place/Pharmanexo+Portal+de+Oportunidades+de+Produtos+Farmac%C3%AAuticos/@-20.3154362,-40.2932153,15z/data=!4m2!3m1!1s0x0:0x2f91e8604faf31ef?sa=X&ved=2ahUKEwik38ihsfH6AhUCJrkGHX4wDbMQ_BJ6BAhJEAU">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                    <span>
                                        Localização
                                    </span>
                                </a>
                            </div>
                        </div>
                        <div class="footer_social">
                            <a href="https://www.facebook.com/people/Pharmanexo-E-Commerce-Brasil/100063746143342/">
                                <i class="fa fa-facebook" aria-hidden="true"></i>
                            </a>
                            <a href="https://mobile.twitter.com/pharmanexo">
                                <i class="fa fa-twitter" aria-hidden="true"></i>
                            </a>
                            <a href="https://br.linkedin.com/company/pharmanexo">
                                <i class="fa fa-linkedin" aria-hidden="true"></i>
                            </a>
                            <a href="https://www.instagram.com/pharmanexo/">
                                <i class="fa fa-instagram" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                    <!--<div class="col-md-6 col-lg-3 footer_col">
          <div class="footer_detail">
            <h4>
              About
            </h4>
            <p>
              Beatae provident nobis mollitia magnam voluptatum, unde dicta facilis minima veniam corporis laudantium
              alias tenetur eveniet illum reprehenderit fugit a delectus officiis blanditiis ea.
            </p>
          </div>
        </div>
        <div class="col-md-6 col-lg-2 mx-auto footer_col">
          <div class="footer_link_box">
            <h4>
              Links
            </h4>
            <div class="footer_links">
              <a class="active" href="index.html">
                Home
              </a>
              <a class="" href="about.html">
                About
              </a>
              <a class="" href="departments.html">
                Departments
              </a>
              <a class="" href="doctors.html">
                Doctors
              </a>
              <a class="" href="contact.html">
                Contact Us
              </a>
            </div>
          </div>
        </div>-->
                </div>
                <div class="footer-info">
                    <p>
                        © 2019 - 2022 Pharmanexo Integração e inteligência de mercado
                    </p>
                </div>
            </div>
        </footer>
        <!-- footer section -->

        <!-- jQery -->
        <script type="text/javascript" src="<?php echo HOME_PATH ?>js/jquery-3.4.1.min.js"></script>
        <!-- popper js -->
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
        </script>
        <!-- bootstrap js -->
        <script type="text/javascript" src="<?php echo HOME_PATH ?>js/bootstrap.js"></script>
        <!-- owl slider -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
        </script>
        <!-- custom js -->
        <script type="text/javascript" src="<?php echo HOME_PATH ?>js/custom.js"></script>
        <!-- Google Map -->
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh39n5U-4IoWpsVGUHWdqB6puEkhRLdmI&callback=myMap">
        </script>
        <!-- End Google Map -->
    </div>
</body>

<?php if (isset($footer)) echo $footer; ?>

<?php if (isset($scripts)) echo $scripts; ?>

<script>
    $(function(e) {


        $('#formSupport').submit(function(e) {
            e.preventDefault();
            var me = $(this);
            var btn = $('.btnSubmit');
            btn.html("<i class='fas fa-spin fa-spinner'></i> Enviando...").attr('disabled', true);
            $.post(me.attr('action'), me.serialize(), function(xhr) {
                btn.html("Enviar Mensagem").attr('disabled', false);
                if (xhr.type === 'success') {
                    $('.text-success', '#formSupport').html(xhr.message);
                } else {
                    $('.error-message', '#formSupport').html(xhr.message);
                }

            }, 'JSON')
        })

        $('#exampleModal').on('hidden.bs.modal', function() {
            $('input, textarea', '#formSupport').val('');
            $('.text-success', '#formSupport').html('');
            $('.error-message', '#formSupport').html('');
        });

        $('#formContact').submit(function(e) {
            e.preventDefault();
            var me = $(this);
            var btn = $('.btnSubmit');
            btn.html("<i class='fas fa-spin fa-spinner'></i> Enviando...").attr('disabled', true);
            $.post(me.attr('action'), me.serialize(), function(xhr) {
                btn.html("Enviar Mensagem").attr('disabled', false);
                if (xhr.type === 'success') {
                    console.log(xhr);
                    $('.sent-message', '#formContact').html(xhr.message);

                } else {
                    $('.error-message', '#formContact').html(xhr.message);

                }

                setTimeout(function() {
                    $('input, textarea', '#formContact').val('');
                    $('.sent-message', '#formContact').html('');
                    $('.error-message', '#formContact').html('');

                }, 5000);



            }, 'JSON')
        })

    })
</script>

</body>

</html>