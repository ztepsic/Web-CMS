<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Razred koji implementira model grupa modula
 *
 * @package models
 *
 * @author Zeljko Tepsic <ztepsic@gmail.com>
 * @copyright ztepsic.com
 * @version 1.0.0
 * @since 2008-12-22
 *
 */
class Modules_groups_model extends Model {

	public function __construct(){
		parent::Model();
	}

	// #######################################
	// ###### MODULES GROUPS ##### BEGIN #####
	// #######################################

	/**
	 * Stvara grupu modula
	 *
	 * @param string $modulesGroupName - naziv grupe modula
	 * @param boolean $modulesGroupPublished - zastavica da li je grupa modula objavljena
	 * @return bookean - true ako je akcija uspjesno obavljena, inace false
	 */
	public function InsertModulesGroup($modulesGroupName, $modulesGroupPublished){
		$insertData['modules_group_id'] = null;

		if(!empty($modulesGroupName)){
			$insertData['modules_group_name'] = $modulesGroupName;
		}

		if(!empty($modulesGroupPublished)){
			$insertData['modules_group_published'] = $modulesGroupPublished;
		}


		return $this->db->insert('zt_modules_groups', $insertData);
	}

	/**
	 * Azurira grupu modula sa pdoacima.
	 *
	 * @param int $modulesGroupId - identifikator grupe koji se azurira
	 * @param string $modulesGroupName - naziv grupe modula
	 * @param boolean $modulesGroupPublished - da li je grupa objavljena
	 * @return bookean - true ako je akcija uspjesno obavljena, inace false
	 */
	public function UpdateModulesGroup($modulesGroupId, $modulesGroupName, $modulesGroupPublished){
		$updateData = array();

		if(!empty($modulesGroupName)){
			$updateData['modules_group_name'] = $modulesGroupName;
		}

		if(isset($modulesGroupPublished)){
			$updateData['modules_group_published'] = $modulesGroupPublished;
		}

		$this->db->where('zt_modules_groups.modules_group_id', $modulesGroupId);
		return $this->db->update('zt_modules_groups', $updateData);
	}

	/**
	 * Brise grupu modula za zadani identifikator grupe
	 *
	 * @param int $modulesGroupId - identifikator grupe
	 * @return boolean - true ako je akcija uspjesno obavljena, inace false
	 */
	public function DeleteModulesGroup($modulesGroupId){
		$this->db->where('modules_group_id', $modulesGroupId);
		return $this->db->delete('zt_modules_groups');
	}


	/**
	 * Dohvaca grupu modula za zadani identifikator grupe.
	 *
	 * @param int $modulesGroupId - identifikator grupe
	 * @return std_class - grupa modula
	 */
	public function GetModulesGroup($modulesGroupId){
		$query = "
			SELECT
				*
			FROM
				zt_modules_groups
			WHERE
				modules_group_id = ?
			LIMIT 1;
		";

		return $this->db->query($query, $modulesGroupId)->row();
	}

	/**
	 * Dohvaca sve grupe modula sortirano uzlazno po abecedi
	 *
	 * @return std_class array - grupa modula
	 */
	public function GetModulesGroups(){
		$query = "
			SELECT
				*
			FROM
				zt_modules_groups
			ORDER BY
				modules_group_name
		";

		return $this->db->query($query)->result();
	}

	// #####################################
	// ###### MODULES GROUPS ##### END #####
	// #####################################


	// ############################################
	// ###### MODULES GROUP ITEMS ##### BEGIN #####
	// ############################################

	/**
	 * Dohvaca sve elemente neke grupe
	 *
	 * @param int $modulesGroupId - identifikator grupe
	 * @param boolean $selectJustModulesGroups - true selektiraj samo gurpe modula, false selektiraj samo module
	 * @return std_class array - elementi grupe modula
	 */
	public function GetModulesGroupItems($modulesGroupId, $selectJustModulesGroups = false){
		if(!$selectJustModulesGroups){
			$query = "
			SELECT
				*
			FROM
				zt_modules_group_items
			WHERE
				modules_group_id = ? AND
				module_id IS NOT NULL
			";

		} else {
			$query = "
			SELECT
				*
			FROM
				zt_modules_group_items
			WHERE
				modules_group_id = ? AND
				module_id IS NULL
			";

		}


		return $this->db->query($query, $modulesGroupId)->result();
	}


	/**
	 * Dodaje novi element grupe modula
	 *
	 * @param int $modulesGroupId - identifikator grupe modula u koju se dodaje element grupe
	 * @param int $modulesGroupIdElement - identifikator grupe modula koja je element grupe modula
	 * @param int $moduleId - identifikator modula
	 * @param int $modulesGroupItemOrder - redoslijed elemenata grupe modula
	 * @return boolean - true ako je akcija uspjela, inace false
	 */
	public function InsertGroupItem($modulesGroupId, $modulesGroupIdElement, $moduleId = null, $modulesGroupItemOrder){
		$insertData['modules_group_item_id'] = null;

		if(!empty($modulesGroupId)){
			$insertData['modules_group_id'] = $modulesGroupId;
		}

		if(!empty($modulesGroupIdElement)){
			$insertData['modules_group_id_element'] = $modulesGroupIdElement;
		}

		if(!empty($moduleId)){
			$insertData['module_id'] = $moduleId;
		}

		if(!empty($modulesGroupItemOrder)){
			$insertData['modules_group_item_order'] = $modulesGroupItemOrder;
		}


		return $this->db->insert('zt_modules_group_items', $insertData);
	}

	/**
	 * Azurira element grupe modula.
	 * To se svodi samo na promjenu redoslijeda elementa
	 *
	 * @param int $modulesGroupItemId - identifiaktor grupe modula
	 * @param int $modulesGroupItemOrder - redoslijed elementa
	 * @return boolean - true ako je akcija uspjela, false inace
	 */
	public function UpdateGroupItem($modulesGroupItemId, $modulesGroupItemOrder){
		$updateData = array();

		if(!empty($modulesGroupItemOrder)){
			$updateData['modules_group_item_order'] = $modulesGroupItemOrder;
		}

		$this->db->where('modules_group_item_id', $modulesGroupItemId);
		return $this->db->update('zt_modules_group_items', $updateData);

	}

	/**
	 * Brise element groupe modula iz grupe modula
	 *
	 * @param int $modulesGroupItemId - identifikator elementa grupe modula
	 * @return boolean - true ako je akcija uspjela, inace false
	 */
	public function DeleteGroupItem($modulesGroupItemId){
		$this->db->where('modules_group_item_id', $modulesGroupItemId);
		return $this->db->delete('zt_modules_group_items');
	}

	// ##########################################
	// ###### MODULES GROUP ITEMS ##### END #####
	// ##########################################


}

?>