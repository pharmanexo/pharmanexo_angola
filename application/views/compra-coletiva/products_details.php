<?php if (isset($header)) echo $header ?>
<body>

<?php if (isset($navbar)) echo $navbar; ?>
<div class="container">
	<div class="row mb-5">
		<div class="row mt-5">
			<div class="col-lg-8 col-12">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-12 col-lg-4">
								<img src="<?php echo base_url("contratos/Contrato{$produto['id']}/{$produto['imagem']}"); ?>" class="w-100 img-fluid" alt="">
								<br>

								<a href="<?php echo base_url("contratos/Contrato{$produto['id']}/contrato.pdf"); ?>" class="btn btn-sm  btn-outline-secondary btn-block" target="_blank">Minuta de Contrato</a>
								<a href="<?php echo base_url("contratos/Contrato{$produto['id']}/anexo_1.pdf"); ?>" class="btn btn-sm  btn-outline-secondary btn-block" target="_blank">Anexo I (Ficha Técnica)</a>

							</div>
							<div class="col-12 col-lg-8">
								<h5><?php echo $produto['descricao']; ?></h5>
								<p><?php echo $produto['apresentacao']; ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-12">

				<form action="<?php echo base_url('compra-coletiva/contrato/gerar/'); ?>" method="post">

					<input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
					<div class="card">
						<div class="card-body">
							<h3 class="text-muted">R$ <span id="preco"><?php echo number_format($produto['valor'], 2, ',', '.'); ?></span> <small>und.</small></h3>
							<p class="small">No preço apresentado por unidade encontra-se incluído todas as taxas e tributos
								inerentes e logística de entrega do produto</p>
							<hr>
							<p><?php echo ($produto['minimo'] == 'Consulte') ? "Preço diferenciado por quantidade: <a href='#' data-toggle='modal' data-target='#exampleModal'>Consulte a tabela</a>" : "Pedido Mínimo: " . $produto['minimo']; ?></p>
							<hr>
							<div class="form-group">
								<label for="">Quantidade</label>
								<input type="number" required min="1" data-prod="<?php echo $produto['id']; ?>" name="quantidade"<?php echo " data-preco='false' data-url='{$urlPreco}' "; ?> id="quantidade" placeholder="informe a quantidade que deseja" class="form-control">
								<p class="small msg"></p>
								<hr>
								<h5 class="text-muted">Total: R$ <span class="text-right" id="total">0,00</span></h5>
							</div>
							<div class="form-group">
								<input type="submit" id="btnSubmit" class="btn btn-primary btn-block" value="Fazer Pedido">
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php if (isset($produtos) && !empty($produtos)) { ?>
		<h5 class="text-muted">Outros Produtos</h5>
		<hr>
		<div class="row mb-5">
			<?php foreach ($produtos as $prod) { ?>
				<div class="col-3">
					<div class="card shadow card-prod mb-3" style="height: auto">

						<img class="card-img-top" hidden style="width: auto; height: 160px" src="<?php echo base_url("contratos/Contrato{$prod['id']}/{$prod['imagem']}"); ?>" alt="Card image cap">

						<div class="card-body">
							<p style="font-size: 16px; height: 50px" class="card-title"><?php echo strtoupper($prod['descricao']); ?></p>
							<p class="text-right" style="font-size: 16px">R$ <?php echo number_format($prod['valor'], 2, ',', '.'); ?> <small>und.</small></p>
							<a href="<?php echo $urlDetalhes . $prod['id']; ?>" class="btn btn-block btn-outline-secondary" data-toggle="tooltip" data-title="Ver Detalhes"><i class="fa fa-search-plus"></i></a>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	<?php } ?>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Tabela de Preços</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-striped">
					<tr>
						<th>Quantidade (und)</th>
						<th>Valor</th>
					</tr>
					<tr>
						<td> >10000</td>
						<td><?php echo number_format($produto['preco_10000'], 2, ',', '.'); ?></td>

					</tr>
					<tr>
						<td> >5000</td>
						<td><?php echo number_format($produto['preco_5000'], 2, ',', '.'); ?></td>

					</tr>
					<tr>
						<td> >2000</td>
						<td><?php echo number_format($produto['preco_2000'], 2, ',', '.'); ?></td>

					</tr>
					<tr>
						<td> >1000</td>
						<td><?php echo number_format($produto['preco_1000'], 2, ',', '.'); ?></td>

					</tr>
					<tr>
						<td> >500</td>
						<td><?php echo number_format($produto['preco_500'], 2, ',', '.'); ?></td>

					</tr>
					<tr>
						<td> até 500</td>
						<td><?php echo number_format($produto['valor'], 2, ',', '.'); ?></td>

					</tr>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
			</div>
		</div>
	</div>
</div>


<?php if (isset($scripts)) echo $scripts ?>

<script>
	$(function () {

		var time;
		$('[data-preco]').on('keyup', function (e) {
			e.preventDefault();
			clearTimeout(time);
			$('.msg').html('Buscando o melhor preço...');

			var me = $(this);
			var url = me.data('url');
			var data = {quantidade: me.val(), 'idProduto': me.data('prod')};

			time = setTimeout(function () {
				$.post(url, data, function (xhr) {
					$('#preco').html(xhr.precoFormatado);
					$('#total').html(xhr.total);
					$('.msg').html('');
				}, 'JSON');
			}, 2000)


		})
	})
</script>

<?php if (isset($footer)) echo $footer ?>
