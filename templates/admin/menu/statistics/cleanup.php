<?php
/**
 * Template for the statistics data cleanup page.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.1.1
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/templates/admin/menu/statistics
 */

?>

<div class="eafl-statistics-cleanup">
	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<input type="hidden" name="action" value="eafl_statistics_cleanup">
		<?php wp_nonce_field( 'eafl_statistics', 'eafl_statistics', false ); ?>
		<h2 class="title"><?php esc_html_e( 'Clean up link clicks', 'easy-affiliate-links' ); ?></h2>
		<p>
			<?php esc_html_e( 'Warning! Using this function will remove matching clicks from the database. Make a backup first if unsure.', 'easy-affiliate-links' ); ?>
		</p>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label for="remove_bots"><?php esc_html_e( 'Remove Bots', 'easy-affiliate-links' ); ?></label>
					</th>
					<td>
						<label for="remove_bots">
							<input name="remove_bots" type="checkbox" id="remove_bots" checked="checked" disabled="disabled" />
							<?php esc_html_e( 'Robots and crawlers that slipped through are automatically removed when cleaning up.', 'easy-affiliate-links' ); ?>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="exclude_ips"><?php esc_html_e( 'Exclude IPs', 'easy-affiliate-links' ); ?></label>
					</th>
					<td>
						<textarea name="exclude_ips" rows="6" cols="50" id="exclude_ips" class="large-text code"><?php echo esc_html( EAFL_Settings::get( 'statistics_exclude_ips' ) ); ?></textarea>
						<p class="description" id="tagline-exclude_ips">
							<?php esc_html_e( 'Remove clicks by these IP addresses. One address per line. Exact match only.', 'easy-affiliate-links' ); ?>
						</p>
					</td>
				</tr>
			</tbody>
		</table>
		<?php submit_button( __( 'Clean Up Clicks', 'easy-affiliate-links' ) ); ?>
	</form>
</div>
