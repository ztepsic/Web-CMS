<?php


class Roles extends ZT_Controller {

	public function rebuild(){
		$this->roles_model->rebuild();
	}

	public function __construct() {
		parent::__construct();

		$this->load->library('core/auth/authentication');
		$this->load->model("core/auth/roles_model");

		$this->load->library('core/auth/authentication');
		$this->load->library('core/auth/authorization');

		$this->load->library('form_validation');

	}

	/**
	 * Prikaz svih uloga
	 *
	 */
	public function index(){
		$hasPermission = $this->authorization->CheckPermission('read', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}

		$roles = $this->roles_model->GetFullTree();

		$data['roles'] = $roles;

		$renderData['main_column'] = $this->load->view("core/admin/roles/roles_list_view", $data, true);
		$head->title = "Uloge";
		$head->meta = "";
		$renderData['head'] = $head;
		$this->render($renderData);
	}

	/**
	 * Stvaranje nove uloge
	 *
	 */
	public function create(){
		$hasPermission = $this->authorization->CheckPermission('create', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}

		if($this->input->post('cancel')){
			redirect('admin/roles');
		}

		$isValidData = $this->userDataValidation();
		if($isValidData){
			$roleName = $this->input->post('role_name');
			$roleDescription = $this->input->post('role_description');
			$roleParentId = $this->input->post('role_parent_id');


			$this->roles_model->InsertRole($roleName, $roleDescription, $roleParentId);

			redirect('admin/roles/');
		} else {
			$roles = $this->roles_model->GetFullTree();
			$data['roles'] = $roles;

			$renderData['main_column'] = $this->load->view("core/admin/roles/role_create_view", $data, true);
			$head->title = "Stvaranje uloga";
			$head->meta = "";
			$renderData['head'] = $head;
			$this->render($renderData);
		}
	}



	/**
	 * Azuriranje postojecih podataka o ulozi
	 *
	 */
	public function edit($roleId){
		$hasPermission = $this->authorization->CheckPermission('update', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}

		if($this->input->post('cancel')){
			redirect('admin/roles');
		}

		$isValidData = $this->userDataValidation();
		if($isValidData){
			$roleName = $this->input->post('role_name');
			$roleDescription = $this->input->post('role_description');
			$roleParentId = $this->input->post('role_parent_id');

			$this->roles_model->UpdateRole($roleId, $roleName, $roleDescription, $roleParentId);

			redirect('admin/roles/');
		} else {
			$role = $this->roles_model->GetRole($roleId);
			$data['currentRole'] = $role;

			$roles = $this->roles_model->GetFullTree();
			$data['roles'] = $roles;

			$renderData['main_column'] = $this->load->view("core/admin/roles/role_edit_view", $data, true);
			$head->title = "Uređivanje uloga";
			$head->meta = "";
			$renderData['head'] = $head;
			$this->render($renderData);
		}


	}


	/**
	 * Validacija podataka o ulozi
	 *
	 * @return boolean - true ako je validacija uspjela, false inace
	 */
	private function userDataValidation(){
		$this->form_validation->set_rules('role_name', 'Naziv uloge', 'trim|required|xss_clean');
		$this->form_validation->set_rules('role_description', 'Opis uloge', 'trim|required|xss_clean');
		$this->form_validation->set_rules('role_parent_id', 'Roditeljska uloga', 'trim|required|numeric|xss_clean');

		if ($this->form_validation->run()) {
			return true;
		} else {
			return false;
		}

	}


	/**
	 * Brisanje uloge
	 *
	 * @param int $roleId - identifikator uloge
	 */
	public function delete($roleId = 0){
		if($roleId == 0){
			$this->index();
		} else {
			$done = $this->roles_model->DeleteRole($roleId);
			if($done){
				redirect('admin/roles');
			} else {
				echo "birsanje nije uspjelo";
			}
		}

	}


}

?>