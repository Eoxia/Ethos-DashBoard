<?php
/**
 * Gestion des fonctions pour les rig.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2018 Eoxia <dev@eoxia.com>
 * @license   MIT <https://spdx.org/licenses/MIT.html>
 * @package   Ethos_Dashboard\Classes
 * @since     0.1.0
 */

namespace ethos_dashboard;

defined( 'ABSPATH' ) || exit;

/**
 * Rig Class.
 */
class Rig_Class extends \eoxia\Singleton_Util {

	/**
	 * Les slug des données à enregistrer en base de donnée après traitement.
	 *
	 * @since  0.2.0
	 * @access public
	 * @var    array   $displayed_column_data Les slug des données à enregistrer
	 * en base de donnée après traitement.
	 */
	public $displayed_column_data = array( 'proxywallet', 'poolemail', 'proxypool1' );

	/**
	 * Les messages d'erreurs reliée au donnée.
	 *
	 * @since 0.2.0
	 * @access private
	 * @var array       $data_errors_message Les messages d'erreurs reliée au donnée.
	 */
	private $data_errors_message;

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 */
	protected function construct() {
		$this->data_errors_message['proxywallet'] = __( 'Wallet not found', 'ethos-dashboard' );
		$this->data_errors_message['poolemail']   = __( 'Poolemail not found', 'ethos-dashboard' );
		$this->data_errors_message['proxypool1']  = __( 'Proxypool1 not found', 'ethos-dashboard' );
	}

	/**
	 * Récupères les champs du rig selon $post_id.
	 *
	 * @since 0.1.0
	 *
	 * @param integer $post_id L'ID du post.
	 *
	 * @return array
	 */
	public function get_fields( $post_id ) {
		$data = array();

		$data['maxgputemp']      = get_field( 'maxgputemp', $post_id );
		$data['globalfan']       = get_field( 'globalfan', $post_id );
		$data['globalcore']      = get_field( 'globalcore', $post_id );
		$data['globalmem']       = get_field( 'globalmem', $post_id );
		$data['globalpowertune'] = get_field( 'globalpowertune', $post_id );
		$data['autoreboot']      = get_field( 'autoreboot', $post_id );
		$data['globalname']      = get_field( 'globalname', $post_id );
		$data['loc']             = get_field( 'worker', $post_id ) . ' ' . get_field( 'newname', $post_id );

		return $data;
	}

	/**
	 * Génère le fichier txt.
	 *
	 * @param array $data .
	 *
	 * @since 0.1.0
	 */
	public function generate( $id, $rig_data, $wallet_data, $category_data ) {
		$data = array_merge( $wallet_data, $category_data, $rig_data );
		$data = $this->order_data( $data );

		$errors = array();

		foreach ( $this->data_errors_message as $key => $error_message ) {
			if ( empty( $data[ $key ] ) ) {
				$errors[] = $error_message;
			}
		}

		if ( 0 === count( $errors ) ) {
			$upload_path = Core_Util::g()->get_upload_path();

			$output = '';
			if ( ! empty( $data ) ) {
				foreach ( $data as $key => $value ) {
					if ( ! empty( $value ) ) {
						$output .= $key . ' ' . $value . PHP_EOL;
					}
				}
			}

			$uploads = wp_upload_dir();

			$full_url = get_post_meta( $id, 'lien_txt', true );
			$full_url = get_post_meta( $id, 'path_txt', true );

			if ( ! empty( $full_url ) ) {
				$full_path = str_replace( '\\', '/', str_replace( $uploads['baseurl'], $uploads['basedir'], $full_url ) );
			} else {
				$full_path = $upload_path . Core_Util::g()->random_str( 64 ) . '.txt';
			}

			if ( $file = fopen( $full_path, 'w+' ) ) {
				if ( fputs( $file, $output ) ) {
					\eoxia\LOG_Util::log( 'Update file ' . $full_path . '<br />' . $output, 'ethos-dashboard' );
				}
				fclose( $file );

				$url = str_replace( str_replace( '\\', '/', $uploads['basedir'] ), $uploads['baseurl'], $full_path );

				$column_data       = $this->process_column_data( $data );
				$title_column_data = array_merge( $data, $column_data );

				update_post_meta( $id, 'column_data', $column_data );
				update_post_meta( $id, 'title_column_data', $title_column_data );
				update_post_meta( $id, 'lien_txt', $url );
				update_post_meta( $id, 'path_txt', $full_path );
				delete_post_meta( $id, 'errors' );
				return true;
			}
		} else {
			update_post_meta( $id, 'errors', $errors );
			return $errors;
		}
	}

	/**
	 * Cette méthode met les données dans le bon ordre selon le fichier json:
	 * ./modules/rig/asset/json/order.json.
	 *
	 * @since 0.1.0
	 *
	 * @param  array $data Les données non ordonnées.
	 *
	 * @return array       Les données ordonnées.
	 */
	public function order_data( $data ) {
		$sorted_array = array();

		$path = \eoxia\Config_Util::$init['ethos-dashboard']->rig->path . '/asset/json/order.json';

		$file   = file_get_contents( $path );
		$orders = json_decode( $file );

		if ( ! empty( $orders ) ) {
			foreach ( $orders as $order ) {
				if ( isset( $data[ $order ] )  ) {
					$sorted_array[ $order ] = $data[ $order ];
				}
			}
		}

		return $sorted_array;
	}

	/**
	 * Traites les trois données principaux pour les colonnes. Effaces certaines
	 * parties des données.
	 *
	 * @since 0.2.0
	 *
	 * @param  array $data Les données à modifier.
	 * @return array       Les données modifiées.
	 */
	private function process_column_data( $data ) {
		$founded_data = array();

		if ( ! empty( $data ) ) {
			foreach ( $data as $key => $value ) {
				if ( in_array( $key, $this->displayed_column_data, true ) ) {
					$founded_data[ $key ] = $value;
				}
			}
		}

		$founded_data['proxywallet'] = 'wallet .....' . substr( $founded_data['proxywallet'], strlen( $founded_data['proxywallet'] ) - 3, 3 );

		// Process poolemail: Récupères les deux premières lettres et le deux dernières lettres.
		$poolemail_begin = substr( $founded_data['poolemail'], 0, 2 );
		$poolemail_end   = substr( $founded_data['poolemail'], strlen( $founded_data['poolemail'] ) - 5, 5 );

		$founded_data['poolemail']  = 'poolemail ' . $poolemail_begin . '...@...' . $poolemail_end;
		$founded_data['proxypool1'] = 'proxypool1 ' . $founded_data['proxypool1'];

		return $founded_data;
	}
}

Rig_Class::g();
