<?php


class Modules extends ZT_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model("core/extensions/modules_groups_model", "ModulesGroupsModel");
		$this->load->model("core/extensions/modules_model", "ModulesModel");


		$this->load->library('core/auth/authentication');
		$this->load->library('core/auth/authorization');

		$this->load->library('form_validation');

	}

	/**
	 * Prikaz svih grupa modula
	 *
	 */
	public function index(){
				$hasPermission = $this->authorization->CheckPermission('read', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}


		$modulesGroups = $this->ModulesGroupsModel->GetModulesGroups();

		$data['modulesGroups'] = $modulesGroups;

		$renderData['main_column'] = $this->load->view("core/admin/modules/modules_groups_list_view", $data, true);
		$head->title = "Grupe modula";
		$head->meta = "";
		$renderData['head'] = $head;
		$this->render($renderData);
	}

	/**
	 * Stvaranje nove grupe modula
	 *
	 */
	public function create(){
				$hasPermission = $this->authorization->CheckPermission('create', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}

		if($this->input->post('cancel')){
			redirect('admin/modules');
		}

		$isValidData = $this->modulesGroupDataValidation();
		if($isValidData){
			$modulesGroupName = $this->input->post('modules_group_name');
			$modulesGroupPublished = $this->input->post('modules_group_published');

			$this->ModulesGroupsModel->InsertModulesGroup($modulesGroupName, $modulesGroupPublished);

			redirect('admin/modules/');
		} else {
			$renderData['main_column'] = $this->load->view("core/admin/modules/modules_groups_create_view", null, true);
			$head->title = "Stvaranje grupe modula";
			$head->meta = "";
			$renderData['head'] = $head;
			$this->render($renderData);
		}
	}



	/**
	 * Azuriranje postojecih podataka o grupi modula
	 *
	 */
	public function edit($modulesGroupId){
				$hasPermission = $this->authorization->CheckPermission('update', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}


		if($this->input->post('cancel')){
			redirect('admin/modules');
		}

		$isValidData = $this->modulesGroupDataValidation();
		if($isValidData){
			$modulesGroupName = $this->input->post('modules_group_name');

			if($this->input->post('modules_group_published')){
				$modulesGroupPublished = 1;
			} else {
				$modulesGroupPublished = 0;
			}


			$result = $this->ModulesGroupsModel->UpdateModulesGroup($modulesGroupId, $modulesGroupName, $modulesGroupPublished);
						if(!$result){
							echo "greska";
							exit;
						}

			redirect('admin/modules/');
		} else {
			$modulesGroup = $this->ModulesGroupsModel->GetModulesGroup($modulesGroupId);
			$data['currentModulesGroup'] = $modulesGroup;

			$renderData['main_column'] = $this->load->view("core/admin/modules/modules_groups_edit_view", $data, true);
			$head->title = "Uređivanje grupe modula";
			$head->meta = "";
			$renderData['head'] = $head;
			$this->render($renderData);
		}

	}



	/**
	 * Validacija podataka o grupi modula
	 *
	 * @return boolean - true ako je validacija uspjela, false inace
	 */
	private function modulesGroupDataValidation(){
		$this->form_validation->set_rules('modules_group_name', 'Naziv grupe modula', 'trim|required');

		if ($this->form_validation->run()) {
			return true;
		} else {
			return false;
		}

	}



	/**
	 * Brisanje grupe modula
	 *
	 * @param int $modulesGroupId - identifikator grupe modula
	 */
	public function delete($modulesGroupId = 0){
		if($modulesGroupId == 0){
			$this->index();
		} else {
			$done = $this->ModulesGroupsModel->DeleteModulesGroup($modulesGroupId);
			if($done){
				echo "brisanje uspjelo";
			} else {
				echo "birsanje nije uspjelo";
			}
		}

	}


	/**
	 * Uredivanje elemenata grupe modula
	 *
	 * @param int $modulesGroupId - identifikator grupe modula
	 */
	public function groupitems($modulesGroupId) {
				$hasPermission = $this->authorization->CheckPermission('read', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}


		if($this->input->post('cancel')){
			redirect('admin/modules');
		}


		if(!empty($_POST)){
			$this->updateModulesGroupItemsData($modulesGroupId);

			redirect('admin/modules/');
		} else {
			$modules = $this->ModulesModel->GetModules();
			$data['modules'] = $modules;

			$currentModuleItems = $this->ModulesGroupsModel->GetModulesGroupItems($modulesGroupId, false);
			$data['currentModuleItems'] = $currentModuleItems;

			$currentModulesGroupItems = $this->ModulesGroupsModel->GetModulesGroupItems($modulesGroupId, true);
			$data['currentModulesGroupItems'] = $currentModulesGroupItems;

			$data['modulesGroupId'] = $modulesGroupId;

			$renderData['main_column'] = 			$this->load->view('core/admin/modules/modules_group_items_edit_view', $data, true);
			$head->title = "Uređivanje elemenata grupe modula";
			$head->meta = "";
			$renderData['head'] = $head;
			$this->render($renderData);
		}


	}


	private function updateModulesGroupItemsData($modulesGroupId){
		$modules = $this->input->post('modules');
		if(empty($modules)){
			$modules = array();
		}

		$currentModuleItems = $this->ModulesGroupsModel->GetModulesGroupItems($modulesGroupId, false);

		// za svaki element iz trenutnih elemenata modula pogledaj da li se navedeni
		// identifikator modula nalazi u odabranim identifikatorima modula
		// ukoliko se ne nalazi onda ga obrisi
		// sve identifikatore stavljaj u polje
		$selectedModulesIds = array();
		foreach ($currentModuleItems as $currentModuleItem){
			$selectedModulesIds[] = $currentModuleItem->module_id;
			if(!in_array($currentModuleItem->module_id, $modules)){
				$this->ModulesGroupsModel->DeleteGroupItem($currentModuleItem->modules_group_item_id);
			}
		}


		// izracunaj identifikatore koji se ne nalaze u trenutnim elementima modula
		// te njih upisi u tablicu
		$insertModulesIds = array_diff($modules, $selectedModulesIds);
		foreach ($insertModulesIds as $insertModuleId){
			$this->ModulesGroupsModel->InsertGroupItem($modulesGroupId, null, $insertModuleId, 1);
		}





	}





}

?>