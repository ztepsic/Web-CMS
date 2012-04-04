<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Razred koji implementira model stranica
 *
 * @package models.core
 *
 * @author Zeljko Tepsic <ztepsic@gmail.com>
 * @copyright ztepsic.com
 * @version 1.0.0
 * @since 2009-01-05
 *
 */
class Pages_model extends Model {

	public function __construct(){
		parent::Model();
	}


	// ###############################
	// ###### PAGES ##### BEGIN ######
	// ###############################

	/**
	 * Dohvaca stranicu u ovisnosti o predanom URI-u
	 *
	 * @param string $pageLink - URI stranice
	 * @return stdClass - stranicu
	 */
	public function GetPageByLink($pageLink){
		$query = "
			SELECT
				zt_pages.*,
				zt_layouts.layout_file
			FROM
				zt_pages
			LEFT OUTER JOIN
				zt_layouts
				ON
					zt_layouts.layout_id = zt_pages.layout_id
			WHERE
				? REGEXP zt_pages.page_pattern

			LIMIT 1;
		";


		return $this->db->query($query, $pageLink)->row();
	}

	/**
	 * Dohvaca stranicu za zadani identifikator
	 *
	 * @param int $pageId - odentifikator trazene stranice
	 * @return std_class - stranica
	 */
	public function GetPage($pageId){
		$query = "
			SELECT
				*
			FROM
				zt_pages
			WHERE
				page_id = ?
			LIMIT 1;
		";

		return $this->db->query($query, $pageId)->row();
	}

	/**
	 * Dohvaca stranicu sa linkom
	 *
	 * @param int $pageId - odentifikator trazene stranice
	 * @return std_class - stranica
	 */
	public function GetPageWithLink($pageId){
		$query = "
			SELECT
				zt_pages.*,
				CONCAT(component_alias, '/', component_method_alias) as page_link_generated
			FROM
				zt_pages,
				zt_component_methods,
				zt_components
			WHERE
				page_id = ? AND
				zt_component_methods.component_method_id =zt_pages.component_method_id AND
				zt_components.component_id = zt_component_methods.component_id
			LIMIT 1;
		";

		return $this->db->query($query, $pageId)->row();
	}

	/**
	 * Dohvaca sve stranice
	 *
	 * @param boolean $getAdminPages - dohvatiti i admin stranice
	 * @return std_class array - stranice
	 */
	public function GetPages($code = 0){
		if($code == 2){
			$query = "
				SELECT
					*
				FROM
					zt_pages
				WHERE
					page_public = 1
				ORDER BY
					page_name ASC
			";
		} elseif($code == 0) {
			$query = "
					SELECT
					*
				FROM
					zt_pages,
					zt_component_methods,
					zt_component_type_methods,
					zt_component_types
				WHERE
					page_public = 1 AND
					zt_pages.component_method_id = zt_component_methods.component_method_id AND
					zt_component_methods.component_type_method_id = zt_component_type_methods.component_type_method_id AND
					zt_component_type_methods.component_type_id = zt_component_types.component_type_id AND
					zt_component_types.component_type_admin = 0
				ORDER BY
					page_name ASC
			";
		} elseif($code == 1){
			$query = "
				SELECT
					*
				FROM
					zt_pages,
					zt_component_methods,
					zt_component_type_methods,
					zt_component_types
				WHERE
					page_public = 1 AND
					zt_pages.component_method_id = zt_component_methods.component_method_id AND
					zt_component_methods.component_type_method_id = zt_component_type_methods.component_type_method_id AND
					zt_component_type_methods.component_type_id = zt_component_types.component_type_id AND
					zt_component_types.component_type_admin = 1
				ORDER BY
					page_name ASC
			";
		}


		return $this->db->query($query)->result();
	}


	/**
	 * Umece novi zapis o stranici
	 *
	 * @param array $pageData - podaci o stranici
	 * @return boolean - true ako je akcija uspjela, inace false
	 */
	public function InsertPage($pageData){
		$insertData['page_id'] = null;

		if(!empty($pageData['page_name'])){
			$insertData['page_name'] = $pageData['page_name'];
		}

		if(!empty($pageData['page_link'])){
			$insertData['page_link'] = $pageData['page_link'];
		}

		if(!empty($pageData['component_method_id'])){
			$insertData['component_method_id'] = $pageData['component_method_id'];
		}

		if(!empty($pageData['page_pattern'])){
			$insertData['page_pattern'] = $pageData['page_pattern'];
		}

		if(!empty($pageData['page_route'])){
			$insertData['page_route'] = (string) $pageData['page_route'];
		}

		if(!empty($pageData['page_locked_by_component'])){
			$insertData['page_locked_by_component'] = $pageData['page_locked_by_component'];
		}

		if(!empty($pageData['layout_id'])){
			$insertData['layout_id'] = $pageData['layout_id'];
		}

		if(!empty($pageData['page_params'])){
			$insertData['page_params'] = $pageData['page_params'];
		}

		if(!empty($pageData['page_public'])){
			$insertData['page_public'] = $pageData['page_public'];
		}

		return $this->db->insert('zt_pages', $insertData);
	}

	/**
	 * Azurira podatke o stranici
	 *
	 * @param array $pageData - podaci o stranici
	 * @return boolean - true ako je akcija uspjela, inace false
	 */
	public function UpdatePage($pageData){
		$updateData = array();

		if(!empty($pageData['page_name'])){
			$updateData['page_name'] = $pageData['page_name'];
		}

		if(!empty($pageData['page_link'])){
			$updateData['page_link'] = $pageData['page_link'];
		}

		if(!empty($pageData['comoponent_method_id'])){
			$insertData['comoponent_method_id'] = $pageData['comoponent_method_id'];
		}

		if(!empty($pageData['page_pattern'])){
			$updateData['page_pattern'] = $pageData['page_pattern'];
		}

		if(!empty($pageData['page_route'])){
			$updateData['page_route'] = $pageData['page_route'];
		}

		if(!empty($pageData['page_locked_by_component'])){
			$updateData['page_locked_by_component'] = $pageData['page_locked_by_component'];
		}

		if(!empty($pageData['page_params'])){
			$updateData['page_params'] = $pageData['page_params'];
		}

		if(!empty($pageData['layout_id'])){
			$updateData['layout_id'] = $pageData['layout_id'];
		}

		if(!empty($pageData['page_public'])){
			$updateData['page_public'] = $pageData['page_public'];
		}

		$this->db->where('page_id', $pageData['page_id']);
		return $this->db->update('zt_pages', $updateData);

	}

	/**
	 * Brise stranicu
	 *
	 * @param int $pageId - identifikator stranice
	 * @return boolean - true ako je akcija uspjela, inace false
	 */
	public function DeletePage($pageId){
		$this->db->where('page_id', $pageId);
		return $this->db->delete('zt_pages');
	}


	// #############################
	// ###### PAGES ##### END ######
	// #############################


	// ###########################################
	// ###### PAGE MODULES GROUPS ##### BEGIN ######
	// ###########################################

	/**
	 * Dohvaca sve grupe modula koje se prikazuju na zadanoj stranici
	 *
	 * @param int $pageId - identifikator stranice
	 * @return std_class array - grupe modula
	 */
	public function GetPageModulesGroups($pageId){
		$query = "
			SELECT
				*
			FROM
				zt_page_modules_groups,
				zt_modules_groups
			WHERE
				page_id = ? AND
				zt_modules_groups.modules_group_id = zt_page_modules_groups.modules_group_id
			ORDER BY
				modules_group_name ASC
		";

		return $this->db->query($query, $pageId)->result();

	}

	/**
	 * Umece novi zapis o grupi modula za zadanu stranicu
	 *
	 * @param array $pageModulesGroupData - podaci za insert
	 * @return boolean - true ako je operacija uspjela, inace false
	 */
	public function InsertPageModulesGroup($pageModulesGroupData){
		$insertData['page_modules_group_id'] = null;

		if(!empty($pageModulesGroupData['page_id'])){
			$insertData['page_id'] = $pageModulesGroupData['page_id'];
		}

		if(!empty($pageModulesGroupData['modules_group_id'])){
			$insertData['modules_group_id'] = $pageModulesGroupData['modules_group_id'];
		}

		if(!empty($pageModulesGroupData['layout_position_id'])){
			$insertData['layout_position_id'] = $pageModulesGroupData['layout_position_id'];
		}

		if(!empty($pageModulesGroupData['page_modules_group_order'])){
			$insertData['page_modules_group_order'] = $pageModulesGroupData['page_modules_group_order'];
		}


		return $this->db->insert('zt_page_modules_groups', $insertData);
	}


	public function UpdatePageModulesGroup($pageModulesGroupData){
		$updateData = array();

		if(!empty($pageModulesGroupData['page_id'])){
			$updateData['page_id'] = $pageModulesGroupData['page_id'];
		}

		if(!empty($pageModulesGroupData['modules_group_id'])){
			$updateData['modules_group_id'] = $pageModulesGroupData['modules_group_id'];
		}

		if(!empty($pageModulesGroupData['layout_position_id'])){
			$updateData['layout_position_id'] = $pageModulesGroupData['layout_position_id'];
		}

		if(!empty($pageModulesGroupData['page_modules_group_order'])){
			$updateData['page_modules_group_order'] = $pageModulesGroupData['page_modules_group_order'];
		}

		$this->db->where('page_modules_group_id', $pageModulesGroupData['page_modules_group_id']);
		return $this->db->update('zt_page_modules_groups', $updateData);
	}

	/**
	 * Brise zapis o grupi modula sa stranice
	 *
	 * @param int $pageModulesGroupId - identifikator grupe modula za stranicu
	 * @return boolean - true ako je akcija supjela, inace false
	 */
	public function DeletePageModulesGroup($pageModulesGroupId){
		$this->db->where('page_modules_group_id', $pageModulesGroupId);
		return $this->db->delete('zt_page_modules_groups');
	}

	// ###########################################
	// ###### PAGE MODULES GROUPS ##### END ######
	// ###########################################




}

?>