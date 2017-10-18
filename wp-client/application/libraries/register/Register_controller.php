<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class Users_controller
* @version 07/05/2015 12:18:00
*/
class Register_controller {

	function readLovQuestion() {

        $data = array('rows' => array(), 'success' => false, 'message' => '', 'current' => 0, 'rowCount' => 0, 'total' => 0);

        try {

            $ci = & get_instance();
           	$data = callWS('register.register_controller', 'lov_private_question');

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    function readLovJob(){
        $data = array('rows' => array(), 'success' => false, 'message' => '', 'current' => 0, 'rowCount' => 0, 'total' => 0);

        try {

            $ci = & get_instance();
            $data = callWS('register.register_controller', 'lov_job_position');

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }  

    function readLovKota(){
        $data = array('rows' => array(), 'success' => false, 'message' => '', 'current' => 0, 'rowCount' => 0, 'total' => 0);

        try {

            $ci = & get_instance();
            $data = callWS('register.register_controller', 'lov_kota');

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }   

    function readLovKecamatan(){
        $data = array('rows' => array(), 'success' => false, 'message' => '', 'current' => 0, 'rowCount' => 0, 'total' => 0);

        try {

            $ci = & get_instance();
            $data = callWS('register.register_controller', 'lov_kecamatan');

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }   

    function readLovKelurahan(){
        $data = array('rows' => array(), 'success' => false, 'message' => '', 'current' => 0, 'rowCount' => 0, 'total' => 0);

        try {

            $ci = & get_instance();
            $data = callWS('register.register_controller', 'lov_kelurahan');

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    } 

    function readLovVatTypeDtl(){
        $data = array('rows' => array(), 'success' => false, 'message' => '', 'current' => 0, 'rowCount' => 0, 'total' => 0);

        try {

            $ci = & get_instance();
            $data = callWS('register.register_controller', 'lov_vat_type_dtl');

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    } 

}