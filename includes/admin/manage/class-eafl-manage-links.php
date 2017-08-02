<?php
/**
 * Handle the manage links page.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/manage
 */

/**
 * Handle the manage links page.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/manage
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Manage_Links {

	/**
	 * Get the data to display in the datatable.
	 *
	 * @since    2.0.0
	 * @param    array   $datatable Datatable request values.
	 * @param    boolean $in_modal Wether or not we're showing this in the modal.
	 * @param    mixed   $selection Currently selected text.
	 */
	public static function get_datatable( $datatable, $in_modal, $selection ) {
		$data = array();

		if ( $in_modal ) {
			$orderby_options = array(
				0 => 'date',
				2 => 'title',
			);
		} else {
			$orderby_options = array(
				1 => 'date',
				3 => 'title',
				4 => 'meta_value_num',
			);
		}

		$orderby = isset( $orderby_options[ $datatable['orderby'] ] ) ? $orderby_options[ $datatable['orderby'] ] : $orderby_options[0];

		$args = array(
			'post_type' => EAFL_POST_TYPE,
			'post_status' => 'any',
			'orderby' => $orderby,
			'order' => $datatable['order'],
			'posts_per_page' => $datatable['length'],
			'offset' => $datatable['start'],
			's' => $datatable['search'],
		);

		if ( 'meta_value_num' === $orderby ) {
			$args['meta_key'] = 'eafl_clicks_total';
		}

		$query = new WP_Query( $args );

		$posts = $query->posts;
		foreach ( $posts as $post ) {
			$link = EAFL_Link_Manager::get_link( $post );

			$date_column = '<div class="eafl-manage-links-date">' . get_the_date( 'Y/m/d', $link->id() ) . '</div><div class="eafl-manage-links-id">#' . $link->id() . '</div>';
			$name_column = '<div id="eafl-manage-links-name-' . $link->id() . '" class="eafl-manage-links-name">' . $link->name() . '</div><div class="eafl-manage-links-description">' . $link->description() . '</div>';

			$categories_column = '<div class="eafl-manage-links-categories">' . $link->categories_list() . '</div>';

			$shortlink = '<div class="eafl-manage-links-shortlink"><span class="dashicons dashicons-clipboard eafl-copy-link" data-clipboard-target="#eafl-shortlink-' . $link->id() . '" title="' . __( 'Copy to clipboard', 'easy-affiliate-links' ) . '"></span> <span id="eafl-shortlink-' . $link->id() . '">' . $link->shortlink() . '</span></div>';
			$url = '<div class="eafl-manage-links-url"><span class="dashicons dashicons-clipboard eafl-copy-link" data-clipboard-target="#eafl-url-' . $link->id() . '" title="' . __( 'Copy to clipboard', 'easy-affiliate-links' ) . '"></span> <span id="eafl-url-' . $link->id() . '">' . $link->url() . '</span></div>';

			$link_column = $shortlink . $url;

			if ( ! $in_modal ) {
				$clicks = EAFL_Clicks::summary( $link->id() );
				$clicks_column = '<div class="eafl-manage-links-clicks-month">' . $clicks['month'] . ' <span class="eafl-manage-links-clicks-text">' . esc_html__( 'This month', 'easy-affiliate-links' ) . '</div>';
				$clicks_column .= '<div class="eafl-manage-links-clicks-total">' . $clicks['all'] . ' <span class="eafl-manage-links-clicks-text">' . esc_html__( 'Lifetime', 'easy-affiliate-links' ) . '</div>';

				$data[] = array(
					'<span class="dashicons dashicons-admin-tools eafl-icon eafl-manage-links-actions" data-id="' . esc_attr( $link->id() ) . '"></span>',
					$date_column,
					$categories_column,
					$name_column,
					$clicks_column,
					$link_column,
				);
			} else {
				$select = '<select class="eafl-select-text">';

				if ( $selection ) {
					$select .= '<option value="' . esc_attr( $selection ) . '">' . $selection . '</option>';
				}

				foreach ( $link->text() as $text ) {
					$select .= '<option value="' . esc_attr( $text ) . '">' . $text . '</option>';
				}
				$select .= '<option value="eafl_custom_link_text">...' . esc_html__( 'or use a custom text', 'easy-affiliate-links' ) . '</option>';
				$select .= '</select>';

				$button = '<button class="button button-primary eafl-add-link"><span class="dashicons dashicons-admin-links"></span></button>';
				$text_column = '<span class="eafl-link-selection">' . $select . $button . '</span>';

				$data[] = array(
					$date_column,
					$categories_column,
					$name_column,
					$text_column,
					$link_column,
				);
			}
		}

		return array(
			'draw' => $datatable['draw'],
			'recordsTotal' => $query->found_posts,
			'recordsFiltered' => $query->found_posts,
			'data' => $data,
		);
	}
}
