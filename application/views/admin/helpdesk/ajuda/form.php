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
                <form name="frmArtigo" id="frmArtigo" action="<?php if (isset($formAction)) echo $formAction; ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-12 col-lg-8">
                            <div class="form-group">
                                <label for="">Título</label>
                                <input type="text" id="" name="titulo" value="<?php if (isset($dados['titulo'])) echo $dados['titulo']; ?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="form-group">
                                <label for="">Categoria</label>
                                <select name="id_categoria" id="" class="form-control">
                                    <option value="">Selecione... </option>
                                    <?php foreach ($categorias as $categoria){ ?>
                                        <option <?php if (isset($dados['id_categoria']) && ($categoria['id'] == $dados['id_categoria'] )) echo 'selected'; ?> value="<?php echo $categoria['id']; ?>"><?php echo $categoria['nome']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="">Conteúdo</label>
                                <textarea name="conteudo" id="conteudo" cols="30" rows="30" class="form-control"><?php if (isset($dados['conteudo'])) echo $dados['conteudo']; ?></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="">Palavras Chave</label>
                                <textarea name="keywords" id="" cols="30" rows="3" class="form-control"><?php if (isset($dados['keywords'])) echo $dados['keywords']; ?></textarea>
                                <span class="small">Separe as palavras com (,) vírgula.</span>
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
$(function () {
    tinymce.init({
        menubar: false,
        statusbar: false,
        selector: '#conteudo'
    });
})
</script>

</html>
