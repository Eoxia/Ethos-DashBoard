<?php
/**
 * Functions available
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2018 Eoxia <dev@eoxia.com>
 * @license   MIT <https://spdx.org/licenses/MIT.html>
 * @package   EthosDashboard\Helper
 * @since     0.1.0
 */

namespace ethos_dashboard;

defined( 'ABSPATH' ) || exit;

/**
 * Return true if acf exists
 *
 * @since 0.1.0
 *
 * @return boolean
 */
function is_acf() {
	if ( class_exists( 'acf' ) ) {
		return true;
	}

	return false;
}
