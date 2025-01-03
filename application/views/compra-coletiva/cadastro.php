<?php if (isset($header)) echo $header ?>
<body>

<?php if (isset($navbar)) echo $navbar; ?>

<div class="container">

	<div class="row login">
		<div class="col-12 col-lg-8 form">
			<p>Preencha o formulário abaixo para darmos início ao seu cadastro</p>
			<p>Os dados serão analisados por nossos consultores e você receberá um SMS de confirmação do cadastro.</p>
			<br>
			<form name="fmrCad" action="<?php echo $form_action; ?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="end[cep]" id="cep">
				<input type="hidden" name="end[logradouro]" id="logradouro">
				<input type="hidden" name="end[numero]" id="numero">
				<input type="hidden" name="end[bairro]" id="bairro">
				<input type="hidden" name="end[localidade]" id="localidade">
				<input type="hidden" name="end[estado]" id="estado">
				<input type="hidden" name="end[complemento]" id="complemento">

				<div class="row">
					<div class="col-12 col-lg-6">
						<div class="form-group">
							<label for="">CPF</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<div class="input-group-text"><i class="fas fa-id-card-alt"></i></div>
								</div>
								<input type="text" name="cpf" id="cpf" data-inputmask="cpf" required class="form-control">
							</div>
						</div>
					</div>

					<div class="col-12 col-lg-6">
						<div class="form-group">
							<label for="">DATA NASCIMENTO</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<div class="input-group-text"><i class="fas fa-calendar"></i></div>
								</div>
								<input type="text" name="data_nascimento" data-inputmask="date" id="data_nascimento" required class="form-control">
							</div>
						</div>
					</div>

					<div class="col-12 col-lg-12">
						<div class="form-group">
							<label for="">NOME</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<div class="input-group-text"><i class="fas fa-user"></i></div>
								</div>
								<input type="text" name="nome" required class="form-control">
							</div>
						</div>
					</div>

					<div class="col-12 col-lg-4">
						<div class="form-group">
							<label for="">CNPJ</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<div class="input-group-text"><i class="fas fa-id-card-alt"></i></div>
								</div>
								<input type="text" name="cnpj" data-url="<?php echo $urlVerificaCNPJ; ?>" id="cnpj" required data-inputmask="cnpj" class="form-control">
							</div>
						</div>
					</div>
					<div class="col-12 col-lg-8">
						<div class="form-group">
							<label for="">EMPRESA</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<div class="input-group-text"><i class="fas fa-building"></i></div>
								</div>
								<input type="text" name="empresa" id="empresa" required class="form-control">
							</div>
						</div>
					</div>

					<div class="col-12 col-lg-6">
						<div class="form-group">
							<label for="">TELEFONE</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<div class="input-group-text"><i class="fas fa-phone"></i></div>
								</div>
								<input type="text" name="telefone" id="telefone" data-inputmask="tel" class="form-control">
							</div>
						</div>
					</div>
					<div class="col-12 col-lg-6">
						<div class="form-group">
							<label for="">CELULAR</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<div class="input-group-text"><i class="fas fa-mobile-alt"></i></div>
								</div>
								<input type="text" name="celular" id="celular" required data-inputmask="cel" class="form-control">
							</div>
						</div>
					</div>

				</div>

				<div class="form-group">
					<input type="submit" id="btnEnviar" value="Cadastrar" class="btn btn-secondary btn-block">
				</div>
			</form>
			<a href="/adesao">Fazer Login</a>
		</div>
	</div>
</div>
<?php if (isset($scripts)) echo $scripts ?>

<script>
	$(function () {
		$('#cnpj').blur(function () {
			$('#btnEnviar').attr('disabled', true);
			if ($(this).val() !== 'undefined' && $(this).val() !== ''){
				$('#empresa').val('Buscando informações e validando junto Receita Federal... aguarde!');
				$.post($(this).data('url'), {cnpj: $(this).val()}, function (xhr) {
					if (xhr.type == 'error') {
						formWarning(xhr);
						$('#empresa').val('');
					} else {
						var cnpj = $('#cnpj').val();
						cnpj = cnpj.replace(/[^\d]+/g, '');

						$.ajax({
							url: "https://pharmanexo.com.br/adesao/cadastro/consultaCNPJ/" + cnpj,
							method: 'GET',
							complete: function (xhr) {
								// Aqui recuperamos o JSON retornado
								var response = xhr.responseJSON;

								$('#empresa').val(response.nome);
								$('#cep').val(response.cep);
								$('#logradouro').val(response.logradouro);
								$('#numero').val(response.numero);
								$('#bairro').val(response.bairro);
								$('#localidade').val(response.municipio);
								$('#estado').val(response.uf);
								$('#complemento').val(response.complemento);
								$('#telefone').val(response.telefone);

								$('#btnEnviar').attr('disabled', false);

								var emp = $('#empresa').val();
								if (emp.indexOf('Buscando') != -1){
									$('#cnpj').val('').focus();
									$('#empresa').val('');
								}

							}
						});
					}
				}, 'JSON');
			}else{
				swal.fire('Informe o CNPJ');
			}

		});

		/*$('#data_nascimento').blur(function (e) {

			$('#nome').val('Buscando informações na Receita Federal... aguarde!')

			var token = "099A37B9-AFF1-49B8-BAFB-2938E3655C6F";
			var cpf = $('#cpf').val();
			var data_nascimento = $('#data_nascimento').val();
			var plugin = "CPF";

			$.ajax({
			url: "https://sintegraws.com.br/api/v1/execute-api.php?token="+token+"&cpf="+cpf+"&data-nascimento="+data_nascimento+"&plugin="+ plugin,
				method:'GET',
				complete: function(xhr){

				// Aqui recuperamos o JSON retornado
				response = xhr.responseJSON;

				if(response.status == 'OK' && response.code == '0') {
					$('#nome').val(response.nome)
				} else {
					alert(response.message);
					$('#cpf').val('');
					$('#nome').val('')

				}
			}
		});*/

	})

	function somenteNumeros(num) {
		var er = /[^0-9.]/;
		er.lastIndex = 0;
		var campo = num;
		if (er.test(campo.value)) {
			campo.value = "";
		}
	}
</script>

<?php if (isset($footer)) echo $footer ?>
