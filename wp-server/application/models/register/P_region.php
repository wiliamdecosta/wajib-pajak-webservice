<?php

/**
 * Icons Model
 *
 */
class P_region extends Abstract_model {

    public $table           = "p_region";
    public $pkey            = "p_region_id";
    public $alias           = "a";

    public $fields          = array(
                                'p_region_id'            => array('pkey' => true, 'type' => 'int', 'nullable' => true, 'unique' => true, 'display' => 'ID Region'),
                                'region_name'           => array('nullable' => true, 'type' => 'str', 'unique' => true, 'display' => 'Regional'),
                                'p_region_level_id'    => array('nullable' => true, 'type' => 'int', 'unique' => false, 'display' => 'ID Regional level'),
                                'p_business_area_id'    => array('nullable' => true, 'type' => 'int', 'unique' => false, 'display' => 'ID Bisnis'),
                                'business_area_name'           => array('nullable' => true, 'type' => 'str', 'unique' => true, 'display' => 'Wilayah'),
                                'parent_id'    => array('nullable' => true, 'type' => 'int', 'unique' => false, 'display' => 'ID Parent'),
                                'description'           => array('nullable' => true, 'type' => 'str', 'unique' => true, 'display' => 'Deskripsi'),
                                'region_code'           => array('nullable' => true, 'type' => 'str', 'unique' => true, 'display' => 'Kode Regional'),
                                'postal_code'           => array('nullable' => true, 'type' => 'str', 'unique' => true, 'display' => 'Kode Postal'),
                                'nasional_code'           => array('nullable' => true, 'type' => 'str', 'unique' => true, 'display' => 'Kode Nasional'),
                                'updated_date'          => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Updated Date'),
                                'updated_by'            => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Updated By'),

                            );

    public $selectClause    = "a.*, business_area_name";
    public $fromClause      = "p_region a left join p_business_area b on a.p_business_area_id = b.p_business_area_id";

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
            //if false please throw new Exception
        }
        return true;
    }

    

}

/* End of file Icons.php */