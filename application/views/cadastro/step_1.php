<!DOCTYPE html>
<html lang="en">

<?php if (isset($header)) echo $header; ?>

<head>
    <!-- Basic -->
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <!-- Site Metas -->
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <link rel="shortcut icon" href="<?php echo HOME_PATH ?>images/favicon.png" type="">

    <title> Portal Pharmanexo </title>

    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>

    <!-- fonts style -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

    <!--owl slider stylesheet -->
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"/>

    <!-- font awesome style -->
    <link href="<?php echo HOME_PATH ?>css/font-awesome.min.css" rel="stylesheet"/>

    <!-- Custom styles for this template -->
    <link href="<?php echo HOME_PATH ?>css/style.css" rel="stylesheet"/>
    <!-- responsive style -->
    <link href="<?php echo HOME_PATH ?>css/responsive.css" rel="stylesheet"/>

    <style>

        .header_section {
            background-color: #e7e7e7;
            padding-bottom: 20px;
            border-bottom: 2px solid #0b0f3d;
        }

        .navbar-nav li a {
            color: #0b0f3d !important;
        }

        #formCad {
            max-width: 600px !important;
        }

    </style>

</head>

<body>
<div class="scroll-container">

    <div class="hero_area">

        <div class="hero_bg_box">

        </div>

        <!-- header section strats -->
        <header class="header_section">
            <div class="container">
                <nav class="navbar navbar-expand-lg custom_nav-container ">
                    <a class="logo mr-auto"><img src="<?php echo HOME_PATH ?>images/logopharmanexo.png"
                                                 alt="Logo Pharmanexo" class="img-fluid"></a>

                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                        <span class=""> </span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav" style="color: #0b0f3d">
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
        <section class="form-cadastro ">
            <div class="container ">
                <div class="page-hero d-flex  justify-content-center">
                    <form action="<?php echo $form_action; ?>" class="" id="formCad">
                        <div class="row">
                            <div class="col-12">
                                <p>Olá, seja bem-vindo ao Pharmanexo.</p>
                                <p>Vamos prosseguir com seu cadastro, carregamos os dados para facilitar o
                                    preenchimento, fique a vontade para alterar caso encontre alguma informação
                                    divergente/desatualizada.</p>
                            </div>
                        </div>
                        <hr style="background-color: #0b0f3d">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="">CNPJ</label>
                                    <input type="text" id="cnpj" name="cnpj" required  value="<?php if (isset($dados['cnpj'])) echo $dados['cnpj']; ?>" class="form-control text-center">
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="">Nome Fantasia</label>
                                    <input type="text" id="nome_fantasia" required  value="<?php if (isset($dados['nome_fantasia'])) echo $dados['nome_fantasia']; ?>" name="nome_fantasia" class="form-control text-center">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Razão Social</label>
                            <input type="text" id="razao_social" required  value="<?php if (isset($dados['razao_social'])) echo $dados['razao_social']; ?>" name="razao_social" class="form-control text-center">
                        </div>

                        <div class="form-group">
                            <label for="">Seu Nome</label>
                            <input type="text" id="nome"   name="nome" class="form-control text-center">
                        </div>
                        <div class="form-group">
                            <label for="">Celular</label>
                            <input type="text" id="celular"   name="celular" class="form-control text-center">
                        </div>
                        <div class="form-group">
                            <label for="">E-mail</label>
                            <input type="text" id="email"   name="email" class="form-control text-center">
                        </div>
                        <hr>
                        <div class="text-right">
                            <input type="reset" value="Cancelar" class="btn btn-secondary">
                            <input type="submit" value="Continuar" class="btn btn-primary">
                        </div>

                    </form>

                </div>

            </div>

        </section>
        <!-- end slider section -->


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
                                    <i class="fa fa-envelope" aria-hidden="true"></i>
                                    <span>
                                        helpdesk@pharmanexo.com.br
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
        <!-- jQery -->
        <script type="text/javascript" src="<?php echo HOME_PATH ?>js/jquery-3.4.1.min.js"></script>


        <!-- popper js -->
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
                integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
                crossorigin="anonymous">
        </script>
        <!-- bootstrap js -->
        <script type="text/javascript" src="<?php echo HOME_PATH ?>js/bootstrap.js"></script>
        <!-- owl slider -->
        <script type="text/javascript"
                src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
        </script>
        <!-- custom js -->
        <script type="text/javascript" src="<?php echo HOME_PATH ?>js/custom.js"></script>
        <!-- Google Map -->
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh39n5U-4IoWpsVGUHWdqB6puEkhRLdmI&callback=myMap">
        </script>
        <!-- End Google Map -->

    </div>

    <a href="#" onclick="topFunction()" class="back-to-top" id="myBtn" style="position: fixed"><i
                class="ri-arrow-up-line"></i></a>

</body>

<?php if (isset($footer)) echo $footer; ?>

<?php if (isset($scripts)) echo $scripts; ?>

<script type="text/javascript" src="<?php echo HOME_PATH ?>js/inputmask/jquery.inputmask.js"></script>
<!-- google recaptcha -->
<script src="https://www.google.com/recaptcha/api.js?render=6Le5i60qAAAAAKHqfRAWymsBeoyGiSf-BSIOhIvU"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(function (e) {

        $('#celular').inputmask("(99) 99999-9999");  //static mask
        $('#cnpj').inputmask("99.999.999/9999-99");  //static mask

        $('#formCad').submit(function (e) {
            e.preventDefault();
            var data = $(this).serialize();
            var action = $(this).prop('action');

            $.post(action, data, function (xhr) {
                Swal.fire({
                    icon: xhr.type,
                    text: xhr.message
                }).then(function (e){
                    if (xhr.url != ''){
                        window.location = xhr.url;
                    }
                });

                if (xhr.type == 'success'){
                    $('#formCad').reset();

                }


            }, 'JSON');

        });


        // Get the button:
        let mybutton = document.getElementById("myBtn");

        // When the user scrolls down 20px from the top of the document, show the button
        window.onscroll = function () {
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


        $('#FormContact').on('submit', function (e) {
            e.preventDefault();
            var me = $(this);
            var url = $(this).attr('action');
            var data = $(this).serialize();
            var mess = $('#messageReturn');
            grecaptcha.ready(function () {
                // do request for recaptcha token
                // response is promise with passed token
                grecaptcha.execute('6Le5i60qAAAAAKHqfRAWymsBeoyGiSf-BSIOhIvU', {
                    action: 'validate_captcha'
                }).then(function (token) {

                    // add token to form
                    document.getElementById('g-recaptcha-response').value = token;
                    $.post(me.attr('action'), me.serialize(), function (xhr) {
                        btn.html("Enviar Mensagem").attr('disabled', false);
                        if (xhr.type === 'success') {
                            console.log(xhr);
                            $('.sent-message', '#formContact').html(xhr.message);
                            alert('teste');
                        } else {
                            $('.error-message', '#formContact').html(xhr.message);

                        }

                        setTimeout(function () {
                            $('input, textarea', '#formContact').val('');
                            $('.sent-message', '#formContact').html('');
                            $('.error-message', '#formContact').html('');

                        }, 5000);


                    }, 'JSON')


                });
            });


        })

        $('#formSupport').submit(function (e) {
            e.preventDefault();
            var me = $(this);
            var btn = $('.btnSubmit');
            btn.html("<i class='fas fa-spin fa-spinner'></i> Enviando...").attr('disabled', true);
            $.post(me.attr('action'), me.serialize(), function (xhr) {
                btn.html("Enviar Mensagem").attr('disabled', false);
                if (xhr.type === 'success') {
                    $('.text-success', '#formSupport').html(xhr.message);
                } else {
                    $('.error-message', '#formSupport').html(xhr.message);
                }

            }, 'JSON')
        })

        $('#exampleModal').on('hidden.bs.modal', function () {
            $('input, textarea', '#formSupport').val('');
            $('.text-success', '#formSupport').html('');
            $('.error-message', '#formSupport').html('');
        });

    })
</script>

</body>

</html>