<?php
/**
 * Responsible for saving links.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin
 */

/**
 * Responsible for saving links.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Link_Saver {

	/**
	 * Register actions and filters.
	 *
	 * @since    2.0.0
	 */
	public static function init() {
		add_action( 'wp_ajax_eafl_save_link', array( __CLASS__, 'ajax_save_link' ) );

		add_filter( 'wp_insert_post_data', array( __CLASS__, 'post_type_switcher_fix' ), 20, 2 );
	}

	/**
	 * Save link submitted through AJAX.
	 *
	 * @since    2.0.0
	 */
	public static function ajax_save_link() {
		if ( check_ajax_referer( 'eafl', 'security', false ) ) {
			$link = isset( $_POST['link'] ) ? EAFL_Link_Sanitizer::sanitize( wp_unslash( $_POST['link'] ) ) : array(); // Input var okay.
			$link_id = isset( $_POST['link_id'] ) ? intval( $_POST['link_id'] ) : 0; // Input var okay.

			if ( 0 !== $link_id && EAFL_POST_TYPE === get_post_type( $link_id ) ) {
				if ( current_user_can( 'edit_post', $link_id ) ) {
					EAFL_Link_Saver::update_link( $link_id, $link );
				}
			} else {
				if ( current_user_can( 'edit_posts' ) ) {
					$link_id = EAFL_Link_Saver::create_link( $link );
				}
			}

			wp_send_json_success( array(
				'id' => $link_id,
				'name' => $link['name'],
				'text' => $link['text'][0],
			) );
		}

		wp_die();
	}

	/**
	 * Create a new link.
	 *
	 * @since    2.0.0
	 * @param	 array $link Link fields to save.
	 */
	public static function create_link( $link ) {
		$post = array(
			'post_type' => EAFL_POST_TYPE,
			'post_status' => 'publish',
		);

		$link_id = wp_insert_post( $post );
		EAFL_Link_Saver::update_link( $link_id, $link );

		// Click counter for sorting.
		update_post_meta( $link_id, 'eafl_clicks_total', 0 );

		return $link_id;
	}

	/**
	 * Save link fields.
	 *
	 * @since    2.0.0
	 * @param	 int   $id Post ID of the link.
	 * @param	 array $link Link fields to save.
	 */
	public static function update_link( $id, $link ) {
		// Filters.
		$link = apply_filters( 'eafl_save_link', $link, $id );
		$link['name'] = apply_filters( 'eafl_save_eafl_name', $link['name'], $id );
		$link['slug'] = apply_filters( 'eafl_save_eafl_slug', $link['slug'], $id );

		// Post Fields.
		$post = array(
			'ID'           => $id,
			'post_title'   => $link['name'],
			'post_name'   => $link['slug'],
		);
		wp_update_post( $post );

		// Link Taxonomies.
		wp_set_object_terms( $id, $link['categories'], 'eafl_category', false );

		// Meta Fields.
		update_post_meta( $id, 'eafl_description', $link['description'] );
		update_post_meta( $id, 'eafl_nofollow', $link['nofollow'] );
		update_post_meta( $id, 'eafl_redirect_type', $link['redirect_type'] );
		update_post_meta( $id, 'eafl_target', $link['target'] );
		update_post_meta( $id, 'eafl_url', $link['url'] );
		update_post_meta( $id, 'eafl_text', $link['text'] );

		EAFL_Link_Manager::invalidate_link( $id );
		self::update_searchable_content( $id );
	}

	/**
	 * Update the searchable content for a link.
	 *
	 * @since    2.0.0
	 * @param	 int $id Post ID of the link.
	 */
	public static function update_searchable_content( $id ) {
		$link = EAFL_Link_Manager::get_link( $id );

		$content = $link->id();
		$content .= ' ' . $link->description();
		$content .= ' ' . $link->slug();
		$content .= ' ' . $link->url();
		$content .= ' ' . $link->categories_list();

		// Link text.
		foreach ( $link->text() as $text ) {
			$content .= ' ' . $text;
		}

		// Update link.
		$post = array(
			'ID'           => $id,
			'post_content' => $content,
		);
		wp_update_post( $post );
	}

	/**
	 * Prevent post type switcher bug from changing our link's post type.
	 *
	 * @since    2.0.0
	 * @param	 array $data    Data that might have been modified by Post Type Switcher.
	 * @param	 array $postarr Unmodified post data.
	 */
	public static function post_type_switcher_fix( $data, $postarr ) {
		if ( EAFL_POST_TYPE === $postarr['post_type'] ) {
			$data['post_type'] = EAFL_POST_TYPE;
		}
		return $data;
	}
}

EAFL_Link_Saver::init();
