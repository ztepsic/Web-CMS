<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

abstract class Module {

	private $CI;
	protected $params;

    public function __construct() {
    	$this->CI =& get_instance();
    }

    public function getCI(){
    	return  $this->CI;
    }

    public function setParams(stdClass $params=null){
    	$this->params = $params;
    }

}

?>