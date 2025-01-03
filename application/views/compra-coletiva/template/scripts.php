<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="<?php echo ASSETS; ?>plugins/fontawesome-free-5.13.0-web/js/all.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>
<script src="//cdn.quilljs.com/1.3.6/quill.core.js"></script>
<script src="//cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="<?php echo ASSETS; ?>js/jquery.inputmask.bundle.js"></script>
<script src="<?php echo ASSETS; ?>js/jquery.maskMoney.min.js"></script>
<script src="<?php echo ASSETS; ?>js/toastr/toastr.min.js"></script>
<script src="<?php echo ASSETS; ?>js/scripts.js"></script>

<?php
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
