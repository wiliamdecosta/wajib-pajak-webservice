<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class Users_controller
* @version 07/05/2015 12:18:00
*/
class Pelaporan_pajak_controller {

	public function upload_fileexcel(){
		$data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

		$ci = & get_instance();
        $ci->load->model('transaksi/t_vat_settlement');
        $table = $ci->t_vat_settlement;

        $jsonItems = getVarClean('items', 'str', '');
        $items = jsonDecode($jsonItems);

        $t_cust_account_id = getVarClean('t_cust_account_id','int','');
		$start_period = getVarClean('start_period','str','');
		$end_period = getVarClean('end_period','str','');
		$p_vat_type_dtl_id = getVarClean('p_vat_type_dtl_id','int','');
		$p_vat_type_dtl_cls_id = getVarClean('p_vat_type_dtl_cls_id','str','');
		$user_name = getVarClean('user_name','str','');

		if($p_vat_type_dtl_cls_id != "") {
            $sql = "SELECT * FROM p_vat_type_dtl_cls WHERE vat_code = ?";
            $result = $table->db->query($sql, array($p_vat_type_dtl_cls_id));
            $row = $result->row_array();
            $p_vat_type_dtl_cls_id = $row['p_vat_type_dtl_cls_id'];
        }else {
            $p_vat_type_dtl_cls_id = 0;
        }

		try {
			$sql = "DELETE FROM t_cust_acc_dtl_trans a
					WHERE a.t_cust_account_id = ". $t_cust_account_id ."
					and not exists (select 1
						from t_vat_setllement_dtl x
							where x.t_cust_acc_dtl_trans_id = a.t_cust_acc_dtl_trans_id)";
			$result = $table->db->query($sql);

			$numItems = count($items);
			$total_transaksi = 0; $total_hari= 0;
			for($i=0; $i < $numItems; $i++)
			{
				$total_hari++;
				$table->db->trans_begin();

				$tgl_trans 		= $items[$i]["i_tgl_trans"];
				$bill_no 		= $items[$i]["i_bill_no"];
				$bill_no_end 	= $items[$i]["i_bill_no_end"];
				$bill_count 	= $items[$i]["i_bill_count"];
				$serve_desc 	= $items[$i]["i_serve_desc"];
				$serve_charge 	= $items[$i]["i_serve_charge"];
				$description 	= $items[$i]["i_desc"];

                $p_vat_type_dtl_cls_id = ($p_vat_type_dtl_cls_id == 0) ? "null" : $p_vat_type_dtl_cls_id;

                $sql = "select o_result_code, o_result_msg from \n" .
                      "f_ins_cust_acc_dtl_trans_v2(" . $t_cust_account_id . ",\n" .
                      "                         '" . $tgl_trans . "',\n" .
                      "                         '" . $bill_no. "',\n" .
                      "                         '" . $serve_desc. "',\n" .
                      "                         " . $serve_charge. ",\n" .
                      "                         null,\n" .
                      "                         '" . $description. "',\n" .
                      "                         '" . $user_name . "',\n" .
                      "                         '" . $p_vat_type_dtl_id. "',\n" .
                      "                         $p_vat_type_dtl_cls_id,".
                "                         " . $bill_count. ",".
                "                         '" . $bill_no_end. "')";
				$message = $ci->db->query($sql);
				$mess = $message->row_array();

				$total_transaksi += $serve_charge;
				$table->db->trans_commit();
			}

			$data['omzet_value'] = $total_transaksi;
			$data['success'] = true;
			$data['message'] = 'Upload file transaksi berhasil dilakukan';
			$data['total'] = $total_hari;
		} catch (Exception $e) {
			$data['success'] = false;
			$data['message'] = $e->getMessage();
		}

		return $data;
	}

	public function p_vat_type_dtl(){

		$data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');
		$vat_type_dtl = getVarClean('vat_type_dtl','int',0);

		try{
			$result = "";
			$ci = & get_instance();
			$ci->load->model('transaksi/transaksi_harian');
			$table= $ci->transaksi_harian;

			// print_r($arr_npwd);exit;
			$q 	= " select vat_type_dtl.* ";
			$q .= " FROM sikp.p_vat_type_dtl vat_type_dtl";
			$q .= " WHERE p_vat_type_dtl_id = ". $vat_type_dtl;
			$q = $ci->db->query($q);
			$result = $q->result_array();

			$data['rows'] = $result;
			$data['success'] = true;
			$data['message'] = 'success';
		}
		catch (Exception $e) {
			$table->db->trans_rollback(); //Rollback Trans
            $data['message'] = $e->getMessage();
            $data['rows'] = array();
		}

		return $data;
	}

	public function pelaporan_bulan(){

		$cust_account_id = getVarClean('cust_account_id','int',0);
		$data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

		try{
			$result = "";
			$ci = & get_instance();
			$ci->load->model('transaksi/transaksi_harian');
			$table= $ci->transaksi_harian;

			$q = "SELECT *,to_char(start_date,'dd-mm-yyyy') as start_date_string,to_char(end_date,'dd-mm-yyyy') as end_date_string
				from view_finance_period_bayar
				where p_finance_period_id - 1<= (
				SELECT p_finance_period_id p_f_p_id
				from view_finance_period_bayar
				where  to_char(start_date,'yyyy-mm-dd') in (
				select start_period start_periods
						from (select *
											from
												(select c.npwd,
															a.t_vat_setllement_id,
															a.t_customer_order_id,
															a.is_surveyed,

																a.payment_key,
																c.company_name,
																b.code as periode_pelaporan,
																to_char(a.settlement_date,'DD-MON-YYYY') tgl_pelaporan,
																a.total_trans_amount as total_transaksi,
																a.total_vat_amount as total_pajak ,
															nvl(a.total_penalty_amount,0) as total_denda,
																d.receipt_no as kuitansi_pembayaran,
																to_char(payment_date,'DD-MON-YYYY HH24:MI:SS') tgl_pembayaran ,
																d.payment_amount,
																c.t_cust_account_id ,
																b.p_finance_period_id ,
																to_char(a.start_period, 'yyyy-mm-dd') as start_period,
																to_char(a.end_period, 'yyyy-mm-dd') as end_period,
																to_char(a.start_period,'DD-MON-YYYY') as periode_awal_laporan,
																to_char(a.end_period,'DD-MON-YYYY') as periode_akhir_laporan,
																e.code as type_code,
																nvl(A.debt_vat_amt,a.total_vat_amount) as debt_vat_amt,
																nvl(a.db_increasing_charge,0) as db_increasing_charge,
																nvl(A.debt_vat_amt,a.total_vat_amount) + nvl(a.db_increasing_charge,0) +nvl(a.db_interest_charge,0) + nvl(a.total_penalty_amount,0) as total_hrs_bayar,
																nvl(a.db_increasing_charge,0) as kenaikan,
																nvl(a.db_interest_charge,0) as kenaikan1,
																a.p_vat_type_dtl_id,
																a.no_kohir,
																to_char(a.due_date,'DD-MON-YYYY') as jatuh_tempo,
																settlement_date,
																b.start_date
													from t_vat_setllement a,
															p_finance_period b,
															t_cust_account c,
															t_payment_receipt d,
															p_settlement_type e,
															p_app_user f
													where a.p_finance_period_id = b.p_finance_period_id
													and start_period is not null
																and a.t_cust_account_id = c.t_cust_account_id
															and a.t_cust_account_id =  ".$cust_account_id."
																and a.t_vat_setllement_id = d.t_vat_setllement_id (+)
															and a.p_settlement_type_id = e.p_settlement_type_id
															and a.created_by = f.app_user_name(+) ) as hasil
											left join p_vat_type_dtl x on x.p_vat_type_dtl_id = hasil.p_vat_type_dtl_id) as data_transaksi

										left join t_cust_acc_masa_jab masa_jab
											on masa_jab.t_cust_account_id = data_transaksi.t_cust_account_id
											and masa_awal <= settlement_date
											and
											case
												when masa_akhir is NULL
													then true
												when masa_akhir >= settlement_date
													then masa_akhir >= settlement_date
											end
				order by start_periods desc
				limit 1))
				limit 36";

			$q = $ci->db->query($q);
			$result = $q->result_array();
			if($result == null){
				$q = "SELECT * FROM  (
							SELECT *,to_char(start_date,'dd-mm-yyyy') as start_date_string,to_char(end_date,'dd-mm-yyyy') as end_date_string
													from view_finance_period_bayar
													limit 15

							) as foo
							order by foo.p_finance_period_id asc";
				$q = $ci->db->query($q);
				$result = $q->result_array();
			};
			$data['rows'] = $result;
			$data['success'] = true;
			$data['message'] = 'data suceeded';
		}
		catch (Exception $e) {
			$table->db->trans_rollback(); //Rollback Trans
            $data['message'] = $e->getMessage();
            $data['rows'] = array();
		}
		return $data;
	}

	public function p_vat_type_dtl_cls(){

		$data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');
		$vat_type_dtl = getVarClean('vat_type_dtl','int',0);

		try{
			$result = "";
			$ci = & get_instance();
			$ci->load->model('transaksi/transaksi_harian');
			$table= $ci->transaksi_harian;

			$q 	= " select * ";
			$q .= " FROM sikp.p_vat_type_dtl_cls ";
			$q .= " WHERE p_vat_type_dtl_id = ". $vat_type_dtl;

			$q = $ci->db->query($q);
			$result = $q->result_array();

			$data['rows'] = $result;
			$data['success'] = true;
			$data['message'] = 'success';
		}
		catch (Exception $e) {
			$table->db->trans_rollback(); //Rollback Trans
            $data['message'] = $e->getMessage();
            $data['rows'] = array();
		}

		return $data;
	}

	public function createSPTPD($args = array()){

        $vat_type_dtl = getVarClean('vat_type_dtl','int',0);
        $user_name = getVarClean('user_name','str','');

        $jsonItems = getVarClean('items', 'str', '');
        $item = jsonDecode($jsonItems);

		$ci = & get_instance();
		$ci->load->model('transaksi/t_vat_settlement');
		$table= $ci->t_vat_settlement;

		$q 	= " select vat_type_dtl.* ";
		$q .= " FROM sikp.p_vat_type_dtl vat_type_dtl";
		$q .= " WHERE p_vat_type_dtl_id = ". $vat_type_dtl;
		$q = $ci->db->query($q);
		$result = $q->result_array();

        $items = $item[0];
        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');
        try {

           if(empty($items['p_vat_type_dtl_cls_id'])){
                $items['p_vat_type_dtl_cls_id'] = 'null';
            }else {
                $items['p_vat_type_dtl_cls_id'] = 'null';
            }

            $sql = "select o_mess,o_pay_key,o_cust_order_id,o_vat_set_id from f_vat_settlement_manual_wp( ". $items['t_cust_accounts_id'] ." ,".$items['finance_period'].",'".$items['npwd']."','".$items['start_period']."','".$items['end_period']."',null,".$items['total_trans_amount'].",".$items['total_vat_amount'].",".$items['p_vat_type_dtl_id'].",".$items['p_vat_type_dtl_cls_id'].", '".$user_name."')";
            $messageq = $table->db->query($sql);
			$message = $messageq->result_array();
			$messagefinal = $message;
            $sql = "select * from f_get_penalty_amt(".$items['total_vat_amount'].",".$items['finance_period'].",".$items['p_vat_type_dtl_id'].");";
            $q = $ci->db->query($sql);
			$penalty = $q->row_array();

            if($message[0]['o_vat_set_id'] == null ||empty($message[0]['o_vat_set_id'])){
                $data['success'] = false;
            }else{
                // $data['success'] = true;
				$params = json_encode(array(
											't_vat_setllement_id'=>$message[0]['o_vat_set_id'],
											't_customer_order_id'=>$message[0]['o_cust_order_id']
											));

				$data['success'] = false;
                $sql = "select sikp.f_before_submit_sptpd_wp(".$message[0]['o_vat_set_id'].",'".$user_name."')";
				// print_r($sql." - ");//exit;
                $messageq = $table->db->query($sql);
				$message1 = $messageq->row_array();


				if(true){
                    $sql="select o_result_msg AS o_mess from sikp.f_first_submit_engine(501,".$message[0]['o_cust_order_id'].",'".$user_name."')";

                    $messageq = $table->db->query($sql);
					$message1 = $messageq->row_array();
                    if($message1=='OK'){
                        $sql="select f_gen_vat_dtl_trans(".$message[0]['o_vat_set_id'].",'".$user_name."')";
						$messageq = $table->db->query($sql);
						$message1 = $messageq->result_array();
						$data['success'] = true;
                    }else {
						$data['success'] = false;
						$data['items'] = array();
						$data['message'] = $message1;

						return $data;
					}

                }

				$data['items'] = $messagefinal[0];
				$data['message'] = $messagefinal[0]['o_mess'];

				return $data;

            }
            $data['items'] = $message[0];
            $data['message'] = $message[0]['o_mess'];

            return $data;
        }
		catch(Exception $e) {
            $data['success'] = false;
            $data['message'] = $e->getMessage();
            return $data;
        }
    }

    public function get_fined_start(){

		$data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

		$nowdate = getVarClean('nowdate', 'str', '');
		$getdate = getVarClean('getdate', 'str', '');
		try{
			$result = "";
			$ci = & get_instance();
			$ci->load->model('transaksi/transaksi_harian');
			$table= $ci->transaksi_harian;

			$q 	= 	"SELECT
						DATE_PART('day', current_date::timestamp - TO_DATE('". $nowdate ."'||due_in_day)::timestamp) boolDenda,
						ceiling(months_between(current_date::timestamp , TO_DATE('". $nowdate ."'||due_in_day)::timestamp)) boolDendaMonth
					from p_finance_period
					where to_char(start_date,'MM-YYYY') = '". $getdate ."'";
			$res = $ci->db->query($q);
			$result = $res->result_array();

			$data['rows'] = $result;
			$data['success'] = true;
			$data['message'] = '';
		}
		catch (Exception $e) {
			$table->db->trans_rollback(); //Rollback Trans
            $data['message'] = $e->getMessage();
		}

		return $data;
	}

	function create_data() {

		$data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');


        $ci = & get_instance();
        $ci->load->model('transaksi/t_vat_settlement');
        $table = $ci->t_vat_settlement;

        $jsonItems = getVarClean('items', 'str', '');
        $items = jsonDecode($jsonItems);

		$t_cust_account_id 		= getVarClean('t_cust_account_id', 'int', 0);
        $p_vat_type_dtl_id 		= getVarClean('p_vat_type_dtl_id', 'int', 0);
		$p_vat_type_dtl_cls_id 	= getVarClean('p_vat_type_dtl_cls_id','str','');
		$user_name 				= getVarClean('user_name','str','');


        if($p_vat_type_dtl_cls_id != "") {
            $sql = "SELECT * FROM p_vat_type_dtl_cls WHERE vat_code = ?";
            $result = $table->db->query($sql, array($p_vat_type_dtl_cls_id));
            $row = $result->row_array();
            $p_vat_type_dtl_cls_id = $row['p_vat_type_dtl_cls_id'];
        }else {
            $p_vat_type_dtl_cls_id = 0;
        }

        if (!is_array($items)){
            $data['message'] = 'Invalid items parameter';
            return $data;
        }

        $errors = array();
		try{

		$sql = "DELETE FROM t_cust_acc_dtl_trans a
			WHERE a.t_cust_account_id = ". $t_cust_account_id ."
			and not exists (select 1
				from t_vat_setllement_dtl x
					where x.t_cust_acc_dtl_trans_id = a.t_cust_acc_dtl_trans_id)";

		$result = $table->db->query($sql);

        if (isset($items[0])){
            $numItems = count($items);
			$total = 0;
            for($i=0; $i < $numItems; $i++)
				{

					$table->db->trans_begin();

					$dates_format = date('Y-m-d',strtotime($items[$i]["trans_date"]));
					$date_t = $dates_format ."T00:00:00";
					$date_only = explode('T', $date_t);


					$tgl_trans = empty($items[$i]["i_tgl_trans"]) ? $date_only [0] : $items[$i]["i_tgl_trans"];
					$tgl_trans = date("Y-m-d", strtotime($tgl_trans));
					$bill_no = empty($items[$i]["i_bill_no"]) ? $items[$i]["bill_no"] : $items[$i]["i_bill_no"];
					$bill_no_end = empty($items[$i]["i_bill_no_end"]) ? $items[$i]["bill_no_end"] : $items[$i]["i_bill_no_end"];
					$bill_count = empty($items[$i]["i_bill_count"]) ? $items[$i]["bill_count"] : $items[$i]["i_bill_count"];
					$serve_desc = empty($items[$i]["i_serve_desc"]) ? $items[$i]["service_desc"] : $items[$i]["i_serve_desc"];
					$serve_charge = empty($items[$i]["i_serve_charge"]) ? $items[$i]["service_charge"] : $items[$i]["i_serve_charge"];
					$description = empty($items[$i]["i_description"]) ? $items[$i]["description"] : $items[$i]["i_description"];

                    $p_vat_type_dtl_cls_id = ($p_vat_type_dtl_cls_id == 0) ? "null" : $p_vat_type_dtl_cls_id;

                    $sql = "select o_result_code, o_result_msg from \n" .
                       "f_ins_cust_acc_dtl_trans_v2(" . $items[$i]["t_cust_account_id"]. ",\n" .
                       "                         '" . $tgl_trans . "',\n" .
                       "                         '" . $bill_no. "',\n" .
                       "                         '" . $serve_desc. "',\n" .
                       "                         " . $serve_charge. ",\n" .
                       "                         null,\n" .
                       "                         '" . $description. "',\n" .
                       "                         '" . $user_name. "',\n" .
                       "                         '" . $p_vat_type_dtl_id. "',\n" .
                       "                         $p_vat_type_dtl_cls_id,".
                    "                         " . $bill_count. ",".
                    "                         '" . $bill_no_end. "')";


                    $message = $ci->db->query($sql);
					$mess = $message->row_array();

					$total++;
                    $table->db->trans_commit();
                }
            }

            $numErrors = count($errors);
            if ($numErrors > 0){
                $data['message'] = $numErrors." from ".$numItems." record(s) failed to be saved.<br/><br/><b>System Response:</b><br/>- ".implode("<br/>- ", $errors)."";
			}else{
                $data['success'] = true;
                $data['message'] = 'Data added successfully submitted';
                $data['total'] = $total;
            }
        }catch(Exception $e)
		{
			$data['success'] = false;
			$data['message'] = $e->getMessage();
		}

		return $data;
    }

}