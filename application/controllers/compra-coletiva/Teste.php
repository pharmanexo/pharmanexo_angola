<?php

class Teste extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();


	}

	public function index()
	{
		var_dump([
			gethostbyname('integracao.plataformasintese.com'),
			gethostbyname('plataformasintese.com'),
			gethostbyname('integracao.plataformasintese.com:8082'),
		]);

		var_dump(number_format('0.2356', 4, ',', '.'));
	}
}
