<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Razred koji implementira model jednostavnih stranica
 *
 * @package components.com_simplepages
 *
 * @author Zeljko Tepsic <ztepsic@gmail.com>
 * @copyright ztepsic.com
 * @version 1.0.0
 * @since 2009-01-05
 *
 */
class SimplePages_model extends Model {

	public function __construct(){
		parent::Model();
	}


	// ######################################
	// ###### SIMPLE PAGES ##### BEGIN ######
	// ######################################

	/**
	 * Dohvaca jednostavne stranice sortirane po nazivu
	 *
	 * @return std_class array - jednostavne stranice
	 */
	public function GetSimplePages(){
		$query = "
			SELECT
				*
			FROM
				zt_simple_pages
			ORDER BY
				simple_page_name ASC
		";

		return $this->db->query($query)->result();
	}

	/**
	 * Dohvaca trazenu jednostavnu stranicu
	 *
	 * @param int $simplePageId - identifikator jednostavne stranice
	 * @return std_class - jednostavna stranica
	 */
	public function GetSimplePage($simplePageId){
		$query = "
			SELECT
				*
			FROM
				zt_simple_pages,
				zt_simple_page_page
			WHERE
				zt_simple_pages.simple_page_id = ? AND
				zt_simple_page_page.simple_page_id = zt_simple_pages.simple_page_id
			LIMIT 1;
		";

		return $this->db->query($query, $simplePageId)->row();
	}

	/**
	 * Umece novi zapis o jednostavnoj stranici
	 *
	 * @param array $simplePageData - podaci o jednostavnoj stranici
	 * @return boolean - true ako je akcija uspjela, inace false
	 */
	public function InsertSimplePage($simplePageData){
		$insertData['simple_page_id'] = null;
		$insertData['simple_page_creation_datetime'] = date(DATE_ISO8601);

		if(!empty($simplePageData['simple_page_alias'])){
			$insertData['simple_page_alias'] = $simplePageData['simple_page_alias'];
		}

		if(!empty($simplePageData['simple_page_name'])){
			$insertData['simple_page_name'] = $simplePageData['simple_page_name'];
		}

		if(!empty($simplePageData['simple_page_body'])){
			$insertData['simple_page_body'] = $simplePageData['simple_page_body'];
		}

		return $this->db->insert('zt_simple_pages', $insertData);
	}

	/**
	 * Azurira jednostavnu stranicu
	 *
	 * @param array $simplePageData - podaci o jednostavnoj stranici
	 * @return boolean - true ako je akcija uspjela, inace false
	 */
	public function UpdateSimplePage($simplePageData){
		$updateData = array();

		$updateData['simple_page_modification_datetime'] = date(DATE_ISO8601);

		if(!empty($simplePageData['simple_page_alias'])){
			$updateData['simple_page_alias'] = $simplePageData['simple_page_alias'];
		}

		if(!empty($simplePageData['simple_page_name'])){
			$updateData['simple_page_name'] = $simplePageData['simple_page_name'];
		}

		if(!empty($simplePageData['simple_page_body'])){
			$updateData['simple_page_body'] = $simplePageData['simple_page_body'];
		}

		$this->db->where('simple_page_id', $simplePageData['simple_page_id']);
		return $this->db->update('zt_simple_pages', $updateData);
	}

	/**
	 * Brise zadanu jednostavnu stranicu
	 *
	 * @param int $simplePageId - identifikator stranice
	 * @return boolean - true ako je akcija uspjela, inace false
	 */
	public function DeleteSimplePage($simplePageId){
		$this->db->where('simple_page_id', $simplePageId);
		return $this->db->delete('zt_simple_pages');
	}


	// ######################################
	// ###### SIMPLE PAGES ##### BEGIN ######
	// ######################################

	// ############################################
	// ###### SIMPLE PAGES PAGES ##### BEGIN ######
	// ############################################

	/**
	 * Umece novi zapis o povezanosti jednostavne stranice sa stranicom
	 *
	 * @param array $simplePagePageData - podaci
	 * @return boolean - true ako je akcija uspjela, inace false
	 */
	public function InsertSimplepAGEPage($simplePagePageData){
		$insertData['simple_page_page_id'] = null;

		if(!empty($simplePagePageData['simple_page_id'])){
			$insertData['simple_page_id'] = $simplePagePageData['simple_page_id'];
		}

		if(!empty($simplePagePageData['page_id'])){
			$insertData['page_id'] = $simplePagePageData['page_id'];
		}

		return $this->db->insert('zt_simple_page_page', $insertData);
	}

	// ##########################################
	// ###### SIMPLE PAGES PAGES ##### END ######
	// ##########################################



}

?>