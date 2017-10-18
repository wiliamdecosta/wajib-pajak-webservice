<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Webservice extends CI_Controller {

	function __construct() {
        parent::__construct();

		//WS Configuration
		define ( 'WS_CI', 'CI_SOAP' );
		define ( 'WSDL_NAME', 'WSDL_' . WS_CI . '.wsdl' );

		//Create WS Service Instance with WSDL
		$this->ws_svr = new nusoap_server ();
		$this->ws_svr->soap_defencoding = 'UTF-8';
		$this->ws_svr->configureWSDL ( WS_CI, 'urn:' . WSDL_NAME );

		$this->ws_svr->register ( 'ws_proccess',
		array('search' => 'xsd:string',
			  'getParams' => 'xsd:string',
			  'controller' => 'xsd:string',
			  'postParams' => 'xsd:string',
			  'jsonItems' => 'xsd:string'
			  ),
		array ('return' => 'xsd:string' ),
		'urn:' . WSDL_NAME,
		'urn:' . WSDL_NAME . '#ws_proccess',
		'rpc',
		'encoded',
		'Deskripsi fungsi ws_proccess' );

		function ws_proccess($search,
                $getParams,
                $controller,
                $postParams,
                $jsonItems){

			if(count(json_decode($getParams)) > 0){
	            $getParams = json_decode($getParams, true);
	        }else{
	            $getParams = array();
	        }

	        if(count(json_decode($postParams)) > 0){
	            $postParams = json_decode($postParams, true);
	        }else{
	            $postParams=array();
	        }

	        if(count(json_decode($jsonItems)) > 0){
	            $jsonItems = json_decode($jsonItems, true);
	        }else{
	            $jsonItems=array();
	        }

	        $controller = json_decode($controller, true);

	        if(count($getParams) > 0){
	            foreach($getParams as $key => $value){
	                $_GET[$key]=$value;
	            }
	        }

	        if(count($postParams) > 0){
	            foreach($postParams as $key => $value){
	                $_POST[$key]=$value;
	            }
	        }

	        if(count($jsonItems) > 0){
	            foreach($jsonItems as $key => $value){
	                $_POST[$key]=$value;
	            }
	        }

	        $class_name = $controller['class'];
	        $func_name = $controller['method'];

	        return getControllerResult($class_name, $func_name);
	    }

	}

	function index() {
        $this->ws_svr->service(file_get_contents("php://input")); //shows the standard info about service
    }

}
