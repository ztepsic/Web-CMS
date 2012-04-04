<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/core/modules/irenderer.php');
require_once(APPPATH.'libraries/core/modules/module.php');


class Menu extends Module implements IRenderer {

	private $menusModel;

 	public function __construct() {
    	parent::__construct();

    	$this->getCI()->load->model("packages/menu/menus_model");
    	$this->menusModel = $this->getCI()->menus_model;

    }

    public function render(){
    	$data['menuItems'] = $this->collectData();
    	$data['pageLink'] = $this->getCI()->uri->uri_string();
    	return $this->getCI()->load->view('packages/menu/modules/menu_' . $this->params->layout_position . '_view', $data, true);
    }

    private function collectData() {
    	$menuId = $this->params->menu_id;
    	$menuItems = $this->menusModel->GetMenuItems($menuId);
    	return $menuItems;
    }

}

?>