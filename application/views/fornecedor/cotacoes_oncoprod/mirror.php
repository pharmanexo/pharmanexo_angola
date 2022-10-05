<!DOCTYPE html>
<html lang="pt-BR">
<?php echo $header; ?>
<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>
<div class="content">
    <?php echo $heading; ?>

    <div class="content__inner">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Cotação enviada com sucesso.</h6>
            </div>
            <div class="card-body">
                <?php echo $mirror ?>
            </div>
        </div>
    </div>
</div>

<?php echo $scripts; ?>

<script>
</script>
</body>

</html>