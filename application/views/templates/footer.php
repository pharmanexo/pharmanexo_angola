<!-- ======= Footer ======= -->
<footer id="footer">


    <div class="container d-md-flex py-4">

        <div class="mr-md-auto text-center text-md-left">

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


<div id="preloader"></div>
<div class="box-cookies1 hide text-center">
    <p style="font-size: 14px" class="msg-cookies">Nós e os terceiros selecionados usamos cookies ou tecnologias similares para finalidades técnicas e, com seu consentimento,
        para outras finalidades, conforme especificado na <a style="color: red;" href="<?php echo base_url('home/politica_cookies') ?>" target="_blank">política de cookies</a>. 
        Negá-los poderá tornar os recursos relacionados indisponíveis.
        <br>Use o botão “Aceitar” para consentir com o uso de tais tecnologias ou clique em “Recusar” para continuar sem aceitar.
    </p>
    <button class="btn-cookies1">Aceitar!</button>
    <button class="btn-cookies">Recusar!</button>

</div>
<script>
    (() => {
        if (!localStorage.pureJavaScriptCookies) {

            document.querySelector(".box-cookies1").classList.remove('hide');
        }

        const acceptCookies = () => {
            document.querySelector(".box-cookies1").classList.add('hide');
            localStorage.setItem("pureJavaScriptCookies", "accept");
        };

        const btnCookies = document.querySelector(".btn-cookies1");

        btnCookies.addEventListener('click', acceptCookies);
        $('.btn-cookies').click(function() {
            $('.box-cookies1').toggle();
        });
    })();
</script>

