<?php
/**
 * Template for the statistics data page.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.1.1
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/templates/admin/menu/statistics
 */

?>

<table id="eafl-statistics-data" class="eafl-statistics-datatable" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th data-width="70px"><?php esc_html_e( 'Date', 'easy-affiliate-links' ); ?></th>
			<th><?php esc_html_e( 'Link', 'easy-affiliate-links' ); ?></th>
			<th><?php esc_html_e( 'User', 'easy-affiliate-links' ); ?></th>
			<th data-sortable="false"><?php esc_html_e( 'Device', 'easy-affiliate-links' ); ?></th>
		</tr>
	</thead>
</table>
