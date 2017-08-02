<?php
/**
 * Template for the general settings sub page.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/templates/admin/settings
 */

?>

<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
	<input type="hidden" name="action" value="eafl_settings_general">
	<?php wp_nonce_field( 'eafl_settings', 'eafl_settings', false ); ?>
	<h2 class="title"><?php esc_html_e( 'Shortlink Slug', 'easy-affiliate-links' ); ?></h2>
	<p>
		<?php esc_html_e( 'Make sure to flush your permalinks after changing this. Direct links to the old slug will be broken.', 'easy-affiliate-links' ); ?>
	</p>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label for="shortlink_slug"><?php esc_html_e( 'Shortlink Slug', 'easy-affiliate-links' ); ?></label>
				</th>
				<td>
					<input name="shortlink_slug" type="text" id="shortlink_slug" value="<?php echo esc_attr( EAFL_Settings::get( 'shortlink_slug' ) ); ?>" class="regular-text">
					<p class="description" id="tagline-shortlink_slug">
						<?php esc_html_e( 'For example:', 'easy-affiliate-links' ); ?> http://www.yourwebsite.com/the-shortlink-slug/product-a
					</p>
				</td>
			</tr>
		</tbody>
	</table>

	<h2 class="title"><?php esc_html_e( 'Defaults', 'easy-affiliate-links' ); ?></h2>
	<p>
		<?php esc_html_e( 'Default options to use for your affiliate links.', 'easy-affiliate-links' ); ?>
	</p>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label for="default_target"><?php esc_html_e( 'Default Target', 'easy-affiliate-links' ); ?></label>
				</th>
				<td>
					<select id="default_target" name="default_target">
						<?php
						$setting = EAFL_Settings::get( 'default_target' );
						$setting_options = array(
							'_self' => __( 'Open in same tab', 'easy-affiliate-links' ),
							'_blank' => __( 'Open in new tab', 'easy-affiliate-links' ),
						);

						foreach ( $setting_options as $setting_option_value => $setting_option_label ) {
							$selected = $setting === $setting_option_value ? ' selected="selected"' : '';
							echo '<option value="' . esc_attr( $setting_option_value ) . '"' . esc_attr( $selected ) . '>' . esc_html( $setting_option_label ) . '</option>';
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="default_redirect_type"><?php esc_html_e( 'Default Redirect Type', 'easy-affiliate-links' ); ?></label>
				</th>
				<td>
					<select id="default_redirect_type" name="default_redirect_type">
						<?php
						$setting = EAFL_Settings::get( 'default_redirect_type' );
						$setting_options = array(
							301 => __( '301 Permanent', 'easy-affiliate-links' ),
							302 => __( '302 Temporary', 'easy-affiliate-links' ),
							307 => __( '307 Temporary', 'easy-affiliate-links' ),
						);

						foreach ( $setting_options as $setting_option_value => $setting_option_label ) {
							$selected = $setting === $setting_option_value ? ' selected="selected"' : '';
							echo '<option value="' . esc_attr( $setting_option_value ) . '"' . esc_attr( $selected ) . '>' . esc_html( $setting_option_label ) . '</option>';
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="default_nofollow"><?php esc_html_e( 'Default Use Nofollow', 'easy-affiliate-links' ); ?></label>
				</th>
				<td>
					<label for="default_nofollow">
						<?php $checked = 'nofollow' === EAFL_Settings::get( 'default_nofollow' ) ? ' checked="checked"' : ''; ?>
						<input name="default_nofollow" type="checkbox" id="default_nofollow"<?php echo esc_html( $checked ); ?> />
						<?php esc_html_e( 'Add the nofollow attribute to affiliate links', 'easy-affiliate-links' ); ?>
					</label>
				</td>
			</tr>
		</tbody>
	</table>

	<h2 class="title"><?php esc_html_e( 'Statistics', 'easy-affiliate-links' ); ?></h2>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label for="enable_clicks"><?php esc_html_e( 'Count Clicks', 'easy-affiliate-links' ); ?></label>
				</th>
				<td>
					<label for="enable_clicks">
						<?php $checked = EAFL_Settings::get( 'enable_clicks' ) ? ' checked="checked"' : ''; ?>
						<input name="enable_clicks" type="checkbox" id="enable_clicks"<?php echo esc_html( $checked ); ?> />
						<?php esc_html_e( 'Register all clicks on affiliate links', 'easy-affiliate-links' ); ?>
					</label>
				</td>
			</tr>
		</tbody>
	</table>

	<?php submit_button( __( 'Save Changes', 'easy-affiliate-links' ) ); ?>
</form>
