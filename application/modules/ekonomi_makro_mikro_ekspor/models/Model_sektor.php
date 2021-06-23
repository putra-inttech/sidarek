<?php (defined('BASEPATH')) or exit('No direct script access allowed');

/**
 * Description of model otg
 *
 * @author Yuda Pramana
 */

class Model_sektor extends CI_Model
{
	protected $_publishDate = "";
	public function __construct()
	{
		parent::__construct();
	}

	public function validasiDataValue($role)
	{

		$this->form_validation->set_rules('nama', 'Sektor Ekspor', 'required');


		validation_message_setting();
		if ($this->form_validation->run() == FALSE)
			return false;
		else
			return true;
	}

	public function process_datatables($param)
	{

		$draw = intval($this->input->get("draw"));

		$dataProduk = $this->_get_datatables($param);

		$data = [];
		$index = 1;
		foreach ($dataProduk as $r) {
			$data[] = array(
				'index' 			=> $index,
				'id_sektor_ekspor'	=> $r['id_sektor_ekspor'],
				'sektor_ekspor' 	=> $r['sektor_ekspor'],
				'action' 			=> '<button type="button" class="btn btn-xs btn-orange btn-status-warning btnEdit" data-id="' . $this->encryption->encrypt($r['id_sektor_ekspor']) . '" title="Edit data"><i class="fa fa-pencil"></i> </button>
											<button type="button" class="btn btn-xs btn-danger btn-status-danger btnDelete" data-id="' . $this->encryption->encrypt($r['id_sektor_ekspor']) . '" title="Delete data"><i class="fa fa-times"></i> </button>'

			);
			$index++;
		}

		$result = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $this->db->count_all_results('ref_perusahaan'),
			"recordsFiltered" => $this->db->count_all_results('ref_perusahaan'),
			"data" => $data
		);

		return $result;
	}

	public function _get_datatables($param)
	{
		$post = array();
		if (is_array($param)) {
			foreach ($param as $v) {
				$post[$v['name']] = $v['value'];
			}
		}

		// Query Filter
		$this->db->select('
							a.id_sektor_ekspor,					
							a.sektor_ekspor
		');
		$this->db->from('ma_sektor_ekspor a');

		if (isset($post['nama']) and $post['nama'] != '') {
			$this->db->where('a.sektor_ekspor', $post['nama']);
		}

		$column_search = array('a.sektor_ekspor');

		$i = 0;

		foreach ($column_search as $item) { // loop column
			if (isset($_POST['search']['value'])) { // if datatable send POST for search
				if ($i === 0) { // first loop
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($column_search) - 1 == $i) { //last loop
					$this->db->group_end();
				} //close bracket
			}
			$i++;
		}
		// End of Query Filter

		// Dont know
		if ($_POST['length'] != -1) {
			$this->db->limit($_POST['length'], $_POST['start']);
		}

		$query = $this->db->get();
		return $query->result_array();
	}

	public function insert_data()
	{
		$result = [
			'status' => false,
			'success' => 'NOPE',
			'message' => null,
			'info' => null
		];

		try {

			$data = array(
				'sektor_ekspor'		=> $this->input->post('nama'),
			);

			$this->db->insert('ma_sektor_ekspor', $data);

			$result['success'] = 'YEAH';
			$result['status'] = true;
			$result['message'] = 'Data Sektor Ekspor Berhasil Disimpan';
		} catch (\Exception $e) {
			$result['info'] = $e->getMessage();
		}

		return $result;
	}

	public function update_data()
	{

		$result = [
			'status' => false,
			'success' => 'NOPE',
			'message' => null,
			'info' => null
		];

		try {

			$id_sektor_ekspor 	= $this->input->post('id_sektor_ekspor');
			$perusahaan   		= $this->db->where('id_sektor_ekspor', $id_sektor_ekspor);

			if ($perusahaan) {

				$data = array(
					'sektor_ekspor'	=> $this->input->post('nama'),
				);

				$this->db->update('ma_sektor_ekspor', $data);

				$result['success'] = 'YEAH';
				$result['status'] = true;
				$result['message'] = 'Data Ekspor Berhasil Diubah';
			}
		} catch (\Exception $e) {
			$result['info'] = $e->getMessage();
		}

		return $result;
	}

	public function delete_data()
	{
		$itemID     = $this->encryption->decrypt($this->input->post('id_sektor_ekspor', true));

		/*query delete*/
		$this->db->where('id_sektor_ekspor', $itemID);
		$this->db->delete('ma_sektor_ekspor');

		return array('message' => 'SUCCESS');
	}
}