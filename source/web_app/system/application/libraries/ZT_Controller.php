<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Front kontroler klasa zaduzena za prikaz modula za odredenu
 * stranicu
 *
 */
class ZT_Controller extends Controller {

	/**
	 * Identifikator stranice
	 *
	 * @var int
	 */
	public $pageId;

	/**
	 * Ime view datoteke za prikaz sadrzaja.
	 *
	 * @var string
	 */
	private $layoutFile;


	/**
	 * Konstruktor
	 *
	 */
	public function __construct(){
		parent::Controller();

		$this->load->model("core/extensions/modules_model", "ModulesModel");
		$this->load->model("core/site_settings_model", "SiteSettingsModel");

		$this->load->helper('html');

		$userId = $this->session->userdata('user_id');
		if(empty($userId)){
			$this->session->set_userdata('user_id', 0);
			$userId = 0;
		}


		$pageLink = correctPageLink($this->uri->uri_string());

		if(empty($_SESSION['zt_page'])){
			show_404($pageLink);
		} else {
			$this->pageId = $_SESSION['zt_page']->page_id;
			$this->layoutFile = $_SESSION['zt_page']->layout_file;
			unset($_SESSION['zt_page']);
		}


		//$this->output->cache(1);
	}

	/**
	 * Iscrtava stranicu
	 *
	 * @param array<string> $data - dodatni podaci koje je potrebno iscrtati
	 */
	protected function render($data=null){

		// ### Moduli - BEGIN ###

		$publishedModules = $this->ModulesModel->GetPublishedModules($this->pageId);

		$modulePrefix = $this->config->item('module_folder_prefix');
		foreach ($publishedModules as $publishedModule){
			$packageFolder = $publishedModule->package;
			$className = $publishedModule->module_type_alias;


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

				// odpakiraj i postavi parametre modula
				$params = unserialize($publishedModule->module_params);
				$params->layout_position = $publishedModule->layout_position_alias;
				$this->{$className}->setParams($params);

				if(empty($data[$publishedModule->layout_position_alias])){
					$data[$publishedModule->layout_position_alias] = $this->moduleRender($this->{$className});
				} else {
					$data[$publishedModule->layout_position_alias] .= $this->moduleRender($this->{$className});
				}

			} else {
				echo "ne psotoji";
				echo $fullClassPath;
				log_message('error', 'Nepostojeca datoteka: $classPath');
			}
		}

		// ### Moduli - END ###

		// head meta podaci
		if(empty($data['head'])) {
			$siteSettings = $this->SiteSettingsModel->GetSiteSettings();
			if(!empty($siteSettings)){
				$setting = array();
				foreach ($siteSettings as $siteSetting){
					$setting[$siteSetting->site_setting_alias] = unserialize($siteSetting->site_setting_value);
				}

				if(!empty($setting['general_setting']->site_name)){
					$head->title = $setting['general_setting']->site_name;
				} else {
					$head->title = "";
				}

				// meta
				if(!empty($setting['metadata_setting']->description)){
					$metaDescription = $setting['metadata_setting']->description;
				} else {
					$metaDescription = "";
				}


				if(!empty($setting['metadata_setting']->keywords)){
					$metaKeywords = $setting['metadata_setting']->keywords;
				} else {
					$metaKeywords = "";
				}


				$meta = array(
			        array('name' => 'description', 'content' => $metaDescription),
			        array('name' => 'keywords', 'content' => $metaKeywords )
			    );

			   	$head->meta = meta($meta);

			    $data['head'] = $head;
			}


		} else {
			$siteSettings = $this->SiteSettingsModel->GetSiteSettings();
			$setting = array();
			foreach ($siteSettings as $siteSetting){
				$setting[$siteSetting->site_setting_alias] = unserialize($siteSetting->site_setting_value);
			}

			if(!empty($setting['general_setting']->site_name)){
				$data['head']->site_name = $setting['general_setting']->site_name;
			} else {
				$data['head']->title = $data['head']->title;
			}
			
			// meta
				if(!empty($setting['metadata_setting']->description)){
					$metaDescription = $setting['metadata_setting']->description;
				} else {
					$metaDescription = "";
				}


				if(!empty($setting['metadata_setting']->keywords)){
					$metaKeywords = $setting['metadata_setting']->keywords;
				} else {
					$metaKeywords = "";
				}


				$meta = array(
			        array('name' => 'description', 'content' => $metaDescription),
			        array('name' => 'keywords', 'content' => $metaKeywords )
			    );
			    
			  
					$data['head']->meta = $meta;

		}

		// iscrtaj sve
		$this->load->view('layouts/' . $this->layoutFile, $data);

	}

	/**
	 * Iscrtava modul
	 *
	 * @param IRenderer $renderer - modul koji treba iscrtat
	 * @return string - iscrtani zapis
	 */
	private function moduleRender(IRenderer $renderer){
		return $renderer->render();
	}



}

?>