<?php
/**
 * Template for the advanced settings sub page.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/templates/admin/settings
 */

?>

<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
	<input type="hidden" name="action" value="eafl_settings_advanced">
	<?php wp_nonce_field( 'eafl_settings', 'eafl_settings', false ); ?>
	<h2 class="title"><?php esc_html_e( 'Permissions', 'easy-affiliate-links' ); ?></h2>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label for="button_capability"><?php esc_html_e( 'Button Capability', 'easy-affiliate-links' ); ?></label>
				</th>
				<td>
					<input name="button_capability" type="text" id="button_capability" value="<?php echo esc_attr( EAFL_Settings::get( 'button_capability' ) ); ?>" class="regular-text">
					<p class="description" id="tagline-shortlink_slug">
						<?php esc_html_e( 'Only users with this role or capability can see the shortcode button in the editor.', 'easy-affiliate-links' ); ?>
					</p>
				</td>
			</tr>
		</tbody>
	</table>

	<h2 class="title"><?php esc_html_e( 'Custom Code', 'easy-affiliate-links' ); ?></h2>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label for="public_css"><?php esc_html_e( 'Public CSS', 'easy-affiliate-links' ); ?></label>
				</th>
				<td>
					<textarea name="public_css" rows="8" cols="50" id="public_css"class="large-text code"><?php echo esc_attr( EAFL_Settings::get( 'public_css' ) ); ?></textarea>
					<p class="description">
						<?php esc_html_e( 'Use your own custom CSS for styling affiliate links.', 'easy-affiliate-links' ); ?>
					</p>
				</td>
			</tr>
		</tbody>
	</table>
	<?php submit_button( __( 'Save Changes', 'easy-affiliate-links' ) ); ?>
</form>
