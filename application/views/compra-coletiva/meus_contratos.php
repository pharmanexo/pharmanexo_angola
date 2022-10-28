<?php if (isset($header)) echo $header ?>
<body>

<?php if (isset($navbar)) echo $navbar; ?>
<div class="container">
	<?php if (isset($heading)) echo $heading; ?>
	<div class="card mt-3">
		<div class="card-body">
			<P>Contratos Gerados</P>
			<br>
			<table class="table table-condensed table-striped w-100">
				<thead>
				<tr>
					<th>Data</th>
					<th>Produto</th>
					<th>Situação</th>
					<th>Visualizar</th>
				</tr>
				</thead>
				<tbody>
				<?php if (isset($contratos) && !empty($contratos)){ ?>
				<?php foreach ($contratos as $contrato){ ?>
				<tr>
					<td><?php echo date('d/m/Y', strtotime($contrato['data_criacao']))?></td>
					<td><?php echo $contrato['produto'];?></td>
					<td><?php echo (empty($contrato['data_aprovacao'])) ? "Aguardando Aceite <a class='btn btn-outline-primary btn-sm float-right' href='https://pharmanexo.com.br/adesao/contrato/gerar/{$contrato['tipo_contrato']}/{$contrato['quantidade']}'> Assinar</a>" : 'Assinado';?></td>
					<td><a href="<?php echo $contrato['url'];?>" target="_blank" class="btn btn-link"><i class="fas fa-file-pdf"></i> Abrir</a></td>
				</tr>
				<?php } ?>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>

</div>
<?php if (isset($scripts)) echo $scripts ?>



<?php if (isset($footer)) echo $footer ?>
