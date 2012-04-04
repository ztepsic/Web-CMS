<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Razred koji implementira model layouta
 *
 * @package models
 *
 * @author Zeljko Tepsic <ztepsic@gmail.com>
 * @copyright ztepsic.com
 * @version 1.0.0
 * @since 2008-12-31
 *
 */
class Layouts_model extends Model {

	public function __construct(){
		parent::Model();
	}

	// ################################
	// ###### LAYOUTS ##### BEGIN #####
	// ################################

	/**
	 * Dohvaca sve layout-e.
	 *
	 * @return std_class array - layouti
	 */
	public function GetLayouts(){
		$query = "
			SELECT
				 *
			 FROM
				zt_layouts
			ORDER BY
				layout_name;
		";


		return $this->db->query($query)->result();
	}

	/**
	 * Dohvaca sve pozicije za zadani layout
	 *
	 * @param int $layoutId -  identifikator layout-a
	 * @return std_class array - pozicije layouta
	 */
	public function GetPositions($layoutId){
		$query = "
			SELECT
				*
			FROM
				zt_layout_positions
			WHERE
				zt_layout_positions.layout_id = ?;
		";

		return $this->db->query($query, $layoutId)->result();
	}

	// ##############################
	// ###### LAYOUTS ##### END #####
	// ##############################


}

?>