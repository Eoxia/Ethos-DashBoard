<?php
/**
 * Les réglages d'ethos dashboard.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2018 Eoxia <dev@eoxia.com>
 * @license   MIT <https://spdx.org/licenses/MIT.html>
 * @package   Ethos_Dashboard\Actions
 * @since     0.2.0
 */

namespace ethos_dashboard;

defined( 'ABSPATH' ) || exit;

/**
 * Setting Action class.
 */
class Setting_Action {

	/**
	 * Constructor.
	 *
	 * @since 0.2.0
	 */
	public function __construct() {
		// add_action( 'admin_menu', array( $this, 'callback_admin_menu' ) );
	}

	/**
	 * Ajoutes la page réglage.
	 *
	 * @since 0.2.0
	 */
	public function callback_admin_menu() {
		// add_submenu_page( 'options-general.php', __( 'Ethos Dashboard', 'ethos-dashboard' ), __( 'Ethos Dashboard', 'ethos-dashboard' ), 'manage_options', 'announces-options', array( $this, 'callback_add_menu_page' ) );
	}

	/**
	 * [callback_add_menu_page description]
	 */
	public function callback_add_menu_page() {

	}
}

new Setting_Action();
