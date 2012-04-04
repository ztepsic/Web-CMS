<?php


class Users extends ZT_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->library('core/auth/authentication');
		$this->load->model("core/auth/users_model");
		$this->load->model("core/auth/roles_model");

		$this->load->library('core/auth/authentication');
		$this->load->library('core/auth/authorization');

		$this->load->library('form_validation');

	}

	/**
	 * Prikaz svih korisnika
	 *
	 */
	public function index(){
		$hasPermission = $this->authorization->CheckPermission('read', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}

		$users = $this->users_model->GetUsers();

		$data['users'] = $users;

		$renderData['main_column'] = $this->load->view("core/admin/users/users_list_view", $data, true);
		$head->title = "Pregled korisnika";
		$head->meta = "";
		$renderData['head'] = $head;
		$this->render($renderData);
	}

	/**
	 * Stvaranje novog korisnika
	 *
	 */
	public function create(){
		$hasPermission = $this->authorization->CheckPermission('create', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}

		if($this->input->post('cancel')){
			redirect('admin/users/');
		}

		$isValidData = $this->userDataValidation(true);
		if($isValidData){
			$name = $this->input->post('user_name');
			$username = $this->input->post('user_username');
			$password = $this->input->post('user_password');
			$email = $this->input->post('user_email');
			$active = $this->input->post('user_active');

			$userData = array(
				'username' => $username,
				'password' => $password,
				'email' => $email,
				'name' => $name
			);

			if($active){
				$this->authentication->Register($userData, true);
			} else {
				$this->authentication->Register($userData, false);
			}

			redirect('admin/users');

		} else {
			$renderData['main_column'] = $this->load->view("core/admin/users/user_create_view", null, true);
			$head->title = "Kreiranje korisnika";
			$head->meta = "";
			$renderData['head'] = $head;
			$this->render($renderData);
		}
	}


	/**
	 * Azuriranje postojecih podataka o korisniku
	 *
	 */
	public function edit($userId){
		$hasPermission = $this->authorization->CheckPermission('update', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}

		if($this->input->post('cancel')){
			redirect('admin/users/');
		}

		$isValidData = $this->userDataValidation(false);
		if($isValidData){
			$this->updateUserData($userId);

			redirect('admin/users');

		} else {
			$user = $this->users_model->GetUser(array('user_id' => $userId));
			$data['user'] = $user;

			$roles = $this->roles_model->GetFullTree();
			$data['roles'] = $roles;

			$userRoles = $this->roles_model->GetUserRoles($userId);
			$data['userRoles'] = $userRoles;

			$renderData['main_column'] = $this->load->view("core/admin/users/user_edit_view", $data, true);
			$head->title = "Uređivanje korisnika";
			$head->meta = "";
			$renderData['head'] = $head;
			$this->render($renderData);
		}

	}


	/**
	 * Azurira postojeceg korisnika na temelju POST podataka
	 *
	 */
	private function updateUserData($userId){
		$name = $this->input->post('user_name');
		$username = $this->input->post('user_username');
		$password = $this->input->post('user_password');
		$email = $this->input->post('user_email');
		$active = $this->input->post('user_active');

		$roleIds = $this->input->post('role');
		if(empty($roleIds)){
			$roleIds = array();
		}


		$userData = array(
			'user_username' => $username,
			'user_email' => $email,
			'user_name' => $name,
			'user_active' => $active
		);

		if(!empty($password)){
			$userData['user_password'] = $this->authentication->EncodePassword($password);
		}


		$this->users_model->UpdateUser($userId, $userData);

		// dohvaca uloge korisnika i puni polje sa id-ovima radi
		// razlike polja id-eva
		$userRoles = $this->roles_model->GetUserRoles($userId);
		$userRoleIds = array();
		foreach ($userRoles as $userRole){
			$userRoleIds[] = $userRole->role_id;

		}

		// brise dodjeljene uloge korisnika
		$deleteRoleIds = array_diff($userRoleIds, $roleIds);
		foreach ($deleteRoleIds as $deleteRoleId){
			$assignmentParams = array (
						'role_id' => (int)$deleteRoleId,
						'role_member_db_table' => 'zt_users',
						'role_member_foreign_key' => $userId
			);

			$this->roles_model->RemoveAssignment($assignmentParams);
		}


		// stvara dodjeljene uloge korisnika
		$createRoleIds = array_diff($roleIds, $userRoleIds);
		foreach ($createRoleIds as $createRoleId){
			$assignmentParams = array (
						'role_id' => (int)$createRoleId,
						'role_member_db_table' => 'zt_users',
						'role_member_foreign_key' => $userId,
						'role_member_alias' => null
			);

			$this->roles_model->AddAssignment($assignmentParams);
		}


	}


	/**
	 * Validacija podataka o korisniku
	 *
	 * @return boolean - true ako je validacija uspjela, false inace
	 */
	private function userDataValidation($isPasswordRequired){
		$this->form_validation->set_rules('user_username', 'Korisničko ime', 'trim|required|xss_clean');
		if($isPasswordRequired){
			$this->form_validation->set_rules('user_password', 'Lozinka', 'trim|required|xss_clean');
		}

		$this->form_validation->set_rules('user_name', 'Ime i prezime', 'trim|required|xss_clean');
		$this->form_validation->set_rules('user_email', 'Email', 'trim|required|valid_email|xss_clean');

		if ($this->form_validation->run()) {
			return true;
		} else {
			return false;
		}

	}



	/**
	 * Brisanje korisnika
	 *
	 * @param int $userId - identifikator korisnika
	 */
	public function delete($userId = 0){
		if($userId == 0){
			$this->index();
		} else {
			$done = $this->users_model->DeleteUser($userId);
			if($done){
				redirect("admin/users");
			} else {
				echo "birsanje nije uspjelo";
			}
		}

	}


}

?>