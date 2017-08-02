<?php
/**
 * Template for the license settings sub page.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.3.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/templates/admin/settings
 */

?>

<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
	<input type="hidden" name="action" value="eafl_settings_license">
	<?php wp_nonce_field( 'eafl_settings', 'eafl_settings', false ); ?>
	<h2 class="title"><?php esc_html_e( 'Premium Licenses', 'easy-affiliate-links' ); ?></h2>
	<p>
		<?php esc_html_e( 'Activate your license key to get notified of updates for the premium add-ons.', 'easy-affiliate-links' ); ?>
	</p>
	<table class="form-table">
		<tbody>
			<?php
			$products = EAFL_License::get_products();

			foreach ( $products as $id => $product ) :
			?>
			<tr>
				<th scope="row">
					<label for="license_<?php echo esc_attr( $id ); ?>"><?php echo esc_html( str_replace( 'Easy Affiliate Links - ', '', $product['name'] ) ); ?></label>
				</th>
				<td>
					<input name="license_<?php echo esc_attr( $id ); ?>" type="text" id="license_<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( EAFL_Settings::get( 'license_' . $id ) ); ?>" class="regular-text">
					<p class="description" id="tagline-license_<?php echo esc_attr( $id ); ?>">
						<?php
						if ( in_array( EAFL_Settings::get( 'license_' . $id . '_active' ), array( 'inactive', 'invalid' ) ) ) {
							echo '<span style="color: darkred;">' . esc_html__( 'Warning: the license is currently inactive.', 'easy-affiliate-links' ) . '</span>';
						} elseif ( in_array( EAFL_Settings::get( 'license_' . $id . '_active' ), array( 'active', 'valid' ) ) ) {
							esc_html_e( 'Your license key is currently active. Fill in a blank key to deactivate.', 'easy-affiliate-links' );
						}
						?>
					</p>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php submit_button( __( 'Save Changes', 'easy-affiliate-links' ) ); ?>
</form>
