<?php


class Menus extends ZT_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model("packages/menu/menus_model", "MenusModel");
		$this->load->model("core/pages_model", "PagesModel");

		$this->load->library('core/auth/authentication');
		$this->load->library('core/auth/authorization');

		$this->load->library('form_validation');

	}

	/**
	 * Prikaz svih izbornika
	 *
	 */
	public function index(){
		$hasPermission = $this->authorization->CheckPermission('read', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}


		$menus = $this->MenusModel->GetMenus();
		$data['menus'] = $menus;

		$renderData['main_column'] = $this->load->view("packages/menu/modules/admin/menus_list_view", $data, true);
		$head->title = "Izbornici";
		$head->meta = "";
		$renderData['head'] = $head;
		$this->render($renderData);
	}

	/**
	 * Validacija podataka za izbornik
	 *
	 * @return boolean - true ako je validacija uspjela, false inace
	 */
	private function menuDataValidation(){
		$this->form_validation->set_rules('menu_name', 'Naziv izbornika', 'trim|required');

		if ($this->form_validation->run()) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Stvaranje novovg izbornika
	 *
	 */
	public function menu_create(){
		$hasPermission = $this->authorization->CheckPermission('create', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}

		if($this->input->post('cancel')){
			redirect('admin/menus');
		}

		$isValidData = $this->menuDataValidation();
		if($isValidData){
			$menuData['menu_name'] = $this->input->post('menu_name');

			if($this->input->post('menu_description')){
				$menuData['menu_description'] = $this->input->post('menu_description');
			}

			$this->MenusModel->InsertMenu($menuData);

			redirect('admin/menus/');
		} else {
			$renderData['main_column'] = $this->load->view('packages/menu/modules/admin/menu_create_view', null, true);
			$head->title = "Stvaranje izbornika";
			$head->meta = "";
			$renderData['head'] = $head;
			$this->render($renderData);
		}
	}

	/**
	 * Azuriranje izbornika
	 *
	 * @param int $menuId - identifikator izbornika
	 */
	public function menu_edit($menuId){
			$hasPermission = $this->authorization->CheckPermission('update', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}


		if($this->input->post('cancel')){
			redirect('admin/menus');
		}

		$isValidData = $this->menuDataValidation();
		if($isValidData){
			$menuData['menu_name'] = $this->input->post('menu_name');

			if($this->input->post('menu_description')){
				$menuData['menu_description'] = $this->input->post('menu_description');
			} else {
				$menuData['menu_description'] = null;
			}

			$menuData['menu_id'] = $menuId;

			$this->MenusModel->UpdateMenu($menuData);

			redirect('admin/menus/');
		} else {
			$data['menu'] = $this->MenusModel->GetMenu($menuId);

			$renderData['main_column'] = $this->load->view('packages/menu/modules/admin/menu_edit_view', $data, true);
			$head->title = "Uređivanje izbornika";
			$head->meta = "";
			$renderData['head'] = $head;
			$this->render($renderData);
		}
	}

	/**
	 * Brisanje izbornika
	 *
	 * @param int $menuId - identifikator izbornika
	 */
	public function menu_delete($menuId){
		$this->MenusModel->DeleteMenu($menuId);
		redirect('admin/menus/');
	}

	/**
	 * Prikaz elemenata koji pripadaju izborniku
	 */
	public function menu_items($menuId){
		$hasPermission = $this->authorization->CheckPermission('read', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}

		$menuItems = $this->MenusModel->GetAllMenuItems($menuId);
		$data['menuId'] = $menuId;
		$data['menuItems'] = $menuItems;

		$renderData['main_column'] = $this->load->view("packages/menu/modules/admin/menu_items_list_view", $data, true);
		$head->title = "Uređivanje izbornika";
		$head->meta = "";
		$renderData['head'] = $head;
		$this->render($renderData);

	}

	/**
	 * Validacija podataka za element izbornika
	 *
	 * @return boolean - true ako je validacija uspjela, false inace
	 */
	private function menuItemDataValidation(){
		$this->form_validation->set_rules('menu_item_name', 'Naziv elementa izbornika', 'trim|required');
		$this->form_validation->set_rules('page_id', 'Stranica', 'required');

		if ($this->form_validation->run()) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 * Stvara novi element izbornika
	 */
	public function menu_item_create($menuId){
				$hasPermission = $this->authorization->CheckPermission('create', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}

		if($this->input->post('cancel')){
			redirect('admin/menus');
		}

		$isValidData = $this->menuItemDataValidation();
		if($isValidData){
			$menuItemData['menu_item_name'] = $this->input->post('menu_item_name');

			if($this->input->post('menu_item_description')){
				$menuItemData['menu_item_description'] = $this->input->post('menu_item_description');
			}


			$menuItemData['menu_id'] = $menuId;
			$menuItemData['page_id'] = $this->input->post('page_id');

			if($this->input->post('menu_item_order')){
				$menuItemData['menu_item_order'] = $this->input->post('menu_item_order');
			}

			if($this->input->post('menu_item_published')){
				$menuItemData['menu_item_published'] = $this->input->post('menu_item_published');
			}



			$this->MenusModel->InsertMenuItem($menuItemData);

			redirect('admin/menus/');
		} else {
			$data['pages'] = $this->PagesModel->GetPages();
			$data['menuId'] = $menuId;

			$renderData['main_column'] = $this->load->view('packages/menu/modules/admin/menu_item_create_view', $data, true);
			$head->title = "Stvaranje elemenata izbornika";
			$head->meta = "";
			$renderData['head'] = $head;
			$this->render($renderData);
		}
	}

	/**
	 * Azurira element izbornika
	 *
	 * @param unknown_type $menuItemId
	 */
	public function menu_item_edit($menuItemId){
				$hasPermission = $this->authorization->CheckPermission('update', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}


		if($this->input->post('cancel')){
			redirect('admin/menus');
		}

		$isValidData = $this->menuItemDataValidation();
		if($isValidData){
			$menuItemData['menu_item_name'] = $this->input->post('menu_item_name');

			if($this->input->post('menu_item_description')){
				$menuItemData['menu_item_description'] = $this->input->post('menu_item_description');
			}


			$menuItemData['menu_item_id'] = $menuItemId;
			$menuItemData['page_id'] = $this->input->post('page_id');

			if($this->input->post('menu_item_order')){
				$menuItemData['menu_item_order'] = $this->input->post('menu_item_order');
			}


			if($this->input->post('menu_item_published')){
				$menuItemData['menu_item_published'] = 1;
			} else {
				$menuItemData['menu_item_published'] = 0;
			}



			$this->MenusModel->UpdateMenuItem($menuItemData);

			redirect('admin/menus/');
		} else {
			$data['pages'] = $this->PagesModel->GetPages();
			$data['menuItem'] = $this->MenusModel->GetMenuItem($menuItemId);

			$renderData['main_column'] = 			$this->load->view('packages/menu/modules/admin/menu_item_edit_view', $data, true);
			$head->title = "Uređivanje elemenata izbornika";
			$head->meta = "";
			$renderData['head'] = $head;
			$this->render($renderData);
		}
	}

	/**
	 * Brise element izbornika
	 *
	 * @param unknown_type $menuItemId
	 */
	public function menu_item_delete($menuItemId){
		$this->MenusModel->DeleteMenuItem($menuItemId);
		redirect('admin/menus/');
	}



}

?>