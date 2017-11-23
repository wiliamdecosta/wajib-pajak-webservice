<?php
    function getNusoap() {

        $ci =& get_instance();
        $ci->load->library("Nusoap_lib");
        $wsdl = $ci->config->item('ws_server');
        //create instance
        $nusoap = new nusoap_client ( $wsdl, true );
		$nusoap->setEndpoint($wsdl);
        $user = "wsclient";
        $pass = "secret";

        //encrypt header value
        $user = base64_encode ( $user );
        $pass = base64_encode ( $pass );

        $header = '<AuthSoapHeader>
                    <UserName>' . $user . '</UserName>
                    <Password>' . $pass . '</Password>
                    </AuthSoapHeader>';

        //set header
        $nusoap->setHeaders ( $header );
        return $nusoap;
    }


    function getResultData($ws_client,$params) {

        foreach($_COOKIE as $cookie_name => $cookie){
            $ws_client->setCookie($cookie_name,$cookie);
        }
        $ws_data = $ws_client->call('ws_proccess',$params);

        if ($ws_client->fault) {
            exit ( $ws_client->faultstring );
        } else {
            $err = $ws_client->getError ();
            if ($err) {
                exit ( $err );
            }
        }
        $ws_data = unserialize (base64_decode ($ws_data));
        return $ws_data;
    }

    function callWS($class_name, $function_name, $jsonItems = array()) {
        if(empty($class_name)) throw new Exception('Class name must be filled');
        if(empty($function_name)) throw new Exception('Function name must be filled');

        $ws_client = getNusoap();
        $params = array('search' => '',
                'getParams' => json_encode($_GET),
                'controller' => json_encode(array('class' => $class_name,
                                                    'method' => $function_name )),
                'postParams' => json_encode($_POST),
                'jsonItems' => json_encode($jsonItems));

        $ws_data = getResultData($ws_client, $params);
        return $ws_data;
    }

?>