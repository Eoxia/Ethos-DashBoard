<?php
/**
 * Mains actions of module
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2018 Eoxia <dev@eoxia.com>
 * @license   MIT <https://spdx.org/licenses/MIT.html>
 * @package   Ethos_Dashboard\Actions
 * @since     0.1.0
 */

namespace ethos_dashboard;

defined( 'ABSPATH' ) || exit;

/**
 * Core Action class.
 */
class Core_Action {

	/**
	 * Constructeur.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		add_action( 'tgmpa_register', array( Core_Util::g(), 'annonces_register_required_plugins' ) );
		add_action( 'admin_notices', array( $this, 'acf_version_notice' ) );

		add_action( 'init', array( $this, 'load_languages' ) );
		add_action( 'admin_init', array( $this, 'create_folder' ) );
	}

	/**
	 * Alert user to update ACF version
	 *
	 * @since 0.1.0
	 */
	public function acf_version_notice() {
		if ( ! is_acf() ) {
			return;
		}

		$acf_datas = get_plugin_data( PLUGIN_ETHOS_DASHBOARD_PATH . '/../advanced-custom-fields/acf.php' );
		if ( (int) substr( $acf_datas['Version'], 0, 1 ) < 5 ) {
			?>
			<div class="notice notice-error">
				<p><?php esc_html_e( 'Ethos Dashboard plugin work with ACF version 5+. Please update !', 'ethos_dashboard' ); ?></p>
			</div>
			<?php
		}
	}

	/**
	 * Initialise le fichier MO
	 *
	 * @since 0.1.0
	 */
	public function load_languages() {
		load_plugin_textdomain( 'ethos_dashboard', false, PLUGIN_ETHOS_DASHBOARD_DIR . '/core/asset/languages/' );
	}

	/**
	 * Créer le dossier contenant les txt.
	 *
	 * @since 0.1.0
	 */
	public function create_folder() {
		$ethos_dashboard_folder = get_option( \eoxia\Config_Util::$init['ethos-dashboard']->folder_meta_key, '' );

		if ( ! $ethos_dashboard_folder ) {
			$uploads = wp_upload_dir();

			$path        = str_replace( '\\', '/', $uploads['basedir'] );
			$folder_name = Core_Util::g()->random_str( 10 );
			$full_path   = $path . '/' . $folder_name . '/';

			if ( wp_mkdir_p( $full_path ) ) {
				update_option( \eoxia\Config_Util::$init['ethos-dashboard']->folder_meta_key, $folder_name );

				if ( $file = fopen( $full_path . '/.htaccess', 'w+' ) ) {
					fputs( $file, 'Options All -Indexes' );
					fclose( $file );
				}
			}
		}

	}
}

new Core_Action();
