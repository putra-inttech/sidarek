<?php (defined('BASEPATH')) or exit('No direct script access allowed');

/**
 * Description of OTG class
 *
 * @author Yuda Pramana
 */

class Ekraf extends SLP_Controller
{

    private $csrf;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('model_ekraf' => 'mekraf'));
        $this->csrf = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );
    }

    public function index()
    {
        $this->breadcrumb->add('Dashboard', site_url('home'));
        $this->breadcrumb->add('Data Ekonomi Kreatif', '#');

        $this->session_info['page_name'] = "Data Ekonomi Kreatif";
        $this->session_info['regency'] = $this->getRegency();
        $this->session_info['jenis_ekraf'] = $this->getJenisEkraf();

        $this->template->build('form_ekraf/list', $this->session_info);
    }

    public function getJenisEkraf()
    {

        $query = $this->db->get("ref_jenis_ekraf");
        $data = [];
        $data[''] = '-- Pilih Jenis Ekraf --';
        foreach ($query->result() as $key => $value) {
            $data[$value->id_jenis_ekraf] = $value->nama;
        }

        return $data;
    }

    public function getRegency()
    {
        $this->db->where('province_id', 13);

        $query = $this->db->get("wa_regency");
        $data = [];
        $data[''] = '-- Semua Kab / Kota --';
        foreach ($query->result() as $key => $value) {
            $data[$value->id] = $value->name;
        }

        return $data;
    }

    //get data kecamatan
    public function district()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            $session  = $this->app_loader->current_account();
            $csrfHash = $this->security->get_csrf_hash();
            $regency = $this->input->get('id_regency', TRUE);

            if (!empty($regency) and !empty($session)) {
                $data = $this->mekraf->getDataDistrictByRegency($regency);
                if (count($data) > 0) {
                    $row = array();
                    foreach ($data as $key => $val) {
                        $row['id']         = $val['id'];
                        $row['text']    = $val['name'];
                        $hasil[] = $row;
                    }
                    $result = array('status' => 1, 'message' => $hasil, 'csrfHash' => $csrfHash);
                } else
                    $result = array('status' => 0, 'message' => '', 'csrfHash' => $csrfHash);
            } else {
                $result = array('status' => 0, 'message' => '', 'csrfHash' => $csrfHash);
            }
            $this->output->set_content_type('application/json')->set_output(json_encode($result));
        }
    }

    //get data Kelurahan/desa/nagari
    public function village()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            $session  = $this->app_loader->current_account();
            $csrfHash = $this->security->get_csrf_hash();
            $district = $this->input->get('id_district', TRUE);
            if (!empty($district) and !empty($session)) {
                $data = $this->mekraf->getDataVillageByDistrict($district);
                if (count($data) > 0) {
                    $row = array();
                    foreach ($data as $key => $val) {
                        $row['id']         = $val['id'];
                        $row['text']    = $val['name'];
                        $hasil[] = $row;
                    }
                    $result = array('status' => 1, 'message' => $hasil, 'csrfHash' => $csrfHash);
                } else
                    $result = array('status' => 0, 'message' => '', 'csrfHash' => $csrfHash);
            } else {
                $result = array('status' => 0, 'message' => '', 'csrfHash' => $csrfHash);
            }
            $this->output->set_content_type('application/json')->set_output(json_encode($result));
        }
    }

    public function listview()
    {

        // Get Session
        $session = $this->app_loader->current_account();

        if (!$this->input->is_ajax_request() || empty($session)) {
            exit('No direct script access allowed');
        } else {
            $param = $this->input->post('param', true);
            $resultDT = $this->mekraf->process_datatables($param);
            $resultDT['csrf'] = $this->csrf;
            $this->output->set_content_type('application/json')->set_output(json_encode($resultDT));
        }
    }


    public function create()
    {

        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {

            if ($this->mekraf->validasiDataValue('new') == false) {
                $result = array('status' => 0, 'message' => $this->form_validation->error_array());
            } else {
                $result = $this->mekraf->insert_data();
            }

            $result['csrf'] = $this->csrf;
            $this->output->set_content_type('application/json')->set_output(json_encode($result));
        }
    }

    public function delete()
    {

        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            $session   = $this->app_loader->current_account();
            $produkId     = $this->encryption->decrypt($this->input->post('produkId', true));
            if (!empty($session)) {
                $data = $this->mekraf->delete_data();
                if ($data['message'] == 'SUCCESS') {
                    $result = array('status' => 1, 'message' => 'Data kebijakan berhasil dihapus...', 'csrf' => $this->csrf);
                } else {
                    $result = array('status' => 0, 'message' => 'Proses delete data kebijakan gagal, silahkan periksa kembali data yang akan dihapus...', 'csrf' => $this->csrf);
                }
            } else {
                $result = array('status' => 0, 'message' => 'Proses delete data gagal...', 'csrf' => $this->csrf);
            }
            $this->output->set_content_type('application/json')->set_output(json_encode($result));
        }
    }

    public function update()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            $result = $this->mekraf->update_data();
            $result['csrf'] = $this->csrf;
            $this->output->set_content_type('application/json')->set_output(json_encode($result));
        }
    }

    public function export_to_excel()
    {
        require_once APPPATH . 'third_party/php_excel/vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $template  = 'repository/template/ekraf.xls';
        $objPHPExcel = $objReader->load($template);
        //get data
        $jenis       = escape($this->input->get('jenis', TRUE));
        $kabkota     = escape($this->input->get('kabkota', TRUE));

        $dataEKRAF   = $this->mekraf->getDataListekrafReport($jenis, $kabkota);

        //set title
        // $objPHPExcel->setActiveSheetIndex(0)->getStyle('E')->getAlignment()->setWrapText(true);
        $objPHPExcel->setActiveSheetIndex(0)->getRowDimension(1)->setRowHeight(-1);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:G2');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'DATA EKRAF');
        //set data hospital
        $noRow = 0;
        $baseRow = 6;
        if (count($dataEKRAF) > 0) {
            foreach ($dataEKRAF as $key => $dh) {
                $noRow++;
                $objPHPExcel->setActiveSheetIndex(0)->getRowDimension(12)->setRowHeight(-1);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('E')->getAlignment()->setWrapText(true);
                $row = $baseRow + $noRow;
                $objPHPExcel->setActiveSheetIndex(0)->insertNewRowBefore($row, 1);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $row, $noRow);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $row, $dh['usaha_nama']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $row, $dh['usaha_owner']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $row, $dh['no_hp']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $row, $dh['jenis_ekraf']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $row, $dh['alamat_jalan'] . ', ' . 'No' . ' ' . $dh['alamat_nomor'] . ', ' . 'RT/RW' . '. ' . $dh['alamat_rt_rw'] . ', ' . $dh['regency_name'] . ', ' . $dh['district_name'] . ', ' . $dh['village_name'] . ' SUMATERA BARAT' . ', ' . $dh['kode_pos']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $row, $dh['usaha_umur'] . ' Bulan');
                // $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $row, $dh['bahan_baku']);
                // $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $row, $dh['total_tk']);
            }
        } else {
            $row = $baseRow + 1;
            $objPHPExcel->setActiveSheetIndex(0)->insertNewRowBefore($row, 1);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $row, 1);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $row, '');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $row, '');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $row, '');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $row, '');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $row, '');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $row, '');
            // $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $row, '');
            // $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $row, '');
        }
        $objPHPExcel->setActiveSheetIndex(0)->removeRow($baseRow, 1);
        $file    = 'ekraf.xlsx';
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=$file");
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }
}