<?php
/**
 * Gestion des fonctions pour les catégories.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2018 Eoxia <dev@eoxia.com>
 * @license   MIT <https://spdx.org/licenses/MIT.html>
 * @package   EthosDashboard\Classes
 * @since     0.1.0
 */

namespace ethos_dashboard;

defined( 'ABSPATH' ) || exit;

/**
 * Rig Category Class.
 */
class Rig_Category_Class extends \eoxia\Singleton_Util {

	/**
	 * Le slug de la taxonomy
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	public $taxonomy = 'rig_taxonomy';

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 */
	protected function construct() {}

	/**
	 * Récupères les champs des catégories de risque selon $term_id.
	 * Cette méthode récupères les champs: maxgputemp, globalfan, custompanel.
	 *
	 * @since 0.1.0
	 *
	 * @param  integer $term_id L'ID de la catégorie.
	 *
	 * @return array
	 */
	public function get_fields( $term_id ) {
		$data = array();

		$id = Rig_Category_Class::g()->taxonomy . '_' . $term_id;

		$data['maxgputemp']  = get_field( 'maxgputemp', $id );
		$data['globalfan']   = get_field( 'globalfan', $id );
		$data['custompanel'] = get_field( 'custompanel', $id );
		$data['wallet_id']   = get_field( 'wallet_id', $id );

		return $data;
	}
}

Rig_Category_Class::g();
