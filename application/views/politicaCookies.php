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
                                    <a style="font-size:30px;font-weight:bold" class="nav-link" href="/home">HOME <span class="sr-only">(current)</span></a>
                                </li>
                                <li class="nav-item">
                                    <a style="font-size:30px;font-weight:bold"class="nav-link" href="/login">LOGIN</a>
                                </li>
                            </ul>
                        </div>

                    </nav>
                </div>
            </header>
            <div class="container">
                <object type="application/pdf" data="<?php echo ASSETS_PATH ?>Politica_de_Privacidade.pdf" type="application/pdf" width="100%" height="1000px">
            </div>


        </div>
    </div>


    <!-- footer section -->
    <footer class="footer_section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-12 footer_col">

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

<?php if (isset($scripts)) echo $scripts; ?>

<script>
    $(function(e) {

    })
</script>

</body>

</html>