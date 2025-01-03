<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <form action="<?php echo $form_action?>" method="post">
        <div class="form-group">
            <label for="">ID REM</label>
            <input name="id_rem" type="text" class="form-group">
        </div>
        <div class="form-group">
            <label for="">ID DEST</label>
            <input name="id_dest" type="text" class="form-group">
        </div>
        <div class="form-group">
            <label for="">Mensagem</label>
            <input name="mensagem" type="text" class="form-group">
        </div>

        <input type="submit" value="enviar">
    </form>

</div>


<?php echo $scripts; ?>

<script>
    $(function() {
        //
    });
</script>
</body>

</html>
