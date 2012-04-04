<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/core/modules/iadmin.php');

class Admin_ipsumbox {

 	public function __construct() {
 		$this->CI = &get_instance();


    }

    public function RenderParamsForm($module){
    	return "";
    }

    public function ProcessParamsForm(){
    	return "";

    }

}

?>