<?php
/**
 * Gestion des filtres des groupes de rig.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2018 Eoxia <dev@eoxia.com>
 * @license   MIT <https://spdx.org/licenses/MIT.html>
 * @package   Ethos_Dashboard\Filters
 * @since     0.2.0
 */

namespace ethos_dashboard;

defined( 'ABSPATH' ) || exit;

/**
 * Rig Category Filter class.
 */
class Rig_Category_Filter {

	/**
	 * Constructeur.
	 *
	 * @since 0.2.0
	 */
	public function __construct() {
		add_filter( 'manage_edit-rig_taxonomy_columns', array( $this, 'callback_taxonomy_columns' ) );
		add_filter( 'manage_rig_taxonomy_custom_column', array( $this, 'callback_taxonomy_custom_column' ), 10, 3 );
	}

	/**
	 * Supprimes les colones descriptions et slug.
	 * Ajoutes la colone "Wallet".
	 *
	 * @since 0.2.0
	 *
	 * @param  array $columns Les colonnes à modifier.
	 * @return array          Les nouvelles colonnes.
	 */
	public function callback_taxonomy_columns( $columns ) {
		if ( isset( $columns['description'] ) ) {
			unset( $columns['description'] );
		}

		if ( isset( $columns['slug'] ) ) {
			unset( $columns['slug'] );
		}

		$columns['wallet'] = __( 'Wallet', 'ethos-dashboard' );

		return $columns;
	}

	public function callback_taxonomy_custom_column( $column, $column_name, $term_id ) {
		switch ( $column_name ) {
			case 'wallet':
				$id        = Rig_Category_Class::g()->taxonomy . '_' . $term_id;
				$wallet_id = get_field( 'wallet_id', $id );

				if ( ! empty( $wallet_id ) ) {
					echo get_the_title( $wallet_id );
				} else {
					_e( 'No wallet associated', 'ethos-dashboard' );
				}
				break;
		}
	}
}

new Rig_Category_Filter();
