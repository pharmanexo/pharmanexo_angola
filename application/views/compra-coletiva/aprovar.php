<?php if (isset($header)) echo $header?>
<body>

<div class="container">
	<div class="row mt-3">
		<div class="col-12 col-lg-12 form">
			<div class="card">
				<div class="card-body">
					<p>Aprovação de Cadastro</p>
					<br>
					<p>CNPJ: <?php echo $cadastro['cnpj']; ?></p>
					<p>EMPRESA: <?php echo $cadastro['empresa']; ?></p>
					<hr>
					<form name="fmrCad" action="<?php echo $form_action; ?>" method="post" enctype="multipart/form-data">
						<input type="hidden" id="id" name="id" value="<?php echo $cadastro['id']; ?>">
						<div class="form-group">
							<label for="">Senha Gerencial</label>
							<input type="password" value="" name="senha" class="form-control">
						</div>
						<input type="submit" class="btn btn-block btn-primary" value="Aprovar Cadastro">
					</form>
				</div>
				</div>
			</div>
	</div>
</div>
<?php if (isset($scripts)) echo $scripts?>

<script>
	$(function () {

	})
</script>

<?php if (isset($footer)) echo $footer?>
