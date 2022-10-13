<?php if (isset($header)) echo $header ?>
<body style="background: url('/adesao/application/views/assets/img/bg-aventais.jpeg') no-repeat; background-size: cover">
<div class="container">
	<div class="row topo">
		<div class="col-6 text-left">
			<img src="https://www.pharmanexo.com.br/pharmanexo_v2/images/img/logo-white.png" alt="Logo Pharmanexo">
		</div>
		<div class="col-6 text-right pt-3">
			<h3 class="text-white">AQUISIÇÃO DE AVENTAIS</h3>
		</div>
	</div>
	<div class="row login">
		<div class="col-12 col-lg-8 form">
			<p>Contrato aprovado com sucesso.</p>
			<p>Chave Segurança: <?php echo $contrato['hash']; ?></p>
			<p>IP Origem: <?php echo $contrato['origin']; ?></p>
			<p>Quantidade: <?php echo $contrato['quantidade']; ?></p>
			<p>CNPJ: <?php echo $contrato['cnpj']; ?></p>
			<br>
			<hr>
			<p class="text-center">
				<a href="<?php if (isset($contrato['url'])) echo $contrato['url']; ?>" class="btn btn-sm btn-primary ">Download</a>
				<a class="btn btn-sm btn-primary" href="<?php echo base_url('contrato/meus_contratos') ?>">Meus Contratos</a>
				<a class="btn btn-sm btn-danger" href="<?php echo base_url('home/logout') ?>">Sair</a>
			</p>
		</div>
	</div>
</div>
<?php if (isset($scripts)) echo $scripts ?>

<script>
	$(function () {

	})
</script>

<?php if (isset($footer)) echo $footer ?>
