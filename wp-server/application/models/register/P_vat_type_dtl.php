<?php

/**
 * Icons Model
 *
 */
class P_vat_type_dtl extends Abstract_model {

    public $table           = "p_vat_type_dtl";
    public $pkey            = "p_vat_type_dtl_id";
    public $alias           = "pr";

    public $fields          = array(
                                'p_vat_type_dtl_id'            => array('pkey' => true, 'type' => 'int', 'nullable' => true, 'unique' => true, 'display' => 'ID Detail Jenis Pajak'),
                                 'p_vat_type_id'            => array('type' => 'int', 'nullable' => true, 'unique' => false, 'display' => 'ID Jenis Pajak'),
                                'vat_code'           => array('nullable' => false, 'type' => 'str', 'unique' => false, 'display' => 'Kode'),
                                'code'           => array('nullable' => false, 'type' => 'str', 'unique' => false, 'display' => 'Jenis Pajak'),
                                'description'    => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Deskripsi'),
                                'vat_pct'    => array('nullable' => false, 'type' => 'str', 'unique' => false, 'display' => 'Persentase Pajak'),
                                'valid_from'          => array('nullable' => false, 'type' => 'date', 'unique' => false, 'display' => 'Valid From'),
                                'valid_to'          => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Valid To'),
                                'creation_date'          => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Created Date'),
                                'created_by'            => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Created By'),
                                'updated_date'          => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Updated Date'),
                                'updated_by'            => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Updated By'),

                            );

    public $selectClause    = "pr.*, to_char(pr.updated_date, 'dd-Mon-yyyy') 
                                as update_date_str, 
                                to_char(pr.valid_from, 'dd-Mon-yyyy') as valid_from_str,
                                to_char(pr.valid_to, 'dd-Mon-yyyy') as valid_to_str";
    public $fromClause      = "p_vat_type_dtl pr";

    public $refs            = array();

    function __construct() {
        parent::__construct();
    }

    function validate() {

        $ci =& get_instance();
        
        if($this->actionType == 'CREATE') {
            //do something
            // example :
            

        }else {
            //do something
            //example:
            //$this->record['valid_from'] = date('Y-m-d', $this->record['valid_from_str']);
            //$this->record['valid_to'] = date('Y-m-d', $this->record['valid_to_str']);
            //if false please throw new Exception
        }
        return true;
    }


}
/* End of file Icons.php */