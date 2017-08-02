<?php
/**
 * Show the statistics page in the backend menu.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.1.1
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/statistics
 */

/**
 * Show the statistics page in the backend menu.
 *
 * @since      2.1.1
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/statistics
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Statistics {

	/**
	 * Register actions and filters.
	 *
	 * @since    2.1.1
	 */
	public static function init() {
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
		add_action( 'admin_menu', array( __CLASS__, 'add_submenu_page' ), 12 );
		add_action( 'eafl_statistics_page', array( __CLASS__, 'statistics_page' ) );
		add_filter( 'eafl_statistics_tabs', array( __CLASS__, 'statistics_tabs' ), 99 );

		add_action( 'admin_post_eafl_statistics_cleanup', array( __CLASS__, 'form_save_cleanup' ) );
		add_action( 'eafl_migration_page', array( __CLASS__, 'cleanup_page' ) );

		add_action( 'wp_ajax_eafl_statistics_datatable', array( __CLASS__, 'ajax_statistics_datatable' ) );
	}

	/**
	 * Enqueue stylesheets and scripts.
	 *
	 * @since    2.1.1
	 */
	public static function enqueue() {
		$screen = get_current_screen();

		if ( 'affiliate-links_page_eafl_statistics' === $screen->id ) {
			wp_enqueue_style( 'datatables', EAFL_URL . 'vendor/datatables/datatables.min.css', array(), EAFL_VERSION, 'all' );
			wp_enqueue_style( 'tooltipster', EAFL_URL . 'vendor/tooltipster/css/tooltipster.bundle.min.css', array(), EAFL_VERSION, 'all' );
			wp_enqueue_style( 'eafl-statistics', EAFL_URL . 'assets/css/admin/statistics.min.css', array(), EAFL_VERSION, 'all' );

			wp_enqueue_script( 'datatables', EAFL_URL . 'vendor/datatables/datatables.min.js', array( 'jquery' ), EAFL_VERSION, true );
			wp_enqueue_script( 'tooltipster', EAFL_URL . 'vendor/tooltipster/js/tooltipster.bundle.min.js', array( 'jquery' ), EAFL_VERSION, true );
			wp_enqueue_script( 'eafl-statistics', EAFL_URL . 'assets/js/admin/statistics.js', array( 'jquery', 'datatables', 'tooltipster' ), EAFL_VERSION, true );

			wp_localize_script( 'eafl-statistics', 'eafl_statistics', array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'eafl' ),
			));
		}
	}

	/**
	 * Processor for the datatable on the statistics page.
	 *
	 * @since    2.1.1
	 */
	public static function ajax_statistics_datatable() {
		if ( check_ajax_referer( 'eafl', 'security', false ) ) {
			$selection = isset( $_POST['selection'] ) ? sanitize_text_field( wp_unslash( $_POST['selection'] ) ) : ''; // Input var okay.
			$table = isset( $_POST['table'] ) ? sanitize_key( $_POST['table'] ) : ''; // Input var okay.
			$datatable = self::sanitize_datatable( $_POST ); // Input var okay.

			$data = array();

			if ( 'eafl-statistics-data' === $table ) {
				$data = EAFL_Statistics_Data::get_datatable( $datatable );
			}

			echo wp_json_encode( $data );
		}

		wp_die();
	}

	/**
	 * Sanitize the datatable values that were passed along through AJAX.
	 *
	 * @since    2.1.1
	 * @param	 array $datatable Datatable request values.
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
	 * Add the Statistics submenu to the EAFL menu.
	 *
	 * @since    2.1.1
	 */
	public static function add_submenu_page() {
		add_submenu_page( 'easyaffiliatelinks', __( 'Statistics', 'easy-affiliate-links' ), __( 'Statistics', 'easy-affiliate-links' ), 'manage_options', 'eafl_statistics', array( __CLASS__, 'page_template' ) );
	}

	/**
	 * Get the template for this submenu.
	 *
	 * @since    2.1.1
	 */
	public static function page_template() {
		require_once( EAFL_DIR . 'templates/admin/menu/statistics.php' );
	}

	/**
	 * Statistics page tabs to output.
	 *
	 * @since    2.3.0
	 * @param	 mixed $tabs Tabs to display.
	 */
	public static function statistics_tabs( $tabs ) {
		$tabs['cleanup'] = __( 'Clean Up', 'easy-affiliate-links' );
		return $tabs;
	}

	/**
	 * Statistics page to output.
	 *
	 * @since    2.0.0
	 * @param	 mixed $sub Sub statistics page to display.
	 */
	public static function statistics_page( $sub ) {
		if ( 'data' === $sub ) {
			require_once( EAFL_DIR . 'templates/admin/menu/statistics/data.php' );
		} elseif ( 'cleanup' === $sub ) {
			require_once( EAFL_DIR . 'templates/admin/menu/statistics/cleanup.php' );
		}
	}

	/**
	 * Clean up the click statistics.
	 *
	 * @since    2.3.0
	 */
	public static function form_save_cleanup() {
		if ( isset( $_POST['eafl_statistics'] ) && wp_verify_nonce( sanitize_key( $_POST['eafl_statistics'] ), 'eafl_statistics' ) ) { // Input var okay.
			$exclude_ips = isset( $_POST['exclude_ips'] ) ? wp_kses_post( wp_unslash( $_POST['exclude_ips'] ) ) : ''; // Input var okay.

			// Save current exclude list as setting.
			$settings = array();
			$settings['statistics_exclude_ips'] = $exclude_ips;
			EAFL_Settings::update_settings( $settings );

			$total_clicks = EAFL_Clicks_Database::count_clicks();
			wp_safe_redirect( admin_url( 'admin.php?page=eafl_migration&sub=cleanup&t=' . $total_clicks ) );
			exit();
		}
		wp_safe_redirect( admin_url( 'admin.php?page=eafl_statistics' ) );
		exit();
	}

	/**
	 * Cleanup migration page to output.
	 *
	 * @since    2.3.0
	 * @param	 mixed $sub Sub migration page to display.
	 */
	public static function cleanup_page( $sub ) {
		if ( 'cleanup' === $sub ) {
			$limit = 250;
			$deleted = isset( $_GET['d'] ) ? intval( $_GET['d'] ) : 0; // Input var okay.
			$page = isset( $_GET['p'] ) ? intval( $_GET['p'] ) : 0; // Input var okay.
			$total = isset( $_GET['t'] ) ? intval( $_GET['t'] ) : false; // Input var okay.

			if ( ! $total ) {
				$total = EAFL_Clicks_Database::count_clicks();
			}

			$total_pages = ceil( $total / $limit );

			if ( $page >= $total_pages ) {
				echo '<p>Cleanup finished successfully. Cleaned up ' . esc_html( $deleted ) . ' clicks.</p>';
			} else {
				$deleted += self::cleanup_clicks( $page, $limit );
				$page++;

				echo '<p>Stage ' . esc_html( $page ) . '/' . esc_html( $total_pages ) . ' of cleanup. Cleaned up ' . esc_html( $deleted ) . ' clicks. Leave this page open until complete.</p>';
				echo '<script>window.onload = function () { setTimeout(function() { window.location = "' . admin_url( 'admin.php?page=eafl_migration&sub=cleanup&d=' . $deleted . '&t=' . $total . '&p=' . $page ) . '"; }, 500); };</script>';
			}
		}
	}

	/**
	 * Cleanup clicks.
	 *
	 * @since    2.3.0
	 * @param	 int $page Page of clicks to cleanup.
	 * @param	 int $limit Number of clicks to cleanup.
	 */
	public static function cleanup_clicks( $page, $limit ) {
		// Crawler Detect vendor.
		require_once( EAFL_DIR . 'vendor/crawlerdetect/CrawlerDetect.php' );

		// IP addresses to exclude.
		$exclude_ips = EAFL_Settings::get( 'statistics_exclude_ips' );
		$exclude_ips = preg_split( "/\r\n|\n|\r/", $exclude_ips );
		$exclude_ips = array_map( 'trim', $exclude_ips );

		// Get set of clicks.
		$args = array(
			'orderby' => 'id',
			'order' => 'ASC',
			'limit' => $limit,
			'offset' => $page * $limit,
		);
		$clicks = EAFL_Clicks_Database::get_clicks( $args );
		$clicks_to_delete = array();

		foreach ( $clicks['clicks'] as $click ) {
			// Check for excluded IP.
			if ( '' !== $click->ip && in_array( $click->ip, $exclude_ips ) ) {
				$clicks_to_delete[] = $click->id;
			}

			// Check for crawler.
			$crawler_detect = new CrawlerDetect( array(), $click->agent );
			if ( $crawler_detect->isCrawler() ) {
				$clicks_to_delete[] = $click->id;
			}
		}

		if ( ! empty( $clicks_to_delete ) ) {
			EAFL_Clicks_Database::delete_clicks( $clicks_to_delete );
		}

		return count( $clicks_to_delete );
	}
}

EAFL_Statistics::init();
