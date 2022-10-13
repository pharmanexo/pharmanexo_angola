
<?php if (isset($_SESSION['admin']) || isset( $_SESSION['permited']) && $_SESSION['permited'] == true ){ ?>

	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<a class="navbar-brand" href="#"><img src="<?php if (isset($logo)) echo $logo; ?>" class="w-75" alt=""></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item">
					<a class="nav-link" href="<?php echo base_url("admin/dashboard")?>"><i class="fas fa-chart-line"></i> Dashboard </a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo base_url("admin/compradores")?>"><i class="fas fa-users"></i> Compradores</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo base_url("admin/notifications")?>"><i class="fas fa-sms"></i> Enviar Notificação</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo base_url("admin/financeiro")?>"><i class="fas fa-money-bill"></i> Financeiro</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo base_url("admin/contratos")?>"><i class="fas fa-file-pdf"></i> Contratos</a>
				</li>
			</ul>
			<div class="form-inline my-2 my-lg-0">
				<a href="<?php echo base_url("admin/login/logout")?>" class="text-secondary"><i class="fas fa-sign-out-alt"></i> Sair do Sitema</a>
			</div>
		</div>
	</nav>
<?php }else{ ?>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<a class="navbar-brand" href="#"><img src="<?php if (isset($logo)) echo $logo; ?>" class="w-75" alt=""></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item">
					<a class="nav-link" href="<?php echo base_url("produtos")?>"><i class="fas fa-boxes"></i> Produtos </a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo base_url("contrato/meus_contratos")?>"><i class="fas fa-file-pdf"></i> Meus Contratos</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo base_url("cadastro/dados")?>"><i class="fas fa-user"></i> Meus Dados</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" target="_blank" href="https://api.whatsapp.com/send?phone=5527992994049&text=Preciso%20de%20ajuda%20no%20Portal%20de%20Ades%C3%B5es%20-%20Pharmanexo"><i class="fas fa-phone-square-alt"></i> Suporte</a>
				</li>
			</ul>
			<div class="form-inline my-2 my-lg-0">
				<a href="<?php echo base_url("login/logout")?>" class="text-secondary"><i class="fas fa-sign-out-alt"></i> Sair do Sitema</a>
			</div>
		</div>
	</nav>


<?php } ?>
