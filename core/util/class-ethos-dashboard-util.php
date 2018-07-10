<?php
/**
 * Les fonctions "utils" principale du plugin.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2018 Eoxia <dev@eoxia.com>
 * @license   MIT <https://spdx.org/licenses/MIT.html>
 * @package   Ethos_Dashboard\Util
 * @since     0.1.0
 */

namespace ethos_dashboard;

defined( 'ABSPATH' ) || exit;

/**
 * Core Util class.
 */
class Core_Util extends \eoxia\Singleton_Util {

	/**
	 * Constructeur.
	 *
	 * @since 0.1.0
	 */
	protected function construct() {}

	/**
	 * Donne le chemin de la vue à inclure dans le module
	 *
	 * @since 0.1.0
	 */
	public function annonces_register_required_plugins() {
		/*
		 * Array of plugin arrays. Required keys are name and slug.
		 * If the source is NOT from the .org repo, then source is also required.
		 */
		$plugins = array(
			array(
				'name'     => 'Advanced Custom Fields',
				'slug'     => 'advanced-custom-fields',
				'required' => true,
			),
		);

		/*
		 * Array of configuration settings. Amend each line as needed.
		 *
		 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
		 * strings available, please help us make TGMPA even better by giving us access to these translations or by
		 * sending in a pull-request with .po file(s) with the translations.
		 *
		 * Only uncomment the strings in the config array if you want to customize the strings.
		 */
		$config = array(
			'id'           => 'ethos-dashboard',        // Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => '',                      // Default absolute path to bundled plugins.
			'menu'         => 'tgmpa-install-plugins', // Menu slug.
			'parent_slug'  => 'plugins.php',            // Parent menu slug.
			'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => true,                    // Show admin notices or not.
			'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,                   // Automatically activate plugins after installation or not.
			'message'      => '',                      // Message to output right before the plugins table.
			'strings'      => array(
				'page_title'                      => __( 'Install Required Plugins', 'ethos-dashboard' ),
				'menu_title'                      => __( 'Install Plugins', 'ethos-dashboard' ),
				'installing'                      => __( 'Installing Plugin: %s', 'ethos-dashboard' ), // %1$s = plugin name
				'oops'                            => __( 'Something went wrong with the plugin API.', 'ethos-dashboard' ),
				'notice_can_install_required'     => _n_noop( 'This plugin requires the following plugin installed or update: %1$s.', 'This plugin requires the following plugins installed or updated: %1$s.', 'ethos-dashboard' ), // %1$s = plugin name(s)
				'notice_can_install_recommended'  => _n_noop( 'This plugin recommends the following plugin installed or updated: %1$s.', 'This plugin recommends the following plugins installed or updated: %1$s.', 'ethos-dashboard' ), // %1$s = plugin name(s)
				'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'ethos-dashboard' ), // %1$s = plugin name(s)
				'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'ethos-dashboard' ), // %1$s = plugin name(s)
				'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'ethos-dashboard' ), // %1$s = plugin name(s)
				'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'ethos-dashboard' ), // %1$s = plugin name(s)
				'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this plugin: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this plugin: %1$s.', 'ethos-dashboard' ), // %1$s = plugin name(s)
				'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'ethos-dashboard' ), // %1$s = plugin name(s)
				'install_link'                    => _n_noop( 'Go Install Plugin', 'Go Install Plugins', 'ethos-dashboard' ),
				'activate_link'                   => _n_noop( 'Go Activate Plugin', 'Go Activate Plugins', 'ethos-dashboard' ),
				'return'                          => __( 'Return to Required Plugins Installer', 'ethos-dashboard' ),
				'plugin_activated'                => __( 'Plugin activated successfully.', 'ethos-dashboard' ),
				'complete'                        => __( 'All plugins installed and activated successfully. %s', 'ethos-dashboard' ), // %1$s = dashboard link
			),
		);

		tgmpa( $plugins, $config );
	}

	/**
	 * Génères une chaine aléatoire de la longueur $length
	 *
	 * @since 0.1.0
	 * @see https://stackoverflow.com/questions/4356289/php-random-string-generator/31107425#31107425
	 *
	 * @param  integer $length   La longueur de la chaine générée.
	 * @param  string  $keyspace Les caractères aléatoires.
	 *
	 * @return string          La chaine générée.
	 */
	public function random_str( $length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' ) {
		$pieces = [];
		$max    = mb_strlen( $keyspace, '8bit' ) - 1;

		for ( $i = 0; $i < $length; ++$i ) {
			$pieces[] = $keyspace[ random_int( 0, $max ) ];
		}

		return implode( '', $pieces );
	}

	/**
	 * Chemin absolue vers le dossier contenant les .txt.
	 *
	 * @since 0.1.0
	 *
	 * @return string Le chemin absolue.
	 */
	public function get_upload_path() {
		$ethos_dashboard_folder = get_option( \eoxia\Config_Util::$init['ethos-dashboard']->folder_meta_key, '' );

		$uploads   = wp_upload_dir();
		$path      = str_replace( '\\', '/', $uploads['basedir'] );
		$full_path = $path . '/' . $ethos_dashboard_folder . '/';

		return $full_path;
	}
}

Core_Util::g();
