<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if ( ! function_exists('comparePageLink')){
	function comparePageLinks($currentPageLink, $pageLink){
		$modifiedCurrentPageLink = correctPageLink($currentPageLink);

		return strcmp($modifiedCurrentPageLink, $pageLink);
	}
}

/**
 * Funkcija koja korigira zadani url.
 * Ukoliko je uri poslije base_url prazan tada ga postavlja na defaultni kontroler.
 * Brise trailing i leading backslash
 *
 * @param string $pageLink
 * @return string - korigirani uri string
 */
if ( ! function_exists('correctPageLink')){
	function correctPageLink($pageLink){
		if(empty($pageLink)){
			require(APPPATH . 'config/routes'. EXT);
			$modifiedpageLink = $route['default_controller'];
			return $modifiedpageLink;
		}

		return trim($pageLink, "/");
	}
}

?>