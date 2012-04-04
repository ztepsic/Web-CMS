<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'models/core/hierarchy_model.php');

/**
 * Razred koji implementira model uloga u ztepsic CMS-u
 *
 * @package models.auth
 *
 * @author Zeljko Tepsic <ztepsic@gmail.com>
 * @copyright ztepsic.com
 * @version 1.0.0
 * @since 2008-12-11
 *
 */
class Roles_model extends Hierarchy_model {

	/**
	 * Konstruktor
	 *
	 */
	public function __construct(){
		parent::__construct();

		// postavljanje parametara koje zahtjeva Hiearchy_model
		$this->SetTableData(
			"zt_roles",
			"role_lft",
			"role_rgt",
			"role_id",
			"role_parent_id",
			"role_name"
		);
	}


	// ###############################
	// ###### ROLES ##### BEGIN ######
	// ###############################

	/**
	 * Dohvaca ulogu za zadani identifikator
	 *
	 * @param int $roleId - identifikator uloge
	 * @return std_class - podaci o ulozi
	 */
	public function GetRole($roleId){
		$query = "
			SELECT
				*
			FROM
				zt_roles
			WHERE
				role_id = ?
			LIMIT 1;
		";

		return $this->db->query($query, $roleId)->row();

	}


	/**
	 * Stvara novu ulogu
	 *
	 * @param string $roleName - naziv uloge
	 * @param string $roleDescription - opis uloge
	 * @param int $roleParentId - identifikator roditelja
	 * @return boolean - true ako je akcija uspjela, inace false
	 */
	public function InsertRole($roleName, $roleDescription, $roleParentId){
		$insertData['role_id'] = null;

		if(!empty($roleName)){
			$insertData['role_name'] = $roleName;
		}

		if(!empty($roleDescription)){
			$insertData['role_description'] = $roleDescription;
		}

		if(!empty($roleParentId)){
			$insertData['role_parent_id'] = $roleParentId;
		}


		$result = $this->db->insert('zt_roles', $insertData);

		if($result){
			$this->rebuild();
			return true;
		} else {
			return false;
		}

	}

	/**
	 * Azurira ulogu, ukoliko nije zakljucana.
	 *
	 * @param string $roleName - naziv uloge
	 * @param string $roleDescription - opis uloge
	 * @param int $roleParentId - identifikator roditelja
	 * @return boolean - true ako je akcija uspjela, inace false
	 */
	public function UpdateRole($roleId, $roleName, $roleDescription, $roleParentId){
		$oldRole = $this->GetNode($roleId);
		$parentRole = $this->GetNode($roleParentId);

		if($oldRole->depth < $parentRole->depth){
			$parentRole->role_parent_id = $oldRole->role_parent_id;
			$query = "
				UPDATE
					zt_roles
				SET
					role_name = ?,
					role_description = ?,
					role_parent_id = ?
				WHERE
					role_id = ?  AND
					role_locked = 0
				LIMIT 1 ;

			";

			$params = array(
				$parentRole->role_name,
				$parentRole->role_description,
				$parentRole->role_parent_id,
				$parentRole->role_id
			);

			$this->db->query($query, $params);
		}

		$query = "
			UPDATE
				zt_roles
			SET
				role_name = ?,
				role_description = ?,
				role_parent_id = ?
			WHERE
				role_id = ?  AND
				role_locked = 0
			LIMIT 1 ;

		";

		$params = array(
			$roleName,
			$roleDescription,
			$roleParentId,
			$roleId
		);

		$result = $this->db->query($query, $params);

		if($result){
			$this->rebuild();
			return true;
		} else {
			return false;
		}

	}

	/**
	 * Brise ulogu, ukoliko nije zakljucana
	 *
	 * @param int $roleId - identifikator uloge
	 * @return boolean - true ako je akcija uspjela, inace false
	 */
	public function DeleteRole($roleId){
		$query = "
			DELETE FROM zt_roles WHERE role_id = ? AND role_locked = 0 LIMIT 1
		";

		$result = $this->db->query($query, $roleId);

		if($result){
			$this->rebuild();
			return true;
		} else {
			return false;
		}
	}


	/**
	 * Dohvaca uloge za korisnika (role_id, role_name)
	 *
	 * @param int $userId - identifikator korisnika
	 * @return std_class - uloge korisnika (role_id, role_name)
	 */
	public function GetUserRoles($userId){
		$query = "
			SELECT
				zt_roles.role_id,
				zt_roles.role_name
			FROM
				zt_roles,
				zt_roles_assignment
			WHERE
				zt_roles_assignment.role_member_db_table = 'zt_users' AND
				zt_roles_assignment.role_member_foreign_key = ? AND
				zt_roles.role_id = zt_roles_assignment.role_id;
		";

		return $this->db->query($query, $userId)->result();
	}


	/**
	 * Ponovno stvara nested-set hijerarhiju
	 * Potrebno radit poslije bilo kakve promjene vezane za doavanje, brisanje cvora
	 * ili azuriranje roditelja.
	 *
	 */
	private function rebuild(){
		$roots = $this->GetRoot();
		$left = 1;
		foreach($roots as $root){
			$left = $this->RebuildTree($root->role_id, $left);
		}
	}

	// #############################
	// ###### ROLES ##### END ######
	// #############################


	// ##########################################
	// ###### ROLES ASSIGNMENT ##### BEGIN ######
	// ##########################################


	/**
	 * Dodjeljuje ulogu nekom entitetu
	 *
	 * @param array $params
	 * @return boolean - true ako je operacija uspjela, false inace
	 */
	public function AddAssignment($params){
		$insertQueryPart = '';
		$valuesQueryPart = '';
		$counterParams = 0;
		foreach ($params as $key => $param) {
			if($key == 'role_id'){
				$insertQueryPart .= "role_id";
				$valuesQueryPart .= "?";
			} elseif($key == 'role_member_alias'){
				$insertQueryPart .= "role_member_alias";
				$valuesQueryPart .= "?";
			} elseif($key == 'role_member_db_table'){
				$insertQueryPart .= "role_member_db_table";
				$valuesQueryPart .= "?";
			} elseif($key == 'role_member_foreign_key'){
				$insertQueryPart .= "role_member_foreign_key";
				$valuesQueryPart .= "?";
			}

			if($counterParams+1 < sizeof($params)) {
				$insertQueryPart .= ", ";
				$valuesQueryPart .= ", ";
			}

			$counterParams++;
		}

		$query = "
		INSERT INTO zt_roles_assignment (
			role_assignment_id,
			"	.	$insertQueryPart	.	"
			)
			VALUES (
			NULL ,
			"	.	$valuesQueryPart	.	"
			);
		";

		return $this->db->query($query, $params);

	}

	/**
	 * Brise ulogu dodjeljenu nekom entitetu
	 *
	 * @param array $params
	 * @return boolean - true ako je operacija uspjela, false inace
	 */
	public function RemoveAssignment($params){
		$whereQueryPart = '';
		$counterParams = 0;
		foreach ($params as $key => $param) {
			if($key == 'role_member_db_table'){
				$whereQueryPart .= "role_member_db_table = ? ";
			} elseif($key == 'role_member_foreign_key'){
				$whereQueryPart .= "role_member_foreign_key = ? ";
			} elseif($key == 'role_member_alias'){
				$whereQueryPart .= "role_member_alias= ? ";
			} elseif($key == 'role_id'){
				$whereQueryPart .= "role_id = ? ";
			}

			if($counterParams+1 < sizeof($params)) {
				$whereQueryPart .= " AND ";
			}

			$counterParams++;

		}

		$query = "
			DELETE FROM
				zt_roles_assignment
			WHERE
			"	.	$whereQueryPart	.	"
		";

		return $this->db->query($query, $params);

	}

	// ##########################################
	// ###### ROLES ASSIGNMENT ##### END ########
	// ##########################################


	// #################################
	// ###### ACTIONS ##### BEGIN ######
	// #################################

	/**
	 * Dohvaca akcije
	 *
	 * @return std_class - akcije
	 */
	public function GetActions(){
		$query = "
			SELECT
				*
			FROM
				zt_actions;
		";

		return $this->db->query($query)->result();
	}


	// ###############################
	// ###### ACTIONS ##### END ######
	// ###############################


	// #########################################
	// ###### ROLE PERMISSION ##### BEGIN ######
	// #########################################

	/**
	 * Dohvaca sve dozvole
	 *
	 * @param array - parametri koji odreduju entitet nad kojim se dohvacaju dozvole
	 * @return std_class array - dozvole
	 */
	public function GetRolePermissions($requestData){
		$whereString = '';
		if(!empty($requestData['requested_db_table']) &&
		 !empty($requestData['requested_foreign_key'])){
			$whereString = "requested_db_table = ?";
			$whereString .= " AND requested_foreign_key = ?";
			$params = array(
				$requestData['requested_db_table'],
				$requestData['requested_foreign_key']
			);
		 } elseif(!empty($requestData['requested_object_alias'])){
			$whereString = "requested_object_alias = ?";
			$params = array(
				$requestData['requested_object_alias']
			);
		 } else {
		 	throw new Exception("Greska: parametri nisu ispravni");
		 }

		$query = "
			SELECT
				*
			FROM
				zt_role_permissions
			WHERE
			" .	$whereString	. "
		";

		return $this->db->query($query, $params)->result();
	}

	/**
	 * Pridruzuje dozvolu ulozi za obavljanje akcije nad nekim entitetom/objektom
	 *
	 * @param string array $rolePermissionData - parametri koji odgovraju tablici zt_role_permission
	 */
	public function InsertRolePermission($rolePermissionData){
		$insertData['role_permission_id'] = null;

		if(!empty($rolePermissionData['role_id'])){
			$insertData['role_id'] = $rolePermissionData['role_id'];
		}

		if(!empty($rolePermissionData['action_id'])){
			$insertData['action_id'] = $rolePermissionData['action_id'];
		}

		if(!empty($rolePermissionData['requested_object_alias'])){
			$insertData['requested_object_alias'] = $rolePermissionData['requested_object_alias'];
		}

		if(!empty($rolePermissionData['requested_db_table'])){
			$insertData['requested_db_table'] = $rolePermissionData['requested_db_table'];
		}

		if(!empty($rolePermissionData['requested_foreign_key'])){
			$insertData['requested_foreign_key'] = $rolePermissionData['requested_foreign_key'];
		}


		return $this->db->insert('zt_role_permissions', $insertData);

	}

	/**
	 * Brise dozvolu
	 *
	 * @param int $rolePermissionId - identifikator dozvole
	 * @param int $roleId - identifikator uloge
	 * @param int $actionId - identifikator akcije
	 * @return boolean - true ako je akcija uspjela, inace false
	 */
	public function DeleteRolePermission($rolePermissionId, $params){
		if(!empty($rolePermissionId)){
			$this->db->where('role_permission_id', $rolePermissionId);
		} else {
			$this->db->where('role_id', $params['role_id']);
			$this->db->where('action_id', $params['action_id']);
			$this->db->where('requested_db_table', $params['requested_db_table']);
			$this->db->where('requested_foreign_key', $params['requested_foreign_key']);
		}

		return $this->db->delete('zt_role_permissions');
	}

	/**
	 * Odreduje da li zadani entitet ima pravo obavljanja akcije nad trazenim entiteom.
	 * Struktura elemenata u polju parametara:
	 * action, memberAlias, memberTable, memberId, requestedAlias, requestedTable, requestedId
	 *
	 * @param string array $params
	 * @return boolean - true ako ima, false inace
	 */
	public function HasPermission($params){
		$assignmentQuery = '';
		if(!empty($params['memberAlias']) && empty($params['memberTable'])){
			$assignmentQuery = "
				zt_roles_assignment.role_member_alias = '" . $params['memberAlias'] . "' AND
			";
		} else if(empty($params['memberAlias'])) {
			$assignmentQuery = "
				zt_roles_assignment.role_member_foreign_key = " . $params['memberId'] . " AND
				zt_roles_assignment.role_member_db_table = '" . $params['memberTable'] . "' AND
			";
		} else {
			$assignmentQuery = "
				(
					(
						zt_roles_assignment.role_member_foreign_key = " . $params['memberId'] . " AND
						zt_roles_assignment.role_member_db_table = '" . $params['memberTable'] . "'
					) OR
					(
					zt_roles_assignment.role_member_alias = '" . $params['memberAlias'] . "'
					)
				) AND
			";

		}

		$permissionObjectQuery = '';
		if(!empty($params['requestedObject'])){
			$permissionObjectQuery = "
				zt_role_permissions.requested_object_alias = '" . $params['requestedAlias'] . "' AND
			";
		} else {
			$permissionObjectQuery = "
				zt_role_permissions.requested_db_table = '" . $params['requestedTable'] . "' AND
				zt_role_permissions.requested_foreign_key = " . $params['requestedId'] . " AND
			";
		}


		$query = "
			SELECT
				COUNT(DISTINCT(zt_role_permissions.role_permission_id)) AS permission
			FROM
				zt_roles_assignment,
				zt_roles AS node,
				zt_roles AS parent,
				zt_role_permissions,
				zt_actions
			WHERE
			"	. $assignmentQuery . "
				node.role_lft BETWEEN parent.role_lft AND parent.role_rgt AND
				zt_roles_assignment.role_id = node.role_id AND
				zt_role_permissions.role_id = zt_roles_assignment.role_id AND
			"	. $permissionObjectQuery . "
				zt_actions.action_id = zt_role_permissions.action_id AND
				zt_actions.action_alias = ?
			GROUP BY
				zt_role_permissions.role_permission_id;
		";

		return $this->db->query($query, $params['action'])->row();

	}


	/**
	 * Odreduje da li korisnik ima pravo obavljanja akcije nad trazenim entiteom.
	 * Struktura elemenata u polju parametara:
	 *
	 * @param int $userId - identifikator korisnika
	 * @param string $action - akcija
	 * @param string $requestedTable - tablica za trazeni entitet
	 * @param string $requestedId - id trazenog entiteta
	 * @return boolean - true ako ima, false inace
	 */
	public function HasUserPermission($userId, $action, $requestedTable, $requestedId){
		if($userId == 0){
			$params['memberAlias'] = 'guest';
		} else {
			// ako gost ima pristup onda ce imat i reg korisnik bez eksplicitne
			// dozvole na ulogu
			$params['memberTable'] = 'zt_users';
			$params['memberId'] = $userId;
			$params['memberAlias'] = 'guest';
		}
		$params['action'] = $action;

		$params['requestedTable'] = $requestedTable;
		$params['requestedId'] = $requestedId;

		return $this->HasPermission($params);
	}

	// #########################################
	// ###### ROLE PERMISSION ##### END ######
	// #########################################

}

?>