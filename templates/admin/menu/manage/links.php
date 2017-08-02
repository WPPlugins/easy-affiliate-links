<?php
/**
 * Template for the links manage page.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/templates/admin/menu/manage
 */

?>

<button type="button" class="button eafl-button button-primary button-large eafl-button-add-link"><?php esc_html_e( 'Create Link', 'easy-affiliate-links' ); ?></button>

<table id="eafl-manage-links" class="eafl-manage-datatable" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th data-sortable="false" data-width="40px">&nbsp;</th>
			<th data-width="70px"><?php esc_html_e( 'Date', 'easy-affiliate-links' ); ?></th>
			<th data-sortable="false"><?php esc_html_e( 'Categories', 'easy-affiliate-links' ); ?></th>
			<th><?php esc_html_e( 'Name', 'easy-affiliate-links' ); ?></th>
			<th data-width="40px"><?php esc_html_e( 'Clicks', 'easy-affiliate-links' ); ?></th>
			<th data-sortable="false"><?php esc_html_e( 'Link', 'easy-affiliate-links' ); ?></th>
		</tr>
	</thead>
</table>
