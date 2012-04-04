<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Razred koji implementira model komponenata(instance)
 *
 * @package models
 *
 * @author Zeljko Tepsic <ztepsic@gmail.com>
 * @copyright ztepsic.com
 * @version 1.0.0
 * @since 2008-12-29
 *
 */
class Components_model extends Model {

	public function __construct(){
		parent::Model();

		$this->load->model('core/pages_model', "PagesModel");

	}

	// ####################################
	// ###### COMPONENTS ##### BEGIN ######
	// ####################################


	/**
	 * Dohvaca sve komponente osim administratorskih instanci.
	 *
	 * @return array std_class - komponente
	 */
	public function GetComponents(){
		$query = "
			SELECT
				*
			FROM
				zt_components,
				zt_component_types
			WHERE
				zt_components.component_type_id = zt_component_types.component_type_id AND
				zt_component_types.component_type_admin = 0
			ORDER BY
				component_name;
		";


		return $this->db->query($query)->result();
	}

	/**
	 * Dohvaca komponentu za zadani identifikator komponente
	 *
	 * @param int $componentId - identifikator komponente
	 * @return std_class - trazena komponenta
	 */
	public function GetComponent($componentId){
		$query = "
			SELECT
				*
			FROM
				zt_components
			WHERE
				component_id = ?
			LIMIT 1;
		";

		return $this->db->query($query, $componentId)->row();
	}


	/**
	 * Umece novu komponentu u bazu
	 *
	 * @param array $componentData - podaci o komponenti
	 * @return boolean - true ako je akcija uspjela, inace false
	 */
	public function InsertComponent($componentData){
		$insertData['component_id'] = null;

		if(!empty($componentData['component_name'])){
			$insertData['component_name'] = $componentData['component_name'];
		}

		if(!empty($componentData['component_description'])){
			$insertData['component_description'] = $componentData['component_description'];
		}

		if(!empty($componentData['component_type_id'])){
			$insertData['component_type_id'] = $componentData['component_type_id'];
		}

		if(!empty($componentData['component_alias'])){
			$insertData['component_alias'] = $componentData['component_alias'];
		}

		if(!empty($componentData['component_params'])){
			$insertData['component_params'] = $componentData['component_params'];
		}

		return $this->db->insert('zt_components', $insertData);
	}

	/**
	 * Umece podatke o komponenti i njezinim metodama.
	 *
	 * @param array $componentData - podaci o komponenti
	 * @param array array $componentMethodData - podaci o metodama komponente
	 * @return boolean - true ako je akcija uspjesno obavljena, inace false
	 */
	public function InsertComponentFullData($componentData, $componentMethodsData){
		$this->insertComponent($componentData);

		$componentId = mysql_insert_id();

		foreach ($componentMethodsData as $componentMethodData){
			$componentMethodData['component_id'] = $componentId;

			$this->InsertComponentMethod($componentMethodData);

			$componentMethodId = mysql_insert_id();
			$pageData = array(
					'page_name' => $componentData['component_name'] . " - " . $componentMethodData['component_method_alias'],
					'component_method_id' => $componentMethodId,
					'page_link' => null,
					'page_pattern' => $componentMethodData['page_pattern'],
					'page_route' => $componentMethodData['page_route'],
					'page_locked_by_component' => 1,
					'layout_id' => 2,
					'page_public' => 1
			);

			$this->PagesModel->InsertPage($pageData);

			if(!empty($componentMethodData['controller_page_pattern'])){
				$pageData = array(
					'page_name' => $componentData['component_name'] . " - " . $componentMethodData['component_method_alias'],
					'component_method_id' => $componentMethodId,
					'page_link' => null,
					'page_pattern' => $componentMethodData['controller_page_pattern'],
					'page_route' => $componentMethodData['page_route'],
					'page_locked_by_component' => 1,
					'layout_id' => $componentMethodData['layout_id'],
					'page_public' => 1
				);

				$this->PagesModel->InsertPage($pageData);
			}


		}


	}

	/**
	 * Azurira komponentu sa novim podacima
	 *
	 * @param array $componentData - novi podaci o komponenti
	 * @return boolean - true ako je akcija uspjela, inace false
	 */
	public function UpdateComponent($componentData){
		$updateData = array();

		if(!empty($componentData['component_name'])){
			$updateData['component_name'] = $componentData['component_name'];
		}

		if(!empty($componentData['component_description'])){
			$updateData['component_description'] = $componentData['component_description'];
		}

		if(!empty($componentData['component_type_id'])){
			$updateData['component_type_id'] = $componentData['component_type_id'];
		}

		if(!empty($componentData['component_alias'])){
			$updateData['component_alias'] = $componentData['component_alias'];
		}

		if(!empty($componentData['component_params'])){
			$updateData['component_params'] = $componentData['component_params'];
		}

		if(!empty($componentData['component_active'])){
			$updateData['component_active'] = $componentData['component_active'];
		}

		$this->db->where('component_id', $componentData['component_id']);
		return $this->db->update('zt_components', $updateData);

	}

	/**
	 * Briše zadanu komponentu
	 *
	 * @param int $componentId - identifikator koponente
	 * @return boolean - true ako je akcija uspjela, inace false
	 */
	public function DeleteComponent($componentId){
		$this->db->where('component_id', $componentId);
		return $this->db->delete('zt_components');
	}


	// ##################################
	// ###### COMPONENTS ##### END ######
	// ##################################


	// ###########################################
	// ###### COMPONENT METHODS ##### BEGIN ######
	// ###########################################

	/**
	 * Dohvaca imena metoda za zadanu insatncu komponente, sa originalnim
	 * nazivima metoda.
	 *
	 * @param int $componentId - identifikator komponente
	 */
	public function GetComponentMethods($componentId){
		$query = "
			SELECT
				*
			FROM
				zt_component_methods,
				zt_component_type_methods,
				zt_component_types
			WHERE
				component_id = ? AND
				zt_component_type_methods.component_type_method_id = zt_component_methods.component_type_method_id AND
				zt_component_types.component_type_id = zt_component_type_methods.component_type_id
			ORDER BY
				component_method_alias ASC;
		";

		return $this->db->query($query, $componentId)->result();
	}

	/**
	 * Azurira podatke o metodi za zadani identifikator metode
	 * Parametri: component_method_id, component_method_alias
	 *
	 * @param array $componentMethodData - podaci o metodama instance komponente
	 * @return boolean - true ako je akcija uspjela, false inace
	 */
	public function UpdateComponentMethod($componentMethodData){
		$updateData = array();

		if(!empty($componentMethodData['component_method_alias'])){
			$updateData['component_method_alias'] = $componentMethodData['component_method_alias'];
		}

		$this->db->where('component_method_id', $componentMethodData['component_method_id']);
		return $this->db->update('zt_component_methods', $updateData);
	}

	/**
	 * Umece novi zapis o metodi insence komponente.
	 *
	 * @param array $componentMethodData - podaci o metodi
	 * @return boolean - true ako je akcija uspjela, inace false
	 */
	public function InsertComponentMethod($componentMethodData){
		$insertData['component_method_id'] = null;

		if(!empty($componentMethodData['component_method_alias'])){
			$insertData['component_method_alias'] = $componentMethodData['component_method_alias'];
		}

		if(!empty($componentMethodData['component_type_method_id'])){
			$insertData['component_type_method_id'] = $componentMethodData['component_type_method_id'];
		}

		if(!empty($componentMethodData['component_id'])){
			$insertData['component_id'] = $componentMethodData['component_id'];
		}


		return $this->db->insert('zt_component_methods', $insertData);
	}

	// #########################################
	// ###### COMPONENT METHODS ##### END ######
	// #########################################


}

?>