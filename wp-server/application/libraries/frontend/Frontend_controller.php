<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class Frontend_controller
* @version 2017-11-09 12:13:59
*/
class Frontend_controller {

    function get_global_params() {

        $data = array();

        try {
            $ci = & get_instance();
            $codes = getVarClean('codes','str','');
            $ci->load->model('parameter/p_global_param');
            $table = $ci->p_global_param;

            $arrCode = json_decode($codes, true);
            $row = $table->getItemsByCodes($arrCode);

            $data['global_params'] = $row;
            $data['success'] = true;
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }
}

/* End of file Auth_controller.php */