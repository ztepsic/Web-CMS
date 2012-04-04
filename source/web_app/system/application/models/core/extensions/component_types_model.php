<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Razred koji implementira model tipa komponente, obuhvaca sve srodne tablice
 *
 * @package models
 *
 * @author Zeljko Tepsic <ztepsic@gmail.com>
 * @copyright ztepsic.com
 * @version 1.0.0
 * @since 2008-12-28
 *
 */
class Component_types_model extends Model {

	public function __construct(){
		parent::Model();

		$this->load->model('core/extensions/module_types_model', 'ModuleTypesModel');
	}


	// #########################################
	// ###### COMPONENT TYPES ##### BEGIN ######
	// #########################################

	/**
	 * Dohvaca sve informacije o tipu komponente. Odnosno
	 * vraca sve metode tipa komponente, instance i njihove metode
	 *
	 * @param string $componentTypeAlias - alias tipa komponente
	 * @param string $componentTypeMethodName - naziv metode tipa komponente
	 * @return std_class array - inforamcije
	 */
	public function GetComponentTypeInformations($componentTypeAlias, $componentTypeMethodName = null){
		if(empty($componentTypeMethodName)){
			$queryString = "zt_component_types.component_type_alias = ? AND";
			$params = array($componentTypeAlias);
		} else {
			$queryString = "zt_component_types.component_type_alias = ? AND";
			$queryString .= "zt_component_type_methods.component_type_method_name = ? AND";
			$params = array(
				$componentTypeAlias,
				$componentTypeMethodName);
		}

		$query = "
			SELECT
				*
			FROM
				zt_component_types,
				zt_component_type_methods,
				zt_component_methods
			WHERE
			"	. $queryString .	"
				zt_component_type_methods.component_type_id = zt_component_types.component_type_id AND
				zt_component_methods.component_type_method_id = zt_component_type_methods.component_type_method_id
		";

		return $this->db->query($query, $params)->result();
	}

	/**
	 * Dohvaca sve tipove komponenata koje nisu administratorske
	 *
	 * @return array std_class - tipovi komponenata
	 */
	public function GetComponentTypes(){
		$query = "
			SELECT
				*
			FROM
				zt_component_types
			WHERE
				component_type_admin = 0
			ORDER BY
				component_type_name;
		";


		return $this->db->query($query)->result();
	}

	/**
	 * Dohvaca sve tipove komponenata koje nisu administratorske i koje
	 * dozvoljavaju stvaranje instanci.
	 *
	 * @return array std_class - tipovi komponenata
	 */
	public function GetComponentTypesForInstances(){
		$query = "
			SELECT
				*
			FROM
				zt_component_types
			WHERE
				zt_component_types.component_type_admin = 0 AND
				(
					zt_component_types.component_type_mulltiple_instances = 1 OR
					zt_component_types.component_type_mulltiple_instances = 0 AND
					0 = (
						SELECT
							COUNT(component_id)
						FROM
							zt_components
						 WHERE
							zt_components.component_type_id = zt_component_types.component_type_id
						)
				)
			ORDER BY
				component_type_name;
		";


		return $this->db->query($query)->result();
	}

	/**
	 * Umece novi zapis o tipu komponente
	 *
	 * @param array $componentTypeData - podaci o tipu komponente
	 * @return boolean - true ako je akcija uspjela, inace false
	 */
	public function InsertComponentType($componentTypeData){
		$insertData['component_type_id'] = null;

		if(!empty($componentTypeData['component_type_name'])){
			$insertData['component_type_name'] = $componentTypeData['component_type_name'];
		}

		if(!empty($componentTypeData['component_type_description'])){
			$insertData['component_type_description'] = $componentTypeData['component_type_description'];
		}

		if(!empty($componentTypeData['component_type_alias'])){
			$insertData['component_type_alias'] = $componentTypeData['component_type_alias'];
		}

		if(!empty($componentTypeData['component_type_mulltiple_instances'])){
			$insertData['component_type_mulltiple_instances'] = $componentTypeData['component_type_mulltiple_instances'];
		}

		if(!empty($componentTypeData['component_type_admin'])){
			$insertData['component_type_admin'] = $componentTypeData['component_type_admin'];
		}

		if(!empty($componentTypeData['component_type_active'])){
			$insertData['component_type_active'] = $componentTypeData['component_type_active'];
		}

		if(!empty($componentTypeData['package'])){
			$insertData['package'] = $componentTypeData['package'];
		}

		return $this->db->insert('zt_component_types', $insertData);

	}


	/**
	 * Brise zadani tip komponente
	 *
	 * @param int $componentTypeId - identifikator tipa komponente
	 * @return boolean - true ako je akcija uspjesno obavljena, inace false
	 */
	public function DeleteComponentType($componentTypeId){
		$this->db->where('component_type_id', $componentTypeId);
		return $this->db->delete('zt_component_types');
	}


	// #######################################
	// ###### COMPONENT TYPES ##### END ######
	// #######################################


	// ################################################
	// ###### COMPONENT TYPE METHODS ##### BEGIN ######
	// ################################################

	/**
	 * Dohvaca sve metode koje sadrzi zadani tip komponente.
	 * Spaja se sa zadanim tipom komponente
	 *
	 * @param int $componentTypeId - identifikator tipa komponente
	 * @return array std_class - methode tipa komponente
	 */
	public function GetComponentTypeMethods($componentTypeId){
		$query = "
			SELECT
				*
			FROM
				zt_component_type_methods,
				zt_component_types
			WHERE
				zt_component_type_methods.component_type_id = ? AND
				zt_component_types.component_type_id = zt_component_type_methods.component_type_id
			ORDER BY
				component_type_method_name ASC
		";

		return $this->db->query($query, $componentTypeId)->result();
	}

	/**
	 * Umece novi zapis o metodi tipa komponente
	 *
	 * @param array $componentTypeMethodData - podaci o metodi tipa komponente
	 * @return boolean - true ako je akcija uspjela, inace false
	 */
	public function InsertComponentTypeMethod($componentTypeMethodData){
		$insertData['component_type_method_id'] = null;

		if(!empty($componentTypeMethodData['component_type_method_name'])){
			$insertData['component_type_method_name'] = $componentTypeMethodData['component_type_method_name'];
		}

		if(!empty($componentTypeMethodData['component_type_id'])){
			$insertData['component_type_id'] = $componentTypeMethodData['component_type_id'];
		}

		if(!empty($componentTypeMethodData['compoent_type_method_has_params'])){
			$insertData['compoent_type_method_has_params'] = $componentTypeMethodData['compoent_type_method_has_params'];
		}

		if(!empty($componentTypeMethodData['component_type_method_back'])){
			$insertData['component_type_method_back'] = $componentTypeMethodData['component_type_method_back'];
		}

		return $this->db->insert('zt_component_type_methods', $insertData);

	}

	// ##############################################
	// ###### COMPONENT TYPE METHODS ##### END ######
	// ##############################################


}

?>