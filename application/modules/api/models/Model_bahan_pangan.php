<?php (defined('BASEPATH')) or exit('No direct script access allowed');

/**
 * Description of home model
 *
 * @author Yuda Pramana
 */

class Model_bahan_pangan extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getDataBahanPangan($year)
    {
        // $yearMonth = $year;
        $date = DateTime::createFromFormat('Y', $year);
        // var_dump($date);
        // die;


        $first  =  clone $date->modify('first day of January');
        // $week   = clone $first->modify('sunday this week');
        $end    = clone $date->modify('last day of December');
        $one_week = DateInterval::createFromDateString('1 week');

        // $ab = date_modify($date, '+1 day');

        // $dateTime = new \DateTime('2020-04-01');
        // $monday = clone $dateTime->modify(('Sunday' == $dateTime->format('l')) ? 'Monday last week' : 'Monday this week');
        // $sunday = clone $dateTime->modify('Sunday this week');

        // var_dump($first, $end);
        // die;

        $arrData = [];
        $week = $first->add($one_week);
        while ($first < $end) {
            $yearT = $week->format('Y');
            $weekT = $week->format('W');
            $week = $first->add($one_week);
            $arrData[] = [
                'year' => $yearT,
                'minggu_tahun' => $weekT,
                'data' => $this->getWeekData($weekT, $yearT),
            ];
        }

        // return $arrData;
        $dataseries = [];
        $categories = [];
        foreach ($arrData as $key => $item) {
            $tdate = date("Y-m-d", strtotime($item['year'] . 'W' . $item['minggu_tahun']));
            $categories[] = $tdate;
            foreach ($item['data'] as $key => $value) {
                $dataseries[$value['nama_komoditas']][$value['kategori_komoditas']][$value['jenis_komoditas']][] = floatval($value['harga']);
            }
        }


        return [
            'categories' => $categories,
            'dataseries' => $dataseries
        ];
    }

    public function getWeekData($minggu_tahun, $year)
    {
        $this->db->select('
                                komoditas.nama AS "nama_komoditas",
                                kategori.nama AS "kategori_komoditas",
                                jenis.nama AS "jenis_komoditas",
                                jenis.satuan,
                                IFNULL(harga.harga, 0) AS "harga"
                        ');
        $this->db->from('ma_komoditas_jenis jenis');
        $this->db->join('ref_komoditas 			komoditas', 'komoditas.id_komoditas = jenis.id_komoditas', 'left');
        $this->db->join('ma_komoditas_kategori 	kategori', 'kategori.id_komoditas_kategori = jenis.id_komoditas_kategori', 'left');
        $this->db->join(
            'ta_komoditas_harga 	harga',
            'harga.id_komoditas_jenis = jenis.id_komoditas_jenis 
            AND harga.minggu_tahun = ' . $minggu_tahun . '
            AND YEAR(harga.monday_date) = ' . $year . '',
            'left'
        );

        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }
}
