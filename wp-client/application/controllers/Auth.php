<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function index() {

        if($this->session->userdata('logged_in')) {
            //go to default page
            redirect(base_url().'panel');
        }

        $data = array();
        $data['login_url'] = base_url()."auth/login";

		$this->load->view('auth/login', $data);
	}

    public function login() {
        $username = $this->security->xss_clean($this->input->post('username'));
        $password = $this->security->xss_clean($this->input->post('password'));

        if(empty($username) or empty($password)) {
            $this->session->set_flashdata('error_message','Username atau password harus diisi');
            redirect(base_url().'auth');
        }

        $_POST['username'] = $username;
        $result = callWS('auth.auth_controller', 'get_user_record');
        $row = $result['user_record'];

        $md5pass = md5(trim($password));

        if( strcmp($md5pass, trim($row['user_password'])) != 0 ) {
            $this->session->set_flashdata('error_message','Username atau password Anda salah');
            redirect(base_url().'auth');
        }

        if($row['user_status'] != 1) {
            $this->session->set_flashdata('error_message','Maaf, User yang bersangkutan sudah tidak aktif. Silahkan hubungi administrator.');
            redirect(base_url().'auth');
        }


        $_POST['user_name'] = $row['user_name'];
        $result = callWS('auth.auth_controller', 'get_npdwd_by_user_name');
        $row2 = $result['user_record'];


        /**
         * Khusus pengecekkan limit nihil restoran
         * Kasus < 10Juta Rupiah
         */
        $result = callWS('auth.auth_controller', 'limit_nihil_restoran');
        $item_param_restoran = $result['param_limit_nihil_restoran'];

        /**
         * 9 = Rumah Makan
         * 10 = Restoran
         * Selain 2 Jenis Pajak Ini, maka tidak ada pengecualian dibawah 10jt.
         * Jika status Y, maka dibawah 10jt nilai nihil berlaku
         * Jika status N, maka dibawah 10jt nilai nihil tidak diberlakukan
         */

        if($row2['p_vat_type_dtl_id'] != 9 and
            $row2['p_vat_type_dtl_id'] != 10) {
            $item_param_restoran['is_active_limit_restoran'] = 'N';
        }

        $userdata = array(
                        'user_id'           => $row['user_id'],
                        'user_name'         => $row['user_name'],
                        'user_email'        => $row['user_email'],
                        'user_realname'     => $row['user_realname'],
                        'cust_account_id'  	=> $row2['t_cust_account_id'],
                        'npwd'     			=> $row2['npwd'],
                        'company_name'     => $row2['company_name'],
                        'vat_type_dtl'     => $row2['p_vat_type_dtl_id'],
                        'nilai_limit_nihil_restoran' => $item_param_restoran['nilai_limit_nihil_restoran'],
                        'is_active_limit_restoran' => $item_param_restoran['is_active_limit_restoran'],
                        'logged_in'         => true,
                      );

        $this->session->set_userdata($userdata);
        redirect(base_url().'panel');

    }

    public function logout() {

        $userdata = array(
                        'user_id'           => '',
                        'user_name'         => '',
                        'user_email'        => '',
                        'user_realname'     => '',
                        'cust_account_id'   => '',
                        'npwd'     			=> '',
                        'company_name'     	=> '',
                        'nilai_limit_nihil_restoran' => '',
                        'is_active_limit_restoran' => '',
                        'logged_in'         => false
                      );

        $this->session->unset_userdata($userdata);
        $this->session->sess_destroy();
        redirect(base_url());

    }

}
