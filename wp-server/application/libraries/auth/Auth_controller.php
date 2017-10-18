<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class Roles_controller
* @version 07/05/2015 12:18:00
*/
class Auth_controller {

    function get_user_record() {

        $username = getVarClean('username','str','');

        $data = array();

        try {
            $ci = & get_instance();

            $sql = "SELECT a.t_customer_user_id as user_id,
                    a.user_name,
                    a.user_pwd as user_password,
                    b.email_address as user_email,
                    c.company_brand as user_realname,
                    a.p_user_status_id as user_status
                    from sikp.t_customer_user a
                    join t_customer b on b.t_customer_id = a.t_customer_id
                    join t_cust_account c on c.t_customer_id = a.t_customer_id
                    join p_app_user d on a.p_app_user_id = d.p_app_user_id
                    where d.app_user_name = ?";

            $query = $ci->db->query($sql, array($username));
            $row = $query->row_array();

            $data['user_record'] = $row;
            $data['success'] = true;
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }


    function get_npdwd_by_user_name() {

        $user_name = getVarClean('user_name','str','');

        $data = array();

        try {
            $ci = & get_instance();

            $sql = "select * from sikp.f_get_npwd_by_username('".$user_name."')";

            $query = $ci->db->query($sql);
            $row = $query->row_array();

            $data['user_record'] = $row;
            $data['success'] = true;
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }


    function limit_nihil_restoran() {

        $data = array();

        try {
            $ci = & get_instance();

            $sql = " select to_number(value) as nilai_limit_nihil_restoran, value_2 as is_active_limit_restoran
                     from sikp.p_global_param
                     where code = 'LIMIT_NIHIL_RESTORAN'";

            $query = $ci->db->query($sql);
            $row = $query->row_array();

            $data['param_limit_nihil_restoran'] = $row;
            $data['success'] = true;
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }
}

/* End of file Auth_controller.php */