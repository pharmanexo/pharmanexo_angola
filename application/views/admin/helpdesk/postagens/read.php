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
               <?php echo $post['conteudo']; ?>
           </div>
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
