<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Installer extends ZT_Controller {

	private $viewFolderPath = "core/admin/installer/";
	private $filePath;
	private $folderPath;

	public function __construct() {
		parent::__construct();

		$this->load->model('core/extensions/component_types_model', "ComponentTypesModel");
		$this->load->model('core/extensions/components_model', "ComponentsModel");
		$this->load->model('core/pages_model', "PagesModel");

		$this->load->library('core/auth/authentication');
		$this->load->library('core/auth/authorization');

		$this->load->library('core/unzip');
		$this->load->helper('file');

	}

	public function index(){
		$hasPermission = $this->authorization->CheckPermission('read', 'zt_pages', $this->pageId) &&
		$this->authorization->CheckPermission('create', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}

		if(empty($_FILES['upload'])){
			$renderData['main_column'] = $this->load->view($this->viewFolderPath . "installer_upload_view", null, true);
			$head->title = "Instalacija";
			$head->meta = "";
			$renderData['head'] = $head;
			$this->render($renderData);
		} else {
			$uploadFileParams = array(
				'file_name' => $_FILES['upload']['name'],
				'file_type' => $_FILES['upload']['type'],
				'file_size' => $_FILES['upload']['size'],
				'tmp_file_name' => $_FILES['upload']['tmp_name']
			);

			$sessionId = $this->session->userdata('session_id');

			$this->folderPath = APPPATH . "installs/" . $sessionId . "/";
			@mkdir($this->folderPath, 0777);

			$this->filePath = $this->folderPath . $uploadFileParams['file_name'];
			move_uploaded_file($uploadFileParams['tmp_file_name'], $this->filePath);

			$config['fileName']  = $this->filePath;
			$config['targetDir'] = $this->folderPath;
			$this->unzip->initialize($config);

			$this->unzip->unzipAll();

			//display's error messages
			//echo $this->unzip->display_errors(2);

			//display's information messages
			//echo $this->unzip->display_errors(1);

			$this->install();

			//delete_files(APPPATH . "installs/", TRUE);

			redirect('admin');

		}

	}

	private function installDatabase($databaseFile){
		$databasePath = $this->folderPath . "database/" . $databaseFile;
		$databaseSql = $specificationFile = read_file($databasePath);

		$queries = split("::", $databaseSql);

		foreach ($queries as $query){
			$this->db->query($query);
		}

	}



	private function install(){
		$specificationFile = read_file($this->folderPath . "/specification.xml");
		$specification = simplexml_load_string($specificationFile);


		// instalacija baze
		if(!empty($specification->database_file)){
			$databaseFile = $specification->database_file;
			$this->installDatabase($databaseFile);

		}


		// instalacija modela
		@rename($this->folderPath . "/models/", APPPATH . "models/packages/" . $specification->alias);

		//instalacija views-a
		@rename($this->folderPath . "/views/", APPPATH . "views/packages/" . $specification->alias);

		// instalacija lib
		@rename($this->folderPath . "/libraries/", APPPATH . "libraries/packages/" . $specification->alias . "libraries");



		// instalacija komponente
		if(!empty($specification->component_spec_file)){
			$componentFile = read_file($this->folderPath . "component/" . $specification->component_spec_file);

			$component = simplexml_load_string($componentFile);
			@rename($this->folderPath . "component/" . $component->alias . EXT, APPPATH . "controllers/" . $component->alias . EXT);
			@rename($this->folderPath . "component/admin/" . $component->alias . EXT, APPPATH . "controllers/admin/" . $component->alias . EXT);

			@rename($this->folderPath . "component/" . $component->alias . ".xml", APPPATH . "packages_spec/" . $component->alias . ".xml");

			$componentTypeData = array();
			$componentTypeData['component_type_name'] = (string)$component->name;
			$componentTypeData['component_type_description'] = (string)$component->description;
			$componentTypeData['component_type_alias'] = (string)$component->alias;
			$componentTypeData['component_type_mulltiple_instances'] = (string)$component->allow_instances;
			$componentTypeData['component_type_admin'] = (string)$component->admin;
			$componentTypeData['package'] = (string)$specification->alias;

			$this->ComponentTypesModel->InsertComponentType($componentTypeData);
			$componentTypeId = $this->db->insert_id();

			foreach ($component->methods->method as $method){

				$componentTypeMethodData = array();
				$componentTypeMethodData['component_type_method_name'] = (string)$method->name;
				$componentTypeMethodData['component_type_id'] = $componentTypeId;
				$componentTypeMethodData['compoent_type_method_has_params'] = $method->params;
				$componentTypeMethodData['component_type_method_back'] = $method->back;


				$this->ComponentTypesModel->InsertComponentTypeMethod($componentTypeMethodData);

			}




			$componentFile = read_file($this->folderPath . "component/admin/" . $specification->component_spec_file);
			$component = simplexml_load_string($componentFile);

			$componentTypeData = array();
			$componentTypeData['component_type_name'] = (string)$component->name;
			$componentTypeData['component_type_description'] = (string)$component->description;
			$componentTypeData['component_type_alias'] = (string)$component->alias;
			$componentTypeData['component_type_mulltiple_instances'] = (string)$component->allow_instances;
			$componentTypeData['component_type_admin'] = (string)$component->admin;
			$componentTypeData['package'] = (string)$specification->alias;

			$this->ComponentTypesModel->InsertComponentType($componentTypeData);
			$componentTypeId = $this->db->insert_id();

			$componentData = array();
			$componentData['component_name'] = (string)$component->name;
			$componentData['component_description'] = (string)$component->description;
			$componentData['component_alias'] = (string)$component->alias;
			$componentData['component_type_id'] = (int) $componentTypeId;

			$this->ComponentsModel->InsertComponent($componentData);
			$componentId = $this->db->insert_id();

			foreach ($component->methods->method as $method){

				$componentTypeMethodData = array();
				$componentTypeMethodData['component_type_method_name'] = (string)$method->name;
				$componentTypeMethodData['component_type_id'] = $componentTypeId;
				$componentTypeMethodData['compoent_type_method_has_params'] = $method->params;
				$componentTypeMethodData['component_type_method_back'] = $method->back;


				$this->ComponentTypesModel->InsertComponentTypeMethod($componentTypeMethodData);
				$componentTypeMethodId = $this->db->insert_id();

				$componentMethodData = array();
				$componentMethodData['component_method_alias'] = (string)$method->name;
				$componentMethodData['component_type_method_id'] = $componentTypeMethodId;
				$componentMethodData['component_id'] = $componentId;


				$this->ComponentsModel->InsertComponentMethod($componentMethodData);
				$componentMethodId = $this->db->insert_id();

				$pageData = array(
					'page_name' => (string)$component->name . " - " . (string)$method->name,
					'component_method_id' => $componentMethodId,
					'page_link' => null,
					'page_pattern' => (string)$method->pattern,
					'page_route' => (string)$method->route,
					'page_locked_by_component' => 1,
					'layout_id' => 1,
					'page_public' => 1
				);

				$this->PagesModel->InsertPage($pageData);

			}

		}

		// instalacija modula
		if(!empty($specification->modules_spec_files)){
			foreach($specification->modules_spec_files->module_spec_file as $moduleSpecFile){
				$moduleFile = read_file($this->folderPath . "/modules/" . $moduleSpecFile);

				$module = simplexml_load_string($moduleFile);

				@mkdir(APPPATH . "libraries/packages/" . $specification->alias, 0755);
				@mkdir(APPPATH . "libraries/packages/" . $specification->alias . "/modules/", 0755);
				@rename($this->folderPath . "modules/" . $module->alias . EXT, APPPATH . "libraries/packages/" . $specification->alias . "/modules/" . $module->alias . EXT);
				@rename($this->folderPath . "modules/admin_" . $module->alias . EXT, APPPATH . "libraries/packages/" . $specification->alias . "/modules/admin_" . $module->alias . EXT);

				@rename($this->folderPath . "modules/admin/" . $module->alias . EXT, APPPATH . "controllers/admin/" . $module->alias . EXT);

				if(empty($componentTypeId)){
					$componetTypeId = null;
				}

				$moduleTypeData = array(
					'module_type_name' => (string)$module->name,
					'module_type_description' => (string)$module->description,
					'component_type_id' => (string)$componentTypeId,
					'module_type_active' => 0,
					'package' => (string)$specification->alias,
					'module_type_alias' => (string)$module->alias,
					'module_type_admin' => 0,
					'module_type_mulltiple_instances' => (int) $module->allow_instances
				);

				$this->ModuleTypesModel->InsertModuleType($moduleTypeData);
			}

		}

	}


}

?>