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
	
	function send_email_pendaftaran(){
        $email = $this->input->post('email');
        $name = $this->input->post('name');

        $html = '';
        $html .= '<table border="1" cellspacing="0" cellpadding="0">
                  <tr>
                        <td width="20%"><img src="http://202.149.77.5:82/mpd_ci/images/logo_lombok.png" width="100px"></td>
                        <td width="80%" align="center">
                            PEMERINTAH KABUPATEN LOMBOK UTARA <br>
                            BADAN PENGELOLAAN PENDAPATAN DAERAH <br>
                            JL. Lombok Utara <br>
                            Telp: 021 xxxxx - Fax: 021 xxxxxx <br>
                            LOMBOK UTARA
                        </td>
                  </tr>
              </table>';

        $html .= '<br><br>';
        $html .= '<table border="0">
                  <tr>
                        <td width="100%">Terima kasih karena telah melakukan pendaftaran online wajib pajak. <br>
                        Data anda akan kami proses dan kami verifikasi terlebih dahulu. </td>
                  </tr>
              </table>'; 
        $html .= '<br><br>';
        $html .= '<table border="0">
                  <tr>
                        <td width="100%">Salam Hormat,<br><br>SMPD - Lombok Utara</td>
                  </tr>
              </table>';      


        // echo $html;
        sendEmail($email, $name, 'Pendaftaran WP', $html);
        exit;

    }
}