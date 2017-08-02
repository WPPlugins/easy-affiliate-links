<?php
/**
 * Handle the manage page.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/manage
 */

/**
 * Handle the manage page.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/manage
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Manage {

	/**
	 * Register actions and filters.
	 *
	 * @since    2.0.0
	 */
	public static function init() {
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
		add_action( 'admin_menu', array( __CLASS__, 'add_submenu_page' ) );
		add_action( 'eafl_manage_page', array( __CLASS__, 'manage_page' ) );

		add_action( 'wp_ajax_eafl_manage_datatable', array( __CLASS__, 'ajax_manage_datatable' ) );

		add_filter( 'eafl_admin_modal_menu', array( __CLASS__, 'manage_modal' ) );

		add_action( 'wp_ajax_eafl_delete_link', array( __CLASS__, 'ajax_delete_link' ) );
		add_action( 'wp_ajax_eafl_create_term', array( __CLASS__, 'ajax_create_term' ) );
		add_action( 'wp_ajax_eafl_delete_or_merge_term', array( __CLASS__, 'ajax_delete_or_merge_term' ) );
	}

	/**
	 * Enqueue stylesheets and scripts.
	 *
	 * @since    2.0.0
	 */
	public static function enqueue() {
		$screen = get_current_screen();

		if ( strpos( strtolower( $screen->id ), 'wordfence' ) === false ) {
			wp_enqueue_style( 'datatables', EAFL_URL . 'vendor/datatables/datatables.min.css', array(), EAFL_VERSION, 'all' );
			wp_enqueue_style( 'tooltipster', EAFL_URL . 'vendor/tooltipster/css/tooltipster.bundle.min.css', array(), EAFL_VERSION, 'all' );
			wp_enqueue_style( 'eafl-manage', EAFL_URL . 'assets/css/admin/manage.min.css', array(), EAFL_VERSION, 'all' );

			wp_enqueue_script( 'datatables', EAFL_URL . 'vendor/datatables/datatables.min.js', array( 'jquery' ), EAFL_VERSION, true );
			wp_enqueue_script( 'tooltipster', EAFL_URL . 'vendor/tooltipster/js/tooltipster.bundle.min.js', array( 'jquery' ), EAFL_VERSION, true );
			wp_enqueue_script( 'clipboard', EAFL_URL . 'vendor/clipboard/clipboard.min.js', array(), EAFL_VERSION, true );
			wp_enqueue_script( 'eafl-manage', EAFL_URL . 'assets/js/admin/manage.js', array( 'jquery', 'datatables', 'tooltipster', 'clipboard' ), EAFL_VERSION, true );

			wp_localize_script( 'eafl-manage', 'eafl_manage', array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'eafl' ),
			));
		}
	}
	/**
	 * Remove the select link options from the modal on the manage page.
	 *
	 * @since    2.0.0
	 * @param	 array $menu Menu for the modal.
	 */
	public static function manage_modal( $menu ) {
		$screen = get_current_screen();

		if ( 'toplevel_page_easyaffiliatelinks' === $screen->id ) {
			$tabs = $menu['easy-affiliate-links']['tabs'];
			unset( $tabs['select-link'] );
			unset( $tabs['edit-text'] );
			$menu['easy-affiliate-links']['tabs'] = $tabs;

			$menu['easy-affiliate-links']['default_tab'] = 'create-edit-link';
		}
		return $menu;
	}

	/**
	 * Processor for the datatable on the manage page.
	 *
	 * @since    2.0.0
	 */
	public static function ajax_manage_datatable() {
		if ( check_ajax_referer( 'eafl', 'security', false ) ) {
			$selection = isset( $_POST['selection'] ) ? sanitize_text_field( wp_unslash( $_POST['selection'] ) ) : ''; // Input var okay.
			$table = isset( $_POST['table'] ) ? sanitize_key( $_POST['table'] ) : ''; // Input var okay.
			$datatable = self::sanitize_datatable( $_POST ); // Input var okay.

			$data = array();

			if ( 'eafl-manage-links' === $table ) {
				$data = EAFL_Manage_Links::get_datatable( $datatable, false, $selection );
			} elseif ( 'eafl-link-overview' === $table ) {
				$data = EAFL_Manage_Links::get_datatable( $datatable, true, $selection );
			} elseif ( 'eafl-manage-categories' === $table ) {
				$data = EAFL_Manage_Categories::get_datatable( $datatable, true );
			}

			echo wp_json_encode( $data );
		}

		wp_die();
	}

	/**
	 * Delete links through AJAX.
	 *
	 * @since    2.0.0
	 */
	public static function ajax_delete_link() {
		if ( check_ajax_referer( 'eafl', 'security', false ) ) {
			$link_id = isset( $_POST['link_id'] ) ? intval( $_POST['link_id'] ) : 0; // Input var okay.

			if ( $link_id ) {
				$link = EAFL_Link_Manager::get_link( $link_id );

				if ( $link ) {
					wp_trash_post( $link_id );
				}
			}
		}

		wp_die();
	}

	/**
	 * Delete or merge terms through AJAX.
	 *
	 * @since    2.0.0
	 */
	public static function ajax_create_term() {
		if ( check_ajax_referer( 'eafl', 'security', false ) ) {
			$name = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : ''; // Input var okay.

			$taxonomy = 'eafl_category';

			if ( $name ) {
				wp_insert_term( $name, $taxonomy );
			}
		}

		wp_die();
	}

	/**
	 * Delete or merge terms through AJAX.
	 *
	 * @since    2.0.0
	 */
	public static function ajax_delete_or_merge_term() {
		if ( check_ajax_referer( 'eafl', 'security', false ) ) {
			$term_id = isset( $_POST['term_id'] ) ? intval( $_POST['term_id'] ) : 0; // Input var okay.
			$new_term_id = isset( $_POST['new_term_id'] ) ? intval( $_POST['new_term_id'] ) : 0; // Input var okay.

			$taxonomy = 'eafl_category';

			if ( $term_id ) {
				$term = get_term( $term_id, $taxonomy );

				// Check if this is one of our taxonomies.
				if ( $term && ! is_wp_error( $term ) ) {
					if ( ! $new_term_id ) {
						wp_delete_term( $term->term_id, $taxonomy );
					} else {
						// This ensures the term to merge into is in the same taxonomy.
						$new_term = get_term( $new_term_id, $taxonomy );

						if ( $new_term && ! is_wp_error( $new_term ) ) {
							self::merge_link_terms( $term, $new_term );
							wp_delete_term( $term->term_id, $taxonomy );
						}
					}
				}
			}
		}

		wp_die();
	}

	/**
	 * Merge terms for all links using them.
	 *
	 * @since    2.0.0
	 * @param    object $term     Term to merge from.
	 * @param    object $new_term Term to merge to.
	 */
	public static function merge_link_terms( $term, $new_term ) {
		$args = array(
			'post_type' => EAFL_POST_TYPE,
			'post_status' => 'any',
			'nopaging' => true,
			'tax_query' => array(
				array(
					'taxonomy' => $term->taxonomy,
					'field' => 'id',
					'terms' => $term->term_id,
				),
			)
		);

		$query = new WP_Query( $args );
		$posts = $query->posts;
		foreach ( $posts as $post ) {
			wp_set_object_terms( $post->ID, $new_term->term_id, $term->taxonomy, true );
		}
	}

	/**
	 * Sanitize the datatable values that were passed along through AJAX.
	 *
	 * @since    2.0.0
	 * @param		 array $datatable Datatable request values.
	 */
	public static function sanitize_datatable( $datatable ) {
		return array(
			'draw' => isset( $datatable['draw'] ) ? intval( $datatable['draw'] ) : 1,
			'start' => isset( $datatable['start'] ) ? intval( $datatable['start'] ) : 0,
			'length' => isset( $datatable['length'] ) ? intval( $datatable['length'] ) : 10,
			'search' => isset( $datatable['search']['value'] ) ? sanitize_text_field( $datatable['search']['value'] ) : '',
			'orderby' => isset( $datatable['order'][0]['column'] ) ? intval( $datatable['order'][0]['column'] ) : 0,
			'order' => isset( $datatable['order'][0]['dir'] ) && 'desc' === $datatable['order'][0]['dir'] ? 'DESC' : 'ASC',
		);
	}

	/**
	 * Add the manage submenu to the EAFL menu.
	 *
	 * @since    2.0.0
	 */
	public static function add_submenu_page() {
		add_submenu_page( 'easyaffiliatelinks', __( 'Manage', 'easy-affiliate-links' ), __( 'Manage', 'easy-affiliate-links' ), 'manage_options', 'easyaffiliatelinks', array( __CLASS__, 'page_template' ) );
	}

	/**
	 * Get the template for this submenu.
	 *
	 * @since    2.0.0
	 */
	public static function page_template() {
		require_once( EAFL_DIR . 'templates/admin/menu/manage.php' );
	}

	/**
	 * Manage page to output.
	 *
	 * @since    2.0.0
	 * @param	 mixed $sub Sub manage page to display.
	 */
	public static function manage_page( $sub ) {
		if ( 'links' === $sub ) {
			require_once( EAFL_DIR . 'templates/admin/menu/manage/links.php' );
		} elseif ( 'categories' === $sub ) {
			require_once( EAFL_DIR . 'templates/admin/menu/manage/categories.php' );
		}
	}
}

EAFL_Manage::init();
