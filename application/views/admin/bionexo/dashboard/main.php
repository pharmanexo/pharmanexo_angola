<!DOCTYPE html>
<html lang="pt-BR">
<?php echo $header; ?>
<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>
<div class="content">
    <?php if (isset($meta)) { ?>
        <div class="row">
            <?php foreach ($meta as $item){ ?>
            <div class="col-4">
                <div class="card  <?php if ($item['dia'] >= META_DEPARA) echo 'border-success'?>" >
                    <div class="card-body">
                        <p><strong><?php echo $item['nome']; ?></strong></p>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar" style="width: <?php echo ($item['dia'] * 100) / META_DEPARA; ?>%; color: #000; " role="progressbar" aria-valuenow="<?php echo $item['dia']; ?>" aria-valuemin="<?php echo META_DEPARA; ?>" aria-valuemax="600"><?php echo $item['dia'] . "/" .META_DEPARA; ?></div>
                        </div>
                        <table class="table table-bordered">
                            <tr>
                                <td class="text-center">
                                    <p><strong>Dia</strong></p>
                                    <p><?php echo $item['dia']; ?>/<?php echo META_DEPARA; ?> Produtos</p>
                                </td>
                                <td class="text-center">
                                    <p><strong>Mês</strong></p>
                                    <p><?php echo $item['total']; ?> Produtos</p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    <?php } ?>

</div>

<?php echo $scripts; ?>
<script>


</script>

</body>
</html>
