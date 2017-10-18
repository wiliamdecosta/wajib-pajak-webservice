<?php

/**
 * p_vat_type Model
 *
 */
class P_private_question extends Abstract_model {

    public $table           = "p_private_question";
    public $pkey            = "p_private_question_id";
    public $alias           = "vt";

    public $fields          = array(
                                'p_private_question_id'       => array('pkey' => true, 'type' => 'int', 'nullable' => true, 'unique' => true, 'display' => 'Pertanyaan ID'),
                                'question_pwd'    => array('nullable' => true, 'type' => 'str', 'unique' => true, 'display' => 'Pertanyaan'),
                                'description'     => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Deskripsi'),

                                'creation_date'  => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Created Date'),
                                'created_by'    => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Created By'),
                                'updated_date'  => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Updated Date'),
                                'updated_by'    => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Updated By'),

                            );

    public $selectClause    = "vt.*";
    public $fromClause      = "p_private_question vt";

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

/* End of file p_vat_type.php */