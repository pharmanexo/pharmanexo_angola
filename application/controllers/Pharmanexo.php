<?php

class Pharmanexo extends MY_Controller
{

    public function index()
    {
        switch ($this->session->userdata("tipo_usuario")) {
            case 1:
                $this->load->model("m_estoque");
                $data['valorvariacaoValidades'] = $this->m_estoque->valorVariacaoValidades();//
                $data['variacaoValidades'] = $this->m_estoque->variacaoValidades();//
                $data['nome_view'] = 'v_pharmanexo';
                $data = [
                    'navbar' => $this->template->navbar(),
                    'sidebar' => $this->template->sidebar([], 'sidebar_painel')
                ];
                $view = "admin/dashboard";
                break;
            case 2:
                $data = [
                    'header' => $this->tmp->header(),
                    'scripts' => $this->tmp->scripts(),
                    'navbar' => $this->tmp->navbar()
                ];
                $view = "marketplace/home";
                break;
            default:
                $data = [
                    'header' => $this->template->header(),
                    'scripts' => $this->template->scripts(),
                    'navbar' => $this->template->navbar(),
                    'sidebar' => $this->template->sidebar(),
                ];
                $view = "admin/dashboard";
                break;
        }

        $this->load->view($view, $data);
    }

}
