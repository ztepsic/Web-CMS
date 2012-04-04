<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| CONFIGURATION ARRAY FOR AUTH
|
| @subpackage  Config
| @category    Auth Configuration
| @author      Zeljko Tepsic <ztepsic@gmail.com>
| @copyright   Copyright (c) 2008
*/

/*
=====================
MAIN CONFIGURATION
=====================
*/

// Omoguci/onemoguci auth
$config['auth_enabled'] = true;

// Putanje u kojima se nalaze php file-ovi
$config['auth_model_path'] = 'auth/';
$config['auth_library_path'] = 'auth/';

/**
 * Dozvola registracija
 */
$config['auth_allow_registrations'] = true;

/**
 * Verifikacija preko email-a.
 */
$config['auth_email_verification'] = true;

$config['auth_cookie_expiration'] = 7200;

?>