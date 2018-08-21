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

		add_action( 'add_meta_boxes_rig', array( $this, 'callback_meta_boxes' ) );

		add_action( 'acf/render_field', array( $this, 'callback_link_txt' ), 21, 1 );

		add_action( 'wp_ajax_load_modal_regenerate', array( $this, 'callback_load_modal_regenerate' ) );
		add_action( 'wp_ajax_regenerate_txt', array( $this, 'callback_regenerate_txt' ) );
	}

	/**
	 * Register RIG post type
	 *
	 * @version 0.1.0
	 */
	public function callback_init() {
		$labels = array(
			'name'          => _x( 'Rigs', 'Post Type General Name', 'ethos-dashboard' ),
			'singular_name' => _x( 'Rig', 'Post Type Singular Name', 'ethos-dashboard' ),
			'menu_name'     => __( 'Rigs', 'ethos-dashboard' ),
		);

		$args = array(
			'label'               => __( 'Rig', 'ethos-dashboard' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'revisions' ),
			'taxonomies'          => array( Rig_Category_Class::g()->taxonomy ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => 'ethos-dashboard',
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
			$categories = wp_get_post_terms( $post->ID, Rig_Category_Class::g()->taxonomy );

			if ( ! empty( $categories[0] ) ) {
			$category   = $categories[0];

				$category->acf = Rig_Category_Class::g()->get_fields( $category->term_id );
				$wallet        = Wallet_Class::g()->get_fields( $category->acf['wallet_id'] );
				$rig           = Rig_Class::g()->get_fields( $post_id );

				unset( $category->acf['wallet_id'] );

				Rig_Class::g()->generate( $post_id, $rig, $wallet, $category->acf );
			}
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

	/**
	* Ajoutes la metabox permettant de préviualiser le résultat du fichier .txt.
	*
	* @since 0.2.0
	*/
	public function callback_meta_boxes() {
		add_meta_box( 'rig-preview-txt', __( 'Preview TXT', 'ethos-dashboard' ), array( $this, 'render_preview_txt' ), 'rig', 'side' );
	}

	/**
	* Gestion du rendu de la metabox.
	*
	* @since 0.2.0
	*/
	public function render_preview_txt() {
		$post_id  = get_the_ID();
		$txt_path = get_post_meta( $post_id, 'path_txt', true );
		$errors   = get_post_meta( $post_id, 'errors', true );

		if ( ! empty( $errors ) ) {
			echo '<pre>';
			_e( 'Preview is not available.', 'ethos-dashboard' );
			echo PHP_EOL;
			printf(  __( 'This rig contains %d errors: ', 'ethos-dashboard' ), count( $errors ) );
			echo PHP_EOL;
			echo esc_html( join( $errors, PHP_EOL ) );
			echo '</pre>';
		} else {
			if ( file_exists( $txt_path ) ) {
				$content = file_get_contents( $txt_path );
				echo '<pre>';
				echo esc_html( $content );
				echo '</pre>';
			} else {
				_e( 'Preview is not available. Publish or Update your rig setting.', 'ethos-dashboard' );
			}
		}
	}

	/**
	 * Charges la vue de la modal pour la génération des fichiers TXT.
	 * Prépares des données pour la vue. Groupes dans un tableau les rigs 20
	 * par 20. Construit un fullname qui correspond au post_title des 20 rigs.
	 *
	 * @since 0.2.0
	 */
	public function callback_load_modal_regenerate() {
		$i = 0;

		$rigs = get_posts( array(
			'post_type'      => 'rig',
			'posts_per_page' => -1,
		) );

		$group_rigs     = array();
		$tmp_rigs_array = array();
		$fullname       = '';
		$ids            = '';

		while ( $i < \eoxia\Config_Util::$init['ethos-dashboard']->rig->generate_mass_number ) {
			if ( empty( $rigs[ $i ] ) ) {
				$fullname = substr( $fullname, 0, -2 );
				$ids      = substr( $ids, 0, -1 );

				$group_rigs[] = array(
					'rigs'     => $tmp_rigs_array,
					'fullname' => $fullname,
					'ids'      => $ids,
				);
				// Break the loop if no rig found.
				break;
			}

			$tmp_rigs_array[] = $rigs[ $i ];
			$fullname        .= '<span data-id="' . $rigs[ $i ]->ID . '">' . $rigs[ $i ]->post_title . '</span>, ';
			$ids             .= $rigs[ $i ]->ID .',';


			if ( $i / \eoxia\Config_Util::$init['ethos-dashboard']->rig->generate_mass_number === 1 || $i >= count( $rigs ) ) {
				$fullname = substr( $fullname, 0, -2 );
				$ids      = substr( $ids, 0, -1 );

				$group_rigs[] = array(
					'rigs'     => $tmp_rigs_array,
					'fullname' => $fullname,
					'ids'      => $ids,
				);

				$tmp_rigs_array = array();
			}

			if ( $i >= count( $rigs ) ) {
				break;
			}

			$i++;
		}

		ob_start();
		\eoxia\View_Util::exec( 'ethos-dashboard', 'rig', 'modal-list', array(
			'group_rigs' => $group_rigs,
			'total'      => $i,
		) );
		$view = ob_get_clean();

		wp_send_json_success( array(
			'view' => $view,
		) );
	}

	/**
	 * Regnères en masses les TXT de tous les rigs.
	 *
	 * @since 0.2.0
	 */
	public function callback_regenerate_txt() {
		check_ajax_referer( 'regenerate_txt' );

		$ids            = ! empty( $_POST['ids'] ) ? sanitize_text_field( $_POST['ids'] ) : '';
		$number_error   = ! empty( $_POST['number_error'] ) ? (int) $_POST['number_error'] : 0;
		$number_success = ! empty( $_POST['number_success'] ) ? (int) $_POST['number_success'] : 0;
		$total          = ! empty( $_POST['total'] ) ? (int) $_POST['total'] : 0;

		if ( empty( $ids ) ) {
			wp_send_json_error();
		}

		$ids = explode( ',', $ids );

		$error_message = array();

		if ( ! empty( $ids ) ) {
			foreach ( $ids as $id ) {
				$categories = wp_get_post_terms( $id, Rig_Category_Class::g()->taxonomy );

				if ( ! empty( $categories[0] ) ) {
					$category   = $categories[0];

					$category->acf = Rig_Category_Class::g()->get_fields( $category->term_id );
					$wallet        = Wallet_Class::g()->get_fields( $category->acf['wallet_id'] );
					$rig           = Rig_Class::g()->get_fields( $id );

					unset( $category->acf['wallet_id'] );

					$state = Rig_Class::g()->generate( $id, $rig, $wallet, $category->acf );
					if ( true === $state ) {
						$number_success++;
					} else {
						$number_error++;
						$rig  = get_post( $id );
						$link = '<a target="_blank" href="' . get_edit_post_link( $id ) . '">' . __( 'Fix it', 'ethos-dashboard' ) . '</a>';

						$error_message[ $id ] = sprintf( __( 'Unable to generate the txt file of the rig: %s, %s<br />Errors: <br /> %s', 'ethos-dashboard' ), $rig->post_title, $link, join( $state, '<br />' ) );
					}
				} else {
					$number_error++;
					$rig  = get_post( $id );
					$link = '<a target="_blank" href="' . get_edit_post_link( $id ) . '">' . __( 'Fix it', 'ethos-dashboard' ) . '</a>';

					$error_message[ $id ] = sprintf( __( 'Unable to generate the txt file of the rig: %s, no group rig was found on it. %s', 'ethos-dashboard' ), $rig->post_title, $link );
				}
			}
		}

		$final_message = '';

		if ( $number_error + $number_success >= $total ) {
			$error         = sprintf( _n( '%d error', '%d errors', $number_error, 'ethos-dashboard' ), number_format_i18n( $number_error ) );
			$success       = sprintf( _n( '%d success', '%d successes', $number_success, 'ethos-dashboard' ), number_format_i18n( $number_success ) );
			$final_message = sprintf( __( 'Generation is complete with %s and %s on a total of %d rigs' ), $error, $success, $total );
		}

		wp_send_json_success( array(
			'number_error'   => $number_error,
			'number_success' => $number_success,
			'total'          => $total,
			'error_message'  => $error_message,
			'final_message'  => $final_message,
		) );
	}
}

new Rig_Action();
