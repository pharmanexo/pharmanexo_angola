<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>


<div class="content">
    <?php echo $heading; ?>
    <div class="container">
        <?php if (isset($_SESSION['empresas'])) { ?>
            <?php foreach ($_SESSION['empresas'] as $empresa) { ?>
                <p><?php echo $empresa['nome_fantasia']; ?></p>
            <?php } ?>
        <?php } ?>
    </div>

    <?php echo $scripts; ?>

    <script>


    </script>
</body>
</html>
