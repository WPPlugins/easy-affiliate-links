<?php
/**
 * Santize link input fields.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin
 */

/**
 * Santize link input fields.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Link_Sanitizer {

	/**
	 * Sanitize link array.
	 *
	 * @since    2.0.0
	 * @param	 array $link Array containing all link input data.
	 */
	public static function sanitize( $link ) {
		$sanitized_link = array();

		// Text fields.
		$sanitized_link['name'] = isset( $link['name'] ) ? sanitize_text_field( $link['name'] ) : '';
		$sanitized_link['description'] = isset( $link['description'] ) ? wp_kses_post( $link['description'] ) : '';
		$sanitized_link['slug'] = isset( $link['slug'] ) ? sanitize_title( $link['slug'] ) : '';

		// Leave URL intact to make sure it's identical.
		$sanitized_link['url'] = isset( $link['url'] ) ? $link['url'] : '';

		// Limited options fields.
		$options = array( 'default', '_self', '_blank' );
		$sanitized_link['target'] = isset( $link['target'] ) && in_array( $link['target'], $options, true ) ? $link['target'] : $options[0];

		$options = array( 'default', 'nofollow', 'follow' );
		$sanitized_link['nofollow'] = isset( $link['nofollow'] ) && in_array( $link['nofollow'], $options, true ) ? $link['nofollow'] : $options[0];

		// Redirect Type.
		if ( isset( $link['redirect_type'] ) && 'default' !== $link['redirect_type'] ) {
			$link['redirect_type'] = intval( $link['redirect_type'] );
		}
		$options = array( 'default', 301, 302, 307 );
		$sanitized_link['redirect_type'] = isset( $link['redirect_type'] ) && in_array( $link['redirect_type'], $options, true ) ? $link['redirect_type'] : $options[0];

		// Link Tags.
		$sanitized_link['categories'] = isset( $link['categories'] ) && $link['categories'] ? array_map( array( __CLASS__, 'sanitize_tags' ), $link['categories'] ) : array();

		// Other Fields.
		$sanitized_link['text'] = isset( $link['text'] ) && $link['text'] ? array_map( 'sanitize_text_field', $link['text'] ) : array();
		if ( 0 === count( $sanitized_link['text'] ) ) {
			$sanitized_link['text'][] = '';
		}

		return $sanitized_link;
	}

	/**
	 * Sanitize link tags.
	 *
	 * @since    2.0.0
	 * @param	mixed $tag Tag ID or new tag name.
	 */
	public static function sanitize_tags( $tag ) {
		if ( is_numeric( $tag ) ) {
			return intval( $tag );
		} else {
			return sanitize_text_field( $tag );
		}
	}
}
