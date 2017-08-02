<?php
/**
 * Responsible for handling the redirects.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 */

/**
 * Responsible for handling the redirects.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Redirect {
	/**
	 * Register actions and filters.
	 *
	 * @since    2.0.0
	 */
	public static function init() {
		add_action( 'template_redirect', array( __CLASS__, 'redirect' ) );
	}

	/**
	 * Handle the link redirect.
	 *
	 * @since    2.0.0
	 */
	public static function redirect() {
		$post = get_post();

		if ( $post && EAFL_POST_TYPE === $post->post_type ) {
			$link = EAFL_Link_Manager::get_link( $post );

			$url = $link->url();
			$url = str_replace( '@', '%40', $url );
			$redirect_type = $link->redirect_type();

			@EAFL_Clicks::register( $link );

			wp_redirect( $url, $redirect_type );
			exit();
		}
	}
}

EAFL_Redirect::init();
