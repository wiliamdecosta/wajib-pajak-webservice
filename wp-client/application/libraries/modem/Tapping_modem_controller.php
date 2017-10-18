<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class Users_controller
* @version 07/05/2015 12:18:00
*/
class Tapping_modem_controller {

    public function getdata_harian(){

        $page = getVarClean('page','int',1);
        $limit = getVarClean('rows','int',5);
        $sidx = getVarClean('sidx','str',' updated_date desc, trans_date');
        $sord = getVarClean('sord','str','asc');
        $nowdate = getVarClean('nowdate','str',"");

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');
        try {
            if(empty($nowdate)) throw new Exception('Silahkan pilih tanggal tapping');

            $ci = & get_instance();
            $url = "http://45.118.112.226/dashboard/page/print/print_data_daily_npwpd.php?tgl=".$nowdate."&npwpd=".$ci->session->userdata('npwd')."";
            $getDataJSON = file_get_contents($url);
            $dataArray = jsonDecode($getDataJSON);

            $data['total'] = count($dataArray['items']);
            $data['records'] = count($dataArray['items']);

            $data['rows'] = $dataArray['items'];
            $data['success'] = true;
        } catch(Exception $e) {
            $data['message'] = $e->getMessage();
        }
        return $data;
    }

    public function getdata_bulanan(){


        $bulan = getVarClean('bulan','str',"");
        $tahun = getVarClean('tahun','str',"");
        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        try {

            if(empty($bulan) or empty($tahun)) throw new Exception('Silahkan pilih periode tapping');

            $ci = & get_instance();
            $url ="http://45.118.112.226/dashboard/page/print/print_data_monthly_npwpd.php?bulan=".$bulan."&tahun=".$tahun."&npwpd=".$ci->session->userdata('npwd')."";

            $getDataJSON = file_get_contents($url);
            $dataArray = jsonDecode($getDataJSON);

            $data['total'] = count($dataArray['items']);
            $data['records'] = count($dataArray['items']);

            $data['rows'] = $dataArray['items'];
            $data['success'] = true;
        }catch(Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }
}