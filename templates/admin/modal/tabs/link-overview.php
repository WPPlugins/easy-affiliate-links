<?php
/**
 * Template for the Link Overflow tab in the modal.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/templates/admin/modal/tabs
 */

?>

<div class="eafl-link-overview-container">
	<?php do_action( 'eafl_modal_notice' ); ?>
	<table id="eafl-link-overview" class="eafl-manage-datatable" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th data-width="70px"><?php esc_html_e( 'Date', 'easy-affiliate-links' ); ?></th>
				<th data-sortable="false"><?php esc_html_e( 'Categories', 'easy-affiliate-links' ); ?></th>
				<th><?php esc_html_e( 'Name', 'easy-affiliate-links' ); ?></th>
				<th data-width="250px" data-sortable="false"><?php esc_html_e( 'Link Text', 'easy-affiliate-links' ); ?></th>
				<th data-sortable="false"><?php esc_html_e( 'Link', 'easy-affiliate-links' ); ?></th>
			</tr>
		</thead>
	</table>
</div>
