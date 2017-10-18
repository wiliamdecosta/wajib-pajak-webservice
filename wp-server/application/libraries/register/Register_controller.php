<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class Users_controller
* @version 07/05/2015 12:18:00
*/
class Register_controller {

    public function submit_registration(){
        $data =  array('success' => false, 'message' => '');
        $post = getVarClean('items','str','');
        $post = json_decode($post, true);

        try {

            $ci = & get_instance();
            $result = array();
            $result_submit = array();
            $sql = "select * from f_ins_order_registration_new (  " . $post['jenis_permohonan'] . "," .
                                                            "'" . $post['description'] . "'," .
                                                            "'" . 'ADMIN' . "'," .
                                                            "'" . $post['wp_name_Name'] . "'," . //4
                                                            "'" . $post['wp_address_name_Name'] . "'," .
                                                            "'" . $post['wp_address_no_Name'] . "'," .
                                                            "'" . $post['wp_address_rt_Name'] . "'," .
                                                            "'" . $post['wp_address_rw_Name'] . "'," .
                                                            $post['p_wp_kelurahan'] . "," .
                                                            $post['p_wp_kecamatan'] . "," .
                                                            $post['p_wp_kota'] . "," .
                                                            "'" . $post['wp_phone_no_Name'] . "'," .
                                                            "'" . $post['wp_phone_no_Name'] . "'," .
                                                            "'" . $post['wp_fax_no_Name'] . "'," .
                                                            "'" . $post['wp_zip_code_Name'] . "'," .
                                                            "'" . $post['wp_email_Name'] . "'," .
                                                            "'" . $post['company_name_Name'] . "'," . //17
                                                            "'" . $post['address_name_Name'] . "'," .
                                                            "'" . $post['address_no_Name'] . "'," .
                                                            "'" . $post['address_rt_Name'] . "'," .
                                                            "'" . $post['address_rw_Name'] . "'," .
                                                            $post['p_kelurahan_code'] . "," .
                                                            $post['p_kecamatan_code'] . "," .
                                                            $post['p_kota_code'] . "," .
                                                            "'" . $post['phone_no_Name'] . "'," .
                                                            "'" . $post['phone_no_Name'] . "'," .
                                                            "'" . $post['fax_no_Name'] . "'," .
                                                            "'" . $post['zip_code_Name'] . "'," .
                                                            "'" . $post['company_brand_Name'] . "'," . //29
                                                            "'" . $post['brand_address_name_Name'] . "'," .
                                                            "'" . $post['brand_address_no_Name'] . "'," .
                                                            "'" . $post['brand_address_rt_Name'] . "'," .
                                                            "'" . $post['brand_address_rw_Name'] . "'," .
                                                            $post['p_brand_kelurahan'] . "," .
                                                            $post['p_brand_kecamatan'] . "," .
                                                            $post['p_brand_kota'] . "," .
                                                            "'" . $post['brand_phone_no_Name'] . "'," .
                                                            "'" . $post['brand_phone_no_Name'] . "'," .
                                                            "'" . $post['brand_fax_no_Name'] . "'," .
                                                            "'" . $post['brand_zip_code_Name'] . "'," .
                                                            "'" . $post['company_owner_Name'] . "'," . //41
                                                            $post['p_job_position_id'] . "," .
                                                            "'" . $post['address_name_owner_Name'] . "'," .
                                                            "'" . $post['address_no_owner_Name'] . "'," .
                                                            "'" . $post['address_rt_owner_Name'] . "'," .
                                                            "'" . $post['address_rw_owner_Name'] . "'," .
                                                            $post['p_kelurahan_own_code'] . "," .
                                                            $post['p_kecamatan_own_code'] . "," .
                                                            $post['p_kota_own_code'] . "," .
                                                            "'" . $post['phone_no_owner_Name'] . "'," .
                                                            "'" . $post['phone_no_owner_Name'] . "'," .
                                                            "'" . $post['fax_no_owner_Name'] . "'," .
                                                            "'" . $post['zip_code_owner_Name'] . "'," .
                                                            "'" . $post['email_owner_Name'] . "'," .
                                                            $post['p_vat_type_dtl_id'] . "," . //55
                                                            $post['p_vat_type_dtl_id'] . "," .
                                                            $post['p_vat_type_dtl_id'] . "," .
                                                            $post['p_vat_type_dtl_id'] . "," .
                                                            "'" . $post['InputUsername'] . "'," . //59
                                                            "'" . $post['InputPassword'] . "'," .
                                                            $post['p_private_question_id'] . "," .
                                                            "'" . $post['question_answer'] . "'," .
                                                            $post['p_private_question_id'] . "," .
                                                            "'" . $post['question_answer'] . "', " . 
                                                            " 1 ," .
                                                            " 1 ," .
                                                            " '1', " .
                                                            " 1," .
                                                            " '1' " .
                                                            " ) ;";
            $q = $ci->db->query($sql);
            $result = $q->row_array();

            if($result['o_result_msg'] == 'OK'){
                $sql_submit = "select o_result_code  as code, o_result_msg as msg ".
                                       "from f_first_submit_engine ( 500 , ".
                                       $result['o_cust_order_id'].",".
                                       " 'ADMIN' ); ";
                $qs = $ci->db->query($sql_submit);
                $result_submit = $qs->row_array();

                $data['message']= '['.$result_submit['msg'].'] Regitrasi berhasil dengan No. Order : '.$result['o_cust_order_id'];
                $data['success']= true;
            }else{
                $data['message'] = 'Registarsi Gagal';
                $data['success'] = false;
            }            

        }catch (Exception $e) {
            $table->db->trans_rollback();
            $data['message'] = $e->getMessage();
        }

        return $data;

    }

    public function cek_user(){
        $data =  array('success' => false, 'message' => '', 'cek_user' => 0);

        $user_name = getVarClean('user_name','str','');

        try {

            $ci = & get_instance();
            $result = 0;

            $sql = "SELECT * FROM sikp.t_vat_registration WHERE wp_user_name = ?";
            $q = $ci->db->query($sql, array($user_name));
            $result = $q->num_rows();

            $data['cek_user'] = $result;
            $data['success'] = true;

        }catch (Exception $e) {
            $table->db->trans_rollback();
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

	public function lov_private_question(){

		$start = getVarClean('current','int',0);
        $limit = getVarClean('rowCount','int',5);

        $sort = getVarClean('sort','str','p_private_question_id');
        $dir  = getVarClean('dir','str','asc');

        $searchPhrase = getVarClean('searchPhrase', 'str', '');

        $data = array('rows' => array(), 'success' => false, 'message' => '', 'current' => $start, 'rowCount' => $limit, 'total' => 0);

        try {

            $ci = & get_instance();
            $ci->load->model('register/p_private_question');
            $table = $ci->p_private_question;

            if(!empty($searchPhrase)) {
                $table->setCriteria("upper(question_pwd) like upper('%".$searchPhrase."%')");
            }

            $start = ($start-1) * $limit;
            $items = $table->getAll($start, $limit, $sort, $dir);
            $totalcount = $table->countAll();

            $data['rows'] = $items;
            $data['success'] = true;
            $data['total'] = $totalcount;

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
	}

    public function lov_job_position(){
        $start = getVarClean('current','int',0);
        $limit = getVarClean('rowCount','int',5);

        $sort = getVarClean('sort','str','p_job_position_id');
        $dir  = getVarClean('dir','str','asc');

        $searchPhrase = getVarClean('searchPhrase', 'str', '');

        $data = array('rows' => array(), 'success' => false, 'message' => '', 'current' => $start, 'rowCount' => $limit, 'total' => 0);

        try {

            $ci = & get_instance();
            $ci->load->model('register/p_job_position');
            $table = $ci->p_job_position;

            if(!empty($searchPhrase)) {
                $table->setCriteria("upper(code) like upper('%".$searchPhrase."%')");
            }

            $start = ($start-1) * $limit;
            $items = $table->getAll($start, $limit, $sort, $dir);
            $totalcount = $table->countAll();

            $data['rows'] = $items;
            $data['success'] = true;
            $data['total'] = $totalcount;

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    public function lov_kota(){
        $start = getVarClean('current','int',0);
        $limit = getVarClean('rowCount','int',5);

        $sort = getVarClean('sort','str','p_region_id');
        $dir  = getVarClean('dir','str','asc');

        $searchPhrase = getVarClean('searchPhrase', 'str', '');

        $data = array('rows' => array(), 'success' => false, 'message' => '', 'current' => $start, 'rowCount' => $limit, 'total' => 0);

        try {

            $ci = & get_instance();
            $ci->load->model('register/p_region');
            $table = $ci->p_region;

            if(!empty($searchPhrase)) {
                $table->setCriteria("(upper(region_name) ".$table->likeOperator." upper('%".$searchPhrase."%')
                                    OR upper(a.description) ".$table->likeOperator." upper('%".$searchPhrase."%'))");
            }
            $table->setCriteria("(p_region_level_id = 3
                                    OR p_region_level_id = 4)");

            $start = ($start-1) * $limit;
            $items = $table->getAll($start, $limit, $sort, $dir);
            $totalcount = $table->countAll();

            $data['rows'] = $items;
            $data['success'] = true;
            $data['total'] = $totalcount;

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    public function lov_kecamatan(){
        $start = getVarClean('current','int',0);
        $limit = getVarClean('rowCount','int',5);

        $sort = getVarClean('sort','str','p_region_id');
        $dir  = getVarClean('dir','str','asc');
        $parent_region_id = getVarClean('the_parent_field', 'int', null);

        $searchPhrase = getVarClean('searchPhrase', 'str', '');

        $data = array('rows' => array(), 'success' => false, 'message' => '', 'current' => $start, 'rowCount' => $limit, 'total' => 0);

        try {

            $ci = & get_instance();
            $ci->load->model('register/p_region');
            $table = $ci->p_region;

            if(!empty($searchPhrase)) {
                $table->setCriteria("(upper(region_name) ".$table->likeOperator." upper('%".$searchPhrase."%')
                                    OR upper(a.description) ".$table->likeOperator." upper('%".$searchPhrase."%'))");
            }
            $table->setCriteria("p_region_level_id = 5");
            $table->setCriteria("parent_id = ".$parent_region_id);

            $start = ($start-1) * $limit;
            $items = $table->getAll($start, $limit, $sort, $dir);
            $totalcount = $table->countAll();

            $data['rows'] = $items;
            $data['success'] = true;
            $data['total'] = $totalcount;

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    public function lov_kelurahan(){
        $start = getVarClean('current','int',0);
        $limit = getVarClean('rowCount','int',5);

        $sort = getVarClean('sort','str','p_region_id');
        $dir  = getVarClean('dir','str','asc');
        $parent_region_id = getVarClean('the_parent_field', 'int', null);

        $searchPhrase = getVarClean('searchPhrase', 'str', '');

        $data = array('rows' => array(), 'success' => false, 'message' => '', 'current' => $start, 'rowCount' => $limit, 'total' => 0);

        try {

            $ci = & get_instance();
            $ci->load->model('register/p_region');
            $table = $ci->p_region;

            if(!empty($searchPhrase)) {
                $table->setCriteria("(upper(region_name) ".$table->likeOperator." upper('%".$searchPhrase."%')
                                    OR upper(a.description) ".$table->likeOperator." upper('%".$searchPhrase."%'))");
            }
            $table->setCriteria("p_region_level_id = 6");
            $table->setCriteria("parent_id = ".$parent_region_id);

            $start = ($start-1) * $limit;
            $items = $table->getAll($start, $limit, $sort, $dir);
            $totalcount = $table->countAll();

            $data['rows'] = $items;
            $data['success'] = true;
            $data['total'] = $totalcount;

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    public function lov_vat_type_dtl(){
        $start = getVarClean('current','int',0);
        $limit = getVarClean('rowCount','int',5);

        $sort = getVarClean('sort','str','p_vat_type_id');
        $dir  = getVarClean('dir','str','asc');

        $searchPhrase = getVarClean('searchPhrase', 'str', '');

        $p_vat_type_id = getVarClean('p_vat_type_id', 'int', 0);

        $data = array('rows' => array(), 'success' => false, 'message' => '', 'current' => $start, 'rowCount' => $limit, 'total' => 0);

        try {

            $ci = & get_instance();
            $ci->load->model('register/p_vat_type_dtl');
            $table = $ci->p_vat_type_dtl;

            if(!empty($searchPhrase)) {
                $table->setCriteria(" upper(code) like upper('%".$searchPhrase."%')");
            }

            if(!empty($p_vat_type_id)) {
                $table->setCriteria(" p_vat_type_id = ".$p_vat_type_id);
            }

            

            $start = ($start-1) * $limit;
            $items = $table->getAll($start, $limit, $sort, $dir);
            $totalcount = $table->countAll();

            $data['rows'] = $items;
            $data['success'] = true;
            $data['total'] = $totalcount;

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

}