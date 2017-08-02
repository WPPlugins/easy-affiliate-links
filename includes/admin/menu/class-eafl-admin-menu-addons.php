<?php
/**
 * Show addons in the backend menu.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin
 */

/**
 * Show addons in the backend menu.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Admin_Menu_Addons {

	/**
	 * Register actions and filters.
	 *
	 * @since    2.0.0
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_submenu_page' ), 16 );
		add_action( 'admin_notices', array( __CLASS__, 'admin_notices' ) );
	}

	/**
	 * Add the FAQ & Support submenu to the EAFL menu.
	 *
	 * @since    2.0.0
	 */
	public static function add_submenu_page() {
		add_submenu_page( 'easyaffiliatelinks', __( 'Add-Ons', 'easy-affiliate-links' ), __( 'Add-Ons', 'easy-affiliate-links' ), 'manage_options', 'eafl_addons', array( __CLASS__, 'page_template' ) );
	}

	/**
	 * Show an admin notice regarding the Premium add-on.
	 *
	 * @since    2.0.0
	 */
	public static function admin_notices() {
		$current_screen = get_current_screen();

		// Just released Statistics add-on.
		if ( current_user_can( 'manage_options' ) && ( 'toplevel_page_easyaffiliatelinks' === $current_screen->id || 'affiliate-links_page_eafl_settings' === $current_screen->id ) ) {
			$now = new DateTime();
			$promotion_start = new DateTime( '2017-03-18 10:00:00', new DateTimeZone( 'Europe/Brussels' ) );
			$promotion_end = new DateTime( '2017-04-18 10:00:00', new DateTimeZone( 'Europe/Brussels' ) );

			if ( ! EAFL_Addons::is_active( 'statistics' ) && $promotion_start < $now && $now < $promotion_end ) {
				echo '<div class="notice notice-info"><p>We recently released the <strong>Easy Affiliate Links - Statistics add-on</strong> providing you with valuable insights in your clicks. <a href="http://bootstrapped.ventures/downloads/easy-affiliate-links-statistics/" target="_blank">Learn more</a>!</p></div>';
			}
		}
	}

	/**
	 * Get the template for this submenu.
	 *
	 * @since    2.0.0
	 */
	public static function page_template() {
		require_once( EAFL_DIR . 'templates/admin/menu/addons.php' );
	}
}

EAFL_Admin_Menu_Addons::init();
