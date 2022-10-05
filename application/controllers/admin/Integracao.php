<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Integracao extends Admin_controller
{
    public function index()
    {
        $dados['nome_view'] = 'v_lista_cotacoes';
        #template
        $dados['navbar'] = $this->template->navbar();
        $dados['sidebar'] = $this->template->sidebar([], 'sidebar_painel');

        $this->load->view('v_layout', $dados);
    }

    public function listaCotacoes($pagina, $qnt_result_pg)
    {
        //calcular o inicio visualização
        $inicio = ($pagina * $qnt_result_pg) - $qnt_result_pg;
        //Limitar os link antes depois
        $max_links = 5;
        $this->load->model("m_integracao");
        $lista_cotacoes = $this->m_integracao->listaCotacoes($inicio, $qnt_result_pg);
        //Verificar se encontrou resultado na tabela
        if (($lista_cotacoes) and ($lista_cotacoes->num_rows != 0)) {
            //Paginação - Somar a quantidade
            $total_registros = $this->m_integracao->CountAll()->row()->numrows;
            //Quantidade de pagina
            $quantidade_pg = ceil($total_registros / $qnt_result_pg);
            $detalhe = '<table class="table table-striped " id="tabela">
						<thead class="table-ligth">
                                                        <th class="text-center">Data Cotação</th>
                                                        <th class="text-center">Cotação</th>
                                                        <th class="text-left">Comprador</th>
                                                        <th class="text-center">UF</th>
                                                        <th class="op-0">&nbsp;</th>
						</thead>
						<tbody>';
            foreach ($lista_cotacoes->result() as $cotacao) {

                $detalhe .= '<tr>
							<td class="text-center">' . $cotacao->data_cotacao . '</td>
							<td class="text-center">' . $cotacao->id_cotacao . ' / ' . $cotacao->submetido . '</td>
							<td class="text-left">' . $cotacao->cnpj_comprador . ' ' . $cotacao->razao_social . '</td>
							<td class="text-center">' . $cotacao->uf_comprador . '</td>
                                                        <td class="op-0"><a href="' . base_url("Integracao/listaProdCotacao/$cotacao->id_cotacao/$cotacao->submetido") . '"  title="Visualizar"><i class="fas fa-eye"></i></a></td>

                                            </tr>';
            }

            $detalhe .= '</tbody></table><nav aria-label="paginacao"><ul class="pagination pagination-sm justify-content-end">
						<span class="page-link"><a href="#" style="text-decoration: none;" onclick="listaCotacoes(1,' . $qnt_result_pg . ')"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a> </span></li>';
            for ($pag_ant = $pagina - $max_links; $pag_ant <= $pagina - 1; $pag_ant++) {
                if ($pag_ant >= 1) {
                    $detalhe .= '<li class="page-item"><a class="page-link" href="#" style="text-decoration: none;" onclick="listaCotacoes(' . $pag_ant . ', ' . $qnt_result_pg . ')">' . $pag_ant . '</a></li>';
                }
            }
            $detalhe .= '<li class="page-item active"><span class="page-link">' . $pagina . '</span></li>';

            for ($pag_dep = $pagina + 1; $pag_dep <= $pagina + $max_links; $pag_dep++) {
                if ($pag_dep <= $quantidade_pg) {
                    $detalhe .= '<li class="page-item"><a class="page-link" href="#" style="text-decoration: none;" onclick="listaCotacoes(' . $pag_dep . ',' . $qnt_result_pg . ')">' . $pag_dep . '</a></li>';
                }
            }
            $detalhe .= '<li class="page-item"><span class="page-link"><a href="#" style="text-decoration: none;" onclick="listaCotacoes(' . $quantidade_pg . ',' . $qnt_result_pg . ')"><i class="fa fa-angle-double-right" aria-hidden="true"></i></a></span></li></ul></nav>';
            echo $detalhe;
        } else {
            $detalhe = '<div class="alert alert-danger" role="alert">Nenhuma Cotações Encontrada!</div>';
            echo $detalhe;
        }
    }

    public function listaProdCotacao($id_cotacao, $submetido)
    {
        $this->load->model("m_integracao");
        $dados['dados_cotacao'] = $this->m_integracao->dadosCotacao($id_cotacao, $submetido);
        $dados['totais'] = $this->m_integracao->totaisCotacao($id_cotacao, $submetido);
        $dados['totais_validos'] = $this->m_integracao->totaisCotacao_validos($id_cotacao, $submetido);
        $dados['nome_view'] = 'v_lista_produtos_cotacao';
        #template
        $dados['navbar'] = $this->template->navbar();
        $dados['sidebar'] = $this->template->sidebar([], 'sidebar_painel');

        $this->load->view('v_layout', $dados);
    }

    public function listaProdCotacaoRejeitado($id_cotacao, $submetido)
    {
        $this->load->model("m_integracao");
        $dados['dados_cotacao'] = $this->m_integracao->dadosCotacao($id_cotacao, $submetido);

        $dados['totais'] = $this->m_integracao->totaisCotacao($id_cotacao, $submetido);
        $dados['totais_rejeitados'] = $this->m_integracao->totaisCotacaoRejeitados($id_cotacao, $submetido);
        $dados['contagem_motivos_rejeitados'] = $this->m_integracao->contagemMotivosRejeicao($id_cotacao, $submetido);
        $dados['nome_view'] = 'v_lista_produtos_rejeitados_cotacao';
        #template
        $dados['navbar'] = $this->template->navbar();
        $dados['sidebar'] = $this->template->sidebar([], 'sidebar_painel');

        $this->load->view('v_layout', $dados);
    }

    public function listaProdutosCotacao($pagina, $qnt_result_pg, $id_cotacao, $submetido)
    {
        //calcular o inicio visualização
        $inicio = ($pagina * $qnt_result_pg) - $qnt_result_pg;
        //Limitar os link antes depois
        $max_links = 5;
        $this->load->model("m_integracao");
        $lista_produtos_cotacao = $this->m_integracao->listaProdutosCotacao($inicio, $qnt_result_pg, $id_cotacao, $submetido);
        //Verificar se encontrou resultado na tabela
        if (($lista_produtos_cotacao) and ($lista_produtos_cotacao->num_rows != 0)) {
            //Paginação - Somar a quantidade
            $total_registros = $this->m_integracao->ProdutosCotacaoCountAll($id_cotacao, $submetido)->row()->numrows;
            //Quantidade de pagina
            $quantidade_pg = ceil($total_registros / $qnt_result_pg);
            $detalhe = '<table class="table table-striped " id="tabela">
						<thead class="table-ligth">
                                                        <th class="text-left col-3">Produto</th>
                                                        <th class="text-left">Marca</th>
                                                        <th class="text-center col-1">Qtd</th>
                                                        <th class="text-right">Menor Oferta</th>
                                                        <th class="text-right col-1" style="background: #b9f2ff99 !important;">Marca/Marca</th>
                                                        <th class="text-right " style="background: #b9f2ff99 !important;">Outra/Marca</th>
                                                        <th class="text-left " style="background: #b9f2ff99 !important;">Outra Marca</th>
						</thead>
						<tbody>';
            foreach ($lista_produtos_cotacao->result() as $produtos_cotacao) {

                $detalhe .= '<tr>
							<td class="text-left">' . $produtos_cotacao->produto . '</td>
							<td class="text-left">' . $produtos_cotacao->marca_solicitada . '</td>
							<td class="text-center">' . $produtos_cotacao->qtd_solicitada . '</td>
							<td class="text-right">' . number_format($produtos_cotacao->preco_oferta, 4, ',', '.') . '</td>
							<td class="text-right" style="background: #b9f2ff99 !important;"> ' . number_format($produtos_cotacao->preco_marca, 4, ',', '.') . '</td>
							<td class="text-right" style="background: #b9f2ff99 !important;">' . number_format($produtos_cotacao->preco_outra_marca, 4, ',', '.') . '</td>
							<td class="text-left" style="background: #b9f2ff99 !important;">' . $produtos_cotacao->outra_marca . '</td>
                                            </tr>';
            }

            $detalhe .= '</tbody></table><nav aria-label="paginacao"><ul class="pagination pagination-sm justify-content-end">
						<span class="page-link"><a href="#" style="text-decoration: none;" onclick="listaProdutosCotacao(1,' . $qnt_result_pg . ', ' . $id_cotacao . ', ' . $submetido . ')"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a> </span></li>';
            for ($pag_ant = $pagina - $max_links; $pag_ant <= $pagina - 1; $pag_ant++) {
                if ($pag_ant >= 1) {
                    $detalhe .= '<li class="page-item"><a class="page-link" href="#" style="text-decoration: none;" onclick="listaProdutosCotacao(' . $pag_ant . ', ' . $qnt_result_pg . ', ' . $id_cotacao . ', ' . $submetido . ')">' . $pag_ant . '</a></li>';
                }
            }
            $detalhe .= '<li class="page-item active"><span class="page-link">' . $pagina . '</span></li>';

            for ($pag_dep = $pagina + 1; $pag_dep <= $pagina + $max_links; $pag_dep++) {
                if ($pag_dep <= $quantidade_pg) {
                    $detalhe .= '<li class="page-item"><a class="page-link" href="#" style="text-decoration: none;" onclick="listaProdutosCotacao(' . $pag_dep . ',' . $qnt_result_pg . ', ' . $id_cotacao . ', ' . $submetido . ')">' . $pag_dep . '</a></li>';
                }
            }
            $detalhe .= '<li class="page-item"><span class="page-link"><a href="#" style="text-decoration: none;" onclick="listaProdutosCotacao(' . $quantidade_pg . ',' . $qnt_result_pg . ', ' . $id_cotacao . ', ' . $submetido . ')"><i class="fa fa-angle-double-right" aria-hidden="true"></i></a></span></li></ul></nav>';
            echo $detalhe;
        } else {
            $detalhe = '<div class="alert alert-danger" role="alert">Nenhuma Produto Encontrado!</div>';
            echo $detalhe;
        }
    }

    public function listaProdutosCotacaoRejeitados($pagina, $qnt_result_pg, $id_cotacao, $submetido)
    {
        //calcular o inicio visualização
        $inicio = ($pagina * $qnt_result_pg) - $qnt_result_pg;
        //Limitar os link antes depois
        $max_links = 5;
        $this->load->model("m_integracao");
        $lista_produtos_cotacao = $this->m_integracao->listaProdutosCotacaoRejeitados($inicio, $qnt_result_pg, $id_cotacao, $submetido);
        //Verificar se encontrou resultado na tabela
        if (($lista_produtos_cotacao) and ($lista_produtos_cotacao->num_rows != 0)) {
            //Paginação - Somar a quantidade
            $total_registros = $this->m_integracao->ProdutosCotacaoRejeitadosCountAll($id_cotacao, $submetido)->row()->numrows;
            //Quantidade de pagina
            $quantidade_pg = ceil($total_registros / $qnt_result_pg);
            $detalhe = '<table class="table table-striped " id="tabela">
						<thead class="table-ligth">
                                                        <th class="text-left col-3">Produto</th>
                                                        <th class="text-left">Marca</th>
                                                        <th class="text-center col-1">Qtd</th>
                                                        <th class="text-right">Preço oferta</th>
                                                        <th class="text-right col-1" style="background: #ffb9b999">Preço Marca/Marca</th>
                                                        <th class="text-right" style="background: #ffb9b999">Preço Outra/Marca</th>
                                                        <th class="text-left" style="background: #ffb9b999">Outra Marca</th>
						</thead>
						<tbody>';
            foreach ($lista_produtos_cotacao->result() as $produtos_cotacao) {

                $detalhe .= '<tr>
							<td class="text-left">' . $produtos_cotacao->produto . '</td>
							<td class="text-left">' . $produtos_cotacao->marca_solicitada . '</td>
							<td class="text-center">' . $produtos_cotacao->qtd_solicitada . '</td>
							<td class="text-right">' . number_format($produtos_cotacao->preco_oferta, 4, ',', '.') . '</td>
							<td class="text-right" style="background: #ffb9b999">' . number_format($produtos_cotacao->menor_preco_rejeitado_marca_marca, 4, ',', '.') . '</td>
							<td class="text-right" style="background: #ffb9b999">' . number_format($produtos_cotacao->menor_preco_rejeitado_outra_marca, 4, ',', '.') . '</td>
							<td class="text-left" style="background: #ffb9b999">' . $produtos_cotacao->outra_marca . '</td>
                                            </tr>';
            }

            $detalhe .= '</tbody></table><nav aria-label="paginacao"><ul class="pagination pagination-sm justify-content-end">
						<span class="page-link"><a href="#" style="text-decoration: none;" onclick="listaProdutosCotacaoRejeitados(1,' . $qnt_result_pg . ', ' . $id_cotacao . ', ' . $submetido . ')"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a> </span></li>';
            for ($pag_ant = $pagina - $max_links; $pag_ant <= $pagina - 1; $pag_ant++) {
                if ($pag_ant >= 1) {
                    $detalhe .= '<li class="page-item"><a class="page-link" href="#" style="text-decoration: none;" onclick="listaProdutosCotacaoRejeitados(' . $pag_ant . ', ' . $qnt_result_pg . ', ' . $id_cotacao . ', ' . $submetido . ')">' . $pag_ant . '</a></li>';
                }
            }
            $detalhe .= '<li class="page-item active"><span class="page-link">' . $pagina . '</span></li>';

            for ($pag_dep = $pagina + 1; $pag_dep <= $pagina + $max_links; $pag_dep++) {
                if ($pag_dep <= $quantidade_pg) {
                    $detalhe .= '<li class="page-item"><a class="page-link" href="#" style="text-decoration: none;" onclick="listaProdutosCotacaoRejeitados(' . $pag_dep . ',' . $qnt_result_pg . ', ' . $id_cotacao . ', ' . $submetido . ')">' . $pag_dep . '</a></li>';
                }
            }
            $detalhe .= '<li class="page-item"><span class="page-link"><a href="#" style="text-decoration: none;" onclick="listaProdutosCotacaoRejeitados(' . $quantidade_pg . ',' . $qnt_result_pg . ', ' . $id_cotacao . ', ' . $submetido . ')"><i class="fa fa-angle-double-right" aria-hidden="true"></i></a></span></li></ul></nav>';
            echo $detalhe;
        } else {
            $detalhe = '<div class="alert alert-danger" role="alert">Nenhuma Produto Encontrado!</div>';
            echo $detalhe;
        }
    }

}
