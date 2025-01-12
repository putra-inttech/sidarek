<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @author Yogi Kaputra
 *
 */

/**
 * Fungsi membuat pesan error
 */
if (!function_exists('error_message')) {
  function error_message($type, $label, $message) {
    $ci =& get_instance();

    $error = '<div class="alert alert-dismissable alert-'.$type.'">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<strong>'.$label.'</strong> '.$message.'
							</div>';

    $ci->session->set_flashdata('message', $error);
  }
}

/**
 * Fungsi Validation Message Setting
 */
if (!function_exists('validation_message_setting')) {
  function validation_message_setting() {
    $ci =& get_instance();

    $ci->form_validation->set_message('required','%s harus diisi.');
    $ci->form_validation->set_message('min_length','%s sekurang-kurangnya harus berisi %s karakter.');
    $ci->form_validation->set_message('max_length','%s tidak boleh lebih dari %s karakter.');
    $ci->form_validation->set_message('valid_email','%s harus diisi dengan alamat email yang valid.');
    $ci->form_validation->set_message('numeric','%s harus bernilai angka yang valid.');
    $ci->form_validation->set_message('integer','%s harus bernilai bilangan bulat yang valid.');
    $ci->form_validation->set_message('matches','%s tidak cocok dengan %s.');
    $ci->form_validation->set_message('is_unique','%s sudah digunakan.');
    $ci->form_validation->set_message('is_natural_no_zero','%s tidak boleh diisi dengan nol.');
    $ci->form_validation->set_message('alpha_numeric','%s hanya boleh diisi dengan huruf atau angka.');
    $ci->form_validation->set_message('alpha','%s hanya boleh diisi dengan huruf.');
    $ci->form_validation->set_message('alpha_dash','%s hanya boleh diisi dengan huruf, angka, garis bawah atau tanda penghubung.');
    $ci->form_validation->set_message('regex_match','%s minimal 8 karakter, mengandung satu huruf besar, satu huruf kecil, satu angka dan satu karakter khusus.');
    $ci->form_validation->set_message('check_price','default');
    $ci->form_validation->set_message('validate_row','validate_row false');

    $ci->form_validation->set_error_delimiters('<div class="has-error"><p class="help-block">', '</p></div>');
  }
}

/**
 * Fungsi generate token
 */
if (!function_exists('generateToken')) {
  function generateToken($param1, $param2) {
    $datetime = gmdate('Y-m-d H:i:s', time()+60*60*7);
		$token 		= strtoupper(substr(sha1(rand().$datetime.$param1.$param2), 0, 32));
    return $token;
  }
}

/**
 * Fungsi status
 */
if (!function_exists('status')) {
  function status() {
    $status = array(
        1 => "AKTIF",
        0 => "TIDAK AKTIF"
    );
    return $status;
  }
}

/**
 * Fungsi status
 */
if (!function_exists('info')) {
  function info($flag=0) {
    if($flag == 1) {
      $status = array(
        'N' => 'Tidak',
        'Y' => 'Ya'
      );
    } else if($flag == 2) {
      $status = array(
        'Y' => 'Ya',
        'N' => 'Tidak',
        'I' => 'Tidak Tahu'
      );
    } else if($flag == 3) {
      $status = array(
        'N' => 'Tidak',
        'I' => 'Tidak Tahu',
        'Y' => 'Ya'
      );
    } else {
      $status = array(
        'Y' => 'Ya',
        'N' => 'Tidak'
      );
    }
    return $status;
  }
}

/**
 * Fungsi convert status
 */
if (!function_exists('convert_status')) {
  function convert_status($id_status) {
    $status = array(
        0 => '<span class="label label-danger">TIDAK AKTIF</span>',
        1 => '<span class="label label-success">AKTIF</span>',
    );
    return $status[intval($id_status)];
  }
}

/**
 * Fungsi blokir
 */
if (!function_exists('blokir')) {
  function blokir() {
    $blokir = array(
        0 => "TIDAK",
        1 => "YA",
    );
    return $blokir;
  }
}

/**
 * Fungsi convert blokir
 */
if (!function_exists('convert_blokir')) {
  function convert_blokir($id_blokir) {
    $blokir = array(
        0 => '<span class="label label-success">TIDAK</span>',
        1 => '<span class="label label-danger">YA</span>',
    );
    return $blokir[intval($id_blokir)];
  }
}

/**
 * Fungsi convert blokir
 */
if (!function_exists('convert_info')) {
  function convert_info($val) {
    switch ($val) {
      case 'Y': $label = "Ya";
        break;
      case 'N': $label = "Tidak";
        break;
      case 'I': $label = "Tidak Tahu";
        break;
      default: $label = "";
        break;
    }
    return $label;
  }
}

/**
 * Fungsi convert jenis fungsi
 */
if (!function_exists('convert_jenis_fungsi')) {
  function convert_jenis_fungsi($id_jenis_fungsi) {
    switch ($id_jenis_fungsi) {
      case '1': $jenis = "Fungsi Public";
        break;
      case '2': $jenis = "Fungsi Pendukung";
        break;
      case '3': $jenis = "Fungsi Private";
        break;
      case '4': $jenis = "Fungsi Belum Ada";
        break;
      default: $jenis = "";
        break;
    }
    return $jenis;
  }
}

/**
 * Fungsi convert level akses
 */
if (!function_exists('convert_level_akses')) {
  function convert_level_akses($id_level_akses) {
    switch ($id_level_akses) {
      case '1': $level = "SUPER ADMIN";
        break;
      case '2': $level = "";
        break;
      default: $level = "";
        break;
    }
    return $level;
  }
}

if (!function_exists('perkawinan')) {
  function perkawinan() {
    $arr = array(
        '' => "Pilih Data",
        '1' => "KAWIN",
        '2' => "BELUM KAWIN",
        '3' => "JANDA",
        '4' => "DUDA",
    );
    return $arr;
  }
}

if (!function_exists('agama')) {
  function agama($B_07) {
    $arr_agama = array(
        0 => "",
        1 => "ISLAM",
        2 => "KRISTEN",
        3 => "KATHOLIK",
        4 => "HINDU",
        5 => "BUDHA",
    );
    return $arr_agama[intval($B_07)];
  }
}

if (!function_exists('jenis_kelamin')) {
  function jenis_kelamin($B_06, $singkat = FALSE) {
    if ($singkat) {
      $arr = array(
        0 => "",
        1 => "L",
        2 => "P"
      );
    } else {
      $arr = array(
        0 => "",
        1 => "LAKI-LAKI",
        2 => "PEREMPUAN"
      );
    }
    return $arr[intval($B_06)];
  }
}

if (!function_exists('replace_backslases')) {
  function replace_backslases($arr) {
    $to = array();
    foreach ($arr as $k => $str) {
      $to[$k] = stripslashes($str);
    }
    return $to;
  }
}

if (!function_exists('array_tingkat_pendidikan')) {
  function array_tingkat_pendidikan() {
    $array_tingkat_pendidikan = array(
      '' => '-',
      '10' => 'SD',
      '20' => 'SLTP',
      '30' => 'SLTA',
      '41' => 'D.1',
      '42' => 'D.2',
      '43' => 'D.3',
      '44' => 'D.4',
      '50' => 'S.1',
      '60' => 'S.2',
      '70' => 'S.3',
    );
    return $array_tingkat_pendidikan;
  }
}
/* End of file inc.php */
/* Location: ./application/helpers/inc_helper.php */
