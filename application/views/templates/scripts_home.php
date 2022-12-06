<!-- Vendor JS Files -->
<script src="<?php echo ASSETS_PATH ?>/vendor/jquery/jquery.min.js"></script>
<script src="<?php echo ASSETS_PATH ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo ASSETS_PATH ?>/vendor/jquery.easing/jquery.easing.min.js"></script>
<script src="<?php echo ASSETS_PATH ?>/vendor/php-email-form/validate.js"></script>
<script src="<?php echo ASSETS_PATH ?>/vendor/waypoints/jquery.waypoints.min.js"></script>
<script src="<?php echo ASSETS_PATH ?>/vendor/counterup/counterup.min.js"></script>
<script src="<?php echo ASSETS_PATH ?>/vendor/venobox/venobox.min.js"></script>
<script src="<?php echo ASSETS_PATH ?>/vendor/owl.carousel/owl.carousel.min.js"></script>
<script src="<?php echo ASSETS_PATH ?>/vendor/isotope-layout/isotope.pkgd.min.js"></script>
<script src="<?php echo ASSETS_PATH ?>/vendor/aos/aos.js"></script>
<script src="https://kit.fontawesome.com/e94dc38f5a.js" crossorigin="anonymous"></script>

<!-- Template Main JS File -->
<script src="<?php echo ASSETS_PATH ?>/js/main.js"></script>
<?php

if (isset($scripts))
    foreach ($scripts as $script) {
        echo "<script src='{$script}'></script>";
    }
?>

<script>
    $(function() {


    })
</script>