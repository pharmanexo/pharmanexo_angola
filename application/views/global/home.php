<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <div class="content__inner">
        <div class="messages">
            <?php echo $contatos; ?>
            <div class="messages__body">
                <div class="messages__header">
                    <div class="toolbar toolbar--inner mb-0">
                        <div class="toolbar__label">Selecione um contato ao lado</div>
                    </div>
                </div>

                <div class="messages__content">
                   <p class="text-muted text-center">Nada a ser exibido</p>
                </div>
            </div>
        </div>
    </div>
</div>


<?php echo $scripts; ?>

<script>

</script>
</body>

</html>
