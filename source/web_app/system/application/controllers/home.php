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
			echo "Nemate pristup ovoj stranici";
			exit;
		}

		$data['main_column'] = $this->load->view("packages/home/component/home_view", null, true);

		$head->title = "Naslovnica";
		$head->meta = "";
		$data['head'] = $head;

		$this->render($data);
	}
}

?>