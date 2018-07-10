<?php
/**
 * Action of Core module.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2018 Eoxia <dev@eoxia.com>
 * @license   MIT <https://spdx.org/licenses/MIT.html>
 * @package   Ethos_Dashboard\Filters
 * @since     0.1.0
 */

namespace ethos_dashboard;

defined( 'ABSPATH' ) || exit;

/**
 * Ethos Dashboard Filter class.
 */
class Ethos_Dashboard_Filter {

	/**
	 * Constructor
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		add_filter( 'acf/settings/load_json', array( $this, 'annonces_annonce_json_load' ) );
	}

	/**
	 * Add a json acf directory
	 *
	 * @since  0.1.0
	 *
	 * @param  Array $paths Acf folders.
	 *
	 * @return Array $paths Acf folders
	 */
	public function annonces_annonce_json_load( $paths ) {
		$paths[] = PLUGIN_ETHOS_DASHBOARD_PATH . 'core/asset/json';
		return $paths;
	}
}

new Ethos_Dashboard_Filter();
