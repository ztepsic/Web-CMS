<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

interface IAdmin {
	public function RenderParamsForm($module);
	public function ProcessParamsForm();
}

?>