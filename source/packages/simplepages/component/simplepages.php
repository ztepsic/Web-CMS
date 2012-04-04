<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class SimplePages extends ZT_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model("packages/simplepages/simplepages_model");

		$this->load->library('core/auth/authorization');

		$this->load->library('core/auth/authentication');
		$this->load->library('core/auth/authorization');

	}

	public function index($simplePageId){
		$hasPermission = $this->authorization->CheckPermission('read', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			echo "Nemate pristup ovoj stranici";
			exit;
		}


		$simplePageResult = $this->simplepages_model->GetSimplePage($simplePageId);
		$simplePage['title'] = $simplePageResult->simple_page_name;
		$simplePage['body'] = $simplePageResult->simple_page_body;

		$simplePageRender = $this->load->view("packages/simplepages/component/simplePages_view", $simplePage, true);
		$data['main_column'] = $simplePageRender;

		$head->title = $simplePageResult->simple_page_name;
		$head->meta = "";
		$data['head'] = $head;

		$this->render($data);
	}
}

?>