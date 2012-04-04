<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page {

    public function __construct() {
    	$this->CI = &get_instance();

		$this->CI->load->helper('file');
    }


    /**
     * Stvara stranicu
     *
     * @param string $controllerTypeAlias - jedinstveno ime kontrolera
     * @param string $controllerTypeMethodName - naziv org metode
     * @param string $controlerAlias - alias kontrolera
     * @param string $methodAlias - alias metode
     * @return string array - page_pattern, page_route, ako je index metoda tada i controller_page_pattern
     */
    public function CreatePage($controllerTypeAlias, $controllerTypeMethodName, $controlerAlias, $methodAlias){
    	$path = APPPATH . "packages_spec/" . $controllerTypeAlias . ".xml";
    	$xmlContent = read_file($path);

		$component = simplexml_load_string($xmlContent);

		$result = array();
		foreach($component->methods->method as $method){
			$methodRoute = $method->route;
			$methodPattern = $method->pattern;

			if($method->name == $controllerTypeMethodName && $controllerTypeMethodName == "index"){
				$result['controller_page_pattern'] = "^" . $controlerAlias . "$";
			}

			if($method->name == $controllerTypeMethodName){
				$result['page_pattern'] = $this->generatePattern($methodPattern, $controlerAlias, $methodAlias);
				$result['page_route'] = $methodRoute;
				break;
			}
		}

		return $result;

    }

    /**
     * Stvara pattern koji se sprema u bazu.
     *
     * @param string $methodPattern - shema patterna
     * @param string $controllerAlias - alias kontrolera koji treba ugraditi u pattern
     * @param string $methodAlias - alias metode koji treba ugraditi u pattern
     * @return string - izgenerirani pattern
     */
    private function generatePattern($methodPattern, $controllerAlias, $methodAlias){
    	$patterns = array();
    	$mehods = array();

    	if(!empty($controllerAlias)){
	    	$patterns[] = "{controller}";
			$replaces[] = $controllerAlias;
    	}

    	if(!empty($methodAlias)){
    		$patterns[] = "{method}";
			$replaces[] = $methodAlias;
    	}


		return str_replace($patterns, $replaces, $methodPattern);
    }

    /**
     * Stvara korisnicki generiranu stranicu.
     *
     * @param string $controllerTypeAlias - jedinstveno ime kontrolera
     * @param string $controllerTypeMethodName - naziv org metode
     * @param string $alias - alias stranice
     * @param string array $params - parametri
     * @return string array - page_pattern i page_route
     */
    public function CreateCustomPage($controllerTypeAlias, $controllerTypeMethodName, $alias, $params){
    	$path = APPPATH . "packages_spec/" . $controllerTypeAlias . ".xml";
    	$xmlContent = read_file($path);

		$component = simplexml_load_string($xmlContent);

		$result = array();
		foreach($component->methods->method as $method){
			$methodRoute = $method->route;
			$methodPattern = $method->pattern;

			if($method->name == $controllerTypeMethodName){
				$result['page_pattern'] = "^" . $alias . "$";
				$result['page_route'] = $this->generateRoute($methodRoute, $params);
				break;
			}
		}

		return $result;
    }

    /**
     * Generira tocnu rutu, odnosno umece prave parametre u shemu route
     *
     * @param string $methodRoute - shema rute
     * @param string array $params - parametri za rutu
     * @return string - tocna ruta
     */
    private function generateRoute($methodRoute, $params){
    	$patterns = array();
    	$mehods = array();

    	foreach ($params as $key => $param){
    		$patterns[] = "$" . $key;
			$replaces[] = $param;
    	}

		return str_replace($patterns, $replaces, $methodRoute);
    }


}


//$page = new Page();
//$page->CreatePage("home", "category", "igre", "joka");
//$page->CreateCustomPage("home", "category", "o-nama", array(1 => 23, 2 => "o-nama"));

?>