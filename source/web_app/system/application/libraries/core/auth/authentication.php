<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Authentication {

	/**
	 * Ime Cookie
	 *
	 * @var string
	 */
	private $cookieName;

	/**
	 * Referenca na CodeIgniter super object
	 *
	 * @var CodeIgniter super object
	 */
	private $CI;

	/**
	 * Defaultni konstruktor za Authentication
	 *
	 */
	public function __construct() {
		$this->CI = &get_instance();

		log_message('debug', 'Authentication Initialized');

		$this->CI->load->model('core/auth/users_model', "UsersModel");


		$this->init();
	}

	/**
	 * Inicijalizacija
	 *
	 */
	private function init(){
		// config.php
		$this->cookieName = $this->CI->config->item('sess_cookie_name');

		// Ulogiraj returning korisnika
		$this->autoLogin();

		if(!$this->CI->config->item('auth_enabled')){
			echo $this->lang->line('auth_disabled');
			exit;
		}

	}

	/**
	 * Auto logiranje
	 *
	 * @return boolean - true ako je auto logiranje uspjelo, false inace
	 */
	private function autoLogin(){
		// obrisi istkle autologine
		$this->CI->UsersModel->DeleteExpiredAutoLogins();

		$username = $this->CI->session->userdata('username');
		$cookie = $this->CI->input->cookie('autologin');
		if(!empty($cookie) && empty($username)) {

			// Extract data
			$autoLogin = unserialize($cookie);

			if(isset($autoLogin['autlogin_key']) && $autoLogin['user_id']) {
				$user = $this->CI->UsersModel->GetUserFromAutoLogin($autoLogin['autologin_key'], $autoLogin['user_id']);
				$this->setSession($user);
				$this->autoCookie(array($autoLogin['autlogin_key'], $autoLogin['user_id']));
			}

		}

		return false;

	}

	/**
	 * Stvara automatsko logiranje.
	 * Stvara kljuc i sprema ga u cookie i bazu za kasnije povezivanje sa
	 * korisnikom
	 *
	 * @param integer $userId - identifikator korisnika
	 * @return boolean - true ako je supjesno obavljeno, inace false
	 */
	private function createAutoLogin($userId) {
		// generiraj kljuc po kojemu mozemo povezati korisnika u bazi sa cookie
		$autologin_key = $this->generateKey();
		$data = array(
			'autologin_key' => $autologin_key,
			'user_id' => $userId,
			'user_autologin_expiration_datetime' => ($this->CI->config->item('auth_cookie_expiration') + time())
		);

		if($this->CI->UsersModel->InsertAutoLoginData($data)){
			$this->autoCookie($data);
			return true;
		} else {
			return false;
		}
	}

		/**
	 * Metoda koja postavlja podatke u cookie
	 *
	 * @param stdClass $data - podaci o korisniku
	 */
	private function autoCookie($data){
		$this->CI->load->helper('cookie');

		$cookie = array(
			'name' => 'autologin',
			'value' => serialize($data),
			'expire' => $this->CI->config->item('auth_cookie_expiration')
		);

		set_cookie($cookie);

		$this->CI->UsersModel->UpdateAutoLogin($data);

	}

	/**
	 * Brise auto login, odnosno brise zapis u bazi
	 * i brise cookie.
	 *
	 */
	private function deleteAutoLogin(){
		$this->CI->load->helper('cookie');

		$cookie = $this->CI->input->cookie('autologin');
		if(!empty($cookie)){
			$autoLogin = unserialize($cookie);
			$this->CI->UsersModel->DeleteAutoLogin($autoLogin['autologin_key']);
			set_cookie('autologin', '', -1);
		}
	}

	/**
	 * Metoda koja postavlja podatke u session
	 *
	 * @param stdClass $data - podaci o korisniku
	 */
	private function setSession($data){
		if(!empty($data->primary_role_id)){
			$primaryRoleId = $data->primary_role_id;
		} else {
			$primaryRoleId = 0;
		}

		$user = array(
			'user_id' => $data->user_id,
			'user_username' => $data->user_username,
			'user_name' => $data->user_name,
			'primary_role_id' => $primaryRoleId
		);

		$this->CI->session->set_userdata($user);

	}


	/**
	 * Provjerava da li korisnik postoji u bazi, te da li je
	 * aktivan.
	 *
	 * @param int $userId - identifikator korisnika
	 * @return boolean - true ako korisnik postoji i aktivan je, false inace
	 */
	private function checkUserExistence($userId){
		$userData = array (
			'user_id' => $userId,
			'user_active' => 1
		);

		$user = $this->CI->UsersModel->GetUser($userData);

		if(!empty($user)){
			// osvjezi podatke
			$this->setSession($user);

			return true;
		} else {
			return false;
		}

	}

	/**
	 * Dohvaca prijavljenog korisnika
	 *
	 * @return std_class - korisnik
	 */
	public function GetUser(){
		if($this->CI->session->userdata('user_id')){
			$user->id = $this->CI->session->userdata('user_id');
			$user->username = $this->CI->session->userdata('user_username');
			$user->name = $this->CI->session->userdata('user_name');
			$user->primary_role_id = 0;

			if($this->CI->session->userdata('primary_role_id')){
				$user->primary_role_id = $this->CI->session->userdata('primary_role_id');
			}


			$userExistence = $this->checkUserExistence($user->id);
			if($userExistence){
				return $user;
			} else {
				$this->Logout();
				return null;
			}

		} elseif($this->CI->session->userdata('user_id') == 0) {
			$user->id = (int) 0;
			$user->username = null;
			$user->name = null;
			$user->primary_role_id = 0;
			return $user;
		} else {
			$this->Logout();
			return null;
		}
	}



	// ############ CHECK methods #################

	/**
	 * Provjerava da li se korisnicko ime vec koristi.
	 *
	 * @param string $username - korisnicko ime
	 * @return boolean - true ako se koristi, false inace
	 */
	private function isUsernameTaken($username){
		$users = $this->CI->UsersModel->GetUser(array('user_username' => $username));
		if(count($users) == 0){
			return false;
		} else {
			return true;
		}
	}


	/**
	 * Provjerava da li je email vec iskoristen
	 *
	 * @param string $email - email adresa korisnika
	 */
	private function isEmailTaken($email){
		$users = $this->CI->UsersModel->GetUser(array('user_email' => $email));
		if(count($users) == 0){
			return false;
		} else {
			return true;
		}

	}

	// ############ END Check methods ###############


	/**
	 * Metoda za prijavu korisnika na sustav.
	 *
	 * @param string $username - korisnicko ime
	 * @param string $password - lozinka
	 * @param boolean $rememberMe - dozvola za autologin
	 */
	public function Login($username, $password, $rememberMe = false){
		if(!empty($username) && !empty($password)){
			$user = $this->CI->UsersModel->GetUser(array(
				'user_username' => $username,
				'user_password' => $this->EncodePassword($password),
			));

			// ako postoji navedni korisnik
			if(!empty($user)){
				// ako je korisnik aktivan
				if($user->user_active){

					$this->setSession($user);

					// ako je postavljen remember me kreiraj auto login
					if($rememberMe){
						$this->createAutoLogin($user->user_id);
					}

					return true;

				} else {
					return false;
				}


			} else {
				return false;
			}

		} else {
			return false;
		}

	}

	/**
	 * Odjava korisnika
	 *
	 */
	public function Logout(){
		// ako je korisnik prijavljen, onda ga odjavi
		if($this->CI->session->userdata('user_id')){
			$userId = $this->CI->session->userdata('user_id');
			$isSuccess = $this->CI->UsersModel->SetLastVisit($userId);
			if(!$isSuccess){
				echo "greska";
			}

			if($this->CI->input->cookie('autologin')){
				$this->deleteAutoLogin();
			}

			if($this->CI->session){
				$this->CI->session->sess_destroy();
			}

		}
	}

	/**
	 * Metoda koja obavlja registraciju novog korisnika.
	 * Pretpostavlja se da su korisnicki podaci ispravni. ####
	 *
	 * @param arrray $userData - korisnicki podaci
	 * @return boolean - true ako je registracija uspjela, false inace
	 */
	public function Register($userData, $active = false){
		// ######### staviti uvjet za dohvat te opcije iz baze, ako ne postoji onda pogledaj iz file-a
		if($this->CI->config->item('auth_allow_registrations')){
			$this->CI->load->helper('url');

			extract($userData);

			$checkUsername = $this->isUsernameTaken($username);
			if($checkUsername){
				return false;
			}

			$checkEmail = $this->isEmailTaken($email);
			if($checkEmail){
				return false;
			}

			$newUser = array (
				'user_ip' => $this->CI->input->ip_address(),
				'username' => $username,
				'password' => $this->EncodePassword($password),
				'email' => $email,
				'name' => $name
			);


			// ### staviti uvjet za dohvat opcije iz baze ili iz file-a.
			if($active){
				$isEmailVerification = false;
			} else {
				$isEmailVerification = $this->CI->config->item('auth_email_verification');
			}

			if($isEmailVerification){
				$newUser['activation_key'] = $this->generateKey();
				$newUser['active'] = 0;
				$result = $this->CI->UsersModel->CreateNewUser($newUser);
				// ##### posalji mail
			} else {
				$newUser['active'] = 1;
				$result = $this->CI->UsersModel->CreateNewUser($newUser);
			}

			return $result;

		} else {
			return false;
		}


	}

	/**
	 * Aktivacija korisnika
	 *
	 * @param string $key - aktivacijski kljuc
	 * @return boolean - true ako je aktivacija uspjela, false inace
	 */
	public function Activate($key){
		$result = $this->CI->UsersModel->ActivateUser($key);

		return $result;

	}

	/**
	 * Metoda koja obraduje zahtjev za generiranjem nove lozinke.
	 *
	 * @param string $username - korisnicko ime
	 * @return boolean - true ako je operacija uspjela, false inace
	 */
	public function ForgottenPassword($username){
		if(!empty($username)){
			$user = $this->CI->UsersModel->GetUser(array('user_username' => $username));
			if(!empty($user)){
				$newPassword = $this->generatePassword();
				$encodedNewPassword = $this->EncodePassword($newPassword);

				$newPasswordKey = $this->generateKey();

				$newPasswordData = array(
					'new_password' => $encodedNewPassword,
					'new_password_key' => $newPasswordKey,
					'username' => $username
				);

				$result = $this->CI->UsersModel->NewPassword($newPasswordData);

				if($result){
					// ############# posalji mail
					return $result;
				} else {
					return $result;
				}

			} else {
				return false;
			}
		}
	}

	/**
	 * Metoda koja resetira korisnicku lozinku
	 *
	 * @param string $key - aktivacijski kljuc za resetiranje lozinke
	 * @return boolean - true ako je operacija uspjela, false inac
	 */
	public function ResetPassword($key){
		if(!empty($key)) {
			$result = $this->CI->UsersModel->ResetPassword($key);
			if($result){
				// ###### autologin
				// ########3 posalji mail
				return  $result;
			} else {
				return $result;
			}
		} else {
			return false;
		}

	}

	public function ChangePassword($oldPassword, $newPassword){

	}

	/**
	 * Metoda koja generira lozinku
	 *
	 * @param string $passwordType - tip passworda. Moze biti:
	 * <ul>
	 * 	<li>any - vraca random lozinku, moze sadrzavati neobicne znakoce</li>
	 * 	<li>alphanum - vraca random lozinku koja sadrzi samo slova i brojeve</li>
	 * </ul>
	 * @param integer $length - duzina lozinke
	 * @return string - nova lozinka
	 */
	private function generatePassword($passwordType = 'alphanum', $passwordLength = 8){

	    $ranges='';

	    if($passwordType == 'any'){
	    	$ranges = '40-59,61-91,93-126';
	    } elseif ($passwordType == 'alphanum'){
	    	$ranges='65-90,97-122,48-57';
	    }

	    if(!empty($ranges)){
			$rangesArray = explode(',', $ranges);
			$numberOfRanges = count($rangesArray);

			mt_srand(time());

			$password = '';
			for($i=0; $i <= $passwordLength; $i++){
				$rangeIndex = mt_rand(0, $numberOfRanges - 1);
				list($min, $max) = explode('-', $rangesArray[$rangeIndex]);
				$password .= chr(mt_rand($min, $max));
			}

			return $password;
	    } else {
			throw new Exception('Greska kod generiranja novog passworda');
	    }

	}

	/**
	 * Metoda koja password provlaci kroz md5 hash
	 * funkciju.
	 *
	 * @param string $password - lozinka koju je potrebno zakodirat
	 * @return string - zakodirana lozinka
	 */
	public function EncodePassword($password){
		$saltedPassword = '';

		// Ukoliko postoji encryption key u config.php iskoristi ga
		if($this->CI->config->item('encryption_key') != ''){
			$saltedPassword = $this->CI->config->item('encryption_key') . $password;
		} else {
			$saltedPassword = $password;
		}

		$encodedPassword = md5($saltedPassword);

		return $encodedPassword;
	}

	/**
	 * Metoda koja generira key.
	 *
	 * @return string - key
	 */
	private function generateKey(){
		$key = md5(microtime() . str_shuffle($this->CI->config->item('encryption_key')));
		return $key;
	}

	// ##########

	private function sendMail($to, $from, $subject, $message){

	}

}

?>