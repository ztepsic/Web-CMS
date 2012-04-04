<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Authorization {

	/**
	 * Referenca na CodeIgniter super object
	 *
	 * @var CodeIgniter super object
	 */
	private $CI;

	public function __construct(){
		$this->CI = &get_instance();

		log_message('debug', 'Authorization Initialized');

		$this->CI->load->model('core/auth/roles_model');

		$this->CI->load->library('core/auth/authentication');

	}


	/**
	 * Provjerava korisnikovu dozvolu za obavljanje akcije na zadanom entitetu.
	 *
	 * @param string $action - akcija
	 * @param string $requestedTable - tablica entiteta
	 * @param int $requestedId - identifikator entiteta
	 * @return boolean - true ako ima dozvolu, false inace
	 */
	public function CheckPermission($action, $requestedTable, $requestedId){
		$user = $this->CI->authentication->GetUser();

		if(!empty($user)){
			if($user->primary_role_id == -1){
				return true;
			} else {
				return $this->CI->roles_model->HasUserPermission($user->id, $action, $requestedTable, $requestedId);
			}
		} else {
			return false;
		}

	}

	private function DenyAccess(){
		echo "Pristup nije dozvoljen.";
		exit;
	}

}

?>