<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Razred koji implementira model postavki stranice
 *
 * @package models.core
 *
 * @author Zeljko Tepsic <ztepsic@gmail.com>
 * @copyright ztepsic.com
 * @version 1.0.0
 * @since 2009-01-06
 *
 */
class Site_settings_model extends Model {

	public function __construct(){
		parent::Model();
	}

	// ######################################
	// ###### SITE SETTINGS ##### BEGIN #####
	// ######################################

	/**
	 * Dohvaca postavke stranice
	 *
	 * @return array std_class - postavke stranice
	 */
	public function GetSiteSettings(){
		$query = "
			SELECT
				 *
			 FROM
				zt_site_settings
		";


		return $this->db->query($query)->result();
	}

	/**
	 * Dohvaca postavku stranice
	 *
	 * @param string $siteSettingAlias - alias postvke stranice
	 * @return std_class - postavka stranice
	 */
	public function GetSiteSetting($siteSettingAlias){
		$query = "
			SELECT
				 *
			 FROM
				zt_site_settings
			WHERE
				site_setting_alias = ?
		";


		return $this->db->query($query, $siteSettingAlias)->row();
	}


	/**
	 * Azurira postojeci zapis o postavci stranice
	 *
	 * @param array $siteSettingData - podaci o postavci
	 * @return boolean - true ako je akcija uspjela, inace false
	 */
	public function UpdateSiteSetting($siteSettingData){
		$updateData = array();

		if(!empty($siteSettingData['site_setting_value'])){
			$updateData['site_setting_value'] = $siteSettingData['site_setting_value'];
		}

		$this->db->where('site_setting_alias', $siteSettingData['site_setting_alias']);
		return $this->db->update('zt_site_settings', $updateData);
	}


	// ####################################
	// ###### SITE SETTINGS ##### END #####
	// ####################################


}

?>