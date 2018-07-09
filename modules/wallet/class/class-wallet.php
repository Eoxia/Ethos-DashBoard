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
class Wallet_Class extends \eoxia\Singleton_Util {

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
	 * Récupères les champs du wallet selon $post_id.
	 *
	 * @since 0.1.0
	 *
	 * @param integer $post_id L'ID du post.
	 *
	 * @return array
	 */
	public function get_fields( $post_id ) {
		$data = array();

		$data['globalminer']         = get_field( 'globalminer', $post_id );
		$data['name']                = get_field( 'name', $post_id );
		$data['poolemail']           = get_field( 'poolemail', $post_id );
		$data['proxywallet']         = get_field( 'proxywallet', $post_id );
		$data['proxypool1']          = get_field( 'proxypool1', $post_id );
		$data['proxypool2']          = get_field( 'proxypool2', $post_id );
		$data['poolpass1']           = get_field( 'poolpass1', $post_id );
		$data['poolpass2']           = get_field( 'poolpass2', $post_id );
		$data['secondcoin']          = get_field( 'secondcoin', $post_id );
		$data['secondcoinintensity'] = get_field( 'secondcoinintensity', $post_id );
		$data['stratumproxy']        = get_field( 'stratumproxy', $post_id );
		$data['flags']               = get_field( 'flags', $post_id );

		return $data;
	}
}

Wallet_Class::g();
