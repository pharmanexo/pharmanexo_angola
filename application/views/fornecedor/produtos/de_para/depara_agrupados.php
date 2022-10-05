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
            <div class="card-body">
                <div class="table-responsive col-sm">


                    <table id="data-table" class="table table-condensend table-hover"  >
                        <thead>
                        <tr>
                            <th>Código</th>
                            <th>Descrição</th>
                            <th></th>
                        </tr>
                        </thead>
                        <?php foreach ($produtos as $produto){ ?>
                            <tr>
                                <td><?php echo $produto['codigo'] ?></td>
                                <td><?php echo $produto['nome_comercial'] . " - " . $produto['descricao'] ?></td>
                                <td>  <a href="<?php echo $url_update . "/{$produto['codigo']}"?>">
                                        Abrir
                                    </a></td>
                            </tr>

                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $scripts; ?>
<script>

    $(function () {
        var dt1 = $('#data-table').DataTable({
            "processing": false,
            "serverSide": false,
            lengthChange: 100,
        });
    });
</script>
</body>

