<!-- ======= Footer ======= -->
<footer id="footer">


    <div class="container d-md-flex py-4">

        <div class="mr-md-auto text-center text-md-left">
            <div class="copyright">
                &copy; Copyright <strong><span>Pharmanexo® 2017</span></strong>. All Rights Reserved
            </div>
        </div>
        <div class="social-links text-center text-md-right pt-3 pt-md-0">
            <a href="https://twitter.com/pharmanexo" target="_blank" class="twitter"><i class="bx bxl-twitter"></i></a>
            <a href="https://www.facebook.com/Pharmanexo-E-Commerce-Brasil-573000749572214/" target="_blank" class="facebook"><i class="bx bxl-facebook"></i></a>
            <a href="https://www.instagram.com/pharmanexo/" target="_blank" class="instagram"><i class="bx bxl-instagram"></i></a>
            <a href="https://www.linkedin.com/in/pharmanexo-intermediadora-45765a143/" target="_blank" class="linkedin"><i class="bx bxl-linkedin"></i></a>
            <a href="https://www.youtube.com/channel/UCFa9M5uJ2GlfZQCOKLXWQ-w" target="_blank" class="linkedin"><i class="bx bxl-youtube"></i></a>
        </div>
    </div>
</footer><!-- End Footer -->

<a href="#" class="back-to-top"><i class="ri-arrow-up-line"></i></a>
<div id="preloader"></div>
<div class="box-cookies hide text-center">
    <p class="msg-cookies">Este site usa cookies para garantir que você obtenha a melhor experiência em nosso site. <br>   <span class="small"><a class="text-white" href="">Leia nossa política de privacidade</a></span></p>

    <button class="btn-cookies">Aceitar!</button>
</div>
<script>
    (() => {
        if (!localStorage.pureJavaScriptCookies) {
            document.querySelector(".box-cookies").classList.remove('hide');
        }

        const acceptCookies = () => {
            document.querySelector(".box-cookies").classList.add('hide');
            localStorage.setItem("pureJavaScriptCookies", "accept");
        };

        const btnCookies = document.querySelector(".btn-cookies");

        btnCookies.addEventListener('click', acceptCookies);
    })();
</script>
