<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Razred koji implementira model izvornika
 *
 * @package models.modules.mod_menu
 *
 * @author Zeljko Tepsic <ztepsic@gmail.com>
 * @copyright ztepsic.com
 * @version 1.0.0
 * @since 2009-01-05
 *
 */
class Menus_model extends Model {

	public function __construct(){
		parent::Model();
	}


	// ###############################
	// ###### MENUS ##### BEGIN ######
	// ###############################

	/**
	 * Dohvaca sve izbornike
	 *
	 * @return std_class array - izbornici
	 */
	public function GetMenus(){
		$query = "
			SELECT
				*
			FROM
				zt_menus
			ORDER BY
				menu_name ASC
		";

		return $this->db->query($query)->result();
	}

	/**
	 * Dohvaca izbornik
	 *
	 * @param int $menuId - identifikator izbornika
	 * @return std_class - izbornik
	 */
	public function GetMenu($menuId){
		$query = "
			SELECT
				*
			FROM
				zt_menus
			WHERE
				menu_id = ?
			LIMIT 1
		";

		return $this->db->query($query, $menuId)->row();
	}


	/**
	 * Umece novi zapis o izborniku
	 *
	 * @param array $menuData - podaci o izborniku
	 * @return boolean - true ako je akcija uspjela, inace false
	 */
	public function InsertMenu($menuData){
		$insertData['menu_id'] = null;

		if(!empty($menuData['menu_name'])){
			$insertData['menu_name'] = $menuData['menu_name'];
		}

		if(!empty($menuData['menu_description'])){
			$insertData['menu_description'] = $menuData['menu_description'];
		}

		return $this->db->insert('zt_menus', $insertData);
	}

	/**
	 * Azurira podatke o izborniku
	 *
	 * @param array $menuData - podaci o izborniku
	 * @return boolean - true ako je akcija uspjela, inace false
	 */
	public function UpdateMenu($menuData){
		$updateData = array();

		if(!empty($menuData['menu_name'])){
			$updateData['menu_name'] = $menuData['menu_name'];
		}

		if(!empty($menuData['menu_description'])){
			$updateData['menu_description'] = $menuData['menu_description'];
		}

		$this->db->where('menu_id', $menuData['menu_id']);
		return $this->db->update('zt_menus', $updateData);
	}

	/**
	 * Brise izbornik
	 *
	 * @param int $menuId - identifikator izbornika
	 * @return boolean - true ako je akcija uspjela, inace false
	 */
	public function DeleteMenu($menuId){
		$this->db->where('menu_id', $menuId);
		return $this->db->delete('zt_menus');
	}


	// ############################
	// ###### MENU ##### END ######
	// ############################


	// ####################################
	// ###### MENU ITEMS ##### BEGIN ######
	// ####################################

	/**
	 * Dohvaca elemente izbornika
	 *
	 * @param int $menuId - identifikator izbornika
	 * @param boolean $published - objavljeno
	 * @return std_array - elementi izbornika
	 */
	public function GetMenuItems($menuId){
		$query = "
			SELECT
				zt_menu_items.*,
				zt_pages.*,
				CONCAT(component_alias, '/', component_method_alias) as page_link_generated
			FROM
				zt_menu_items,
				zt_pages,
				zt_component_methods,
				zt_components
			WHERE
				zt_menu_items.page_id = zt_pages.page_id AND
				zt_component_methods.component_method_id = zt_pages.component_method_id AND
				zt_components.component_id = zt_component_methods.component_id AND
				zt_menu_items.menu_id = ? AND
				zt_menu_items.menu_item_published = 1
			ORDER BY
				zt_menu_items.menu_item_order ASC
			;

		";

		return $this->db->query($query, $menuId)->result();
	}

		/**
	 * Dohvaca elemente izbornika
	 *
	 * @param int $menuId - identifikator izbornika
	 * @param boolean $published - objavljeno
	 * @return std_array - elementi izbornika
	 */
	public function GetAllMenuItems($menuId){
		$query = "
			SELECT
				zt_menu_items.*,
				zt_pages.*,
				CONCAT(component_alias, '/', component_method_alias) as page_link_generated
			FROM
				zt_menu_items,
				zt_pages,
				zt_component_methods,
				zt_components
			WHERE
				zt_menu_items.page_id = zt_pages.page_id AND
				zt_component_methods.component_method_id = zt_pages.component_method_id AND
				zt_components.component_id = zt_component_methods.component_id AND
				zt_menu_items.menu_id = ?
			ORDER BY
				zt_menu_items.menu_item_order ASC
			;

		";

		return $this->db->query($query, $menuId)->result();
	}

	/**
	 * Dohvaca element izbornika
	 *
	 * @param int $menuItemId - identifikator elementa izbornika
	 * @return std_class - element izbornika
	 */
	public function GetMenuItem($menuItemId){
		$query = "
			SELECT
				*
			FROM
				zt_menu_items
			WHERE
				menu_item_id = ?
		";

		return $this->db->query($query, $menuItemId)->row();
	}


	/**
	 * Umece novi zapis o elementu izbornika
	 *
	 * @param array $menuItemData - podaci o elementu izbornika
	 * @return boolean - true ako je akcija uspjela, inace false
	 */
	public function InsertMenuItem($menuItemData){
		$insertData['menu_item_id'] = null;

		if(!empty($menuItemData['menu_item_name'])){
			$insertData['menu_item_name'] = $menuItemData['menu_item_name'];
		}

		if(!empty($menuItemData['menu_item_description'])){
			$insertData['menu_item_description'] = $menuItemData['menu_item_description'];
		}

		if(!empty($menuItemData['menu_id'])){
			$insertData['menu_id'] = $menuItemData['menu_id'];
		}

		if(!empty($menuItemData['page_id'])){
			$insertData['page_id'] = $menuItemData['page_id'];
		}

		if(!empty($menuItemData['menu_item_order'])){
			$insertData['menu_item_order'] = $menuItemData['menu_item_order'];
		}

		if(!empty($menuItemData['menu_item_published'])){
			$insertData['menu_item_published'] = $menuItemData['menu_item_published'];
		}

		if(!empty($menuItemData['menu_item_params'])){
			$insertData['menu_item_params'] = $menuItemData['menu_item_params'];
		}

		return $this->db->insert('zt_menu_items', $insertData);
	}


	/**
	 * Azurira zapis o elementu izbornika
	 *
	 * @param array $menuItemData - podaci o elementu izbornika
	 * @return boolean - true ako je akcija uspjela, inace false
	 */
	public function UpdateMenuItem($menuItemData){
		$updateData = array();

		if(!empty($menuItemData['menu_item_name'])){
			$updateData['menu_item_name'] = $menuItemData['menu_item_name'];
		}

		if(!empty($menuItemData['menu_item_description'])){
			$updateData['menu_item_description'] = $menuItemData['menu_item_description'];
		}

		if(!empty($menuItemData['menu_id'])){
			$updateData['menu_id'] = $menuItemData['menu_id'];
		}

		if(!empty($menuItemData['page_id'])){
			$updateData['page_id'] = $menuItemData['page_id'];
		}

		if(!empty($menuItemData['menu_item_order'])){
			$updateData['menu_item_order'] = $menuItemData['menu_item_order'];
		}

		if(isset($menuItemData['menu_item_published'])){
			$updateData['menu_item_published'] = $menuItemData['menu_item_published'];
		}

		if(!empty($menuItemData['menu_item_params'])){
			$updateData['menu_item_params'] = $menuItemData['menu_item_params'];
		}

		$this->db->where('menu_item_id', $menuItemData['menu_item_id']);
		return $this->db->update('zt_menu_items', $updateData);
	}

	/**
	 * Brise element izbornika
	 *
	 * @param int $menuItemId - identifikator elementa izbornika
	 * @return boolean - true ako je akcija uspjela, inace false
	 */
	public function DeleteMenuItem($menuItemId){
		$this->db->where('menu_item_id', $menuItemId);
		return $this->db->delete('zt_menu_items');
	}

	// ############################
	// ###### MENU ITEMS ##### END ######
	// ############################

	public function getMenuByType($menuType){
		$query = "
			SELECT
				*
			FROM
				zt_menus
			WHERE
				zt_menus.menu_type = ?
			;
		";

		return $this->db->query($query, $menuType)->row();
	}


}

?>