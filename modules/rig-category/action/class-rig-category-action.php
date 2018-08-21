<?php
/**
 * Les actions des catégories de RIG.
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

		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ) );

		add_action( 'edited_terms', array( $this, 'callback_edit_terms' ), 10, 2 );
	}

	/**
	 * Register taxonomy "rig-category"
	 *
	 * @version 0.1.0
	 */
	public function callback_init() {
		$labels = array(
			'name'          => _x( 'RIG Groups', 'Taxonomy General Name', 'ethos-dashboard' ),
			'singular_name' => _x( 'RIG Group', 'Taxonomy Singular Name', 'ethos-dashboard' ),
			'menu_name'     => __( 'RIG Group', 'ethos-dashboard' ),
		);

		$args = array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => false,
			'show_ui'           => true,
			'show_in_menu'      => 'ethos-dashboard',
			'show_admin_column' => true,
		);

		register_taxonomy( Rig_Category_Class::g()->taxonomy, array( 'rig' ), $args );
	}

	/**
	 * Add rig group sub menu.
	 *
	 * @since 0.1.0
	 */
	public function callback_admin_menu() {
		add_submenu_page( 'ethos-dashboard', __( 'Rig Group', 'ethos_dashboard' ), __( 'Rig Group', 'ethos_dashboard' ), 'manage_options', 'edit-tags.php?taxonomy=' . Rig_Category_Class::g()->taxonomy );
	}

	/**
	 * This method regenerate all txt files from the current category;
	 *
	 * @since 0.1.0
	 *
	 * @param integer $term_id  L'ID du term.
	 * @param string  $taxonomy Le nom de la taxonomy.
	 */
	public function callback_edit_terms( $term_id, $taxonomy ) {
		if ( Rig_Category_Class::g()->taxonomy === $taxonomy ) {
			// Tous les rigs ayant cette catégorie.
			// Regénéré le fichier TXT.
			$rigs = get_posts( array(
				'post_type'      => 'rig',
				'posts_per_page' => -1,
			) );

			if ( ! empty( $rigs ) ) {
				foreach ( $rigs as $rig ) {
					$rig_id        = $rig->ID;
					$category      = get_term( $term_id, Rig_Category_Class::g()->taxonomy );
					$category->acf = Rig_Category_Class::g()->get_fields( $category->term_id );
					$wallet        = Wallet_Class::g()->get_fields( $category->acf['wallet_id'] );
					$rig           = Rig_Class::g()->get_fields( $rig->ID );

					unset( $category->acf['wallet_id'] );

					Rig_Class::g()->generate( $rig_id, $rig, $wallet, $category->acf );
				}
			}
		}
	}
}

new Rig_Category_Action();
