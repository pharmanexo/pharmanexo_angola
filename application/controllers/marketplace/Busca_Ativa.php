<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Busca_Ativa extends MY_Controller {

    private $views;
    private $route;

    public function __construct()
    {
        parent::__construct();
        $this->views = "marketplace/";
        $this->route = "marketplace/busca_ativa/";
    }

	public function index()
	{
        $page_title = "Busca Ativa";

        // TEMPLATE
        $data['header'] = $this->tmp->header([
            'title' => 'Busca Ativa',
            'styles' => [
            ]
        ]);
        $data['navbar'] = $this->tmp->navbar();
        $data['scripts'] = $this->tmp->scripts([
            'scripts' => [
            ]
        ]);

        $this->load->view("{$this->views}v_busca_ativa", $data);
	}

	public function localizar_cursos($pagina,$qnt_result_pg)
	{
		//calcular o inicio visualização
		$inicio = ($pagina * $qnt_result_pg) - $qnt_result_pg;
		//Limitar os link antes depois
		$max_links = 5;
		$area = $this->input->post('area');
		$curso = $this->input->post('curso');
		if (empty($area))	$area = 0;
		if (!empty($curso)) $curso = "'" . $curso . "'";		
		$this->load->model("m_curso");
		//consultar no banco de dados
		$resultado_cursos = $this->m_curso->retorna_cursos($inicio, $qnt_result_pg);
		//Verificar se encontrou resultado na tabela "usuarios"
		if (($resultado_cursos) and ($resultado_cursos->num_rows != 0)) {
			//Paginação - Somar a quantidade de usuários
			$total_registros = $this->m_curso->CountAll()->row()->numrows;
			//Quantidade de pagina
			$quantidade_pg = ceil($total_registros / $qnt_result_pg);			
			$detalhe= '<table class="table table-striped " id="tabela">
						<thead class="table-ligth">
							<th>#</th>
							<th>Sigla</th>
							<th>Área</th>
							<th>Modalidade</th>
							<th>Curso</th>
						</thead>
						<tbody>';
			foreach ($resultado_cursos->result() as $linha) {
				$link = '<a  href="#" onClick="editar_curso(' . $linha->id . ');" style="cursor:pointer; text-decoration: none;">';
				$detalhe.='<tr>
							<td>' . $link . $linha->id.'</a></td>
							<td>' . $link . $linha->sigla. '</a></td>
							<td>' . $link . $linha->area. '</a></td>							
							<td>' . $link . $linha->modalidade. '</a></td>
							<td>' . $link . $linha->curso. '</a></td>
						</tr>';
			}

			$detalhe .= '</tbody></table><nav aria-label="paginacao"><ul class="pagination pagination-sm justify-content-end">
						<span class="page-link"><a href="#" style="text-decoration: none;" onclick="listar_curso(1,'. $qnt_result_pg . ', ' . $area . ', ' . $curso . ')">Primeira</a> </span></li>';
			for ($pag_ant = $pagina - $max_links; $pag_ant <= $pagina - 1; $pag_ant++) {
				if ($pag_ant >= 1) {
					$detalhe .= '<li class="page-item"><a class="page-link" href="#" style="text-decoration: none;" onclick="listar_curso('.$pag_ant.', '.$qnt_result_pg. ', ' . $area . ', ' . $curso . ')">'.$pag_ant.'</a></li>';
				}
			}
			$detalhe .= '<li class="page-item active"><span class="page-link">'.$pagina.'</span></li>';

			for ($pag_dep = $pagina + 1; $pag_dep <= $pagina + $max_links; $pag_dep++) {
				if ($pag_dep <= $quantidade_pg) {
					$detalhe .= '<li class="page-item"><a class="page-link" href="#" style="text-decoration: none;" onclick="listar_curso('.$pag_dep.','. $qnt_result_pg . ', ' . $area . ', ' . $curso  .')">'.$pag_dep.'</a></li>';
				}
			}
			$detalhe .= '<li class="page-item"><span class="page-link"><a href="#" style="text-decoration: none;" onclick="listar_curso('.$quantidade_pg.','. $qnt_result_pg . ', ' . $area . ', ' . $curso  . ')"> Última</a></span></li></ul></nav>';
			echo $detalhe;
			} else {
				$detalhe = '<div class="alert alert-danger" role="alert">Nenhum Curso Encontrado!</div>';
				echo $detalhe;
			}
			
	}
}
