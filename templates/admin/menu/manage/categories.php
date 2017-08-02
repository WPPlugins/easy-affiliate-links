<?php
/**
 * Template for the categories manage page.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/templates/admin/menu/manage
 */

?>

<button type="button" class="button eafl-button button-primary button-large eafl-button-add-category"><?php esc_html_e( 'Create Category', 'easy-affiliate-links' ); ?></button>

<table id="eafl-manage-categories" class="eafl-manage-datatable" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th data-sortable="false" data-width="40px">&nbsp;</th>
			<th data-width="50px"><?php esc_html_e( 'ID', 'easy-affiliate-links' ); ?></th>
			<th><?php esc_html_e( 'Name', 'easy-affiliate-links' ); ?></th>
			<th><?php esc_html_e( 'Affiliate Links', 'easy-affiliate-links' ); ?></th>
		</tr>
	</thead>
</table>
