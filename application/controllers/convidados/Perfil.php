<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Perfil extends Conv_controller
{
    private $route;
    private $views;

    public function __construct()
    {
      parent::__construct();

        $this->route = base_url('/convidados/perfil');
        $this->views = 'convidados/perfil/';


    }

    /**
     * Perfil do usuario logado
     *
     * @return view
     */
    public function index()
    {
        if (!isset($_SESSION['dados'])){
            redirect(base_url('/login'));
        }

        $page_title = "Comprador";
        $data['rep'] = $_SESSION['dados'];
        $data['form_action'] = "{$this->route}/save";
        $data['header'] = $this->tmp_conv->header([ 'title' => $page_title ]);
        $data['navbar']  = $this->tmp_conv->navbar();
        $data['sidebar'] = $this->tmp_conv->sidebar();
        $data['heading'] = $this->tmp_conv->heading([
            'page_title' => $page_title,
            'buttons'    => [
                [
                    'type'  => 'submit',
                    'id'    => 'btnSave',
                    'form'  => 'formRep',
                    'class' => 'btn-primary',
                    'icone' => 'fa-save',
                    'label' => 'Salvar Alterações'
                ]
            ]
        ]);
        $data['scripts'] = $this->tmp_conv->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/jquery.form.min.js',
                THIRD_PARTY . 'plugins/jquery-validation-1.19.1/dist/jquery.validate.min.js'
            ]
        ]);

        $this->load->view("{$this->views}main", $data);
    }

    /**
     * Função que atualiza o perfil
     *
     * @return json - ajax
     */
    public function save()
    {
        $this->load->library('upload');

        $post = $this->input->post();

        $id = $post['id'];


        // Verifica se o usuario  não informou uma nova senha
        if (!isset($post['senha']) || empty($post['senha'])) {

            unset($post['senha']);
        } else {

            $post['senha'] = password_hash($post['senha'], PASSWORD_DEFAULT);
        }

        // Remove estes campos do array para não atualizar
        unset($post['nome']);
        unset($post['email']);
        unset($post['c_senha']);
        unset($post['id']);

        // Se o usuario alterar a foto
        if ( isset($_FILES['foto']['name']) && !empty($_FILES['foto']['name']) ) {

            $config['upload_path'] = PUBLIC_PATH . "representantes/{$id}/";
            $config['allowed_types'] = 'png|gif|jpg|jpeg';
            $config['encrypt_name'] = TRUE;

            $this->load->helper('file');
            delete_files($config['upload_path'], true);

            if (!file_exists($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }


            $this->upload->initialize($config);
            
            if (!$this->upload->do_upload('foto')) {
                $error = $this->upload->display_errors('<p>', '</p>');

                $warning = [ "type" => 'warning', "message" => $error ];

                $this->session->set_userdata('warning', $warning);

                redirect($this->route);
            } else {
                $data = $this->upload->data();

                $post['foto'] = $data['file_name'];

            }
        } 

        $this->db->where('id', $id);

        if ($this->db->update('representantes', $post)) {

            // Se cadastrou logo, atualiza a session para exibir imagem no perfil
            if (isset($post['foto'])) 
               $this->session->set_userdata('foto', $post['foto']);

            $warning = [ 'type' => 'success', 'message' => notify_update ];
        } else {

            var_dump($this->db->error());
            exit();
            $warning = [ 'type' => 'warning', 'message' => notify_failed ];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }
}

/* End of file: Perfil.php */