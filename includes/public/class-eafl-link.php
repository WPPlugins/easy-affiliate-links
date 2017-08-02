<?php
/**
 * Represents a link.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 */

/**
 * Represents a link.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Link {

	/**
	 * WP_Post object associated with this link post type.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      object    $post    WP_Post object of this link post type.
	 */
	private $post;

	/**
	 * Metadata associated with this link post type.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      array    $meta    Link metadata.
	 */
	private $meta = false;

	/**
	 * Get new link object from associated post.
	 *
	 * @since    2.0.0
	 * @param    object $post WP_Post object for this link post type.
	 */
	public function __construct( $post ) {
		$this->post = $post;
	}

	/**
	 * Get link data.
	 *
	 * @since    2.0.0
	 */
	public function get_data() {
		$link = array();

		// Technical Fields.
		$link['id'] = $this->id();

		// Link Details.
		$link['name'] = $this->name();
		$link['description'] = $this->description();
		$link['categories'] = $this->categories();
		$link['text'] = $this->text();
		$link['url'] = $this->url();
		$link['slug'] = $this->slug();
		$link['target'] = $this->target( true );
		$link['redirect_type'] = $this->redirect_type( true );
		$link['nofollow'] = $this->nofollow( true );

		return $link;
	}

	/**
	 * Get metadata value.
	 *
	 * @since    2.0.0
	 * @param    mixed $field   Metadata field to retrieve.
	 * @param	 mixed $default	Default to return if metadata is not set.
	 */
	public function meta( $field, $default ) {
		if ( ! $this->meta ) {
			$this->meta = get_post_custom( $this->id() );
		}

		if ( isset( $this->meta[ $field ] ) ) {
			return $this->meta[ $field ][0];
		}

		return $default;
	}

	/**
	 * Get the link ID.
	 *
	 * @since    2.0.0
	 */
	public function id() {
		return $this->post ? $this->post->ID : 0;
	}

	/**
	 * Get the link categories.
	 *
	 * @since    2.0.0
	 */
	public function categories() {
		$terms = get_the_terms( $this->id(), 'eafl_category' );

		return is_array( $terms ) ? $terms : array();
	}

	/**
	 * Get the link categories as a list.
	 *
	 * @since    2.0.0
	 */
	public function categories_list() {
		$terms = $this->categories();
		$term_list = wp_list_pluck( $terms, 'name' );

		return implode( ', ', $term_list );
	}

	/**
	 * Get the link description.
	 *
	 * @since    2.0.0
	 */
	public function description() {
		return $this->meta( 'eafl_description', '' );
	}

	/**
	 * Get the link name.
	 *
	 * @since    2.0.0
	 */
	public function name() {
		return $this->post ? $this->post->post_title : '';
	}

	/**
	 * Get the link nofollow type.
	 *
	 * @since    2.0.0
	 * @param    mixed $keep_default If false default will be replaced by the actual default value.
	 */
	public function nofollow( $keep_default = false ) {
		$nofollow = $this->meta( 'eafl_nofollow', 'default' );

		if ( ! $keep_default && 'default' === $nofollow ) {
			$nofollow = EAFL_Settings::get( 'default_nofollow' );
		}
		return $nofollow;
	}

	/**
	 * Get the link prefix.
	 *
	 * @since    2.0.0
	 */
	public function prefix() {
		return $this->meta( 'eafl_prefix', '' );
	}

	/**
	 * Get the link redirect type.
	 *
	 * @since    2.0.0
	 * @param    mixed $keep_default If false default will be replaced by the actual default value.
	 */
	public function redirect_type( $keep_default = false ) {
		$redirect_type = intval( $this->meta( 'eafl_redirect_type', 999 ) );

		if ( ! $keep_default && 999 === $redirect_type ) {
			$redirect_type = EAFL_Settings::get( 'default_redirect_type' );
		}
		return $redirect_type;
	}

	/**
	 * Get the shortlink.
	 *
	 * @since    2.0.0
	 */
	public function shortlink() {
		return $this->post ? get_permalink( $this->post->ID ) : '';
	}

	/**
	 * Get the link slug.
	 *
	 * @since    2.0.0
	 */
	public function slug() {
		return $this->post ? $this->post->post_name : '';
	}

	/**
	 * Get the link target type.
	 *
	 * @since    2.0.0
	 * @param    mixed $keep_default If false default will be replaced by the actual default value.
	 */
	public function target( $keep_default = false ) {
		$target = $this->meta( 'eafl_target', 'default' );

		if ( ! $keep_default && 'target' === $target ) {
			$target = EAFL_Settings::get( 'default_target' );
		}
		return $target;
	}

	/**
	 * Get the link text.
	 *
	 * @since    2.0.0
	 */
	public function text() {
		$text = maybe_unserialize( $this->meta( 'eafl_text', '' ) );
		return is_array( $text ) ? $text : array( $text );
	}

	/**
	 * Get the link url.
	 *
	 * @since    2.0.0
	 */
	public function url() {
		return $this->meta( 'eafl_url', '' );
	}
}
