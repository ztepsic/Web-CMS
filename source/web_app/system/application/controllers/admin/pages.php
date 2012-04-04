<?php


class Pages extends ZT_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('core/pages_model', "PagesModel");
		$this->load->model('core/extensions/modules_groups_model', "ModulesGroupsModel");
		$this->load->model('core/layouts_model', "LayoutsModel");

		$this->load->model('core/auth/roles_model', "RolesModel");

		$this->load->library('core/auth/authentication');
		$this->load->library('core/auth/authorization');

		$this->load->library('form_validation');
	}


	/**
	 * Prikaz svih stranica
	 *
	 */
	public function index(){
				$hasPermission = $this->authorization->CheckPermission('read', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}


		$code = $this->uri->segment(3);
		if($code == 1){
			$data['pages'] = $this->PagesModel->GetPages(1);
		} elseif($code == 2) {
			$data['pages'] = $this->PagesModel->GetPages(2);
		} else {
			$data['pages'] = $this->PagesModel->GetPages(0);
		}



		$renderData['main_column'] = $this->load->view('core/admin/pages/pages_list_view', $data, true);
		$head->title = "Stranice";
		$head->meta = "";
		$renderData['head'] = $head;
		$this->render($renderData);
	}


	/**
	 * Prikaz stranice za postavljanje modula
	 *
	 * @param unknown_type $pageId
	 */
	public function page_modules($pageId){
		$hasPermission = $this->authorization->CheckPermission('read', 'zt_pages', $this->pageId) &&
			$this->authorization->CheckPermission('update', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}


		if($this->input->post('cancel')){
			redirect('admin/pages/');
		}

		$page = $this->PagesModel->GetPage($pageId);

		$modulesGroups = $this->ModulesGroupsModel->GetModulesGroups();
		$pageModulesGroups = $this->PagesModel->GetPageModulesGroups($pageId);

		//$isValidData = $this->componentDataValidation();
		if(!empty($_POST)){
			if($this->input->post('modules_group_ids')){
				$selectedModulesGroups = $this->input->post('modules_group_ids');
			} else {
				$selectedModulesGroups = array();
			}

			$pageModulesGroupsIds = array();
			foreach($pageModulesGroups as $pageModulesGroup){
				$pageModulesGroupsIds [] = $pageModulesGroup->modules_group_id;
			}

			$deletePageModulesGroupIds = array_diff($pageModulesGroupsIds, $selectedModulesGroups);
			foreach($deletePageModulesGroupIds as $deletePageModulesGroupId){
				$this->PagesModel->DeletePageModulesGroup(null, $pageId, $deletePageModulesGroupId);
			}

			$insertPageModulesGroupIds = array_diff($selectedModulesGroups, $pageModulesGroupsIds);
			foreach($deletePageModulesGroupIds as $deletePageModulesGroupId){
				$pageModulesGroupId = $this->input->post('page_module_group_' . $deletePageModulesGroupId);
				$this->PagesModel->DeletePageModulesGroup($pageModulesGroupId);
			}

			$insertPageModulesGroupIds = array_diff($selectedModulesGroups, $pageModulesGroupsIds);
			foreach($insertPageModulesGroupIds as $insertPageModulesGroupId){
				$pageModulesGroupData = array(
					'page_id' => $pageId,
					'modules_group_id' => $insertPageModulesGroupId,
					'layout_position_id' => $this->input->post('layout_position_id_' . $insertPageModulesGroupId),
					'page_modules_group_order' => $this->input->post('page_modules_group_odrer_' . $insertPageModulesGroupId)
				);

				$this->PagesModel->InsertPageModulesGroup($pageModulesGroupData);
			}

			$updatePageModulesGroupIds = array_intersect($selectedModulesGroups, $pageModulesGroupsIds);
			foreach($updatePageModulesGroupIds as $updatePageModulesGroupId){
				$pageModulesGroupData = array(
					'page_modules_group_id' => $this->input->post('page_module_group_' . $updatePageModulesGroupId),
					'layout_position_id' => $this->input->post('layout_position_id_' . $updatePageModulesGroupId),
					'page_modules_group_order' => $this->input->post('page_modules_group_odrer_' . $updatePageModulesGroupId)
				);

				$this->PagesModel->UpdatePageModulesGroup($pageModulesGroupData);
			}

			redirect('admin/pages/');
		} else {
			$data['pageId'] = $pageId;
			$data['modulesGroups'] = $modulesGroups;
			$data['pageModulesGroups'] = $pageModulesGroups;

			$data['layoutPositions'] = $this->LayoutsModel->GetPositions($page->layout_id);

			$renderData['main_column'] = $this->load->view('core/admin/pages/page_modules_view', $data, true);
			$head->title = "Moduli na stranici";
			$head->meta = "";
			$renderData['head'] = $head;
			$this->render($renderData);
		}

	}

	/**
	 * Dodjela dozvola ulogama za zadanu stranicu
	 *
	 * @param int $pageId - identifikator stranice
	 */
	public function page_permissions($pageId){
		$hasPermission = $this->authorization->CheckPermission('read', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}


		if($this->input->post('cancel')){
			redirect('admin/pages/');
		}

		$roles = $this->RolesModel->GetFullTree();

		$requestData = array(
				'requested_db_table' => 'zt_pages',
				'requested_foreign_key' => $pageId
			);
		$rolePermissions = $this->RolesModel->GetRolePermissions($requestData);

		//$isValidData = $this->componentDataValidation();
		if(!empty($_POST)){
			foreach ($roles as $role){

				$postActionIds = array();
				if($this->input->post('actions_' . $role->role_id)){
					$postActionIds = $this->input->post('actions_' . $role->role_id);
				}

				$actionIds = $this->getActions($role->role_id, $rolePermissions);

				$deleteActions = array_diff($actionIds, $postActionIds);

				foreach ($deleteActions as $deleteAction) {
					$params['role_id'] = $role->role_id;
					$params['action_id'] = $deleteAction;
					$params['requested_db_table'] = "zt_pages";
					$params['requested_foreign_key'] = $pageId;
					$this->RolesModel->DeleteRolePermission(null, $params);
					print_r($params);
				}


				$insertActions = array_diff($postActionIds, $actionIds);
				foreach ($insertActions as $insertAction) {
					$rolePermissionData = array(
						'role_id' => $role->role_id,
						'action_id' => $insertAction,
						'requested_db_table' => "zt_pages",
						'requested_foreign_key' => $pageId
					);

					$this->RolesModel->InsertRolePermission($rolePermissionData);
				}

			}


			redirect('admin/pages/');
		} else {
			$data['actions'] = $this->RolesModel->GetActions();
			$data['roles'] = $roles;

			$data['rolePermissions'] = $rolePermissions;
			$data['pageId'] = $pageId;

			$renderData['main_column'] = $this->load->view('core/admin/pages/page_permissions_view', $data, true);
			$head->title = "Dozvole za stranicu";
			$head->meta = "";
			$renderData['head'] = $head;
			$this->render($renderData);

		}
	}

	/**
	 * Vraca polje akcija za ulogu
	 *
	 * @param unknown_type $roleId
	 * @param unknown_type $rolePermissions
	 * @return unknown
	 */
	private function getActions($roleId, $rolePermissions){
		$actions = array();
		foreach ($rolePermissions as $rolePermission){
			if($rolePermission->role_id == $roleId){
				$actions[] = $rolePermission->action_id;
			}
		}

		return $actions;
	}


}

?>