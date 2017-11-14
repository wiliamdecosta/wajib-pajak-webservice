<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gate extends CI_Controller {

	public function index()
	{

		$common_link_daftar =  base_url().'register';
		$common_link_login = base_url().'auth';

		$_POST['codes'] = json_encode(array('ALAMAT_1',
											'ALAMAT_2',
											'ALAMAT_3',
											'INSTANSI_1',
											'INSTANSI_2',
											'EMAIL_1',
											'INSTANSI_3'));

		$global_params = callWS('frontend.frontend_controller', 'get_global_params');
		$gb_params = $global_params['global_params'];

		$params =  array('link_daftar' => $common_link_daftar,
						'link_login' => $common_link_login);

		foreach($gb_params as $item) {
			$params[$item['code']] = $item['value'];
		}

		$this->load->view('gate', $params);

	}
}