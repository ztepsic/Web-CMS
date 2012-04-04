<?php


class Site_settings extends ZT_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model("core/site_settings_model", "SiteSettingsModel");

		$this->load->library('core/auth/authentication');
		$this->load->library('core/auth/authorization');

		$this->load->library('form_validation');

	}

	/**
	 * Validacija podataka za postavke stranice
	 *
	 * @return boolean - true ako je validacija uspjela, false inace
	 */
	private function siteSettingsDataValidation(){
		$this->form_validation->set_rules('general_setting_site_name', 'Naziv jednostavne stranice', 'trim|required|xss');

		if ($this->form_validation->run()) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Prikaz svih stranica
	 *
	 */
	public function index(){
		$hasPermission = $this->authorization->CheckPermission('read', 'zt_pages', $this->pageId) &&
			$this->authorization->CheckPermission('update', 'zt_pages', $this->pageId);
		if(!$hasPermission){
			redirect("admin");
		}

		if($this->input->post('cancel')){
			redirect('admin/');
		}

		$isValidData = $this->siteSettingsDataValidation();
		if($isValidData){
			$generalSetting->site_name = $this->input->post('general_setting_site_name');
			$siteSettingData = array (
				'site_setting_alias' => 'general_setting',
				'site_setting_value' => serialize($generalSetting)
			);

			$this->SiteSettingsModel->UpdateSiteSetting($siteSettingData);

			$metadataSetting->description = $this->input->post('metadata_setting_description');
			$metadataSetting->keywords = $this->input->post('metadata_setting_keywords');

			$siteSettingData = array (
				'site_setting_alias' => 'metadata_setting',
				'site_setting_value' => serialize($metadataSetting)
			);

			$this->SiteSettingsModel->UpdateSiteSetting($siteSettingData);

			redirect('admin');


		} else {
			$siteSettings = $this->SiteSettingsModel->GetSiteSettings();
			$data['siteSetting'] = array();
			foreach ($siteSettings as $siteSetting){
				$data['siteSetting'][$siteSetting->site_setting_alias] = unserialize($siteSetting->site_setting_value);
			}

			$renderData['main_column'] = $this->load->view("core/admin/site_settings/site_settings_view", $data, true);
			$head->title = "Postavke stranice";
			$head->meta = "";
			$renderData['head'] = $head;
			$this->render($renderData);
		}

	}



}

?>