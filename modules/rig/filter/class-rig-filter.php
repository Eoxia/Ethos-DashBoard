<?php
/**
 * Permet de désactiver le champ "lien_txt" afin qu'il ne soit pas prise en
 * compte lors de la modification d'un RIG.
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
 * Rig Filter class.
 */
class Rig_Filter {

	/**
	 * Constructeur.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		add_filter( 'acf/load_field/name=lien_txt', array( $this, 'disable_acf_load_field' ) );

		add_filter( 'manage_rig_posts_columns', array( $this, 'callback_rig_posts_columns' ) );
		add_filter( 'manage_rig_posts_custom_column', array( $this, 'callback_rig_posts_custom_column' ), 10, 2 );
	}

	/**
	 * Rend le champ "lien_txt" disable.
	 *
	 * @since 0.1.0
	 *
	 * @param array $field Les données du champ.
	 *
	 * @return array       Les données du champ avec le disabled
	 */
	public function disable_acf_load_field( $field ) {
		$field['disabled'] = 1;
		return $field;
	}

	/**
	 * Ajoutes les colones "Generate date" et "Preview TXT".
	 *
	 * @since 0.2.0
	 *
	 * @param array $columns Les colonnes à rajouter.
	 *
	 * @return array         Les colonnes à rajouter.
	 */
	public function callback_rig_posts_columns( $columns ) {
		$columns['generate_date'] = __( 'Generate date file', 'ethos-dashboard' );
		$columns['preview_txt']   = __( 'Preview TXT', 'ethos-dashboard' );
		$columns['link_txt']      = __( 'TXT link', 'ethos-dashboard' );
		return $columns;
	}

	/**
	 * Le contenu des colonnes par post.
	 *
	 * @since 0.2.0
	 *
	 * @param string  $column  Le slug de la colonne.
	 * @param integer $post_id L'ID du post.
	 */
	public function callback_rig_posts_custom_column( $column, $post_id ) {
		$output = '';

		switch ( $column ) {
			case 'generate_date':
				$path_txt  = get_post_meta( $post_id, 'path_txt', true );

				if ( file_exists( $path_txt ) ) {
					$timestamp = filemtime( $path_txt );
					$date      = date( 'Y/m/d', $timestamp );
					$full_date = date( 'Y/m/d g:m:s a', $timestamp );
					$output    = __( 'Last Modified' );
					$output   .= '<br /><abbr title="' . $full_date . '">' . $date . '</abbr>';
					echo $output;
				} else {
					_e( 'File not generated yet', 'ethos-dashboard' );
				}
				break;
			case 'preview_txt':
				$txt_url        = get_post_meta( $post_id, 'lien_txt', true );
				$displayed_data = get_post_meta( $post_id, 'column_data', true );
				$full_data      = get_post_meta( $post_id, 'title_column_data', true );
				$errors         = get_post_meta( $post_id, 'errors', true );
				$output_title   = '';


				if ( ! empty( $errors ) ) {
					echo '<pre>';
					_e( 'Preview is not available.', 'ethos-dashboard' );
					echo PHP_EOL;
					printf(  __( 'This rig contains %d errors: ', 'ethos-dashboard' ), count( $errors ) );
					echo PHP_EOL;
					echo esc_html( join( $errors, PHP_EOL ) );
					echo '</pre>';
				} else {

					if ( ! empty( $displayed_data ) ) {

						if ( ! empty( $full_data ) ) {
							foreach ( $full_data as $key => $value ) {
								if ( ! empty( $value ) ) {
									if ( ! in_array( $key, Rig_Class::g()->displayed_column_data, true ) ) {
										$output_title .= $key . ' ';
									}

									$output_title .= $value . PHP_EOL;
								}
							}
						}

						$output = '<abbr title="' . $output_title . '">' . join( $displayed_data, PHP_EOL ) . '</abbr>';
						echo '<pre>';
						echo $output;
						echo '</pre>';
					} else {
						_e( 'Preview not available.', 'ethos-dashboard' );
					}
				}
				break;
			case 'link_txt':
				$txt_url = get_post_meta( $post_id, 'lien_txt', true );
				$errors  = get_post_meta( $post_id, 'errors', true );

				if ( empty( $errors ) ) {
					printf( __( '<a href="%s">%s</a>', 'ethos-dashboard' ), $txt_url, substr( $txt_url, 0, 20 ) . '...' . substr( $txt_url, strlen( $txt_url ) - 20, strlen( $txt_url ) - 5 ) );
				} else {
					_e( 'Unable to retrieve txt link', 'ethos-dashboard' );
				}
				break;
		}

	}
}

new Rig_Filter();
