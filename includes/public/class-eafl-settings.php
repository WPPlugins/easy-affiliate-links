<?php
/**
 * Responsible for the plugin settings.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 */

/**
 * Responsible for the plugin settings.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Settings {
	/**
	 * Cached version of the plugin settings.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      array    $settings    Array containing the plugin settings.
	 */
	private static $settings = array();

	/**
	 * Defaults for the plugin settings.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      array    $defaults    Default values for unset settings.
	 */
	private static $defaults = array(
		// General.
		'shortlink_slug' => 'recommends',
		'default_target' => '_blank',
		'default_redirect_type' => 301,
		'default_nofollow' => 'nofollow',
		'enable_clicks' => true,
		// Advanced.
		'button_capability' => 'edit_posts',
		'public_css' => '',
		// Statistics.
		'statistics_exclude_ips' => '',
	);

	/**
	 * Register actions and filters.
	 *
	 * @since    2.0.0
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_submenu_page' ), 20 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
		add_action( 'admin_post_eafl_settings_general', array( __CLASS__, 'form_save_settings_general' ) );
		add_action( 'admin_post_eafl_settings_advanced', array( __CLASS__, 'form_save_settings_advanced' ) );

		add_action( 'eafl_settings_page', array( __CLASS__, 'settings_page' ) );
	}

	/**
	 * Enqueue stylesheets and scripts.
	 *
	 * @since    2.0.0
	 */
	public static function enqueue() {
		wp_enqueue_style( 'eafl-settings', EAFL_URL . 'assets/css/admin/settings.min.css', array(), EAFL_VERSION, 'all' );
		wp_enqueue_style( 'eafl-select2', EAFL_URL . 'vendor/select2/css/select2.min.css', array(), EAFL_VERSION, 'all' );

		wp_enqueue_script( 'eafl-select2', EAFL_URL . 'vendor/select2/js/select2.min.js', array( 'jquery' ), EAFL_VERSION, true );
		wp_enqueue_script( 'eafl-settings', EAFL_URL . 'assets/js/admin/settings.js', array( 'jquery', 'eafl-select2' ), EAFL_VERSION, true );
	}

	/**
	 * Add the settings submenu to the EAFL menu.
	 *
	 * @since    2.0.0
	 */
	public static function add_submenu_page() {
		add_submenu_page( 'easyaffiliatelinks', __( 'EAFL Settings', 'easy-affiliate-links' ), __( 'Settings', 'easy-affiliate-links' ), 'manage_options', 'eafl_settings', array( __CLASS__, 'settings_page_template' ) );
	}

	/**
	 * Settings page to output.
	 *
	 * @since    2.0.0
	 * @param		 mixed $sub Sub settings page to display.
	 */
	public static function settings_page( $sub ) {
		if ( 'general' === $sub ) {
			require_once( EAFL_DIR . 'templates/admin/settings/general.php' );
		} elseif ( 'advanced' === $sub ) {
			require_once( EAFL_DIR . 'templates/admin/settings/advanced.php' );
		}
	}

	/**
	 * Get the template for the settings page.
	 *
	 * @since    2.0.0
	 */
	public static function settings_page_template() {
		require_once( EAFL_DIR . 'templates/admin/settings.php' );
	}

	/**
	 * Get the value for a specific setting.
	 *
	 * @since    2.0.0
	 * @param		 mixed $setting Setting to get the value for.
	 */
	public static function get( $setting ) {
		$settings = self::get_settings();

		if ( isset( $settings[ $setting ] ) ) {
			return $settings[ $setting ];
		} else {
			return self::get_default( $setting );
		}
	}

	/**
	 * Get the default for a specific setting.
	 *
	 * @since    2.0.0
	 * @param	 mixed $setting Setting to get the default for.
	 */
	public static function get_default( $setting ) {
		$defaults = self::get_defaults();
		if ( isset( $defaults[ $setting ] ) ) {
			return $defaults[ $setting ];
		} else {
			return false;
		}
	}

	/**
	 * Get the default settings.
	 *
	 * @since    2.0.0
	 */
	public static function get_defaults() {
		return apply_filters( 'eafl_settings_defaults', self::$defaults );
	}

	/**
	 * Get all the settings.
	 *
	 * @since    2.0.0
	 */
	public static function get_settings() {
		// Lazy load settings.
		if ( empty( self::$settings ) ) {
			self::load_settings();
		}

		return self::$settings;
	}

	/**
	 * Load all the plugin settings.
	 *
	 * @since    2.0.0
	 */
	private static function load_settings() {
		self::$settings = get_option( 'eafl_settings', array() );
	}

	/**
	 * Update the plugin settings.
	 *
	 * @since    2.0.0
	 * @param		 array $settings_to_update Settings to update.
	 */
	public static function update_settings( $settings_to_update ) {
		$settings = self::get_settings();

		if ( is_array( $settings_to_update ) ) {
				$settings = array_merge( $settings, $settings_to_update );
		}

		update_option( 'eafl_settings', $settings );
		self::$settings = $settings;
	}

	/**
	 * Save the general settings.
	 *
	 * @since    2.0.0
	 */
	public static function form_save_settings_general() {
		if ( isset( $_POST['eafl_settings'] ) && wp_verify_nonce( sanitize_key( $_POST['eafl_settings'] ), 'eafl_settings' ) ) { // Input var okay.
			$shortlink_slug = isset( $_POST['shortlink_slug'] ) ? sanitize_title( $_POST['shortlink_slug'] ) : false; // Input var okay.
			$default_target = isset( $_POST['default_target'] ) ? sanitize_key( $_POST['default_target'] ) : false; // Input var okay.
			$default_redirect_type = isset( $_POST['default_redirect_type'] ) ? intval( $_POST['default_redirect_type'] ) : false; // Input var okay.
			$default_nofollow = isset( $_POST['default_nofollow'] ) && sanitize_key( $_POST['default_nofollow'] ) ? true : false; // Input var okay.
			$enable_clicks = isset( $_POST['enable_clicks'] ) && sanitize_key( $_POST['enable_clicks'] ) ? true : false; // Input var okay.

			$settings = array();

			if ( $shortlink_slug ) {
				$settings['shortlink_slug'] = $shortlink_slug;
				EAFL_Permalinks::set_flush_needed();
			}

			if ( $default_target ) {
				$settings['default_target'] = $default_target;
			}

			if ( $default_redirect_type ) {
				$settings['default_redirect_type'] = $default_redirect_type;
			}

			$settings['default_nofollow'] = $default_nofollow ? 'nofollow' : 'follow';
			$settings['enable_clicks'] = $enable_clicks;

			self::update_settings( $settings );
		}
		wp_safe_redirect( admin_url( 'admin.php?page=eafl_settings&sub=general' ) );
		exit();
	}

	/**
	 * Save the advanced settings.
	 *
	 * @since    2.0.0
	 */
	public static function form_save_settings_advanced() {
		if ( isset( $_POST['eafl_settings'] ) && wp_verify_nonce( sanitize_key( $_POST['eafl_settings'] ), 'eafl_settings' ) ) { // Input var okay.
			$button_capability = isset( $_POST['button_capability'] ) ? sanitize_key( $_POST['button_capability'] ) : ''; // Input var okay.
			$public_css = isset( $_POST['public_css'] ) ? $_POST['public_css'] : ''; // Input var okay.

			$settings = array();
			$settings['button_capability'] = $button_capability;
			$settings['public_css'] = $public_css;

			self::update_settings( $settings );
		}
		wp_safe_redirect( admin_url( 'admin.php?page=eafl_settings&sub=advanced' ) );
		exit();
	}
}

EAFL_Settings::init();
