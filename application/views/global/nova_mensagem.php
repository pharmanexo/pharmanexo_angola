<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php if (isset($menu_correio)) echo $menu_correio; ?>
    <div class="card">
        <div class="card-header">
            <p class="card-title">Nova Mensagem</p>
            <span class="pull-right"><a href="" class="btn btn-primary d-inline pull-right">Enviar</a></span>
        </div>
        <div class="card-body">

        </div>
    </div>

</div>


<?php echo $scripts; ?>

<script>
    $(function() {
        //
    });
</script>
</body>

</html>
