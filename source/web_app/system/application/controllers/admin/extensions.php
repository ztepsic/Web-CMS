<?php


class Extensions extends ZT_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('core/extensions/component_types_model', "ComponentTypesModel");
		$this->load->model('core/extensions/module_types_model', "ModuleTypesModel");
		$this->load->model('core/extensions/components_model', "ComponentsModel");

		$this->load->model('core/extensions/module_types_model', "ModuleTypesModel");
		$this->load->model('core/extensions/modules_model', "ModulesModel");

		$this->load->model('core/layouts_model', "LayoutsModel");

		$this->load->library('core/auth/authentication');
		$this->load->library('core/auth/authorization');

		$this->load->library('form_validation');
	}


	/**
	 * Prikaz svih tipova komponenata i modula
	 *
	 */
	public function index(){

	}


	/**
	 * Prikaz tipova komponenti
	 *
	 */
	public function componenttypes(){
		$hasPermission = $this->authorization->CheckPermission('read', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}

		$componentTypes = $this->ComponentTypesModel->GetComponentTypes();

		$data['componentTypes'] = $componentTypes;

		$renderData['main_column'] = $this->load->view('core/admin/extensions/component_types_list_view', $data, true);
		$head->title = "Tipovi komponenata";
		$head->meta = "";
		$renderData['head'] = $head;
		$this->render($renderData);

	}

	public function componenttype_delete($componentTypeId){
		$hasPermission = $this->authorization->CheckPermission('delete', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}

		$this->ComponentTypesModel->DeleteComponentType($componentTypeId);
		redirect("admin/extensions/componenttypes");
	}


	/**
	 * Prikaz tipova modula
	 *
	 */
	public function moduletypes(){
		$hasPermission = $this->authorization->CheckPermission('read', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}

		$moduleTypes = $this->ModuleTypesModel->GetModuleTypes();

		$data['moduleTypes'] = $moduleTypes;

		$renderData['main_column'] = $this->load->view('core/admin/extensions/module_types_list_view', $data, true);
		$head->title = "Tipovi modula";
		$head->meta = "";
		$renderData['head'] = $head;
		$this->render($renderData);
	}

	public function moduletype_delete($moduleTypeId){
		$hasPermission = $this->authorization->CheckPermission('delete', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}

		$this->ModuleTypesModel->DeleteModuleType($moduleTypeId);
		redirect("admin/extensions/moduletypes");
	}


	/**
	 * Prikaz instanci komponenata
	 *
	 */
	public function components(){
		$hasPermission = $this->authorization->CheckPermission('read', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}

		$components = $this->ComponentsModel->GetComponents();

		$data['components'] = $components;

		$renderData['main_column'] = $this->load->view('core/admin/extensions/components_list_view', $data, true);
		$head->title = "Komponente";
		$head->meta = "";
		$renderData['head'] = $head;
		$this->render($renderData);
	}


	/**
	 * Validacija podataka o komponenti
	 *
	 * @return boolean - true ako je validacija uspjela, false inace
	 */
	private function componentDataValidation(){
		$this->form_validation->set_rules('component_name', 'Naziv komponente', 'trim|required|xss_clean');
		$this->form_validation->set_rules('component_description', 'Opis komponente', 'trim|required|xss_clean');
		$this->form_validation->set_rules('component_alias', 'Alias komponente', 'trim|required|alpha_dash|xss_clean');

		if ($this->form_validation->run()) {
			return true;
		} else {
			return false;
		}

	}

	/**
	 * Prikaz stranice za stvaranje nove instance komponente
	 *
	 */
	public function component_create(){
		$hasPermission = $this->authorization->CheckPermission('read', 'zt_pages', $this->pageId) &&
		$this->authorization->CheckPermission('create', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}

		if($this->input->post('cancel')){
			redirect('admin/extensions/components');
		}

		$isValidData = $this->componentDataValidation();
		if($isValidData){
			$serializedPostData = serialize($_POST);
			$this->session->set_userdata('serialized_post_data', $serializedPostData);
			redirect('admin/extensions/component_mehtods_create');
		} else {
			$data['componentTypes'] = $this->ComponentTypesModel->GetComponentTypesForInstances();

			$renderData['main_column'] = $this->load->view('core/admin/extensions/component_create_view', $data, true);
			$head->title = "Stvaranje instance komponente";
			$head->meta = "";
			$renderData['head'] = $head;
			$this->render($renderData);
		}

	}

	/**
	 * Validacija podataka o metodama komponente
	 *
	 * @return boolean - true ako je validacija uspjela, false inace
	 */
	private function componentMethodDataValidation(){
		return true;
		$this->form_validation->set_rules('component_method_alias', 'Naziv metode komponente', 'trim|required|alpha_dash|xss_clean');

		if ($this->form_validation->run()) {
			return true;
		} else {
			return false;
		}

	}


	/**
	 * Prikaz stranice za dodavanje imena metoda komponenti
	 *
	 */
	public function component_mehtods_create(){
		if($this->input->post('cancel')){
			redirect('admin/extensions/components');
		}

		$componentPostData = unserialize($this->session->userdata('serialized_post_data'));
		$componentTypeMethods = $this->ComponentTypesModel->GetComponentTypeMethods($componentPostData['component_type_id']);


		//$isValidData = $this->componentMethodDataValidation();
		if(!empty($_POST)){

			$componentData = array(
				'component_name' => $componentPostData['component_name'],
				'component_description' => $componentPostData['component_description'],
				'component_type_id' => $componentPostData['component_type_id'],
				'component_alias' => $componentPostData['component_alias']
			);

			$componentMethodsData = array();

			// ### stvaranje stranica ###
			$this->load->library('core/page');

			foreach ($componentTypeMethods as $componentTypeMethod){
				$componentMethodData = array (
					'component_method_alias' => $this->input->post('component_type_method_' . $componentTypeMethod->component_type_method_id),
					'component_type_method_id' => $componentTypeMethod->component_type_method_id
				);

				// ## stvaranje stranica ##
				$resultCreatePage = $this->page->CreatePage(
					$componentTypeMethod->component_type_alias,
					$componentTypeMethod->component_type_method_name,
					$componentData['component_alias'],
					$componentMethodData['component_method_alias']);

				$componentMethodData['page_route'] = $resultCreatePage['page_route'];
				$componentMethodData['page_pattern'] = $resultCreatePage['page_pattern'];
				$componentMethodData['layout_id'] = 2;

				if($componentTypeMethod->component_type_method_name == "index"){
					$componentMethodData['controller_page_pattern'] = $resultCreatePage['controller_page_pattern'];
				}


				$componentMethodsData[] = $componentMethodData;

			}

			$this->ComponentsModel->InsertComponentFullData($componentData, $componentMethodsData);

			$this->session->unset_userdata('serialized_post_data');

			redirect('admin/extensions/components/');
		} else {
			$data['componentTypeMethods'] = $componentTypeMethods;

			$renderData['main_column'] = $this->load->view('core/admin/extensions/component_methods_create_view', $data, true);
			$head->title = "Stvaranje instance komponente - Korak 2";
			$head->meta = "";
			$renderData['head'] = $head;
			$this->render($renderData);
		}

	}


	/**
	 * Prikaz stranice za azuriranje podataka zadane instance komponente
	 *
	 * @param unknown_type $componentId
	 */
	public function component_edit($componentId = null){
		$hasPermission = $this->authorization->CheckPermission('read', 'zt_pages', $this->pageId) &&
		$this->authorization->CheckPermission('update', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}

		if($this->input->post('cancel')){
			redirect('admin/extensions/components');
		}

		$isValidData = $this->componentDataValidation();
		if($isValidData){
			$componentName = $this->input->post('component_name');
			$componentDescription = $this->input->post('component_description');
			$componentAlias = $this->input->post('component_alias');
			$componentActive = 0;
			if($this->input->post('component_active')){
					$componentActive = 1;
				}

			$componentData = array(
				'component_id' => $componentId,
				'component_name' => $componentName,
				'component_description' => $componentDescription,
				'component_alias' => $componentAlias,
				'component_active' => $componentActive
			);

			$this->ComponentsModel->UpdateComponent($componentData);

			// ### osvjezavanje stranica zbog promjene aliasa komponente/kontrolera

			redirect('admin/extensions/components');
		} else {
			$data['componentTypes'] = $this->ComponentTypesModel->GetComponentTypes();
			$data['component'] = $this->ComponentsModel->GetComponent($componentId);

			$renderData['main_column'] = $this->load->view('core/admin/extensions/component_edit_view', $data, true);
			$head->title = "Uređivanje komponente";
			$head->meta = "";
			$renderData['head'] = $head;
			$this->render($renderData);

		}

	}

	/**
	 * Prikaz stranice za azuriranje imena metoda komponenti
	 *
	 */
	public function component_mehtods_edit($componentId){
		if($this->input->post('cancel')){
			redirect('admin/extensions/components');
		}

		$componentMethods = $this->ComponentsModel->GetComponentMethods($componentId);

		//$isValidData = $this->componentMethodDataValidation();
		if(!empty($_POST)){
			foreach ($componentMethods as $componentMethod){

				$componentMethodData = array (
					'component_method_alias' => $this->input->post('component_method_' . $componentMethod->component_method_id),
					'component_method_id' => $componentMethod->component_method_id
				);

				$this->ComponentsModel->UpdateComponentMethod($componentMethodData);
			}

			redirect('admin/extensions/components/');
		} else {
			$data['componentMethods'] = $componentMethods;
			$data['componentId'] = $componentId;
			$data['layouts'] = $this->LayoutsModel->GetLayouts();


			$renderData['main_column'] = 			$this->load->view('core/admin/extensions/component_methods_edit_view', $data, true);
			$head->title = "Uređivanje metoda komponente";
			$head->meta = "";
			$renderData['head'] = $head;
			$this->render($renderData);
		}

	}

	/**
	 * Brise komponentu
	 *
	 * @param int $componentId - identifikator komponente koja se brise
	 */
	public function component_delete($componentId){
		$isDeleteDone = $this->ComponentsModel->DeleteComponent($componentId);
		if($isDeleteDone){
			redirect('admin/extensions/components/');
		} else {
			echo "greska kod brisanja";
		}
	}

	/**
	 * Prikaz instanci modula
	 *
	 */
	public function modules(){
		$hasPermission = $this->authorization->CheckPermission('read', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}
		$modules = $this->ModulesModel->GetModules();

		$data['modules'] = $modules;

		$renderData['main_column'] = $this->load->view('core/admin/extensions/modules_list_view', $data, true);
		$head->title = "Moduli";
		$head->meta = "";
		$renderData['head'] = $head;
		$this->render($renderData);
	}

	/**
	 * Validacija podataka o modulu
	 *
	 * @return boolean - true ako je validacija uspjela, false inace
	 */
	private function moduleDataValidation(){
		$this->form_validation->set_rules('module_name', 'Naziv modula', 'trim|required|xss_clean');
		$this->form_validation->set_rules('module_description', 'Opis modula', 'trim|required|xss_clean');

		if ($this->form_validation->run()) {
			return true;
		} else {
			return false;
		}

	}

	/**
	 * Prikaz stranice za stvaranje instanci modula
	 *
	 */
	public function module_create(){
		$hasPermission = $this->authorization->CheckPermission('read', 'zt_pages', $this->pageId) &&
		$this->authorization->CheckPermission('create', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}

		if($this->input->post('cancel')){
			redirect('admin/extensions/modules');
		}

		$isValidData = $this->moduleDataValidation();
		if($isValidData){

			$moduleData = array(
				'module_name' => $this->input->post('module_name'),
				'module_description' => $this->input->post('module_description'),
				'module_type_id' => $this->input->post('module_type_id')
			);

			$this->ModulesModel->InsertModule($moduleData);

			redirect('admin/extensions/modules');
		} else {
			$data['moduleTypes'] = $this->ModuleTypesModel->GetModuleTypes();
			$renderData['main_column'] = $this->load->view('core/admin/extensions/module_create_view', $data, true);
			$head->title = "Stvaranje modula";
			$head->meta = "";
			$renderData['head'] = $head;
			$this->render($renderData);
		}
	}

	/**
	 * Prikaz stranice za azuriranje instance modula
	 *
	 * @param unknown_type $moduleId
	 */
	public function module_edit($moduleId){
		$hasPermission = $this->authorization->CheckPermission('read', 'zt_pages', $this->pageId) &&
		$this->authorization->CheckPermission('update', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}

		if($this->input->post('cancel')){
			redirect('admin/extensions/modules');
		}

		$module = $this->ModulesModel->GetModule($moduleId);

		$packageFolder = $module->package;
		$className = "admin_" . $module->module_type_alias;


		// staza do paketa u libraries folderu
		$packageClassPath = $this->config->item('package_directory_path') . $packageFolder . "/";

		// potpuna staza iz root direktorija do datoteke razreda
		$fullClassPath = $packageClassPath .
			$this->config->item('module_directory_path') .
			$className . EXT;

		// staza do datoteke razreda unutar library direktorija
		$libraryClassPath = "packages/" . $packageFolder . "/" .
			$this->config->item('module_directory_path') .
			$className;

		if(file_exists($fullClassPath)){
			$this->load->library($libraryClassPath);

		} else {
			log_message('error', 'Nepostojeca datoteka: $classPath');
		}

		$isValidData = $this->moduleDataValidation();
		if($isValidData){
			$modulePublished = 0;
			if($this->input->post('module_published')){
				$modulePublished = 1;
			}

			$moduleParams = $this->{$className}->ProcessParamsForm();
			$moduleData = array(
				'module_name' => $this->input->post('module_name'),
				'module_description' => $this->input->post('module_description'),
				'module_type_id' => $this->input->post('module_type_id'),
				'module_published' => $modulePublished,
				'module_id' => $moduleId,
				'module_params' => $moduleParams
			);

			$this->ModulesModel->UpdateModule($moduleData);


			redirect('admin/extensions/modules');
		} else {
			$data['moduleTypes'] = $this->ModuleTypesModel->GetModuleTypes();
			$data['module'] = $module;
			$data['params'] = $this->{$className}->RenderParamsForm($module);

			$renderData['main_column'] = $this->load->view('core/admin/extensions/module_edit_view', $data, true);
			$head->title = "Uređivanje modula";
			$head->meta = "";
			$renderData['head'] = $head;
			$this->render($renderData);
		}
	}

	/**
	 * Brisanje modula
	 *
	 * @param unknown_type $moduleId
	 */
	public function module_delete($moduleId){
		$isDeleteDone = $this->ModulesModel->DeleteModule($moduleId);
		if($isDeleteDone){
			redirect('admin/extensions/modules/');
		} else {
			echo "greska kod brisanja";
		}
	}

}

?>