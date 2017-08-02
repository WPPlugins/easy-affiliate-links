<?php
/**
 * Template for the Link Text tab in the modal.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/templates/admin/modal/tabs
 */

?>

<div class="eafl-link-form eafl-link-text-form">
	<div class="eafl-form-section">
		<div class="eafl-form-line">
			<div class="eafl-form-label">
				<label for="eafl-link-actual-text"><?php esc_html_e( 'Text', 'easy-affiliate-links' ); ?></label>
			</div>
			<div class="eafl-form-input">
				<input type="text" id="eafl-link-actual-text" />
			</div>
			<div class="eafl-form-description">
				<?php esc_html_e( 'Text to show for this specific link.', 'easy-affiliate-links' ); ?>
			</div>
		</div>
	</div>
</div>
