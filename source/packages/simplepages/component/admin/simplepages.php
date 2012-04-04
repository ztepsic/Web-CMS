<?php


class SimplePages extends ZT_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model("packages/simplepages/simplepages_model", "SimplePagesModel");
		$this->load->model('core/pages_model', "PagesModel");
		$this->load->model('core/extensions/component_types_model', "ComponentTypesModel");

		$this->load->library('core/auth/authentication');
		$this->load->library('core/auth/authorization');

		$this->load->library('core/page');
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

		$simplePages = $this->SimplePagesModel->GetSimplePages();
		$data['simplePages'] = $simplePages;

		$renderData['main_column'] = $this->load->view("packages/simplepages/component/admin/simplepages_list_view", $data, true);
		$head->title = "Jednostavne stranice";
		$head->meta = "";
		$renderData['head'] = $head;
		$this->render($renderData);
	}

	/**
	 * Validacija podataka za jednostavne stranice
	 *
	 * @return boolean - true ako je validacija uspjela, false inace
	 */
	private function simplePagesDataValidation(){
		$this->form_validation->set_rules('simple_page_name', 'Naziv jednostavne stranice', 'trim|required|xss');
		$this->form_validation->set_rules('simple_page_alias', 'Alias jednostavne stranice', 'trim|required|alpha_dash|xss');

		if ($this->form_validation->run()) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Stvaranje jednostavne stranice
	 *
	 */
	public function simplepage_create(){
		$hasPermission = $this->authorization->CheckPermission('create', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}

		if($this->input->post('cancel')){
			redirect('admin/simplepages');
		}

		$isValidData = $this->simplePagesDataValidation();
		if($isValidData){
			$simplePageData['simple_page_alias'] = $this->input->post('simple_page_alias');
			$simplePageData['simple_page_name'] = $this->input->post('simple_page_name');

			if($this->input->post('simple_page_body')){
				$simplePageData['simple_page_body'] = $this->input->post('simple_page_body');
			}

			$this->SimplePagesModel->InsertSimplePage($simplePageData);

			$simplePageId = $this->db->insert_id();

			$resultCreateCustomPage = $this->page->CreateCustomPage(
					"simplepages",
					"index",
					$simplePageData['simple_page_alias'],
					array(1 => $simplePageId)
			);


			$informations = $this->ComponentTypesModel->GetComponentTypeInformations('simplepages');
			$componentTpyeId = $informations[0]->component_method_id;

			$pageData = array(
					'page_name' => "Simple Page" . " - " . $simplePageData['simple_page_name'],
					'component_method_id' => $componentTpyeId,
					'page_link' => $simplePageData['simple_page_alias'],
					'page_pattern' => $resultCreateCustomPage['page_pattern'],
					'page_route' => $resultCreateCustomPage['page_route'],
					'page_locked_by_component' => 1,
					'layout_id' => 2,
					'page_public' => 1
			);

			$this->PagesModel->InsertPage($pageData);

			$pageId = $this->db->insert_id();
			$simplePagePageData = array(
				'simple_page_id' => $simplePageId,
				'page_id' => $pageId
			);

			$this->SimplePagesModel->InsertSimplePagePage($simplePagePageData);

			redirect('admin/simplepages/');
		} else {
			$renderData['main_column'] = $this->load->view('packages/simplepages/component/admin/simplepage_create_view', null, true);
			$head->title = "Stvori jednostavnu stranicu";
			$head->meta = "";
			$renderData['head'] = $head;
			$this->render($renderData);
		}
	}

	/**
	 * Azuriranje jednostavne stranice
	 *
	 * @param int $simplePageId - identifikator jednostavne stranice
	 */
	public function simplepage_edit($simplePageId){
		$hasPermission = $this->authorization->CheckPermission('edit', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}

		if($this->input->post('cancel')){
			redirect('admin/simplepages');
		}

		$isValidData = $this->simplePagesDataValidation();
		if($isValidData){
			$simplePageData['simple_page_id'] = $simplePageId;
			$simplePageData['simple_page_alias'] = $this->input->post('simple_page_alias');
			$simplePageData['simple_page_name'] = $this->input->post('simple_page_name');

			if($this->input->post('simple_page_body')){
				$simplePageData['simple_page_body'] = $this->input->post('simple_page_body');
			}

			$this->SimplePagesModel->UpdateSimplePage($simplePageData);

			$resultCreateCustomPage = $this->page->CreateCustomPage(
					"simplepages",
					"index",
					$simplePageData['simple_page_alias'],
					array(1 => $simplePageId)
			);

			$simplePage = $this->SimplePagesModel->GetSimplePage($simplePageId);
			$pageData = array(
					'page_name' => "Simple Page" . " - " . $simplePageData['simple_page_name'],
					'page_link' => $simplePageData['simple_page_alias'],
					'page_pattern' => $resultCreateCustomPage['page_pattern'],
					'page_route' => $resultCreateCustomPage['page_route'],
					'page_id' => $simplePage->page_id
			);

			$this->PagesModel->UpdatePage($pageData);

			redirect('admin/simplepages/');
		} else {
			$data['simplePage'] = $this->SimplePagesModel->GetSimplePage($simplePageId);
			$renderData['main_column'] = $this->load->view('packages/simplepages/component/admin/simplepage_edit_view', $data, true);
			$head->title = "Uredi jednostavnu stranicu";
			$head->meta = "";
			$renderData['head'] = $head;
			$this->render($renderData);

		}
	}

	/**
	 * Brise zadanu jednostavnu stranicu
	 *
	 * @param int $simplePageId - identifikator jednostavne stranice
	 */
	public function simplepage_delete($simplePageId){
		$simplePage = $this->SimplePagesModel->GetSimplePage($simplePageId);
		$this->PagesModel->DeletePage($simplePage->page_id);
		$this->SimplePagesModel->DeleteSimplePage($simplePageId);
		redirect('admin/simplepages/');
	}

}

?>