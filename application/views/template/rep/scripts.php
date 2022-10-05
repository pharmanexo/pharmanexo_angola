<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<script src="<?php echo THIRD_PARTY . 'plugins/bootstrap-4.2.1/js/bootstrap.bundle.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'plugins/toastr/toastr.min.js' ?>"></script>

<script src="<?php echo THIRD_PARTY . 'theme/plugins/popper.js/popper.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'theme/plugins/jquery-scrollbar/jquery.scrollbar.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'theme/plugins/jquery-scrollLock/jquery-scrollLock.min.js' ?>"></script>

<script src="<?php echo THIRD_PARTY . 'theme/plugins/datatables/jquery.dataTables.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'theme/plugins/datatables-buttons/dataTables.buttons.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'theme/plugins/datatables-buttons/buttons.print.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'theme/plugins/jszip/jszip.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'theme/plugins/datatables-buttons/buttons.html5.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'plugins/dataTables.select.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'plugins/jquery.inputmask.bundle.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'plugins/jquery.maskMoney.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'plugins/sweetalert/sweetalert.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'plugins/select2-4.0.7/dist/js/select2.full.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'plugins/bootstrap-datepicker-1.9.0/js/bootstrap-datepicker.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'plugins/bootstrap-datepicker-1.9.0/locales/bootstrap-datepicker.pt-BR.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'plugins/isotope.pkgd.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'theme/js/app.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'theme/js/scripts.js' ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<?php if (isset($_SESSION['id_usuario'])) { ?>
    <script src="<?php echo THIRD_PARTY . 'theme/js/notifications.js' ?>"></script>
<?php } ?>
<script src="https://kit.fontawesome.com/e94dc38f5a.js" crossorigin="anonymous"></script>
<?php
/**
 * Created by PhpStorm.
 * User: dutra
 * Date: 10/06/2019
 * Time: 23:22
 */
if (isset($scripts))
    foreach ($scripts as $script) {
        echo "<script src='{$script}'></script>";
    }
?>

<?php if (isset($warning)) { ?>
    <script>
        formWarning(JSON.parse('<?php echo $warning; ?>'));
    </script>
<?php } ?>

<script src="<?php echo THIRD_PARTY . 'theme/plugins/pace-1.0.2/pace.min.js' ?>"></script>
<script>
    $(document).ajaxStart(function () {
        Pace.restart();
    });
</script>

