<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Klasa za pozivanje kontrolera.
 * Routanje se vrsi preko routes.php datoteke, ali
 * i preko route iz baze podataka.
 *
 * @author Zeljko Tepsic <ztepsic@gmail.com>
 * @copyright Zeljko Tepsic - ztepsic.com
 * @version 1.0 2008-09-12
 */
class ZT_Router extends CI_Router {

	/**
	 * Referenca na Pages_model objekt
	 *
	 * @var Pages_model
	 */
	private $pages_model;

    public function __construct() {
    	require_once(APPPATH . 'helpers/pagelink_helper' . EXT);

    	require_once(BASEPATH.'codeigniter/Base5'.EXT);
    	$this->load =& load_class('Loader');
		$this->load->database();


    	require_once(BASEPATH . 'libraries/Model' . EXT);
    	require_once(APPPATH . 'models/core/pages_model' . EXT);

    	$this->pages_model = new Pages_model();

    	parent::CI_Router();
    }

    /**
	 * Set the route mapping
	 *
	 * This function determines what should be served based on the URI request,
	 * as well as any "routes" that have been set in the routing config file.
	 *
	 * @access	private
	 * @return	void
	 */
	function _set_routing()
	{
		// Are query strings enabled in the config file?
		// If so, we're done since segment based URIs are not used with query strings.
		if ($this->config->item('enable_query_strings') === TRUE AND isset($_GET[$this->config->item('controller_trigger')]))
		{
			$this->set_class(trim($this->uri->_filter_uri($_GET[$this->config->item('controller_trigger')])));

			if (isset($_GET[$this->config->item('function_trigger')]))
			{
				$this->set_method(trim($this->uri->_filter_uri($_GET[$this->config->item('function_trigger')])));
			}

			return;
		}

		// #### ucitavanje ruta iz baze i routes.php datoteke - BEGIN ###

		// Fetch the complete URI string - dupliciran kod, potrebno da bi
		// ispod uri_string() funkcija radila
		$this->uri->_fetch_uri_string();
		$pageLink = correctPageLink($this->uri->uri_string());

		$getPageByLinkResult = $this->pages_model->GetPageByLink($pageLink);
		if(!empty($getPageByLinkResult)){
			$_SESSION['zt_page'] = $getPageByLinkResult;
			$pagePattern = $getPageByLinkResult->page_pattern;
			$pageRoute = $getPageByLinkResult->page_route;

			@include(APPPATH.'config/routes'.EXT);
			$this->routes['default_controller'] = $route['default_controller'];
			$this->routes['scaffolding_trigger'] = $route['scaffolding_trigger'];

			$this->routes[$pagePattern] = $pageRoute;

			//echo "<pre>";
			//print_r($getPageByLinkResult);
			//exit;
		} else {
			// Load the routes.php file.
			@include(APPPATH.'config/routes'.EXT);
			$this->routes = ( ! isset($route) OR ! is_array($route)) ? array() : $route;
			unset($route);

		}

		// #### ucitavanje ruta iz baze i routes.php datoteke - END	###


		// Set the default controller so we can display it in the event
		// the URI doesn't correlated to a valid controller.
		$this->default_controller = ( ! isset($this->routes['default_controller']) OR $this->routes['default_controller'] == '') ? FALSE : strtolower($this->routes['default_controller']);

		// Fetch the complete URI string
		$this->uri->_fetch_uri_string();

		// Is there a URI string? If not, the default controller specified in the "routes" file will be shown.
		if ($this->uri->uri_string == '')
		{
			if ($this->default_controller === FALSE)
			{
				show_error("Unable to determine what should be displayed. A default route has not been specified in the routing file.");
			}

			$this->set_class($this->default_controller);
			$this->set_method('index');
			$this->_set_request(array($this->default_controller, 'index'));

			// re-index the routed segments array so it starts with 1 rather than 0
			$this->uri->_reindex_segments();

			log_message('debug', "No URI present. Default controller set.");
			return;
		}
		unset($this->routes['default_controller']);

		// Do we need to remove the URL suffix?
		$this->uri->_remove_url_suffix();

		// Compile the segments into an array
		$this->uri->_explode_segments();

		// Parse any custom routing that may exist
		$this->_parse_routes();

		// Re-index the segment array so that it starts with 1 rather than 0
		$this->uri->_reindex_segments();
	}

	// --------------------------------------------------------------------


}

?>