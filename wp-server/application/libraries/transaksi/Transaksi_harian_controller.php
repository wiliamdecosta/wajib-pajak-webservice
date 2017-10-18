<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class Users_controller
* @version 07/05/2015 12:18:00
*/
class Transaksi_harian_controller {

    function read() {

        // $user_name = getVarClean('user_name','str',32);
		$page = getVarClean('page','int',1);
        $limit = getVarClean('rows','int',5);
        $sidx = getVarClean('sidx','str','start_period');
        $sord = getVarClean('sord','str','desc');
        $t_cust_account_id = getVarClean('cust_account_id','int',0);
        $npwd = getVarClean('npwd','str','');

        $nilai_limit_nihil_restoran = getVarClean('nilai_limit_nihil_restoran','int',0);
        $is_active_limit_restoran = getVarClean('is_active_limit_restoran','str','');

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        try {

            $ci = & get_instance();
            $ci->load->model('transaksi/transaksi_harian');
            $table = new Transaksi_harian($npwd);

            $req_param = array(
                "sort_by" => $sidx,
                "sord" => $sord,
                "limit" => null,
                "field" => null,
                "where" => null,
                "where_in" => null,
                "where_not_in" => null,
                "search" => $_POST['_search'],
                "search_field" => isset($_POST['searchField']) ? $_POST['searchField'] : null,
                "search_operator" => isset($_POST['searchOper']) ? $_POST['searchOper'] : null,
                "search_str" => isset($_POST['searchString']) ? $_POST['searchString'] : null
            );

            $req_param['where'] = array();
			$table->setCriteria("
			t_cust_acc_dtl_trans.t_cust_account_id = ".$t_cust_account_id." AND
			trans_date >= CASE
					WHEN  t_vat_setllement.start_period is null THEN p_finance_period.start_date
					ELSE t_vat_setllement.start_period
				END
			AND
			t_vat_setllement.payment_key is null
			AND
			trans_date <= CASE
					WHEN  t_vat_setllement.end_period is null THEN p_finance_period.end_date
					ELSE t_vat_setllement.end_period
				END");

            $table->setJQGridParam($req_param);
            $count = $table->countAllData();

            if ($count > 0) $total_pages = ceil($count / $limit);
            else $total_pages = 1;

            if ($page > $total_pages) $page = $total_pages;
            $start = $limit * $page - ($limit); // do not put $limit*($page - 1)

            $req_param['limit'] = array(
                'start' => $start,
                'end' => $limit
            );

            $table->setJQGridParam($req_param);

            if ($page == 0) $data['page'] = 1;
            else $data['page'] = $page;

            $data['total'] = $total_pages;
            $data['records'] = $count;

            $items = $table->getAllData();

            for($i = 0; $i < count($items); $i++) {
                if( $items[$i]['jum_trans'] < $nilai_limit_nihil_restoran ) {
                    if( $is_active_limit_restoran == 'Y' ) {
                        $items[$i]['jum_pajak_view'] = 'NIHIL';
                    }else {
                        $items[$i]['jum_pajak_view'] = numberFormat($items[$i]['jum_pajak'],0);
                    }
                }else {
                    $items[$i]['jum_pajak_view'] = numberFormat($items[$i]['jum_pajak'],0);
                }
            }

            $data['rows'] = $items;
            $data['success'] = true;

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

}