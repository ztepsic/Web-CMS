<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Razred koji implementira model tipa modula
 *
 * @package models
 *
 * @author Zeljko Tepsic <ztepsic@gmail.com>
 * @copyright ztepsic.com
 * @version 1.0.0
 * @since 2008-12-28
 *
 */
class Module_types_model extends Model {

	public function __construct(){
		parent::Model();
	}

	// #########################################
	// ###### MODULE TYPES ##### BEGIN ######
	// #########################################

	/**
	 * Dohvaca sve tipove modula koji nisu administratorski
	 *
	 * @return array std_class - tipovi komponenata
	 */
	public function GetModuleTypes(){
		$query = "
			SELECT
				zt_module_types.*,
				zt_component_types.*
			FROM
				zt_module_types
			LEFT OUTER JOIN zt_component_types ON
				zt_component_types.component_type_id = zt_module_types.component_type_id
			WHERE
				module_type_admin = 0
			ORDER BY
				module_type_name;
		";


		return $this->db->query($query)->result();
	}

	/**
	 * Umece novi zapis o tipu modula
	 *
	 * @param array $moduleTypeData - podaci o tipu modula
	 * @return boolean - true ako je akcija uspjela, inace false
	 */
	public function InsertModuleType($moduleTypeData){
		$insertData['module_type_id'] = null;

		if(!empty($moduleTypeData['module_type_name'])){
			$insertData['module_type_name'] = $moduleTypeData['module_type_name'];
		}

		if(!empty($moduleTypeData['module_type_description'])){
			$insertData['module_type_description'] = $moduleTypeData['module_type_description'];
		}

		if(!empty($moduleTypeData['component_type_id'])){
			$insertData['component_type_id'] = $moduleTypeData['component_type_id'];
		}

		if(!empty($moduleTypeData['module_type_active'])){
			$insertData['module_type_active'] = $moduleTypeData['module_type_active'];
		}

		if(!empty($moduleTypeData['package'])){
			$insertData['package'] = $moduleTypeData['package'];
		}

		if(!empty($moduleTypeData['module_type_alias'])){
			$insertData['module_type_alias'] = $moduleTypeData['module_type_alias'];
		}

		if(!empty($moduleTypeData['module_type_admin'])){
			$insertData['module_type_admin'] = $moduleTypeData['module_type_admin'];
		}

		if(!empty($moduleTypeData['module_type_mulltiple_instances'])){
			$insertData['module_type_mulltiple_instances'] = $moduleTypeData['module_type_mulltiple_instances'];
		}


		return $this->db->insert('zt_module_types', $insertData);

	}


	/**
	 * Brise zadani tip modula
	 *
	 * @param int $moduleTypeId - identifikator tipa modula
	 * @return boolean - true ako je akcija uspjesno obavljena, inace false
	 */
	public function DeleteModuleType($moduleTypeId){
		$this->db->where('module_type_id', $moduleTypeId);
		return $this->db->delete('zt_module_types');
	}


	// #######################################
	// ###### MODULE TYPES ##### END ######
	// #######################################


}

?>