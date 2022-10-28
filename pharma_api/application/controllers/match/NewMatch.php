<?php

class NewMatch extends CI_Controller
{
    private $bio;
    private $hmg;

    public function __construct()
    {
        parent::__construct();

        $this->bio = $this->load->database('bionexo', true);
        $this->hmg = $this->load->database('teste_pharmanexo', true);
    }

    public function index()
    {
        $produtos = $this->bio
            ->select('ct.codigo, ct.descricao, ct.id_cliente')
            ->from('pharmanexo.vw_produtos_clientes_sem_depara ct')
            ->join('pharmanexo.compradores c', ' c.id = ct.id_cliente')
            ->where('c.estado', 'RS')
            // ->where('ct.id_categoria', 100)
            //->where('ct.codigo', 113)
            // ->where('c.id', 14042)
            //->where('ct.process', 0)
            // ->where('ct.ocultar', 0)
            ->limit(100)
            ->get()->result_array();


        foreach ($produtos as $produto) {
            $nome_produto = $this->global_string($produto['descricao']);
            $keys = explode(' ', $nome_produto);
            $sintese = $this->getProdutosSintese($keys[0]);

            var_dump($keys);

            foreach ($sintese as $kk => $prod_s){
                $desc = $prod_s['descricao'];
                $pts = 0;

                foreach ($keys as $key){
                    $check = strpos($desc, $key);

                    if ($check != false){
                        $pts = $pts + 10;
                    }
                }

                $base = count($keys) * 10;
                $p = ($pts / $base) * 100;


                if ($p < 70) {
                    unset($sintese[$kk]);
                }

            }

            var_dump($sintese);
            exit();


        }

    }

    private function getProdutosSintese($termo)
    {
        $produtos_sintese = $this->db
            ->select('id_produto, id_sintese, descricao')
            ->where("descricao like '%{$termo}%'")
            ->group_by('id_produto')
            ->get('produtos_marca_sintese')
            ->result_array();

        foreach ($produtos_sintese as $k=> $prod_sint){
            $produtos_sintese[$k]['descricao'] = $this->global_string($prod_sint['descricao']);

        }

        return $produtos_sintese;
    }

    private function global_string($string)
    {
        $string = str_replace('/', ' POR ', $string);
        $string = strtoupper($this->tirarAcentos($string));

        $abrevs = [
            'ALUMÍNIO' => 'AL',
            'ÂMBAR' => 'AMB',
            'ÂMPOLA' => 'AMP',
            'BANDAGEM OCLUSIVA' => 'BAND OCL',
            'BISNAGA' => 'BISN',
            'BLISTER' => 'BL',
            'CAIXA' => 'CX',
            'CÁPSULA' => 'CAP',
            'CARPULE' => 'CARP',
            'CENTÍMETRO' => 'CM',
            'CENTÍMETRO CUBICO' => 'CC',
            'COLHER DOSADORA' => 'COL DOS',
            'COMPRIMIDO' => 'CP',
            'CONJUNTO' => 'CONJ',
            'COPO DOSADOR' => 'CP DOS',
            'COPO MEDIDA' => 'CP MED',
            'DERMATOLÓGICO' => 'DERM',
            'DILUENTE' => 'DIL',
            'DISPOSITIVO' => 'DISP',
            'TRANSFUSÃO' => 'TRANSF',
            'DRÁGEA' => 'DRG',
            'EFERVESCENTE' => 'EFERV',
            'EMULSÃO' => 'EMUL',
            'ENVELOPE' => 'ENV',
            'ESTOJO' => 'EST',
            'FLACONETE' => 'FLAC',
            'FRASCO' => 'FR',
            'FRASCO AMPOLA' => 'FA',
            'GOTAS' => 'GTS',
            'GRAMA' => 'GRA/G',
            'GRANULADO' => 'GRAN',
            'INALATÓRIO' => 'INAL',
            'INJETÁVEL' => 'INJ',
            'INTRAMUSCULAR' => 'IM',
            'INTRAVENOSO' => 'IV',
            'LIBERAÇÃO' => 'LIB',
            'LIÓFILO' => 'LIÓF',
            'MASTIGÁVEL' => 'MAST',
            'MILILITRO' => 'ML',
            'MILIGRAMA' => 'MG',
            'NUTRIÇÃO PARENTERAL' => 'NPP',
            'OFTÁLMICO' => 'OFT',
            'OTOLÓGICO' => 'OTOL',
            'PASTILHA' => 'PAS',
            'PLÁSTICO' => 'PLÁST',
            'PREPARAÇÃO EXTEMPORÂNEA' => 'PREP EXTEMP',
            'REVESTIDO' => 'REV',
            'SERINGA DOSADORA' => 'SERDOS',
            'SERINGA' => 'SER',
            'SERINGA PREENCHIDA' => 'SER PREENCH',
            'PREENCHIDA' => 'PREENCH',
            'SISTEMA DE APLICAÇÃO' => 'SIST APLIC',
            'APLICAÇÃO' => 'APLIC',
            'SISTEMA FECHADO' => 'SIST FECH',
            'FECHADO' => 'FECH',
            'SISTEMA' => 'SIST',
            'SOLUÇÃO' => 'SOL',
            'STRIP' => 'STR',
            'SUPOSITÓRIO' => 'SUP',
            'SUSPENSÃO' => 'SUSP',
            'TABLETE' => 'TAB',
            'TÓPICA' => 'TÓP',
            'TUBO' => 'TB',
            'UNIDADE' => 'UND',
            'UNIDADES INTERNACIONAIS' => 'UI',
            'VAGINAL' => 'VAG',
            'VÁLVULA' => 'VÁLV',
            'DOSADORA' => 'DOS',
            'VIDRO' => 'VD',
            'XAROPE' => 'XPE',
            'TAMANHO' => 'TAM'
        ];
        $termos = [];

        foreach ($abrevs as $k => $item) {
            $termos[$item] = $k;
        }

        $arrayComp = [
            'IM' => 'AMPOLA',
            'EV' => 'AMPOLA',
            'AMP' => 'AMPOLA',
            'INJ' => 'AMPOLA',
            'INJETAVEL' => 'AMPOLA',
            'IV' => 'AMPOLA',
            'FR/AMP' => 'AMPOLA',
            'CPRS' => 'COMPRIMIDO',
            'SEINGA' => 'SERINGA',
        ];

        $termos = array_merge($termos, $arrayComp);


        $arrayString = explode(' ', $string);

        foreach ($arrayString as $k => $item) {

            $isNum = $this->soNum($item);
            if ($isNum > 0) {
                $str = str_replace([',', '.', '/'], ['', '', ''], $item);
                $t = str_replace($isNum, '', $str);

                if (!empty($t)) {
                    $i = isset($termos[$t]) ? $termos[$t] : $item;

                    $item = str_replace($t, $i, $item);
                }

            }

            $key = str_replace('.', '', $item);
            $arrayString[$k] = isset($termos[$key]) ? $termos[$key] : $item;
        }

        return implode(" ", $arrayString);
    }

    private function repairProd($keys)
    {
        foreach ($keys as $p => $key) {
            switch (strtoupper($key)) {

                case '-':
                case '-':
                case 'DE':
                case '|':
                case '':
                case 'REF.':
                case 'REF':
                case 'NAO':
                case 'NÃO':
                case 'ACEITA':
                case 'ALTERNATIVA':
                    unset($keys[$p]);
                    break;
                default:
                    break;
            }
        }

        return $keys;
    }

    private function tirarAcentos($string)
    {
        return preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"), explode(" ", "a A e E i I o O u U n N"), $string);
    }

    private function soNum($num)
    {
        return preg_replace('/[^0-9]/', '', $num);
    }
}