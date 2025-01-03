<?php


class LogEnvioBkup extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('M_log_envio_automatico', 'envio_automatico');
        $this->load->model('M_log_envio_automatico_bkp', 'envio_automatico_bkp');
    }


    public function run()
    {
        //Consulta apenas as datas menos que hoje
        $logs = $this->envio_automatico->find('*', "DATE_FORMAT(data_criacao, '%Y-%m-%d') < curdate()");

        foreach ($logs as $log):

            $log_id = $log['id'];

            $checkIsExist = $this->envio_automatico_bkp->find('*', "id_fornecedor = {$log['id_fornecedor']} 
            AND integrador = '{$log['integrador']}'
            AND cd_cotacao = '{$log['cd_cotacao']}'  
            AND id_cliente = {$log['id_cliente']}
            AND id_estado = {$log['id_estado']}
            AND data_criacao = '{$log['data_criacao']}'
            AND status = {$log['status']}
            ");

            if (empty($checkIsExist)):
                unset($log['id']);

                //salva na tabela de backup
                if (!$this->envio_automatico_bkp->insert($log)):
                    echo "não gravou <br>";
                    var_dump($log);
                else:
                    //apaga o log da tabela original;

                    $this->envio_automatico->delete($log_id);

                endif;
            else:
                //apaga o log da tabela original caso ainda exista;
                $this->envio_automatico->delete($log_id);
            endif;

        endforeach;


    }


}