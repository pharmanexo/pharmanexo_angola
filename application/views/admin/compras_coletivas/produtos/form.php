<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>
<div class="content">
    <?php echo $heading; ?>
    <div class="content__inner">
	<form action="<?php if (isset($form_action)) echo $form_action; ?>" method="post" id="frmProduto" enctype="multipart/form-data">
		<div class="card mt-3">
			<div class="card-body">
				<div class="row">
					<div class="col-12 col-lg-4" >
						<label for="imgInp">
							<img src="<?php echo (isset($produto['contrato'])) ? "https://pharmanexo.com.br/adesao/contratos/contrato{$produto['contrato']}/{$produto['imagem']}" : $src_logo; ?>" id="blah" data-toggle="tooltip" data-title="Clique para inserir uma imagem" class="img-thumbnail img-resposive">
							<input type='file' name="foto" id="imgInp" hidden />
						</label>
						<hr>
						<div class="form-group">
							<input type="checkbox" value="1" name="ativo" <?php if (isset($produto['ativo']) && $produto['ativo'] == 1) echo "checked"; ?> id="ativo"> Produto Ativo
						</div>
						<hr>
						<div class="form-group">
							<label for="">Ficha Técnica</label>
							<input type='file' id="ficha" name="ficha" value="" class="form-control" />
						</div>
						<hr>
						<div class="form-group">
							<label for="">Vendedor</label>
							<select name="id_vendedor" id="" class="form-control select2">
								<option value="">Selecione ...</option>
								<?php if (isset($vendedores)){ ?>
                                    <?php foreach ($vendedores as $distribuidor){ ?>
                                        <option value="<?php echo $distribuidor['id']?>"><?php echo $distribuidor['nome_fantasia']?></option>
                                    <?php } ?>
                                <?php } ?>
							</select>
						</div>
					</div>
					<div class="col-12 col-lg-8">
						<div class="row">
							<div class="form-group col-12">
								<label for="">Descrição</label>
								<input type="text" name="descricao" id="descricao" value="<?php if (isset($produto['descricao'])) echo $produto['descricao']; ?>" class="form-control">
							</div>

							<div class="form-group col-12">
								<label for="">Apresentação</label>
								<textarea name="apresentacao" id="apresentacao"  cols="30" rows="10" class="form-control"><?php if (isset($produto['apresentacao'])) echo $produto['apresentacao']; ?></textarea>
							</div>
							<div class="form-group col-12 col-lg-4">
								<label for="">Marca</label>
								<input type="text" name="marca" id="marca" value="<?php if (isset($produto['marca'])) echo $produto['marca']; ?>" class="form-control">
							</div>

							<div class="form-group col-12 col-lg-4">
								<label for="">Pedido Mínimo</label>
								<input type="number" min="1" name="minimo" id="minimo" <?php if (isset($produto['minimo'])) echo $produto['minimo']; ?> class="form-control">
							</div>

							<div class="form-group col-12 col-lg-4">
								<label for="">Data Cadastro</label>
								<input type="date" value="<?php echo (isset($produto['data_cadastro']))  ? $produto['data_cadastro'] : date("Y-m-d", time()); ?>" name="valor" readonly id="preco"  class="form-control">
							</div>

                            <div class="form-group col-12 col-lg-4">
                                <label for="">Início Adesão</label>
                                <input type="date" name="inicio_adesao" id="inicio_adesao" value="<?php if (isset($produto['inicio_adesao'])) echo $produto['inicio_adesao']; ?>"  class="form-control">
                            </div>

                            <div class="form-group col-12 col-lg-4">
                                <label for="">Fim Adesão</label>
                                <input type="date" name="fim_adesao" id="fim_adesao" value="<?php if (isset($produto['fim_adesao'])) echo $produto['fim_adesao']; ?>"  class="form-control">
                            </div>

                            <div class="form-group col-12 col-lg-4">
                                <label for="">Previsão Entrega</label>
                                <input type="date" name="previsao_entrega" id="previsao_entrega" value="<?php if (isset($produto['fim_entrega'])) echo $produto['fim_entrega']; ?>"  class="form-control">
                            </div>

						</div>

					</div>
				</div>

			</div>
		</div>
	</form>
    </div>
</div>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>


<?php if (isset($scripts)) echo $scripts ?>
<script>
	$(function () {
		$("#imgInp").change(function() {
			readURL(this);
		});

	})

	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function(e) {
				$('#blah').attr('src', e.target.result);
			}

			reader.readAsDataURL(input.files[0]); // convert to base64 string
		}
	}

</script>

<?php if (isset($footer)) echo $footer ?>
