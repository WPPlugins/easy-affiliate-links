<?php
/**
 * Handle the manage categories pages.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/manage
 */

/**
 * Handle the manage categories pages.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/manage
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Manage_Categories {

	/**
	 * Register actions and filters.
	 *
	 * @since    2.0.0
	 */
	public static function init() {
		add_filter( 'eafl_manage_tabs', array( __CLASS__, 'manage_tabs' ) );
	}

	/**
	 * Add tags to the manage tabs.
	 *
	 * @since    2.0.0
	 * @param 	 array $tabs Tags tabs.
	 */
	public static function manage_tabs( $tabs ) {
		$tabs['categories'] = __( 'Categories', 'easy-affiliate-links' );
		return $tabs;
	}

	/**
	 * Get the data to display in the datatable.
	 *
	 * @since    2.0.0
	 * @param    array $datatable Datatable request values.
	 */
	public static function get_datatable( $datatable ) {
		$data = array();

		$orderby_options = array(
			1 => 'id',
			2 => 'name',
			3 => 'count',
		);
		$orderby = isset( $orderby_options[ $datatable['orderby'] ] ) ? $orderby_options[ $datatable['orderby'] ] : $orderby_options[0];

		$args = array(
				'taxonomy' => 'eafl_category',
				'hide_empty' => false,
				'orderby' => $orderby,
				'order' => $datatable['order'],
				'number' => $datatable['length'],
				'offset' => $datatable['start'],
				'search' => $datatable['search'],
		);

		$terms = get_terms( $args );

		foreach ( $terms as $term ) {
			$data[] = array(
				'<span class="dashicons dashicons-admin-tools eafl-icon eafl-manage-categories-actions" data-id="' . esc_attr( $term->term_id ) . '"></span>',
				$term->term_id,
				'<span id="eafl-manage-categories-name-' . esc_attr( $term->term_id ) . '">' . $term->name . '</span>',
				$term->count,
			);
		}

		$total = wp_count_terms( 'eafl_category', array( 'hide_empty' => false ) );

		return array(
			'draw' => $datatable['draw'],
			'recordsTotal' => $total,
			'recordsFiltered' => $total,
			'data' => $data,
		);
	}
}

EAFL_Manage_Categories::init();
