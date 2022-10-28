<?php if (isset($header)) echo $header ?>
<body>
<?php if (isset($navbar)) echo $navbar; ?>
<div class="container">
	<?php if (isset($heading)) echo $heading; ?>

	<div class="card mt-4">
		<div class="card-body">
			<div class="row">
				<div class="col-12 col-lg-12 form">
					<p>Para concluirmos a captação dos seus dados e gerar o contrato é necessário que complete seu cadastro com as informações abaixo.</p>
					<br>
					<form name="fmrCad" id="frmCadCompleto" action="<?php echo $form_action; ?>" method="post" enctype="multipart/form-data">
						<input type="hidden" name="id" value="<?php if(isset($dados['id'])) echo $dados['id']; ?>">
						<p class="text-muted"><strong>Dados do Responsável</strong></p>
						<hr>
						<div class="row">
							<div class="col-12 col-lg-4 form-group">
								<label for="">CPF</label>
								<input type="text" name="cpf" id="cpf" data-inputmask="cpf" readonly value="<?php if(isset($dados['cpf'])) echo $dados['cpf']; ?>" class="form-control">
							</div>
							<div class="col-12 col-lg-8 form-group">
								<label for="">Nome Completo</label>
								<input type="text" name="nome" id="nome" value="<?php if(isset($dados['nome'])) echo $dados['nome']; ?>" class="form-control">
							</div>
						</div>
						<div class="row">
							<div class="col-12 col-lg-4 form-group">
								<label for="">RG</label>
								<input type="text" name="rg" id="rg" class="form-control">
							</div>
							<div class="col-12 col-lg-4 form-group">
								<label for="">Cargo</label>
								<input type="text" name="cargo" id="cargo" class="form-control">
							</div>
							<div class="col-12 col-lg-4 form-group">
								<label for="">Você é procurador legal?</label><br>
								<input type="radio" name="procurador" value="1"> SIM | <input type="radio" name="procurador" value="1"> NÃO
							</div>
						</div>

						<div class="row">
							<div class="col-12 col-lg-4 form-group">
								<label for="">TELEFONE</label>
								<input type="text" name="telefone" data-inputmask="tel" id="telefone" value="<?php if(isset($dados['telefone'])) echo $dados['telefone']; ?>" class="form-control text-center">
							</div>
							<div class="col-12 col-lg-4 form-group">
								<label for="">CELULAR</label>
								<input type="text" name="celular" data-inputmask="cel" id="celular" value="<?php if(isset($dados['celular'])) echo $dados['celular']; ?>" class="form-control text-center">
							</div>
							<div class="col-12 col-lg-4 form-group">
								<label for="">E-mail</label>
								<input type="text" name="email" id="email" class="form-control">
							</div>
						</div>
						<div class="row">
							<div class="col-3">
								<div class="form-group">
									<label for="cep">CEP</label>
									<div class="input-group mb-3">
										<input type="text" class="form-control" name="end[pessoal][cep]" id="cep" data-inputmask="cep" value="">
										<div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <a id="filtro-cep" data-toggle="tooltip" title="" data-original-title="Buscar">
                                                                <i class="fas fa-search"></i>
                                                            </a>
                                           </span>
										</div>
									</div>
								</div>
							</div>

							<div class="col-7">
								<div class="form-group">
									<label for="rua">Endereço</label>
									<input type="text" class="form-control" name="end[pessoal][logradouro]" id="logradouro" value="">
								</div>
							</div>

							<div class="col-2">
								<div class="form-group">
									<label for="numero">Número</label>
									<input type="number" class="form-control" name="end[pessoal][numero]" id="numero" value="">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-5">
								<div class="form-group">
									<label for="bairro">Bairro</label>
									<input type="text" class="form-control" name="end[pessoal][bairro]" id="bairro" value="">
								</div>
							</div>

							<div class="col-5">
								<div class="form-group">
									<label for="municipio">Cidade</label>
									<input type="text" class="form-control" name="end[pessoal][localidade]" id="localidade" value="">
								</div>
							</div>

							<div class="col-2">
								<div class="form-group">
									<label for="estado">Estado</label>
									<input type="text" class="form-control" name="end[pessoal][estado]" id="estado" value="">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-12">
								<div class="form-group">
									<label for="complemento">Complemento</label>
									<input type="text" class="form-control" name="end[pessoal][complemento]" id="complemento" value="">
								</div>
							</div>
						</div>
						<p class="text-muted"><strong>Dados da Empresa</strong></p>
						<hr>
						<div class="row">
							<div class="col-12 col-lg-4 form-group">
								<label for="">CNPJ</label>
								<input type="text" name="cnpj" id="cnpj" data-inputmask="cnpj" value="<?php if(isset($dados['cnpj'])) echo $dados['cnpj']; ?>" class="form-control">
							</div>
							<div class="col-12 col-lg-8 form-group">
								<label for="">Razão Social</label>
								<input type="text" name="empresa" id="empresa" value="<?php if(isset($dados['empresa'])) echo $dados['empresa']; ?>" class="form-control">
							</div>
						</div>
						<div class="row">
							<div class="col-12 col-lg-4 form-group">
								<label for="">Alvará Sanitário</label>
								<input type="text" name="alvara_sanitario" id="alvara_sanitario" class="form-control">
							</div>
							<div class="col-12 col-lg-4 form-group">
								<label for="">Nome RT </label>
								<input type="text" name="nome_rt" id="nome_rt" class="form-control">
							</div>
							<div class="col-12 col-lg-4 form-group">
								<label for="">Número RT</label>
								<input type="text" name="numero_rt" id="numero_rt" class="form-control">
							</div>
						</div>
						<div class="row">
							<div class="col-12 col-lg-6">
								<div class="form-group">
									<label for="">Anexar Alvará</label>
									<input type="file" name="doc_alvara" required class="form-control">
								</div>
							</div>
							<div class="col-12 col-lg-6">
								<label for="">Anexar Cartão CNPJ</label>
								<input type="file" name="doc_cnpj" required class="form-control">
							</div>
						</div>
						<div class="row">
							<div class="col-3">
								<div class="form-group">
									<label for="cep">CEP</label>
									<div class="input-group mb-3">
										<input type="text" class="form-control" name="end[comercial][cep]" id="com_cep" data-inputmask="cep" value="<?php if(isset($dados['endereco_empresa']['cep'])) echo $dados['endereco_empresa']['cep']; ?>">
										<div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <a id="filtro-cep" data-toggle="tooltip" title="" data-original-title="Buscar">
                                                                <i class="fas fa-search"></i>
                                                            </a>
                                           </span>
										</div>
									</div>
								</div>
							</div>

							<div class="col-7">
								<div class="form-group">
									<label for="rua">Endereço</label>
									<input type="text" class="form-control" name="end[comercial][logradouro]" id="com_logradouro" value="<?php if(isset($dados['endereco_empresa']['logradouro'])) echo $dados['endereco_empresa']['logradouro']; ?>">
								</div>
							</div>

							<div class="col-2">
								<div class="form-group">
									<label for="numero">Número</label>
									<input type="number" class="form-control" name="end[comercial][numero]" id="com_numero" value="<?php if(isset($dados['endereco_empresa']['numero'])) echo $dados['endereco_empresa']['numero']; ?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-5">
								<div class="form-group">
									<label for="bairro">Bairro</label>
									<input type="text" class="form-control" name="end[comercial][bairro]" id="com_bairro" value="<?php if(isset($dados['endereco_empresa']['bairro'])) echo $dados['endereco_empresa']['bairro']; ?>">
								</div>
							</div>

							<div class="col-5">
								<div class="form-group">
									<label for="municipio">Cidade</label>
									<input type="text" class="form-control" name="end[comercial][localidade]" id="com_localidade" value="<?php if(isset($dados['endereco_empresa']['localidade'])) echo $dados['endereco_empresa']['localidade']; ?>">
								</div>
							</div>

							<div class="col-2">
								<div class="form-group">
									<label for="estado">Estado</label>
									<input type="text" class="form-control" name="end[comercial][estado]" id="com_estado" value="<?php if(isset($dados['endereco_empresa']['estado'])) echo $dados['endereco_empresa']['estado']; ?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-12">
								<div class="form-group">
									<label for="complemento">Complemento</label>
									<input type="text" class="form-control" name="end[comercial][complemento]" id="com_complemento" value="<?php if(isset($dados['endereco_empresa']['complemento'])) echo $dados['endereco_empresa']['complemento']; ?>">
								</div>
							</div>
						</div>
						<hr>

						<div class="form-group text-right">
							<input type="submit" value="Enviar Dados" class="btn btn-secondary">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

</div>
<?php if (isset($scripts)) echo $scripts ?>
<script>
	$(function () {
		$('input', '#frmCadCompleto').each(function () {
			$(this).attr('required', true)
		})
	})
</script>


<?php if (isset($footer)) echo $footer ?>
