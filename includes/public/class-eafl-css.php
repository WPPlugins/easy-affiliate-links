<?php
/**
 * Responsible for adding the custom CSS.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 */

/**
 * Responsible for adding the custom CSS.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Css {
	/**
	 * Register actions and filters.
	 *
	 * @since    2.0.0
	 */
	public static function init() {
		add_action( 'wp_head', array( __CLASS__, 'custom_css' ) );
	}

	/**
	 * Output the custom CSS.
	 *
	 * @since    2.0.0
	 */
	public static function custom_css() {
		$public_css = EAFL_Settings::get( 'public_css' );

		if ( trim( $public_css ) ) {
			echo '<style type="text/css">' . $public_css . '</style>';
		}
	}
}

EAFL_Css::init();
