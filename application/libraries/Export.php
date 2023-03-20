<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Export
{

    private $db;
    private $words = [1 => 'A', 2 => 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

    public function __construct()
    {
        $this->CI = &get_instance();

        $this->db = $this->CI->db;
        $this->CI->load->helper('download');
        // ini_set('memory_limit', '128M');
    }

    /**
     * Função para gerar planilha excel
     *
     * @param - String nome do arquivo
     * @param - Array dados pagina 1 (dados, titulo)
     * @param - Array dados pagina 2 (dados, titulo)
     * @param - Array dados pagina 3 (dados, titulo)
     *
     * @return bool/download file
     */
    public function excel($filename, $page1, $page2 = null, $page3 = null)
    {

        try {

            $spreadsheet = new Spreadsheet();

            if (count($page1['dados']) > 0) {
                // Set the document header and center it by '&C'
                $sheet = $spreadsheet->getActiveSheet()->setTitle($page1['titulo']);
                $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(55);
                $spreadsheet->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension("C")->setAutoSize(true);
                $spreadsheet->getActiveSheet()->setCellValue('A1', 'helpdesk@pharmanexo.com.br');
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('Pharmanexo logo');
                $drawing->setPath('public/assets/img/logo_after.png');
                $drawing->setHeight(50);
                $drawing->setCoordinates('A1');
                $drawing->setWorksheet($spreadsheet->getActiveSheet());

                $countNumber = 2;

                $data = $this->getHeader($page1['dados']);

                foreach ($data as $chave => $row) {

                    $countWord = 1;
                    foreach ($row as $column) {
                        $sheet->setCellValue("{$this->words[$countWord]}{$countNumber}", $column);
                        $countWord++;
                    }
                    $countNumber++;
                }
            }

            if (isset($page2['dados']) && count($page2['dados']) > 0) {

                $sheet2 = $spreadsheet->createSheet()->setTitle($page2['titulo']);

                $countNumber = 1;

                $data = $this->getHeader($page2['dados']);

                foreach ($data as $chave => $row) {

                    $countWord = 1;
                    foreach ($row as $column) {
                        $sheet2->setCellValue("{$this->words[$countWord]}{$countNumber}", $column);
                        $countWord++;
                    }
                    $countNumber++;
                }
            }

            if (isset($page3['dados']) && count($page3['dados']) > 0) {

                $sheet3 = $spreadsheet->createSheet()->setTitle($page3['titulo']);

                $countNumber = 1;

                $data = $this->getHeader($page3['dados']);

                foreach ($data as $chave => $row) {

                    $countWord = 1;
                    foreach ($row as $column) {
                        $sheet3->setCellValue("{$this->words[$countWord]}{$countNumber}", $column);
                        $countWord++;
                    }
                    $countNumber++;
                }
            }

            $writer = new Xlsx($spreadsheet);

            $name = "public/exports/" . time() . $filename;

            $writer->save($name);

            force_download($name, NULL);
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {

            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Função para inserir o cabeçalho do excel no array
     *
     * @param - Array dados
     *
     * @return array
     */
    public function getHeader($data)
    {
        $titles = [];
        foreach (array_keys($data[0]) as $title) {

            $titles["{$title}"] = $title;
        }

        array_unshift($data, $titles);

        return $data;
    }
}
