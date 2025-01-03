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

    <title> Pharmanexo Angola </title>

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
                <img src="<?php echo HOME_PATH ?>images/ubuntu_header_2.jpg" alt="">
            </div>

            <!-- header section strats -->
            <header class="header_section">
                <div class="container">
                    <nav class="navbar navbar-expand-lg custom_nav-container ">
                        <a class="logo mr-auto"><img src="<?php echo HOME_PATH ?>images/ubuntu.png" alt="Logo Pharmanexo" class="img-fluid" style="max-width: 280px"></a>

                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class=""> </span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav">
                                <li class="nav-item active">
                                    <a class="nav-link" href="/home">Home <span class="sr-only">(current)</span></a>
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
                                                O MAIOR PORTAL DE INTEGRAÇÃO PARA PRODUTOS FARMACÊUTICOS DO
                                                CONTINENTE AFRICANO
                                            </h1>
                                            <p>
                                                INTEGRANDO E AUTOMATIZANDO A CADEIA DE FORNECIMENTO DE PRODUTOS FARMACÊUTICOS
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

        <section id="portais" HIDDEN class="department_section layout_padding">
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
                                O Pharmanexo é a maior ferramenta de integração transparente do Brasil, conectando
                                distribuidores aos
                                maiores portais de cotação, atuando também na Europa e África.
                            </p>
                            <p>
                                Possuímos
                                integração com os maiores ERP's do mundo: Oracle, Totvs (Winthor, Protheus), Sankhya, SAP,
                                assim como,
                                disponibilizamos
                                API para integração com os mais diversos sistemas.
                            </p>
                            <p>
                                Entregamos aos nossos clientes uma ferramenta validada e homologada pelos 4 maiores portais
                                de
                                cotação do mercado brasileiro (Bionexo, Síntese, Apoio Cotações e Huma).
                            <p>
                                Nossos números falam um pouco sobre nosso resultado: mais de 1 milhão cotações processadas,
                                com
                                aproximadamente R$ 8,2 bilhões em respostas enviadas, sendo 94,3% respondidas de forma
                                totalmente
                                automática.
                            </p>
                            <p>
                                Utilizando um modelo disruptivo desde sua concepção até suas entregas, integramos somente
                                empresas
                                convidadas
                                por nossos parceiros, sem custo de integração, sem limite de usuários, sem cobranças
                                variáveis, e
                                totalmente
                                customizada para cada cliente.
                            </p>
                            <p>

                                Não oferecemos a nossos clientes um software ou uma licença, mas sim uma ferramenta de
                                automação e
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
                        Temos integração com os maiores ERPs do mercado como: Oracle ERP, SAP, TOTVs, Sankhya, entre outros.
                    </p>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-lg-3 mx-auto">
                        <div class="box">
                            <div class="img-box">
                                <img src="<?php echo HOME_PATH ?>images/oracle-erp.png" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3 mx-auto">
                        <div class="box">
                            <div class="img-box">
                                <img src="<?php echo HOME_PATH ?>images/TOTVS.jpg" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3 mx-auto">
                        <div class="box">
                            <div class="img-box">
                                <img src="<?php echo HOME_PATH ?>images/SAP.png" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3 mx-auto">
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
                                <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
                                <input type="hidden" name="action" value="validate_captcha">
                                <div class="form-row">
                                    <div class="col-lg-6">
                                        <div>
                                            <input required class="contato" type="text" name="nome" placeholder="Seu Nome *" />
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div>
                                            <input required class="contato" type="text" name="telefone" placeholder="Seu melhor número *" />
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <input type="email" required class="contato" name="email" placeholder="Seu melhor Email *" />
                                </div>
                                <div>
                                    <input type="text" name="mensagem" required class="message-box contato" placeholder="Nos conte do que precisa *" />
                                </div>
                                <div class="mb-3">
                                    <div class="error-message text-danger"></div>
                                    <div class="sent-message text-success"></div>
                                </div>
                                <div class="btnsubmit">
                                    <button class="btnSubmit" id="btnContato" type="submit">
                                        ENVIAR
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

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
                                        administracao@pharmanexo.ao
                                    </span>
                                </a>
                                <a href="#">
                                    <i class="fa fa-envelope" aria-hidden="true"></i>
                                    <span>
                                        helpdesk@pharmanexo.ao
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
                </div>
                <div class="footer-info">
                    <p>
                        © 2019 - 2022 Pharmanexo Integração e inteligência de mercado
                    </p>
                </div>
            </div>
        </footer>
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

    <a href="#" onclick="topFunction()" class="back-to-top" id="myBtn" style="position: fixed"><i class="ri-arrow-up-line"></i></a>

</body>

<?php if (isset($footer)) echo $footer; ?>

<?php if (isset($scripts)) echo $scripts; ?>
<!-- google recaptcha -->
<script src="https://www.google.com/recaptcha/api.js?render=6Le5i60qAAAAAKHqfRAWymsBeoyGiSf-BSIOhIvU"></script>
<script>
    $(function(e) {
        // Get the button:
        let mybutton = document.getElementById("myBtn");

        // When the user scrolls down 20px from the top of the document, show the button
        window.onscroll = function() {
            scrollFunction()
        };

        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                mybutton.style.display = "block";
            } else {
                mybutton.style.display = "none";
            }
        }

        // When the user clicks on the button, scroll to the top of the document
        function topFunction() {
            document.body.scrollTop = 0; // For Safari
            document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
        }



        $('#FormContact').on('submit', function(e) {
            e.preventDefault();
            var me = $(this);
            var url = $(this).attr('action');
            var data = $(this).serialize();
            var btn = $('#btnContato');
            btn.html("<i class='fas fa-spin fa-spinner'></i> Enviando...").attr('disabled', true);
            grecaptcha.ready(function() {
                // do request for recaptcha token
                // response is promise with passed token
                grecaptcha.execute('6Le5i60qAAAAAKHqfRAWymsBeoyGiSf-BSIOhIvU', {
                    action: 'validate_captcha'
                }).then(function(token) {

                    // add token to form
                    document.getElementById('g-recaptcha-response').value = token;
                    $.post(me.attr('action'), me.serialize(), function(xhr) {
                        btn.html("Enviar Mensagem").attr('disabled', false);
                        if (xhr.type === 'success') {
                            Swal.fire({
                                title: 'Enviamos sua solicitação com sucesso, em breve alguém do nosso time entrará em contato!',
                                icon: 'success',
                            }).then((result) => {
                                $('.contato').val('');
                            })
                        } else {
                            Swal.fire({
                                title: 'Houve um erro ao enviar sua mensagem, nos comunique por helpdesk@pharmanexo.com.br.',
                                icon: 'error',
                            })
                        }



                    }, 'JSON')


                });
            });


        })

    })
</script>

</body>

</html>