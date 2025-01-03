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
<script src="<?php echo THIRD_PARTY . 'plugins/sweetalert/sweetalert.min.js' ?>"></script>
<script src="https://kit.fontawesome.com/e94dc38f5a.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

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