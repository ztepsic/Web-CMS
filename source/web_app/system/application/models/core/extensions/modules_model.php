<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Razred koji implementira model modula
 *
 * @package models
 *
 * @author Zeljko Tepsic <ztepsic@gmail.com>
 * @copyright ztepsic.com
 * @version 1.0.0
 * @since 2008-12-22
 *
 */
class Modules_model extends Model {

	public function __construct(){
		parent::Model();
	}

	// ################################
	// ###### MODULES ##### BEGIN #####
	// ################################

	/**
	 * Dohvaca module(instance).
	 *
	 * @return array std_class - moduli
	 */
	public function GetModules(){
		$query = "
			SELECT
				 *
			 FROM
				zt_modules
			 LEFT OUTER JOIN zt_components ON
				zt_modules.component_id =  zt_components.component_id
			JOIN zt_module_types ON
				zt_modules.module_type_id = zt_module_types.module_type_id
		";


		return $this->db->query($query)->result();
	}

	/**
	 * Dohvaca objavljene module za zadanu stranicu.
	 *
	 * @param int $pageId - identifikator stranice na kojoj ce se prikazati moduli
	 * @return stdClass - moduli za zadanu stranicu
	 */
	public function GetPublishedModules($pageId){
		$query = "
			SELECT
				zt_modules.*,
				zt_module_types.module_type_alias,
				zt_module_types.package,
				zt_layout_positions.layout_position_alias
			FROM
				zt_page_modules_groups,
				zt_layout_positions,
				zt_modules_group_items,
				zt_modules,
				zt_module_types,
				zt_modules_groups
			WHERE
				zt_page_modules_groups.page_id = ? AND
				zt_modules_groups.modules_group_id = zt_modules_group_items.modules_group_id AND
				zt_modules_groups.modules_group_published = 1 AND
				zt_layout_positions.layout_position_id = zt_page_modules_groups.layout_position_id AND
				zt_modules_group_items.modules_group_id = zt_page_modules_groups.modules_group_id AND
				zt_modules.module_id = zt_modules_group_items.module_id AND
				zt_modules.module_published = 1 AND
				zt_module_types.module_type_id = zt_modules.module_type_id
			ORDER BY
				zt_page_modules_groups.page_modules_group_order,
				zt_modules_group_items.modules_group_item_order
		";

		//echo $query;

		return $this->db->query($query, $pageId)->result();

	}


	/**
	 * Dohvaca zadani modul
	 *
	 * @param int $moduleId - identifikator modula
	 * @return std_class - trazeni modul
	 */
	public function GetModule($moduleId){
		$query = "
			SELECT
				*
			FROM
				zt_modules,
				zt_module_types
			WHERE
				module_id = ? AND
				zt_module_types.module_type_id = zt_modules.module_type_id
		";

		return $this->db->query($query, $moduleId)->row();
	}


	/**
	 * Umece novi zapis o modulu(instanci) u bazu.
	 *
	 * @param array $moduleData - podaci o modulu
	 * @return boolean - true ako je akcija uspjela, inace false
	 */
	public function InsertModule($moduleData){
		$insertData['module_id'] = null;

		if(!empty($moduleData['module_name'])){
			$insertData['module_name'] = $moduleData['module_name'];
		}

		if(!empty($moduleData['module_description'])){
			$insertData['module_description'] = $moduleData['module_description'];
		}

		if(!empty($moduleData['module_type_id'])){
			$insertData['module_type_id'] = $moduleData['module_type_id'];
		}

		if(!empty($moduleData['module_published'])){
			$insertData['module_published'] = $moduleData['module_published'];
		}

		if(!empty($moduleData['module_params'])){
			$insertData['module_params'] = $moduleData['module_params'];
		}

		if(!empty($moduleData['module_locked'])){
			$insertData['module_locked'] = $moduleData['module_locked'];
		}

		if(!empty($moduleData['component_id'])){
			$insertData['component_id'] = $moduleData['component_id'];
		}


		return $this->db->insert('zt_modules', $insertData);

	}

	/**
	 * Azurira postojeci zapis o modulu(instanci) u bazi.
	 *
	 * @param array $moduleData - podaci o modulu
	 * @return boolean - true ako je akcija uspjela, inace false
	 */
	public function UpdateModule($moduleData){
		$updateData = array();

		if(!empty($moduleData['module_name'])){
			$updateData['module_name'] = $moduleData['module_name'];
		}

		if(!empty($moduleData['module_description'])){
			$updateData['module_description'] = $moduleData['module_description'];
		}

		if(!empty($moduleData['module_type_id'])){
			$updateData['module_type_id'] = $moduleData['module_type_id'];
		}

		if(isset($moduleData['module_published'])){
			$updateData['module_published'] = $moduleData['module_published'];
		}

		if(!empty($moduleData['module_params'])){
			$updateData['module_params'] = $moduleData['module_params'];
		}

		if(!empty($moduleData['module_locked'])){
			$updateData['module_locked'] = $moduleData['module_locked'];
		}

		if(!empty($moduleData['component_id'])){
			$updateData['component_id'] = $moduleData['component_id'];
		}


		$this->db->where('module_id', $moduleData['module_id']);
		return $this->db->update('zt_modules', $updateData);
	}

	/**
	 * Brise zadani modul (instancu)
	 *
	 * @param int $moduleId - identifikator modula
	 * @return boolean - true ako je akcija uspjesno obavljena, inace false
	 */
	public function DeleteModule($moduleId){
		$this->db->where('module_id', $moduleId);
		return $this->db->delete('zt_modules');
	}

	// ##############################
	// ###### MODULES ##### END #####
	// ##############################


}

?>