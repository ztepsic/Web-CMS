<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Home extends ZT_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->library('core/auth/authentication');
		$this->load->library('core/auth/authorization');
	}

	public function index(){
		$hasPermission = $this->authorization->CheckPermission('read', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin/login");
		}

		$renderData['main_column'] = "Dobrodošli";
		$head->title = "Naslovnica";
		$head->meta = "";
		$renderData['head'] = $head;
		$this->render($renderData);

	}

	public function login(){
		$this->load->library('form_validation');

		$this->form_validation->set_error_delimiters('<li>', '</li>');
		$this->form_validation->set_rules('username', 'Korisničko ime', 'trim|required');
		$this->form_validation->set_rules('password', 'Lozinka', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			$this->load->view('core/admin/admin_login_view');
		} else {
			$username = $this->input->post('username', true);
			$password = $this->input->post('password', true);
			$autologin = $this->input->post('autologin', true);

			if(empty($autologin)){
				$login = $this->authentication->Login($username, $password);
			} else {
				$login = $this->authentication->Login($username, $password, true);
			}

			if(!$login){
				$this->session->set_userdata('login', 'Korisnik ne postoji ili ste unjeli pogrešne podatke.');
			}

			redirect('admin/home');

		}

	}

	public function logout(){
		$this->authentication->Logout();
		redirect('admin/home');
	}
}

?>