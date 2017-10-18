<?php

    function getControllerResult($class_name, $func_name) {
        $ci =& get_instance();
        $class_location = explode(".",$class_name);
        if(count($class_location) > 1) {
            $ci->load->library($class_location[0]."/".$class_location[1]);
            $class_name = $class_location[1];
        }else {
            $ci->load->library($class_name);
        }

        $result = $ci->$class_name->$func_name();
        $return = base64_encode ( serialize ( $result ) );

        return $return;
    }
?>