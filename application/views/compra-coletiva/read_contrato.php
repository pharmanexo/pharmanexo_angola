<?php if (isset($header)) echo $header ?>
<body>
<?php if (isset($navbar)) echo $navbar; ?>

<div class="container">
	<?php if (isset($heading)) echo $heading; ?>
	<div class="card mt-3">
		<div class="card-body">
			<p>Este é o Contrato de Adesão, após ler desça ao fim da página para aceitar os termos e prosseguir.</p>
			<br>
			<object data="<?php if (isset($file)) echo $file?>" type="application/pdf" width="100%" height="600">
				<p>Alternative text - include a link <a href="<?php if (isset($file)) echo $file?>">to the PDF!</a></p>
			</object>
			<br>
			<br>
			<p>Assim, tendo em vista as cláusulas do presente Termo, o COMPRADOR AUTORIZADO declara que concorda integralmente com todas as
				disposições nele contidas, se comprometendo a respeitar as condições aqui previstas de forma irretratável e irrevogável,
				bem como a utilizar de modo proveitoso e legal os serviços a ele oferecidos.</p>

			<div class="row">
				<div class="col-12 col-lg-6">
					<a href="<?php echo $urlAnexos; ?>anexo_1.pdf" target="_blank">Anexo I</a>
					<a href="<?php echo $urlAnexos; ?>anexo_2.pdf" target="_blank">Anexo II</a>
					<a href="<?php echo $urlAnexos; ?>anexo_3.pdf" target="_blank">Anexo III</a>
				</div>
				<div class="col-12 col-lg-6">
					<p class="text-right"><a href="<?php echo $urlAceite; ?>" class="btn btn-primary">ACEITAR CONTRATO</a></p>
				</div>
			</div>
		</div>
	</div>

</div>
<?php if (isset($scripts)) echo $scripts ?>



<?php if (isset($footer)) echo $footer ?>
