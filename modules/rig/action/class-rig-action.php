<?php
/**
 * Action of RIG module.
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
 * Rig Action class.
 */
class Rig_Action {

	/**
	 * Constructor.
	 *
	 * @version 0.1.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'callback_init' ) );
		add_action( 'save_post', array( $this, 'callback_save_post' ), 20, 3 );
		add_action( 'acf/render_field', array( $this, 'callback_link_txt' ), 21, 1 );
	}

	/**
	 * Register RIG post type
	 *
	 * @version 0.1.0
	 */
	public function callback_init() {

		$labels = array(
			'name'          => _x( 'Rigs', 'Post Type General Name', 'wp-ethos-dashboard' ),
			'singular_name' => _x( 'Rig', 'Post Type Singular Name', 'wp-ethos-dashboard' ),
			'menu_name'     => __( 'Rigs', 'wp-ethos-dashboard' ),
		);

		$args = array(
			'label'               => __( 'Rig', 'wp-ethos-dashboard' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'revisions' ),
			'taxonomies'          => array( Rig_Category_Class::g()->taxonomy ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-desktop',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
		);

		register_post_type( 'rig', $args );
	}

	/**
	 * Génère le fichier .txt.
	 *
	 * @since 0.1.0
	 *
	 * @param  integer $post_id L'ID du post.
	 * @param  WP_Post $post    Les données du post de WordPress.
	 * @param  boolean $update  True si c'est une mise à jour du POST.
	 */
	public function callback_save_post( $post_id, $post, $update ) {
		if ( 'rig' === $post->post_type && $update ) {
			$category = get_term( get_field( 'rig_category', $post_id ), Rig_Category_Class::g()->taxonomy );

			$category->acf = Rig_Category_Class::g()->get_fields( $category->term_id );
			$wallet        = Wallet_Class::g()->get_fields( $category->acf['wallet_id'] );
			$rig           = Rig_Class::g()->get_fields( $post_id );

			unset( $category->acf['wallet_id'] );

			Rig_Class::g()->generate( $post_id, $rig, $wallet, $category->acf );
		}
	}

	/**
	 * Ajoutes un bouton permettant de copier le lien dans le presse papier.
	 *
	 * @since 0.1.0
	 *
	 * @param  array $field Les données du champ.
	 */
	public function callback_link_txt( $field ) {
		if ( 'lien_txt' === $field['_name'] ) {
			$output = __( 'Copy to clipboard!', 'ethos-dashboard' );
			echo '<i aria-label="' . esc_attr( $output ) . '" class="alignright wpeo-tooltip-event fas fa-copy"></i>';
		}
	}
}

new Rig_Action();
