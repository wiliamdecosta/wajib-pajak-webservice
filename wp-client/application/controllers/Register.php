<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller {

	public function index()
	{
		$this->load->view('register/pendaftaran_online');

	}

	function check_user(){
        $data =  array('success' => false, 'message' => '', 'cek_user' => 0);
        try {

            $ci = & get_instance();
            $data = callWS('register.register_controller', 'cek_user');

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        echo json_encode($data);
        exit;
    }

    function submit_registration(){
    	$data =  array('success' => false, 'message' => '');

    	try {

    		$_POST['items'] = json_encode($this->input->post());
            $ci = & get_instance();
            $data = callWS('register.register_controller', 'submit_registration');

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        echo json_encode($data);
        exit;
    }
}