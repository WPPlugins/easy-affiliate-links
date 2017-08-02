<?php
/**
 * Responsible for handling Premium add-on licensing.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.3.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin
 */

/**
 * Responsible for handling Premium add-on licensing.
 *
 * @since      2.3.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_License {

	/**
	 *  EDD store to contact.
	 *
	 * @since    2.3.0
	 * @access   private
	 * @var      string $store EDD store to contact.
	 */
	private static $store = 'http://bootstrapped.ventures';

	/**
	 *  Premium products on this website.
	 *
	 * @since    2.3.0
	 * @access   private
	 * @var      array $products Premium products on this website.
	 */
	private static $products = array();

	/**
	 * Register actions and filters.
	 *
	 * @since    2.3.0
	 */
	public static function init() {
		add_filter( 'eafl_settings_defaults', array( __CLASS__, 'license_settings_defaults' ) );
		add_filter( 'eafl_settings_tabs', array( __CLASS__, 'license_settings_tab' ), 90 );

		add_action( 'eafl_settings_page', array( __CLASS__, 'license_settings_page' ) );
		add_action( 'admin_post_eafl_settings_license', array( __CLASS__, 'form_save_settings' ) );
		add_action( 'admin_notices', array( __CLASS__, 'license_inactive_notice' ) );
		add_action( 'admin_init', array( __CLASS__, 'edd_plugin_updater' ) );

		if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
			include( EAFL_DIR . '/vendor/edd/EDD_SL_Plugin_Updater.php' );
		}
	}

	/**
	 * Get all the Easy Affiliate Links products on this website.
	 *
	 * @since    2.3.0
	 */
	public static function get_products() {
		return apply_filters( 'eafl_edd_products', self::$products );
	}

	/**
	 * Set up plugin updater to check for plugin updates.
	 *
	 * @since    2.3.0
	 */
	public static function edd_plugin_updater() {
		$products = self::get_products();

		foreach ( $products as $id => $product ) {
			new EDD_SL_Plugin_Updater( self::$store, $product['file'], array(
					'version' 	=> $product['version'],
					'license' 	=> EAFL_Settings::get( 'license_' . $id ),
					'item_name' => $product['name'],
					'author' 	=> 'Bootstrapped Ventures',
				)
			);
		}
	}

	/**
	 * Add license settings defaults.
	 *
	 * @since    2.3.0
	 * @param    array $defaults Settings defaults.
	 */
	public static function license_settings_defaults( $defaults ) {
		$products = self::get_products();

		foreach ( $products as $id => $product ) {
			$defaults = array_merge( $defaults, array(
				'license_' . $id => '',
				'license_' . $id . '_active' => '',
			) );
		}

		return $defaults;
	}

	/**
	 * Add license to the settings tab.
	 *
	 * @since    2.3.0
	 * @param	 array $tabs Settings tabs.
	 */
	public static function license_settings_tab( $tabs ) {
		$products = self::get_products();

		if ( count( $products ) > 0 ) {
			$tabs['license'] = __( 'Licenses', 'easy-affiliate-links' );
		}

		return $tabs;
	}

	/**
	 * Settings page to output.
	 *
	 * @since    2.3.0
	 * @param	 mixed $sub Sub settings page to display.
	 */
	public static function license_settings_page( $sub ) {
		if ( 'license' === $sub ) {
			require_once( EAFL_DIR . 'templates/admin/settings/license.php' );
		}
	}

	/**
	 * Save the settings.
	 *
	 * @since    2.3.0
	 */
	public static function form_save_settings() {
		if ( isset( $_POST['eafl_settings'] ) && wp_verify_nonce( sanitize_key( $_POST['eafl_settings'] ), 'eafl_settings' ) ) { // Input var okay.
			$settings = array();
			$products = self::get_products();

			foreach ( $products as $id => $product ) {
				$license = isset( $_POST[ 'license_' . $id ] ) ? self::sanitize_license( $id, wp_unslash( $_POST[ 'license_' . $id ] ) ) : ''; // Input var okay.

				$settings[ 'license_' . $id ] = $license;
			}

			EAFL_Settings::update_settings( $settings );
		}
		wp_safe_redirect( admin_url( 'admin.php?page=eafl_settings&sub=license' ) );
		exit();
	}

	/**
	 * Sanitize function for the license key.
	 *
	 * @since    2.3.0
	 * @param    mixed $id  	ID of the product we are sanitizing the license for.
	 * @param    mixed $license License key to sanitize.
	 */
	private static function sanitize_license( $id, $license ) {
		$license = trim( $license );

		$old = EAFL_Settings::get( 'license_' . $id );
		$status = EAFL_Settings::get( 'license_' . $id . '_active' );

		if ( $old !== $license ) {
			self::update_license_status( $id, '' ); // Changing license, so clear status.
			self::activate_license( $id, $license );
		}

		if ( 'valid' === $status && $old !== $license ) {
			self::deactivate_license( $id, $old ); // Deactivate old license.
		}

		return $license;
	}

	/**
	 * Update the status of the license key.
	 *
	 * @since    2.3.0
	 * @param    mixed $id     ID of the product we are updating the license for.
	 * @param    mixed $status Status to set.
	 */
	private static function update_license_status( $id, $status ) {
		EAFL_Settings::update_settings( array(
			'license_' . $id . '_active' => $status,
			)
		);
	}

	/**
	 * Activate a license key.
	 *
	 * @since    2.3.0
	 * @param    mixed $id     ID of the product we are activating the license for.
	 * @param    mixed $license License key to activate.
	 */
	private static function activate_license( $id, $license ) {
		$products = self::get_products();
		$product = $products[ $id ];

		$api_params = array(
			'edd_action' => 'activate_license',
			'license' 	 => $license,
			'item_name'  => urlencode( $product['name'] ),
			'url'        => home_url(),
		);

		// Call the EDD license API.
		$response = wp_remote_post( self::$store, array( 'timeout' => 60, 'sslverify' => false, 'body' => $api_params ) );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		self::update_license_status( $id, $license_data->license );
	}

	/**
	 * Deactivate a license key.
	 *
	 * @since    2.3.0
	 * @param    mixed $id     ID of the product we are deactivating the license for.
	 * @param    mixed $license License key to deactivate.
	 */
	private static function deactivate_license( $id, $license ) {
		$products = self::get_products();
		$product = $products[ $id ];

		$api_params = array(
			'edd_action' => 'deactivate_license',
			'license' 	 => $license,
			'item_name'  => urlencode( $product['name'] ),
			'url'        => home_url(),
		);

		// Call the EDD license API.
		$response = wp_remote_post( self::$store, array( 'timeout' => 60, 'sslverify' => false, 'body' => $api_params ) );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		if ( 'deactivated' === $license_data->license ) {
			return true;
		}
	}

	/**
	 * Show a notice on the plugin page if the license is inactive.
	 *
	 * @since    2.3.0
	 */
	public static function license_inactive_notice() {
		$screen = get_current_screen();

		if ( 'plugins' === $screen->id ) {
			$products = self::get_products();

			foreach ( $products as $id => $product ) {
				if ( 'valid' !== EAFL_Settings::get( 'license_' . $id . '_active' ) ) {
					echo '<div class="error">';
					echo '<p><strong>' . esc_html( $product['name'] ) . '</strong><br/>';
					echo esc_html__( 'Activate your license key to receive automatic updates.', 'easy-affiliate-links' );
					echo ' <a href="' . esc_url( add_query_arg( 'sub', 'license', admin_url( 'admin.php?page=eafl_settings' ) ) ) . '">' . esc_html__( 'Activate now', 'easy-affiliate-links' ) . '</a>';
					echo ' - <a href="http://bootstrapped.ventures/easy-affiliate-links/" target="_blank">' . esc_html__( 'Get a License', 'easy-affiliate-links' ) . '</a>';
					echo '</p>';
					echo '</div>';
				}
			}
		}
	}
}

EAFL_License::init();
