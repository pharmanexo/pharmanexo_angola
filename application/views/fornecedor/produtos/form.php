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
                <form method="POST" action="<?php if (isset($formAction)) echo $formAction; ?>" id="formProdutos">
                    <div class="row">
                        <div class="col-12">
                            <div id="novo-produto-row-0" class="row">
                                <div class="col-12 col-lg-12 form-group ">
                                    <label>Produto</label>
                                    <input type="text" id="nome_comercial" name="nome_comercial" value="<?php echo $produto['nome_comercial']; ?>" class="form-control" readonly>
                                    <!--                                    <select id="id_produto" name="id_produto" class="form-control" data-src="--><?php //if (isset($slct2_produtos)) echo $slct2_produtos; ?><!--" data-value="--><?php //if (isset($produto['id_produto'])) echo $produto['id_produto']; ?><!--" style="width: 100%"></select>-->
                                </div>

                                <div class="col-12 col-lg-6 form-group ">
                                    <label>Apresentação </label>
                                    <input type="text" id="apresentacao" class="form-control" value="<?php if (isset($produto['apresentacao'])) echo $produto['apresentacao'] ?>" placeholder="" maxlength="45" name="apresentacao" readonly>
                                </div>

                                <div class="col-12 col-lg-6  form-group">
                                    <label>Marcas</label>
                                    <input type="hidden" name="id_marca" id="id_marca" value="">
                                    <input type="text" id="marca" name="marca" value="<?php if (isset($marca)) echo $marca ?>" class="form-control" readonly>
                                </div>
                                <div class="col-12 col-lg-3 form-group">
                                    <label>EAN <span class="mr-0 text-right d-inline-block" data-toggle="tooltip" title="CÓDIGO DE BARRAS"><i class="fas fa-info-circle"></i></span></label>
                                    <input type="text" id="ean" class="form-control" value="<?php if (isset($produto['ean'])) echo $produto['ean'] ?>" placeholder="" maxlength="45" name="ean" readonly>
                                </div>

                                <div class="col-12 col-lg-3 form-group">
                                    <label>RMS <span class="mr-0 text-right d-inline-block" data-toggle="tooltip" title="REGISTRO DO MINISTÉRIO DA SAÚDE"><i class="fas fa-info-circle"></i></span></label>
                                    <input type="text" id="rms" class="form-control" value="<?php if (isset($produto['rms'])) echo $produto['rms'] ?>" placeholder="" maxlength="45" name="rms" readonly>
                                </div>

                                <div class="col-12 col-lg-2 form-group">
                                    <label>Código Interno <span class="mr-0 text-right d-inline-block" data-toggle="tooltip" title="Código de identificação no fornecedor"><i class="fas fa-info-circle"></i></span></label>
                                    <input type="text" id="codigo" class="form-control" value="<?php if (isset($produto['codigo'])) echo $produto['codigo'] ?>" placeholder="" maxlength="45" name="codigo" readonly>
                                </div>

                                <div class="col-12 col-md-6 col-lg-2 form-group ">
                                    <label>Unidade</label>
                                    <input type="text" id="unidade" value="<?php if (isset($produto['unidade'])) echo $produto['unidade'] ?>" class="form-control" placeholder="" maxlength="45" name="unidade" readonly>
                                </div>

                                <div class="col-12 col-md-6 col-lg-3 form-group">
                                    <label>Quantidade na embalagem</label>
                                    <input type="number" id="qtd_unidade" value="<?php if (isset($produto['quantidade_unidade'])) echo $produto['quantidade_unidade'] ?>" class="form-control" placeholder="" maxlength="45" name="qtd_unidade" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <?php if (!isset($produto['id'])) { ?>
                <div class="card-footer">
                    <p class='text-info'>O cadastro de produtos passam por uma aprovação, somente após confirmado o cadastro que o produto estará disponível para configurar preços e estoque.</p>
                </div>
            <?php } ?>
        </div>
    </div>
</div>


<?php if (isset($scripts)) echo $scripts; ?>

<script type="text/javascript">
    let id_cliente;
    var url_delete = $('#data-table').data('delete');

    $(function () {
        var $form = $('#formProdutos');

        $('#formProdutos').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                type: $form.attr('method'),
                url: $form.attr('action'),
                data: $form.serialize(),
                dataType: "json",

                success: function (response) {
                    alert(response.status);
                }
            });

            return false;
        });


    });

</script>
</body>