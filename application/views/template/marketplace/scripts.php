<script src="<?php echo THIRD_PARTY . 'plugins/jquery-3.4.1.min.js'; ?>"></script>
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
<script src="<?php echo THIRD_PARTY . 'theme/js/app.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'theme/js/scripts.js' ?>"></script>

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
