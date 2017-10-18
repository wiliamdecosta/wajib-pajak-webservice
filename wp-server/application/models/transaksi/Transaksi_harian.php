<?php

/**
 * Users Model
 *
 */
class Transaksi_harian extends Abstract_model {

    public $table           = "npwd";
    public $pkey            = "";
    public $alias           = "";

    public $fields          = array(

                            );

    public $selectClause    =" 'P200373322601' as npwd,
                            t_cust_acc_dtl_trans.t_cust_account_id,
                            sum(t_cust_acc_dtl_trans.service_charge) as jum_trans,
                            sum(t_cust_acc_dtl_trans.vat_charge) as jum_pajak,
                            t_cust_acc_dtl_trans.p_vat_type_dtl_id,
                            t_vat_setllement.payment_key as pay_key,
                            t_payment_receipt.receipt_no as kuitansi_pembayaran,
                            p_finance_period.p_finance_period_id,
                            p_finance_period.code,
                            t_customer_order.p_order_status_id,
                            case when t_vat_setllement.start_period is null then to_char(p_finance_period.start_date,'yyyy-mm-dd') else to_char(t_vat_setllement.start_period,'yyyy-mm-dd') END as start_period,
                            case when t_vat_setllement.end_period is null then to_char(p_finance_period.end_date,'yyyy-mm-dd') else to_char(t_vat_setllement.end_period,'yyyy-mm-dd') END as end_period";
    public $fromClause      = "t_cust_acc_dtl_trans
                            LEFT JOIN p_finance_period on to_char(trans_date, 'YYYY-MM') = to_char(p_finance_period.start_date, 'YYYY-MM')
                            LEFT JOIN t_vat_setllement on t_cust_acc_dtl_trans.t_cust_account_id = t_vat_setllement.t_cust_account_id and  p_finance_period.p_finance_period_id = t_vat_setllement.p_finance_period_id
                            LEFT JOIN t_customer_order on t_customer_order.t_customer_order_id = t_vat_setllement.t_customer_order_id
                            LEFT JOIN t_payment_receipt on t_payment_receipt.t_vat_setllement_id = t_vat_setllement.t_vat_setllement_id
                            ";


    function __construct($npwd = '') {
        // $this->fromClause = sprintf($this->fromClause, $this->session->userdata('npwd'));
        //$this->selectClause = sprintf($this->selectClause, $npwd);
        parent::__construct();
    }

    public function getAllData($start = 0, $limit = 30, $orderby = '', $ordertype = 'ASC') {

        $this->db->select($this->selectClause);
        $this->db->from($this->fromClause);
        if(count($this->joinClause) > 0) {
            foreach($this->joinClause as $with) {
                if(empty($with['table_name']) or
                    empty($with['on']) or empty($with['join_type'])) {
                    throw new Exception('Error Join Clause');
                }

                $this->db->join($with['table_name'], $with['on'], $with['join_type']);
            }
        }

        $whereCondition = '';
        $condition = array();
        $condition = $this->getCriteria();

        $whereCondition = join(" AND ", $condition);
        if( isset($this->jqGridParamSearch['where']) and count($this->jqGridParamSearch['where']) > 0)
            $whereCondition .= join(" AND ", $this->jqGridParamSearch['where']);

        $wh = "";
        if(count($this->jqGridParamSearch) > 0) {
            if($this->jqGridParamSearch['search'] != null && $this->jqGridParamSearch['search'] === 'true'){
                $wh = "UPPER(".$this->jqGridParamSearch['search_field'].")";
                switch ($this->jqGridParamSearch['search_operator']) {
                    case "bw": // begin with
                        $wh .= " LIKE UPPER('".$this->jqGridParamSearch['search_str']."%')";
                        break;
                    case "ew": // end with
                        $wh .= " LIKE UPPER('%".$this->jqGridParamSearch['search_str']."')";
                        break;
                    case "cn": // contain %param%
                        $wh .= " LIKE UPPER('%".$this->jqGridParamSearch['search_str']."%')";
                        break;
                    case "eq": // equal =
                        if(is_numeric($this->jqGridParamSearch['search_str'])) {
                            $wh .= " = ".$this->jqGridParamSearch['search_str'];
                        } else {
                            $wh .= " = UPPER('".$this->jqGridParamSearch['search_str']."')";
                        }
                        break;
                    case "ne": // not equal
                        if(is_numeric($this->jqGridParamSearch['search_str'])) {
                            $wh .= " <> ".$this->jqGridParamSearch['search_str'];
                        } else {
                            $wh .= " <> UPPER('".$this->jqGridParamSearch['search_str']."')";
                        }
                        break;
                    case "lt":
                        if(is_numeric($this->jqGridParamSearch['search_str'])) {
                            $wh .= " < ".$this->jqGridParamSearch['search_str'];
                        } else {
                            $wh .= " < '".$this->jqGridParamSearch['search_str']."'";
                        }
                        break;
                    case "le":
                        if(is_numeric($this->jqGridParamSearch['search_str'])) {
                            $wh .= " <= ".$this->jqGridParamSearch['search_str'];
                        } else {
                            $wh .= " <= '".$this->jqGridParamSearch['search_str']."'";
                        }
                        break;
                    case "gt":
                        if(is_numeric($this->jqGridParamSearch['search_str'])) {
                            $wh .= " > ".$this->jqGridParamSearch['search_str'];
                        } else {
                            $wh .= " > '".$this->jqGridParamSearch['search_str']."'";
                        }
                        break;
                    case "ge":
                        if(is_numeric($this->jqGridParamSearch['search_str'])) {
                            $wh .= " >= ".$this->jqGridParamSearch['search_str'];
                        } else {
                            $wh .= " >= '".$this->jqGridParamSearch['search_str']."'";
                        }
                        break;
                    default :
                        $wh = "";
                }
            }
        }

        if(!empty($wh)) {
            if(!empty($whereCondition))
                $whereCondition .= " AND ".$wh;
            else
                $whereCondition = $wh;
        }

        if($whereCondition != "") {
            $this->db->where($whereCondition, null, false);
        }

        $this->db->group_by("t_cust_acc_dtl_trans.t_cust_account_id,
                            t_cust_acc_dtl_trans.p_vat_type_dtl_id,
                            p_finance_period.p_finance_period_id,
                            p_finance_period.code,
                            pay_key,
                            kuitansi_pembayaran,
                            t_customer_order.p_order_status_id,
                            case when t_vat_setllement.start_period is null then to_char(p_finance_period.start_date,'yyyy-mm-dd') else to_char(t_vat_setllement.start_period,'yyyy-mm-dd') END,
                            case when t_vat_setllement.end_period is null then to_char(p_finance_period.end_date,'yyyy-mm-dd') else to_char(t_vat_setllement.end_period,'yyyy-mm-dd') END");

        if(count($this->jqGridParamSearch) > 0) {
            $this->db->order_by($this->jqGridParamSearch['sort_by'], $this->jqGridParamSearch['sord']);
        }else {
            if(empty($orderby)) $orderby = $this->pkey;
            $this->db->order_by($orderby, $ordertype);
        }

        if(count($this->jqGridParamSearch) > 0) {
            $this->db->limit($this->jqGridParamSearch['limit']['end'], $this->jqGridParamSearch['limit']['start']);
        }else if($limit != -1) {
            $this->db->limit($limit, $start);
        }

        // print_r($this->db->get_compiled_select());exit;
        $queryResult = $this->db->get();
        $items = $queryResult->result_array();

        $queryResult->free_result();

        return $items;

    }


    public function countAllData() {
        //$this->db->_protect_identifiers = false;

        $query = "SELECT COUNT(1) AS totalcount FROM (SELECT COUNT(1) FROM ".$this->fromClause;
        if(count($this->joinClause) > 0) {

            foreach($this->joinClause as $with) {
                if(empty($with['table_name']) or
                        empty($with['on']) or empty($with['join_type'])) {
                        throw new Exception('Error Join Clause');
                }
                $query.= " ".$with['join_type']." JOIN ".$with['table_name']." ON ".$with['on'];
            }
        }

        $whereCondition = '';
        $condition = array();
        $condition = $this->getCriteria();

        $whereCondition = join(" AND ", $condition);
        if(isset($this->jqGridParamSearch['where']) and count($this->jqGridParamSearch['where']) > 0)
            $whereCondition .= join(" AND ", $this->jqGridParamSearch['where']);

        $wh = "";
        if(count($this->jqGridParamSearch) > 0) {
            if($this->jqGridParamSearch['search'] != null && $this->jqGridParamSearch['search'] === 'true'){
                $wh = "UPPER(".$this->jqGridParamSearch['search_field'].")";
                switch ($this->jqGridParamSearch['search_operator']) {
                    case "bw": // begin with
                        $wh .= " LIKE UPPER('".$this->jqGridParamSearch['search_str']."%')";
                        break;
                    case "ew": // end with
                        $wh .= " LIKE UPPER('%".$this->jqGridParamSearch['search_str']."')";
                        break;
                    case "cn": // contain %param%
                        $wh .= " LIKE UPPER('%".$this->jqGridParamSearch['search_str']."%')";
                        break;
                    case "eq": // equal =
                        if(is_numeric($this->jqGridParamSearch['search_str'])) {
                            $wh .= " = ".$this->jqGridParamSearch['search_str'];
                        } else {
                            $wh .= " = UPPER('".$this->jqGridParamSearch['search_str']."')";
                        }
                        break;
                    case "ne": // not equal
                        if(is_numeric($this->jqGridParamSearch['search_str'])) {
                            $wh .= " <> ".$this->jqGridParamSearch['search_str'];
                        } else {
                            $wh .= " <> UPPER('".$this->jqGridParamSearch['search_str']."')";
                        }
                        break;
                    case "lt":
                        if(is_numeric($this->jqGridParamSearch['search_str'])) {
                            $wh .= " < ".$this->jqGridParamSearch['search_str'];
                        } else {
                            $wh .= " < '".$this->jqGridParamSearch['search_str']."'";
                        }
                        break;
                    case "le":
                        if(is_numeric($this->jqGridParamSearch['search_str'])) {
                            $wh .= " <= ".$this->jqGridParamSearch['search_str'];
                        } else {
                            $wh .= " <= '".$this->jqGridParamSearch['search_str']."'";
                        }
                        break;
                    case "gt":
                        if(is_numeric($this->jqGridParamSearch['search_str'])) {
                            $wh .= " > ".$this->jqGridParamSearch['search_str'];
                        } else {
                            $wh .= " > '".$this->jqGridParamSearch['search_str']."'";
                        }
                        break;
                    case "ge":
                        if(is_numeric($this->jqGridParamSearch['search_str'])) {
                            $wh .= " >= ".$this->jqGridParamSearch['search_str'];
                        } else {
                            $wh .= " >= '".$this->jqGridParamSearch['search_str']."'";
                        }
                        break;
                    default :
                        $wh = "";
                }
            }
        }



        if(!empty($wh)) {
            if(!empty($whereCondition))
                $whereCondition .= " AND ".$wh;
            else
                $whereCondition = $wh;
        }

        if(!empty($whereCondition)) {
            $query = $query. " WHERE ".$whereCondition ."";
        }

        $query.= " GROUP BY t_cust_acc_dtl_trans.t_cust_account_id,
                            t_cust_acc_dtl_trans.p_vat_type_dtl_id,
                            p_finance_period.p_finance_period_id,
                            p_finance_period.code,
                            t_customer_order.p_order_status_id,
                            case when t_vat_setllement.start_period is null then to_char(p_finance_period.start_date,'yyyy-mm-dd') else to_char(t_vat_setllement.start_period,'yyyy-mm-dd') END,
                            case when t_vat_setllement.end_period is null then to_char(p_finance_period.end_date,'yyyy-mm-dd') else to_char(t_vat_setllement.end_period,'yyyy-mm-dd') END
                    )
                            ";
        $query = $this->db->query($query);
        $row = $query->row_array();

        $query->free_result();

        return $row['totalcount'];
    }


}

/* End of file Users.php */