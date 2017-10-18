<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class Users_controller
* @version 07/05/2015 12:18:00
*/
class Cust_acc_trans_controller {

	function read_acc_trans() {
		$ci = & get_instance();

        $_POST['sidx'] = getVarClean('sidx','str','t_cust_acc_dtl_trans_id');
        $_POST['sord'] = getVarClean('sord','str','desc');
        $_POST['p_vat_type_dtl_id'] = getVarClean('p_vat_type_dtl_id','int',$ci->session->userdata('vat_type_dtl'));
        $_POST['start_period'] = getVarClean('start_period','str','');
        $_POST['end_period'] = getVarClean('end_period','str','');
        $_POST['cust_account_id'] = $ci->session->userdata('cust_account_id');

        $data = array();

        try {
            $data = callWS('transaksi.cust_acc_trans_controller', 'read_acc_trans');
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }
}