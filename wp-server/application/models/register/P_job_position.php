<?php

/**
 * Icons Model
 *
 */
class P_job_position extends Abstract_model {

    public $table           = "p_job_position";
    public $pkey            = "p_job_position_id";
    public $alias           = "pr";

    public $fields          = array(
                                'P_job_position_id'            => array('pkey' => true, 'type' => 'int', 'nullable' => true, 'unique' => true, 'display' => 'ID Room'),
                                'code'           => array('nullable' => false, 'type' => 'str', 'unique' => true, 'display' => 'Golongan Kamar'),
                                'description'    => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Deskripsi'),

                                'creation_date'          => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Created Date'),
                                'created_by'            => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Created By'),
                                'updated_date'          => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Updated Date'),
                                'updated_by'            => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Updated By'),

                            );

    public $selectClause    = "pr.*";
    public $fromClause      = "p_job_position pr";

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
            //if false please throw new Exception
        }
        return true;
    }

}

/* End of file Icons.php */