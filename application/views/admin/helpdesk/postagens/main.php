<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>
<div class="content">
    <?php echo $heading; ?>
    <div class="content__inner">

        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <input type="text" class="form-control w-100" placeholder="Pesquisar">
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <?php foreach ($posts as $post){ ?>
                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <span class="bg-light p-1 "> <?php echo $post['nome']; ?></span>
                            <a href="<?php echo $url_update . $post['id'] ;?>">
                                <p class="mt-3">
                                    Postado em: <?php echo date("d/m/Y H:i", strtotime($post['created_at'])); ?>
                                </p>
                            </a>
                            <h4>
                                <?php echo $post['titulo']; ?>
                            </h4>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

    </div>
</div>
</body>

<?php echo $scripts; ?>

<script>

    $(function() {

    });
</script>

</html>
