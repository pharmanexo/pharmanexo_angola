<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_configuracoes_envio extends MY_Model
{
    protected $table = 'configuracoes_envio';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = 'id_estado';
    protected $order_direction = 'ASC';

    public function __construct()
    {
        parent::__construct();
    }
	
    /**
     * Salva a observação por estado para exibir no envio da cotação manual e automatica
     *
     * @param - POST - request do form
     * @return bool
     */
	public function gravar($post)
	{

		$insert = [];

		$this->db->trans_begin();

		foreach ($post['estados'] as $id_estado) {

			# Se o usuario marcar a opção todos, registra somente um registro
			if ( in_array(0, $post['estados']) ) {

				# Verifica se o tipo da configuração ja possui registro para todos
				$this->db->where("id_fornecedor", $this->session->id_fornecedor);
				$this->db->where("tipo", $post['tipo']);
				$this->db->where("integrador", $post['integradores']);
				$existe = $this->db->get($this->table)->row_array();


				if ( isset($existe) && !empty($existe) ) {
					
					# Remove todos os registros anterior
					$this->db->where('tipo', $post['tipo'])->where("id_fornecedor", $this->session->id_fornecedor)->delete($this->table);
				}
				
				$insert[] = [
					'observacao' => $post['observacao'],
					'tipo' => $post['tipo'],
					'integrador' => $post['integrador'],
					'id_estado' => $id_estado,
					'id_fornecedor' => $this->session->id_fornecedor
				];

				break;
			}
			 else {

				$this->db->select("id");
				$this->db->where('id_estado', $id_estado);
				$this->db->where('tipo', $post['tipo']);
				$this->db->where('integrador', $post['integrador']);
				$this->db->where('id_fornecedor', $this->session->id_fornecedor);
				$config = $this->db->get($this->table)->row_array();

				if ( isset($config) && !empty($config) ) {
					
					$this->db->where("id", $config['id'])->update($this->table, [
						'observacao' => $post['observacao'],
						'tipo' => $post['tipo'],
						'integrador' => $post['integrador'],
					]);
				} else {

					$insert[] = [
						'observacao' => $post['observacao'],
						'tipo' => $post['tipo'],
						'integrador' => $post['integrador'],
						'id_estado' => $id_estado,
						'id_fornecedor' => $this->session->id_fornecedor
					];
				}
			}
		}

		if ( !empty($insert) ) {
			
			$this->db->insert_batch($this->table, $insert);
		}

		if ($this->db->trans_status() === false) {

	        $this->db->trans_rollback();

	        return false;
		} else {

	        $this->db->trans_commit();

	        return true;
		}
	}

	/**
     * Atualiza a observação exibida no envio da cotação manual e automatica
     *
     * @param - POST - request do form
     * @return bool
     */
	public function atualizar($post)
	{

		$updt = $this->db->where("id", $post['id'])->update($this->table, ['observacao' => $post['observacao'] ]);

		if ( $updt ) {
			
			return true;
		} else {

			return false;
		}
	}
}
