<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class Warehouse_controller
* @version 07/05/2015 12:18:00
*/
class History_transaksi_controller {

    function read() {

        $_POST['page'] = getVarClean('page','int',1);
        $_POST['limit'] = getVarClean('rows','int',5);
        $_POST['sidx'] = getVarClean('sidx','str','settlement_date');
        $_POST['sord'] = getVarClean('sord','str','desc');

        $data = array();
        try {

            $ci = & get_instance();
            $_POST['cust_account_id'] = $ci->session->userdata('cust_account_id');

            $data = callWS('history.history_transaksi_controller', 'read');

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

}

/* End of file Warehouse_controller.php */