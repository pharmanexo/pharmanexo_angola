<!DOCTYPE html>
<html lang="pt-BR">
<?php echo $header; ?>
<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>
<div class="content">
    <?php echo $heading; ?>

    <div class="content__inner">
        <form>
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Cotação enviada com sucesso.</h6>
                </div>
                <div class="card-body">
                 <?php echo $msg; ?>
                </div>
            </div>
        </form>
    </div>
</div>

<?php echo $scripts; ?>

<script>
    
    $(function () {

        $('#destinatarios').select2({
            tags: true,
            tokenSeparators: [',', ' ']
            createTag: function (params) {
                var term = $.trim(params.term);

                if (term === '') {
                  return null;
                }

                return {
                  id: term,
                  text: term,
                  newTag: true // add additional parameters
                }
            }
        });

    });

</script>
</body>

</html>