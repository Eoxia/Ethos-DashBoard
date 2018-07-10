<?php
/**
 * Les actions des WALLET.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2018 Eoxia <dev@eoxia.com>
 * @license   MIT <https://spdx.org/licenses/MIT.html>
 * @package   Ethos_Dashboard\Actions
 * @since     0.1.0
 */

namespace ethos_dashboard;

defined( 'ABSPATH' ) || exit;

/**
 * Wallet Action class.
 */
class Wallet_Action {

	/**
	 * Constructor.
	 *
	 * @version 0.1.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'callback_init' ) );
	}

	/**
	 * Register Wallets post type
	 *
	 * @version 0.1.0
	 */
	public function callback_init() {

		$labels = array(
			'name'          => _x( 'Wallets', 'Post Type General Name', 'ethos-dashboard' ),
			'singular_name' => _x( 'Wallet', 'Post Type Singular Name', 'ethos-dashboard' ),
			'menu_name'     => __( 'Wallets', 'ethos-dashboard' ),
		);

		$args = array(
			'label'               => __( 'Wallet', 'ethos-dashboard' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'revisions' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-layout',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
		);
		register_post_type( 'wallet', $args );

	}
}

new Wallet_Action();
