<?php
/**
 * Plugin Name: ethos-dashboard
 * Plugin URI: http://www.eoxia.com
 * Description: Handle ethos config
 * Version: 0.1.0
 * Author: Eoxia <dev@eoxia.com>
 * Author URI: http://www.eoxia.com
 * License: AGPLv3
 * License URI: <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package EthosDashboard
 */

namespace ethos_dashboard;

DEFINE( 'PLUGIN_ETHOS_DASHBOARD_PATH', realpath( plugin_dir_path( __FILE__ ) ) . '/' );
DEFINE( 'PLUGIN_ETHOS_DASHBOARD_URL', plugins_url( basename( __DIR__ ) ) . '/' );
DEFINE( 'PLUGIN_ETHOS_DASHBOARD_DIR', basename( __DIR__ ) );

// Include EO_Framework.
require_once 'core/external/eo-framework/eo-framework.php';

DEFINE( 'ACF_EARLY_ACCESS', '5' );

// Boot your plugin.
\eoxia\Init_Util::g()->exec( PLUGIN_ETHOS_DASHBOARD_PATH, basename( __FILE__, '.php' ) );
