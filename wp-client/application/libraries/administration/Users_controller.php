<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class Users_controller
* @version 07/05/2015 12:18:00
*/
class Users_controller{

    function updateProfile() {

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');
        $user_id = getVarClean('user_id','int',0);
        $user_email = getVarClean('user_email','str','');
        $password = getVarClean('password','str','');
        $password_confirmation = getVarClean('password_confirmation','str','');

        try {
            $ci = & get_instance();

            if(empty($user_id)) throw new Exception('ID tidak boleh kosong');
            // if(empty($user_email)) throw new Exception('Email tidak boleh kosong');

            $result = callWS('administration.users_controller', 'get_user_by_id');
            $item = $result['user_record'];

            if($item == null) throw new Exception('ID tidak ditemukan');

            if(!empty($password)) {
                if(strlen($password) < 4) throw new Exception('Min.Password 4 Karakter');
                if($password != $password_confirmation) throw new Exception('Password tidak sesuai');

                $result = callWS('administration.users_controller', 'update_customer_user_password');
				if($result['success'] == false) throw new Exception('Update Password Gagal');
            }

            $result = callWS('administration.users_controller', 'update_customer_user_email');
            if($result['success'] == false) throw new Exception('Update Email Gagal');

			$userdata = array(
                        'user_email'        => $user_email
                      );

			$ci->session->set_userdata($userdata);

            $data['success'] = true;
            $data['message'] = 'Data profile berhasil diupdate';
        }catch (Exception $e) {

            $data['message'] = $e->getMessage();
        }

        return $data;
    }
}

/* End of file Users_controller.php */