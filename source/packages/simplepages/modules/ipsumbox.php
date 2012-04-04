<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/core/modules/irenderer.php');
require_once(APPPATH.'libraries/core/modules/module.php');


class Ipsumbox extends Module implements IRenderer {



 	public function __construct() {
    	parent::__construct();
    }

    public function render(){
			return "Cras et ante eu dui accumsan tristique. In enim urna, fermentum in, porta quis, pharetra et, massa. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;";
    }

    private function collectData() {

    }

}

?>