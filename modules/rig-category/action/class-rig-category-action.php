<?php
/**
 * Action of Annonce module.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2018 Eoxia <dev@eoxia.com>
 * @license   MIT <https://spdx.org/licenses/MIT.html>
 * @package   EthosDashboard\Actions
 * @since     0.1.0
 */

namespace ethos_dashboard;

defined( 'ABSPATH' ) || exit;

/**
 * Rig Category Action class.
 */
class Rig_Category_Action {

	/**
	 * Constructor.
	 *
	 * @version 0.1.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'callback_init' ) );
	}

	/**
	 * Register taxonomy "rig-category"
	 *
	 * @version 0.1.0
	 */
	public function callback_init() {
		$labels = array(
			'name'          => _x( 'RIG taxonomies', 'Taxonomy General Name', 'wp-ethos-dashboard' ),
			'singular_name' => _x( 'RIG taxonomy', 'Taxonomy Singular Name', 'wp-ethos-dashboard' ),
			'menu_name'     => __( 'RIG taxonomy', 'wp-ethos-dashboard' ),
		);

		$args = array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
		);

		register_taxonomy( Rig_Category_Class::g()->taxonomy, array( 'rig' ), $args );
	}
}

new Rig_Category_Action();
