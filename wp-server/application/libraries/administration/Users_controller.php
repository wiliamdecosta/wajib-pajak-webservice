<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class Users_controller
* @version 07/05/2015 12:18:00
*/
class Users_controller {


    function get_user_by_id() {
        $user_id = getVarClean('user_id','int',0);

        $data = array();

        try {
            $ci = & get_instance();
            $ci->load->model('administration/users');
            $table = $ci->users;

            $item = $table->get($user_id);

            $data['user_record'] = $item;
            $data['success'] = true;
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    function update_customer_user_password() {

        $user_id = getVarClean('user_id','int',0);
        $password = getVarClean('password','str','');

        $data = array();

        try {
            $ci = & get_instance();

            $sql = "UPDATE sikp.t_customer_user a
                        set user_pwd ='".md5($password)."'
                        where t_customer_user_id = ".$user_id;

            $query = $ci->db->query($sql);

            $data['success'] = true;
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
            $data['success'] = false;
        }

        return $data;
    }


    function update_customer_user_email() {

        $user_id = getVarClean('user_id','int',0);
        $user_email = getVarClean('user_email','str','');

        $data = array();

        try {
            $ci = & get_instance();

            $sql = "UPDATE sikp.t_customer
                    set email_address ='".$user_email."'
                    where t_customer_id = (SELECT t_customer_id from t_customer_user where t_customer_user_id = ".$user_id.")";

            $query = $ci->db->query($sql);

            $data['success'] = true;
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
            $data['success'] = false;
        }

        return $data;
    }
}

/* End of file Users_controller.php */