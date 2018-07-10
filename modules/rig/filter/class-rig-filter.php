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
}
