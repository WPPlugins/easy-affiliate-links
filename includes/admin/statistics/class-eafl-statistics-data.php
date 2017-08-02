<?php
/**
 * Handle the statistics data page.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.1.1
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/statistics
 */

/**
 * Handle the statistics data page.
 *
 * @since      2.1.1
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/statistics
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Statistics_Data {

	/**
	 * Register actions and filters.
	 *
	 * @since    2.1.1
	 */
	public static function init() {
		if ( true ) {
			add_action( 'admin_notices', array( __CLASS__, 'modal_notice' ) );
		}
	}

	/**
	 * Get the data to display in the datatable.
	 *
	 * @since    2.1.1
	 * @param    array $datatable Datatable request values.
	 */
	public static function get_datatable( $datatable ) {
		$data = array();

		$orderby_options = array(
			0 => 'date',
			1 => 'link_id',
			2 => 'user_id',
		);

		$orderby = isset( $orderby_options[ $datatable['orderby'] ] ) ? $orderby_options[ $datatable['orderby'] ] : $orderby_options[0];

		$args = array(
			'orderby' => $orderby,
			'order' => $datatable['order'],
			'offset' => $datatable['start'],
			'limit' => $datatable['length'],
		);

		if ( ! EAFL_Addons::is_active( 'statistics' ) ) {
			$args['offset'] = 0;
			$args['limit'] = 10;
		}

		$query = EAFL_Clicks_Database::get_clicks( $args );

		foreach ( $query['clicks'] as $click ) {
			$link = EAFL_Link_Manager::get_link( $click->link_id );

			// User.
			$name = $click->user_id ? __( 'Unknown', 'easy-affiliate-links' ) : __( 'n/a', 'easy-affiliate-links' );
			$user = get_user_by( 'id', $click->user_id );
			if ( $user ) {
				$name = '<a href="' . get_edit_user_link( $click->user_id ) . '">' . $user->display_name . '</a>';
			}

			// Click Type.
			$click_device = __( 'Desktop', 'easy-affiliate-links' );
			if ( ! $click->is_desktop ) {
				$click_device = $click->is_tablet ? __( 'Tablet', 'easy-affiliate-links' ) : __( 'Mobile', 'easy-affiliate-links' );
			}

			$data[] = array(
				'<span class="eafl-statistics-data-date">' . $click->date . '</span>',
				'<span class="eafl-statistics-data-link">' . $link->name() . '</span><span class="eafl-statistics-data-referrer">' . $click->referer . '</span>',
				'<span class="eafl-statistics-data-user">' . $name . '</span><span class="eafl-statistics-data-ip">' . $click->ip . '</span>',
				'<span class="eafl-statistics-data-device">' . $click_device . '</span><span class="eafl-statistics-data-agent">' . $click->agent . '</span>',
			);
		}

		return array(
			'draw' => $datatable['draw'],
			'recordsTotal' => $query['total'],
			'recordsFiltered' => $query['total'],
			'data' => $data,
		);
	}

	/**
	 * Show a notice if the statistics add-on is not enabled.
	 *
	 * @since    2.1.1
	 */
	public static function modal_notice() {
		$screen = get_current_screen();

		if ( 'affiliate-links_page_eafl_statistics' === $screen->id && ! EAFL_Addons::is_active( 'statistics' ) ) {
			echo '<div class="notice notice-warning">';
			echo '<p>Full data and statistics are only available in the <a href="' . esc_url( admin_url( 'admin.php?page=eafl_addons' ) ) . '">Easy Affiliate Links Statistics add-on</a></p>';
			echo '<p>Data is limited to 10 entries without the add-on.</p>';
			echo '</div>';
		}
	}
}

EAFL_Statistics_Data::init();
