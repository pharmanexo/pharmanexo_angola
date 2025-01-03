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
                <h4 class="card-title">Informe a cotação e selecione o fornecedor</h4>
            </div>
            <div class="card-body">
               <form method="POST" action="<?php if(isset($form_action)) echo $form_action ?>" id="formCotacoes">
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="cd_cotacao">Cotação</label>
                                <select class="select2" name="cd_cotacao" id="cd_cotacao">
                                    <option value="">Selecione</option>
                                    <?php foreach($cotacoes as $cot) { ?>
                                    <option value="<?php echo $cot['cd_cotacao'] ?>"><?php echo $cot['cd_cotacao'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="form-group">
                                <label for="fornecedores">Fornecedor</label>
                                <select class="form-control" name="id_fornecedor" id="fornecedores">
                                    <option value="">Selecione</option>
                                    <?php foreach($fornecedores as $f) { ?>
                                    <option value="<?php echo $f['id'] ?>"><?php echo "{$f['cnpj']} - {$f['razao_social']}" ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
               </form>
            </div>
        </div>
    </div>
</div>
</body>

<?php echo $scripts; ?>

<script>

    var url = "<?php echo $form_action ?>";
   
    $(function() {
       
        $('#cd_cotacao').on('change', function(e) {

            $('#formCotacoes').attr('action', url + '/' + $(this).val());
        });
        
    });
</script>

</html>
