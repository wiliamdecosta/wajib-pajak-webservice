<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class Users_controller
* @version 07/05/2015 12:18:00
*/
class Cust_acc_trans_controller {


	function read_acc_trans() {
		$ci = & get_instance();

		$sidx = getVarClean('sidx','str','t_cust_acc_dtl_trans_id');
        $sord = getVarClean('sord','str','desc');
        $p_vat_type_dtl_id = getVarClean('p_vat_type_dtl_id','int',0);
        $start_period = getVarClean('start_period','str','');
        $end_period = getVarClean('end_period','str','');
        $cust_account_id = getVarClean('cust_account_id','int',0);

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        try {


            $ci->load->model('transaksi/cust_acc_trans');
            $table= $ci->cust_acc_trans;

 			$table->setCriteria('p_vat_type_dtl_id = '. $p_vat_type_dtl_id);
			$table->setCriteria( " (trunc(trans_date) BETWEEN '".$start_period."' AND '".$end_period."') ");
			$trans_date = 'null';


			$query = "select to_char(trans_date,'yyyy-mm-dd') as trans_date, to_char(trans_date,'dd-mm-yyyy') as trans_date_jqgrid,t_cust_acc_dtl_trans_id, t_cust_account_id, bill_no,bill_no_end,bill_count, service_desc, service_charge, vat_charge, tbl.description,p_vat_type_dtl_id,p_finance_period_id
                      from sikp.f_get_cust_acc_dtl_trans_v2(". $cust_account_id .",$trans_date)AS tbl (t_cust_acc_dtl_trans_id)
                      left join p_finance_period on p_finance_period.start_date <= trans_date and p_finance_period.end_date >= trans_date
                      ".$table->getCriteriaSQL()." ORDER BY ". $sidx ." ". $sord;

			$temp_row = $table->db->query($query);
			$items_from_db = $temp_row->result_array();


			$items = array();
			for ($i = 0 ; $i < substr($end_period,8,2);$i++){
				$item_transdate = date('Y-m-d',strtotime("+".$i." day",strtotime(substr($start_period,0,10))));
				$items[$i] = array('trans_date' => $item_transdate,
						't_cust_acc_dtl_trans_id' => '', 't_cust_account_id' => $cust_account_id, 'bill_no' => '',
						'bill_no_end' => '','bill_count' => '',
						'service_desc' => '','service_charge' => '','vat_charge' => '','description' => '',
						'p_vat_type_dtl_id' => $p_vat_type_dtl_id,'p_finance_period_id' => '');


				foreach($items_from_db as $item){
					if($item_transdate == $item['trans_date']){
						$items[$i] = $item;
					}
				}
			}


            $data['page'] = 1;
            $data['total'] = 1;
            $data['records'] = count($items);

            $data['rows'] = $items;
            $data['success'] = true;

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

}