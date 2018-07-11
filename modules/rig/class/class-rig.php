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
	 * Constructor.
	 *
	 * @since 0.1.0
	 */
	protected function construct() {}

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

		$upload_path = Core_Util::g()->get_upload_path();

		$output = '';
		if ( ! empty( $data ) ) {
			foreach ( $data as $key => $value ) {
				if ( ! empty( $value ) ) {
					$output .= $key . ' ' . $value . "
";
				}
			}
		}

		$uploads = wp_upload_dir();

		$full_url = get_post_meta( $id, 'lien_txt', true );

		if ( ! empty( $full_url ) ) {
			$full_path = str_replace( '\\', '/', str_replace( $uploads['baseurl'] , $uploads['basedir'], $full_url ) );
		} else {
			$full_path = $upload_path . Core_Util::g()->random_str( 64 ) . '.txt';
		}

		if ( $file = fopen( $full_path, 'w+' ) ) {
			fputs( $file, $output );
			fclose( $file );


			$url = str_replace( str_replace( '\\', '/', $uploads['basedir'] ), $uploads['baseurl'], $full_path );

			update_post_meta( $id, 'lien_txt', $url );
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
}

Rig_Class::g();
