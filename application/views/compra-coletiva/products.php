<?php if (isset($header)) echo $header ?>
<body>

<?php if (isset($navbar)) echo $navbar; ?>
<div class="container">
	<?php if (isset($heading)) echo $heading; ?>
	<P>Selecione um dos produtos para gerar o contrato de aquisição.</P>


	<?php if (isset($produtos)) { ?>
	<div class="row mb-5">
		<?php foreach ($produtos as $produto) { ?>
			<div class="col-12 col-lg-6 mb-3 mb-lg-0 mt-3">
				<div class="card">
					<div class="card-header">
						<p class="text-center card-title"><?php echo strtoupper($produto['descricao'] );?></p>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-12">
								<table class="table table-sm">
									<tr>
										<td><a href="<?php echo base_url("contratos/Contrato{$produto['id']}/contrato.pdf"); ?>" class="" target="_blank">Minuta de Contrato</a></td>
										<td><a href="<?php echo base_url("contratos/Contrato{$produto['id']}/anexo_1.pdf"); ?>" class="" target="_blank">Anexo I <br> (Ficha Técnica)</a></td>
									</tr>
									<tr>
										<td><a href="<?php echo base_url("contratos/Contrato{$produto['id']}/anexo_2.pdf"); ?>" class="" target="_blank">Anexo II <br> (Valor e Formas de Pagamento)</a></td>
										<td><a href="<?php echo base_url("contratos/Contrato{$produto['id']}/anexo_3.pdf"); ?>" class="" target="_blank">Anexo III <br> (Prazo Entrega)</a></td>
									</tr>
								</table>
							</div>
							<div class="col-6">
								<p class="h5 text-muted">R$ <?php echo number_format($produto['valor'], '2', ',', '.')?> (un)</p>
							</div>
							<div class="col-6">
								<a href="<?php echo base_url('contrato/gerar/') . $produto['id']; ?>" class="btn btn-outline-primary btn-block" data-product="1" data-toggle="tooltip" data-title="<?php echo $produto['descricao']?>>">Assinar</a>
							</div>
						</div>
					</div>
				</div>

			</div>
		<?php } ?>
	</div>
	<?php } ?>

	<div class="row mb-5" hidden>
		<div class="col-12 col-lg-4 mb-3 mb-lg-0">
			<div class="card">
				<div class="card-header">
					<p class="text-center card-title">Avental descartável 40g com Anvisa</p>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-12">
							<a href="<?php echo base_url('contratos/Contrato1/contrato1.pdf'); ?>" class="btn btn-link" target="_blank">Contrato</a>
							<a href="<?php echo base_url('anexos/Contrato1/anexo_1.pdf'); ?>" class="btn btn-link" target="_blank">Anexo I</a>
							<a href="<?php echo base_url('anexos/Contrato1/anexo_2.pdf'); ?>" class="btn btn-link" target="_blank">Anexo II</a>
							<a href="<?php echo base_url('anexos/Contrato1/anexo_3.pdf'); ?>" class="btn btn-link" target="_blank">Anexo III</a>
						</div>
						<div class="col-12">
							<a href="<?php echo base_url('contrato/gerar/1'); ?>" class="btn btn-outline-primary btn-block" data-product="1" data-toggle="tooltip" data-title="Avental descartável 40g com Anvisa">Assinar</a>
						</div>
					</div>
				</div>
			</div>

		</div>
		<div class="col-12 col-lg-4 mb-3 mb-lg-0">
			<div class="card">
				<div class="card-header">
					<p class="text-center card-title">Avental descartável 40g RDC 348 Anvisa</p>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-12">
							<a href="<?php echo base_url('contratos/Contrato2/contrato2.pdf'); ?>" class="btn btn-link" target="_blank">Contrato</a>
							<a href="<?php echo base_url('anexos/Contrato2/anexo_1.pdf'); ?>" class="btn btn-link" target="_blank">Anexo I</a>
							<a href="<?php echo base_url('anexos/Contrato2/anexo_2.pdf'); ?>" class="btn btn-link" target="_blank">Anexo II</a>
							<a href="<?php echo base_url('anexos/Contrato2/anexo_3.pdf'); ?>" class="btn btn-link" target="_blank">Anexo III</a>
						</div>
						<div class="col-12">
							<a href="<?php echo base_url('contrato/gerar/2'); ?>" class="btn btn-outline-primary btn-block" data-product="1" data-toggle="tooltip" data-title="Avental descartável 40g RDC 348 Anvisa">Assinar</a>

						</div>
					</div>
				</div>
			</div>

		</div>
		<div class="col-12 col-lg-4 mb-3 mb-lg-0">
			<div class="card">
				<div class="card-header">
					<p class="text-center card-title">Avental impermeável 50g com Anvisa</p>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-12">
							<a href="<?php echo base_url('contratos/Contrato3/contrato3.pdf'); ?>" class="btn btn-link" target="_blank">Contrato</a>
							<a href="<?php echo base_url('anexos/Contrato3/anexo_1.pdf'); ?>" class="btn btn-link" target="_blank">Anexo I</a>
							<a href="<?php echo base_url('anexos/Contrato3/anexo_2.pdf'); ?>" class="btn btn-link" target="_blank">Anexo II</a>
							<a href="<?php echo base_url('anexos/Contrato3/anexo_3.pdf'); ?>" class="btn btn-link" target="_blank">Anexo III</a>
						</div>
						<div class="col-12">
							<a href="<?php echo base_url('contratos/gerar/3'); ?>" class="btn btn-outline-primary btn-block" data-product="1" data-toggle="tooltip" data-title="Avental  impermeável 50g com Anvisa">Assinar</a>

						</div>
					</div>
				</div>
			</div>

		</div>
	</div>

</div>
<?php if (isset($scripts)) echo $scripts ?>

<script>
	$(function () {
		$("[data-product]").click(function (e) {
			e.preventDefault();
			var me = $(this);

			Swal.fire({
				text: 'Informe a quantidade que deseja adquirir',
				input: 'text',
				inputAttributes: {
					autocapitalize: 'off'
				},
				showCancelButton: true,
				confirmButtonText: 'Gerar',
				showLoaderOnConfirm: true,
			}).then((result) => {
				if (result.value) {

					if (result.value.includes(',')) {
						Swal.fire({
							text: 'Não utilize vírgula'
						});
					} else {
						let url = me.attr('href') + '/' + result.value;
						window.location.replace(url);
					}


				} else {
					Swal.fire({
						text: 'Informe um valor.'
					});
				}
			})


		})
	})
</script>

<?php if (isset($footer)) echo $footer ?>
