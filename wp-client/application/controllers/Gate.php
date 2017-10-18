<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gate extends CI_Controller {

	public function index()
	{

		$common_link_daftar =  base_url().'register';
		$common_link_login = base_url().'auth';


		$this->load->view('gate', array('link_daftar' => $common_link_daftar,
											'link_login' => $common_link_login)
							);

	}
}