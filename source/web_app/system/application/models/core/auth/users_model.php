<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Razred koji implementira model korisnika
 *
 * @package models.auth
 *
 * @author Zeljko Tepsic <ztepsic@gmail.com>
 * @copyright ztepsic.com
 * @version 1.0.0
 * @since 2008-12-20
 *
 */
class Users_model extends Model {

	public function __construct(){
		parent::Model();

		$this->load->library('core/auth/authentication');

	}

	// ####################################
	// ###### AUTO LOGIN ##### BEGIN ######
	// ####################################

	/**
	 * Sprema podatke o korisniku
	 *
	 * @param array $data - podaci o korisniku
	 * <ul>
	 * 	<li>autologin_key - kljuc za autologiranje</li>
	 * 	<li>user_id - identifikator korisnika</li>
	 * </ul>
	 * @return boolean - true ako je operacija uspjesno obavljena, false inace
	 */
	public function InsertAutoLoginData($data){
		$insertData['modules_group_id'] = null;

		if(!empty($data['autologin_key'])){
			$insertData['user_autologin_key'] = $data['autologin_key'];
		}

		if(!empty($data['user_id'])){
			$insertData['user_id'] = $data['user_id'];
		}

		if(!empty($data['user_autologin_expiration_datetime'])){
			$insertData['user_autologin_expiration_datetime'] = $data['user_autologin_expiration_datetime'];
		}


		return $this->db->insert('zt_users_autologin', $insertData);

	}

	/**
	 * Osvjezava vrijeme isteka autologin-a
	 *
	 * @param array $data - podaci o korisniku
	 * <ul>
	 * 	<li>autologin_key - kljuc za autologiranje</li>
	 * 	<li>user_id - identifikator korisnika</li>
	 * 	<li>expiration - vrijeme isteka</li>
	 * </ul>
	 * @return boolean - true ako je operacija uspjesno obavljena, false inace
	 */
	public function UpdateAutoLogin($data){
		$time = time() + $this->config->item('auth_cookie_expiration');
		$query = "
			UPDATE
				zt_users_autologin
			SET
				user_autologin_expiration_datetime = ?
			WHERE
				CONVERT(zt_users_autologin.user_autologin_key USING utf8) = ? AND
				zt_users_autologin.user_id = ?
			LIMIT 1 ;
		";


		$updateParams = array(
			$time,
			$data['autologin_key'],
			$data['user_id']
		);

		return $this->db->query($query, $updateParams);
	}


	/**
	 * Brise autologin zapis
	 *
	 * @param array $data - podaci o korisniku
	 * <ul>
	 * 	<li>autologin_key - kljuc za autologiranje</li>
	 * 	<li>user_id - identifikator korisnika</li>
	 * </ul>
	 * @return boolean - true ako je operacija uspjesno obavljena, false inace
	 */
	public function DeleteAutoLogin($data){
		$query = "
			DELETE
			FROM
				zt_users_autologin
			WHERE
				CONVERT(zt_users_autologin.user_autologin_key USING utf8) = ? AND
				zt_users_autologin.user_id = ?
			LIMIT 1
		";

		$deleteParams = array(
			$data['autologin_key'],
			$data['user_id']
		);

		return $this->db->query($query, $deleteParams);

	}

	/**
	 * Brise istekle autologin zapise
	 *
	 * @return boolean - true ako je operacija uspjesno obavljena, false inace
	 */
	public function DeleteExpiredAutoLogins(){
		$query = "
			DELETE
			FROM
				zt_users_autologin
			WHERE
				user_autologin_expiration_datetime < NOW()
		";


		return $this->db->query($query);
	}

	/**
	 * Za zadani autologin_key i user_id dohvaca korisnika
	 *
	 * @param string $autoLogin_key - autologin kljuc
	 * @param integer $userId - identifikator korisnika
	 * @return stdClass - korisnik
	 */
	public function GetUserFromAutoLogin($autoLogin_key, $userId){
		$query = "
			SELECT
				zt_users.*
			FROM
				zt_users_autologin,
				zt_users
			WHERE
				zt_users_autologin.user_autologin_key = ? AND
				zt_users_autologin.user_id ? AND
				zt_users.user_id = zt_users_autologin.user_id
			LIMIT 1
		";

		$result = $this->db->query($query, array($autoLogin_key, $userId));
		return $result->row();
	}

	// ####################################
	// ###### AUTO LOGIN ##### END ########
	// ####################################


	// ####################################
	// ###### USERS ##### BEGIN ###########
	// ####################################

	/**
	 * Dohvaca korisnika preko zadanih opcija
	 *
	 * @param array[key, value] $userParams - parametri koje poblize odreduju korisnika
	 * moguci kljucevi:
	 * <ul>
	 * 	<li><b>username</b> - korisnicko ime</li>
	 * 	<li><b>password</b> - korisnicka lozinka</li>
	 * </ul>
	 * @return stdClass - objekt sa odgovarajucim atributima
	 */
	public function GetUser($userParams){
		$whereQueryPart = '';
		$counterParams = 0;
		foreach ($userParams as $key => $userParam) {
			if($key == 'user_id'){
				$whereQueryPart .= "zt_users.user_id = ? ";
			} elseif($key == 'user_username'){
				$whereQueryPart .= "zt_users.user_username = ? ";
			} elseif($key == 'user_password'){
				$whereQueryPart .= "zt_users.user_password = ? ";
			} elseif($key == 'user_email'){
				$whereQueryPart .= "zt_users.user_email = ? ";
			} elseif($key == 'user_active'){
				$whereQueryPart .= "zt_users.user_active = ? ";
			}

			if($counterParams+1 < sizeof($userParams)) {
				$whereQueryPart .= " AND ";
			}

			$counterParams++;

		}

		$query = "
			SELECT
				zt_users.*
			FROM
				zt_users
			WHERE
				$whereQueryPart
			LIMIT 1;
		";

		$result = $this->db->query($query, $userParams);
		return $result->row();
	}

	/**
	 * Azurira podatke korisnika
	 *
	 * @param array $userParams - parametri koji se mjenjaju
	 * @return boolean - true ako je azuriranje uspjelo, false inace
	 */
	public function UpdateUser($userId, $userParams){
		$setQueryPart = '';
		$counterParams = 0;
		foreach ($userParams as $key => $userParam) {
			if($key == 'user_username'){
				$setQueryPart .= "user_username = ? ";
			} elseif($key == 'user_password'){
				$setQueryPart .= "user_password = ? ";
			} elseif($key == 'user_name'){
				$setQueryPart .= "user_name = ? ";
			} elseif($key == 'user_email'){
				$setQueryPart .= "user_email = ? ";
			} elseif($key == 'user_active'){
				$setQueryPart .= "user_active = ? ";
			}

			if($counterParams+1 < sizeof($userParams)) {
				$setQueryPart .= ", ";
			}

			$counterParams++;

		}

		$query = "
			UPDATE
				zt_users
			SET
			"	. $setQueryPart	.	"
			WHERE
				zt_users.user_id = ? LIMIT 1 ;
		";

		array_push(&$userParams, $userId);

		$result = $this->db->query($query, $userParams);

		if($result){
			return true;
		} else {
			return false;
		}

	}


	/**
	 * Brise korisnika
	 *
	 * @param int $userId - identifikator korisnika
	 * @return boolean - true ako je korisnik obrisan, inace false
	 */
	public function DeleteUser($userId){
		$this->db->where('user_id', $userId);
		return $this->db->delete('zt_users');
	}

	/**
	 * Postavlja vrijeme posljednjeg logiranja za korisnika
	 *
	 * @param int $user_id - identifikator korisnika
	 * @return boolean - true ako je uspjesno obavljeno, inace false
	 */
	public function SetLastVisit($user_id){
		$query = "
			UPDATE
				zt_users
			SET
				user_last_visit_datetime = NOW()
			WHERE
				zt_users.user_id = ?
			LIMIT 1 ;
		";


		return $this->db->query($query, $user_id);
	}

	/**
	 * Umece novog korisnika u bazu.
	 *
	 * @param array $newUser - polje sa korisnickim podacima
	 * @return boolean - true ako je uspjesno obavljeno, inace false
	 */
	public function CreateNewUser($newUser){
		extract($newUser);

		$query = "
			INSERT INTO zt_users (
				user_id ,
				user_name ,
				user_username ,
				user_email ,
				user_password ,
				user_active ,
				user_activation_key ,
				user_register_datetime ,
				user_last_visit_datetime ,
				user_ip ,
				user_params ,
				user_new_password ,
				user_new_password_key ,
				user_new_password_datetime
			)
			VALUES (
				NULL ,
				?,
				?,
				?,
				?,
				?,
				?,
				NOW(),
				NULL ,
				?,
				NULL ,
				NULL ,
				NULL ,
				NULL
			);

		";

		// ako akctivacijski kljuc nije postavljen, onda ga postavi na null
		if(empty($activation_key)){
			$activation_key = null;
		}

		$insertParams = array(
			$name,
			$username,
			$email,
			$password,
			$active,
			$activation_key,
			$user_ip
		);

		$this->db->query($query, $insertParams);

		$affected_rows = mysql_affected_rows();
		if($affected_rows == 1){
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Metoda koja kativira korisnika
	 *
	 * @param string $key - aktivacijski kljuc
	 * @return boolean - true ako je uspjesno obavljeno, inace false
	 */
	public function ActivateUser($key){
		$updateData = array();

		$updateData['user_active'] = 1;

		$this->db->where('zt_users.user_activation_key', $key);
		return $this->db->update('zt_users', $updateData);

	}

	/**
	 * Metoda koja postavlja novu lozinku i aktivacijski kljuc.
	 *
	 * @param array - $newPasswordData -
	 * <ul>
	 * 	<li>username - korisnicko ime</li>
	 * 	<li>new_password - nova lozinka</li>
	 * 	<li>new_password_key - aktivacijski kljuc </li>
	 * </ul>
	 * @return boolean - true ako je uspjesno obavljeno, inace false
	 */
	public function NewPassword($newPasswordData){
		$query = "
			UPDATE
				zt_users
			SET
				user_new_password = ?,
				user_new_password_key = ?,
				user_new_password_datetime = NOW()
			WHERE
				zt_users.user_username = ?
			LIMIT 1 ;
		";

		$updateParams = array(
			$newPasswordData['new_password'],
			$newPasswordData['new_password_key'],
			$newPasswordData['username']
		);

		$this->db->query($query, $updateParams);

		$affected_rows = mysql_affected_rows();
		if($affected_rows == 1){
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Metoda koja resetira korisnicku lozinku
	 *
	 * @param string $key - aktivacijski kljuc za resetiranje lozinke
	 * @return boolean - true ako je operacija uspjela, false inac
	 */
	public function ResetPassword($key){
		$getNewPasswordQuery = "
			SELECT
				zt_users.user_id,
				zt_users.user_new_password
			FROM
				zt_users
			WHERE
				zt_users.user_new_password_key = ?
			LIMIT 1;
		";

		$user = $this->db->query($getNewPasswordQuery, $key)->row();

		if(empty($user)){
			return false;
		}

		$newPassword = $user->user_new_password;
		$userId = $user->user_id;

		$updatePasswordQuery = "
			UPDATE
				zt_users
			SET
				user_password = ?,
				user_new_password_key = NULL
			WHERE
				zt_users.user_id = ?
			LIMIT 1 ;
		";

		$updateParams = array(
			$newPassword,
			$userId
		);

		$this->db->query($updatePasswordQuery, $updateParams);

		$affected_rows = mysql_affected_rows();
		if($affected_rows == 1){
			return true;
		} else {
			return false;
		}
	}



	/**
	 * Dohvaca sve korisnike
	 *
	 * @return stdClass - osoba
	 */
	public function GetUsers(){
		$query = "
			SELECT
				zt_users.*
			FROM
				zt_users
			ORDER BY user_username
		";

		$result = $this->db->query($query);
		return $result->result();
	}


	// ####################################
	// ###### USERS ##### END ###########
	// ####################################


}

?>