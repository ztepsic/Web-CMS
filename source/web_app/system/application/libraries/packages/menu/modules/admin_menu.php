<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/core/modules/iadmin.php');

class Admin_menu {

 	public function __construct() {
 		$this->CI = &get_instance();

    	$this->CI->load->model("packages/menu/menus_model", "MenusModel");

    }

    public function RenderParamsForm($module){
    	$menuParams = unserialize($module->module_params);
    	if(!empty($menuParams)){
			$data["currentMenuId"] = $menuParams->menu_id;
    	}
    	$data['menus'] = $this->CI->MenusModel->GetMenus();
		return $this->CI->load->view('packages/menu/modules/admin/admin_menu_params_view', $data, true);
    }

    public function ProcessParamsForm(){
    	$menuId = $this->CI->input->post('menu_id');
    	$menu->menu_id = $menuId;
    	return serialize($menu);

    }

}

?>