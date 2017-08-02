<?php
/**
 * The core plugin class.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class Easy_Affiliate_Links {

	/**
	 * Define any constants to be used in the plugin.
	 *
	 * @since    2.0.0
	 */
	private function define_constants() {
		define( 'EAFL_VERSION', '2.4.0' );
		define( 'EAFL_POST_TYPE', 'easy_affiliate_link' );
		define( 'EAFL_DIR', plugin_dir_path( dirname( __FILE__ ) ) );
		define( 'EAFL_URL', plugin_dir_url( dirname( __FILE__ ) ) );
	}

	/**
	 * Make sure all is set up for the plugin to load.
	 *
	 * @since    2.0.0
	 */
	public function __construct() {
		$this->define_constants();
		$this->load_dependencies();
		do_action( 'eafl_init' );
	}

	/**
	 * Load all plugin dependencies.
	 *
	 * @since    2.0.0
	 */
	private function load_dependencies() {
		// General.
		require_once( EAFL_DIR . 'includes/class-eafl-i18n.php' );

		// Priority.
		require_once( EAFL_DIR . 'includes/public/class-eafl-settings.php' );

		// Public.
		require_once( EAFL_DIR . 'includes/public/class-eafl-addons.php' );
		require_once( EAFL_DIR . 'includes/public/class-eafl-clicks.php' );
		require_once( EAFL_DIR . 'includes/public/class-eafl-clicks-database.php' );
		require_once( EAFL_DIR . 'includes/public/class-eafl-css.php' );
		require_once( EAFL_DIR . 'includes/public/class-eafl-link.php' );
		require_once( EAFL_DIR . 'includes/public/class-eafl-link-manager.php' );
		require_once( EAFL_DIR . 'includes/public/class-eafl-post-type.php' );
		require_once( EAFL_DIR . 'includes/public/class-eafl-redirect.php' );
		require_once( EAFL_DIR . 'includes/public/class-eafl-shortcode.php' );
		require_once( EAFL_DIR . 'includes/public/class-eafl-taxonomies.php' );

		// Admin.
		if ( is_admin() ) {
			require_once( EAFL_DIR . 'includes/admin/class-eafl-giveaway.php' );
			require_once( EAFL_DIR . 'includes/admin/class-eafl-license.php' );
			require_once( EAFL_DIR . 'includes/admin/class-eafl-link-sanitizer.php' );
			require_once( EAFL_DIR . 'includes/admin/class-eafl-link-saver.php' );
			require_once( EAFL_DIR . 'includes/admin/class-eafl-migrations.php' );
			require_once( EAFL_DIR . 'includes/admin/class-eafl-permalinks.php' );

			// Manage.
			require_once( EAFL_DIR . 'includes/admin/manage/class-eafl-manage-categories.php' );
			require_once( EAFL_DIR . 'includes/admin/manage/class-eafl-manage-export.php' );
			require_once( EAFL_DIR . 'includes/admin/manage/class-eafl-manage-import.php' );
			require_once( EAFL_DIR . 'includes/admin/manage/class-eafl-manage-links.php' );
			require_once( EAFL_DIR . 'includes/admin/manage/class-eafl-manage.php' );

			// Menu.
			require_once( EAFL_DIR . 'includes/admin/menu/class-eafl-admin-menu-addons.php' );
			require_once( EAFL_DIR . 'includes/admin/menu/class-eafl-admin-menu-faq.php' );
			require_once( EAFL_DIR . 'includes/admin/menu/class-eafl-admin-menu.php' );

			// Modal.
			require_once( EAFL_DIR . 'includes/admin/modal/class-eafl-button.php' );
			require_once( EAFL_DIR . 'includes/admin/modal/class-eafl-modal.php' );
			require_once( EAFL_DIR . 'includes/admin/modal/class-eafl-shortcode-preview.php' );

			// Statistics.
			require_once( EAFL_DIR . 'includes/admin/statistics/class-eafl-statistics-data.php' );
			require_once( EAFL_DIR . 'includes/admin/statistics/class-eafl-statistics.php' );
		}
	}
}
