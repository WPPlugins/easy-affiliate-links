<?php
/**
 * Add the modal.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/modal
 */

/**
 * Add the modal.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/modal
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Modal {

	/**
	 * Register actions and filters.
	 *
	 * @since    2.0.0
	 */
	public static function init() {
		add_action( 'admin_footer', array( __CLASS__, 'add_modal_content' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
	}

	/**
	 * Enqueue stylesheets and scripts.
	 *
	 * @since    2.0.0
	 */
	public static function enqueue() {
		$screen = get_current_screen();

		if ( strpos( strtolower( $screen->id ), 'wordfence' ) === false ) {
			wp_enqueue_style( 'eafl-select2', EAFL_URL . 'vendor/select2/css/select2.min.css', array(), EAFL_VERSION, 'all' );
			wp_enqueue_style( 'eafl-modal', EAFL_URL . 'assets/css/admin/modal.min.css', array(), EAFL_VERSION, 'all' );

			wp_enqueue_script( 'eafl-select2', EAFL_URL . 'vendor/select2/js/select2.min.js', array( 'jquery' ), EAFL_VERSION, true );

			wp_enqueue_script( 'eafl-modal', EAFL_URL . 'assets/js/admin/modal.js', array( 'jquery' ), EAFL_VERSION, true );
			wp_enqueue_script( 'eafl-code-button', EAFL_URL . 'assets/js/admin/shortcode-button-code.js', array( 'jquery' ), EAFL_VERSION, true );
			wp_enqueue_script( 'eafl-link-overview', EAFL_URL . 'assets/js/admin/link-overview.js', array( 'jquery', 'eafl-modal', 'datatables', 'clipboard', 'eafl-select2' ), EAFL_VERSION, true );
			wp_enqueue_script( 'eafl-link-form', EAFL_URL . 'assets/js/admin/link-form.js', array( 'jquery', 'eafl-modal', 'eafl-select2' ), EAFL_VERSION, true );

			wp_localize_script( 'eafl-modal', 'eafl_modal', array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'eafl' ),
				'text' => array(
					'action_button_insert' => __( 'Insert', 'easy-affiliate-links' ),
					'action_button_update' => __( 'Update', 'easy-affiliate-links' ),
					'create_link' => __( 'Create Link', 'easy-affiliate-links' ),
					'edit_link' => __( 'Edit Link', 'easy-affiliate-links' ),
					'custom_link_text' => __( 'Link text to use for', 'easy-affiliate-links' ),
				),
				'addons' => array(
					'premium' => EAFL_Addons::is_active( 'premium' ),
				),
			));
		}
	}

	/**
	 * Add modal template to edit screen.
	 *
	 * @since    2.0.0
	 */
	public static function add_modal_content() {
		$screen = get_current_screen();

		if ( strpos( strtolower( $screen->id ), 'wordfence' ) === false ) {
			$menu = EAFL_Modal::get_modal_menu();
			require_once( EAFL_DIR . 'templates/admin/modal/modal.php' );
		}
	}

	/**
	 * Get menu to show in modal.
	 *
	 * @since    2.0.0
	 */
	public static function get_modal_menu() {
		$menu = array(
			'easy-affiliate-links' => array(
				'order' => 100,
				'default' => true,
				'label' => 'Easy Affiliate Links',
				'tabs' => array(
					'select-link' => array(
						'order' => 100,
						'label' => __( 'Select Link', 'easy-affiliate-links' ),
						'template' => EAFL_DIR . 'templates/admin/modal/tabs/link-overview.php',
						'init' => 'link_overview',
					),
					'edit-text' => array(
						'order' => 150,
						'label' => __( 'Edit Text', 'easy-affiliate-links' ),
						'template' => EAFL_DIR . 'templates/admin/modal/tabs/link-text.php',
						'callback' => 'update_link_text',
						'init' => 'set_link_text',
						'button' => __( 'Edit', 'easy-affiliate-links' ),
					),
					'create-edit-link' => array(
						'order' => 200,
						'label' => __( 'Create Link', 'easy-affiliate-links' ),
						'template' => EAFL_DIR . 'templates/admin/modal/tabs/link-details.php',
						'callback' => 'insert_update_link',
						'init' => 'set_link',
					),
				),
				'default_tab' => 'select-link',
			),
		);

		// Allow menu to be altered.
		$menu = apply_filters( 'eafl_admin_modal_menu', $menu );

		// Sort menu before returning.
		$sorted_menu = array();
		foreach ( $menu as $menu_item => $options ) {
			uasort( $options['tabs'], array( __CLASS__, 'sort_by_order' ) );

			$sorted_menu[ $menu_item ] = $options;
		}

		uasort( $sorted_menu, array( __CLASS__, 'sort_by_order' ) );

		return $sorted_menu;
	}

	/**
	 * Custom sorting function for menu array.
	 *
	 * @since    2.0.0
	 * @param    mixed $a First array to compare.
	 * @param    mixed $b Second array to compare.
	 */
	public static function sort_by_order( $a, $b ) {
		return $a['order'] - $b['order'];
	}
}

EAFL_Modal::init();
