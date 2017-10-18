<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class Users_controller
* @version 07/05/2015 12:18:00
*/
class Pelaporan_pajak_controller {

	public function upload_fileexcel(){

		$data = array();
		$start_period = getVarClean('start_period','str','');
        $end_period = getVarClean('end_period','str','');

        global $_FILES;

        //read file excel
        if(empty($_FILES['excel_trans_cust']['name'])){
			throw new Exception('File tidak boleh kosong');
		}

		$file_name = $_FILES['excel_trans_cust']['name']; // <-- File Name
		$file_location = 'upload_excel/'.$file_name; // <-- LOKASI Upload File

		if (!move_uploaded_file($_FILES['excel_trans_cust']['tmp_name'], $file_location)){
			throw new Exception("Upload file gagal");
		}

		include('excel/reader.php');
		$xl_reader = new Spreadsheet_Excel_Reader();
		$res = $xl_reader->_ole->read($file_location);

		if($res === false) {
			if($xl_reader->_ole->error == 1) {
				echo "File Harus Format Excel";
				exit;
			}
		}

		$xl_reader->read($file_location);
		$firstColumn = $xl_reader->sheets[0]['cells'][1][2];

		$jumlah_hari = (int)substr($end_period,8,2) - substr($start_period,8,2) + 1;
		$tahun_bulan = substr($start_period,0,8);

		$items = array();
		$loop_hari = 1;


		for($i = 3; $i <= $xl_reader->sheets[0]['numRows']; $i++) {
			$temp_date = $tahun_bulan.sprintf("%02d", ($i-3+substr($start_period,8,2)));

			if($loop_hari <= $jumlah_hari) {

				if (isset($xl_reader->sheets[0]['cells'][$i][1])){
					$temp_date2 = $xl_reader->sheets[0]['cells'][$i][1];
				}else{
					$temp_date2 = '';
				}

				if ($temp_date != $temp_date2){
					throw new Exception("Laporan masa pajak anda ini tidak sesuai dengan Laporan Rekapitulasi Penerimaan Harian. Cek kembali pemilihan masa pajak".$loop_hari);
				}
				// $item['t_cust_account_id'] = $t_cust_account_id;
				$item['i_tgl_trans'] =  $xl_reader->sheets[0]['cells'][$i][1];
				$bills = explode("-", $xl_reader->sheets[0]['cells'][$i][2]);
				$item['i_bill_no'] =  $bills[0];
				$item['i_bill_no_end'] =  $bills[1];
				$item['i_bill_count'] =  $xl_reader->sheets[0]['cells'][$i][3];
				$item['i_serve_desc'] =  '';
				$item['i_serve_charge'] =  $xl_reader->sheets[0]['cells'][$i][4];
				$i_vat_charge = $xl_reader->sheets[0]['cells'][$i][4];
				$item['i_vat_charge'] = "null";
				$item['i_desc'] = $xl_reader->sheets[0]['cells'][$i][5];
				// $item['p_vat_type_dtl_id'] = $p_vat_type_dtl_id;
				// $item['p_vat_type_dtl_cls_id'] = $p_vat_type_dtl_cls_id;
				$items[] = $item;
				$loop_hari++;

			}
		}

        try {

            $ci = & get_instance();

			$_POST['items']					 = json_encode($items);
            $_POST['t_cust_account_id']      = $ci->session->userdata('cust_account_id');
            $_POST['user_name']      		 = $ci->session->userdata('user_name');

            $data = callWS('pelaporan_pajak.pelaporan_pajak_controller', 'upload_fileexcel');

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
	}

    public function p_vat_type_dtl(){

    	$data = array();
        try {

            $ci = & get_instance();
            $_POST['vat_type_dtl'] = $ci->session->userdata('vat_type_dtl');
            $data = callWS('pelaporan_pajak.pelaporan_pajak_controller', 'p_vat_type_dtl');

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
	}

	public function pelaporan_bulan(){

		$data = array();
        try {

            $ci = & get_instance();
            $_POST['cust_account_id'] = $ci->session->userdata('cust_account_id');
            $data = callWS('pelaporan_pajak.pelaporan_pajak_controller', 'pelaporan_bulan');

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
	}

	public function p_vat_type_dtl_cls(){
		$data = array();
        try {

            $ci = & get_instance();
            $_POST['vat_type_dtl'] = $ci->session->userdata('vat_type_dtl');
            $data = callWS('pelaporan_pajak.pelaporan_pajak_controller', 'p_vat_type_dtl_cls');

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
	}

	public function createSPTPD($args = array()){
        $data = array();
        try {

            $ci = & get_instance();
            $_POST['vat_type_dtl'] = $ci->session->userdata('vat_type_dtl');
            $_POST['user_name'] = $ci->session->userdata('user_name');

            $data = callWS('pelaporan_pajak.pelaporan_pajak_controller', 'createSPTPD');

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    public function get_fined_start(){
		$data = array();
        try {

            $ci = & get_instance();
            $data = callWS('pelaporan_pajak.pelaporan_pajak_controller', 'get_fined_start');

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
	}

	public function create_data() {

        $data = array();

        try {

            $ci = & get_instance();
            $_POST['t_cust_account_id']      = $ci->session->userdata('cust_account_id');
            $_POST['p_vat_type_dtl_id']      = $ci->session->userdata('vat_type_dtl');
            $_POST['user_name']              = $ci->session->userdata('user_name');

            $data = callWS('pelaporan_pajak.pelaporan_pajak_controller', 'create_data');

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;

	}

}