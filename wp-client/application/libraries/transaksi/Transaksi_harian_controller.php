<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class Users_controller
* @version 07/05/2015 12:18:00
*/
class Transaksi_harian_controller {

    function read() {

        $_POST['page'] = getVarClean('page','int',1);
        $_POST['limit'] = getVarClean('rows','int',5);
        $_POST['sidx'] = getVarClean('sidx','str','start_period');
        $_POST['sord'] = getVarClean('sord','str','desc');

        $data = array();
        try {

            $ci = & get_instance();
            $_POST['cust_account_id'] = $ci->session->userdata('cust_account_id');
            $_POST['npwd'] = $ci->session->userdata('npwd');

            /**
             * parameter tambahan untuk kasus nihil restoran, sehingga jumlah pajak
             * tampilannya adalah 0
             */
            $_POST['nilai_limit_nihil_restoran'] = $ci->session->userdata('nilai_limit_nihil_restoran');
            $_POST['is_active_limit_restoran'] = $ci->session->userdata('is_active_limit_restoran');


            $data = callWS('transaksi.transaksi_harian_controller', 'read');

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

}